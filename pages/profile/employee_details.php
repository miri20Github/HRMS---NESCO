<?php

	include("header.php");
	$empid = $_GET['com']; 
	$date = date("Y-m-d");
	$time = date('H:i:s');


	//check if employed		
	$checkifemployed = $nq->getOneField('emp_id','employee3',"emp_id = '$empid' limit 1 ");		
		
	//changing of photo/image profile of the employee
	if(!isset($_FILES['upload_scanned_photo']['tmp_name'])) {

		echo "";
	} else {	

		$image= addslashes(file_get_contents($_FILES['upload_scanned_photo']['tmp_name']));
		$image_name= addslashes($_FILES['upload_scanned_photo']['name']);	
		$extension = end(explode(".",$_FILES["upload_scanned_photo"]["name"]));
		$fimage = "../images/users/".$empid."=".date('Y-m-d')."="."Profile"."=".date('H-i-s-A').".".$extension; 

		move_uploaded_file($_FILES["upload_scanned_photo"]["tmp_name"],$fimage);			
			$save = mysql_query("UPDATE applicant SET photo = '$fimage' where app_id = '$empid'")or die(mysql_error());
			$nn = utf8_decode($nq->getAppName($empid));
			if($save){

				$result = "2||$empid";
				//inserting to logs			
				$nq->savelogs("Updated the Photo of ".$nn,$date,$time,$_SESSION['emp_id'],$_SESSION['username']);	
			}
		?>
		  <script language="javascript" type="text/javascript">window.top.window.stopUploadPhoto("<?php echo $result; ?>");</script>  

		<?php 
	}

	if(isset($_POST['liveBirthUpload'])){

	        $counter = $_POST['imgCounter'];
	        $name 	= $_POST['childName'];
	        $classify = $_POST['classify'];

	        $result = 0;
	        $image = addslashes($_FILES['upload_scanned_NSO']['name']);
	        $image_ext = explode(".", $image);
	        $extension = end($image_ext);

	        // filename : Clearance=emp_id=record_no=date(Y-m-d H-i-s-A)
	        $filename = "liveBirth=$counter=hrId=".$_SESSION['emp_id']."=".date("Y-m-d H-i-s-A").".$extension";
	        $destination_path = "../document/live_birth/$filename";

	        if(@move_uploaded_file($_FILES['upload_scanned_NSO']['tmp_name'], utf8_decode($destination_path))){

	            $result = "2|$counter|$destination_path|$classify";
	        }
	      
	?>
	  <script language="javascript" type="text/javascript">window.top.window.stopUploadLiveBirth("<?php echo $result; ?>");</script>  

	<?php                  
	    }

	if(@$_FILES['file_upload']['name'])
	{
		
		$cat = array(
			"",
			"Application Letter",
			"BI",
			"BirthCertificate",
			"Cedula",
			"Fingerprint",
			"MarriageCertificate",
			"MedicalCertificate",
			"OrientationCertificate",
			"ParentsConsent",
			"PoliceClearance",
			"Recommendation",
			"Resume",
			"Sketch",
			"SSS",
			"Transcript of Records",
			"Misconduct",
			"Suspension",
			"Showcause",
			"Others",
			"Drug Test",
			"Regularization",
			"Job Transfers",
			"Promotion"
		);
		$dir = array(
			"",
			"application_letter",
			"bi",
			"birth_certificate",
			"cedula",
			"fingerprint",
			"marriage_certificate",
			"medical_certificate",
			"orientation",
			"parent_consent",
			"police_clearance",
			"recommendation_letter",
			"resume",
			"sketch",
			"sss",
			"tor",
			"misconduct",
			"suspension",
			"showcause",
			"others",
			"drug_test",
			"regularization",
			"job_transfer",
			"promotion"
		);	
		$cate = $_POST['cat'];
		$dir_initial = "../document/initial_requirements/";
		$dir_final = "../document/final_requirements/";
		$dir_violation = "../document/violation/";
		$dir_reg = "../document/";
		$file = $_FILES['file_upload']['name'];
		$table = "";
		if($cate == 1 || $cate == 15 || $cate == 12){
			$path = $dir_initial.$dir[$cate]."/";
			$table = "application_initialreq";
			$code = "app_code";
		} elseif($cate == 16 || $cate == 17 || $cate == 18) {
			$path = $dir_violation.$dir[$cate]."/";
			$table = "applicant_violation";
			$code = "app_id";
		} elseif($cate == 19) {
			$path = $dir_final.$dir[$cate]."/";
			$table = "application_otherreq";
			$code = "app_id";
		} elseif($cate == 21 || $cate == 22) {
			$path = $dir_reg.$dir[$cate]."/";
			$table = "application_otherreq";
			$code = "app_id";
		}elseif($cate == 23){
			$path = $dir_reg.$dir[$cate]."/";
			$table = "application_otherreq";
			$code = "app_id";
		}else {
			$path = $dir_final.$dir[$cate]."/";
			$table = "application_finalreq";
			$code = "app_id";
		}
		
		for($x=0;$x<count($file);$x++) :
			@$sql = mysql_query(
				"SELECT
					$code
				 FROM
					$table
				 WHERE
					$code = '".$_POST['empid']."'
				 AND
					requirement_name = '".$cat[$cate]."'"
			   ) or die(mysql_error());
		    $c = mysql_num_rows(@$sql)+1;
			$extension[$x] = explode(".",$file[$x]);
			$new_name[$x] = $path.$_POST['empid']."=".$c."=".date('Y-m-d')."=".$dir[$cate]."=".date('H-i-s-A').".".$extension[$x][1];
			if(move_uploaded_file($_FILES["file_upload"]["tmp_name"][$x],$new_name[$x])):
				mysql_query(
					"INSERT
						INTO
					 $table
						(
							$code,
							requirement_name,
							filename,
							date_time,
							requirement_status,
							receiving_staff
						) VALUES
						(
							'".$_POST['empid']."',
							'".$cat[$cate]."',
							'".$new_name[$x]."',
							'".date('Y-m-d')."',
							'passed',
							'".$_SESSION['emp_id']."'
						)"
				) or die(mysql_error());
				//inserting to logs			
				$nq->savelogs("Uploaded the 201 file [ ".$cat[$cate]." ] of ".$nq->getAppName($_POST['empid']),$date,$time,$_SESSION['emp_id'],$_SESSION['username']);
				$result = 1;
			else:
				die("Error");
			endif;		
		endfor;
		?>
			<script language="javascript" type="text/javascript">window.top.window.stopUpload('<?php echo $result; ?>','<?php echo $_POST['empid'];?>','<?php echo $_POST['input'];?>');</script> 
		<?php
	}
	//saving the scanned contract 
	if(!empty($_FILES['upload_scanned_contract']['name']) || !empty($_FILES['upload_scanned_clearance']['name']) || !empty($_FILES['upload_scanned_epas']['name']))
	{ 
		$rec		= $_POST['contract_record_no'];
		$table 		= $_POST['contract_table'];	
		$date 		= date("Y-m-d");
		$time 		= date('H:i:s');
		$uploadedby = preg_replace('/  */', '_',$nq->getAppName($_SESSION['emp_id']));	
		$empname 	= preg_replace('/  */', '_',$nq->getEmpName($nq->getOneField('emp_id',$table,"record_no='$rec'")));
		
		if(!empty($_FILES['upload_scanned_contract']['name']))
		{	
			$image1		= addslashes(file_get_contents($_FILES['upload_scanned_contract']['tmp_name']));
			$image_name1= addslashes($_FILES['upload_scanned_contract']['name']);	
			$array1 	= explode(".",$_FILES["upload_scanned_contract"]["name"]);	
			
			//filename contains --- contract_record_no_name_sa_employee_name_sa_ga_upload	
			$filename1 	= "contract_recordno.".$_POST['contract_record_no']."_of_".$empname."_uploadedby_".$uploadedby.".".$array1[1];
			$fimage1 	= "../document/contract/".$filename1; 
			
			if(move_uploaded_file($_FILES["upload_scanned_contract"]["tmp_name"],$fimage1)){
				$save1= mysql_query("UPDATE $table SET contract = '".$fimage1."' where record_no = '".$rec."' ");
			}			
			if($save1){	$contract_flag = 'true'; }	
		}		
		if(!empty($_FILES['upload_scanned_clearance']['name']))
		{
			$image2		= addslashes(file_get_contents($_FILES['upload_scanned_clearance']['tmp_name']));
			$image_name2= addslashes($_FILES['upload_scanned_clearance']['name']);	
			$array2		= explode(".",$_FILES["upload_scanned_clearance"]["name"]);
			
			//filename contains --- contract_record_no_name_sa_employee_name_sa_ga_upload	
			$filename2 	= $_POST['empid']."=".date('Y-m-d')."="."Clearance"."=".date('H-i-s-A').".".$array2[1];		
			$fimage2 	= "../document/clearance/".$filename2; 
			if(move_uploaded_file($_FILES["upload_scanned_clearance"]["tmp_name"],$fimage2)){
				$save2= mysql_query("UPDATE $table SET clearance = '".$fimage2."' where record_no = '".$rec."' ");			
			}
			if($save2) {  $clearance_flag = 'true';	}			
		}		
		if(!empty($_FILES['upload_scanned_epas']['name']))
		{		
			$image3		= addslashes(file_get_contents($_FILES['upload_scanned_epas']['tmp_name']));
			$image_name3= addslashes($_FILES['upload_scanned_epas']['name']);	
			$array3		= explode(".",$_FILES["upload_scanned_epas"]["name"]);
			
			//filename contains --- contract_record_no_name_sa_employee_name_sa_ga_upload	
			$filename3 	= "epas_recordno.".$_POST['contract_record_no']."_of_".$empname."_uploadedby_".$uploadedby.".".$array3[1];
			$fimage3 	= "../document/epas/".$filename3; 		
			?>
			<script> alert('<?php echo $_FILES["upload_scanned_epas"]["tmp_name"];?>'); </script>
			<?php
			
			if(move_uploaded_file($_FILES["upload_scanned_epas"]["tmp_name"],$fimage3)){
				$save3= mysql_query("UPDATE $table SET epas_code = '".$fimage3."' where record_no = '".$rec."' ");
			}		
			if($save3){ $epas_flag = 'true'; }
			
		}	
				
		if($contract_flag == 'true' && $clearance_flag == 'true' && $epas_flag == 'true')
		{ 
			$activity 		= "Uploaded the scanned Contract, Clearance and EPAS of ".@$empname." Record No.".$rec;
			$nq->savelogs($activity,$date,$time,$_SESSION['emp_id'],$_SESSION['username']);	?>
			<script> alert('Contract, Clearance and Scanned EPAS are Successfully Uploaded!'); </script><?php
		}
		else if($contract_flag == 'true' && $clearance_flag == 'true')
		{ 
			$activity 		= "Uploaded the scanned Contract and Clearance of ".@$empname." Record No.".$rec;
			$nq->savelogs($activity,$date,$time,$_SESSION['emp_id'],$_SESSION['username']); ?>
			<script> alert('Contract and Clearance are Successfully Uploaded!'); </script><?php
		}
		else if($contract_flag == 'true' && $epas_flag == 'true')
		{
			$activity 		= "Uploaded the scanned Contract and EPAS of ".@$empname." Record No.".$rec;
			$nq->savelogs($activity,$date,$time,$_SESSION['emp_id'],$_SESSION['username']); ?>
			<script> alert('Contract and Scanned EPAS are Successfully Uploaded!'); </script><?php
		}
		else if($epas_flag == 'true' && $clearance_flag == 'true')
		{
			$activity 		= "Uploaded the scanned Clearance and EPAS of ".@$empname." Record No.".$rec;
			$nq->savelogs($activity,$date,$time,$_SESSION['emp_id'],$_SESSION['username']); ?>
			<script> alert('Clearance and Scanned EPAS are Successfully Uploaded!'); </script><?php
		}
		else if($contract_flag == 'true')
		{
			$activity 		= "Uploaded the scanned Contract of ".@$empname." Record No.".$rec;
			$nq->savelogs($activity,$date,$time,$_SESSION['emp_id'],$_SESSION['username']); ?>
			<script> alert('Contract Successfully Uploaded!'); </script><?php
		}
		else if($clearance_flag == 'true')
		{
			$activity 		= "Uploaded the scanned Clearance (override) ".@$empname." Record No.".$rec;
			$nq->savelogs($activity,$date,$time,$_SESSION['emp_id'],$_SESSION['username']); ?>
			<script> alert('Clearance Successfully Uploaded!'); </script><?php
		}
		else if($epas_flag == 'true')
		{
			$activity 		= "Uploaded the scanned EPAS of ".@$empname." Record No.".$rec;
			$nq->savelogs($activity,$date,$time,$_SESSION['emp_id'],$_SESSION['username']); ?>
			<script> alert('Scanned EPAS Successfully Uploaded!'); </script><?php
		}
	}

	$query = mysql_query("SELECT photo, firstname, lastname,middlename, birthdate, suffix, home_address FROM applicant where app_id = '$empid' limit 1");				  		
		while($row = mysql_fetch_array($query))
			{
				//basic information	
				$photo 		= @$row['photo'];	
				if($photo == ''){
					$photo = '../images/users/icon-user-default.png';
				}
				$lastname 	= @$row['lastname'];
				$firstname  = @$row['firstname'];

				if($row['suffix']){
					$name = $row['lastname'].", ".$row['firstname']." ".$row['suffix'];
				}else{
					$name = $row['lastname'].", ".$row['firstname'];
				}

				date_default_timezone_set('Asia/Manila');
			    function getAge( $dob, $tdate )
			    {
			        $age = 0;
			        while( $tdate >= $dob = strtotime('+1 year', $dob)){
			                ++$age;
			        }return $age;
			    }
			    $datebirth = $row['birthdate'];/*** a date before 1970 ***/		
				if($row['birthdate'] == '0000-00-00' || $row['birthdate'] == '1900-01-01' || $row['birthdate'] == '')
				{
					$age = '';
					$msgbd = '';
				}
				else
				{
					$dob = strtotime($datebirth);		
					$now = date('Y-m-d');/*** another date ***/		
					$tdate = strtotime($now);/*** show the date ***/		
					$age= getAge( $dob, $tdate );
						 
					if($datebirth !=""){ $age =  $age.' years old'; } 
					$md = explode('-',$datebirth);
					if($md[1]."-".$md[2] == date('m-d')){ $msgbd = "<img src='../images/system/bday.gif' width='110' height='100' style='float:right; margin-top: -100px;'>"; } else { $msgbd = "";}
				}
			}	

	$query1 = mysql_query("SELECT position, sub_status, company_code, bunit_code, dept_code,section_code, current_status, emp_type,startdate,eocdate FROM employee3 where emp_id = '$empid' ");	
		while($row1 = mysql_fetch_array($query1))
			{
				//employee
				$position   = @$row1['position'];
				$designation= @$nq->getCompanyAcroname($row1['company_code'])."<br>".@$nq->getBusinessUnitName($row1['bunit_code'],$row1['company_code'])."<br>".@$nq->getDepartmentName($row1['dept_code'],$row1['bunit_code'],$row1['company_code'])."<br>".@$nq->getSectionName($row1['section_code'],$row1['dept_code'],$row1['bunit_code'],$row1['company_code']);
				$company 	= $nq->getCompanyAcroname($row1['company_code']);
				$businessUnit = $nq->getBusinessUnitName($row1['bunit_code'],$row1['company_code']);
				$department = $nq->getDepartmentName($row1['dept_code'],$row1['bunit_code'],$row1['company_code']);
				$section 	= $nq->getSectionName($row1['section_code'],$row1['dept_code'],$row1['bunit_code'],$row1['company_code']);
				$homeadd 	= @$row1['home_address'];
				$emptype 	= @$row1['emp_type'];
				$status 	= @$row1['current_status'];

				if($row1['sub_status'] == ""){ $substatus = ""; } else { $substatus = "(".$row1['sub_status'].")"; }
				
				if($row1['startdate'] == '' || $row1['startdate'] =="0000-00-00" ){ $sd = ""; } else { $sd = $nq->changeDateFormat('m/d/Y',$row1['startdate']); }	
				if($row1['eocdate'] == '' || $row1['eocdate'] =="0000-00-00" ){ $ed = ""; } else { $ed = $nq->changeDateFormat('m/d/Y',$row1['eocdate']); }
				
				
					if($status == "active" || $status == "Active")
					{
						$newstatus = "<h4><span class='label label-success'>".$status ." ".$substatus."</span></h4>";
					}
					else if($status == "resigned" || $status == "deleted" || $status == "blacklisted")
					{
						$newstatus = "<h4><span class='label label-danger'>".$status." ". $substatus."</span></h4>";
					}	 
					else
					{  
						$newstatus = "<h4><span class='label label-warning'>".$status." ".$substatus."</span></h4>";
					}				
			}   

	if(mysql_num_rows($query1) ==0 )
		{
			$checkifblacklisted = mysql_query("SELECT app_id FROM `blacklist` WHERE app_id = '$empid'");
			if(mysql_num_rows($checkifblacklisted) > 0){
				$newstatus = "<h4><span class='label label-danger'>blacklisted</span></h4>";
			} else {	
				$newstatus = "<h4><span class='label label-primary'>".$nq->getApplicantStatus($empid)."</span></h4>";
			}
		}
	  		
	$userlogin = mysql_query("SELECT login from users where emp_id = '$empid' and ( usertype='employee' or usertype='supervisor' )");
	$ru = mysql_fetch_array($userlogin);
	$userstat = $ru['login'];
		if($userstat == "yes"){

			$usericon = "../images/icons/user_active.png";
			$usertitle = "online";
		} else {

			$usericon = "../images/icons/user_inactive.png";
			$usertitle = "offline";
		}

	//11/12/16 for SIL display
	$q_regclass = mysql_query("SELECT display FROM reg_class INNER JOIN employee3 ON reg_class.reg_class = employee3.reg_class WHERE emp_id = '$empid'") or die(mysql_error());
		$r_regclass = mysql_fetch_array($q_regclass);
		if($r_regclass['display']!=''){
			
			$regclass = "<span style='color:green'>(".$r_regclass['display'].")</span>";
		} else {

			$regclass = '';
		}

	if(@$emptype =='NESCO Regular' || @$emptype == "NESCO Regular Partimer"){
		$c_date = @$sd;					
	} else {
		$c_date = @$sd." - ".@$ed;
	}	

	$dhss = $nq->getOneField('date_hired','application_details',"app_id='$empid' "); 
	if($dhss == '0000-00-00'){ 
		$ddhs = '';
	} else if($dhss == ''){ 
		$ddhs = '';
	} else { 
		$ddhs = $nq->changeDateFormat('m/d/Y',$dhss); 
	}

	$getEligibility = mysql_query("SELECT * FROM `application_seminarsandeligibility` WHERE app_id = '$empid' and
							(name like '%CPA%' or name like '%Certified Public Accountant%' 
							or name like '%Nursing Licensure Examination%' or name like '%Registered Nurse%' or name like '%Passed the Philippine Nursing Licensure Examination%' or name like '%Mechanical Engineer%') ") or die(mysql_error());
		$regb = mysql_fetch_array($getEligibility);

			if($regb['name']=='CPA' or $regb['name'] =='Certified Public Accountant'){
				
				$eligibility ="<span style='color:green'>Certified Public Accountant (CPA)</span><br>";
			} else if($regb['name']=='Registered Nurse' or $regb['name'] =='Nursing Licensure Examination' or $regb['name'] =='Passed the Philippine Nursing Licensure Examination'){
				
				$eligibility ="<span style='color:green'>Registered Nurse(RN)</span><br>";
			} else if($regb['name'] == "Mechanical Engineering Licensure Examination Board Passer" || $regb['name'] == "Licensed Mechanical Engineer"){
				
				$eligibility = "<span style='color:green'>Licensed Mechanical Engineer</span><br>";
			} else {

				$eligibility = '';
			} 

?>
<html>
	<head></head>
	<style> 
		.modf { float:right;margin-top:-55px;font-size:20px}
		.img {width:90px;height:100px;}
		.tff { height: 30px ; width: 100%}
		.preview {
			background-image: url("../images/unknown.png");
			background-size:contain;
			width:300px;
			height:370px;
			border:2px solid #BBD9EE
		}

		.preview_NSO {
			background-image: url("../images/unknown.png");
			background-size:contain;
			width:568px;
			height:319px;
			border:2px solid #BBD9EE
		} 

		.preview_photo {
	        background-image: url("../images/unknown.png");
	        background-size:contain;
	        width:289px;
	        height:301px;
	        border:2px solid #BBD9EE
	    } 
		/*newly added*/
		.setup { font-family:calibri; } 
		.docs{width:100px;height:100px;}
		.temp_img:hover { cursor:pointer }
		.sm { font-size: 12px; }
		/*.masked { position:fixed; top:0; left:0; background-color: #000; width:100%; height:100%; z-index:1; filter:apha(opacity=50); opacity:0.4; cursor:not-allowed; display:none; }*/
		/*.upload_modal { position:absolute; left:50%; margin-left:-300px; width:600px; height: auto; border:1px solid #CCC; background-color:#FFF; z-index:2; top:0; margin-top:50px; display:none; box-shadow: 0px 5px 10px #20202F; border-radius: 8px; padding:8px; }*/
		/*.upload_modal-title { padding-bottom:8px; border-bottom:1px solid #ccc; }*/
		/*.upload_modal-content { padding:50px; }*/
		#upload_process { display:none; }
	</style>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-3">
				
				<center>
		  			<img src="<?php echo $photo;?>" width="200" height="200" id="Image" style='border:1px solid #ccc;border-radius:0px' onclick="editImage('<?php echo $empid ;?>')">
		  			<h4><img src='<?php echo $usericon;?>' title='<?php echo $usertitle;?>' data-toggle='tooltip' data-placement='top'> <?php echo utf8_encode($name);?></h4>
					<p>
						<button onclick="editImage('<?php echo $empid ;?>')" class='btn btn-success  btn-sm'> Change Photo</button>
						<a href='../administrator/personnel_info_pdf.php?app_id=<?php echo $empid;?>' target='_blank'  class='btn btn-success  btn-sm'> View Full Profile </a>
						<input type="hidden" name='empidD' value="<?php echo $empid."*".$name;?>">
					</p>
				</center>
			</div>
			<div class="col-md-9">
				
				<div class="row">
					<div class="col-md-5" style="width:100%; height:285px; border: 0px #ccc solid;background:white;float:right ">
						<br>
						<table class="table table-bordered" style="border: 0px;">
							<tbody>
								<tr>
									<td width="15%" style="border: 0px; padding:2px; font-size: 14px;"><label>HRMS ID</label></td>
									<td width="25%" style="border: 0px; padding:2px; font-size: 14px;">: <?php echo $empid; ?></td>
									<td width="15%" style="border: 0px; padding:2px; font-size: 14px;"><label>Company</label></td>
									<td width="45%" style="border: 0px; padding:2px; font-size: 14px;">: <?php echo @$company; ?></td>
								</tr>
								<tr>
									<td style="border: 0px;  padding:2px; font-size: 14px;"><label>Age</label></td>
									<td style="border: 0px;  padding:2px; font-size: 14px;">: <span><?php if($datebirth == '' ||  $datebirth == '0000-00-00'){ echo ''; } else { echo "$age"; } ?></span></td>
									<td style="border: 0px;  padding:2px; font-size: 14px;"><label>Business Unit</label></td>
									<td style="border: 0px;  padding:2px; font-size: 14px;">: <?php echo @$businessUnit; ?></td>
								</tr>
								<tr>
									<td style="border: 0px; padding:2px; font-size: 14px;"><label>Position</label></td>
									<td style="border: 0px; padding:2px; font-size: 14px;">: <?php echo @$position; ?></td>
									<td style="border: 0px; padding:2px; font-size: 14px;"><label>Department</label></td>
									<td style="border: 0px; padding:2px; font-size: 14px;">: <?php echo @$department; ?></td>
								</tr>
								<tr>
									<td style="border: 0px; padding:2px; font-size: 14px;"><label>Date Hired</label></td>
									<td style="border: 0px; padding:2px; font-size: 14px;">: <?php echo @$ddhs; ?></td>
									<td style="border: 0px; padding:2px; font-size: 14px;"><label>Section</label></td>
									<td style="border: 0px; padding:2px; font-size: 14px;">: <?php echo @$section; ?></td>
								</tr>	
								<tr>
									<td style="border: 0px;  padding:2px; font-size: 14px;"><label><?php if(@$emptype =='NESCO Regular' || @$emptype == "NESCO Regular Partimer"){ echo "Date Regular"; } else  { echo "Start - EOC"; } ?></label></td>
									<td style="border: 0px;  padding:2px; font-size: 14px;" colspan="3">: <?php echo@$c_date; ?></td>
								</tr>
								<tr>
									<td style="border: 0px;  padding:2px; font-size: 14px;"><label>Emp. Type</label></td>
									<td style="border: 0px;  padding:2px; font-size: 14px;"  colspan="3">: <?php echo @$emptype." $regclass"; ?></td>
								</tr>
								<?php
									if(!empty($eligibility)){
										echo "<tr>
											<td style='border: 0px;  padding:2px; font-size: 14px;''><label>Eligibility</label></td>
											<td style='border: 0px;  padding:2px; font-size: 14px;'' colspan='3'>: $eligibility</td>
										</tr>";
									}
								?>
							</tbody>
						</table>
						<?php echo @$newstatus;?><?php echo $msgbd; 
							if(!empty($msgbd)){
						?>
						<i style="color:red; float:right; font-size:14px;"><label>.. has a birthday today!!!</label></i>
						<?php } ?>
					</div>
					<div class="col-md-7">
						
					</div>
				</div>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-md-12" style="border: 1px #ccc solid;background:white;float:right ">
			<br>
				<div class="row">
					<div class="col-md-6">		  	
						<select class="form-control" style="width:250px" id='classify' onchange="getdefault(this.value)">				  		
					  		<option value="basicinfo">Basic Information</option>
					  		<option value="family">Family Background</option>
					  		<option value="contact">Contact & Address Information</option>
					  		<option value="educ">Educational Background</option>
					  		<option value="seminar">Eligibility/Seminars/Trainings</option>
					  		<option value="charref">Character References</option>
					  		<option value="skills">Skills and Competencies</option>
					  		<option value="eocapp">EOC Appraisal</option>
					  		<option value="application">Application History</option>
							<option value='employment'>Contract History</option>
					  		<option value="history">Employment History</option>
							<option value="transfer">Job Transfer History</option>
					  		<option value="blacklist">Blacklist History</option>
					  		<option value="benefits">Benefits</option>
					  		<option value="201doc">201 Documents</option>
							<option value="pss">Peer-Subordinate-Supervisor</option>
					  		<option value="remarks">Remarks</option>
							<option value="useraccount">User Account</option>
					  	</select>
					</div>
					
					<?php
					if(@$status != "blacklisted"){?>
					
					<div class="col-md-6" style="text-align:right">
					  	<button type="button" class="btn btn-primary" data-toggle='modal' data-target='#contact_form' onclick="show_contract()" id="add_c" style="display:none;">Add</button>
					</div>
					<?php } ?>
				</div>
				<br>
				  	<div id='details'> <!-- do not delete please-->
				  		
				  	</div>

				  	<div id='add-edit-seminar'></div>	<!-- do not delete please-->			  	
				  	<div id='add-edit-charref'></div>  <!-- do not delete please-->
				  	<div id='add-edit-emphis'></div>  <!-- do not delete please-->
			</div>
		</div>
	</div>

<!-- upload scanned live birth -->
<div id = "upload_nso" class="modal fade bs-example-modal-md">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header alert-info">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Upload Scanned Birth Certificate</h4>
      </div>
      <form method = 'POST' enctype='multipart/form-data' target='upload_target' onsubmit='startUploadLiveBirth();'>
          
          <input type="hidden" name="imgCounter">
          <input type="hidden" name="childName">
          <input type="hidden" name="classify">

          <div class="modal-body">
              <div class = "cleranceReq">
                  <div class="form-group">
						<img id='preview_NSO' class='preview_NSO img-responsive'/><br>
						<input type='file' name='upload_scanned_NSO' id='upload_scanned_NSO' class='btn btn-default' onchange='readNSO(this);'>
						<input type='button' name='clearNSO' id='clearNSO' style='display:none; margin-top:10px;' class='btn btn-danger' value='Clear'  onclick=clears('upload_scanned_NSO','preview_NSO','clearNSO')>
						<input type='button' id='upload_scanned_NSO_change' style='display:none;' class='btn btn-primary btn-sm' value='Change Birth Certificate?' onclick='changeNSO()'>
                      <p class="help-block"> Allowed File : jpg, jpeg, png only </p>
                  </div>
              </div>
          </div>

          <div class="modal-footer">
            <span id='f1_upload_process' style="display:none;"><img src = "../images/ajax.gif"> <font size = "2">Please Wait...</font></span>
            <input type="submit" name="liveBirthUpload" class="btn btn-primary" value=" Submit "> 
            <button type="button" class="dis_ btn btn-default " data-dismiss="modal">Close</button>
         
          </div>

          <iframe id='upload_target' name='upload_target' src='#' style='width:0;height:0;border:0px solid #fff;'></iframe>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>

<!-- upload scanned live birth -->
<div id = "view_nso" class="modal fade bs-example-modal-md">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header alert-info">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">View Scanned Birth Certificate</h4>
      </div>
      
          <div class="modal-body">
               <img id='view_NSO' class='preview_NSO img-responsive'/>
          </div>

          <div class="modal-footer"> 
            <button type="button" class="dis_ btn btn-default " data-dismiss="modal">Close</button>
          </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>

<!-- end here -->
	
<div class="setup">
	<div class="modal fade" id="view201files">
		<div class="modal-dialog" style='width:70%'>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" title="close" data-dismiss="modal" aria-hidden="true">x</button>
					<h4 class="modal-title" id="201Files_title"></h4>
				</div>
				<div class="modal-body" id="201FilesData">
					<img src="../images/loading19.gif"> please wait...
				</div>
				<div class="modal-footer"></div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
<!-- <div class="masked"></div> -->

<div id = "upload_modal" class="modal fade bs-example-modal-md">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header alert-info">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="closeUploadModal()">&times;</button>
        <h4 class="modal-title">Upload 201 Files</h4>
      </div>
	<form method="POST" enctype="multipart/form-data" target = "upload_target" onsubmit="startUpload201Files();"/>
      <div class="modal-body">
        <div class="upload_modal-content">
					 <div id="upload_process"><center><img src="images/upload.gif"></center></div>
					 <div id="upload_form">
						<input type="hidden" name="empid">
						<input type="hidden" name="input">
						<div class="form-group">
							<label>201 Files Name</label>
							<select name="cat" class="form-control" required>
										<option value="">select</option>
										<?php
											$qu = mysql_query("SELECT * FROM 201files order by 201_name");
											while($rq = mysql_fetch_array($qu)){
												if($rq['201_id']!= 24){									
													echo "<option value='".$rq['201_id']."'>".$rq['201_name']."</option>";
												}
										}?>								
							</select>
						</div>
						<div class="form-group">
							<label>201 Files</label>
							<input type="file" id="file_upload" class="btn btn-default" name="file_upload[]" multiple required onchange='uploadonchange(this.id)'>
							<p class="help-block" style="color:red;"> Allowed File : jpg, jpeg, png only </p>
						</div>
					</div>
					<div id="msg_alert"></div>
		</div>
      </div>

	    <div class="modal-footer">
	        <input type = "submit" name = "upload" value = "Upload" class="btn btn-primary btn-md">
	    </div>
		<iframe id="upload_target" name="upload_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>
    </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>	

<!-- upload photo of the employee -->	
<div id = "upload_photo" class="modal fade bs-example-modal-md">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header alert-info">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="closeModal('photo')">&times;</button>
        <h4 class="modal-title">Upload Photo</h4>
      </div>
      <form method = 'POST' enctype='multipart/form-data' target='upload_target' onsubmit='startUpload();'>

          <div class="modal-body">
              <div class = "photoReq">
                  <div class="form-group">
                      <center><img id='preview_photo' class='preview_photo img-responsive'/><br>
                      <input type='file' name='upload_scanned_photo' id='upload_scanned_photo' class='btn btn-default' required onchange='readPhoto(this);'>
                      <input type='button' name='clearphoto' id='clearphoto' style='display:none; margin-top:10px;' class='btn btn-danger' value='Clear'  onclick=clears('upload_scanned_photo','preview_photo','clearphoto')>
                      <input type='button' id='upload_scanned_photo_change' style='display:none;' class='btn btn-primary btn-sm' value='Change Photo?' onclick='changePhoto()'>
                      <p class="help-block"> Allowed File : jpg, jpeg, png only and file size should not be greater than 2MB. </p></center>
                  </div>
              </div>
          </div>

          <div class="modal-footer">
            <span id='f1_upload_process' style="display:none;"><img src = "../images/ajax.gif"> <font size = "2">Please Wait....</font></span>
            <input type="submit" name="photoUpload" class="btn btn-primary" value=" Submit "> 
            <button type="button" class="dis_ btn btn-default " data-dismiss="modal" onclick="closeModal('photo')">Close</button>
         
          </div>

          <iframe id='upload_target' name='upload_target' src='#' style='width:0;height:0;border:0px solid #fff;'></iframe>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
<!-- end here -->

<!-- view job transfer -->
<div id = "jobtransfer" class="modal fade bs-example-modal-lg">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header alert-info">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="closeModal('photo')">&times;</button>
        <h4 class="modal-title">Job Transfer Report</h4>
      </div>
          <div class="modal-body">
              <div class = "jobtrans">
                  <embed id="view_jobTrans" src="" width="100%" height="480"></embed>
              </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="dis_ btn btn-default " data-dismiss="modal" onclick="closeModal('photo')">Close</button>
         
          </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
<!-- end here -->

<div class="modal fade" id="upload_contract" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style='width:85%'>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<div class='row'>
					<div class='col-md-4'><h4 class="modal-title" id="myModalLabel">UPLOAD </h4></div>
					<div class='col-md-4' align='right'><br>  <span class="label label-success">  Allowed File: .jpg, .jpeg, .png; File Size should not be greater than 2MB.</span></div>
				</div>
				
			</div>
			<div class="modal-body">	
				<div id='uploadcontract'>
					<form action = "" method="POST" enctype="multipart/form-data"  target = "upload_target"/> <!--  onsubmit="startUpload201Files();"-->	
						<div class="row">
							<div class="col-md-4">
								<b>Clearance</b><br>
								<img id='preview_clearance' class='preview img-responsive'/><br>
								<input type='file' name='upload_scanned_clearance' id='upload_scanned_clearance' onchange='readURLClearance(this);'>
								<input type='button' id='clearclearance' style='display:none' class='btn btn-default' value='Clear' onclick=clears('upload_scanned_clearance','preview_clearance','clearclearance')>
								<input type='button' id='upload_scanned_clearance_change' style='display:none;' class='btn btn-primary btn-sm' value='Change Clearance?' onclick='changeclearance()'>	
							</div>
							<div class="col-md-4">
								<b>Contract</b><br>
								<img id='preview_contract' class='preview img-responsive'/><br>
								<input type='file' name='upload_scanned_contract' id='upload_scanned_contract' class='btn btn-default' onchange='readURLContract(this);'>	
								<input type='button' name='clearcontract' id='clearcontract' style='display:none' class='btn btn-default' value='Clear'  onclick=clears('upload_scanned_contract','preview_contract','clearcontract')>
								<input type='button' id='upload_scanned_contract_change' style='display:none;' class='btn btn-primary btn-sm' value='Change Contract?' onclick='changecontract()'>							
							</div>							
							<div class="col-md-4">
								<b>EOC Appraisal</b><br>
								<img id='preview_epas' class='preview img-responsive'/><br>								
								<input type='file' name='upload_scanned_epas' id='upload_scanned_epas'  class='btn btn-default' onchange='readURLEpas(this);'>	
								<input type='button' name='cleareocappraisal' id='cleareocappraisal' class='btn btn-default' value='Clear' style='display:none' onclick=clears('upload_scanned_epas','preview_epas','cleareocappraisal')>								
							</div>
						</div>
						<input type='hidden' name='empid' id='empid' value='<?php echo $empid;?>'>
						<input type='hidden' name='contract_record_no' id='contract_record_no'>
						<input type='hidden' name='contract_table' id='contract_table'>
						<!-- <input type="file" name="upload_scanned_contract" id='upload_scanned_contract' onchange="validateForm()" accept="image/jpeg" required><br> -->
						<br>
						<input type="submit" name="submit" value="Upload" class="btn btn-primary"><br>						
						<iframe id="upload_target" name="upload_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe> 
					</form>
				</div>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->	

<!-- Modal  O P E N   E M P L O Y M E N T-->
<div class="modal fade" id="viewexamdetails" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:40%; height:100%">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">EXAMINATION DETAILS</i></h4>
			</div>
			<div class="modal-body" id='viewexam'> 				   
			</div>
			<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal  O P E N   E M P L O Y M E N T-->
<div class="modal fade" id="viewapphistory" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:70%; height:100%">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">APPLICATION HISTORY DETAILS</i></h4>
			</div>
			<div class="modal-body" id='viewappdetails'> 				   
			</div>
			<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- Modal  O P E N   E M P L O Y M E N T-->
<div class="modal fade" id="viewinterviewdetails" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:70%; height:100%">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">INTERVIEW HISTORY</i></h4>
			</div>
			<div class="modal-body" id='interviewhistory'> 
				   
			</div>
			<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal  O P E N   E M P L O Y M E N T-->
<div class="modal fade" id="empdetails" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:70%; height:100%">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">EMPLOYMENT HISTORY DETAILS</h4>
			</div>
			<div class="modal-body"> 
				<iframe src="" width="100%" height="400" id="employmentdet" frameborder="0"></iframe>   
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal for O P E N   A P P R A I S A L -->
<div class="modal fade" id="viewcertificate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:70%; height:80%">
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="myModalLabel">&nbsp;</h4>
		</div>
		<div class="modal-body"> 
			 <span id='viewcert'></span>
		</div>
		<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal for O P E N   A P P R A I S A L -->
<div class="modal fade" id="openapp" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:70%; height:80%">
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="myModalLabel">PERFORMANCE APPRAISAL DETAILS <?php ///echo $name." [".$empid."]";?></h4>
		</div>
		<div class="modal-body"> 
			<iframe src="" width="100%" height="500" id="appraisaldet" frameborder="0"></iframe>   
		</div>
		<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

  <!-- Modal for viewing of requirements -->
<div class="modal fade" id="viewreqr" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:90%; height:450px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">View Requirements</i></h4>
      </div>
    <div class="modal-body"> 
      <iframe src="" width="100%" height="400" id="viewreq" frameborder="0"></iframe>   
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal for upload profile photo -->
<div class="modal fade" id="uploadphoto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="height:300px;width:70%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Upload Photo</i></h4>
      </div>
    <div class="modal-body"> 
      <iframe id="imag" width="95%" height="400" frameborder="0"></iframe></div> 
    </div>
    <!--<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>-->
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal for EMPLOYEE DETAILS -->
<div class="modal fade" id="empdetails" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="height:300px;width:80%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">EDIT EMPLOYEE INFORMATION</i></h4>
      </div>
    <div class="modal-body"> 
      <iframe id="det" width="100%" height="580" frameborder="0"></iframe></div> 
    </div>
    <!--<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>-->
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal for Adding Contract-->
<div class="modal fade" id="contact_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
 <div class="modal-dialog" style='width:80%'>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"> x</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="contract_title">New Contract</h4>
      </div>
      <div class="modal-body">
		<div id="edit_contact_form"></div>
		<div id="add_contact_form">
			<div class="row">
				<div class="col-md-7 col-md-offset-2">
					<span class="label label-danger">Required Fields : Company, Business Unit, Department, Start and End Date, Position, Employee Type, Current Status</span>
				</div>
				<br>
				<br>
				<div class="col-md-4">
					<div class="form-group">
						<label>Company</label>
						<select class="form-control" name="comp_code">
							<option value="">Select</option>
							<?php 
								$sub_query = mysql_query(
												"SELECT
													*
												 FROM
													locate_company
												 ORDER BY
													company ASC"
											 ) or die(mysql_error());
								while($res=mysql_fetch_array($sub_query)){
							?>
								<option value="<?php echo $res['company_code'];?>"><?php echo $res['company'];?></option>
							<?php } ?>
						<select>
					 </div>
					 <div class="form-group">
						<label>Business Unit</label>
						<select class="form-control" name="bunit_code">
							<option value="">Select</option>
						<select>
					 </div>
					 <div class="form-group">
						<label>Department</label>
						<select class="form-control" name="dept_code">
							<option value="">Select</option>
						<select>
					 </div>
					 <div class="form-group">
						<label>Section</label>
						<select class="form-control" name="sec_code">
							<option value="">Select</option>
						<select>
					 </div>
					 <div class="form-group">
						<label>Sub-section</label>
						<select class="form-control" name="ssec_code">
							<option value="">Select</option>
						<select>
					 </div>
					 <div class="form-group">
						<label>Unit</label>
						<select class="form-control" name="unit_code">
							<option value="">Select</option>
						<select>
					 </div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label>Start Date</label>
						<input type="text" class="form-control" name="start_date" placeholder="mm/dd/yyyy">
					 </div>
					 <div class="form-group">
						<label>End Date</label>
						<input type="text" class="form-control" name="end_date" placeholder="mm/dd/yyyy">
					 </div>
					 <div class="form-group">
						<label>Position</label>				
						<select id="contract_position" name="contract_position" class='form-control'>
							<?php 
							$query = mysql_query("SELECT position FROM positions order by position asc");
							while($rq = mysql_fetch_array($query))
							{
								if(@$_POST['pos'] == @$rq['position'])
								{?>
									<option value="<?php echo $rq['position'];?>" selected='selected'><?php echo $rq['position'];?></option><?php 
								}else{ ?>
									<option value="<?php echo $rq['position'];?>"><?php echo $rq['position'];?></option><?php 	
								}
							}?>				
						</select> 	
							
					
					 </div>
					 <div class="form-group">
						<label>Employee Type</label>
						<select class="form-control" name="contract_emptype" required>
							<option value="">Select</option>
							<?php
							$query = mysql_query("SELECT * FROM employee_type");
							while($r = mysql_fetch_array($query)){
								echo "<option value='".$r['emp_type']."' >".$r['emp_type']."</option>";								
							}
							?>						
						</select>
					 </div>
					 <div class="form-group">
						<label>Current Status</label>
						<select class="form-control" name="contract_cstatus">
							<option value="">Select</option>
							<option>Active</option>
							<option>End of Contract</option>
							<option>Resigned</option>
							<option>For Promotion</option>						
						</select>
					 </div>
					 <div class="form-group">
						<label>Position Level</label>
						<select class="form-control" name="contract_positionlevel">
							<option value="">Select</option>
							<option>1</option>
							<option>2</option>
							<option>3</option>
							<option>4</option>
							<option>5</option>
							<option>6</option>
							<option>7</option>
							<option>8</option>
							<option>9</option>
							<option>10</option>
						</select>
					</div>
				</div>
				<div class="col-md-4">
					 <div class="form-group">
						<label>Lodging</label>
						<select class="form-control" name="contract_lodging">
							<option value="">Select</option>
							<option>Stay-in</option>
							<option>Stay-out</option>
						</select>
					 </div>
					 <div class="form-group">
						<label>Position Description</label>
						<textarea class="form-control" name="contract_positiondesc" rows="3"></textarea>
					 </div>
					 <div class="form-group">
						<label>Remarks</label>
						<textarea class="form-control" name="contract_remarks" rows="3"></textarea>
					 </div>
					 <div class="form-group">
						<button type="button" name="add_contract" class="btn btn-primary">Add</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
					 </div>
				</div>
			</div>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id = "viewDatePicker" class="modal fade bs-example-modal-md">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header alert-info">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Renew Employee</h4>
      </div>
      <div class="modal-body">
        <div class = "renew"></div>
      </div>

      <div class="modal-footer">
        <span class = 'loadingSave'></span>
        <button class="btn btn-primary submit" onclick="submit()" disabled=""> Submit </button>
        <button class="btn btn-primary" onclick="reset()"> Reset </button>
     
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>

<script type="text/javascript" src="../jquery/script_add_applicant.js" charset="utf-8"></script>
<script type="text/javascript" src="../jquery/jquery-1.9.1.js" ></script> 
<script type="text/javascript" src="../jquery/jquery-latest.min.js"></script>
<script type="text/javascript" src="../jquery/jquery.maskedinput.js" ></script>

<script>
$(document).ready(function(){
	getdefault('basicinfo');	
	
});

/* pra sa yearly family get together */
function addChildInfo(){

	var noChild = $("[name = 'noChild']").val();
	var counter = $("[name = 'counter']").val();

	if(noChild == ""){

		alert("Input Number Of Child/Children First");
	} else {
		if(noChild > 10){

			alert("Maximum number to be inputed is 10");
		} else {

			$.ajax({
		        type : "POST",
		        url  : "functionquery.php?request=addchildInfo",
		        data : { noChild:noChild, counter:counter },
		        success : function(data){
		        	data = data.split("&&");
		            $(".goSubmit").hide();
		            $(".addSubmit").show();
		            $("[name = 'noChild']").val('');
		 
		            $(".childInfo").show();
		            $(".thead_").fadeIn();
		        	$('#myTable').append(data[0]);
		        	$("[name = 'counter']").val(data[1]);
		        }
		    });
		}
	}
}

function editChild(){

	$(".disAct").prop("disabled",false);
	$(".delChildInfo").show();
	$(".editChild").hide();
	$(".saveEditedChild").fadeIn();
	$(".addChild").fadeIn();
	$(".cancelChild").fadeIn();
	$(".hideupload_").show();
	$(".hideview_").hide();
}

function cancelChild(){

	$(".disAct").prop("disabled",true);
	$(".delChildInfo").hide();
	$(".editChild").show();
	$(".saveEditedChild").fadeOut();
	$(".addChild").fadeOut();
	$(".cancelChild").fadeOut();
	$(".addChildDiv").hide();

	$(".hideupload_").hide();
	$(".hideview_").show();
}

function delChild(childId){

	var r = confirm("Are You Sure You Want To Delete?");
	if(r == true){

		var origFilename = $(".origFilename_"+childId).val();
		alert(origFilename);

		$.ajax({
	      	type : "POST",
	      	url  : "functionquery.php?request=delchildId",
	      	data : { childId:childId, origFilename:origFilename },
	      	success : function(data){
	      		
	      		data = data.trim();
	      		if(data == "Ok"){

				  	alert("Successfully Deleted!");

	      			// $("tr td[class^=td_]").css("background-color","#fff");
				  	$("#td_"+childId).css({"background-color":"#d3d6ff"});
				  	$("#td_"+childId).fadeOut();

				  	$(".deleted2_"+childId).val("deleted");
				  	var ctr = $("[name = 'ctr']").val();

				  	var deleted = "";
				  	var deleted2 = "";

				  	var ifDeleted2 = document.getElementsByName("ifDeleted2[]");
				  	for (var i = 0; i < ctr; i++) {
				
				  		if(ifDeleted2[i].value == "deleted"){

				  			deleted = "true";
				  		} else {

				  			deleted2 = "false"
				  		}
				  	}

				  	if(deleted == "true" && deleted2 == ""){
				  		
				  		$('#myTable2').fadeIn().append("<tr><td colspan='6' align='center'><label> NO CHILD/CHILDREN </label></td></tr>");
				  	}

	      		}
	      	}
	    });
	}
	
}

function numericFilter(txb){
	
   txb.value = txb.value.replace(/[^\0-9]/ig, "");
}


function delChild2(counter){

	var r = confirm("Are You Sure You Want To Delete?");
	if(r == true){

	    $("#td2_"+counter).css({"background-color":"#d3d6ff"});
		$("#td2_"+counter).fadeOut();
		$(".deleted_"+counter).val("deleted");

		var ctr = $("[name = 'counter']").val();
		var ifDeleted = document.getElementsByName("ifDeleted[]");
		var deleted = "";
	  	var deleted2 = "";

	  	for (var i = 0; i < ctr; i++) {
	
	  		if(ifDeleted[i].value == "deleted"){

	  			deleted = "true";
	  		} else {

	  			deleted2 = "false"
	  		}
	  	}

	  	if(deleted == "true" && deleted2 == ""){
	  		$(".childInfo").hide();
	  	}

	  	var filename = $(".fileName_"+counter).val();
		if(filename != ""){
			$.ajax({
		      	type : "POST",
		      	url  : "functionquery.php?request=deleteNSO",
		      	data : { filename:filename },
		      	success : function(data){
		      		data = data.trim();
		      		if(data == "Ok"){

		      		} else {
		      			alert(data);
		      		}
		      	}
		    });
		}
	}
}

function saveEditedChild(){

	/*update spouse_info*/
	var spouseId = $("[name = 'spouseId']").val();
	var empId = $("[name = 'empId']").val();
	var spouseEmpid = $("[name = 'spouseEmpid']").val();
	var spouseName = $("[name = 'spouseName']").val();
	var ichangeEmpid = $("[name = 'ichangeEmpid']").val();

	/*update children_info*/
	var childIds = document.getElementsByName("childIds[]");
	var ifDeleted2 = document.getElementsByName("ifDeleted2[]");
	var updfname = document.getElementsByName("updfname[]");
	var updmname = document.getElementsByName("updmname[]");
	var updlname = document.getElementsByName("updlname[]");
	var updbday = document.getElementsByName("updbday[]");
	var updgender = document.getElementsByName("updgender[]");
	var updfilename = document.getElementsByName("updfileName[]");
	var origfileName = document.getElementsByName("origfileName[]");

	var tempChildIds = tempUpdfname = tempUpdmname = tempUpdlname = tempUpdbday = tempUpdgender = tempUpdfilename = tempOrigFilename = "";
	var ifEmpty2 = "";

	for(var i = 0; i< childIds.length; i++){

		if((ifDeleted2[i].value == "notdeleted") && (updfname[i].value == "" || updlname[i].value == "" || updbday[i].value == "" || updgender[i].value == ""))
		{
			if(updfname[i].value == ""){
				$(".updfname_"+childIds[i].value).css('border-color','#E55B5B');
			}

			if(updlname[i].value == ""){
				$(".updlname_"+childIds[i].value).css('border-color','#E55B5B');
			}

			if(updbday[i].value == ""){
				$(".updbday_"+childIds[i].value).css('border-color','#E55B5B');
			}

			if(updgender[i].value == ""){
				$(".updgender_"+childIds[i].value).css('border-color','#E55B5B');
			}

			ifEmpty2 = "true";

		} else {

			tempChildIds += childIds[i].value+"&";
			tempUpdfname += updfname[i].value+"&";
			tempUpdmname += updmname[i].value+"&";
			tempUpdlname += updlname[i].value+"&";
			tempUpdbday  += updbday[i].value+"&";
			tempUpdgender += updgender[i].value+"&";
			tempUpdfilename += updfilename[i].value+"&";
			tempOrigFilename += origfileName[i].value+"&";
		}

	}

	if(ifEmpty2 == "true"){

		alert("Please Fill-up Required Fields");
	} else {
	
		$.ajax({
	      	type : "POST",
	      	url  : "functionquery.php?request=updateFamilyInfo",
	      	data : { spouseId:spouseId, ichangeEmpid:ichangeEmpid, empId:empId, spouseEmpid:spouseEmpid, spouseName:spouseName, tempChildIds:tempChildIds, tempUpdfname:tempUpdfname, tempUpdmname:tempUpdmname, tempUpdlname:tempUpdlname, tempUpdbday:tempUpdbday, tempUpdgender:tempUpdgender, tempUpdfilename:tempUpdfilename, tempOrigFilename:tempOrigFilename },
	      	success : function(data){
	      		data = data.trim();
	      		if(data == "Ok"){

	      			alert("Successfully Saved!!");
	      			getdefault('family');
	      		} else {

	      			alert(data);
	      		}
	      	}
	    });
	}
}

function addChild(){
	$(".addChildDiv").show();
	$(".button2nd").show();
	$(".button1st").hide();
}

function cancelChild2(){
	$(".addChildDiv").hide();
	$(".button2nd").hide();
	$(".button1st").show();

	$(".td2").css({"background-color":"#d3d6ff"});
	$(".td2").fadeOut();
	$(".thead_").fadeOut();

	$(".hideupload_").hide();
	$(".hideview_").show();
}

function cancelInsertChild(){

	$(".childInfo").hide();
	$(".td2").css({"background-color":"#d3d6ff"});
	$(".td2").fadeOut();
	$(".thead_").fadeOut();
}

function saveEditedChild2(){


	// update spouse_info
	var spouseId = $("[name = 'spouseId']").val();
	var empId = $("[name = 'empId']").val();
	var spouseEmpid = $("[name = 'spouseEmpid']").val();
	var spouseName = $("[name = 'spouseName']").val();
	var ichangeEmpid = $("[name = 'ichangeEmpid']").val();
	
	// update children_info
	var childIds = document.getElementsByName("childIds[]");
	var ifDeleted2 = document.getElementsByName("ifDeleted2[]");
	var updfname = document.getElementsByName("updfname[]");
	var updmname = document.getElementsByName("updmname[]");
	var updlname = document.getElementsByName("updlname[]");
	var updbday = document.getElementsByName("updbday[]");
	var updgender = document.getElementsByName("updgender[]");
	var updfilename = document.getElementsByName("updfileName[]");
	var origfileName = document.getElementsByName("origfileName[]");

	var tempChildIds = tempUpdfname = tempUpdmname = tempUpdlname = tempUpdbday = tempUpdgender = tempUpdfilename = tempOrigFilename = "";
	var ifEmpty2 = "";

	for(var i = 0; i< childIds.length; i++){

		if((ifDeleted2[i].value == "notdeleted") && (updfname[i].value == "" || updlname[i].value == "" || updbday[i].value == "" || updgender[i].value == ""))
		{
			if(updfname[i].value == ""){
				$(".updfname_"+childIds[i].value).css('border-color','#E55B5B');
			}

			if(updlname[i].value == ""){
				$(".updlname_"+childIds[i].value).css('border-color','#E55B5B');
			}

			if(updbday[i].value == ""){
				$(".updbday_"+childIds[i].value).css('border-color','#E55B5B');
			}

			if(updgender[i].value == ""){
				$(".updgender_"+childIds[i].value).css('border-color','#E55B5B');
			}

			ifEmpty2 = "true";

		} else {

			tempChildIds += childIds[i].value+"&";
			tempUpdfname += updfname[i].value+"&";
			tempUpdmname += updmname[i].value+"&";
			tempUpdlname += updlname[i].value+"&";
			tempUpdbday  += updbday[i].value+"&";
			tempUpdgender += updgender[i].value+"&";
			tempUpdfilename += updfilename[i].value+"&";
			tempOrigFilename += origfileName[i].value+"&";
		}
	}


	// add children info

	var counter = document.getElementsByName("addCounter[]");
	var ifDeleted = document.getElementsByName("ifDeleted[]");
	var addfname = document.getElementsByName("addFname[]");
	var addmname = document.getElementsByName("addMname[]");
	var addlname = document.getElementsByName("addLname[]");
	var addbday = document.getElementsByName("addBday[]");
	var addgender = document.getElementsByName("addGender[]");
	var addfilename = document.getElementsByName("addFileName[]");

	var tempCounter = tempAddfname = tempAddmname = tempAddlname = tempAddbday = tempAddgender = tempAddFilename = "";
	var ifEmpty = "";
	for(var i = 0; i< counter.length; i++){

		if((ifDeleted[i].value == "notdeleted") && (addfname[i].value == "" || addlname[i].value == "" || addbday[i].value == "" || addgender[i].value == ""))
		{

			if(addfname[i].value == ""){
				$(".fname_"+counter[i].value).css('border-color','#E55B5B');
			}

			if(addlname[i].value == ""){
				$(".lname_"+counter[i].value).css('border-color','#E55B5B');
			}

			if(addbday[i].value == ""){
				$(".bday_"+counter[i].value).css('border-color','#E55B5B');
			}

			if(addgender[i].value == ""){
				$(".gender_"+counter[i].value).css('border-color','#E55B5B');
			}

			ifEmpty = "true";

		} else {

			tempCounter += counter[i].value+"&";
			tempAddfname += addfname[i].value+"&";
			tempAddmname += addmname[i].value+"&";
			tempAddlname += addlname[i].value+"&";
			tempAddbday  += addbday[i].value+"&";
			tempAddgender += addgender[i].value+"&";
			tempAddFilename += addfilename[i].value+"&";
		}
	}

	if(ifEmpty == "true" || ifEmpty2 == "true"){

		alert("Please Fill-up Required Fields");
	} else {
		$.ajax({
	      	type : "POST",
	      	url  : "functionquery.php?request=updateFamilyInfo2",
	      	data : { spouseId:spouseId, ichangeEmpid:ichangeEmpid, empId:empId, spouseEmpid:spouseEmpid, spouseName:spouseName, tempChildIds:tempChildIds, tempUpdfname:tempUpdfname, tempUpdmname:tempUpdmname, tempUpdlname:tempUpdlname, tempUpdbday:tempUpdbday, tempUpdgender:tempUpdgender, tempUpdfilename:tempUpdfilename, tempOrigFilename:tempOrigFilename, tempCounter:tempCounter, tempAddfname:tempAddfname, tempAddmname:tempAddmname, tempAddlname:tempAddlname, tempAddbday:tempAddbday, tempAddgender:tempAddgender, tempAddFilename:tempAddFilename },
	      	success : function(data){
	      		data = data.trim();
	      		if(data == "Ok"){

	      			alert("Successfully Saved!!");
	      			getdefault('family');
	      		} else {

	      			alert(data);
	      		}
	      	}
	    });
	}
}

function fname(counter){

	$(".fname_"+counter).css('border-color','#ccc');
	$(".updfname_"+counter).css('border-color','#ccc');
}

function lname(counter){

	$(".lname_"+counter).css('border-color','#ccc');
	$(".updlname_"+counter).css('border-color','#ccc');
}

function bday(counter,classify){
	
	if(classify == "update"){

		$(".updbday_"+counter).css('border-color','#ccc');
		var bday = $(".updbday_"+counter).val();

		$.ajax({
	      	type : "POST",
	      	url  : "functionquery.php?request=getAge",
	      	data : { bday:bday },
	      	success : function(data){
	      		if(data){
	      			$(".updAge_"+counter).val(data);
	      		} else {

	      			alert(data);
	      		}
	      	}
	    });

	} else {

		$(".bday_"+counter).css('border-color','#ccc');
		var bday = $(".bday_"+counter).val();

		$.ajax({
	      	type : "POST",
	      	url  : "functionquery.php?request=getAge",
	      	data : { bday:bday },
	      	success : function(data){
	      		if(data){
	      			$(".age_"+counter).val(data);
	      		} else {

	      			alert(data);
	      		}
	      	}
	    });
	}
}

function uploadImage(){

	$("#upload_clearance").modal({
            backdrop: 'static',
            keyboard: false
        });

    $("#upload_clearance").modal("show");
}

function startUploadLiveBirth(){

    $("#f1_upload_process").show();
    return true;
}

function stopUploadLiveBirth(success){

	var result = success.split("|");
	var counter = result[1];
	var destination_path = result[2];
	var classify = result[3];

    if (result[0] == 2){

        setTimeout(function(){
        	
        	if(classify == "add"){
	     		
	     		$(".fileName_"+counter).val(destination_path);
        	} else {

	     		$(".updFilename_"+counter).val(destination_path);
        	}

	     	$("#upload_scanned_NSO").hide();
	     	$("#clearNSO").hide();
	     	$("#upload_scanned_NSO_change").show();
	        alert("Temporary Saved! Please Close");
        },500);
    }
    
    
    if(success == 0) {
        alert("There was an error during file upload!");
    }
    
    $("#f1_upload_process").hide();   
    return true;   
}

function stopUploadPhoto(result){

	result = result.split("||");
	var success = result[0];
	var empId 	= result[1];

    if (success == 2){
     
        alert("Photo Successfully Updated!");
        setTimeout(function(){
        
           // $("#upload_photo").modal("hide");
           window.location = "?p=employee&com="+empId;
        },1000);
    }
    
    
    if(success == 0) {
        alert("There was an error during file upload!");
    }
    
    $("#f1_upload_process").hide();   
    return true;   
}

function uploadNso(counter,classify){

	$("[name = 'imgCounter']").val(counter);
	var fname = $(".fname_"+counter).val();
	var lname = $(".lname_"+counter).val();

	if(classify == "add"){
		
		var data = $(".fileName_"+counter).val();
	} else {

		var data = $(".updFilename_"+counter).val();
	}

	var name = lname+", "+fname;
	$("[name = 'childName']").val(name);
	$("[name = 'classify']").val(classify);

	$('#preview_NSO').removeAttr('src');	
	$('#upload_scanned_NSO').val('');
	$('#clearNSO').hide();
	
	if(data != ''){
		document.getElementById("preview_NSO").src = data;		
		$('#upload_scanned_NSO').hide();		
		$('#upload_scanned_NSO_change').show();
	}	
	else{
		$('#upload_scanned_NSO').show();		
		$('#upload_scanned_NSO_change').hide();
	}

}

function viewNso(destination_path){
	document.getElementById("view_NSO").src = destination_path;
}

function readNSO(input)
{
	$('#clearNSO').show();	
	
	var res = validateForm('upload_scanned_NSO')
	if(res !=1){
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$('#preview_NSO').attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}else{
		$('#preview_NSO').removeAttr('src');
		document.getElementById('upload_scanned_NSO').value = '';
	}	
} 

function changeNSO(){
	var r = confirm('You are attempting to change the uploaded birth certificate, Click ok to proceed.')
	if(r == true){
		$('#upload_scanned_NSO_change').hide();
		$('#upload_scanned_NSO').show();
	}	
}
/* change Photo of the Employee */
function changePhoto(){

	var r = confirm('You are attempting to change the uploaded photo, Click ok to proceed.')
	if(r == true){
		$('#upload_scanned_photo_change').hide();
		$('#upload_scanned_photo').show();
	}
}

function startUpload(){

    $("#f1_upload_process").show();
    return true;
}

function readPhoto(input)
{
	$('#clearphoto').show();	
	
	var res = validateForm('upload_scanned_photo')
	if(res !=1){
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$('#preview_photo').attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}else{
		$('#preview_photo').removeAttr('src');
		document.getElementById('upload_scanned_photo').value = '';
	}	
} 
/* end here */

function gender(counter){

	$(".gender_"+counter).css('border-color','#ccc');
	$(".updgender_"+counter).css('border-color','#ccc');
}

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
            url  : "functionquery.php?request=findParentsName",
            data : { str : str},
            success : function(data){
            	data = data.trim();
                if(data){
                    $(".search-results").show().html(data);
                } else {
                	$(".search-results").hide();
                	$("[name = 'spouseEmpid']").val('');
                }
            } 
        });
    }
}

