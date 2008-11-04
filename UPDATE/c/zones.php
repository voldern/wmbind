<?php

class Controller_Zones extends Controller_Application
{
	public $models = array('Zone', 'User', 'Record', 'Option');

	function beforeCall()
	{
		$this->checkLogin();

		if ($this->action == 'register')
			$this->requireAdmin();
	}

	function index()
	{
		if ($_SESSION['admin'])
			$this->template->zones = $this->Zone->findAll('1 = 1');
		else
			$this->template->zones = $this->Zone->findAll('`owner` = ?', array($_SESSION['userid'])); 
	}

	function register()
	{
		// Get users from the database and format it so that it can be used inside the owner <select></select>
		$this->template->users = $this->User->options();
		
		// Get the default primary and secondary NS
		$dns = $this->Option->findAll("`prefkey` = 'prins' OR `prefkey` = 'secns' OR `prefkey` = 'transfer'", 
			NULL, array('prefval'), '1 DESC');

		$this->template->prins = $dns[0]['prefval'];
		$this->template->secns = $dns[1]['prefval'];
		$this->template->transfer = $dns[2]['prefval'];

		if ($this->request == 'POST')
		{
			// Validates the input
			if ($this->Zone->validate()) {
				if ($this->Zone->saveReg())
					$this->redirect('/zones/');
			} else {
			}
		}

	}

	function edit($args)
	{
		if (empty($args[0]) || !is_numeric($args[0]))
			$this->redirect('/zones/');

		// Check if user has permission to edit the zone
		if (!$_SESSION['admin'] && $this->template->zone['owner'] != $_SESSION['userid'])
			$this->redirect('/zones/');

		// Save any changes
		if ($this->request == "POST") {
			// Validates the zone related input
			if ($this->Zone->validate(false)) {
				if ($this->Zone->update($args[0])) {
					if (!$this->Record->saveEdit($args[0]))
						$this->template->error = "Could not update/save the records, please try again. <br />\n";
				} else
					$this->template->error = "Could not update/save the zone, please try again. <br />\n";
					
			}	
		}

		// Get data to populate the view with
		$this->template->zone = $this->Zone->find($args[0]);
		if (empty($this->template->zone))
			$this->redirect('/zones/');

		$this->template->records = $this->Record->findAll("`zone` = ?", array($args[0]), NULL, '`host`, `type`, `pri`, `destination`');

		// Create a list of available type options
		$types = $this->Option->findAll('`preftype` = ? AND `prefval` = ?', array('record', 'on'), array('prefkey'));
		foreach ($types as $type)
			$options[$type['prefkey']] = $type['prefkey'];

		$this->template->options = $options;
		$this->template->users = $this->User->options();
	}

	function delete($args)
	{
		if (!is_numeric($args[0]))
			$this->redirect('/zones/');

		// Get zone
		$this->template->zone = $this->Zone->find($args[0], NULL, array('id', 'name', 'owner'));

		// Redirect back if no zone where found
		if (empty($this->template->zone))
			$this->redirect('/zones/');

		// Checks if user has permission to delete the zone
		if (!$_SESSION['admin'] && $this->template->zone['owner'] != $_SESSION['userid'])
			$this->redirect('/zones');

		if (isset($args[1]) && $args[1] == 'yes') {
			// Delete zone and all records
			$this->Record->delete('`zone` = ?', array($args[0]));

			// TODO
			// Remove dirty hack	
			if ($this->Zone->delete($args[0]))
				$this->Zone->query("UPDATE `zones` SET `updated` = 'yes' LIMIT 1");
			
			// Redirect back
			$this->redirect('/zones/');

		}
	}

