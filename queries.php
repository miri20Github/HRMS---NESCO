<?php 
class queries extends configs{
	function makeQuery($query){						
		return @mysql_query($query);
	}
	function fetchArray($result){						
		return @mysql_fetch_array($result);
	}
	function changeDateFormat($changeformatto,$date)
	{		
		$this->Connect();	
		if($date != "0000-00-00"){
			$convert_date = new DateTime(@$date); 		
			return @$convert_date->format($changeformatto);
		}
	}
	function display_valid_date($dates)
	{
		$date_format = 'Y-m-d';
		$input = $dates;		
		$input = trim($input);
		$time = strtotime($input);		
		$is_valid = date($date_format, $time) == $input;			
		//print "Valid? ".($is_valid ? 'yes' : 'no');
		return $is_valid;
	}
		function innerJOINbrgytownprov(){	
		$this->Connect();
		$query = "SELECT brgy_name, town_name, prov_name FROM barangay 
		INNER JOIN town ON barangay.town_id = town.town_id
		INNER JOIN province ON town.prov_id = province.prov_id ";	
		//where brgy_name like '%$q%' || town_name like '%$q%' || prov_name like '%$q%'		
		$result = $this->makeQuery($query);			
		return $result;	
	}
	function selectALLfromATTAINMENT(){	
		$this->Connect();
		$query = "SELECT attainment from attainment";			
		$result = $this->makeQuery($query);			
		return $result;	
	}
	function selectLnFnfromApplicant($ln,$fn){	
		$this->Connect();
		$query = "SELECT * from applicant where lastname = '$ln' and firstname = '$fn'";			
		$result = $this->makeQuery($query);			
		return $result;	
	}
	function selectLnFnMnfromApplicant(){	
		$this->Connect();
		$query = "SELECT * FROM applicant WHERE status!='blacklisted' order by lastname,firstname";			
		$result = $this->makeQuery($query);			
		return $result;	
	}
	
	function selectNamefromApplicant(){	
		$this->Connect();
		$query = "SELECT app_id,lastname, firstname, middlename FROM applicant order by lastname,firstname";			
		$result = $this->makeQuery($query);			
		return $result;	
	}
	
	function selectNamefromEmployee(){	
		$this->Connect();
		$query = "SELECT distinct(emp_id), name FROM employee2";			
		$result = $this->makeQuery($query);			
		return $result;	
	}
	
