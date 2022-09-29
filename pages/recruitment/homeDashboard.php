<div class="row">
	<div class="col-md-6">
		<div class="panel panel-default">
		  	<div class="panel-heading" style="font-size: 18px;">D A S H B O A R D</div>
		  	<div class="panel-body">
		  		<ul class="list-group" >            
					<li class="list-group-item">
						<span class="badge" id="tags1" style='cursor: pointer; background-color:#e13d3d;'><?php echo finalRequirements(); ?></span> Applicants for checking final requirements
					</li>       
		         
					<li class="list-group-item">
						<span class="badge" id="tags2" style='cursor: pointer; background-color:#e13d3d;'><?php echo hiring(); ?></span> Applicants ready for hiring
					</li>
		                       
					<li class="list-group-item">
						<span class="badge" id="tags3" style='cursor: pointer; background-color:#e13d3d;'><?php echo deployment(); ?></span> Applicants ready for deployment
					</li>
				</ul>
		  	</div>
		</div>
	</div>
</div>

<?php 

	function finalRequirements(){

		$query = mysql_query("SELECT app_code FROM applicants, applicant WHERE applicants.app_code = applicant.appcode AND applicants.status = 'for final completion' AND tagged_to = 'NESCO'")or die(mysql_error());
		$num = mysql_num_rows($query);	

		if($num > 0) { 
			
			echo "<a href='?p=finalReq&&db=finalCompletion&&q=recruitment' style='color:white'>$num</a>"; 
		} else { 
			
			echo "<a href='#' style='color:white'>0</a>";
		}
	
	}

	function hiring(){

		$query = mysql_query("SELECT app_code FROM applicants, applicant WHERE applicants.app_code = applicant.appcode AND applicants.status = 'for hiring' AND tagged_to = 'NESCO'")or die(mysql_error());
		$num = mysql_num_rows($query);	

		if($num > 0) { 
			
			echo "<a href='?p=appToBeHired&&db=hiring&&q=recruitment' style='color:white'>$num</a>"; 
		} else { 
			
			echo "<a href='#' style='color:white'>0</a>";
		}
	
	}

	function deployment(){

		$query = mysql_query("SELECT app_code FROM applicants, applicant WHERE applicants.app_code = applicant.appcode AND applicants.status = 'new employee' AND tagged_to = 'NESCO'")or die(mysql_error());
		$num = mysql_num_rows($query);	

		if($num > 0) { 
			
			echo "<a href='?p=empForDeployment&&db=deployment&&q=recruitment' style='color:white'>$num</a>"; 
		} else { 
			
			echo "<a href='#' style='color:white'>0</a>";
		}
	
	}

?>