<?php
	$login_id = $_SESSION['emp_id'];

	//COUNTS THE SENT MESSAGES
	$countsent  = mysql_query("SELECT messages.msg_id FROM `messages`, `message_details`
									WHERE `messages`.`msg_id` = `message_details`.`msg_id` AND sender = '$login_id' and sender_delete = 0 GROUP BY msg_id") or die(mysql_error());
	$ctrsent	= mysql_num_rows($countsent);
	$result = 0;

	if(!empty($_POST['subject']) && !empty($_POST['msg']) && !empty($_POST['receiver']))
	{
		$datesent	= date("Y-m-d H:i:s");
		$ccid		= $_POST['ccid'];

		//MESSAGES
		$query 		= mysql_query("INSERT INTO messages (msg_id,subject,msg,sender,datesent) 
		VALUES ('','".addslashes($_POST['subject'])."','".addslashes($_POST['msg'])."','".addslashes($_POST['sender'])."','$datesent')");
		
		//GETTING THE MESSAGE ID
		$getmsgid 	= mysql_query("SELECT max(msg_id) from MESSAGES");
		$rm 	  	= mysql_fetch_array($getmsgid);
		$msgid 	  	= $rm['max(msg_id)'];		

		//MESSAGE DETAILS
		for($i=0;$i<count($ccid);$i++){
			if($ccid[$i] !=''){
				$msgquery 	= mysql_query("INSERT INTO MESSAGE_DETAILS (msgdet_id,msg_id,cc,msg_stat,dateread)
				VALUES ('','$msgid','".$ccid[$i]."','0','') ") or die(mysql_error()); 
			}
		}

		$attachment	= $_FILES["attachment"]["name"];
			
		//if(!isset($_FILES["attachment"]["tmp_name"]))
		if(($_FILES["attachment"]["name"] !=""))
		{		

			for($j=0;$j<count($attachment);$j++){
				$dc = preg_replace('/  */', '_',$attachment[$j]);
				if($attachment[$j] == ""){
					$attachments 	= '';		
				}	
				else{
					$attachments 	= "../document/attachments/".$dc;	
				}				
				
				move_uploaded_file($_FILES["attachment"]["tmp_name"][$j],$attachments);	
					
				//$attachment = "hi&Hello";
				//echo $attachment;
				$pos = strpos($attachments,"&");
				if($pos === false){
					
				} else {					
					$attachments = str_replace('&','and',$attachments);
				}
			
				//MESSAGE ATTACHMENTS
				if($attachments !=''){
					$aquery = mysql_query("INSERT INTO MESSAGE_ATTACHMENTS (msgattach_id,msg_id,attachments)
				VALUES ('','$msgid', '".$attachments."') "); 
				}
			}
		}
		
		if($query && $msgquery){	
		//echo "<label class='alert alert-success alert-dismissable' id='sentmsg'>Message Sent!</label>";	
			?>
			<script> 
			//dismsg()
			//function dismsg(){
			//$(document).ready(function(){	
				alert('Message Sent')
				//$('#sentmsg').html("<label class='alert alert-success alert-dismissable' id='sentmsg'>Message Sent!</label>");	
				//$("#sentmsg").show().fadeOut(5000); 
		
			</script>
			<?php
		}
	}

	if(isset($_POST['attachmentUpload']))
	{
		$datesent	= date("Y-m-d H:i:s");

		// MESSAGES
		$query 		= mysql_query("INSERT INTO messages (msg_id,subject,msg,sender,datesent) 
		VALUES ('','".addslashes($_POST['subjectAttach'])."','".addslashes($_POST['msgAttach'])."','".addslashes($_POST['senderAttach'])."','$datesent')");
		
		// GETTING THE MESSAGE ID
		$getmsgid 	= mysql_query("SELECT max(msg_id) from MESSAGES");
		$rm 	  	= mysql_fetch_array($getmsgid);
		$msgid 	  	= $rm['max(msg_id)'];		

		// MESSAGE DETAILS
		$msgquery 	= mysql_query("INSERT INTO MESSAGE_DETAILS (msgdet_id,msg_id,cc,msg_stat,dateread) VALUES ('','$msgid','".addslashes($_POST['ccAttach'])."','0','') ") or die(mysql_error()); 

        $attachment	= $_FILES["attachmentAttach"]["name"];
        if(($_FILES["attachmentAttach"]["name"] !=""))
		{
			for($j=0; $j<count($attachment); $j++){
				$image = preg_replace('/  */', '_',$attachment[$j]);
				if($attachment[$j] == ""){
					$attachments 	= '';		
				}	
				else{
					$attachments 	= "../document/attachments/".$image;	
				}				
				
				if(@move_uploaded_file($_FILES["attachmentAttach"]["tmp_name"][$j],$attachments)){	
					
					$pos = strpos($attachments,"&");
					if($pos === false){
						
					} else {					
						$attachments = str_replace('&','and',$attachments);
					}

						$aquery = mysql_query("INSERT INTO MESSAGE_ATTACHMENTS (msgattach_id,msg_id,attachments)
					VALUES ('','$msgid', '".$attachments."') "); 
				}
			}
		}

		if($query && $msgquery)
		{
			$result = 2;
		}      
		?>
  		<script language="javascript" type="text/javascript">window.top.window.stopUpload("<?php echo $result; ?>");</script>  
	<?php                  
    }	
?>
<style type="text/css">	

	.messages {
		width: 50%;
	}
	.size-div {
		overflow-y: scroll;
		overflow-x: hidden;
		max-height: 480px;
	}
	.attachment-sender {
		border-radius: 3px;
		background: #f0f0f0;
		margin-top: 8px;
		float: right;
		padding: 10px;
		width: 400px;
	}

	.attachment-cc {
		border-radius: 3px;
		background: #f0f0f0;
		margin-top: 8px;
		float: left;
		padding: 10px;
		width: 400px;
	}

	 .message {
	    border: 1px solid #ccc;
	    border-radius: 3px;
	    padding: 5px;
	    margin-right: 5px;
	    white-space: wrap;
	    width:515px;
	    text-align: left;
	    display: inline;
	    display: inline-block;
	    background-position: #b0d8cd;

	} 

	.txtx { 
		width:300px; height 28px;
	}

	.preview_attachment {
        background-image: url("../images/unknown.png");
        background-size:contain;
        width:568px;
        height:319px;
        border:2px solid #BBD9EE
    }

    .btn {
    	border-radius: 0px;
    }
</style>

<body onload="msgOption('newmessage')"></body>
<div class='row' style='width:100%;margin:auto'>
	
	<div class='col-md-3'>
		
		<div class="list-group">
			<a href='javascript:void(0)' class="list-group-item" onclick="msgOption('newmessage')">
				<i class="fa fa-pencil"></i> &nbsp;Create New Message
			</a>
			<a href='javascript:void(0)' class="list-group-item"  onclick="msgOption('inbox')">
				<i class="glyphicon glyphicon-envelope"></i> &nbsp;Inbox <span class="badge" style="background-color:red;color:white"><?php echo $ctrinbox;?></span>
			</a>
			<a href='javascript:void(0)' class="list-group-item"  onclick="msgOption('sentMessage')">
				<i class="fa fa-folder-open-o"></i> &nbsp;Sent Messages <span class="badge" style="background-color:red;color:white"><?php echo $ctrsent;?></span>
			</a>
		</div>
	</div>
	<div class='col-md-9'>
		<span id='_loading' style='color:red'></span>	
		<div style="border:solid 1px #ccc;" class="row">
			<div class="col-md-12 show-form">
				
			</div>
		</div>
	</div>
</div>


<!-- Modal -->
<div class="modal fade" id="createmsgmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Send Messages to the following</h4>
      </div>
      <div class="modal-body" id='cc_sent' > <!-- Person to be sent here! --></div>      
      <div class="modal-footer">
		<input type='hidden' id='i_no'>	
		<input type='hidden' id='employee'>
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>		
	  </div>          
    </div> <!--/.modal-content -->
  </div> <!--/.modal-dialog -->
</div><!-- /.modal -->

<div id = "viewMessage" class="modal fade bs-example-modal-md">
  	<div class="modal-dialog modal-md messages">
    	<div class="modal-content">
	      	<div class="modal-header alert-info">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="closeInterval()">&times;</button>
		        <h4 class="modal-title">Message</h4>
		    </div>
	      	<div class="modal-body">
	      		<input type="hidden" name="intervalId">
	      		<div class="row">
	      			<div class="col-md-12">
	      				<input type="hidden" name="ccAttach">
	      				<input type="hidden" name="senderAttach">
	      				<div class="viewMessage size-div"></div>
	      			</div>
	      		</div>
	      	</div>

	      	<div class="modal-footer">
        		<div class="row">
        			<div class="col-md-12">
        				<div class="input-group">
							<span class="input-group-btn">
								<button class="btn btn-default" name="" style="border-radius:0px;" onclick="uploadAttachment()"><i class="fa fa-paperclip"></i></button>
							</span>
							<input type="text" class="form-control" name="replyMessage" style="border-radius:0px;">               
							<span class="input-group-btn">
								<button class="btn btn-primary" name="sentMessage" style="border-radius:0px;" onclick="replyMsg()">Sent <i class="fa fa-location-arrow"></i></button>
							</span>
						</div>
        			</div>
        		</div>
	     	</div>
    	</div><!-- /.modal-content -->
  	</div><!-- /.modal-dialog -->
</div>

<div id = "upload_attachment" class="modal fade bs-example-modal-md">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header alert-info">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="closeModal('attachment')">&times;</button>
        <h4 class="modal-title">Upload Attachments</h4>
      </div>
      <form method = 'POST' enctype='multipart/form-data' target='upload_target' onsubmit='startUpload();'>
          
          <input type="hidden" name="ccAttach">
          <input type="hidden" name="senderAttach">

          <div class="modal-body">
              <div class = "cleranceReq">
                  <div class="form-group">

						<p><input type='text' name='subjectAttach' placeholder='Subject' class="form-control" required=""  style="border-radius:0px;"></p>
						<textarea class="form-control" rows='10' style='resize:none; border-radius:0px;' name='msgAttach' placeholder='Type your message here...' required=""></textarea></p>
						<p><label>Attachment/s</label> <br><input type='file' id='attachment' name='attachmentAttach[]' multiple class="btn btn-default"><br>
						<p></p>
                  </div>
              </div>
          </div>

          <div class="modal-footer">
            <span id='f1_upload_process' style="display:none;"><img src = "../images/ajax.gif"> <font size = "2">Please Wait....</font></span>
            <input type="submit" name="attachmentUpload" class="btn btn-primary" value=" Submit">
            <button type="button" class="dis_ btn btn-default " data-dismiss="modal">Close</button>
         
          </div>

          <iframe id='upload_target' name='upload_target' src='#' style='width:0;height:0;border:0px solid #fff;'></iframe>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>

<div id = "viewsentMessage" class="modal fade bs-example-modal-md">
  	<div class="modal-dialog modal-md messages">
    	<div class="modal-content">
	      	<div class="modal-header alert-info">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		        <h4 class="modal-title">Sent Message Details</h4>
		    </div>
	      	<div class="modal-body">
	      		<input type="hidden" name="intervalId">
	      		<div class="row">
	      			<div class="col-md-12">
	      				<div class="viewsentMessage"></div>
	      			</div>
	      		</div>
	      	</div>

	      	<div class="modal-footer">
        		<button type="button" class="dis_ btn btn-default " data-dismiss="modal">Close</button>
	     	</div>
    	</div><!-- /.modal-content -->
  	</div><!-- /.modal-dialog -->
</div>

<script>
	function sendmessageto()
	{
		if($('input[type=text]').hasClass('cc_class')==true){
			var a = document.getElementsByName('ccid[]');	
			var id = '';
			for(var i = 0;i<a.length;i++) {
				id += a[i].value+',';
			}
			$.ajax({
				type: "POST",
				url: "functionquery.php?request=getccsents",
				data: { id :id },
				success: function(data){
					$('#cc_sent').html(data);
				}
			});
		}
		else {
			$.ajax({
				type: "POST",
				url: "functionquery.php?request=getccsents",
				success: function(data){
					$('#cc_sent').html(data);
				}
			});
		}
	}

	function savecc()
	{
		$(".cc_class").remove();
		var a = document.getElementsByName('cc[]');	
		var id = '';
		var x = 0;
		for(var i = 0;i<a.length;i++) {
			if(a[i].checked == true) {
				id += a[i].value+',';
				x++
			}
		}
		if(x == 0) {
			alert("No Receiver Saved!");
			return false;
		}
		var ids = id.split(',');
		var cc = '';
		for(var i=0;i<ids.length-1;i++){
			cc += $('td[id^="n_'+ids[i]+'"]').text()+ ' | ';

			$('#createmsg').append("<div style='display:none'><input type='text' class='cc_class' name='ccid[]' value='"+ids[i]+"'></done>");
		}
		//
		$('#receiver').val(cc);
		alert('Receiver Successfully added');
		
	}

	function msgOption(code){

		$("#_loading").html('<i>Loading, Please wait.....</i>');
		var $request;
		switch(code){
			case "inbox": 		$request = "showInbox"; break;
			case "newmessage": 	$request = "newMessage"; break;
			case "sentMessage": $request = "showSentMessage"; break;
		}
	
		$.ajax({
			type: "POST",
			url: "functionquery.php?request="+$request,
			success: function(data){	
				$("#_loading").html('');							
				$('.show-form').html(data);					
			}
		});
	}

	function viewMsg(sender,cc){

        $("#viewMessage").modal({
            backdrop: 'static',
            keyboard: false
        });

        $("#viewMessage").modal("show");
        
        // set value to input type
        $("[name = 'ccAttach']").val(sender);
        $("[name = 'senderAttach']").val(cc);

        $.ajax({
			type : "POST",
			url	 : "functionquery.php?request=viewMessage",
			data : { cc:sender },
			success: function(data){
				$(".viewMessage").html(data);
				// $(".size-div").scrollBy(100,100);		
			}
		});

        var intervalId = null;
        intervalId = setInterval(function(){

		        $.ajax({
					type : "POST",
					url	 : "functionquery.php?request=viewMessage",
					data : { cc:sender },
					success: function(data){

						$(".viewMessage").html(data);
						// $(".size-div").scrollBy(100,100);		
					}
				});
        	}, 6000
        );

        $("[name = 'intervalId']").val(intervalId);
	}

	function closeInterval(){

		var intervalId = $("[name = 'intervalId']").val();
		clearInterval(intervalId); // stop the interval
		
		window.location = "?p=message";
	}

	function deleteMsg(msgId,details){

		var r = confirm("Are you want to delete this message?");
		if(r == true){

			$.ajax({
				type : "POST",
				url	 : "functionquery.php?request=deleteMessage",
				data : { msgId:msgId, details:details },
				success: function(data){
					data = data.trim();
					if(data == "Ok"){

						$(".deleteMsg_"+msgId).fadeOut(1000);		
					} else {
						alert(data);
					}
				}
			});
		}
	}

    function stopUpload(success){

        if (success == 2){
         
            alert("Successfully Save!");
            setTimeout(function(){
            
                $("#upload_attachment").modal("hide");

                /*var intervalId = $("[name = 'intervalId']").val();
				clearInterval(intervalId); // stop the interval*/

            },1000);
        }
        
        
        if(success == 0) {
            alert("There was an error during file upload!");
        }
        
        $("#f1_upload_process").hide();   
        return true;   
    }

    function uploadAttachment(){

        $("#upload_attachment").modal({
            backdrop: 'static',
            keyboard: false
        });

        $("#upload_attachment").modal("show");
    }

    function replyMsg(){

    	var reply  = $("[name = 'replyMessage']").val();
    	var sender = $("[name = 'senderAttach']").val();
    	var cc     = $("[name = 'ccAttach']").val();

    	$.ajax({
			type: "POST",
			url: "functionquery.php?request=replyMessage",
			data: { sender:sender, cc:cc, reply:reply },
			success: function(data){								
				
				data = data.trim();
				if(data == "Ok"){

					alert("Message Sent");
					$("[name = 'replyMessage']").val("");
				} else {
					alert(data);
				}					
			}
		});
    }

    function read(msgId,cc){
    	$.ajax({
			type: "POST",
			url: "functionquery.php?request=readMessage",
			data: { msgId:msgId, cc:cc },
			success: function(data){
			
				data = data.trim();
				if(data == "Ok"){

					// alert("Seen");
				} else {
					alert(data);
				}					
			}
		});
    }

    function deleteSentItem(msgId){
    	var details = "sender";
    	var r = confirm("Are you want to delete this message?");
		if(r == true){

			$.ajax({
				type : "POST",
				url	 : "functionquery.php?request=deleteMessage",
				data : { msgId:msgId, details:details },
				success: function(data){
					data = data.trim();
					if(data == "Ok"){

						$("#deleteSentItem_"+msgId).fadeOut(1000);		
					} else {
						alert(data);
					}
				}
			});
		}
    }

    function viewSentItem(msgId){


        $("#viewsentMessage").modal({
            backdrop: 'static',
            keyboard: false
        });

        $("#viewsentMessage").modal("show");

        $.ajax({
			type : "POST",
			url	 : "functionquery.php?request=viewsentMessage",
			data : { msgId:msgId },
			success: function(data){
				$(".viewsentMessage").html(data);		
			}
		});
    }

    function deleteAllImg(sender,cc,convoId){

    	var r = confirm("Are you want to delete all messages?");
		if(r == true){

			$.ajax({
				type : "POST",
				url	 : "functionquery.php?request=deleteAllMessage",
				data : { sender:sender, cc:cc },
				success: function(data){
					data = data.trim();
					if(data == "Ok"){

						$("#convoId_"+convoId).fadeOut(1000);		
					} else {
						alert(data);
					}
				}
			});
		}
    }

</script>