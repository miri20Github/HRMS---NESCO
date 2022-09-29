<?php
$dashboard 		= $_GET['db'];

if($dashboard == 'nescoemployee')
{ 
	echo  '
	<!-- /. ROW  --> 
	<div class="row text-center pad-top">                                    
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
			<div class="div-square">
			   <a href="?p=blacklists&&db=nescoemployee" >
					<i class="fa fa-envelope-o fa-5x"></i>
					<h4>Blacklists</h4>
				</a>
			</div>
		</div>
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
			<div class="div-square">
			   <a href="?p=jobtransfers&&db=nescoemployee" >
					<i class="fa fa-clipboard fa-5x"></i>
					<h4>Job Transfers</h4>
				</a>
			</div>
		</div>
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
			<div class="div-square">
				<a href="?p=masterfile&&db=nescoemployee" >
					<i class="fa fa-users fa-5x"></i>
					<h4>Masterfile</h4>
				</a>
			</div>
		</div>	
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
			<div class="div-square">
				<a href="?p=benefits&&db=nescoemployee" >
					<i class="fa fa-users fa-5x"></i>
					<h4>Benefits</h4>
				</a>
			</div>
		</div>		
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
			<div class="div-square">
				<a href="?p=nescoregulars&&db=nescoemployee" >
					<i class="fa fa-comments-o fa-5x"></i>
					<h4>RegularNesco</h4>
				</a>
			</div>
		</div> 
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
			<div class="div-square">
				<a href="?p=termination&&db=nescoemployee" >
					<i class="fa fa-wechat fa-5x"></i>
					<h4>Resignation</h4>
				</a>
			</div>
		</div>
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
			<div class="div-square">
			   <a href="?p=silmonitor&&db=nescoemployee" >
					<i class="fa fa-clipboard fa-5x"></i>
					<h4> SIL Monitoring</h4>
				</a>
			</div>
		</div>	
	</div>';
}
if($dashboard == 'entries')
{ 
	/*<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
		<div class="div-square">
		   <a href="?p=employee-add&&db=entries" >
				<i class="fa fa-clipboard fa-5x"></i>
				<h4>Add <br> Employee</h4>
			</a>
		</div>
	</div>*/		
	echo  '
	<!-- /. ROW  --> 
	<div class="row text-center pad-top"> 
		
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
			<div class="div-square">
			   <a href="?p=blacklists-add&&db=entries" >
					<i class="fa fa-envelope-o fa-5x"></i>
					<h4>Add<br>Blacklists</h4>
				</a>
			</div>
		</div>
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
			<div class="div-square">
			   <a href="?p=transfers-add&&db=entries" >
					<i class="fa fa-clipboard fa-5x"></i>
					<h4>Add <br>Job Transfers</h4>
				</a>
			</div>
		</div>
			
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
			<div class="div-square">
				<a href="?p=toregular&&db=entries" >
					<i class="fa fa-male fa-5x"></i>
					<h4>Employee <br>Regularization</h4>
				</a>
			</div>
		</div> 
		
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
			<div class="div-square">
				<a href="?p=clearance-secure&&db=entries" >
					<i class="fa fa-wechat fa-5x"></i>
					<h4 style="color:green">Secure <br> Clearance </h4>
				</a>
			</div>
		</div> 
	</div>';
/*	<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
			<div class="div-square">
				<a href="?p=resignation-add&&db=entries" >
					<i class="fa fa-wechat fa-5x"></i>
					<h4>Add <br>Resignation</h4>
				</a>
			</div>
		</div>*/ 
}
else if($dashboard == 'contracts')
{
	//remove 05292020 miri
/*	<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
			<div class="div-square">
				<a href="?p=renewal&&db=contracts" style="text-decoration:none;">
					<i class="fa fa-gear fa-5x"></i>
					<h4>Renewal</h4>
				</a>
			</div>
		</div>*/
			
	echo '
	<!-- /. ROW  --> 
	<div class="row text-center pad-top">                                    
		
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
			<div class="div-square">
				<a href="?p=renewal-list&&db=contracts" style="text-decoration:none;">
					<i class="fa fa-gear fa-5x"></i>
					<h4 style="color:green"> EOC List </h4>
				</a>
			</div>
		</div>
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
			<div class="div-square">
				<a href="?p=probationary-list&&db=contracts" style="text-decoration:none;">
					<i class="fa fa-gear fa-5x"></i>
					<h4 style="color:green">Probationary List</h4>
				</a>
			</div>
		</div>		
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
			<div class="div-square">
			    <a href="?p=reprint&&db=contracts" >
					<i class="fa fa-clipboard fa-5x"></i>
					<h4>Reprint</h4>
				</a>
			</div>
		</div>
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
			<div class="div-square">
				<a href="?p=reapply&&db=contracts" >
					<i class="fa fa-users fa-5x"></i>
					<h4>Reapply</h4>
				</a>
			</div>
		</div>	
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
			<div class="div-square" >
				<a href="?p=tag_resignation&&db=contracts" >
					<i class="fa fa-tag fa-4x"></i>
					<h4>Tag for Resignation</h4>
				</a>
			</div>
		</div> 
	</div>';
}
else if($dashboard == 'reports')
{	
	echo '
	<!-- /. ROW  --> 
	<div class="row text-center pad-top">                                    	
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
			<div class="div-square">
			   <a href="?p=termination_reports&&db=reports" >
					<i class="fa fa-clipboard fa-5x"></i>
					<h4>Termination of Contract</h4>
				</a>
			</div>
		</div>
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
			<div class="div-square">
				<a href="?p=birthday_reports&&db=reports" >
					<i class="fa fa-clipboard fa-5x"></i>
					<h4>Birthday Celebrants</h4>
				</a>
			</div>
		</div>	
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
			<div class="div-square">
				<a href="?p=qbe&&db=reports" >
					<i class="fa fa-clipboard fa-5x"></i>
					<h4>Query By Example</h4>
				</a>
			</div>
		</div>	
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
			<div class="div-square">
				<a href="?p=employee_benefits_report&&db=reports" >
					<i class="fa fa-clipboard fa-5x"></i>
					<h4>Employee Benefits </h4>
				</a>
			</div>
		</div>			
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
			<div class="div-square">
				<a href="?p=employee_statistics&&db=reports" >
					<i class="fa fa-clipboard fa-5x"></i>
					<h4>Employee Statistics</h4>
				</a>
			</div>
		</div>
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
			<div class="div-square">
				<a href="?p=employeestatus&&db=reports" >
					<i class="fa fa-clipboard fa-5x"></i>
					<h4>Employee Status </h4>
				</a>
			</div>
		</div>
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
			<div class="div-square">
				<a href="?p=yearInService&&db=reports" >
					<i class="fa fa-clipboard fa-5x"></i>
					<h4>Year in Service </h4>
				</a>
			</div>
		</div>
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
			<div class="div-square">
				<a href="?p=username_report&&db=reports" >
					<i class="fa fa-clipboard fa-5x"></i>
					<h4>Username Report</h4>
				</a>
			</div>
		</div>
	</div>';
}
else if($dashboard == 'setup')
{
	echo '                              
	<!-- /. ROW  --> 
	<div class="row text-center pad-top"> ';
	//miri// marcia nolasco/mercey narce//mechille betonio//jessa rafayla
	$access_arr = array("03399-2013","15104-2013","04478-2017","02653-2013","12038-2013");
	if(in_array($_SESSION['emp_id'],$access_arr)){
		echo '
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
			<div class="div-square"> <br>
			   <a href="?p=salnumsetup&&db=setup" >
					<i class="fa fa-circle-o-notch fa-5x""></i>
					<h4> Setup Salary Num <br> &nbsp;</h4>
				</a>
			</div>
		</div>';
	}
	echo '
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
			<div class="div-square"> <br>
			   <a href="?p=subordinates&&db=setup" >
					<i class="fa fa-clipboard fa-5x"></i>
					<h4>Setup Subordinates <br> &nbsp;</h4>
				</a>
			</div>
		</div>
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
			<div class="div-square"> <br>
				<a href="?p=useraccounts&&db=setup" >
					<i class="fa fa-user fa-5x"></i>
					<h4>Add User Account <br> &nbsp;</h4>
				</a>
			</div>
		</div>	 
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
			<div class="div-square"> <br>
				<a href="?p=manageuseraccounts&&db=setup" >
					<i class="fa fa-users fa-5x"></i>
					<h4>Manage User Account <br> &nbsp;</h4>
				</a>
			</div>
		</div>	';
	if($_SESSION['emp_id'] =='03399-2013'){   	
		echo '	
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
			<div class="div-square"> <br>
			   <a href="?p=updatejobtrans&&db=setup" >
					<i class="fa fa-clipboard fa-5x"></i>
					<h4>Update Job Trans <br> &nbsp;</h4>
				</a>
			</div>
		</div>
	</div>';
	}
}

