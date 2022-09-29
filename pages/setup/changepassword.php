<?php
if(isset($_SESSION['username'])){
	//header("Location: index.php");
}

$query 	= mysql_query("SELECT password FROM users where username = '$_SESSION[username]' ");
$row 	= mysql_fetch_array($query);
$old 	= $row['password'];

if(isset($_POST['submit'])){
	if($old == md5($_POST['oldpass'])){
		$newpass = md5($_POST['newpass']);		
		$savepass = mysql_query("UPDATE users set password = '$newpass', c_pass = 1 where username = '$_SESSION[username]' ");
		if($savepass){?>
			<script>
				alert('You have Successfully change your password. The system will logout and kindly log in again. Thank You!');
				window.location = "logout.php";
			</script>
			<?php
		}			
	}else{
		?>
			<script>
				alert('Please input the exact Old Password!!');
				window.location = "change_password.php";
			</script>
			<?php
	}
}
$word1='Password must contain at least 6 characters, including UPPER/lowercase and numbers ex: AkoC123';
$word2='Please enter the same Password like the New Password';
?>
<div style="width:40%;">
    <div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><span class="glyphicon glyphicon-hand-right"></span>&nbsp;CHANGE PASSWORD</h3>
	</div>  
	<div class="panel-body">
		
	<form action="?p=changepassword&&db=setup" method="post" onsubmit="return checkpassword()">
			<p><i>Note: Password is alphanumeric. It must contain letters (uppercase and lowercase) and numbers. </i></p>
			<label>Old Password</label>
			<p><input type="password" name="oldpass" id="oldpass" class="form-control" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])\w{6,}" onChange=" this.setCustomValidity(this.validity.patternMismatch ? '<?php echo $word1;?>' : ''); " /></p>
		
			<label>New Password</label>
			<p><input type="password" name="newpass" id="newpass" class="form-control" required="required" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])\w{6,}" onChange=" this.setCustomValidity(this.validity.patternMismatch ? '<?php echo $word1;?>' : '');
						if(this.checkValidity()) form.cpassword.pattern = this.value; "/></p>
		
			<label>Confirm New Password</label>
			<br> 
			<input type="password" name="confirmpass" id="confirmpass" class="form-control" required="required" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])\w{6,}" onChange=" this.setCustomValidity(this.validity.patternMismatch ? '<?php echo $word2;?>' : '');
						if(this.checkValidity()) form.cpassword.pattern = this.value; " />
			<br>
			<input type="submit" name="submit" id="submit" value="Submit" class="btn btn-primary"/>
			<input type="button" name="button" id="button" value="Cancel" onclick="backs()" class="btn btn-default"/>
		</form>
		</div>
	</div>
</div>
<script>
	function checkpassword(){
		var newpass = document.getElementById('newpass').value;
		var confirmpass = document.getElementById('confirmpass').value;
		if(newpass != confirmpass){
			alert('Password Mismatch!'); // + newpass + " "+ confirmpass
			return false;
		}
	}
	function backs()
	{
		window.location = '?p=home';
	}
</script>