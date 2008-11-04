<?php
/**
 * @author Espen Volden <voldern@hoeggen.net>
 * @package WMBIND 
 */

/**
 * Require startup.php  
 */
require 'include/startup.php';

/**
 * Create the registry object
 */
$registry = new Registry;

/**
 * Connect to DB 
 */
try {
	$db = new PDO("{$registry->db_type}:host={$registry->db_host};dbname={$registry->db_db}", $registry->db_user, $registry->db_pass);
} catch (Exception $e) {
	die("Could not connect to mysql database.\n<br />Error: ". $e->getMessage()); 
}

$registry->db = $db;

/**
 *  Load template/view object
 */
$template = new Template($registry);
$registry->template = $template;
$registry->template->overwrite = true;

/**
 *  Load smarty object
 */
require($registry->smarty_path);
$smarty = new Smarty();
$smarty->template_dir = site_path . 'v';
$smarty->compile_dir = site_path . 'v' . DIRSEP . 'smarty' . DIRSEP . 'compile';
$smarty->cache_dir = site_path . 'v' . DIRSEP . 'smarty' . DIRSEP . 'cache';
$smarty->config_dir = site_path . 'v' . DIRSEP . 'smarty' . DIRSEP . 'configs';
$registry->smarty = $smarty;

/**
 *  Load router
 */
$router = new Router($registry);
$registry->router = $router;
$router->setPath(site_path . 'c');


/**
 *  Launch the page 
 */
$router->delegate();

?>