function getEmpId(id){

    var id = id.split("*");
    var empId = id[0].trim();
    var name = id[1].trim();

    $("[name='spouseName']").val(name);
    $("[name='spouseEmpid']").val(empId);
    $(".search-results").hide();

}

function saveInsertChild(){

	var empId = $("[name = 'empId']").val();
	var spouseEmpid = $("[name = 'spouseEmpid']").val();
	var spouseName = $("[name = 'spouseName']").val();

	/*add children info*/

	var counter = document.getElementsByName("addCounter[]");
	var ifDeleted = document.getElementsByName("ifDeleted[]");
	var addfname = document.getElementsByName("addFname[]");
	var addmname = document.getElementsByName("addMname[]");
	var addlname = document.getElementsByName("addLname[]");
	var addbday = document.getElementsByName("addBday[]");
	var addgender = document.getElementsByName("addGender[]");
	var addfilename = document.getElementsByName("addFileName[]");

	var tempCounter = tempAddfname = tempAddmname = tempAddlname = tempAddbday = tempAddgender = tempAddFilename = "";
	var ifEmpty = "";

	for(var i = 0; i< counter.length; i++){

		if((ifDeleted[i].value == "notdeleted") && (addfname[i].value == "" || addlname[i].value == "" || addbday[i].value == "" || addgender[i].value == ""))
		{

			if(addfname[i].value == ""){
				$(".fname_"+counter[i].value).css('border-color','#E55B5B');
			}

			if(addlname[i].value == ""){
				$(".lname_"+counter[i].value).css('border-color','#E55B5B');
			}

			if(addbday[i].value == ""){
				$(".bday_"+counter[i].value).css('border-color','#E55B5B');
			}

			if(addgender[i].value == ""){
				$(".gender_"+counter[i].value).css('border-color','#E55B5B');
			}

			ifEmpty = "true";

		} else {

			tempCounter += counter[i].value+"&";
			tempAddfname += addfname[i].value+"&";
			tempAddmname += addmname[i].value+"&";
			tempAddlname += addlname[i].value+"&";
			tempAddbday  += addbday[i].value+"&";
			tempAddgender += addgender[i].value+"&";
			tempAddFilename += addfilename[i].value+"&";
		}
	}

	if(ifEmpty == "true"){

		alert("Please Fill-up Required Fields");
	} else {

		$.ajax({
	      	type : "POST",
	      	url  : "functionquery.php?request=addFamilyInfo",
	      	data : { empId:empId, spouseEmpid:spouseEmpid, spouseName:spouseName, tempCounter:tempCounter, tempAddfname:tempAddfname, tempAddmname:tempAddmname, tempAddlname:tempAddlname, tempAddbday:tempAddbday, tempAddgender:tempAddgender, tempAddFilename:tempAddFilename },
	      	success : function(data){
	      		data = data.trim();
	      		if(data == "Ok"){

	      			alert("Successfully Saved!!");
	      			getdefault('family');
	      		} else {

	      			alert(data);
	      		}
	      	}
	    });
	}
}