	function applicantforOrientation(){	
		$this->Connect();
		$query = "SELECT applicant.app_id, applicant.lastname, applicant.firstname, applicant.middlename, position, app_code FROM applicants 
		          INNER JOIN applicant
				  ON applicants.lastname=applicant.lastname AND applicants.firstname=applicant.firstname AND applicants.middlename=applicant.middlename
				  WHERE applicants.status='for orientation' order by lastname,firstname";			
		$result = $this->makeQuery($query);			
		return $result;	
	}
	function applicantOrientation(){	
		$this->Connect();
		$query = "SELECT applicant.app_id, applicant.lastname, applicant.firstname, applicant.middlename, position, app_code FROM applicants 
		          INNER JOIN applicant
				  ON applicants.lastname=applicant.lastname AND applicants.firstname=applicant.firstname AND applicants.middlename=applicant.middlename
				  WHERE applicants.status='orientation' order by lastname,firstname";			
		$result = $this->makeQuery($query);			
		return $result;	
	}
	function selectLnFnMnfromApplicantEmp(){	
		$this->Connect();
		$query = "SELECT distinct(emp_id),lastname, firstname, middlename from employee inner join applicant on applicant.app_id = employee.emp_id where current_status = 'active'";			
		$result = $this->makeQuery($query);			
		return $result;	
	}
	

//======================================================================================================================================================
//                                             S C H O O L      T A B L E         Q U E R I E S
//======================================================================================================================================================
	    function selectDISTINCTschoolnameFROMschool(){
		$this->Connect();
		$query="SELECT DISTINCT school_name FROM school";
		$result=$this->makeQuery($query);
		return $result;
	}
		function selectschoolnameWHEREschoolname($q){
		$this->Connect();
		$query="SELECT school_name from school where school_name like '%$q%' order by school_name";
		$result=$this->makeQuery($query);
		return $result;
	}

//======================================================================================================================================================
//                                             C O U R S E      T A B L E         Q U E R I E S
//======================================================================================================================================================
		function selectDISTINCTcoursenameFROMCOURSE(){
		$this->Connect();
		$query="SELECT DISTINCT course_name FROM course";
		$result=$this->makeQuery($query);
		return $result;
	}
		function selectcoursenameWHEREcoursename($q){
		$this->Connect();
		$query="SELECT course_name from course where course_name like '%$q%' order by course_name";
		$result=$this->makeQuery($query);
		return $result;
	}
//======================================================================================================================================================
//                                             P O S I T I O N      T A B L E         Q U E R I E S
//======================================================================================================================================================
		function selectDISTINCTpositionnameFROMPOSITION(){
		$this->Connect();
		$query="SELECT position from locate_position";
		$result=$this->makeQuery($query);
		return $result;
	}
		function selectALLfromPOSITIONwhereDEPTID($deptID){
		$this->Connect();
		$query="SELECT * FROM position where dept_id = '".$deptID."' order by pos_name  ";
		$result=$this->makeQuery($query);
		return $result;
	}
//======================================================================================================================================================
//                                             U S E R S      T A B L E         Q U E R I E S
//======================================================================================================================================================
	function selectALLfromUSERTYPE(){	
		$this->Connect();
		$query = "SELECT * FROM usertype";			
		$result = $this->makeQuery($query);			
		return $result;	
	}
		function selectALLfromUSERSwhereUSERNAME($username){	
		$this->Connect();
		$query = "SELECT * FROM users WHERE username='".$username."'";			
		$result = $this->makeQuery($query);			
		return $result;	
	}
		function selectALLfromUSERSwhereUSERNAMEandSTATUS($username){	
		$this->Connect();
		$query = "SELECT * FROM users WHERE username='".$username."' AND status='active' ";			
		$result = $this->makeQuery($query);			
		return $result;	
	}
	function ViewListUsers($start, $limit){	//used in view_all_events.php
		$this->Connect();
		if($_SESSION['usertype']=='superadmin'){
		$query = "SELECT 
						*
				  FROM
				  		users
				  ORDER BY date_created DESC,username ASC 
				  LIMIT $start, $limit	";}	
		if($_SESSION['usertype']=='administrator'){
		$query = "SELECT 
						*
				  FROM
				  		users
				  WHERE usertype!='superadmin'
				  ORDER BY date_created DESC,username ASC AND branch='".$_SESSION['branchname']."'
				  LIMIT $start, $limit	";}	
		$result = $this->makeQuery($query);			
		return $result;	
	}

	function countTableUsers(){							
		$this->Connect();
		if($_SESSION['usertype']=='superadmin'){
		$query = "SELECT 
						COUNT(*) AS num 
				 FROM 
				 		users ";}
		if($_SESSION['usertype']=='administrator'){
		$query = "SELECT 
						COUNT(*) AS num 
				 FROM 
				 		users 				 
				WHERE usertype!='superadmin' AND branch='".$_SESSION['branchname']."'";}
		$result = $this->makeQuery($query);			
		$total_pages = $this->fetchArray($result);
		$total_pages = $total_pages['num'];   
		return $total_pages;
	}
	function ViewListHistories($start, $limit){	//used in view_all_events.php
		$this->Connect();
		$query = "SELECT 
						*
				  FROM
				  		sys_logs
				  ORDER BY tym_logIN DESC,tym_logOUT DESC,username ASC 
				  LIMIT $start, $limit	";			
		$result = $this->makeQuery($query);			
		return $result;	
	}

	function countTableHistories(){							
		$this->Connect();
		$query = "SELECT 
						COUNT(*) AS num 
				 FROM 
				 		sys_logs ";
		$result = $this->makeQuery($query);			
		$total_pages = $this->fetchArray($result);
		$total_pages = $total_pages['num'];   
		return $total_pages;
	}
	function ViewListEvents($start, $limit){	//used in view_all_events.php
		$this->Connect();
		$query = "SELECT 
						*
				  FROM
				  		sys_event
				 INNER JOIN 
				 		users ON sys_event.creator_name = users.username
				 INNER JOIN
				        applicant ON users.emp_id     = applicant.app_id 
				  ORDER BY sys_event.tym_act DESC,sys_event.creator_name ASC 
				  LIMIT $start, $limit	";			
		$result = $this->makeQuery($query);			
		return $result;	
	}

	function countTableEvents(){							
		$this->Connect();
		$query = "SELECT 
						COUNT(*) AS num 
				 FROM 
				 		sys_event ";
		$result = $this->makeQuery($query);			
		$total_pages = $this->fetchArray($result);
		$total_pages = $total_pages['num'];   
		return $total_pages;
	}
	
	function ViewUserEventsSelf($com,$start, $limit){	//used in view_all_events.php
		$this->Connect();
		$query = "SELECT 
						*
				  FROM
				  		sys_event
				 WHERE creator_name='".$com."'
				 ORDER BY tym_act DESC,creator_name ASC 
				 LIMIT $start, $limit	";			
		$result = $this->makeQuery($query);			
		return $result;	
	}

	function countEventsSelf($com){							
		$this->Connect();
		$query = "SELECT 
						COUNT(*) AS num 
				 FROM 
				 		sys_event WHERE creator_name='".$com."' ";
		$result = $this->makeQuery($query);			
		$total_pages = $this->fetchArray($result);
		$total_pages = $total_pages['num'];   
		return $total_pages;
	}
	
	
	function ViewListLogs($start, $limit){	//used in view_all_events.php
		$this->Connect();
		$query = "SELECT
						*
				 FROM
				 		sys_session
				 INNER JOIN 
				 		users ON sys_session.username = users.username
				 INNER JOIN
				        applicant ON users.emp_id     = applicant.app_id 
				  ORDER BY sys_session.username ASC,sys_session.tym_logOUT DESC
				  LIMIT $start, $limit	";			
		$result = $this->makeQuery($query);			
		return $result;	
	}

	function countTableLogs(){							
		$this->Connect();
		$query = "SELECT 
						COUNT(*) AS num 
				 FROM 
				 		sys_session ";
		$result = $this->makeQuery($query);			
		$total_pages = $this->fetchArray($result);
		$total_pages = $total_pages['num'];   
		return $total_pages;
	}
	
	function ViewUserLogsSelf($start, $limit){	//used in view_all_events.php
		$this->Connect();
		$query = "SELECT
						*
				 FROM
				 		sys_session
				 INNER JOIN 
				 		users ON sys_session.username = users.username
				 INNER JOIN
				        applicant ON users.emp_id     = applicant.app_id WHERE sys_session.username='".$_SESSION['com']."'
				  ORDER BY sys_session.username ASC,sys_session.tym_logOUT DESC
				  LIMIT $start, $limit	";			
		$result = $this->makeQuery($query);			
		return $result;	
	}

	function CountLogsSelf(){							
		$this->Connect();
		$query = "SELECT 
						COUNT(*) AS num 
				 FROM 
				 		sys_session WHERE sys_session.username='".$_SESSION['com']."'";
		$result = $this->makeQuery($query);			
		$total_pages = $this->fetchArray($result);
		$total_pages = $total_pages['num'];   
		return $total_pages;
	}
	function ViewUserEventsFilter($filter, $start, $limit){
		$this->Connect();
		$query = "SELECT *
				 FROM 
						sys_event				
				 WHERE creator_role = '$filter'
				 ORDER BY tym_act DESC,creator_name ASC
				 LIMIT $start, $limit	";						
		$result = $this->makeQuery($query);			
		return $result;	
	}
	
	//used in view_employee.php to count the filter employees
	function CountEventsFilter($filter){
		$this->Connect();
		$query = "SELECT COUNT(*) AS num
				FROM sys_event
				WHERE creator_role = '$filter'
				ORDER BY tym_act DESC,creator_name ASC";
		$result = $this->makeQuery($query);			
		$total_pages = $this->fetchArray($result);
		$total_pages = $total_pages['num'];   
		return $total_pages;
	}	
	
	//used in view_employee.php to display the filter employees
	function ViewUserFilter($filter, $start, $limit){
		$this->Connect();
		$query = "SELECT *
				 FROM 
						users				
				 WHERE usertype = '$filter'
				 ORDER BY date_created DESC,username ASC
				 LIMIT $start, $limit	";						
		$result = $this->makeQuery($query);			
		return $result;	
	}
	
	//used in view_employee.php to count the filter employees
	function CountUserFilter($filter){
		$this->Connect();
		$query = "SELECT COUNT(*) AS num
				FROM users
				 WHERE usertype = '$filter'
				 ORDER BY date_created DESC,username ASC";
		$result = $this->makeQuery($query);			
		$total_pages = $this->fetchArray($result);
		$total_pages = $total_pages['num'];   
		return $total_pages;
	}	
	
	
	//used in view_employee.php to display the filter employees
	function ViewUserLogsFilter($filter, $start, $limit){
		$this->Connect();
		$query = "SELECT *
				 FROM 
						sys_session				
				WHERE usertype = '$filter'
				ORDER BY sys_session.username ASC,sys_session.tym_logOUT DESC
				LIMIT $start, $limit	";						
		$result = $this->makeQuery($query);			
		return $result;	
	}
	
	//used in view_employee.php to count the filter employees
	function CountLogsFilter($filter){
		$this->Connect();
		$query = "SELECT COUNT(*) AS num
				FROM sys_session
				WHERE usertype = '$filter'
				ORDER BY sys_session.username ASC,sys_session.tym_logOUT DESC";
		$result = $this->makeQuery($query);			
		$total_pages = $this->fetchArray($result);
		$total_pages = $total_pages['num'];   
		return $total_pages;
	}	
		//used in Searching
	function SearchEqualUsers($id,$start,$limit){
		$this->Connect();	
		$query = "SELECT * 
				FROM users 
				WHERE emp_id = '$id' OR username='$id'					
				ORDER BY 
						username,emp_id limit $start, $limit";			
		$result = $this->makeQuery($query);
		return $result;
	}
	//used in searching
	function CountEqualSearchUsers($id){
		$this->Connect();
		$query = "SELECT 
				COUNT (*) AS num 
				FROM users
				WHERE emp_id = '$id' OR username='$id'
				ORDER BY username,emp_id"; 
		$result = $this->makeQuery($query);			
		$total_pages = $this->fetchArray($result);
		$total_pages = $total_pages['num'];   
		return $total_pages;
	}
	//used in searching
	function SearchLikeUsers($key,$start,$limit){
	$this->Connect();	
	$query = "SELECT *
			  FROM users 
			  WHERE emp_id	LIKE '%$key%' OR
					username LIKE '%$key%' 
			ORDER BY username,emp_id limit $start, $limit";				
	$result = $this->makeQuery($query);
	return $result;
	}
	//used in searching
	function CountLikeSearchUsers($key){
		$this->Connect();
		$query = "SELECT 
				  COUNT (*) AS num
				  FROM users 
			      WHERE 
					   emp_id	LIKE '%$key%' OR
					   username LIKE '%$key%' 
			      ORDER BY username,emp_id";				
		$result = $this->makeQuery($query);			
		$total_pages = $this->fetchArray($result);
		$total_pages = $total_pages['num'];   
		return $total_pages;
	}
		//used in view_employee.php to display active employees
	function ViewEmployeeAdmin(){
		$this->Connect();
		$query = "SELECT 
						employee.emp_id, lastname, firstname, middlename, sub_code, current_status, position, dept_id 
				 FROM 
						applicant				
				 INNER JOIN employee ON employee.emp_id = applicant.app_id	
				 WHERE current_status = 'active' AND sub_code=".$_SESSION['bunit_code']."
				 ORDER BY lastname";
		$result = $this->makeQuery($query);			
		return $result;	
	}
		//used in view_employee.php to display active employees
	function ViewEmployeeSuperadmin(){
		$this->Connect();
		$query = "SELECT 
						employee.emp_id, lastname, firstname, middlename, sub_code, current_status, photo, position, dept_id,
						current_status
				 FROM 
						applicant				
				 INNER JOIN employee ON employee.emp_id = applicant.app_id	
				 WHERE current_status = 'active'
				 ORDER BY lastname";						
		$result = $this->makeQuery($query);			
		return $result;	
	}
		//used in view_employee.php to display active employees
	function ViewSearchEmployee($key){
		$this->Connect();
		$query = "SELECT 
						employee.emp_id, lastname, firstname, middlename, sub_code, current_status, photo, position, dept_id,
						current_status
				 FROM 
						applicant				
				 INNER JOIN employee ON employee.emp_id = applicant.app_id	
				 WHERE current_status = 'active' AND sub_code=".$_SESSION['bunit_code']." AND lastname='$key'
				 ORDER BY lastname";						
		$result = $this->makeQuery($query);			
		return $result;	
	}
//======================================================================================================================================================
//                                      S U B S I D I A R I E S      T A B L E         Q U E R I E S
//======================================================================================================================================================
		function selectALLfromSUBSIDIARIESasc(){	
		$this->Connect();
		$query = " SELECT sub_code,sub_name FROM subsidiaries order by sub_name ASC ";			
		$result = $this->makeQuery($query);			
		return $result;	
	}
		function selectALLfromSUBSIDIARIESwhere($subs){	
		$this->Connect();
		$query = " SELECT * FROM subsidiaries where sub_code='$subs' ";			
		$result = $this->makeQuery($query);			
		return $result;	
	}
	function selectDEPARTMENTwhereSUB($dep,$su){	
		$this->Connect();
		$query = " SELECT * FROM department where sub_code='$su' AND dept_id='$dep' ";			
		$result = $this->makeQuery($query);			
		return $result;	
	}
	function selectDEPARTMENTwhereSUBDEP($dep,$su){	
		$this->Connect();
		$query = " SELECT * FROM department where sub_code='$su' AND dept_name='$dep' ";			
		$result = $this->makeQuery($query);			
		return $result;	
	}
//======================================================================================================================================================
//                                             A P P L I C A N T      T A B L E         Q U E R I E S
//======================================================================================================================================================	

	
	function selectApp_id($ln, $fn, $mn){
		$this->Connect();
		$query = "SELECT * from applicant where lastname = '$ln' and middlename = '$mn' and firstname = '$fn' ";
		$result = $this->makeQuery($query);
		return $result;
	}

	function getCODE($ln, $fn, $mn){
		$this->Connect();
		$query = "SELECT app_code from applicants where lastname = '$ln' and middlename = '$mn' and firstname = '$fn' ";
		$result = $this->makeQuery($query);
		return $result;
	}
	
		function selectTagged($ln, $fn, $mn){
		$this->Connect();
		$query = "SELECT * from applicants where lastname = '$ln' and middlename = '$mn' and firstname = '$fn' and status='tagged'";
		$result = $this->makeQuery($query);
		return $result;
	}

	function deleteTagged($app){
		$this->Connect();
		$query = "delete from applicants where app_code = '$app' ";
		$result = $this->makeQuery($query);
		return $result;
	}
	function updateTagged($app, $app_code){
		$this->Connect();
		$query = "update initial_requirements set app_code = '$app' where app_code =  '$app_code' ";
		$result = $this->makeQuery($query);
		return $result;
	}
	function updateApplicants($app_code, $dates, $stat){
		$this->Connect();
		$query = "update applicants set status = '$stat', date_time = '$dates' where app_code =  '$app_code' ";
		$result = $this->makeQuery($query);
		return $result;
	}
	function updateApplicanty($appcode,$stat){
		$this->Connect();
		$query = "update applicants set status = '$stat' where app_code =  '$appcode' ";
		$result = $this->makeQuery($query);
		return $result;
	}
	function selectDistinctName($field){
		$this->Connect();
		$query = "SELECT distinct $field from applicant";
		$result = $this->makeQuery($query);
		return $result;	
	}	
			
	function selectInitialRequirements($app_code){
		$this->Connect();
		$query = "SELECT * FROM `initial_requirements` WHERE initial_requirements.app_code = '$app_code' ";
		$result = $this->makeQuery($query);
		return $result;	
	}
	
	function updateInitialRequirements($appcode, $req, $date_time){
		$this->Connect();
		$query = "UPDATE `initial_requirements` SET requirement_status = 'passed', date_time = '$date_time' WHERE app_code = '$appcode' and requirement_name = '$req' ";
		$result = $this->makeQuery($query);
		return $result;	
	}
	
	function updateRequirements($appcode){
		$this->Connect();
		$query = "UPDATE applicants SET status = 'tagged' WHERE app_code = '$appcode' ";
		$result = $this->makeQuery($query);
		return $result;	
	}
	function checkReqStat($app){
		$this->Connect();
		$query = "SELECT * FROM initial_requirements WHERE app_code = '$app' and requirement_status = 'pending' ";
		$result = $this->makeQuery($query);
		return $result;	
	}
	
	function insertIntoInitialReq($app, $req_name, $dates, $stat, $staff){
		$this->Connect();
		$query = "INSERT INTO initial_requirements VALUES ('$app','$req_name','$dates','$stat','$staff')";
		$result = $this->makeQuery($query);
		return $result;	
	}
	function insertIntoFinalReq($app, $req_name, $dates, $stat, $staff){
		$this->Connect();
		$query = "INSERT INTO final_requirements VALUES ('$app','$req_name','$dates','$stat','$staff')";
		$result = $this->makeQuery($query);
		return $result;	
	}
	
	function insertIntoApplicants($ln, $fn, $mn, $po, $des, $stat, $dates){
		$this->Connect();		
		$query = "INSERT INTO applicants VALUES ('', '$ln' ,'$fn' ,'$mn', '$po', '$des', '$stat', '$dates') ";		
		$result = $this->makeQuery($query);
		return $result;	
	}
	function insertIntomApplicants($code, $s_f, $s_m, $s_l, $m, $gen, $cv){
		$this->Connect();
		$query = "INSERT INTO married_applicants VALUES ('$code', '$s_f', '$s_m', '$s_l', '$m', '$gen', '$cv') ";		
		$result = $this->makeQuery($query);
		return $result;	
	}
	function insertIntommApplicants($gen, $cv){
		$this->Connect();		
		$query = "INSERT INTO married_applicants VALUES ('', '', '$gen', '$cv') ";		
		$result = $this->makeQuery($query);
		return $result;	
	}
	function selectmarried($app){
		$this->Connect();		
		$query = "SELECT * FROM application_newdetails WHERE app_code='$app'";		
		$result = $this->makeQuery($query);
		return $result;	
	}
	
	function selectALLfromAPPLICANTwhereYEAR($year,$order){	
		$this->Connect();
		$query = "SELECT * FROM applicant where year = '".$year."' order by id DESC limit 1";			
		$result = $this->makeQuery($query);			
		return $result;	
	}
	function selectALLfromAPPLICANTwhereAPPID($a){	
		$this->Connect();
		$query = "SELECT * from applicant WHERE app_id = '".$a."'";			
		$result = $this->makeQuery($query);			
		return $result;	
	}
	function selectALLfromAPPLICANTSappcode($code){	
		$this->Connect();
		$query = "SELECT lastname,firstname,middlename from applicants WHERE app_code = '".$code."'";			
		$result = $this->makeQuery($query);			
		return $result;	
	}
		function innerJOINapplicantsANDappdet($a){	
		$this->Connect();
		$query = "SELECT * FROM applicant INNER JOIN application_details ON applicant.app_id = application_details.app_id where applicant.app_id='".$a."' ";			
		$result = $this->makeQuery($query);			
		return $result;	
	}
		function updateAPPLICANTsetPHOTO($a,$location){	
		$this->Connect();
		$query = " UPDATE applicant SET photo = '".$location."' where app_id = '".$a."' ";			
		$result = $this->makeQuery($query);			
		return $result;	
	}




//used in insert_applicant.php
	function AffectedApplicants($lastname,$firstname,$middlename){	
		$this->Connect();
		$query = "SELECT 
				  * 
			      FROM applicant WHERE lastname = '".$lastname."' AND firstname = '".$firstname."' AND middlename = '".$middlename."'";			
		$result = $this->makeQuery($query);			
		return $result;	
	}
//used in insert_applicant.php
	function JoinApplicants($emp_id){	
		$this->Connect();
		$query = "SELECT DISTINCT
				  applicant.app_id, applicant.lastname, applicant.firstname, applicant.middlename, applicant.home_address, 
				  current_status, position, startdate, eocdate, emp_type, dept_name, sub_name, section_name, emp_id
				  
				  FROM applicant
				  				
				  INNER JOIN employee ON employee.emp_id = applicant.app_id
				  LEFT JOIN subsidiaries ON employee.sub_code = subsidiaries.sub_code
				  LEFT JOIN department ON employee.dept_id = department.dept_id
				  LEFT JOIN section ON employee.section_id = section.section_id
				  WHERE current_status = 'active' AND emp_id='".$emp_id."'";
				  			
		$result = $this->makeQuery($query);			
		return $result;	
	}
//used in view_list_applicants.php
	function ViewListApplicants($start, $limit){	
		$this->Connect();
		$query = "SELECT 
						applicant.app_id,lastname,firstname, middlename, home_address, application_status, position_applied, photo, attainment, contactno, course
				  FROM
				  		applicant 
				  INNER JOIN
				  		application_details ON applicant.app_id = application_details.app_id 		
				  LIMIT $start, $limit	";			
		$result = $this->makeQuery($query);			
		return $result;	
	}
//used in view_list_applicants.php to count
	function countTable(){							
		$this->Connect();
		$query = "SELECT 
						COUNT(*) AS num 
				 FROM 
				 		applicant ";
		$result = $this->makeQuery($query);			
		$total_pages = $this->fetchArray($result);
		$total_pages = $total_pages['num'];   
		return $total_pages;
	}
	//used in view_blacklist.php
	function ViewBlackListApplicants($start,$limit){
		$this->Connect();
		$query = "SELECT *
				  FROM
				 		applicant
                 JOIN 
				 		blacklist ON applicant.app_id = blacklist.app_id 
				 WHERE blacklist.status!='Removed'
				 ORDER BY record_no DESC
				 LIMIT $start, $limit";	
		$result = $this->makeQuery($query);
		return $result;	
	}
	function countBlackList(){							//used in view_list_applicants.php to count
		$this->Connect();
		$query = "SELECT 
						COUNT(*) AS num 
				 FROM 
				 		blacklist
				 WHERE blacklist.status!='Removed'";
		$result = $this->makeQuery($query);			
		$total_pages = $this->fetchArray($result);
		$total_pages = $total_pages['num'];   
		return $total_pages;
	}
	//used in view_applicants.php to display the search value
	function ViewSearchApplicants($key){
		$this->Connect();
		$query  = "SELECT 
						 app_id,lastname, firstname, middlename,date_hired,application_status
				  FROM
				  		 applicant 
				 
				  WHERE 
				  		 date_hired between date_sub(CURDATE(), INTERVAL 3 MONTH) and date_hired and lastname = '$key'";
		$result = $this->makeQuery($query);
		return $result;  		
	}
	//used in view_applicants.php to display all applicants who are not blacklisted
	function ViewApplicants(){
		$this->Connect();
		$query  = "  SELECT DISTINCT 
						 applicant.app_id, lastname, firstname, middlename, date_hired
				   FROM 
				   		applicant
				   ";				   
					$result = $this->makeQuery($query);
		return $result;  		
	}
	//used in view_applicants.php to display applicant for blacklisting
	function ViewForBlacklistApplicants(){
		$this->Connect();
		$query = "SELECT applicant.app_id, lastname, firstname, middlename
				FROM 
						applicant
				INNER JOIN application_details ON applicant.app_id = application_details.app_id				
				WHERE 
						application_details.status != 'blacklisted'";
		$result = $this->makeQuery($query);			
		return $result;	
	}	
	//used in view_applicants.php to display the search value to be blacklisted
	function ViewSearchForBlacklistApplicants($key){
		$this->Connect();
		$query = "SELECT DISTINCT
						applicant.app_id, lastname, firstname, middlename
				 FROM 
						applicant				
				 WHERE 
						applicant.app_id = '$key' 
				";			
		$result = $this->makeQuery($query);			
		return $result;	
	}	
	//used in Searching
	function SearchEqualApplicants($id,$start,$limit){
		$this->Connect();	
		$query = "SELECT * 
				FROM 
						applicant 
				INNER JOIN
				  		application_details ON applicant.app_id = application_details.app_id 			
				WHERE 
						applicant.app_id = '$id'						
				ORDER BY 
						lastname,firstname limit $start, $limit";			
		$result = $this->makeQuery($query);
		return $result;
	}
	//used in Searching
	function SearchEqualBlacklisted($id,$start,$limit){
		$this->Connect();	
		$query = "SELECT * 
				FROM 
						applicant 
				JOIN
				  		blacklist ON applicant.app_id = blacklist.app_id 			
				WHERE 
						applicant.app_id = '$id' AND blacklist.status!='Removed'
				ORDER BY 
						lastname,firstname limit $start, $limit";			
		$result = $this->makeQuery($query);
		return $result;
	}
	//used in searching
	function CountEqualSearch($id){
		$this->Connect();
		$query = "SELECT 
				COUNT 
						(*) AS num 
				FROM 
						applicant
				INNER JOIN
				  		application_details ON applicant.app_id = application_details.app_id 			 
				WHERE 
						app_id = '$id'
				ORDER BY
						lastname,firstname "; 
		$result = $this->makeQuery($query);			
		$total_pages = $this->fetchArray($result);
		$total_pages = $total_pages['num'];   
		return $total_pages;
	}
	//used in searching
	function CountEqualSearchBlacklisted($id){
		$this->Connect();
		$query = "SELECT 
				COUNT 
						(*) AS num 
				FROM 
						applicant
				JOIN
				  		blacklist ON applicant.app_id = blacklist.app_id 			 
				WHERE 
						applicant.app_id = '$id' AND blacklist.status!='Removed'
				ORDER BY
						lastname,firstname "; 
		$result = $this->makeQuery($query);			
		$total_pages = $this->fetchArray($result);
		$total_pages = $total_pages['num'];   
		return $total_pages;
	}
	//used in searching
	function SearchLikeApplicants($key,$start,$limit){
	$this->Connect();	
	$query = "SELECT 
					applicant.app_id,lastname,firstname, middlename, home_address, application_status, position_applied, photo, contactno, attainment, course
			  FROM
					applicant 
			  INNER JOIN
					application_details ON applicant.app_id = application_details.app_id 
			  WHERE 
					applicant.app_id	LIKE '%$key%' ||
					lastname LIKE '%$key%' || 
					firstname LIKE '%$key%' || 
					middlename LIKE '%$key%' 
			ORDER BY lastname,firstname limit $start, $limit";				
	$result = $this->makeQuery($query);
	return $result;
	}
	//used in searching
	function SearchLikeApplicantsBlacklisted($key,$start,$limit){
	$this->Connect();	
	$query = "SELECT 
					*
			  FROM
					applicant 
			  JOIN
					blacklist ON applicant.app_id = blacklist.app_id 
			  WHERE 
					applicant.app_id	LIKE '%$key%' ||
					lastname LIKE '%$key%' || 
					firstname LIKE '%$key%' || 
					middlename LIKE '%$key%' 
					AND blacklist.status!='Removed'
			ORDER BY lastname,firstname limit $start, $limit";				
	$result = $this->makeQuery($query);
	return $result;
	}
	//used in searching
	function CountLikeSearch($key){
		$this->Connect();
		$query = "SELECT 
				COUNT
						(*) AS num
				FROM 
						applicant 
				WHERE 
						app_id	LIKE '%$key%' || lastname like '%$key%' || firstname like '%$key%' || middlename like '%$key%'
				ORDER BY lastname,firstname "; 
		$result = $this->makeQuery($query);			
		$total_pages = $this->fetchArray($result);
		$total_pages = $total_pages['num'];   
		return $total_pages;
	}
	function splitString($v,$key){
		$value 	= explode("$v",$key); 
		if(count($value)>1) {
			$id		= $value[0];			
		}		
		return @$id;
	}
	/*searching in mview employee*/
	function selectActiveEmployee(){	
		$this->Connect();
		$query = "SELECT distinct(emp_id), name FROM employee3 WHERE current_status = 'active'";			
		$result = $this->makeQuery($query);			
		return $result;	
	}
	function selectAllEmployee(){	
		$this->Connect();
		$query = "SELECT distinct(emp_id), name FROM employee3";			
		$result = $this->makeQuery($query);			
		return $result;	
	}
	
	//used in view_list_applicants.php to display the filter employees
	function ViewApplicantFilterAll($filter, $start, $limit){
		$this->Connect();
		$query = "SELECT applicant.app_id , lastname, firstname, middlename, home_address, current_status, photo, attainment, application_status, 
						 contactno, position_applied
				 FROM 
						applicant				
				 INNER JOIN application_details ON application_details.app_id = applicant.app_id	
				 WHERE application_details.application_status = '$filter'
				 LIMIT $start, $limit	";
		$result = $this->makeQuery($query);			
		return $result;	
	}
	//used in view_list_applicants.php to count the filter employees
	function CountApplicantFilterAll($filter){
		$this->Connect();
		$query = "SELECT COUNT(DISTINCT application_details.app_id ) AS num
				FROM application_details
				WHERE application_status = '$filter'";
		$result = $this->makeQuery($query);			
		$total_pages = $this->fetchArray($result);
		$total_pages = $total_pages['num'];   
		return $total_pages;
	}
//======================================================================================================================================================
//                                           E M P L O Y E E      T A B L E         Q U E R I E S
//======================================================================================================================================================

	//used in view_employee.php to display active employees
	function ViewEmployee($start, $limit){
		$this->Connect();
		$query = "SELECT emp_id, name, current_status, company_code, bunit_code, dept_code
				 FROM employee3 	
				 WHERE current_status = 'active' order by name
				 LIMIT $start, $limit	";		//AND bunit_code=".$_SESSION['bunit_code']."				
		$result = $this->makeQuery($query);			
		return $result;	
	}
	
	//used in view_employee.php to count the number of employees
	function CountEmployee(){
		$this->Connect();
		$query = "SELECT count(record_no) AS num
				FROM employee3
				WHERE current_status = 'active' ";
		$result = $this->makeQuery($query);		//AND bunit_code=".$_SESSION['bunit_code'];	
		$total_pages = $this->fetchArray($result);
		$total_pages = $total_pages['num'];   
		return $total_pages;
	}
	// for promo
	//used in view_promo.php to display active promo
	function ViewPromo($start, $limit){
		$this->Connect();
		$query = "SELECT DISTINCT(employee3.emp_id), name, current_status, position, emp_type, promo_company, promo_department, al_tag, al_tal, icm, pm, abenson_tag, abenson_icm, promo_type
				 FROM employee3
				 INNER JOIN promo_record ON employee3.emp_id=promo_record.emp_id 	
				 WHERE employee3.current_status = 'active' and employee3.emp_type='promo' order by name
				 LIMIT $start, $limit	";				
		$result = $this->makeQuery($query);			
		return $result;	
	}
	function selectActivePromo(){	
		$this->Connect();
		$query = "SELECT distinct(emp_id), name FROM employee3 WHERE emp_type= 'Promo' AND current_status = 'Active'";			
		$result = $this->makeQuery($query);			
		return $result;	
	}
	function selectAllPromo(){	
		$this->Connect();
		$query = "SELECT distinct(emp_id), name FROM employee3 WHERE emp_type= 'Promo' AND current_status != 'blacklisted' ";			
		$result = $this->makeQuery($query);			
		return $result;	
	}
	/*function ViewPromoFilter($key,$filter,$start,$limit){
		$this->Connect();
		$query = "SELECT DISTINCT(employee3.emp_id), name, current_status, position, emp_type, promo_company, promo_department, al_tag, al_tal, icm, pm, abenson_tag, abenson_icm, promo_type
				 FROM employee3
				 LEFT JOIN promo_record ON employee3.emp_id=promo_record.emp_id 	
				 WHERE employee3.current_status = 'Active' and employee3.emp_type='Promo' and promo_department = '$filter' $key order by name
				 LIMIT $start, $limit	";						
		$result = $this->makeQuery($query);			
		return $result;	
	}
	function CountPromoFilter($key,$filter){
		$this->Connect();
		$query = "SELECT COUNT(DISTINCT employee3.emp_id) AS num, promo_department
				 FROM employee3
				 LEFT JOIN promo_record ON employee3.emp_id=promo_record.emp_id 	
				 WHERE employee3.current_status = 'Active' and employee3.emp_type='Promo' and promo_department = '$filter' $key ";
		$result = $this->makeQuery($query);			
		$total_pages = $this->fetchArray($result);
		$total_pages = $total_pages['num'];   
		return $total_pages;
	}*/	
	/*newly updated function*/
	function ViewPromoFilter($key,$filter,$start,$limit){
		$this->Connect();
		$query = "SELECT DISTINCT(employee3.emp_id), name, current_status, position, emp_type, promo_company, promo_department, al_tag, al_tal, icm, pm, abenson_tag, abenson_icm, cdc, berama, al_tub, promo_type
				 FROM employee3
				 LEFT JOIN promo_record ON employee3.emp_id=promo_record.emp_id 	
				 WHERE employee3.current_status = 'Active' and employee3.emp_type='Promo' $filter $key order by name
				 LIMIT $start, $limit	";						
		$result = $this->makeQuery($query);			
		return $result;	
	}
	function CountPromoFilter($key,$filter){
		$this->Connect();
		$query = "SELECT COUNT(DISTINCT employee3.emp_id) AS num, promo_department
				 FROM employee3
				 LEFT JOIN promo_record ON employee3.emp_id=promo_record.emp_id 	
				 WHERE employee3.current_status = 'Active' and employee3.emp_type='Promo' $filter $key ";
		$result = $this->makeQuery($query);			
		$total_pages = $this->fetchArray($result);
		$total_pages = $total_pages['num'];   
		return $total_pages;
	}	
	function SearchLikePromo($filter, $key,$start,$limit){
		$this->Connect();
		if(($_SESSION['usertype']=='administrator')||($_SESSION['type']=='placement')){			
		$query = "SELECT DISTINCT(employee3.emp_id), name, current_status, position, emp_type, promo_company, promo_department, al_tag, al_tal, icm, pm, abenson_tag, abenson_icm, cdc, berama, al_tub, promo_type
				 FROM employee3
				 LEFT JOIN promo_record ON employee3.emp_id=promo_record.emp_id 	
				 WHERE employee3.current_status = 'Active' and employee3.emp_type='Promo' $filter and name like '%$key%' || employee3.emp_id like '%$key%' order by name limit $start, $limit"; //and bunit_code = ".$_SESSION['bunit_code']."
				 }
		$result = $this->makeQuery($query);
		return $result;
		}
	function selectActiveUsers()
	{
		$this->Connect();
		$query = "SELECT distinct(users.emp_id), name from users
				  inner join  employee3 on employee3.emp_id=users.emp_id
				  where user_status='Active'";
		$result = $this->makeQuery($query);
		return $result;
	}

	//newly added to promo quiries
	function selectActivePlacementUsers(){
		$this->Connect();
		$query = "SELECT distinct(users.emp_id), name from employee3, promo_user, users
				  WHERE employee3.emp_id = promo_user.emp_id AND employee3.emp_id=users.emp_id AND
				  users.user_status='active'";
		$result = $this->makeQuery($query);
		return $result;
	}
	function ViewPromo2($start, $limit){
		$this->Connect();
		$query = "SELECT DISTINCT(employee3.emp_id), name, current_status, position, emp_type
				 FROM employee3 
				 LEFT JOIN promo_record on employee3.emp_id=promo_record.emp_id	
				 WHERE current_status = 'active' and emp_type='Promo' order by name
				 LIMIT $start, $limit	";					
		$result = $this->makeQuery($query);			
		return $result;	
	}
	function ViewPromoRecord($id)
	{
		$this->Connect();
		$query = "SELECT promo_company, promo_department, al_tag, al_tal, icm, pm, abenson_tag, abenson_icm, cdc, berama, cdc, berama, al_tub, promo_type
				 FROM promo_record	
				 WHERE emp_id='$id'";				
		$result = $this->makeQuery($query);			
		return $result;	
	}

	function CountPromo2(){
		$this->Connect();
		$query = "SELECT count(employee3.emp_id)
				 FROM employee3 
				 LEFT JOIN promo_record on employee3.emp_id=promo_record.emp_id	
				 WHERE current_status = 'Active' and emp_type='Promo' order by name";		//AND bunit_code=".$_SESSION['bunit_code']."				
		$result = $this->makeQuery($query);		
		$total_pages = $this->fetchArray($result);
		$total_pages = $total_pages['count(employee3.emp_id)'];   
		return $total_pages;
	}
	//used in view_employee.php to count the number of employees
	function CountPromo(){
		$this->Connect();
		$query = "SELECT COUNT(DISTINCT employee3.emp_id ) AS num
				FROM employee3
				 INNER JOIN promo_record ON employee3.emp_id=promo_record.emp_id 	
				 WHERE employee3.current_status = 'active' and employee3.emp_type='promo' order by name ";
		$result = $this->makeQuery($query);		//AND bunit_code=".$_SESSION['bunit_code'];	
		$total_pages = $this->fetchArray($result);
		$total_pages = $total_pages['num'];   
		return $total_pages;
	}
	function selectwitness1()
	{
		$this->Connect();
		$query = "SELECT distinct(witness1) FROM employment_witness";
		$result = $this->makeQuery($query);
		return $result;
	}
	function selectwitness2()
	{
		$this->Connect();
		$query = "SELECT distinct(witness2) FROM employment_witness";
		$result = $this->makeQuery($query);
		return $result;
	}
	//end promo quiries
	
	/*copied */
	function ViewEmpRec($start, $limit){
		$this->Connect();
		$query = "SELECT * FROM `employee3`
				 WHERE current_status = 'active' and emp_type = 'Contractual' order by sub_code, dept_id, name
				 LIMIT $start, $limit";		 						
		$result = $this->makeQuery($query);			
		return $result;	
	}
	/*6 to 11 key personnel */
	function ViewEmpRecFilter($condition,$start, $limit){
		$this->Connect();
		$query = "SELECT * ROM `employee3`
				 WHERE current_status = 'active' and emp_type = 'Contractual' and emp_cat != '$condition' and emp_cat != ''  order by sub_code, dept_id, name
				 LIMIT $start, $limit";		 						
		$result = $this->makeQuery($query);			
		return $result;	
	}
	/*6 to 11 key personnel */
	function CountEmpRecFilter($condition){
		$this->Connect();
		$query = "SELECT COUNT(distinct(emp_id)) as num 
				 FROM `employee3`
				 WHERE current_status = 'active' and emp_type = 'Contractual' and emp_cat != '$condition' and emp_cat != '' ";			 						
		$result = $this->makeQuery($query);			
		$total_pages = $this->fetchArray($result);
		$total_pages = $total_pages['num'];   
		return $total_pages;
	}
		/*6 to 11 non key personnel */
	function ViewEmpRecFilterN($condition,$start, $limit){
		$this->Connect();
		$query = "SELECT * FROM `employee3`
				 WHERE current_status = 'active' and emp_type = 'Contractual' and emp_cat = '$condition' and emp_cat != ''  order by name
				 LIMIT $start, $limit";		 						
		$result = $this->makeQuery($query);			
		return $result;	
	}
	/*6 to 11 non key personnel */
	function CountEmpRecFilterN($condition){
		$this->Connect();
		$query = "SELECT COUNT(distinct(emp_id)) as num
				 FROM `employee3`
				 WHERE current_status = 'active' and emp_type = 'Contractual' and emp_cat = '$condition' and emp_cat != ''   "; 						
		$result = $this->makeQuery($query);			
		$total_pages = $this->fetchArray($result);
		$total_pages = $total_pages['num'];   
		return $total_pages;
	}
	/**use in regularization*/
	function SelectNames($key,$start,$limit){
		$this->Connect();
		$query = "SELECT * FROM `employee3`
				 WHERE current_status = 'active' and emp_type = 'Contractual' and name like '%$key%' order by name
				 LIMIT $start, $limit";						 
		$result = $this->makeQuery($query);			
		return $result;	
	}
	/*count like search */
	function CountEmpNames($key){
		$this->Connect();
		$query = "SELECT COUNT(distinct(emp_id)) as num
				 FROM `employee3`
				 WHERE current_status = 'active' and emp_type = 'Contractual' and name like '%$key%' "; 						
		$result = $this->makeQuery($query);			
		$total_pages = $this->fetchArray($result);
		$total_pages = $total_pages['num'];   
		return $total_pages;
	}		
	//used in view_employee.php to count the number of employees
	function CountEmpRec(){
		$this->Connect();
		$query = "SELECT COUNT(distinct(emp_id)) as num
				 FROM `employee3`
				 WHERE current_status = 'active' and emp_type = 'Contractual' ";	
		$result = $this->makeQuery($query);			
		$total_pages = $this->fetchArray($result);
		$total_pages = $total_pages['num'];   
		return $total_pages;
	}
	/*end*/
	
	//used in view_employee.php to display the filter employees
	function ViewEmployeeFilter($key,$filter,$start,$limit){
		$this->Connect();
		$query = "SELECT DISTINCT emp_id, name, current_status, company_code, bunit_code, dept_code								
				 FROM employee3
				 WHERE current_status = 'active' $filter $key order by name
				 LIMIT $start, $limit	";						
		$result = $this->makeQuery($query);			
		return $result;	
		/*
		$this->Connect();
		$query = "SELECT DISTINCT emp_id, name, current_status, company_code, bunit_code, dept_code								
				 FROM employee3
				 WHERE current_status = 'active' and company_code = '$filter' $key order by name
				 LIMIT $start, $limit	";						
		$result = $this->makeQuery($query);			
		return $result;	
		*/
	}

	//used in view_employee.php to count the filter employees
	function CountFilter($key,$filter){
		$this->Connect();
		$query = "SELECT COUNT(DISTINCT emp_id) AS num
				FROM employee3
				WHERE current_status = 'active' $filter $key ";
		$result = $this->makeQuery($query);			
		$total_pages = $this->fetchArray($result);
		$total_pages = $total_pages['num'];   
		return $total_pages;
	}	
	
		//used in view_employee.php to display active employees
	function ViewEmployeeAll($start, $limit){
		$this->Connect();
		if($_SESSION['usertype']=='superadmin'){
		$query = "SELECT DISTINCT(emp_id), name, home_address, current_status, photo
				 FROM 
						applicant				
				 INNER JOIN employee3 ON employee3.emp_id = applicant.app_id	
				 WHERE current_status = 'active'
				 LIMIT $start, $limit";}
		if($_SESSION['usertype']!='administrator'){ //if($_SESSION['usertype']=='administrator'){
		$query = "SELECT DISTINCT(emp_id),name, home_address, current_status, photo
				 FROM 
						applicant				
				 INNER JOIN employee3 ON employee3.emp_id = applicant.app_id	
				 WHERE current_status = 'active'  AND sub_code=".$_SESSION['bunit_code']."
				 LIMIT $start, $limit	";}
		$result = $this->makeQuery($query);			
		return $result;	
	}
	
	//used in view_employee.php to count the number of employees
	function CountEmployeeAll(){
		$this->Connect();
		if($_SESSION['usertype']=='superadmin'){
		$query = "SELECT COUNT(DISTINCT(emp_id)) AS num
				FROM employee3
				WHERE current_status = 'active'";}
		if($_SESSION['usertype']=='administrator'){
		$query = "SELECT COUNT(DISTINCT(emp_id)) AS num
				FROM employee3
				WHERE current_status = 'active' AND bunit_code='".$_SESSION['bunit_code']."'";
				}
		$result = $this->makeQuery($query);			
		$total_pages = $this->fetchArray($result);
		$total_pages = $total_pages['num'];   
		return $total_pages;
	}
	
	//used in view_employee.php to display the filter employees
	function ViewEmployeeFilterAll($filter, $start, $limit){
		$this->Connect();
		if($_SESSION['usertype']=='superadmin'){
		$query = "SELECT DISTINCT
						(employee.emp_id) , lastname, firstname, middlename, home_address, current_status, photo
				 FROM 
						applicant				
				 INNER JOIN employee3 ON employee3.emp_id = applicant.app_id	
				 WHERE current_status = '$filter'
				 LIMIT $start, $limit	";}
		if($_SESSION['usertype']=='administrator'){
		$query = "SELECT DISTINCT
						(employee3.emp_id) , lastname, firstname, middlename, home_address, current_status, photo
				 FROM 
						applicant				
				 INNER JOIN employee3 ON employee.emp_id = applicant.app_id	
				 WHERE current_status = '$filter' AND bunit_code=".$_SESSION['bunit_code']."
				 LIMIT $start, $limit	";}
		$result = $this->makeQuery($query);			
		return $result;	
	}
	
	//used in view_employee.php to count the filter employees
	function CountFilterAll($filter){
		$this->Connect();
		if($_SESSION['usertype']=='superadmin'){
		$query = "SELECT COUNT(DISTINCT employee3.emp_id ) AS num
				FROM employee3
				WHERE current_status = '$filter'";}
		if($_SESSION['usertype']=='administrator'){
		$query = "SELECT COUNT(DISTINCT employee3.emp_id ) AS num
				FROM employee3
				WHERE current_status = '$filter' ";}
			//	AND bunit_code='".$_SESSION['bunit_code']."'"
		$result = $this->makeQuery($query);			
		$total_pages = $this->fetchArray($result);
		$total_pages = $total_pages['num'];   
		return $total_pages;
	}
	//used in Searching
	function SearchEqualEmployee($id,$start,$limit){
		$this->Connect();
		if(($_SESSION['usertype']=='administrator')||($_SESSION['type']=='placement')){	
		$query = "SELECT DISTINCT(emp_id), name, current_status, photo, company_code, bunit_code, dept_code
				 FROM 
						applicant				
				 INNER JOIN employee3 ON employee3.emp_id = applicant.app_id	
				 WHERE current_status = 'active' and employee3.emp_id = '$id'		
				 ORDER BY 
						name $start, $limit";
		}			// AND employee3.bunit_code=".$_SESSION['bunit_code']."			
						
		if($_SESSION['usertype']=='superadmin'){	
		$query = "SELECT DISTINCT(emp_id), name, current_status, photo, company_code, bunit_code, dept_code
				 FROM 
						applicant				
				 INNER JOIN employee3 ON employee3.emp_id = applicant.app_id	
				 WHERE current_status = 'active' and employee3.emp_id = '$id' AND employee3.bunit_code=".$_SESSION['bunit_code']."		
				 ORDER BY 
						name $start, $limit";}
		$result = $this->makeQuery($query);
		return $result;
	}
	//used in searching
	function CountEqualSearchEmployee($id){
		$this->Connect();
		$query = "SELECT 
				COUNT 
						(*) AS num 
				FROM 
						applicant
				INNER JOIN
				  		application_details ON applicant.app_id = application_details.app_id 			 
				WHERE 
						app_id = '$id'
				ORDER BY
						lastname,firstname "; 
		$result = $this->makeQuery($query);			
		$total_pages = $this->fetchArray($result);
		$total_pages = $total_pages['num'];   
		return $total_pages;
	}
	//used in searching
	function SearchLikeEmployee($filter, $key,$start,$limit){
		$this->Connect();
		if(($_SESSION['usertype']=='administrator')||($_SESSION['type']=='placement')){			
		$query = "SELECT 
						distinct(emp_id), name, current_status, company_code, bunit_code, dept_code
				 from
				 		EMPLOYEE3 
				 where current_status = 'active' $filter and name like '%$key%' || emp_id like '%$key%' order by name limit $start, $limit"; //and bunit_code = ".$_SESSION['bunit_code']."
				 }
		if($_SESSION['usertype']=='superadmin'){	
		$query = "SELECT 
						distinct(emp_id), name, current_status, photo, home_address
				 from
				 		EMPLOYEE3 inner join applicant on employee3.emp_id = applicant.app_id
				 where name like '%$key%' || emp_id like '%$key%' order by name limit $start, $limit";
				 }
		$result = $this->makeQuery($query);
		return $result;
		}
	//used in searching
	function CountLikeSearchEmployee($key){
		$this->Connect();
		if(($_SESSION['usertype']=='administrator')||($_SESSION['type']=='placement')){	
		$query = "SELECT 
						count(distinct(emp_id)) as num
				 from
				 		EMPLOYEE3 
				 where current_status = 'active' and bunit_code = ".$_SESSION['bunit_code']." and name like '%$key%' || emp_id like '%$key%' ";	
		}
		if($_SESSION['usertype']=='superadmin'){	
		$query = "SELECT 
						count(distinct(emp_id)) as num
				 from
				 		EMPLOYEE3 
				 where name like '%$key%' || emp_id like '%$key%'";			
		$result = $this->makeQuery($query);					 
		return $result;
		}		
	}
		//used in view_applicants.php to display applicant for blacklisting
	function ViewForBlacklistEmployee(){
		$this->Connect();
		$query = "SELECT applicant.app_id,lastname, firstname, middlename
				FROM 
						applicant
				INNER JOIN employee ON applicant.app_id = employee.emp_id				
				WHERE 
						employee.current_status = 'active' AND employee.sub_code=".$_SESSION['bunit_code'];
		$result = $this->makeQuery($query);			
		return $result;	
	}
		//used in view_blacklist.php
	function ViewBlackListEmployee(){
		$this->Connect();
		$query = "SELECT
						blacklist.record_no, applicant.app_id, lastname, firstname, middlename, home_address, date_blacklisted,reportedby,reason, status, sub_code
				FROM
						applicant
				INNER JOIN 
						blacklist ON applicant.app_id = blacklist.app_id
				INNER JOIN employee ON
						applicant.app_id = employee.emp_id
				WHERE  status = 'blacklisted' AND employee.sub_code=".$_SESSION['bunit_code'];	
		$result = $this->makeQuery($query);
		return $result;	
	}	
	/*used in epas_details.php------------------------------------------------------------------------------------*/
	function SelectEmpEpas($start,$limit){
		$this->Connect();
		$query = " SELECT * FROM `employee3`
				 WHERE sub_code = '1' and current_status = 'active' order by dept_id, name	
				 LIMIT $start, $limit";									 
		$result = $this->makeQuery($query);			
		return $result;	
	}
	/*count like search */
	function CountEmpEpas(){
		$this->Connect();
		$query = "SELECT COUNT(distinct(emp_id)) as num
				 FROM `employee3`
				 WHERE sub_code = '1' and current_status = 'active' "; 						
		$result = $this->makeQuery($query);			
		$total_pages = $this->fetchArray($result);
		$total_pages = $total_pages['num'];   
		return $total_pages;
	}
	/**use in*/
	function SelectName($key,$start,$limit){
		$this->Connect();
		$query = "SELECT distinct(emp_id), name, position, emp_cat, emp_type, current_status, company_code, bunit_code
				 FROM `employee3`
				 WHERE company_code = '01' and current_status = 'active' and name like '%$key%' order by dept_code, name	
				 LIMIT $start, $limit";						 
		$result = $this->makeQuery($query);			
		return $result;	
	}
	/*count like search */
	function CountEmpName($key){
		$this->Connect();
		$query = "SELECT COUNT(distinct(emp_id)) as num
				 FROM `employee3`
				 WHERE sub_code = '1' and current_status = 'active' and name like '%$key%' "; 						
		$result = $this->makeQuery($query);			
		$total_pages = $this->fetchArray($result);
		$total_pages = $total_pages['num'];   
		return $total_pages;
	}
	/**use in salary_details.php*/
	function SelectSalName($start,$limit){
		$this->Connect();
		$query = "SELECT record_no,name,emp_id,position,emp_type, current_status, sub_code, dept_id 
				 from EMPLOYEE3 WHERE current_status = 'active' and sub_code = '1' && emp_type != 'OJT'		
			     order by dept_id, name	
				 LIMIT $start, $limit";						 
		$result = $this->makeQuery($query);			
		return $result;	
	}
	/*count like search */
	function CountSalName($key){
		$this->Connect();
		$query = "SELECT COUNT(distinct(emp_id)) as num
				 FROM `employee3`
				 WHERE sub_code = '1' and current_status = 'active'  && emp_type != 'OJT' and name like '%$key%' "; 						
		$result = $this->makeQuery($query);			
		$total_pages = $this->fetchArray($result);
		$total_pages = $total_pages['num'];   
		return $total_pages;
	}
	/*END OF EPAS DETAILS------------------------------------------------------------------------------------------*/
	//eoc.php //default nga mogawas
	function SelectEocEmp($frm,$to,$epas,$clearance){//$start,$limit,
		$this->Connect();		

		$query = "SELECT * from EMPLOYEE3 WHERE 
					current_status = 'active' and 
					company_code = '01' and
					bunit_code = '01' and
					emp_type != 'regular' and
					eocdate between '$frm' and '$to'
					and $epas
					and $clearance and tag_as != 'renewed'
					order by eocdate desc";						 
		$result = $this->makeQuery($query);//	LIMIT $start, $limit		
		return $result;	
	}
	/*count search */
	function CountEocEmp($frm,$to,$epas,$clearance){
		$this->Connect();
		$query = "SELECT COUNT(distinct(emp_id)) as num FROM EMPLOYEE3 WHERE 
					current_status = 'active' and 
					company_code = '01' and
					bunit_code = '01' and
					emp_type != 'regular' and
					eocdate between '$frm' and '$to'
					and $epas and $clearance ";								
		$result = $this->makeQuery($query);			
		$total_pages = $this->fetchArray($result);
		$total_pages = $total_pages['num'];   
		return $total_pages;
	}
	/* select like search */
	function SelectLikeEocEmp($key,$frm,$to){//$limit,$start,current_status = 'active'
		$this->Connect();
		$query = "SELECT * from EMPLOYEE3 WHERE 
					company_code = '01' and
					bunit_code = '01' and
					emp_type != 'regular' and					
					name like '%$key%'
			     order by eocdate";											 
		$result = $this->makeQuery($query);	//	 LIMIT $start, $limit";	 eocdate between '$frm' and '$to' and
		return $result;	
	}
	/*count like search */
	function CountLikeEocEmp($key,$frm,$to){
		$this->Connect();
		$query = "SELECT COUNT(distinct(emp_id)) as num from EMPLOYEE3 WHERE 
					current_status = 'active' and 
					company_code = '01' and
					bunit_code = '01' and
					emp_type != 'regular' and					
					name like '%$key%' ";					
		$result = $this->makeQuery($query);//	eocdate between '$frm' and '$to' and
		$total_pages = $this->fetchArray($result);
		$total_pages = $total_pages['num'];   
		return $total_pages;
	}
//======================================================================================================================================================
//                                        
//======================================================================================================================================================

		function updateEMPLOYEEwhereEMPID($emp_id){	
		$this->Connect();
		$query = " UPDATE employee SET current_status = 'blacklisted' where emp_id = '".$emp_id."' ";			
		$result = $this->makeQuery($query);			
		return $result;	
	}
		function updateAPPLICANTwhereNO($no,$app_id){	
		$this->Connect();
		$query = " UPDATE applicant set app_id = '".$app_id."' WHERE no = '".$no."' ";			
		$result = $this->makeQuery($query);			
		return $result;	
	}
		function updateAPPDETwhereAPPID($app_id){	
		$this->Connect();
		$query = " UPDATE application_details SET status = 'blacklisted' where app_id = '".$app_id."' ";			
		$result = $this->makeQuery($query);			
		return $result;	
	}
		function updateAPPDET($app){	
		$this->Connect();
		$query = " UPDATE application_details SET status = 'Examination' where app_id = '".$app."' ";			
		$result = $this->makeQuery($query);			
		return $result;	
	}
		function insertSYSEVENT($creator_role,$creator_name,$event_name,$created_event,$tym_act,$tym_act_format){	
		$this->Connect();
		$query = " INSERT INTO sys_event VALUES('".$creator_role."','".$creator_name."','".$event_name."','".$created_event."','".$tym_act."','".$tym_act_format."') ";			
		$result = $this->makeQuery($query);			
		return $result;	
	}
		function insertBLACKLIST($id,$db,$rb,$re,$s){	
		$this->Connect();
		$query = " INSERT INTO blacklist VALUES('','".$id."','".$db."','".$rb."','".$re."','".$s."','','') ";			
		$result = $this->makeQuery($query);			
		return $result;	
	}
		function selectBLACKLIST($a){	
		$this->Connect();
		$query = " SELECT * FROM blacklist WHERE app_id = '".$a."' ";			
		$result = $this->makeQuery($query);			
		return $result;	
	}
	function insertTOAPPLICANT($id,$year,$lastname,$firstname,$middlename,$datebirth,$home_addr,$city_add,$province,$town,$brgy,$religion,$civilstatus,$spouse,
		                       $siblings,$siblingOrder,$gender,$school,$attainment,$course,$contactno,$telno,$email,$fb,$twitter,$citizenship,$bloodtype,$weight,
							   $height,$contact_per,$contact_add,$contact_num,$mother,$father,$guardian,$hobbies,$skills,$photo){	
		$this->Connect();
		$query = " INSERT INTO applicant
		           (app_id, no, id, year, lastname, firstname, middlename, birthdate, home_address, city_address, province, 
		            town, brgy, religion, civilstatus, spouse, noofSiblings,siblingOrder,gender, school, attainment, course,contactno,telno,email, facebookAcct, 
					twitterAcct,citizenship, bloodtype, weight, height, contact_person, contact_person_address, contact_person_number,mother,father, guardian,hobbies, 
					specialSkills, photo)
					VALUES('', '', '$id','$year','$lastname','$firstname','$middlename','$datebirth','$home_addr','$city_add','$province','$town','$brgy',
					'$religion','$civilstatus', '$spouse','$siblings','$siblingOrder', '$gender','$school', '$attainment','$course', '$contactno','$telno',
					'$email', '$fb','$twitter','$citizenship', '$bloodtype','$weight','$height','$contact_per','$contact_add','$contact_num',
					'$mother','$father','$guardian','$hobbies','$skills','$photo') ";			
		$result = $this->makeQuery($query);			
		return $result;	
	}
	function insertTOAPPLICANT2($app,$id,$no,$year,$lastname,$firstname,$middlename,$datebirth,$home_addr,$city_add,$province,$town,$brgy,$religion,$civilstatus,$spouse,
		                       $siblings,$siblingOrder,$gender,$school,$attainment,$course,$contactno,$telno,$email,$fb,$twitter,$citizenship,$bloodtype,$weight,
							   $height,$contact_per,$contact_add,$contact_num,$mother,$father,$guardian,$hobbies,$skills,$photo){	
		$this->Connect();
		$query = " INSERT INTO applicant
		           (app_id, no, id, year, lastname, firstname, middlename, birthdate, home_address, city_address, province, 
		            town, brgy, religion, civilstatus, spouse, noofSiblings,siblingOrder,gender, school, attainment, course,contactno,telno,email, facebookAcct, 
					twitterAcct,citizenship, bloodtype, weight, height, contact_person, contact_person_address, contact_person_number,mother,father, guardian,hobbies, 
					specialSkills, photo)
					VALUES('$app', '$no', '$id','$year','$lastname','$firstname','$middlename','$datebirth','$home_addr','$city_add','$province','$town','$brgy',
					'$religion','$civilstatus', '$spouse','$siblings','$siblingOrder', '$gender','$school', '$attainment','$course', '$contactno','$telno',
					'$email', '$fb','$twitter','$citizenship', '$bloodtype','$weight','$height','$contact_per','$contact_add','$contact_num',
					'$mother','$father','$guardian','$hobbies','$skills','$photo') ";			
		$result = $this->makeQuery($query);			
		return $result;	
	}
	function insertTOAPPDETAILS($app_id,$pos_applied,$unit,$subsection,$section,$department,$businessunit,$company,$dateapplied,$app_status,$updatedby,$dateup,$aeregular)	
	{	
	$this->Connect();
	$query = "INSERT INTO application_details
						   (app_id,position_applied,unit_code,sub_section_code,section_code,dept_code,bunit_code,company_code,date_applied,application_status,
							updatedby,date_updated,aeregular)
							VALUES('$app_id','$pos_applied','$unit','$subsection','$section','$department','$businessunit','$company','$dateapplied','$app_status',
							'$updatedby','$dateup','$aeregular')";			
	$result = $this->makeQuery($query);			
	return $result;	
	}
}
?>