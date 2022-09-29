/*
$(document).keydown( function(event) 
{
	var keycode = event.keyCode; // get the key code of the enter button
	if(keycode == 13)
	{		
		$('#loading').show();
		//alert(keycode)
		var username = $('#userName').val().trim();
		var password = $('#passWord').val().trim();		

		if(username == "" && password == ""){ // if username and password are empty
			$('#loading').hide();
			document.getElementById('ms').value = "Please input username and password to login";
			$("#ms").show().fadeOut(5000);	
			//alert("Please input username and password to login")
			//$('#loading').hide();
		}
		else if(username == "" ){			  // if username is empty
			$('#loading').hide();
			document.getElementById('ms').value = "Please input username to login";
			$("#ms").show().fadeOut(5000);	
			//alert("Please input username to login")
			//$('#loading').hide();
		}
		else if(password == ""){	
				  // if password is empty
			$('#loading').hide();
			document.getElementById('ms').value = "Please input password to login";			
			$("#ms").show().fadeOut(5000);	
			//alert("Please input password to login")
			
		}
		else{
		  	$.ajax({
				type: "POST",
				url: "checklogin.php",
				data: { username:username, password:password },
				success: function(data)
				{
					//alert(data)
					var n = data.search("success"); 
					if(n >= 0){
						var arr = data.split('%');						
						if(arr[0] == "success")
						{
							//$('#contracts').html('<div style="margin-left:-400px;margin-top:200px;"><img src="../images/system/10.gif"><p style="margin-left:-20%;"><b><i>LOADING. . .</i></b></p></div>');
							//$("#loading").html('<div style="margin-left:-400px;margin-top:200px;"><img src="../images/icons/loading11.gif"></div>');							
							$('#loading').hide();
							alert('Successfully Login');
							
							window.location = arr[1]+"/"; 
						} 
					}
					else
					{	
						//document.getElementById('msg').value = data;
						//$("#msg").show().fadeOut(5000);
						$('#loading').hide();
						alert(data);										
					}
				}
			});				
		}
	}	
});
*/
$(document).ready(function()
{  	
	//on click login
	$('#login').click(function()
	{		
		$('#loading').show();

		var username = $('#userName').val().trim();
		var password = $('#passWord').val().trim();		
	
		if(username == "" && password == ""){ // if username and password are empty
			$('#loading').hide();
			document.getElementById('ms').value = "Please input username and password to login";
			$("#ms").show().fadeOut(5000);	
			//alert("Please input username and password to login")
			$('#loading').hide();
		}
		else if(username == "" ){			  // if username is empty
			$('#loading').hide();
			document.getElementById('ms').value = "Please input username to login";
			$("#ms").show().fadeOut(5000);	
			//alert("Please input username to login")
			//$('#loading').hide();
		}
		else if(password == ""){			  // if password is empty
			$('#loading').hide();
			document.getElementById('ms').value = "Please input password to login";			
			$("#ms").show().fadeOut(5000);	
			//alert("Please input password to login")
			//$('#loading').hide();
		}
		else{
			$("#ms").hide();
		  	$.ajax({
				type: "POST",
				url: "checklogin.php",
				data: { username:username, password:password },
				success: function(data)
				{
 					var n = data.search("success"); 
					if(n >= 0){
						var arr = data.split('%');						
						if(arr[0] == "success")
						{
							//$('#contracts').html('<div style="margin-left:-400px;margin-top:200px;"><img src="../images/system/10.gif"><p style="margin-left:-20%;"><b><i>LOADING. . .</i></b></p></div>');
							//$("#loading").html('<div style="margin-left:-400px;margin-top:200px;"><img src="../images/icons/loading11.gif"></div>');							
							$('#loading').hide();
							alert('Successfully Login');
							
							window.location = arr[1]+"/"; 
						} 
					}
					else
					{	
						//document.getElementById('msg').value = data;
						//$("#msg").show().fadeOut(5000);
						$('#loading').hide();
						alert(data);										
					}
				}
			});		
		}
	});	

	//on click password field
	$('#passWord').click(function()
	{
		var uname = $('#userName').val().trim();
		var logins = $('#log').val().trim();
		
		if(uname!='')
		{								
			$.ajax({
				type: "POST",
				url: "checkusername.php",
				data: { uname:uname, logins:logins },
				success: function(data)
				{											
					if(data != "true")
					{
						if(data!= "false")
						{	
							var x = "Invalid Account for this Login!";
							//alert(x)
							$('#err').html(x).fadeOut(5000);					
							document.getElementById('login').disabled = true;
							document.getElementById('ms').value = x;	
							document.getElementById('err').style.visibility = "visible";											
						}
						else
						{
							document.getElementById('login').disabled = false;
							document.getElementById('err').style.visibility = "hidden";								
						}
					}
					else
					{
						document.getElementById('login').disabled = false;
						document.getElementById('err').style.visibility = "hidden";	
					}										
				}
			});	
		}
	});	

	//on focus to password field
	$('#passWord').focus(function()
	{
		var uname = $('#userName').val().trim();		
		var logins = $('#log').val().trim();			

		if(uname!='')
		{			
			$.ajax({
				type: "POST",
				url: "checkusername.php",
				data: { uname:uname, logins:logins },
				success: function(data)
				{					
					if(data != "true")
					{
						if(data!= "false")
						{							
							var x = "Invalid Account for this Login!";
							//alert(x)
							$('#err').html(x).fadeOut(5000);	
							document.getElementById('login').disabled = true;
							document.getElementById('ms').value = x;	
							document.getElementById('err').style.visibility = "visible";
						}
						else
						{
							document.getElementById('login').disabled = false;
							document.getElementById('err').style.visibility = "hidden";	
						}
					}
					else
					{
						document.getElementById('login').disabled = false;
						document.getElementById('err').style.visibility = "hidden";	
					}							
				}
			});	
		}
	});			
});

