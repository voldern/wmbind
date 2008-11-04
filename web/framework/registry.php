<?php

/**
 * Registry 
 *
 * This registry holds data that will be shared between the controller, model and view
 *
 * @uses ArrayAccess
 * @author Espen Volden <voldern@hoeggen.net> 
 * @package WMBIND 
 */
class Registry
{
	/**
	 * $vars 
	 *
	 * List of variables that the registry holds
	 *
	 * @var array
	 * @access private
	 */
	private $vars = array();

	function __construct()
	{
		// Require config file
		require('./include/config.php');
	}

	/**
	 * set 
	 *
	 * Store a variable in the registry
	 *
	 * @param mixed $key 
	 * @param mixed $var 
	 * @access public
	 * @return bool
	 */
	function __set($key, $var)
	{
		if (isset($this->vars[$key]) == true) {
			throw new Exception('Unable to set var `' . $var . '`. Already set');
		}

		$this->vars[$key] = $var;
		return true;
	}

	/**
	 * get 
	 * 
	 * Gets data from the registry
	 *
	 * @param mixed $key 
	 * @access public
	 * @return mixed Variable with the $key name
	 */
	function __get($key)
	{
		if (isset($this->vars[$key]) == false) {
			return false;
		}

		return $this->vars[$key];
	}

	/**
	 * remove 
	 * 
	 * Removes data from the registry
	 *
	 * @param mixed $key 
	 * @access public
	 * @return bool
	 */
	function remove($key)
	{
		unset($this->vars[$key]);
		return true;
	}
}

?>
