<?php include("header.php"); 
	$record = $_GET['rec'];
	$empid = $_GET['e'];

	if(isset($empid) && isset($record))
	{ 
		$reap = @$_GET['reap'];
		if($reap =='reapply')
		{
			$name = $nq->getEmpName($empid);
			$log  = date('Y-m-d H:i:s')."|$empid|$name|".$_SESSION['emp_id']."|".$nq->getEmpName($_SESSION['emp_id'])." \r\n";
			$logDir   = "../logs/reapply/"; 
			$filename = "reapply-";
			$nq->writeLogs($log,$logDir,$filename);
		}
		
		//open else ug ang close else toa sa kinaubsan
		$query = mysql_query("SELECT * FROM employee3 WHERE emp_id  ='$empid' and record_no = '$record'")or die(mysql_error()); 				 				 	
		while($r = mysql_fetch_array($query)){ 
			
			$name	= $r['name']; 
			$pos	= $r['position']; 
			$cc		= $r['company_code'];
			$bc		= $r['bunit_code'];
			$dc		= $r['dept_code'];	
			$sc		= $r['section_code'];
			$ssc	= $r['sub_section_code'];
			$uc		= $r['unit_code'];
			$lodge  = $r['lodging'];
			$type 	= $r['emp_type'];
			$sd		= $r['startdate'];
			$ed		= $r['eocdate'];	
			$pos_level = $r['poslevel'];
			$current_status = $r['current_status'];
			//added 6-25-15
			$barcodeid 	= $r['barcodeId'];
			$biometricid= $r['bioMetricId'];
			$payrollno	= $r['payroll_no'];
			$pcc		= $r['pcc'];
			
			$sdate = new DateTime($r['startdate']);		
			$edate = new DateTime($r['eocdate']);		
		}
	} else
?>

<style type="text/css">
	
	.red { color:red; }

	.btn, .form-control, .panel, .panel-heading, .modal-content, .thumbnail, .input-group-addon
    {
      border-radius: 0px;
    }

    .display_ {
    	display: none;
    }

