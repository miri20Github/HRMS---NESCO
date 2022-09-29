<?php
session_start();
include("../connection.php");
mysql_set_charset("UTF-8");
$employeetype = " (emp_type IN ('NESCO-BACKUP','NESCO Contractual','NESCO Partimer','NESCO-PTA','Promo-NESCO','NESCO-PTP','NESCO Regular','NESCO Probationary','NESCO Regular Partimer') )";

if(@$_GET['request'] == "loadBlacklists")
{	
	// storing  request (ie, get/post) global array to a variable  
	$requestData = $_REQUEST;

	// datatable column index  => database column lastname
	$columns = array( 	
		0=>'app_id',
		1=>'name',
		2=>'reportedby',
		3=>'date_blacklisted',
		4=>'reason',
		5=>'status',
		6=>'action'
	);

	// getting total number records without any search
	$sql 	= " SELECT app_id FROM `blacklist` ";
	$query 	= mysql_query($sql) or die(mysql_error());
	$totalData = mysql_num_rows($query);
	// when there is no search parameter then total number rows = total number filtered rows.
	$totalFiltered = $totalData;  	

	$sql = "SELECT * FROM `blacklist` WHERE 1=1";
	if(!empty($requestData['search']['value']) ) {   
		// if there is a search parameter, $requestData['search']['value'] contains search parameter
		$sql.=" AND ( app_id LIKE '%".$requestData['search']['value']."%' ";    
		$sql.=" OR name LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR reportedby LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR DATE_FORMAT(date_blacklisted, '%m/%d/%Y') LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR reason LIKE '%".$requestData['search']['value']."%' )";
	}
	
	// when there is a search parameter then we have to modify total number filtered rows as per search result. 
	$totalFiltered 	= mysql_num_rows($query); 

	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
	$query 			= mysql_query($sql) or die(mysql_error());

	$data = array();
	$dateB= "";
	while($row=mysql_fetch_array($query)) {  // preparing an array		
		$status = "<span class='label label-danger'>".$row['status']."</span>";		
		if($row['date_blacklisted'] != NULL or $row['date_blacklisted'] != ''){  
			$dateB = $nq->changeDateFormat('m/d/Y',$row['date_blacklisted']);
		}
		
		$link = "<a href='?p=blacklists-add&blno=$row[blacklist_no]&emp=$row[app_id]'>edit</a>";
		$nestedData 	= array(); 
		$nestedData[] 	= $row["app_id"];
		$nestedData[] 	= $row["name"];
		$nestedData[] 	= $row["reportedby"];
		$nestedData[] 	= $dateB;
		$nestedData[] 	= $row["reason"];
		$nestedData[] 	= $status;	
		$nestedData[] 	= $link;			
		$data[] 		= $nestedData;
	}

	$json_data = array(
				"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
				"recordsTotal"    => intval( $totalData ),  // total number of records
				"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
				"data"            => $data   // total data array
				);
	echo json_encode($json_data);  // send data as json format
}

