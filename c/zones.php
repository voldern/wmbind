<?php

class Controller_Zones extends Controller_Base
{
	public $models = array('Zone', 'User', 'Record', 'Option');
	
	function index()
	{
		$this->template->zones = $this->Zone->findAll('1 = 1');		
	}

	function register()
	{
		// Get users from the database and format it so that it can be used inside the owner <select></select>
		$this->template->users = $this->User->options();

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

		if (isset($args[1]) && $args[1] == 'yes') {
			// Delete zone and all records
			$this->Record->delete('`zone` = ?', array($args[0]));
				
			if ($this->Zone->delete($args[0]))
				$this->Zone->query("UPDATE `zones` SET `updated` = 'yes' LIMIT 1");
			
			// Redirect back
			$this->redirect('/zones/');

		} else {
			$this->template->zone = $this->Zone->find($args[0], NULL, array('id', 'name'));
			
			// Redirect back if no zone where found
			if (empty($this->template->zone))
				$this->redirect('/zones/');
		}
	}

	function commit()
	{
		$zones = $this->Zone->findAll("`updated` = 'yes'");
		if (empty($zones))
			$this->redirect('/');

		foreach ($zones as $zone) {
			$records = $this->Record->findAll("`zone` = ? AND `valid` != 'no'", array($zone['id']), NULL, 'host, type, pri, destination');
			$out = <<<EOF
\$TTL	{$zone['ttl']}
@		IN		SOA		{$zone['pri_dns']}
EOF;

			$fd = fopen($this->registry->zones_path . preg_replace('/\//', '-', $zone['name']), 'w')
				or die('Cannot open: ' . $this->registry->zones_path . preg_replace('/\//', '-', $zone['name']));

			fwrite($fd, $out);
			fclose($fd);
		}
	}
}

?>