</style>
<div class="container-fluid">
    <div class="panel panel-default">

        <div class="panel-heading">  
            <div class="row">
              <div class="col-md-12">      
                <span style="font-size:18px;"> RENEWAL OF CONTRACTS - <?php echo "[".$empid."] ".ucwords(strtolower($name)); ?></span>
              </div>
            </div>  
        </div>  
        <div class="panel-body">


            <table class="table table-bordered"> 
	        	<input type="hidden" name="recordNo" value="<?php echo $record;?>"/>
				<input type="hidden" name="empId" value="<?php echo $empid;?>"/>	             
				<input type="hidden" name="name" value="<?php echo $name;?>"/>
				<input type="hidden" name="current_status" value="<?php echo $current_status;?>"/>
				
				<!-- added 6-25-15 -->
				<input type='hidden' name='barcodeid' value='<?php echo $barcodeid;?>'>
				<input type='hidden' name='biometricid' value='<?php echo $biometricid;?>'>
				<input type='hidden' name='payrollno' value='<?php echo $payrollno;?>'>
				<input type='hidden' name='pcc' value='<?php echo $pcc;?>'>

            	<input type="hidden" name="temp_company" value="<?php echo $cc;?>">
            	<input type="hidden" name="temp_businessunit" value="<?php echo $bc;?>">
            	<input type="hidden" name="temp_department" value="<?php echo $dc;?>">
            	<input type="hidden" name="temp_section" value="<?php echo $sc;?>">
            	<input type="hidden" name="temp_subsection" value="<?php echo $ssc;?>">
            	<input type="hidden" name="temp_unit" value="<?php echo $uc;?>">
            	<input type="hidden" name="temp_lodging" value="<?php echo $lodge;?>">
            	<input type="hidden" name="temp_position" value="<?php echo $pos;?>">
            	<input type="hidden" name="temp_type" value="<?php echo $type;?>">
            	<input type="hidden" name="temp_pos_level" value="<?php echo $pos_level;?>">

            	<!-- ilhanan kung geedit or renew largo :) -->

            	<input type="hidden" name="notEdited" value="1">
            	<input type="hidden" name="edited" value="0">

	            <tr>
	              	<th width="19%">&nbsp;</th>
	              	<th> Previous Contract Details </th>
	              	<th> New Contract Details
						<a href="javascript:void" onclick="edit_renew_control();" id="edit_new" class="btn btn-primary btn-sm pull-right">Edit</a>
						<a href="javascript:void" id="cancel_new" onclick="cancel_renew_control();" class="btn btn-danger btn-sm pull-right display_">Cancel</a>
				  	</th>
	            </tr>	  
	            <tr>
	              	<th>COMPANY</th>
	              	<td><?php $company = $nq->getCompanyName($cc); echo $company; ?></td>
	              	<td>
						<input type="hidden" name="company" value="<?php echo $cc;?>"/>
						<span id="company_label"> <?php echo $company; ?> </span>
						<select id="company_select" name="company_select" class="form-control display_" onchange="company_select(this.value)"></select>
				  	</td>
	            </tr>
	            <tr>
	              	<th> BUSINESS UNIT </th>
	              	<td><?php $businessunit = $nq->getBusinessUnitName($bc,$cc); echo @$businessunit; ?></td>
	              	<td>
						<input type="hidden" name="businessunit" value="<?php echo $bc;?>"/>
						<span id="bunit_label">
							<?php $businessunit = $nq->getBusinessUnitName($bc,$cc); echo @$businessunit; ?>
						</span>
						<select name="bunit_select" id="bunit_select" class="form-control display_" onchange="bunit_select(this.value)"></select>
				  	</td>
	            </tr>
	            <tr>
	              	<th> DEPARTMENT </td>
	              	<td><?php $dept = $nq->getDepartmentName($dc,$bc,$cc); echo @$dept; ?></td>
	              	<td>
						<input type="hidden" name="department" value="<?php echo $dc;?>"/>
						<span id="dept_label">
							<?php $dept = $nq->getDepartmentName($dc,$bc,$cc); echo @$dept; ?>
						</span>
						<select name="dept_select" id="dept_select" class="form-control display_" onchange="dept_select(this.value)"></select>
				  	</td>
	            </tr>
	            <tr>
	              	<th> SECTION </th>
	              	<td><?php $section = $nq->getSectionName($sc,$dc,$bc,$cc); echo @$section; ?></td>
	              	<td>
						<input type="hidden" name="section" value="<?php echo $sc;?>"/>
						<span id="section_label">
							<?php $section = $nq->getSectionName($sc,$dc,$bc,$cc); echo @$section; ?>
						</span>
						<select id="section_select" name="section_select" class="form-control display_" onchange="section_select(this.value)"></select>
				  	</td>
	            </tr>
	            <tr>
	              	<th> SUBSECTION </td>
	              	<td><?php $subsection = $nq-> getSubSectionName($ssc,$sc,$dc,$bc,$cc); echo $subsection; ?></td>
	              	<td>
						<input type="hidden" name="subsection" value="<?php echo $ssc;?>"/>
						<span id="ssection_label">
							<?php $subsection = $nq-> getSubSectionName($ssc,$sc,$dc,$bc,$cc); echo $subsection; ?>
						</span>
						<select id="ssection_select" name="ssection_select" class="form-control display_" onchange="ssection_select(this.value)"></select>
				  	</td>
	            </tr>
	            <tr>
	              	<th> UNIT </td>
	              	<td><?php $unit = $nq->getUnitName($uc,$ssc,$sc,$dc,$bc,$cc); echo $unit; ?></td>
	              	<td>
						<input type="hidden" name="unit" value="<?php echo $uc;?>"/>
						<span id="unit_label">
							<?php $unit = $nq->getUnitName($uc,$ssc,$sc,$dc,$bc,$cc); echo $unit;?>
						</span>
						<select id="unit_select" class="form-control display_" name="unit_select"></select>
				  	</td>
	            </tr>
	            <tr>
	              	<th> POSITION </td>
	              	<td><?php echo $pos; ?></td>
	              	<td>
						<input type="hidden" name="position" value="<?php echo $pos;?>"/>
						<span id="position_label">
							<?php echo $pos; ?>
						</span>
						<select id="position_select" onchange='getLevel(this.value)' class="form-control display_" name="position_select"></select>
				  	</td>
	            </tr>
				<tr>
					<th> POSITION LEVEL </th>
					<td><?php echo $pos_level; ?></td>
					<td>
						<input type="hidden" name="pos_level" value="<?php echo $pos_level;?>">
						<span id="positionlevel_label">
							<?php echo $pos_level;?>
						</span>
						<select name="poslevel_select" class="form-control display_" id="poslevel_select"></select>
					</td>
				</tr>
	            <tr>
	              	<td><strong> LODGING </strong></td>
	              	<td><?php echo $lodge; ?></td>
	              	<td>
						<input type="hidden" name="lodging" value="<?php echo $lodge;?>"/>
						<span id="lodging_label">
							<?php echo $lodge;?>
						</span>
						<select id="lodging_select" class="form-control display_" name="lodging_select" style="display:none"></select>
				  	</td>
	            </tr>
	            <tr>
	              	<th> TYPE </th>
	              	<td><?php echo $type; ?></td>
	              	<td>
						<input type="hidden" name="type" value="<?php echo $type;?>"/>
						<span id="type_label">
							<?php echo $type;?>
						</span>
						<select id="type_select" class="form-control  display_" name="type_select"  style="display:none"></select>
				  	</td>
	            </tr>	    
	            <tr>
	              <th colspan="3"> <i>INCLUSIVE DATES OF CONTRACT</i> </th>
	            </tr>
	            <tr>
	              	<th> START DATE </th>
	              	<td><?php echo $sdate = $sdate->format("M. d, Y"); ?></td>
	              	<td>
	              		<input type="text" class="form-control" name="startdate" id="startdate" placeholder="mm-dd-yyyy" size="65" autocomplete="off" onchange="ondate(this.id)"/>
	              	</td>
	            </tr>
	            <tr>
	              	<th> END DATE </th>
	              	<td><?php echo $edate = $edate->format("M. d, Y"); ?></td>
	              	<td>
	              		<input type="text" class="form-control" name="eocdate"  id="eocdate" placeholder="mm-dd-yyyy" autocomplete="off" size="65" onclick="getdate2()" onchange="ondate(this.id)"/>
	              	</td>
	            </tr>
	            <tr>	
	              	<th> No. of Month(s) to Work </th>
	              	<td>&nbsp;</td>
	              	<td>
		            	<select name="months" id="months" class="form-control" onChange="month()">
					  		<option value=""></option>
					  		<option value="extension">extension (less than 1 month)</option>
							<option value="1">1 month</option>
							<option value="1.5">1.5 month</option>
							<option value="2">2 months</option>
							<option value="2.5">2.5 months</option>
							<option value="3">3 months</option>
							<option value="3.5">3.5 months</option>
							<option value="4">4 months</option>
							<option value="4.5">4.5 months</option>
							<option value="5">5 months</option>	
							<option value="6">6 months </option>					
							<option value="12">12 months </option>
				  		</select>
				  	</td>
	            </tr>
	            <tr>
	              	<th colspan="2"><strong>SIGNED IN THE PRESENCE OF</strong></th>
	              	<th>Comments / Remarks</th>
	            </tr>
	            <tr>
	              	<th colspan="2"> WITNESS 1: <input type="text" style="text-transform:uppercase;" class="form-control" name="witness1" id="witness1" size="50"  placeholder="Firstname Lastname" onkeyup="onkeyupWitness(this.id)" /></td>
	              	<th>Comment: <input type="text" class="form-control" name="comment" size="60"/></th>
	            </tr>
	            <tr>
	              	<th colspan="2"> WITNESS 2: <input type="text" style="text-transform:uppercase;" class="form-control" name="witness2" id="witness2" size="50" placeholder="Firstname Lastname" onkeyup="onkeyupWitness(this.id)" /></td>
	              	<th> Remarks:  <input type="text" class="form-control" name="remarks" id="remarks" size="60"/></th>
	            </tr>
	            <tr>
	            	<th colspan="2"><i>Note: Mark with <span class="red">*</span> means required.</i></th>
	              	<td>
						<input name="submit" class="btn btn-primary" value="Submit" onclick="submit_()" id="submit_renewal"/>&nbsp;
						<input type="button" class="btn btn-danger"  value="Cancel" onclick="cancel()" id="button" /><input type="hidden" name="code" id="code" value="<?php echo @$code;?>"/></td>
	            </tr>
	       </table>
        </div>
      </div>
