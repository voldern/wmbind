<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" >
<head>
	<title>WMBIND</title>
	<link rel="stylesheet" media="screen" type="text/css" href="<?php echo site_addr . "/html.css"; ?>" />
	</head><body>
	<a href="<?php echo site_addr; ?>"><h1>WMBIND</h1></a>
	<div id="menu">
<?php
// Check if any zones need to be commited
// If so change the text of "Commit changes" to red
$sth = $this->registry->db->prepare("SELECT `id` FROM `zones` WHERE `updated` = 'yes'");
$sth->execute();
if ($sth->rowCount() > 0)
	$text = '<font color="red">Commit changes</font>';
else
	$text = 'Commit changes';
?>
		<ul>
		<?php if(isset($_SESSION['admin'])): ?>
			<li><a href="<?php echo site_addr; ?>/zones/">Zones</a></li>
			<li><a href="<?php echo site_addr; ?>/zones/commit"><?php echo $text; ?></a></li>
		<?php if($_SESSION['admin']): ?>
			<li><a href="<?php echo site_addr; ?>/users/">Users</a></li>
			<li><a href="<?php echo site_addr; ?>/options/">Options</a></li>
		<?php endif; ?>
			<li><a href="<?php echo site_addr; ?>/users/password">Change password</a></li>
			<li><a href="<?php echo site_addr; ?>/users/logout">Log out</a></li>
		<?php else: ?>
			<li><a href="<?php echo site_addr; ?>/users/login">Login</a></li>
		<?php endif; ?>
		</ul>
	</div>
	<div id="content">
		<!-- <img src="" width="100" height="100" alt="Image" /> -->
