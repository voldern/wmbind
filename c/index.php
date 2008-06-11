<?php

class Controller_Index extends Controller_Application
{
	function beforeCall()
	{
		$this->checkLogin();
	}

	function index()
	{
	}
}

?>
