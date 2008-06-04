<?php

class Controller_Options extends Controller_Base
{

	public $models = array('Option');

	function index()
	{
		$records = $this->Option->findAll("`preftype` = 'record'", NULL, array('prefkey', 'prefval'), '`prefkey`');
		for ($x = 0, $y = 0, $i = 0; $i < count($records); $y++, $i++) {
			if ($y == 5) {
				$x++;
				$y = 0;
			}
			$recordsArray[$x][$y] = $records[$i];
		}

		$options = $this->Option->findAll("`preftype` = 'normal'", NULL, array('prefkey', 'prefval'), '`prefkey`');
		foreach ($options as $option)
			$optionsArray[$option['prefkey']] = $option['prefval'];

		$this->template->options = $optionsArray;
		$this->template->records = $recordsArray; 
	}

	function edit()
	{
		if ($this->request == "POST") {
			// Reset all options
			$this->Option->query("UPDATE `options` SET `prefval` = 'off' WHERE `preftype` = 'record'");

			foreach ($_POST as $key => $value) {
				if (!$this->Option->query("UPDATE `options` SET `prefval` = '{$value}' WHERE `prefkey` = '{$key}'"))
					$this->redirect('/options/');
			}

			$this->redirect('/options/');
		}
	}
}


?>