</div>

<div id = "viewManual" class="modal fade bs-example-modal-md">
  	<div class="modal-dialog modal-md">
    	<div class="modal-content">
			<div class="modal-header alert-info">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="back()">&times;</button>
				<h4 class="modal-title">Please click the following buttons</h4>
			</div>
      		<div class="modal-body">
        		<div class = "print"></div>
      		</div>
      		<div class="modal-footer">
            	<button type="button" class="dis_ btn btn-default " data-dismiss="modal" onclick="back()">Close</button>
         	</div>
    	</div><!-- /.modal-content -->
  	</div><!-- /.modal-dialog -->
</div>

<div id = "viewContract" class="modal fade bs-example-modal-md">
  	<div class="modal-dialog modal-md">
    	<div class="modal-content">
      		<div class="modal-header alert-info">
        		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        		<h4 class="modal-title">Print Contract</h4>
      		</div>
      		<div class="modal-body">
        		<div class = "printContract"></div>
      		</div>
      		<div class="modal-footer">
         		<input type='button' name='submit' class='btn btn-primary btn-md' value='Proceed' onclick="renewcontract()">
            	<button type="button" class="dis_ btn btn-default " data-dismiss="modal">Close</button>
      		</div>
    	</div><!-- /.modal-content -->
  	</div><!-- /.modal-dialog -->
