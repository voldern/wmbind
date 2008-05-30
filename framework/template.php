<?php

/**
 * @package MVC 
 */

/**
 * Template 
 * 
 * @author Espen Volden <voldern@hoeggen.net> 
 * @package MVC
 */
class Template
{
	/**
	 * $registry 
	 * 
	 * @var mixed
	 * @access private
	 */
	private $registry;

	/**
	 * $vars 
	 * 
	 * @var array
	 * @access private
	 */
	private $vars = array();

	public $overwrite;

	/**
	 * __construct 
	 * 
	 * @param mixed $registry 
	 * @access protected
	 * @return void
	 */
	function __construct($registry)
	{
		$this->registry = $registry;
	}

	/**
	 * set 
	 * 
	 * @param mixed $varname 
	 * @param mixed $value 
	 * @param mixed $overwrite 
	 * @access public
	 * @return void
	 */
	public function __set($varname, $value)
	{
		if (isset($this->vars[$varname]) == true && $this->overwrite == false) {
			throw new Exception('Unable to set var `' . $varname . '`. Already set, and overwrite not allowed.');
			return false;
		}

		$this->vars[$varname] = $value;
		return true;
	}

	/**
	 * get 
	 * 
	 * Gets data from the template 
	 *
	 * @param mixed $key 
	 * @access public
	 * @return mixed Variable with the $key name
	 */
	public function __get($key)
	{
		if (isset($this->vars[$key]) == false) {
			return false;
		}

		return $this->vars[$key];
	}

	public function __isset($key)
	{
		return isset($this->vars[$key]);
	}

	public function __empty($key)
	{
		return empty($this->vars[$key]);
	}


	/**
	 * remove 
	 * 
	 * @param mixed $varname 
	 * @access public
	 * @return void
	 */
	function remove($varname)
	{
		unset($this->vars[$varname]);
		return true;
	}

	/**
	 * show 
	 * 
	 * @param mixed $name 
	 * @param bool $smarty [OPTIONAL]
	 * @access public
	 * @return void
	 */
	function show($name, $smarty = false)
	{
		if ($smarty) {
			$path = site_path . 'v' . DIRSEP . $name . '.tpl';
			$smarty = $this->registry->smarty;
		} else {
			$path = site_path . 'v' . DIRSEP . $name . '.php';
		}

		if (file_exists($path) == false) {
			throw new Exception('Template `' . $name . '` does not exist');
			return false;
		}

		foreach ($this->vars as $key => $value) {
			if ($smarty)
				$smarty->assign($key, $value);
			else
				$$key = $value;
		}
		
		if ($smarty)
			$smarty->display($name . '.tpl');
		else
			include($path);
	}
}

?>
