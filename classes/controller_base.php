<?php

/**
 * @package MVC
 */


/**
 * Controller_Base 
 * 
 * @abstract
 * @package MVC 
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
	function __construct($registry)
	{
		$this->registry = $registry;
		$this->template = $this->registry->template;
		$this->request = $_SERVER['REQUEST_METHOD'];
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
	 * index 
	 * 
	 * @abstract
	 * @access public
	 * @return void
	 */
	abstract function index();

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
	}
}

?>