/* end of module for yearly family get together*/

function changeclearance(){
	var r = confirm('You are attempting to change the uploaded clearance, Click ok to proceed.')
	if(r == true){
		$('#upload_scanned_clearance_change').hide();
		$('#upload_scanned_clearance').show();		
	}
}
function changecontract(){
	var r = confirm('You are attempting to change the uploaded contract, Click ok to proceed.')
	if(r == true){
		$('#upload_scanned_contract_change').hide();
		$('#upload_scanned_contract').show();
	}	
}
function uploadonchange(imgid)
{	
	var res = validateForm(imgid);	
	if(res ==1){
		document.getElementById(imgid).value = '';
	}
}
function clears(file,preview,clrbtn){
	document.getElementById(file).value= '';
	$('#'+preview).removeAttr('src');
	$('#'+clrbtn).hide();
}
function readURLClearance(input)
{
	$('#clearclearance').show();
	//document.getElementById(file).value= '';
	//$('#'+preview).removeAttr('src');
	
	var res = validateForm('upload_scanned_clearance')
	if(res !=1){
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$('#preview_clearance').attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}else{
		$('#preview_clearance').removeAttr('src');
		document.getElementById('upload_scanned_clearance').value = '';
	}	
} 

function readURLContract(input)
{
	$('#clearcontract').show();	
	
	var res = validateForm('upload_scanned_contract')
	if(res !=1){
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$('#preview_contract').attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}else{
		$('#preview_contract').removeAttr('src');
		document.getElementById('upload_scanned_contract').value = '';
	}	
} 

