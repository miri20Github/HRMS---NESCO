 <?php
session_start();
  
  $mtime = microtime();
  $mtime = explode(" ",$mtime);
  $mtime = $mtime[1] + $mtime[0];
  $starttime = $mtime;
  

mysql_set_charset("UTF-8");

	include('connection.php');
	$empid = $_POST['empid']; 
	
	$Sweight = $nq->selectTable('weight');
	$Sheight = $nq->selectTable('height');
	$religion_query = $nq->selectTable('religion');

	$bloodtype_array = array('A','A+','A-','B','B+','B-','O','O+','O-','AB','AB+','AB-');
	$cv_array		 = array('Single','Married','Widowed','Separated','Anulled','Divorced');	

$appdet = mysql_query("SELECT date_brief, date_hired, date_applied, aeregular, exam_results, position_applied, date_examined from application_details where app_id = '$empid' ");
while($rad = @mysql_fetch_array($appdet))
{	
	if($rad['date_applied'] == '' || $rad['date_applied'] == '0000-00-00' ){  $dateapplied ='';	 }else { $dateapplied = $nq->changeDateFormat('m/d/Y',$rad['date_applied']);  }
	if($rad['date_hired'] == '' || $rad['date_hired'] == '0000-00-00' ){	$datehired ='';	 } else {  $datehired 	 = $nq->changeDateFormat('m/d/Y',$rad['date_hired']);      }
	if($rad['date_brief'] == '' || $rad['date_brief'] == '0000-00-00' ){ 	    $datebrief ='';  } else {  $datebrief 	 = $nq->changeDateFormat('m/d/Y',$rad['date_brief']);     }	
	if($rad['date_examined'] == '' || $rad['date_examined'] == '0000-00-00'){ 	 $dateexamined ='';    } else {  $dateexamined 	 = $nq->changeDateFormat('m/d/Y',$rad['date_examined']);    }
	$examresult	 = $rad['exam_results'];
	$posapplied  = $rad['position_applied'];
	$aeregular   = $rad['aeregular'];
}