</div>

<script>
function cancel(){
	if(!confirm("Are you sure to cancel the renewal of contract?")) return false;
	var loc = document.location;
	window.location = loc;
}

function company_select(id){
	$.ajax({
		type : "POST",
		url : "ajax.php?load=bunit",
		data : { id : id },
		success : function(data){
			$("[name='bunit_select']").html(data);
			$("[name='dept_select']").html('');
			$("[name='section_select']").html('');
			$("[name='ssection_select']").html('');
			$("[name='unit_select']").html('');
		}
	});
}

function bunit_select(id){
	$.ajax({
		type : "POST",
		url : "ajax.php?load=dept",
		data : { id : id },
		success : function(data){
			$("[name='dept_select']").html(data);
			$("[name='section_select']").html('');
			$("[name='ssection_select']").html('');
			$("[name='unit_select']").html('');
		}
	});
}

function dept_select(id){
	$.ajax({
		type : "POST",
		url : "ajax.php?load=section",
		data : { id : id },
		success : function(data){
			$("[name='section_select']").html(data);
			$("[name='ssection_select']").html('');
			$("[name='unit_select']").html('');
		}
	});
}

function section_select(id){
	$.ajax({
		type : "POST",
		url : "ajax.php?load=ssection",
		data : { id : id },
		success : function(data){
			$("[name='ssection_select']").html(data);
			$("[name='unit_select']").html('');
		}
	});
}

function ssection_select(id){
	$.ajax({
		type : "POST",
		url : "ajax.php?load=unit",
		data : { id : id },
		success : function(data){
			$("[name='unit_select']").html(data);
		}
	});
}

