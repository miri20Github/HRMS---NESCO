<?php
/***** QUERIES  *****/
class newqueries extends configs{ 
	function makeQuery($query){						
		return @mysql_query($query);
	}
	function fetchArray($result){						
		return @mysql_fetch_array($result);
	}
	function changeDateFormat($changeformatto,$date)
	{	
		if($date != '0000-00-00'){
			$convert_date = new DateTime($date); 		
			return $convert_date->format($changeformatto);
		}else{
			return '';
		}
	}
	
	function splitString($v,$key){
		$value 	= explode("$v",$key); 
		if(count($value)>1) {
			$id		= $value[0];			
		}		
		return @$id;
	}
	
	
	function writeLogs($log,$logDir,$filename)
	{
		$filename = $filename.date("Ymd").".txt";
		$file = $logDir."/".$filename;
		// Let's make sure the file exists and is writable first. [d/M/Y:g:ia]  
		if(file_exists($file)){
			if (is_writable($file)) {
				
				// In our example we're opening $filename in append mode.
				// The file pointer is at the bottom of the file hence
				// that's where $somecontent will go when we fwrite() it.
				if (!$handle = fopen($file, 'a')) {
					 die("Cannot open file ($filename)");
				}

				// Write $somecontent to our opened file.
				if (fwrite($handle, $log) === FALSE) {
					die("Cannot write to file ($filename)");
				}
				fclose($handle);

			} else {
				die("The file $filename is not writable");
			}
		}
		else {
			fopen($file, "w");
			if (is_writable($file)) {
				
				// In our example we're opening $filename in append mode.
				// The file pointer is at the bottom of the file hence
				// that's where $somecontent will go when we fwrite() it.
				if (!$handle = fopen($file, 'a')) {
					 die("Cannot open file ($filename)");
				}
 
				// Write $somecontent to our opened file.
				if (fwrite($handle, $log) === FALSE) {
					die("Cannot write to file ($filename)");
				}
				fclose($handle);

			} else {
				die("The file $filename is not writable");
			}
		}
	} 

	function getUrl(){
		//$query 		= $_SERVER['PHP_SELF'];
		//$path 		= pathinfo( $query );
		//$url 		= $path['basename'];
		$url 		= $_SERVER['REQUEST_URI'];
		return $url;
	}
	function curPageURL(){
		$pageURL = 'http';
		// if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		}else{
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}
	
	function showCStatus($currentstatus)
	{
		switch($currentstatus){
			case "active":
				$status = "<span class='label label-success'>Active</span>"; break;
			case "Active":
				$status = "<span class='label label-success'>Active</span>"; break;
			case "blacklisted":
				$status = "<span class='label label-danger'>blacklisted</span>"; break;	
			case "Blacklisted":
				$status = "<span class='label label-danger'>blacklisted</span>"; break;	
			case "End of Contract":
				$status = "<span class='label label-warning'>End of Contract</span>"; break;	
			case "end of contract":
				$status = "<span class='label label-warning'>End of Contract</span>"; break;					
			case "Resigned":
				$status = "<span class='label label-warning'>Resigned</span>"; break;		
			case "resigned":
				$status = "<span class='label label-warning'>Resigned</span>"; break;				
			default: $status = $currentstatus; break;
		}
		return $status;
	}
	
	function showUserStatus($userstatus)
	{
		switch($userstatus){
			case "active":
				$status = "<span class='label label-success'>active</span>"; break;
			case "inactive":
				$status = "<span class='label label-danger'>inactive</span>"; break;							
			default: $status = $currentstatus; break;
		}
		return $status;
	}

	function getNorm($ntype, $score){
		$norm = "N/A";
		$q1 = mysql_query("Select * From application_exam_norms Where n_type='$ntype'");
		if(mysql_num_rows($q1)){
			while($r1 = mysql_fetch_array($q1)){				
				if(intval($score) >= $r1['n_low']&&intval($score) <= $r1['n_high']){
					$norm = $r1['n_desc'];
					return $norm;
				}
			}		
		}
		return $norm;
	}
	
	/*-----------------------------------*/
	//		getting random number		 //
	/*-----------------------------------*/
	function random(){
		$length=5;
		$list="0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		mt_srand((double)microtime()*1000000); 
		$newstring=""; 

		if($length>0){ 
			while(strlen($newstring)<$length){ 
				$newstring.=$list[mt_rand(0, strlen($list)-1)]; 
			} 
		} 
		return $newstring; 
	}

	function getmonthname($no)
	{		
		switch($no){
			case "01": $monthname = "January"; break;		
			case "02": $monthname = "Febuary"; break;		
			case "03": $monthname = "March"; break;		
			case "04": $monthname = "April"; break;		
			case "05": $monthname = "May"; break;		
			case "06": $monthname = "June"; break;
			case "07": $monthname = "July"; break;		
			case "08": $monthname = "August"; break;		
			case "09": $monthname = "September"; break;		
			case "10": $monthname = "October"; break;		
			case "11": $monthname = "November"; break;		
			case "12": $monthname = "December"; break;
			default: $monthname = ''; break;
		}
		return $monthname;
	}

