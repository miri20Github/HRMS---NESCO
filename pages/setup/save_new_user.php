<?php 
if(isset($_POST['submit'])) // submit for new users
{    
	// store inside TABLE : -----------------------------------------------users
	$emp   = explode('*',@$_POST['emp_id']);	
	$emp_id = $emp[0];
	$fullname = $nq->getAppName($emp_id);

	$username = mysql_real_escape_string(trim(@$_POST['username'])); 
	$password = mysql_real_escape_string(trim(@$_POST['password'])); 
	$usertype = @$_POST['usertype'];	

	$groupname= @$_POST['usergroup'];	
	$stat='inactive';
	
	if($usertype == "supervisor"){
		$getuserid 	= mysql_query("SELECT usertype_id FROM usertype where usertype_name = 'employee' "); //suprvisr and employee should be the same usertype id
	}	
	else{
		$getuserid 	= mysql_query("SELECT usertype_id FROM usertype where usertype_name = '$usertype' ");
	}	
	$rr 		= @mysql_fetch_array($getuserid);
	$usertypeid = $rr['usertype_id'];

	$cpass = '1';

	// store inside TABLE: -----------------------------------------------sys_event
	$creator_role    = @$_SESSION['usertype'];
	//echo $creator_role;
	$creator_name    = @$_SESSION['username'];
	$event_name      = 'added useraccount';
	$created_event   = "Added useraccount<br>
						EMP_ID".@$emp_id."<br> 
						EMP_NAME".@ucwords($fullname)."<br> 
						USERNAME".@$username."<br> 
						USERTYPE". @$usertype[1]."<br> 
						BUSINESSUNIT".@$sub;

	date_default_timezone_set('Asia/Manila');
	$tym_act         = date('Y-m-d H:i:s', time()); 
	$tym_act_format  = date("l jS \of F Y h:i:s A"); 
	$time1  = date('Y-m-d H:i:s', time()); 
	$time2  = '';

	mysql_query("INSERT INTO sys_event VALUES ('$creator_name','$event_name','$created_event','$tym_act','$tym_act_format')" );
	// check if emp_id is registered ----------------------------------------------------------------

	if($usertype  == "employee" || $usertype == "supervisor"){
		$groupname = "";
	}

	$affect = @mysql_query("SELECT emp_id FROM employee3 WHERE emp_id='$emp_id' AND current_status = 'active' ORDER BY record_no DESC LIMIT 1");	
	if (@mysql_num_rows($affect)==0)
	{ 
		$msg = "Sorry, cannot add inactive employee.";
    }
	else
	{  
		//ACTIVE NA DAUN ANG USER STATUS NAG SAVE
		$sel=mysql_query("SELECT * FROM users");
		if(mysql_num_rows($sel)==0)
		{  
			$ins 	 = mysql_query("INSERT INTO users
			(user_no,emp_id,username,password,usertype,user_status,login,date_created,date_updated,user_id,usergroup,c_pass)
			VALUES 
			('','$emp_id','$username',md5('$password'),'$usertype[1]','active','no','$time1','$time2','$usertypeid','$groupname','$cpass')" );
			$msg 	 = "Employee Number has successfully created an account..";
	    }
		else
		{ 	// check if username is already taken
			$select=mysql_query("SELECT * FROM users where username='$username'");
			if(mysql_affected_rows()==1)
			{  
					$msg = "Sorry, Username is already taken, cannot have similar username.";
			}
			else
			{ 	// check if user has duplicate usertype 
				$select=mysql_query("SELECT * FROM users where emp_id='$emp_id' AND usertype='$usertype[1]' AND user_id='$usertype[0]'");
				if(mysql_affected_rows()==1)
				{ 
					$msg = 'Sorry, Employee Number has already an '.strtoupper($usertype[1]).' account for '.strtoupper($grp).' group ... \n\nPlease choose another usertype...';
				}
				 else
				{ 
					$inss = mysql_query("INSERT INTO users 
					(user_no,emp_id,username,password,usertype,user_status,login,date_created,date_updated,user_id,usergroup,c_pass)
					VALUES 
					('','$emp_id','$username',md5('$password'),'$usertype','active','no','$time1','$time2','$usertypeid','$groupname','$cpass') ");
					//$msg = "INSERT INTO users VALUES ('','$emp_id','$username',md5('$password'),'$usertype','inactive','no','$time1','$time2','$usertypeid','$groupname','$cpass')";

					$ins2=mysql_query("INSERT INTO sys_event VALUES ('$creator_role','$creator_name','$event_name','$created_event','$tym_act','$tym_act_format')");
					$msg='Account successfully added for Employee IDNo: '.$emp_id.'!\n\nThank You...'; 				
				}
		  	}		
	  	}  
 	} 
 	?>
 	<script>
 	alert('<?php echo $msg;?>');
 	window.location = "?p=useraccounts";
 	</script>
 	<?php 
}
?>