function edit_renew_control()
{
	var cc 	= $("[name ='temp_company']").val();
	var bc 	= $("[name = 'temp_businessunit']").val();
	var dc 	= $("[name = 'temp_department']").val();
	var sc 	= $("[name = 'temp_section']").val();
	var ssc = $("[name = 'temp_subsection']").val();
	var uc 	= $("[name = 'temp_unit']").val();
	var lodging = $("[name = 'temp_lodging']").val();
	var pos = $("[name = 'temp_position']").val();
	var type = $("[name = 'temp_type']").val();
	var pos_level = $("[name = 'temp_pos_level']").val();


	if(!confirm("Are you sure do you want to edit this records?")) return false;
	$("#company_label").hide();
	$("#bunit_label").hide();
	$("#dept_label").hide();
	$("#section_label").hide();
	$("#ssection_label").hide();
	$("#unit_label").hide();
	$("#position_label").hide();
	$("#positionlevel_label").hide();
	$("#lodging_label").hide();
	$("#type_label").hide();
	$("#company_select").show().prop("required",true);
	$("#bunit_select").show();//.prop("required",true);
	$("#dept_select").show();
	$("#section_select").show();
	$("#ssection_select").show();
	$("#position_select").show();
	$("#position_select").show().prop("required",true);
	$("#poslevel_select").show();
	$("#lodging_select").show().prop("required",true);
	$("#unit_select").show();
	$("#type_select").show().prop("required",true);
	$("#cancel_new").show();
	$("#edit_new").hide();
	//clear all text value

	$("[name='company']").val('');
	$("[name='businessunit']").val('');
	$("[name='department']").val('');
	$("[name='section']").val('');
	$("[name='subsection']").val('');
	$("[name='unit']").val('');
	$("[name='position']").val('');
	$("[name='lodging']").val('');
	$("[name='type']").val('');
	$("[name='pos_level']").val('');

	$("[name = 'notEdited']").val(0);
	$("[name = 'edited']").val(1);
	
	// for company 

	$.ajax({
		type : "POST",
		url : "ajax.php?request=loadSelectedCompany",
		data : { cc : cc },
		success : function(data){
			$("[name='company_select']").html(data);
		}
	});
	// for business unit
	$.ajax({
		type : "POST",
		url : "ajax.php?request=loadSelectedBunit",
		data : { cc : cc, bc : bc },
		success : function(data){
			$("[name='bunit_select']").html(data);
		}
	});
	// for department
	$.ajax({
		type : "POST",
		url : "ajax.php?request=loadSelectedDept",
		data : { cc : cc, bc : bc, dc : dc },
		success : function(data){
			$("[name='dept_select']").html(data);
		}
	});
	// for section
	$.ajax({
		type : "POST",
		url : "ajax.php?request=loadSelectedSec",
		data : { cc : cc, bc : bc, dc : dc, sc : sc },
		success : function(data){
			$("[name='section_select']").html(data);
		}
	});
	// for sub section
	$.ajax({
		type : "POST",
		url : "ajax.php?request=loadSelectedSsec",
		data : { cc : cc, bc : bc, dc : dc, sc : sc, ssc : ssc},
		success : function(data){
			$("[name='ssection_select']").html(data);
		}
	});
	// for unit
	$.ajax({
		type : "POST",
		url : "ajax.php?request=loadSelectedUnit",
		data : { cc : cc, bc : bc, dc : dc, sc : sc, ssc : ssc, uc : uc },
		success : function(data){
			$("[name='unit_select']").html(data);
		}
	});
	// for lodging
	$.ajax({
		type : "POST",
		url : "ajax.php?request=loadSelectedLodging",
		data : { lodging : lodging },
		success : function(data){
			$("[name='lodging_select']").html(data);
		}
	});
	// for type
	$.ajax({
		type : "POST",
		url : "ajax.php?request=loadSelectedType",
		data : { type : type },
		success : function(data){
			$("[name='type_select']").html(data);
		}
	});
	// for position
	$.ajax({
		type : "POST",
		url : "ajax.php?request=loadSelectedPos",
		data : { pos : pos },
		success : function(data){
			$("#position_select").html(data);
		}
	});
	// for position level
	$.ajax({
		type : "POST",
		url : "ajax.php?request=loadSelectedPosLevel",
		data : { pos_level : pos_level },
		success : function(data){
			$("#poslevel_select").html(data);
		}
	});
}