	function commit()
	{
		$hostmaster = $this->Option->find('`prefkey` = ?', array('hostmaster'), array('prefval'));
		$hostmaster = $hostmaster['prefval'];
		$badZones = array();

		$zones = $this->Zone->findAll("`updated` = 'yes'");
		/*if (empty($zones))
			$this->redirect('/');*/

		foreach ($zones as $zone) {
			$records = $this->Record->findAll("`zone` = ? AND `valid` != 'no'", array($zone['id']), NULL, 'host, type, pri, destination');
			// Add options
			$out = <<<EOF
\$TTL	{$zone['ttl']}
@		IN		SOA		{$zone['pri_dns']}. $hostmaster. (
			{$zone['serial']} \t; Serial
			{$zone['refresh']} \t\t; Refresh
			{$zone['retry']} \t\t; Retry
			{$zone['expire']} \t; Expire
			{$zone['ttl']}) \t; Negative Cache TTL
;

EOF;
			// Add the primary and secondary DNS
			if (!empty($zone['pri_dns']))
				$out .= "@		IN		NS		" . $zone['pri_dns'] . ".\n";
			if (!empty($zone['sec_dns']))
				$out .= "@		IN		NS		" . $zone['sec_dns'] . ".\n";

			// Write the zone file
			$fd = fopen($this->registry->zones_path . preg_replace('/\//', '-', $zone['name']), 'w')
				or die('Cannot open: ' . $this->registry->zones_path . preg_replace('/\//', '-', $zone['name']));

			fwrite($fd, $out);
			fclose($fd);

			// Check if the zone file is valid
			$cmd = $this->registry->namedcheckzone . " " . $zone['name'] . " " . 
				$this->registry->zones_path . preg_replace('/\//', '-', $zone['name']) . ' > /dev/null';
			system($cmd, $exit);

			if ($exit == 0) {
				if (!$this->Zone->save(array('id' => $zone['id'], 'updated' => 'no', 'valid' => 'yes')))
					die('Could not update zone');

				$rebuild = true;
			} else {
				if (!$this->Zone->save(array('id' => $zone['id'], 'updated' => 'yes', 'valid' => 'no'))) 
					die('Could not update zone');

				// Add zone to list of broken zones if the user has permission to edit that zone
				if ($_SESSION['admin'] || $zone['owner'] == $_SESSION['userid'])
					$badZones[] = array('id' => $zone['id'], 'name' => $zone['name']);
			}	
			
			// Get records associated with zone
			$records = $this->Record->findAll("`zone` = ? AND `valid` != 'no'", array($zone['id']), NULL, 'host, type, pri, destination');
			if (empty($badZones)) {
				foreach ($records as $record) {
					// Only add priority if the record is of type 'MX'
					if ($record['type'] == 'MX')
						$pri = $record['pri'];
					else
						$pri = '';

					// Get the right destination depending on record type
					if (($record['type'] == 'NS' || $record['type'] == 'PTR' || $record['type'] == 'CNAME' || $record['type'] == 'MX'
						|| $record['type'] == 'SRV') &&	$record['destination'] != '@') {
							$destination = $record['destination'] . ".";
					} elseif ($record['type'] == 'TXT')
						$destination = '"' . $record['destination'] . '"';
					else
						$destination = $record['destination'];

					$out = $record['host'] . "\tIN\t" . $record['type'] . "\t" . $pri . "\t" . $destination . "\n";
					
					// Write record to end of file
					$fd = fopen($this->registry->zones_path . preg_replace('/\//', '-', $zone['name']), 'a')
						or die('Cannot open: ' . $this->registry->zones_path . preg_replace('/\//', '-', $zone['name']));
					fwrite($fd, $out);
					fclose($fd);

					// Validate the new record
					$cmd = $this->registry->namedcheckzone . " " . $zone['name'] . " " . 
						$this->registry->zones_path . preg_replace('/\//', '-', $zone['name']) . ' > /dev/null';
					system($cmd, $exit);
					
					if ($exit == 0) {
						if (!$this->Record->save(array('id' => $record['id'], 'valid' => 'yes'))) 
							die('Could not update zone');
					} else {
						// Remove the last record from the zone file that caused the error
						$fd = fopen($this->registry->zones_path . preg_replace('/\//', '-', $zone['name']), 'r+') 
							or die('Cannot open: ' . $this->registry->zones_path . preg_replace('/\//', '-', $zone['name']));

						for ($i = 0; fgets($fd); $i++)
							$addr[$i] = ftell($fd);

						ftruncate($fd, $addr[$i - 2]);
						fclose($fd);

						if (!$this->Record->save(array('id' => $record['id'], 'valid' => 'no'))) 
							die('Could not update zone');
					}	
				}
			}
		}

		// Create a new config file
		if (isset($rebuild) && $rebuild == true) {
			if (!$zones = $this->Zone->findAll('1 = 1', NULL, array('name', 'transfer'), 'name'))
				die('Could not find any zones');

			$cout = '';
			foreach ($zones as $zone) {
				if (!empty($zone['transfer']))
					$transfer = 'allow-transfer { ' . $zone['transfer'] . ' };';
				else
					$transfer = '';

				$cout .= 'zone "' . $zone['name'] . '" {
					type master;
					file "' . $this->registry->zones_path . preg_replace('/\//', '-', $zone['name']) . "\";
					$transfer
				};\n\n";
			}

			$fd = fopen($this->registry->conf_path, 'w')
				or die('Cannot open: ' . $this->registry->conf_path);
			fwrite($fd, $cout);
			fclose($fd);

			// Check if conf file is valid
			$cmd = $this->registry->namedcheckconf . ' ' . $this->registry->conf_path . ' > /dev/null';
			system($cmd, $exit);
			if ($exit != 0)
				die ($this->registry->namedcheckconf . ' exit status ' . $exit);

			// Reload bind
			$cmd = $this->registry->rndc . ' reload > /dev/null';
			system($cmd, $exit);
			if ($exit != 0)
				die($this->registry->rndc . ' exit status ' . $exit);
		}

		// Assing view variables
		if (!empty($badZones))
			$this->template->badZones = $badZones;

		$this->template->badRecords = $this->Record->badRecords(1);
	}
}

?>
