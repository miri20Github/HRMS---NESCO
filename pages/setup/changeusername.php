<?php
if(isset($_SESSION['username'])){
	//header("Location: index.php");
}

$usertype	= $_SESSION['usertype'];
if(isset($_POST['submit'])){
	$newuser 	= $_POST['new_user'];		
	$query 		= mysql_query("SELECT user_no from users where username = '".$newuser."' ");
	if(mysql_num_rows($query) > 0){?>
		<script>
			alert('Username is already taken. Please use another username.!');			
		</script><?php
	}else{ ?>
		<?php 
		$querys   = mysql_query("SELECT user_no from users where emp_id = '$_SESSION[emp_id]' and username = '".$_SESSION['username']."' ");
		$row 	  = mysql_fetch_array($querys);
		$userno   = $row['user_no'];

		$saveuser = mysql_query("UPDATE users set username = '$newuser' where user_no = '$userno' ");
		if($saveuser){?>
			<script>
				alert('You have Successfully change your username. The system will logout and kindly log in again. Thank You!');
				window.location = "logout.php";
			</script>
			<?php
		}	
	}	
} ?>

<div style="width:40%;">
    <div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><span class="glyphicon glyphicon-hand-right"></span>&nbsp;CHANGE USERNAME</h3>
		</div>  
		<div class="panel-body">    	
			<form action="?p=changeusername&&db=setup" method="post" onSubmit="return validateForm();">
				<p><i>Note: Username should be unique, therefore you are advised to use username that is relevant to you or to your name. </i></p>
				<label>New Username</label>
				<p><input type="text" name="new_user" id="new_user" onkeyup='checkusername()' autocomplete='no' class="form-control" required="required"/></p>           
				<p><input type="submit" name="submit" id="submit" value="Submit" class="btn btn-primary"/>
					<input type="button" name="button" id="button" value="Cancel" onclick="backs()" class="btn btn-default"/></p>
			</form>
		</div>
	</div>
</div>
<script>
	function validateForm(){
		var r = confirm('Are you sure to change you username?')
		if(r == false){
			return false;
		}
	}
	function checkusername()
	{
		var new_user = document.getElementById('new_user').value;			
		$.ajax({
		type: "POST",
			url: "functionquery.php?request=checkusername",
			data: { new_user:new_user },
			success: function(data){				
				if(data > 0){				
					//document.getElementById('new_user').value = '';
					alert('Username is already taken. Please use another username.')				
				}
			}
		});			
	}

	function backs(){
		window.location = '?p=home';
	}
</script>