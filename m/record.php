<?php

class Model_Record extends Model_Base
{
	public $table = 'records';	

	function saveEdit($zone)
	{
		if ($_POST['total'] != 0) {
			for ($i = 0; $i < $_POST['total']; $i++) {

				if (isset($_POST['delete'][$i]) && $_POST['delete'][$i] === 'true') {
					// Delete record
					if (!$this->delete($_POST['host_id'][$i]))
						return false;
				} else {
					// Update record
					if (($_POST['type'][$i] == "MX") && ($_POST['pri'][$i] == 0))
						$_POST['pri'][$i] = 5;
					elseif (empty($_POST['pri'][$i]))
						$_POST['pri'][$i] = 0;

					if (empty($_POST['host'][$i]))
						$_POST['host'][$i] = '@';	

					$data = array('id' => $_POST['host_id'][$i], 'host' => $_POST['host'][$i], 'type' => $_POST['type'][$i], 'pri' => $_POST['pri'][$i],
						'destination' => $_POST['destination'][$i], 'valid' => 'unknown');

					if (!$this->save($data)) {
						return false;
					}
				}
			}
		}

		// Add new record
		if (!empty($_POST['newdestination'])) {
			if (empty($_POST['newhost']))
				$_POST['newhost'] = '@';

			if ($_POST['newtype'] == 'MX')
				$pri = 5;
			else
				$pri = 0;

			$data = array('zone' => $zone, 'host' => $_POST['newhost'], 'type' => $_POST['newtype'], 'pri' => $pri,
				'destination' => $_POST['newdestination']);

			if (!$this->save($data))
				return false;
		}

		return true;
	}

	// Get all the bad records for the user with the id $userId
	function bad_records($userId) {
		// TODO
		// Give admin full right to see all zones
		$zones = $this->query("SELECT zones.id, zones.name FROM `zones` JOIN `records` ON records.zone = zones.id 
			WHERE records.valid != 'yes' AND zones.owner = ?", array($userId), true);

		return $zones;
	}
}

?>
