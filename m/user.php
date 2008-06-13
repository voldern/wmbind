<?php

class Model_User extends Model_Base
{
	public $table = 'users';

	function hash($pass)
	{
		return sha1($this->registry->passwordHash . $pass);
	}

	public function validateReg()
	{
		$this->registry->template->error = NULL; 

		if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['password2']) || empty($_POST['admin']))
			$this->registry->template->error .= "Missing username or/and password <br />\n";
		
		if ($this->unique('`username` LIKE ?', array($_POST['username'])) == false)
			$this->registry->template->error .= "The username is already in use <br />\n";
		
		if ($_POST['password'] != $_POST['password2'])
			$this->registry->template->error .= "The passwords didn't match <br />\n";

		if (empty($this->registry->template->error) == false)
			return false;

		return true;
	}

	public function validateEdit($id)
	{
		$this->registry->template->error = NULL;

		if (empty($id) || !is_numeric($id))
			$this->registry->template->error .= "Wrong user id, please try again.";

		if (empty($_POST['admin']))
			$this->registry->template->error .= "Missing admin status<br />\n";

		if ($_POST['password'] != $_POST['password2'])
			$this->registry->template->error .= "The passwords didn't match <br />\n";

		if (empty($this->registry->template->error) == false)
			return false;

		if (!empty($_POST['password']) && !empty($_POST['password2']))
			$this->data = array('id' => $id, 'password' => $this->hash($_POST['password']), 'admin' => $_POST['admin']);
		else
			$this->data	= array('id' => $id, 'admin' => $_POST['admin']);

		return true;
	}

	public function options()
	{
		$users = $this->findAll('1 = 1', NULL, array('id', 'username'));
		
		foreach ($users as $user) {
			$foo[$user['id']] = $user['username'];
		}

		return $foo;
	}

	public function login()
	{
		$password = $this->hash($_POST['password']);
		$user = $this->find('`username` = ? AND `password` = ?', array($_POST['username'], $password), array('id', 'admin'));

		if (!empty($user)) {
			$_SESSION['userid'] = $user['id'];

			if ($user['admin'] == 'yes')
				$_SESSION['admin'] = true;
			else
				$_SESSION['admin'] = false;

			return true;
		}

		return false;
	}

	public function validatePass()
	{
		$error = NULL;

		if (empty($_POST['current']) || empty($_POST['password']) || empty($_POST['password2']))
			$error .= "Missing some required fields. <br />\n";

		if ($_POST['password'] != $_POST['password2'])
			$error .= "The two new passwords are not identical. <br />\n";

		// Check if the password is right
		$user = $this->find('`id` = ? AND `password` = ?', array($_SESSION['userid'], $this->hash($_POST['current'])), array('id')); 
		if (empty($user))
			$error .= "The current password is not right. <br />\n";

		$this->registry->template->error = $error;

		if(empty($error))
			return true;

		return false;
	}
}

?>