function cancel_renew_control(){

	var cc 	= $("[name ='temp_company']").val();
	var bc 	= $("[name = 'temp_businessunit']").val();
	var dc 	= $("[name = 'temp_department']").val();
	var sc 	= $("[name = 'temp_section']").val();
	var ssc = $("[name = 'temp_subsection']").val();
	var uc 	= $("[name = 'temp_unit']").val();
	var lodging = $("[name = 'temp_lodging']").val();
	var pos = $("[name = 'temp_position']").val();
	var type = $("[name = 'temp_type']").val();
	var pos_level = $("[name = 'temp_pos_level']").val();

	if(!confirm("Are you sure do you want to cancel the edit transaction?")) return false;
	$("#company_label").show();
	$("#bunit_label").show();
	$("#dept_label").show();
	$("#section_label").show();
	$("#ssection_label").show();
	$("#unit_label").show();
	$("#position_label").show();
	$("#positionlevel_label").show();
	$("#lodging_label").show();
	$("#type_label").show();
	$("#company_select").hide().removeAttr("required");
	$("#bunit_select").hide();
	$("#dept_select").hide();
	$("#section_select").hide();
	$("#ssection_select").hide();
	$("#unit_select").hide();
	$("#position_select").hide();
	$("#poslevel_select").hide();
	$("#position_select").hide().removeAttr("required");
	$("#lodging_select").hide().removeAttr("required");
	$("#type_select").hide().removeAttr("required");
	$("#cancel_new").hide();
	$("#edit_new").show();
	//return the default value
	$("[name='company']").val(cc);
	$("[name='businessunit']").val(bc);
	$("[name='department']").val(dc);
	$("[name='section']").val(sc);
	$("[name='subsection']").val(ssc);
	$("[name='unit']").val(uc);
	$("[name='position']").val(pos);
	$("[name='lodging']").val(lodging);
	$("[name='type']").val(type);
	$("[name='pos_level']").val(pos_level);

	$("[name = 'notEdited']").val(1);
	$("[name = 'edited']").val(0);
}

function getdate2(){

	var sd = $("#startdate").val();
	
	if(sd ==''){

		alert("Please set startdate first!");
		$("#startdate").css('border-color','red');
		$("#startdate").focus();
	}
	
}

function ondate(id){

	$("#"+id).css('border-color','#ccc');	
}

function month(){

	$("#months").css('border-color','#ccc');

	/* var mo = document.getElementById('months').value;
	var d1 = document.getElementById('startdate').value;
	var d = new Date(d1);
	d.setMonth( d.getMonth() + parseInt(mo) );
									  
	var yyyy = d.getFullYear().toString();                                    
	var mm = (d.getMonth()+1).toString(); // getMonth() is zero-based         
	var dd  = d.getDate().toString(); 		       						
	// d2 = yyyy + '-' + (mm[1]?mm:"0"+mm[0]) + '-' + (dd[1]?dd:"0"+dd[0]);   
	d2 = (mm[1]?mm:"0"+mm[0]) + '/' + (dd[1]?dd:"0"+dd[0]) + '/' + yyyy;   
	if(d1 != ""){ document.getElementById('eocdate').value = d2; }	*/
}

function onkeyupWitness(id){

	var witness = $("#"+id).val();

	if(witness.trim() != ""){

		$("#"+id).css('border-color','#ccc');
	}
}

