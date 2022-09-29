<?php
include("header.php");
$date = ("Y-m-d");
$empid= $_GET['empid'];
mysql_set_charset("UTF-8");

if(isset($_POST['submit']))
{ 
	//getting location para i save sa tables
	//new location
	if($_POST['unit_code']){         $newlocation = $_POST['comp_code']."-".$_POST['bunit_code']."-".$_POST['dept_code']."-".$_POST['sec_code']."-".$_POST['ssec_code']."-".$_POST['unit_code']; }
	else if($_POST['ssec_code']){    $newlocation = $_POST['ssec_code'];  }
	else if($_POST['sec_code']){     $newlocation = $_POST['sec_code'];  } 
	else if($_POST['dept_code']){    $newlocation = $_POST['dept_code'];  }
	else if($_POST['bunit_code']){   $newlocation = $_POST['bunit_code'];  }
	else if($_POST['comp_code']){    $newlocation = $_POST['comp_code']; }
	$newlocation = str_replace("/","-",$newlocation);

	//old location
	if($_POST['unit']){              $oldlocation = $_POST['company']."-".$_POST['businessunit']."-".$_POST['department']."-".$_POST['section']."-".$_POST['subsection']."-".$_POST['unit']; }
	else if($_POST['subsection']){   $oldlocation = $_POST['company']."-".$_POST['businessunit']."-".$_POST['department']."-".$_POST['section']."-".$_POST['subsection'];  }
	else if($_POST['section']){      $oldlocation = $_POST['company']."-".$_POST['businessunit']."-".$_POST['department']."-".$_POST['section'];  } 
	else if($_POST['department']){   $oldlocation = $_POST['company']."-".$_POST['businessunit']."-".$_POST['department'];  }
	else if($_POST['businessunit']){ $oldlocation = $_POST['company']."-".$_POST['businessunit'];  }
	else if($_POST['company']){      $oldlocation = $_POST['company']; }                     
		
	$cc = $_POST['company'];
	$bc = $_POST['businessunit'];

	$currentstatus  = "Active";        
	$status         = "transferred";   
	$empid          = $_POST['empid']; 
	$effectiveon    = $nq->changeDateFormat('Y-m-d',$_POST['effectiveon']);
	$assignedfrom   = $_POST['from'];

	$supervisor 	= explode("*",$_POST['supervision']);
	$supervisor 	= trim($supervisor[0]);

	$re             = $_POST['re'];
	$pos            = $_POST['contract_position']; 
	$prev_pos       = $_POST['prev_pos'];
	$oldpos         = $_POST['oldpos'];   
	$entrydate		= date('Y-m-d');	
	$level 			= $_POST['level'];
	$entryby		= $_SESSION['emp_id'];
	$oldpayroll		= $_POST['payrollno'];

	$transfertype 	= $_POST['transfer_type'];
	$emptype 		= $_POST['emptype'];

	//carbon copy
	$cc1     = addslashes(@$_POST['cc1']);
	$cc2     = addslashes(@$_POST['cc2']);
	$cc3     = mysql_real_escape_string(@$_POST['cc3']);
	$cc4     = mysql_real_escape_string(@$_POST['cc4']);
	$cc5     = mysql_real_escape_string(@$_POST['cc5']);
	$cc6     = mysql_real_escape_string(@$_POST['cc6']);
	$ccopy   = $cc1."$".$cc2."$".$cc3."$".$cc4."$".$cc5."$".$cc6; 

	$select 	= mysql_query("SELECT record_no, position, payroll_no, poslevel FROM employee3 WHERE emp_id = '$empid' ");
	$row 		= mysql_fetch_array($select);
	$oldposition= $row['position'];
	$oldlevel 	= $row['poslevel'];
	$oldpayroll = $row['payroll_no'];
	$record_no 	= $row['record_no'];

	if(@$oldlocation == ""){?>
	<script>alert('Please update first the current location of the employee before adding job transfer. Thank you'); 
	window.location = "?p=home";</script><?php 
	}
	else
	{ 
		$process = 'no'; //1 means for process pa, e update pa ang effectivity date depende sa nakasave 		
		$insert_query = mysql_query("
			INSERT INTO `employee_transfer_details` (
				`transfer_no`, 
				`emp_id`, 
				`record_no`,
				`effectiveon`,
				`old_position`,
				`old_level`,
				`position`,
				`level`,
				`old_location`,
				`new_location`,
				`carbon_copy`,
				`assignedfrom`,
				`supervision`,
				`reason`,
				`status`,
				`file`,
				`old_payroll_no`,
				`entry_date`,
				`entry_by`,
				`process`,
				`type_of_transfer`,
				`transfer_to_emptype`)
			VALUES 
				('',
				'$empid',
				'$record_no',
				'$effectiveon',
				'$oldposition',
				'$oldlevel',
				'$pos',
				'$level',
				'$oldlocation',
				'$newlocation',
				'$ccopy',
				'$assignedfrom',
				'$supervisor',
				'$reason',
				'$status',
				'$file',
				'$oldpayroll',
				'$entrydate',
				'$entryby',
				'$process',
				'$transfertype',
				'$emptype') ")or die(mysql_error());

		$transquery = mysql_query("SELECT max(transfer_no) as transno from employee_transfer_details where emp_id = '$empid' ");
		$tr         = mysql_fetch_array($transquery);
		$transno    = $tr['transno'];

		if($insert_query) // && $update_employee3
		{
			$select_q = mysql_query("SELECT * FROM leveling_subordinates WHERE ratee = '$supervisor' and subordinates_rater = '$empid' ");
			if(mysql_num_rows($select_q) == 0){
				//insert subordinates
				$insert_sub = mysql_query("INSERT INTO `leveling_subordinates`
					(`record_no`, `ratee`, `subordinates_rater`, `ratee_stat`, `removeNo`) 
					VALUES ('','$supervisor','$empid','','') ");
			}

			if($transfertype == "nescotoae")
			{ ?>
				<script>
					var recordno 	= "<?= $record_no;?>";
					var empid 		= "<?= $empid;?>";
					var transno 	= "<?= $transno;?>";
					var cc 			= "<?= $_POST['company'];?>";
					var bc 			= "<?= $_POST['businessunit'];?>";

					window.open(`../report/nesco_to_ae_transfer.php?rec=${recordno}&empid=${empid}&transno=${transno}&cc=${cc}&bc=${bc}`);	
					window.location = '?p=jobtransfers';
				</script> <?php
			}else{ ?>
				<script>
					var recordno 	= "<?= $record_no;?>";
					var empid 		= "<?= $empid;?>";
					var transno 	= "<?= $transno;?>";

					window.open(`../report/newtransfer.php?rec=${recordno}&empid=${empid}&transno=${transno}`);	
					window.location = '?p=jobtransfers';	
				</script> <?php
			} ?>
			<?php 
		}
		else
		{
			echo "Creating report error!";
		}   
	}   
}
else if(isset($empid))
{        
    $query = mysql_query("SELECT 
		payroll_no, name, record_no, company_code, bunit_code, dept_code, section_code, sub_section_code, unit_code, position, poslevel, lodging, emp_type, startdate, eocdate, current_status
	FROM employee3 where emp_id = '$empid' order by name,record_no desc");  
	$row = @mysql_fetch_array($query);
	
	$name 	 = $row['name'];
	$company = $nq->getCompanyName($row['company_code']);
	$bunit 	 = $nq->getBusinessUnitName($row['bunit_code'],$row['company_code']);
	$dept 	 = $nq->getDepartmentName($row['dept_code'],$row['bunit_code'],$row['company_code']);
	$section = $nq->getSectionName($row['section_code'],$row['dept_code'],$row['bunit_code'],$row['company_code']);
	$subsec	 = $nq->getSubSectionName($row['sub_section_code'],$row['section_code'],$row['dept_code'],$row['bunit_code'],$row['company_code']);
	$unit	 = $nq->getUnitName($row['unit_code'],$row['sub_section_code'],$row['section_code'],$row['dept_code'],$row['bunit_code'],$row['company_code']);
	
	$com	= $row['company_code'];
	$bun 	= $row['bunit_code'];
	$dep	= $row['dept_code'];
	$sec	= $row['section_code'];
	$ssec	= $row['sub_section_code'];
	$un		= $row['unit_code'];
	
	//if($row['effectiveon'] !='' || $row['effectiveon'] !='0000-00-00'){ $effectiveon = ''; } else { $effectiveon = $nq->ChangeDateFormat('m/d/Y',$row['effectiveon']); }
	if($row['startdate'] !='' || $row['startdate'] != '0000-00-00'){ $startdate = ''; } else { $startdate = $nq->ChangeDateFormat('m/d/Y',$row['startdate']); } 
	if($row['eocdate'] !='' || $row['eocdate'] != '0000-00-00'){ $eocdate = ''; } else { $eocdate = $nq->ChangeDateFormat('m/d/Y',$row['eocdate']); } 
	
}

$signatory = "<p>MERCEDES NARCE <BR>NESCO MANAGER</p>";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<style>.theight {height:28px;width:500px} .red{ color:red}</style>
<title>HRMS</title>

<style type="text/css">    
    .search-results{
       box-shadow: 5px 5px 5px #ccc; 
       margin-top: 1px; 
       margin-left : 0px; 
       background-color: #F1F1F1;
       width : 57%;
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

    .rqd{
    	color:red;
    }
</style>
</head>

<body>
<div style="width:98%; margin-left:auto; margin-right:auto; ">   	
	<div class='panel panel-default'>
	<div class='panel-heading'>
		<div style='font-size:24px;text-indent:10px;'> Job Transfer of <?= utf8_encode($name);?></div>
	</div>        
    <div class="panel-body">	
		<div class='row'>
			<div class='col-md-4'>
				<table class='table'>
					<tr>
					  	<td colspan="2" style="background-color:darkgreen; color:white"><i>(Current Details)</i></td>
					</tr>
					<tr><td align="right"><b>Company</b></td>			<td><i><?php echo $company;?></td></i> </tr>					
					<tr><td align="right"><b>Business Unit</b></td>   <td><i><?php echo $bunit;?></td></i> </tr>
					<tr><td align="right"><b>Department</b></td>		<td><i><?php echo $dept;?></td></i> </tr>
					<tr><td align="right"><b>Section</b></td>			<td><i><?php echo $section;?></td></i> </tr>
					<tr><td align="right"><b>Sub Section</b></td> 	<td><i><?php echo $subsec;?></td></i> </tr>
					<tr><td align="right"><b>Unit</b></td>			<td><i><?php echo $unit;?></td></i> </tr>
					<tr><td align="right"><b>Position</b></td> 		<td><i><?php echo $row['position'];?></td></i> </tr>
					<tr><td align="right"><b>Position Level</b></td> 	<td><i><?php echo $row['poslevel'];?></td></i> </tr>
					<tr><td align="right"><b>Lodging</b></td> 		<td><i><?php echo $row['lodging'];?></td></i> </tr>
					<tr><td align="right"><b>Employee Type</b></td>	<td><i><?php echo $row['emp_type'];?></td></i> </tr>
					<tr><td align="right"><b>Startdate</b></td>		<td><i><?php echo $startdate;?></td></i> </tr>
					<tr><td align="right"><b>Eocdate</b></td>			<td><i><?php echo $eocdate;?></td></i> </tr>
					<tr><td align="right"><b>Current Status</b></td>			<td><i><?php echo $row['current_status'];?></td></i> </tr>
				</table>
			</div>
			<div class='col-md-8'>				
				<form action='?p=transfers&&empid=<?php echo $empid;?>' method='post' onsubmit='return validate()'>
					<!--hidden values-->
					<input type='hidden' value='<?php echo $com;?>' name='company'>
					<input type='hidden' value='<?php echo $bun;?>' name='businessunit'>
					<input type='hidden' value='<?php echo $dep;?>' name='department'>
					<input type='hidden' value='<?php echo $sec;?>' name='section'>
					<input type='hidden' value='<?php echo $ssec;?>' name='subsection'>
					<input type='hidden' value='<?php echo $un;?>' name='unit'>
					<input type='hidden' value='<?php echo $row['record_no'];?>' name='rec'>
					<input type='hidden' value='<?php echo $empid;?>' name='empid'>
					<input type='hidden' value='<?php echo $row['position'];?>' name='oldpos'>
					<input type='hidden' value='<?php echo $row['payroll_no'];?>' name='payrollno'>

					<table class="table" width="80%"> 
						<tr>
						  	<td><b>Direct Supervisor</b> <span class="red">*</span></td>
								<td> <input type="text" class="form-control" required name="supervision" id= supervision' onkeyup="namesearch(this.value)" placeholder="Lastname, Firstname" value="" autocomplete="off" required="">
								<div class="search-results" style="display:none;"></div>
							</td>
						</tr>
						<tr>
						  	<td align="right"><b>New Position <span class="red">*</span></b> </td>              
						  	<td>	
								<input type='hidden' name='prev_pos' id='prev_pos' value="<?php echo @$pos;?>">	
								<select class='form-control' id="contract_position" required  name="contract_position" onchange="getLevel(this.value)">
									<option value="">Select</option>			              
									<?php $query = mysql_query("SELECT position_title from position_leveling order by position_title ");  
									while($rs = mysql_fetch_array($query)) { 
										echo "<option value='".$rs['position_title']."'>".$rs['position_title']."</option>";  
									}?>
								</select>
							</td>
						</tr> 
						<tr>
							<td align="right"><b> New Level </b></td>
							<td> <input type='text' id='level' required name="level" readonly="" style="width: 100%"> </td>
						</tr>  
						<tr>
						  	<td align="right"><b>Effectiveon <span class="red">*</span></b></td>              
						  	<td><input type="text" name="effectiveon" id="effectiveon" class='theight'  placeholder='mm/dd/yyyy' required/></td>
						</tr>        
						<tr>
						  	<td colspan="2" style="background-color:darkgreen; color:white"><i>(Please fill up for the letter heading)</i></td>
						</tr>
						<tr>
						  	<td><b>To</b> </td>
						  	<td>
								<input id="to" class='theight' type="text" name="to" autocomplete="off" placeholder="  Search Employee" value="<?php echo utf8_encode($row['name']);?>"/>
							</td>
						</tr>
						<tr>
						  	<td><b>From <span class="red">*</span></b> </td>
						  	<td><input type="text" class='theight' name="from" id="from" required value='NESCO HRD' onkeyup='changeborder(this.id)'/></td>
						</tr>
						<tr>
						  	<td><b>Re</b> </td>
						  	<td><input type="text" class='theight' name="re" id="re" readonly value="Job Transfer"/></td>
						</tr>
						<tr>
						  	<td colspan="2" style="background-color:darkgreen; color:white"><i>(New Location Details)</i></td>
						</tr>
						<tr>
						  	<td align="right"><b>Company</b> <span class="red">*</span></td>
						  	<td> <input type="hidden" name="cc" id='cc' value="<?php echo @ $cc;?>"/>
								<select class="form-control" name="comp_code" onchange='getCompany(this.value)' required>
									<option value="">Select</option>
									<?php 
										$sub_query = mysql_query("SELECT * FROM locate_company where status ='active' ORDER BY company ASC") or die(mysql_error());
										while($res=mysql_fetch_array($sub_query)){ ?>
										<option value="<?php echo $res['company_code'];?>" <?php if(@$cc==$res['company_code']){ echo "selected";}?>><?php echo $res['acroname'];?></option>
									<?php } ?>
								<select>
						  	</td>            
						</tr>
						<tr>
						  	<td align="right"><b>Business Unit</b> <span class="red">*</span></td>
						  	<td> <input type="hidden" name="bc" id='bc' value="<?php echo @$bc;?>"/>
							  	<select class="form-control" name="bunit_code" onchange='getBusinessUnit(this.value)' required>
									<?php
									if(@$bc || @$cc){
										echo '<option value="">Select</option>';
										$sql = mysql_query("SELECT * FROM locate_business_unit WHERE company_code = '".$cc."' ORDER BY business_unit ASC");
										while($res = mysql_fetch_array($sql)){?>
										<option value='<?php echo $cc."/".$res['bunit_code'];?>' <?php if($bc==$res['bunit_code']){ echo "selected";}?>><?php echo $res['business_unit'];?></option> <?php
										}
									}else{?>
									<option value="">Select</option>
									<?php } ?>
								<select>
						  	</td>             
						</tr>
						<tr>
						  	<td align="right"><b>Department</b> <span class="red">*</span></td>
						  	<td> <input type="hidden" name="dc" id='dc' value="<?php echo @$dc;?>"/>
								<input type="hidden" name="dc" value="<?php echo @$dc;?>"/>
								<select class="form-control" name="dept_code" onchange='getDepartment(this.value)' required>				
									<?php
									if(@$dc || @$bc){
										echo '<option value="">Select</option>';
										$sql = mysql_query("SELECT * FROM locate_department WHERE company_code = '".$cc."' and bunit_code = '".$bc."' ORDER BY dept_name ASC");
										while($res = mysql_fetch_array($sql)){?>
											<option value='<?php echo $cc."/".$bc."/".$res['dept_code'];?>' <?php if($dc==$res['dept_code']){ echo "selected";}?>>
											<?php echo $res['dept_name'];?></option><?php
										}
										
									}else{?>
									<option value="">Select</option>
									<?php } ?>
								<select>
						  	</td>              
						</tr>					
						<tr>
						  	<td align="right"><b>Section</b></td>
						  	<td> <input type="hidden" name="sc" id='sc' value="<?php echo @$sc;?>"/>
								<input type="hidden" name="sc" value="<?php echo @$sc;?>"/>
								<select class="form-control" name="sec_code" onchange='getSection(this.value)'>
									<?php
									if(@$sc || @$dc){
										echo '<option value="">Select</option>';
										$sql = mysql_query("SELECT * FROM locate_section WHERE company_code = '".$cc."' and bunit_code = '".$bc."' and dept_code = '".$dc."' 
												ORDER BY section_name ASC");
										while($res = mysql_fetch_array($sql)){?>
											<option value='<?php echo $cc."/".$bc."/".$dc."/".$res['section_code'];?>' <?php if($sc==$res['section_code']){ echo "selected";}?>>
											<?php echo $res['section_name'];?></option><?php
										}
									}else{ ?>
										<option value="">Select</option>
									<?php } ?>
								<select>
							</td>            
						</tr>
						<tr>
						  	<td align="right"><b>Sub-section</b></td>
						  	<td> <input type="hidden" name="ssc" id='ssc' value="<?php echo @$ssc;?>"/>
								<input type="hidden" name="ssc" value="<?php echo @$ssc;?>"/>
								<select class="form-control" name="ssec_code" onchange='getSubSection(this.value)'>
									<?php
									if(@$ssc || @$sc){
										echo '<option value="">Select</option>';
										$sql = mysql_query("SELECT * FROM locate_sub_section WHERE company_code = '".$cc."' and bunit_code = '".$bc."' and dept_code = '".$dc."' and section_code = '".$sc."' ORDER BY sub_section_name ASC ");
										while($res = mysql_fetch_array($sql)){?>
											<option value='<?php echo $cc."/".$bc."/".$dc."/".$sc."/".$res['sub_section_code'];?>' <?php if($ssc==$res['sub_section_code']){ echo "selected";}?>>
											<?php echo $res['sub_section_name'];?></option><?php
										}
									}else{?>
										<option value="">Select</option>
										<?php } ?>
								<select>
							</td>              
						</tr>
						<tr>              
						  	<td align="right"><b>Unit</b></td>
						  	<td><input type="hidden" name="uc" id='uc' value="<?php echo @$uc;?>"/>
								<select class="form-control" name="unit_code">
									<option value="">Select</option>
								<select>
							</td> 
						</tr>						         
						<tr> <td colspan="2" style="background-color:darkgreen; color:white"><i>(Please fill up for the cc)</i></td></tr>      
						<tr> <td></td> <td>1) <input type="text" name="cc1" id="cc1" size="40" value='NESCO' required/> <span class="red">*</span></td> </tr>
						<tr> <td></td> <td>2) <input type="text" name="cc2" id="cc2" size="40" value="201 File" required/> <span class="red">*</span></td></tr>
						<tr> <td></td> <td>3) <input type="text" name="cc3" id="cc3" size="40" value="<?php echo utf8_encode($row['name']);?>"/></td> </tr>
						<tr> <td></td> <td>4) <input type="text" name="cc4" id="cc4" size="40"/></td> </tr>
						<tr> <td></td> <td>5) <input type="text" name="cc5" id="cc5" size="40"/></td> </tr>
						<tr> <td></td> <td>6) <input type="text" name="cc6" id="cc6" size="40"/></td> </tr>
						<tr>
							<td colspan="2" style="background-color:darkgreen; color:white"> (Indicate Type of Transfer) </td>	
						</tr>
						<tr>
							<td align="right"> Type of Transfer <span class="red">*</span> </td>
							<td> 
								<select name='transfer_type' class="form-control"  required onchange="showEmptype(this.value)" style="width: 100%">
									<option> </option>
									<option value='jobtransfer'> Job Transfer </option>
									<option value='nescotoae'> NESCO to AE - Transfer </option>
								</select>
							</td>
						</tr>
						<tr>
							<td align="right">  New Employee Type </td>
							<td>
								<select class="form-control"  name='emptype' id='emptype' style="width: 100%; display: none">
									<option> </option>
									<option value='Regular'> Regular </option>
									<option value='Regular Partimer'> Regular Partimer </option>
									<option value='Contractual'> Contractual </option>
									<option value='Probationary'> Probationary </option>
								</select>
							</td>
						</tr>
						<tr><td>&nbsp;</td>
						  	<td>
								<input type='submit' name='submit' id='submit' class='btn btn-success btn-sm' value='Generate Job Transfer Report'/>      
							</td>
						  	<td colspan="2">&nbsp;</td>
						</tr>
						<tr><td colspan="2"><i>Note : Mark with <span class='red'>*</span> means required fields.</i></td></tr>
						</table>
					</form>	  
				</div>
			</div>
		</div>        
    </div>
