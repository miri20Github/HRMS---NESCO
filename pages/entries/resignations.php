<?php
include("header.php");

$empid = @$_GET['e'];
$name = @$_GET['name'];

$emp  = @$_GET['emp'];
$name = $nq->getAppName($emp);

if(isset($_POST['submit']))
{	
	$dateupdate = date('Y-m-d');
	$addedby 	= @$_SESSION['emp_id'];
	$status 	= $_POST['status'];
	$empid 		= $_POST['namesearch'];
	$empid	 	= explode("*",$empid);
	$empid	   	= $empid[0];
	
	$dateresign = $nq->changeDateFormat('Y-m-d',$_POST['dateresign']);

	//***********************************************											
	if(@$_FILES['letter']['name'] != "")
	{
		$letter 	= "../document/resignation/" . $_FILES["letter"]["name"];				
		$array 		= explode(".",$_FILES["letter"]["name"]);
		$fletter 	= "../document/resignation/".$empid."=".date('Y-m-d')."="."Resignation-Letter"."=".date('H-i-s-A').".".$array[1];	
		move_uploaded_file($_FILES["letter"]["tmp_name"],@$fletter);		
	}
	else
	{
		$letter = "";
		$fletter= "";
	}
	//***********************************************
	if(@$_FILES['clearance']['name'] != "")
	{
		$clearance 	= "../document/clearance/" . $_FILES["clearance"]["name"];				
		$array 		= explode(".",$_FILES["clearance"]["name"]);
		$fclearance = "../document/clearance/".$empid."=".date('Y-m-d')."="."Clearance"."=".date('H-i-s-A').".".$array[1];	
		move_uploaded_file($_FILES["clearance"]["tmp_name"],@$fclearance);		
	}
	else{
		$clearance = "";
		$fclearance = "";
	}	

	//***********************************************	
													//termination_no,emp_id,date,remarks,resignationletter,added_by,date_updated
	//$ins = mysql_query("INSERT INTO termination VALUES ('','$empid','$dateresign','$_POST[remarks]','$fletter','$addedby','$dateupdate')");
	
	$ins = mysql_query("INSERT INTO termination
	(termination_no, emp_id, date, remarks, resignation_letter, added_by, date_updated) 
	VALUES
	('', '$empid', '$dateresign', '$_POST[remarks]', '$fletter', '$addedby', '$dateupdate') ") or die(mysql_error());
	
	if($ins)
	{			
		$update = mysql_query("UPDATE employee3 set current_status = '$status', clearance = '$fclearance' where emp_id = '$empid'  ");
		if($update)
		{ ?>
			<script> alert('Submitting Successful!'); window.location = "?p=resignation-add&&db=entries"; </script>
			<?php
		}
	}	
}
?>
<div style="width:80%; margin-left:auto; margin-right:auto;">   	
	<div class='panel panel-default'>
	<div class='panel-heading'>
		<div style='font-size:24px;text-indent:10px;'> Add Termination </div>
	</div>      
    <div class="panel-body">			
		<input type="hidden" id="creator"  value="<?php echo @$_SESSION['emp_id'];?>" /> 
		<input type="hidden" name='empid' id='empid'>  
		<form method='post' action='?p=resignation-add&&db=entries' enctype="multipart/form-data">
		<table width='100%'>
			<tr>
				<td><label> EMPLOYEE </label> <span class="red">(required)</span></td>
				<td><label> DATE </label> <span class="red">(required)</span> </td>
			</tr>
			<tr><td>
					<input list="se" id="namesearch" type="text" class="form-control" name="namesearch" autocomplete="off" placeholder="  Search Employee" required
						value="<?php if(@$_GET['emp'] !=""){ echo $emp."*".$name;} else{ echo @$key;} ?>"/>
					<datalist id="se">
						<?php
						 $res = mysql_query("SELECT emp_id, name from employee3 where current_status = 'Active' and
							(emp_type='NESCO' or emp_type='NESCO Contractual' or emp_type='NESCO-PTA' or emp_type='NESCO-PTP' or emp_type = 'NESCO Regular' or emp_type='NESCO Regular Partimer' or emp_type='NESCO Probationary')
							and (current_status = 'Active' or current_status = 'End of Contract')");
																				
						while($rs = mysql_fetch_array($res))
						{						 
							$ax = $rs['emp_id']."*".$rs['name'];?>
							<option value="<?php echo $ax;?>"><?php echo $ax;?></option><?php 					
						}
						?>
					</datalist>
				</td><!-- onkeyup="keypress()"-->
				<td><input type="text" name="dateresign" class="form-control" id="dateresign"  autocomplete="off" size="50" required="required" placeholder='mm/dd/yyyy' /></td>
			</tr>
			<tr><td><label> REMARKS </label>  <span class="red">(required)</span></td></tr>
			<tr><td colspan='2'><textarea name="remarks" id="remarks" cols="47" class="form-control" rows="2" required="required"></textarea></td></tr>
			<tr><td><label> CHOOSE STATUS </label> <span class="red">(required)</span></td></tr>
			<tr><td colspan='2'>
				<select name='status' id='status' class='form-control' onchange='showresignation(this.value)' required>
					<option></option>
					<option value='Resigned'> Resigned </option>
					<option value='End of Contract'> End of Contract </option>
				</select></td>
			</tr>
			<tr><td> <label> UPLOAD CLEARANCE </LABEL> </td> <td> <label> UPLOAD RESIGNATION LETTER </label> </td></tr>
			<tr><td><input type="file" accept="image/*" name="clearance" class="btn btn-default" onchange="check(this.id,'imgclearance')" id="clearance" size="50"/></td>
					<td><input type="file" name="letter" class="btn btn-default" onchange="check(this.id, 'imgresignation')" id="letter" size="50"  /></td></tr>
			
			<tr><td colspan='2'><i style='color:green'>Note: Acceptable file format are [ jpg, png, pdf ] and file size should not be greater than 2MB.</i></td></tr>
			<tr><td colspan='2' align='center'><input type="submit" name="submit" id='submit' class="btn btn-primary" value="Submit" />
								<input type="reset" id='reset' onclick="resetform()" class="btn btn-default" value="Reset" /></td></tr>			
		</table>
		</form>
		</div>        
    </div>
</div>
<br>

<script>
function resetform()
{
	$("#res").hide();
	$('#msg').hide();
	window.location = '?p=resignation-add&&db=entries';	
}
function check(img_id, img1){	//check the image format if allowed	
	var img = document.getElementById(img_id).value;		
	var res = '';
	var i = img.length-1;	
	while(img[i] != "."){
		res = img[i] + res;		
		i--;
	}	
	//checks the file format
	if(res != "PNG" && res != "png" && res != "jpg" && res != "JPG" && res !="JPEG" && res !="jpeg" && res != "PDF" && res != "pdf"){ 		
		document.getElementById(img_id).value = '';		
		alert('Invalid File Format. Only this file format [.pdf,.jpg,.png] are acceptable!\nPlease upload another file. Thank You');		
	}
	
	//checks the filesize- should not be greater than 2MB
	//for further checking
	var uploadedFile = document.getElementById(img_id);
    var fileSize = uploadedFile.files[0].size < 1024 * 1024 * 2;    
	if(fileSize == false){
		alert('The size of the file exceeds 2MB!');
		document.getElementById(img_id).value = '';	
	}
}
</script> 
