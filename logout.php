<?php
// Inialize session
session_start();

	// include connection
	include('config.php');
	include('configs.php');
	if(isset($_GET['com']))
	{$com=$_GET['com'];}
	
	// get session id
	$sess_id  = $_SESSION['id'];
	// get session username
	$username = $_SESSION['username'];
	// Function to get the client ip address
	function get_client_ip() 
	{
		$ipaddress = '';
		if ($_SERVER['HTTP_CLIENT_IP'])
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if($_SERVER['HTTP_X_FORWARDED_FOR'])
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if($_SERVER['HTTP_X_FORWARDED'])
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if($_SERVER['HTTP_FORWARDED_FOR'])
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if($_SERVER['HTTP_FORWARDED'])
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if($_SERVER['REMOTE_ADDR'])
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}
	// get now the ip address
	$ip_add      =  get_client_ip();
	// get now the timezone ex:  2013-08-15 23:59:59
	date_default_timezone_set('Asia/Manila');
	$tym_logOUT  =  date('Y-m-d H:i:s', time()); 
	// update table 
	$updateOff   =  mysql_query("UPDATE sys_logs SET tym_logOUT='$tym_logOUT',status='OFFLINE' 
	WHERE username='$username'");
	//update table again
	$updateDue   =  mysql_query("UPDATE SYS_SESSION SET tym_logOUT='$tym_logOUT' WHERE username='$username' 
	AND sess_id='$sess_id'");
	$updateUser  =  mysql_query("UPDATE users SET login='no' WHERE username='$username'");
	// Delete certain session
	session_regenerate_id(); // assign new session id
	// Unset all of the session variables.
	$_SESSION = array();
	
	// If it's desired to kill the session, also delete the session cookie.
	// Note: This will destroy the session, and not just the session data!
	/*if (ini_get("session.use_cookies")) {
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000,
			$params["path"], $params["domain"],
			$params["secure"], $params["httponly"]
		);
	}*/
	
	// Finally, destroy the session.
	session_destroy();		
	header("Location: ../nesco/");
?>