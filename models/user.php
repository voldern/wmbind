<?php

class Model_User extends Model_Base
{
	public $table = 'users';

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
}

?>
