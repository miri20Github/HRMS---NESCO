<?php
session_start();
include("../connection.php");
mysql_set_charset("UTF-8");
$employeetype = " (emp_type IN ('NESCO-BACKUP','NESCO Contractual','NESCO Partimer','NESCO-PTA','Promo-NESCO','NESCO-PTP','NESCO Regular','NESCO Probationary','NESCO Regular Partimer') )";

if(@$_GET['request'] == "loadNewEmployee")
{	
	$next7days  = date('Y-m-d');//date('Y-m-d', strtotime('+7 days'));
	$monthminus = date('Y-m-d', strtotime('-1 month')); //date one month from the current date

	// storing  request (ie, get/post) global array to a variable  
	$requestData= $_REQUEST;
	$columns = array( 
	    // datatable column index  => database column lastname		
		0=>'empid',
		1=>'name',
		2=>'position',
		3=>'emptype',
		4=>'business unit',
		5=>'department',
		6=>'section', 
		7=>'startdate',
		8=>'eocdate'
	);
	
	// getting total number records without any search
	$sql    = "SELECT emp_id FROM employee3
                WHERE tag_as = 'new' and startdate between '$monthminus' and '$next7days'
                and ($employeetype) ";	
	$query  = mysql_query($sql) or die(mysql_error());
	$totalData = mysql_num_rows($query);
	$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

	$sql = "SELECT emp_id, name, position, emp_type, company_code, bunit_code, dept_code, section_code, startdate, eocdate FROM employee3
	        WHERE 1=1 and tag_as = 'new' and startdate between '$monthminus' and '$next7days'
	        and ($employeetype) ";
	
    if(!empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
		$sql.=" AND ( emp_id LIKE '%".$requestData['search']['value']."%' ";    
		$sql.=" OR name LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR position LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR emp_type LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR DATE_FORMAT(startdate, '%m/%d/%Y') LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR DATE_FORMAT(eocdate, '%m/%d/%Y') LIKE '%".$requestData['search']['value']."%' )";	
	}
	
	$totalFiltered = mysql_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
	$query=mysql_query($sql) or die(mysql_error());

	$data = array();
	while($row=mysql_fetch_array($query)) {  // preparing an array
		
		if($row['startdate'] != NULL or $row['startdate'] != ''){ $startdate = $nq->changeDateFormat('m/d/Y',$row['startdate']); }	else { $startdate = '';}			
		if($row['eocdate'] != NULL or $row['eocdate'] != ''){ $eocdate = $nq->changeDateFormat('m/d/Y',$row['eocdate']); }	else { $eocdate = '';}
		
		$bunit = $nq->getBusinessUnitName($row['bunit_code'],$row['company_code']);
		$dept  = $nq->getDepartmentName($row['dept_code'],$row['bunit_code'],$row['company_code']);
		$section = $nq->getSectionName($row['section_code'],$row['dept_code'],$row['bunit_code'],$row['company_code']);
		
		$link = "<a href='?p=employee&com=$row[emp_id]'>".utf8_encode($row['name'])."</a>";
		$nestedData=array(); 
		$nestedData[] = $link;
		$nestedData[] = $row["position"];
		$nestedData[] = $bunit;
		$nestedData[] = $dept;
		$nestedData[] = $section;		
		$nestedData[] = $startdate;
		$nestedData[] = $eocdate;			
		$data[] = $nestedData;
	}

	$json_data = array(
				"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
				"recordsTotal"    => intval( $totalData ),  // total number of records
				"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
				"data"            => $data   // total data array
				);

	echo json_encode($json_data);  // send data as json format	
}

else if(@$_GET['request'] == "loadNewBlacklist")
{	
	$sevenday  = date('Y-m-d', strtotime('-7 days'));
	$sevendays  = date('Y-m-d', strtotime('+7 days'));	

	// storing  request (ie, get/post) global array to a variable  
	$requestData= $_REQUEST;
	$columns = array( 
	    // datatable column index  => database column lastname
		0=>'app_id',
		1=>'name',
		2=>'reportedby',
		3=>'date_blacklisted',
		4=>'reason'
	);

	// getting total number records without any search
	$sql    = "SELECT blacklist_no FROM `blacklist` WHERE date_blacklisted between '$sevenday' and '$sevendays'";
	$query  = mysql_query($sql) or die(mysql_error());
	$totalData = mysql_num_rows($query);
	$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

	$sql = "SELECT blacklist_no,app_id, name, status, reason, reportedby, date_blacklisted FROM `blacklist` WHERE 1=1 AND date_blacklisted between '$sevenday' and '$sevendays'";
	if(!empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
		$sql.=" AND ( app_id LIKE '%".$requestData['search']['value']."%' ";    
		$sql.=" OR name LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR reportedby LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR DATE_FORMAT(date_blacklisted, '%m/%d/%Y') LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR reason LIKE '%".$requestData['search']['value']."%' )";
	}
	
	$totalFiltered = mysql_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
	$query  = mysql_query($sql) or die(mysql_error());

	$data   = array();
	while($row = mysql_fetch_array($query)) {  // preparing an array
		
		$status = "<span class='label label-danger'>".$row['status']."</span>";		
		if($row['date_blacklisted'] != NULL or $row['date_blacklisted'] != ''){  
			$dateB = $nq->changeDateFormat('m/d/Y',$row['date_blacklisted']);
		}				
		
		$link = "<a href='?p=employee&com=$row[app_id]'>$row[app_id]</a>";
		$nestedData=array(); 
		$nestedData[] = $link;
		$nestedData[] = $row["name"];
		$nestedData[] = $row["reportedby"];
		$nestedData[] = $dateB;
		$nestedData[] = $row["reason"];			
		$data[] = $nestedData;
	}

	$json_data = array(
				"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
				"recordsTotal"    => intval( $totalData ),  // total number of records
				"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
				"data"            => $data   // total data array
				);

	echo json_encode($json_data);  // send data as json format	
}

