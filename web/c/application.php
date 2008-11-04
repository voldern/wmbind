<?php

abstract class Controller_Application extends Controller_Base
{
	public $models = array();
	abstract function index();

	function checkLogin()
	{
		if (!isset($_SESSION['userid']) || !isset($_SESSION['admin'])) {
			unset($_SESSION['userid']);
			unset($_SESSION['admin']);
			$this->redirect('/users/login');
		}
	}

	function requireAdmin()
	{
		if (!$_SESSION['admin'])
			$this->redirect('/');
	}
}


?>