function readURLEpas(input)
{
	
	$('#cleareocappraisal').show();	
	//document.getElementById("cleareocappraisal").style.visibility = "visible";
	//document.getElementById("cleareocappraisal").visible = true;
	var res = validateForm('upload_scanned_epas')
	if(res !=1){
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$('#preview_epas').attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}else{
		$('#preview_epas').removeAttr('src');
		document.getElementById('upload_scanned_epas').value = '';
	}
} 

function viewexam(hist_exam_code){
	var empid = '<?php echo $empid;?>';	
	$.ajax({
		type: "POST",
		url: "employee_information_details.php?request=viewexamdet",
		data: { empid:empid,hist_exam_code:hist_exam_code },
		success: function(data){				
			$('#viewexam').html(data);
		}
	});
}
function viewappdet(){
	var empid = '<?php echo $empid;?>';		
	$.ajax({
		type: "POST",
		url: "employee_information_details.php?request=apphistorydetails",
		data: { empid:empid },
		success: function(data){			
			$('#viewappdetails').html(data);
		}
	});
}

function viewinterview()
{		
	var empid = '<?php echo $empid;?>';		
	$.ajax({
		type: "POST",
		url: "employee_information_details.php?request=viewinterviewdetails",
		data: { empid:empid },
		success: function(data){			
			$('#interviewhistory').html(data);
		}
	});
}
function viewcertificate(cert){	
	$('#viewcert').html("<center><img src='"+cert+"' style='border:1px solid #ccc;width:100%' ></center>");
}
function add_seminar(no)
{
	$('#add-edit-seminar').show();
	var empid = '<?php echo $empid;?>';
	$.ajax({
		type: "POST",
		url: "employee_information_details.php?req=addseminar",
		data: { empid:empid,no:no },
		success: function(data){						
			$('#add-edit-seminar').html(data);
		}
	});
}
function save_seminar(no)
{
	var empid = '<?php echo $empid;?>';	
	var name 	= document.getElementById('name').value;
	var dates 	= document.getElementById('date').value;		
	var location= document.getElementById('location').value;
		
	$.ajax({
		type: "POST",
		url: "employee_information_details.php?req=save_seminar",
		data: { empid:empid,no:no,name:name,dates:dates,location:location },
		success: function(data){					
			
			alert(data.trim());
			getdefault('seminar');
			$('#add-edit-seminar').hide();
		}
	});
}
function add_emphis(no)
{
	$('#add-edit-emphis').show();
	var empid = '<?php echo $empid;?>';
	$.ajax({
		type: "POST",
		url: "employee_information_details.php?req=addemphis",
		data: { empid:empid,no:no },
		success: function(data){						
			$('#add-edit-emphis').html(data);
		}
	});
}

