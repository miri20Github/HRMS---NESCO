
	function succSave(messej){
		$.alert.open({
		  type: 'warning',
			title: 'Info',
			icon: 'confirm',
			content: messej
		});
	}


	function succSaveReload(messej,url){

	  $.alert.open({
		type: 'warning',
		  title: 'Info',
		  icon: 'confirm',
		  content: messej,

			callback: function() {
			  window.location.href = url;
		  }
	  });
	}

	function confirmPro($id,messej){

		var func_name = $("[name = 'func_name']").val();

	   	$.alert.open({
        type: 'warning',
        cancel: false,
        content: messej,
        buttons:{
            OK: 'Yes',
            NO: 'Not now'
        },
            callback: function(button) {
                if (button == 'OK'){
                		
            		if(func_name == "del_ip_now"){ //multiple delete
            	 		del_ip_now($id);		
            		}
            		else if(func_name == "del_setup"){ //single delete
            			del_setup($id);
            		}
            			
                }			

            }
    	});
	}


	function con_delIpDate(suId,messej){

	   	$.alert.open({
        type: 'warning',
        cancel: false,
        content: messej,
        buttons:{
            OK: 'Yes',
            NO: 'Not now'
        },
            callback: function(button) {
                if (button == 'OK'){
                		
                	delIpDateNow(suId);
                }			

            }
    	});
	}	

	function errDup(messej){
		$.alert.open({
			type: 'warning',
			content: messej
		});  
	}
		

	function undo_back(){

	   	$.alert.open({
        type: 'warning',
        cancel: false,
        content: "Do you want to logout?",
        buttons:{
            OK: 'Ok',
            NO: 'Not now'
        },
            callback: function(button) {
                if (button == 'OK'){
                    // window.location.href = "../../hrms/employee";
                	window.location.href = "logout.php";
                }			

            }
    	});
	}