else if(@$_GET['request'] == "loadNescoBenefitsMasterfile")
{	
	// storing  request (ie, get/post) global array to a variable  
	$requestData = $_REQUEST;
	$columns 	 = array( 
		// datatable column index  => database column lastname		
		0=>'emp_id',
		1=>'name',
		2=>'sss',
		3=>'philhealth',
		4=>'pagibig',
		5=>'pagibigrtn',
		6=>'tinno'
	);

	// getting total number records without any search
	$sql 		= " SELECT emp_id FROM employee3 
					INNER JOIN applicant_otherdetails ON employee3.emp_id = applicant_otherdetails.app_id 
					WHERE $employeetype and current_status = 'Active' ";
	$query 		= mysql_query($sql) or die(mysql_error());
	$totalData 	= mysql_num_rows($query);
	$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

	$sql 		= " SELECT emp_id, name, sss_no, pagibig_tracking, pagibig, philhealth, tin_no 
					FROM employee3 
					INNER JOIN applicant_otherdetails ON employee3.emp_id = applicant_otherdetails.app_id 
					WHERE 1=1 AND $employeetype and current_status = 'Active' ";

	if(!empty($requestData['search']['value']) ) {   
		// if there is a search parameter, $requestData['search']['value'] contains search parameter
		$sql.=" AND ( emp_id LIKE '%".$requestData['search']['value']."%' ";    
		$sql.=" OR name LIKE '%".$requestData['search']['value']."%' ) ";
	}
	
	$totalFiltered 	= mysql_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
	
	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
	$query 			= mysql_query($sql) or die(mysql_error());
	$data 			= array();	

	while($row=mysql_fetch_array($query)) {  // preparing an array		
		$status 		= "<span class='label label-success'>".$row['current_status']."</span>";
		$link_emp 		= "<a href=?p=employee&com=".$row['emp_id'].">".$row['emp_id']."</a>";				
		$nestedData 	= array(); 		
		$nestedData[] 	= $link_emp;
		$nestedData[] 	= strtoupper($row['name']);
		$nestedData[] 	= str_replace("-","", $row['sss_no']);
		$nestedData[] 	= str_replace("-","", $row['philhealth']);
		$nestedData[] 	= str_replace("-","", $row['pagibig']);
		$nestedData[] 	= str_replace("-","", $row['pagibig_tracking']);
		$nestedData[] 	= str_replace("-","", $row['tin_no']);		
		$data[] 		= $nestedData;
	}

	$json_data = array(
				"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
				"recordsTotal"    => intval( $totalData ),  // total number of records
				"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
				"data"            => $data   // total data array
				);
	echo json_encode($json_data);  // send data as json format
}