else if ($dashboard == "finalCompletion") {
	?>

		<div class="panel panel-default">
		  	<div class="panel-heading">
	            <span style="font-size:18px;"> FOR FINAL COMPLETION </span>
	        </div>
		  	<div class="panel-body">
		  		
		  		<table class="table">
		  			
		  			<tr>
		  				<td width="170"><a href="?p=finalReq&&db=finalCompletion&&q=recruitment"><button class="btn btn-sm btn-primary"><span class="fa fa-link"></span> Check Requirements</button></a></td>
		  				<td width="50"><span class="fa fa-arrow-right"></span></td>
		  				<td><p>The Final Requirements are the following :</p></td>
		  				<td></td>
		  			</tr>
		  			<tr>
		  				<td colspan="2"></td>
		  				<td>
		  					<p>1 . Birth Certificate</p>
                     		<p>2 . Medical Certificate</span></p>
                     		<p>3 . Police Clearance</p>
                     		<p>4 . Cedula</p>
                     		<p>5 . SSS</p>
                     		<p>6 . IDcard</p>
                     		<p>7 . Fingerprint</p>
                     		<p>8 . Background Investigation (from SSD)</p>
                     		<p>9 . Sketch of Home Address</p>
                     		<p>10. Recommendation Letter (from a regular employee within this company)</p>
		  				</td>
		  				<td>
		  					<P>11.  Marriage Certificate (if applicant is married)</p>
							<P>12. Parent's Consent (if applicant is still 17 years old)</p>
							<P>13. Drug Test</p>
							<P>14. Pag-Ibig no.</p>
							<P>15. Philhealth no.</p>
							<P>16. Other Documents</p>
		  				</td>
		  			</tr>
		  		</table>
		  	</div>
		</div>
	<?php
}

