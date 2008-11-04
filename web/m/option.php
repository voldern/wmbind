<?php

class Model_Option extends Model_Base
{
	public $table = 'options';	

	function validate() {
			$this->registry->template->error = NULL;
			// Validate allow-transfer
			if (!empty($_POST['transfer'])) {
				$_POST['transfer'] = trim($_POST['transfer']);

				// Check if only valid characters were used
				$transferRegex = '/^(([1-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])(\.([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3}\; ?)+$/';

				if (preg_match($transferRegex, $_POST['transfer']) == 0) {
					$this->registry->template->error .= "Allow-transfer was not using the right syntax <br />\n";
				}
			}

			if (!empty($this->registry->template->error))
				return false;
			
			return true;
	}
}

?>