function add_charref(no)
{
	$('#add-edit-charref').show();
	var empid = '<?php echo $empid;?>';
	$.ajax({
		type: "POST",
		url: "employee_information_details.php?req=addcharref",
		data: { empid:empid,no:no },
		success: function(data){						
			$('#add-edit-charref').html(data);
		}
	});
}

jQuery(function($){	
	$("#ph").mask("00-000000000-0");
   	$("#sss").mask("00-0000000-0");
   	$("#pagibig").mask("0000-0000-0000");  
   	$("[name='start_date']").mask("99/99/9999");  
   	$("[name='end_date']").mask("99/99/9999"); 
	var employee_id = "<?php echo @$_GET['com'];?>";
	
	$("[name='comp_code']").change(function(){
		var id = this.value;
		$.ajax({
			type : "POST",
			url : "ajax.php?load=bunit",
			data : { id : id },
			success : function(data){
				$("[name='bunit_code']").html(data);
				$("[name='dept_code']").val('');
				$("[name='sec_code']").val('');
				$("[name='ssec_code']").val('');
				$("[name='unit_code']").val('');
			}
		});
	});
	$("[name='bunit_code']").change(function(){
		var id = this.value;
		$.ajax({
			type : "POST",
			url : "ajax.php?load=dept",
			data : { id : id },
			success : function(data){
				$("[name='dept_code']").html(data);
				$("[name='sec_code']").val('');
				$("[name='ssec_code']").val('');
				$("[name='unit_code']").val('');
			}
		});
	});
	$("[name='dept_code']").change(function(){
		var id = this.value;
		$.ajax({
			type : "POST",
			url : "ajax.php?load=section",
			data : { id : id },
			success : function(data){
				$("[name='sec_code']").html(data);
				$("[name='ssec_code']").val('');
				$("[name='unit_code']").val('');
			}
		});
	});
	$("[name='sec_code']").change(function(){
		var id = this.value;
		$.ajax({
			type : "POST",
			url : "ajax.php?load=ssection",
			data : { id : id },
			success : function(data){
				$("[name='ssec_code']").html(data);
				$("[name='unit_code']").val('');
			}
		});
	});
	$("[name='ssec_code']").change(function(){
		var id = this.value;
		$.ajax({
			type : "POST",
			url : "ajax.php?load=unit",
			data : { id : id },
			success : function(data){
				$("[name='unit_code']").html(data);
			}
		});
	});
	
	$("[name='add_contract']").click(function(){
		var comp_code = $("[name='comp_code']").val();
		var bunit_code = $("[name='bunit_code']").val();
		var dept_code = $("[name='dept_code']").val();
		var sec_code = $("[name='sec_code']").val();
		var ssec_code = $("[name='ssec_code']").val();
		var unit_code = $("[name='unit_code']").val();
		var start_date = $("[name='start_date']").val();
		var end_date = $("[name='end_date']").val();
		var contract_position = $("[name='contract_position']").val();
		var contract_emptype = $("[name='contract_emptype']").val();
		var contract_cstatus = $("[name='contract_cstatus']").val();
		var contract_positionlevel = $("[name='contract_positionlevel']").val();
		var contract_lodging = $("[name='contract_lodging']").val().trim();
		var contract_positiondesc = $("[name='contract_positiondesc']").val().trim();
		var contract_remarks = $("[name='contract_remarks']").val().trim();
		
		///if(comp_code && bunit_code && dept_code && start_date && end_date && contract_emptype && contract_cstatus){
		if(comp_code && start_date && end_date && contract_emptype && contract_cstatus){
			$.ajax({
				type : "POST",
				url : "ajax.php?request=add_contract",
				data : { comp_code : comp_code, bunit_code : bunit_code, dept_code : dept_code, sec_code : sec_code, ssec_code : ssec_code, unit_code : unit_code, start_date : start_date, end_date : end_date, contract_position : contract_position, contract_emptype : contract_emptype, contract_cstatus : contract_cstatus, contract_positionlevel : contract_positionlevel, contract_lodging : contract_lodging, contract_positiondesc : contract_positiondesc, contract_remarks : contract_remarks, employee_id : employee_id },
				success : function(data){
					if(data == 'Ok'){
						alert("Successfully Added!");
						getdefault('employment');
					}else{
						alert(data);
					}
				}
			});
		}else{
			alert("Please take note the required fields!");
		}
	});
});

