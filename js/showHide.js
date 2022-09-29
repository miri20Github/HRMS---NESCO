function showLogin(division){
	$("#loginPart").fadeIn(700);
	$("#tableLinks").hide();

	$.ajax({

		type : 'POST',
		data : {division:division},
		url : 'login.php?action=login',
		success: function(responseText){
			if(responseText){
				$('#loginPart').html(responseText);
			}
		}
	});	
}

function hideLogin(){
	$("#tableLinks").fadeIn(700);
	$("#loginPart").hide();
}