else if(@$_GET['request'] == "loadNESCORegulars")
{	
	// storing  request (ie, get/post) global array to a variable  
	$requestData= $_REQUEST;
	$columns = array( 
	// datatable column index  => database column lastname		
		0=>'emp_id',
		1=>'name',
		2=>'position', 
		3=>'emp_type', 
		4=>'businessunit',
		5=>'department',
		6=>'section'
	);

	// getting total number records without any search
	$sql 		= "SELECT * FROM employee3 WHERE current_status = 'Active'
					and (emp_type ='NESCO Regular' or emp_type='NESCO Regular Partimer') ";
	$query 		= mysql_query($sql) or die(mysql_error());
	$totalData 	= mysql_num_rows($query);
	$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

	$sql = "SELECT emp_id, name, position, emp_type, company_code, bunit_code, dept_code, section_code 
			FROM employee3 WHERE 1=1 and (emp_type ='NESCO Regular' or emp_type='NESCO Regular Partimer')";
	
	if(!empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
		$sql.=" AND (emp_id LIKE '%".$requestData['search']['value']."%'";    
		$sql.=" OR name LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR position LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR emp_type LIKE '%".$requestData['search']['value']."%' ) ";
	}
	
	$totalFiltered = mysql_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
	$query 	= mysql_query($sql) or die(mysql_error());
	$data 	= array();	

	while($row = mysql_fetch_array($query)) {  // preparing an array	
		$link 			= "<a href='?p=employee&com=$row[emp_id]'>$row[emp_id]</a>";		
		$nestedData 	= array(); 
		$nestedData[] 	= $link;
		$nestedData[] 	= $row['name'];
		$nestedData[] 	= $row['position'];	
		$nestedData[] 	= $row['emp_type'];	
		$nestedData[] 	= $nq->getBusinessUnitName($row['bunit_code'],$row['company_code']);	
		$nestedData[] 	= $nq->getDepartmentName($row['dept_code'],$row['bunit_code'],$row['company_code']);			
		$nestedData[] 	= $nq->getSectionName($row['section_code'],$row['dept_code'],$row['bunit_code'],$row['company_code']);
		$data[] 		= $nestedData;
	}

	$json_data = array(
				"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
				"recordsTotal"    => intval( $totalData ),  // total number of records
				"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
				"data"            => $data   // total data array
				);
	echo json_encode($json_data);  // send data as json format	
}
else if(@$_GET['request'] == "loadNescoJobTrans")
{	
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
	$sql = "SELECT employee_transfer_details.transfer_no FROM employee_transfer_details
		INNER JOIN employee3 ON employee_transfer_details.emp_id = employee3.emp_id
		WHERE current_status ='active' and $employeetype";

	$query		= mysql_query($sql) or die(mysql_error());
	$totalData 	= mysql_num_rows($query);
	$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

	$sql = "SELECT employee_transfer_details.transfer_no, employee3.name, employee3.emp_type, employee3.record_no, employee_transfer_details.emp_id, employee_transfer_details.effectiveon, employee_transfer_details.old_position, employee_transfer_details.position
		FROM employee_transfer_details
		INNER JOIN employee3 ON employee_transfer_details.emp_id = employee3.emp_id	
		WHERE 1=1 and current_status ='active' and $employeetype";

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
	$query 	= mysql_query($sql) or die(mysql_error());
	$data 	= array();
	$effectiveon = '';
	while($row = mysql_fetch_array($query)) {  // preparing an array		
		
		$link_emp = "<a href='?p=employee&com=$row[emp_id]'>$row[emp_id]</a>";			
		//$link_edit= "<a href='?p=edittransfers&empid=$row[emp_id]&rec=$row[record_no]&transno=$row[transfer_no]'>Edit</a>";
		$link_view= "<a href='javascript:void' onclick=\"viewJobTrans('".$row['transfer_no']."')\">View</a>";
		if($row['effectiveon'] != NULL or $row['effectiveon'] != ''){  
			$effectiveon = $nq->changeDateFormat('m/d/Y',$row['effectiveon']);
		}	
				
		$nestedData 	= array(); 
		$nestedData[] 	= $row['transfer_no'];
		$nestedData[] 	= $link_emp;
		$nestedData[] 	= $row['name'];//$nq->getEmpName($row['emp_id']);
		$nestedData[] 	= $row['emp_type'];
		$nestedData[] 	= $effectiveon;	
		$nestedData[] 	= $row['old_position'];	
		$nestedData[] 	= $row['position'];	
		$nestedData[] 	= $link_view;		
		$data[] 		= $nestedData;
	}

	$json_data = array(
				"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
				"recordsTotal"    => intval( $totalData ),  // total number of records
				"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
				"data"            => $data   // total data array
				);
	echo json_encode($json_data);  // send data as json format		
}

else if(@$_GET['request'] == "loadNescoMasterfile")
{	
	// storing  request (ie, get/post) global array to a variable  
	$requestData= $_REQUEST;
	$columns = array( 
		// datatable column index  => database column lastname		
		0=>'emp_id',
		1=>'name',
		2=>'bunit',
		3=>'dept',
		4=>'position',
		5=>'emptype',
		6=>'status'
	);

	// getting total number records without any search
	$sql 		= " SELECT emp_id FROM employee3 WHERE $employeetype and current_status = 'Active' ";
	$query 		= mysql_query($sql) or die(mysql_error());
	$totalData 	= mysql_num_rows($query);
	$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

	$sql = " SELECT company_code, bunit_code, dept_code, current_status, emp_id, name, emp_type, position
			FROM employee3
			WHERE 1=1 AND $employeetype and current_status = 'Active' ";
	
	if(!empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
		$sql.=" AND ( emp_id LIKE '%".$requestData['search']['value']."%' ";    
		$sql.=" OR name LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR emp_type LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR position LIKE '%".$requestData['search']['value']."%' ) ";
	}
	
	$totalFiltered = mysql_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
	$query = mysql_query($sql) or die(mysql_error());
	$data  = array();
	
	while($row = mysql_fetch_array($query)) {  // preparing an array		
		$status 	= "<span class='label label-success'>".$row['current_status']."</span>";
		$link_emp 	= "<a href=?p=employee&com=".$row['emp_id'].">".$row['emp_id']."</a>";
						
		$nestedData 	= array(); 		
		$nestedData[] 	= $link_emp;
		$nestedData[] 	= strtoupper($row['name']);
		$nestedData[] 	= $nq->getBusinessUnitName($row['bunit_code'],$row['company_code']);
		$nestedData[] 	= $nq->getDepartmentName($row['dept_code'],$row['bunit_code'],$row['company_code']);
		$nestedData[] 	= $row['position'];
		$nestedData[] 	= $row['emp_type'];
		$nestedData[] 	= $status;		
		$data[] 		= $nestedData;
	}

	$json_data = array(
				"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
				"recordsTotal"    => intval( $totalData ),  // total number of records
				"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
				"data"            => $data   // total data array
				);
	echo json_encode($json_data);  // send data as json format
}