function save_charref(no)
{
	var empid 		= '<?php echo $empid;?>';	
	var name 		= document.getElementById('cname').value;
	var position 	= document.getElementById('cposition').value;		
	var contactno 	= document.getElementById('ccontactno').value;
	var company 	= document.getElementById('ccompany').value;
	$.ajax({
		type: "POST",
		url: "employee_information_details.php?req=save_charref",
		data: { empid:empid,no:no,name:name,position:position,contactno:contactno,company:company },
		success: function(data){								
			alert(data.trim());
			getdefault('charref');
			$('#add-edit-charref').hide();
		}
	});
}

function save_emphis(no)
{
	var empid 	= '<?php echo $empid;?>';	
	var company	= document.getElementById('company').value;
	var position= document.getElementById('position').value;		
	var start 	= document.getElementById('start').value;
	var end 	= document.getElementById('end').value;
	var address = document.getElementById('address').value;
		
	$.ajax({
		type: "POST",
		url: "employee_information_details.php?req=save_emphis",
		data: { empid:empid,no:no,company:company,position:position,start:start,end:end,address:address },
		success: function(data){								
			alert(data.trim());
			getdefault('history');
			$('#add-edit-emphis').hide();
		}
	});
}

function save_remarks()
{	
	var empid 	= '<?php echo $empid;?>';
	var remarks = document.getElementById('remarks').value;
	$.ajax({
		type: "POST",
		url: "employee_information_details.php?req=save_remarks",
		data: { empid:empid,remarks:remarks },
		success: function(data){						
			if(data.trim() == "success")
			{
				alert('Adding Sucessful!')				
			}
			else
			{
				alert('Adding failed!')
			}
			getdefault('remarks');			
		}
	});
}
function getdefault(code)
{			
	//alert(code);
	$('#add-edit-seminar').hide();
	$('#add-edit-charref').hide();
	$('#add-edit-emphis').hide();
	$("#add_c").hide();
	
	var empid = '<?php echo $empid;?>';
	if(code == "201doc"){
		var input = $("[name='empidD']").val();
		$.ajax({
			type: "POST",
			url: "ajax.php?request=load201Files1",
			data: { input:input },
			success: function(data){		
				$('#details').html(data);
			}
		});
	}else{
		$.ajax({
			type: "POST",
			url: "employee_information_details.php?request="+code,
			data: { empid:empid },
			success: function(data){				
				$('#details').html(data);				
				if(code == 'employment'){
					$("#add_c").show();
				}
			}
		});
	}
}