function submit_(){

	var notEdited 	= $("[name = 'notEdited']").val();
	var edited 		= $("[name = 'edited']").val();
	var startdate 	= $("[name = 'startdate']").val();
	var eocdate 	= $("[name = 'eocdate']").val();
	var months 	 	= $("[name = 'months']").val();
	var witness1 	= $("[name = 'witness1']").val();
	var witness2 	= $("[name = 'witness2']").val();

	var empId 	 	= $("[name = 'empId']").val();
	var recordNo 	= $("[name = 'recordNo']").val();
	var current_status 	= $("[name = 'current_status']").val();

	if(startdate == ""){

		alert("Please fill up STARTDATE first!");
		$("#startdate").css('border-color','red');
		$("#startdate").focus();

	} else if(eocdate == ""){

		alert("Please fill up EOCDATE first!");
		$("#eocdate").css('border-color','red');
		$("#eocdate").focus();

	} else if(months == ""){

		alert("Please fill up NO. OF MONTH(S) TO WORK first!");
		$("#months").css('border-color','red');
		$("#months").focus();

	} else if(witness1 == ""){

		alert("Please fill up WITNESS 1 first!");
		$("#witness1").css('border-color','red');
		$("#witness1").focus();
	} else if(witness2 == ""){

		alert("Please fill up WITNESS 2 first!");
		$("#witness2").css('border-color','red');
		$("#witness2").focus();
	} else {

		$("#submit_renewal").prop("disabled", true);

		if(notEdited == 1){

			// get value of not edited form
			var company 	= $("[name = 'company']").val();
			var businessunit= $("[name = 'businessunit']").val();
			var department 	= $("[name = 'department']").val();
			var section 	= $("[name = 'section']").val();
			var subsection 	= $("[name = 'subsection']").val();
			var unit 		= $("[name = 'unit']").val();
			var lodging 	= $("[name = 'lodging']").val();
			var position 	= $("[name = 'position']").val();
			var type 		= $("[name = 'type']").val();
			var pos_level 	= $("[name = 'pos_level']").val();

		} else {

			// get value of edited form
			var company 	= $("[name = 'company_select']").val();
			var businessunit= $("[name = 'bunit_select']").val();
			var department 	= $("[name = 'dept_select']").val();
			var section 	= $("[name = 'section_select']").val();
			var subsection 	= $("[name = 'ssection_select']").val();
			var unit 		= $("[name = 'unit_select']").val();
			var lodging 	= $("[name = 'lodging_select']").val();
			var position 	= $("[name = 'position_select']").val();
			var type 		= $("[name = 'type_select']").val();
			var pos_level 	= $("[name = 'poslevel_select']").val();
		}

		var comment  = $("[name = 'comment']").val();
		var remarks  = $("[name = 'remarks']").val();

		$.ajax({

		    type: "POST",
			url : "functionquery.php?request=process_renewal",
			data: { empId:empId, recordNo:recordNo, current_status:current_status, notEdited:notEdited, edited:edited, company:company, businessunit:businessunit, department:department, section:section, subsection:subsection, unit:unit, lodging:lodging, position:position, type:type, pos_level:pos_level, months:months, startdate:startdate, eocdate:eocdate, witness1:witness1, witness2:witness2, comment:comment, remarks:remarks },
			success: function(data){	 	
			  
			  	data = data.trim();
			  	if(data == "Ok"){
			  		alert("Successsfully Save");
			  		printContractPermit(empId);
			  	} else {
			  		alert(data);
			  	}
			}
		});
	}
}

function printContractPermit(empId){

	$("#viewManual").modal({
        backdrop: 'static',
        keyboard: false
      });

    $("#viewManual").modal("show");

    $.ajax({

	    type: "POST",
		url : "functionquery.php?request=printContractPermit",
		data : { empId:empId },
		success: function(data){	 	
		  
		  	$(".print").html(data);
		}
	});
			
}

function contract(recordNo,empType,empId){


	$("#viewContract").modal({
        backdrop: 'static',
        keyboard: false
      });

    $("#viewContract").modal("show");

    $.ajax({

	    type: "POST",
		url : "functionquery.php?request=printContract",
		data : { recordNo:recordNo, empType:empType, empId:empId },
		success: function(data){	 	
		  
		  	$(".printContract").html(data);
		}
	});
}

