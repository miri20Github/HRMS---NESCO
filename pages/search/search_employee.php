<?php
	//put this at the top of the page
    $mtime = microtime();
    $mtime = explode(" ",$mtime);
    $mtime = $mtime[1] + $mtime[0];
    $starttime = $mtime;
 
	mysql_query('SET NAMES utf8');	
	if(@$_GET['search']){
		$search 	= @mysql_escape_string(trim(@$_GET['search']));
	}else{
		$search 	= @mysql_escape_string(trim(@$_GET['search']));
	}

	$count      = strlen($search);		
	
	$str = preg_replace('/[^A-Za-z0-9\. -]/', '', $search);	 //remove the special characters
	$str = " ".$str." ";									 //put spaces in the first and last part of the string
	$str = preg_replace('/  */', '%', $str);	
		
	//employee3 fields
	$fields  	= "employee3.record_no, sub_status, employee3.emp_id, lastname, firstname, middlename, suffix, employee3.position, employee3.current_status, employee3.startdate, employee3.eocdate,employee3.company_code, employee3.bunit_code, employee3.dept_code,employee3.section_code,employee3.sub_section_code, employee3.emp_type, applicant.photo";
	//applicants fields
	$fields_app = "birthdate, home_address, civilstatus";
	$employeetype = "(emp_type IN ('NESCO-BACKUP','NESCO','NESCO Probationary','NESCO Partimer','NESCO-PTA','NESCO-PTP','NESCO Regular','NESCO Regular Partimer','NESCO Contractual','Promo-NESCO') )";

	if(isset($_GET['search']) )
	{    
		if($count < 2)
		{
			$msg = "Search Value should have a minimum of 2 characters.";
		}	
		else if($search != "")
		{
			$query = mysql_query("SELECT $fields FROM employee3 inner join applicant on applicant.app_id = employee3.emp_id where  $employeetype and employee3.name like '%$search%' or emp_id = '$search' order by employee3.name,employee3.record_no desc  ") or die(mysql_error());	//LIMIT $start,$limit		
			$tot   = mysql_query("SELECT record_no,emp_id FROM employee3 where $employeetype and employee3.name like '%$search%' or emp_id = '$search' order by name,record_no desc ") or die(mysql_error());
			$total_pages = mysql_num_rows($tot);				
		}	
	}
