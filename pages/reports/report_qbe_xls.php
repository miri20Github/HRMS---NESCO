<?php
//session_start();
//sorted by company, businessunit,department,name
$date2d = date("Y-m-d H:i:s");
include("../../../connection.php");

	if(isset($_POST['submit']))
	{	
		$filename = 'qbe-'.str_replace(" ","_", @$_POST['filename']);	
		header("Cache-Control: public"); 
		header("Content-Type: application/octet-stream");
		header( "Content-Type: application/vnd.ms-excel; charset=utf-8" );
		header( "Content-disposition: attachment; filename=".$filename.".xls");
	
		$ff = "";	
		$f = @$_POST['check'];	
		
		for($i=0;$i<count($f);$i++)
		{					
			if($i<count($f)-1)
			{						
				$ff = $ff.$f[$i].",";								
			}else{
				$ff = $ff.$f[$i];						
			}		
		}
		//check if date hired is checked in the fieldnames
		$datehired		= @$_POST['datehiredcb'];		
		
		//check if age is checked in the fieldnames
		$empage 		= @$_POST['agecb'];

		//check if cedula is checked in the fieldnames
		$cedula 		= @$_POST['cedulacb'];

		//get age function
		date_default_timezone_set('Asia/Manila');
		function getAge( $dob, $tdate )
		{
			$age = 0;
			while( $tdate >= $dob = strtotime('+1 year', $dob)){
					++$age;
			}return $age;
		}
		
		$ch 		= @$_POST['check1'];
		$ch_array 	= array();	
			
		for($i=0;$i<count($ch);$i++)
		{		
			$ch_array[$i] = $ch[$i];	
		}	
		
		//main queries
		$name 			= @$_POST['nname'];
		$homeaddress 	= @$_POST['nhome_address'];
		$religion		= @$_POST['nreligion'];
		$gender			= @$_POST['ngender'];
		$civilstatus 	= @$_POST['ncivilstatus'];
		$attainment 	= @$_POST['nattainment'];
		$school			= @$_POST['nschool'];
		$course			= @$_POST['ncourse'];
		$height 		= @$_POST['nheight'];
		$weight			= @$_POST['nweight'];
		$bloodtype 		= @$_POST['nbloodtype'];
		$position 		= @$_POST['nposition'];
		$emptype 		= @$_POST['emp_type'];
		$lodging		= @$_POST['lodging'];
		$contactno		= @$_POST['ncontactno'];
		
		//other details
		$cc 			= @$_POST['comp_code'];
		$bc 			= @$_POST['bunit_code'];
		$dc 			= @$_POST['dept_code'];
		$sc 			= @$_POST['sec_code'];
		$ssc 			= @$_POST['ssec_code'];
		$uc 			= @$_POST['unit_code'];
		$title 			= @$_POST['report_title'];
		$currentstatus 	= @$_POST['current_status'];
						
		if($uc !=''){ $code = $uc; }
		else if($ssc !=''){ $code = $ssc; }
		else if($sc !=''){ $code = $sc; }
		else if($dc !=''){ $code = $dc; }
		else if($bc !=''){ $code = $bc; }
		else if($cc !=''){ $code = $cc;}
		
		$ec	 	= explode("/",$code);
		$cc	   	= @$ec[0];
		$bc		= @$ec[1];
		$dc		= @$ec[2];
		$sc		= @$ec[3];
		$ssc	= @$ec[4];
		$uc		= @$ec[5];

		if($uc !="") $location 		= "and company_code='$cc' and bunit_code='$bc' and dept_code='$dc' and section_code='$sc' and sub_section_code='$ssc' and unit_code='$uc'";
		else if($ssc !="") $location= "and company_code='$cc' and bunit_code='$bc' and dept_code='$dc' and section_code='$sc' and sub_section_code='$ssc'";
		else if($sc !="") $location = "and company_code='$cc' and bunit_code='$bc' and dept_code='$dc' and section_code='$sc'";
		else if($dc !="") $location = "and company_code='$cc' and bunit_code='$bc' and dept_code='$dc'";
		else if($bc !="") $location = "and company_code='$cc' and bunit_code='$bc'";
		else if($cc !="") $location = "and company_code='$cc'";

		//names
		//remove the special characters
		$str 	= preg_replace('/[^A-Za-z0-9\. -]/', '', mysql_real_escape_string(trim($name))); 
		//put spaces in the first and last part of the string
		$str 	= " ".$str." ";	
		$str 	= preg_replace('/  */', '%', $str);

		if($name)		{	$nm 	= "and name like '%$str%' "; 					$nmquery	= "name like '$str'; "; }
		if($homeaddress){ 	$hm 	= "and home_address like '%$homeaddress%'"; 	$hmquery 	= "home address like '$homeaddress'; "; }
		if($religion)	{ 	$rel 	= "and religion like '%$religion%' "; 			$relquery 	= "religion like '$religion'; "; }
		if($gender)		{ 	$gen 	= "and gender = '$gender' "; 					$genquery 	= "gender = '$gender'; ";}
		if($civilstatus){ 	$cv 	= "and civilstatus = '$civilstatus' ";  		$cvquery	= "civilstatus = '$civilstatus'; "; }
		if($attainment)	{ 	$attain = "and attainment like '%$attainment%' ";  		$attainquery= "attainment like '$attainment';  "; }
		if($school) { 		$sch 	= "and school like '%$school%' "; 				$schquery 	= "school like '$school';  ";}
		if($course) { 		$cours 	= "and course like '%$course%' "; 				$coursquery	= "course like '$course'; ";}
		if($height) { 		$hei 	= "and height like '%$height%' "; 				$heiquery 	= "height like '$height'; ";}
		if($weight) { 		$wei 	= "and weight like '%$weight%' "; 				$weiquery 	= "weight like '$weight'; "; }
		if($bloodtype) { 	$bt 	= "and bloodtype = '$bloodtype' ";				$btquery 	= "bloodtype = '$bloodtype'; "; }
		if($position)  { 	$pos 	= "and position like '%$position%' "; 			$posquery 	= "position like '$position'; "; }	
		if($lodging){		$lod	= "and lodging = '$lodging'";					$lodquery	= "lodging = '$lodging' "; }
		if($contactno){		$cno	= "and contactno like '%$contactno%'";			$contactnoquery	= "contactno like '%$contactno%'"; }
		
		if($emptype == ""){
			$type 	= "and (emp_type = 'NESCO' or emp_type = 'NESCO Contractual' or emp_type = 'NESCO-PTA' or emp_type = 'NESCO-PTP' or emp_type = 'NESCO Regular' or emp_type='NESCO Regular Partimer' or emp_type = 'NESCO Probationary')"; 
			$typequery 	= "and (emp_type = 'NESCO' or emp_type = 'NESCO Contractual' or emp_type = 'NESCO-PTA' or emp_type = 'NESCO-PTP' or emp_type = 'NESCO Regular' or emp_type='NESCO Regular Partimer' or emp_type = 'NESCO Probationary')"; 
		}else{
			if($emptype){ 		$type 	= "and emp_type = '$emptype' "; 			$typequery 	= "emp_type = '$emptype' "; }
		}
		
		if($currentstatus){ $csquery 	= "current_status = '$currentstatus' "; }

		$details	= @$nm." ".@$hm." ".@$rel." ".@$gen." ".@$cv." ".@$attain." ".@$sch." ".@$cours." ".@$hei." ".@$wei." ".@$bt." ".@$pos." ".@$type." ".@$lod." ".@$cno;
		$condition 	= @$nmquery." ".@$hmquery.@$relquery.@$genquery.@$cvquery.@$attainquery.@$schquery.@$coursquery.@$heiquery.@$weiquery.@$btquery.@$posquery.@$csquery.@$typequery.@$lodquery.@$contactnoquery;
			
		if($ff == ""){
			$select="emp_id,payroll_no,barcodeId,emp_no,firstname,middlename,lastname,birthdate,position, company_code, bunit_code, dept_code, section_code,sub_section_code";
		}else{ 
			$select = "emp_id,payroll_no,barcodeId,emp_no,firstname,middlename,lastname,birthdate,position,company_code, bunit_code, dept_code, section_code,sub_section_code,".$ff;
		}		
						
		//required
		if($currentstatus){ $cs = "current_status = '$currentstatus' "; $cs = "current_status = '$currentstatus' "; }

		//date details from and to
		//if($_POST['asof'] !=""){ $where_date = "and startdate <= '".$_POST['asof']."' "; }else { $where_date = ""; }			

		
		/************************************************************************/
			//other details	
			echo "<br>";			
			echo "<i>Date Generated : ".date('F d, Y H:i:s A')."</i><br>";
			echo "<i>Generated Thru : HRMS - NESCO</i><br>";
			echo "<i>Generated by : ".@$_SESSION['name']."</i><br>";	
			echo "<i>Report Title : QUERY BY EXAMPLE :".$_POST['report_title']."</i><br><br>";
			echo "QUERY: ".$condition."<br>";	
			echo "<br>";
		/************************************************************************/	
	
		//query
		mysql_query("SET NAMES utf8"); 
		$query = mysql_query("SELECT $select from employee3 inner join applicant on applicant.app_id = employee3.emp_id where ".$cs." ".@$location."  ".@$details." order by company_code,bunit_code, dept_code, section_code, name  ");	
			
			echo "<table border='1' style='font-size:12px'>";
			//table header
			echo "<tr>";
				echo "<td align='center'><b>Emp No.</b></td>";
				if(@$_SESSION['emp_id'] == '03399-2013' || @$_SESSION['emp_id'] == '01022-2014'){
					echo "<td align='center'><b>PAYROLL NO</b></td>";
					echo "<td align='center'><b>BARCODE ID</b></td>";
					echo "<td align='center'><b>EMPNO</b></td>";
				}
				echo "<td align='center'><b>FirstName</b></td>";
				echo "<td align='center'><b>MiddleName</b></td>";
				echo "<td align='center'><b>LastName</b></td>";
				echo "<td align='center'><b>Company</b></td>";	
				echo "<td align='center'><b>Business Unit</b></td>";	
				echo "<td align='center'><b>Department</b></td>";
				echo "<td align='center'><b>Section</b></td>";
				echo "<td align='center'><b>SubSection</b></td>";
				echo "<td align='center'><b>Position</b></td>";
				
				if($datehired != ''){
					echo "<td align='center'><b>Date Hired</b></td>";
				}
				
				if($empage !=""){					
					echo "<td align='center'><b>Age</b></td>";	
				}
				$fn = explode(",",$ff);		
				for($i=0;$i<=count($fn) - 1;$i++)
				{
					if($fn[$i] == "civilstatus"){
						echo "<td align='center'><b>Civil Status</b></td>";	
					}else if($fn[$i] == "home_address"){
						echo "<td align='center'><b>Home Address</b></td>";											
					}else if($fn[$i] == " "){

					}else{
						echo "<td align='center'><b>".ucwords(strtolower($fn[$i]))."</b></td>";										
					}
				}
				if(!empty($cedula)){
					echo "<td>Cedula</td>";
				}		
			echo "</tr>";
		
			$countDisplay = array();	
						
			while($row = mysql_fetch_array($query))
			{		
				if($empage != "")
				{					
					$datebirth = $row['birthdate'];/*** a date before 1970 ***/		
					$dob = strtotime($datebirth);		
					$now = date('Y-m-d');/*** another date ***/		
					$tdate = strtotime($now);/*** show the date ***/		
					$age= getAge( $dob, $tdate );
					//if($row['birthdate']!=""){ $age = $age; }   	 
					if($datebirth !=""){ $age = $age; }    
				}

				
				if(!in_array($row['emp_id'], $countDisplay)) // checks if the emp_id is already in the array $countDisplay
				{
					array_push($countDisplay,$row['emp_id']); //save and pushes the emp_id to the array $countDisplay							

					//checks if the current_status id eoc but still having an active status
					if($currentstatus == 'End of Contract'){
						$query_check = mysql_query("Select emp_id, name from employee3 where emp_id = '$row[emp_id]' and current_status = 'active' ");
						$cqc = mysql_num_rows($query_check);				
					}else{
						$cqc=0;
					}

					if(!$cqc)
					{	
						$fn = explode(",",$ff);
						echo "<tr>";	
						echo "<td>".$row['emp_id']."</td>";	
					if(@$_SESSION['emp_id'] == '03399-2013' || @$_SESSION['emp_id'] == '01022-2014'){
							echo "<td align='center'><b>".$row['payroll_no']."</b></td>";
							echo "<td align='center'><b>".$row['barcodeId']."</b></td>";
							echo "<td align='center'><b>".$row['emp_no']."</b></td>";
						}		
						echo "<td>".mb_convert_encoding(ucwords(strtolower($row['firstname'])), 'UCS-2LE', 'UTF-8')."</td>";	
						echo "<td>".mb_convert_encoding(ucwords(strtolower($row['middlename'])), 'UCS-2LE', 'UTF-8')."</td>";	
						echo "<td>".mb_convert_encoding(ucwords(strtolower($row['lastname'])), 'UCS-2LE', 'UTF-8')."</td>";							
						echo "<td>".$nq->getCompanyAcroName($row['company_code'])."</td>";							
						echo "<td>".$nq->getBusinessUnitName($row['bunit_code'],$row['company_code'])."</td>";	
						echo "<td>".$nq->getDepartmentName($row['dept_code'],$row['bunit_code'],$row['company_code'])."</td>";	
						echo "<td>".$nq->getSectionName($row['section_code'],$row['dept_code'],$row['bunit_code'],$row['company_code'])."</td>";
						echo "<td>".$nq->getSubSectionName($row['sub_section_code'],$row['section_code'],$row['dept_code'],$row['bunit_code'],$row['company_code'])."</td>";	
						echo "<td>".$row['position']."</td>";	
						
						
						if($datehired != ''){
							//$nq->changeDateFormat('m/d/Y',$dh)
							$dh = $nq->getOneField('date_hired','application_details'," app_id = '$row[emp_id]' ");
							//if($dh == '0001-11-30' || $dh == '0000-00-00' || $dh == '')
							//	echo "<td></td>";
							//else
								echo "<td>".$dh."</td>";
						}
						
						if($empage != ""){
							if($row['birthdate'] =='' || $row['birthdate'] =='0000-00-00'){
								echo "<td></td>";	
							}else{								
								echo "<td>".$age."</td>";	
							}
						}
						for($i=0;$i<count($fn);$i++)
						{		
							if($fn[$i] == ""){						
								echo "";
							}else{
								echo "<td>".mb_convert_encoding($row[$fn[$i]], 'UCS-2LE', 'UTF-8')."</td>";		
							}			
						}

						if(!empty($cedula)){

							$cedulaQuery = mysql_query("SELECT `cedula_no` FROM `applicant_otherdetails` WHERE `app_id` = '$row[emp_id]' ORDER BY no DESC") or die(mysql_error());
								$cedulaFetch = mysql_fetch_array($cedulaQuery);
								$cedula_no = $cedulaFetch['cedula_no'];

								echo "<td>$cedula_no</td>";
						}
					}		

					echo "</tr>";
			 	}
			}
			echo "</table>";

		if(mysql_num_rows($query) < 1 ){
			echo "<br><h3>No Result Found!</h3>";
		}
	}
	//logs
	$date = date("Y-m-d");
	$time = date("H:i:s");     
	$qw   = $nq->savelogs("Generated QBE - Report title: ".@$_POST['report_title'],$date,$time,@$_SESSION['emp_id'],@$_SESSION['username']);
?>