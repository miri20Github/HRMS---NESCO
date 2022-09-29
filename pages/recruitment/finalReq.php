<?php 
	
	include("header.php");

	if(isset($_POST['fileUpload'])){

        $appId = $_POST['appId'];
        $appCode = $_POST['appCode'];
        $date  = date('Y-m-d');
		$staff = $_SESSION['username']."-".$_SESSION['type'];
		$position    = $_POST['position'];

        $result = 0;
        
        // birth certificate
    	$birth = count($_FILES['birthCertificate']['name']);
    	for($i = 0; $i < $birth; $i++) {

		    $image 	= addslashes($_FILES['birthCertificate']['name'][$i]);
			$extension=end(explode(".", $image));

			$filename = "$appId=$date=birthcertificate=".date('h-i-s-A').".".$extension;
			$destination_path = "../document/final_requirements/birth_certificate/$filename";

			if(@move_uploaded_file($_FILES['birthCertificate']['tmp_name'][$i],$destination_path)) {

				$query = mysql_query("INSERT INTO `application_finalreq`
												(`no`, `app_id`, `requirement_name`, `filename`, `date_time`, `requirement_status`, `receiving_staff`) 
											VALUES 
												('','$appId','BirthCertificate','$destination_path','$date','passed','$staff')")or die(mysql_error());
			}
    	}

		// police clearance
    	$image 	= addslashes($_FILES['policeClearance']['name']);
		$extension=end(explode(".", $image));

		$filename = "$appId=$date=policeclearance=".date('h-i-s-A').".".$extension;
		$destination_path = "../document/final_requirements/police_clearance/$filename";

		if(@move_uploaded_file($_FILES['policeClearance']['tmp_name'],$destination_path)) {

			$query = mysql_query("INSERT INTO `application_finalreq`
											(`no`, `app_id`, `requirement_name`, `filename`, `date_time`, `requirement_status`, `receiving_staff`) 
										VALUES 
											('','$appId','PoliceClearance','$destination_path','$date','passed','$staff')")or die(mysql_error());
		}

		// fingerprint
		$image 	= addslashes($_FILES['fingerprint']['name']);
		$extension=end(explode(".", $image));

		$filename = "$appId=$date=fingerprint=".date('h-i-s-A').".".$extension;
		$destination_path = "../document/final_requirements/fingerprint/$filename";

		if(@move_uploaded_file($_FILES['fingerprint']['tmp_name'],$destination_path)) {

			$query = mysql_query("INSERT INTO `application_finalreq`
											(`no`, `app_id`, `requirement_name`, `filename`, `date_time`, `requirement_status`, `receiving_staff`) 
										VALUES 
											('','$appId','Fingerprint','$destination_path','$date','passed','$staff')")or die(mysql_error());
		}

		// sss
		$image 	= addslashes($_FILES['sss']['name']);
		$extension=end(explode(".", $image));

		$filename = "$appId=$date=sss=".date('h-i-s-A').".".$extension;
		$destination_path = "../document/final_requirements/sss/$filename";

		if(@move_uploaded_file($_FILES['sss']['tmp_name'],$destination_path)) {

			$query = mysql_query("INSERT INTO `application_finalreq`
											(`no`, `app_id`, `requirement_name`, `filename`, `date_time`, `requirement_status`, `receiving_staff`) 
										VALUES 
											('','$appId','SSS','$destination_path','$date','passed','$staff')")or die(mysql_error());
		}

		// cedula
		$image 	= addslashes($_FILES['cedula']['name']);
		$extension=end(explode(".", $image));

		$filename = "$appId=$date=cedula=".date('h-i-s-A').".".$extension;
		$destination_path = "../document/final_requirements/cedula/$filename";

		if(@move_uploaded_file($_FILES['cedula']['tmp_name'],$destination_path)) {

			$query = mysql_query("INSERT INTO `application_finalreq`
											(`no`, `app_id`, `requirement_name`, `filename`, `date_time`, `requirement_status`, `receiving_staff`) 
										VALUES 
											('','$appId','Cedula','$destination_path','$date','passed','$staff')")or die(mysql_error());
		}

		// parents consent
		if(isset($_FILES['parentsConsent']['tmp_name'])) {
			$image 	= addslashes($_FILES['parentsConsent']['name']);
			$extension=end(explode(".", $image));

			$filename = "$appId=$date=consent=".date('h-i-s-A').".".$extension;
			$destination_path = "../document/final_requirements/parent_consent/$filename";

			if(@move_uploaded_file($_FILES['parentsConsent']['tmp_name'],$destination_path)) {

				$query = mysql_query("INSERT INTO `application_finalreq`
												(`no`, `app_id`, `requirement_name`, `filename`, `date_time`, `requirement_status`, `receiving_staff`) 
											VALUES 
												('','$appId','ParentsConsent','$destination_path','$date','passed','$staff')")or die(mysql_error());
			}
		}

		// medical certificate
		$image 	= addslashes($_FILES['medicalCert']['name']);
		$extension=end(explode(".", $image));

		$filename = "$appId=$date=medicalcertificate=".date('h-i-s-A').".".$extension;
		$destination_path = "../document/final_requirements/medical_certificate/$filename";

		if(@move_uploaded_file($_FILES['medicalCert']['tmp_name'],$destination_path)) {

			$query = mysql_query("INSERT INTO `application_finalreq`
											(`no`, `app_id`, `requirement_name`, `filename`, `date_time`, `requirement_status`, `receiving_staff`) 
										VALUES 
											('','$appId','MedicalCertificate','$destination_path','$date','passed','$staff')")or die(mysql_error());
		}

		// sketch
		$image 	= addslashes($_FILES['sketch']['name']);
		$extension=end(explode(".", $image));

		$filename = "$appId=$date=sketch=".date('h-i-s-A').".".$extension;
		$destination_path = "../document/final_requirements/sketch/$filename";

		if(@move_uploaded_file($_FILES['sketch']['tmp_name'],$destination_path)) {

			$query = mysql_query("INSERT INTO `application_finalreq`
											(`no`, `app_id`, `requirement_name`, `filename`, `date_time`, `requirement_status`, `receiving_staff`) 
										VALUES 
											('','$appId','Sketch','$destination_path','$date','passed','$staff')")or die(mysql_error());
		}

		// background investigation
		$image 	= addslashes($_FILES['backgroundInvestigation']['name']);
		$extension=end(explode(".", $image));

		$filename = "$appId=$date=bi=".date('h-i-s-A').".".$extension;
		$destination_path = "../document/final_requirements/bi/$filename";

		if(@move_uploaded_file($_FILES['backgroundInvestigation']['tmp_name'],$destination_path)) {

			$query = mysql_query("INSERT INTO `application_finalreq`
											(`no`, `app_id`, `requirement_name`, `filename`, `date_time`, `requirement_status`, `receiving_staff`) 
										VALUES 
											('','$appId','BI','$destination_path','$date','passed','$staff')")or die(mysql_error());
		}

		// drug test
		$image 	= addslashes($_FILES['drugTest']['name']);
		$extension=end(explode(".", $image));

		$filename = "$appId=$date=drugtest=".date('h-i-s-A').".".$extension;
		$destination_path = "../document/final_requirements/drug_test/$filename";

		if(@move_uploaded_file($_FILES['drugTest']['tmp_name'],$destination_path)) {

			$query = mysql_query("INSERT INTO `application_finalreq`
											(`no`, `app_id`, `requirement_name`, `filename`, `date_time`, `requirement_status`, `receiving_staff`) 
										VALUES 
											('','$appId','DrugTest','$destination_path','$date','passed','$staff')")or die(mysql_error());
		}

		// recommendation Letter
		$image 	= addslashes($_FILES['recommendationLetter']['name']);
		$extension=end(explode(".", $image));

		$filename = "$appId=$date=recommendationletter=".date('h-i-s-A').".".$extension;
		$destination_path = "../document/final_requirements/recommendation_letter/$filename";

		if(@move_uploaded_file($_FILES['recommendationLetter']['tmp_name'],$destination_path)) {

			$query = mysql_query("INSERT INTO `application_finalreq`
											(`no`, `app_id`, `requirement_name`, `filename`, `date_time`, `requirement_status`, `receiving_staff`) 
										VALUES 
											('','$appId','Recommendation','$destination_path','$date','passed','$staff')")or die(mysql_error());
		}

		// marriage Certificate
		if(isset($_FILES['marriageCert']['tmp_name'])) {
			$image 	= addslashes($_FILES['marriageCert']['name']);
			$extension=end(explode(".", $image));

			$filename = "$appId=$date=marriagecertificate=".date('h-i-s-A').".".$extension;
			$destination_path = "../document/final_requirements/marriage_certificate/$filename";

			if(@move_uploaded_file($_FILES['marriageCert']['tmp_name'],$destination_path)) {

				$query = mysql_query("INSERT INTO `application_finalreq`
												(`no`, `app_id`, `requirement_name`, `filename`, `date_time`, `requirement_status`, `receiving_staff`) 
											VALUES 
												('','$appId','MarriageCertificate','$destination_path','$date','passed','$staff')")or die(mysql_error());
			}
		}

		if(isset($_POST['docName']))
		{
	    	$other = count($_FILES['others']['name']);
	    	for($x=0; $x < $other; $x++) {

				$image 	= addslashes($_FILES['others']['name'][$x]);
				$extension=end(explode(".", $image));

				$documentName = $_POST['docName'][$x];
				$filename = "$appId=$date=".$_POST['docName'][$x]."=".date('h-i-s-A').".".$extension;
				$destination_path = "../document/final_requirements/others/$filename";

				if(@move_uploaded_file($_FILES['others']['tmp_name'][$x],$destination_path)) {

					$query = mysql_query("INSERT INTO `application_otherreq`
													(`no`, `app_id`, `requirement_name`, `filename`, `date_time`, `requirement_status`, `receiving_staff`) 
												VALUES 
													('','$appId','$documentName','$destination_path','$date','passed','$staff')")or die(mysql_error());
				}
	    	}
		}

		// blood type
		$update = mysql_query("UPDATE `applicant` SET `bloodtype`='".$_POST['bloodType']."' WHERE `app_id` = '$appId'")or die(mysql_error());

		// remarks for final completion
		$queryRemarks = mysql_query("SELECT `remno` FROM `application_finalreq_remarks` WHERE `app_id` = '$appId'")or die(mysql_error());
		if(mysql_num_rows($queryRemarks) > 0){

			$rw = mysql_fetch_array($queryRemarks);
			$updRemarks = mysql_query("UPDATE `application_finalreq_remarks` SET `date`='$date',`remarks`='".$_POST['remarks']."' WHERE `remno` = '".$rw['remno']."'")or die(mysql_error());
		} else {

			$insertRemarks = mysql_query("INSERT INTO `application_finalreq_remarks`
													(`remno`, `app_id`, `date`, `remarks`) 
												VALUES 
													('','$appId','$date','".$_POST['remarks']."')")or die(mysql_error());
		}

		// philhealth
		if(isset($_POST['philhealthNo'])) {

			$philHealth = $_POST['philhealthNo'];
		} else {

			$philHealth = "";
		}

		// pagibig mid no
		if(isset($_POST['midNo'])) {

			$pagibig = $_POST['midNo'];
		} else {

			$pagibig = "";
		}

		$otherDetails = mysql_query("SELECT * FROM `applicant_otherdetails` WHERE `app_id` = '$appId'")or die(mysql_error());
		if(mysql_num_rows($otherDetails) > 0){

			$updateOther = mysql_query("UPDATE `applicant_otherdetails` 
											SET 
												`cedula_no`='".$_POST['ctcNo']."',`cedula_date`='".$_POST['issudeOn']."',`cedula_place`='".$_POST['issuedAt']."' WHERE `app_id` = '$appId'")or die(mysql_error());
			$insertOther = "true";
		} else {

			$insertOther = mysql_query("INSERT INTO `applicant_otherdetails`
												(`no`, `app_id`, `sss_no`, `card_no`, `cedula_no`, `cedula_date`, `cedula_place`, `recordedby`, `pagibig_tracking`, `pagibig`, `philhealth`, `tin_no`) 
											VALUES 
												('','$appId','".$_POST['sssNo']."','".$_POST['idCardNo']."','".$_POST['ctcNo']."','".$_POST['issudeOn']."','".$_POST['issuedAt']."','$staff','".$_POST['trackingNo']."','$pagibig','$philHealth','')")or die(mysql_error());
		}

			
		// insert to benefits table

		$queryBenefits = mysql_query("SELECT `ben_id` FROM `benefits` WHERE `emp_id` = '$appId'")or die(mysql_error());
		if (mysql_num_rows($queryBenefits) > 0) {
			
			$rows = mysql_fetch_array($queryBenefits);
			$updateBenefits = mysql_query("UPDATE `benefits` SET `philhealth`='$philHealth',`sssno`='".$_POST['sssNo']."',`pagibig`='$pagibig' WHERE `ben_id` = '".$_POST['ben_id']."'")or die(mysql_error());
		} else {

			$insertBenifits = mysql_query("INSERT INTO `benefits`
												(`ben_id`, `emp_id`, `philhealth`, `sssno`, `pagibig`) 
											VALUES 
												('','$appId','$philHealth','".$_POST['sssNo']."','$pagibig')")or die(mysql_error());
		}
		
		// update applicants
		$updateStat = mysql_query("UPDATE `applicants` SET `status`='for orientation' WHERE `app_code` = '$appCode'")or die(mysql_error());
	
		// update application details
		$updateAppDetails = mysql_query("UPDATE `application_details` SET `application_status`='Orientation' WHERE `app_id` = '$appId'")or die(mysql_error());

		// insert application history 
		$insertAppHistory = mysql_query("INSERT INTO `application_history`
													(`no`, `app_id`, `date_time`, `description`, `position`, `phase`, `status`, `soc`, `eoc`) 
												VALUES 
													('','$appId','$date','final requirements checked','$position','Final Completion','completed','','')")or die(mysql_error());

		$result = "Ok";

?>
  <script language="javascript" type="text/javascript">window.top.window.stopUpload("<?php echo $result; ?>");</script>  

<?php                  
    }
?>
	<style type="text/css">
		
		.asterisk {

			color: red;
		}
		.search-results {

	       	box-shadow: 5px 5px 5px #ccc; 
	       	margin-top: 1px; 
	       	margin-left : 1px; 
	       	background-color: #F1F1F1;
	       	width : 58%;
	       	border-radius: 3px 3px 3px 3px;
	       	font-size: 18x;
	       	padding: 8px 10px;
	       	display: block;
	       	position:absolute;
	       	z-index:9999;
	       	max-height:300px;
	       	overflow-y:scroll;
	       	overflow:auto; 
	    }
	</style>

	<div class="panel panel-default">
      	<div class="panel-heading"><span style="font-size:18px;"> FOR FINAL COMPLETION </span></div>
      	<div class="panel-body">

			<table class="table table-striped" width="100%" id="finalReq" style='font-size:11px'>		
				<thead>
					<tr>
						<td><b>APP ID</b></td>
						<td><b>APPLICANT NAME</b></td>
						<td><b>APPLYING FOR</b></td>
						<td><b>DATE APPLIED</b></td>	
						<td align="center"><b>ACTION</b></td> 
					</tr>
				</thead>
			</table>
		</div>
	</div>

<div id = "checkReq" class="modal fade bs-example-modal-lg">
  	<div class="modal-dialog" style="width: 90%; height:auto;">
    	<div class="modal-content">
      		<div class="modal-header alert-info">
	        	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	        	<h4 class="modal-title">Final Requirements</h4>
      		</div>
      		<form method="POST" enctype="multipart/form-data" target="upload_target" onsubmit="startUpload()">
		      	<div class="modal-body">
		      		<div class="checkReq">
		      			
		      		</div>
		      	</div>
		      	<div class="modal-footer">
		        	<span id='f1_upload_process' style="display:none;"><img src = "../images/ajax.gif"> <font size = "2">Please Wait....</font></span>
		        	<input type="submit" name="fileUpload" class="btn btn-primary" value="Submit Requirements">
		        	<button type="button" class="dis_ btn btn-default" data-dismiss="modal">Close</button>
		      	</div>
		      	<iframe id='upload_target' name='upload_target' src='#' style='width:0;height:0;border:0px solid #fff;'></iframe>
	      </form>
    	</div><!-- /.modal-content -->
  	</div><!-- /.modal-dialog -->
</div>

<script type="text/javascript">
	
	function checkReq(appId,appCode) {
		
		$("#checkReq").modal({
            backdrop: 'static',
            keyboard: false
        });

        $("#checkReq").modal("show");

        $.ajax({
			type: "POST",
			url : "functionquery.php?request=checkReq",
			data: { appId:appId, appCode:appCode },
			success : function(data){	
				
				$('.checkReq').html(data);		
			}
		});
	}

	function placeIssued(key){

        $(".search-results").show();

        var str = key.trim();
        $(".search-results").hide();
        if(str == '') {
            $(".search-results-loading").slideUp(100);
        }
        else {
            $.ajax({
                type : "POST",
                url  : "functionquery.php?request=findPlace",
                data : { str : str},
                success : function(data){
                	data = data.trim();
                    if(data != ""){

                        $(".search-results").show().html(data);
                    } else {

                    	$(".search-results").hide();
                    }
                } 
            });
     	}
    }

    function getPlace(address){

        $("[name='issuedAt']").val(address);
        $(".search-results").hide();
    }

    function addRow(){

    	var counter = $("[name = 'counter']").val();

    	$.ajax({
	        type : "POST",
	        url  : "functionquery.php?request=addRow",
	        data : { counter:counter },
	        success : function(data){
	        	data = data.split("&&");
	 
	            // $(".thead_").fadeIn();
	        	$('#myTable').append(data[0]);
	        	$("[name = 'counter']").val(data[1]);
	        }
	    });
    }

    function delRow(counter) {

    	$("#td_"+counter).css({"background-color":"#d3d6ff"});
	  	$("#td_"+counter).fadeOut();
	  	$(".deleted_"+counter).val("deleted");
    }

    function uploadonchange(imgid) {

		var res = validateForm(imgid);	
		if(res ==1){
			$("[name = '"+imgid+"']").val("");
		}
	}

    function validateForm(imgid) {
		// var img = document.getElementById(imgid).value;		
		var img = $("[name = '"+imgid+"']").val();
		var res = '';
		var i = img.length-1;	
		while(img[i] != "."){
			res = img[i] + res;		
			i--;
		}	

		//checks the file format
		if(res != "PNG" && res != "jpg" && res !="JPG" && res != "png" && res !="JPEG" && res !="jpeg"){				
			// document.getElementById('upload_scanned_contract').value = '';
			$("[name = '"+imgid+"']").val("");
			alert('Invalid File Format. Take note on the allowed file!');
			return 1;
		}	
	}

	function startUpload(){

        $("#f1_upload_process").show();
        return true;
    }

    function stopUpload(result){

        result = result.trim();

        if (result == "Ok"){
         
            alert("Final requirements is successfully saved!\nProceed to orientation setup...");
            setTimeout(function(){
            
                window.location = "?p=dashboard&&db=finalCompletion&&q=recruitment";
            },1000);
        } else {

        	alert("There was an error during file upload!");
        }
        
        $("#f1_upload_process").hide();   
        return true;   
    }
</script>
