<?php 
	//ARRAYS
	$civilstatus 	= array('','Single','Married','Separated','Divorced','Widowed','Annulled');
	$bloodtype 		= array('','A','B','O','A+','A-','B+','B-','O+','O-','AB','AB+','AB-');
	$emptype 		= array('NESCO','NESCO Contractual','NESCO-PTA','NESCO-PTP','NESCO Probationary','NESCO Regular','NESCO Regular Partimer');
	
	$currentstatus	= array('','Active','End of Contract','Resigned','V-Resigned','Ad-Resigned','Retrenched','Blacklisted');
	$weight			= $nq->selectTable('weight');
	$height 		= $nq->selectTable('height');
	$religion 		= $nq->selectTable('religion');
	$course 		= $nq->selectTable('course');
	$attainment		= $nq->selectTable('attainment');
	$school 		= $nq->selectTable('school');
	$position 		= $nq->selectTable('positions');
?>	

<div class="panel panel-default" style='margin-left:auto;margin-right:auto;width:99%;'>
	<div class="panel-heading"> <b> QUERY BY EXAMPLE (QBE) </b> </div>
		<div class="panel-body">
			<form action="pages/reports/report_qbe_xls.php" method="post" >
			<?php include('companydetails.php');?>
			<div class='row'>
				<div class="col-md-4">
					<H4 style='color:green'>FIELDNAMES</H4>
					<div > 
						<div><!--class="panel-body" -->
							<p><i>Note: Click fieldnames atleast one or as many as you can. (What you check will be displayed in the report.)</i></p><hr>	
							<?php
							$values = array('name','home_address','gender','birthdate','religion','civilstatus','school','attainment','course','contactno','mother','father','height','weight','bloodtype','startdate','eocdate','current_status','emp_type','lodging');
							$fields = array('Full Name','Home Address','Gender','Birthday','Religion','Civil Status','School','Attainment','Course','Contact Number','Mother','Father','Height','Weight','Bloodtype','Startdate','Eocdate','Current Status','Employee Type','Lodging');
							for($i=0;$i<count($fields);$i++){
								$field = str_replace(" ","_",$fields[$i]);
								$v = $values[$i]."%".$field;
								echo "<input type='checkbox' name='check[]' value=".$values[$i]." onclick=addField('".$field."') id='".$fields[$i]."' class='chk'>&nbsp; 
									<span id='".$field."f'>".$fields[$i]."</span><br>"; 	
							}
							//$noyears = str_replace(" ","_",'No.of Years Employed');
							?>
							<input type='checkbox' name='datehiredcb' value="datehired" onclick=addField('DateHired') id='DateHired' class='chk'>&nbsp; <span id='DateHiredf'>Date Hired</span><br>
							<input type='checkbox' name='agecb' value="age" onclick=addField('Age') id='Age' class='chk'>&nbsp; <span id='Agef'>Age</span><br>
							<input type='checkbox' name='cedulacb' value="age" onclick=addField('Cedula') id='Cedula' class='chk'>&nbsp; <span id='Cedulaf'>Cedula</span><br>		
							<hr><p><i><b>Take Note: </b>Employee No, Firstname, Middlename, Lastname, Position, Company, Business Unit, Department and Section are default column names.</i></p> 									
						</div> 
						<BR><H4 style='color:green'>OTHER DETAILS</H4>
						<hr>
						<p>Current Status <i>(required)</i></p></p>	
						<p><select class="form-control" id='current_statustf' name="current_status" required ><?php for($i=0;$i<count($currentstatus);$i++){
						echo "<option value='$currentstatus[$i]'>".$currentstatus[$i]."</option>";  }?></select></p>            

						<p>Report Title: <i>(required)</i></p>
						<p><input type="text" name="report_title" id="report_title" class="form-control" required></p>

						<p>Filename: <i>(required)</i></p>
						<p><input type="text" name="filename" id="filename" class="form-control" placeholder="ex. All_single_employees" required></p>			
					</div>
				</div>
				
				<div class="col-md-8">
					<H4 style='color:green'>CONDITIONS</H4>
					<div class="panel-body" id="queryid">
						<i>Note: Choose only five (5) conditions. Once reached to 5 selection, unchecked the checkbox & select another one.</i><hr>					
						<div >							
						
						<p><input type='checkbox' name='check1[]' value="name" onclick=checkField('name') id='namei'> &nbsp <span id='nameff'>Name</span></p>	
						<p><input type='text' class='form-control' id='nametf' required size='55' name='nname' disabled /></p>

						<p><input type='checkbox' name='check1[]' value="home_address" onclick=checkField('home_address') id='home_addressi'> &nbsp <span id='home_addressff'>Home Address</span></p>	
						<p><input type='text' class='form-control' id='home_addresstf' required size='55' name='nhome_address' disabled /></p>

						<p><input type='checkbox' name='check1[]' value="gender" onclick=checkField('gender') id='genderi'> &nbsp <span id='genderff'>Gender</span></p>	
						<select class="form-control" id='gendertf' name="ngender" required disabled><option></option><option>Male</option><option>Female</option></select>

						<p><input type='checkbox' name='check1[]' value="religion" onclick=checkField('religion') id='religioni' > &nbsp <span id='religionff'>Religion</span></p>	
						<input list="religions" class="form-control" size="50" name="nreligion" id='religiontf' autocomplete="off" disabled required />
						<datalist id="religions">
								<?php while($rrel = mysql_fetch_array($religion)){
								echo "<option value='".$rrel['religion']."'>".$rrel['religion']."</option>";  }?></option>						       
						</datalist>

						<p><input type='checkbox' name='check1[]' value="civilstatus" onclick=checkField('civilstatus') id='civilstatusi'> &nbsp <span id='civilstatusff'>Civil Status</span></p>	
						<select class="form-control" id='civilstatustf' name="ncivilstatus" disabled required><?php for($i=0;$i<count($civilstatus);$i++){
						echo "<option value='$civilstatus[$i]'>".$civilstatus[$i]."</option>";  }?></select>

						<p> <input type='checkbox' name='check1[]' value="school" onclick=checkField('school') id='schooli'> &nbsp <span id='schoolff'>School</span></p>	
							<input list="schools" class="form-control" size="50" name="nschool" id="schooltf" autocomplete="off" disabled required/>
							<datalist id="schools">
								<?php while($rsch = mysql_fetch_array($school)){
								echo "<option value='".$rsch['school_name']."'>".$rsch['school_name']."</option>";  }?></option>						       
							</datalist>

						<p><input type='checkbox' name='check1[]' value="attainment" onclick=checkField('attainment') id='attainmenti'> &nbsp <span id='attainmentff'>Attainment</span></p>	 
							<input list="attainments" class="form-control" size="50" name="nattainment" id="attainmenttf" autocomplete="off" disabled required/>
							<datalist id="attainments">
								<?php while($ratt = mysql_fetch_array($attainment)){
								echo "<option value='".$ratt['attainment']."'>".$ratt['attainment']."</option>";  }?></option>						       
							</datalist>

						<p><input type='checkbox' name='check1[]' value="course" onclick=checkField('course') id='coursei'> &nbsp <span id='courseff'>Course</span></p>	
							<input list="courses" class="form-control" size="50" name="ncourse" id="coursetf" autocomplete="off" disabled required/>
							<datalist id="courses">						    
								<?php while($rco = mysql_fetch_array($course)){
								echo "<option value='".$rco['course_name']."'>".$rco['course_name']."</option>";  }?></option>						       
							</datalist>

						<p><input type='checkbox' name='check1[]' value="height" onclick=checkField('height') id='heighti'> &nbsp <span id='heightff'>Height</span></p>
							<input list="heights" class="form-control" size="50" name="nheight" id="heighttf" autocomplete="off" disabled required/>
							<datalist id="heights">
								<?php while($rhe = mysql_fetch_array($height)){
								echo "<option value='".$rhe['feet']."'>".$rhe['feet']."/".$rhe['cm']."</option>";  }?></option>						       
							</datalist>

						<p><input type='checkbox' name='check1[]' value="weight" onclick=checkField('weight') id='weighti'> &nbsp <span id='weightff'>Weight</span></p>	
							<input list="weights" class="form-control" size="50" name="nweight" id="weighttf" autocomplete="off" disabled required/>
							<datalist id="weights">
								<?php while($rwe = mysql_fetch_array($weight)){
								echo "<option value='".$rwe['kilogram']."'>".$rwe['kilogram']."/".$rwe['pounds']."</option>";  }?></option>						       
							</datalist>

						<p><input type='checkbox' name='check1[]' value="bloodtype" onclick=checkField('bloodtype') id='bloodtypei'> &nbsp <span id='bloodtypeff'>Bloodtype</span></p>	
							<select class="form-control" id='bloodtypetf' name="nbloodtype" disabled required><?php for($i=0;$i<count($bloodtype);$i++){
							echo "<option value='$bloodtype[$i]'>".$bloodtype[$i]."</option>";  }?></select>

						<p><input type='checkbox' name='check1[]' value="position" onclick=checkField('position') id='positioni'> &nbsp <span id='positionff'>Position</span></p>	
							<input list="positions" class="form-control" size="50" name="nposition" id="positiontf" autocomplete="off" disabled required/>
							<datalist id="positions">
								<?php while($rpo = mysql_fetch_array($position)){
								echo "<option value='".$rpo['position']."'>".$rpo['position']."</option>";  }?></option>						       
							</datalist>					

						<p><input type='checkbox' name='check1[]' value="emp_type" onclick=checkField('emp_type') id='emp_typei'> &nbsp <span id='emp_typeff'>Employee Type</span></p>	
							<select class="form-control" id='emp_typetf' name="emp_type" disabled="" required>
							<option> </option>
							<?php				
							//$quer = mysql_query("SELECT emp_type FROM `employee_type`");
							//while($rq = mysql_fetch_array($quer)){
							for($i=0;$i<count($emptype);$i++){
								echo "<option value='$emptype[$i]'>".$emptype[$i]."</option>"; 
							}?>
							</select>	
						
						<p><input type='checkbox' name='check1[]' value="lodging" onclick=checkField('lodging') id='lodgingi'> &nbsp <span id='lodgingff'>Lodging</span></p>	
							<select class="form-control" id='lodgingtf' name="lodging" disabled="" required>
							<option></option><option value='Stay-in'>Stay In</option><option value='Stay-out'>Stay Out</option></select>

						<p><input type='checkbox' name='check1[]' value="contactno" onclick=checkField('contactno') id='contactnoi'> &nbsp <span id='contactnoff'>Contact Number</span></p>
						<p><input type='text' class='form-control' id='contactnotf' required size='55' name='ncontactno' disabled /></p>
					</div>
				</div>	
				&nbsp; &nbsp; &nbsp;<input type='submit' name='submit' class=' btn btn-primary' value='Submit'> 
				<input type='button' class=' btn btn-default' onclick='cancel()' value='Cancel'> 
			</div>	
			</form>
		</div>
	</div>
