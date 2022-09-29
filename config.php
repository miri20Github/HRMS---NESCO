<?php
$hostname = 'localhost:3307';         // Your MySQL hostname. Usualy named as 'localhost'.
$dbname   = 'pis';               // Your database name.
$username = 'pis';       // Your database username.
$password = 'itprog2013';    // Your database password. If your database has no password, leave it empty.
// host connect
mysql_connect($hostname, $username, $password) or DIE('Connection to host is failed, perhaps the service is down!');
// database select
mysql_select_db($dbname) or DIE('Database name is not available!');
?>