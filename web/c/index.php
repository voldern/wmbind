<?php

class Controller_Index extends Controller_Application
{
	public $models = array('User', 'Record');

	function beforeCall()
	{
		$this->checkLogin();
	}

	function index()
	{
		$user = $this->User->find($_SESSION['userid']);
		$this->template->user = $user['username'];

		if ($_SESSION['admin'])
			$zones = $this->User->query('SELECT COUNT(*) FROM `zones`', NULL, true);
		else
			$zones = $this->User->query('SELECT COUNT(*) FROM `zones` WHERE `owner` = ?', array($_SESSION['userid']), true);

		$this->template->zones = $zones[0]['COUNT(*)'];

		// Check if BIND is running
		system($this->registry->rndc . ' status > /dev/null', $exit);
		$this->template->bindStatus = $exit;

		// Get bad records
		$this->template->bad = $this->Record->badRecords();
	}
}

?>