function update(code)
{
	var empid = '<?php echo $empid;?>';		
	var r = confirm('Are you sure to save the new update?')
	if(r==true)
	{			
		//UPDATE-FAMILY
		if(code == 'update-family')
		{
			var mother 	= document.getElementById('mother').value;
			var father 	= document.getElementById('father').value;	
			var guardian= document.getElementById('guardian').value;
			var spouse 	= document.getElementById('spouse').value;
			$.ajax({
				type: "POST",
				url: "employee_information_details.php?request="+code,
				data: { empid:empid,mother:mother,father:father,guardian:guardian,spouse:spouse },
				success: function(data){				
					if(data.trim() == "success")
					{
						alert('Updating Sucessful!')				
					}
					else
					{
						alert('Updating failed!')
					}
					getdefault('family');
				}
			});
		}
		else if(code == 'update-basicinfo')
		{
			var fname 	= document.getElementById('fname').value;
			var mname 	= document.getElementById('mname').value;
			var lname 	= document.getElementById('lname').value;
			var bday 	= document.getElementById('datebirth').value;
			var gender 	= document.getElementById('gender').value;
			var cvstat 	= document.getElementById('civilstatus').value;
			var religion= document.getElementById('religion').value;
			var height 	= document.getElementById('height').value;
			var weight 	= document.getElementById('weight').value;
			var blood 	= document.getElementById('bloodtype').value;
			var citizenship = document.getElementById('citizenship').value;
			var suffix =  document.getElementById('suffix').value;
			
			$.ajax({
				type: "POST",
				url: "employee_information_details.php?request="+code,
				data: { empid:empid,fname:fname,mname:mname,lname:lname,bday:bday,gender:gender,cvstat:cvstat,religion:religion,height:height,weight:weight,blood:blood,citizenship:citizenship,suffix:suffix },
				success: function(data){		
											
					if(data.trim() == "success")
					{
						alert('Updating Sucessful!')	
						window.location = "?p=employee&com="+empid;			
					}
					else
					{
						alert(data);
						//alert('Updating failed!')
						getdefault('basicinfo');
					}
					
				}
			});			
		}
		else if(code == 'update-contact')
		{
			var homeadd 	= document.getElementById('homeaddress').value;
			var cityadd 	= document.getElementById('cityaddress').value;
			var cperson 	= document.getElementById('contactperson').value;
			var cpersonadd	= document.getElementById('contactpersonaddress').value;
			var cpersonno	= document.getElementById('contactpersonno').value;
			var cellno 		= document.getElementById('cellno').value;
			var telno 		= document.getElementById('telno').value;
			var email 		= document.getElementById('email').value;
			var fb 			= document.getElementById('fb').value;
			var twitter 	= document.getElementById('twitter').value;

			$.ajax({
				type: "POST",
				url: "employee_information_details.php?request="+code,
				data: { empid:empid,homeadd:homeadd,cityadd:cityadd,cperson:cperson,cpersonadd:cpersonadd,cpersonno:cpersonno,cellno:cellno,telno:telno,email:email,fb:fb,twitter:twitter },
				success: function(data){										
					if(data.trim() == "success")
					{
						alert('Updating Sucessful!')										
					}
					else
					{
						alert('Updating failed!')						
					}
					getdefault('contact');
				}
			});		
		}
		else if(code == "update-educ")
		{
			var attainment = document.getElementById('attainment').value;
			var school = document.getElementById('school').value;
			var course = document.getElementById('course').value;
			$.ajax({
				type: "POST",
				url: "employee_information_details.php?request="+code,
				data: { empid:empid,attainment:attainment,school:school,course:course },
				success: function(data){														
					if(data.trim() == "success")
					{
						alert('Updating Sucessful!')										
					}
					else
					{
						alert('Updating failed!')						
					}
					getdefault('educ');
				}
			});	
		}
		else if(code == "update-skills")
		{
			var hobbies = document.getElementById('hobbies').value;
			var skills = document.getElementById('skills').value;
			$.ajax({
				type: "POST",
				url: "employee_information_details.php?request="+code,
				data: { empid:empid,hobbies:hobbies,skills:skills },
				success: function(data){														
					if(data.trim() == "success")
					{
						alert('Updating Sucessful!')										
					}
					else
					{
						alert('Updating failed!')						
					}
					getdefault('skills');
				}
			});	
		}
		else if(code == "update-benefits")
		{
			var ph 	= document.getElementById('ph').value;
			var sss = document.getElementById('sss').value;
			var pagibig = document.getElementById('pagibig').value; 
			var pagibigrtn = document.getElementById('pagibigrtn').value; 
			var tinno = document.getElementById('tinno').value; 
			
			$.ajax({
				type: "POST",
				url: "employee_information_details.php?request="+code,
				data: { empid:empid,ph:ph,sss:sss,pagibig:pagibig,pagibigrtn:pagibigrtn,tinno:tinno },
				success: function(data){																	
					if(data.trim() == "success")
					{
						alert('Updating Sucessful!')										
					}
					else
					{
						alert('Updating failed!')						
					}
					getdefault('benefits');
				}
			});	
		}
		else if(code == "update-apphis")
		{
			var dateapplied = document.getElementById('dateapplied').value;
			var datebriefed = document.getElementById('datebriefed').value;
			var datehired   = document.getElementById('datehired').value;
			var aeregular   = document.getElementById('aeregular').value;
			var dateexamined= document.getElementById('dateexamined').value;
			var posapplied  = document.getElementById('posapplied').value;
			var examres		= document.getElementById('examres').value;
			
			$.ajax({
				type: "POST",
				url: "employee_information_details.php?request="+code,
				data: { empid:empid,dateapplied:dateapplied,datebriefed:datebriefed,datehired:datehired,aeregular:aeregular,dateexamined:dateexamined,posapplied:posapplied,examres:examres},
				success: function(data){		
																					
					if(data.trim() == "success")
					{
						alert('Updating Sucessful!')										
					}
					else
					{
						//alert('Updating failed!')						
						alert(data);			
					}
					getdefault('application');
				}
			});	
		}
	}
}
function editemployment(rec){
	$("#contract_title").html("Edit Contract");
	$("#add_contact_form").hide();
	$("#edit_contact_form").html('');
	$("#edit_contact_form").show().html('<img src="../images/loading19.gif"> please wait...');
	$.ajax({
		type : "POST",
		url : "ajax.php?request=editContract",
		data : { rec : rec },
		success : function(data){
			$("#edit_contact_form").html(data);
		}
	});
	$("#contact_form").modal("show");
}

/* new code 07-08-2017 */
function edit_contract(){

	var employment = "employee";
	var emp_id = $("[name='emp_id']").val();
	var rec_no = $("[name='rec_no']").val();
	var comp_code = $("[name='comp_code']").val();
	var bunit_code = $("[name='bunit_code']").val();
	var dept_code = $("[name='dept_code']").val();
	var sec_code = $("[name='sec_code']").val();
	var ssec_code = $("[name='ssec_code']").val();
	var unit_code = $("[name='unit_code']").val();
	var start_date = $("[name='estart_date']").val();
	var end_date = $("[name='eend_date']").val();
	var contract_position = $("[name='contract_position']").val();
	var contract_emptype = $("[name='contract_emptype']").val();
	var contract_cstatus = $("[name='contract_cstatus']").val();
	var contract_positionlevel = $("[name='contract_positionlevel']").val();
	var contract_lodging = $("[name='contract_lodging']").val().trim();
	var contract_positiondesc = $("[name='contract_positiondesc']").val().trim();
	var contract_remarks = $("[name='contract_remarks']").val().trim();
	
	// alert(emp_id+"/"+rec_no+"/"+comp_code+"/"+bunit_code+"/"+dept_code+"/"+sec_code+"/"+ssec_code+"/"+unit_code+"/"+start_date+"/"+end_date);
	// alert(contract_position+"/"+contract_emptype+"/"+contract_cstatus+"/"+contract_positionlevel+"/"+contract_lodging+"/"+contract_positiondesc+"/"+contract_remarks);
	if(comp_code && start_date && end_date && contract_emptype && contract_cstatus){
		$.ajax({
			type : "POST",
			url : "ajax.php?request=edit_contract",
			data : { comp_code : comp_code, bunit_code : bunit_code, dept_code : dept_code, sec_code : sec_code, ssec_code : ssec_code, unit_code : unit_code, start_date : start_date, end_date : end_date, contract_position : contract_position, contract_emptype : contract_emptype, contract_cstatus : contract_cstatus, contract_positionlevel : contract_positionlevel, contract_lodging : contract_lodging, contract_positiondesc : contract_positiondesc, contract_remarks : contract_remarks, rec_no : rec_no, emp_id : emp_id , employment : employment },
			success : function(data){
				if(data == 'Ok'){
					alert(data);
					alert("Successfully Updated!");
					getdefault('employment');
				}else{
					alert(data);
				}
			}
		});
	}else{
		alert("Please take note the required fields!");
	}
}

function edit_employment_contract(){

	var emp_id = $("[name='emp_id']").val();
	var rec_no = $("[name='rec_no']").val();
	var comp_code = $("[name='comp_code']").val();
	var bunit_code = $("[name='bunit_code']").val();
	var dept_code = $("[name='dept_code']").val();
	var sec_code = $("[name='sec_code']").val();
	var ssec_code = $("[name='ssec_code']").val();
	var unit_code = $("[name='unit_code']").val();
	var start_date = $("[name='estart_date']").val();
	var end_date = $("[name='eend_date']").val();
	var contract_position = $("[name='contract_position']").val();
	var contract_emptype = $("[name='contract_emptype']").val();
	var contract_cstatus = $("[name='contract_cstatus']").val();
	var contract_positionlevel = $("[name='contract_positionlevel']").val();
	var contract_lodging = $("[name='contract_lodging']").val().trim();
	var contract_positiondesc = $("[name='contract_positiondesc']").val().trim();
	var contract_remarks = $("[name='contract_remarks']").val().trim();
	
	// alert(emp_id+"/"+rec_no+"/"+comp_code+"/"+bunit_code+"/"+dept_code+"/"+sec_code+"/"+ssec_code+"/"+unit_code+"/"+start_date+"/"+end_date);
	// alert(contract_position+"/"+contract_emptype+"/"+contract_cstatus+"/"+contract_positionlevel+"/"+contract_lodging+"/"+contract_positiondesc+"/"+contract_remarks);
	if(comp_code && start_date && end_date && contract_emptype && contract_cstatus){						
		$.ajax({
			type : "POST",
			url : "ajax.php?request=edit_contract",
			data : { comp_code : comp_code, bunit_code : bunit_code, dept_code : dept_code, sec_code : sec_code, ssec_code : ssec_code, unit_code : unit_code, start_date : start_date, end_date : end_date, contract_position : contract_position, contract_emptype : contract_emptype, contract_cstatus : contract_cstatus, contract_positionlevel : contract_positionlevel, contract_lodging : contract_lodging, contract_positiondesc : contract_positiondesc, contract_remarks : contract_remarks, rec_no : rec_no, emp_id : emp_id },
			success : function(data){
				if(data == 'Ok'){
					alert("Successfully Updated!");
					getdefault('employment');
				}else{
					alert(data);
				}
			}
		});
	}else{
		alert("Please take note the required fields!");
	}
}

function rprint(rec){		

	var r = confirm("Generate Permit-To-Work now?")
	if(r == true){	
		
		window.open("../report/permittowork_NESCO.php?table=employmentrecord_&&rec="+rec,"_blank");
	}
}

function comp_code(id){
	$.ajax({
		type : "POST",
		url : "ajax.php?load=bunit",
		data : { id : id },
		success : function(data){
			$("[name='bunit_code']").html(data);
			$("[name='dept_code']").val('');
			$("[name='sec_code']").val('');
			$("[name='ssec_code']").val('');
			$("[name='unit_code']").val('');
		}
	});
}

function bunit_code(id){
	$.ajax({
		type : "POST",
		url : "ajax.php?load=dept",
		data : { id : id },
		success : function(data){
			$("[name='dept_code']").html(data);
			$("[name='sec_code']").val('');
			$("[name='ssec_code']").val('');
			$("[name='unit_code']").val('');
		}
	});
}

function dept_code(id){
	$.ajax({
		type : "POST",
		url : "ajax.php?load=section",
		data : { id : id },
		success : function(data){
			$("[name='sec_code']").html(data);
			$("[name='ssec_code']").val('');
			$("[name='unit_code']").val('');
		}
	});
}

function sec_code(id){
	$.ajax({
		type : "POST",
		url : "ajax.php?load=ssection",
		data : { id : id },
		success : function(data){
			$("[name='ssec_code']").html(data);
			$("[name='unit_code']").val('');
		}
	});
}