</div>
<br>

<link rel="stylesheet" href="../css/sweetalert.css" type="text/css" media="screen, projection" /> 
<script src="../jquery/sweetalert.js" ></script>
<script>

	function namesearch(key)
	{
	    $(".search-results").show();

	    var str = key.trim();
	    $(".search-results").hide();
	    if(str == '') {
	        $(".search-results-loading").slideUp(100);
	    }
	    else {
			$.ajax({
				type : "POST",
				url  : "functionquery.php?request=findEmployeeSupervisor",
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

	function getEmpId_2(id)
	{
		var id 		= id.split("*");
		var empId 	= id[0].trim();  
		var name 	= id[1].trim();
		var status 	= id[2].trim();
		
		$("[name='supervision']").val(empId+" * "+name);
		$(".search-results").hide();  
		$("[name='cc4']").val(name);
	}

	function getLevel(position)
    {    	
    	$.ajax({
			type : "POST",
			url : "functionquery.php?request=getLevel",
			data : { position:position },
			success : function(data){
				$("#level").val(data);
			}
		});
    }
	
    function showEmptype(val)
    { 
    	if(val == 'nescotoae'){
    		$("#emptype").show();
    	}else{
    		$("#emptype").hide();	
    	}
    }
	
	function changeborder(id){
		$('#'+id).css('border-color','#ccc');
	}
	function validate()
	{
		var from = $('#from').val()
		from = from.trim();
		var supervisor = $('#supervision').val();
		supervisor = supervisor.trim();
		var prev_pos = $('#contract_position').val();
		prev_pos = prev_pos.trim();
		
		if(from == '' || supervisor == '' || prev_pos == '')
		{
			if(from == ''){
				$('#from').focus();		
				$('#from').css('border-color','red');			
			}
			if(supervisor == ''){
				$('#supervision').focus();		
				$('#supervision').css('border-color','red');			
			}
			if(prev_pos == ''){				
				$('#contract_position').focus();		
				$('#contract_position').css('border-color','red');			
			}
			return false;
		}
		else{ 
			return true;
		}
	}

	function getCompany(id){
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

	function getBusinessUnit(id){	
		$.ajax({
			type : "POST",
			url : "ajax.php?load=dept",
			data : { id : id },
			success : function(data){
				$("[name='dept_code']").html(data);
				$("[name='sec_code']").html('<option value="">Select</option>');
				$("[name='ssec_code']").val('');
				$("[name='unit_code']").val('');
			}
		});	
	} 

	function getDepartment(id)
	{	
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

	function getSection(id){
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

	function getSubSection(id){
		$.ajax({
			type : "POST",
			url : "ajax.php?load=unit",
			data : { id : id },
			success : function(data){
				$("[name='unit_code']").html(data);
			}
		});	
	}

	function jobtransfer(emp,rec){
		window.location = "job_transfer.php?empid="+emp+"&rec="+rec
	}
	function set_transfer(msg)
	{
		$('#trans').html(msg);
	} 

	$("#buttos").click(function(){
		alert('test')
	});


</script>
</body>
</html>