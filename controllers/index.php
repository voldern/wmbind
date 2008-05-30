<?php

class Controller_Index extends Controller_Base
{
	public $name = 'Index';

	function index()
	{
		$this->template->firstName = 'Espen';
	}
}

?>
