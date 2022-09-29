<?php
session_start();
include("connection.php");
$date2d = date("Y-m-d"); //date today
$date 	= date('Y-m-d');
$time 	= date("H:i:s");
mysql_set_charset("UTF-8");

$employeetype = " (emp_type IN ('NESCO-BACKUP','NESCO Contractual','NESCO Partimer','NESCO-PTA','Promo-NESCO','NESCO-PTP','NESCO Regular','NESCO Probationary','NESCO Regular Partimer') )";
$empTypeWithOutReg = " (emp_type IN ('NESCO-BACKUP','NESCO-PTA','NESCO-PTP','NESCO Probationary','NESCO Contractual') )";

if(@$_GET['request'] == 'checkusername'){
	$query = mysql_query("SELECT user_no from users where username = '".$_POST['new_user']."' ");
	echo mysql_num_rows($query);
}

else if($_GET['request'] == 'eocrateeforcomment')
{
	$empid 	= $_POST['empid'];
	$rec	= $_POST['rec'];
	
	$ap_q  = mysql_query("SELECT details_id, code, rater, ratercomment, numrate,descrate from appraisal_details where record_no = '$rec' ");	
	$pq	   = mysql_fetch_array($ap_q);	
	
	$query = mysql_query("SELECT q_no,title, rate FROM `appraisal_answer` inner join appraisal on appraisal.appraisal_id = appraisal_answer.appraisal_id WHERE details_id = '$pq[details_id]'  ");
	
	//appraisal type 
	$type_query = mysql_query("SELECT * from appraisal_type where code = '$pq[code]'");
	$tq    = mysql_fetch_array($type_query);
	
	echo "<center><b>".$tq['header']." <br> ".$tq['appraisal']."</b></center><br>";
	echo "
		<input type='hidden' name='detailsid' value='$pq[details_id]'>
		<div class='row'>
			<div class='col-md-6'>
				<table class='table table-striped'>
					<tr><th>NO</th><th>GUIDE QUESTIONS</th><th>RATE</th></tr>";
					while($r = mysql_fetch_array($query))
					{
						echo "
						<tr>
							<td>$r[q_no]</td>
							<td>$r[title]</td>	
							<td>$r[rate]</td>					
						</tr>";	
					}
				echo "</table>								
				
			</div>
			<div class='col-md-6'>				
				<p> <i>NUMRATE:</i> <b>".$pq['numrate']."</b> </p>
				<p> <i>DESCRIPTIVE RATING:</i> <b>".$pq['descrate']."</b> </p>
				<p> <i>Rater:</i> <b>".$nq->getApplicantName($pq['rater'])."</b> </p>
				<p> <i>Supervisor's Comment:</i> <br>
					<b>".$pq['ratercomment']."</b> </p>	
				<p> <i> Your Comment:(required)</i> <br>
					<textarea class='form-control' name='rateecom' rows='6' placeholder='type your comment here....'></textarea> </p>
				<input type='button' class='btn btn-primary' name='signoff' onclick='signoffrateeeoc()' value='Sign Off' > </p>				
			</div>
		</div>";	
}

