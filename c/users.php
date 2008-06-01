<?php

class Controller_Users extends Controller_Base
{

	public $models = array('User');

	function index()
	{
		$this->template->users = $this->User->findAll('1 = 1');
	}

	function register()
	{
		if ($this->request == 'POST') {
			// Validating POST data
			if ($this->User->validateReg()) {	
				// Trying to save the user to the database
				if ($this->User->save(array('username' => $_POST['username'], 'password' => $this->User->hash($_POST['password']),
					'admin' => $_POST['admin']))) {
					$this->redirect('/users/');
				}
			} else {
			}
		}
	}

	function edit($args)
	{
		if ($this->request == 'POST') {
			// Validation POST data
			if($this->User->validateEdit($args[0])) {
				// Trying to update the user in the database
				if ($this->User->save($this->User->data)) {
					// Flash here
					$this->redirect('/users/');
				} else {
					$this->template->error .= "Could not update, please try again later.";
				}
			}
		}
		
		if (!empty($args[0]) && is_numeric($args[0])) {
			// Get the user information to fill the fields with
			$user = $this->User->find((int)$args[0]);
			if (!empty($user)) {
				$this->template->user = $user;
			} else {
				// Flash here
				$this->redirect('/users/');
			}
		} else {
			// Flash here
			$this->redirect('/users/');
		}	
	}
}

?>