function permit(rec){

	var r = confirm("Generate Permit-To-Work now?")
	if(r == true){
		
		window.open("../report/permittowork_NESCO.php?rec="+rec,"_blank");

	}
}

function editkra(posno,empid){
	window.open("create_kra.php?posno="+posno+"&sc=0&empid="+empid);
}

function sssctc(val)
{
	if(val == 'ctc'){
		$("#cleartf").prop("disabled",false);			
		$("#ssstf").prop("disabled",true);			
		$('#ssstf').hide();
		$('#cleartf').show();
		$('#issuedon').show();	
		$('#is').show();		
	}
	else if(val == 'sss'){
		$("#ssstf").prop("disabled",false);			
		$("#cleartf").prop("disabled",true);			
		$('#ssstf').show();
		$('#cleartf').hide();
		$('#issuedon').hide();	
		$('#is').hide();
	}	
}

function back(){

	var r = confirm("Are you sure you want to EXIT?");

	if(r == true){
		window.location = "?p=dashboard&&db=contracts";
	}
}

function renewcontract()
{

	var r1		= $("#r1").val();
	var r2		= $("#r2").val();
	var cleartf = $("[name = 'cleartf']").val();
	var ssstf	= $("[name = 'ssstf']").val();
	var issuedon= $("[name = 'issuedon']").val();
	var issuedat= $("[name = 'issuedat']").val();
	var cdate 	= $("[name = 'contractdate']").val();
	var recordNo= $("[name = 'newRecordNo']").val();
	var empType = $("[name = 'empType']").val();
	var clear 	= "";
	
	if($("#r1").is(':checked')){
		
		clear = r1;	
	} else if($("#r2").is(':checked')){
		
		clear = r2;
	} else {

		clear = "";
	}
	//checks empty	
	if(clear == ""){

		alert('Please choose either to use Cedula (CTC No.) or SSS No.');
	}

	if($("#r1").is(':checked')){
		if(cleartf == ""){

			alert("Please fill up CEDULA (CTC NO.) first!");
			$("#cleartf").css('border-color','red');
			$("#cleartf").focus();

		} else if(issuedon == ""){

			alert("Please fill up ISSUED ON first!");
			$("#issuedon").css('border-color','red');
			$("#issuedon").focus();
		
		} else if(issuedat == ""){

			alert("Please fill up ISSUED AT first!");
			$("#issuedat").css('border-color','red');
			$("#issuedat").focus();

		} else if(cdate == ""){

			alert("Please fill up ISSUED AT first!");
			$("#contractdate").css('border-color','red');
			$("#contractdate").focus();
		}
	}

	if($("#r2").is(':checked')){

		if(ssstf == ""){

			alert("Please fill up SSS NO. first!");
			$("#ssstf").css('border-color','red');
			$("#ssstf").focus();

		} else if(issuedat == ""){

			alert("Please fill up ISSUED AT first!");
			$("#issuedat").css('border-color','red');
			$("#issuedat").focus();

		} else if(cdate == ""){

			alert("Please fill up DATE OF SIGNING OF CONTRACT/EMPLOYEE first!");
			$("#contractdate").css('border-color','red');
			$("#contractdate").focus();
		}
	}
	
	if(clear != "" && clear != "" && cdate != ""){	

		window.open("../report/contract_NESCCO.php?clear="+clear+"&ssstf="+ssstf+"&cleartf="+cleartf+"&issuedon="+issuedon+"&issuedat="+issuedat+"&rec="+recordNo+"&cdate="+cdate,"_blank");			
	}
}

function editKra(posNo, empId){
	
	alert("under constraction");
	// window.open("");
}

function getLevel(position)
{
	$.ajax({
		type: "POST",
		url : "ajax.php?request=loadLevel",
		data : { position:position },
		success: function(data){	
			$("#poslevel_select").val(data);
		}
	});
}
</script>