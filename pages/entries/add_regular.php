<?php 
	if(isset($_POST['submitReg'])){

        $date 		= date('Y-m-d');
		$hrId 		= $_SESSION['emp_id'];
        $empId 		= $_POST['empId'];
        $recordNo 	= $_POST['recordNo'];
        $oldEmpType = $_POST['empType'];
        $regularization = $_POST['regularization'];
        $current_stat 	= strpos(strtoupper($oldEmpType),'REGULAR');

		if ($current_stat !== false){ 			
			$current_status = "Resigned";
		} else {
			$current_status = "End of Contract";
		}

        $emptype 	= $_POST['regularType'];
        $startdate 	= $nq->changeDateFormat('Y-m-d',$_POST['dateRegular']);
        $eocdate  	= date('Y-m-d', strtotime($startdate.'-1 day'));
        $regclass 	= $_POST['regclass'];

        $result 	= 0;
        $image 		= addslashes($_FILES['myfile']['name']);
        $image_ext 	= explode(".", $image);
        $extension 	= end($image_ext);
		$destination_path   = "../document/regularization/".$empId."=".date('Y-m-d')."="."regularization"."=".date('H-i-s-A').".".$extension;	
		
		if(@move_uploaded_file($_FILES['myfile']['tmp_name'], $destination_path)) {	            
			$ins = mysql_query("INSERT INTO application_otherreq 
						(no, app_id, requirement_name, filename, date_time, requirement_status, receiving_staff ) 
					VALUES
						('','$empId','Regularization','$destination_path','$date','passed','$hrId')") or die(mysql_error());
        }

        $query  = mysql_query("SELECT * FROM employee3 WHERE emp_id = '$empId' AND  record_no = '$recordNo'")or die(mysql_error());
        $old_data = mysql_fetch_array($query);

        // insert employee3 data to employmentrecord_

        $employmentrecord_ =  mysql_query(
								"INSERT
									INTO
								 employmentrecord_
									(
										emp_id,
										company_code,
										bunit_code,
										dept_code,
										section_code,
										sub_section_code,
										unit_code,
										emp_no,
										emp_pins,
										barcodeId,
										bioMetricId,
										payroll_no,
										startdate,
										eocdate,
										emp_type,
										reg_class,
										position,
										positionlevel,
										current_status,
										duration,
										lodging,
										job_cat,
										emp_cat,
										pos_desc,
										remarks,
										epas_code,
										contract,
										permit,
										clearance,
										comments,
										date_updated,
										updatedby,			
										pcc
									) VALUES (
										'".$empId."',
										'".$old_data['company_code']."',
										'".$old_data['bunit_code']."',
										'".$old_data['dept_code']."',
										'".$old_data['section_code']."',
										'".$old_data['sub_section_code']."',
										'".$old_data['unit_code']."',
										'".$old_data['emp_no']."',
										'".$old_data['emp_pins']."',
										'".$old_data['barcodeId']."',
										'".$old_data['bioMetricId']."',
										'".$old_data['payroll_no']."',
										'".$old_data['startdate']."',
										'".$eocdate."',
										'".$old_data['emp_type']."',
										'".$old_data['reg_class']."', 
										'".$old_data['position']."',
										'".$old_data['positionlevel']."',
										'".$current_status."',
										'".$old_data['duration']."',
										'".$old_data['lodging']."',
										'".$old_data['job_cat']."',
										'".$old_data['emp_cat']."',
										'".$old_data['position_desc']."',
										'".mysql_real_escape_string($old_data['remarks'])."',
										'".$old_data['epas_code']."',
										'".$old_data['contract']."',
										'".$old_data['permit']."',
										'".$old_data['clearance']."',		
										'".mysql_real_escape_string($old_data['comments'])."',
										'".$old_data['date_updated']."',
										'".$old_data['updated_by']."',	
										'".$old_data['pcc']."'
									)"
								) or die(mysql_error());

		//get record_no from newly inserted employmentrecord_
		$sql = mysql_query(
			"SELECT
				record_no
			  FROM
				employmentrecord_
			  WHERE
				emp_id = '".$empId."'
			  ORDER BY 
				record_no DESC"
		   ) or die(mysql_error());

		$new_rno = mysql_fetch_array($sql);

		// appraisal details
		$sql = mysql_query(
				"SELECT 
					record_no
				 FROM
					appraisal_details
				 WHERE
					record_no = '".$old_data['record_no']."' and emp_id = '".$empId."' "
			   ) or die(mysql_error());
	    $c_appdetails = mysql_num_rows($sql);
		
		//if true updates the appraisal_details to new record_no
		if($c_appdetails > 0){
			mysql_query(
				"UPDATE
					appraisal_details
				 SET
					record_no = '".$new_rno['record_no']."'
				 WHERE
					record_no = '".$old_data['record_no']."' and emp_id = '".$empId."'  "
			) or die(mysql_error());
		}

		// witness
		$sql = mysql_query(
				"SELECT
					rec_no
				 FROM
					employment_witness
				 WHERE
					rec_no = '".$old_data['record_no']."'"
			   ) or die(mysql_error());
		$c_empwitness = mysql_num_rows($sql);
		
		//update the employment_witness if there is a  contract
		if($c_empwitness > 0){
			mysql_query(
			"UPDATE
				employment_witness
			 SET
				rec_no = '".$new_rno['record_no']."'
			 WHERE
				rec_no = '".$old_data['record_no']."'
			") or die(mysql_error());
		}
		$sql = mysql_query(
				"SELECT
					record_no
				 FROM
					tag_clearances
				 WHERE
					record_no = '".$old_data['record_no']."'"
			   ) or die(mysql_error());
		$c_tag = mysql_num_rows($sql);
		
		//tag_clearances
		if($c_tag > 0){
			mysql_query(
				"UPDATE
					tag_clearances
				 SET
					record_no = '".$new_rno['record_no']."'
				 WHERE
					record_no = '".$old_data['record_no']."'"
			) or die(mysql_error());
		}

		//update employee3- startdate, emp_type, reg_class
		$employee3 = mysql_query("UPDATE 
			employee3 SET 
			emp_type  = '$emptype',
			reg_class = '$regclass',
			startdate = '$startdate',
			epas_code = '',
			clearance = '',
			remarks   = '',
			contract  = '',
			permit 	  = '',
			added_by  = '$hrId',
			date_added= '$date',
			updated_by= '',
			date_updated = '',
			comments  = '',
			duration  = '',
			tag_as 	  = '',
			eocdate   = ''
		WHERE emp_id  = '$empId' AND record_no = '$recordNo'
		") or die(mysql_error());

		$result = 0;

		if($employee3 && $employmentrecord_)
		{
			//SAVE LOGS	
			$name 			= $nq->getEmpName($empId);
			$activity 		= "Add New Regular [$regclass]".$name;
			$date 			= date("Y-m-d");
			$time 			= date("H:i:s");	
			$nq->savelogs($activity,$date,$time,$_SESSION['emp_id'],$_SESSION['username']);

			//logs textfile		
			$log  = date('Y-m-d H:i:s')."|$empId - $name|$emptype:$regclass|dateregular:$startdate|HRD incharge=$_SESSION[emp_id] ".$nq->getEmpName($_SESSION['emp_id'])." \r\n";
			$logDir   = "../logs/newregular/"; 
			$filename = "newregular-";		
			$nq->writeLogs($log,$logDir,$filename); 

			// save sil history
			$silHist = mysql_query("INSERT INTO `sil_history`(`sil_no`, `emp_id`, `date_reg`, `reg_class`, `emp_type`, `setup_by`, `setup_date`) 
							VALUES ('','$empId','$startdate','$regclass','$emptype','$_SESSION[emp_id]','$date')")or die(mysql_error());

			$result = 2;	
		} 
		
		if(
			($oldEmpType == "Contractual" || $oldEmpType == "Probationary") && ($emptype == "NESCO Regular" || $emptype == "NESCO Regular Partimer")
			|| ($oldEmpType == "Partimer" || $oldEmpType == "PTA" || $oldEmpType == "PTP") && ($emptype == "NESCO Regular" || $emptype == "NESCO Regular Partimer")
			|| ($oldEmpType == "NESCO" || $oldEmpType == "NESCO Contractual") && ($emptype == "Regular" || $emptype == "Regular Partimer")
			|| ($oldEmpType == "NESCO-PTP" || $oldEmpType == "NESCO-PTA") && ($emptype == "Regular" || $emptype == "Regular Partimer")
			|| ($oldEmpType == "NESCO Regular" || $oldEmpType == "NESCO Regular Partimer") && ($emptype == "Regular" || $emptype == "Regular Partimer")
		  )
		{
			$query = mysql_query("INSERT INTO `ae_nesco_transfer`(`no`, `emp_id`, `empType_from`, `empType_to`) VALUES ('','$empId','$oldEmpType','$emptype')")or die(mysql_error());
		} 

?>
  <script language="javascript" type="text/javascript">window.top.window.stopUpload(<?php echo "$result"; ?>);</script>  

<?php                  
    }
?>
<style type="text/css">
    .search-results{

       box-shadow: 5px 5px 5px #ccc; 
       margin-top: 1px; 
       margin-left : 0px; 
       background-color: #F1F1F1;
       width : 81%;
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

    .btn {

       border-radius: 0px;
    }

</style>
<script type="text/javascript">
	
	function namesearch(key){

        $(".search-results").show();

        var str = key.trim();
        $(".search-results").hide();
        if(str == '') {
            $(".search-results-loading").slideUp(100);
        }
        else {
            $.ajax({
                type : "POST",
                url  : "functionquery.php?request=findEmpType",
                data : { str : str},
                success : function(data){
                    if(data){
                        $(".search-results").show().html(data);
                    }
                } 
            });
        }
    }

    function getEmpId(id){
    	var id = id.split("*");
        var empId = id[0].trim();
        var recordNo = id[1].trim();
        var name = id[2].trim();
        var empType = id[3].trim();

        $("[name='app_id']").val(empId+" * "+name);
        $(".search-results").hide();

        $.ajax({
            type : "POST",
            url  : "functionquery.php?request=isRegular",
            data : { empType : empType},
            success : function(data){
            	data = data.trim();
                if(data){
                	if(data == "true"){

				        $.ajax({
				            type : "POST",
				            url  : "functionquery.php?request=viewRegular",
				            data : { empId:empId, recordNo:recordNo, empType:empType },
				            success : function(data){
				            
				                $(".regularization").html(data);  
				                $(".regularization").show();
				                $("#footer").show();
				            }
				        });
                	} else {

                		$.ajax({
				            type : "POST",
				            url  : "functionquery.php?request=viewCasual",
				            data : { empId:empId, recordNo:recordNo, empType:empType },
				            success : function(data){

				                $(".regularization").html(data);
				                $(".regularization").show();
				                $("#footer").show();

				            }
				        });
                		 
                	}
                }
            } 
        });
    }

    function startUpload(){

        $("#f1_upload_process").show();
        return true;
    }

    function stopUpload(success){
        if (success == 2){
         
            // succSave("Successfully Save!");
            alert("Successfully Save!");
            setTimeout(function(){
            
                window.location = "?p=toregular&&db=entries";
            },1000);
        }
        
        
        if(success == 0) {
            // errDup("There was an error during saving process!");
            alert("There was an error during saving process!");
        }
        
        $("#f1_upload_process").hide();   
        return true;   
    }

    function uploadonchange(imgid){ 

        var res = validateForm(imgid);  
        if(res ==1){
            document.getElementById(imgid).value = '';
        }
    }

    function validateForm(imgid){

        var img = document.getElementById(imgid).value; 
        var res = '';
        var i = img.length-1; 
        while(img[i] != "."){
            res = img[i] + res;   
            i--;
        } 
        //checks the file format
        if(res != "PNG" && res != "jpg" && res !="JPG" && res != "png" && res != "jpeg" && res != "JPEG"){        
            document.getElementById(imgid).value = '';
            // errDup('Invalid File Format. Take note on the allowed file!');
            alert("Invalid File Format. Take note on the allowed file!");
         return 1;
        } 
        //checks the filesize- should not be greater than 2MB
        var uploadedFile = document.getElementById(imgid);
        var fileSize = uploadedFile.files[0].size < 1024 * 1024 * 2;
        if(fileSize == false){
            document.getElementById(imgid).value = '';
            // errDup('The size of the file exceeds 2MB!')
            alert("The size of the file exceeds 2MB!");
            return 1;
        } 
    }

    function back(){
    	window.location = "?p=home";
    }

    function ifRequired(oldEmptype,name){
    	
    	var newEmptype = $("[name = '"+name+"']").val();    	
    	if(newEmptype == "Regular" || newEmptype == "Regular Partimer"){    		
    		$("#imgid").prop("required", true);
    	} else {
    		$("#imgid").prop("required", false);
    	}
    }
</script>
<div class='container-fluid'>
	<div class="col-md-12">
		<div class='row'>
			<div class='panel panel-default'>
				<div class="panel-heading"> 
					<span style="font-size:24px;">Employee for Regularization</span>						
				</div>	
				<form method = 'POST' enctype='multipart/form-data' target='upload_target' onsubmit='startUpload();'>
					<div class='panel-body'>

							<div class="form-group">
						      	<div class="input-group">
						          	<input type="text" name="app_id" onkeyup="namesearch(this.value)" class="form-control" placeholder="Search Employee (Emp. ID, Lastname or Firstname)" value="" autocomplete="off" required="">
						          	<span class="input-group-btn">
						            	<button class="btn btn-info" name="search" >Search &nbsp;<i class="glyphicon glyphicon-search"></i></button>
						          	</span>
						      	</div>
						      	<div class="search-results" style="display:none;"></div>
						    </div>

						    <div class = "regularization" style="display:none;"></div>
					</div>
					<div class="panel-footer" id="footer" style="display:none;">
						<div class="row">
							<div class='col-md-5 col-md-offset-7' style="text-align:right;">
								
								<span id='f1_upload_process' style="display:none;"><img src = "../images/ajax.gif"> <font size = "2">Please Wait....</font></span>
	            				<input type="submit" name="submitReg" class="btn btn-primary" value=" Submit ">
						        <button type="button" class="btn btn-default" onclick="back()"> Back </button>
						    </div>
					    </div>
					</div>
					<iframe id='upload_target' name='upload_target' src='#' style='width:0;height:0;border:0px solid #fff;' style="display:none;"></iframe>
				</form>
			</div>
		</div>
	</div>
</div>