function ssec_code(id){
	$.ajax({
		type : "POST",
		url : "ajax.php?load=unit",
		data : { id : id },
		success : function(data){
			$("[name='unit_code']").html(data);
		}
	});
}
/* end here yow*/
function editemployment1(rec){

	$("#editemployment1").modal({
		backdrop: "static",
		keyboard: false
	});

	$("#contract_title").html("Edit Contract");
	$("#add_contact_form").hide();
	$("#edit_contact_form").html('');
	$("#edit_contact_form").show().html('<img src="../images/loading19.gif"> please wait...');
	var employment = "employee";
	$.ajax({
		type : "POST",
		url : "ajax.php?request=editContract",
		data : { rec : rec, employment : employment },
		success : function(data){
			$("#edit_contact_form").html(data);
		}
	});
	$("#contact_form").modal("show");
}
function openemployment(rec,emp) // open employment details 
{
	console.log("changing src"); //please do not remove
    var myIframe = document.getElementById("employmentdet"); //id of the frame	
	myIframe.src = "pages/profile/employment_details1.php?rec="+rec+"&empid="+emp;		
}
function openemployment1(rec, emp){
	console.log("changing src"); //please do not remove
    var myIframe = document.getElementById("employmentdet"); //id of the frame	
	myIframe.src = "pages/profile/employment_details.php?rec="+rec+"&empid="+emp;	
}
function openappraisal(rec,emp,epascode) //open appraisal details
{
	console.log("changing src"); //please do not remove
    var myIframe = document.getElementById("appraisaldet"); //id of the frame	
	myIframe.src = "pages/profile/employee_appraisal.php?rec="+rec+"&emp="+emp+"&epascode="+epascode;	
}
$(document).ready(function(){ $('#menu').tabify(); });
/*function editImage(emp)
{	
	console.log("changing src"); //please do not remove	
    var myIframe = document.getElementById("imag");
    myIframe.src = "pages/add_photo.php?emp="+emp;
}*/

function editImage(emp){

	$("#upload_photo").modal({
        backdrop: 'static',
        keyboard: false
    });

    $("#upload_photo").modal("show");

    // var data = $("[name = 'filename']").val();
    $.ajax({
        type : "POST",
        url  : "functionquery.php?request=getEmpPhoto",
        data : { empId:emp },
        success : function(data){
        	data = data.trim();

        	$('#preview_photo').removeAttr('src');    
		    $('#upload_scanned_photo').val('');
		    $('#clearphoto').hide();
		    
		    if(data != ''){
		        document.getElementById("preview_photo").src = data;      
		        $('#upload_scanned_photo').hide();        
		        $('#upload_scanned_photo_change').show();
		    }   
		    else{
		        $('#upload_scanned_photo').show();        
		        $('#upload_scanned_photo_change').hide();
		    }
		}
    });
}

function empDetails(emp)
{// displays the iframe and passes
    console.log("changing src"); //please do not remove	
    var myIframe = document.getElementById("det");
    myIframe.src = "edit_employee_details.php?emp="+emp;
}
function view_req(field,appid,req,table)
{ 
  console.log("changing src"); //please do not remove 
  var myIframe = document.getElementById("viewreq"); 
  myIframe.src = "view_requirements.php?appid="+appid+"&req="+req+"&table="+table+"&field="+field;
}
function edit_benefits()
{		
	document.getElementById('ph').disabled = false;
	document.getElementById('sss').disabled = false;
	document.getElementById('pagibig').disabled = false;
	document.getElementById('pagibigrtn').disabled = false; 
	document.getElementById('tinno').disabled = false; 
	
	$('#update-benefits').show();
	$('#edit-benefits').hide();
}
function edit_skills()
{		
	document.getElementById('hobbies').disabled = false;
	document.getElementById('skills').disabled = false;
	$('#update-skills').show();
	$('#edit-skills').hide();
}
function edit_apphis()
{		
	document.getElementById('dateapplied').disabled = false;
	document.getElementById('datebriefed').disabled = false;
	document.getElementById('datehired').disabled = false;
	document.getElementById('aeregular').disabled = false;
	document.getElementById('dateexamined').disabled = false;
	document.getElementById('posapplied').disabled = false;
	document.getElementById('examres').disabled = false;
	$('#update-apphis').show();
	$('#edit-apphis').hide();
}
function edit_remarks()
{		
	document.getElementById('remarks').disabled = false;
	$('#save-remarks').show();
	$('#edit-remarks').hide();
}
function edit_basicinfo()
{
	document.getElementById('fname').disabled = false;
	document.getElementById('mname').disabled = false;
	document.getElementById('lname').disabled = false;
	document.getElementById('suffix').disabled = false;
	document.getElementById('datebirth').disabled = false;
	document.getElementById('gender').disabled = false;
	document.getElementById('civilstatus').disabled = false;
	document.getElementById('citizenship').disabled = false;
	document.getElementById('religion').disabled = false;
	document.getElementById('height').disabled = false;
	document.getElementById('weight').disabled = false;
	document.getElementById('bloodtype').disabled = false;
	$('#update-basicinfo').show();
	$('#edit-basicinfo').hide();
}
function edit_family()
{		
	document.getElementById('mother').disabled = false;
	document.getElementById('father').disabled = false;
	document.getElementById('guardian').disabled = false;
	document.getElementById('spouse').disabled = false;
	$('#update-family').show();
	$('#edit-family').hide();
}
function edit_contact()
{		
	document.getElementById('homeaddress').disabled = false;
	document.getElementById('cityaddress').disabled = false;
	document.getElementById('contactperson').disabled = false;
	document.getElementById('contactpersonaddress').disabled = false;
	document.getElementById('contactpersonno').disabled = false;
	document.getElementById('cellno').disabled = false;
	document.getElementById('telno').disabled = false;
	document.getElementById('email').disabled = false;
	document.getElementById('fb').disabled = false;
	document.getElementById('twitter').disabled = false;
	$('#update-contact').show();
	$('#edit-contact').hide();
}
function edit_educ()
{		
	document.getElementById('attainment').disabled = false;
	document.getElementById('school').disabled = false;
	document.getElementById('course').disabled = false;
	$('#update-educ').show();
	$('#edit-educ').hide();
}
/*show contract form*/
function show_contract(){ 
	$("[name='comp_code']").val('');
	$("[name='bunit_code']").val('');
	$("[name='dept_code']").val('');
	$("[name='sec_code']").val('');
	$("[name='ssec_code']").val('');
	$("[name='unit_code']").val('');
	$("[name='start_date']").val('');
	$("[name='end_date']").val('');
	$("[name='contract_position']").val('');
	$("[name='contract_emptype']").val('');
	$("[name='contract_cstatus']").val('');
	$("[name='contract_positionlevel']").val('');
	$("[name='contract_lodging']").val('');
	$("[name='contract_positiondesc']").val('');
	$("[name='contract_remarks']").val('');
	$("#add_contact_form").show();
	$("#edit_contact_form").html('').hide();
	$("#contact_form").modal("show");
}
function enabledupload(){
	$('#uploadcontract').show();
	$('#contractmsg').hide();
}
function startUpload201Files(){
	$("#msg_alert").html('');
	$("#upload_form").hide();
	$("#upload_process").show();	
}
function stopUpload(success,id,input){

	if(success == 1){
		$("#msg_alert").html('<div class="alert alert-success"><center>Successfully Upload!</center></div>');
		$("#upload_form").show();
		$("#upload_process").hide();
		$("[name='empid']").val(id);
		var empname = $("[name = 'empidD']").val();
		$("[name='input']").val(empname);
		$("[name = 'cat]").val("");
		$("#file_upload").val("");
		$("#201Files").show();

		$.ajax({
			type : "POST",
			url : "ajax.php?request=load201Files",
			data : { input : input },
			success : function(data){
				$("#201Files").html(data).fadeIn(500);
				// $("[name='empname']").attr("disabled",true);
			}
		});
	} else {
		$("#upload_form").show();
		$("#upload_process").hide();
		$("#msg_alert").html('<div class="alert alert-danger"><center>Failed to upload, please check your file!</center></div>');
	}
}
function set_record(record_no,table)
{	
	$('#preview_contract').removeAttr('src');	
	$('#upload_scanned_epas').val('');
	$('#upload_scanned_clearance').val('');
	$('#upload_scanned_contract').val('');
	$('#clearclearance').hide();
	$('#clearcontract').hide();
	$('#cleareocappraisal').hide();
	
	$.ajax({
		type : "POST",
		url : "functionquery.php?request=getscannedcontract",
		data : { table : table, record_no : record_no },
		success : function(data){
			data = data.trim();	
			if(data != ''){
				var alternative = '../images/system/contract_msg.jpg'
				document.getElementById("preview_contract").src = alternative;		
				$('#upload_scanned_contract').hide();		
				$('#upload_scanned_contract_change').show();
			}	
			else{
				$('#upload_scanned_contract').show();		
				$('#upload_scanned_contract_change').hide();
			}
		}
	});
	$('#preview_clearance').removeAttr('src');	
	$.ajax({
		type : "POST",
		url : "functionquery.php?request=getscannedclearance",
		data : { table : table, record_no : record_no },
		success : function(data){
			data = data.trim();
			if(data != ''){				
				document.getElementById("preview_clearance").src = data;	
				$('#upload_scanned_clearance').hide();		
				$('#upload_scanned_clearance_change').show();								
			}else{
				$('#upload_scanned_clearance_change').hide();	
				$('#upload_scanned_clearance').show();
			}			
		}
	});
	$('#preview_epas').removeAttr('src');	
	$.ajax({
		type : "POST",
		url : "functionquery.php?request=getscannedepas",
		data : { table:table, record_no:record_no },
		success : function(data){
			data = data.trim();			
			if(data != 0){
				if(data > 0){
					var alternative = '../images/system/epas_msg.jpg'
					document.getElementById("preview_epas").src = alternative;	
					$('#upload_scanned_epas').hide();
				}
				else
				{				
					document.getElementById("preview_epas").src = data;	
					$('#upload_scanned_epas').hide();
				}
			}
			else{
				$('#upload_scanned_epas').show();
			}			
		}
	});
	
	document.getElementById('contract_table').value = table;	
	document.getElementById('contract_record_no').value = record_no;	
}
function validateForm(imgid)
{
	var img = document.getElementById(imgid).value;		
	var res = '';
	var i = img.length-1;	
	while(img[i] != "."){
		res = img[i] + res;		
		i--;
	}	
	//checks the file format
	if(res != "PNG" && res != "jpg" && res !="JPG" && res != "png"){				
		document.getElementById('upload_scanned_contract').value = '';
		alert('Invalid File Format. Take note on the allowed file!');
		//$('#msgi').html('Invalid File Format. Only this file format [.pdf,.jpg,.png] are acceptable!');
		//$("#msgi").show().fadeOut(5000);	
		//return false;		
		return 1;
	}	
	//checks the filesize- should not be greater than 2MB
	var uploadedFile = document.getElementById(imgid);
    var fileSize = uploadedFile.files[0].size < 1024 * 1024 * 2;
	if(fileSize == false){
		document.getElementById(imgid).value = '';
		alert('The size of the file exceeds 2MB!')		
		//$('#msgi').html('The size of the file exceeds 2MB!');
		//$("#msgi").show().fadeOut(5000);	
		//return false;
		return 1;
	}	
}

function viewJobTrans(transNo){

	$("#jobtransfer").modal({
        backdrop: 'static',
        keyboard: false
    });

    $("#jobtransfer").modal("show");

    $.ajax({
        type : "POST",
        url  : "functionquery.php?request=getEmpJobTransFile",
        data : { transNo:transNo },
        success : function(data){
        	data = data.trim();
		    if(data != ''){
		        document.getElementById("view_jobTrans").src = data;  
		    }
		}
    });
}
function startUpload201Files(){
	$("#msg_alert").html('');
	$("#upload_form").hide();
	$("#upload_process").show();	
}
function upload201files(empid){
    $("#upload_modal").modal({
        backdrop: 'static',
        keyboard: false
      });

    $("#upload_modal").modal("show");

    $("#upload_form").show();
    $("#upload_process").hide();
	$(".upload_modal").fadeIn(200);
	$("[name='empid']").val(empid);
	var empname = $("[name = 'empidD']").val();
	$("[name='input']").val(empname);

}
function closeUploadModal(){
	if(!confirm("Are you sure do you want to cancel uploading 201 files?")) return  false;
	$(".masked").hide();
	$(".upload_modal").fadeOut(200);
	$("[name='cat']").val('');
	$("#file_upload").val('');
	$("#msg_alert").html('');
	getdefault('201doc');
	//window.location='employee_details.php?com=<?php echo $empid;?>';
}	
var title = Array("","Application Letter","Background Investigation","Birth Certificate","Cedula","Clearance","Contract","Drug Test","Fingerprint","Job Transfers","KRA","Marriage Certificate","Medical Certificate","Misconduct","Orientation Certificate","Others","Parent Consent","Police Clearance","Recommendation Letter","Regularization","Resume","Showcause","Sketch","SSS","Suspension","Transcript of Records","Promotion","Resignation Letter");
function view201Files(cat,id){
	$("#201Files_title").html(title[cat]);
	$("#201FilesData").html('<img src="../images/loading19.gif"> please wait...');
	//alert(cat)
	$.ajax({
		type : "POST",
		url : "ajax.php?request=load201files",
		data : { cat : cat, id : id },
		success : function(data){
			$("#201FilesData").html(data);
		}
	});
	$("#view201files").modal("show");
}

function pagi(cat,p,id){
	$("#201FilesData").html('<img src="../images/loading19.gif"> please wait...');
	$.ajax({
		type : "POST",
		url : "ajax.php?request=load201files",
		data : { cat : cat, id : id, p : p },
		success : function(data){
			$("#201FilesData").html(data);
		}
	});
}
</script>