//*********************************** BASIC INFORMATION ********************************//
if(@$_GET['request'] == "basicinfo")
{	
	$basicinfo = mysql_query(" Select firstname, lastname, middlename,suffix,citizenship, gender,civilstatus,religion,weight,height,bloodtype,birthdate 
	from applicant where app_id = '$empid' ");
	
	$row = mysql_fetch_array($basicinfo);
	//basic information	
	$lastname	= $row['lastname'];//htmlspecialchars($row['lastname'], ENT_QUOTES);
	$firstname	= $row['firstname'];//htmlspecialchars($row['firstname'], ENT_QUOTES);
	$middlename	= $row['middlename'];//htmlspecialchars($row['middlename'], ENT_QUOTES);
	$suffix		= $row['suffix'];//htmlspecialchars($row['suffix'], ENT_QUOTES);
	$citizenship= htmlspecialchars($row['citizenship'], ENT_QUOTES);
	$gender 	= htmlspecialchars($row['gender'], ENT_QUOTES);
	$cv 		= htmlspecialchars($row['civilstatus'], ENT_QUOTES);
	$religion 	= htmlspecialchars($row['religion'], ENT_QUOTES);
	$weight 	= htmlspecialchars($row['weight'], ENT_QUOTES);
	$height 	= htmlspecialchars($row['height'], ENT_QUOTES);
	$bloodtype 	= htmlspecialchars($row['bloodtype'], ENT_QUOTES);
	$date = new DateTime($row['birthdate']); 
	if($row['birthdate'] == '0000-00-00' || $row['birthdate'] == '1900-01-01' || $row['birthdate'] == ''){ $bdate=''; } else { $bdate =  $date->format('m/d/Y');}
	
	//if(@$row['birthdate']!=""){ $bdate =  $date->format('m/d/Y'); }
	
	echo 
	"<div class='modf'>BASIC INFORMATION
	<input type='button' name='edit' id='edit-basicinfo' value='edit' class='btn btn-primary btn-sm' onclick='edit_basicinfo()'>
	<input type='button' class='btn btn-primary btn-sm' id='update-basicinfo' value='update' onclick='update(this.id)' style='display:none'></div>
	<table class='table table-bordered'>
		<tr>			
			<td width='17%' align='right'>Employee No</td>			
			<td colspan='4'><input type='text' name='empid' value='".$empid."' readonly class='form-control' disabled></td>			
		</tr>
		<tr>			
			<td align='right'>Firstname</td>			
			<td><input type='text' name='fname' id='fname' value='".$firstname."' class='form-control' onKeyUp='checkName(this)' disabled></td>			
			<td align='right'>Middlename</td>			
			<td><input type='text' name='mname' id='mname' value='".utf8_encode($middlename)."' class='form-control' onKeyUp='checkName(this)' disabled></span></td>
		</tr>		
		<tr>
			<td align='right'>Lastname</td>			
			<td><input type='text' name='lname' id='lname' value='".utf8_encode($lastname)."' class='form-control' onKeyUp='checkName(this)' disabled></span></td>			
			<td align='right'>Suffix</td>			
			<td><input type='text' name='suffix' id='suffix' value='".$suffix."' class='form-control' onKeyUp='checkName(this)' disabled></span></td>
		</tr>		
		<tr>			
			<td align='right'>Date of Birth</td>			
			<td><input type='text' name='datebirth' id='datebirth' value='".@$bdate."' maxlength='10' class='form-control' placeholder='mm/dd/yyyy' disabled></td>			
			<td align='right'>Citizenship</td>			
			<td><input type='text' name='citizenship' id='citizenship' value='".@$citizenship."' class='form-control' disabled></td>
		</tr>
		<tr>			
			<td align='right'>Gender</td>			
			<td>
				<select name='gender' class='form-control' id='gender' disabled>
			        <option></option>";
			        if($gender == 'Female'){
			            echo "<option value='Female' selected='selected'>Female</option>";
			            echo "<option value='Male' >Male</option>";
			        }else if($gender == 'Male'){
			        	echo "<option value='Male' selected='selected'>Male</option>";
			        	echo "<option value='Female'>Female</option>";
			        }else{
			        	echo "<option value='Male'>Male</option>";
			        	echo "<option value='Female'>Female</option>";
			        }
		     	echo "</select>		    
			</td>			
			<td align='right'>Civil Status</td>			
			<td>
				<select name='civilstatus' class='form-control' id='civilstatus' disabled>
			        <option></option>";
			        for($i=0;$i<count($cv_array);$i++){
			        	if($cv == $cv_array[$i]){
			  				echo "<option value='".$cv_array[$i]."' selected='selected' >".$cv_array[$i]."</option>";			  		
				  		}else{
	  						echo "<option value='".$cv_array[$i]."'>".$cv_array[$i]."</option>";
			  			}	
			        }
			    echo "</select>
			</td>
		</tr>		
		<tr>
			<td align='right'>Religion</td>			
			<td>
				<input list='religions' name='religion' class='form-control' id='religion' autocomplete='off' value='".$religion."' onKeyUp='checkName(this)' disabled/>
			    <datalist id='religions'>";
			        while($rrow = mysql_fetch_array($religion_query)){
			        	if(@$rrw['religion'] == @$religion){			        		
			        		echo "<option value='".$rrow['religion']."''>".$rrow['religion']."</option>";	
			        	}else{
			        		echo "<option value='".$rrow['religion']."''>".$rrow['religion']."</option>";
			        	}
			        }
			    echo "</datalist>
			</td>
			<td align='right'>Bloodtype</td>			
			<td>
				<select class='form-control' name='bloodtype' id='bloodtype' disabled >
					<option value=''></option>";
					for($i=0;$i<count($bloodtype_array);$i++){
						if($bloodtype == $bloodtype_array[$i]){
							echo "<option value='".$bloodtype_array[$i]."' selected='selected'>".$bloodtype_array[$i]."</option>";										
						}else{
							echo "<option value='".$bloodtype_array[$i]."'>".$bloodtype_array[$i]."</option>";	
						}
					}
		        echo "</select>
		    </td>    
		</tr>
		<tr>
			<td align='right'>Weight <i>in kilogram</i></td>			
			<td>
				<input list='weights' name='weight' id='weight' class='form-control' autocomplete='off' value='".$weight."' disabled/>
		        <datalist id='weights'>";
			        while($wrow = mysql_fetch_array($Sweight)){
						$we = $wrow['kilogram']." / ".$wrow['pounds']; 
			        	echo "<option value='".$wrow['kilogram']."'>".$we."</option>";
			        }
		    	echo "</datalist>
			</td>
			<td align='right'>Height <i>in centimeter</i></td>			
			<td>
				<input list='heights' name='height' id='height' class='form-control' autocomplete='off' value='".$height."' disabled/>
		        <datalist id='heights'>";
			       	while($hrow = mysql_fetch_array($Sheight)){
						$he = $hrow['feet']." / ".$hrow['cm']; 
			        	echo "<option value='".$hrow['cm']."'>".$he."</option>";
			        }
		    	echo "</datalist>
			</td>
		</tr>		
	</table>";
}
//*********************************** FAMILY INFORMATION ********************************//
else if(@$_GET['request'] == "family")
{
	$faminfo = mysql_query("SELECT mother,father,guardian,noofSiblings,siblingOrder,spouse, gender from applicant where app_id = '$empid'");	
	$row = mysql_fetch_array($faminfo);	
	//family information
	$mother 	= htmlspecialchars($row['mother'], ENT_QUOTES);
	$father 	= htmlspecialchars($row['father'], ENT_QUOTES);
	$guardian 	= htmlspecialchars($row['guardian'], ENT_QUOTES);
	$noofsibling= htmlspecialchars($row['noofSiblings'], ENT_QUOTES);
	$siblingordr= htmlspecialchars($row['siblingOrder'], ENT_QUOTES);
	$spouse 	= htmlspecialchars($row['spouse'], ENT_QUOTES);
	$gender 	= $row['gender'];
	
	echo 
	"<div class='modf'>FAMILY BACKGROUND
	<input type='button' name='edit' id='edit-family' value='edit' class='btn btn-primary btn-sm' onclick='edit_family()'>
	<input type='button' class='btn btn-primary btn-sm' id='update-family' value='update' onclick='update(this.id)' style='display:none'></div>
	<table width='600' class='table table-bordered' >
		<tr>
			<td width='17%' align='right'>Mother</td>			
			<td colspan='2'><input type='text' name='mother' id='mother' value='".$mother."' class='form-control' onKeyUp='checkName(this)' disabled></td>			
		</tr>
		<tr>
			<td align='right'>Father</td>			
			<td  colspan='2'><input type='text' name='father' id='father' value='".$father."' class='form-control' onKeyUp='checkName(this)' disabled></td>			
		</tr>
		<tr>			
			<td align='right'>Guardian</td>			
			<td  colspan='2'><input type='text' name='guardian' id='guardian' value='".$guardian."' class='form-control' onKeyUp='checkName(this)' disabled></td>
		</tr>		
		<tr>			
			<td align='right'>Spouse</td>			
			<td  colspan='2'><input type='text' name='spouse' id='spouse' value='".$spouse."' class='form-control' onKeyUp='checkName(this)' disabled></td>
		</tr>		
	</table>	
	";
}

//*********************************** CONTACT INFORMATION ********************************//
else if(@$_GET['request'] == "contact")
{
	$contactinfo = mysql_query("SELECT home_address,city_address,contact_person,contact_person_address,contact_person_number,contactno,telno,email,facebookAcct,twitterAcct
	from applicant where app_id = '$empid' ");
	$row = mysql_fetch_array($contactinfo);
	//contact information
	$homeaddress 		= htmlspecialchars($row['home_address'], ENT_QUOTES);
	$cityaddress 		= htmlspecialchars($row['city_address'], ENT_QUOTES); 
	$contactperson 		= htmlspecialchars($row['contact_person'], ENT_QUOTES);
	$contactpersonadd 	= htmlspecialchars($row['contact_person_address'], ENT_QUOTES); 
	$contactpersonno 	= htmlspecialchars($row['contact_person_number'], ENT_QUOTES); 
	$cellphone 	= htmlspecialchars($row['contactno'], ENT_QUOTES); 
	$telno 		= htmlspecialchars($row['telno'], ENT_QUOTES); 
	$email 		= htmlspecialchars($row['email'], ENT_QUOTES); 
	$fb 		= htmlspecialchars($row['facebookAcct'], ENT_QUOTES); 
	$twitter 	= htmlspecialchars($row['twitterAcct'], ENT_QUOTES); 
	
	echo 
	"<div class='modf'>CONTACT & ADDRESS INFORMATION	
	<input type='button' name='edit' id='edit-contact' value='edit' class='btn btn-primary btn-sm' onclick='edit_contact()'>
	<input type='button' class='btn btn-primary btn-sm' id='update-contact' value='update' onclick='update(this.id)' style='display:none'>
	</div>
	<table class='table table-bordered'>
		<tr>
			<td width='17%' align='right'>Home Address</td>
			<td colspan='4'>";
				$result = $q->innerJOINbrgytownprov();
			    echo "<input list='homeadd'  id='homeaddress' name='home' autocomplete='off' value='".$homeaddress."' class='form-control' disabled/>
			    <datalist id='homeadd'>";
			    while($rs =$q->fetchArray($result)){ 
			    	echo "<option value='".$rs['brgy_name'].", ".$rs['town_name'].", ".$rs['prov_name']."'>".$rs['brgy_name'].", ".$rs['town_name'].", ".$rs['prov_name']."</option>";
			    }echo "</datalist>
			</td>
		</tr>
		<tr>
			<td align='right'>City Address</td>
			<td colspan='4'>";
				$result = $q->innerJOINbrgytownprov();
			    echo "<input list='cityadd'  id='cityaddress' name='city' autocomplete='off' value='".$cityaddress."' class='form-control' disabled/>
			    <datalist id='cityadd'>";
			    while($rs =$q->fetchArray($result))
			    { 
			    	echo "<option value='".$rs['brgy_name'].", ".$rs['town_name'].", ".$rs['prov_name']."'>".$rs['brgy_name'].", ".$rs['town_name'].", ".$rs['prov_name']."</option>";
			    }
			    echo "</datalist>
			</td>
		</tr>
		<tr>
			<td align='right'>Contact Person</td>			
			<td colspan='4'><input type='text' name='contactperson' id='contactperson' value='".$contactperson."' class='form-control' onKeyUp='checkName(this)' disabled></td>
		</tr>
		<tr>
			<td align='right'>Contact Person Address</td>			
			<td colspan='4'>";
				$result = $q->innerJOINbrgytownprov();
				echo "<input list='contactpersonadd'  id='contactpersonaddress' name='contactpersonadd' autocomplete='off' value='".$contactpersonadd."' class='form-control' disabled/>
				<datalist id='contactpersonadd'>";
				while($rs =$q->fetchArray($result))
				{ 
					echo "<option value='".$rs['brgy_name'].", ".$rs['town_name'].", ".$rs['prov_name']."'>".$rs['brgy_name'].", ".$rs['town_name'].", ".$rs['prov_name']."</option>";
				}
				echo "</datalist>
			</td>
		</tr>
		<tr>
			<td align='right'>Contact Person No.</td>			
			<td><input type='text' name='contactpersonno' id='contactpersonno' value='".$contactpersonno."' class='form-control' disabled></td>
			<td align='right'>Cellphone No</td>			
			<td><input type='text' name='cellphone' id='cellno' value='".$cellphone."' class='form-control' onKeyUp='checkInput(this)' disabled></td>
		</tr>
		<tr>
			<td align='right'>Telephone No.</td>			
			<td><input type='text' name='telno' id='telno' value='".$telno."' class='form-control' disabled></td>
			<td align='right'>Email address</td>			
			<td><input type='text' name='email' id='email' value='".$email."' class='form-control' disabled></td>
		</tr>
		<tr>
			<td align='right'>Facebook</td>
			<td><input type='text' name='fb' id='fb' value='".$fb."' class='form-control' disabled></td>
			<td align='right'>Twitter</td>
			<td><input type='text' name='twitter' id='twitter' value='".$twitter."' class='form-control' disabled></td>
		</tr>
		<tr>	
	</table>
	<p>&nbsp;</p>";
}

//*********************************** EDUCATIONAL INFORMATION *******************************//
else if(@$_GET['request'] == "educ")
{
	$educinfo = mysql_query("SELECT attainment,school,course from applicant where app_id = '$empid' ");
	$row = mysql_fetch_array($educinfo);
	//educational attainment
	$attainment = htmlspecialchars($row['attainment'], ENT_QUOTES); 
	$school 	= htmlspecialchars($row['school'], ENT_QUOTES); 
	$course 	= htmlspecialchars($row['course'], ENT_QUOTES); 
	
	echo 
	"<div class='modf'>EDUCATIONAL BACKGROUND
	<input type='button' name='edit' id='edit-educ' value='edit' class='btn btn-primary btn-sm' onclick='edit_educ()'>
	<input type='button' class='btn btn-primary btn-sm' id='update-educ' value='update' onclick='update(this.id)' style='display:none'></div>
	
	<table class='table table-bordered'>
		<tr>
			<td width='17%' align='right'>Educational Attainment</td>
			<td>
				<select name='attainment' class='form-control' id='attainment' disabled>
        		<option></option>";        	
				$result1 = $q->selectALLfromATTAINMENT();
				while($rw =$q->fetchArray($result1)){
					if($attainment == $rw['attainment']){
		 		    	echo "<option value='".$rw['attainment']."' selected='selected' >".$rw['attainment']."</option>";
					}else{ 
		 		    	echo "<option value='".$rw['attainment']."' >".$rw['attainment']."</option>";
					}
				}echo "</select>	
		    </td>   		    
		</tr>
		<tr>
			<td align='right'>School</td>
			<td>";
				$result = $q->selectDISTINCTschoolnameFROMSCHOOL();
			    echo "<input list='schools'  id='school' onkeypress='checkName(this)' name='school' autocomplete='off' value='".$school."' class='form-control' disabled />
      			<datalist id='schools'>";
        		while($rows =$q->fetchArray($result)){ 
        			if($school == $rows['school_name']){
        				echo "<option value='".$rows['school_name']."'>".$rows['school_name']."</option>";
        			}else{
        				echo "<option value='".$rows['school_name']."'>".$rows['school_name']."</option>";
        			}        			
        		}echo "</datalist>
			</td>
		</tr>
		<tr>			
			<td align='right'>Details / Course</td>
			<td>
				<input list='courses'  name='course' id='course' onkeypress='checkName(this)' autocomplete='off' value='".$course."' class='form-control' disabled/>
		      	<datalist id='courses'>";
		     	$result = $nq->selectTable('course');
				while($rs =mysql_fetch_array($result)){ 
		        	if($course == $rs['course_name']){
			        	echo "<option value='".$rs['course_name']."'>".$rs['course_name']."</option>";
		        	}else{		        		
		        		echo "<option value='".$rs['course_name']."'>".$rs['course_name']."</option>";	
		        	}
		        }echo "</datalist>
			</td>
		</tr>	
	</table>	
	<p>&nbsp;</p>";
}
//*********************************** SEMINARS INFORMATION ********************************//
else if(@$_GET['request'] == "seminar")
{
	echo 
	"<div class='modf'>ELIGIBILITY / SEMINARS / TRAININGS INFORMATION
	<input type='button' class='btn btn-primary btn-sm' id='add-seminar' value='add' onclick=add_seminar('')></div>
	<table class='table table-striped' width='100%'>
		<tr>           
			<th>Name</th>
			<th>Date</th>		
			<th>Location</th>
			<th>Action</th>
		</tr>";		
		$ss = mysql_query("SELECT * from `application_seminarsandeligibility` where app_id  = '$empid' ");
		while($rwss = mysql_fetch_array($ss))
		{ 	 
		echo "  
		<tr>		           
			<td>".htmlspecialchars($rwss['name'], ENT_QUOTES)."</td>
			<td>".htmlspecialchars($rwss['dates'], ENT_QUOTES)."</td>
			<td>".htmlspecialchars($rwss['location'], ENT_QUOTES)."</td>
			<td><input type='button' class='btn btn-primary btn-sm' value='edit' id='edit-seminar' onclick=add_seminar('$rwss[no]')></td>
		</tr>";
		}		
	echo "</table>
	<p>&nbsp;</p>";
}

//*********************************** CHARACTER REFERENCES ********************************//
else if(@$_GET['request'] == "charref")
{
	echo 
	"<div class='modf'>CHARACTER REFERENCES
	<input type='button' class='btn btn-primary btn-sm' id='add-charref' value='add' onclick=add_charref('')>
	</div>
	<table width='100%' class='table table-striped'>
		<tr>           
			<th>Name</th>
			<th>Position</th>
			<th>Contact Number</th>
			<th>Company / Location / Address</th>
			<th>Action</th>
		</tr>";
		$s = mysql_query("SELECT * from application_character_ref where app_id  = '$empid' ");
		while($rws = @mysql_fetch_array($s))
		{
		echo " 
			<tr>            
				<td>".htmlspecialchars($rws['name'], ENT_QUOTES)."</td>
				<td>".htmlspecialchars($rws['position'], ENT_QUOTES)."</td>
				<td>".htmlspecialchars($rws['contactno'], ENT_QUOTES)."</td>
				<td>".htmlspecialchars($rws['company'], ENT_QUOTES)."</td>
				<td><input type='button' class='btn btn-primary btn-sm' value='edit' id='edit-charref' onclick=add_charref('$rws[no]')></td>
			</tr>";
		} 
	echo "</table>
	<p>&nbsp;</p>";
}

//****************************************** SKILLS **************************************//
else if(@$_GET['request'] == "skills")
{
	$skillsinfo = mysql_query("SELECT hobbies,specialSkills from applicant where app_id = '$empid'"); 
	$row = mysql_fetch_array($skillsinfo);
	//skills
	$hobbies 	= htmlspecialchars($row['hobbies'], ENT_QUOTES);
	$skills  	= htmlspecialchars($row['specialSkills'], ENT_QUOTES);
	
	echo "<div class='modf'>SKILLS & COMPETENCIES
	<input type='button' name='edit' id='edit-skills' value='edit' class='btn btn-primary btn-sm' onclick='edit_skills()'>
    <input type='button' class='btn btn-primary btn-sm' id='update-skills' value='update' onclick='update(this.id)' style='display:none'>
	</div>
	<table width='100%' class='table table-bordered'>
		<tr>
			<td width='17%' align='right'>Hobbies</td>		
			<td><textarea name='hobbies' class='form-control' disabled id='hobbies' >".$hobbies."</textarea></td>
		</tr>
		<tr>
			<td align='right'>Special skills / Talents</td>
			<td><textarea name='skills' class='form-control' disabled id='skills'>".$skills."</textarea></td>
		</tr>
    </table> 
    <p>&nbsp;</p>";
}

//****************************************** EOC APPRAISAL **************************************
else if(@$_GET['request'] == "eocapp")
{
	echo "<table class='table table-striped' width='100%'>    	
		<tr>						
			<th><b>STARTDATE</b></th>
			<th><b>EOCDATE</b></th>
			<th><b>PERFORMANCE EVALUATION</b></th>
			<th><b>RATER'S NAME</b></th>			
			<th><b>NUMERICAL RATING</b></th>
			<th><b>DESCRIPTIVE RATING</b></th> 		
			<th><b>RATING</b></th>			
			<th><b>ACTION</b></th>
		</tr>";		
	
		$query1 = mysql_query("SELECT record_no, startdate, eocdate, epas_code FROM employee3 WHERE emp_id = '$empid' and epas_code != '0' and epas_code != '' order by record_no desc");
		while ($row1 = mysql_fetch_array($query1))
		{ 			
			$q1 = mysql_query("SELECT rater, numrate, descrate, code, ratingdate, record_no from appraisal_details where record_no = '$row1[record_no]' and emp_id = '$empid' ");
			$r1 = mysql_fetch_array($q1);
			
			switch($r1['descrate']){
		   		case "E"	: $drate = "Excellent"; break;
		   		case "VS"	: $drate = "Very Satisfactory"; break;
		   		case "S"	: $drate = "Satisfactory"; break;
		   		case "US"	: $drate = "Unsatisfactory"; break;
		   		case "VU"	: $drate = "Very Unsatisfactory"; break;
	 	    }
			
			echo 
			"<tr><td>";				
				if($row1['startdate'] == '' || $row1['startdate'] == '0000-00-00' ){ echo ""; }
				else{ echo "<span id='sdate_".$row1['record_no']."'>".$nq->changeDateFormat('m/d/Y',$row1['startdate'])."</span>"; }
											
				echo "</td><td>";								
				if($row1['eocdate'] == '' || $row1['eocdate'] == '0000-00-00' ){ echo ""; }
				else{echo "<span id='edate_".$row1['record_no']."'>".$nq->changeDateFormat('m/d/Y',$row1['eocdate'])."</span>"; }
				
				if($r1['ratingdate'] == '' || $r1['ratingdate'] == '0000-00-00' ){ $rd = ''; } else { $rd = $nq->changeDateFormat('m/d/Y',$r1['ratingdate']);}

				$getaddr = mysql_query(" SELECT `add` FROM `appraisal_type` WHERE `code` = '$r1[code]' ");
				$radr = mysql_fetch_array($getaddr);
				$addr = $radr['add'];
				
				echo " 
				</td> 
				<td>".$nq->getOneField('appraisal','appraisal_type',"code='$r1[code]'")." (".$addr.")</td>
				<td>".$nq->getApplicantName($r1['rater'])."</td>
				<td>".$r1['numrate']."</td>
				<td>".$drate."</td>
				<td>".$rd."</td>
				<td style='display:none;'><span id='remarks_".$row1['record_no']."'>".$row1['remarks']."</span></td>
				<td><input type='button' data-toggle='modal' data-target='#openapp' name='viewapp' value='view' class='btn btn-primary btn-sm' onclick=openappraisal('".$r1['record_no']."','".$empid."','".$row1['epas_code']."')></td>					
			</tr>";					 
		}
		
		$query2 = mysql_query("SELECT * FROM employmentrecord_ WHERE emp_id = '$empid' and epas_code != '0' and epas_code != '' order by startdate desc");
		while ($row2 = mysql_fetch_array($query2))
		{ 
			$q2 = mysql_query("SELECT rater, numrate, descrate, code, ratingdate, record_no from appraisal_details where record_no = '$row2[record_no]' and emp_id = '$empid' ");
			$r2 = mysql_fetch_array($q2);
		
			switch($r2['descrate'])
		    {
		   		case "E"	: $drate = "Excellent"; break;
		   		case "VS"	: $drate = "Very Satisfactory"; break;
		   		case "S"	: $drate = "Satisfactory"; break;
		   		case "US"	: $drate = "Unsatisfactory"; break;
		   		case "VU"	: $drate = "Very Unsatisfactory"; break;
	 	    }
						
			echo 
			"<tr><td>";
				if($row2['startdate'] == '' || $row2['startdate'] == '0000-00-00' ){ echo ""; }
				else{ echo "<span id='sdate_".$row2['record_no']."'>".$nq->changeDateFormat('m/d/Y',$row2['startdate'])."</span>"; }
				
				echo "</td><td>";				
				if($row2['eocdate'] == '' || $row2['eocdate'] == '0000-00-00' ){ echo "";	}
				else{ echo "<span id='edate_".$row2['record_no']."'>".$nq->changeDateFormat('m/d/Y',$row2['eocdate'])."</span>"; }
				
				$getaddr = mysql_query(" SELECT `add` FROM `appraisal_type` WHERE `code` = '$r2[code]' ");
				$radr = mysql_fetch_array($getaddr);
				$addr = $radr['add'];

				echo " 
				</td> 
				<td>".$nq->getOneField('appraisal','appraisal_type',"code='$r2[code]'")." (".$addr.")</td>
				<td>".$nq->getApplicantName($r2['rater'])."</td>
				<td>".$r2['numrate']."</td>
				<td>".@$drate."</td>
				<td>".$nq->changeDateFormat('m/d/Y',$r2['ratingdate'])."</td>
				<td style='display:none;'><span id='remarks_".$row2['record_no']."'>".$row2['remarks']."</span></td>
				<td><input type='button' data-toggle='modal' data-target='#openapp' name='viewapp' value='view' class='btn btn-primary btn-sm' onclick=openappraisal('".$r2['record_no']."','".$empid."','".$row2['epas_code']."')></td>					

			</tr>";					 
	}
	echo "</table>";
}

//****************************************** APPLICATION **************************************//
else if(@$_GET['request'] == "application")
{
	echo 
	"<div class='modf'>APPLICATION HISTORY
	<input type='button' name='edit' id='edit-apphis' value='edit' class='btn btn-primary btn-sm' onclick='edit_apphis()'>
    <input type='button' class='btn btn-primary btn-sm' id='update-apphis' value='update' onclick='update(this.id)' style='display:none'>
	</div>
	<table class='table table-bordered'>		
		<tr>
			<td width='17%' align='right'>Position Applied</td>
			<td><input type='text' name='posapplied' id='posapplied' value='".htmlspecialchars(@$posapplied, ENT_QUOTES)."'  class='form-control' disabled></td>	
			<td align='right'>Date Applied</td>
			<td><input type='text' name='dateapplied' id='dateapplied' value='".htmlspecialchars(@$dateapplied, ENT_QUOTES)."' placeholder='mm/dd/yyyy' class='form-control' disabled></td>				
		</tr>			
		<tr>
			<td align='right'>Date of Exam</td>
			<td><input type='text' name='dateexamined' id='dateexamined' value='".htmlspecialchars(@$dateexamined, ENT_QUOTES)."' placeholder='mm/dd/yyyy' class='form-control' disabled></td>				
			<td align='right'>Exam Result</td>
			<td><input type='text' name='examres' id='examres'  value='".htmlspecialchars(@$examresult, ENT_QUOTES)."' class='form-control' disabled></td>				
		</tr>		
		<tr>
			<td align='right'>Date Briefed</td>
			<td><input type='text' name='datebriefed' id='datebriefed' value='".htmlspecialchars(@$datebrief, ENT_QUOTES)."' placeholder='mm/dd/yyyy' class='form-control' disabled></td>				
			<td align='right'>Date Hired</td>
			<td><input type='text' name='datehired' id='datehired' value='".htmlspecialchars(@$datehired, ENT_QUOTES)."' placeholder='mm/dd/yyyy' class='form-control' disabled></td>					
		</tr>
		<tr>
			<td align='right'>Recommended by (Alturas Employee)</td>
			<td colspan='3'>
				<input list='aeregulars' name='aeregular' class='form-control' id='aeregular' autocomplete='off' value='".$aeregular."' onKeyUp='checkName(this)' disabled />
			    <datalist id='aeregulars'>";
					$regs = $nq->getRegulars();
			        while($rreg = mysql_fetch_array($regs)){
			        	if(@$rreg['name'] == @$aeregular){			        		
			        		echo "<option value='".$rreg['name']."''>".$rreg['name']."</option>";	
			        	}else{
			        		echo "<option value='".$rreg['name']."''>".$rreg['name']."</option>";
			        	}
			        }
			    echo "</datalist>
			</td>				
		</tr>	
	</table>";
	
	//<input type='text' name='aeregular' id='aeregular' value='".htmlspecialchars(@$aeregular, ENT_QUOTES)."' class='form-control' disabled>
	echo "
	<table width='96%'  class='table table-striped'>
		<tr>
			<th height='39' colspan='11'>Examination History</th></tr>
			<tr bgcolor='#CCCCCC'>
			<th width='174'>No.</th>
			<th width='130'>Examination&nbsp;Date</th>
			<th width='500'>Applying&nbsp;For</th>
			<th width='345'>Exam&nbsp;Code</th>
			<th width='345'>Exam&nbsp;Details</th>
		</tr>"; 
	 	
		$sql= mysql_query("SELECT * from application_history WHERE app_id='$empid' and phase='Examination' and status='completed' ");
		$x=0;
		if(@mysql_num_rows($sql)>0)
		{
			while($row= mysql_fetch_array($sql))
			{   
				$x++;
				$exstr = explode(",", $row['description']);
				$excode = explode(" ", $exstr[1]);
				$exam_val = $empid."|".$excode[0];
				echo            
				"<tr>
					<td width='174'>".$x."</td>
					<td width='419'>".$nq->changeDateFormat("M d, Y",$row['date_time'])."</td>
					<td width='307'>".$row['position']."</td>
					<td width='419'>".$excode[0]."</td>
					<td width='345'><a href='#' onclick=viewexam('".$exam_val."') data-toggle='modal' data-target='#viewexamdetails' >view</a></td>                
				</tr>"; 
			}
		}
	echo "</table>";
	
	echo "<input type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#viewapphistory' onclick='viewappdet()' value='View Application Details'> ";
	echo "<input type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#viewinterviewdetails' onclick='viewinterview()' value='View Interview Details'><br><br>";
	echo "<p>&nbsp;</p>	";		

}

//****************************************** EMPLOYMENT **************************************
else if(@$_GET['request'] == "employment")
{	
	echo "
	<p><i style='color:red'>Note: There should ONLY BE ONE CURRENT CONTRACT and that should be the latest status of the employee. <br>
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;When adding PREVIOUS CONTRACT, status should not be active.</i></p>";
	/* Present Contract */
	echo "<h4><span class='label label-success'>CURRENT CONTRACT</span></h4>";
	//<th><b>COMPANY</b></th>
	echo 
	"<table class='table table-striped' width='100%'>    	
		<tr >
			<th><b>NO</b></th>
			<th><b>POSITION</b></th>			
			<th><b>BUSINESS UNIT</b></th>
			<th><b>DEPT</b></th>
			<th><b>SECTION</b></th>			
			<th><b>STATUS</b></th>	
			<th><b>EMPTYPE</b></th>			
			<th><b>STARTDATE</b></th>
			<th><b>EOCDATE</b></th> 			
			<th><b>ACTION</b></th>
		</tr>";		
		$i = 0;
		$query1 = mysql_query("SELECT * FROM employee3 WHERE emp_id = '$empid' order by record_no desc");
		while ($row = mysql_fetch_array($query1))
		{ 
			$i++;
			$cc 	= $nq->getCompanyAcroName($row['company_code']); //getting the business unit namel
			$bunit	= $nq->getBusinessUnitName($row['bunit_code'],$row['company_code']); //getting the business unit name
			$dept	= $nq->getDepartmentName($row['dept_code'],$row['bunit_code'],$row['company_code']);
			$sec    = $nq->getSectionName($row['section_code'],$row['dept_code'],$row['bunit_code'],$row['company_code']);	
								
			if($dept == 'INFORMATION TECHNOLOGY' || $dept == 'Information Technology'){
				$dept = 'IT';
			}
			//<td>".@$cc."</td>
			echo 
			"<tr>	 
				<td>".$i."</td>
				<td><span id='pos_".$row['record_no']."'>".$row['position']."</span></td>				
				<td>".@$bunit."</td>
				<td>".@$dept."</td>
				<td>".@$sec."</td>			
				<td>".@$row['current_status']."</td>
				<td>".@$row['emp_type']."</td>
				<td>";
				if($row['startdate'] == '' || $row['startdate'] == '0000-00-00' ){
					echo "";
				}else{
					echo "<span id='sdate_".$row['record_no']."'>".$nq->changeDateFormat('m/d/Y',$row['startdate'])."</span>";
				}
				
				echo "</td>
				<td>";				
				if($row['eocdate'] == '' || $row['eocdate'] == '0000-00-00' ){
					echo "";
				}else{
					echo "<span id='edate_".$row['record_no']."'>".$nq->changeDateFormat('m/d/Y',$row['eocdate'])."</span>";
				}echo " 
				</td> 
				<td style='display:none;'><span id='remarks_".$row['record_no']."'>".$row['remarks']."</span></td>
				<td><a href='#' data-toggle='modal' data-target='#empdetails' title='View Employment Information' onclick=openemployment1('".$row['record_no']."','".$empid."') ><img src='../images/icons/Search-icon.png'></a>
				&nbsp;<a href='#' data-toggle='modal' data-target='#contact_form' title='Edit Employment History' onclick=editemployment1('".$row['record_no']."') ><img src='../images/icons/edit-icon.png'></a>
				<a href='#' data-toggle='modal' data-target='#upload_contract' title='Upload Scanned Contract' onclick=set_record('".$row['record_no']."','employee3') ><img src='../images/icons/button-arrow-up-icon1.png'></a>	
				</td>
			</tr>";					 
	}
	echo "</table>";
	
		/* History Record */
	
	echo "<h4><span class='label label-danger'>PREVIOUS CONTRACT</span></h4>";
	//<th><b>COMPANY</b></th>
	echo 
	"
	<table class='table table-striped' width='100%'>    	
		<tr >
			<th><b>NO</b></th>
			<th><b>POSITION</b></th>
			
			<th><b>BUSINESS UNIT</b></th>
			<th><b>DEPT</b></th>
			<th><b>SECTION</b></th>			
			<th><b>STATUS</b></th>	
			<th><b>EMPTYPE</b></th>			
			<th><b>STARTDATE</b></th>
			<th><b>EOCDATE</b></th> 			
			<th><b>ACTION</b></th>
		</tr>";		
		$i = 0;
		
		$query = mysql_query("SELECT * FROM employmentrecord_ WHERE emp_id = '$empid' order by startdate desc");
		while ($row = mysql_fetch_array($query))
		{ 
			$i++;
			$cc 	= $nq->getCompanyAcroName($row['company_code']); //getting the business unit namel
			$bunit	= $nq->getBusinessUnitName($row['bunit_code'],$row['company_code']); //getting the business unit name
			$dept	= $nq->getDepartmentName($row['dept_code'],$row['bunit_code'],$row['company_code']);
			$sec    = $nq->getSectionName($row['section_code'],$row['dept_code'],$row['bunit_code'],$row['company_code']);	

			if($dept == 'INFORMATION TECHNOLOGY' || $dept == 'Information Technology'){
				$dept = 'IT';
			}
			//<td>".@$cc."</td>
			echo 
			"<tr>	 
				<td>".$i."</td>
				<td><span id='pos_".$row['record_no']."'>".$row['position']."</span></td>
				
				<td>".@$bunit."</td>
				<td>".@$dept."</td>
				<td>".@$sec."</td>			
				<td>".@$row['current_status']."</td>
				<td>".@$row['emp_type']."</td>			
				<td>";
				if($row['startdate'] == '' || $row['startdate'] == '0000-00-00' ){
					echo "";
				}else{
					echo "<span id='sdate_".$row['record_no']."'>".$nq->changeDateFormat('m/d/Y',$row['startdate'])."</span>";
				}
				
				echo "</td>
				<td>";				
				if($row['eocdate'] == '' || $row['eocdate'] == '0000-00-00' ){
					echo "";
				}else{
					echo "<span id='edate_".$row['record_no']."'>".$nq->changeDateFormat('m/d/Y',$row['eocdate'])."</span>";
				}echo " 
				</td> 
				<td style='display:none;'><span id='remarks_".$row['record_no']."'>".$row['remarks']."</span></td>
				<td>
					<a href='#' data-toggle='modal' data-target='#empdetails' title='View Employment Information' onclick=openemployment('".$row['record_no']."','".$empid."') ><img src='../images/icons/Search-icon.png'></a>
					&nbsp;<a href='#' data-toggle='modal' data-target='#contact_form' title='Edit Employment History' onclick=editemployment('".$row['record_no']."') ><img src='../images/icons/edit-icon.png'></a>
					&nbsp;<a href='#' data-toggle='modal' data-target='#upload_contract' title='Upload Scanned Contract' onclick=set_record('".$row['record_no']."','employmentrecord_') ><img src='../images/icons/button-arrow-up-icon1.png'></a>
				</td>
			</tr>";					 
	}
	echo "</table>";
}

//****************************************** Employment History **************************************//
else if(@$_GET['request'] == "history")
{
	echo "<div class='modf'>EMPLOYMENT HISTORY</div>
	<table class='table table-striped' width='100%'>    	
		<tr >
			<th><b>NO</b></th>
			<th><b>COMPANY</b></th>
			<th><b>POSITION</b></th>
			<th><b>DATE START</b></th>
			<th><b>DATE END</b></th>			
			<th><b>ADDRESS/LOCATION</b></th>
			<th><b>CERTIFICATE</b></th>
			<th><b>ACTION</b></th>
		</tr>";		
		$i = 0;
		$query = mysql_query("SELECT * FROM application_employment_history WHERE app_id = '$empid' ");
		while ($row = mysql_fetch_array($query))
		{ 
			$i++;
			echo 
			"<tr>	 
				<td>".$i."</td>
				<td>".$row['company']."</td>
				<td>".$row['position']."</td>
				<td>".$row['yr_start']."</td>
				<td>".$row['yr_ends']."</td>
				<td>".$row['address']."</td>";
			if($row['emp_certificate'] !=''){
			echo "	
				<td><input type='button' data-toggle='modal' data-target='#viewcertificate' class='btn btn-primary btn-sm' value='view' onclick=viewcertificate('$row[emp_certificate]') ></td>";
				}else{
			echo "<td>none</td>";	
				}
				
				echo "<td><input type='button' class='btn btn-primary btn-sm' value='edit' id='edit-charref' onclick=add_emphis('$row[no]')></td>
			</tr>";					 
		}
	echo "</table>
	<input type='button' class='btn btn-primary' id='add-emphis' value='add' onclick=add_emphis('')><p>&nbsp;</p>";
}
else if(@$_GET['request'] == "transfer")
{
	echo "<div class='modf'>JOB TRANSFER HISTORY</div>	
	<table class='table table-striped' id='data'>
		<tr>
		<th>TNO</th>
        <th>EFFECTIVITY</th>   
        <th>TRANSFER FROM</th>      
        <th>TRANSFER TO</th>
        <th>OLD POSITION</th>
        <th>NEW POSITION</th>  
        <th>DIRECT SUPERVISOR</th>      
        <th>JOB TRANS</th>
        </tr>";                               
        $jobtransfer = mysql_query("SELECT * FROM employee_transfer_details where emp_id = '$empid' order by transfer_no desc ");
        while(@$row = mysql_fetch_array($jobtransfer))
       	{  
   			$ol = explode('-',$row['old_location']);
   			$nl = explode('-',$row['new_location']);
			$olddept = $nq->getDepartmentName(@$ol[2],@$ol[1],@$ol[0]);
            $dept = $nq->getDepartmentName(@$nl[2],@$nl[1],@$nl[0]);
            if($dept == 'INFORMATION TECHNOLOGY' || $dept == 'Information Technology'){
                $dept = 'IT';
            }else{
                $dept = $nq->getDepartmentName(@$nl[2],@$nl[1],@$nl[0]);
            }
   		?>
            <tr>
				<td><?php echo $row['transfer_no'];?></td>
                <td><?php 
                if(strlen($row['effectiveon']) > 10 || strlen($row['effectiveon']) < 10){ echo $row['effectiveon']; } else { echo $nq->changeDateFormat('m/d/Y',@$row['effectiveon']); } ?></td>
                <td><?php echo $nq->getCompanyAcroName($ol[0])."-".$nq->getBusinessUnitName($ol[1],$ol[0])."-".$olddept;?></td>
                <td><?php echo $nq->getCompanyAcroName($nl[0])."-".$nq->getBusinessUnitName($nl[1],$nl[0])."-".$dept;?></td>	
                <td><?php echo @$row['old_position'];?></td> 
                <td><?php echo @$row['position'];?></td>   
                <td><?php echo @$row['supervision'];?></td>	 
                <td><input type='button' onclick=viewJobTrans('<?php echo @$row['transfer_no'];?>') value='view' class='btn btn-primary btn-sm' /></td>  
            </tr><?php                   
        }
    echo "</table>";
}


//****************************************** MISCONDUCT **************************************//
else if(@$_GET['request'] == "misconduct")
{
	echo 
	"<table class='table table-bordered'>
		<tr>
			<td>MISCONDUCT INFORMATION HERE</td>
		</tr>		
	</table>";

}

//****************************************** BLACKLIST **************************************//
else if(@$_GET['request'] == "blacklist")
{
	echo "<div class='modf'>BLACKLIST HISTORY</div>";
	$qEditBL = mysql_query("SELECT * FROM blacklist where app_id = '$empid'");
	if(mysql_num_rows($qEditBL) > 0)
	{
		echo 
		"<table class='table table-bordered'>
			<tr>
				<td><b>DATE BLACKLISTED</b></td>
				<td><b>REPORTED BY</b></td>
				<td><b>REASON/DETAILS</b></td>
				<td><b>DATE ADDED</b></td>
				<td><b>STATUS</b></td>                
			</tr>";
			while($rr = mysql_fetch_array($qEditBL)){
				if($rr['date_blacklisted'] == '0000-00-00' || $rr['date_blacklisted'] == ''){
					$datebl = '';
				}else{
					$datebl = new DateTime($rr['date_blacklisted']);
					$datebl = $datebl->format('F d, Y');
				}

				if($rr['date_added'] == '0000-00-00' || $rr['date_added'] == ''){
					$dateadded = '';
				}else{
					$dateadded = new DateTime($rr['date_added']);
					$dateadded = $dateadded->format('F d, Y');
				}
				
				echo 
				"<tr>	
					<td>".$datebl."</td>
					<td>".$rr['reportedby']."</td>
					<td>".$rr['reason']."</td>	
					<td>".$dateadded."</td>			
					<td><span class='label label-danger'>".$rr['status']."</span></td>
				</tr>";
			}      
		echo "</table>";
    }else{ 
    	echo "<p align='center'><b>NO BLACKLIST HISTORY</b></p>";                         
    }
}

//****************************************** BENEFITS **************************************//
/*
else if(@$_GET['request'] == "benefits")
{		
	$sel = mysql_query("SELECT * FROM benefits where emp_id = '$empid'");
	while($rb = mysql_fetch_array($sel)){ @$ph = $rb['philhealth']; $sss = $rb['sssno']; $pagibig = $rb['pagibig'];   }  

	$prtn = $nq->getOneField('pagibig_tracking','applicant_otherdetails'," app_id = '$empid' ");
	//<input type='button' name='edit' id='edit-benefits' value='edit' class='btn btn-primary btn-sm' onclick='edit_benefits()'>
//	<input type='button' class='btn btn-primary btn-sm' id='update-benefits' value='update' style='display:none' onclick=update(this.id)>
	echo 
	"<div class='modf'>BENEFITS
	</div>
	<table width='100%' class='table table-bordered'>
		<tr>
			<td width='17%' align='right'>Philhealth No.</td>			
			<td><input type='text' name='ph' id='ph' value='".@$ph."' class='form-control' placeholder='00-000000000-0' disabled></td>      
		</tr>
		<tr>
			<td align='right'>SSS No.</td>			
			<td><input type='text' name='sss' id='sss' value='".@$sss."' class='form-control' placeholder='00-0000000-0' disabled></td>
		</tr>
		<tr>
			<td align='right'>Pag-ibig No.</td>			
			<td><input type='text' name='pagibig' id='pagibig' value='".@$pagibig."' class='form-control' placeholder='0000-0000-0000' disabled></td>
		</tr>
		<tr>
			<td align='right'>Pag-ibig RTN</td>			
			<td><input type='text' name='pagibigrtn' id='pagibigrtn' value='".@$prtn."' class='form-control' placeholder='0000-0000-0000' disabled></td>
		</tr>
		";
		/*
		<tr>
			<td align='right'>TIN No.</td>			
			<td><input type='text' name='pagibig' id='pagibig' value='".@$tin."' class='form-control' placeholder='000-000-000-000' disabled></td>
		</tr>
		echo "
	</table>
	<p>&nbsp;</p>";
}*/
else if(@$_GET['request'] == "benefits")
{		
	$sel = mysql_query("SELECT * FROM applicant_otherdetails where app_id = '$empid'");
	while($rb = mysql_fetch_array($sel)){ 
		$ph 	= $rb['philhealth']; 
		$sss 	= $rb['sss_no']; 
		$pagibig= $rb['pagibig']; 
		$prtn 	= $rb['pagibig_tracking']; 
		$tin 	= $rb['tin_no'];
	}  


	echo 
	"<div class='modf'>BENEFITS 
		<input type='button' name='edit' id='edit-benefits' value='edit' class='btn btn-primary btn-sm' onclick='edit_benefits()'>
		<input type='button' class='btn btn-primary btn-sm' id='update-benefits' value='update' style='display:none' onclick=update(this.id)>		
	</div>
	<table width='100%' class='table table-bordered'>
		<tr>
			<td width='17%' align='right'>Philhealth No.</td>			
			<td><input type='text' name='ph' id='ph' value='".@$ph."' class='form-control' placeholder='00-000000000-0' disabled></td>      
		</tr>
		<tr>
			<td align='right'>SSS No.</td>			
			<td><input type='text' name='sss' id='sss' value='".@$sss."' class='form-control' placeholder='00-0000000-0' disabled></td>
		</tr>
		<tr>
			<td align='right'>Pag-ibig No.</td>			
			<td><input type='text' name='pagibig' id='pagibig' value='".@$pagibig."' class='form-control' placeholder='0000-0000-0000' disabled></td>
		</tr>
		<tr>
			<td align='right'>Pag-ibig RTN</td>			
			<td><input type='text' name='pagibigrtn' id='pagibigrtn' value='".@$prtn."' class='form-control' placeholder='0000-0000-0000' disabled></td>
		</tr>
		<tr>
			<td align='right'>TIN no.</td>			
			<td><input type='text' name='tinno' id='tinno' value='".@$tin."' class='form-control' placeholder='000-000-000-000' disabled></td>
		</tr>
		";
		echo "
	</table>
	<p>&nbsp;</p>";
	?>
	<script type="text/javascript" src="../jquery/jquery.maskedinput.js" ></script>
	<script>
		$(document).ready(function(){
			$("input[name='ph']").mask("99-999999999-9");
			$("input[name='sss']").mask("99-9999999-9");
			$("input[name='pagibig']").mask("9999-9999-9999");
			$("input[name='pagibigrtn']").mask("9999-9999-9999");
			$("input[name='tinno']").mask("999-999-999-999");
		});
	</script>
	<?php
}


//****************************************** 201 DOCS **************************************//
else if(@$_GET['request'] == "201doc")
{
	echo "<div class='modf'>201 DOCUMENTS</div>";
	echo 	
	"<h4>INITIAL REQUIREMENTS</h4>
    <table class='table table-bordered'>
    <tr>
		<td>NO</td>
		<td>REQUIREMENT NAME</td>
		<td>DATE SUBMITTED</td>
		<td>ACTION</td>
    </tr>";   
    $i=0;
	$allreq = mysql_query("select * from application_initialreq where app_code = '$empid' order by date_time desc ");
	while($r = mysql_fetch_array($allreq))
	{
		$date = new DateTime($r['date_time']); 
		$reqname = htmlspecialchars($r['requirement_name'], ENT_QUOTES);

	    echo 
	    "<tr>
			<td>".($i+1)."</td>
			<td>".$reqname."</td>
			<td>".$date->format('F d, Y')."</td>
			<td><a href='#' data-toggle='modal' data-target='#viewreqr' class='btn btn-primary btn-sm' onclick=view_req('app_code','".$empid."','".$reqname."','application_initialreq')>view</a></td>
	    </tr>";
	    
   		$i++;
    } 
    echo "</table>
		
    <h4>FINAL REQUIREMENTS</h4>
    <table class='table table-bordered'>
		<tr>
			<td>NO</td>
			<td>REQUIREMENT NAME</td>
			<td>DATE SUBMITTED</td>
			<td>ACTION</td>
		</tr>";
   $i=0;
	$allreq = mysql_query("select * from application_finalreq where app_id = '$empid' order by date_time desc ");
	while($r = mysql_fetch_array($allreq))
	{
		$date = new DateTime($r['date_time']); 
		$reqname = htmlspecialchars($r['requirement_name'], ENT_QUOTES);
   
	   	echo 
	    "<tr>
			<td>".($i+1)."</td>
			<td>".$reqname."</td>
			<td>".$date->format('F d, Y')."</td>
			<td><a href='#' data-toggle='modal' class='btn btn-primary btn-sm' data-target='#viewreqr' onclick=view_req('app_id','".$empid."','".$reqname."','application_finalreq')>view</a>      
	    </tr>";
    
   		$i++;
    } 
    echo "</table>";

}
else if(@$_GET['request'] == "pss")
{
	//$sup = mysql_query("SELECT * FROM leveling_supervisor where ratee = '$empid'");
	$sup = mysql_query("SELECT * FROM leveling_subordinates where subordinates_rater = '$empid'");
	$peer= mysql_query("SELECT * FROM leveling_peers where ratee = '$empid' ");
	$sub = mysql_query("SELECT * FROM leveling_subordinates where ratee = '$empid' ");
	
	echo "<p><h4>SUPERVISOR</h4></p>";	
	echo "<table class='table table-striped'>";
	echo "<tr><td width='40%'><b>NAME</b></td><td width='30%'><b>POSITION</b></td><td><b>EMPLOYEE TYPE</b></td></tr>";
	while($rsup  = mysql_fetch_array($sup)){
		$name 	 = $nq->getApplicantName($rsup['ratee']);
		$pos 	 = $nq->getEmpPosition($rsup['ratee']);
		$emptype = $nq->getEmpType($rsup['ratee']);		
		echo "<tr><td><a href='employee_details.php?com=$rsup[ratee]'>".ucwords(strtolower($name))."</a></td><td>$pos</td><td>$emptype</td></tr>";				
	}
	echo "</table>";
	
	echo "<p><h4>PEERS</h4></p>";	
	echo "<table class='table table-striped'>";
	echo "<tr><td  width='40%'><b>NAME</b></td><td width='30%'><b>POSITION</b></td><td><b>EMPLOYEE TYPE</b></td></tr>";
	while($rpeer = mysql_fetch_array($peer)){
		$name 	 = $nq->getApplicantName($rpeer['peer_rater']);
		$pos 	 = $nq->getEmpPosition($rpeer['peer_rater']);
		$emptype = $nq->getEmpType($rpeer['peer_rater']);		
		echo "<tr><td><a href='employee_details.php?com=$rpeer[peer_rater]'>".ucwords(strtolower($name))."</a></td><td>$pos</td><td>$emptype</td></tr>";				
	}
	echo "</table>";

	
	$subordinate = mysql_query("SELECT l.record_no, l.subordinates_rater, l.ratee, e.emp_id, e.name, e.position, e.current_status FROM leveling_subordinates AS l INNER JOIN employee3 AS e on l.subordinates_rater = e.emp_id where ratee = '$empid' ");// nd current_status = 'Active'

	echo "<p><h4>SUBORDINATES</h4></p>";
	echo 
	"<table id='subordinates-datatable' class='table table-striped'>
		<thead>
			<tr><th></th><th width='40%'><b>NAME</b></th><th width='30%'><b>POSITION</b></th><th><b>EMPLOYEE TYPE</b></th></tr>
		</thead>		
		<tbody>";		
			$count = 0;
			while($rsub = mysql_fetch_array($subordinate)){ 
				$count++;
				echo "<tr>	
				<td width='7'>".$count."</td>
				<td width='105'><a href='employee_details.php?com=$rsub[emp_id]'>".ucwords(strtolower(@$rsub['name']))."</a></td>				
				<td width='266'>".@$rsub['position']."</td>
				<td width='266'>".@$rsub['current_status']."</td>"; 
			
			echo "</tr>";
			} 
			echo "</tbody>
	</table>";
}

//****************************************** REMARKS **************************************//
else if(@$_GET['request'] == "remarks")
{
	$remarks_q = mysql_query("SELECT remarks FROM remarks where emp_id = '$empid' ");
	$re = mysql_fetch_array($remarks_q);
	
	echo 
	"<div class='modf'>REMARKS
	<input type='button' class='btn btn-primary btn-sm' id='save-remarks' value='save remarks' onclick='save_remarks()' style='display:none;'>
	<input type='button' name='edit' id='edit-remarks' value='edit' class='btn btn-primary btn-sm' onclick='edit_remarks()'></div>";
	
	$checkifres = mysql_query("SELECT * FROM `termination` WHERE emp_id = '$empid' order by date desc");

	if(mysql_num_rows($checkifres) > 0){
		
		echo "<div class='alert alert-info' role='alert'>";
		while($rch = mysql_fetch_array($checkifres)){
			echo "<i>".$rch['remarks']." last ".$nq->changeDateFormat('M d, Y',$rch['date'])." added by ".$nq->getApplicantName2($rch['added_by'])." updated last ".$nq->changeDateFormat('M d, Y',$rch['date_updated']).".</i><br>";
		}
		echo "</div>";
	}
	echo "
	<textarea rows='15' cols='150' class='form-control' id='remarks' disabled>".htmlspecialchars($re['remarks'], ENT_QUOTES)."</textarea>
	<p>&nbsp;</p>";
}
else if(@$_GET['request'] == "useraccount"){
	$query = mysql_query("SELECT user_no,username,usertype,user_status,login,date_created from users where emp_id = '$empid' ");
	echo "
	<div class='modf'>USER ACCOUNT</div>
	<table class='table table-striped table-hover'>
		<tr>
			<th>USERNO</th>
			<th>USERNAME</th>
			<th>USERTYPE</th>
			<th>USERSTATUS</th>
			<th>LOG IN</th>
			<th>DATE CREATED</th>
		</tr>";

		while($r = mysql_fetch_array($query)){
			if($r['user_status'] == 'active'){
				$userstat = "<span class='label label-success'>$r[user_status]</span>";
			}else if($r['user_status']){
				$userstat = "<span class='label label-warning'>$r[user_status]</span>";
			}

			echo "
			<tr>
				<td>".$r['user_no']."</td>
				<td>".$r['username']."</td>
				<td>".$r['usertype']."</td>
				<td>".$userstat."</td>
				<td>".$r['login']."</td>
				<td>".$nq->changeDateFormat('F d, Y H:i:s a',$r['date_created'])."</td>
			</tr>";
		}
		echo "
	</table>";
}
/*************************************** UPDATING HAPPENS HERE **********************************/

//**************************************** UPDATE family
if(@$_GET['request'] == "update-family")
{

	$updatefam = mysql_query(
					"UPDATE 
						applicant 
					 set 
						mother = '".addslashes($_POST['mother'])."', 
						father = '".addslashes($_POST['father'])."', 
						guardian = '".addslashes($_POST['guardian'])."', 
						spouse = '".addslashes($_POST['spouse'])."' 
					 where 
						app_id = '".$_POST['empid']."'
				") or die(mysql_error());
	if($updatefam)
	{
		logs($_SESSION['emp_id'],$_SESSION['username'],date("Y-m-d"),date("H:i:s"),'Updating the Family Information of '.$nq->getAppName(@$_POST['empid']));	
		echo "success";
	}
}
//**************************************** UPDATE basicinfo
else if(@$_GET['request']=="update-basicinfo")
{		
	if($_POST['bday'] == ''){ $bday = ''; } else { $bday = $nq->changeDateFormat('Y-m-d',@$_POST['bday']); }

	if($_POST['suffix']){
		$name = $_POST['lname'].", ".$_POST['fname']." ".$_POST['suffix'].", ".$_POST['mname'];	
	}else{
		$name = $_POST['lname'].", ".$_POST['fname']." ".$_POST['mname'];	
	}
	$proper_name = ucwords($name);	
	
	//$proper_name = ucwords($_POST['lname'].", ".$_POST['fname']. " ".$_POST['mname']);
	$updatebasicinfo = mysql_query("UPDATE applicant set 
		firstname='".addslashes($_POST['fname'])."',
		middlename='".addslashes($_POST['mname'])."', 
		lastname='".addslashes($_POST['lname'])."', 
		birthdate='".addslashes($bday)."', 
		gender='".addslashes($_POST['gender'])."', 
		civilstatus='".addslashes($_POST['cvstat'])."', 
		religion='".addslashes($_POST['religion'])."', 
		height='".addslashes($_POST['height'])."', 
		weight='".addslashes($_POST['weight'])."', 
		bloodtype='".addslashes($_POST['blood'])."',
		citizenship = '".addslashes(@$_POST['citizenship'])."', 
		suffix = '".addslashes($_POST['suffix'])."' 
		where app_id = '".@$_POST['empid']."' ") or die(mysql_error());
	
	mysql_query(
		"UPDATE
			employee3
		 SET
			name = '".$proper_name."'
		 WHERE
			emp_id = '".$_POST['empid']."'"
	) or die(mysql_error());
	
	if($updatebasicinfo)
	{
		logs($_SESSION['emp_id'],$_SESSION['username'],date("Y-m-d"),date("H:i:s"),'Updating the Basic Information of '.$nq->getAppName(@$_POST['empid']));	
		echo "success";
	}
}
//**************************************** UPDATE contact
else if(@$_GET['request']=="update-contact")
{
	$updatecontact = mysql_query(
						"UPDATE 
							applicant 
						 set 
							home_address ='".addslashes($_POST['homeadd'])."', 
							city_address ='".addslashes($_POST['cityadd'])."', 
							contact_person ='".addslashes($_POST['cperson'])."', 
							contact_person_address ='".addslashes($_POST['cpersonadd'])."',
							contact_person_number ='".addslashes($_POST['cpersonno'])."', 
							contactno ='".addslashes($_POST['cellno'])."', 
							telno='".addslashes($_POST['telno'])."', 
							email='".addslashes($_POST['email'])."', 
							facebookAcct ='".addslashes($_POST['fb'])."', 
							twitterAcct ='".addslashes($_POST['twitter'])."'
						 where 
							app_id = '".addslashes($_POST['empid'])."' 
					") or die(mysql_error());
	if($updatecontact)
	{
		logs($_SESSION['emp_id'],$_SESSION['username'],date("Y-m-d"),date("H:i:s"),'Updating the Contact Information of '.$nq->getAppName(@$_POST['empid']));			
		echo "success";
	}
}
//**************************************** UPDATE educ
else if(@$_GET['request']=="update-educ")
{	
	$updateeduc = mysql_query(
					"UPDATE 
						applicant 
					 set 
						attainment='".addslashes($_POST['attainment'])."', 
						school='".addslashes($_POST['school'])."', 
						course='".addslashes($_POST['course'])."' 
					 where 
						app_id = '".@$_POST['empid']."' 
				 ") or die(mysql_error());
	if($updateeduc)
	{
		logs($_SESSION['emp_id'],$_SESSION['username'],date("Y-m-d"),date("H:i:s"),'Updating the Educational Information of '.$nq->getAppName(@$_POST['empid']));	
		echo "success";
	}
}
//**************************************** UPDATE skills
else if(@$_GET['request']=="update-skills")
{	
	$updateskills = mysql_query(
						"UPDATE 
							applicant 
						 set 
							hobbies='".mysql_real_escape_string(strip_tags(($_POST['hobbies'])))."', 
							specialSkills='".mysql_real_escape_string(strip_tags(($_POST['skills'])))."' 
						 where 
							app_id = '".addslashes($_POST['empid'])."' 
					") or die(mysql_error());
	if($updateskills)
	{
		logs($_SESSION['emp_id'],$_SESSION['username'],date("Y-m-d"),date("H:i:s"),'Updating the Skills Information of '.$nq->getAppName(@$_POST['empid']));	
		echo "success";
	}
}
//**************************************** UPDATE benefits
else if(@$_GET['request']=="update-benefits")
{	

	//for pagibigrtn
	$pagibigrtn = $_POST['pagibigrtn'];	
	$recordedby = $_SESSION['username']."/".$_SESSION['emp_id']; 
	$check = mysql_query("SELECT * FROM applicant_otherdetails where app_id = '".$_POST['empid']."' ");
	if(mysql_num_rows($check)>0)
	{
		$act = "Updating the Benefits Info of";	
		$updatebenefits = mysql_query(
							"UPDATE 
								applicant_otherdetails 
							SET 
								philhealth='".addslashes($_POST['ph'])."', 
								sss_no='".addslashes($_POST['sss'])."', 
								pagibig='".addslashes($_POST['pagibig'])."',
								pagibig_tracking = '$pagibigrtn',
								tin_no = '".$_POST['tinno']."',
								recordedby = '$recordedby'								
							WHERE 
								app_id = '".addslashes($_POST['empid'])."'
						 ") or die(mysql_error());
	}
	else
	{
		$act = "Adding the Benefits Info of";
		$updatebenefits = mysql_query(
							"INSERT INTO
								applicant_otherdetails
							(no,app_id,sss_no,recordedby,pagibig_tracking,pagibig,philhealth,tin_no)
							VALUES	(no,'$_POST[empid]','".addslashes($_POST['sss'])."','$recordedby','$pagibigrtn','".addslashes($_POST['pagibig'])."','".addslashes($_POST['ph'])."','".addslashes($_POST['tinno'])."')
							") or die(mysql_error());
	}

	if($updatebenefits){
		logs($_SESSION['emp_id'],$_SESSION['username'],date("Y-m-d"),date("H:i:s"),$act." ".$nq->getAppName(@$_POST['empid']));	
		echo "success";
	}
}
//**************************************** UPDATE application history
else if(@$_GET['request']=="update-apphis")
{	
	$date = date('Y-m-d');
	$check = mysql_query("SELECT * FROM application_details where app_id = '".$_POST['empid']."' ");
	//echo $_POST['empid'];
	if($_POST['dateapplied'] == ''){  $dateapplied ='';	 }else { $dateapplied = $nq->changeDateFormat('Y-m-d',$_POST['dateapplied']);  }
	if($_POST['datehired'] == '' ){	$datehired ='';	 } else {  $datehired 	 = $nq->changeDateFormat('Y-m-d',$_POST['datehired']);      }
	if($_POST['datebriefed'] == '' ){ 	    $datebrief ='';  } else {  $datebrief 	 = $nq->changeDateFormat('Y-m-d',$_POST['datebriefed']);     }	
	if($_POST['dateexamined'] == ''){ 	 $dateexamined ='';    } else {  $dateexamined 	 = $nq->changeDateFormat('Y-m-d',$_POST['dateexamined']);    }
	
	if(mysql_num_rows($check)>0)
	{
		$act = "Updating the Application History Information of";	
		$updateapphis = mysql_query(
							"UPDATE 
								application_details 
						 	 set 
								date_applied = '".addslashes($dateapplied)."',
								date_brief = '".addslashes($datebrief)."',
								date_hired = '".addslashes($datehired)."',
								aeregular  = '".addslashes($_POST['aeregular'])."',
								date_examined = '".addslashes($dateexamined)."',
								position_applied= '".addslashes($_POST['posapplied'])."',
								exam_results = '".addslashes($_POST['examres'])."'
							 where 
								app_id = '".addslashes($_POST['empid'])."'"
						) or die(mysql_error());
	}
	else
	{
		$act = "Adding the Application History  of";
		$updateapphis = mysql_query(
				"INSERT 
					INTO 
				 application_details 
					VALUES(
						'',
						'".addslashes($_POST['empid'])."',
						'".addslashes($_POST['posapplied'])."',
						'',
						'',
						'',
						'',
						'',
						'',
						'".addslashes($dateapplied)."',
						'',
						'',
						'".addslashes($_POST['examres'])."',
						'".addslashes($dateexamined)."',
						'".addslashes($datebrief)."',
						'".addslashes($datehired)."',
						'',
						'',
						'".$date."',
						'".addslashes($_POST['aeregular'])."',
						'',
						'',
						''
					) 
				") or die(mysql_error());
	}

	if($updateapphis)
	{
		logs($_SESSION['emp_id'],$_SESSION['username'],date("Y-m-d"),date("H:i:s"),$act." ".$nq->getAppName(@$_POST['empid']));	
		echo "success";
	}
}
/*****************************************************************************************************************************************/
//add seminar
if(@$_GET['req']=="addseminar")
{
	$no = '';
	if($_POST['no'] != "")
	{
		$no = $_POST['no'];
		$edit = mysql_query("SELECT * FROM application_seminarsandeligibility where app_id = '$_POST[empid]' and no = '$_POST[no]' ");
		while($r = mysql_fetch_array($edit))
		{
			@$name = htmlspecialchars($r['name'], ENT_QUOTES);
			@$dates= htmlspecialchars($r['dates'], ENT_QUOTES);
			@$loc  = htmlspecialchars($r['location'], ENT_QUOTES);
		}
	}
	echo 
	"<table class='table table-bordered'>
		<tr>
			<td>Name</td>
			<td><input type='text' id='name' class='form-control' value='".@$name."'/></td>
		</tr>
		<tr>
			<td>Date</td>
			<td><input type='text' id='date' class='form-control' value='".@$dates."'/></td>
		</tr>	
		<tr>
			<td>Location</td>
			<td><input type='text' id='location' class='form-control' value='".@$loc."'/></td>
		</tr>
		<tr>
			<td></td>
			<td><input type='button' class='btn btn-primary btn-sm' onclick=save_seminar('".$no."') value='save'/></td>
		</tr>
	</table>";
}
if(@$_GET['req']=="save_seminar")
{	
	if($_POST['no'] == "")
	{
		$savesem = mysql_query(
					"INSERT 
						INTO 
					 application_seminarsandeligibility 
						VALUES(
							'',
							'".addslashes($_POST['empid'])."',
							'".addslashes($_POST['name'])."',
							'".addslashes($_POST['dates'])."',
							'".addslashes($_POST['location'])."',
							''
						) 
					") or die(mysql_error());
		if($savesem)
		{
			logs($_SESSION['emp_id'],$_SESSION['username'],date("Y-m-d"),date("H:i:s"),"Adding New Seminar/Eligibility/Training Information of ".$nq->getAppName(@$_POST['empid']));	
			echo "Adding Sucessful! ";
		}
		else
		{
			echo "Adding Failed! ";
		}
	}
	else
	{
		$update = mysql_query(
					"UPDATE 
						application_seminarsandeligibility 
					 SET 
						name = '".addslashes($_POST['name'])."', 
						dates= '".addslashes($_POST['dates'])."', 
						location = '".addslashes($_POST['location'])."' 
					 where 
						no = '".addslashes($_POST['no'])."' 
					 and 
						app_id = '".addslashes($_POST['empid'])."' 
				  ") or die(mysql_error());
		if($update)
		{
			logs($_SESSION['emp_id'],$_SESSION['username'],date("Y-m-d"),date("H:i:s"),"Updating Seminar/Eligibility/Training Information of ".$nq->getAppName(@$_POST['empid']));	
			echo "Updating Sucessful! ";
		}
		else
		{
			echo "Updating Failed! ";
		}
	}
}

//add character references
if(@$_GET['req']=="addcharref")
{
	$no = '';
	if($_POST['no'] != "")
	{
		$no = $_POST['no'];
		$edit = mysql_query("SELECT * FROM application_character_ref where app_id = '$_POST[empid]' and no = '$_POST[no]' ");
		while($r = mysql_fetch_array($edit))
		{
			@$name 		= htmlspecialchars($r['name'], ENT_QUOTES);			
			@$pos  		= htmlspecialchars($r['position'], ENT_QUOTES);
			@$contact 	= htmlspecialchars($r['contactno'], ENT_QUOTES);
			@$company 	= htmlspecialchars($r['company'], ENT_QUOTES);

		}
	}
	echo 
	"<table class='table table-brdered'>
		<tr>
			<td>Name</td>
			<td><input type='text' id='cname' class='form-control' value='".@$name."'/></td>
		</tr>
		<tr>
			<td>Position</td>
			<td><input type='text' id='cposition' class='form-control' value='".@$pos."'/></td>
		</tr>	
		<tr>
			<td>Contact Number</td>
			<td><input type='text' id='ccontactno' class='form-control' value='".@$contact."'/></td>
		</tr>
		<tr>
			<td>Company/Address</td>
			<td><input type='text' id='ccompany' class='form-control' value='".@$company."'/></td>
		</tr>
		<tr>
			<td></td>
			<td><input type='button' class='btn btn-primary btn-sm' onclick=save_charref('".$no."') value='save'/></td>
		</tr>
	</table>";
}

if(@$_GET['req']=="save_charref")
{		
	if($_POST['no'] == "")
	{

		$savesem = mysql_query(
					"INSERT 
						INTO 
					 application_character_ref 
						VALUES(
							'',
							'".addslashes($_POST['empid'])."',
							'".addslashes($_POST['name'])."',
							'".addslashes($_POST['position'])."',
							'".addslashes($_POST['contactno'])."',
							'".addslashes($_POST['company'])."'
						) 
					") or die(mysql_error());
		if($savesem)
		{
			logs($_SESSION['emp_id'],$_SESSION['username'],date("Y-m-d"),date("H:i:s"),"Adding new Character References Details of ".$nq->getAppName(@$_POST['empid']));	
			echo "Adding Successful";
		}	
		else
		{
			echo "Adding Failed! ";
		}
	}
	else
	{
		$update = mysql_query(
					"UPDATE 
						application_character_ref 
					 SET 
						name = '".addslashes($_POST['name'])."', 
						position= '".addslashes($_POST['position'])."', 
						contactno = '".addslashes($_POST['contactno'])."', 
						company = '".addslashes($_POST['company'])."' 
					 where 
						no = '".$_POST['no']."' 
					 and 
						app_id = '".$_POST['empid']."' 
				 ") or die(mysql_error());
		if($update)
		{
			logs($_SESSION['emp_id'],$_SESSION['username'],date("Y-m-d"),date("H:i:s"),"Updating the Character References Details of ".$nq->getAppName(@$_POST['empid']));	
			echo "Updating Sucessful! ";
		}
		else
		{
			echo "Updating Failed! ";
		}
	}
}

//add character references
if(@$_GET['req']=="addemphis")
{
	$no = '';
	if($_POST['no'] != "")
	{
		$no = $_POST['no'];
		$edit = mysql_query("SELECT * FROM application_employment_history where app_id = '$_POST[empid]' and no = '$_POST[no]' ");
		while($r = mysql_fetch_array($edit))
		{
			@$no 		= htmlspecialchars($r['no'], ENT_QUOTES);
			@$company 	= htmlspecialchars($r['company'], ENT_QUOTES);			
			@$pos  		= htmlspecialchars($r['position'], ENT_QUOTES);
			@$yr_start 	= htmlspecialchars($r['yr_start'], ENT_QUOTES);
			@$yr_ends 	= htmlspecialchars($r['yr_ends'], ENT_QUOTES);
			@$address 	= htmlspecialchars($r['address'], ENT_QUOTES);
		}
	}
	echo 
	"<table class='table table-bordered'>
		<tr>
			<td>Company</td>
			<td><input type='text' id='company' class='form-control' value='".@$company."'/></td>
		</tr>
		<tr>
			<td>Position</td>
			<td><input type='text' id='position' class='form-control' value='".@$pos."'/></td>
		</tr>	
		<tr>
			<td>Date Start</td>
			<td><input type='text' id='start' class='form-control' value='".@$yr_start."'/></td>
		</tr>
		<tr>
			<td>Date End</td>
			<td><input type='text' id='end' class='form-control' value='".@$yr_ends."'/></td>
		</tr>
		<tr>
			<td>Address</td>
			<td><input type='text' id='address' class='form-control' value='".@$address."'/></td>
		</tr>
		<tr>
			<td></td>
			<td><input type='button' class='btn btn-primary btn-sm' onclick=save_emphis('".$no."') value='save'/></td>
		</tr>
	</table>";
}

if(@$_GET['req']=="save_emphis")
{		
	if($_POST['no'] == "")
	{
		$savesemhis = mysql_query(
						"INSERT INTO 
							application_employment_history 
								VALUES(
									'',
									'".addslashes($_POST['empid'])."',
									'".addslashes($_POST['company'])."',
									'".addslashes($_POST['position'])."',
									'".addslashes($_POST['start'])."',
									'".addslashes($_POST['end'])."',
									'".addslashes($_POST['address'])."',
									''
								) 
						") or die(mysql_error());
		if($savesemhis)
		{
			logs($_SESSION['emp_id'],$_SESSION['username'],date("Y-m-d"),date("H:i:s"),"Adding new Employment History of ".$nq->getAppName(@$_POST['empid']));	
			echo "Adding Successful";
		}	
		else
		{
			echo "Adding Failed! ";
		}
	}
	else
	{
		$update = mysql_query(
					"UPDATE 
						application_employment_history 
					 SET 
						company = '".addslashes($_POST['company'])."', 
						position= '".addslashes($_POST['position'])."', 
						yr_start = '".addslashes($_POST['start'])."', 
						yr_ends = '".addslashes($_POST['end'])."', 
						address = '".addslashes($_POST['address'])."' 
					 where 
						no = '".addslashes($_POST['no'])."' 
					 and 
						app_id = '".addslashes($_POST['empid'])."' 
				 ") or die(mysql_error());
		if($update)
		{
			logs($_SESSION['emp_id'],$_SESSION['username'],date("Y-m-d"),date("H:i:s"),"Updating the Employment History of ".$nq->getAppName(@$_POST['empid']));	
			echo "Updating Sucessful! ";
		}
		else
		{
			echo "Updating Failed! ";
		}
	}
}
if(@$_GET['req']=="save_remarks")
{		
	$check = mysql_query("SELECT * from remarks where emp_id = '$_POST[empid]' ");
	if(mysql_num_rows($check) > 0)
	{
		$savesem = mysql_query("UPDATE remarks SET remarks = '".addslashes($_POST['remarks'])."' where emp_id = '$_POST[empid]' ");
	}
	else
	{	
		$savesem = mysql_query("INSERT INTO remarks VALUES('','$_POST[empid]','".addslashes($_POST['remarks'])."') ");
	}
	
	if($savesem)
	{
		logs($_SESSION['emp_id'],$_SESSION['username'],date("Y-m-d"),date("H:i:s"),"Saving remarks of ".$nq->getAppName(@$_POST['empid']));	
		echo "success";
	}
}
else if(@$_GET['request'] == "viewinterviewdetails")
{
	$empid = $_POST['empid'];
	
	echo 
	"<table width='96%' class='table-striped table-bordered'>";      
      	$sqly= mysql_query("SELECT distinct(`group`) from `application_interview_details` where `interviewee_id`= '$empid' ORDER BY `group` DESC") or die(mysql_error());
      	if(mysql_num_rows($sqly)>0){
			//if kung naay interview history
        	$sql=mysql_query("SELECT distinct(`group`) from `application_interview_details` where `interviewee_id`= '$empid' ORDER BY `group` DESC");
      	}else{
			//else kung walay interview history
        	$sql=mysql_query("SELECT distinct(`group`) from `application_interview_details_history` where `interviewee_id`='$empid' ORDER BY `group` DESC");
      	}
      	if(mysql_num_rows($sql)>0)
		{			
  	   	 	while($row=mysql_fetch_array($sql))
  	    	{
  	    		echo "
    		  	<tr bgcolor='#CCCCCC'>
					<th colspan='6'>Date Interviewed -";
    			    $sqly=mysql_query("SELECT distinct(`date_interviewed`) from `application_interview_details_history` where `group` = '".$row['group']."' ");
					$rowy = mysql_fetch_array($sqly);
					echo $nq->changeDateFormat('F d, Y',$rowy['date_interviewed']);  
    			echo "</th>
				</tr>
				<tr>    			
					<th width='13%'>Interview Code</th>
					<th width='21%'>&nbsp;&nbsp;Interviewer</th>
					<th width='6%'>&nbsp;&nbsp;Status</th>
					<th width='65%'>&nbsp;&nbsp;Remarks</th>
					
				</tr>";
    		  
    			$sqls=mysql_query("SELECT * from `application_interview_details_history` where `interviewee_id` = '$empid' and `group`= '".$row['group']."' ORDER BY interviewee_level ASC");
    			while($rows=mysql_fetch_array($sqls)){ 
            	$go=$rows['interviewee_id']."/".$rows['interviewee_level']."/".$rows['interview_code'];
      			echo 
      			"<tr>      			
      			<td>".$rows['interview_code']."</td>
      			<td>&nbsp;&nbsp;";
      			    $emp=mysql_query("SELECT distinct(`name`) from employee3 where `emp_id`= '".$rows['interviewer_id']."' ");
      			    if(mysql_num_rows($emp)){
                    	$em=mysql_fetch_array($emp);
						echo $em['name'];
                  	}else{
                    	$sql=mysql_query("SELECT name,position from users4owner where user_id = '".$rows['interviewer_id']."' ");
                    	$tab=mysql_fetch_array($sql);       
                    	echo $tab['name'];                    
                  	}
                  	echo 
                  	"</td>
      			<td>&nbsp;&nbsp;".$rows['interview_status']."</td>
      			<td > <p align='justify' style='padding:10px'>".nl2br($rows['interviewer_remarks'])."</p></td>";
/*      			<td>";
             	if($rows['interviewee_level']!=0 || $rows['interviewee_level']==''){ 
					echo "<button class='label label-primary' onClick=window.open('applicant_modal_target.php?interviewee_id2=$go','_blank')>GRADE SHEET</button>";
				}else{ 
					echo "<button class='label label-default' onClick=alert('No grade sheet for initial interviews.\nInitial interviews are not quantifiable.')>GRADE SHEET</button>"; }
      			echo "
      			</td>";*/
				echo "
            </tr>"; 
    			}
    			echo 
    		  "<tr bgcolor='#CCCCCC'>
    			<th colspan='6'></th>
    		  </tr>";
  	    	}
      	}
      	else
      	{	    
      		echo 
      		"<tr bgcolor='#CCCCCC'>
			<th colspan='6'>Date Interviewed</th>
		  </tr>
		  <tr>
			<th width='2%'>Level</th>
			<th width='5%'>Interview&nbsp;Code</th>
			<th width='32%'>&nbsp;&nbsp;Interviewer</th>
			<th width='6%'>&nbsp;&nbsp;Status</th>
			<th width='50%'>&nbsp;&nbsp;Remarks</th>
			
		  </tr>";
      }
	echo "</table>";
}
else if(@$_GET['request'] == "apphistorydetails")
{
	$empid = $_POST['empid'];
	echo 
	"<table width='96%'  class='table table-striped'>
		<tr>
			<th width='3%'><center>No</center></th>
			<th width='7%'>Date&nbsp;Accomplished</th>
			<th width='35%'>Description&nbsp;</th>
			<th width='12%'>Applying&nbsp;For&nbsp;</th>
			<th width='8%'>Status&nbsp;</th>
			<th width='17%'>Phases / Process</th>
		</tr>";
		$sql= mysql_query("SELECT * from application_history where app_id='$empid' ORDER BY no DESC");
		$x=@mysql_num_rows($sql) + 1;
		if(@mysql_num_rows($sql)>0)
		{
			while($row = mysql_fetch_array($sql))
			{
				$x--;
				echo "
				<tr>
					<td><center>".$x."</center></td>
					<td>".$nq->changeDateFormat('M. d, Y ',$row['date_time'])."</td>
					<td>".$row['description']."</td>
					<td>".$row['position']."</td>
					<td>".$row['status']."</td>
					<td>".$row['phase']."</td>
				</tr>";
			}
		}
	echo "</table>";
}
else if(@$_GET['request'] == "viewexamdet")
{
	$empid		= $_POST['empid'];
	$examval	= explode("|", $_POST['hist_exam_code']);
	$appid 		= $examval[0];
	$examcode 	= $examval[1];
	$q1 		= mysql_query("Select exam_codename From application_examtypes Where exam_code='$examcode'");
	$rw1 		= mysql_fetch_array($q1);
	$codename 	= $rw1['exam_codename']; 
	$q3 		= mysql_query("Select * From application_examtypes Where exam_code='$examcode'"); 

    echo "
	<h4>$codename</h4>
	<table width='100%' class='table table-striped'>
        <tr>
			<th width='50%'>Exam Type</th>
			<th align='center'>Score</th>
			<th align='center'>Norm</th></tr>";
		$overall = 0;
        while($r3 = mysql_fetch_array($q3))
		{
			$extype = $r3['exam_type'];            
            if($extype == "EXB")
              { $overall = 28; }
            elseif($extype == "ACCP-A" || $extype == "ACCP-B")
              { $overall = 10; }
            elseif($extype == "AIT-A")  
              { $overall = 60; }
            elseif($extype == "AIT-B")  
              { $overall = 50; }
            elseif($extype == "FIT")    
              { $overall = 12; }
            elseif($extype == "NTA" || $extype == "VAT")    
              { $overall = 25; }
            elseif($extype == "STAR" || $extype == "SACHS")     
              { $overall = 0;  }            
            else
              { $overall = 0; }

			$q2 = mysql_query("Select * From application_examdetails Where exam_ref like '%$empid' AND exam_type='$extype'");
			$retctr = 0;
			while($r2 = mysql_fetch_array($q2))
			{
				$retctr++;
				$exscore = $r2['exam_score']." / ".$overall;       
				echo"               
				<tr>
				  <td><label for='gender'>".$extype."</label>"; if($retctr>1){ echo " - $retctr(retake)";} echo "</td>
				  <td align='center'>$exscore</td>
				  <td align='center'>".$nq->getNorm($extype, $exscore)."</td>
				</tr>";         
			}
		}
		$q6 = mysql_query("Select result From application_exams2take Where app_id='$empid' AND exam_cat='$examcode'");
		$r6 = mysql_fetch_array($q6);
		
		echo "		
		<tr>
        <td>
			<label for='gender'>Exam result: &ensp;&ensp;</label>";          
			$result = $r6['result'];
            if($result=="passed"){
              echo "<label class='label label-success'>Passed</label>"; 
            }else if($result=="assessment"){
              echo "<label class='label label-information'>For Assessment</label>";
            }else if($result=="failed"){
              echo "<label class='label label-danger'>Failed</label>";
            }
        echo "  
        </td>
        <td></td>
        <td align='center'></td>
      </tr>
    </table>";
}

function logs($user,$username,$date,$time,$activity)
{
	$report_query = mysql_query(
						"INSERT 
							into 
						 logs 
							VALUES(
								'',
								'".addslashes($activity)."',
								'".addslashes($date)."',
								'".addslashes($time)."',
								'".addslashes($user)."',
								'".addslashes($username)."'
						)"
					) or die(mysql_error());
}

?>
<?php

$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$endtime = $mtime;
$totaltime = ($endtime - $starttime); 
//echo "<i style='color:gray'>".$totaltime."</i>";
//echo $totaltime;
?>