else if ($dashboard == "hiring") {
	?>	

	<div class="panel panel-default">
		  	<div class="panel-heading">
	            <span style="font-size:18px;"> FOR HIRING </span>
	        </div>
		  	<div class="panel-body">
		  		
		  		<table class="table">
		  			
		  			<tr>
		  				<td width="170"><a href="?p=appToBeHired&&db=hiring&&q=recruitment">Applicants to be Hired</a></td>
		  				<td width="50"><span class="fa fa-arrow-right"></span></td>
		  				<td><p>Lists all applicants ready for hiring.</p></td>
		  				<td></td>
		  			</tr>
		  			<tr>
		  				<td colspan="4"></td>
		  			</tr>
		  			<tr>
		  				<td width="170"><a href="?p=newlyHired&&db=hiring&&q=recruitment">Newly-Hired  Employees</a></td>
		  				<td width="50"><span class="fa fa-arrow-right"></span></td>
		  				<td><p>Lists all newly-hired employees.</p></td>
		  				<td></td>
		  			</tr>
		  			<tr>
		  				<td colspan="4"></td>
		  			</tr>
		  		</table>
		  	</div>
		</div>
	<?php
}

else if ($dashboard == "deployment") {
	?>	

	<div class="panel panel-default">
		  	<div class="panel-heading">
	            <span style="font-size:18px;"> FOR DEPLOYMENT </span>
	        </div>
		  	<div class="panel-body">
		  		
		  		<table class="table">
		  			
		  			<tr>
		  				<td width="240"><a href="?p=empForDeployment&&db=deployment&&q=recruitment">Newly-Hired Employees for Deployment</a></td>
		  				<td width="50"><span class="fa fa-arrow-right"></span></td>
		  				<td><p>Lists of all newly-hired employees ready for deployment.</p></td>
		  				<td></td>
		  			</tr>
		  			<tr>
		  				<td colspan="4"></td>
		  			</tr>
		  			<tr>
		  				<td width="170"><a href="?p=newlyDeployed&&db=deployment&&q=recruitment">Newly-Deployed Employee List</a></td>
		  				<td width="50"><span class="fa fa-arrow-right"></span></td>
		  				<td><p>Lists of all newly-deployed employees this month.</p></td>
		  				<td></td>
		  			</tr>
		  			<tr>
		  				<td colspan="4"></td>
		  			</tr>
		  		</table>
		  	</div>
		</div>
	<?php
}
?>
