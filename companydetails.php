<?php
$code	= @$_GET['code'];
$ec	 	= explode("/",$code);
$cc	   	= @$ec[0];
$bc		= @$ec[1];
$dc		= @$ec[2];
$sc		= @$ec[3];
$ssc	= @$ec[4];
$uc		= @$ec[5];	
		?>
<div class='row'>
	<div class='col-md-4'>
		<div class="form-group">
			<label>Company</label> <i>(required)</i><br>
			<select class="form-control" name="comp_code" onchange='getCompany(this.value)'>
				<option value="">Select</option>
				<?php 
					$sub_query = mysql_query("SELECT * FROM locate_company where status ='active' ORDER BY company ASC") or die(mysql_error());
					while($res=mysql_fetch_array($sub_query)){ ?>
					<option value="<?php echo $res['company_code'];?>" <?php if(@$cc==$res['company_code']){ echo "selected";}?>><?php echo $res['acroname'];?></option>
				<?php } ?>
			<select>
		 </div>
		 <div class="form-group">
			<label>Business Unit</label>
			<select class="form-control" name="bunit_code" onchange='getBusinessUnit(this.value)'>
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
		 </div>	
	</div>
	
	<div class='col-md-4'>
		<div class="form-group">
		<label>Department</label>
			<select class="form-control" name="dept_code" onchange='getDepartment(this.value)'>
				
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
		</div>
		<div class="form-group">
			<label>Section</label>
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
		</div>
	</div>
	<div class='col-md-4'>
		<div class="form-group">
			<label>Sub-section</label>
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
		</div>		 
		<div class="form-group">
			<label>Unit</label>
			<select class="form-control" name="unit_code">
				<option value="">Select</option>
			<select>
		</div>
	</div>
</div>	
	
<script>
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
</script>