	function bdayreport($bmonth)
	{
		switch($bmonth)
		{
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
		return $bday;
	}

	function monthname()
	{
		$monthname_array = array('January','February','March','April','May','June','July','August','September','October','November','December'); 
		return $monthname_array;	
	}
	function monthno()
	{
		$monthno_array = array('01','02','03','04','05','06','07','08','09','10','11','12'); 
		return $monthno_array;	
	}	
	function getBirthday($appid){
		$this->Connect();
		$query = "SELECT birthdate FROM `applicant` WHERE app_id = '$appid' limit 1";      
        $result = $this->makeQuery($query);
		$fetch = $this->fetchArray($result);
	//	$this->Disconnect();
		return $fetch['birthdate'];
	}
	
	function getRegulars(){
		$this->Connect();
		$query = "SELECT name FROM employee3 WHERE emp_type = 'regular' and current_status = 'active' order by name ";
		$result = $this->makeQuery($query);		
		return $result;
	}
	
	function checkCompanyUsing($cc,$bc,$dc){
		$this->Connect();
		$value = 0;
		if($dc!=''){
			$query  = "SELECT cc,bc,dc,sc,ssc from si_user_setup_details where cc = '$cc' and bc='$bc' and dc ='$dc' ";		
		}else{
			$query1 = "SELECT cc,bc,dc,sc,ssc from si_user_setup_details where cc = '$cc' and bc='$bc' ";
		}
		
		$result = @$this->makeQuery($query);
		$result1= @$this->makeQuery($query1);
		
		if(mysql_num_rows($result)>0){
			$return = 1;
		}else if(mysql_num_rows($result1)>0){
			$return = 1;		
		}else{
			$return = 0;
		}
		
		return $return;
	}

	function selectallemployee()
	{
		$this->Connect();		
		$query 	= "SELECT emp_id,name from employee3 order by name";   
		$result = $this->makeQuery($query);	
	//	$this->Disconnect();
		return $result;	
	}
	function savelogs($activity,$date,$time,$user,$username)
	{
		$this->Connect();		
		$query 	= "INSERT into logs VALUES('','$activity','$date','$time','$user','$username')";   
		$result = $this->makeQuery($query);	
		//$this->Disconnect();		
		return $result;	
	}
	function selectallforeoc()
	{
		$this->Connect();		
		$query 	= "select record_no,emp_id, name from employee3 where emp_type != 'Regular' and (current_status = 'active' || current_status = 'End of Contract') order by name,record_no desc";   
		$result = $this->makeQuery($query);	 
		return $result;	
	}
	function selectallblacklistemployee()
	{
		$this->Connect();		
		$query 	= "SELECT app_id, name FROM blacklist where status = 'blacklisted' order by name ";   
		$result = $this->makeQuery($query);	 
		return $result;	
	}
	function selectallactiveemployee()
	{
		$this->Connect();		
		$query 	= "SELECT distinct(emp_id), name from employee3 where current_status = 'active' order by name, record_no desc ";   
		$result = $this->makeQuery($query);	 
		return $result;	
	}
	function getAppraisalType_header($code){
		$this->Connect();
		$query = "SELECT header FROM `appraisal_type` WHERE code = '$code' ";      
        $result = $this->makeQuery($query);
		$fetch = $this->fetchArray($result);
		return $fetch['header'];
	}
	function getAppraisalType_appraisal($code){
		$this->Connect();
		$query = "SELECT appraisal FROM `appraisal_type` WHERE code = '$code' ";      
        $result = $this->makeQuery($query);
		$fetch = $this->fetchArray($result);
		return $fetch['appraisal'];
	}

	/*-----------------------------------*/
	//	  end of getting random number	 //
	/*-----------------------------------*/
	function getEmpType($empid){
		$this->Connect();
		$query = "SELECT emp_type FROM `employee3` WHERE emp_id = '$empid' and current_status = 'active' ";      
        $result = $this->makeQuery($query);
		$fetch = $this->fetchArray($result);
		return $fetch['emp_type'];
	}
	function getPositionNo($position){
		$this->Connect();
		$query = "SELECT position_no FROM `positions` WHERE position = '$position' ";      
        $result = $this->makeQuery($query);
		$fetch = $this->fetchArray($result);
		return $fetch['position_no'];
	}
	function getPosition($posno){
		$this->Connect();
		$query = "SELECT position FROM `positions` WHERE position_no = '$posno' ";      
        $result = $this->makeQuery($query);
		$fetch = $this->fetchArray($result);
		return $fetch['position'];
	}
	function getEmpPosition($empid){
		$this->Connect();
		$query = "SELECT position FROM `employee3` WHERE emp_id = '$empid' and current_status = 'active' limit 1 ";      
        $result = $this->makeQuery($query);
		$fetch = $this->fetchArray($result);
		return $fetch['position'];
	}
	function getEmpPositionPromo($empid){
		$this->Connect();
		$query = "SELECT position FROM `employee3` WHERE emp_id = '$empid' limit 1 ";      
        $result = $this->makeQuery($query);
		$fetch = $this->fetchArray($result);
		return $fetch['position'];
	}
	function getPhoto($empid){
		$this->Connect();
		$query = "SELECT photo FROM `applicant` WHERE app_id = '$empid' limit 1";      
        $result = $this->makeQuery($query);
		$fetch = $this->fetchArray($result);
		return $fetch['photo'];
	}
	function getSSS($empid){
		$this->Connect();
		$query = "SELECT sss_no FROM `applicant_otherdetails` WHERE app_id = '$empid' ";      
        $result = $this->makeQuery($query);
		$fetch = $this->fetchArray($result);
		return $fetch['sss_no'];
	}
	function getOneField($field,$table,$where)
	{
		$this->Connect();
		$query = "SELECT $field from $table where $where";
		$result = $this->makeQuery($query);
		$fetch = $this->fetchArray($result);
		return $fetch[$field];
	}
	function search()
	{
		$this->Connect();
		//$search = @mysql_escape_string(trim($key));
		//$str 	= preg_replace('/[^A-Za-z0-9\. -]/', '', $search);	 //remove the special characters
		//$str 	= " ".$str." ";									 //put spaces in the first and last part of the string
		//$str 	= preg_replace('/  */', '%', $str);

		$query 	= "SELECT record_no, emp_id, name from employee3 where current_status = 'active'"; // name like '%$str%'
		$result = $this->makeQuery($query);	
		return $result;	
	}
	function getEmployeeType(){
		$this->Connect();
		$query = "SELECT emp_type from employee_type";
		$result = $this->makeQuery($query);
		return $result;
	}
	function getEmpName($empid){
		$this->Connect();
		$query = "SELECT name from employee3 where emp_id = '$empid'";
		$result = $this->makeQuery($query);
		$fetch = $this->fetchArray($result);
		return $fetch['name'];
	}
	function getEmpRec($empid){
		$this->Connect();
		$query = "SELECT emp_id from employee3 where record_no = '$empid'";
		$result = $this->makeQuery($query);
		$fetch = $this->fetchArray($result);
		return $fetch['emp_id'];
	}
	
	function getEmpRec1($empid){
		$this->Connect();
		$query = "SELECT emp_id from employee3 where record_no = '$empid'";
		$result = $this->makeQuery($query);
		$fetch = $this->fetchArray($result);
		return $fetch['emp_id'];
	}
	
	function getEmpRecord($rec){
		$this->Connect();
		$query = "SELECT emp_id from employmentrecord_ where record_no = '$rec'";
		$result = $this->makeQuery($query);
		$fetch = $this->fetchArray($result);
		return $fetch['emp_id'];
	}
	
	function getRec($empid){
		$this->Connect();
		$query = "SELECT record_no from employee3 where emp_id = '$empid' ";//and current_status = 'active' ";
		$result = $this->makeQuery($query);
		$fetch = $this->fetchArray($result);
		return $fetch['record_no'];
	}
	
	function getRecEmpName($rec){
		$this->Connect();
		$query = "SELECT name from employee3 where record_no = '$rec'";
		$result = $this->makeQuery($query);
		$fetch = $this->fetchArray($result);
		return $fetch['name'];
	}
	function getEmpidfromUsername($usern){
		$this->Connect();
		$query = "SELECT emp_id from users where username = '$usern' limit 1 ";
		$result = $this->makeQuery($query);
		$fetch = $this->fetchArray($result);
		return $fetch['emp_id'];
	}
	function getApplicantName($appid){
		$this->Connect();
		$query = "SELECT firstname, lastname from applicant where app_id = '$appid' limit 1 ";
		$result = $this->makeQuery($query);
		$fetch = $this->fetchArray($result);
		return $fetch['firstname']." ".$fetch['lastname'];
	}

	function getPromoName($appid){
		$this->Connect();
		$query = "SELECT firstname, lastname, MID(middlename,1,1) AS mname, suffix from applicant where app_id = '$appid' limit 1 ";
		$result = $this->makeQuery($query);
		$fetch = $this->fetchArray($result);
		$suffix = $fetch['suffix'];
		$mname = $fetch['mname'];
		if($suffix !=""){ $suffix = " $suffix,"; } else { $suffix=""; }
		if($mname!="") {$mname = " $mname."; } else { $mname = ""; }
		return $fetch['lastname'].", ".$fetch['firstname']."".$suffix."".$mname;
	}

	function getPromoInchargeName($appid){
		$this->Connect();
		$query = "SELECT firstname, lastname, MID(middlename,1,1) AS mname, suffix from applicant where app_id = '$appid' limit 1 ";
		$result = $this->makeQuery($query);
		$fetch = $this->fetchArray($result);
		$suffix = $fetch['suffix'];
		$mname = $fetch['mname'];
		if($mname!="") {$mname = " $mname."; } else { $mname = ""; }
		return $fetch['firstname']."".$mname." ".$fetch['lastname']."".$suffix;
	}
	
	function getApplicantName2($appid){
		$this->Connect();
		$query = "SELECT firstname, lastname from applicant where app_id = '$appid' limit 1 ";
		$result = $this->makeQuery($query);
		$fetch = $this->fetchArray($result);
		return $fetch['lastname'].", ".$fetch['firstname'];
	}
	
	function getFirstName($appid){
		$this->Connect();
		$query = "SELECT firstname from applicant where app_id = '$appid' limit 1";
		$result = $this->makeQuery($query);
		$fetch = $this->fetchArray($result);
		return $fetch['firstname'];
	}
	
	function getLastName($appid){
		$this->Connect();
		$query = "SELECT lastname from applicant where app_id = '$appid' limit 1 ";
		$result = $this->makeQuery($query);
		$fetch = $this->fetchArray($result);
		return $fetch['lastname'];
	}
	
	
	function getEmpPos($empid){
		$this->Connect();
		$query = "SELECT position from employee3 where emp_id = '$empid' and current_status = 'active' ";
		$result = $this->makeQuery($query);
		$fetch = $this->fetchArray($result);
		return $fetch['position'];
	}
	function getEmpPosRec($rec){
		$this->Connect();
		$query = "SELECT position from employee3 where record_no = '$rec' limit 1";
		$result = $this->makeQuery($query);
		$fetch = $this->fetchArray($result);
		return $fetch['position'];
	}
	function getAppName($appid){
		$this->Connect();
		$query = "SELECT lastname, firstname, middlename from applicant where app_id = '$appid' limit 1";
		$result = $this->makeQuery($query);
		$fetch = $this->fetchArray($result);
		$name = $fetch['lastname'].", ".$fetch['firstname']." ".$fetch['middlename'];
		return $name;
	}
	function getLastNameFirstName($appid){
		$this->Connect();
		$query = "SELECT lastname, firstname, middlename from applicant where app_id = '$appid'";
		$result = $this->makeQuery($query);
		$fetch = $this->fetchArray($result);
		$name = $fetch['lastname'].", ".$fetch['firstname'];
		return $name;
	}
	function getFulName($appid){
		$this->Connect();
		$query = "SELECT lastname, firstname, middlename, suffix from applicant where app_id = '$appid'";
		$result = $this->makeQuery($query);		
		return $result;
	}



	//-----------------------------------------------------------//
	//** F U N C T I O N   G E T S   T A B L E   D E T A I L S **//
	//-----------------------------------------------------------//
	function getCompany($code){	
		$this->Connect();
		$query = "SELECT * FROM locate_company where company_code = '$code' ";		
		$result = $this->makeQuery($query);			
		return $result;	
	}
	function getCompanyName($code){	
		$this->Connect();
		$query = "SELECT company FROM locate_company where company_code = '$code' ";		
		$result = $this->makeQuery($query);	
		$fetch = $this->fetchArray($result);		
		return $fetch['company'];	
	}
	function getCompanyAcroname($code){	
		$this->Connect();
		$query = "SELECT acroname FROM locate_company where company_code = '$code' ";		
		$result = $this->makeQuery($query);	
		$fetch = $this->fetchArray($result);		
		return $fetch['acroname'];	
	}
	function getAllCompanyAcroname(){	
		$this->Connect();
		$query = "SELECT company_code,acroname FROM locate_company ";		
		$result = $this->makeQuery($query);	
		return $result;
	}
	function getAllCompany(){	
		$this->Connect();
		$query = "SELECT * FROM locate_company";		
		$result = $this->makeQuery($query);			
		return $result;	
	}	

	function getBusinessUnit($bcode, $ccode){	
		$this->Connect();
		$query = "SELECT * FROM locate_business_unit where bunit_code = '$bcode' and company_code = '$ccode' ";		
		$result = $this->makeQuery($query);			
		return $result;	
	}
	function getBusinessUnitName($bcode, $ccode){	
		$this->Connect();
		$query = "SELECT business_unit FROM locate_business_unit where bunit_code = '$bcode' and company_code = '$ccode' ";		
		$result = $this->makeQuery($query);			
		$fetch = $this->fetchArray($result);		
		return $fetch['business_unit'];
	}

	function getAllBusinessUnit($ccode){	
		$this->Connect();
		$query = "SELECT * FROM locate_business_unit where company_code = '$ccode' ";		
		$result = $this->makeQuery($query);			
		return $result;	
	}
	
	function getBUAcroname($bcode,$ccode){	
		$this->Connect();
		$query = "SELECT acroname, business_unit FROM locate_business_unit where bunit_code = '$bcode' and company_code = '$ccode' ";		
		$result = $this->makeQuery($query);	
		$fetch = $this->fetchArray($result);
		if($fetch['acroname'] !=''){		
			return $fetch['acroname'];	
		}else{
			return $fetch['business_unit'];	
		}
	}
	
	function getDeptAcroname($dcode,$bcode,$ccode){	
		$this->Connect();
		$query = "SELECT acroname, dept_name FROM locate_department where dept_code = '$dcode' and bunit_code = '$bcode' and company_code = '$ccode' ";		
		$result = $this->makeQuery($query);	
		$fetch = $this->fetchArray($result);
		if($fetch['acroname'] !=''){		
			return $fetch['acroname'];	
		}else{
			return $fetch['dept_name'];	
		}
	}

	
	function getDepartment($dcode,$bcode,$ccode){	
		$this->Connect();
		$query = "SELECT * FROM locate_department where dept_code = '$dcode' and bunit_code = '$bcode' and company_code = '$ccode' ";		
		$result = $this->makeQuery($query);			
		return $result;	
	}	
	function getDepartmentName($dcode,$bcode,$ccode){	
		$this->Connect();
		$query = "SELECT dept_name FROM locate_department where dept_code = '$dcode' and bunit_code = '$bcode' and company_code = '$ccode' ";		
		$result = $this->makeQuery($query);			
		$fetch = $this->fetchArray($result);		
		return $fetch['dept_name'];
	}	
	function getAllDepartment($bcode,$ccode){	
		$this->Connect();
		$query = "SELECT * FROM locate_department where bunit_code = '$bcode' and company_code = '$ccode' ";		
		$result = $this->makeQuery($query);			
		return $result;	
	}	
	function getSection($scode,$dcode,$bcode,$ccode){	
		$this->Connect();
		$query = "SELECT * FROM locate_section where section_code = '$scode' and dept_code = '$dcode' and bunit_code = '$bcode' and company_code = '$ccode' ";		
		$result = $this->makeQuery($query);			
		return $result;	
	}	
	function getSectionName($scode,$dcode,$bcode,$ccode){	
		$this->Connect();
		$query = "SELECT section_name FROM locate_section where section_code = '$scode' and dept_code = '$dcode' and bunit_code = '$bcode' and company_code = '$ccode' ";		
		$result = $this->makeQuery($query);			
		$fetch = $this->fetchArray($result);		
		return $fetch['section_name'];
	}	
	function getAllSection($dcode,$bcode,$ccode){	
		$this->Connect();
		$query = "SELECT * FROM locate_section where dept_code = '$dcode' and bunit_code = '$bcode' and company_code = '$ccode' ";		
		$result = $this->makeQuery($query);			
		return $result;	
	}
	function getAllSubSection($scode,$dcode,$bcode,$ccode){	
		$this->Connect();
		$query = "SELECT * FROM locate_sub_section where section_code = '$scode' and dept_code = '$dcode' and bunit_code = '$bcode' and company_code = '$ccode' ";		
		$result = $this->makeQuery($query);			
		return $result;	
	}
	function getAllUnit($sscode,$scode,$dcode,$bcode,$ccode){	
		$this->Connect();
		$query = "SELECT * FROM locate_unit where sub_section_code = '$sscode' and section_code = '$scode' and dept_code = '$dcode' and bunit_code = '$bcode' 
		          and company_code = '$ccode' ";		
		$result = $this->makeQuery($query);			
		return $result;	
	}	
	function getSubSection($sbcode,$scode,$dcode,$bcode,$ccode){	
		$this->Connect();
		$query = "SELECT * FROM locate_sub_section where sub_section_code='$sbcode' and section_code='$scode' and dept_code='$dcode' and bunit_code='$bcode' 
		          and company_code = '$ccode' ";		
		$result = $this->makeQuery($query);			
		return $result;	
	}
	function getSubSectionName($sbcode,$scode,$dcode,$bcode,$ccode){	
		$this->Connect();
		$query = "SELECT sub_section_name FROM locate_sub_section where sub_section_code='$sbcode' and section_code='$scode' and dept_code='$dcode' and bunit_code='$bcode' and company_code = '$ccode' ";		
		$result = $this->makeQuery($query);			
		$fetch = $this->fetchArray($result);		
		return $fetch['sub_section_name'];
	}	
	function getUnit($ucode,$sbcode,$scode,$dcode,$bcode,$ccode){	
		$this->Connect();
		$query = "SELECT * FROM locate_unit 
				 where unit_code = '$ucode' and sub_section_code='$sbcode' and section_code='$scode' and dept_code='$dcode' and bunit_code='$bcode' 
				 and company_code = '$ccode' ";		
		$result = $this->makeQuery($query);			
		return $result;	
	}
	function getUnitName($ucode,$sbcode,$scode,$dcode,$bcode,$ccode){	
		$this->Connect();
		$query = "SELECT unit_name FROM locate_unit 
				 where unit_code = '$ucode' and sub_section_code='$sbcode' and section_code='$scode' and dept_code='$dcode' and bunit_code='$bcode'
				  and company_code = '$ccode' ";		
		$result = $this->makeQuery($query);			
		$fetch = $this->fetchArray($result);		
		return $fetch['unit_name'];
	}
	function getEpas($rec,$empid)
	{		
		$this->Connect();
		//$query = "SELECT numrate, descrate FROM appraisal_details where record_no = '$rec' and (epas_code !='' or epas_code !='') ";
		$query = "SELECT numrate, descrate FROM appraisal_details 
				inner join employee3
				on employee3.record_no = appraisal_details.record_no 
				where ( epas_code= '1' or  epas_code= '2') and employee3.record_no = '$rec' and appraisal_details.emp_id = '$empid' ";

		$result = $this->makeQuery($query);
		return $result;		
	}
	function getEpasCode($rec,$emp)
	{		
		$this->Connect();
		//$query = "SELECT numrate, descrate FROM appraisal_details where record_no = '$rec' and (epas_code !='' or epas_code !='') ";
		$query = "SELECT numrate, descrate FROM appraisal_details 
				inner join employmentrecord_
				on employmentrecord_.record_no = appraisal_details.record_no 
				where ( epas_code= '1' or  epas_code= '2') and employmentrecord_.record_no = '$rec' and appraisal_details.emp_id = '$emp' ";
		$result = $this->makeQuery($query);
		return $result;		
	}
	function getName($empid){
		$this->Connect();
		$query  = "SELECT lastname, firstname, middlename from applicant where app_id = '$empid'";		
		$result = $this->makeQuery($query);
		$fetch  = $this->fetchArray($result);
		$m  	=  substr($fetch['middlename'],0,1);
		return $fetch['lastname'].", ".$fetch['firstname']." ".$m.".";
	}
	/*query functions for getting the head, subordinates and peers*/	
	function getHead($cc,$bc,$dc){
		$this->Connect();
		$query = "SELECT * FROM locate_department where company_code = '$cc' and bunit_code = '$bc' and dept_code = '$dc' ";		
		$result = $this->makeQuery($query);
		return $result;
	}
	function getNameHead($emp){
		$this->Connect();
		$query = "SELECT emp_id,name,position from employee3 where emp_id = '$emp'";
		$result = $this->makeQuery($query);
		return $result;
	}
	function getHeadSection($cc,$bc,$dc,$sc){
		$this->Connect();
		$query = "SELECT * FROM locate_section where company_code = '$cc' and bunit_code = '$bc' and dept_code = '$dc' and section_code='$sc' ";
		$result = $this->makeQuery($query);
		return $result;
	}
	function getPeers($cc,$bc,$dc,$sc, $pos,$empid){
		$this->Connect();
		$query = "SELECT * FROM employee3 where company_code = '$cc' and bunit_code = '$bc' and dept_code = '$dc' and section_code = '$sc' and position like '%$pos%' and current_status = 'active' and emp_id != '$empid'";
		$result = $this->makeQuery($query);
		return $result;
	}
	function getPeersDept($cc,$bc,$dc,$pos,$empid){
		$this->Connect();
		$query = "SELECT * FROM employee3 where company_code = '$cc' and bunit_code = '$bc' and dept_code = '$dc' and position like '%$pos%' and current_status = 'active' and emp_id != '$empid'";
		$result = $this->makeQuery($query);
		return $result;
	}
	function getSubordinates($cc,$bc,$dc,$sc,$empid,$poslevel){
		$this->Connect();
		$query = "SELECT * FROM employee3 where company_code = '$cc' and bunit_code = '$bc' and dept_code = '$dc' and section_code = '$sc' and current_status = 'active' and emp_id 
		!= '$empid' and positionlevel < '$poslevel' ";
		$result = $this->makeQuery($query);
		return $result;		
	}
	function getStatus($emp){
		$this->Connect();
		$query = "SELECT current_status FROM employee3 where emp_id = '$emp' ";
		$result = $this->makeQuery($query);			
		$fetch = $this->fetchArray($result);		
		return $fetch['current_status'];		
	}
	function getSalaryIncPeriod()
	{
		$this->Connect();
		$query = "SELECT * FROM salary_increase_period where status = 'open' ";
		$result = $this->makeQuery($query);
		return $result;
	}
	/*new queries  as of 1/2/2014*/
	function getEmpInfo($emp_id)
	{		
		$this->Connect();
		$query = "SELECT * FROM employee3 where emp_id = '$emp_id' and current_status = 'active'";
		$result = $this->makeQuery($query);
		return $result;		
	}
	function getEmpInfoByRecord($rec)
	{		
		$this->Connect();
		$query = "SELECT * FROM employee3 where record_no = '$rec'";
		$result = $this->makeQuery($query);
		return $result;		
	}
	function getEmpInfoByRecord1($rec)
	{		
		$this->Connect();
		$query = "SELECT * FROM employmentrecord_ where record_no = '$rec'";
		$result = $this->makeQuery($query);
		return $result;		
	}
	function getEmpDept($cc,$bc,$dc)
	{		
		$this->Connect();
		$query = "SELECT * FROM employee3 where company_code = '$cc' and bunit_code = '$bc' and dept_code = '$dc' and current_status = 'active' order by name";
		$result = $this->makeQuery($query);
		return $result;		
	}
	function getEmpInfoByRecordId($rec,$empid)
	{		
		$this->Connect();
		$query = "SELECT * FROM employee3 where record_no = '$rec' and emp_id = '$empid'";
		$result = $this->makeQuery($query);
		return $result;		
	}
	
	// for promo queries
	function promoCount($condition,$department)
	{
		$this->Connect();
		$query = "SELECT count(employee3.emp_id) as num FROM employee3 INNER JOIN promo_record ON employee3.emp_id=promo_record.emp_id WHERE $condition and promo_department= '$department' and current_status='Active' and promo_record.type='Contractual'";
		$result = $this->makeQuery($query);			
		$fetch = $this->fetchArray($result);
		$fetch_c = $fetch['num'];
		if($fetch_c > 0)
		{
			return $fetch_c;
		}else {
			return $fetch_c = ''; 
		}
		
	}

	function promoCountAll($condition)
	{
		$this->Connect();
		$query = "SELECT count(employee3.emp_id) as num FROM employee3 INNER JOIN promo_record ON employee3.emp_id = promo_record.emp_id WHERE $condition and current_status = 'Active' and type = 'contractual'";
		$result = $this->makeQuery($query);
		$fetch = $this->fetchArray($result);
		$fetch_c = $fetch['num'];
		if($fetch_c > 0)
		{
			return $fetch_c;
		}else {
			return $fetch_c = ''; 
		}
	}
	
	function selectallpromo()
	{
		$this->Connect();		
		$query 	= "SELECT distinct(emp_id), name from employee3 where emp_type='Promo' order by name, record_no desc";   
		$result = $this->makeQuery($query);	 
		return $result;	
	}
	function getEpasPromo($rec, $store)
	{		
		$this->Connect();
		$query = "SELECT numrate, descrate FROM appraisal_details where record_no = '$rec' and store='$store' ";
		$result = $this->makeQuery($query);
		return $result;		
	}
	function getAllPromoCompany()
	{
		$this->Connect();
		$query = "SELECT pc_name FROM locate_promo_company";
		$result = $this ->makeQuery($query);
		return $result;
	}
	function getAllPromoDept()
	{
		$this->Connect();
		$query = "SELECT dept_name FROM locate_promo_department";
		$result = $this ->makeQuery($query);
		return $result;
	}
	function getPromoInfo($emp_id)
	{		
		$this->Connect();
		$query = "SELECT distinct(emp_id), name, positionlevel, position FROM employee3 where emp_id = '$emp_id' and emp_type='Promo' and current_status = 'active'";
		$result = $this->makeQuery($query);
		return $result;		
	}
	function selectWitness()
	{
		$this->Connect();
		$query = "SELECT distinct(witness1) from employment_witness order by witness1 asc";
		$result = $this->makeQuery($query);
		return $result;
	}
	function getPromoInfoByRecordId($rec,$empid)
	{		
		$this->Connect();
		$query = "SELECT employee3.emp_id, employee3.record_no, name, position, startdate, eocdate, promo_department, promo_company, al_tal, al_tag, icm, pm, abenson_tag, abenson_icm, al_tub, promo_type FROM employee3 inner join promo_record on employee3.emp_id=promo_record.emp_id where employee3.record_no = '$rec' and employee3.emp_id = '$empid'";
		$result = $this->makeQuery($query);
		return $result;		
	}
	function getEmpInfoByClearance($empid)
	{		
		$this->Connect();
		$query = "SELECT asc_clearance, tal_clearance, icm_clearance, pm_clearance, absna_clearance, absni_clearance, tub_clearance FROM promo_record where emp_id = '$empid'";
		$result = $this->makeQuery($query);
		return $result;		
	}
	function getPromoInfoByRecord($emp)
	{		
		$this->Connect();
		$query = "SELECT employee3.record_no, employee3.emp_id, startdate, eocdate, emp_type, remarks, position, current_status, comments,
						 promo_company, promo_department, company_duration, al_tag, al_tal, icm, pm, abenson_tag, abenson_icm, cdc, berama, al_tub, promo_type, asc_clearance, tal_clearance, icm_clearance, pm_clearance, absna_clearance, absni_clearance, cdc_clearance, berama_clearance, tub_clearance,
						 asc_contract, tal_contract, icm_contract, pm_contract, absna_contract, absni_contract, cdc_contract, berama_contract, tub_contract,
						 asc_epascode, tal_epascode, icm_epascode, pm_epascode, absna_epascode, absni_epascode, cdc_epascode, berama_epascode, tub_epascode, asc_permit, tal_permit, icm_permit, pm_permit, absna_permit, absni_permit, cdc_permit, berama_permit, tub_permit,
						 asc_intro, tal_intro, icm_intro, pm_intro, absna_intro, absni_intro, cdc_intro, berama_intro, tub_intro, type
				  FROM promo_record
				  INNER JOIN employee3 ON promo_record.emp_id = employee3.emp_id
				  WHERE promo_record.emp_id = '$emp' limit 1";
		$result = $this->makeQuery($query);
		return $result;		
	}
	function getPromoHistoryByRecord($rec,$emp)
	{
		$this->Connect();
		$query = "SELECT employmentrecord_.record_no, employmentrecord_.emp_id, startdate, eocdate, emp_type, remarks, position, current_status, comments,
						 promo_company, promo_department, company_duration, al_tag, al_tal, icm, pm, abenson_tag, abenson_icm, cdc, berama, al_tub, promo_type, asc_clearance, tal_clearance, icm_clearance, pm_clearance, absna_clearance, absni_clearance, cdc_clearance, berama_clearance, tub_clearance,
						 asc_contract, tal_contract, icm_contract, pm_contract, absna_contract, absni_contract, cdc_contract, berama_contract, tub_contract,
						 asc_epascode, tal_epascode, icm_epascode, pm_epascode, absna_epascode, absni_epascode, cdc_epascode, berama_epascode, tub_epascode, asc_permit, tal_permit, icm_permit, pm_permit, absna_permit, absni_permit, cdc_permit, berama_permit, tub_permit,
						 asc_intro, tal_intro, icm_intro, pm_intro, absna_intro, absni_intro, cdc_intro, berama_intro, tub_intro, type
				  FROM promo_history_record
				  INNER JOIN employmentrecord_ ON promo_history_record.record_no = employmentrecord_.record_no
				  WHERE employmentrecord_.record_no= '$rec' limit 1";
		$result = $this->makeQuery($query);
		return $result;	
	}
	//end of queries
	
	function getDeptHeadId($emp_id)
	{
		$this->Connect();
		$query = "SELECT * FROM locate_department where dept_head = '$emp_id'";
		$result = $this->makeQuery($query);
		return $result;	
	}
	function getDeptHeadbyLocation($cc,$bc,$dc)
	{
		$this->Connect();
		$query = "	SELECT * FROM locate_department where company_code = '$cc' and bunit_code = '$bc' and dept_code = '$dc'";
		$result = $this->makeQuery($query);
		return $result;		
	}

	function getSectionHeadId($emp_id)
	{
		$this->Connect();
		$query = "SELECT * FROM locate_section where section_head = '$emp_id'";
		$result = $this->makeQuery($query);
		return $result;		
	}	
	function getSectionHeadbyLocation($cc,$bc,$dc,$sc)
	{
		$this->Connect();
		$query = "SELECT * FROM locate_section where company_code = '$cc' and bunit_code = '$bc' and dept_code = '$dc' and section_code = '$sc'";
		$result = $this->makeQuery($query);
		return $result;	 
	}
	function getSubSectionHeadId($emp_id)
	{
		$this->Connect();
		$query = "SELECT * FROM locate_sub_section where subsection_head = '$emp_id'";
		$result = $this->makeQuery($query);
		return $result;	
	}
	function getSubSectionHeadbyLocation($cc,$bc,$dc,$sc,$ssc)
	{
		$this->Connect();
		$query = "SELECT * FROM locate_section where company_code = '$cc' and bunit_code = '$bc' and dept_code = '$dc' and section_code = '$sc' and sub_section_code = '$ssc' ";
		$result = $this->makeQuery($query);
		return $result;	 
	}
	function getSubSectionbyLocation($cc,$bc,$dc,$sc,$ssc,$emp_id)
	{
		$this->Connect();
		$query = "SELECT * FROM employee3 where company_code = '$cc' and bunit_code = '$bc' and dept_code = '$dc' and section_code = '$sc' and sub_section_code = '$ssc' and current_status = 'active' and emp_id != '$emp_id' ";
		$result = $this->makeQuery($query);
		return $result;	
	}
	function getUnitHeadId($emp_id)
	{
		$this->Connect();
		$query = "SELECT * FROM locate_unit where unit_head = '$emp_id'";
		$result = $this->makeQuery($query);
		return $result;			
	}
	function getUnitHeadbyLocation($cc,$bc,$dc,$sc,$ssc,$uc)
	{
		$this->Connect();
		$query = "SELECT * FROM locate_section where company_code = '$cc' and bunit_code = '$bc' and dept_code = '$dc' and section_code = '$sc' and sub_section_code = '$ssc' and unit_code = '$uc' ";
		$result = $this->makeQuery($query);
		return $result;			
	}
	function getDeptHeadSubordinates($cc,$bc,$dc,$emp_id,$poslevel)
	{
		$this->Connect();
		$query = "SELECT * FROM employee3
		   where company_code = '$cc' and bunit_code = '$bc' and dept_code = '$dc' and current_status = 'active' and emp_id != '$emp_id' and positionlevel < '$poslevel' ";
		$result = $this->makeQuery($query);
		return $result;			
	}
	function getDeptPeers($cc,$bc,$dc,$pos,$poslevel,$empid){
		$this->Connect();
		$query = "SELECT * FROM employee3 where company_code = '$cc' and bunit_code = '$bc' and dept_code = '$dc' and position like '%$pos%' and positionlevel = '$poslevel' and 
			   	 current_status = 'active' and emp_id != '$empid'";
		$result = $this->makeQuery($query);
		return $result;
	}
	function getSectionSubordinates($cc,$bc,$dc,$sc,$poslevel)
	{
		$this->Connect();
		$query = "SELECT * FROM employee3 where company_code = '$cc' and bunit_code = '$bc' and dept_code = '$dc' and section_code = '$sc' and current_status = 'active' and(positionlevel < '$poslevel' ||  positionlevel IS NULL)";
		$result = $this->makeQuery($query);
		return $result; 
	}
	function getField($field,$emp)
	{		
		$this->Connect();
		$query = "SELECT $field FROM employee3 where emp_id = '$emp' ";
		$result = $this->makeQuery($query);
		$fetch = $this->fetchArray($result);
		return $fetch; 
	}
	/********************************************************************************************************************************************************/
	/*****************************************************************	L E V E L I N G  Q U E R I E S  *****************************************************/
	/********************************************************************************************************************************************************/
	function getPeer($empid)
	{
		$this->Connect();
		$query = "SELECT * FROM leveling_peers where ratee = '$empid'";
		$result = $this->makeQuery($query);
		return $result; 
	}
	function getSupervisors($empid)
	{
		$this->Connect();
		$query = "SELECT * FROM leveling_supervisor where ratee = '$empid'";
		$result = $this->makeQuery($query);
		return $result; 
	}
	function getSubordinate($empid)
	{
		$this->Connect();
		$query = "SELECT * FROM leveling_subordinates where ratee = '$empid'";
		$result = $this->makeQuery($query);
		return $result; 
	}
	
	/***************************************************************************************************************************************************/
	function selectTable($tablename)
	{
		$this->Connect();
		$query = "SELECT * FROM $tablename ";
		$result = $this->makeQuery($query);
		return $result;
	}
	function selectTableWhere($tablename,$fieldname,$value)
	{
		$this->Connect();
		$query = "SELECT * FROM $tablename where $fieldname = '$value' ";
		$result = $this->makeQuery($query);
		return $result;
	}
	function selectRegularization($tag,$status)
	{
		$this->Connect();
		$query = "SELECT * FROM regularization_details where tagged_as = '$tag' and status = '$status'";
		$result = $this->makeQuery($query);
		return $result;
	}	
	/***************************************************************************************************************************************************/
	function select($cc,$dc,$bc)
	{
		$this->Connect();
		$query = "SELECT emp_id, name FROM employee3 where company_code = '$cc' and bunit_code = '$bc' and dept_code = '$dc' and current_status ='active' order by name";
		$result = $this->makeQuery($query);
		return $result;
	}
	/*------------------------------------------------*/
	/*--------A P P R A I S A L   Q U E R I E S-------*/
	/*------------------------------------------------*/
	function allappraisal($recordno)
	{
		$this->Connect();
		$query = "SELECT 
			appraisal.appraisal_id, code, q_no, title, description,	answer_id, rate, appraisal_answer.details_id,
			appraisal_details.record_no, rater, numrate, descrate,ratercomment, rateecomment, ratingdate,
			raterSO, rateeSO, dateraterSO, daterateeSO, addedby, dateadded, emp_id, name, position
		FROM 
			appraisal inner join
			appraisal_answer inner join
			appraisal_details inner join
			employee3
		ON 
			appraisal.appraisal_id = appraisal_answer.appraisal_id AND
			appraisal_answer.details_id = appraisal_details.details_id AND
			appraisal_details.record_no = employee3.record_no
		WHERE 
			appraisal_details.record_no = '$recordno' ";  

		$result = $this->makeQuery($query);
		return $result;
	}
	function allpreviousappraisal($empid,$minus)
	{
		$this->Connect();		
		$query = "SELECT q_no, rate 
		FROM 
		    appraisal inner join
		    appraisal_answer inner join
		    appraisal_details inner join
		    employee3
		ON 
		    appraisal.appraisal_id = appraisal_answer.appraisal_id AND
		    appraisal_answer.details_id = appraisal_details.details_id AND
		    appraisal_details.record_no = employee3.record_no    
		WHERE employee3.emp_id = '$empid' and epas_code =  '$minus' "; 

		$result = $this->makeQuery($query);
		return $result; 
	}
	function getApplicantStatus($appid)
	{
		$this->Connect();
		$query = "SELECT applicants.status FROM applicant INNER JOIN applicants ON applicant.appcode = applicants.app_code WHERE applicant.app_id = '$appid' ";
		$result = $this->makeQuery($query);
		$fetch = $this->fetchArray($result);
		return $fetch['status']; 			
	}
	function getCount($select,$location,$etype)
	{
		/*
		$this->Connect();
		$query = "$select $location and current_status = 'Active' and emp_type = '$etype' ";
		$result = $this->makeQuery($query);			
		$fetch = $this->fetchArray($result);
		if($fetch['count(emp_id)'] != '0'){
			return $fetch['count(emp_id)'];
		}else{
			return '';
		}
		*/
		$this->Connect();
		$query = "$select $location and current_status = 'Active' and emp_type = '$etype' ";
		$result = $this->makeQuery($query);			
		$fetch = $this->fetchArray($result);
		$fetch_c = $fetch['count(emp_id)'];
		if($etype=='NESCO-PTA' || $etype=='NESCO-PTP' || $etype=='PTA' || $etype=='PTP' || $etype=='Regular Partimer' || $etype=='NESCO Regular Partimer' || $etype=='Partimer' || $etype=='NESCO Partimer'){
			$fetchs = $fetch_c * 0.5; 
			if($fetchs != 0){
				return $fetchs;
			}
		}else if($fetch['count(emp_id)'] != '0'){
			return $fetch_c;
		}else{
			return '';
		}
	}
	function getCountInbox($emp)
	{
		$this->Connect();
		//$countinbox = mysql_query("SELECT count(msgdet_id) from message_details where cc = '$emp' and msg_stat = 0 ")or die(mysql_error());
		//$rcinbx 	= mysql_fetch_array($countinbox);
		//$ctrinbox 	= $rcinbx['count(msgdet_id)'];

		$query = "SELECT count(msgdet_id) from message_details where cc = '$emp' and msg_stat = 0  ";
		$result = $this->makeQuery($query);
		$fetch = $this->fetchArray($result);
		return $fetch['count(msgdet_id)']; 			
	}
	function checkSprpGrade($empid,$rec)
	{
		//get the grades in employee3
		$countGrade1 = 0;		
		$countGrade2 = 0;
		$totGrade	 = 0;
		
		$query1 = 
			"SELECT numrate from employee3 
			INNER JOIN appraisal_details ON
			employee3.record_no = appraisal_details.record_no 
			WHERE appraisal_details.emp_id = '$empid' and employee3.record_no = '$rec' and ( epas_code !='' ||  epas_code !='0') ";
		
		//get the results
		$result1 	= $this->makeQuery($query1);
		$fetch1 	= $this->fetchArray($result1);
		$numrate1 	= $fetch1['numrate'];
		if($numrate1 >= 70 && $numrate1 <= 84.99){	$countGrade1++; }

		//get the grades in employmentrecord_
		$query2 = "
			SELECT numrate from employmentrecord_ 
			INNER JOIN appraisal_details ON
			employmentrecord_.record_no = appraisal_details.record_no 
			WHERE emp_id = '$empid' and ( epas_code !='' ||  epas_code !='0') ";
		
		//get the results
		$result2 	= $this->makeQuery($query2);
		while($fetch2 = $this->fetchArray($result2)){
			$numrate2 	= $fetch2['numrate'];
			if($numrate2 >= 70 && $numrate2 <= 84){	$countGrade2++; }		
		}
		
		$totGrade	 = $countGrade1 + $countGrade2;		
		return $totGrade;	
	}	
	function getCountEmptype($etype)
	{
		$this->Connect();
		$query = "SELECT count(emp_id) as num from employee3 where emp_type = '$etype' and current_status = 'Active' ";
		$result = $this->makeQuery($query);			
		$fetch = $this->fetchArray($result);
		$fetch_c = $fetch['num'];
		if($etype=='NESCO-PTA' || $etype=='NESCO-PTP' || $etype=='PTA' || $etype=='PTP'){
			$fetchs = $fetch_c * 0.5; 
			if($fetchs != 0){
				return $fetchs;
			}
		}else if($fetch['num'] != '0'){
			return $fetch_c;
		}else{
			return '';
		}
	}

	function getCtmName($empid)
	{
		$this->Connect();
		$query = "SELECT `firstname`, MID(`middlename`,1,1) AS mname, `lastname`,  `suffix` FROM `applicant` WHERE `app_id` = '$empid'";
		$result = $this->makeQuery($query);
		$fetch = $this->fetchArray($result);
		$name =  $fetch['firstname']." ".$fetch['mname'].". ".$fetch['lastname']." ".$fetch['suffix'];
		return $name;
	}

	function getNameWithSuffix($empid,$no)
	{		
		$this->Connect();
		$query = "SELECT `firstname`, `lastname`, `suffix` FROM `applicant` WHERE `app_id` = '$empid'";
		$result = $this->makeQuery($query);
		$fetch = $this->fetchArray($result);
		switch($no) {
			case '1 ':
				$name =  $fetch['firstname']." ".$fetch['lastname']." ".$fetch['suffix'];
				break;
			case '2':
				$name =  $fetch['lastname'].", ".$fetch['firstname']." ".$fetch['suffix'];
				break;	
			case '3':
				$name =  $fetch['firstname']." ".$fetch['suffix'];
				break;					
			/*default:
				$name =  $fetch['lastname'].", ".$fetch['firstname']." ".$fetch['suffix'];
				break;
				*/
		}
		//$name =  $fetch['firstname']." ".$fetch['lastname']." ".$fetch['suffix'];
		return $name;
	}

	function encryptor($action, $string) 
	{
		$output = false;

		$encrypt_method = "AES-256-CBC";
		//pls set your unique hashing key
		$secret_key = 'muni';
		$secret_iv = 'muni123';

		// hash
		$key = hash('sha256', $secret_key);
		
		// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
		$iv = substr(hash('sha256', $secret_iv), 0, 16);

		//do the encyption given text/string/number
		if( $action == 'encrypt' ) {
			$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
			$output = base64_encode($output);
		}
		else if( $action == 'decrypt' ){
			//decrypt the given text/string/number
			$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
		}

		return $output;
	}

	function getCountSa($cc,$bc,$etype)
	{
		$this->Connect();
	
		$query = "SELECT count(si_details_id) from
			si_details inner join employee3
			on si_details.emp_id = employee3.emp_id
			where 
			company_code = '$cc' and bunit_code = '$bc'
			and (current_status = 'Active' or current_status = 'End of Contract' or current_status = 'Resigned') $etype ";

		$result = $this->makeQuery($query);
		$fetch = $this->fetchArray($result);
		return $fetch['count(si_details_id)']; 			
	}	


	function CountforPosition($emptype,$cc,$bc,$dc)
	{
		$this->Connect();
		$query = "SELECT count(emp_type) FROM `employee3` WHERE emp_type ='$emptype' and current_status = 'active' and company_code = '$cc' 
		and bunit_code = '$bc' and dept_code ='$dc'";
		$result = $this->makeQuery($query);			
		$fetch = $this->fetchArray($result);
		$count = $fetch['count(emp_type)'];
		if($emptype == 'PTA' || $emptype == 'PTP' || $emptype == 'NESCO-PTA' || $emptype == 'NESCO-PTP'){
			$count = $count/2;
		}		
		if($count  == 0){ $count =''; } else{ $count = $count;}	
		return $count;
	}	

	function CountforPositionwithSection($emptype,$cc,$bc,$dc,$sc)
	{
		$this->Connect();
		$query = "SELECT count(emp_type) FROM `employee3` WHERE emp_type ='$emptype' and current_status = 'active' and company_code = '$cc' 
		and bunit_code = '$bc' and dept_code ='$dc' and section_code = '$sc' ";
		$result = $this->makeQuery($query);			
		$fetch = $this->fetchArray($result);
		$count = $fetch['count(emp_type)'];
		if($emptype == 'PTA' || $emptype == 'PTP' || $emptype == 'NESCO-PTA' || $emptype == 'NESCO-PTP'){
			$count = $count/2;
		}	
		if($count  == 0){ $count =''; } else{ $count = $count;}	
		return $count;
	}
	
	function CountPositionSection($position,$emptype,$cc,$bc,$dc,$sc)
	{
		$this->Connect();
		$query = "SELECT count(emp_type) FROM `employee3` WHERE emp_type ='$emptype' and current_status = 'active' and position = '$position' and company_code = '$cc' 
		and bunit_code = '$bc' and dept_code ='$dc' and section_code = '$sc' group by position order by count(position)";
		$result = $this->makeQuery($query);			
		$fetch = $this->fetchArray($result);
		$count = $fetch['count(emp_type)'];
		if($emptype == 'PTA' || $emptype == 'PTP' || $emptype == 'NESCO-PTA' || $emptype == 'NESCO-PTP'){
			$count = $count/2;
		}	
		if($count  == 0){ $count =''; } else{ $count = $count;}	
		return $count;
	}
	function getCountGender($loc,$gen)
	{
		$query = "SELECT emp_id from employee3 where current_status = 'Active' and $loc";
		$result = $this->makeQuery($query);			
		
		$m = 0;
		$f = 0;
		while($r = mysql_fetch_array($result))
		{
			$q = mysql_query("SELECT gender from applicant where app_id = '$r[emp_id]' ");
			while($rr = mysql_fetch_array($q)){
				if($rr['gender'] == 'Female'){
					$f++;
				}else if($rr['gender'] == 'Male'){
					$m++;
				}
			}
		}

		if($gen == "Female"){ 
			if($f == 0)
				return "";
			else
				return $f;
		}else{
			if($m == 0)
				return "";
			else
				return $m;
		}
	}
	function getAge($birthdate)
	{
		$bd  = explode('-',$birthdate); 	
		$b 	 = @$bd[1];	
		$year= @$bd[0];	
		$age = date('Y') - $year;	
		return $age;
	}
	function table_header($fields,$title,$subtitle)
	{
		if($subtitle != ''){ $subtitle = $subtitle."<br>";} else { $subtitle = '';}
		$t = "<center><b style='font-size:18px'>$title</b><BR> $subtitle date: ".date("M d, Y")."</center><br>	
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

	function checking_access($accesscode) // 03292022 miri
	{
		$this->Connect();
		$query = "SELECT count(assign_id) as num FROM access_assignment 
		WHERE access_code ='$accesscode' and access_id='".$_SESSION['accessid']."' ";			

		$result = $this->makeQuery($query);			
		$fetch 	= $this->fetchArray($result);
		$count 	= $fetch['num'];
		return $count;
	}
} 
?>