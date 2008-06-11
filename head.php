<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" >
<head>
	<title>SMBIND</title>
	<link rel="stylesheet" media="screen" type="text/css" href="<?php echo site_addr . "/include/html.css"; ?>" />
	</head><body>
	<h1>SMBIND</h1>
	<div id="menu">
		<ul>
		<?php if(isset($_SESSION['admin'])): ?>
			<li><a href="<?php echo site_addr; ?>/zones/">Zones</a></li>
			<li><a href="<?php echo site_addr; ?>/zones/commit">Commit changes</a></li>
		<?php if($_SESSION['admin']): ?>
			<li><a href="<?php echo site_addr; ?>/users/">Users</a></li>
			<li><a href="<?php echo site_addr; ?>/options/">Options</a></li>
		<?php endif; ?>
			<li><a href="<?php echo site_addr; ?>/users/logout">Log out</a></li>
		<?php else: ?>
			<li><a href="<?php echo site_addr; ?>/users/login">Login</a></li>
		<?php endif; ?>
		</ul>
	</div>
	<div id="content">
		<img src="http://localhost/smbind/images/logo1.png" width="100" height="100" alt="Image" />
