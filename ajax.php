<?php
session_start();
include("connection.php");

/*
$funct = new gallery();

if(@$_GET['req'] == "date"){
	?>
	<tr id='s<?php echo $_POST['counter'];?>' class="f_color">+
	<td colspan='3'></td>
	<td>DATE BEGIN: </td>
	<td>
		<input type='text' id='date<?php echo $_POST['counter'];?>' class='date2' id='cloz_box' name='datpick[]' style="width:95%; font-family: arial; font-size: 14px;	font-weight:bold;" required autocomplete='off'>
		<a href='javascript:void(0);' onclick="clox('<?php echo $_POST['counter'];?>')"><b>x</b></a><br/>
	</td>
	</tr>
	
	<script>
		function clox(id)
		{
			alert(id);
			$("#test tr[id^='s"+id+"']").fadeOut(500);
		}
		$("input[type='text'][id^=date]").click(function(){
			var id = this.id;
			var i = id.substr(5,1);
			$( "input[type='text'][id^='date"+i+"']").datepicker({ dateFormat: "M-dd-yy", changeMonth: true, changeYear: true, showButtonPanel: true });
		});
	</script>
	<?php
}
 */
if(@$_GET['load'] == 'bunit')
{
	$sql = mysql_query
			("
				SELECT
					*
				FROM
					locate_business_unit
				WHERE
					company_code = '".$_POST['id']."' and status = 'active'
				ORDER BY
					business_unit ASC
			") or die(mysql_error());
	?>
		<option value="">Select</option>
	<?php
	while($res=mysql_fetch_array($sql)){ ?>
		<option value="<?php echo $res['company_code']."/".$res['bunit_code'];?>">
			<?php echo $res['business_unit'];?>
		</option>
	<?php }
}
elseif(@$_GET['load'] == 'bunits')
{
	
	$sql = mysql_query
			("
				SELECT
					*
				FROM
					locate_business_unit
				WHERE
					company_code = '".$_POST['id']."'
				ORDER BY
					business_unit ASC
			") or die(mysql_error());
	?>
		<option value="">Select</option>
	<?php
	while($res=mysql_fetch_array($sql)){
		for($x=0;$x<count($d);$x++){
			if($res['company_code'] == $d[$x]['cc'] && $res['bunit_code'] == $d[$x]['bc']){
				if(!$tmp){
					$tmp = $res['bunit_code']; ?>
					<option value="<?php echo $res['company_code']."/".$res['bunit_code'];?>">
						<?php echo $res['business_unit'];?>
					</option>
		<?php 	}
				if($tmp != $d[$x]['bc']){
					$tmp = $res['bunit_code']; ?>
					<option value="<?php echo $res['company_code']."/".$res['bunit_code'];?>">
						<?php echo $res['business_unit'];?>
					</option>
		<?php	}
			}
		}
	}
}	
elseif(@$_GET['load'] == 'dept')
{
	$id = explode("/",$_POST['id']);
	$sql = mysql_query
			("
				SELECT
					*
				FROM
					locate_department
				WHERE
					company_code = '".$id[0]."'
				AND
					bunit_code = '".$id[1]."' AND status = 'active'
				ORDER BY
					dept_name ASC
			") or die(mysql_error());
	?>
		<option value="">Select</option>
	<?php
	while($res=mysql_fetch_array($sql)){ ?>
		<option value="<?php echo $res['company_code']."/".$res['bunit_code']."/".$res['dept_code'];?>">
			<?php echo $res['dept_name'];?>
		</option>
	<?php }
}
elseif(@$_GET['load'] == 'depts') 
{
	$id = explode("/",$_POST['id']);
	$sql = mysql_query
			("
				SELECT
					*
				FROM
					locate_department
				WHERE
					company_code = '".$id[0]."'
				AND
					bunit_code = '".$id[1]."' 
				AND status = 'active'
				ORDER BY
					dept_name ASC
			") or die(mysql_error());
	?>
		<option value="">Select</option>
	<?php
	while($res=mysql_fetch_array($sql)){ ?>
		<option value="<?php echo $res['company_code']."/".$res['bunit_code']."/".$res['dept_code'];?>">
			<?php echo $res['dept_name'];?>
		</option>
	<?php }
}
elseif(@$_GET['load'] == 'section') 
{
	$id = explode("/",$_POST['id']);
	$sql = mysql_query
			("
				SELECT
					*
				FROM
					locate_section
				WHERE
					company_code = '".$id[0]."'
				AND
					bunit_code = '".$id[1]."'
				AND
					dept_code = '".$id[2]."'
				AND status = 'active'
				ORDER BY
					section_name ASC
			") or die(mysql_error());
	?>
		<option value="">Select</option>
	<?php
	while($res=mysql_fetch_array($sql)){ ?>
		<option value="<?php echo $res['company_code']."/".$res['bunit_code']."/".$res['dept_code']."/".$res['section_code'];?>">
			<?php echo $res['section_name'];?>
		</option>
	<?php }
}
elseif(@$_GET['load'] == 'sections')
{
	$id = explode("/",$_POST['id']);
	$sql = mysql_query
			("
				SELECT
					*
				FROM
					locate_section
				WHERE
					company_code = '".$id[0]."'
				AND
					bunit_code = '".$id[1]."'
				AND
					dept_code = '".$id[2]."'
				AND status = 'active'
				ORDER BY
					section_name ASC
			") or die(mysql_error());
	?>
		<option value="">Select</option>
	<?php
	while($res=mysql_fetch_array($sql)){ ?>
		<option value="<?php echo $res['company_code']."/".$res['bunit_code']."/".$res['dept_code']."/".$res['section_code'];?>">
			<?php echo $res['section_name'];?>
		</option>
	<?php }	
}
elseif(@$_GET['load'] == 'ssection')
{
	$id = explode("/",$_POST['id']);
	$sql = mysql_query
			("
				SELECT
					*
				FROM
					locate_sub_section
				WHERE
					company_code = '".$id[0]."'
				AND
					bunit_code = '".$id[1]."'
				AND
					dept_code = '".$id[2]."'
				AND
					section_code = '".$id[3]."'
				AND status = 'active'
				ORDER BY
					sub_section_name ASC
			") or die(mysql_error());
	?>
		<option value="">Select</option>
	<?php
	while($res=mysql_fetch_array($sql)){ ?>
		<option value="<?php echo $res['company_code']."/".$res['bunit_code']."/".$res['dept_code']."/".$res['section_code']."/".$res['sub_section_code'];?>">
			<?php echo $res['sub_section_name'];?>
		</option>
	<?php }
}
elseif(@$_GET['load'] == 'ssections') 
{
	$id = explode("/",$_POST['id']);
	$sql = mysql_query
			("
				SELECT
					*
				FROM
					locate_sub_section
				WHERE
					company_code = '".$id[0]."'
				AND
					bunit_code = '".$id[1]."'
				AND
					dept_code = '".$id[2]."'
				AND
					section_code = '".$id[3]."'
				AND status = 'active'
				ORDER BY
					sub_section_name ASC
			") or die(mysql_error()); ?>
		<option value="">Select</option><?php
		
	while($res=mysql_fetch_array($sql)){ ?>
		<option value="<?php echo $res['company_code']."/".$res['bunit_code']."/".$res['dept_code']."/".$res['section_code']."/".$res['sub_section_code'];?>">
			<?php echo $res['sub_section_name'];?>
		</option>
	<?php }
} 	
elseif(@$_GET['load'] == 'unit')
{
	$id = explode("/",$_POST['id']);
	$sql = mysql_query
			("
				SELECT
					*
				FROM
					locate_unit
				WHERE
					company_code = '".$id[0]."'
				AND
					bunit_code = '".$id[1]."'
				AND
					dept_code = '".$id[2]."'
				AND
					section_code = '".$id[3]."'
				AND
					sub_section_code = '".$id[4]."'
				AND status = 'active'
				ORDER BY
					unit_name ASC
			") or die(mysql_error());	?>
		<option value="">Select</option> <?php
		
	while($res=mysql_fetch_array($sql)){ ?>
		<option value="<?php echo $res['company_code']."/".$res['bunit_code']."/".$res['dept_code']."/".$res['section_code']."/".$res['sub_section_code']."/".$res['unit_code'];?>">
			<?php echo $res['unit_name'];?>
		</option>
	<?php }
}	

elseif(@$_GET['request'] == 'load201Files1')
{
	$id = explode("*",$_POST['input']);
	$name = explode("*",$_POST['input']);
	$id = $id[0];
	$check = strpos($_POST['input'],'*');
	
	if($check == true){	
		// get the number of files in application letter
		$app_query = mysql_query(
						"SELECT
							requirement_name
						 FROM
							application_initialreq
						 WHERE
							app_code = '".$id."'
						 AND
							requirement_name = 'Application Letter'"
					 ) or die(mysql_error());
		$total_appletter = mysql_num_rows($app_query);
		// get the number of files in background investigation
		$bi_query = mysql_query(
						"SELECT
							requirement_name
						 FROM
							application_finalreq
						 WHERE
							app_id = '".$id."'
						 AND
							requirement_name = 'BI'"
					  ) or die(mysql_error());
		$total_bi = mysql_num_rows($bi_query);
		// get the number of files in birth certificate
		$bc_query = mysql_query(
						"SELECT
							requirement_name
						 FROM
							application_finalreq
						 WHERE
							app_id = '".$id."'
						 AND
							requirement_name = 'BirthCertificate'"
					) or die(mysql_error());
		$total_bc = mysql_num_rows($bc_query);
		// get the number of files in cedula
		$c_query = mysql_query(
						"SELECT
							requirement_name
						 FROM
							application_finalreq
						 WHERE
							app_id = '".$id."'
						 AND
							requirement_name = 'Cedula'"
				   ) or die(mysql_error());
		$total_c = mysql_num_rows($c_query);
		// get the number of files in fingerprint
		$fp_query = mysql_query(
						"SELECT
							requirement_name
						 FROM
							application_finalreq
						 WHERE
							app_id = '".$id."'
						 AND
							requirement_name = 'Fingerprint'"
					) or die(mysql_error());
		$total_fp = mysql_num_rows($fp_query);
		// get the number of files in marriage contract
		$mc_query = mysql_query(
						"SELECT
							requirement_name
						 FROM
							application_finalreq
						 WHERE
							app_id = '".$id."'
						 AND
							requirement_name = 'MarriageCertificate'"
					) or die(mysql_error());
		$total_mc = mysql_num_rows($mc_query);
		// get the number of files in medical certificate
		$medc_query = mysql_query(
						"SELECT
							requirement_name
						 FROM
							application_finalreq
						 WHERE
							app_id = '".$id."'
						 AND
							requirement_name = 'MedicalCertificate'"
					) or die(mysql_error());
		$total_medc = mysql_num_rows($medc_query);
		// get the number of files in orientation certificate
		$oc_query = mysql_query(
						"SELECT
							requirement_name
						 FROM
							application_finalreq
						 WHERE
							app_id = '".$id."'
						 AND
							requirement_name = 'OrientationCertificate'"
					) or die(mysql_error());
		$total_oc = mysql_num_rows($oc_query);
		// get the number of files in parent consent
		$pc_query = mysql_query(
						"SELECT
							requirement_name
						 FROM
							application_finalreq
						 WHERE
							app_id = '".$id."'
						 AND
							requirement_name = 'ParentsConsent'"
					) or die(mysql_error());
		$total_pc = mysql_num_rows($pc_query);
		// get the number of files in police clearance
		$polc_query = mysql_query(
						"SELECT
							requirement_name
						 FROM
							application_finalreq
						 WHERE
							app_id = '".$id."'
						 AND
							requirement_name = 'PoliceClearance'"
					) or die(mysql_error());
		$total_polc = mysql_num_rows($polc_query);
		// get the number of files in recommendation letter
		$r_query = mysql_query(
						"SELECT
							requirement_name
						 FROM
							application_finalreq
						 WHERE
							app_id = '".$id."'
						 AND
							requirement_name = 'Recommendation'"
					) or die(mysql_error());
		$total_r = mysql_num_rows($r_query);
		// get the number of files in resume
		$res_query = mysql_query(
						"SELECT
							requirement_name
						 FROM
							application_initialreq
						 WHERE
							app_code = '".$id."'
						 AND
							requirement_name = 'Resume'"
					) or die(mysql_error());
		$total_re = mysql_num_rows($res_query);
		// get the number of files in sketch
		$sk_query = mysql_query(
						"SELECT
							requirement_name
						 FROM
							application_finalreq
						 WHERE
							app_id = '".$id."'
						 AND
							requirement_name = 'Sketch'"
					) or die(mysql_error());
		$total_sk = mysql_num_rows($sk_query);
		// get the number of files in sss
		$sss_query = mysql_query(
						"SELECT
							requirement_name
						 FROM
							application_finalreq
						 WHERE
							app_id = '".$id."'
						 AND
							requirement_name = 'SSS'"
					) or die(mysql_error());
		$total_sss = mysql_num_rows($sss_query);
		// get the number of files in tor
		$tor_query = mysql_query(
						"SELECT
							requirement_name
						 FROM
							application_initialreq
						 WHERE
							app_code = '".$id."'
						 AND
							requirement_name = 'Transcript of Records'"
					) or die(mysql_error());
		$total_tor = mysql_num_rows($tor_query);
		
		//updated 6-27-15 for others
		$other_query = mysql_query(
						"SELECT
							requirement_name
						 FROM
							application_otherreq
						 WHERE
							app_id = '".$id."'
							and requirement_name != 'Regularization'
							and requirement_name != 'Job Transfers'
						"
					) or die(mysql_error());
		$total_others = mysql_num_rows($other_query);
		
		$dt_query = mysql_query(
						"SELECT
							requirement_name
						 FROM
							application_finalreq
						 WHERE
							app_id = '".$id."'
						AND
							requirement_name = 'Drug Test' "
					) or die(mysql_error());
		$total_dt = mysql_num_rows($dt_query);
		
		$misc_query = mysql_query(
						"SELECT
							requirement_name
						 FROM
							applicant_violation
						 WHERE
							app_id = '".$id."'
						AND
							requirement_name = 'Misconduct' "
					) or die(mysql_error());
		$total_misc = mysql_num_rows($misc_query);
		
		$shc_query = mysql_query(
						"SELECT
							requirement_name
						 FROM
							applicant_violation
						 WHERE
							app_id = '".$id."'
						AND
							requirement_name = 'Showcause' "
					) or die(mysql_error());
		$total_shc = mysql_num_rows($shc_query);
		
		$sus_query = mysql_query(
						"SELECT
							requirement_name
						 FROM
							applicant_violation
						 WHERE
							app_id = '".$id."'
						AND
							requirement_name = 'Suspension' "
					) or die(mysql_error());
		$total_sus = mysql_num_rows($sus_query);
		
		$reg_query = mysql_query(
						"SELECT
							requirement_name
						 FROM
							application_otherreq
						 WHERE
							app_id = '".$id."'
						 AND 
							requirement_name = 'Regularization'
						"
					) or die(mysql_error());
		$total_reg = mysql_num_rows($reg_query);
		$jobtrans_query = mysql_query(
						"SELECT
							requirement_name
						 FROM
							application_otherreq
						 WHERE
							app_id = '".$id."'
						 AND 
							requirement_name = 'Job Transfers'
						"
					) or die(mysql_error());
		$total_jobtrans = mysql_num_rows($jobtrans_query);
		$promotion_query = mysql_query(
						"SELECT
							requirement_name
						 FROM
							application_otherreq
						 WHERE
							app_id = '".$id."'
						 AND 
							requirement_name = 'Promotion'
						"
					) or die(mysql_error());
		$total_promotion = mysql_num_rows($promotion_query);
		$resigletter_query = mysql_query(
						"SELECT
							emp_id
						 FROM
							termination
						 WHERE
							emp_id = '".$id."'
						 AND 
							resignation_letter != ''
						"
					) or die(mysql_error());
		$total_resigletter = mysql_num_rows($resigletter_query);

		$clearance_query = mysql_query(
						"SELECT
							clearance
						 FROM
							employmentrecord_
						 WHERE
							emp_id = '".$id."'
						 AND 
							clearance != ''
						"
					) or die(mysql_error());
		$total_clearance = mysql_num_rows($clearance_query);
		
		?>
		<?php
		//dli allowed : mam thelma ug ma lucy sa payroll | updated:10/2/15 11:22am
  		?>
		<div class="col-md-1 col-md-offset-11">
			<button class='btn btn-xs btn-primary' onclick="upload201files('<?php echo $id?>')"><span class="glyphicon glyphicon-upload" ></span> Upload</button>
		</div>
		<br>
		<br>
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="row">
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">Application Letter</span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right"><?php echo $total_appletter;?></span>
								<center>
									<a href="javascript:void" data-toggle='modal' data-target='#view201files' onclick="view201Files(1,'<?php echo $id;?>')" title="click to view"><img src="../images/docs.png" class="img"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">Background Investigation</span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right"><?php echo $total_bi;?></span>
								<center>
									<a href="javascript:void" data-toggle='modal' data-target='#view201files' onclick="view201Files(2,'<?php echo $id;?>')" title="click to view"><img src="../images/docs.png" class="img"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">Birth Certificate</span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right"><?php echo $total_bc;?></span>
								<center>
									<a href="javascript:void" data-toggle='modal' data-target='#view201files' onclick="view201Files(3,'<?php echo $id;?>')" title="click to view"><img src="../images/docs.png" class="img"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">Cedula</span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right"><?php echo $total_c;?></span>
								<center>
									<a href="javascript:void" data-toggle='modal' data-target='#view201files' onclick="view201Files(4,'<?php echo $id;?>')" title="click to view"><img src="../images/docs.png" class="img"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">Clearance</span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right"><?php echo $total_clearance;?></span>
								<center>
									<a href="javascript:void" data-toggle='modal' data-target='#view201files' onclick="view201Files(5,'<?php echo $id;?>')" title="click to view"><img src="../images/docs.png" class="img"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">Contract</span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right">0</span>
								<center>
									<a href="javascript:void" data-toggle='modal' data-target='#view201files' onclick="view201Files(6,'<?php echo $id;?>')" title="click to view"><img src="../images/docs.png" class="img"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">Drug Test</span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right"><?php echo $total_dt;?></span>
								<center>
									<a href="javascript:void" data-toggle='modal' data-target='#view201files' onclick="view201Files(7,'<?php echo $id;?>')" title="click to view"><img src="../images/docs.png" class="img"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">Fingerprint</span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right"><?php echo $total_fp;?></span>
								<center>
									<a href="javascript:void" data-toggle='modal' data-target='#view201files' onclick="view201Files(8,'<?php echo $id;?>')" title="click to view"><img src="../images/docs.png" class="img"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">Job Transfers</span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right"><?php echo $total_jobtrans;?></span>
								<center>
									<a href="javascript:void" data-toggle='modal' data-target='#view201files' onclick="view201Files(9,'<?php echo $id;?>')" title="click to view"><img src="../images/docs.png" class="img"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">KRA</span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right">0</span>
								<center>
									<a href="javascript:void" data-toggle='modal' data-target='#view201files' onclick="view201Files(10,'<?php echo $id;?>')" title="click to view"><img src="../images/docs.png" class="img"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">Marriage Certificate</span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right"><?php echo $total_mc;?></span>
								<center>
									<a href="javascript:void" data-toggle='modal' data-target='#view201files' onclick="view201Files(11,'<?php echo $id;?>')" title="click to view"><img src="../images/docs.png" class="img"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">Medical Certificate</span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right"><?php echo $total_medc;?></span>
								<center>
									<a href="javascript:void" data-toggle='modal' data-target='#view201files' onclick="view201Files(12,'<?php echo $id;?>')" title="click to view"><img src="../images/docs.png" class="img"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">Misconduct</span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right"><?php echo $total_misc;?></span>
								<center>
									<a href="javascript:void" data-toggle='modal' data-target='#view201files' onclick="view201Files(13,'<?php echo $id;?>')" title="click to view"><img src="../images/docs.png" class="img"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">Orientation Certificate</span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right"><?php echo $total_oc;?></span>
								<center>
									<a href="javascript:void" data-toggle='modal' data-target='#view201files' onclick="view201Files(14,'<?php echo $id;?>')" title="click to view"><img src="../images/docs.png" class="img"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">Others</span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right"><?php echo $total_others;?></span>
								<center>
									<a href="javascript:void" data-toggle='modal' data-target='#view201files' onclick="view201Files(15,'<?php echo $id;?>')" title="click to view"><img src="../images/docs.png" class="img"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">Parent Consent</span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right"><?php echo $total_pc;?></span>
								<center>
									<a href="javascript:void" data-toggle='modal' data-target='#view201files' onclick="view201Files(16,'<?php echo $id;?>')" title="click to view"><img src="../images/docs.png" class="img"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">Police Clearance</span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right"><?php echo $total_polc;?></span>
								<center>
									<a href="javascript:void" data-toggle='modal' data-target='#view201files' onclick="view201Files(17,'<?php echo $id;?>')" title="click to view"><img src="../images/docs.png" class="img"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">Recommendation Letter</span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right"><?php echo $total_r;?></span>
								<center>
									<a href="javascript:void" data-toggle='modal' data-target='#view201files' onclick="view201Files(18,'<?php echo $id;?>')" title="click to view"><img src="../images/docs.png" class="img"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">Regularization</span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right"><?php echo $total_reg;?></span>
								<center>
									<a href="javascript:void" data-toggle='modal' data-target='#view201files' onclick="view201Files(19,'<?php echo $id;?>')" title="click to view"><img src="../images/docs.png" class="img"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">Resume</span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right"><?php echo $total_re;?></span>
								<center>
									<a href="javascript:void" data-toggle='modal' data-target='#view201files' onclick="view201Files(20,'<?php echo $id;?>')" title="click to view"><img src="../images/docs.png" class="img"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">Showcause</span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right"><?php echo $total_shc;?></span>
								<center>
									<a href="javascript:void" data-toggle='modal' data-target='#view201files' onclick="view201Files(21,'<?php echo $id;?>')" title="click to view"><img src="../images/docs.png" class="img"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">Sketch</span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right"><?php echo $total_sk;?></span>
								<center>
									<a href="javascript:void" data-toggle='modal' data-target='#view201files' onclick="view201Files(22,'<?php echo $id;?>')" title="click to view"><img src="../images/docs.png" class="img"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">SSS</span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right"><?php echo $total_sss;?></span>
								<center>
									<a href="javascript:void" data-toggle='modal' data-target='#view201files' onclick="view201Files(23,'<?php echo $id;?>')" title="click to view"><img src="../images/docs.png" class="img"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">Suspension</span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right"><?php echo $total_sus;?></span>
								<center>
									<a href="javascript:void" data-toggle='modal' data-target='#view201files' onclick="view201Files(24,'<?php echo $id;?>')" title="click to view"><img src="../images/docs.png" class="img"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">Transcript of Records </span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right"><?php echo $total_tor;?></span>
								<center>
									<a href="javascript:void" data-toggle='modal' data-target='#view201files' onclick="view201Files(25,'<?php echo $id;?>')" title="click to view"><img src="../images/docs.png" class="img"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">Promotion</span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right"><?php echo $total_promotion;?></span>
								<center>
									<a href="javascript:void" data-toggle='modal' data-target='#view201files' onclick="view201Files(26,'<?php echo $id;?>')" title="click to view"><img src="../images/docs.png" class="img"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm"> Resignation Letter </span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right"><?php echo $total_resigletter;?></span>
								<center>
									<a href="javascript:void" data-toggle='modal' data-target='#view201files' onclick="view201Files(27,'<?php echo $id;?>')" title="click to view"><img src="../images/docs.png" class="img"></a>
								</center>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php } else {
		echo "Employee Not Found!";
	}
}

elseif(@$_GET['request'] == "load201Files"){

	$id = explode("*",$_POST['input']);
	$name = explode("*",$_POST['input']);
	$id = $id[0];
	$check = strpos($_POST['input'],'*');
	if($check == true){
		// get the number of files in application letter
		$app_query = mysql_query(
						"SELECT
							requirement_name
						 FROM
							application_initialreq
						 WHERE
							app_code = '".$id."'
						 AND
							requirement_name = 'Application Letter'"
					 ) or die(mysql_error());
		$total_appletter = mysql_num_rows($app_query);
		// get the number of files in background investigation
		$bi_query = mysql_query(
						"SELECT
							requirement_name
						 FROM
							application_finalreq
						 WHERE
							app_id = '".$id."'
						 AND
							requirement_name = 'BI'"
					  ) or die(mysql_error());
		$total_bi = mysql_num_rows($bi_query);
		// get the number of files in birth certificate
		$bc_query = mysql_query(
						"SELECT
							requirement_name
						 FROM
							application_finalreq
						 WHERE
							app_id = '".$id."'
						 AND
							requirement_name = 'BirthCertificate'"
					) or die(mysql_error());
		$total_bc = mysql_num_rows($bc_query);
		// get the number of files in cedula
		$c_query = mysql_query(
						"SELECT
							requirement_name
						 FROM
							application_finalreq
						 WHERE
							app_id = '".$id."'
						 AND
							requirement_name = 'Cedula'"
				   ) or die(mysql_error());
		$total_c = mysql_num_rows($c_query);
		// get the number of files in fingerprint
		$fp_query = mysql_query(
						"SELECT
							requirement_name
						 FROM
							application_finalreq
						 WHERE
							app_id = '".$id."'
						 AND
							requirement_name = 'Fingerprint'"
					) or die(mysql_error());
		$total_fp = mysql_num_rows($fp_query);
		// get the number of files in marriage contract
		$mc_query = mysql_query(
						"SELECT
							requirement_name
						 FROM
							application_finalreq
						 WHERE
							app_id = '".$id."'
						 AND
							requirement_name = 'MarriageCertificate'"
					) or die(mysql_error());
		$total_mc = mysql_num_rows($mc_query);
		// get the number of files in medical certificate
		$medc_query = mysql_query(
						"SELECT
							requirement_name
						 FROM
							application_finalreq
						 WHERE
							app_id = '".$id."'
						 AND
							requirement_name = 'MedicalCertificate'"
					) or die(mysql_error());
		$total_medc = mysql_num_rows($medc_query);
		// get the number of files in orientation certificate
		$oc_query = mysql_query(
						"SELECT
							requirement_name
						 FROM
							application_finalreq
						 WHERE
							app_id = '".$id."'
						 AND
							requirement_name = 'OrientationCertificate'"
					) or die(mysql_error());
		$total_oc = mysql_num_rows($oc_query);
		// get the number of files in parent consent
		$pc_query = mysql_query(
						"SELECT
							requirement_name
						 FROM
							application_finalreq
						 WHERE
							app_id = '".$id."'
						 AND
							requirement_name = 'ParentsConsent'"
					) or die(mysql_error());
		$total_pc = mysql_num_rows($pc_query);
		// get the number of files in police clearance
		$polc_query = mysql_query(
						"SELECT
							requirement_name
						 FROM
							application_finalreq
						 WHERE
							app_id = '".$id."'
						 AND
							requirement_name = 'PoliceClearance'"
					) or die(mysql_error());
		$total_polc = mysql_num_rows($polc_query);
		// get the number of files in recommendation letter
		$r_query = mysql_query(
						"SELECT
							requirement_name
						 FROM
							application_finalreq
						 WHERE
							app_id = '".$id."'
						 AND
							requirement_name = 'Recommendation'"
					) or die(mysql_error());
		$total_r = mysql_num_rows($r_query);
		// get the number of files in resume
		$re_query = mysql_query(
						"SELECT
							requirement_name
						 FROM
							application_initialreq
						 WHERE
							app_code = '".$id."'
						 AND
							requirement_name = 'Resume'"
					) or die(mysql_error());
		$total_re = mysql_num_rows($re_query);
		// get the number of files in sketch
		$sk_query = mysql_query(
						"SELECT
							requirement_name
						 FROM
							application_finalreq
						 WHERE
							app_id = '".$id."'
						 AND
							requirement_name = 'Sketch'"
					) or die(mysql_error());
		$total_sk = mysql_num_rows($sk_query);
		// get the number of files in sss
		$sss_query = mysql_query(
						"SELECT
							requirement_name
						 FROM
							application_finalreq
						 WHERE
							app_id = '".$id."'
						 AND
							requirement_name = 'SSS'"
					) or die(mysql_error());
		$total_sss = mysql_num_rows($sss_query);
		// get the number of files in tor
		$tor_query = mysql_query(
						"SELECT
							requirement_name
						 FROM
							application_initialreq
						 WHERE
							app_code = '".$id."'
						 AND
							requirement_name = 'Transcript of Records'"
					) or die(mysql_error());
		$total_tor = mysql_num_rows($tor_query);// get the number of files in tor
		$tor_query = mysql_query(
						"SELECT
							requirement_name
						 FROM
							application_initialreq
						 WHERE
							app_code = '".$id."'
						 AND
							requirement_name = 'Transcript of Records'"
					) or die(mysql_error());
		$total_tor = mysql_num_rows($tor_query);
		$promotion_query = mysql_query(
						"SELECT
							requirement_name
						 FROM
							application_otherreq
						 WHERE
							app_id = '".$id."'
						 AND
							requirement_name = 'Promotion'"
					) or die(mysql_error());
		$total_promotion = mysql_num_rows($promotion_query);
		$clearance_query = mysql_query(
						"SELECT 
							clearance
						FROM 
							employmentrecord_ 
						WHERE 
							emp_id = '".$id."' and clearance !=''
						"
					) or die(mysql_error());
		$total_clearance = mysql_num_rows($clearance_query);
		
		?>
		<div class="panel panel-default">
			<div class="panel-heading">			
				<h3 class="panel-title">
					201 Files of <?php echo $name[1];?>
					<button class="btn btn-primary btn-xs pull-right" onclick="upload201files('<?php echo $id?>')"> Upload </button>
				</h3> 				
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">Application Letter</span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right"><?php echo $total_appletter;?></span>
								<center>
									<a href="javascript:void" onclick="view201Files(1,'<?php echo $id;?>')" title="click to view"><img src="images/docs.png" class="docs"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">Background Investigation</span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right"><?php echo $total_bi;?></span>
								<center>
									<a href="javascript:void" onclick="view201Files(2,'<?php echo $id;?>')" title="click to view"><img src="images/docs.png" class="docs"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">Birth Certificate</span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right"><?php echo $total_bc;?></span>
								<center>
									<a href="javascript:void" onclick="view201Files(3,'<?php echo $id;?>')" title="click to view"><img src="images/docs.png" class="docs"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">Cedula</span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right"><?php echo $total_c;?></span>
								<center>
									<a href="javascript:void" onclick="view201Files(4,'<?php echo $id;?>')" title="click to view"><img src="images/docs.png" class="docs"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">Clearance</span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right"><?php echo $total_clearance;?></span>
								<center>
									<a href="javascript:void" onclick="view201Files(5,'<?php echo $id;?>')" title="click to view"><img src="images/docs.png" class="docs"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">Contract</span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right">0</span>
								<center>
									<a href="javascript:void" onclick="view201Files(6,'<?php echo $id;?>')" title="click to view"><img src="images/docs.png" class="docs"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">Fingerprint</span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right"><?php echo $total_fp;?></span>
								<center>
									<a href="javascript:void" onclick="view201Files(7,'<?php echo $id;?>')" title="click to view"><img src="images/docs.png" class="docs"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">KRA</span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right">0</span>
								<center>
									<a href="javascript:void" onclick="view201Files(8,'<?php echo $id;?>')" title="click to view"><img src="images/docs.png" class="docs"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">Marriage Certificate</span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right"><?php echo $total_mc;?></span>
								<center>
									<a href="javascript:void" onclick="view201Files(9,'<?php echo $id;?>')" title="click to view"><img src="images/docs.png" class="docs"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">Medical Certificate</span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right"><?php echo $total_medc;?></span>
								<center>
									<a href="javascript:void" onclick="view201Files(10,'<?php echo $id;?>')" title="click to view"><img src="images/docs.png" class="docs"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">Orientation Certificate</span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right"><?php echo $total_oc;?></span>
								<center>
									<a href="javascript:void" onclick="view201Files(11,'<?php echo $id;?>')" title="click to view"><img src="images/docs.png" class="docs"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">Parent Consent</span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right"><?php echo $total_pc;?></span>
								<center>
									<a href="javascript:void" onclick="view201Files(12,'<?php echo $id;?>')" title="click to view"><img src="images/docs.png" class="docs"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">Police Clearance</span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right"><?php echo $total_polc;?></span>
								<center>
									<a href="javascript:void" onclick="view201Files(13,'<?php echo $id;?>')" title="click to view"><img src="images/docs.png" class="docs"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">Recommendation Letter</span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right"><?php echo $total_r;?></span>
								<center>
									<a href="javascript:void" onclick="view201Files(14,'<?php echo $id;?>')" title="click to view"><img src="images/docs.png" class="docs"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">Resume</span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right"><?php echo $total_re;?></span>
								<center>
									<a href="javascript:void" onclick="view201Files(15,'<?php echo $id;?>')" title="click to view"><img src="images/docs.png" class="docs"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">Sketch</span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right"><?php echo $total_sk;?></span>
								<center>
									<a href="javascript:void" onclick="view201Files(16,'<?php echo $id;?>')" title="click to view"><img src="images/docs.png" class="docs"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">SSS</span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right"><?php echo $total_sss;?></span>
								<center>
									<a href="javascript:void" onclick="view201Files(17,'<?php echo $id;?>')" title="click to view"><img src="images/docs.png" class="docs"></a>
								</center>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<center><span class="sm">Transcript of Records </span></center>
							</div>
							<div class="panel-body">
								<span class="label label-danger pull-right"><?php echo $total_tor;?></span>
								<center>
									<a href="javascript:void" onclick="view201Files(18,'<?php echo $id;?>')" title="click to view"><img src="images/docs.png" class="docs"></a>
								</center>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php } 
	else {
		echo "Employee Not Found!";
	}
}
elseif(@$_GET['request'] == 'load201files'){
	$start = 0;
	$limit = 1;
	if(@$_POST['p']){
		$id = @$_POST['p'];
		$start=($id-1)*$limit;
	} else {
		$id = 1;
	}
	//array pattern from the requirement_name in the database - application_initialreq, application_finalreq, application_otherreq
	$cat = array("","Application Letter","BI","BirthCertificate","Cedula","Clearance","Contract","Drug Test","Fingerprint","Job Transfers","KRA","MarriageCertificate","MedicalCertificate","Misconduct","OrientationCertificate","Others","ParentsConsent","PoliceClearance","Recommendation","Regularization","Resume","Showcause","Sketch","SSS","Suspension","Transcript of Records","Promotion","Resignation Letter");
	//$cat = array("","Application Letter","BI","BirthCertificate","Cedula","","","Fingerprint","","MarriageCertificate","MedicalCertificate","OrientationCertificate","ParentsConsent","PoliceClearance","Recommendation","Resume","Sketch","SSS","Transcript of Records");
	if($_POST['cat'] == 1 || $_POST['cat'] == 20 || $_POST['cat'] == 25){ 
		$sql = mysql_query(
				"SELECT
					*
				 FROM
					application_initialreq
				 WHERE
					app_code = '".$_POST['id']."'
				 AND
					requirement_name = '".$cat[$_POST['cat']]."'
				 LIMIT $start, $limit"
			   ) or die(mysql_error());
		$rows = mysql_num_rows(mysql_query(
				"SELECT
					*
				 FROM
					application_initialreq
				 WHERE
					app_code = '".$_POST['id']."'
				 AND
					requirement_name = '".$cat[$_POST['cat']]."'"
			   ));
		$total=ceil($rows/$limit);
		?>
		<div class="col-sm-2" style="position:absolute;top:-2px;right:5px;">
			<div class="form-horizontal">
				<div class="form-group">
					<label class="col-sm-2 control-label">page</label>
					<div class="col-sm-9">
						<select class="form-control" onchange="pagi('<?php echo @$_POST['cat']?>',this.value,'<?php echo @$_POST['id']?>')">
							<?php for($x=1;$x<=$total;$x++):?>
								<option <?php if(@$id == $x):echo"selected";endif;?>><?php echo $x?></option>
							<?php endfor;?>
						</select>
					</div>
				</div>
			</div>
		</div>
	<?php
		while($res=mysql_fetch_array($sql)){
	?>
		<div style="position:absolute;top:3px;left:15px;">
		<span><i>Date Uploaded :</i><strong><?php echo $nq->changeDateFormat('F d, Y',$res['date_time']);?></strong></span>
		<span><i>Uploaded By : </i><strong><?php echo $res['receiving_staff'];?></strong></span> 
		</div><hr>
		<div class="row">
			<img src="<?php echo $res['filename'];?>" width="100%">
		</div>
<?php 	}
	}
	elseif($_POST['cat'] == 13 || $_POST['cat'] == 21 || $_POST['cat'] == 24){
		$sql = mysql_query(
				"SELECT
					*
				 FROM
					applicant_violation
				 WHERE
					app_id = '".$_POST['id']."'
				AND
					requirement_name = '".$cat[$_POST['cat']]."'
				 LIMIT $start, $limit"
			   ) or die(mysql_error());
			   
		$rows = mysql_num_rows(mysql_query(
				"SELECT
					*
				 FROM
					applicant_violation
				 WHERE
					app_id = '".$_POST['id']."' 
				 AND
					requirement_name = '".$cat[$_POST['cat']]."'"
			   ));
		$total=ceil($rows/$limit);
		?>
		<div class="col-sm-2" style="position:absolute;top:-2px;right:5px;">
			<div class="form-horizontal">
				<div class="form-group">
					<label class="col-sm-2 control-label">page</label>
					<div class="col-sm-9">
						<select class="form-control" onchange="pagi('<?php echo @$_POST['cat']?>',this.value,'<?php echo @$_POST['id']?>')">
							<?php for($x=1;$x<=$total;$x++):?>
								<option <?php if(@$id == $x):echo"selected";endif;?>><?php echo $x?></option>
							<?php endfor;?>
						</select>
					</div>
				</div>
			</div>
		</div>
	<?php
		while($res=mysql_fetch_array($sql)){
	?>
		<div style="position:absolute;top:3px;left:15px;">
		<span><i>Date Uploaded :</i> <strong><?php echo $nq->changeDateFormat('F d, Y',$res['date_time']);?></strong></span>
		<span><i>Uploaded By : </i><strong><?php echo $res['receiving_staff'];?></strong></span> 
		</div><hr>
		<div class="row">
			<img src="<?php echo $res['filename'];?>" width="100%">
		</div>
	<?php 
		}
	}
	// requirement for others update 6-27-15
	else if($_POST['cat'] == 15 ){ 
		$sql = mysql_query(
				"SELECT
					*
				 FROM
					application_otherreq
				 WHERE
					app_id = '".$_POST['id']."'		
				 and requirement_name !='Regularization'
				 and requirement_name !='Job Transfers'
				 LIMIT $start, $limit"
			   ) or die(mysql_error());
			   
		$rows = mysql_num_rows(mysql_query(
				"SELECT
					*
				 FROM
					application_otherreq
				 WHERE
					app_id = '".$_POST['id']."'
				and requirement_name !='Regularization'		
				and requirement_name !='Job Transfers'"
			   ));
		$total=ceil($rows/$limit);
		?>
		<div class="col-sm-2" style="position:absolute;top:-2px;right:5px;">
			<div class="form-horizontal">
				<div class="form-group">
					<label class="col-sm-2 control-label">page</label>
					<div class="col-sm-9">
						<select class="form-control" onchange="pagi('<?php echo @$_POST['cat']?>',this.value,'<?php echo @$_POST['id']?>')">
							<?php for($x=1;$x<=$total;$x++):?>
								<option <?php if(@$id == $x):echo"selected";endif;?>><?php echo $x?></option>
							<?php endfor;?>
						</select>
					</div>
				</div>
			</div>
		</div>
	<?php
		while($res=mysql_fetch_array($sql)){
	?>
		<div style="position:absolute;top:3px;left:15px;">
		<span><i>Date Uploaded :</i> <strong><?php echo $nq->changeDateFormat('F d, Y',$res['date_time']);?></strong></span>
		<span><i>Uploaded By : </i><strong><?php echo $res['receiving_staff'];?></strong></span> 
		</div><hr>
		<div class="row">
			<img src="<?php echo $res['filename'];?>" width="100%">
		</div>
	<?php 
		}
	}
	else if($_POST['cat'] == 26){ 
		$sql = mysql_query(
				"SELECT
					*
				 FROM
					application_otherreq
				 WHERE
					app_id = '".$_POST['id']."'		
				 and requirement_name ='Promotion'
				 LIMIT $start, $limit"
			   ) or die(mysql_error());
			   
		$rows = mysql_num_rows(mysql_query(
				"SELECT
					*
				 FROM
					application_otherreq
				 WHERE
					app_id = '".$_POST['id']."'
				and requirement_name ='Promotion'"
			   ));
		$total=ceil($rows/$limit);
		?>
		<div class="col-sm-2" style="position:absolute;top:-2px;right:5px;">
			<div class="form-horizontal">
				<div class="form-group">
					<label class="col-sm-2 control-label">page</label>
					<div class="col-sm-9">
						<select class="form-control" onchange="pagi('<?php echo @$_POST['cat']?>',this.value,'<?php echo @$_POST['id']?>')">
							<?php for($x=1;$x<=$total;$x++):?>
								<option <?php if(@$id == $x):echo"selected";endif;?>><?php echo $x?></option>
							<?php endfor;?>
						</select>
					</div>
				</div>
			</div>
		</div>
	<?php
		while($res=mysql_fetch_array($sql)){
	?>
		<div style="position:absolute;top:3px;left:15px;">
		<span><i>Date Uploaded :</i> <strong><?php echo $nq->changeDateFormat('F d, Y',$res['date_time']);?></strong></span>
		<span><i>Uploaded By : </i><strong><?php echo $res['receiving_staff'];?></strong></span> 
		</div><hr>
		<div class="row">
			<img src="<?php echo $res['filename'];?>" width="100%">
		</div>
	<?php 
		}
	}
	else if($_POST['cat'] == 27){ 
		$sql = mysql_query(
				"SELECT
					*
				 FROM
					termination
				 WHERE
					emp_id = '".$_POST['id']."'		
                 and resignation_letter != ''				
				 LIMIT $start, $limit"
			   ) or die(mysql_error());
			   
		$rows = mysql_num_rows(mysql_query(
				"SELECT
					*
				 FROM
					termination
				 WHERE
					emp_id = '".$_POST['id']."'
				and resignation_letter != '' "
			   ));
		$total=ceil($rows/$limit);
		?>
		<div class="col-sm-2" style="position:absolute;top:-2px;right:5px;">
			<div class="form-horizontal">
				<div class="form-group">
					<label class="col-sm-2 control-label">page</label>
					<div class="col-sm-9">
						<select class="form-control" onchange="pagi('<?php echo @$_POST['cat']?>',this.value,'<?php echo @$_POST['id']?>')">
							<?php for($x=1;$x<=$total;$x++):?>
								<option <?php if(@$id == $x):echo"selected";endif;?>><?php echo $x?></option>
							<?php endfor;?>
						</select>
					</div>
				</div>
			</div>
		</div>
	<?php
		while($res=mysql_fetch_array($sql)){
	?>
		<div style="position:absolute;top:3px;left:15px;">
		<span><i>Date Uploaded :</i> <strong><?php echo $nq->changeDateFormat('F d, Y',$res['date_updated']);?></strong></span>
		<span><i>Uploaded By : </i><strong><?php echo $res['added_by'];?></strong></span> 
		</div><hr>
		<div class="row">
			<img src="<?php echo $res['resignation_letter'];?>" width="100%">
		</div>
	<?php 
		}
	}
	else if($_POST['cat'] == 19){ 
		$sql = mysql_query(
				"SELECT
					*
				 FROM
					application_otherreq
				 WHERE
					app_id = '".$_POST['id']."'
				 AND
					requirement_name = 'Regularization'
				 LIMIT $start, $limit"
			   ) or die(mysql_error());
			   
		$rows = mysql_num_rows(mysql_query(
				"SELECT
					*
				 FROM
					application_otherreq
				 WHERE
					app_id = '".$_POST['id']."' 
				 AND
					requirement_name = 'Regularization'"
			   ));
		$total=ceil($rows/$limit);
		?>
		<div class="col-sm-2" style="position:absolute;top:-2px;right:5px;">
			<div class="form-horizontal">
				<div class="form-group">
					<label class="col-sm-2 control-label">page</label>
					<div class="col-sm-9">
						<select class="form-control" onchange="pagi('<?php echo @$_POST['cat']?>',this.value,'<?php echo @$_POST['id']?>')">
							<?php for($x=1;$x<=$total;$x++):?>
								<option <?php if(@$id == $x):echo"selected";endif;?>><?php echo $x?></option>
							<?php endfor;?>
						</select>
					</div>
				</div>
			</div>
		</div>
	<?php
		while($res=mysql_fetch_array($sql)){
	?>
		<div style="position:absolute;top:3px;left:15px;">
		<span><i>Date Uploaded :</i> <strong><?php echo $nq->changeDateFormat('F d, Y',$res['date_time']);?></strong></span>
		<span><i>Uploaded By : </i><strong><?php echo $res['receiving_staff'];?></strong></span> 
		</div><hr>
		<div class="row">
			<img src="<?php echo $res['filename'];?>" width="100%">
		</div>
	<?php 
		}
	}
	else if($_POST['cat'] == 9){ 
		$sql = mysql_query(
				"SELECT
					*
				 FROM
					application_otherreq
				 WHERE
					app_id = '".$_POST['id']."'
				 AND
					requirement_name = 'Job Transfers'
				 LIMIT $start, $limit"
			   ) or die(mysql_error());
			   
		$rows = mysql_num_rows(mysql_query(
				"SELECT
					*
				 FROM
					application_otherreq
				 WHERE
					app_id = '".$_POST['id']."' 
				 AND
					requirement_name = 'Job Transfers'"
			   ));
		$total=ceil($rows/$limit);
		?>
		<div class="col-sm-2" style="position:absolute;top:-2px;right:5px;">
			<div class="form-horizontal">
				<div class="form-group">
					<label class="col-sm-2 control-label">page</label>
					<div class="col-sm-9">
						<select class="form-control" onchange="pagi('<?php echo @$_POST['cat']?>',this.value,'<?php echo @$_POST['id']?>')">
							<?php for($x=1;$x<=$total;$x++):?>
								<option <?php if(@$id == $x):echo"selected";endif;?>><?php echo $x?></option>
							<?php endfor;?>
						</select>
					</div>
				</div>
			</div>
		</div>
	<?php
		while($res=mysql_fetch_array($sql)){
	?>
		<div style="position:absolute;top:3px;left:15px;">
		<span><i>Date Uploaded :</i> <strong><?php echo $nq->changeDateFormat('F d, Y',$res['date_time']);?></strong></span>
		<span><i>Uploaded By : </i><strong><?php echo $res['receiving_staff'];?></strong></span> 
		</div><hr>
		<div class="row">
			<img src="<?php echo $res['filename'];?>" width="100%">
		</div>
	<?php 
		}
	}
	else if($_POST['cat'] == '5'){	
		$sql = mysql_query(
				"SELECT
					clearance
				FROM
					employmentrecord_
				WHERE
					emp_id = '".$_POST['id']."'
				AND
					clearance !=''
				 LIMIT $start, $limit") or die(mysql_error());
				
		$rows = mysql_num_rows(mysql_query(
				"SELECT
					clearance
				FROM
					employmentrecord_
				WHERE
					emp_id = '".$_POST['id']."'
				AND
					clearance !='' "
			   ));
		$total=ceil($rows/$limit);	
		?>
		<div class="col-sm-2" style="position:absolute;top:-2px;right:5px;">
			<div class="form-horizontal">
				<div class="form-group">
					<label class="col-sm-2 control-label">page</label>
					<div class="col-sm-9">
						<select class="form-control" onchange="pagi('<?php echo @$_POST['cat']?>',this.value,'<?php echo @$_POST['id']?>')">
							<?php for($x=1;$x<=$total;$x++):?>
								<option <?php if(@$id == $x):echo"selected";endif;?>><?php echo $x?></option>
							<?php endfor;?>
						</select>
					</div>
				</div>
			</div>
		</div><?php		
		while($res=mysql_fetch_array($sql)){
		?>
		<div style="position:absolute;top:3px;left:15px;">
		<span><i>Date Uploaded :</i> <strong></strong></span>
		<span><i>Uploaded By : </i><strong></strong></span> 
		</div><hr>
		<div class="row">
			<img src="<?php echo $res['clearance'];?>" width="100%">
		</div>
<?php 	}
	}
	else {
		$sql = mysql_query(
				"SELECT
					*
				 FROM
					application_finalreq
				 WHERE
					app_id= '".$_POST['id']."'
				 AND
					requirement_name = '".$cat[$_POST['cat']]."'
				 LIMIT $start, $limit"
			   ) or die(mysql_error());
		$rows = mysql_num_rows(mysql_query(
				"SELECT
					*
				 FROM
					application_finalreq
				 WHERE
					app_id= '".$_POST['id']."'
				 AND
					requirement_name = '".$cat[$_POST['cat']]."'"
			   ));
		$total=ceil($rows/$limit);
		?>
		<div class="col-sm-2" style="position:absolute;top:-2px;right:5px;">
			<div class="form-horizontal">
				<div class="form-group">
					<label class="col-sm-2 control-label">page</label>
					<div class="col-sm-9">
						<select class="form-control" onchange="pagi('<?php echo @$_POST['cat']?>',this.value,'<?php echo @$_POST['id']?>')">
							<?php for($x=1;$x<=$total;$x++):?>
								<option <?php if(@$id == $x):echo"selected";endif;?>><?php echo $x?></option>
							<?php endfor;?>
						</select>
					</div>
				</div>
			</div>
		</div>
	<?php
		while($res=mysql_fetch_array($sql)){
	?>
		<div style="position:absolute;top:3px;left:15px;">
		<span><i>Date Uploaded :</i> <strong><?php echo $nq->changeDateFormat('F d, Y',$res['date_time']);?></strong></span>
		<span><i>Uploaded By : </i><strong><?php echo $res['receiving_staff'];?></strong></span> 
		</div><hr>
		<div class="row">
			<img src="<?php echo $res['filename'];?>" width="100%">
		</div>
<?php }
	}
}

elseif(@$_GET['request'] == 'loadSelectedCompany'){
	$result = $nq->getAllCompany();
	while($res=mysql_fetch_array($result)){
		if($res['company_code'] == $_POST['cc']) {?>
			<option selected value="<?php echo $res['company_code'];?>"><?php echo $res['company'];?></option>
	<?php } else { ?>
			<option value="<?php echo $res['company_code'];?>"><?php echo $res['company'];?></option>
	<?php } 
		}
}
elseif(@$_GET['request'] == 'loadSelectedBunit'){
	$bunit = $nq->getAllBusinessUnit($_POST['cc']);
	?>
	<option></option>
	<?php
	while($res=mysql_fetch_array($bunit)){
	if($res['company_code'] == $_POST['cc'] && $res['bunit_code'] == $_POST['bc']){
	?>
		<option selected value="<?php echo $res['company_code']."/".$res['bunit_code'];?>"><?php echo $res['business_unit'];?></option>
	<?php } else {?>
		<option value="<?php echo $res['company_code']."/".$res['bunit_code'];?>"><?php echo $res['business_unit'];?></option>
	<?php }
	}
}
elseif(@$_GET['request'] == 'loadSelectedDept'){
	$dept_c = $nq->getAllDepartment($_POST['bc'],$_POST['cc']);
	?>
	<option></option>
	<?php
	while($res=mysql_fetch_array($dept_c)){
		if($res['company_code'] == $_POST['cc'] && $res['bunit_code'] == $_POST['bc'] && $res['dept_code'] == $_POST['dc']){
?>
			<option selected value="<?php echo $res['company_code']."/".$res['bunit_code']."/".$res['dept_code'];?>"><?php echo $res['dept_name'];?></option>
<?php
		} else { ?>
			<option value="<?php echo $res['company_code']."/".$res['bunit_code']."/".$res['dept_code'];?>"><?php echo $res['dept_name'];?></option>
<?php	}
	}
}
elseif(@$_GET['request'] == 'loadSelectedSec'){
	$sec = $nq->getAllSection($_POST['dc'],$_POST['bc'],$_POST['cc']);
	?>
	<option></option>
	<?php
		while($res=mysql_fetch_array($sec)){
			if($res['company_code'] == $_POST['cc'] && $res['bunit_code'] == $_POST['bc'] && $res['dept_code'] == $_POST['dc'] && $res['section_code'] == $_POST['sc']){
	?>
				<option selected value="<?php echo $res['company_code']."/".$res['bunit_code']."/".$res['dept_code']."/".$res['section_code'];?>"><?php echo $res['section_name'];?></option>
	<?php
			} else { ?>
				<option value="<?php echo $res['company_code']."/".$res['bunit_code']."/".$res['dept_code']."/".$res['section_code'];?>"><?php echo $res['section_name'];?></option>
	<?php	}
		}
}
elseif(@$_GET['request'] == 'loadSelectedSsec'){
	$ssec = $nq->getAllSubSection($_POST['sc'],$_POST['dc'],$_POST['bc'],$_POST['cc']);
	?>
	<option></option>
	<?php
	while($res=mysql_fetch_array($ssec)){
		if($res['company_code'] == $_POST['cc'] && $res['bunit_code'] == $_POST['bc'] && $res['dept_code'] == $_POST['dc'] && $res['section_code'] == $_POST['sc'] && $res['sub_section_code'] == $_POST['ssc']){
?>
			<option selected value="<?php echo $res['company_code']."/".$res['bunit_code']."/".$res['dept_code']."/".$res['section_code']."/".$res['sub_section_code'];?>"><?php echo $res['sub_section_name'];?></option>
<?php
		} else { ?>
			<option value="<?php echo $res['company_code']."/".$res['bunit_code']."/".$res['dept_code']."/".$res['section_code']."/".$res['sub_section_code'];?>"><?php echo $res['sub_section_name'];?></option>
<?php	}
	}
}
elseif(@$_GET['request'] == 'loadSelectedPosLevel'){
	$pos_level = $_POST['pos_level'];

	for($x=0;$x<=11;$x++){ ?>
		
		<option <?php if($pos_level == $x):?>selected<?php endif;?>><?php echo $x; ?></option> <?php 
	}
}	
elseif(@$_GET['request'] == 'loadSelectedUnit'){
	$unit = $nq->getAllUnit($_POST['ssc'],$_POST['sc'],$_POST['dc'],$_POST['bc'],$_POST['cc']);
	?>
	<option></option>
	<?php
	while($res=mysql_fetch_array($unit)){
		if($res['company_code'] == $_POST['cc'] && $res['bunit_code'] == $_POST['bc'] && $res['dept_code'] == $_POST['dc'] && $res['section_code'] == $_POST['sc'] && $res['sub_section_code'] == $_POST['ssc'] && $res['unit_code'] == $_POST['uc']){
?>
			<option selected value="<?php echo $res['company_code']."/".$res['bunit_code']."/".$res['dept_code']."/".$res['section_code']."/".$res['sub_section_code']."/".$res['unit_code'];?>"><?php echo $res['unit_name'];?></option>
<?php
		} else { ?>
			<option value="<?php echo $res['company_code']."/".$res['bunit_code']."/".$res['dept_code']."/".$res['section_code']."/".$res['sub_section_code']."/".$res['unit_code'];?>"><?php echo $res['unit_name'];?></option>
<?php	}
	}
}
elseif(@$_GET['request'] == 'loadSelectedPos'){

		$query = mysql_query("SELECT position_title FROM position_leveling order by position_title asc");
		while($rq = mysql_fetch_array($query))
		{
			if($_POST['pos'] == $rq['position_title'])
			{?>
				<option value="<?php echo $rq['position_title'];?>" selected='selected'><?php echo $rq['position_title'];?></option><?php 
			}else{ ?>
				<option value="<?php echo $rq['position_title'];?>"><?php echo $rq['position_title'];?></option><?php 	
			}
		}
}
elseif(@$_GET['request'] == 'loadSelectedLodging'){
	?>
		<option value="Stay-in" <?php if($_POST['lodging'] == 'Stay-in'):?> selected<?php endif;?>>Stay-in</option>
		<option value="Stay-out" <?php if($_POST['lodging'] == 'Stay-out'):?> selected<?php endif;?>>Stay-out</option>
	<?php
}
elseif(@$_GET['request'] == 'loadSelectedType'){
	
		$query = mysql_query("SELECT * FROM employee_type");
		while($r = mysql_fetch_array($query)){
			if($_POST['type'] == $r['emp_type']){
				echo "<option value='".$_POST['type'] ."' selected>".$_POST['type'] ."</option>";
			}else{
				echo "<option value='".$r['emp_type']."' >".$r['emp_type']."</option>";
			}
		}
}
elseif(@$_GET['request'] == 'loadLevel'){
	
	$position 	= $_POST['position'];
	$levelno 	= $nq->getOneField("lvlno","position_leveling","position_title = '$position'");
	echo $levelno;
}

elseif(@$_GET['request'] == 'edit_contract'){

	$bunit = explode("/",$_POST['bunit_code']);
	$dept = explode("/",$_POST['dept_code']);
	@$section = explode("/",$_POST['sec_code']);
	@$ssection = explode("/",$_POST['ssec_code']);
	@$unit = explode("/",$_POST['unit_code']);
	$name = $nq->getAppName($_POST['emp_id']);
	if(@$_POST['employment']){
		$sql = mysql_query(
				"UPDATE
					employee3
				 SET
					company_code = '".mysql_real_escape_string(strip_tags(@$_POST['comp_code']))."',
					bunit_code = '".mysql_real_escape_string(strip_tags(@$bunit[1]))."',
					dept_code = '".mysql_real_escape_string(strip_tags(@$dept[2]))."',
					section_code = '".mysql_real_escape_string(strip_tags(@$section[3]))."',
					sub_section_code = '".mysql_real_escape_string(strip_tags(@$ssection[4]))."',
					unit_code = '".mysql_real_escape_string(strip_tags(@$unit[5]))."',
					startdate = '".mysql_real_escape_string(strip_tags($nq->changeDateFormat('Y-m-d',@$_POST['start_date'])))."',
					eocdate = '".mysql_real_escape_string(strip_tags($nq->changeDateFormat('Y-m-d',@$_POST['end_date'])))."',
					emp_type = '".mysql_real_escape_string(strip_tags(@$_POST['contract_emptype']))."',
					positionlevel = '".mysql_real_escape_string(strip_tags(@$_POST['contract_positionlevel']))."',
					position = '".mysql_real_escape_string(strip_tags(@$_POST['contract_position']))."',
					position_desc = '".mysql_real_escape_string(strip_tags(@$_POST['contract_positiondesc']))."',
					lodging = '".mysql_real_escape_string(strip_tags(@$_POST['contract_lodging']))."',
					remarks = '".mysql_real_escape_string(strip_tags(@$_POST['contract_remarks']))."',
					current_status = '".mysql_real_escape_string(strip_tags(@$_POST['contract_cstatus']))."'
				 WHERE
					emp_id = '".mysql_real_escape_string(strip_tags(@$_POST['emp_id']))."'"
			   ) or die(mysql_error());
		
		$nq->savelogs("Updated the current contract history of ".$name,date('Y-m-d'),date('H:i:s'),$_SESSION['emp_id'],$_SESSION['username']);	
		
		die("Ok");
		
		
	} else {
		$sql = mysql_query(
				"SELECT
					startdate,
					eocdate
				 FROM
					employmentrecord_
				 WHERE
					record_no != '".$_POST['rec_no']."'
				 AND
					emp_id = '".$_POST['emp_id']."'"
			   ) or die(mysql_error());
		 while($res=mysql_fetch_array($sql)){
			 if($res['startdate'] == mysql_real_escape_string(strip_tags($nq->changeDateFormat('Y-m-d',$_POST['start_date']))) && $res['eocdate'] == mysql_real_escape_string(strip_tags($nq->changeDateFormat('Y-m-d',$_POST['end_date'])))){
				die("This Contract is already Added!");
			 }
		 }
		mysql_query(
			"UPDATE
				employmentrecord_
			 SET
				company_code = '".mysql_real_escape_string(strip_tags(@$_POST['comp_code']))."',
				bunit_code = '".mysql_real_escape_string(strip_tags(@$bunit[1]))."',
				dept_code = '".mysql_real_escape_string(strip_tags(@$dept[2]))."',
				section_code = '".mysql_real_escape_string(strip_tags(@$section[3]))."',
				sub_section_code = '".mysql_real_escape_string(strip_tags(@$ssection[4]))."',
				unit_code = '".mysql_real_escape_string(strip_tags(@$unit[5]))."',
				startdate = '".mysql_real_escape_string(strip_tags($nq->changeDateFormat('Y-m-d',@$_POST['start_date'])))."',
				eocdate = '".mysql_real_escape_string(strip_tags($nq->changeDateFormat('Y-m-d',@$_POST['end_date'])))."',
				emp_type = '".mysql_real_escape_string(strip_tags(@$_POST['contract_emptype']))."',
				current_status = '".mysql_real_escape_string(strip_tags(@$_POST['contract_cstatus']))."',
				positionlevel = '".mysql_real_escape_string(strip_tags(@$_POST['contract_positionlevel']))."',
				position = '".mysql_real_escape_string(strip_tags(@$_POST['contract_position']))."',
				lodging = '".mysql_real_escape_string(strip_tags(@$_POST['contract_lodging']))."',
				pos_desc = '".mysql_real_escape_string(strip_tags(@$_POST['contract_positiondesc']))."',
				remarks = '".mysql_real_escape_string(strip_tags(@$_POST['contract_remarks']))."',
				date_updated = '".date('Y-m-d')."',
				updatedby =	'".@$_SESSION['emp_id']."'
			 WHERE
				record_no = '".$_POST['rec_no']."'"
		) or die(mysql_error());
		//logs
		$name = $nq->getRecEmpName($_POST['rec_no']);
		$nq->savelogs("Updated the contract history of ".$name." record_no = '".$_POST['rec_no']."'" ,date('Y-m-d'),date('H:i:s'),$_SESSION['emp_id'],$_SESSION['username']);	
		
		die("Ok");
	}
}

elseif(@$_GET['request'] == 'editContract'){

	if(@$_POST['employment']){
		$sql = mysql_query(
				"SELECT
					*
				 FROM
					employee3
				 WHERE
					record_no = '".$_POST['rec']."'"
			) or die(mysql_error());
		$d = mysql_fetch_array($sql);
		?>
		<div class="row">
			<div class="col-md-7 col-md-offset-2">
				<span class="label label-danger"><?php echo $_POST['rec'];?>Required Fields : Company, Business Unit, Department, Start and End Date, Position, Employee Type, Current Status</span>
			</div>
			<br>
			<br>
			<div class="col-md-4">
				<div class="form-group">
					<label>Company</label>
					<input type="hidden" name="rec_no" value="<?php echo $_POST['rec'];?>">
					<input type="hidden" name="emp_id" value="<?php echo $d['emp_id'];?>">
					<select class="form-control" name="comp_code" onchange="comp_code(this.value)">
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
								if($res['company_code'] == $d['company_code']){
						?>
							<option selected value="<?php echo $res['company_code'];?>"><?php echo $res['company'];?></option>
						<?php } else { ?>
							<option value="<?php echo $res['company_code'];?>"><?php echo $res['company'];?></option>
						<?php	
							}
						} ?>
					<select>
				 </div>
				 <div class="form-group">
					<label>Business Unit</label>
					<select class="form-control" name="bunit_code" onchange="bunit_code(this.value)">
						<option value="">Select</option>
						<?php
							$sql = mysql_query
									("
										SELECT
											*
										FROM
											locate_business_unit
										WHERE
											company_code = '".$d['company_code']."'
										ORDER BY
											business_unit ASC
									") or die(mysql_error());
							while($res=mysql_fetch_array($sql)){ 
								if($res['bunit_code'] == $d['bunit_code']){
							?>
								<option selected value="<?php echo $res['company_code']."/".$res['bunit_code'];?>">
									<?php echo $res['business_unit'];?>
								</option>
							<?php } else { ?>
								<option value="<?php echo $res['company_code']."/".$res['bunit_code'];?>">
									<?php echo $res['business_unit'];?>
								</option>
							<?php	} 
							}?>
					<select>
				 </div>
				 <div class="form-group">
					<label>Department</label>
					<select class="form-control" name="dept_code" onchange="dept_code(this.value)">
						<option value="">Select</option>
						<?php 
						$sql = mysql_query
								("
									SELECT
										*
									FROM
										locate_department
									WHERE
										company_code = '".$d['company_code']."'
									AND
										bunit_code = '".$d['bunit_code']."'
									ORDER BY
										dept_name ASC
								") or die(mysql_error());
							while($res=mysql_fetch_array($sql)){
									if($res['dept_code'] == $d['dept_code']){
							?>
									<option selected value="<?php echo $res['company_code']."/".$res['bunit_code']."/".$res['dept_code'];?>">
										<?php echo $res['dept_name'];?>
									</option>
							<?php } else { ?>
									<option value="<?php echo $res['company_code']."/".$res['bunit_code']."/".$res['dept_code'];?>">
										<?php echo $res['dept_name'];?>
									</option>
							<?php	} 
							}?>
					<select>
				 </div>
				 <div class="form-group">
					<label>Section</label>
					<select class="form-control" name="sec_code" onchange="sec_code(this.value)">
						<option value="">Select</option>
						<?php
						$sql = mysql_query
									("
										SELECT
											*
										FROM
											locate_section
										WHERE
											company_code = '".$d['company_code']."'
										AND
											bunit_code = '".$d['bunit_code']."'
										AND
											dept_code = '".$d['dept_code']."'
										ORDER BY
											section_name ASC
									") or die(mysql_error());
							while($res=mysql_fetch_array($sql)){ 
								if($res['section_code'] == $d['section_code']){
							?>
								<option selected value="<?php echo $res['company_code']."/".$res['bunit_code']."/".$res['dept_code']."/".$res['section_code'];?>">
									<?php echo $res['section_name'];?>
								</option>
							<?php }	else { ?>
								<option value="<?php echo $res['company_code']."/".$res['bunit_code']."/".$res['dept_code']."/".$res['section_code'];?>">
									<?php echo $res['section_name'];?>
								</option>
							<?php }
							}?>
					<select>
				 </div>
				 <div class="form-group">
					<label>Sub-section</label>
					<select class="form-control" name="ssec_code" onchange="ssec_code(this.value)">
						<option value="">Select</option>
						<?php
						$sql = mysql_query
									("
										SELECT
											*
										FROM
											locate_sub_section
										WHERE
											company_code = '".$d['company_code']."'
										AND
											bunit_code = '".$d['bunit_code']."'
										AND
											dept_code = '".$d['dept_code']."'
										AND
											section_code = '".$d['section_code']."'
										ORDER BY
											sub_section_name ASC
									") or die(mysql_error());
							while($res=mysql_fetch_array($sql)){ 
								if($res['sub_section_code'] == $d['sub_section_code']){
							?>
								<option selected value="<?php echo $res['company_code']."/".$res['bunit_code']."/".$res['dept_code']."/".$res['section_code']."/".$res['sub_section_code'];?>">
									<?php echo $res['sub_section_name'];?>
								</option>
							<?php } else { ?>
								<option value="<?php echo $res['company_code']."/".$res['bunit_code']."/".$res['dept_code']."/".$res['section_code']."/".$res['sub_section_code'];?>">
									<?php echo $res['sub_section_name'];?>
								</option>
							<?php }
							}?>
					<select>
				 </div>
				 <div class="form-group">
					<label>Unit</label>
					<select class="form-control" name="unit_code">
						<option value="">Select</option>
						<?php
						$sql = mysql_query
								("
									SELECT
										*
									FROM
										locate_unit
									WHERE
										company_code = '".$d['company_code']."'
									AND
										bunit_code = '".$d['bunit_code']."'
									AND
										dept_code = '".$d['dept_code']."'
									AND
										section_code = '".$d['section_code']."'
									AND
										sub_section_code = '".$d['sub_section_code']."'
									ORDER BY
										unit_name ASC
								") or die(mysql_error());
						while($res=mysql_fetch_array($sql)){ 
							if($res['unit_code'] == $d['unit_code']){
						?>
							<option selected value="<?php echo $res['company_code']."/".$res['bunit_code']."/".$res['dept_code']."/".$res['section_code']."/".$res['sub_section_code']."/".$res['unit_code'];?>">
								<?php echo $res['unit_name'];?>
							</option>
						<?php } else { ?>
							<option value="<?php echo $res['company_code']."/".$res['bunit_code']."/".$res['dept_code']."/".$res['section_code']."/".$res['sub_section_code']."/".$res['unit_code'];?>">
								<?php echo $res['unit_name'];?>
							</option>
						<?php }
						}?>
					<select>
				 </div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label>Start Date</label>
					<input type="text" class="form-control" id="sdate" name="estart_date" value="<?php echo $nq->changeDateFormat('m/d/Y',$d['startdate']);?>" placeholder="mm/dd/yyyy">
				 </div>
				 <div class="form-group">
					<label>End Date</label>
					<input type="text" class="form-control" id="enddate" value="<?php echo $nq->changeDateFormat('m/d/Y',$d['eocdate']); ?>" name="eend_date" placeholder="mm/dd/yyyy">
				 </div>
				 <div class="form-group">
					<label>Position</label>
					
					<select id='contract_position' name="contract_position" class='form-control'>
						<option> </option>
						<?php 
						$query = mysql_query("SELECT position FROM positions order by position asc");
						while($rq = mysql_fetch_array($query))
						{
							if($d['position'] == $rq['position'])
							{ ?>
								<option value="<?php echo $rq['position'];?>" selected><?php echo $rq['position'];?></option><?php
							}else
							{ ?>
								<option value="<?php echo $rq['position'];?>"><?php echo $rq['position'];?></option><?php
							}								
						} ?>				
					</select>								
				 </div>
				 <div class="form-group">
					<label>Employee Type</label>
					<select class="form-control" name="contract_emptype" required>
						<option value="">Select</option>
						<?php
						$query = mysql_query("SELECT * FROM employee_type");
						while($r = mysql_fetch_array($query)){
							if($d['emp_type'] == $r['emp_type']){
								echo "<option value='".$d['emp_type']."' selected>".$d['emp_type']."</option>";
							}else{
								echo "<option value='".$r['emp_type']."' >".$r['emp_type']."</option>";
							}
						}
						?>						
					</select>
				 </div>
				 <div class="form-group">
					<label>Current Status</label>
					<select class="form-control" name="contract_cstatus" 
						<?php if($_SESSION['emp_id'] =='03399-2013' || 
						$_SESSION['emp_id'] == '01476-2015' || 
						$_SESSION['emp_id'] == '02609-2015' || 
						$_SESSION['emp_id'] == '04517-2015' ||
						$_SESSION['emp_id'] == '03442-2015' || 
						$_SESSION['emp_id'] == '18217-2013' ||
						$_SESSION['emp_id'] == '06359-2013' ||
						$_SESSION['emp_id'] == '00975-2016' ||						
						$_SESSION['emp_id'] == '02951-2016' ||						
						$_SESSION['emp_id'] == '01653-2013' ||						
						$_SESSION['emp_id'] == '00556-2017' ||						
						$_SESSION['emp_id'] == '00677-2017' ||						
						$_SESSION['emp_id'] == '04819-2015'){ echo ""; } else { echo "disabled";}?>>
						<option value="">Select</option>
						<option <?php if($d['current_status'] == 'Active'):?>selected<?php endif;?>>Active</option>
						<option <?php if($d['current_status'] == 'End of Contract'):?>selected<?php endif;?>>End of Contract</option>
						<option <?php if($d['current_status'] == 'Resigned'):?>selected<?php endif;?>>Resigned</option>
						<option <?php if($d['current_status'] == 'For Promotion'):?>selected<?php endif;?>>For Promotion</option>
						<?php if($_SESSION['emp_id'] == "06359-2013"){ ?>
							<option <?php if($d['current_status'] == 'blacklisted' || $d['current_status'] == "Blacklisted"):?>selected<?php endif;?>>blacklisted</option>
						<?php } ?>
					</select>
				 </div>
				 <div class="form-group">
					<label>Position Level</label>
					<select class="form-control" name="contract_positionlevel">
						<option value="">Select</option>
						
						<option <?php if($d['positionlevel'] == '1'):?>selected<?php endif;?>>1</option>
						<option <?php if($d['positionlevel'] == '2'):?>selected<?php endif;?>>2</option>
						<option <?php if($d['positionlevel'] == '3'):?>selected<?php endif;?>>3</option>
						<option <?php if($d['positionlevel'] == '4'):?>selected<?php endif;?>>4</option>
						<option <?php if($d['positionlevel'] == '5'):?>selected<?php endif;?>>5</option>
						<option <?php if($d['positionlevel'] == '6'):?>selected<?php endif;?>>6</option>
						<option <?php if($d['positionlevel'] == '7'):?>selected<?php endif;?>>7</option>
						<option <?php if($d['positionlevel'] == '8'):?>selected<?php endif;?>>8</option>
						<option <?php if($d['positionlevel'] == '9'):?>selected<?php endif;?>>9</option>
						<option <?php if($d['positionlevel'] == '10'):?>selected<?php endif;?>>10</option>
						<option <?php if($d['positionlevel'] == '11'):?>selected<?php endif;?>>11</option>
						<option <?php if($d['positionlevel'] == '12'):?>selected<?php endif;?>>12</option>
						<option <?php if($d['positionlevel'] == '13'):?>selected<?php endif;?>>13</option>
						<option <?php if($d['positionlevel'] == '14'):?>selected<?php endif;?>>14</option>
						<option <?php if($d['positionlevel'] == '15'):?>selected<?php endif;?>>15</option>
						<option <?php if($d['positionlevel'] == '16'):?>selected<?php endif;?>>16</option>
					</select>
				</div>
			</div>
			<div class="col-md-4">
				 <div class="form-group">
					<label>Lodging</label>
					<select class="form-control" name="contract_lodging">
						<option value="">Select</option>
						<option <?php if($d['lodging'] == 'Stay-in'):?>selected<?php endif;?>>Stay-in</option>
						<option <?php if($d['lodging'] == 'Stay-out'):?>selected<?php endif;?>>Stay-out</option>
					</select>
				 </div>
				 <div class="form-group">
					<label>Position Description</label>
					<textarea class="form-control" name="contract_positiondesc" rows="3"><?php echo $d['position_desc'];?></textarea>
				 </div>
				 <div class="form-group">
					<label>Remarks</label>
					<textarea class="form-control" name="contract_remarks" rows="3"><?php echo $d['remarks'];?></textarea>
				 </div>
				 <div class="form-group">
					<button type="button" name="edit_contract" class="btn btn-primary" onclick="edit_contract()">Update</button>
					<!-- <button type="button" name="print_contract" onclick="rprint('<?php //echo $d['record_no'];?>','<?php //echo $d['emp_type'];?>')" class="btn btn-primary">Print</button>-->
					<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				 </div>
			</div>
		</div>
		<script type="text/javascript" src="js/jquery-latest.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui.js"></script>
	    <script src="assets/js/bootstrap.min.js"></script>
		<script>
			$(function() {  //minDate: new Date(), minDate: new Date(),
				$( "#sdate" ).datepicker({ dateFormat: "mm/dd/yy", changeMonth: true, changeYear: true, showButtonPanel: true });
				$( "#enddate" ).datepicker({ dateFormat: "mm/dd/yy", changeMonth: true, changeYear: true, showButtonPanel: true });
			}); 

			$('#sdate').click(function(){
			    var popup =$(this).offset();
			    var popupTop = popup.top - 80;
			    $('.ui-datepicker').css({
			      	'top' : popupTop,
			      	'position' : 'fixed',
			      	'top' : '200px',
			      	'left' : '517px',
			      	/*'display' : 'block',
			      	'z-index' : '99999'*/
			    });
			}); 

			$('#enddate').click(function(){
			    var popup =$(this).offset();
			    var popupTop = popup.top - 80;
			    $('.ui-datepicker').css({
			      	'top' : popupTop,
			      	'position' : 'fixed',
			      	'top' : '275px',
			      	'left' : '517px',
			      	/*'display' : 'block',
			      	'z-index' : '99999'*/
			    });
			});
		</script>
	<?php
	} else {
	$sql = mysql_query(
				"SELECT
					*
				 FROM
					employmentrecord_
				 WHERE
					record_no = '".$_POST['rec']."'"
			) or die(mysql_error());
	 $d = mysql_fetch_array($sql);
	?>
		<div class="row">
			<div class="col-md-7 col-md-offset-2">
				<span class="label label-danger">Required Fields : Company, Business Unit, Department, Start and End Date, Position, Employee Type, Current Status</span>
			</div>
			<br>
			<br>
			<div class="col-md-4">
				<div class="form-group">
					<label>Company</label>
					<input type="hidden" name="rec_no" value="<?php echo $_POST['rec'];?>">
					<input type="hidden" name="emp_id" value="<?php echo $d['emp_id'];?>">
					<select class="form-control" name="comp_code" onchange="comp_code(this.value)">
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
								if($res['company_code'] == $d['company_code']){
						?>
							<option selected value="<?php echo $res['company_code'];?>"><?php echo $res['company'];?></option>
						<?php } else { ?>
							<option value="<?php echo $res['company_code'];?>"><?php echo $res['company'];?></option>
						<?php	
							}
						} ?>
					<select>
				 </div>
				 <div class="form-group">
					<label>Business Unit</label>
					<select class="form-control" name="bunit_code" onchange="bunit_code(this.value)">
						<option value="">Select</option>
						<?php
							$sql = mysql_query
									("
										SELECT
											*
										FROM
											locate_business_unit
										WHERE
											company_code = '".$d['company_code']."'
										ORDER BY
											business_unit ASC
									") or die(mysql_error());
							while($res=mysql_fetch_array($sql)){ 
								if($res['bunit_code'] == $d['bunit_code']){
							?>
								<option selected value="<?php echo $res['company_code']."/".$res['bunit_code'];?>">
									<?php echo $res['business_unit'];?>
								</option>
							<?php } else { ?>
								<option value="<?php echo $res['company_code']."/".$res['bunit_code'];?>">
									<?php echo $res['business_unit'];?>
								</option>
							<?php	} 
							}?>
					<select>
				 </div>
				 <div class="form-group">
					<label>Department</label>
					<select class="form-control" name="dept_code" onchange="dept_code(this.value)">
						<option value="">Select</option>
						<?php 
						$sql = mysql_query
								("
									SELECT
										*
									FROM
										locate_department
									WHERE
										company_code = '".$d['company_code']."'
									AND
										bunit_code = '".$d['bunit_code']."'
									ORDER BY
										dept_name ASC
								") or die(mysql_error());
							while($res=mysql_fetch_array($sql)){
									if($res['dept_code'] == $d['dept_code']){
							?>
									<option selected value="<?php echo $res['company_code']."/".$res['bunit_code']."/".$res['dept_code'];?>">
										<?php echo $res['dept_name'];?>
									</option>
							<?php } else { ?>
									<option value="<?php echo $res['company_code']."/".$res['bunit_code']."/".$res['dept_code'];?>">
										<?php echo $res['dept_name'];?>
									</option>
							<?php	} 
							}?>
					<select>
				 </div>
				 <div class="form-group">
					<label>Section</label>
					<select class="form-control" name="sec_code" onchange="sec_code(this.value)">
						<option value="">Select</option>
						<?php
						$sql = mysql_query
									("
										SELECT
											*
										FROM
											locate_section
										WHERE
											company_code = '".$d['company_code']."'
										AND
											bunit_code = '".$d['bunit_code']."'
										AND
											dept_code = '".$d['dept_code']."'
										ORDER BY
											section_name ASC
									") or die(mysql_error());
							while($res=mysql_fetch_array($sql)){ 
								if($res['section_code'] == $d['section_code']){
							?>
								<option selected value="<?php echo $res['company_code']."/".$res['bunit_code']."/".$res['dept_code']."/".$res['section_code'];?>">
									<?php echo $res['section_name'];?>
								</option>
							<?php }	else { ?>
								<option value="<?php echo $res['company_code']."/".$res['bunit_code']."/".$res['dept_code']."/".$res['section_code'];?>">
									<?php echo $res['section_name'];?>
								</option>
							<?php }
							}?>
					<select>
				 </div>
				 <div class="form-group">
					<label>Sub-section</label>
					<select class="form-control" name="ssec_code" onchange="ssec_code(this.value)">
						<option value="">Select</option>
						<?php
						$sql = mysql_query
									("
										SELECT
											*
										FROM
											locate_sub_section
										WHERE
											company_code = '".$d['company_code']."'
										AND
											bunit_code = '".$d['bunit_code']."'
										AND
											dept_code = '".$d['dept_code']."'
										AND
											section_code = '".$d['section_code']."'
										ORDER BY
											sub_section_name ASC
									") or die(mysql_error());
							while($res=mysql_fetch_array($sql)){ 
								if($res['sub_section_code'] == $d['sub_section_code']){
							?>
								<option selected value="<?php echo $res['company_code']."/".$res['bunit_code']."/".$res['dept_code']."/".$res['section_code']."/".$res['sub_section_code'];?>">
									<?php echo $res['sub_section_name'];?>
								</option>
							<?php } else { ?>
								<option value="<?php echo $res['company_code']."/".$res['bunit_code']."/".$res['dept_code']."/".$res['section_code']."/".$res['sub_section_code'];?>">
									<?php echo $res['sub_section_name'];?>
								</option>
							<?php }
							}?>
					<select>
				 </div>
				 <div class="form-group">
					<label>Unit</label>
					<select class="form-control" name="unit_code">
						<option value="">Select</option>
						<?php
						$sql = mysql_query
								("
									SELECT
										*
									FROM
										locate_unit
									WHERE
										company_code = '".$d['company_code']."'
									AND
										bunit_code = '".$d['bunit_code']."'
									AND
										dept_code = '".$d['dept_code']."'
									AND
										section_code = '".$d['section_code']."'
									AND
										sub_section_code = '".$d['sub_section_code']."'
									ORDER BY
										unit_name ASC
								") or die(mysql_error());
						while($res=mysql_fetch_array($sql)){ 
							if($res['unit_code'] == $d['unit_code']){
						?>
							<option selected value="<?php echo $res['company_code']."/".$res['bunit_code']."/".$res['dept_code']."/".$res['section_code']."/".$res['sub_section_code']."/".$res['unit_code'];?>">
								<?php echo $res['unit_name'];?>
							</option>
						<?php } else { ?>
							<option value="<?php echo $res['company_code']."/".$res['bunit_code']."/".$res['dept_code']."/".$res['section_code']."/".$res['sub_section_code']."/".$res['unit_code'];?>">
								<?php echo $res['unit_name'];?>
							</option>
						<?php }
						}?>
					<select>
				 </div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label>Start Date</label>
					<input type="text" class="form-control" id="sdate" name="estart_date" value="<?php echo $nq->changeDateFormat('m/d/Y',$d['startdate']);?>" placeholder="mm/dd/yyyy">
				 </div>
				 <div class="form-group">
					<label>End Date</label>
					<input type="text" class="form-control" id="enddate" value="<?php echo $nq->changeDateFormat('m/d/Y',$d['eocdate']);?>" name="eend_date" placeholder="mm/dd/yyyy">
				 </div>
				 <div class="form-group">
					<label>Position</label>
					<select id='contract_position' name="contract_position" class='form-control'>
						<option> </option>
						<?php 
						$query = mysql_query("SELECT position FROM positions order by position asc");
						while($rq = mysql_fetch_array($query))
						{
							if($d['position'] == $rq['position'])
							{ ?>
								<option value="<?php echo $rq['position'];?>" selected><?php echo $rq['position'];?></option><?php
							}else
							{ ?>
								<option value="<?php echo $rq['position'];?>"><?php echo $rq['position'];?></option><?php
							}								
						} ?>				
					</select>	
				 </div>
				 <div class="form-group">
					<label>Employee Type</label>
					<select class="form-control" name="contract_emptype" required>
						<option value="">Select</option>
						<?php
						$query = mysql_query("SELECT * FROM employee_type");
						while($r = mysql_fetch_array($query)){
							if($d['emp_type'] == $r['emp_type']){
								echo "<option value='".$d['emp_type']."' selected>".$d['emp_type']."</option>";
							}else{
								echo "<option value='".$r['emp_type']."' >".$r['emp_type']."</option>";
							}
						}
						?>						
					</select>
					
				 </div>
				 <div class="form-group">
					<label>Current Status</label>
					<select class="form-control" name="contract_cstatus">
						<option value="">Select</option>
						<option <?php if($d['current_status'] == 'Active'):?>selected<?php endif;?>>Active</option>
						<option <?php if($d['current_status'] == 'End of Contract'):?>selected<?php endif;?>>End of Contract</option>
						<option <?php if($d['current_status'] == 'Resigned'):?>selected<?php endif;?>>Resigned</option>
						<option <?php if($d['current_status'] == 'For Promotion'):?>selected<?php endif;?>>For Promotion</option>
					</select>
				 </div>
				 <div class="form-group">
					<label>Position Level</label>
					<select class="form-control" name="contract_positionlevel">
						<option value="">Select</option>
						<option <?php if($d['positionlevel'] == '1'):?>selected<?php endif;?>>1</option>
						<option <?php if($d['positionlevel'] == '2'):?>selected<?php endif;?>>2</option>
						<option <?php if($d['positionlevel'] == '3'):?>selected<?php endif;?>>3</option>
						<option <?php if($d['positionlevel'] == '4'):?>selected<?php endif;?>>4</option>
						<option <?php if($d['positionlevel'] == '5'):?>selected<?php endif;?>>5</option>
						<option <?php if($d['positionlevel'] == '6'):?>selected<?php endif;?>>6</option>
						<option <?php if($d['positionlevel'] == '7'):?>selected<?php endif;?>>7</option>
						<option <?php if($d['positionlevel'] == '8'):?>selected<?php endif;?>>8</option>
						<option <?php if($d['positionlevel'] == '9'):?>selected<?php endif;?>>9</option>
						<option <?php if($d['positionlevel'] == '10'):?>selected<?php endif;?>>10</option>
						<option <?php if($d['positionlevel'] == '11'):?>selected<?php endif;?>>11</option>
						<option <?php if($d['positionlevel'] == '12'):?>selected<?php endif;?>>12</option>
						<option <?php if($d['positionlevel'] == '13'):?>selected<?php endif;?>>13</option>
						<option <?php if($d['positionlevel'] == '14'):?>selected<?php endif;?>>14</option>
						<option <?php if($d['positionlevel'] == '15'):?>selected<?php endif;?>>15</option>
						<option <?php if($d['positionlevel'] == '16'):?>selected<?php endif;?>>16</option>
					</select>
				</div>
			</div>
			<div class="col-md-4">
				 <div class="form-group">
					<label>Lodging</label>
					<select class="form-control" name="contract_lodging">
						<option value="">Select</option>
						<option <?php if($d['lodging'] == 'Stay-in'):?>selected<?php endif;?>>Stay-in</option>
						<option <?php if($d['lodging'] == 'Stay-out'):?>selected<?php endif;?>>Stay-out</option>
					</select>
				 </div>
				 <div class="form-group">
					<label>Position Description</label>
					<textarea class="form-control" name="contract_positiondesc" rows="3"><?php echo $d['pos_desc'];?></textarea>
				 </div>
				 <div class="form-group">
					<label>Remarks</label>
					<textarea class="form-control" name="contract_remarks" rows="3"><?php echo $d['remarks'];?></textarea>
				 </div>
				 <div class="form-group">
					<button type="button" name="edit_contract" class="btn btn-primary" onclick="edit_employment_contract()">Update</button>
					<button type="button" name="print_contract" onclick="rprint('<?php echo $d['record_no'];?>')" class="btn btn-primary">Print</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				 </div>
			</div>
		</div>
		
		<script>
			$(function() {  //minDate: new Date(), minDate: new Date(),
				$( "#sdate" ).datepicker({ dateFormat: "mm/dd/yy", changeMonth: true, changeYear: true, showButtonPanel: true });
				$( "#enddate" ).datepicker({ dateFormat: "mm/dd/yy", changeMonth: true, changeYear: true, showButtonPanel: true });
			}); 

			$('#sdate').click(function(){
			    var popup =$(this).offset();
			    var popupTop = popup.top - 80;
			    $('.ui-datepicker').css({
			      	'top' : popupTop,
			      	'position' : 'fixed',
			      	'top' : '200px',
			      	'left' : '517px',
			      	/*'display' : 'block',
			      	'z-index' : '99999'*/
			    });
			}); 

			$('#enddate').click(function(){
			    var popup =$(this).offset();
			    var popupTop = popup.top - 80;
			    $('.ui-datepicker').css({
			      	'top' : popupTop,
			      	'position' : 'fixed',
			      	'top' : '275px',
			      	'left' : '517px',
			      	/*'display' : 'block',
			      	'z-index' : '99999'*/
			    });
			}); 
			
		</script
	<?php }
}
else if($_GET['request'] == "tagAsDone")
{
	if(!@$_SESSION['emp_id']) die("Your session has expire, please login!");
	$id = mysql_real_escape_string($_POST['$val']);
	mysql_query(
		"INSERT INTO `ids_and_pins`
		 (`emp_id`,`date_tag`,`tagBy`)VALUES('".$id."','".date("Y-m-d h:ia")."','".$_SESSION['emp_id']."')"
	) or die(mysql_error());
	die("Ok");
}
else if($_GET['request'] == "genNewId"){
	function checkEmpNo($id){
		$sql = mysql_query(
				"SELECT count(`emp_no`) FROM `employee3` WHERE `emp_no` LIKE '%".$id."'"
			) or die(mysql_error());
		$res = mysql_fetch_array($sql);
		return $res['count(`emp_no`)'];
	}
	function getLastNo(){
		$sql = mysql_query(
			  "SELECT `emp_no`,`emp_pins`
			   FROM `employee3` WHERE
			   `company_code` != '07' ORDER BY `emp_no` DESC LIMIT 1"
			) or die(mysql_error());
		$res = mysql_fetch_assoc($sql);
		return $res;
	}
	function getLastPin($pin){
		$sql = mysql_query(
			  "SELECT `pin_id`
			   FROM `employee_pins`
			   WHERE `pins` = '".$pin."'"
			) or die(mysql_error());
		$res = mysql_fetch_assoc($sql);
		return $res['pin_id'];
	}
	function newEmpPin($pid){
		$sql = mysql_query(
			  "SELECT `pins`
			   FROM `employee_pins`
			   WHERE `pin_id` = '".$pid."'"
			) or die(mysql_error());
		$res = mysql_fetch_assoc($sql);
		return $res['pins'];
	}
	$newEmpNo = getLastNo()['emp_no'] + 1;
	$lastEmpPin = getLastPin(getLastNo()['emp_pins']) + 1;
	$newEmpPin = newEmpPin($lastEmpPin);
	
	if(checkEmpNo($newEmpNo) > 0){
		die("Employee No has already been assigned to another employee.\nPlease call 1844 and look for Ms. Carla for assistance.");
	}
	else {
		mysql_query("UPDATE `employee3` SET `emp_no` = '".$newEmpNo."', `emp_pins` = '".$newEmpPin."' WHERE `emp_id` = '".mysql_real_escape_string($_POST['$val'])."'") or die(mysql_error());
		mysql_query("INSERT INTO `ids_and_pins_genby`
		 (`emp_id`,`dateGen`,`genBy`)VALUES('".mysql_real_escape_string($_POST['$val'])."','".date("Y-m-d h:ia")."','".$_SESSION['emp_id']."')"
		) or die(mysql_error());
		die("Ok");
	}
}
else if(@$_GET['request'] == 'add_contract'){
	$bunit = explode("/",$_POST['bunit_code']);
	$dept = explode("/",$_POST['dept_code']);
	@$section = explode("/",$_POST['sec_code']);
	@$ssection = explode("/",$_POST['ssec_code']);
	@$unit = explode("/",$_POST['unit_code']);
	if($_POST['contract_cstatus'] == 'Active'){
		//die($_POST['employee_id']);
		$sql = mysql_query(
				"SELECT
					*
				 FROM
					employee3
				 WHERE
					emp_id = '".mysql_real_escape_string(strip_tags($_POST['employee_id']))."'
				 AND
					current_status = 'Active'"
			   ) or die(mysql_error);
		if(mysql_num_rows($sql) > 0){
			die("This Contract already Added!");
		} else {
			$name = $nq->getAppName($_POST['employee_id']);
			mysql_query(
				"INSERT
					INTO
				 employee3
					(
						emp_id,
						name,
						startdate,
						eocdate,
						emp_type,
						current_status,
						company_code,
						bunit_code,
						dept_code,
						section_code,
						sub_section_code,
						unit_code,
						positionlevel,
						position,
						position_desc,
						lodging,
						remarks
					) VALUES (
						'".mysql_real_escape_string(strip_tags($_POST['employee_id']))."',
						'".mysql_real_escape_string(strip_tags($name))."',
						'".mysql_real_escape_string(strip_tags($nq->changeDateFormat('Y-m-d',$_POST['start_date'])))."',
						'".mysql_real_escape_string(strip_tags($nq->changeDateFormat('Y-m-d',$_POST['end_date'])))."',
						'".mysql_real_escape_string(strip_tags(@$_POST['contract_emptype']))."',
						'".mysql_real_escape_string(strip_tags(@$_POST['contract_cstatus']))."',
						'".mysql_real_escape_string(strip_tags(@$_POST['comp_code']))."',
						'".mysql_real_escape_string(strip_tags(@$bunit[1]))."',
						'".mysql_real_escape_string(strip_tags(@$dept[2]))."',
						'".mysql_real_escape_string(strip_tags(@$section[3]))."',
						'".mysql_real_escape_string(strip_tags(@$ssection[4]))."',
						'".mysql_real_escape_string(strip_tags(@$unit[5]))."',
						'".mysql_real_escape_string(strip_tags(@$_POST['contract_positionlevel']))."',
						'".mysql_real_escape_string(strip_tags(@$_POST['contract_position']))."',
						'".mysql_real_escape_string(strip_tags(@$_POST['contract_positiondesc']))."',
						'".mysql_real_escape_string(strip_tags(@$_POST['contract_lodging']))."',
						'".mysql_real_escape_string(strip_tags(@$_POST['contract_remarks']))."'
					)"
			) or die(mysql_error());
			
			$name = $nq->getAppName($_POST['employee_id']);
			$nq->savelogs("Added the contract history of ".$name,date('Y-m-d'),date('H:i:s'),$_SESSION['emp_id'],$_SESSION['username']);	
		
			die("Ok");
		}
	} else {
		$sql = mysql_query(
				"SELECT
					startdate,
					eocdate
				 FROM
					employmentrecord_
				 WHERE
					startdate = '".mysql_real_escape_string(strip_tags($nq->changeDateFormat('Y-m-d',$_POST['start_date'])))."'
				 AND
					eocdate = '".mysql_real_escape_string(strip_tags($nq->changeDateFormat('Y-m-d',$_POST['end_date'])))."'
				 AND
					emp_id = '".mysql_real_escape_string(strip_tags(@$_POST['employee_id']))."'"
			   ) or die(mysql_error());
		 if(mysql_num_rows($sql) > 0){
			die("This Contract is already Added!");
		 } else {
			$sql = mysql_query(
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
							startdate,
							eocdate,
							emp_type,
							current_status,
							positionlevel,
							position,
							lodging,
							pos_desc,
							remarks
							
						)
						VALUES(
							'".mysql_real_escape_string(strip_tags(@$_POST['employee_id']))."',				
							'".mysql_real_escape_string(strip_tags(@$_POST['comp_code']))."',				
							'".mysql_real_escape_string(strip_tags(@$bunit[1]))."',				
							'".mysql_real_escape_string(strip_tags(@$dept[2]))."',				
							'".mysql_real_escape_string(strip_tags(@$section[3]))."',				
							'".mysql_real_escape_string(strip_tags(@$ssection[4]))."',				
							'".mysql_real_escape_string(strip_tags(@$unit[5]))."',				
							'".mysql_real_escape_string(strip_tags($nq->changeDateFormat('Y-m-d',@$_POST['start_date'])))."',				
							'".mysql_real_escape_string(strip_tags($nq->changeDateFormat('Y-m-d',@$_POST['end_date'])))."',				
							'".mysql_real_escape_string(strip_tags(@$_POST['contract_emptype']))."',				
							'".mysql_real_escape_string(strip_tags(@$_POST['contract_cstatus']))."',				
							'".mysql_real_escape_string(strip_tags(@$_POST['contract_positionlevel']))."',				
							'".mysql_real_escape_string(strip_tags(@$_POST['contract_position']))."',				
							'".mysql_real_escape_string(strip_tags(@$_POST['contract_lodging']))."',		
							'".mysql_real_escape_string(strip_tags(@$_POST['contract_positiondesc']))."',		
							'".mysql_real_escape_string(strip_tags(@$_POST['contract_remarks']))."'		
						)"
					) or die(mysql_error());
					
			$name = $nq->getAppName($_POST['employee_id']);
			$nq->savelogs("Added the contract history of ".$name,date('Y-m-d'),date('H:i:s'),$_SESSION['emp_id'],$_SESSION['username']);	
	
			die("Ok");
		 }
	}
}
?>
