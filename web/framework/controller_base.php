<?php

/**
 * @package WMBIND 
 */


/**
 * Controller_Base 
 * 
 * @abstract
 * @package WMBIND 
 * @author Espen Volden <voldern@hoeggen.net> 
 */
abstract class Controller_Base
{
	
	/**
	 * $registry 
	 * 
	 * Store the registry object
	 *
	 * @var mixed
	 * @access protected
	 */
	protected $registry;

	/**
	 * $name 
	 *
	 * The name of the controller
	 *
	 * @var mixed
	 * @access public
	 */
	public $name = NULL;

	public $template;

	public $model = NULL;

	protected $action;

	/**
	 * $request 
	 *
	 * The request method
	 * 
	 * @var mixed
	 * @access public
	 */
	public $request = NULL;

	/**
	 * __construct 
	 * 
	 * @param mixed $registry 
	 * @access protected
	 * @return void
	 */
	function __construct($registry, $action)
	{
		$this->registry = $registry;
		$this->template = $this->registry->template;
		$this->request = $_SERVER['REQUEST_METHOD'];
		$this->action = $action;
	}

	/**
	 * __get 
	 *
	 * Gives the controller direct access to the model
	 *
	 * @param mixed $name 
	 * @access protected
	 * @return mixed 
	 */
	function __get($name)
	{
		if (!empty($this->models) && is_array($this->models) && in_array($name, $this->models))
		{
			return $this->registry->{$name};
		}

		return false;
	}

	/**
	 * redirect 
	 * 
	 * Redirects user to a local or external path
	 *
	 * @param mixed $path 
	 * @param bool $extern 
	 * @access public
	 * @return void
	 */
	function redirect($path, $extern = false) {
		if ($extern)
			header('Location: ' . site_addr);
		else
			header('Location: ' . site_addr . $path);		
		
		exit();
	}
}

?>
