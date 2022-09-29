<?php
include('../../../connection.php');
require_once("../../get_contract_duration.php");  
$contract = new get_contract_duration();

$filename = str_replace(" ","_", @$_GET['filename']).date('Ymd');	
header("Cache-Control: public"); 
header("Content-Type: application/octet-stream");
header( "Content-Type: application/vnd.ms-excel; charset=utf-8" );
header( "Content-disposition: attachment; filename=".$filename.".xls");

function table_header($fields,$title,$subtitle)
{
	if($subtitle != ''){ $subtitle = $subtitle."<br>";} else { $subtitle = '';}
	$t = "<center><b style='font-size:18px'>$title</b><BR> $subtitle Date: ".date("M d, Y")."</center><br>	
		<table border='1' style='font-size:12px'> <tr>";
		
	for($i=0; $i<count($fields); $i++){
		$t .= "<td><b>$fields[$i]</b></td>";
	}
	$t .= "</tr>";	
	return $t;
}

function table_tr($fields)
{
	$t ='';
	echo "<tr>";	
		for($i=0; $i<count($fields); $i++){
			$t .= "<td><b>$fields[$i]</b></td>";
		}
	$t .= "</tr>";	
	return $t;
}

/************************ GET COMPANY DETAILS */
$employeetypee = "and emp_type IN ('NESCO Contractual','NESCO-PTA','NESCO-PTP','NESCO Regular','NESCO Regular Partimer','NESCO Probationary')";

$code 	= $_GET['code'];
$ec	 	= explode("/",$code);
$cc	   	= @$ec[0];
$bc		= @$ec[1];
$dc		= @$ec[2];
$sc		= @$ec[3];
$ssc	= @$ec[4];
$uc		= @$ec[5];

if($cc != '')
{		
	if($uc != ''){		@$loc = "company_code = '$cc' and bunit_code = '$bc' and dept_code = '$dc' and section_code = '$sc' and sub_section_code = '$ssc' and unit_code = '$uc' "; }
	else if($ssc !=''){	@$loc = "company_code = '$cc' and bunit_code = '$bc' and dept_code = '$dc' and section_code = '$sc' and sub_section_code = '$ssc' "; }
	else if($sc !=''){	@$loc = "company_code = '$cc' and bunit_code = '$bc' and dept_code = '$dc' and section_code = '$sc' ";  }
	else if($dc !=''){	@$loc = "company_code = '$cc' and bunit_code = '$bc' and dept_code = '$dc' ";  }
	else if($bc !=''){  @$loc = "company_code = '$cc' and bunit_code = '$bc' "; }    
	else if($cc !=''){  @$loc = "company_code = '$cc'"; }
}
/************************ GET COMPANY DETAILS */
$rcom	= $nq->getCompanyName($cc); 					//getting the company name
$rbunit	= $nq->getBusinessUnitName($bc, $cc); 			//getting the business unit name
$rdept	= $nq->getDepartmentName($dc,$bc,$cc); 			//getting the department name
$rsec   = $nq->getSectionName($sc,$dc,$bc,$cc); 		//getting the section name
$rsub   = $nq->getSubSectionName($ssc,$sc,$dc,$bc,$cc);	//getting the sub section name
$runit  = $nq->getUnitName($uc,$ssc,$sc,$dc,$bc,$cc);	//getting the unit name

if($uc != ""){ $designation = $rbunit." - ".$rdept." - ".$rsec." - ".$rsub." - ".$runit; }
else if($ssc  != ""){  $designation = $rbunit." - ".$rdept." - ".$rsec." - ".$rsub;}
else if($sc  != ""){ $designation = $rbunit." - ".$rdept." - ".$rsec; }
else if($dc != ""){ $designation = $rbunit." - ".$rdept; }
else if($bc != ""){ $designation = $rbunit;}
else { $designation = ""; }	
		
$report = $_GET['rname'];

