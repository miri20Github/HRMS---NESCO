## PROJECT TITLE

	HRMS NESCO

## DESCRIPTION
	
	HRMS NESCO is a project intended to monitor all employee-related information of all NESCO agency employees. Significant modules are present here namely: Recruitment, Employee Masterfile, Contracts, Blacklist, EOC Appraisal, Job Transfer, Resignation/ Termination, Regularization, Setup, Reports and Document Management of 201 Files. 

	
GETTING STARTED
	
## DEPENDENCIES
	
	Prerequisites prior to HRMS NESCO installations:
	> Preferable Windows 7 up
	> Mozilla Firefox or Google Chrome
	> Stable network connection


## INSTALLING

	Upload nesco folder under HRMS folder.
	Set the file permission to public *777*	
	Other than that, no more changes are to be made after copying the nesco folder.

## EXECUTING PROGRAM

	To run HRMS NESCO
	> Open browser and access this link 172.16.161.34:8080/hrms/nesco
	> Enter the user credentials (username and password)

## HELP

	Most common problems that may occur during the actual using of the system is the network 	connection. Other than that, errors may occur during transactions are semantic or 		syntactic error and must be resolve immediately.

## AUTHOR
	
	Developer		: Mary Ann Caliao, Zoren Ormido
	System Analyst	: Jacquilyn Curaza-Bulac, Lenalin Gambe
	Supervisors		: Maria Cristina Evarle, Jeneffer Caren
	Project Manager	: Maria Neliza U. Fuertes
	
## VERSION HISTORY

	VERSION 1.0
		> Initial Release and Implementation on August 2017
		

	VERSION 2.0
		> Code Updated on September 2022

## TOOLS & IDE & LANGUAGE
	
	#LANGUAGE
	PHP 4.0.4 - PHP 5.2.0	
	HTML5
	CSS
	Bootstrap
	JavaScript
	jQuery

	#DATABASE
	MYSQL (PHPMyadmin / MySQL Query Browser)
	
	#EDITOR
	Notepad++ / Sublime / Visual Studio Code

## MODULES
	
	Recruitment
		Final Completion
		Hiring
		Deployment
		Searching of Applicant	

	Placement
		Job Transfer
		Blacklisting
		Contracts - EOC and Probi Processing
		Performance Appraisal
		Masterfile
		SIL Monitoring
		Regularization
		Clearance Processing / Resignation
		Reports
		Setup
		
	Other Links
		Employee Benefits
		Timekeeping

