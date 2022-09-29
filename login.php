<?php 
if(isset($_POST['submit']))
{
	/* SQL Injection Protect */
	$username  =  mysql_real_escape_string($_POST['userName']);
	$password  =  mysql_real_escape_string(md5($_POST['passWord']));
	
	/* XSS Protect */
	$username  =  htmlspecialchars(trim($username), ENT_QUOTES);
	$password  =  htmlspecialchars(trim($password), ENT_QUOTES);
	// Retrieve username and password from database according to user's input
					
	$query  = mysql_query("SELECT * FROM users WHERE username = '".$username."'")or die(mysql_error());
	$numrows = mysql_num_rows($query);

	if($numrows == 1)
	{ //open numrows		
		$row      = mysql_fetch_array($query);
		$dbid     = $row['user_id'];
		$dbuser   = $row['username'];
		$dbpass   = $row['password'];            
		$dbactive = $row['user_status'];
		$dbutype  = $row['usertype'];
		$dbempid  = $row['emp_id'];
		$dblogin  = $row['login'];
		$cpass    = $row['c_pass'];
		
		$querx  = mysql_query("SELECT * FROM users WHERE username = '".$username."' AND password = '".$password."' AND usertype = 'nesco'")or die(mysql_error());
		$numrowx = mysql_num_rows($querx);

		$c_stat	= $nq->getOneField("current_status","employee3","emp_id = '$dbempid' ");
		
		if($c_stat == "Active")
		{
	
			if ($numrowx == 1) 
			{ //open numrowx
					   
				if($dbactive == 'active') 
				{ //open dbactive	
				
					//open dblogin
						$sql=mysql_query("SELECT * FROM usertype WHERE usertype_id = '$dbid' ");
						while($rw=mysql_fetch_array($sql)){ echo $group=$rw['user_group']; $_SESSION['type']=$rw['user_group'];} 
						
						$_SESSION['usertype']     =  $dbutype;  
						$_SESSION['emp_id']       =  $dbempid; 
						$_SESSION['username']     =  $dbuser;
						$_SESSION['id']           =  session_id();
						$_SESSION['c_pass']       =  $cpass;			
																
						$sql = mysql_query("SELECT * FROM employee3 WHERE emp_id = '".$dbempid."' ORDER BY record_no DESC LIMIT 1");
						
						$qry = mysql_fetch_array($sql);
						$_SESSION['name']           =   $qry['name'];
						$_SESSION['position']       =   $qry['position'];
						$_SESSION['bunit_code']     =   $qry['bunit_code'];
								
						//EMPLOYEE DESIGNATION AREA
						$_SESSION['cc']     	  = $qry['company_code'];
						$_SESSION['bc']			  = $qry['bunit_code'];
						$_SESSION['dc']   		  = $qry['dept_code'];
						
						$_SESSION['company']      = $nq->getCompanyName($qry['company_code']);
						$_SESSION['businessunit'] = $nq->getBusinessUnitName($qry['bunit_code'],$qry['company_code']);
						$_SESSION['department']   = $nq->getDepartmentName($qry['dept_code'],$qry['bunit_code'],$qry['company_code']);
								
						$upd = mysql_query("UPDATE users SET login='yes' WHERE username = '$dbuser' and password = '$dbpass' ");
					 	
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
						$ip_add   =  get_client_ip();
													
						$tym_logIN=  date('Y-m-d H:i:s'); 
						$sql_logs =  mysql_query("SELECT * FROM sys_logs WHERE username= '".$dbuser."' ");
						$row      =  mysql_fetch_array($sql_logs);
						
						if(($row['tym_logOUT']=='0000-00-00 00:00:00')||($row['tym_logOUT']=='')) 
						{$_SESSION['tymlog']=$row['tym_logIN']; }
						else                                                                      
						{$_SESSION['tymlog']=$row['tym_logOUT'];}
						
						if(@mysql_num_rows($sql_logs) > 0)
						{						
							$upd= mysql_query("UPDATE sys_logs SET ip_add='$ip_add',tym_logIN='$tym_logIN',status='ONLINE' WHERE username = '$dbuser' ");
						}
						else
						{			
							$insert1  = mysql_query("INSERT INTO sys_logs (`username`, `ip_add`, `tym_logIN`, `tym_logOUT`, `status`)
							VALUES ('$dbuser','$ip_add','$tym_logIN','','ONLINE') ");					
							
							if(isset($insert1))
							{							
								$insert2  = mysql_query("INSERT INTO sys_session (`sess_id`, `username`, `ip_add`, `tym_logIN`, `tym_logOUT`) 
								VALUES ('$sess_id','$dbuser','$ip_add','$tym_logIN','') ");							
							}
						}
						header("Location: index.php");
						exit;				
				}// close dbactive
				else
				{
					// Jump to login page
					session_destroy();
					$msg = "User Account is inactive! Please contact your system administrator to activate your account.";										
				}
			}//close numrowx
			else
			{
				// Jump to login page
				session_destroy();
				$msg = "Password entered is incorrect!";					
			}
		}	
		else
		{
				// Jump to login page
				session_destroy();
				$msg = "Account is disabled for this user! Please contact your system administrator to activate your account.!";					
		}
	}//close numrows
	else
	{
		// Jump to login page
		session_destroy();
		$msg  = "User Account does not exists!";								
	}				
}
?>
<html xlmns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="css/layout_index.css" media="all" />		
		<title>HRMS</title>
	</head>

	<body onLoad="if(parent.frames.length!=0)top.location='index.php';">	
		<div id="title" class="title"> <img src="../images/icons/title2.png"></div>		
		<div id="content">
			
			<div id="loginPart1">
				
				<img src="../images/icons/nesco.jpg" width="200px" style="margin-top:50px"  id='profpic'/>
				
				<br><br>
				<form method="post">
					<table id="LoginTable1">
						<tr>
							<td>
								<input type="text" id="userName" name="userName" autocomplete="off" placeholder="username" required>
								<input name="log" id="log" type="hidden" value="employee"/>
							</td>
						</tr>
						<tr>
							<td>
								<input type="password" id="passWord" name="passWord" placeholder="password" required>
							</td>
						</tr>
						<tr>
							<td>
								<input type="submit" id="login" name="submit" value="NESCO Log In">
							</td>
						</tr>
					</table>
				</form>
				<div id='err' align="center"><?php echo @$msg;?></div>
			</div>
		</div>		
	</body>
</html>