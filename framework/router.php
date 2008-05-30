<?php

/**
 * @package WMBIND 
 */

/**
 * Router 
 * 
 * @author Espen Volden <voldern@hoeggen.net> 
 * @package WMBIND 
 */
class Router
{
	/**
	 * $registry 
	 * 
	 * @var mixed
	 * @access private
	 */
	private $registry;

	/**
	 * $path 
	 * 
	 * @var mixed
	 * @access private
	 */
	private $path;

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
	 * setPath 
	 * 
	 * @param mixed $path 
	 * @access public
	 * @return void
	 */
	function setPath($path)
	{
		//$path = trim($path, '/\\');
		$path .= DIRSEP;

		if (is_dir($path) == false) {
			throw new Exception('Invalid controller path: `'. $path .'`');
		}

		$this->path = $path;
	}

	/**
	 * delegate 
	 * 
	 * @access public
	 * @return void
	 */
	function delegate() {
		// Analyse controller
		$this->getController($file, $controller, $action, $args);

		if (is_readable($file) == false) {
			die ('404 Not Found');
		}

		// Require the file
		require($file);

		// Require config files
		require('./include/config.php');

		// Initiate the class
		$class = 'Controller_' . $controller;
		$testController = $controller;
		$controller = new $class($this->registry);

		// Action available?
		if (is_callable(array($controller, $action)) == false) {
			die ('404 Not Found');
		}

		// Does the controller use a model?
		if ($controller->models != NULL && is_array($controller->models))
		{
			foreach ($controller->models as $model)	{
				$modelFile = site_path . 'm' . DIRSEP . strtolower($model) . '.php';
				if (file_exists($modelFile) == false)
					die ('404 Model not found');

				require($modelFile);
	
				$class = 'Model_' . $model;
				$foo = new $class($this->registry);
				$this->registry->{$model} = $foo;
			}
		}

		// Call the controller
		$controller->$action($args);


		// Checks if the user wants to display a regular or a smarty template
		// Then autoloads the view if it exists
		require 'head.php';

		if (file_exists(site_path . 'v' . DIRSEP . $testController . DIRSEP . $action . '.tpl')) {
			$template = $this->registry->template;
			$template->show($testController . DIRSEP . $action, true);
		} elseif (file_exists(site_path . 'v' . DIRSEP . $testController . DIRSEP . $action . '.php')) {
			$template = $this->registry->template;
			$template->show($testController . DIRSEP . $action);
		}

		require 'foot.php';
	}

	/**
	 * getController 
	 * 
	 * @param mixed $file 
	 * @param mixed $controller 
	 * @param mixed $action 
	 * @param mixed $args 
	 * @access private
	 * @return void
	 */
	private function getController(&$file, &$controller, &$action, &$args)
	{
		$route = (empty($_GET['route'])) ? '' : $_GET['route'];

		if (empty($route))
			$route = 'index';

		// Get seperate parts
		$route = trim($route, '/\\');
		$parts = explode('/', $route);

		// Find right controller
		$cmdPath = $this->path;
		foreach ($parts as $part) {
			$fullpath = $cmdPath . $part;

			// Is there a dir with this path?
			if (is_dir($fullpath)) {
				$cmdPath .= $part . DIRSEP;
				array_shift($parts);
			}

			// Find the file
			if (is_file($fullpath . '.php')) {
				$controller = $part;
				array_shift($parts);
				break;
			}
		}

		if (empty($controller))
			$controller = 'index';

		// Get action
		$action = array_shift($parts);
		if (empty($action))
			$action = 'index';

		$file = $cmdPath . $controller . '.php';
		$args = $parts;
	}
}

?>
