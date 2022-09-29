<?php
session_start();
include("../connection.php");
mysql_set_charset("UTF-8");
$employeetype = " (emp_type IN ('NESCO-BACKUP','NESCO Contractual','NESCO Partimer','NESCO-PTA','Promo-NESCO','NESCO-PTP','NESCO Regular','NESCO Probationary','NESCO Regular Partimer') )";


if(@$_GET['request'] == "loadSetupSalnum")
{		
	// storing  request (ie, get/post) global array to a variable  
	$requestData= $_REQUEST;
	$columns = array( 
	// datatable column index  => database column lastname		
		0=>'emp_id',
		1=>'name',
		2=>'date_hired',
		3=>'currentstatus',
		4=>'emp_type', 
		5=>'position', 
		6=>'payroll_no'
	);

	// getting total number records without any search
	//$sql = "SELECT current_status, emp_id, name, emp_type, position, payroll_no from employee3 WHERE $employeetype and current_status = 'Active' ";
	$sql = "SELECT current_status, emp_id, name, date_hired, emp_type, position, payroll_no from employee3 inner join application_details on employee3.emp_id = application_details.app_id WHERE $employeetype and current_status = 'Active'";

	$query=mysql_query($sql) or die(mysql_error());
	$totalData = mysql_num_rows($query);
	$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

	//$sql = "SELECT current_status, emp_id, name, emp_type, position, payroll_no from employee3
	//WHERE 1=1 and $employeetype and current_status = 'Active'";
	$sql = "SELECT current_status, emp_id, name, date_hired, emp_type, position, payroll_no from employee3 inner join application_details on employee3.emp_id = application_details.app_id WHERE 1=1 and $employeetype and current_status = 'Active'";

	if(!empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
		$sql.=" AND (emp_id LIKE '%".$requestData['search']['value']."%'";    
		$sql.=" OR name LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR position LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR emp_type LIKE '%".$requestData['search']['value']."%' ) ";
	}
	
	$query=mysql_query($sql) or die(mysql_error());
	$totalFiltered = mysql_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
	$query=mysql_query($sql) or die(mysql_error());

	$data = array();	
	while($row=mysql_fetch_array($query)) {  // preparing an array		
		
		//$quer = mysql_query("SELECT date_hired from application_details where app_id = '$row[emp_id]' ");
		//$rr   = mysql_fetch_array($quer);
		$datehired = $row['date_hired'];
		
		$eids = $row['emp_id'];
		$link = "<a href='?p=employee&com=$row[emp_id]'>$row[emp_id]</a>";	
		$textp= "<input type='hidden' value='".$row['payroll_no']."' id='pid2_".$row['emp_id']."' name='pid2_".$row['emp_id']."' >
				<input type='text' value='".$row['payroll_no']."' name='pid_".$row['emp_id']."' id='".$row['emp_id']."' maxlength='13' onkeyup='numericFilter(this)' onkeypress='return savepid(event,\"$eids\");'>";
		
		$nestedData=array(); 
		$nestedData[] = $link;
		$nestedData[] = utf8_encode($row['name']);
		$nestedData[] = $datehired;
		$nestedData[] = $row['current_status'];
		$nestedData[] = $row['emp_type'];	
		$nestedData[] = $row['position'];	
		$nestedData[] = $textp;	
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

else if(@$_GET['request'] == "loadUserEmployee")
{	
	// storing  request (ie, get/post) global array to a variable  
	$requestData= $_REQUEST;
	$columns = array( 
		// datatable column index  => database column lastname		
		0=>'user_no',
		1=>'name',
		2=>'username', 
		3=>'position',	
		4=>'emp_type',
		5=>'current_status',
		6=>'user_status',
		7=>'action'
	);
	 
	// getting total number records without any search
	$sql1 		= "SELECT employee3.emp_id FROM employee3 
					INNER JOIN users ON employee3.emp_id = users.emp_id
					WHERE usertype='employee' AND $employeetype ";
	$query 		= mysql_query($sql1) or die(mysql_error());
	$totalData 	= mysql_num_rows($query);
	$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

	$sql 		= "SELECT employee3.emp_id, users.user_no, employee3.name, users.username, users.user_status, employee3.current_status, employee3.position, employee3.emp_type
					FROM employee3 INNER JOIN users ON employee3.emp_id = users.emp_id 
					WHERE 1=1 and usertype='employee' AND $employeetype";

	if(!empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
		$sql.=" AND (name LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR position LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR emp_type LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR username LIKE '%".$requestData['search']['value']."%' )";
	}
	
	$query 			= mysql_query($sql) or die(mysql_error());
	$totalFiltered 	= mysql_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
	$query = mysql_query($sql) or die(mysql_error());

	$data = array();
	$effectiveon = '';
	while($row=mysql_fetch_array($query)) {  // preparing an array		
		
		$link_emp 	= "<a href=?p=employee&com=".$row['emp_id'].">".$row['name']."</a>";
		if($row['user_status']=="inactive"){ 
			$r = "<a href='#' title='Click to activate account' onclick=userfunction('".$row['user_no']."','activateAccount')><img src='../images/icons/icn_active.gif'></a>"; } 
		else { 
			$r = "<a href='#' title='Click to deactivate account' onclick=userfunction('".$row['user_no']."','deactivateAccount')><img src='../images/icons/icon-close-circled-20.png'></a>";}
						
		$s  = "<a href='#' title='Click to reset password' onclick=userfunction('".$row['user_no']."','resetPass')><img src='../images/icons/refresh.png' width='17' height='17'></a>";
		$nestedData=array(); 
		$nestedData[] = $row['user_no'];
		$nestedData[] = $link_emp;	
		$nestedData[] = $row['username'];
		$nestedData[] = $row['position'];	
		$nestedData[] = $row['emp_type'];		
		$nestedData[] = $nq->showCStatus($row['current_status']);		
		$nestedData[] = $nq->showUserStatus($row['user_status']);
		$nestedData[] = $s.$r;
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