else if(@$_GET['request'] == "loadNescoTermination")
{	
	// storing  request (ie, get/post) global array to a variable  
	$requestData= $_REQUEST;
	$columns = array( 
		// datatable column index  => database column lastname		
		0=>'termination_no',
		1=>'emp_id',
		2=>'name',
		3=>'date',		
		4=>'date_updated',
		5=>'remarks',
		6=>'resignation_letter'
	);
	
	// getting total number records without any search
	$sql 		= "SELECT termination.emp_id FROM `termination`
					INNER JOIN employee3 ON termination.emp_id = employee3.emp_id";
	$query 		= mysql_query($sql) or die(mysql_error());
	$totalData 	= mysql_num_rows($query);
	$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

	$sql = "SELECT termination.termination_no, termination.emp_id, termination.date, termination.date_updated, termination.remarks, termination.resignation_letter, employee3.name FROM `termination`
			INNER JOIN employee3 ON termination.emp_id = employee3.emp_id WHERE 1=1";
	
	if(!empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
		$sql.=" AND ( termination.termination_no LIKE '%".$requestData['search']['value']."%' ";    
		$sql.=" OR employee3.name LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR DATE_FORMAT(termination.date_updated, '%m/%d/%Y') LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR DATE_FORMAT(termination.date, '%m/%d/%Y') LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR termination.remarks LIKE '%".$requestData['search']['value']."%')";				
	}
	
	$totalFiltered 	= mysql_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
	$query 		= mysql_query($sql) or die(mysql_error());
	$data 		= array();
	$dateres 	= '';
	$dateup 	= '';

	while($row = mysql_fetch_array($query)) {  // preparing an array		
		
		$link_emp 	= "<a href=?p=employee&com=".$row['emp_id'].">".$row['emp_id']."</a>";
		if($row['date'] != NULL or $row['date'] != ''){  
			$dateres = $nq->changeDateFormat('m/d/Y',$row['date']);
		}

		if($row['date_updated'] != NULL or $row['date_updated'] != ''){  
			$dateup = $nq->changeDateFormat('m/d/Y',$row['date_updated']);
		}	
		
		if($row['resignation_letter'] != ''){
			$let = "<button class='btn btn-primary btn-sm' data-toggle='modal' data-target='#viewresignation' onclick=viewresig('".$row['termination_no']."')>view</button>";
		}else{
			$let = "";
		}		
				
		$nestedData 	= array(); 
		$nestedData[] 	= $row['termination_no'];
		$nestedData[] 	= $link_emp;
		$nestedData[] 	= strtoupper($nq->getEmpName($row['emp_id']));		
		$nestedData[] 	= $dateres;
		$nestedData[] 	= $dateup;
		$nestedData[] 	= $row['remarks'];	
		$nestedData[] 	= $let;			
		$data[] 		= $nestedData;
	}

	$json_data = array(
				"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
				"recordsTotal"    => intval( $totalData ),  // total number of records
				"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
				"data"            => $data   // total data array
				);
	echo json_encode($json_data);  // send data as json format		
}