else if(@$_GET['request'] == "loadNewJobTrans")
{
	$sevenday  = date('Y-m-d', strtotime('-20 days'));
	$sevendays  = date('Y-m-d', strtotime('+20 days'));	

	// storing  request (ie, get/post) global array to a variable  
	$requestData= $_REQUEST;
	$columns = array( 
	    // datatable column index  => database column lastname
		0=>'transfer_no',
		1=>'emp_id',
		2=>'name',
		3=>'emp_type',
		4=>'effectiveon',
		5=>'old_position',
		6=>'position'
	);

	// getting total number records without any search		
	$sql    = "SELECT employee_transfer_details.transfer_no FROM employee_transfer_details
			    INNER JOIN employee3 ON employee_transfer_details.emp_id = employee3.emp_id
			    WHERE effectiveon between '$sevenday' and '$sevendays' AND $employeetype ";
	$query  = mysql_query($sql) or die(mysql_error());
	$totalData = mysql_num_rows($query);
	$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

	$sql = "SELECT employee_transfer_details.transfer_no, employee3.name, employee3.emp_type, employee_transfer_details.emp_id, employee_transfer_details.effectiveon, employee_transfer_details.old_position, employee_transfer_details.position
			FROM employee_transfer_details
			INNER JOIN employee3 ON employee_transfer_details.emp_id = employee3.emp_id
			WHERE  1=1 AND effectiveon between '$sevenday' and '$sevendays' AND $employeetype ";
	
	if(!empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
		$sql.=" AND ( employee_transfer_details.emp_id LIKE '%".$requestData['search']['value']."%' ";	
		$sql.=" OR employee3.name LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR DATE_FORMAT(effectiveon, '%m/%d/%Y') LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR employee_transfer_details.old_position LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR employee_transfer_details.position LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR employee_transfer_details.transfer_no LIKE '%".$requestData['search']['value']."%' )";
	}
	
	$totalFiltered = mysql_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
	$query=mysql_query($sql) or die(mysql_error());

	$data = array();
	while($row = mysql_fetch_array($query)) {  // preparing an array
		
		if($row['effectiveon'] == '' || $row['effectiveon']  == '0000-00-00'){
			$effectiveon = '';	
		}else{
			$effectiveon = $nq->changeDateFormat('m/d/Y',$row['effectiveon']);
		}
		
		$link = "<a href='?p=employee&com=$row[emp_id]'>$row[emp_id]</a>";
		$nestedData=array(); 
		$nestedData[] = $row["transfer_no"];
		$nestedData[] = $link;	
		$nestedData[] = $row["name"];
		$nestedData[] = $row["emp_type"];		
		$nestedData[] = $effectiveon;
		$nestedData[] = $row["old_position"];
		$nestedData[] = $row["position"];			
		$data[] = $nestedData;
	}

	$json_data = array(
				"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
				"recordsTotal"    => intval( $totalData ),  // total number of records
				"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
				"data"            => $data   // total data array
				);

	echo json_encode($json_data);  // send data as json format		
}

else if(@$_GET['request'] == "loadStatisticsDetails")
{	
	$emptype = $_GET['emptype'];
	
	// storing  request (ie, get/post) global array to a variable  
	$requestData = $_REQUEST;
	$columns = array( 
	    // datatable column index  => database column lastname		
		0=>'emp_id',
		1=>'name',
		2=>'position', 		
		3=>'businessunit',
		4=>'department',
		5=>'section'
	);

	// getting total number records without any search
	$sql    = "SELECT emp_id FROM employee3 WHERE emp_type = '$emptype' and current_status = 'Active' ";
	$query  = mysql_query($sql) or die(mysql_error());
	$totalData = mysql_num_rows($query);
	$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

	$sql = "SELECT emp_id, name, position, emp_type, company_code, bunit_code, dept_code, section_code from employee3 
	WHERE 1=1 and emp_type = '$emptype' and current_status = 'Active' ";
	if(!empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
		$sql.=" AND (emp_id LIKE '%".$requestData['search']['value']."%'";    
		$sql.=" OR name LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR position LIKE '%".$requestData['search']['value']."%' )";
	}
	
	$totalFiltered = mysql_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
	$query=mysql_query($sql) or die(mysql_error());

	$data = array();	
	while($row = mysql_fetch_array($query)) {  // preparing an array		
		
		$link = "<a href='?p=employee&com=$row[emp_id]'>$row[emp_id]</a>";			
		
		$nestedData=array(); 
		$nestedData[] = $link;
		$nestedData[] = $row['name'];
		$nestedData[] = $row['position'];				
		$nestedData[] = $nq->getBusinessUnitName($row['bunit_code'],$row['company_code']);	
		$nestedData[] = $nq->getDepartmentName($row['dept_code'],$row['bunit_code'],$row['company_code']);			
		$nestedData[] = $nq->getSectionName($row['section_code'],$row['dept_code'],$row['bunit_code'],$row['company_code']);
		$data[] = $nestedData;
	}

	$json_data = array(
				"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
				"recordsTotal"    => intval( $totalData ),  // total number of records
				"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
				"data"            => $data   // total data array
				);
	echo json_encode($json_data);  // send data as json format	
}

?>