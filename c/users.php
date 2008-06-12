<?php

class Controller_Users extends Controller_Application
{

	public $models = array('User');

	function beforeCall()
	{
		if ($this->action != 'login')
			$this->checkLogin();

		if (($this->action != 'login') && ($this->action != 'logout'))
			$this->requireAdmin();
	}

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

	function delete($args)
	{
		if (!is_numeric($args[0]))
			$this->redirect('/users/');

		if (isset($args[1]) && $args[1] == 'yes') {
			if ($this->User->delete($args[0]))
				$this->User->query('UPDATE `zones` SET `owner` = 1 WHERE `owner` = ?', array($args[0]));

			$this->redirect('/users/');
		} else {
			$this->template->user = $this->User->find($args[0], NULL, array('id', 'username'));
			
			// Redirect back if no zone where found
			if (empty($this->template->user))
				$this->redirect('/users/');
		}
	}

	function login()
	{
		if ($this->request == "POST") {
			if (!empty($_POST['username']) && !empty($_POST['password'])) {
				if ($this->User->login())
					$this->redirect('/');
				else
					$this->registry->template->error = "Wrong username and/or password. <br />\n";
			} else
				$this->template->error = "Missing username and/or password. <br />\n";
		}
	}

	function logout()
	{
		unset($_SESSION['username']);
		unset($_SESSION['admin']);

		$this->redirect('/users/login');
	}

	function password()
	{

	}
}

?>