</div>

<script>
	function cancel(){
		window.location = '?p=dashboard&&db=reports';
	}
	function addField(fieldId)
	{	
		var ctr = 0;
		var newi = fieldId+"f";
		var f = fieldId.replace(/_/g," ");	
		var fid = document.getElementById(f);
		if(fid.checked == false)
		{
			document.getElementById(newi).style.color = "black";
			document.getElementById(newi).style.fontStyle="normal";
		}
		else
		{		
			document.getElementById(newi).style.color = "red";	
			document.getElementById(newi).style.fontStyle="italic";
		}
	}
	function checkField(id)
	{		
		var count 	= 0;		// counter
		var tf_id	= id+"tf";	// id of the textfield
		var newid   = id+"i";   // id of the checkbox
		var c = document.getElementsByName('check1[]'); // name sa checkbox	
		var ii = document.getElementById(newid);			
		var newif = id+"ff";
		for(var i=0;i<c.length;i++){
			if(c[i].checked == true){
				count++;
			}
		}

		//alert(tf_id + " "+ newid +" "+newif+" "+ii.checked)	
		if(count > 5){
			document.getElementById(newid).checked = false;
		}else{
			if(ii.checked == false){
				//alert(ii.checked + "is false")		
				document.getElementById(tf_id).disabled = true;	
				document.getElementById(tf_id).value = "";				
				document.getElementById(newif).style.color = "black";
				document.getElementById(newif).style.fontStyle="normal";
			}else if(ii.checked == true){
				//alert(ii.checked + "is true")	
				document.getElementById(tf_id).disabled = false;
				document.getElementById(newif).style.color = "red";	
				document.getElementById(newif).style.fontStyle="italic";
			}
		}
	}
</script>