?>
<div style="width:100%; margin-left:auto; margin-right:auto;">    
	<?php
	if(@$search != ""){
		if($count < 1){	
			similar_daw($search); // if not found displays other option
			$flag = 1;
		}else{
			$flag = 0;
			echo "There are ".@$total_pages." results for <b>$search</b> <br>"; 	
		}	
	}
	echo "<hr style='color:black;height:5px;widthL'>";
	?>
	<center><img id='loading' style='display:none' src="../images/icons/loading11.gif"></center>
	<div style="width:90%; margin-left:3%;font-size:12px;">
	<?php
		$i 		= 0;
		$ctr 	= 0;
		$tot 	= 0;			
		while(@$row = mysql_fetch_array(@$query))
		{	
			$i++;
			//get applicant details
			$queryapp = mysql_query("SELECT $fields_app FROM applicant where app_id = '$row[emp_id]' ");					
			while($rapp = mysql_fetch_array($queryapp))
			{
				$bd 	= new DateTime($rapp['birthdate']); 
				$home 	= $rapp['home_address'];
				$cv 	= $rapp['civilstatus'];	

				if($row['sub_status'] == ""){ $substatus = ""; } else { $substatus = "(".$row['sub_status'].")"; }

				if($row['current_status'] == "Active"){ $c_stat = "<span class='label label-success'>".ucwords(strtolower($row['current_status']))." ".$substatus."</span>";}	
				else if($row['current_status'] == "blacklisted" || $row['current_status'] == "Blacklisted"){ $c_stat = "<span class='label label-danger'>".ucwords(strtolower($row['current_status']))."</span>";}
				else if($row['current_status'] == "End of Contract"){ $c_stat = "<span class='label label-warning'>".ucwords(strtolower($row['current_status']))." ".$substatus."</span>";}
				else if($row['current_status'] == "resigned" || $row['current_status'] == "Resigned" || $row['current_status'] == "V-Resigned" || $row['current_status'] == "Ad-Resigned"){ $c_stat = "<span class='label label-warning'>".ucwords(strtolower($row['current_status']))." ".$substatus."</span>";}
				else if($row['current_status'] == "Retrenched" || $row['current_status'] == "Retired"){ $c_stat = "<span class='label label-warning'>".ucwords(strtolower($row['current_status']))." ".$substatus."</span>";}
				else { $c_stat = ucwords(strtolower($row['current_status'])); }

				$photo = $row['photo'];
				if($photo == ''){
					$photo = '../images/users/icon-user-default.png';
				}

				if($row['suffix']){
					$name = $row['lastname'].", ".$row['firstname']." ".$row['suffix'].", ".$row['middlename'];	
				}else{
					$name = $row['lastname'].", ".$row['firstname']." ".$row['middlename'];	
				}

				if($row['sub_section_code'] !=''){
					$subsec = "Sub Section: <i>".$nq->getSubSectionName($row['sub_section_code'],$row['section_code'],$row['dept_code'],$row['bunit_code'], $row['company_code'])."</i>";
				}else{ $subsec= '';}

				$q_regclass = mysql_query("SELECT display from reg_class inner join employee3 
							on reg_class.reg_class = employee3.reg_class where emp_id = '".$row['emp_id']."' ");
				$r_regclass = mysql_fetch_array($q_regclass);
				if($r_regclass['display']!=''){
					$regclass = "<span style='color:green'>(".$r_regclass['display'].")</span>";
				}else{
					$regclass = '';
				}	
				
				echo "<div>";
				if($row['emp_type'] == "Promo" && @$promoUser_stat == "active"){
					
					echo "<img style='float:left' src='$photo' width='70' height='70'> &nbsp; [".$i."] <a id='sear' href='../promo/$loc?com=$row[emp_id]' style='font-size:16px'>".$row['emp_id']." ".$name."</a> ".$c_stat."<br>";
				} else {
					
					echo "<img style='float:left' src='$photo' width='70' height='70'> &nbsp; [".$i."] <a id='sear' href='?p=employee&com=$row[emp_id]' style='font-size:16px'>".$row['emp_id']." ".$name."</a> ".$c_stat."<br>";
				}

				echo "&nbsp;&nbsp;<font style='color:green'>Company: <i>".$nq->getCompanyAcroname($row['company_code'])."</i> Business Unit: <i>".ucwords(strtolower($nq->getBusinessUnitName($row['bunit_code'], $row['company_code'])))."</i> 
					Department: <i>".ucwords(strtolower($nq->getDepartmentName($row['dept_code'],$row['bunit_code'], $row['company_code'])))."</i> Section: <i>".$nq->getSectionName($row['section_code'],$row['dept_code'],$row['bunit_code'], $row['company_code'])." </i> $subsec </font><br>
					&nbsp;&nbsp;Position: <i>".ucwords(strtolower($row['position']))."</i>
					&nbsp; Employee Type: <i>".ucwords(strtolower($row['emp_type']))." ".$regclass."</i> &nbsp; Civil Status: <i>".ucwords(strtolower($cv))."</i>
					&nbsp; Birthdate: <i>".$bd->format('M d, Y')."</i> &nbsp; Home Address: <i>".ucwords(strtolower($home))."</i>
				</div><br>";
			}
		}		
	?>
	</div>
	<div id='description' class="ViewDetails" style="display:none;position:absolute; top:185px;right:70px;%;width: 35%; float:right; border: #ccc 1px solid;">
		<iframe id="imge" align="middle" width="100%" height="460" frameborder="0"></iframe>
	</div>

	<?php
	if(@$flag == 0 && @$_GET['search'] != ""){
		echo "<hr style='color:black;height:5px;'>";
	}?>
</div>

<input type="hidden" id="y_r"> 
<p>&nbsp;</p>

<?php 
	function similar_daw($search)
	{
		$words  = array();
		$i = 0;
		$largest = 0.0;
		$nearest = '';
		$w  = mysql_query("SELECT name from employee3");
		while($rrr = mysql_fetch_array($w))
		{		
			array_push($words,$rrr['name']);
			similar_text($search, $words[$i], $p);
			if($i == 0){ $largest = 0.0;}
			if($largest < $p)
			{
				$largest = $p;
				$nearest = $words[$i];
			} 	    
			$i++;    
		}
		echo "<p style='text-indent:85px'><font style='font-size:18px;color:red;'>Did you mean:</font> <a href='custom_search.php?search=$nearest'><font style='font-size:18px;color: blue; font-style:italic'>".$nearest."</font></a></p>";//" largest -".$largest."<br>";
	}
	//put this code at the bottom of the page 
	$mtime = microtime();
	$mtime = explode(" ",$mtime);
	$mtime = $mtime[1] + $mtime[0];
	$endtime = $mtime;
	$totaltime = ($endtime - $starttime);    
?>