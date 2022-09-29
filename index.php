<?php
include('connection.php');
mysql_set_charset("UTF-8");
session_start();

if(!@$_SESSION['emp_id']){
	include 'login.php';
	die();

} else {
     
    if( $nq->checking_access('002') >= 1 ){ // 002 access code of nesco link //03292022 miri
    } 
    else {
    	if($_SESSION['usertype'] != "nesco"){
			header("Location: ../../hrms/index.php");
		}
	}

	$employeetype = "emp_type IN ('NESCO-BACKUP','NESCO Contractual','NESCO Partimer','NESCO-PTA','Promo-NESCO','NESCO-PTP','NESCO Regular','NESCO Probationary','NESCO Regular Partimer') ";
	
	//pages
	$p = @$_GET['p'];

	if($p == 'dashboard'):
		# DASHBOARD
		$page 	= 'dashboard/dashboard.php';
		$title 	= ''; 
	elseif($p == 'blacklists') :
		# code...
		$tblid  = "blacklists";
		$title 	= 'BLACKLISTED EMPLOYEES';
		$page 	= 'nesco_employees/nesco_tables.php';	
	
	elseif($p == 'jobtransfers') :
		# code...
		$page 	= 'nesco_employees/nesco_transfers.php';
		$title 	= 'JOB TRANSFER LIST';
	elseif($p == 'masterfile') :
		#updated 09172022 by miri
		$tblid  = "masterfile";
		$title 	= 'NESCO EMPLOYEE MASTERFILE';
		$page 	= 'nesco_employees/nesco_tables.php';	
		
	elseif($p == 'nescoregulars'):
		#updated 09172022 by miri
		$tblid  = "nescoregulars";
		$title 	= 'NESCO REGULARS EMPLOYEES';		
		$page 	= 'nesco_employees/nesco_tables.php';	
			
	elseif($p == 'benefits') :
		#updated 09172022 by miri
		$tblid  = "benefits";
		$title 	= 'NESCO EMPLOYEE BENEFITS NUMBER MASTERFILE';	
		$page 	= 'nesco_employees/nesco_tables.php';		

	elseif($p == 'termination') :
		# code...
		$page 	= 'nesco_employees/nesco_terminations.php';
		$title 	= 'TERMINATION LIST ';
	elseif($p == 'silmonitor'):
		# code...
		$page  	= 'nesco_employees/nesco_sil.php';
		$title 	= '';		
	elseif($p == 'reprint'):
		# code...
		$page 	= 'contracts/reprint.php';
		$title 	= '';
	elseif($p == 'reapply'):
		# code...
		$page  	= 'contracts/reapply_contracts.php';
		$title 	= '';
	elseif($p == 'employment'):
		$page 	= 'contracts/add_new_employment.php';
		$title 	= '';	
	

	// my pages is here -> natsu
	// elseif($p == 'renewal'): //deleted september 16, 2022 miri
	// 	$page 	= 'contracts.php';
	// 	$title 	= '';		
	// elseif($p == 'EOC'):
	// 	$page = 'dashboard/process_eoc.php';
	// 	$title = '';
	elseif($p == 'tag_resignation'):
		$page = 'contracts/tag_for_resignation.php';
		$title = '';
	// end

	// recruitment module coded by natsu - >
	elseif($p == "finalReq"):
		$page = "recruitment/finalReq.php";

	elseif($p == "appToBeHired"):
		$page = "recruitment/appToBeHired.php";

	elseif($p == "newlyHired"):
		$page = "recruitment/newlyHired.php";

	elseif($p == "empForDeployment"):
		$page = "recruitment/empForDeployment.php";

	elseif($p == "newlyDeployed"):
		$page = "recruitment/newlyDeployed.php";

	elseif($p == "homeDashboard"):
		$page = "recruitment/homeDashboard.php";

	// end here


		
	### entries starts here	
	elseif($p == 'employee-add'):
		$page   = 'entries/add_employee.php';
		$title 	= '';
	elseif($p == 'blacklists-add'):
		$page   = 'entries/add_blacklist.php';
		$title 	= '';
	elseif($p == 'resignation-add'):
		$page   = 'entries/resignations.php';
		$title 	= '';
	elseif($p == 'transfers-add'):
		$page 	= 'entries/searchfor_transfer.php';
		$title  = '';
	elseif($p == 'transfers'):
		$page 	= 'entries/list_for_transfer.php';
		$title  = '';
	elseif($p == 'edittransfers'):
		$page 	= 'jobtransfer_edit.php';
		$title  = '';
	elseif($p == 'viewtransfers'):
		$page 	= 'preview_jobtrans.php';
		$title  = '';
	elseif($p == 'updatejobtrans'):
		$page 	= 'setup/setup_update_jobtrans.php';
		$title  = '';	
	elseif($p == 'toregular'):
		$page 	= 'entries/add_regular.php';
		$title  = '';
	elseif($p == 'message'):
		$page 	= 'search/message.php';
		$title  = '';
	
	### reports starts here
	elseif($p == 'termination_reports'):
		$page 	= 'reports/report_termination.php';
		$title 	= '';
	elseif($p == 'birthday_reports'):
		$page 	= 'reports/report_birthday.php';
		$title 	= '';
	elseif($p == 'employee_benefits_report'):
		$page 	= 'reports/report_benefits.php';
		$title 	= '';
	elseif($p == 'username_report'):
		$page 	= 'reports/report_username.php';
		$title 	= '';
	elseif($p == 'employeestatus'):
		$page 	= 'reports/report_employeeStatus.php';
		$title 	= '';
	// elseif($p == 'employee_statistics'):
	// 	$page 	= 'reports/report_statistics.php';
	// 	$tite 	= '';
	elseif($p == 'employee_statistics'):
		$page 	= 'reports/report_statistics.php';
		$tite 	= '';	
	elseif($p == 'qbe'):
		$page 	= 'reports/report_qbe.php';
		$tite 	= '';
	elseif($p == 'dueContracts'):
		$page 	= 'dashboard/due_contracts.php';
		$tite 	= '';
	elseif($p == 'yearInService'):
		$page 	= 'reports/report_yearsInService.php';
		$tite 	= '';	
		
	### dashboard
	elseif($p == 'birthdaytoday'):
		$page 	= 'dashboard/birthdaytoday.php';
		$title	= '';
	elseif($p == 'newemployees'):

		#updated 09172022 by miri
		$tblid  = "newemployees";
		$title 	= 'NEW NESCO EMPLOYEES';
		$page 	= 'dashboard/dashboard_tables.php';

	elseif($p == 'newblacklist'):

		#updated 09172022 by miri
		$tblid  = "newblacklist";
		$title 	= 'NEW BLACKLISTED EMPLOYEES';
		$page 	= 'dashboard/dashboard_tables.php';

	elseif($p == 'newjobtrans'):

		#updated 09172022 by miri
		$tblid  = "newjobtrans";
		$title 	= 'NEW JOB TRANSFERS';
		$page 	= 'dashboard/dashboard_tables.php';

	elseif($p == 'statistics_details'):

		#updated 09172022 by miri
		$tblid  = "statistics_details";
		$title 	= 'STATISTICS DETAILS';
		$page 	= 'dashboard/dashboard_tables.php';
		
	elseif($p == 'searchID'):
		$page 	= 'dashboard/search_id.php';
		$title 	= '';
	elseif($p == 'nobenefits'):
		$page 	= 'dashboard/nobenefitsno.php';
		$title 	= '';	
		
	###  setup here
	elseif($p == 'salnumsetup'):
		$page 	= 'setup/setup_salaryno.php';
		$tite 	= '';	
	elseif($p == 'subordinates'):
		$page 	= 'setup/setup_subordinates.php';
		$tite 	= '';	
	elseif($p == 'useraccounts'):
		$page 	= 'setup/setup_new_user.php';
		$tite 	= '';
	elseif($p == 'manageuseraccounts'):
		$page 	= 'setup/setup_users.php';
		$title 	= '';
	elseif($p == 'changeusername'):
		$page 	= 'setup/changeusername.php';
		$title 	= '';
	elseif($p == 'changepassword'):
		$page 	= 'setup/changepassword.php';
		$title 	= '';	
			
	### search
	elseif($p == 'searchemployee'):
		$page 	= 'search/search_employee.php';
		$title  = '';
	elseif($p == 'searchApp'):
		$page 	= 'search/search_applicant.php';
		$title  = '';	

	//05292020
	elseif($p == 'clearance-secure'):
		$page   = 'entries/new_resignation_process.php';
		$title 	= '';
	elseif($p == 'renewal-list'):
		$page   = 'contracts/renewal.php';
		$title 	= '';
	elseif($p == 'probationary-list'):
		$page   = 'contracts/probationary.php';
		$title 	= '';	
		
		
	### view profile
	elseif($p == 'employee'):
		$page 	= 'profile/employee_details.php';
		$tite 	= '';						
	else:	
		$page 	= 'dashboard/home.php';
		$title 	= 'Home';	
	endif;
		
	include('frame.php');
}	
?>