/*****USERNAME REPORT*****/
if($report == 'username-report')
{	
	$query	= mysql_query("SELECT users.username, employee3.emp_id, employee3.emp_type, employee3.current_status, employee3.name, employee3.position, employee3.company_code, employee3.bunit_code, employee3.dept_code, employee3.section_code
						FROM employee3 inner join users on employee3.emp_id = users.emp_id  
						WHERE current_status = 'Active' and $loc $employeetypee 
						ORDER BY company_code, bunit_code, dept_code, `name` ") or die(mysql_error());	
	$ctr  = 0;
	$fields = array('NO','USERNAME','EMPID','NAME','POSITION','EMPTYPE','CURRENTSTATUS','BUSINESS UNIT','DEPARTMENT','SECTION');
	echo table_header($fields,"USERNAME REPORT",'');
	while($r = mysql_fetch_array($query)){
		$ctr++;
		echo "<tr>
			<td>$ctr</td>
			<td>$r[username]</td>
			<td>$r[emp_id]</td>			
			<td>".ucwords(strtoupper($r['name']))."</td>
			<td>$r[position]</td>
			<td>$r[emp_type]</td>
			<td>$r[current_status]</td>
			<td>".$nq->getBusinessUnitName($r['bunit_code'],$r['company_code'])."</td>	
			<td>".$nq->getDepartmentName($r['dept_code'],$r['bunit_code'],$r['company_code'])."</td>	
			<td>".$nq->getSectionName($r['section_code'],$r['dept_code'],$r['bunit_code'],$r['company_code'])."</td>
		</tr>";
	}
	echo "</table>";
}
else if($report == 'bday-report')
{
	$mode = $_GET['mode'];
	if($mode =='1')
	{
		echo "<center><b>".$rcom."</b><br><b>".$designation."</b><br><i> &nbsp; ".date('F d, Y H:i:s A')."</i></center>
				<br><table border='1' style='font-size:12px'>";	

		for($i=1;$i<=12;$i++)
		{
			if($i>=10 && $i<=12){
				$mo = $i;
			}else{
				$mo = "0".$i; 
			}

			$que = mysql_query("SELECT emp_id, name, position, emp_type, current_status, birthdate 
				FROM employee3 INNER JOIN applicant ON applicant.app_id = employee3.emp_id 
				WHERE $loc $employeetypee and current_status = 'Active' and birthdate like '%-$mo-%' 
				ORDER BY MONTH(birthdate), DAY(birthdate) ");

			$bday = $nq->bdayreport($mo);

			echo "<tr bgcolor='green'><td colspan='6'> <center><b>$bday</b ></center> </td></tr>";		
			$fields = array('EMPID','NAME','POSITION','EMPTYPE','BIRTHDAY','AGE');			
			echo table_tr($fields);

			while($row = mysql_fetch_array($que))
			{	 
				$age = $nq->getAge($row['birthdate']);
				echo "<tr>			
					<td>".$row['emp_id']."</td>
					<td>".ucwords(strtoupper($row['name']))."</td>
					<td>".ucwords(strtolower($row['position']))."</td>
					<td>".$row['emp_type']."</td>
					<td>".$nq->changeDateFormat('m/d/Y',$row['birthdate'])."</td>
					<td>".$age."</td>
				</tr>";			
			}	
		}
		echo "</table>";
	}
	else if($mode =='2')
	{ 
		//*** second option
		$bmonth	= @$_GET['bmonth'];
		switch($bmonth){
			case "01": $bday = "January Birthday Celebrants";break;
			case "02": $bday = "February Birthday Celebrants";break;
			case "03": $bday = "March Birthday Celebrants";break;
			case "04": $bday = "April Birthday Celebrants";break;
			case "05": $bday = "May Birthday Celebrants";break;
			case "06": $bday = "June Birthday Celebrants";break;
			case "07": $bday = "July Birthday Celebrants";break; 
			case "08": $bday = "August Birthday Celebrants";break;
			case "09": $bday = "September Birthday Celebrants";break;
			case "10": $bday = "October Birthday Celebrants";break;
			case "11": $bday = "November Birthday Celebrants";break;
			case "12": $bday = "December Birthday Celebrants";break;
			case "all": $bday = "All Birthday Celebrants"; break;
		}

		$que = mysql_query("SELECT emp_id, name, position, current_status, emp_type, birthdate 
		FROM employee3 INNER JOIN applicant ON applicant.app_id = employee3.emp_id 
		WHERE current_status = 'Active' and $loc $employeetypee order by name, birthdate");

		$title 	="<center>".$rcom."<br>".$bday."<br>".$designation."</center>";
		$fields = array('NO','EMPID','NAME','POSITION','EMPTYPE','BIRTHDAY','AGE');			
		echo table_header($fields,$title,'');
		
		//displaying
		$ctr = 0;
		while($row = mysql_fetch_array($que))
		{	
			$bd  = explode('-',$row['birthdate']); 	
			$b 	 = @$bd[1];	
			$year= @$bd[0];	
			$age = date('Y') - $year;	

			if($bmonth == "all")
			{		
				$ctr++;	
				echo "<tr>
				<td>".$ctr."</td>
				<td>".$row['emp_id']."</td>
				<td>".ucwords(strtoupper($row['name']))."</td>
				<td>".ucwords(strtolower($row['position']))."</td>
				<td>".$row['emp_type']."</td>
				<td>".$nq->changeDateFormat('m/d/Y',$row['birthdate'])."</td>
				<td>".$age."</td></tr>";	
			}	
			else if($bmonth == @$b)
			{	
				$ctr++;	
				echo "<tr>
				<td>".$ctr."</td>
				<td>".$row['emp_id']."</td>
				<td>".ucwords(strtoupper($row['name']))."</td>
				<td>".ucwords(strtolower($row['position']))."</td>
				<td>".$row['emp_type']."</td>
				<td>".$nq->changeDateFormat('m/d/Y',$row['birthdate'])."</td>
				<td>".$age."</td></tr>";
			}
		}	
		echo "</table>";		
	}
}
else if($report == 'status-report')
{
	$ctr = 0;
	$condition = $loc. "and current_status = '".$_GET['stat']."' ";
	if($_GET['etype'] == 'All'){ 
		$condition .= $employeetypee; } 
	else { $condition .= "and emp_type = '$etype' "; }   

	$query 	= mysql_query("SELECT emp_id, name, position, emp_type, current_status, company_code, bunit_code, dept_code, section_code 
	FROM employee3 where $condition order by name") or die(mysql_error());
	
	$fields = array('NO','NAME','POSITION','EMPLOYEE TYPE','CURRENTSTATUS','COMPANY','BUSINESS UNIT','DEPARTMENT','SECTION','DATEHIRED');//, 'DATE HIRED');			
	echo table_header($fields,"EMPLOYEE STATUS REPORT",'' );
	
	while($r = mysql_fetch_array($query))
	{	
		$ctr++;
		$dh = $nq->getOnefield('date_hired','application_details',"app_id='".$r['emp_id']."' ");
		echo 
		"<tr>
			<td>$ctr</td>
			<td>$r[name]</td>
			<td>$r[position]</td>
			<td>$r[emp_type]</td>
			<td>$r[current_status]</td>
			<td>".$nq->getCompanyAcroName($r['company_code'])."</td>	
			<td>".$nq->getBusinessUnitName($r['bunit_code'],$r['company_code'])."</td>	
			<td>".$nq->getDepartmentName($r['dept_code'],$r['bunit_code'],$r['company_code'])."</td>	
			<td>".$nq->getSectionName($r['section_code'],$r['dept_code'],$r['bunit_code'],$r['company_code'])."</td>
			<td>$dh</td>
		</tr>";
	}
	echo "</table>";	
}
else if($report =='statistics-report')
{
 	$showsections = @$_GET['showsections'];
	$showsubsections = @$_GET['showsubsections'];
	?>
	<center><b>ACTIVE EMPLOYEE STATISTICS REPORT <br> <?php echo $nq->getCompanyName($cc);?></B>
	<br> <?php echo "Date:".date('M d, Y');?></center><br>
				
						
						<?php	
						 //echo "<i style='color:green'>Note: Please take note that every partime count as 0.5 </i>.";	
						/*************************************************/
						$select = "SELECT count(emp_id) from employee3 where ";
						$status = "current_status = 'active'";
					
						/*************************************************/
						if($dc !=''){
							$dept = $nq->getDepartment($dc,$bc,$cc);
						}else{
							$dept = $nq->getAllDepartment($bc,$cc);
						}				

						if($bc !=''){ 
							$businessunit = $nq->getBusinessUnit($bc,$cc);
						}else{ $businessunit = $nq->getAllBusinessUnit($cc); 
						}
						/***************** C O M P A N Y ***************************/
						$com = $nq->getCompany($cc);
						echo "
						<div class='table-responsive'>
						<table id='companystructure' width='100%'  border='1' style='font-size:12px' >							
							<tr>
								<th rowspan='4' width='200'>BU</th>
								<th rowspan='4' width='200'>DEPT</th>";
								if($showsections == '1'){ //if ge click ang show sections
									echo "<th rowspan='4' width='200'>SECTION</th>";
								}

								if($showsubsections == '1'){ //if ge click ang show sections								
									echo "<th rowspan='4' width='200'>SUBSECTION</th>";
								}
								echo "								
								<th colspan='7'><center>NESCO EMPLOYEE</center></th>
							</tr>
							<tr>
								<th colspan='7'><center>EMPLOYMENT STATUS</center></th>	
							</tr>
							<tr>
								<th rowspan='2' width='40'>CAS</th>										
								<th colspan='2' width='80'><center>REG</center> </th>								
								<th rowspan='2' width='40'>PTA</th>
								<th rowspan='2' width='40'>PTP</th>
								<th rowspan='2' width='40'>Probi</th>
								<th rowspan='2' width='40'>TOTAL<br>NESCO</th>	 								
							</tr>								
								<th width='4px'>Reg</th>	
								<th width='4px'>RegPT</th>";

						while($rc = mysql_fetch_array($com))
						{			
							/***************** B U S I N E S S   U N I T ***************************/	
							//$businessunit = $nq->getBusinessUnit($bc,$cc);	
							while($rb = mysql_fetch_array($businessunit))
							{
								//count business unit
									$count_bc_cc = mysql_query("$select $status $employeetypee and company_code = '$cc' and bunit_code = '$rb[bunit_code]' and (emp_type != 'TAMBLOT' && emp_type != 'NICO' && emp_type !='CYDEM' && emp_type !='NEMPEX' ) ");// or die(mysql_error());
									$r1 = mysql_fetch_array($count_bc_cc);
									$bc_count = $r1['count(emp_id)'];	
										
									$count_bc_cc_pt = mysql_query("$select $status $employeetypee and company_code = '$cc' and bunit_code = '$rb[bunit_code]' ")or die(mysql_error());
									$r_pt = mysql_fetch_array($count_bc_cc_pt);
									$bc_count_pt = $r_pt['count(emp_id)'];
									$bc_count_pt = $bc_count_pt * 0.5; 									
									$tot_c 	= $bc_count + $bc_count_pt;									
									
								//count dept								
									$code = $cc.'/'.$rb['bunit_code'];//$bc;									
									$url = 	"statistics_details_report.php?code=$code";
									$loca = "company_code = '$cc' and bunit_code = '$rb[bunit_code]' ";									
									$total_nesco = $nq->getCount($select,$loca,'NESCO')+ $nq->getCount($select,$loca,'NESCO Regular')+$nq->getCount($select,$loca,'NESCO Regular Partimer')+$nq->getCount($select,$loca,'NESCO-PTA')+$nq->getCount($select,$loca,'NESCO-PTP')+$nq->getCount($select,$loca,'NESCO Probationary');
								
								echo 
								"<tr style='background-color:#ccc'> 									
									<td>".$rb['business_unit']."</td> 
									<td></td>";
									if($showsections == '1'){ //if ge click ang show sections									
										echo "<td></td>";
									}
									if($showsubsections == '1'){ //if ge click ang show sections									
										echo "<td></td>";
									}
									echo "									
									<td>".$nq->getCount($select,$loca,'NESCO')."</td>
									<td>".$nq->getCount($select,$loca,'NESCO Regular')."</td>
									<td>".$nq->getCount($select,$loca,'NESCO Regular Partimer')."</td>
									<td>".$nq->getCount($select,$loca,'NESCO-PTA')."</td>
									<td>".$nq->getCount($select,$loca,'NESCO-PTP')."</td>
									<td>".$nq->getCount($select,$loca,'NESCO Probationary')."</td>
									<td>$total_nesco</td>
								</tr>";

								/***************** D E P A R T M E N T ***************************/		
								//$dept = $nq->getAllDepartment($bc,$cc);
								while($rd = mysql_fetch_array($dept))
								{
									//count dept
									$count_dc = mysql_query("$select $status $employeetypee and company_code = '$cc' and bunit_code = '$bc' and dept_code ='$rd[dept_code]' and (emp_type != 'TAMBLOT' && emp_type != 'NICO' && emp_type !='CYDEM' ) ");
									$r2 = mysql_fetch_array($count_dc);	
									$dc_count = $r2['count(emp_id)'];
																					
									$count_dc_pt = mysql_query("$select $status $employeetypee and company_code = '$cc' and bunit_code = '$rb[bunit_code]' and dept_code ='$rd[dept_code]' ")or die(mysql_error());
									$r1_pt = mysql_fetch_array($count_dc_pt);
									$dc_count_pt = $r1_pt['count(emp_id)'];
									$dc_count_pt = $dc_count_pt * 0.5; 									
									$tot_c 	= $dc_count + $dc_count_pt;									
									//------------------------------------------------------------------------------------																	
									//variance sa dept								
									$code = $cc.'/'.$bc.'/'.$rd['dept_code'];
									$url = 	"statistics_details_report.php?code=$code";
									$loca = "company_code = '$cc' and bunit_code = '$bc' and dept_code ='$rd[dept_code]' ";
									$total_nesco = $nq->getCount($select,$loca,'NESCO')+ $nq->getCount($select,$loca,'NESCO Regular')+$nq->getCount($select,$loca,'NESCO Regular Partimer')+$nq->getCount($select,$loca,'NESCO-PTA')+$nq->getCount($select,$loca,'NESCO-PTP')+$nq->getCount($select,$loca,'NESCO Probationary');
									
									echo 
									"<tr > 										
										<td></td>
										<td>".$rd['dept_name']."</td>";
										if($showsections == '1'){ //if ge click ang show sections
											echo "<td></td>";
										}
										if($showsubsections == '1'){ //if ge click ang show sections
											echo "<td></td>";
										} 
										echo "
										<td>".$nq->getCount($select,$loca,'NESCO')."</td>
										<td>".$nq->getCount($select,$loca,'NESCO Regular')."</td>
										<td>".$nq->getCount($select,$loca,'NESCO Regular Partimer')."</td>
										<td>".$nq->getCount($select,$loca,'NESCO-PTA')."</td>
										<td>".$nq->getCount($select,$loca,'NESCO-PTP')."</td>
										<td>".$nq->getCount($select,$loca,'NESCO Probationary')."</td>
										<td>$total_nesco</td>
									</tr>";
									
									/***************** S E C T I O N ***************************/	
									if(@$sc != ''){
										$section = $nq->getSection($sc,$rd['dept_code'],$rb['bunit_code'],$rc['company_code']);
									}else{
										$section = $nq->getAllSection($rd['dept_code'],$rb['bunit_code'],$rc['company_code']);			
									}
										
									if($showsections == '1') //if ge click ang show sections
									{
									
									while($rs = mysql_fetch_array($section))
									{										
										//count section
										$count_sc = mysql_query("$select $status $employeetypee and company_code = '$cc' and bunit_code = '$bc' and dept_code='$rd[dept_code]'  
										and section_code ='$rs[section_code]' and current_status = 'active'  and (emp_type != 'TAMBLOT' && emp_type != 'NICO' && emp_type !='CYDEM')")or die(mysql_error());
										$r3 = mysql_fetch_array($count_sc);	
										$sc_count = $r3['count(emp_id)'];
										
										//variance sa dept
										$count_sc_pt = mysql_query("$select $status $employeetypee and company_code = '$cc' and bunit_code = '$rb[bunit_code]' and dept_code='$rd[dept_code]'  and section_code = '$rs[section_code]' ")or die(mysql_error());
										$r3_pt = mysql_fetch_array($count_sc_pt);
										$sc_count_pt = $r3_pt['count(emp_id)'];
										$sc_count_pt = $sc_count_pt * 0.5; 									
										$tot_c 	= $sc_count + $sc_count_pt;										
										
										$code = $cc.'/'.$bc.'/'.$rd['dept_code'].'/'.$rs['section_code'];										
										$url = 	"statistics_details_report.php?code=$code";
										$loca = "company_code = '$cc' and bunit_code = '$bc' and dept_code='$rd[dept_code]' and section_code ='$rs[section_code]' ";										
										$total_nesco = $nq->getCount($select,$loca,'NESCO')+ $nq->getCount($select,$loca,'NESCO Regular')+$nq->getCount($select,$loca,'NESCO Regular Partimer')+$nq->getCount($select,$loca,'NESCO-PTA')+$nq->getCount($select,$loca,'NESCO-PTP')+$nq->getCount($select,$loca,'NESCO Probationary');								
									
										echo 
										"<tr>											
											<td></td>
											<td></td>";
											if($showsections == '1'){ //if ge click ang show sections
												echo "<td>".$rs['section_name']."</td>";
											}

											if($showsubsections == '1'){ //if ge click ang show sections
												echo "<td></td>";
											}	
											echo "			
											<td>".$nq->getCount($select,$loca,'NESCO')."</td>
											<td>".$nq->getCount($select,$loca,'NESCO Regular')."</td>
											<td>".$nq->getCount($select,$loca,'NESCO Regular Partimer')."</td>
											<td>".$nq->getCount($select,$loca,'NESCO-PTA')."</td>
											<td>".$nq->getCount($select,$loca,'NESCO-PTP')."</td>
											<td>".$nq->getCount($select,$loca,'NESCO Probationary')."</td>
											<td>$total_nesco</td>
										</tr>";
									
										/***************** S U B  S E C T I O N ***************************/											
										$subsection = $nq->getAllSubSection($rs['section_code'],$rd['dept_code'],$rb['bunit_code'],$rc['company_code']);
										if($showsubsections == '1') //if ge click ang show sections
										{
											while($rss = mysql_fetch_array($subsection))
											{
												if(mysql_num_rows($subsection)>0)
												{
													//count sub section
													$count_ssc = mysql_query("$select $status $employeetypee and company_code = '$cc' and bunit_code = '$bc' and dept_code='$rd[dept_code]'  
													and section_code ='$rs[section_code]' and sub_section_code = '$rss[sub_section_code]' and current_status = 'active' and (emp_type != 'TAMBLOT' && emp_type != 'NICO' && emp_type !='CYDEM') ");
													$r4 = mysql_fetch_array($count_ssc);	
													$ssc_count = $r4['count(emp_id)'];
													
													$count_ssc_pt = mysql_query("$select $status $employeetypee and company_code = '$cc' and bunit_code = '$rb[bunit_code]' and dept_code='$rd[dept_code]'  and section_code = '$rs[section_code]' and sub_section_code = '$rss[sub_section_code]' ")or die(mysql_error());
													$r3_pt = mysql_fetch_array($count_ssc_pt);
													$ssc_count_pt = $r3_pt['count(emp_id)'];
													$ssc_count_pt = $ssc_count_pt * 0.5; 									
													$tot_c 	= $ssc_count + $ssc_count_pt;												
													
													$code = $cc.'/'.$bc.'/'.$rd['dept_code'].'/'.$rs['section_code'].'/'.$rss['sub_section_code'];
													$url = 	"statistics_details_report.php?code=$code";
													$loca = "company_code = '$cc' and bunit_code = '$bc' and dept_code='$rd[dept_code]'  
													and section_code ='$rs[section_code]' and sub_section_code = '$rss[sub_section_code]' ";

													$total_ae	 = $nq->getCount($select,$loca,'Contractual')+$nq->getCount($select,$loca,'Regular')+$nq->getCount($select,$loca,'Regular Partimer')+$nq->getCount($select,$loca,'PTA')+$nq->getCount($select,$loca,'PTP')+$nq->getCount($select,$loca,'Probationary');
													$total_nesco = $nq->getCount($select,$loca,'NESCO')+ $nq->getCount($select,$loca,'NESCO Regular')+$nq->getCount($select,$loca,'NESCO Regular Partimer')+$nq->getCount($select,$loca,'NESCO-PTA')+$nq->getCount($select,$loca,'NESCO-PTP')+$nq->getCount($select,$loca,'NESCO Probationary');
													$total_ae_nesco = $total_ae + $total_nesco;

													echo 
													"<tr>											
														<td></td>
														<td></td>";
														if($showsections == '1'){ //if ge click ang show sections
															echo "<td></td>";
														}
														if($showsubsections == '1'){//if ge click ang show sections
															echo "<td>".$rss['sub_section_name']."</td>";
														}
													echo "
													<td>".$nq->getCount($select,$loca,'NESCO')."</td>
													<td>".$nq->getCount($select,$loca,'NESCO Regular')."</td>
													<td>".$nq->getCount($select,$loca,'NESCO Regular Partimer')."</td>
													<td>".$nq->getCount($select,$loca,'NESCO-PTA')."</td>
													<td>".$nq->getCount($select,$loca,'NESCO-PTP')."</td>
													<td>".$nq->getCount($select,$loca,'NESCO Probationary')."</td>
													<td>$total_nesco</td>
												</tr>";
												}										
											}
										}
										}
									}
								}
							}
						}	
						echo "</table>";
}
else if($report =='benefits-report')
{
	$etype  = @$_GET['etype'];
	if($etype == 'All'){ $employeetypee = $employeetypee; } else { $employeetypee = "and emp_type = '$etype' "; }  
	$fields = array('NO','LASTNAME','FIRSTNAME','MIDDLENAME','BIRTHDAY','DATE HIRED','BUSINESS UNIT','DEPARTMENT','SECTION','POSITION','EMPTYPE','CURRENTSTATUS','CTCNO');
	//benefits type 
	switch(@$_GET['ben_no'])
	{	
		case "all": array_push($fields,'SSSNO','PHILHEALTH NO','PAGIBIG MID','PAGIBIG RTN','TIN NO'); break;
		case "sss_no": array_push($fields,'SSSNO'); break;			
		case "philhealth":	array_push($fields,'PHILHEALTH NO'); break;			
		case "pagibig_tracking": array_push($fields,'PAGIBIG RTN'); break;			
		case "pagibig": array_push($fields,'PAGIBIG NO'); break;			
		case "tin_no": array_push($fields,'TIN NO'); break;		
	}
	
	$query = mysql_query("SELECT employee3.emp_id, name, philhealth, cedula_no, sss_no, pagibig, pagibig_tracking, tin_no, company_code, bunit_code, 
	dept_code, section_code, sub_section_code, emp_type, position, current_status 
	FROM employee3 INNER JOIN applicant_otherdetails ON employee3.emp_id = applicant_otherdetails.app_id 
	WHERE $loc $employeetypee and current_status = 'Active'") or die(mysql_error());	

	echo table_header($fields,"EMPLOYEE BENEFITS REPORT",'');
	
	$ctr = 0;
	while($row = mysql_fetch_array($query))
	{			
		$ctr++;
		$dh = '';
		$bd = $nq->getOnefield('birthdate','applicant',"app_id='".$row['emp_id']."' ");
		$dh = $nq->getOnefield('date_hired','application_details',"app_id='".$row['emp_id']."' ");
		if($bd =='' || $bd =='0000-00-00'){ $bd = ''; } else { $bd = $nq->changeDateFormat('m/d/Y',$bd); }
		if($dh =='' || $dh =='0000-00-00'){ $dh = ''; } else { $dh = $nq->changeDateFormat('m/d/Y',$dh); }
		echo "<tr>	
			<td>$ctr</td>	
			<td>".UCWORDS(STRTOUPPER($nq->getOneField('lastname','applicant',"app_id = '".$row['emp_id']."'")))."</td>	
			<td>".UCWORDS(STRTOUPPER($nq->getOneField('firstname','applicant',"app_id = '".$row['emp_id']."'")))."</td>	
			<td>".UCWORDS(STRTOUPPER($nq->getOneField('middlename','applicant',"app_id = '".$row['emp_id']."'")))."</td>		
			<td>".$bd."</td>
			<td>".$dh."</td>
			<td>".ucwords(strtolower($nq->getBusinessUnitName($row['bunit_code'],$row['company_code'])))."</td>
			<td>".ucwords(strtolower($nq->getDepartmentName($row['dept_code'],$row['bunit_code'],$row['company_code'])))."</td>
			<td>".ucwords(strtolower($nq->getSectionName($row['section_code'],$row['dept_code'],$row['bunit_code'],$row['company_code'])))."</td>
			<td>".ucwords(strtolower($row['position']))."</td>	
			<td>".$row['emp_type']."</td>	
			<td>".$row['current_status']."</td>	
			<td>".$row['cedula_no']."</td>";
			
			if(@$_GET['ben_no'] == 'sss_no'){ echo "<td>".$row['sss_no']."</td>"; }
			if(@$_GET['ben_no'] == 'philhealth'){ echo "<td>".$row['philhealth']."</td>"; }
			if(@$_GET['ben_no'] == 'pagibig'){ echo "<td>".$row['pagibig']."</td>"; 	}
			if(@$_GET['ben_no'] == 'pagibig_tracking'){ echo "<td>".$row['pagibig_tracking']."</td>"; 	}
			if(@$_GET['ben_no'] == 'tin_no'){ echo "<td>".$row['tin_no']."</td>"; 	}
			if(@$_GET['ben_no'] == 'all'){
				echo "<td>".$row['sss_no']."</td>";
				echo "<td>".$row['philhealth']."</td>";
				echo "<td>".$row['pagibig']."</td>";
				echo "<td>".$row['pagibig_tracking']."</td>"; 
				echo "<td>".$row['tin_no']."</td>";
			}
		echo "</tr>";		
	}
	echo "</table>";	
}
else if($report =='termination-report')
{
	$et = $_GET['et'];	
	$mo = $_GET['mo'];
	$dt    = date('Y');
	$mname = $nq->getmonthname(@$mo);
	$mm    = explode("|",$mo);
	$date  = $dt."-".$mo;
	if(@$mm[1] != ''){
		$dt = $mm[1];
		$mname = $nq->getmonthname(@$mm[0]);
		$date  = $dt."-".$mm[0];
	}
	if($et == 'All'){
		$employeetypee= $employeetypee;
	}else{
		$employeetypee= "and emp_type = '$et'";
	}
	
	$title  = strtoupper($mname)." ".date('Y')."  EOC LIST EMPLOYEES";
	$fields = array('NO','EMPID','LASTNAME','FIRSTNAME','BUSINESS UNIT','DEPARTMENT','SECTION','POSITION','EMPTYPE','CURRENTSTATUS','EOCDATE');
	echo table_header($fields,$title,'');	

	$ctr 	= 0;
	$quer   = mysql_query("SELECT record_no, emp_id, position, company_code, bunit_code, dept_code, section_code, sub_section_code, firstname, lastname, eocdate, emp_type, current_status 
		FROM employee3 
		inner join applicant on applicant.app_id = employee3.emp_id
		WHERE $loc $employeetypee and eocdate like '$date%' and current_status = 'active' order by bunit_code, dept_code, eocdate") or die(mysql_error());	
	
	while($row = mysql_fetch_array($quer))
	{				
		$ctr++;
		$bd = explode('-',$row['eocdate']); 	
		$b = @$bd[1];	
		$year = @$bd[0];
		if($mo == $b && $year == $dt)
		{
			echo "
			<tr>
			<td>$ctr</td>
			<td>".$row['emp_id']."</td>						
			<td>".mb_convert_encoding(ucwords(strtolower($row['lastname'])), 'UCS-2LE', 'UTF-8')."</td>
			<td>".mb_convert_encoding(ucwords(strtolower($row['firstname'])), 'UCS-2LE', 'UTF-8')."</td>					
			<td>".$nq->getBusinessUnitName($row['bunit_code'],$row['company_code'])."</td>           
			<td>".$nq->getDepartmentName($row['dept_code'],$row['bunit_code'],$row['company_code'])."</td>
			<td>".$nq->getSectionName($row['section_code'],$row['dept_code'],$row['bunit_code'],$row['company_code'])."</td>           
			<td>".$row['position']."</td> 
			<td>".$row['emp_type']."</td>
			<td>".$row['current_status']."</td>
			<td>".$row['eocdate']."</td>		
			</tr>"; 
		}
	}
	echo "</table>";
}
else if($report == "yearsInService-report")
{  
    //orderby
    if($_GET['orderby']== '1'){
   		$orderby = "order by name";
    }else if($_GET['orderby'] == '2'){
		$orderby = "order by emp_type, company_code, bunit_code, dept_code";
	}else if($_GET['orderby'] == '3'){
		$orderby = "order by company_code, bunit_code, dept_code, name";
	}

    $query 	= mysql_query("SELECT name, emp_id, startdate, eocdate, company_code, bunit_code, dept_code, section_code, sub_section_code, current_status, emp_type, position 
					FROM EMPLOYEE3 WHERE $loc $employeetype and current_status = 'active' $orderby "); //) or die(mysql_error());
	$title  = "YEARS IN SERVICE REPORT";
	$fields = array('NAME','POSITION','BUSINESS UNIT','DEPARTMENT','SECTION','SUB-SECTION','EMPTYPE','DATEHIRED','STARTDATE','EOCDATE','YEARS','MONTHS','DAYS');
	echo table_header($fields,$title,'');

	while($qt = mysql_fetch_array($query))
	{
		$q1 = mysql_query("SELECT date_hired FROM `application_details` where app_id = '$qt[emp_id]' "); 
		$r1 = mysql_fetch_array($q1);

		if($qt['startdate'] == '0000-00-00' || $qt['startdate'] == '0001-11-30' || $qt['startdate'] == ''){ $startd =''; } else { $startd = $nq->ChangeDateFormat('m/d/Y',$qt['startdate']); }
		if($qt['eocdate'] == '0000-00-00' || $qt['eocdate'] == '0001-11-30' || $qt['eocdate'] == ''){ $eoc =''; } else { $eoc = $nq->ChangeDateFormat('m/d/Y',$qt['eocdate']); }
		if($r1['date_hired'] == '0000-00-00' || $r1['date_hired'] == '0001-11-30' || $r1['date_hired'] == '' ){ $dh =''; } else { $dh = $nq->ChangeDateFormat('m/d/Y',$r1['date_hired']); }
				
		$aa    = $contract->getYear($r1['date_hired'],$qt['startdate']); 
		$ab    = explode("/",$aa);
	    $x     = $ab[0];
	    $y     = $ab[1];
	    $z     = $ab[2]; ?>
		<tr>
			<td><?php echo mb_convert_encoding(ucwords(strtolower($qt['name'])), 'UCS-2LE', 'UTF-8'); ?></td>			
			<td><?php echo $qt['position']; ?></td>			
			<td><?php echo $nq->getBusinessUnitName($qt['bunit_code'],$qt['company_code']); ?></td>
			<td><?php echo $nq->getDepartmentName($qt['dept_code'],$qt['bunit_code'],$qt['company_code']); ?></td>
			<td><?php echo $nq->getSectionName($qt['section_code'],$qt['dept_code'],$qt['bunit_code'],$qt['company_code']); ?></td>			
			<td><?php echo $nq->getSubSectionName($qt['sub_section_code'],$qt['section_code'],$qt['dept_code'],$qt['bunit_code'],$qt['company_code']); ?></td>			
			<td><?php echo $qt['emp_type']; ?></td>
			<td><?php 
				if($r1['date_hired'] == '0000-00-00' || $r1['date_hired'] == '0001-11-30' || $r1['date_hired'] == ''){ 
					echo "";
				}else{ 
					echo $nq->ChangeDateFormat('m/d/Y',$r1['date_hired']);
				}?>
			</td>
			<td><?php echo $startd; ?> </td>
			<td><?php 
				if($qt['eocdate'] == '0000-00-00' || $qt['eocdate'] == '0001-11-30' || $qt['eocdate'] == ''){
					echo ''; 
				}else{ 
					echo $nq->ChangeDateFormat('m/d/Y',$qt['eocdate']); 
				} ?>
			</td>	
			<td><?php 
				if($qt['startdate'] == '0000-00-00' || $qt['startdate'] == '0001-11-30' || $qt['startdate'] ==''){	
					echo ""; 
				}
				else if($r1['date_hired'] == '0000-00-00' || $r1['date_hired'] == '0001-11-30' || $r1['date_hired'] == '' ){
					echo "";	
				}
				else{
					echo $x;
				}?>
			</td>
			<td><?php 
				if($qt['startdate'] == '0000-00-00' || $qt['startdate'] == '0001-11-30' || $qt['startdate'] ==''){	
					echo ""; 
				}
				else if($r1['date_hired'] == '0000-00-00' || $r1['date_hired'] == '0001-11-30' || $r1['date_hired'] == '' )
				{
					echo "";
				}
				else{
					echo $y;
				} ?>
			</td>
			<td><?php 
				if($qt['startdate'] == '0000-00-00' || $qt['startdate'] == '0001-11-30' || $qt['startdate'] ==''){	
					echo ""; 
				}else if($r1['date_hired'] == '0000-00-00' || $r1['date_hired'] == '0001-11-30' || $r1['date_hired'] == '' ){
					echo "";
				}else{
					echo $z;
				}?>
			</td>			
		</tr>		
	<?php
	}
	echo "</table>";
}
?>