## CODE
	
	This project is not using MVC, so bear with the code structuring.

	Under NESCO folder, the following folders are all assets:
	(Pardon for some redundancy)
	> alert
	> css
	> js
	> images
	> datatables
	> assets
	
	
	> functions
	  Contains the separated load functions
		
		functions_dashboard.php
		- A file that contains the functions used by most of the dashboard files
		  under dashboard folder.
		
		function_nesco.php
		- A file that contains the functions used by most of the nesco_employees files 
		  under nesco_employees folder.
	
	> pages
	  Folder names according to the side menu names and functions.
		
		> contracts
		  add_new_employment.php
		  - This page is where a new contract is created before inserting the details 
		    in employee3. All the related information here like company details, employee type,
		    position, position level, and among others.

		  renewal.php
		  - This page lists the NESCO Contractual employees ready for probationary processing.
		    In order to proceed, passing EPAS and for Renewal remarks are the requirements.

		  probationary.php
		  - This page lists the NESCO Probationary employees ready for processing to
		    be regular. In order to proceed, passing EPAS is a requirement.

		  reprint.php
		  - This page allows the reprinting of Contract and Permit to work when 
		    unexpected error of entry is encountered.

		  tag_for_resignation.php
		  - This page is to tag employee for resignation so that the supervisor will see
		    the employees name for EPAS rating.

		  reapplycontracts.php
		  - This page is used to fill up the company details, position, employee type, 
		    position level and other related information of a reapply employee.
		    Only EOC's or Resigned employees can be reapplied. Hence, this link will
		    be eventually removed since creating of contracts should all be in the
		    recruitment hiring facility.


		> dashboard
		  birthdaytoday.php
		  - This page contains the list of NESCO employees who has birthday today.

		  dashboard.php
		  - This page contains the sub menus of the side bar links.

		  dashboard_tables.php
		  - This page is created to reduce code redundancy or preventing of repeating codes 
		    with probably has the same purpose. This file is called in the index.php.
		    The variable $tblid is the key.

		  due_contracts.php
		  - This page filters the report for NESCO employees whose contract are not renewed 
		    yet.

		  home.php
		  - This page controls the displays in the dashboard, from the list to the charts
		    and to some other links.

		  nobenefitsno.php
		  - This is the page that displays the list of employees who has no benefits no, 
		    like for instance, no Philhealth no., no SSS number, no Pagbig MID number, 
		    no Pagibig RTN

		  search_id.php
		  - This is the page for searching employees and display the employee number and pins.
		    This page is used by the ID incharge for ID printing. The incharge will copy
		    the emp num and emp pins.

		
		> entries
		  add_blacklist.php
		  - This page is to add a new blacklist. Applicant, employee or even none of the two
		    can be added to blacklist for future reference.
 
	   	  add_employee.php
		  - This page is used only by the encoder for updating purposes. Other than that
		    it must be restricted.

		  add_regular.php
		  - This page is to create a new regularization of NESCO employee.
		 

		  new_resignation_process.php
		  - This is the clearance processing. Other used file is the functionquery.php

	 	  resignations.php
		  - This is the old adding of resignation record before clearance processing is 
		    created.

		  searchfor_transfer.php
		  - This page is searching of employees for transfer.

 		  list_for_transfer.php
		  - This page is displayes the job transfer details after searching of employees.


		> nesco_employees
		  nesco_sil.php
		  - This page is the monitoring of SIL, no SIL, 5 days SIL, 7 days SIL.
		    HRD updates the SIL according to the enough required contract history
		
		  nesco_terminations.php
		  - This page is a list of terminations.

		  nesco_transfers.php
		  - This page is a list of job transfers.

		  nesco_tables.php
		  - This page a combination of other monitoring list of tables.


		> profile
		  clearanceview.php
		  - A file that previews the clearance in the contract details.
		  
		  employee_appraisal.php
		  - A file that displays the performance appraisal details in the contract details link.
		  
		  employee_details.php
		  - A file that contains the profile of employee. This file calls the 							    employee_information_details.php
		 
		  employment_details.php
		  - A file that displays the current contract viewing details.
 
		  employment_details.php
		  - A file that displays the previous contract viewing details.

		  
		> recruitment
		  appToBeHired.php
		  - A file that contains the list of applicants to be hired.
		
		  empforDeployment.php
		  - A file that contains the list of applicants for deployment.
		  
		  finalReq.php
		  - A file that contains the list for final completion. 
		    All important requirements for hiring will be uploaded here.
		
		  homeDashboard.php
		  - A file that contains the dashboard of the recruitment menu.

		  newlyDeployed.php
		  - A file that contains the list of newly deployed employees.
	
		  newlyHired.php
		  - A file that contains the list of newly hired applicants. 
		    When hiring is done, there records are now save in employee3 table

		> reports			
		  report_benefits.php
		  - A file that filters the report of benefits information of NESCO 
		    that includes the sss no, philheath no, pagibig mid, pagibig rtn.
		    This file uses the companydetails.php for department filtering.

		  report_birthday.php
		  - A report that generates the employees filter by department.

		  report_employeeStatus.php
		  - A report that generates the current status of employees filters also by department.
		
		  report_statistics.php
		  - A report that generates the statistics of NESCO employees filters by department.
		    
		  report_username.php
		  - A report that generates the usernames of NESCO employees.

		  report_termination.php
		  - A report that generate the excel and pdf file for incoming NESCO employees for 
		    End of Contract.
		
		  report_termination_pdf.php
		  - A pdf file generated by the termination report contains the 
		    Notice of Termination for NESCO Contractual employees.

		  report_yearsInService.php
		  - A report that generates the year in service of NESCO employees.
		
		  report_qbe.php
		  - A report that contains the Query By Example type of data filtering. 
		    Results vary according to what is set and filter in the conditions.

		  report_qbe_xls.php
		  - The excel file generated by Query By Example.
		
		  excel_reports.php
		  - A queries and functions used by all report pages. Navigate the file thru request code.
			
		  report_js.js
		  - A javascript file that contains the javascript functions used by most reports pages.
		    

		> search
		  message.php
			- A file that contains the code of the NESCO messages menu. 
			  Message link is found in the header portion.

		  search_applicant.php
			- A file that contains the code of searching applicant lastname and firstname. 
			  Employees can still be search here. This link is found under Recruitment 
			  in the side bar menu.

		  search_employee.php
			- A file that contains the code of the seaching of employee names.
			  Search constraint value is "Lastname, Firstname Middlename". 
			  Anything unknown in between could be filled with percentage symbol "%".
			  The link for this file is found in the NESCO Placement side bar menu.
		
		> setup
		  changepassword.php
			- A file that contains the change password facility.

		  changeusername.php
			- A file that contains the change username facility.
		
		  setup_users.php
			- A file that contains the management of users.
		
		  setup_update_jobtrans.php
			- A file that contains the updating of jobtrans / or processing the 
			  unprocess job trns to effect the transfer based on effectivity.

		  setup_subordinates.php
			- A file that contains the setup of subordinates functionality.
			  This file allows single and multiple setup as well as removing of subordinates.

		  setup_salaryno.php
			- A file that allows user to setup salary no. Usually the NESCO payroll incharge 
			  do this job. The payroll no is from their NESCO Payroll System and
			  the salary no always starts with NES then a series of number.

		  
		  setup_new_user.php
			- A file that setup new user access.
			
		  save_new_user.php
			- A file that save the new user access in the database.

		  upload_remittances.php
			- This file is created for NESCO to upload remittances for the EBM deductions 
			  monitoring.
			


	> other files

	  ajax.php 
		- An important file used by most of the pages that uses ajax functions.
		  Trace the code through request values.

    	  companydetails.php
		- A file that contains the company structure dropdown from company down to unit. 
		  This is mostly used in the reports filtering in reports folder.

	  config.php
		- A native database configuration. 

	  configs.php
		- A class of database configuration.

	  connection.php
		- A file that contains the inclusions of configuration files and functions.
	
 	  employee_information_details.php
		- A file used by employee_details additional information.

	  footer.php
		- Contains most of the javascript functions.
	
	  frame.php
		- A main file that holds the side bar menu, top menu and others. 

	  functionquery.php
		- The main ajax and functions file that contains most request made by other page.
	
	  get_contract_duration.php
		- A class of that computes employees years of service.

	  index.php
		- The file that controls the linkage of the pages thru a $p variable.
	
	  login.php
		- A login page.

	  logout.php
		- A logout page.

	  newqueries.php
		- A class of functions mostly being used by all nesco pages.

	  queries.php
		- A class of functions mostly used in the NESCO recruitment pages.

	  download_attachments.php
		- A file used in messages.php that downloads the attachments being sent.
	 

## DATE
	
	Last Documentation Update: September 21, 2022