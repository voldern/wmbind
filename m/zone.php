<?php

class Model_Zone extends Model_Base
{
	public $table = 'zones';	

	function validate($save = true)
	{
		$this->registry->template->error = NULL;

		if (($save && isset($_POST['name']) && empty($_POST['name'])) || empty($_POST['refresh']) || empty($_POST['retry']) || empty($_POST['expire']) 
			|| empty($_POST['ttl']) || empty($_POST['pri_dns']) || empty($_POST['sec_dns']) || empty($_POST['owner']))
				$this->registry->template->error .= "Missing some required fields <br />\n";

		if ($save && !$this->unique('`name` LIKE ?', array($_POST['name'])))
			$this->registry->template->error .= "This zone name is allready in use <br />\n";
		
		if (!is_numeric($_POST['refresh']) || !is_numeric($_POST['retry']) || !is_numeric($_POST['expire']) || !is_numeric($_POST['ttl']))
			$this->registry->template->error .= "Refresh, Retry, Expire etc can only consist of numbers <br />\n";
		
		// Validate ip adresses
		$ipRegex = "^([1-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])(\.([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3}$";
		if (!empty($_POST['www']) && !ereg($ipRegex, $_POST['www']))
			$this->registry->template->error .= "WWW is not a valid ip address <br />\n";

		if (!empty($_POST['mail']) && !ereg($ipRegex, $_POST['mail']))
			$this->registry->template->error .= "Mail is a valid ip address <br />\n";

		if (empty($this->registry->template->error) == false)
			return false;

		return true;
	}

	function saveReg()
	{
		if (!$this->save(array('name' => $_POST['name'], 'refresh' => $_POST['refresh'], 'retry' => $_POST['retry'], 
			'expire' => $_POST['expire'], 'pri_dns' => $_POST['pri_dns'], 'sec_dns' => $_POST['sec_dns'], 'owner' => $_POST['owner'], 
			'serial' => date("Ymd") . '00'))) {
				return false;
			} else {
				$id = $this->find("`name` = ?", array($_POST['name']), array('id'));
				$id = $id['id'];
				// Try to save WWW record
				if (!empty($_POST['www'])) {
					if (!$this->save(array('zone' => $id, 'host' => '@', 'type' => 'A', 'pri' => 0, 'destination' => $_POST['www']), 'records')) {
						// Delete zone record because of error
						$this->delete($id);
						return false;
					} else {
						if (!$this->save(array('zone' => $id, 'host' => 'www', 'type' => 'A', 'pri' => 0, 'destination' => $_POST['www']), 'records')) {
							$this->delete($id);
							$this->delete("`zone` = ?", array($id), 'records');
							return false;
						}
					}
				}

				// Try to save mail record
				if (!empty($_POST['mail'])) {
					if (!$this->save(array('zone' => $id, 'host' => 'mail', 'type' => 'A', 'pri' => 0, 'destination' => $_POST['mail']), 'records')) {
						// Rollback 
						$this->delete($id);
						return false;
					} else {
						if (!$this->save(array('zone' => $id, 'host' => '@', 'type' => 'MX', 'pri' => 5, 'destination' => $_POST['mail']), 'records')) {
							// Rollback
							$this->delete($id);
							$this->delete("`zone` = ?", array($id), 'records');
							return false;
						}
					}
				}
			}
		return true;
	}

	function update($zoneId)
	{
		// Update serial
		if (!$oldSerial = $this->find($zoneId, NULL, array('serial'))) {
			echo 'Old Serial error';
			return false;
		}
			
		$serial = date("Ymd") . substr($oldSerial['serial'] + 1, -2);

		if ($serial < $oldSerial)
			$serial = $oldSerial['serial'] + 1;

		$data = array('id' => $zoneId, 'updated' => 'yes', 'refresh' => $_POST['refresh'], 'retry' => $_POST['retry'], 'expire' => $_POST['expire'], 
			'ttl' => $_POST['ttl'], 'pri_dns' => $_POST['pri_dns'], 'sec_dns' => $_POST['sec_dns'], 'owner' => $_POST['owner'],
			'serial' => $serial);
		
		if(!$this->save($data))
			return false;

		return true;
	}

}

?>