else if($_GET['request'] == 'insertbenefits')
{
	$empid = $_POST['empid'];
	$recordedby = $_SESSION['username']."-".$_SESSION['emp_id'];
	$select = mysql_query("SELECT app_id from applicant_benefits where emp_id = '$empid' ");
	if($mysql_num_rows($select) == 0){
		$query = mysql_query("INSERT INTO applicant_benefits (app_recordID,app_id,sss_no,pagibig_tracking,pagibig,philhealth,recordedby)
		VALUES ('','$empid','$sssno','$pagibigrtn','$pagibig','$philhealth','$recordedby') ");
	}
	else{	
		$query = mysql_query("UPDATE applicant_benefits set 
		sss_no = '$sssno',
		pagibig_tracking = '$pagibigrtn',
		pagibig = '$pagibig',
		philhealth = '$philhealth'
	WHERE app_id = '' ");
	}
	if($query){
		echo "1";
	}else{ echo "0"; }	
}
else if($_GET['request'] == 'CheckDuplicatePayrollNo')
{
	$payrollno 	= $_POST['pid'];
	$empid		= $_POST['id'];
	$query1 	=  mysql_query("SELECT emp_id FROM employee3 where payroll_no = '$payrollno' and emp_id !='$empid' ");
	$query2 	=  mysql_query("SELECT emp_id FROM employmentrecord_ where payroll_no = '$payrollno' and emp_id !='$empid' ");
	if(mysql_num_rows($query1) || mysql_num_rows($query2)){
		echo "0";
	}else{
		$query = mysql_query("UPDATE employee3 set payroll_no = '$payrollno' where emp_id = '$empid' ");
		if($query){
			echo '1';
		}
	}	
}
else if($_GET['request'] == 'CheckDuplicate')
{
	$appid	 = $_POST['id'];
	$field	 = $_POST['field']; //pagibig,philhealth,pagibig_tracking,sssno
	$val	 = $_POST['val'];
	$table 	 = 'applicant_otherdetails';
	$select  = mysql_query("SELECT app_id from $table where $field = '$val' and app_id != '$appid' ")or die(mysql_error());
		
	if(mysql_num_rows($select) == 0 ){
		echo "1";
	}else{
		echo '0';		
	}
}
else if($_GET['request'] == 'CheckDuplicate_temp')
{
	$appid	 = $_POST['id'];
	$field	 = $_POST['field']; //pagibig,philhealth,pagibig_tracking,sssno
	$val	 = $_POST['val'];
	$table 	 = 'benefits_temp';
	$select  = mysql_query("SELECT app_id from $table where $field = '$val' and app_id != '$appid' ")or die(mysql_error());
		
	if(mysql_num_rows($select) == 0 ){
		echo "1";
	}else{
		echo '0';		
	}
}
else if($_GET['request'] == 'savebenefits')
{
	$fieldval= $_POST['val'];
	if($fieldval == '__-_______-_' || $fieldval =='____-____-____'){
		$fieldval = '';
	}
	
	$appid	 = $_POST['id'];
	$field	 = $_POST['field']; //pagibig,philhealth,pagibig_tracking,sssno
	$table 	 = 'applicant_otherdetails';
	$select  = mysql_query("SELECT app_id from $table where app_id = '$appid' ");
	if(mysql_num_rows($select) > 0 ){
		$query   = mysql_query("UPDATE $table set $field = '$fieldval' where app_id = '$appid' ");
		//echo "UPDATE $table set $field = '$fieldval' where app_id = '$appid' ";
	}else{
		$query   = mysql_query("INSERT INTO $table (no,app_id,$field) VALUES ('','$appid','$fieldval') ");
		//echo "INSERT INTO $table (no,app_id,$field) VALUES ('','$appid','$fieldval') ";
	}
	
	if($query){
		$activity = 'Updated $field no of $appid';
		$nq->savelogs($activity,date('Y-m-d'),date('H:i:s'),$_SESSION['emp_id'],$_SESSION['username']);
		echo "1";
	}else{
		echo "0";
	}
}
else if($_GET['request'] == 'savepagibigrtn')
{
	$pgrtn	 = $_POST['pgrtn'];
	$appid	 = $_POST['id'];
	$table 	 = 'applicant_benefits';
	$query   = mysql_query("UPDATE $table set pagibig_tracking = '$pgrtn' where app_id = '$appid' ");
	if($query){
		$activity = 'Updated pagibigrtn no of $appid';
		$nq->savelogs($activity,date('Y-m-d'),date('H:i:s'),$_SESSION['emp_id'],$_SESSION['username']);
		echo "1";
	}
	else{
		echo "0";
	}
}
else if($_GET['request'] == 'savephilhealth')
{
	$ph		 = $_POST['ph'];
	$appid	 = $_POST['id'];
	$table 	 = 'applicant_benefits';
	$query   = mysql_query("UPDATE $table set philhealth = '$ph' where app_id = '$appid' ");
	if($query){
		$activity = 'Updated philhealth no of $appid';
		$nq->savelogs($activity,date('Y-m-d'),date('H:i:s'),$_SESSION['emp_id'],$_SESSION['username']);
		echo "1";
	}
	else{
		echo "0";
	}
}
else if($_GET['request'] == 'savesssno')
{
	$sssno   = $_POST['sssno'];
	$appid	 = $_POST['id'];
	$table 	 = 'applicant_benefits';
	$query   = mysql_query("UPDATE $table set sss_no = '$sssno' where app_id = '$appid' ");
	if($query){
		$activity = 'Updated sssno of $appid';
		$nq->savelogs($activity,date('Y-m-d'),date('H:i:s'),$_SESSION['emp_id'],$_SESSION['username']);
		echo "1";
	}
	else{
		echo "0";
	}
}
else if($_GET['request'] == 'addbenefitsquery')
{
    ?>
	<script>
	jQuery(function($){
	$("input[name='philhealth']").mask("99-999999999-9");
   	$("input[name='sssno']").mask("99-9999999-9");
   	$("input[name='pagibigrtn']").mask("9999-9999-9999");
	$("input[name='pagibigno']").mask("9999-9999-9999");
	//$('#finalcompletion').DataTable();
});
	</script>
	<?php
	echo "
	<table class='table table-striped'>
		<tr><td>SSSNO<td> <td><input type='text' name='sssno' class='form-control'></td></tr>
		<tr><td>PHILHEALTH<td> <td><input type='text' name='philhealth' class='form-control'></td></tr>
		<tr><td>PAG IBIG RTN<td> <td><input type='text' name='pagibigrtn' class='form-control'></td></tr>
		<tr><td>PAG IBIG MID NO<td> <td><input type='text' name='pagibigno' class='form-control'></td></tr>
		<tr><td><input type='button' class='btn btn-primary btn-sm' value='Submit'><td> <td></td></tr>
	</table>
	";	
}
else if($_GET['request'] == 'saving_temp')
{
	$fieldval= $_POST['val'];
	if($fieldval == '__-_______-_' || $fieldval =='____-____-____'){
		$fieldval = '';
	}
	
	$appid	 = $_POST['id'];
	$field	 = $_POST['field']; //pagibig,philhealth,pagibig_tracking,sssno
	$table 	 = 'benefits_temp';
	$select  = mysql_query("SELECT app_id from $table where app_id = '$appid' limit 1");
	if(mysql_num_rows($select) > 0 ){
		$query   = mysql_query("UPDATE $table set $field = '$fieldval' where app_id = '$appid' ");
	}else{
		$query   = mysql_query("INSERT INTO $table (no,app_id,$field) VALUES ('','$appid','$fieldval') ");
	}
	
	if($query){
		$activity = 'Updated $field no of $appid to benefits_temp';
		$nq->savelogs($activity,date('Y-m-d'),date('H:i:s'),$_SESSION['emp_id'],$_SESSION['username']);
		echo "1";
	}else{
		echo "0";
	}
}
else if($_GET['request'] == 'saveapprovedbenefits')
{
	$fieldval= $_POST['val'];
	if($fieldval == '__-_______-_' || $fieldval =='____-____-____'){
		$fieldval = '';
	}
	
	$appid	 = $_POST['id'];
	$field	 = $_POST['field']; //pagibig,philhealth,pagibig_tracking,sssno
	$table 	 = 'applicant_otherdetails';
	$select  = mysql_query("SELECT app_id from $table where app_id = '$appid' ");
	if(mysql_num_rows($select) > 0 ){
		$query   = mysql_query("UPDATE $table set $field = '$fieldval' where app_id = '$appid' ");
		//echo "UPDATE $table set $field = '$fieldval' where app_id = '$appid' ";
	}else{
		$query   = mysql_query("INSERT INTO $table (no,app_id,$field) VALUES ('','$appid','$fieldval') ");
		//echo "INSERT INTO $table (no,app_id,$field) VALUES ('','$appid','$fieldval') ";
	}
	
	if($query){
		$activity = 'Updated $field no of $appid';
		$nq->savelogs($activity,date('Y-m-d'),date('H:i:s'),$_SESSION['emp_id'],$_SESSION['username']);
		echo "1";
	}else{
		echo "0";
	}
}
else if($_GET['request'] == "countinbox"){
	$countinbox = mysql_query("SELECT msg_id from message_details where cc = '$login_id' and msg_stat = '0' ");
	$ctrinbox	= mysql_num_rows($countinbox);
	echo $ctrinbox;
}
else if($_GET['request'] == "getinbox")
{
	echo "
	<h4>Inbox</h4> <i>(click on the table row to view the details )</i>
	<table class='table table-striped table-hover'>
		<tr>
			<th>Sender</th>
			<th>Subject</th>	
			<th>Date/Time</th>  
			<th>Status</th>            				          				
		</tr>";		
		$inbox = mysql_query("SELECT sender,messages.msg_id,datesent,msg_stat,subject FROM message_details inner join messages on messages.msg_id = message_details.msg_id where cc = '$login_id' order by datesent desc ");
		
		while($r= mysql_fetch_array($inbox))
		{
		echo "
		<tr id='trinbox' data-toggle='modal' data-target='#showmsgmodal' onclick=showmessagedetails('".$r['msg_id']."','inbox') >
			<td>".ucwords(strtolower($nq->getApplicantName($r['sender'])))."</td>
			<td>".$r['subject']."</td>			
			<td>".$nq->changeDateFormat('m/d/Y H:i:s a',$r['datesent'])."</td>
			<td>"; 
				if($r['msg_stat']==0){
					echo "<div id='msgstat'><span class='label label-warning'>unread</span></div>";
				}else if($r['msg_stat']==1){ 
					echo "<div id='msgstat'><span class='label label-success'>read</span></div>";
				} echo "
			</td>			
		</tr>";
		} echo "	
	</table>";
}
else if($_GET['request'] == "viewviolations"){	
	$siid = $_POST['siid'];
	$query = mysql_query("SELECT loa, tardiness, suspension, awol, undertime from si_details where si_details_id = '$siid' ");
	$r = mysql_fetch_array($query);
		$loa = $r['loa'];
		$tardiness = $r['tardiness'];
		$suspension = $r['suspension'];
		$awol = $r['awol'];
		$undertime = $r['undertime'];

	if(mysql_num_rows($query)==0){
		echo "<span style='color:red'><h4 align='center'>No Violations has been inputted!</h4></span>";
	}
	else
	{	
	$empid = $_POST['empid'];
	echo "Name:<b>".$nq->getEmpName($empid)."</b><br>Position:<b>".$nq->getEmpPos($empid)."</b><br>Employee Type:<b>".$nq->getEmpType($empid)."</b><br><br>";

	echo "
	<table class='table' align ='center'>
		<tr>
			<th>VIOLATION NAME</th>
			<th>OCCURENCES</th>		
		</tr>
		<tr>
			<td>LOA</td>
			<td>$loa</td>		
		</tr>
		<tr>
			<td>TARDINESS</td>
			<td>$tardiness</td>		
		</tr>
		<tr>
			<td>SUSPENSION</td>
			<td>$suspension</td>		
		</tr>
		<tr>
			<td>AWOL</td>
			<td>$awol</td>		
		</tr>
		<tr>
			<td>UNDERTIME</td>
			<td>$undertime</td>		
		</tr>
	</table>";
	}	
}

/***BLACKLISTS *****/
else if($_GET['request'] == 'browsenames'){
	//$search = $_POST['search'];		
	$ln = trim($_POST['ln']);
	$fn = trim($_POST['fn']);
	$n  = $ln.", ".$fn;
	$n2 = $ln.",".$fn;
	$q1 = mysql_query("SELECT app_id, lastname, firstname, middlename FROM applicant where lastname like '%$ln%' and firstname like '%$fn%'  ");	
	$q2 = mysql_query("SELECT name, status FROM blacklist where name like '%$n2%'");
	$nn1 = mysql_num_rows($q1);	
	$nn2 = mysql_num_rows($q1);
	if($nn1 == 0 && $nn2 == 0){
		echo '';
	}
	else
	{
		echo "<br>
		<div class='row'>
			<div class='col-md-6'>
				APPLICANT/EMPLOYEE
				<table class='table'>";	
				while($r = mysql_fetch_array($q1))
				{
					$n = $r['lastname'].", ".$r['firstname'];
					$n1= $r['lastname'].", ".$r['firstname'];
					 
					$q3 = mysql_query("SELECT status FROM blacklist where app_id = '$r[app_id]' "); 					
					//$q4 = mysql_query("SELECT status FROM blacklist where name like '%$n%' or name like '%$n1%' ");		
									
										
					if(mysql_num_rows($q3) > 0){
						
						while( $rr = mysql_fetch_array($q3)){				
							echo "<tr><td><a href='employee_details.php?com=$r[app_id]'>".ucwords(strtolower($r['lastname'])).", ".ucwords(strtolower($r['firstname']))." ".ucwords(strtolower($r['middlename']))."</a></td>
							<td> <span class='label label-danger'>".$rr['status']."</span></td><td></td></tr>";
						}				
					
					}
					/*else if(mysql_num_rows($q4) > 0)
					{	
						while( $rr = mysql_fetch_array($q4)){				
							echo "<tr><td><a href='employee_details.php?com=$r[app_id]'>".ucwords(strtolower($r['lastname'])).", ".ucwords(strtolower($r['firstname']))." ".ucwords(strtolower($r['middlename']))."</a></td>
							<td> <span class='label label-danger'>".$rr['status']."</span></td><td></td></tr>";
						}
						
					}*/
					else{
						//$q = mysql_query("SELECT current_status FROM employee3 where app_id = '".$r['app_id']."' ");
						$currentstatus = $nq->getOneField('current_status','employee3',"emp_id = '".$r['app_id']."' ");
										
						echo "<tr>
							<td><a href='employee_details.php?com=$r[app_id]'>".ucwords(strtolower($r['lastname'])).", ".ucwords(strtolower($r['firstname']))." ".ucwords(strtolower($r['middlename']))."</a></td>
							<td>";
							$label = '';
							switch($currentstatus)
							{
								case "blacklisted": $label = 'danger'; break; 
								case "Active": $label = 'success'; break; 
								case "Resigned": $label = 'warning'; break; 
								case "End of Contract": $label = 'warning' ;break;		
							}	
							echo "<span class='label label-$label'>$currentstatus</span></td>";
							//$emps = $r['app_id']."*".$r['lastname'].", ".$r['firstname']." ".$r['middlename'];?>
							<td><button type='button' id='chbtn' class='btn btn-default btn-sm' data-dismiss='modal' onclick='choose("<?php echo $r['app_id']."*".$r['lastname'].", ".$r['firstname']." ".$r['middlename'];?>")'>Choose</button></td>
						</tr><?php
					}
				}
				echo "</table>		
			</div>
			
			<div class='col-md-6'>
				BLACKLISTED 
				<table class='table'>";
				while($r2 = mysql_fetch_array($q2))
				{
					if($n != $r['name']){
						echo "<tr><td>".$r2['name']."</td><td><span class='label label-danger'>".$r2['status']."</span></td></tr>";
					}else{
						echo "<tr><td>".$r2['name']."</td><td><span class='label label-danger'>".$r2['status']."</span></td></tr>";
					}
				}
				echo "</table>
			</div>
		</div>";
	}	
}
else if($_GET['request'] == "saveblacklist")
{
	$dateadded 	= date("Y-m-d");
	$datebls 	= $nq->changeDateFormat('Y-m-d',$_POST['datebls']);
	$emp 		= $_POST['empid'];
	$reportedby = $_POST['reportedby'];
	$reason 	= addslashes($_POST['reason']);
	$status 	= 'blacklisted';
	$bdays		= $_POST['bdays'];
	$addr		= $_POST['addr'];
	$creator	= $_POST['creator'];
	$namesearch = $_POST['namesearch'];
	$ids 		= $nq->splitString("*",$namesearch);

	if($ids != "")
	{
		$key 	= explode("*",$namesearch);		
		$id 	= $ids; //empid
		$name 	= @$key[1]; //name		
		$check 	= mysql_query("SELECT * FROM blacklist where app_id = '$id ' ");		
	}
	else
	{		
		$id 	= "";
		$name 	= $namesearch;
		$check 	= mysql_query("SELECT * FROM blacklist where name = 'trim($name)' ");
	}

	if(mysql_num_rows($check) == 0)
	{
		//insert into blacklisted table
		//blacklist_no, app_id, name, date_blacklisted, date_added, reportedby, reason, status, staff
		$qu = mysql_query("INSERT INTO blacklist
			(blacklist_no, app_id,name, date_blacklisted,date_added,reportedby,reason,status,staff,bday,address)
		VALUES ('','$id','$name','$datebls','$dateadded','$reportedby','$reason','$status','$creator','$bdays','$addr')");

		//inserting to logs
		$date = date("Y-m-d");
		$time = date('H:i:s');		
		$nq->savelogs("Added ".$name." to Blacklisted Employee",$date,$time,@$_SESSION['emp_id'],@$_SESSION['username']);	
		
		//get the current contract status of the employee
		$select = mysql_query("SELECT record_no FROM `EMPLOYEE3` WHERE EMP_ID = '$id' order by record_no desc limit 1 ");
		$rr 	= mysql_fetch_array($select);
		$record = $rr['record_no'];
		
		//update employee3 table
		$update = mysql_query("UPDATE employee3 set current_status = 'blacklisted' where record_no = '$record' ");		

		if($qu){	
			echo 'success';
		}
	}
	else
	{ 
		echo "";
	}	
}

//******* BLACKLISTS END  *******//
else if($_GET['request'] == 'getSIcode'){
	$sid = $_POST['sid'];
	$query = mysql_query("SELECT code from si_details where si_details_id = '$sid' ");
	$r = mysql_fetch_array($query);
	echo $r['code'];
}
else if($_GET['request'] == 'updateSIcode'){
	$sid = $_POST['sid'];
	$code= $_POST['code'];
	$query = mysql_query("Update si_details set code = '$code' where si_details_id = '$sid' ");
	$r = mysql_fetch_array($query);
	echo 1;
}


else if(@$_GET['request'] == "loadEOClist")
{	
	$eoc = $_POST['eoc'];

	if(!empty($eoc)){

		$date = date("Y-m-d");
		$condition = "AND eocdate = '$date'";
	} else {

		$condition = "";
	}
	// storing  request (ie, get/post) global array to a variable  
	$requestData= $_REQUEST;
	$columns = array( 
	// datatable column index  => database column lastname
		
		0=>'emp_id',
		1=>'name',
		2=>'bunit',
		3=>'dept',
		4=>'startdate',
		5=>'eocdate',
		6=>'position',
		7=>'emptype',
		8=>'status'
	);

	// getting total number records without any search
	$sql = " SELECT company_code, bunit_code, dept_code, current_status, record_no, emp_id, name, emp_type, position, startdate, eocdate, epas_code
			FROM employee3
			WHERE (emp_type ='NESCO'  or emp_type = 'NESCO-PTA' or emp_type = 'NESCO-PTP' or emp_type = 'NESCO Probationary' or emp_type = 'NESCO Contractual') and current_status = 'Active' $condition"; 
	$query=mysql_query($sql) or die(mysql_error());
	$totalData = mysql_num_rows($query);
	$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

	$sql = " SELECT company_code, bunit_code, dept_code, current_status, record_no, emp_id, name, emp_type, position, startdate, eocdate, epas_code
			FROM employee3
			WHERE 1=1 AND $empTypeWithOutReg and current_status = 'Active' $condition";
	if(!empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
		$sql.=" AND ( emp_id LIKE '%".$requestData['search']['value']."%' ";    
		$sql.=" OR name LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR emp_type LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR position LIKE '%".$requestData['search']['value']."%' ) ";
	}
	
	$query=mysql_query($sql) or die(mysql_error());
	$totalFiltered = mysql_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	//$requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  	
	$query=mysql_query($sql) or die(mysql_error());

	$data = array();
	$ctr =0;
	while($row=mysql_fetch_array($query)) {  // preparing an array
		
		$getEpas = mysql_query("SELECT details_id, numrate, ratercomment,rateecomment, rater, raterSO, rateeSO FROM appraisal_details
						INNER JOIN employee3 ON appraisal_details.record_no = employee3.record_no 
						WHERE employee3.record_no = '$row[record_no]' AND appraisal_details.emp_id = '$row[emp_id]' AND epas_code= '1' limit 1")or die(mysql_error());	

				$re 		= mysql_fetch_array($getEpas);	
				$numrate 	= $re['numrate'];
				$ratercom	= $re['ratercomment'];
				$rateecom 	= $re['rateecomment'];
				$raterSO	= $re['raterSO'];
				$rateeSO	= $re['rateeSO'];
				$did 		= $re['details_id'];
				$rater      = $nq->getApplicantName($re['rater']);

				$rso = ""; $eso = "";

				
				if($numrate > 0)
		      	{
		      		if($numrate == 100){							$label = "label label-success"; }
					else if($numrate >= 90 && $numrate <= 99.9){	$label = "label label-primary"; }
					else if($numrate >= 85 && $numrate <= 89.9){	$label = "label label-info";    }
					else if($numrate >= 70 && $numrate <= 84.9){    $label = "label label-warning"; }
					else if($numrate >= 0  && $numrate <= 69.9) { 	$label = "label label-danger";  }

					if($raterSO == 1){ $rso = "<center><a href='javascript:void' onclick=\"viewComment('raterSO','".$did."')\" style='text-decoration:none;'><span class='label label-success'> yes </span></a></center>"; } else if($raterSO == 0) { $rso = "<center><a href='javascript:void' onclick=\"viewComment('raterSO','".$did."')\" style='text-decoration:none;'><span class='label label-warning'> no </span></a></center>"; }
					if($rateeSO == 1){ $eso = "<center><a href='javascript:void' onclick=\"viewComment('rateeSO','".$did."')\" style='text-decoration:none;'><span class='label label-success'> yes </span></a></center>"; } else if($rateeSO == 0) { $eso = "<center><a href='javascript:void' onclick=\"viewComment('rateeSO','".$did."')\" style='text-decoration:none;'><span class='label label-warning'> no </span></a></center>"; }
					
					$rates = "<center><a href='javascript:void' onclick=\"viewDetails('".$did."')\" ><span class='$label' title='Click to view Appraisal details'>$numrate</span></a></center>";
					if($numrate >=85){
						if($raterSO == 1 && $rateeSO == 1){
							
							$option = "<select name='proceedTo' onchange=\"proceedTo(this.value,'".$row['emp_id']."',".$numrate.",".$row['record_no'].")\" >
											<option>Proceed to </option>								 
											<option value='Renewal'>Renewal</option>
											<option value='Resigned'>Resigned</option>												
									   </select>
									   <input type='hidden' class='filename_".$row['emp_id']."'>";
						}else{
							
							$option = "<select name='proceedTo' onchange=\"proceedTo(this.value,'".$row['emp_id']."',".$numrate.",".$row['record_no'].")\">
											<option>Proceed to </option>
											<option value='Resigned'>Resigned</option>				
									   </select>";	
						}
						
					} else {
						
							$option = "<select name='proceedTo' onchange=\"proceedTo(this.value,'".$row['emp_id']."',".$numrate.",".$row['record_no'].")\">
											<option>Proceed to </option>
											<option value='Resigned'>Resigned</option>
											<option value='Blacklist'>Blacklist</option>				
									   </select>";					
					}				
				}
		      	else{
					$rates 	= "<center><span class='label label-default'>none</span></center>";
					$option = "";
		      	}			

		
				$link_emp 	= "<a href=?p=employee&com=".$row['emp_id'].">".$row['emp_id']."</a>";
				$nestedData=array(); 
				
				$nestedData[] = $link_emp;
				$nestedData[] = strtoupper($row['name']);
				$nestedData[] = $nq->getBusinessUnitName($row['bunit_code'],$row['company_code']);
				$nestedData[] = $nq->getDepartmentName($row['dept_code'],$row['bunit_code'],$row['company_code']);
				$nestedData[] = $nq->changeDateFormat("m/d/Y",$row['startdate']);
				$nestedData[] = $nq->changeDateFormat("m/d/Y",$row['eocdate']);
				$nestedData[] = $rso;
				$nestedData[] = $eso;
				$nestedData[] = $rates;		
				$nestedData[] = $option;		
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

else if($_GET['request'] == 'savesubordinate')
{
	//SINGLE SAVING -  2/27/17 MIRI
	$supervisor 	= $_POST['sup'];
	$subordinates 	= $_POST['sub'];

	$query 			= mysql_query("SELECT * from leveling_subordinates where ratee = '$supervisor' and  subordinates_rater = '$subordinates' ") or die(mysql_error());
	if(mysql_num_rows($query) > 0){ //check if ni exist na
		echo "Supervisor - Subordinate Setup Already Exist! ";
	}else{
		$select = mysql_query("SELECT * FROM leveling_subordinates WHERE ratee = '' and subordinates_rater = '' limit 1");
		$rse 	= mysql_fetch_array($select);
		$rec_no = $rse['record_no'];
		if($rec_no != ''){
			//echo "updating ".$rec_no;					
			$update = mysql_query("UPDATE leveling_subordinates SET ratee = '$supervisor', subordinates_rater = '$subordinates' WHERE record_no = '$rec_no' ");
			if($update){
				echo "Successfully Saved!";
			}
		}
		else{
			//echo "inserting";			
		 	$insert		= mysql_query("INSERT INTO leveling_subordinates (record_no,ratee,subordinates_rater) VALUES ('','$supervisor','$subordinates') ") or die(mysql_error());
			if($insert){
				echo "Successfully Saved!";
			}
		}
	}
}
else if($_GET['request'] == 'save_mult_subordinates')
{	
	//MULTIPLE SAVING  2/27/17 MIRI	
	$sup = $_POST['sup'];
	$emp = $_POST['emp'];	
	$emp = explode(",",$emp);
	
	for($i=0;$i<count($emp)-1;$i++)
	{
		$select = mysql_query("SELECT * FROM leveling_subordinates WHERE ratee = $sup and subordinates_rater = '$emp[$i]' ");	 
		if(mysql_num_rows($select) == 0) //IF WALA
		{	
			$insert = mysql_query("INSERT INTO leveling_subordinates (record_no,ratee,subordinates_rater) VALUES ('','$sup','$emp[$i]')");
			//echo "INSERT INTO leveling_subordinates (record_no,ratee,subordinates_rater) VALUES ('','$sup','$emp[$i]')";	
		}
	}

	if($insert){
			echo "Successfully Saved!";
	}else{			
		echo "Supervisor and Subordinate Setup Already Exist! ";				
	}
} 
else if($_GET['request'] == 'showtablesubordinates')
{
	$sup 	= $_POST['mulsup'];
	$code 	= $_POST['code']; 
	$ec 	= explode("/",$code);
	$cc	   	= @$ec[0];
	$bc		= @$ec[1];
	$dc		= @$ec[2];
	$sc		= @$ec[3];
	$ssc	= @$ec[4];
	//$uc		= @$ec[5];

	if($ssc != ''){
		$location = "and company_code = '$cc' and bunit_code = '$bc' and dept_code = '$dc' and section_code = '$sc' and sub_section_code = '$sc' ";
	}else if($sc != ''){
		$location = "and company_code = '$cc' and bunit_code = '$bc' and dept_code = '$dc' and section_code = '$sc' ";
	}else if($dc != ''){
		$location = "and company_code = '$cc' and bunit_code = '$bc' and dept_code = '$dc'";
	}else if($bc != ''){
		$location = "and company_code = '$cc' and bunit_code = '$bc' ";
	}else if($cc != ''){
		$location = "and company_code = '$cc' ";
	}

	$query = mysql_query("
		SELECT emp_id, name, position, emp_type, current_status
		FROM employee3
		WHERE current_status = 'Active' and emp_id != '$sup' $location
  		") or die(mysql_error());

	
	echo 
	"<input type='checkbox' class='chk_0' onclick='chk(0)'/> Check All Per Page
	<p align='right'> <input type='button' class='btn btn-success btn-sm' value='Save Subordinates' onclick='save_multsubordinate()'> </p>
	<table class='table table-striped table-bordered' id='sub-table'>
		<thead>
			<th></th>			
			<th>EMPID</th>			
			<th>NAME</th>
			<th>POSITION</th>
			<th>EMPTYPE</th>			
			<th>CURRENT STATUS</th>

		</thead>		
		<tbody>";
			
			$ctr 	= 0; //ratee - supervisor //subordinates_rater - sakop
			while($r = mysql_fetch_array($query))
			{		
				$subordinates = mysql_query("SELECT * from leveling_subordinates where ratee = '$sup' and subordinates_rater = '$r[emp_id]' ");	
				if(mysql_num_rows($subordinates) == 0){ 	
					echo "
						<tr>
						<td><input name='subordinates[]' type='checkbox' class='chkC' value='$r[emp_id]' /></td>	
						<td>$r[emp_id]</td>			
						<td>".ucwords(strtolower(utf8_encode($r['name'])))."</td>
						<td>$r[position]</td>
						<td>$r[emp_type]</td>						
						<td>$r[current_status]</td>
						</tr>";
				}				
			}			
			echo "
		</tbody>
	</table>";	
	?>
	<link href='../datatables/jquery.dataTables.css' rel='stylesheet'/> 
	<script src="../datatables/jquery-1.11.1.min.js" type="text/javascript"></script>
	<script src="../datatables/jquery.dataTables.min.js" type="text/javascript"></script>

	<script>
	$(document).ready(function() {
	    $('#sub-table').DataTable();
	} );
	</script>
	<?php
} 
else if($_GET['request'] == 'removetablesubordinates')
{
	$sup = $_POST['sup'];
	echo 
	"<input type='checkbox' class='chk_0' onclick='chk(0)'/> Check All Per Page
	<p align='right'> <input type='button' class='btn btn-success btn-sm' value='Remove Subordinates' onclick='removingsubordinate()' > </p>
	<table class='table table-striped table-bordered' id='sub-table'>
		<thead>
			<th></th>			
			<th>EMPID</th>			
			<th>NAME</th>
			<th>POSITION</th>
			<th>EMPTYPE</th>				
			<th>CURRENT STATUS</th>	
		</thead>		
		<tbody>";			
			$subordinates = mysql_query("SELECT leveling_subordinates.record_no, emp_id, name, position, emp_type, current_status from leveling_subordinates
				INNER JOIN employee3
				on leveling_subordinates.subordinates_rater = employee3.emp_id
				where ratee = '$sup' ");	
			while($r = mysql_fetch_array($subordinates))
			{						 
				echo "
					<tr>
						<td><input name='removesubordinates[]' type='checkbox' class='chkC' value='$r[record_no]' /></td>	
						<td>$r[emp_id]</td>			
						<td>".ucwords(strtolower(utf8_encode($r['name'])))."</td>
						<td>$r[position]</td>
						<td>$r[emp_type]</td>						
						<td>$r[current_status]</td>
					</tr>";
			}			
			echo "
		</tbody>
	</table>";	

	?>
	<link href='../datatables/jquery.dataTables.css' rel='stylesheet'/> 
	<script src="../datatables/jquery-1.11.1.min.js" type="text/javascript"></script>
	<script src="../datatables/jquery.dataTables.min.js" type="text/javascript"></script>

	<script>
	$(document).ready(function() {
	    $('#sub-table').DataTable();
	} );
	</script>
	<?php
}
else if($_GET['request'] == 'deletesubordinates')
{
	//$sup = $_POST['sup'];
	$rec = $_POST['rec'];	
	$rec = explode(",",$rec);
	
	$update ='';
	for($i=0;$i<count($rec)-1;$i++)
	{
		$select = mysql_query("SELECT * FROM leveling_subordinates WHERE record_no ='$rec[$i]' ");	 
		if(mysql_num_rows($select) > 0) //IF WALA
		{	
			//echo "UPDATE leveling_subordinates SET ratee='', subordinates_rater='' WHERE record_no = '$rec[$i]'";
			$update = mysql_query("UPDATE leveling_subordinates SET ratee='', subordinates_rater='' WHERE record_no = '$rec[$i]' ");					
		}		
	}
	if($update){
		echo "Successfully Remove!";	
	}
	else{			
		echo "Removing Failed!";			
	}
}
else if(@$_GET['request'] == "loadContractHistory")
{		
	$empid	= explode("*",$_GET['empid']);
	$empid	= $empid[0];
	
	$fields = "record_no, emp_id, startdate, eocdate, current_status, emp_type, company_code, bunit_code, dept_code, section_code";
	$sql = mysql_query("SELECT $fields FROM `employee3` where emp_id = '$empid' ");
	$sql1 = mysql_query("SELECT $fields FROM `employmentrecord_` where emp_id = '$empid' ORDER by startdate desc");
	
	echo 
	"<table class='table table-striped' style='font-size:12px;' width='100px'>
	<tr>
		<th>STARTDATE</th>
		<th>EOCDATE</th>
		<th>CURRENT STATUS</th>
		<th>EMPTYPE</th>		
		<th>BUSINESS UNIT</th>
		<th>DEPARTMENT</th>
		<th>SECTION</th>		
		<th>PERMIT</th>
		<th>CONTRACT</th>
	</tr>
	";
	while($r=mysql_fetch_array($sql))
	{
		if($r['startdate'] != NULL or $r['startdate'] != ''){  
			$startdate = $nq->changeDateFormat('m/d/Y',$r['startdate']);
		}	
		if($r['eocdate'] != NULL or $r['eocdate'] != ''){  
			$eocdate = $nq->changeDateFormat('m/d/Y',$r['eocdate']);
		}			
		
		echo "<tr>
			<td>$startdate</td>
			<td>$eocdate</td>
			<td>$r[current_status]</td>
			<td>$r[emp_type]</td>
			<td>".$nq->getBusinessUnitName($r['bunit_code'],$r['company_code'])."</td>
			<td>".$nq->getDepartmentName($r['dept_code'],$r['bunit_code'],$r['company_code'])."</td>
			<td>".$nq->getSectionName($r['section_code'],$r['dept_code'],$r['bunit_code'],$r['company_code'])."</td>
			<td><a href=''>reprint</a></td>
			<td><a href=''>reprint</a></td>
			</tr>";
	}	
	while($r1=mysql_fetch_array($sql1))
	{
		if($r1['startdate'] != NULL or $r1['startdate'] != ''){  
			$startdate = $nq->changeDateFormat('m/d/Y',$r1['startdate']);
		}	
		if($r1['eocdate'] != NULL or $r1['eocdate'] != ''){  
			$eocdate = $nq->changeDateFormat('m/d/Y',$r1['eocdate']);
		}			
		
		echo "<tr>
			<td>$startdate</td>
			<td>$eocdate</td>
			<td>$r1[current_status]</td>
			<td>$r1[emp_type]</td>
			<td>".$nq->getBusinessUnitName($r1['bunit_code'],$r1['company_code'])."</td>
			<td>".$nq->getDepartmentName($r1['dept_code'],$r1['bunit_code'],$r1['company_code'])."</td>
			<td>".$nq->getSectionName($r1['section_code'],$r1['dept_code'],$r1['bunit_code'],$r1['company_code'])."</td>
			<td><a href=''>reprint</a></td>
			<td><a href=''>reprint</a></td>
			</tr>";
	}	
}


// nesco recruitment
else if(@$_GET['request'] == "loadFinalReq")
{		
	
	// storing  request (ie, get/post) global array to a variable  
	$requestData= $_REQUEST;
	$columns = array( 
	// datatable column index  => database column lastname		
		
		0=>'appno',
		1=>'lastname',
		2=>'firstname',
		3=>'position', 
		4=>'status'
	);

	// getting total number records without any search
	$sql = "SELECT app_code, applicant.lastname, applicant.firstname, applicant.middlename, position, date_time, applicant.suffix, app_id FROM applicants, applicant WHERE applicants.app_code = applicant.appcode AND applicants.status = 'for final completion' AND tagged_to = 'NESCO' ";
	$query=mysql_query($sql) or die(mysql_error());
	$totalData = mysql_num_rows($query);
	$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

	$sql = "SELECT app_code, applicant.lastname, applicant.firstname, applicant.middlename, position, date_time, applicant.suffix, app_id FROM applicants, applicant 
	WHERE 1=1 AND applicants.app_code = applicant.appcode AND applicants.status = 'for final completion' AND tagged_to = 'NESCO' ";

	if(!empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
		$sql.=" AND ( applicant.lastname LIKE '%".$requestData['search']['value']."%' ";    
		$sql.=" OR applicant.firstname LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR applicant.middlename LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR position LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR date_time LIKE '%".$requestData['search']['value']."%' ) ";
	}
	
	$query=mysql_query($sql) or die(mysql_error());
	$totalFiltered = mysql_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
	$query=mysql_query($sql) or die(mysql_error());

	$ctr = 1;
	$data = array();	
	while($row=mysql_fetch_array($query)) {  // preparing an array		
		
		$suffix = $row['suffix'];
		$mname = $row['middlename'];
		if($suffix !=""){ $suffix = " $suffix,"; } else { $suffix=""; }
		if($mname!="") {$mname = " $mname"; } else { $mname = ""; }
	
		$link = "<a href='?p=employee&com=".$row['app_id']."' target='_blank'>".$row['app_id']."</a>";
		$appName =  "<b>".utf8_encode($row['lastname'])."</b>, ".utf8_encode($row['firstname'])."".$suffix."".utf8_encode($mname);
		$option  = "<center><button class='btn btn-sm btn-primary' onclick=checkReq(\"$row[app_id]\",\"$row[app_code]\")><span class='fa fa-check-square-o'></span> Check Requirements</button></center>";

		$nestedData=array(); 
		$nestedData[] = $link;
		$nestedData[] = $appName;
		$nestedData[] = $row['position'];
		$nestedData[] = date("m/d/Y",strtotime($row['date_time']));		
		$nestedData[] = $option;	
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

else if(@$_GET['request'] == "loadAppToBeHired")
{		
	
	// storing  request (ie, get/post) global array to a variable  
	$requestData= $_REQUEST;
	$columns = array( 
	// datatable column index  => database column lastname		
		
		0=>'appno',
		1=>'lastname',
		2=>'firstname',
		3=>'position', 
		4=>'status'
	);

	// getting total number records without any search
	$sql = "SELECT app_code, applicant.lastname, applicant.firstname, applicant.middlename, position, date_time, attainment, applicant.suffix, app_id FROM applicants, applicant WHERE applicants.app_code = applicant.appcode AND applicants.status = 'for hiring' AND tagged_to = 'NESCO' ";
	$query=mysql_query($sql) or die(mysql_error());
	$totalData = mysql_num_rows($query);
	$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

	$sql = "SELECT app_code, applicant.lastname, applicant.firstname, applicant.middlename, position, date_time, attainment, applicant.suffix, app_id FROM applicants, applicant 
	WHERE 1=1 AND applicants.app_code = applicant.appcode AND applicants.status = 'for hiring' AND tagged_to = 'NESCO' ";

	if(!empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
		$sql.=" AND ( applicant.lastname LIKE '%".$requestData['search']['value']."%' ";    
		$sql.=" OR applicant.firstname LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR applicant.middlename LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR position LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR date_time LIKE '%".$requestData['search']['value']."%' ) ";
	}
	
	$query=mysql_query($sql) or die(mysql_error());
	$totalFiltered = mysql_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
	$query=mysql_query($sql) or die(mysql_error());

	$ctr = 1;
	$data = array();	
	while($row=mysql_fetch_array($query)) {  // preparing an array		
		
		$suffix = $row['suffix'];
		$mname = $row['middlename'];
		if($suffix !=""){ $suffix = " $suffix,"; } else { $suffix=""; }
		if($mname!="") {$mname = " $mname"; } else { $mname = ""; }
	
		$link = "<a href='?p=employee&com=".$row['app_id']."' target='_blank'>".$row['app_id']."</a>";
		$appName =  "<b>".utf8_decode(utf8_encode($row['lastname']))."</b>, ".utf8_decode(utf8_encode($row['firstname']))."".$suffix."".utf8_decode(utf8_encode($mname));
		$option  = "<center><button class='btn btn-sm btn-success' onclick=hiredNow(\"$row[app_id]\",\"$row[app_code]\")> Hired Applicant?</button></center>";

		$nestedData=array(); 
		$nestedData[] = $link;
		$nestedData[] = $appName;
		$nestedData[] = $row['position'];
		$nestedData[] = $row['attainment'];
		$nestedData[] = date("m/d/Y",strtotime($row['date_time']));		
		$nestedData[] = $option;	
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

else if(@$_GET['request'] == "loadNewlyHired")
{		
	
	// storing  request (ie, get/post) global array to a variable  
	$requestData= $_REQUEST;
	$columns = array( 
	// datatable column index  => database column lastname		
		
		0=>'app_id',
		1=>'lastname',
		2=>'firstname',
		3=>'position', 
		4=>'attainment', 
		5=>'date_hired',
		6=>'date_applied'
	);

	// getting total number records without any search
	$sql = "SELECT applicant.lastname, applicant.firstname, applicant.middlename, position, date_time, applicant.suffix, applicant.app_id, attainment, date_applied, date_hired FROM applicants, applicant, application_details WHERE applicants.app_code = applicant.appcode AND applicant.app_id = application_details.app_id AND application_details.application_status='Hired' AND tagged_to = 'NESCO' ";
	$query=mysql_query($sql) or die(mysql_error());
	$totalData = mysql_num_rows($query);
	$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

	$sql = "SELECT app_code, applicant.lastname, applicant.firstname, applicant.middlename, position, date_time, applicant.suffix, applicant.app_id, attainment, date_applied, date_hired FROM applicants, applicant, application_details 
	WHERE 1=1 AND applicants.app_code = applicant.appcode AND applicant.app_id = application_details.app_id AND application_details.application_status='Hired' AND tagged_to = 'NESCO' ";

	if(!empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
		$sql.=" AND ( applicant.lastname LIKE '%".$requestData['search']['value']."%' ";    
		$sql.=" OR applicant.firstname LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR applicant.middlename LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR position LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR date_time LIKE '%".$requestData['search']['value']."%' ) ";
	}
	
	$query=mysql_query($sql) or die(mysql_error());
	$totalFiltered = mysql_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
	$query=mysql_query($sql) or die(mysql_error());

	$ctr = 1;
	$data = array();	
	while($row=mysql_fetch_array($query)) {  // preparing an array		
		
		$suffix = $row['suffix'];
		$mname = $row['middlename'];
		if($suffix !=""){ $suffix = " $suffix,"; } else { $suffix=""; }
		if($mname!="") {$mname = " $mname"; } else { $mname = ""; }
	
		$link = "<a href='?p=employee&com=".$row['app_id']."' target='_blank'>".$row['app_id']."</a>";
		$appName =  "<b>".utf8_decode(utf8_encode($row['lastname']))."</b>, ".utf8_decode(utf8_encode($row['firstname']))."".$suffix."".utf8_decode(utf8_encode($mname));
		
		$nestedData=array(); 
		$nestedData[] = $link;
		$nestedData[] = $appName;
		$nestedData[] = $row['position'];
		$nestedData[] = $row['attainment'];		
		$nestedData[] = date("m/d/Y",strtotime($row['date_applied']));		
		$nestedData[] = date("m/d/Y",strtotime($row['date_hired']));		
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


else if(@$_GET['request'] == "loadEmpForDeployment")
{		
	
	// storing  request (ie, get/post) global array to a variable  
	$requestData= $_REQUEST;
	$columns = array( 
	// datatable column index  => database column lastname		
		
		0=>'appno',
		1=>'lastname',
		2=>'firstname',
		3=>'position', 
		4=>'status'
	);

	// getting total number records without any search
	$sql = "SELECT app_code, applicant.lastname, applicant.firstname, applicant.middlename, position, date_time, attainment, applicant.suffix, app_id FROM applicants, applicant WHERE applicants.app_code = applicant.appcode AND applicants.status = 'new employee' AND tagged_to = 'NESCO' ";
	$query=mysql_query($sql) or die(mysql_error());
	$totalData = mysql_num_rows($query);
	$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

	$sql = "SELECT app_code, applicant.lastname, applicant.firstname, applicant.middlename, position, date_time, attainment, applicant.suffix, app_id FROM applicants, applicant 
	WHERE 1=1 AND applicants.app_code = applicant.appcode AND applicants.status = 'new employee' AND tagged_to = 'NESCO' ";

	if(!empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
		$sql.=" AND ( applicant.lastname LIKE '%".$requestData['search']['value']."%' ";    
		$sql.=" OR applicant.firstname LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR applicant.middlename LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR position LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR date_time LIKE '%".$requestData['search']['value']."%' ) ";
	}
	
	$query=mysql_query($sql) or die(mysql_error());
	$totalFiltered = mysql_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
	$query=mysql_query($sql) or die(mysql_error());

	$ctr = 1;
	$data = array();	
	while($row=mysql_fetch_array($query)) {  // preparing an array		
		
		$suffix = $row['suffix'];
		$mname = $row['middlename'];
		if($suffix !=""){ $suffix = " $suffix,"; } else { $suffix=""; }
		if($mname!="") {$mname = " $mname"; } else { $mname = ""; }
	
		$link = "<a href='?p=employee&com=".$row['app_id']."' target='_blank'>".$row['app_id']."</a>";
		$appName =  "<b>".utf8_decode(utf8_encode($row['lastname']))."</b>, ".utf8_decode(utf8_encode($row['firstname']))."".$suffix."".utf8_decode(utf8_encode($mname));
		$option  = "<center><button class='btn btn-sm btn-success' onclick=deployNow(\"$row[app_id]\",\"$row[app_code]\")> Deploy Now?</button></center>";

		$nestedData=array(); 
		$nestedData[] = $link;
		$nestedData[] = $appName;
		$nestedData[] = $row['position'];
		$nestedData[] = $row['attainment'];
		$nestedData[] = date("m/d/Y",strtotime($row['date_time']));		
		$nestedData[] = $option;	
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

else if(@$_GET['request'] == "loadNewlyDeployed")
{		
	
	// storing  request (ie, get/post) global array to a variable  
	$requestData= $_REQUEST;
	$columns = array( 
	// datatable column index  => database column lastname		
		
		0=>'app_id',
		1=>'lastname',
		2=>'firstname',
		3=>'position', 
		4=>'attainment', 
		5=>'date_hired',
		6=>'date_deployed'
	);

	// getting total number records without any search
	$sql = "SELECT applicant.lastname, applicant.firstname, applicant.middlename, position, date_time, applicant.suffix, applicant.app_id, attainment, date_deployed, date_hired FROM applicants, applicant, application_details WHERE applicants.app_code = applicant.appcode AND applicant.app_id = application_details.app_id AND application_details.application_status='Deployed' AND tagged_to = 'NESCO' ";
	$query=mysql_query($sql) or die(mysql_error());
	$totalData = mysql_num_rows($query);
	$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

	$sql = "SELECT app_code, applicant.lastname, applicant.firstname, applicant.middlename, position, date_time, applicant.suffix, applicant.app_id, attainment, date_deployed, date_hired FROM applicants, applicant, application_details 
	WHERE 1=1 AND applicants.app_code = applicant.appcode AND applicant.app_id = application_details.app_id AND application_details.application_status='Deployed' AND tagged_to = 'NESCO' ";

	if(!empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
		$sql.=" AND ( applicant.lastname LIKE '%".$requestData['search']['value']."%' ";    
		$sql.=" OR applicant.firstname LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR applicant.middlename LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR position LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR date_time LIKE '%".$requestData['search']['value']."%' ) ";
	}
	
	$query=mysql_query($sql) or die(mysql_error());
	$totalFiltered = mysql_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
	$query=mysql_query($sql) or die(mysql_error());

	$ctr = 1;
	$data = array();	
	while($row=mysql_fetch_array($query)) {  // preparing an array		
		
		$suffix = $row['suffix'];
		$mname = $row['middlename'];
		if($suffix !=""){ $suffix = " $suffix,"; } else { $suffix=""; }
		if($mname!="") {$mname = " $mname"; } else { $mname = ""; }
	
		$link = "<a href='?p=employee&com=".$row['app_id']."' target='_blank'>".$row['app_id']."</a>";
		$appName =  "<b>".utf8_decode(utf8_encode($row['lastname']))."</b>, ".utf8_decode(utf8_encode($row['firstname']))."".$suffix."".utf8_decode(utf8_encode($mname));
		
		$nestedData=array(); 
		$nestedData[] = $link;
		$nestedData[] = $appName;
		$nestedData[] = $row['position'];
		$nestedData[] = $row['attainment'];		
		$nestedData[] = date("m/d/Y",strtotime($row['date_hired']));		
		$nestedData[] = date("m/d/Y",strtotime($row['date_deployed']));		
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
// end here

else if($_GET['request'] == "resetPass")
{
	$password = 'Hrms2014';
	$reset = mysql_query("UPDATE users SET password = md5('$password') WHERE user_no = '".$_POST['userno']."' ");
	if($reset)
	{
		echo "Successfully Resetted the password.";		
	}else{ echo "Resetting Password Failed.";}
}
else if($_GET['request'] == "activateAccount")
{
	$status = 'active';
	$reset = mysql_query("UPDATE users SET user_status = '$status' WHERE user_no = '".$_POST['userno']."' ");
	if($reset)
	{
		echo "Successfully Activated the user account.";		
	}else{ echo "Activating Failed.";}
}
else if($_GET['request'] == "deactivateAccount")
{
	$status = 'inactive';
	$reset = mysql_query("UPDATE users SET user_status = '$status' WHERE user_no = '".$_POST['userno']."' ");
	if($reset){
		echo "Successfully Deactivated the user account.";		
	}else{ echo "Deactivating Failed.";}
}
else if($_GET['request'] == 'birthday')
{
	$month_day = date('m-d');	
	$bday = mysql_query("SELECT count(emp_id) from applicant inner join employee3 on applicant.app_id = employee3.emp_id where birthdate like '%$month_day' and current_status = 'active'
	and ($employeetype)")or die(mysql_error());	
	$r = mysql_fetch_array($bday);
	$countbday = $r['count(emp_id)'];
	if($countbday != ""){ echo $countbday; } else {echo "0";}
}
else if($_GET['request'] == 'newemp')
{
	$lastmonth = date("Y-m-d",strtotime("-1 month"));
	
	$date = date('Y-m-d');
	$new = mysql_query("SELECT count(emp_id) from employee3 where tag_as = 'new' and startdate between '$lastmonth' and '$date' 
	and ($employeetype)") or die(mysql_error());		
	$r = mysql_fetch_array($new);
	$countnew = $r['count(emp_id)'];		
	if($countnew != ""){ echo $countnew; } else {echo "0";}	
}
else if($_GET['request']=="jobtransthisweek")
{
	$sevenday  = date('Y-m-d', strtotime('-20 days'));
	$sevendays  = date('Y-m-d', strtotime('+20 days'));
	//$query = mysql_query("SELECT transfer_no FROM employee_transfer_details where effectiveon between '$sevenday' and '$sevendays' 	");
	$query = mysql_query("SELECT employee_transfer_details.transfer_no, employee3.name, employee_transfer_details.emp_id, employee_transfer_details.effectiveon, employee_transfer_details.old_position, employee_transfer_details.position
			FROM employee_transfer_details
			INNER JOIN employee3 ON employee_transfer_details.emp_id = employee3.emp_id
			WHERE effectiveon between '$sevenday' and '$sevendays' and $employeetype ");
	$countfc = mysql_num_rows($query);	
	if($countfc != ""){ echo $countfc; } else {echo "0";}		
}
else if($_GET['request'] == "eocToday")
{
	$today = date("Y-m-d");
	$query = mysql_query("SELECT emp_id
			FROM employee3
			WHERE (emp_type ='NESCO'  or emp_type = 'NESCO-PTA' or emp_type = 'NESCO-PTP' or emp_type = 'NESCO Probationary') and current_status = 'Active' AND eocdate = '$today'")or die(mysql_error());
	$countEoc = mysql_num_rows($query);	
	if($countEoc != ""){ echo $countEoc; } else { echo "0"; }
}
else if($_GET['request']=="newblacklists")
{
	$sevenday  = date('Y-m-d', strtotime('-7 days'));
	$sevendays  = date('Y-m-d', strtotime('+7 days'));
	$query = mysql_query("SELECT blacklist_no FROM blacklist where date_blacklisted between '$sevenday' and '$sevendays'  ");
	$countfc = mysql_num_rows($query);
	if($countfc != ""){ echo $countfc; } else {echo "0";}		
}

else if($_GET['request'] == "updateblacklist")
{
	$name	= $nq->getAppName($_POST['empid']);
	$datebl = $nq->changeDateFormat('Y-m-d',$_POST['datebls']);	
	$date 	= date('m/d/Y');	
	$creator= $_POST['creator'];

	$qus = mysql_query("UPDATE blacklist SET 
	reason = '$_POST[reason]', 
	reportedby = '$_POST[reportedby]', 
	date_blacklisted = '$datebl', 
	staff = '$creator',
	bday = '$_POST[bdays]',
	address = '$_POST[addr]'
	where blacklist_no = '$_POST[blno]' ");	

	if($qus)
	{ 
		//inserting to logs
		$date = date("Y-m-d");
		$nq->savelogs("Updated the blacklist record of ".$name,$date,$time,@$_SESSION['emp_id'],@$_SESSION['username']);	
		echo 'success';
	}else{
		echo "success 2";
	}
}

/* my function :) zoren ormido is here */
else if($_GET['request'] == "viewManual"){
?>

<input type="hidden" name="recordNo">
<input type="hidden" name="empId">
<input type="hidden" name="filename">

<div class="form-group">
    <div class="input-group">
        <input type="text" name="app_id" onkeyup="nameSearch(this.value)" class="form-control textFocus" placeholder="Search Employee (Emp. ID, Lastname or Firstname)" value="" autocomplete="off">
        <span class="input-group-btn">
          <button class="btn btn-info" name="search" onclick="uploadReq()">Search &nbsp;<i class="glyphicon glyphicon-search"></i></button>
        </span>
    </div>
    <div class="search-results" style="display:none;"></div>
</div>

<table class="table">
<tr>
	<td>
		<div class="form-group">
		    <label> Clearance </label>
		    <div class="row">
		    	<div class="col-md-6"><input type="radio" name="clearance" class="clearance" value="clearance" disabled="" onclick="withClearance()"> With Clearance </div>
		    	<div class="col-md-6"><input type="radio" name="clearance" class="clearance" value="noclearance" disabled="" onclick="noClearance()"> Without Clearance </div>
		    </div>
		</div>
	</td>
</tr>
<tr>
	<td>
		<div class="form-group">
		    <label> EOC Appraisal </label>
		    <div class="row">
		    	<div class="col-md-6"><input type="radio" class="epas" value="epas" disabled="" onclick="withEpas()"> With EPAS </div>
		    	<div class="col-md-6"><input type="radio" class="epas" value="noepas" disabled="" onclick="noEpas()"> No EPAS </div>
		    </div>
		</div>
	</td>
</tr>
</table>
<?php
} 

else if($_GET['request'] == "findEmp"){

  	$key = mysql_real_escape_string($_POST['str']);
	$val = "";
	$empname = mysql_query("SELECT employee3.`record_no`, employee3.`emp_id`, `name` FROM `employee3`
								WHERE (current_status = 'Active' or current_status = 'End of Contract' or current_status = 'Resigned') 
								AND (emp_type = 'NESCO-BACKUP' or  emp_type = 'NESCO' or emp_type = 'NESCO Partimer' or emp_type = 'NESCO Probationary' or emp_type = 'NESCO-PTA' or emp_type = 'NESCO-PTP' or emp_type = 'NESCO Contractual') 
								AND (name like '%$key%' or employee3.emp_id = '$key')  order by name limit 10")or die(mysql_error());
	while($n = mysql_fetch_array($empname)){
		$empId = $n['emp_id'];
		$name  = $n['name'];
		$recordNo = $n['record_no'];

		if($val != $empId){
			echo "<a class = \"nameFind\" href = \"javascript:void\" onclick='getEmpId(\"$empId*$recordNo*$name\")'>[ ".$empId." ] = ".$name."</a></br>";
		}
		else{
			echo "<a class = \"afont\" href = \"javascript:void\">No Result Found</a></br>";	
		}
	}
}

else if($_GET['request'] == "findEmpEOCandResign"){

  	$key = mysql_real_escape_string($_POST['str']);
	$val = "";
	$empname = mysql_query("SELECT employee3.`record_no`, employee3.`emp_id`, `name` FROM `employee3`
								WHERE (current_status = 'End of Contract' or current_status = 'Resigned' or current_status = 'V-Resigned') 
								AND (emp_type = 'OJT' or emp_type = 'NESCO' or emp_type = 'NESCO-BACKUP' or emp_type = 'NESCO Partimer' or emp_type = 'NESCO Probationary' or emp_type = 'NESCO-PTA' or emp_type = 'NESCO-PTP' or emp_type = 'NESCO Regular' or emp_type = 'NESCO Contractual') 
								AND (name like '%$key%' or employee3.emp_id = '$key')  order by name limit 10")or die(mysql_error());
	while($n = mysql_fetch_array($empname)){
		$empId = $n['emp_id'];
		$name  = $n['name'];
		$recordNo = $n['record_no'];

		if($val != $empId){
			echo "<a class = \"nameFind\" href = \"javascript:void\" onclick='getEmpId(\"$empId*$recordNo*$name\")'>[ ".$empId." ] = ".$name."</a></br>";
		}
		else{
			echo "<a class = \"afont\" href = \"javascript:void\">No Result Found</a></br>";	
		}
	}
}

else if($_GET['request'] == "findEmpType"){

  	$key = mysql_real_escape_string($_POST['str']);
	$val = "";
	$empname = mysql_query("SELECT employee3.`record_no`, employee3.`emp_id`, `name`, `emp_type` FROM `employee3`
								WHERE (current_status = 'Active' or current_status = 'End of Contract' or current_status = 'Resigned') 
								AND (emp_type = 'NESCO Contractual' or emp_type = 'NESCO' or emp_type = 'NESCO Partimer' or emp_type = 'NESCO Probationary' or emp_type = 'NESCO-PTA' or emp_type = 'NESCO-PTP' or emp_type = 'NESCO Regular' or emp_type = 'NESCO Regular Partimer') 
								AND (name like '%$key%' or employee3.emp_id = '$key')  order by name limit 10")or die(mysql_error());
	while($n = mysql_fetch_array($empname)){
		$empId = $n['emp_id'];
		$name  = $n['name'];
		$recordNo = $n['record_no'];
		$empType = $n['emp_type'];

		if($val != $empId){
			echo "<a class = \"nameFind\" href = \"javascript:void\" onclick='getEmpId(\"$empId*$recordNo*$name*$empType\")'>[ ".$empId." ] = ".$name."</a></br>";
		}
		else{
			echo "<a class = \"afont\" href = \"javascript:void\">No Result Found</a></br>";	
		}
	}
}

else if($_GET['request'] == "findEmpActive"){

  	$key = mysql_real_escape_string($_POST['str']);
	$val = "";
	$empname = mysql_query("SELECT employee3.`record_no`, employee3.`emp_id`, `name`, `emp_type` FROM `employee3`
								WHERE current_status = 'Active'  
								AND (emp_type = 'NESCO'  or emp_type = 'NESCO Contractual' or emp_type = 'NESCO Partimer' or emp_type = 'NESCO Probationary' or emp_type = 'NESCO-PTA' or emp_type = 'NESCO-PTP' or emp_type = 'NESCO Regular' or emp_type = 'NESCO Regular Partimer') 
								AND (name like '%$key%' or employee3.emp_id = '$key')  order by name limit 10")or die(mysql_error());
	while($n = mysql_fetch_array($empname)){
		$empId = $n['emp_id'];
		$name  = $n['name'];
		$recordNo = $n['record_no'];
		$empType = $n['emp_type'];

		if($val != $empId){
			echo "<a class = \"nameFind\" href = \"javascript:void\" onclick='getEmpId(\"$empId*$recordNo*$name*$empType\")'>[ ".$empId." ] = ".$name."</a></br>";
		}
		else{
			echo "<a class = \"afont\" href = \"javascript:void\">No Result Found</a></br>";	
		}
	}
}

else if($_GET['request'] == "findThisApplicant"){

	$key = mysql_real_escape_string($_POST['str']);
	$val = "";
	$empname = mysql_query("SELECT `app_id`, lastname, firstname, middlename, suffix FROM `applicant`  WHERE lastname like '%$key%' OR firstname like '%$key%' or app_id = '$key' ")or die(mysql_error());
		while($n = mysql_fetch_array($empname)){
			$appId = $n['app_id'];
			$firstname 	= ucwords(strtolower($n['firstname']));
			$lastname 	= ucwords(strtolower($n['lastname']));
			$middlename = ucwords(strtolower($n['middlename']));
			$suffix = $n['suffix'];

			if($suffix != ""){
				
				$suffix = " $suffix,";
			} else {

				$suffix = "";
			}
			$name = "$lastname, $firstname$suffix $middlename";

			if($val != $appId){
				echo "<a class = \"nameFind\" href = \"javascript:void\" onclick='getId(\"$appId * $name\")'>".$appId." * ".$name."</a></br>";
			}
			else{
				echo "<a class = \"afont\" href = \"javascript:void\">No Result Found</a></br>";	
			}
		}	
}

else if($_GET['request'] == "resetAll"){

	$recordNo = $_POST['recordNo'];
	$empId = $_POST['empId'];
	$filename = $_POST['filename'];

	if($filename != ""){

		unlink("$filename");
	}

	$empRecord = mysql_query("UPDATE `employee3` SET `epas_code`='',`clearance`='' WHERE `emp_id` = '$empId' and `record_no` = '$recordNo'") or die(mysql_error());
	$appraisal_details = mysql_query("DELETE FROM appraisal_details WHERE `emp_id` = '$empId' AND `record_no` = '$recordNo'") or die(mysql_error());

	if($empRecord && $appraisal_details){

		die("Ok");
	}
}

else if($_GET['request'] == "submitEpas"){

	$recordNo = $_POST['recordNo'];
	$empId = $_POST['empId'];
	$epasGrade = $_POST['epasGrade'];
	$appraisalType = $_POST['appraisalType'];
	$dateTimeAdded = date('Y-m-d H:i:s');
	$addedby = $_SESSION['emp_id'];

	if($epasGrade == 100){
	    $desrate = "Excellent";
	    $code = "E";
    }
    else if($epasGrade >= 90 && $epasGrade <= 99.9){
      	$desrate = "Very Satisfactory";
      	$code = "VS";
    }
    else if($epasGrade >= 85 && $epasGrade <= 89.9){
      	$desrate = "Satisfactory";
      	$code = "S";
    }
    else if($epasGrade >= 70 && $epasGrade <= 84.9){
      	$desrate = "Unsatisfactory";
      	$code = "US";
    }
    else if($epasGrade >= 0 && $epasGrade <= 69.9){
      	$desrate = "Very Unsatisfactory";
      	$code = "VU";
    }

	$updateRec = mysql_query("UPDATE `employee3` SET `epas_code`='1' WHERE `record_no` = '$recordNo' and emp_id = '$empId'")or die(mysql_error());

	$query = mysql_query("SELECT * FROM appraisal_details WHERE emp_id = '$empId' AND  record_no = '$recordNo'")or die(mysql_error());
	$row = mysql_num_rows($query);

	if($row == 0){
		$insertRec = mysql_query("INSERT INTO appraisal_details(emp_id, record_no, addedby, numrate, descrate, ratingdate, code) VALUES ('$empId','$recordNo','$addedby','$epasGrade','$code','$dateTimeAdded',$appraisalType)")or die(mysql_error());
		
	}
    

    $name = $nq->getEmpName($empId);
    $date = date("Y-m-d");
    $time = date("H:i:s");

    $nq->savelogs("Added the EOC Appraisal for Abenson of ".$name." record no.".$recordNo,$date,$time,$_SESSION['emp_id'],$_SESSION['username']);

    if($updateRec){
    	
    	die("Ok");
    } else{

    	die("Error");
    }
}

else if($_GET['request'] == "process_renewal"){

	$notEdited = $_POST['notEdited'];
	$edited = $_POST['edited'];

	$empId = $_POST['empId'];
	$recordNo = $_POST['recordNo'];
	$current_status = $_POST['current_status'];

	if($notEdited == 1){

		// get value of not edited form
		$company 		= $_POST['company'];
		$businessunit 	= $_POST['businessunit'];
		$department 	= $_POST['department'];
		$section 		= $_POST['section'];
		$subsection 	= $_POST['subsection'];
		$unit 			= $_POST['unit'];
		$lodging 		= $_POST['lodging'];
		$position 		= mysql_real_escape_string($_POST['position']);
		$type 			= $_POST['type'];
		$pos_level 		= $_POST['pos_level'];
	} else {

		// get value of edited form
		$company 		= $_POST['company'];
		$businessunit 	= end(explode("/",$_POST['businessunit']));
		$department 	= end(explode("/",$_POST['department']));
		$section 		= end(explode("/",$_POST['section']));
		$subsection 	= end(explode("/",$_POST['subsection']));
		$unit 			= end(explode("/",$_POST['unit']));
		$lodging 		= $_POST['lodging'];
		$position 		= mysql_real_escape_string(@$_POST['position']);
		$type 			= $_POST['type'];
		$pos_level 		= $_POST['pos_level'];
	}

	$months 		= $_POST['months'];
	$startdate 		= $nq->changeDateFormat('Y-m-d',$_POST['startdate']);
	$eocdate 		= $nq->changeDateFormat('Y-m-d',$_POST['eocdate']);
	$witness1 		= $_POST['witness1'];
	$witness2 		= $_POST['witness2'];
	$comment 		= mysql_real_escape_string($_POST['comment']);
	$remarks 		= mysql_real_escape_string($_POST['remarks']);

	$dateadded = date('Y-m-d');
	$addedby = $_SESSION['emp_id'];

	if($_POST['current_status'] == "Active"){

		$current_status = 'End of Contract';
	} else {

		$current_status = $_POST['current_status'];
	}

		$sql = mysql_query(
				"SELECT
					*
				 FROM
					employee3 
				 WHERE
					emp_id = '".$empId."'"
			   ) or die(mysql_error());
		$old_data = mysql_fetch_array($sql);

		$name = $old_data['name'];
		// insert the old contrct to the employment record table
		$insertEmploymentRecord_SQL = mysql_query(
			"INSERT
				INTO
			 employmentrecord_
				(
					emp_id,
					company_code,
					bunit_code,
					dept_code,
					section_code,
					sub_section_code,
					unit_code,
					barcodeId,
					bioMetricId,
					payroll_no,
					startdate,
					eocdate,
					emp_type,
					position,
					positionlevel,
					current_status,
					lodging,
					pos_desc,
					remarks,
					epas_code,
					contract,
					permit,
					clearance,
					comments,
					date_updated,
					updatedby,
					duration,
					emp_no,
					emp_pins,
					pcc
				) VALUES (
					'".$empId."',
					'".$old_data['company_code']."',
					'".$old_data['bunit_code']."',
					'".$old_data['dept_code']."',
					'".$old_data['section_code']."',
					'".$old_data['sub_section_code']."',
					'".$old_data['unit_code']."',
					'".$old_data['barcodeId']."',
					'".$old_data['bioMetricId']."',
					'".$old_data['payroll_no']."',
					'".$old_data['startdate']."',
					'".$old_data['eocdate']."',
					'".$old_data['emp_type']."',
					'".$old_data['position']."',
					'".$old_data['positionlevel']."',
					'".$current_status."',
					'".$old_data['lodging']."',
					'".$old_data['position_desc']."',
					'".addslashes($old_data['remarks'])."',
					'".$old_data['epas_code']."',
					'".$old_data['contract']."',
					'".$old_data['permit']."',
					'".$old_data['clearance']."',
					'".$old_data['comments']."',
					'".$old_data['date_updated']."',
					'".$old_data['updated_by']."',
					'".$old_data['duration']."',
					'".$old_data['emp_no']."',
					'".$old_data['emp_pins']."',
					'".$old_data['pcc']."'
				)"
		) or die(mysql_error());
		$sql = mysql_query(
				"SELECT
					record_no
				  FROM
					employmentrecord_
				  WHERE
					emp_id = '".$empId."'
				  ORDER BY 
					record_no DESC"
			   ) or die(mysql_error());
		$new_rno = mysql_fetch_array($sql);
		// appraisal details
		$sql = mysql_query(
				"SELECT 
					record_no
				 FROM
					appraisal_details
				 WHERE
					record_no = '".$old_data['record_no']."' and emp_id = '".$empId."' "
			   ) or die(mysql_error());
	    $c_appdetails = mysql_num_rows($sql);
		if($c_appdetails > 0){
			mysql_query(
				"UPDATE
					appraisal_details
				 SET
					record_no = '".$new_rno['record_no']."'
				 WHERE
					record_no = '".$old_data['record_no']."' and emp_id = '".$empId."'  "
			) or die(mysql_error());
		}
		// witness
		$sql = mysql_query(
				"SELECT
					rec_no
				 FROM
					employment_witness
				 WHERE
					rec_no = '".$old_data['record_no']."'"
			   ) or die(mysql_error());
		$c_empwitness = mysql_num_rows($sql);
		if($c_empwitness > 0){
			mysql_query(
				"UPDATE
					employment_witness
				 SET
					rec_no = '".$new_rno['record_no']."'
				 WHERE
					rec_no = '".$old_data['record_no']."'"
			) or die(mysql_error());
		}
		// employee transfer details
		$sql = mysql_query(
				"SELECT
					record_no
				 FROM
					employee_transfer_details
				 WHERE
					record_no = '".$old_data['record_no']."'"
			   ) or die(mysql_error());
		$c_trnsdetails  = mysql_num_rows($sql);
		if($c_trnsdetails > 0){
			mysql_query(
				"UPDATE
					employee_transfer_details
				 SET
					record_no = '".$new_rno['record_no']."'
				 WHERE
					record_no = '".$old_data['record_no']."'"
			) or die(mysql_error());
		}
		$sql = mysql_query(
				"SELECT
					record_no
				 FROM
					tag_clearances
				 WHERE
					record_no = '".$old_data['record_no']."'"
			   ) or die(mysql_error());
		$c_tag = mysql_num_rows($sql);
		if($c_tag > 0){
			mysql_query(
				"UPDATE
					tag_clearances
				 SET
					record_no = '".$new_rno['record_no']."'
				 WHERE
					record_no = '".$old_data['record_no']."'"
			) or die(mysql_error());
		}
		// delete the old record in employee3
		mysql_query(
			"DELETE
				FROM
			 employee3
				WHERE
			 emp_id = '".$empId."'"
		) or die(mysql_error());
		// insert new in employee 3
		
		$insertEmployee3SQL = mysql_query(
			"INSERT 
				INTO
			 employee3
				(
					emp_id,
					name,
					startdate,
					eocdate,
					emp_type,
					current_status,
					company_code,
					bunit_code,
					dept_code,
					section_code,
					sub_section_code,
					unit_code,
					barcodeId,
					bioMetricId,
					payroll_no,
					position,
					positionlevel,
					comments,
					remarks,
					date_updated,
					updated_by,
					lodging,
					duration,
					emp_no,
					emp_pins,
					pcc
				) VALUES (
					'".$empId."',
					'".$name."',
					'".$startdate."',
					'".$eocdate."',
					'".$type."',
					'Active',
					'".$company."',
					'".$businessunit."',
					'".$department."',
					'".$section."',
					'".$subsection."',
					'".$unit."',
					'".$old_data['barcodeId']."',
					'".$old_data['bioMetricId']."',
					'".$old_data['payroll_no']."',
					'".$position."',
					'".$pos_level."',
					'".$comment."',
					'".$remarks."',
					'".$dateadded."',
					'".$addedby."',
					'".$lodging."',
					'".$months."',
					'".$old_data['emp_no']."',
					'".$old_data['emp_pins']."',
					'".$old_data['pcc']."'
				)"
		) or die(mysql_error());
		$sql = mysql_query(
				"SELECT
					record_no
				 FROM
					employee3
				 WHERE
					emp_id = '".$empId."'
				 ORDER BY
					record_no DESC"
			   ) or die(mysql_error());
		$n_rno = mysql_fetch_array($sql);
		mysql_query(
			"INSERT
				INTO
			 employment_witness
				VALUES
			(
				'',
				'',
				'".$n_rno['record_no']."',
				'".$witness1."',
				'".$witness2."',
				'',
				'',
				'',
				'',
				'',
				'',
				''
			)"
		) or die(mysql_error());
	

		$rec = mysql_query("SELECT record_no from employee3 WHERE emp_id = '".$empId."'");
		$r = @mysql_fetch_array($rec);
		$record = $r['record_no'];
			
		
		//logs
		$activity 		= "Added a new Contract of Employment of ".$name." Record no:".$record;
		$date 			= date("Y-m-d");
		$time 			= date("H:i:s");	
		$nq->savelogs($activity,$date,$time,$_SESSION['emp_id'],$_SESSION['username']);	

	if($insertEmploymentRecord_SQL && $insertEmployee3SQL){
		die("Ok");
	}

}

else if($_GET['request'] == "printContractPermit"){

	$empId = $_POST['empId'];

	$query = mysql_query("SELECT record_no, position, emp_type FROM employee3 WHERE emp_id = '$empId'")or die(mysql_error());
	$r = mysql_fetch_array($query);

	$recordNo = $r['record_no'];
	$position = $r['position'];
	$emp_type = $r['emp_type'];

	$posno = $nq->getPositionNo($position);
?>
	<div class="row">
		<br>
		<div class="col-md-8 col-md-offset-2">
			<button class="btn btn-primary btn-md" onclick="permit('<?php echo $recordNo; ?>')"> Permit-To-Work </button>
			<button class="btn btn-primary btn-md" data-toggle='modal' data-target='#viewContract' onclick="contract('<?php echo $recordNo; ?>','<?php echo $emp_type; ?>','<?php echo $empId; ?>')"> Contract of Employment </button>
			<button class="btn btn-primary btn-md" onclick="editKra('<?php echo $posno; ?>','<?php echo $empId; ?>')"> KRA </button>
		</div>
		<br>
		<br>
		<br>
	</div>

<?php
}

else if($_GET['request'] == "printContract"){

	$recordNo = $_POST['recordNo'];
	$empType  = $_POST['empType'];
	$empId    = $_POST['empId'];
	$sssnum   = $nq->getSSS($empId);
?>
	<input type="hidden" name='newRecordNo' value="<?php echo $recordNo; ?>">
    <input type="hidden" name='empType' value="<?php echo $empType; ?>">

    <div class="form-group">
    	<label>Please choose either to use Cedula (CTC No.) or SSS No.</label>
    	<div style="margin-left:50px;">	
    		<div class="form-group">
	        	<div class="row">
	        		<div class="col-md-4"><input type='radio' name='clear' id='r1' value='Cedula' onclick="sssctc('ctc')"><label>&nbsp;Cedula (CTC No.)</label></div>
	        		<div class="col-md-8"><input type='text' class="form-control" name='cleartf' id='cleartf' disabled="" placeholder="CCI____ ________" onkeyup="onkeyupWitness(this.id)"></div>
	        	</div>
    		</div> 
    		<div class="form-group">
	        	<div class="row">
	        		<div class="col-md-4"><input type='radio' name='clear' id='r2' value='SSS' onclick="sssctc('sss')"><label>&nbsp;SSS No.</label></div>
	        		<div class="col-md-8"><input type='text' class="form-control" name='ssstf' id='ssstf' value="<?php echo $sssnum;?>" disabled="" onkeyup="onkeyupWitness(this.id)"></div>
	        	</div>
	        </div>
    		<p id='is'><label> Issued on: </label><input type='text' class="form-control" name='issuedon' id='issuedon' size='50' placeholder="mm-dd-yyyy" autocomplete="off" onchange="onkeyupWitness(this.id)"></p>		    
        	<label> Issued at: </label><input type='text' class="form-control" name='issuedat' id='issuedat' size='50' onkeyup="onkeyupWitness(this.id)">
    	</div>
    </div>
    <div class="form-group">
    	<label>Date of Signing the Contract/Employee</label>
    	<input type='text' class="form-control" name='contractdate' id='contractdate' placeholder="mm-dd-yyyy" autocomplete="off" onchange="onkeyupWitness(this.id)">
    </div>

<script type="text/javascript" src="../jquery/jquery.maskedinput.js" ></script>
<script>
	
	$(function() {  //minDate: new Date(), minDate: new Date(),
		$( "#issuedon" ).datepicker({ dateFormat: "mm/dd/yy", changeMonth: true, changeYear: true, showButtonPanel: true });
		$( "#contractdate" ).datepicker({ dateFormat: "mm/dd/yy", changeMonth: true, changeYear: true, showButtonPanel: true });

		$("input[name='ssstf']").mask("9999-9999-9999");
		$("input[name='cleartf']").mask("CCI9999 99999999");
	});

	$('#contractdate').click(function(){
	    var popup =$(this).offset();
	    var popupTop = popup.top - 80;
	    $('.ui-datepicker').css({
	      	'top' : popupTop,
	      	'position' : 'fixed',
	      	'top' : '127px',
	      	'left' : '398.5px',
	      	'display' : 'block',
	      	'z-index' : '99999'
	    });
	}); 

	$('#issuedon').click(function(){
	    var popup =$(this).offset();
	    var popupTop = popup.top - 80;
	    $('.ui-datepicker').css({
	      	'top' : popupTop,
	      	'position' : 'fixed',
	      	'top' : '285px',
	      	'left' : '448px',
	      	'display' : 'block',
	      	'z-index' : '99999'
	    });
	}); 
</script>
<?php
}

else if($_GET['request'] == "viewComment"){

	$signOff 	= $_POST['SO'];
	$detailsId 	= $_POST['dId'];

	if($signOff == "raterSO"){

		$query = mysql_query("SELECT `rater`, `ratercomment` FROM `appraisal_details` WHERE `details_id` = '$detailsId'") or die(mysql_error());
		$fetch = mysql_fetch_array($query);

		$rater 	 = $fetch['rater'];
		$comment = $fetch['ratercomment'];
		$raterName = $nq->getEmpName($rater);

	} else {

		$query = mysql_query("SELECT `rateecomment` FROM `appraisal_details` WHERE `details_id` = '$detailsId'") or die(mysql_error());
		$fetch = mysql_fetch_array($query);

		$comment = $fetch['rateecomment'];
	}

?>
	<div class="form-group">
		<?php if($signOff == "raterSO"){ ?>
			<label>Rater's Comment</label>
		<?php } else { ?>
			<label>Ratee's Comment</label>
		<?php } ?>
		<textarea rows="5" class="form-control" readonly=""><?php echo $comment; ?></textarea>
	</div>
	<?php if($signOff == "raterSO"){ ?>

		<div class="form-group">
			<label>Rater's Name</label>
			<input type="text" class="form-control" readonly="" value="<?php echo $raterName; ?>">
		</div>
	<?php } ?>
<?php
}

else if($_GET['request'] == "viewDetails")
{	
	$did 			= $_POST['dId'];
	$query 			= mysql_query("SELECT * FROM appraisal_details WHERE details_id = '$did' ");
	$row 			= mysql_fetch_array($query);
	$ratercomment 	= $row['ratercomment'];
	$rateecomment 	= $row['rateecomment'];
	$raterSO		= $row['raterSO'];
	$rateeSO		= $row['rateeSO'];

	if($raterSO == 1){
		$rso = "<span class='label label-success'>yes</span>";		} 
	else if($raterSO == 0){ 
		$rso = "<span class='label label-warning'>no</span>";		}
	else{
		$rso = "";	}
	
	
	if($rateeSO == 1){ 
		$eso = "<span class='label label-success'>yes</span>"; 		} 
	else if($rateecomment == ""){ 
		$eso = ""; 	}
	else{
		$eso = "<span class='label label-warning'>no</span>"; 	}

	//GET ANSWERS
	$q = mysql_query("SELECT q_no,title, description, rate FROM `appraisal_answer` inner join appraisal on appraisal.appraisal_id = appraisal_answer.appraisal_id WHERE details_id = '$did' ");

	echo 
	"<div style='border:#ccc solid 1px;width:100%;margin-left:auto;margin-right:auto;background:white; padding: 10px'>
	
		<table class='table'>
			<tr><td><b>GUIDE</b></td><td><b>RATING</b></td></tr>";
			while($rans = mysql_fetch_array($q))
			{		
				echo "<tr><td>".$rans['q_no'].") ".$rans['title']."</td><td>".$rans['rate']."</tr>";
			}echo 
		"</table>	
		
		<hr>
		<div style='border:1px solid #ccc; padding:5px'>
			<label> RATER COMMENT  ( ".$nq->getEmpName($row['rater'])." ) </label>
			<p style='text-indent:20px'> <i> ~ $ratercomment </i> </p>	
			<p> Sign Off: $rso</p>	
		</div>		
		
		&nbsp;

		<div style='border:1px solid #ccc; padding:5px'>
			<label> RATEE COMMENT </label>
			<p style='text-indent:20px'> <i> ~ $rateecomment </i> </p>	
			<p> Sign Off: $eso </p>	
		</div>
	</div>";

}

else if($_GET['request'] == "reprintPermit"){
?>
	<input type="hidden" name="recordNo">
	<input type="hidden" name="empId">
	<input type="hidden" name="type" value="permit">

	<div class="form-group">
	    <div class="input-group">
	        <input type="text" name="app_id" onkeyup="nameSearch(this.value)" class="form-control textFocus" placeholder="Search Employee (Emp. ID, Lastname or Firstname)" value="" autocomplete="off">
	        <span class="input-group-btn">
	          <button class="btn btn-info" name="search">Search &nbsp;<i class="glyphicon glyphicon-search"></i></button>
	        </span>
	    </div>
	    <div class="search-results" style="display:none;"></div>
	</div>

<?php
}

else if($_GET['request'] == "reprintContract"){
?>
	<input type="hidden" name="recordNo">
	<input type="hidden" name="empId">
	<input type="hidden" name="type" value="contract">

	<div class="form-group">
	    <div class="input-group">
	        <input type="text" name="app_id" onkeyup="nameSearch(this.value)" class="form-control textFocus" placeholder="Search Employee (Emp. ID, Lastname or Firstname)" value="" autocomplete="off">
	        <span class="input-group-btn">
	          <button class="btn btn-info" name="search">Search &nbsp;<i class="glyphicon glyphicon-search"></i></button>
	        </span>
	    </div>
	    <div class="search-results" style="display:none;"></div>
	</div>
	<div class="contractForm"></div>
	
<?php
}

else if($_GET['request'] == "contractForm"){

	$empId 		= $_POST['empId'];
	$recordNo 	= $_POST['recordNo'];
	$sssnum   = $nq->getSSS($empId);
?>
	<hr>
	<div class="form-group">
		<div class="row">
			<div class="col-md-6">
				<label>Witness 1</label>
				<input type="text" class="form-control" name="witness1" id="witness1" placeholder="FIRSTNAME LASTNAME" style="text-transform:uppercase;" onkeyup="onkeyupWitness(this.id)">
			</div>
			<div class="col-md-6">
				<label>Witness 2</label>
				<input type="text" class="form-control" name="witness2" id="witness2" placeholder="FIRSTNAME LASTNAME" style="text-transform:uppercase;" onkeyup="onkeyupWitness(this.id)">
			</div>
		</div>
	</div>
    <div class="form-group">
    	<label>Please choose either to use Cedula (CTC No.) or SSS No.</label>
    	<div style="margin-left:50px;">	
    		<div class="form-group">
	        	<div class="row">
	        		<div class="col-md-4"><input type='radio' name='clear' id='r1' value='Cedula' onclick="sssctc('ctc')"><label>&nbsp;Cedula (CTC No.)</label></div>
	        		<div class="col-md-8"><input type='text' class="form-control" name='cleartf' id='cleartf' disabled="" placeholder="CCI____ ________" onkeyup="onkeyupWitness(this.id)"></div>
	        	</div>
    		</div> 
    		<div class="form-group">
	        	<div class="row">
	        		<div class="col-md-4"><input type='radio' name='clear' id='r2' value='SSS' onclick="sssctc('sss')"><label>&nbsp;SSS No.</label></div>
	        		<div class="col-md-8"><input type='text' class="form-control" name='ssstf' id='ssstf' value="<?php echo $sssnum;?>" disabled="" onkeyup="onkeyupWitness(this.id)"></div>
	        	</div>
	        </div>
    		<p id='is'><label> Issued on: </label><input type='text' class="form-control" name='issuedon' id='issuedon' size='50' placeholder="mm-dd-yyyy" autocomplete="off" onchange="onkeyupWitness(this.id)"></p>		    
        	<label> Issued at: </label><input type='text' class="form-control" name='issuedat' id='issuedat' size='50' onkeyup="onkeyupWitness(this.id)">
    	</div>
    </div>
    <div class="form-group">
    	<label>Date of Signing the Contract/Employee</label>
    	<input type='text' class="form-control" name='contractdate' id='contractdate' placeholder="mm-dd-yyyy" autocomplete="off" onchange="onkeyupWitness(this.id)">
    </div>
<script>
	
	$(function() {  //minDate: new Date(), minDate: new Date(),
		$( "#issuedon" ).datepicker({ dateFormat: "mm/dd/yy", changeMonth: true, changeYear: true, showButtonPanel: true });
		$( "#contractdate" ).datepicker({ dateFormat: "mm/dd/yy", changeMonth: true, changeYear: true, showButtonPanel: true });
	});

	$('#contractdate').click(function(){
	    var popup =$(this).offset();
	    var popupTop = popup.top - 80;
	    $('.ui-datepicker').css({
	      	'top' : popupTop,
	      	'position' : 'fixed',
	      	'top' : '127px',
	      	'left' : '398.5px',
	      	'display' : 'block',
	      	'z-index' : '99999'
	    });
	}); 

	$('#issuedon').click(function(){
	    var popup =$(this).offset();
	    var popupTop = popup.top - 80;
	    $('.ui-datepicker').css({
	      	'top' : popupTop,
	      	'position' : 'fixed',
	      	'top' : '285px',
	      	'left' : '448px',
	      	'display' : 'block',
	      	'z-index' : '99999'
	    });
	}); 
</script>
<?php
}

/* for regularization module */
else if($_GET['request'] == "isRegular"){

	$empType = $_POST['empType'];

	$isReg = strpos(strtolower($empType),'regular');

	if ($isReg !== false){ 
			
		echo "true";
	} else {

		echo "false";
	}
}

else if($_GET['request'] == "viewCasual"){

	$empId 		= $_POST['empId'];
	$recordNo 	= $_POST['recordNo'];
	$empType 	= $_POST['empType'];
?>
	<input type="hidden" name="empId" value="<?php echo $empId; ?>">
	<input type="hidden" name="recordNo" value="<?php echo $recordNo; ?>">
	<input type="hidden" name="empType" value="<?php echo $empType; ?>">
	<input type="hidden" name="regularization" value="forCasual">
	<div class="form-group">
	    <div class="row">
	    	<div class="col-md-3"><label><i> Employee Type </i></label></div>
	    	<div class="col-md-9">
	    		<input type="text" disabled="" class="form-control" value="<?php echo $empType; ?>">
	    	</div>
	    </div> 
	</div>
	<div class="form-group">
	    <div class="row">
	    	<div class="col-md-3"><label><i> Type of Regular </i></label></div>
	    	<div class="col-md-9">
	    		<select name="regularType" id="regularType" class="form-control" required="">
	    			<option value="Regular" <?php if($empType == "Contractual" || $empType == "Probationary") { echo "selected"; } ?> >Regular (AE)</option>
	    			<option value="NESCO Regular" <?php if($empType == "NESCO" || $empType == "NESCO Contractual" || $empType == "NESCO Probationary") { echo "selected"; } ?> >NESCO Regular</option>
	    			<option value="Regular Partimer" <?php if($empType == "Partimer" || $empType == "PTA" || $empType == "PTP") { echo "selected"; } ?> >Regular Partimer</option>
	    			<option value="NESCO Regular Partimer" <?php if($empType == "NESCO-PTP" || $empType == "NESCO-PTA") { echo "selected"; } ?> >NESCO Regular Partimer</option>
	    		</select>
	    	</div>
	    </div> 
	</div>
	<div class="form-group">
	    <div class="row">
	    	<div class="col-md-3"><label> Date Regularization </label></div>
	    	<div class="col-md-9">
	    		<input type='text' name="dateRegular" class="form-control" id='contractdate' placeholder = "mm/dd/yyyy" required="">
	    	</div>
	    </div> 
	</div>
	<div class="form-group">
	    <div class="row">
	    	<div class="col-md-3"><label><i> S I L </i></label></div>
	    	<div class="col-md-9">
	    		<select class='form-control' name='regclass' required="">
					<option value=''> -- Select -- </option>					
					<option value='RC1'> Regular 6mos and 1 day - 11 mos </option>
					<option value='RC2'> Regular 1-5yrs (5days SIL) </option>
					<option value='RC3'> Regular 6yrs and above (7days SIL) </option>
					<option value='RC4'> Regular with PRA </option>
				</select>
	    	</div>
	    </div> 
	</div>
 	<div class="form-group">
 		<div class="row">
 			<div class="col-md-3"><label><i> Signed Regularization Form </i></label></div>
 			<div class="col-md-9">
              	<input type="file" name="myfile" id='imgid' class="btn btn-default" onchange='uploadonchange(this.id)' required="">
              	<p class="help-block"> Allowed File : jpg, jpeg, png only </p>
            </div>
        </div>
  	</div>

  	<script>
  	
		$(function() {  //minDate: new Date(), minDate: new Date(),
			$( "#contractdate" ).datepicker({ dateFormat: "mm/dd/yy", changeMonth: true, changeYear: true, showButtonPanel: true });
		});
	</script>
<?php
}

else if($_GET['request'] == "viewRegular"){
	
	$empId = $_POST['empId'];
	$recordNo = $_POST['recordNo'];
	$empType = $_POST['empType'];
?>
	<input type="hidden" name="empId" value="<?php echo $empId; ?>">
	<input type="hidden" name="recordNo" value="<?php echo $recordNo; ?>">
	<input type="hidden" name="empType" value="<?php echo $empType; ?>">
	<input type="hidden" name="regularization" value="forRegular">
	<div class="form-group">
	    <div class="row">
	    	<div class="col-md-3"><label><i> From Employee Type </i></label></div>
	    	<div class="col-md-9">
	    		<input type="text" disabled="" class="form-control" value="<?php echo $empType; ?>" required="">
	    	</div>
	    </div> 
	</div>
	<div class="form-group">
	    <div class="row">
	    	<div class="col-md-3"><label><i> To Employee Type </i></label></div>
	    	<div class="col-md-9">
	    		<select name="regularType" class="form-control" required="" onchange = "ifRequired('<?php echo $empType; ?>',this.name)">
	    			<option value=""> -- Select -- </option>
	    			<?php
	    				if($empType == "Regular"){

	    					echo "<option value='Regular Partimer'>Regular Partimer</option>";
	    				}

	    				if($empType == "Regular Partimer"){

	    					echo "<option value='Regular'>Regular (AE)</option>";
	    				}

	    				if($empType == "NESCO Regular"){

	    					echo "
	    							<option value='Regular'>Regular (AE)</option>
	    							<option value='Regular Partimer'>Regular Partimer</option>
	    							<option value='NESCO Regular Partimer'>NESCO Regular Partimer</option>
	    						";
	    				}

	    				if($empType == "NESCO Regular Partimer"){

	    					echo "
	    							<option value='Regular'>Regular (AE)</option>
	    							<option value='Regular Partimer'>Regular Partimer</option>
	    							<option value='NESCO Regular Partimer'>NESCO Regular</option>
	    						";
	    				}
	    			 ?>
	    		</select>
	    	</div>
	    </div> 
	</div>
	<div class="form-group">
	    <div class="row">
	    	<div class="col-md-3"><label><i> Date Regularization </i></label></div>
	    	<div class="col-md-9">
	    		<input type='text' name="dateRegular" class="form-control" id='contractdate' placeholder = "mm/dd/yyyy" required="">
	    	</div>
	    </div> 
	</div>
	<div class="form-group">
	    <div class="row">
	    	<div class="col-md-3"><label><i> S I L </i></label></div>
	    	<div class="col-md-9">
	    		<?php 

	    			$query = mysql_query("SELECT `reg_class` FROM `employee3` WHERE `emp_id` = '$empId' and `record_no` = '$recordNo'") or die(mysql_error());
	    			$row = mysql_fetch_array($query);

	    			if($row['reg_class'] == "RC1"){ $regclass = "Regular 6mos and 1 day - 11 mos"; }
	    			if($row['reg_class'] == "RC2"){ $regclass = "Regular 1-5yrs (5days SIL)"; }
	    			if($row['reg_class'] == "RC3"){ $regclass = "Regular 6yrs and above (7days SIL)"; }
	    			if($row['reg_class'] == "RC4"){ $regclass = "Regular with PRA"; }
	    		?>			
				<input type="hidden" name="regclass" value="<?php echo $row['reg_class']; ?>">
				<input type="text" disabled="" class="form-control" value="<?php echo $regclass; ?>">
	    	</div>
	    </div> 
	</div>
 	<div class="form-group">
 		<div class="row">
 			<div class="col-md-3"><label><i> Signed Regularization Form </i></label></div>
 			<div class="col-md-9">
              	<input type="file" name="myfile" id='imgid' class="btn btn-default" onchange='uploadonchange(this.id)' required="">
              	<p class="help-block"> Allowed File : jpg, jpeg, png only </p>
            </div>
        </div>
  	</div>

  	<script>
  	
		$(function() {  //minDate: new Date(), minDate: new Date(),
			$( "#contractdate" ).datepicker({ dateFormat: "mm/dd/yy", changeMonth: true, changeYear: true, showButtonPanel: true });
		});
	</script>
<?php
}
else if($_GET['request'] == 'viewresig')
{
	$query = mysql_query("SELECT resignation_letter from termination where termination_no = '".$_POST['recno']."' ");	
	$r = mysql_fetch_array($query);
	$rl = $r['resignation_letter'];
	echo $rl;
}

else if($_GET['request'] == "getscannedcontract")
{
	$table 	= $_POST['table'];
	$record	= $_POST['record_no'];
	$query 	= mysql_query("SELECT contract FROM $table WHERE record_no = '".$record."'")or die(mysql_error());
	$r 		= mysql_fetch_array($query);
	$contract = $r['contract'];
	echo $contract;	
}
else if($_GET['request'] == "getscannedclearance")
{
	$table 	= $_POST['table'];
	$record	= $_POST['record_no'];
	$query 	= mysql_query("SELECT clearance FROM $table WHERE record_no = '".$record."'")or die(mysql_error());
	$r 		= mysql_fetch_array($query);
	$clearance = $r['clearance'];
	echo $clearance;	
}
else if($_GET['request'] == "getscannedepas")
{
	$table 	= $_POST['table'];
	$record	= $_POST['record_no'];
	$query 	= mysql_query("SELECT epas_code FROM $table WHERE record_no = '".$record."'")or die(mysql_error());
	$r 		= mysql_fetch_array($query);
	$epas_code = $r['epas_code'];
	echo $epas_code;	
}

else if($_GET['request'] == "getEmpPhoto"){

	$appId = $_POST['empId'];

	$query = mysql_query("SELECT `photo` FROM `applicant` WHERE `app_id` = '$appId'")or die();
		$row = mysql_fetch_array($query);

		$photo = $row['photo'];
	echo $photo;
}

else if($_GET['request'] == 'savepid')
{
	$empid 	= $_POST['id'];
	$pid 	= $_POST['pid'];
	
	$empname = $nq->getLastNameFirstName($empid);
	$salno = $nq->getOneField('payroll_no','employee3',"emp_id = '$empid' ");

	//SELECT pid, id from e3 where pid ='newpid'
	$query = mysql_query("SELECT payroll_no, emp_id from employee3 where payroll_no= '$pid' ");
	if(mysql_num_rows($query))
	{
		while($r = mysql_fetch_array($query))
		{
			if($r['emp_id'] == $empid){ 
				echo "Salary Number is already saved!";
			}else{ 
				echo "Salary Number is already taken!"; 
				//save back up and paste again to textfield
			}
		}
	}
	else{
		$query1 = mysql_query("UPDATE employee3 set payroll_no = '$pid' where emp_id = '$empid' ");	
		if($query1){
			$activity = "Updated PayrollNO of $empname from $salno to $pid ";
			mysql_query("INSERT into logs VALUES('','$activity','$date','$time','".$_SESSION['emp_id']."','".$_SESSION['username']."')")or die(mysql_error());
			echo '1'; 
		} 
	}	
}
else if($_GET['request'] =='updatejobtrans'){
	$transno = $_POST['transno'];
	$select  = mysql_query("SELECT * FROM employee_transfer_details WHERE transfer_no = '$transno' ");
	while($r=mysql_fetch_array($select))
	{		
		$new_loc= $r['new_location'];
		$pos 	= $r['position'];		
		$rec 	= $r['record_no'];
		$empid	= $r['emp_id'];
		$name 	= $nq->getEmpName($empid);
		$ec	 	= explode("-",$new_loc);
		$cc	   	= @$ec[0];
		$bc		= @$ec[1];
		$dc		= @$ec[2];
		$sc		= @$ec[3];
		$ssc	= @$ec[4];
		$uc		= @$ec[5];
		
		//updates now the employee3 based sa wala pa ma process nga job transfer	
		$update_employee3 = mysql_query("UPDATE employee3 SET 
			company_code = '".$cc."', 
			bunit_code = '".$bc."',
			dept_code = '".$dc."',
			section_code = '".$sc."',
			sub_section_code = '".$ssc."',
			unit_code = '".$uc."',
			position = '$pos'
		WHERE record_no = '$rec' AND emp_id = '$empid' ");			

		$date = date("Y-m-d");		
		$update = mysql_query("UPDATE employee_transfer_details SET process = 'yes'  WHERE transfer_no = '".$r['transfer_no']."' "); //and effectiveon = '".$date."'
		//echo "UPDATE employee_transfer_details SET process = 'yes' WHERE transfer_no = '".$r['transfer_no']."' and effectiveon = '".$date."'";
		if($update){
			echo "1";	
		}
		
	$time = date("H:i:s");
	$nq->savelogs("Process JobTrans effectivity of ".$name."/transno=".$r['transfer_no'],$date,$time,$_SESSION['emp_id'],$_SESSION['emp_id']);	
	}	
}

else if($_GET['request'] == "getEmpJobTransFile"){

	$transNo 	= $_POST['transNo'];
	$filename 	= mysql_query("SELECT * FROM employee_transfer_details WHERE transfer_no = '$transNo'")or die(mysql_error());
	$r 		= mysql_fetch_array($filename);
	$file 	= $r['file'];

	echo $file;
}

else if($_GET['request'] == "addEmployee"){

	$invalidID = array("04517-2015","03442-2015","18217-2013","04819-2015","02951-2016","01653-2013","00556-2017","00677-2017","06359-2013");
						
		$app_d = explode("*",$_POST['appname']);
		$app_id = mysql_real_escape_string($app_d[0]);	//emp_id
		$app_name = mysql_real_escape_string($app_d[1]); //emp_name
		$emp_type = mysql_real_escape_string($_POST['emp_type']);
		$pos = mysql_real_escape_string($_POST['position']);
		
		$sQl = mysql_query(
				"SELECT
					emp_id,
					name
				 FROM
					employee3
				 WHERE
					emp_id = '".$app_id."'"
				) or die(mysql_error());
		if(mysql_num_rows($sQl) > 0){
			die("Ok*0");
		} else {
			function getLastNo(){
				$sql = mysql_query(
					  "SELECT `emp_no`,`emp_pins`
					   FROM `employee3` WHERE
					   `company_code` != '07' ORDER BY `emp_no` DESC LIMIT 1"
					) or die(mysql_error());
				$res = mysql_fetch_assoc($sql);
				return $res;
			}
			function getLastPin($pin){
				$sql = mysql_query(
					  "SELECT `pin_id`
					   FROM `employee_pins`
					   WHERE `pins` = '".$pin."'"
					) or die(mysql_error());
				$res = mysql_fetch_assoc($sql);
				return $res['pin_id'];
			}
			function newEmpPin($pid){
				$sql = mysql_query(
					  "SELECT `pins`
					   FROM `employee_pins`
					   WHERE `pin_id` = '".$pid."'"
					) or die(mysql_error());
				$res = mysql_fetch_assoc($sql);
				return $res['pins'];
			}
			$securityCheck = 0;
			for($x=0;$x<count($invalidID);$x++){
				if($invalidID[$x] == $_SESSION['emp_id']){
					$securityCheck = 1;
				}
			}
			if($securityCheck == 0 || $_POST['c_stat'] == "Active") {
				$newEmpNo = getLastNo()['emp_no'] + 1;
				$lastEmpPin = getLastPin(getLastNo()['emp_pins']) + 1;
				$newEmpPin = newEmpPin($lastEmpPin);
				$c_status = "Active";
			}
			else {
				$newEmpNo = "";
				$newEmpPin = "";
				$c_status = $_POST['c_stat'];
			}
			$q = mysql_query(
					"INSERT
						INTO
					 employee3
						(
							emp_id,
							name,
							emp_type,
							position,
							current_status,
							date_added,
							added_by,
							barcodeId
						)
						values(
							'".$app_id."',
							'".$app_name."',
							'".$emp_type."',
							'".$pos."',
							'".$c_status."',
							'".date('Y-m-d')."',
							'".$_SESSION['emp_id']."',
							'00000000'
						)"
				 ) or die(mysql_error());

			$date = date("Y-m-d");
			$time = date('H:i:s');		
			$nq->savelogs("Add new employee [$app_id][$app_name] ",$date,$time,$_SESSION['emp_id'],$_SESSION['username']);			 
	 
			die("Ok*1");
		}
}

else if ($_GET['request'] == "showInbox") {

	$query = mysql_query("SELECT messages.msg_id, subject, msg, sender, cc, datesent, msg_stat FROM `messages`, `message_details`
							WHERE `messages`.`msg_id` = `message_details`.`msg_id`
							AND `cc` = '$_SESSION[emp_id]'
							AND (sender_delete != '$_SESSION[emp_id]' AND receiver_delete != '$_SESSION[emp_id]')
							GROUP BY `sender`
							ORDER BY  msg_id DESC") or die(mysql_error());
?>
	<div class="row">
		<div class="col-md-12">
			<h4><i class="glyphicon glyphicon-envelope"></i> Inbox</h4>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-12 size-div">
			<table class="table table hover" id="messageInbox">
				<thead>
					<tr>
						<th>Photo</th>
						<th>Name</th>
						<th>Subject</th>
						<th>Date</th>
						<th>Time</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php

						$convoId = 0;
						while ($sql = mysql_fetch_array($query)) { 

							$convoId++;
							$msg_id   = $sql['msg_id'];
							$msg 	  = $sql['msg'];
							$sender   = $sql['sender'];
							$cc   	  = $sql['cc'];
							$msg_stat = $sql['msg_stat'];

							$photo = $nq->getPhoto($sender);
							$name  = ucwords(strtolower($nq->getApplicantName($sender)));

							$query2 = mysql_query("SELECT * FROM `messages` , `message_details` WHERE `messages`.`msg_id` = `message_details`.`msg_id`
										AND (`cc` = '$cc' AND `sender` = '$sender') AND `msg_stat` = '0'
										AND (sender_delete != '$_SESSION[emp_id]' AND receiver_delete != '$_SESSION[emp_id]')
										GROUP BY `message_details`.`msg_id`") or die(mysql_error());
							if(mysql_num_rows($query2) > 0){
								$highlyt = "font-weight:bold;";
								$img = "<img src='images/closeMsg.png' height='15' weight='15'>";
								$msgCount = "<span class='badge' style='background-color:#C80A0A;color:white';> ".mysql_num_rows($query2)." </span>";
								$deleteImg = "";
							} else{
								$highlyt = "";
								$img = "<img src='images/openMsg.png' height='20' weight='20'>";
								$msgCount = "<span></span>";
								$deleteImg = "<a href='javascript:void' onclick='deleteAllImg(\"$sender\",\"$cc\",\"$convoId\")'><img src='images/images.jpg' height='20' weight='20'></a>";
							}

							$datesentQ = mysql_query("SELECT subject, datesent FROM `messages` , `message_details` WHERE `messages`.`msg_id` = `message_details`.`msg_id`
											AND (`cc` = '$cc' AND `sender` = '$sender')
											ORDER BY datesent DESC")or die(mysql_error());
							$fetch = mysql_fetch_array($datesentQ);

							$subject  = $fetch['subject'];
							$sentdate = $fetch['datesent'];
							$datesent = date("M d, Y",strtotime($fetch['datesent']));
							$timesent = date("h:i A",strtotime($fetch['datesent']));

							echo "<tr style='$highlyt' id='convoId_$convoId'>
									<td><span style='display:none;'>$sentdate</span><img src='$photo' class='img-circle' height='40' weight='40'> <sup>$msgCount</sup></td>
									<td>$name</td>
									<td>".substr($subject,0,12)."...</td>
									<td>$datesent</td>
									<td>$timesent</td>
									<td align='center'><a href='javascript:void' onclick=viewMsg(\"$sender\",\"$cc\")>$img</a> &nbsp;$deleteImg</td>
								 </tr>";
						}
					?>
				</tbody>
			</table>
			<link href='../datatables/jquery.dataTables.css' rel='stylesheet'/> 
			<script src="../datatables/jquery.dataTables.min.js" type="text/javascript"></script>
			<script>

				$('#messageInbox').DataTable({
					paging : false,
					"order": [[ 0, 'desc']]
				});
			</script>
		</div>
	</div>
<?php 
}
else if ($_GET['request'] == "newMessage") {
	
?>
	<div class="row">
		<div class="col-md-12">
			<h4> <i class="fa fa-pencil"></i> New Message</h4>
		</div>
	</div>	
	<div class="row">
		<form action='?p=message' method="post" enctype="multipart/form-data" >
		<div class="col-md-12" id='createmsg'>
			
				<input type='hidden' name='sender' value="<?php echo $_SESSION['emp_id'];?>" >
				<p><input type='text' required id='receiver' name='receiver' placeholder='Send to' onclick='sendmessageto()' data-toggle='modal' data-target='#createmsgmodal' class="form-control"></p>
				<p><input type='text' required id='subject' name='subject' placeholder='Subject' class="form-control"></p>
				<textarea class="form-control" required id='msg' rows='10' style='resize:none' name='msg' placeholder='Type your message here...'></textarea></p>
				<p>Attachment/s (<i>not required</i>)<br><input type='file' id='attachment' name='attachment[]' multiple class="btn btn-default"></p>
				<p><button id='sendmsg' name='submit' class='btn btn-primary' >
				Send <i class='fa fa-arrow-circle-right'></i></button></p>
			
		</div>
		</form>
	</div>
<?php 
}
else if($_GET['request'] == "getccsents")
{
	$ids = explode(",",@$_POST['id']);
	$query = mysql_query("SELECT distinct(users.emp_id),name FROM `users` inner join employee3 on users.emp_id = employee3.emp_id WHERE (usergroup = 'placement' OR usertype = 'nesco') and user_status ='active' order by name");
	echo 
	"<div class='row'>
	  <div class='col-md-8'>		  
		  <input type='text' id='searchcc' class='form-control' placeholder='search'/>
	  </div>
	  <div class='col-md-4' align='right'>
		
		<button type='button' class='btn btn-primary' data-dismiss='modal' aria-hidden='true' onclick='savecc()'>Save CC </button>
		</div>
	</div>
	<br>
	<div style='height:450px;overflow-y:scroll'>
	<table class='table table-striped' id='cctable' >
		<tr>
			<th><input type='checkbox' id='chkAllcc' /></th>
			<th>HRMS ID</th>
			<th>NAMES</th>			          				
		</tr>";		
		while($r= mysql_fetch_array($query))
		{
		?>
		<tr>
			<td><input name='cc[]' <?php for($x=0;$x<sizeof($ids)-1;$x++):if($r['emp_id'] == @$ids[$x]):echo "checked";endif;endfor;?> type='checkbox' class='chk' value='<?php echo $r['emp_id']?>' /></td>
			<td><?php echo $r['emp_id']?></td>
			<td id='n_<?php echo $r['emp_id']?>'><?php echo $r['name'] ?></td>			
		</tr><?php
		} echo "
	</table>
	</div>";
	?>
	<script>
	$("#chkAllcc").click(function(){  $(".chk").prop("checked",$("#chkAllcc").prop("checked"))  });
	$('#searchcc').keyup(function(){  searchTables($(this).val());  });
	function searchTables(inputVal)
	{
		var table = $('#cctable');
		table.find('tr').each(function(index, row){
			var allCells = $(row).find('td');
			if(allCells.length > 1){
				var found = false;
				allCells.each(function(index, td){
					var regExp = new RegExp(inputVal, 'i');
					if(regExp.test($(td).text())){
						found = true;					
						return false;
					}
				});
				if(found == true){	$(row).show();	}	else{ $(row).hide(); }
			}
		});
	}
	</script>
	<?php
}
else if($_GET['request'] == "viewMessage"){

	$cc = $_POST['cc'];

	$query = mysql_query("SELECT * FROM `messages`, `message_details`
							WHERE `messages`.`msg_id` = `message_details`.`msg_id`
							AND ((`sender` = '$cc' OR `cc` = '$cc') AND (`cc` = '$_SESSION[emp_id]' OR `sender` = '$_SESSION[emp_id]'))
							AND (sender_delete != '$_SESSION[emp_id]' AND receiver_delete != '$_SESSION[emp_id]')
							ORDER BY `datesent` DESC")or die(mysql_error());
	while ($sql = mysql_fetch_array($query)) {
		
		$msg_id = $sql['msg_id'];
		$sender = $sql['sender'];
		$subject = $sql['subject'];
		$msg 	= $sql['msg'];
		$msg_stat 	= $sql['msg_stat'];
		$datesent 	= $sql['datesent'];
		$date = date("M d, Y", strtotime($datesent));
		$time = date("g:i", strtotime($datesent));

		// echo "$sender- $cc";
		if($sender == $cc){	

			$attach = mysql_query("SELECT `attachments` FROM `message_attachments` WHERE `msg_id` = '$msg_id'")or die(mysql_error());
			$attachNo = mysql_num_rows($attach);
		?>
			<div class="form-group deleteMsg_<?php echo $msg_id; ?>">
				<label class="pull-right">&nbsp;<li class="fa fa-trash-o" style='color:red; cursor:pointer; cursor:hand;' onclick="deleteMsg('<?php echo $msg_id; ?>','receiver')"></li>
					<?php if($msg_stat == 0){ ?> &nbsp;<li class="fa fa-eye" style='color:red; cursor:pointer; cursor:hand;' title="Mark as Read" onclick="read('<?php echo $msg_id; ?>','<?php echo $_SESSION['emp_id']; ?>')"></li> &nbsp; <?php } else { ?> &nbsp;<li class="fa  fa-check-circle-o" style='color:blue; cursor:pointer; cursor:hand;' title="Read" onclick="read()"> Read</li> &nbsp;<?php } ?>
					<?php echo "$date at $time"; ?>
				<br>
				<p><?php echo $subject; ?></p>
				</label>
				<br>
				<div class="row">
					<div class="col-md-2" style="margin-top:5px; padding-top:30px;">
						<img src="<?php echo $nq->getPhoto($sender); ?>" class="img-circle" height="50" width="50">
					</div>
					<div class="col-md-10 pull-right">
						<div class="form-group">
							<div class="message">
								<?php echo nl2br($msg); ?>
							</div>
							<?php 
								if($attachNo > 0) { 
								while ($file = mysql_fetch_array($attach)) {
								
									$f = explode("../document/attachments/", $file['attachments']);
									$attachments = end($f);
									// $attachments = $file['attachments'];	
							?>
							<div class="attachment-sender">
								<div class="row">
									<div class="col-md-12">
                                        <p class="filename">
                                        	<?php echo $attachments; ?>
                                        </p>
                                        <div class="pull-right">
                                             <a href="download_attachments.php?download=<?php echo $file['attachments']; ?>"><button class="btn btn-primary btn-sm btn-flat">Open</button></a>
                                        </div>
									</div>
								</div>        
                            </div>
                            <?php }  } ?>
						</div>
					</div>
				</div>
			</div><br>
		<?php
		} else {

			$attach = mysql_query("SELECT `attachments` FROM `message_attachments` WHERE `msg_id` = '$msg_id'")or die(mysql_error());
			$attachNo = mysql_num_rows($attach);
		?>
			<div class="form-group deleteMsg_<?php echo $msg_id; ?>">
				<label class=""><?php echo "$date at $time"; ?> &nbsp;<li class="fa fa-trash-o" style='color:red; cursor:pointer; cursor:hand;' title="Delete Message" onclick="deleteMsg('<?php echo $msg_id; ?>','sender')"></li>
					<?php if($msg_stat == 1){ ?> &nbsp;<li class="fa  fa-check-circle-o" style='color:blue; cursor:pointer; cursor:hand;' title="Seen" onclick="read()"> Seen</li> &nbsp;<?php } ?>
					
					<br>
					<p><?php echo $subject; ?></p>
				</label>
				<div class="row">
					<div class="col-md-10">
						<div class="form-group">
							<div class="message">
								<?php echo nl2br($msg); ?>
							</div>
							<?php if($attachNo > 0) { 

								while ($file = mysql_fetch_array($attach)) {
								
									$f = explode("../document/attachments/", $file['attachments']);
									$attachments = end($f);
									// $attachments = $file['attachments'];	
							?>
									<div class="attachment-cc">
										<div class="row">
											<div class="col-md-12">
		                                        <p class="filename">
		                                            <?php echo $attachments; ?>
		                                        </p>
		                                        <div class="pull-right">
		                                            <a href="download_attachments.php?download=<?php echo $file['attachments']; ?>"><button class="btn btn-primary btn-sm btn-flat">Open</button></a>
		                                        </div>
											</div>
										</div>        
		                            </div>
                            <?php } } ?>
						</div>
					</div>
					<div class="col-md-2">
						<img src="<?php echo $nq->getPhoto($sender); ?>" class="img-circle" height="50" width="50">
					</div>
				</div>
			</div><br>
		<?php
		}
	}
}
else if($_GET['request'] == "deleteMessage"){

	$msgId = $_POST['msgId'];
	$details = $_POST['details'];

	if($details == "sender"){

		$field = "sender_delete = '$_SESSION[emp_id]'";
	} else {
		
		$field = "receiver_delete = '$_SESSION[emp_id]'";
	}

	$delmsg = mysql_query("UPDATE messages SET stat = 'deleted', $field WHERE msg_id = '$msgId'")or die(mysql_error());

	/*$query = mysql_query("SELECT attachments FROM message_attachments WHERE `msg_id` = '$msgId'")or die(mysql_error());
	while ($sql = mysql_fetch_array($query)) {
		
		unlink($sql['attachments']);
	}

	$delete = mysql_query("DELETE FROM `message_attachments` WHERE `msg_id` = '$msgId'")or die(mysql_error());*/
	if($delmsg){

		die("Ok");
	}
}
else if($_GET['request'] == "replyMessage"){

	$datesent	= date("Y-m-d H:i:s");
	// MESSAGES
	$query 		= mysql_query("INSERT INTO messages (msg_id,subject,msg,sender,datesent) 
	VALUES ('','Message Reply','".addslashes($_POST['reply'])."','".addslashes(trim($_POST['sender']))."','$datesent')");
	
	// GETTING THE MESSAGE ID
	$getmsgid 	= mysql_query("SELECT max(msg_id) from MESSAGES");
	$rm 	  	= mysql_fetch_array($getmsgid);
	$msgid 	  	= $rm['max(msg_id)'];

	// MESSAGE DETAILS
	$msgquery 	= mysql_query("INSERT INTO MESSAGE_DETAILS (msgdet_id,msg_id,cc,msg_stat,dateread) VALUES ('','$msgid','".addslashes(trim($_POST['cc']))."','0','') ") or die(mysql_error()); 

	if ($query && $msgquery) {
		
		die("Ok");
	}
}
else if($_GET['request'] == "readMessage"){

	$msgId = $_POST['msgId'];
	$cc = $_POST['cc'];
	$datetime = date("Y-m-d H:i:s");
	$dateRead = mysql_query("UPDATE `message_details` SET `msg_stat`='1', `dateread`='$datetime' WHERE `msg_id` = '$msgId' AND `cc` = '$cc'") or die(mysql_error());

	if($dateRead){

		die("Ok");
	}
}

else if($_GET['request'] == "showSentMessage"){

	$query = mysql_query("SELECT messages.msg_id, msg, subject, sender, cc, datesent, msg_stat FROM `messages`, `message_details`
							WHERE `messages`.`msg_id` = `message_details`.`msg_id`
							AND `sender` = '$_SESSION[emp_id]'
							AND sender_delete = 0
							ORDER BY  datesent DESC") or die(mysql_error());
	?>
		<div class="row">
			<div class="col-md-12">
				<h4><i class="fa fa-folder-open-o"></i> Sent Message</h4>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-md-12 size-div">
				<table class="table table hover" id="sentMessage">
					<thead>
						<tr>
							<th>CC</th>
							<th>Subject</th>	
							<th>Message</th>
							<th>Date Sent</th>
							<th>Time Sent</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php

							while ($sql = mysql_fetch_array($query)) { 

								$msg_id   = $sql['msg_id'];
								$sender   = $sql['sender'];
								$cc   	  = $sql['cc'];
								$msg_stat = $sql['msg_stat'];
								$msg 	  = $sql['msg'];
								$name  	  = ucwords(strtolower($nq->getApplicantName($cc)));
								$subject  = $sql['subject'];
								$sentdate = $sql['datesent'];
								$datesent = date("M d, Y",strtotime($sql['datesent']));
								$timesent = date("h:i A",strtotime($sql['datesent']));

								echo "<tr id='deleteSentItem_$msg_id'>
										<td><span style='display:none;'>$sentdate</span>$name</td>
										<td>".substr($subject,0,12)."...</td>
										<td>".substr($msg,0,12)."...</td>
										<td align='center'>$datesent</td>
										<td align='center'>$timesent</td>
										<td align='center'>
											<span class='glyphicon glyphicon-search' style='color:blue; cursor:pointer; cursor:hand;' onclick='viewSentItem(\"$msg_id\")'></span>
											<span class='glyphicon glyphicon-trash' style='color:red; cursor:pointer; cursor:hand;' onclick='deleteSentItem(\"$msg_id\")'></span>
										</td>
									 </tr>";
							}
						?>
					</tbody>
				</table>
				<link href='../datatables/jquery.dataTables.css' rel='stylesheet'/> 
				<script src="../datatables/jquery.dataTables.min.js" type="text/javascript"></script>
				<script>

					$('#sentMessage').DataTable({
						// paging : false,
						"order": [[ 0, 'desc']]
					});
				</script>
			</div>
		</div>
	<?php
}
else if($_GET['request'] == "viewsentMessage"){

	$msg_id = $_POST['msgId'];

	$query = mysql_query("SELECT * FROM messages, message_details
							WHERE `messages`.`msg_id` = `message_details`.`msg_id`
							AND sender = '$_SESSION[emp_id]' AND messages.msg_id = '$msg_id' AND sender_delete = 0") or die(mysql_error());
	$sql = mysql_fetch_array($query);

	$sender = $sql['sender'];
	$subject = $sql['subject'];
	$msg 	= $sql['msg'];
	$msg_stat 	= $sql['msg_stat'];
	$dateread 	= $sql['dateread'];
	$date = date("M d, Y", strtotime($dateread));
	$time = date("g:i", strtotime($dateread));

	$attach = mysql_query("SELECT `attachments` FROM `message_attachments` WHERE `msg_id` = '$msg_id'")or die(mysql_error());
	$attachNo = mysql_num_rows($attach);
	?>
		<div class="form-group">
				<label class=""><?php if($dateread != "0000-00-00 00:00:00"){ echo "$date at $time"; } ?> <?php if($msg_stat == 1){ ?>&nbsp;<li class="fa  fa-check-circle-o" style='color:blue; cursor:pointer; cursor:hand;' title="Seen" onclick="read()"> Seen</li> &nbsp; <?php } ?>
					<br>
					<p><?php echo $subject; ?></p>
				</label>
				<div class="row">
					<div class="col-md-10">
						<div class="form-group">
							<div class="message">
								<?php echo nl2br($msg); ?>
							</div>
							<?php if($attachNo > 0) { 

								while ($file = mysql_fetch_array($attach)) {
								
									$f = explode("../document/attachments/", $file['attachments']);
									$attachments = end($f);
							?>
									<div class="attachment-cc">
										<div class="row">
											<div class="col-md-12">
		                                        <p class="filename">
		                                            <?php echo $attachments; ?>
		                                        </p>
		                                        <div class="pull-right">
		                                            <a href="download_attachments.php?download=<?php echo $file['attachments']; ?>"><button class="btn btn-primary btn-sm btn-flat">Open</button></a>
		                                        </div>
											</div>
										</div>        
		                            </div>
                            <?php } } ?>
						</div>
					</div>
					<div class="col-md-2">
						<img src="<?php echo $nq->getPhoto($sender); ?>" class="img-circle" height="50" width="50">
					</div>
				</div>
			</div>

	<?php
}

else if($_GET['request'] == "deleteAllMessage"){

	$sender = $_POST['sender'];
	$cc 	= $_POST['cc'];

	$delSender = mysql_query("SELECT messages.msg_id FROM `messages`, `message_details` WHERE `messages`.`msg_id` = `message_details`.`msg_id`
								AND `cc` = '$cc' AND `sender` = '$sender'
								AND (sender_delete != '$cc' AND receiver_delete != '$cc')") or die(mysql_error());
		while ($dels = mysql_fetch_array($delSender)) {
			
			$msgId = $dels['msg_id'];

			$updSender = mysql_query("UPDATE `messages` SET `stat`='deleted', `receiver_delete`='$cc' WHERE `msg_id` = '$msgId'")or die(mysql_error());
		}

	$delCc = mysql_query("SELECT messages.msg_id FROM `messages`, `message_details` WHERE `messages`.`msg_id` = `message_details`.`msg_id`
								AND `cc` = '$sender' AND `sender` = '$cc'
								AND (sender_delete != '$cc' AND receiver_delete != '$cc')") or die(mysql_error());
		while ($dels = mysql_fetch_array($delSender)) {
			
			$msgId = $dels['msg_id'];

			$updCc = mysql_query("UPDATE `messages` SET `stat`='deleted', `sender_delete`='$cc' WHERE `msg_id` = '$msgId'")or die(mysql_error());
		}

	if($updSender || $updCc){

		die("Ok");
	}
}

// newly added by natsu :)
else if($_GET['request'] == "findSup"){

  	$key = mysql_real_escape_string($_POST['str']);
	$val = "";
	$empname = mysql_query("SELECT `users`.`emp_id`,`employee3`.`name` FROM `users` INNER JOIN `employee3` ON `users`.`emp_id` = `employee3`.`emp_id`
							   	WHERE `users`.`usertype` = 'supervisor' 
							   	AND (name like '%$key%' or employee3.emp_id = '$key') order by name limit 10")or die(mysql_error());
	if(mysql_num_rows($empname) > 0){
		
		while($n = mysql_fetch_array($empname)){
			$empId = $n['emp_id'];
			$name  = $n['name'];

			if($val != $empId){
				echo "<a class = \"nameFind\" href = \"javascript:void\" onclick='getEmpId(\"$empId * $name\")'>".$empId." * ".$name."</a></br>";
			}
			else{
				echo "<a class = \"afont\" href = \"javascript:void\">No Result Found</a></br>";	
			}
		}
	} else {

		echo "<a class = \"afont\" href = \"javascript:void\">No Result Found</a></br>";	
	}
}

else if($_GET['request'] == "load_subordinates"){

	$id = $_POST['supId'];

	$sql = mysql_query("SELECT `employee3`.`name`,`employee3`.`emp_id`,`employee3`.`position`,`employee3`.`emp_type`
						   FROM `leveling_subordinates` INNER JOIN `employee3` ON
						   `leveling_subordinates`.`subordinates_rater` = `employee3`.`emp_id`
						   WHERE `leveling_subordinates`.`ratee` = '".$id."'
						   AND `employee3`.`current_status` = 'Active' and ( epas_code='0' or epas_code='') ORDER BY name ASC") or die(mysql_error());

	function checkStat($id,$stat,$rid){

		$sql1 = mysql_fetch_array(
				mysql_query(
					"SELECT `tag_stat`
					 FROM `tag_for_resignation`
					 WHERE `ratee_id` = '".$id."'
					 AND `tag_stat` = '".$stat."'
					 AND `rater_id` = '".$rid."'"
				)
			);
		return $sql1['tag_stat'];
	}
?>
	    <input type='text' id='searches' class='form-control' placeholder="search name here..." style='width:40%;'>
		<br>

		<div class="size-emp">
			<table width="90%" class="table table-hover" align="center" id='data'>
			  	<?php while($res=mysql_fetch_array($sql)){?>
			  	<tr class="<?php if(checkStat($res['emp_id'],'Pending',$id[0])):echo"info";elseif(checkStat($res['emp_id'],'Done',$id[0])):echo"success";endif;?>">
					<td><?php echo $res['emp_id'];?></td>
					<td><?php echo $res['name'];?></td>
					<td><?php echo $res['emp_type'];?></td>
					<td><?php echo $res['position'];?></td>
			    	<td>
				  	<?php 
						  if(checkStat($res['emp_id'],'Pending',$id)): ?>
							<a class="text-danger" data-toggle="tooltip" data-placement="top" title="Untag for Resignation" href="javascript:void(0)" onclick="unTagForReg('<?php echo $res['emp_id'];?>','<?php echo $id;?>');">
						      <i class="glyphicon glyphicon-remove"></i>
						    </a>
						  <?php elseif(checkStat($res['emp_id'],'Done',$id)): ?>
							 <i data-toggle="tooltip" data-placement="top" title="EPAS done" class="glyphicon glyphicon-ok"></i>
						  <?php else: ?>
						  <a data-toggle="tooltip" data-placement="top" title="Click to Tag for Resignation" href="javascript:void(0)" onclick="tagForReg('<?php echo $res['emp_id'];?>','<?php echo $id;?>');">
						    <i class="glyphicon glyphicon-tag"></i>
						  </a>
						  <?php endif;?>
					</td>
			  	</tr>
			  <?php } ?>
			</table>
		</div>
		<script>
		  $('[data-toggle="tooltip"]').tooltip();
		  $(document).ready(function()
		  {
			$('#searches').keyup(function(){		
				searchTable($(this).val());
			});
		  });

		  function searchTable(inputVal){
			var table = $('#data');
			table.find('tr').each(function(index, row){
				var allCells = $(row).find('td');
				if(allCells.length > 1){
					var found = false;
					allCells.each(function(index, td){
						var regExp = new RegExp(inputVal, 'i');
						if(regExp.test($(td).text())){
							found = true;					
							return false;
						}
					});
					if(found == true){	$(row).show();	}	else{ $(row).hide(); }
				}
			});
		  }
		</script>
<?php
}

elseif($_GET['request'] == "tag_for_resignation"){

	$addedBy = $_SESSION['emp_id'];
	$ratee = mysql_real_escape_string($_POST['id']);
	$rater = mysql_real_escape_string($_POST['ids']);
	$date = date("Y-m-d");
	$status = "Pending";
	
	mysql_query(
		"INSERT INTO `tag_for_resignation`
		 (
			`ratee_id`,
			`rater_id`,
			`added_by`,
			`date_added`,
			`tag_stat`
		 ) VALUES (
			'".$ratee."',
			'".$rater."',
			'".$addedBy."',
			'".$date."',
			'".$status."'
		 )"
	) or die(mysql_error());
	die("Ok");
}

elseif($_GET['request'] == "untag_for_resignation"){

	$addedBy = $_SESSION['emp_id'];
	$ratee = mysql_real_escape_string($_POST['id']);
	$rater = mysql_real_escape_string($_POST['ids']);
	$status = "Pending";

	mysql_query(
		"DELETE FROM `tag_for_resignation`
		 WHERE `ratee_id` = '".$ratee."'
		 AND `rater_id` = '".$rater."'
		 AND `added_by` = '".$addedBy."'
		 "
	) or die(mysql_error());
	die("Ok");
}

elseif($_GET['request'] == "checkReq"){

	$appId = $_POST['appId'];
	$appCode = $_POST['appCode'];

	$query = mysql_query("SELECT `lastname`, `firstname`, `middlename`, `suffix`, `birthdate`, `attainment`, `course`, `civilstatus` FROM `applicant` WHERE `app_id` = '$appId'")or die(mysql_error());
	$row = mysql_fetch_array($query);

	date_default_timezone_set('Asia/Manila');
    function getAge( $dob, $tdate )
    {
        $age = 0;
        while( $tdate >= $dob = strtotime('+1 year', $dob)){
                ++$age;
        }return $age;
    }
    $datebirth = $row['birthdate'];/*** a date before 1970 ***/		
	if($row['birthdate'] == '0000-00-00' || $row['birthdate'] == '1900-01-01' || $row['birthdate'] == '')
	{
		$age = '';
		$edad = '';
		$msgbd = '';
	}
	else
	{
		$dob = strtotime($datebirth);		
		$now = date('Y-m-d');/*** another date ***/		
		$tdate = strtotime($now);/*** show the date ***/		
		$edad= getAge( $dob, $tdate );

		if($datebirth !=""){ $age =  $edad.' years old'; } 
	}

	function getApplicantPosition($appId){

		$query = mysql_query("SELECT `position` FROM `applicants`,`applicant` WHERE applicants.app_code = applicant.appcode AND `app_id` = '$appId'") or die(mysql_error());
		$fetch = mysql_fetch_array($query);
		return $fetch['position'];
	}

	$suffix = $row['suffix'];
	$mname = $row['middlename'];
	if($suffix !=""){ $suffix = " $suffix,"; } else { $suffix=""; }
	if($mname!="") {$mname = " $mname"; } else { $mname = ""; }

	$name = $row['lastname'].", ".$row['firstname']."".$suffix."".$mname;
	$position = getApplicantPosition($appId);
?>
	
	<div class="row">
		<input type="hidden" name="appId" value="<?php echo $appId; ?>">
		<input type="hidden" name="appCode" value="<?php echo $appCode; ?>">
		<input type="hidden" name="position" value="<?php echo $position; ?>">
		<div class="col-md-3">
			
			<div class="panel panel-default">
				<div class="panel-heading">
					Applicant Details
				</div>
				<div class="panel-body">
					<div class="form-group">
						<label>Applicant</label>
						<input type="text" name="" class="form-control" readonly="" value="<?php echo utf8_decode(ucwords(strtolower($name))); ?>">
					</div>
					<div class="form-group">
						<label>Applying For</label>
						<input type="text" name="" class="form-control" readonly="" value="<?php echo ucwords(strtolower($position)); ?>">
					</div>
					<div class="form-group">
						<label>Attainment</label>
						<input type="text" name="" class="form-control" readonly="" value="<?php echo $row['attainment']; ?>">
					</div>
					<div class="form-group">
						<label>Course</label>
						<input type="text" name="" class="form-control" readonly="" value="<?php echo $row['course']; ?>">
					</div>
					<div class="form-group">
						<label>Civil Status</label>
						<input type="text" name="" class="form-control" readonly="" value="<?php echo $row['civilstatus']; ?>">
					</div>
					<div class="form-group">
						<label>Age</label>
						<input type="text" name="" class="form-control" readonly="" value="<?php echo $age; ?>">
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-9">
			
			<div class="panel panel-default">
			  	<div class="panel-heading">
			  		<p><b>Note :</b> Please fill-up all the final requirements in order to proceed for submission.</p>
			  	</div>
			  	<div class="panel-body">
			  		
			  		<div class="row">
			  			<div class="col-md-6">
			  				
			  				<table class="table">
			  					<tr>
			  						<td>Blood Type</td>
			  						<td>
			  							<select name="bloodType" class="form-control">
			  								<option value="">Select</option>
			  								<?php 

			  									$blood = array("A+","A-","B+","B-","O+","O-","AB+","AB-");
			  									for ($i=0; $i < sizeof($blood); $i++) { 
			  										
			  										echo "<option value='$blood[$i]'>$blood[$i]</option>";
			  									}
			  								?>
			  							</select>
			  						</td>
			  					</tr>
			  					<tr>
			  						<td><span class="asterisk">*</span>Birth Certificate</td>
			  						<td><input type="file" name="birthCertificate[]" id="birthCertificate" multiple="" class="form-control" required="" onchange="uploadonchange(this.name)"></td>
			  					</tr>
			  					<tr>
			  						<td><span class="asterisk">*</span>Police Certificate</td>
			  						<td><input type="file" name="policeClearance" id="policeClearance" class="form-control" required="" onchange="uploadonchange(this.name)"></td>
			  					</tr>
			  					<tr>
			  						<td><span class="asterisk">*</span>Fingerprint</td>
			  						<td><input type="file" name="fingerprint" id="fingerprint" class="form-control" required="" onchange="uploadonchange(this.name)"></td>
			  					</tr>
			  					<tr>
			  						<td><span class="asterisk">*</span>SSS</td>
			  						<td><input type="file" name="sss" id="sss" class="form-control" required="" onchange="uploadonchange(this.name)"></td>
			  					</tr>
			  					<tr>
			  						<td><span class="asterisk">*</span>Cedula</td>
			  						<td><input type="file" name="cedula" id="cedula" class="form-control" required="" onchange="uploadonchange(this.name)"></td>
			  					</tr>
			  					<tr>
			  						<td><span class="asterisk">*</span>Parent's Consent</td>
			  						<td><input type="file" name="parentsConsent" id="parentsConsent" class="form-control" onchange="uploadonchange(this.name)" <?php if($edad < 18){ echo "required=''"; } else { echo "disabled=''"; } ?>></td>
			  					</tr>
			  					<tr>
			  						<td><span class="asterisk">*</span>Medical Certificate</td>
			  						<td><input type="file" name="medicalCert" id="medicalCert" class="form-control" required="" onchange="uploadonchange(this.name)"></td>
			  					</tr>
			  					<tr>
			  						<td><span class="asterisk">*</span>House Sketch</td>
			  						<td><input type="file" name="sketch" id="sketch" class="form-control" required="" onchange="uploadonchange(this.name)"></td>
			  					</tr>
			  					<tr>
			  						<td><span class="asterisk">*</span>Pagibig Tracking No.</td>
			  						<td><input type="text" name="trackingNo" id="trackingNo" class="form-control" required="" placeholder="____________"></td>
			  					</tr>
			  					<tr>
			  						<td>Pagibig MID No.</td>
			  						<td><input type="text" name="midNo" id="midNo" class="form-control" placeholder="____-____-____"></td>
			  					</tr>
			  				</table>
			  			</div>
			  			<div class="col-md-6">

			  				<table class="table">
			  					<tr>
			  						<td><span class="asterisk">*</span>Background Investigation</td>
			  						<td><input type="file" name="backgroundInvestigation" id="backgroundInvestigation" class="form-control" required="" onchange="uploadonchange(this.name)"></td>
			  					</tr>
			  					<tr>
			  						<td><span class="asterisk">*</span>Drug Test</td>
			  						<td><input type="file" name="drugTest" id="drugTest" class="form-control" required="" onchange="uploadonchange(this.name)"></td>
			  					</tr>
			  					<tr>
			  						<td><span class="asterisk">*</span>Recommendation Letter</td>
			  						<td><input type="file" name="recommendationLetter" id="recommendationLetter" class="form-control" required="" onchange="uploadonchange(this.name)"></td>
			  					</tr>
			  					<tr>
			  						<td><span class="asterisk">*</span>Marriage Certificate <?php echo $row['civilstatus']; ?></td>
			  						<td><input type="file" name="marriageCert" id="marriageCert" class="form-control" onchange="uploadonchange(this.name)" <?php if(ucwords(strtolower($row['civilstatus'])) == "Single"){ echo "disabled = ''"; } else { echo "required = ''"; } ?>></td>
			  					</tr>
			  					<tr>
			  						<td><span class="asterisk">*</span>SSS No.</td>
			  						<td><input type="text" name="sssNo" class="form-control" required="" placeholder="__-_______-_"></td>
			  					</tr>
			  					<tr>
			  						<td>ID Card No.</td>
			  						<td><input type="text" name="idCardNo" class="form-control" placeholder="________"></td>
			  					</tr>
			  					<tr>
			  						<td><span class="asterisk">*</span>CTC No.</td>
			  						<td><input type="text" name="ctcNo" class="form-control" required="" placeholder="CCI____ ________"></td>
			  					</tr>
			  					<tr>
			  						<td><span class="asterisk">*</span>Issued On(CTC)</td>
			  						<td><input type="text" name="issudeOn" class="form-control" required="" placeholder="yyyy-mm-dd"></td>
			  					</tr>
			  					<tr>
			  						<td><span class="asterisk">*</span>Issued At(CTC)</td>
			  						<td>
			  							<input type="text" name="issuedAt" onkeyup="placeIssued(this.value)" class="form-control" required="" >
			  							<div class="search-results" style="display: none;"></div>
			  						</td>
			  					</tr>
			  					<tr>
			  						<td><span class="asterisk">*</span>Philhealth No.</td>
			  						<td><input type="text" name="philhealthNo" class="form-control" required="" placeholder="__-_________-_"></td>
			  					</tr>
			  				</table>
			  			</div>
			  			<div class = "col-md-12">
			  				<input type="hidden" name="counter" value="0">
				            <button class="btn btn-sm btn-primary" onclick="addRow()">Add Other Document(s)</button>
				            <table class="table">
				              	<tbody id="myTable">

				              	</tbody>
				          	</table>

				          	<div class="form-group">
					        	<label>Remarks for Final Completion</label>
					        	<textarea class="form-control" rows="4" name="remarks"></textarea>
					        </div>
				        </div>
			  		</div>
			  	</div>
			</div>
		</div>
	</div>
	<script type="text/javascript" src="../jquery/jquery.maskedinput.js" ></script>
	<script>
		$(document).ready(function(){
			$("input[name='trackingNo']").mask("999999999999");
			$("input[name='midNo']").mask("9999-9999-9999");
			$("input[name='sssNo']").mask("99-9999999-9");
			$("input[name='idCardNo']").mask("99999999");
			$("input[name='ctcNo']").mask("CCI9999 99999999");
			$("input[name='issudeOn']").mask("9999-99-99");
			$("input[name='philhealthNo']").mask("99-999999999-9");
		});
	</script>
<?php
}

elseif ($_GET['request'] == "findPlace") {
	
	$key = mysql_real_escape_string($_POST['str']);
	$val = "";

	$query = mysql_query("SELECT brgy_name, town_name, prov_name FROM barangay 
      		INNER JOIN town ON barangay.town_id = town.town_id
      		INNER JOIN province ON town.prov_id = province.prov_id WHERE brgy_name LIKE '%$key%' ORDER BY brgy_name ASC")or die(mysql_error());

	if(mysql_num_rows($query) > 0){
		while ($row = mysql_fetch_array($query)) {
			
			$barangay = $row['brgy_name'];
			$town = $row['town_name'];
			$province = $row['prov_name'];

			$address = "$barangay, $town, $province";
			echo "<a class = \"placeFind\" href = \"javascript:void\" onclick='getPlace(\"$address\")'>".$address."</a></br>";
		}
	} else {
		die("");
	}
}

elseif ($_GET['request'] == "addRow") {
	
	$counter = $_POST['counter'];
	$loop = 1;
	for($i = 1; $i == $loop; $i++) {
		$counter++;
		?>
		<tr id="td_<?php echo $counter; ?>" class = "td2">
			<input type="text" name="addCounter[]" value="<?php echo $counter; ?>">
			<input type="text" name="ifDeleted[]" class="deleted_<?php echo $counter; ?>" value="notdeleted">
			<td><input type="text" name="docName[]" class="form-control docName_<?php echo $counter; ?>" placeholder = "Document Name"></td>
			<td><input type="file" name="others[]" class="form-control" required=""</td>
			<td><a href = "javascript:void" title = "delete this row" onclick = "delRow(<?php echo $counter; ?>)" style="color:red;"><button class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-trash" ></span> </button></a></td>
		</tr>
<?php 
	} 

	echo "&&$counter";
}

elseif ($_GET['request'] == "hiredNow") {

	$appId = $_POST['appId'];
	$appCode = $_POST['appCode'];

	function getApplicantPosition($appId){

		$query = mysql_query("SELECT `position` FROM `applicants`,`applicant` WHERE applicants.app_code = applicant.appcode AND `app_id` = '$appId'") or die(mysql_error());
		$fetch = mysql_fetch_array($query);
		return $fetch['position'];
	}

	function getApplicantName($appId){

		$query = mysql_query("SELECT `lastname`, `firstname`, `middlename`, `suffix` FROM `applicant` WHERE `app_id` = '$appId'")or die(mysql_error());
		$fetch = mysql_fetch_array($query);

		$suffix = $fetch['suffix'];
		$mname = $fetch['middlename'];
		if($suffix !=""){ $suffix = " $suffix,"; } else { $suffix=""; }
		if($mname!="") {$mname = " $mname"; } else { $mname = ""; }

		return $fetch['lastname'].", ".$fetch['firstname']."".$suffix."".$mname;	
	}

	$query = mysql_query("SELECT `name`, `cc`, `bc`, `dc`, `sc`, `ssc`, `uc`, `position` FROM `applicant_on_training` WHERE `app_id` = '$appId'")or die(mysql_error());
	$row = mysql_fetch_array($query);

	if(isset($row['name'])){

		$name = $row['name'];
	} else {

		$name = getApplicantName($appId);
	}

	if(isset($row['position'])){

		$position = $row['position'];
	} else {

		$position = getApplicantPosition($appId);
	}
?>
	
	<input type="hidden" name="appId" value="<?php echo $appId; ?>">
	<input type="hidden" name="appCode" value="<?php echo $appCode; ?>">

	<table class="table table-bordered">
		<tr>
			<th width="50%">APPLICANT NAME</th>
			<th width="50%"><input type="text" name="appName" class="form-control" readonly="" value="<?php echo ucwords(strtolower($name)); ?>"></th>
		</tr>
		<tr>
			<th><span class="asterisk">*</span> COMPANY</th>
			<td>
				<select name="company" class="form-control" onchange="company(this.value)">
					<option value="">Select</option>
					<?php 

						$company = mysql_query("SELECT `company_code`, `company` FROM `locate_company` WHERE `status` = 'active'")or die(mysql_error());
						while ($cc = mysql_fetch_array($company)) { ?>
							
							<option value="<?php echo $cc['company_code']; ?>" <?php if($nq->getCompanyName($row['cc']) == $cc['company']){ echo "selected=''"; } ?>><?php echo $cc['company']; ?></option>
							<?php
						}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<th><span class="asterisk">*</span> BUSINESS UNIT</th>
			<td>
				<select name="bunit" class="form-control" onchange="bunit(this.value)">
					<option value="">Select</option>
					<?php

						$bunit = mysql_query("SELECT `business_unit`, `company_code`, `bunit_code` FROM `locate_business_unit` WHERE `company_code` = '".$row['cc']."' AND `status` = 'active'")or die(mysql_error());
						while ($bc = mysql_fetch_array($bunit)) { ?>
							
							<option value="<?php echo $bc['company_code'].'/'.$bc['bunit_code']; ?>" <?php if($nq->getBusinessUnitName($row['bc'],$row['cc']) == $bc['business_unit']){ echo "selected=''"; } ?>><?php echo $bc['business_unit']; ?></option>
							<?php
						}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<th><span class="asterisk">*</span> DEPARTMENT</th>
			<td>
				<select name="dept" class="form-control" onchange="dept(this.value)">
					<option value="">Select</option>
					<?php 

						$dept = mysql_query("SELECT `dept_name`, `company_code`, `bunit_code`, `dept_code`, `status` FROM `locate_department` WHERE `company_code` = '".$row['cc']."' AND `bunit_code` = '".$row['bc']."' AND `status` = 'active'")or die(mysql_error());
						while ($dc = mysql_fetch_array($dept)) { ?>
							
							<option value="<?php echo $dc['company_code'].'/'.$dc['bunit_code'].'/'.$dc['dept_code']; ?>" <?php if($nq->getDepartmentName($row['dc'],$row['bc'],$row['cc']) == $dc['dept_name']){ echo "selected=''"; } ?>><?php echo $dc['dept_name']; ?></option>
							<?php
						}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<th>SECTION</th>
			<td>
				<select name="section" class="form-control" onchange="section(this.value)">
					<option value="">Select</option>
					<?php 

						$section = mysql_query("SELECT `section_name`, `company_code`, `bunit_code`, `dept_code`, `section_code` FROM `locate_section` WHERE `company_code` = '".$row['cc']."' AND `bunit_code` = '".$row['bc']."' AND `dept_code` = '".$row['dc']."' AND `status` = 'active'")or die(mysql_error());
						while ($sc = mysql_fetch_array($section)) { ?>
							
							<option value="<?php echo $sc['company_code'].'/'.$sc['bunit_code'].'/'.$sc['dept_code'].'/'.$sc['section_code']; ?>" <?php if($nq->getSectionName($row['sc'],$row['dc'],$row['bc'],$row['cc']) == $sc['section_name']){ echo "selected=''"; } ?>><?php echo $sc['section_name']; ?></option>
							<?php
						}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<th>SUB-SECTION</th>
			<td>
				<select name="ssection" class="form-control" onchange="ssection(this.value)">
					<option value="">Select</option>
					<?php 

						$ssection = mysql_query("SELECT `sub_section_name`, `company_code`, `bunit_code`, `dept_code`, `section_code`, `sub_section_code` FROM `locate_sub_section` WHERE `company_code` = '".$row['cc']."' AND `bunit_code` = '".$row['bc']."' AND `dept_code` = '".$row['dc']."' AND `section_code` = '".$row['sc']."' AND `status` = 'active'")or die(mysql_error());
						while ($ssc = mysql_fetch_array($ssection)) { ?>
							
							<option value="<?php echo $ssc['company_code'].'/'.$ssc['bunit_code'].'/'.$ssc['dept_code'].'/'.$ssc['section_code'].'/'.$ssc['sub_section_code']; ?>" <?php if($nq->getSubSectionName($row['ssc'],$row['sc'],$row['dc'],$row['bc'],$row['cc']) == $ssc['sub_section_name']){ echo "selected=''"; } ?>><?php echo $ssc['sub_section_name']; ?></option>
							<?php
						}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<th>UNIT</th>
			<td>
				<select name="unit" class="form-control" onchange="unit(this.value)">
					<option value="">Select</option>
					<?php 

						$unit = mysql_query("SELECT `unit_name`, `company_code`, `bunit_code`, `dept_code`, `section_code`, `sub_section_code`, `unit_code` FROM `locate_unit` WHERE `company_code` = '".$row['cc']."' AND `bunit_code` = '".$row['bc']."' AND `dept_code` = '".$row['dc']."' AND `section_code` = '".$row['sc']."' AND `sub_section_code` = '".$row['ssc']."' AND `status` = 'active'")or die(mysql_error());
						while ($uc = mysql_fetch_array($unit)) { ?>
							
							<option value="<?php echo $uc['company_code'].'/'.$uc['bunit_code'].'/'.$uc['dept_code'].'/'.$uc['section_code'].'/'.$uc['sub_section_code'].'/'.$uc['unit_code']; ?>" <?php if($nq->getUnitName($row['uc'],$row['ssc'],$row['sc'],$row['dc'],$row['bc'],$row['cc']) == $uc['unit_name']){ echo "selected=''"; } ?>><?php echo $uc['unit_name']; ?></option>
							<?php
						}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<th><span class="asterisk">*</span> POSITION</th>
			<td>
				<select name="position" class="form-control" onchange="positionLevel(this.value)">
					<option value="">Select</option>
					<?php 

						$pos = mysql_query("SELECT poslevel_no, position_title, level FROM `position_leveling` ORDER BY position_title ASC")or die(mysql_error());
						while ($p = mysql_fetch_array($pos)) { ?>
							
							<option value="<?php echo $p['poslevel_no']; ?>" <?php if($position == $p['position_title']){ echo "selected=''"; } ?>><?php echo $p['position_title']; ?></option>
							<?php
						}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<th><span class="asterisk">*</span> POSITION LEVEL</th>
			<td>
				<?php 

					$query = mysql_query("SELECT lvlno FROM position_leveling WHERE position_title = '".$position."'")or die(mysql_error());
					$posLevel = mysql_fetch_array($query)['lvlno'];
				?>
				<input type="text" class="form-control" readonly="" name="posLevel" value="<?php echo $posLevel ?>">
			</td>
		</tr>
		<tr>
			<th><span class="asterisk">*</span> LODGING</th>
			<td>
				<select name="lodging" class="form-control">
					<option value="Stay Out">Stay Out</option>
					<option value="Stay In">Stay In</option>
				</select>
			</td>
		</tr>
		<tr>
			<th><span class="asterisk">*</span> EMPLOYEE TYPE</th>
			<td>
				<select name="empType" class="form-control">
					<option value="NESCO">NESCO</option>
					<option value="NESCO Contractual">NESCO Contractual</option>
					<option value="NESCO-PTP">NESCO-PTP</option>
					<option value="NESCO-PTA">NESCO-PTA</option>
					<option value="NESCO Partimer">NESCO Partimer</option>
				</select>
			</td>
		</tr>
		<tr>
			<th colspan="2"><i>INCLUSIVE DATES OF CONTRACT</i></th>
		</tr>
		<tr>
			<th><span class="asterisk">*</span> STARTDATE</th>
			<td>
				<input type="text" name="startdate" id="startdate" class="form-control" placeholder="mm/dd/yyyy" autocomplete="off" onchange="inputText(this.name)">
			</td>
		</tr>
		<tr>
			<th><span class="asterisk">*</span> EOCDATE</th>
			<td>
				<input type="text" name="eocdate" id="eocdate" class="form-control" placeholder="mm/dd/yyyy" autocomplete="off" onchange="inputText(this.name)">
			</td>
		</tr>
		<tr>
			<th><span class="asterisk">*</span> No. of Month(s) to Work</th>
			<td>				
				<select name="duration" id="duration" class="form-control" onchange="inputText(this.name)">
			  		<option value=""></option>
					<option value="1">1 month</option>
					<option value="1.5">1.5 month</option>
					<option value="2">2 months</option>
					<option value="2.5">2.5 months</option>
					<option value="3">3 months</option>
					<option value="3.5">3.5 months</option>
					<option value="4">4 months</option>
					<option value="4.5">4.5 months</option>
					<option value="5">5 months</option>	
					<option value="6">6 months </option>					
					<option value="12">12 months </option>
		  		</select>
			</td>
		</tr>
		<tr>
			<th>Number of Hours (for OJT)</th>
			<td>
				<input type="text" name="ojtHrs"  class="form-control" onkeyup="numericVal(this)">
			</td>
		</tr>
		<tr>
			<th>Schedule (for Part-timer)</th>
			<td>
				<input type="text" name="partimerSched"  class="form-control">
			</td>
		</tr>
		<tr>
			<th><span class="asterisk">*</span> SUPERVISOR</th>
			<td>
				<input type="hidden" name="supId">
				<input type="text" name="supName" class="form-control" onkeyup="searchSupervisorName(this.value)" autocomplete="off">
				<div class="search-results2" style="display:none;"></div>
			</td>
		</tr>
		<tr>
			<th colspan="2"><i>SIGNED IN THE PRESENCE OF</i></th>
		</tr>
		<tr>
			<td>
				<div class="form-group">
					<label><span class="asterisk">*</span> WITNESS 1:</label>
					<input type="text" name="witness1" class="form-control" placeholder="Firstname Lastname" style="text-transform:uppercase;" onkeypress="inputText(this.name)">
				</div>
			</td>
			<td>
				<div class="form-group">
					<label><span class="asterisk">*</span> WITNESS 2:</label>
					<input type="text" name="witness2" class="form-control" placeholder="Firstname Lastname" style="text-transform:uppercase;" onkeypress="inputText(this.name)">
				</div>
			</td>
		</tr>
		<tr>
			<th colspan="2"><i>Comments / Remarks</i></th>
		</tr>
		<tr>
			<td>
				<div class="form-group">
					<label>Comment</label>
					<textarea class="form-control" name="comment" rows="4"></textarea>
				</div>
			</td>
			<td>
				<div class="form-group">
					<label>Remarks</label>
					<textarea class="form-control" name="remarks" rows="4"></textarea>
				</div>
			</td>
		</tr>
	</table>
	<script type="text/javascript">
		
		$(function(){

			$( "#startdate" ).datepicker({ dateFormat: "mm/dd/yy", changeMonth: true, changeYear: true, showButtonPanel: true });
			$( "#eocdate" ).datepicker({ dateFormat: "mm/dd/yy", changeMonth: true, changeYear: true, showButtonPanel: true });
		});
	</script>
<?php
}

elseif ($_GET['request'] == "processHiring") {
	
	$appId = $_POST['appId'];
	$appCode = $_POST['appCode'];
	$appName = mysql_real_escape_string($_POST['appName']);

	$company = $_POST['company'];
	$bunit = $_POST['bunit'];
	$dept  = $_POST['dept'];
	$section  = $_POST['section'];
	$ssection  = $_POST['ssection'];
	$unit  = $_POST['unit'];

	$bunit = explode("/", $bunit);
	$dept = explode("/", $dept);
	$section = explode("/", $section);
	$ssection = explode("/", $ssection);
	$unit = explode("/", $unit);

	$bunit = end($bunit);
	$dept = end($dept);
	$section = end($section);
	$ssection = end($ssection);
	$unit = end($unit);


	$lodging = $_POST['lodging'];
	// $position = mysql_real_escape_string($_POST['position']);
	$query = mysql_query("SELECT position_title FROM position_leveling WHERE poslevel_no = '".$_POST['position']."'") or die(mysql_error());
	$position = mysql_fetch_array($query)['position_title'];
	$empType = $_POST['empType'];
	$posLevel = $_POST['posLevel'];
	$duration = $_POST['duration'];
	$startdate = date("Y-m-d",strtotime($_POST['startdate']));
	$eocdate = date("Y-m-d",strtotime($_POST['eocdate']));
	$witness1 = $_POST['witness1'];
	$witness2 = $_POST['witness2'];
	$ojtHrs = $_POST['ojtHrs'];
	$partimerSched = $_POST['partimerSched'];
	$comment = mysql_real_escape_string($_POST['comment']);
	$remarks = mysql_real_escape_string($_POST['remarks']);
	$supId = $_POST['supId'];

	// applicant tracking

	$trackQuery = mysql_query("INSERT INTO `applicant_tracking`
									(`uni_id`, `date`, `time_done`, `appcode`, `appid`, `process`, `subprocess`, `status`, `user_identify`) 
								VALUES 
									('','".date("Y-m-d H:i:s")."','','$appCode','$appId','8','1','','".$_SESSION['emp_id']."')")or die(mysql_error());


	$updateQuery = mysql_query("UPDATE `applicant_tracking` 
									SET `time_done`='$time', `status`='Done' 
									WHERE `appid` = '$appId' AND `process` = '7' AND `subprocess` = '1'")or die(mysql_error());
	// end

	$emp_idno = $cedula_no = $cedula_date = $cedula_place = "";
	$sql = mysql_query("SELECT * FROM `applicant_otherdetails` WHERE `app_id` = '$appId' ORDER BY `no` DESC LIMIT 1")or die(mysql_error());
	if (mysql_num_rows($sql)) {
		
		$rw = mysql_fetch_array($sql);
		$emp_idno = $rw['card_no'];
		$cedula_no = $rw['cedula_no'];
		$cedula_date = $rw['cedula_date'];
		$cedula_place = $rw['cedula_place'];
	}

	// this is a code i inserted //
		$empidExist = mysql_query("SELECT emp_id FROM employee3 WHERE emp_id = '$appId'")or die(mysql_error());
        $numEmpid = mysql_num_rows($empidExist);
        


        if($numEmpid > 0) {

        	$sql = mysql_query(
					"SELECT
						*
					 FROM
						employee3 
					 WHERE
						emp_id = '".$appId."'"
				   ) or die(mysql_error());
			$old_data = mysql_fetch_array($sql);

			// insert the old contrct to the employment record table
			mysql_query(
				"INSERT
					INTO
				 employmentrecord_
					(
						emp_id,
						emp_no,
						emp_pins,
						company_code,
						bunit_code,
						dept_code,
						section_code,
						sub_section_code,
						unit_code,
						barcodeId,
						bioMetricId,
						payroll_no,
						startdate,
						eocdate,
						emp_type,
						position,
						positionlevel,
						current_status,
						lodging,
						pos_desc,
						remarks,
						epas_code,
						contract,
						permit,
						clearance,
						comments,
						date_updated,
						updatedby,
						duration
						
					) VALUES (
						'".$appId."',
						'".$old_data['emp_no']."',
						'".$old_data['emp_pins']."',
						'".$old_data['company_code']."',
						'".$old_data['bunit_code']."',
						'".$old_data['dept_code']."',
						'".$old_data['section_code']."',
						'".$old_data['sub_section_code']."',
						'".$old_data['unit_code']."',
						'".$old_data['barcodeId']."',
						'".$old_data['bioMetricId']."',
						'".$old_data['payroll_no']."',
						'".$old_data['startdate']."',
						'".$old_data['eocdate']."',
						'".$old_data['emp_type']."',
						'".$old_data['position']."',
						'".$old_data['positionlevel']."',
						'End of Contract',
						'".$old_data['lodging']."',
						'".$old_data['position_desc']."',
						'".addslashes($old_data['remarks'])."',
						'".$old_data['epas_code']."',
						'".$old_data['contract']."',
						'".$old_data['permit']."',
						'".$old_data['clearance']."',
						'".$old_data['comments']."',
						'".$old_data['date_updated']."',
						'".$old_data['updated_by']."',
						'".$old_data['duration']."'
					)"
			) or die(mysql_error()); 

			// employmentrecord_
			$sql = mysql_query(
					"SELECT
						record_no
					  FROM
						employmentrecord_
					  WHERE
						emp_id = '".$appId."'
					  ORDER BY 
						record_no DESC"
				   ) or die(mysql_error());
			$new_rno = mysql_fetch_array($sql);
			
			// appraisal details
			$sql = mysql_query(
					"SELECT 
						record_no
					 FROM
						appraisal_details
					 WHERE
						record_no = '".$old_data['record_no']."'"
				   ) or die(mysql_error());
		    $c_appdetails = mysql_num_rows($sql);
			if($c_appdetails > 0){
				mysql_query(
					"UPDATE
						appraisal_details
					 SET
						record_no = '".$new_rno['record_no']."'
					 WHERE
						record_no = '".$old_data['record_no']."'"
				) or die(mysql_error());
			}

			// witness
			$sql = mysql_query(
					"SELECT
						rec_no
					 FROM
						employment_witness
					 WHERE
						rec_no = '".$old_data['record_no']."'"
				   ) or die(mysql_error());
			$c_empwitness = mysql_num_rows($sql);
			if($c_empwitness > 0){
				mysql_query(
					"UPDATE
						employment_witness
					 SET
						rec_no = '".$new_rno['record_no']."'
					 WHERE
						rec_no = '".$old_data['record_no']."'"
				) or die(mysql_error());
			}

			// delete the old record in employee3
			mysql_query(
				"DELETE
					FROM
				 employee3
					WHERE
				 emp_id = '".$appId."'"
			) or die(mysql_error());	
        }

        if(!empty($old_data['emp_no']) && !empty($old_data['emp_pins']))
		{
			$emp_no   = $old_data['emp_no'];
			$emp_pins = $old_data['emp_pins'];
		} 
		else
		{
			$emp_no   = "";
			$emp_pins = "";
		}

		$insert = mysql_query("INSERT INTO `employee3`
									(`emp_id`, `emp_no`, `emp_pins`, `name`, `startdate`, `eocdate`, `emp_type`, `current_status`, `duration`, `company_code`, `bunit_code`, `dept_code`, `section_code`, `sub_section_code`, `unit_code`, `poslevel`, `position`, `lodging`, `comments`, `remarks`, `tag_as`, `date_added`, `added_by`) 
								VALUES 
									('$appId', '$emp_no', '$emp_pins', '$appName', '$startdate', '$eocdate', '$empType', 'Active', '$duration', '$company', '$bunit', '$dept', '$section', '$ssection', '$unit', '$posLevel', '$position', '$lodging', '$comment', '$remarks', 'new', '$date','".$_SESSION['emp_id']."')")or die(mysql_error());
		$recordNo = mysql_insert_id();

		if($insert){

			$checkother = mysql_query("SELECT * FROM `employee_otherdetails` WHERE `emp_id` = '$appId'")or die(mysql_error());

			if(mysql_num_rows($checkother) == 0){

				$insertOther = mysql_query("INSERT INTO `employee_otherdetails`(`id`, `emp_id`, `ojt_hours`, `part_sched`) VALUES ('','$appId','".$ojtHrs." Hours','$partimerSched')")or die(mysql_error());
			} else {

				$updateOther = mysql_query("UPDATE `employee_otherdetails` SET `ojt_hours`='".$ojtHrs." Hours',`part_sched`='$partimerSched' WHERE `emp_id` = '$appId'")or die(mysql_error());
			}
		}

		$supervise = mysql_query("	INSERT into `leveling_subordinates` (`record_no`, `ratee`, `subordinates_rater`) VALUES ('','$supId','$appId')") or die( mysql_error());

		$select = mysql_query("SELECT `ew_no` FROM `employment_witness` WHERE `emp_id` = '$appId' ORDER BY `ew_no` DESC LIMIT 1")or die(mysql_error());
		if(mysql_num_rows($select) == 0){

			$insert2 = mysql_query("INSERT INTO `employment_witness`
											(`ew_no`, `emp_id`, `rec_no`, `witness1`, `witness2`) 
										VALUES 
											('','$appId','$recordNo','$witness1','$witness2')")or die(mysql_error());
		} else {

			$update2 = mysql_query("UPDATE `employment_witness` SET `witness1`='$witness1',`witness2`='$witness2' WHERE `emp_id` = '$appId'")or die(mysql_error());
		}

		$insert3 = mysql_query("INSERT INTO `application_history`
										(`no`, `app_id`, `date_time`, `description`, `position`, `phase`, `status`, `soc`, `eoc`) 
									VALUES 
										('','$appId','$date','recorded as new employee','$position','Hired','successful','$startdate','$eocdate')")or die(mysql_error());

		$selectAppDetails = mysql_query("SELECT `date_hired` FROM `application_details` WHERE `app_id` = '$appId'")or die(mysql_error());


		function getAge($dob,$tdate)
	    {
	        $age = 0;
	        while( $tdate >= $dob = strtotime('+1 year', $dob)){
	                ++$age;
	        }return $age;
	    }

		if(mysql_num_rows($selectAppDetails) > 0){

			$appDet = mysql_fetch_array($selectAppDetails);

			$dateHired = $appDet['date_hired'];
			$dh = strtotime($dateHired);		
			$now = date('Y-m-d');/*** another date ***/		
			$tdate = strtotime($now);/*** show the date ***/		
			$yearsGap = getAge($dh, $tdate);

			if ($yearsGap >= 3) {
				
				$dateHired = $startdate;
			} 
	        
			$updateAppDetails = mysql_query("UPDATE `application_details` SET 
													`position_applied`='$position',`unit_code`='$unit',`sub_section_code`='$ssection',`section_code`='$section',`dept_code`='$dept',`bunit_code`='$bunit',`company_code`='$company',`date_hired`='$dateHired',`application_status`='Hired',`remarks`='$empType' WHERE `app_id` = '$appId'")or die(mysql_error());
		} else {

			$insertAppDetails = mysql_query("INSERT INTO `application_details`
														(`no`, `app_id`, `position_applied`, `unit_code`, `sub_section_code`, `section_code`, `dept_code`, `bunit_code`, `company_code`,`date_hired`, `application_status`,`remarks`) 
												VALUES 
														('','$appId','$position','$unit','$ssection','$section','$dept','$bunit','$company','$startdate','Hired','$empType')")or die(mysql_error());
		}

		$updateApp = mysql_query("UPDATE `applicants` SET `position`='$position', `status`='new employee' WHERE `app_code` = '$appCode'")or die(mysql_error());

		$selectUsers = mysql_query("SELECT `user_no` FROM `users` WHERE `emp_id` = '$appId' AND  `usertype` = 'employee'")or die(mysql_error());
		if (mysql_num_rows($selectUsers) > 0) {
			
			$password = md5("Hrms2014");
			$updateUsers = mysql_query("UPDATE `users` 
											SET `username`='$appId',`password`='$password',`user_status`='active',`date_updated`='".date("Y-m-d H:i:s")."' 
											WHERE `emp_id` = '$appId' AND `usertype` = 'employee'")or die(mysql_error());
		} else {

			$insertUsers = mysql_query("INSERT INTO `users`
												(`user_no`, `emp_id`, `username`, `password`, `usertype`, `user_status`, `login`, `date_created`,`user_id`) 
											VALUES 
												('','$appId','$appId','$password','employee','active','no','".date("Y-m-d H:i:s")."','4')")or die(mysql_error());
		}

		die("Ok");

}

else if($_GET['request'] == "generateContractPermit"){

	$empId = $_POST['empId'];

	$query = mysql_query("SELECT record_no, position, emp_type FROM employee3 WHERE emp_id = '$empId'")or die(mysql_error());
	$r = mysql_fetch_array($query);

	$recordNo = $r['record_no'];
	$position = $r['position'];
	$emp_type = $r['emp_type'];

	$posno = $nq->getPositionNo($position);
?>
	<div class="row">
		<br>
		<div class="col-md-8 col-md-offset-2">
			<button class="btn btn-primary btn-md" onclick="permit('<?php echo $recordNo; ?>')"> Permit-To-Work </button>
			<button class="btn btn-primary btn-md" data-toggle='modal' data-target='#viewContract' onclick="contract('<?php echo $recordNo; ?>','<?php echo $emp_type; ?>','<?php echo $empId; ?>')"> Contract of Employment </button>
		</div>
		<br>
		<br>
		<br>
	</div>
<?php
}

else if($_GET['request'] == "getApplicantName"){

	$appId = $_POST['appId'];

	$query = mysql_query("SELECT firstname,middlename,lastname,suffix FROM applicant WHERE app_id = '$appId'")or die(mysql_error());
	$row = mysql_fetch_array($query);

	$suffix = $row['suffix'];
	$mname = $row['middlename'];
	if($suffix !=""){ $suffix = " $suffix,"; } else { $suffix=""; }
	if($mname!="") {$mname = " $mname"; } else { $mname = ""; }

	$appName =  utf8_decode(utf8_encode($row['lastname'])).", ".utf8_decode(utf8_encode($row['firstname']))."".$suffix."".utf8_decode(utf8_encode($mname));
	echo $appName;
}

else if($_GET['request'] == "deployNow") {

	$appId = $_POST['appId'];
	$appCode = $_POST['appCode'];

	$updAppDetails = mysql_query("UPDATE `application_details` SET `application_status`='Deployed',`date_deployed`='$date' WHERE `app_id` = '$appId'")or die(mysql_error());
	$updApplicants = mysql_query("UPDATE `applicants` SET `status`='deployed' WHERE `app_code` = '$appCode'")or die(mysql_error());


	if($updAppDetails && $updApplicants){

		$updateQuery = mysql_query("UPDATE `applicant_tracking` 
									SET `time_done`='$time', `status`='Done' 
									WHERE `appid` = '$appId' AND `process` = '8' AND `subprocess` = '1'")or die(mysql_error());

		die("Ok");
	}
}

else if($_GET['request'] == "findEmployeeforClearance"){

  	$key = mysql_real_escape_string($_POST['str']);
	$val = "";
	$empname = mysql_query("SELECT employee3.`emp_id`, `name` FROM `employee3`
								WHERE emp_type like 'NESCO%' and (current_status = 'Active' or current_status = 'End of Contract') 
								AND (name like '%$key%' or employee3.emp_id = '$key')  order by name limit 10")or die(mysql_error());
	while($n = mysql_fetch_array($empname)){
		$empId = $n['emp_id'];
		$name  = $n['name'];
		
		if($val != $empId){
			echo "<a class = \"nameFind\" href = \"javascript:void\" onclick=\"getEmpId('$empId*$name')\">[ ".$empId." ] = ".$name."</a></br>";
		}
		else{
			echo "<a class = \"afont\" href = \"javascript:void\">No Result Found</a></br>";	
		}
	}
}

else if($_GET['request'] == "findEmployeeforUploadSignedClearance"){

  	$key = mysql_real_escape_string($_POST['str']);
	$val = "";
	$empname = mysql_query("SELECT employee3.`emp_id`, `name`,`current_status` FROM `employee3`
								WHERE emp_type like 'NESCO%' AND (current_status = 'Active' or current_status = 'End of Contract' or current_status = 'Resigned' or current_status = 'V-Resigned' or current_status = 'Ad-Resigned' or current_status = 'Retrenched' or current_status = 'Retired' or current_status = 'Deceased') 
								AND (sub_status != 'Cleared')
								AND (name like '%$key%' or employee3.emp_id = '$key')  order by name limit 10")or die(mysql_error());
	while($n = mysql_fetch_array($empname)){
		$empId = $n['emp_id'];
		$name  = $n['name'];
		$status= $n['current_status'];
		
		if($val != $empId){			
			echo "<a class = \"nameFind\" href = \"javascript:void\" onclick='getEmpId_2(\"$empId*$name*$status\")'>[ ".$empId." ] = ".$name."</a></br>";
		}
		else{
			echo "<a class = \"afont\" href = \"javascript:void\">No Result Found</a></br>";	
		}
	}
} 

else if($_GET['request'] == "findEmployeeforClearanceReprint"){

  	$key = mysql_real_escape_string($_POST['str']);
	$val = "";
	$empname = mysql_query("SELECT employee3.`emp_id`, `name`,`sc_id`,`generated_clearance` FROM `employee3`
								INNER JOIN secure_clearance
								ON employee3.emp_id = secure_clearance.emp_id
								WHERE secure_clearance.status = 'Pending'							
								AND (name like '%$key%' or employee3.emp_id = '$key')  order by name limit 10")or die(mysql_error());
	while($n = mysql_fetch_array($empname)){
		$empId = $n['emp_id'];
		$name  = $n['name'];
		$scid  = $n['sc_id'];
		$generatedclearance = $n['generated_clearance'];
		
		if($val != $empId){
			echo "<a class = \"nameFind\" href = \"javascript:void\" onclick='getEmpId_reprint(\"$empId*$name*$scid*$generatedclearance\")'>[ ".$empId." ] = ".$name."</a></br>";
		}
		else{
			echo "<a class = \"afont\" href = \"javascript:void\">No Result Found</a></br>";	
		}
	}
} 

else if($_GET['request'] == "div_notifyres")
{ ?>

	<div id='div_notifyresignation'>
		<h4> SUBMIT RESIGNATION LETTER </h4>
		<div class="row">
			<div class="col-md-7">
				<table width="100%">
					<tr>
						<td> Employee Name </td>
					</tr>
					<tr>
						<td> 
							<div class="form-group">
						      	<div class="input-group">
						          	<input type="text" name="app_id" onkeyup="namesearch(this.value)" class="form-control" placeholder="Search Employee (Emp. ID, Lastname or Firstname)" value="" autocomplete="off" required="">
						          	<span class="input-group-btn">
						            	<button class="btn btn-info" name="search" >Search &nbsp;<i class="glyphicon glyphicon-search"></i></button>
						          	</span>
						      	</div>
						      	<div class="search-results" style="display:none;"></div>
						    </div>

						    <div class = "regularization" style="display:none;">

						    </div>
						</td>
					</tr>
					<tr>
						<td> Resignation Date / EOC Date </td>
					</tr>
					<tr>
						<td> <input type="text" autocomplete="off" class="form-control" name='dateres' id='dateres'  placeholder='mm/dd/yyyy' > </td>
					</tr>
					<tr>
						<td> Upload Resignation Letter (Scanned) </td>
					</tr>
					<tr>
						<td> 
							<input type="file" accept="image/*" name="clearance" class="btn btn-default" onchange="check(this.id,'imgclearance')" id="clearance" size="50" >
						</td>
					</tr>
					<tr>
						<td> 
							<input type="button" class="btn btn-md btn-primary" value="Submit"> 
							<input type="button" class="btn btn-md btn-danger" value="Cancel"> 
						</td>
					</tr>
				</table> <br>
			</div>
			<div class="col-md-5" style="border-left:1px solid #ccc">
				<h4> TAKE NOTE !!! </h4> <br> 
				≈ After submitting, status will change to <b style='color:green'> Active (For Resignation)</b> <br>
				≈ Once tagged, giving of EPAS will also be automatically available for supervisors. <br>
				≈ The day after the resignation date, status will automatically change to <b style='color:orange'> RESIGNED (UNCLEARED)</b><br><br>
			</div>
		</div>
		
		
	</div>

	<link rel="stylesheet" type="text/css" media="all" href="../css/jquery-ui.css" />
	<script type="text/javascript" src="../jquery/jquery-latest.min.js"></script>
	<script type="text/javascript" src="../jquery/jquery-ui.js"></script>
	<script>
		$(function(){ 
			$( "#dateres" ).datepicker({ dateFormat: "mm/dd/yy", changeMonth: true, changeYear: true, showButtonPanel: true });	
		});
	</script>

	<?php
}
else if($_GET['request'] == "div_secureclearance")
{
	/** Note: Secure clearance and after that status will change to 
	RESIGNED (UNCLEARED) or END OF CONTRACT (UNCLEARED)
	1.) check emptype if NESCO OR AE
		IF NESCO print NESCO CLEARANCE
	**/
 ?>

	<div id='div_secureclearance'>
		<h4> SECURE CLEARANCE </h4>

		<form action='' name='printClearance' id='printClearance_form' method="POST" enctype="multipart/form-data"> 	
			
			<div class="form-group">
		    	<label> <span class='rqd'> * </span>  Reason for asking Clearance </label>
			    <select required class="form-control" id='reason' name='reason' onchange='getRL(this.value)'>
					<option value=''> - Please Choose - </option>
					<!-- <option value="resignation"> RESIGNATION FROM EMPLOYMENT WITH THE COMPANY</option> -->
					<option value="V-Resigned"> VOLUNTARY RESIGNATION FROM EMPLOYMENT WITH THE COMPANY </option>
					<option value="Ad-Resigned"> ADVISED TO RESIGNED FROM EMPLOYMENT WITH THE COMPANY </option>
					<option value="Termination"> TERMINATION OF CONTRACT FROM THE COMPANY </option>
					<option value="Retrenchment"> RETRENCHMENT </option>
					<option value="Retirement"> RETIREMENT </option>
					<option value="Deceased"> DECEASED </option>
				</select>
			</div>

			<div class="form-group">
				<label> <span class='rqd'> * </span> Employee Name  </label>
		      	<div class="input-group">
		          	<input type="text" required name="empid" id= 'empidClearance' onkeyup="namesearch(this.value)" class="form-control" placeholder="Lastname, Firstname" value="" autocomplete="off" required="">
		          	<span class="input-group-btn">
		            	<button class="btn btn-info" name="search" >Search &nbsp;<i class="glyphicon glyphicon-search"></i></button>
		          	</span>
		      	</div>
		      	<div class="search-results" style="display:none;"></div>		    
		    </div>		   

		    <div class="non_deceased_form">	</div>

			<div class="deceased_form">	</div>

			<input type="submit" class="btn btn-primary" value='Submit'> 	<br><br>			
			<i> Note: <span class='rqd'> * </span> Required fields. </i> <br>
			
		</form>
	</div> 

	<link rel="stylesheet" type="text/css" media="all" href="../css/jquery-ui.css" />
	<script type="text/javascript" src="../jquery/jquery-latest.min.js"></script>
	<script type="text/javascript" src="../jquery/jquery-ui.js"></script>

	<script>
		$(function() { // minDate: new Date(), //"yy-mm-dd"					
						
		});

	</script>

	<?php
}
else if($_GET['request'] == "div_uploadclearance")
{ ?>

	<div id='div_uploadclearance'>
		<h4> UPLOAD CLEARANCE & CHANGE STATUS </h4>

		<form action='' name='uploadSignedClearance' id='uploadSignedClearance' method="POST" enctype="multipart/form-data"> 	
			
			<div class="form-group">
				<label>  <span class='rqd'> * </span> Employee Name </label>
				<div class="input-group">
		          	<input type="text" name="empid" id='empid' onkeyup="namesearch_2(this.value)" class="form-control" placeholder="Search Employee (Emp. ID, Lastname or Firstname)" value="" autocomplete="off" required="">
		          	<span class="input-group-btn">
		            	<button class="btn btn-info" name="search" >Search &nbsp;<i class="glyphicon glyphicon-search"></i></button>
		          	</span>
		      	</div>
		      	<div class="search-results" style="display:none;"></div>
			</div>
			
			<div id='showEpas'></div>			

			<div class="form-group">
				<label>  <span class='rqd'> * </span> Remarks </label>	
				<textarea required name="remarks" id="remarks" cols="47" class="form-control" rows="2" ></textarea>      	
		    </div>	    

		    <div class="form-group">
				<label>  <span class='rqd'> * </span> Signed Clearance (Scanned) </label>	
				<input type="file" required accept="image/*"  name="clearance" id="clearance" class="btn btn-default" onchange="check(this.id,'imgclearance')"  size="50" >	
		    </div>

		    <input type="submit" class="btn btn-primary" id='submit_printclearance_btn' value='Submit'>
		</form>

		<br><i> Note: 
		<br><span class='rqd'> * </span> Required fields. 	
		<br>EPAS grade is a REQUIREMENT. </b> </i> <br><br>

	</div> <?php
}
else if($_GET['request'] == "div_clearanceprocessflow")
{ ?>
	<style type="text/css">
	#csubmission {display:none;}
	</style>

	<h4> PROCESS FLOW </h4>	

	<embed src='images/HRMSClearance_050620_v13.2.pdf' style="height:450px;width:100%;"></embed>

	<!-- <ul class="nav nav-tabs">		
        <li class="active"><a href="#tab1default" data-toggle="tab"> Clearance Printing  </a></li>
        <li><a href="#tab2default" data-toggle="tab"> Clearance Submission </a></li>                
    </ul>
   
	<div class="tab-content">
		<div class="tab-pane fade in active" id="tab1default"> <br>
			<img src='images/clearanceprocess_page1.jpg' width="100%"> <br><br><br> 
		</div>

		<div class="tab-pane fade" id="tab2default" > <br>
			<img src='images/clearanceprocess_page1.jpg'  width="100%"> <br> <br><br> 
		</div>
	</div>	 -->
	
<?php
}  
else if($_GET['request'] == "div_listofwhosecure") // clearance
{ 
	if($_POST['year'] !="" ){ $currentyear = $_POST['year']; } else { $currentyear = date('Y'); }	
	
	// //get the distinct years for secure clearance to filter years for display
	$year_arr 	= array();
	$queryY 	= mysql_query("SELECT DISTINCT YEAR(date_resignation) AS years FROM `secure_clearance` order by years desc ");
	while($ryear= mysql_fetch_array($queryY)){
		if($ryear['years'] !=0 ){ $year_arr[]	= $ryear['years']; }
	}

	echo "
	<div class='row'>
		<div class='col-md-3'> <h4> EMPLOYEE LIST </h4> </div>
		<div class='col-md-7'> </div>
		<div class='col-md-2'> <br>
			Filter Year <select name='year' onchange='filter_year(this.value)' >";
				foreach ($year_arr as $key => $value) {
					if($currentyear == $value){ $select = "selected"; } else { $select = ""; }
					echo "<option value='$value' $select> $value </option>";
				} echo "
			</select>
		</div>		
	</div>";

	$query = mysql_query("SELECT 
		secure_clearance.emp_id, reason, date_activefor_resign, date_secure, date_resignation, date_uncleared, date_cleared,
		resignation_letter, generated_clearance, status, emp_type
		FROM `secure_clearance`
		INNER JOIN employee3
		ON secure_clearance.emp_id = employee3.emp_id 
		where emp_type like 'NESCO%' and date_resignation like '$currentyear%' ");
	
	echo 
		"<table class='table' id='secureclearance_table'>
			<thead>
				<tr>					
					<th> NAME </th>
					<th> SECURE </th>
					<th> EFFECTIVE </th>
					<th> CLEARED </th>
					<th> DOC </th>					
					<th> STATUS </th>
					<th> REASON </th>
					<th> PRINT </th>
				</tr>
			</thead>
			<tbody>";

			while($row = mysql_fetch_array($query))
			{ 
				$status 	= $nq->getOneField("current_status","employee3"," emp_id = '$row[emp_id]'");
				$substatus 	= "(".$nq->getOneField("sub_status","employee3"," emp_id = '$row[emp_id]'").")";

				$query_d 	= mysql_query("SELECT * FROM `secure_clearance_deceased`  WHERE emp_id = '$row[emp_id]' ");
				$row_d 		= mysql_fetch_array($query_d);

				if($status == "Active"){
					$status = "<span class='label label-success'> Active $substatus </span>";
				}else{	
					if($substatus == "(Uncleared)"){
						$status = "<span class='label label-danger'> $status $substatus</span>";						
					}else{
						$status = "<span class='label label-warning'> $status $substatus</span>";
					}			
				}			

				if($substatus == "(Cleared)"){
					$view_print = "";
				}else{
					$view_print = "<a href='#' onclick=show_RL('$row[resignation_letter]') data-toggle='modal' data-target='#modal_rl'> view </a>";
				}

				if($row['reason'] == "Deceased" ){
					$view_autho = "<a href='#' onclick=show_RL('$row_d[authorization_letter]') data-toggle='modal' data-target='#modal_rl'> view </a>";
				}else{
					$view_autho = "";
				}

				/*$datecleared = '';
				if($substatus == '(Cleared)') { $datecleared = $nq->changeDateFormat("m/d/Y",$row['date_cleared']); } else { $datecleared = ''; }
*/
				echo "<tr>
					<td> <a href='?p=employee&com=$row[emp_id]'>".ucwords(strtolower($nq->getEmpName($row['emp_id'])))." </a> </td>				
					<td> ".$nq->changeDateFormat("m/d/y",$row['date_secure'])." </td>
					<td> ".$nq->changeDateFormat("m/d/y",$row['date_resignation'])." </td>
					<td> ".$nq->changeDateFormat("m/d/y",$row['date_cleared'])." </td>
					<td> $view_print $view_autho</td>					
					<td> $status </td>
					<td> $row[reason] </td>
					<td>";
					if($row['generated_clearance'] == ""){
						echo "<a href='#' onclick=print_clearance('".$row['reason']."','".$row['emp_id']."') title='Print Clearance'> 
							<img src='images/printer.png' width='18' height='17'>
							</a> ";
					} echo "</td>
				</tr>";
			}  

				echo "
			</tbody>
		</table>"; ?>

		<link href='../datatables/jquery.dataTables.css' rel='stylesheet'/> 
		<script src="../datatables/jquery-1.11.1.min.js" type="text/javascript"></script>
		<script src="../datatables/jquery.dataTables.min.js" type="text/javascript"></script>

		<script>
		$(document).ready(function() {
		    $('#secureclearance_table').DataTable();
		} );
		</script> 

		<?php

}
//REPRINT GENERATED CLEARANCE
else if($_GET['request'] == "div_reprintclearance") // clearance
{ 
	echo "<h4> REPRINT CLEARANCE </h4>";
	$query = mysql_query("SELECT * FROM `secure_clearance` ");

	?>
		<div class="form-group">
			<label>  <span class='rqd'> * </span> Employee Name </label>
			<div class="input-group">
	          	<input type="text" name="empid" id='empid' onkeyup="namesearch_reprint(this.value)" class="form-control" placeholder="Search Employee (Emp. ID, Lastname or Firstname)" value="" autocomplete="off" required="">
	          	<span class="input-group-btn">
	            	<button class="btn btn-info" name="search" >Search &nbsp;<i class="glyphicon glyphicon-search"></i></button>
	          	</span>
	      	</div>
	      	<div class="search-results" style="display:none;"></div>
		</div>

		<input type="hidden" id="scid" name="scid">

		<div class="form-group">
			<label>  <span class='rqd'> * </span> Reason for Clearance Reprint </label>	
			<textarea required name="reasonreprint" id="reasonreprint" cols="47" class="form-control" rows="2" ></textarea>      	
	    </div>	

	    <input type="submit" class="btn btn-primary" id='submit_reprintclearance_btn' onclick="reprint_clearance()" value='Submit'>
	   
	    <input type="submit" class="btn btn-primary" value='View Clearance' id='view_reprintclearance_btn' onclick='get_reason()' disabled> <!-- onclick="show_Clearance()" data-toggle='modal' data-target='#modal_rl'-->
	    <br><br>

		<?php
}
else if($_GET['request'] == "getclearancedetails")
{
	$empid 		= $_POST['empid'];
	$clearance  = mysql_query("SELECT * FROM secure_clearance WHERE emp_id = '$empid' and status = 'Pending' limit 1 ");
	$row 		= mysql_fetch_array($clearance);
	$reason 	= $row['reason'];
	echo $reason;
}

else if($_GET['request'] == "submit_notifyres") //insert to termination
{
	$empid 				= "";
	$dateresignation 	= "";
	$datenotify 		= date("Y-m-d");
	$resignationletter 	= "";
	$addedby 			= $_SESSION['emp_id'];

	$query = mysql_query("INSERT INTO termination
		(`termination_no`,`emp_id`,`date_notify`,`date_resignation`,`resignation_letter`,`added_by`) 
		VALUES
		('','$empid','$datenotify','$dateresignation','$resignationletter','$addedby') ");
	if($query){
		echo "success+Resignation Notification Done!";
	}
}

else if($_GET['request'] == "getCCandBC") //insert to termination
{
	$empid 	= $_POST['empId'];
	
	$query 	= mysql_query("SELECT company_code, bunit_code, dept_code, emp_type from employee3 where emp_id = '$empid' ");
	$row 	= mysql_fetch_array($query);
	$cc 	= $row['company_code'];
	$bc 	= $row['bunit_code'];
	$emptype= $row['emp_type'];
	
	if($emptype == "NESCO" || $emptype = 'NESCO-BACKUP' || $emptype == "NESCO Contractual" || $emptype == "NESCO Regular" || $emptype == "NESCO-PTA" || $emptype == "NESCO-PTP" || $emptype == "NESCO Partimer" || $emptype == "NESCO Regular Partimer" || $emptype == "NESCO Probationary")
	{
		$value = "NESCO";
	}else{
		$value 	= $cc.".".$bc;	
	}

	echo $value;
}
else if($_GET['request'] == "postoutstanding_employeeofthemonth")
{ 
	$month 	= $_POST['month'];
	if($month == "lastyear"){
		$month 	= "December";
		$year  	= date("Y")-1; 
	}else{
		$month 	= $month;
		$year  	= date("Y");
	}
	
	$sql = mysql_query("UPDATE outstanding_month SET month = '$month', year = '$year', status = 'yes' ")or die(mysql_error());	
	if($sql){
		echo "Posting Done!";
	}
}
//generating clearance
else if($_GET['request'] == "insert_secure_clearance")
{	
	//check from secure_clearance
	//insert into secure_clearance
	//update status to employee3 ex RESIGNED (UNCLEARED)

	//NOTES
	// date_activefor_resign// date when the employee inform his resignation and sub_status will change to ACTIVE (For Resignation) 
	// date_secure 			// date when the employee go to the hr ask for clearance and then generate the clearance
	// date_resignation 	// date of resignation or effectivity date
	// date_uncleared		// date when the sub_status change to RESIGNED (UNCLEARED)
	// date_cleared 		// date when the employee submits the fully-signed clearance RESIGNED (CLEARED)

	$addedby 		= $_SESSION['emp_id'];
	$reason 		= $_POST['reason'];	
	$empid 			= explode("*",$_POST['empid']);
	$empid 			= trim($empid[0]);	
	$date_res 		= $nq->changeDateFormat("Y-m-d",$_POST['date_resignation']);
	$date_secure 	= date("Y-m-d");

	//if deceased
	$claimant 		= @$_POST['claimant'];
	$relation 		= @$_POST['relation'];
	$dateofdeath 	= $nq->changeDateFormat("Y-m-d",@$_POST['dateofdeath']);
	$causeofdeath 	= @$_POST['causeofdeath'];
	$status 		= $reason;
	//CHECK
	$select_query 	= mysql_query("SELECT emp_id from secure_clearance where emp_id = '$empid' and date_secure = '$date_secure' ");
	if(mysql_num_rows($select_query) == 0)
	{
		//check date secure vs date resignation
		if($date_secure < $date_res)
		{
			$status = "Active";

			switch($reason) {
				case 'V-Resigned'	: 	$substatus = "For Resignation";
										break;
				case 'Ad-Resigned'	: 	$substatus = "For Resignation";
										break;
				case 'Termination'	:	$substatus = "For End of Contract";
										break;
				case 'Retrenchment' :	$substatus = "For Retrenchment";
										break;
				case 'Retirement' 	:	$substatus = "For Retirement";
										break;
			}

			$dateforactiveresign 	= date("Y-m-d");
			$dateuncleared 			= "";

		}
		else if(($date_secure == $date_res) || ($date_secure > $date_res))
		{
			switch($reason) {
				case 'V-Resigned'	: 	$status = "V-Resigned";
										break;
				case 'Ad-Resigned'	: 	$status = "Ad-Resigned";
										break;
				case 'Termination'	:	$status = "End of Contract";
										break;
				case 'Retrenchment' :	$status = "Retrenched";
										break;
				case 'Retirement' 	:	$status = "Retired";
										break;
			}

			$substatus 				= "Uncleared";
			$dateforactiveresign 	= "";
			$dateuncleared 			= date("Y-m-d");
		}				
             

        //save and move the required documents     
        if(isset($_FILES['resignationletter']['name']))
        {  
        	$letter 	= "../document/resignation/" . $_FILES["resignationletter"]["name"];				
			$array 		= explode(".",$_FILES["resignationletter"]["name"]);
			$fletter 	= "../document/resignation/".trim($empid)."=".date('Y-m-d')."="."Resignation-Letter"."=".date('H-i-s-A').".".$array[1];	
			move_uploaded_file($_FILES["resignationletter"]["tmp_name"],@$fletter);		            
        }   

         if(isset($_FILES['authorizationletter']['name']))
        {  
        	$letter 	= "../document/authorizationletter/" . $_FILES["authorizationletter"]["name"];				
			$array 		= explode(".",$_FILES["authorizationletter"]["name"]);
			$autholetter= "../document/authorizationletter/".trim($empid)."=".date('Y-m-d')."="."Authorization-Letter"."=".date('H-i-s-A').".".$array[1];	
			move_uploaded_file($_FILES["authorizationletter"]["tmp_name"],@$autholetter);		            
        }      

        //insert into table secure clearance
		$insert_query = mysql_query("INSERT INTO `secure_clearance` 
						(`sc_id`, `emp_id`, `reason`, `date_activefor_resign`, `date_secure`, `date_resignation`,`date_uncleared`,`resignation_letter`,`added_by`,`status`)
					 	VALUES  ('','$empid','$reason','$dateforactiveresign','$date_secure','$date_res','$dateuncleared','$fletter','$addedby','Pending')")or die(mysql_error());         

		if($reason == "Deceased")//if reason is deceased insert into this table
		{
			$last_inserted_id = mysql_insert_id();		
			$insert_query2 = mysql_query("INSERT INTO `secure_clearance_deceased` 
							(`scd_id`, `sc_id`, `emp_id`, `claimant`, `relation`, `dateofdeath`, `causeofdeath`,`authorization_letter`) 
						VALUES ('','$last_inserted_id','$empid','$claimant','$relation','$dateofdeath','$causeofdeath','$autholetter') ")or die(mysql_error());
			$status = "Deceased";
		}

		if($reason == "V-Resigned" || $reason == "Ad-Resigned" || $reason == "Retrenchment")
		{
			//get raters of employee
			$slevelingsubordinates = mysql_query("SELECT * FROM leveling_subordinates where subordinates_rater = '$empid' ")or die(mysql_error());
			while($rowsl = mysql_fetch_array($slevelingsubordinates)){
				//employee // subordinate_rater
				//supervisor  // ratee

				//insert into tag for resignation //automatic
				$insert_tag_resign = mysql_query("INSERT INTO `tag_for_resignation` 
								(`tfreg_id`, `ratee_id`, `rater_id`, `added_by`, `date_added`, `tag_stat`) 
						VALUES ('','".$rowsl['subordinates_rater']."','".$rowsl['ratee']."','".$_SESSION['emp_id']."','".date('Y-m-d')."','Pending')")or die(mysql_error());
			}
		}		
	
		//update the current_status and sub_status in employee3 table
		$update = mysql_query("UPDATE employee3 SET current_status = '$status', sub_status = '$substatus' WHERE emp_id = '$empid' ")or die(mysql_error());		
	}

	//get emptype
	$emptype = $nq->getOneField("emp_type","employee3"," emp_id = '$empid' ");
	if($emptype == "NESCO Contractual" || 
		$emptype == "NESCO Regular" ||
		$emptype == "NESCO-PTA" || 
		$emptype == "NESCO-PTP" || 
		$emptype == "NESCO Partimer" || 
		$emptype == "NESCO Regular Partimer" || 
		$emptype == "NESCO-BACKUP" || 
		$emptype == "NESCO Probationary"){

		$emptypes = "NESCO";
	}else{
		$emptypes = "AE";
	}
	
	if($insert_query && $update){
		echo "success+".$emptypes;
	}
}
//UPLOAD SIGNED CLEARANCE
else if($_GET['request'] == "upload_signed_clearance")
{	
	//insert into termination
	//update date_cleared to secure_clearance
	//update status to employee3 ex RESIGNED (UNCLEARED)

	//NOTES
	// date_activefor_resign// date when the employee inform his resignation and sub_status will change to ACTIVE (For Resignation) 
	// date_secure 			// date when the employee go to the hr ask for clearance and then generate the clearance
	// date_resignation 	// date of resignation or effectivity date
	// date_uncleared		// date when the sub_status change to RESIGNED (UNCLEARED)
	// date_cleared 		// date when the employee submits the fully-signed clearance RESIGNED (CLEARED)

	$addedby 		= $_SESSION['emp_id'];
	$remarks 		= $_POST['remarks'];	
	//$status 		= $_POST['status'];	
	$empid 			= explode("*",$_POST['empid']);
	$empid 			= trim($empid[0]);	
	$dateupdate 	= date("Y-m-d");
	$datecleared 	= date("Y-m-d");
	$substatus 		= "Cleared";

	$select 			= mysql_query("SELECT * FROM secure_clearance where emp_id = '$empid' and status = 'Pending' ");
	$rows 				= mysql_fetch_array($select);
	$reason 			= $rows['reason'];
	$resignationletter 	= $rows['resignation_letter'];

	switch($reason) {
		case 'V-Resigned'	: 	$status = "V-Resigned";
								break;
		case 'Ad-Resigned'	: 	$status = "Ad-Resigned";
								break;
		case 'Termination'	:	$status = "End of Contract";
								break;
		case 'Retrenchment' :	$status = "Retrenched";
								break;
		case 'Retirement' 	:	$status = "Retired";
								break;
		case 'Deceased' 	:	$status = "Deceased";
								break;	
	}

	//CHECK
	$date_res = $nq->getOneField("date_resignation","secure_clearance","emp_id = '$empid' "); //WHAT IF DUHAY RECORD??????
	            
    if(isset($_FILES['clearance']['name']))
    {  
    	$clearance 	= "../document/clearance/" . $_FILES["clearance"]["name"];				
		$array 		= explode(".",$_FILES["clearance"]["name"]);
		$fclearance = "../document/clearance/".$empid."=".date('Y-m-d')."="."Clearance"."=".date('H-i-s-A').".".$array[1];	
		move_uploaded_file($_FILES["clearance"]["tmp_name"],@$fclearance);		            
    }  

    $insert = mysql_query("INSERT INTO termination
				(`termination_no`,`emp_id`,`date`,`remarks`,`resignation_letter`,`added_by`,`date_updated` )
		VALUES  ('','$empid','$date_res','$remarks','$resignationletter','$addedby','$dateupdate')");

	$update_secc = mysql_query("UPDATE secure_clearance SET date_cleared = '$datecleared', status = 'Completed' where emp_id = '$empid' and status = 'Pending' ");
	$update_emp3 = mysql_query("UPDATE employee3 SET current_status = '$status', sub_status = '$substatus', clearance = '$fclearance' WHERE emp_id = '$empid' ");	
			
	if($insert && $update_secc && $update_emp3){ 
		echo "success+Employee Successfully Cleared!+success"; 
	}else{
		echo "error+Error Saving!+error";
	}

}	
else if($_GET['request'] == "getEOCdate") //insert to termination
{
	$empid = $_POST['empId'];
	$emptype = $nq->getOneField("emp_type","employee3"," emp_id = '$empid' ");
	$eocdate = $nq->getOneField("eocdate","employee3"," emp_id = '$empid' and (eocdate !='' or eocdate !='0000-00-00') ");
	$eocdate = $nq->changeDateFormat("m/d/Y",$eocdate);

	if($emptype == "Contractual" or $emptype == "NESCO-BACKUP" or $emptype == "NESCO Contractual" or $emptype == "PTA" or $emptype == "PTP" or $emptype == "Probationary" or $emptype == "NESCO Probationary" or $emptype == "Seasonal" or $emptype == "NESCO-PTA" or $emptype =="NESCO-PTP" or $emptype  =="Back-Up" or $emptype == "Partimer"){
		echo "ok+".$eocdate;
	}else{
		echo "error";
	}	
}

else if($_GET['request'] == "getEPAS") //insert to termination
{
	$empid = $_POST['empId'];
	
	$query 	= mysql_query("SELECT epas_code, numrate, descrate, emp_type, eocdate FROM employee3 
			INNER JOIN appraisal_details
			ON
			employee3.record_no = appraisal_details.record_no
			WHERE employee3.emp_id = '$empid'  and appraisal_details.emp_id = '$empid' ")or die(mysql_error());

	$row 	= mysql_fetch_array($query);
	$epas 	= $row['numrate']." [".$row['descrate']."]";

	$select = mysql_query("SELECT * FROM secure_clearance where emp_id = '$empid' and status = 'Pending' ");
	$rows = mysql_fetch_array($select);
	$reason = $rows['reason'];

	$newreason = "";
	if($reason == "V-Resigned"){
		$newreason = "V-Resigned";
	}else if($reason == "Ad-Resigned"){
		$newreason = "Ad-Resigned";
	}else if($reason == "Retrenchment"){
		$newreason = "Retrenched";	
	}else if($reason == "Retirement"){
		$newreason = "Retired";
	}else if($reason == "Deceased"){
		$newreason = "Deceased";
	}

	if(mysql_num_rows($query) == 0){
		echo "0";
	}else{
		echo "
		<div class='form-group'>
			<label>  <span class='rqd'> * </span> EPAS </label>	
			<input type='text' required id='epas' class='form-control' disabled value='$epas'>		
	    </div>

	    <div class='form-group'>
			<label>  <span class='rqd'> * </span> Status </label>	
			<input type='text' required id='status' id='status' class='form-control' disabled value='$newreason (Cleared)'>	
			 	
	    </div>";	

	    /*
	<select  class='form-control' name='status' id='status' required>";
				
				if($row['emp_type'] == "Regular" || $row['emp_type'] == "NESCO Regular" || $row['emp_type'] == "Regular Partimer" || $row['emp_type'] == "NESCO Regular Partimer"){
					echo "<option value='Resigned' selected> RESIGNED (CLEARED) </option>";
					echo "<option value='End of Contract'> EOC (CLEARED) </option>";
				}else{
					echo "<option value='Resigned' > RESIGNED (CLEARED) </option>";
					echo "<option value='End of Contract' selected> EOC (CLEARED) </option>";
				} echo "

			</select>  
	    */

	}	
}

else if($_GET['request'] == "check_employee_secure_clearance") 
{
	//checking so that the employee cannot secure clearance after asking just recently
	$empid 				= trim($_POST['empId']);

	$query = mysql_query("SELECT emp_id from secure_clearance where emp_id = '$empid' and status = 'Pending' ");
	
	if(mysql_num_rows($query) == 0){
		echo "success";
	}else{
		echo "error";
	}
}

else if($_GET['request'] == "check_employee_age") 
{	
	$empid 	= trim($_POST['empId']);
	$query 	= mysql_query("SELECT birthdate from applicant where app_id = '$empid'  ");
	$row 	= mysql_fetch_array($query);

	date_default_timezone_set('Asia/Manila');
    function getAge( $dob, $tdate )
    {
        $age = 0;
        while( $tdate >= $dob = strtotime('+1 year', $dob)){
                ++$age;
        }return $age;
    }

    $datebirth = $row['birthdate'];
    $dob = strtotime($datebirth);		
    $now = date('Y-m-d');
    $tdate = strtotime($now);	
    $age= getAge( $dob, $tdate );
     	 
	if($datebirth !=""){ $age = $age; }  

	if($age >= 60){
		echo "ok";
	}else{
		echo "error";
	}	
}

else if($_GET['request'] == "updateLodging") //update lodging 05042020
{
	$empid 		= $_POST['empid'];
	$lodging 	= $_POST['lodging'];
	
	//echo "UPDATE employee3 set lodging = '$lodging' WHERE emp_id = '$empid' ";
	
	$query 		= mysql_query("UPDATE employee3 set lodging = '$lodging' WHERE emp_id = '$empid' ")or die(mysql_error());
	if($query){
		$empName 	= $nq->getEmpName($empid);
		$log  		= date('Y-m-d H:i:s')."| $empid | $empName | $lodging | Updatedby: $entryby | ".$nq->getEmpName($entryby)." \r\n";
		$logDir   	= "../logs/lodging/"; 
		$filename 	= "lodging_update-";		
		$nq->writeLogs($log,$logDir,$filename);
		echo "success";
	}
}

else if($_GET['request'] == "div_taggingstatus")
{  ?>

	<div id='div_uploadclearance'>
		<h4> TAGGING OF STATUS ( No Clearance & Resignation Letter ) </h4>

		<form action='' name='uploadSignedClearance' id='uploadSignedClearance' method="POST" enctype="multipart/form-data"> 	
			
			<div class="form-group">
				<label>  <span class='rqd'> * </span> Employee Name </label>
				<div class="input-group">
		          	<input type="text" name="empid" id='empid' onkeyup="namesearch_2(this.value)" class="form-control" placeholder="Search Employee (Emp. ID, Lastname or Firstname)" value="" autocomplete="off" required="">
		          	<span class="input-group-btn">
		            	<button class="btn btn-info" name="search" >Search &nbsp;<i class="glyphicon glyphicon-search"></i></button>
		          	</span>
		      	</div>
		      	<div class="search-results" style="display:none;"></div>
			</div>

			<label> Status  </label>
			<select class="form-control">
				<option> </option>
				<option> </option>
			</select>
				
			<div class="form-group">
				<label>  <span class='rqd'> * </span> Remarks </label>	
				<textarea required name="remarks" id="remarks" cols="47" class="form-control" rows="2" ></textarea>      	
		    </div>	    

		    <div class="form-group">
				<label>  <span class='rqd'> * </span> Signed Clearance (Scanned) </label>	
				<input type="file" required accept="image/*"  name="clearance" id="clearance" class="btn btn-default" onchange="check(this.id,'imgclearance')"  size="50" >	
		    </div>

		    <input type="submit" class="btn btn-primary" id='submit_printclearance_btn' value='Submit'>
		</form>

		<br><i> Note: 
		<br><span class='rqd'> * </span> Required fields. 	
		<br>EPAS grade is a REQUIREMENT. </b> </i> <br><br>

	</div>

	<?php
}
else if($_GET['request'] == "record_clearance_reprint") //insert to termination
{
	$scid 				= $_POST["scid"];
	$reason 			= $_POST["reason"];

	$query = mysql_query("INSERT INTO secure_clearance_reprint
		(`scr_id`,`sc_id`,`reason`,`date`,`generatedby`) 
		VALUES
		('','$scid','$reason','".date("Y-m-d H:i:s")."','".$_SESSION['emp_id']."') ");

	if($query){
		echo "ok";
	}
}

else if($_GET['request'] == "confirmed_Change_Status") 
{
	$empid = $_POST['empid'];
	$query = mysql_query("UPDATE employee3 
						SET current_status = 'End of Contract', 
						sub_status = 'Uncleared' 
						WHERE emp_id = '$empid' ")or die(mysql_error());
	if($query){
		echo "success";
	}else{
		echo "error";
	}
}

else if($_GET['request'] == "getProbiDates") 
{
	$empid = $_POST['empid'];
	$query = mysql_query("SELECT startdate, eocdate 
						FROM employee3 						
						WHERE emp_id = '$empid' ")or die(mysql_error());
	$row = mysql_fetch_array($query);

	//get the last eocdate for the new startdate
	$startdate = strtotime($row['eocdate']);
	$startdate = strtotime("+1 day", $startdate);
	$startdate = date('m/d/Y', $startdate);

	$eocdate = strtotime($startdate);
	$eocdate = strtotime("+30 days", $eocdate);
	$eocdate = date('m/d/Y', $eocdate);

	$datetoday = date("Y-m-d");
	$nstartdate= $nq->changeDateFormat("Y-m-d",$startdate);

	if($datetoday < $nstartdate){// 20 <= 21 = true = dli pwede 22 < 21 = false == pwede
		echo "true*".$startdate."*".$eocdate;
	}else{
		echo "false*".$startdate."*".$eocdate;
	}
}

else if($_GET['request'] == "add_new_employment_probi")
{
	//pass values
	$empid 		= $_POST['empid'];
	$startdate 	= $_POST['startdate'];
	$eocdate 	= $_POST['eocdate'];

	if(isset($startdate) && isset($eocdate))
	{
		$sql 			= mysql_query("SELECT * FROM employee3 WHERE emp_id = '$empid' and current_status = 'Active' ") or die(mysql_error());
		$old_data 		= mysql_fetch_array($sql);
		$currentstatus 	= "End of Contract";
		$emptype 		= $old_data['emp_type'];		
		$recordno 		= $old_data['record_no'];

		// insert the old contrct to the employment record table
		$employmentrecord_ = mysql_query(
			"INSERT
				INTO
			 employmentrecord_
				(						
					poslevel,
					sub_status,
					emp_id,
					emp_no,
					emp_pins,
					company_code,
					bunit_code,
					dept_code,
					section_code,
					sub_section_code,
					unit_code,
					barcodeId,
					bioMetricId,
					payroll_no,
					names,
					startdate,
					eocdate,
					emp_type,
					reg_class,
					current_status,
					duration,
					job_cat,
					emp_cat,
					positionlevel,
					position,
					lodging,
					pos_desc,
					remarks,
					epas_code,
					contract,
					permit,
					clearance,
					uniform_contract,
					coe,
					comments,
					date_updated,
					updatedby,
					kra_code,
					pcc
				) VALUES (	
					'".$old_data['poslevel']."',
					'".$old_data['sub_status']."',
					'".$empid."',
					'".$old_data['emp_no']."',
					'".$old_data['emp_pins']."',						
					'".$old_data['company_code']."',
					'".$old_data['bunit_code']."',
					'".$old_data['dept_code']."',
					'".$old_data['section_code']."',
					'".$old_data['sub_section_code']."',
					'".$old_data['unit_code']."',
					'".$old_data['barcodeId']."',
					'".$old_data['bioMetricId']."',
					'".$old_data['payroll_no']."',
					'".$old_data['names']."',
					'".$old_data['startdate']."',
					'".$old_data['eocdate']."',
					'".$old_data['emp_type']."',
					'".$old_data['reg_class']."',
					'".$currentstatus."',
					'".$old_data['duration']."',
					'".$old_data['job_cat']."',
					'".$old_data['emp_cat']."',
					'".$old_data['positionlevel']."',
					'".$old_data['position']."',
					'".$old_data['lodging']."',
					'".$old_data['position_desc']."',
					'".addslashes($old_data['remarks'])."',
					'".$old_data['epas_code']."',
					'".$old_data['contract']."',
					'".$old_data['permit']."',
					'".$old_data['clearance']."',
					'".$old_data['uniform_contract']."',
					'".$old_data['coe']."',
					'".$old_data['comments']."',
					'".$old_data['date_added']."',
					'".$old_data['added_by']."',
					'".$old_data['kra_code']."',
					'".$old_data['pcc']."'	
				)"
		) or die(mysql_error());

 		//get record_no from newly inserted employmentrecord_
		$sql 			= mysql_query("SELECT record_no FROM employmentrecord_ WHERE emp_id = '".$empid."' ORDER BY record_no DESC") or die(mysql_error());
		$new_rno 		= mysql_fetch_array($sql);

		// appraisal details
		$sql 			= mysql_query("SELECT record_no FROM appraisal_details WHERE record_no = '".$old_data['record_no']."' and emp_id = '".$empid."' ") or die(mysql_error());
	    $c_appdetails 	= mysql_num_rows($sql);
		
		//if true updates the appraisal_details to new record_no
		if($c_appdetails > 0){
			mysql_query("UPDATE appraisal_details SET record_no = '".$new_rno['record_no']."' WHERE record_no = '".$old_data['record_no']."' and emp_id = '".$empid."'  ") or die(mysql_error());
		}

		// witness
		$sql 			= mysql_query("SELECT rec_no FROM employment_witness WHERE rec_no = '".$old_data['record_no']."' ") or die(mysql_error());
		$c_empwitness 	= mysql_num_rows($sql);		
		//update the employment_witness if there is a  contract
		if($c_empwitness > 0){
			mysql_query("UPDATE employment_witness SET rec_no = '".$new_rno['record_no']."' WHERE rec_no = '".$old_data['record_no']."' ") or die(mysql_error());
		}

		$sql 			= mysql_query("SELECT record_no FROM tag_clearances WHERE record_no = '".$old_data['record_no']."' ") or die(mysql_error());
		$c_tag 			= mysql_num_rows($sql);		
		//tag_clearances
		if($c_tag > 0){
			mysql_query("UPDATE tag_clearances SET record_no = '".$new_rno['record_no']."' WHERE record_no = '".$old_data['record_no']."' ") or die(mysql_error());
		}

		if($emptype == "Contractual" || $emptype == "Partimer" || $emptype == "PTA" || $emptype == "PTP")
        {
        	$new_emptype = "Probationary";
        }else if($emptype == "NESCO-PTA" || $emptype == "NESCO-PTP" || $emptype == "NESCO Contractual"){
        	$new_emptype = "NESCO Probationary";
        }

        $startdate  = $nq->changeDateFormat('Y-m-d',$_POST['startdate']);
		$eocdate  	= $nq->changeDateFormat('Y-m-d',$_POST['eocdate']);		
		$dateadded 	= date('Y-m-d');
		$addedby 	= $_SESSION['emp_id'];


		//update employee3- startdate, emp_type, reg_class
		$employee3 = mysql_query("UPDATE 
			employee3 SET 
			emp_type  = '$new_emptype',			
			startdate = '$startdate',
			eocdate   = '$eocdate',
			epas_code = '',	
			clearance = '',
			remarks   = '',
			contract  = '',
			permit 	  = '',
			added_by  = '$addedby',
			date_added= '$dateadded',
			updated_by= '',
			date_updated = '',
			comments  = '',
			duration  = '',
			tag_as 	  = ''			
		WHERE emp_id  = '$empid' AND record_no = '$recordno'
		") or die(mysql_error());		

		if($employee3 && $employmentrecord_)
		{
			//SAVE LOGS	
			$name 			= $nq->getEmpName($empid);
			$activity 		= "Add New Probationary ".$name;				
			$nq->savelogs($activity,date("Y-m-d"),date("H:i:s"),$_SESSION['emp_id'],$_SESSION['username']);

			echo "success";
		}else{
			echo "error";
		}	
	}
}

else if($_GET['request'] == "getemployee_EOCdate") //renewal.php //probationary.php
{
	$empid 		= $_POST['empid'];		
	$eocdate 	= $nq->getOneField("eocdate","employee3"," emp_id = '$empid' and (eocdate !='' or eocdate !='0000-00-00') ");
	
	$datetoday  = date("Y-m-d");

	if($datetoday <= $eocdate){// 20 <= 21 = true = dli pwede 22 < 21 = false == pwede
		echo "true";
	}else{
		echo "false";
	}
}
else if ($_GET['request'] == "positionLevel") 
{
	$position_no = $_POST['position_no'];
	
	$query = mysql_query("SELECT lvlno FROM position_leveling WHERE poslevel_no = '".$position_no."'") or die(mysql_error());
	echo $position_no = mysql_fetch_array($query)['lvlno'];
}

else if($_GET['request'] == "findEmployeeSupervisor")
{
	$key = mysql_real_escape_string($_POST['str']);
  	$val = "";
  	$empname = mysql_query("SELECT employee3.emp_id, name, current_status
			  FROM `employee3`
			  INNER JOIN users ON employee3.emp_id = users.emp_id
			  WHERE current_status = 'Active' AND usertype = 'supervisor' 
			  AND (name like '%$key%' or employee3.emp_id = '$key')  order by name limit 10")or die(mysql_error());

  	while($n = mysql_fetch_array($empname)){
		$empId = $n['emp_id'];
		$name  = ucwords(strtolower($n['name']));
		$status= $n['current_status'];

		if($val != $empId){			
			echo "<a class = \"nameFind\" href = \"javascript:void\" onclick='getEmpId_2(\"$empId*$name*$status\")'>[ ".$empId." ] = ".$name."</a></br>";
		}
		else{
			echo "<a class = \"afont\" href = \"javascript:void\">No Result Found</a></br>";	
		}
  	}
} 

else if($_GET['request'] == "getLevel")
{
	$position = $_POST['position'];
	$level 	  = $nq->getOneField("lvlno","position_leveling","position_title = '$position' ");
	echo $level;
}