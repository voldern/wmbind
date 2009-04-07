<?php

// Additional hash to hash the passwords with
// WARNING: Changing this during production will make all current accounts invalid
$this->passwordHash = '¡@3kDKAS9123:.213if;2,31ujasd1__123';

// Should WMBIND be backwards compatible with SMBIND? (WARNING: This will lead to weaker security and the hash above will be ignored)
$this->compability = false;

// Database config
$this->db_type = 'mysql'; // mysql for MySQL, pgsql for PostgreSQL
$this->db_user = ''; // Database username
$this->db_pass = ''; // Database password
$this->db_host = 'localhost'; // Database host
$this->db_db = ''; // Database name

// Full path to Smarty.class.php
$this->smarty_path = '/var/www/smarty/Smarty.class.php';

// Zone data paths
$this->zones_path = "/etc/bind/"; // Path to where to store zone files
$this->conf_path = "/etc/smbind/smbind.conf"; // Include this file in named.conf.

// BIND utilities.
$this->namedcheckconf = '/usr/sbin/named-checkconf';
$this->namedcheckzone = '/usr/sbin/named-checkzone';
$this->rndc = '/usr/sbin/rndc';
