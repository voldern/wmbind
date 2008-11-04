<html>
<head>
<title>WMBIND Install</title>
<style type="text/css">
<!--
input { margin-bottom: 5px; }
-->
</style>
</head>
<body>
<h1>WMBIND Install</h1>
<p>This script will guide you trough the configuration of WMBIND (Web Management for BIND). If you haven't read README yet, please do it first.</p>
<?php
fopen('./include/config.php', 'w')
	or die('<strong style="color:red">Could not open ./include/config.php, please make sure it has a chmod of 777.</strong>');

if ($_POST['submit'] == 'Save') {
	if (!empty($_POST['mysql']['host']) && !empty($_POST['mysql']['username']) && !empty($_POST['mysql']['db']) && !empty($_POST['smarty']) && 
		!empty($_POST['zones']) && !empty($_POST['conf']) && !empty($_POST['namedcheckconf']) && !empty($_POST['namedcheckzone']) && 
		!empty($_POST['rndc'])) {
			
			if ($_POST['compability'] != true)
				$_POST['compability'] = 'false';

			echo 'Testing smarty path...';
			fopen($_POST['smarty'], 'r')
				or die('Could not read Smarty. Check that ' . $_POST['smarty'] . ' exists.');
			echo 'OK <br />';

			echo 'Testing permission of ./v/smarty/compile...';
			fopen('./v/smarty/compile/test', 'w')
				or die('Could not write to ./v/smarty/compile. Please make shure that the folder has a chmod of 777.');
			echo 'OK <br />';

			echo 'Testing zones path...';
			fopen($_POST['zones'] . 'test', 'w')
				or die('Could not write to ' . $_POST['zones']);
			echo 'OK <br />';

			echo 'Testing conf directory...';
			file_exists(dirname($_POST['conf']))
				or die('The ' . dirname($_POST['conf']) . ' directory does not exists.');
			echo 'Ok <br />';

			echo 'Testing conf file...';
			fopen($_POST['conf'], 'a')
				or die('Could not write to ' . $_POST['conf']);
			echo 'OK <br />';

			echo 'Testing named-checkconf...';
			file_exists($_POST['namedcheckconf'])
				or die('Could not find ' . $_POST['namedcheckconf'] . ". Please make sure that it's installed.");
			is_executable($_POST['namedcheckconf'])
				or die('Could not execute ' . $_POST['namedcheckconf']);
			echo 'OK <br />';

			echo 'Testing named-checkzone...';
			file_exists($_POST['namedcheckzone'])
				or die('Could not find ' . $_POST['namedcheckzone'] . ". Please make sure that it's installed.");
			is_executable($_POST['namedcheckzone'])
				or die('Could not execute ' . $_POST['namedcheckzone']);		
			echo 'OK <br />';

			echo 'Testing rndc...';
			file_exists($_POST['rndc'])
				or die('Could not find ' . $_POST['rndc'] . ". Please make sure that it's installed.");
			if (!exec($_POST['rndc'] . ' status')) {
				$out = exec($_CONF['rndc'] . ' status 2>&1');
				die('Could not run rndc as ' . exec('id -un') . '. ' .
					'Please make sure that ' . exec('id -un') . ' is a member of the group that runs named, ' .
					'and that all rndc config files and keys are readable by ' . exec ('id -un') . '.<br /> <br />' .
					"Output was: $out");
			}
			echo 'OK <br />';

			echo 'Testing connection to localhost:953...';
			fsockopen('localhost', 953, $errorno, $errorstr, 5)
				or die("Could not connect to localhost:953. $errorstr($errorno) <br />
				Either named isn't running or rndc is configured on an alternate port.");
			echo 'OK <br />';

			echo 'Testing database connection...';
			try {
				$dbh = new PDO('mysql:host=' . $_POST['mysql']['host'] . ';dbname=' . $_POST['mysql']['db'], 
					$_POST['mysql']['username'], $_POST['mysql']['password']);
			} catch (PDOException $e) {
				die('Connection failed: ' . $e->getMessage());
			}
			echo 'OK <br />';

			if ($_POST['compability'] != 'true') {
				echo "Adding user with username 'admin' and password 'admin' to the database...";
				$password = sha1('@3kDKAS9123:.213if;2,31ujasd1__123admin');
				$dbh->query("INSERT INTO `users` (`id`, `username`, `password`, `admin`) VALUES (1, 'admin', '$password', 'yes')");
				echo 'Ok <br />';
			}

			$conf = <<<EOF
<?php
// Hash to hash the user passwords with
// WARNING: Changing this during production will make all current accounts invalid
\$this->passwordHash = '@3kDKAS9123:.213if;2,31ujasd1__123';

// Should WMBIND be backwards compatible with SMBIND? (WARNING: This will lead to weaker security and the hash above will be ignored)
\$this->compability = {$_POST['compability']};

// Database config
\$this->db_type = 'mysql'; // mysql for MySQL, pgsql for PostgreSQL
\$this->db_user = '{$_POST['mysql']['username']}'; // Database username
\$this->db_pass = '{$_POST['mysql']['password']}'; // Database password
\$this->db_host = '{$_POST['mysql']['host']}'; // Database host
\$this->db_db = '{$_POST['mysql']['db']}'; // Database name

// Full path to Smarty.class.php
\$this->smarty_path = '{$_POST['smarty']}';

// Zone data paths
\$this->zones_path = '{$_POST['zones']}'; // Path to where to store zone files
\$this->conf_path = '{$_POST['conf']}'; // Include this file in named.conf.

// BIND utilities.
\$this->namedcheckconf = '{$_POST['namedcheckconf']}';
\$this->namedcheckzone = '{$_POST['namedcheckzone']}';
\$this->rndc = '{$_POST['rndc']}';
?>
EOF;

			echo '<u>Writing config file...</u>';
			$fd = fopen('./include/config.php', 'w');
			fwrite($fd, $conf);
			fclose($fd);
			echo 'OK <br />';

			echo '<br /><strong>Congratulations, your setup looks good.</strong> <br />';
			echo '<u>Make sure to remove install.php and to chmod include/config.php to 640!</u> <br /> <br />';

			echo '<strong>Please remember to add the following line to your named.conf:</strong>';
			echo '<br /><tt>include "' . $_POST['conf'] .'";</tt> <br />';

			echo '</body></html>';
			exit();

		} else {
			echo '<strong style="color: red">Could not save: missing some required data.</strong> <br />';
		}

} else { 

?>
<strong>Required components:</strong>
<ul>
	<li>MYSQL</li>
	<li>PHP5.2</li>
	<li>Smarty</li>
	<li>mod_rewrite for Apache <?php if (!isset($_GET['rewrite'])) echo '<strong> | <u>Not enabled!</u></strong>'; ?></li>
	<li>BIND9</li>
</ul>

<?php
}

// Check if mod_rewrite is enabeled
if (!isset($_GET['rewrite']))
	die('<font color="red">Fatal error: mod_rewrite is not enabeled!</font>');
?>

<form method="POST" action="install.php">
<h3>MySQL</h3>
MySQL host: <input type="text" name="mysql[host]" value="localhost" /> <br />
MySQL username: <input type="text" name="mysql[username]" /> <br />
MySQL password: <input type="password" name="mysql[password]" /> <br />
MySQL database: <input type="text" name="mysql[db]" /> <br />
<h3>Paths:</h3>
Smarty path: <input type="text" name="smarty" value="/usr/share/php/smarty/Smarty.class.php" /> <i>(Full path to Smarty.class.php)</i> <br />
Zones path: <input type="text" name="zones" value="/var/lib/bind/" /> <i>(Path to where to store zone files)</i> <br />
Conf path: <input type="text" name="conf" value="/etc/wmbind/wmbind.conf" /> <i>(Include this file in named.conf)</i> <br />
named-checkconf: <input type="text" name="namedcheckconf" value="/usr/sbin/named-checkconf" size="23" /> <i>Full path to executable named-checkconf</i> <br />
named-checkzone: <input type="text" name="namedcheckzone" value="/usr/sbin/named-checkzone" size="23" /> <i>Full path to executable named-checkzone</i> <br />
rndc: <input type="text" name="rndc" value="/usr/sbin/rndc" value="/usr/sbin/rndc" /> <i>Full path to executable rndc</i> <br />
<h3>Other:</h3>
SMBIND compability: <input type="checkbox" name="compability" value="true" /> <i>Compability with SMBIND userdatabases (weaker security)</i> <br />
<input type="submit" name="submit" value="Save" />
</form>
</body>
</html>
