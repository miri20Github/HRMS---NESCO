<?php 

	include("header.php");
?>
	
	<div class="panel panel-default">
      	<div class="panel-heading"><span style="font-size:18px;"> NEWLY-HIRED EMPLOYEES READY FOR DEPLOYMENT </span></div>
      	<div class="panel-body">

			<table class="table table-striped" width="100%" id="empForDeployment" style='font-size:11px'>		
				<thead>
					<tr>
						<td><b>APP ID</b></td>
						<td><b>APPLICANT NAME</b></td>
						<td><b>APPLYING FOR</b></td>
						<td><b>ATTAINMENT</b></td>
						<td><b>DATE APPLIED</b></td>	
						<td align="center"><b>ACTION</b></td> 
					</tr>
				</thead>
			</table>
		</div>
	</div>

<script type="text/javascript">
	
	function deployNow(appId,appCode) {
		
		$.ajax({
			type: "POST",
			url : "functionquery.php?request=getApplicantName",
			data: { appId:appId },
			success : function(data){	
				
				var name = data.trim();
				if(name != "") {
					if (confirm("Are you sure you want to deploy "+name+" now ?") == true) {

						$.ajax({
							type: "POST",
							url : "functionquery.php?request=deployNow",
							data: { appId:appId, appCode:appCode },
							success : function(data){	
								
								data = data.trim();
								if(data == "Ok") {

									alert(name+" is now successfully deployed! Thank You...");
									var loc = document.location;

									window.location = loc;
								}
							}
						});
					}
				}
			}
		});
	}
</script>