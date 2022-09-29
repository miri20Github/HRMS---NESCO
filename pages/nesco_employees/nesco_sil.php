<?php
require_once("get_contract_duration.php");  
$contract = new get_contract_duration();

$rc 		= @$_GET['rc'];
$regclass 	= "";
$sil 		= "";
$condition  = "current_status ='Active' ";
switch($rc){
	case '1': $condition .= "and reg_class='RC1'"; $sil = 'WITHOUT SIL'; break;
	case '2': $condition .= "and reg_class='RC2'"; $sil = '5 DAYS SIL'; break;	
	case '3': $condition .= "and reg_class='RC3'"; $sil = '7 DAYS SIL'; break;
}

$condition .= " and emp_type IN ('NESCO','NESCO-PTA','NESCO-PTP','NESCO Regular','NESCO Probationary','NESCO Regular Partimer') ";

$query = mysql_query("SELECT emp_id, name, position, reg_class, emp_type, startdate, company_code, bunit_code, dept_code from employee3
where $condition");
?>
<html>
<head>
<link href='../datatables/jquery.dataTables.css' rel='stylesheet'/> 
<script src="../datatables/jquery-1.11.1.min.js" type="text/javascript"></script>
<script src="../datatables/jquery.dataTables.min.js" type="text/javascript"></script>

<script>
$(document).ready(function() {
    $('#placement_access').DataTable();
});
</script>
</head>
<body>
<div class="panel panel-default" style='margin-left:auto;margin-right:auto;width:99%;'>
	<div class="panel-heading">
		<div class="row"> 
			<div class='col-md-3'><h4> SIL MONITORING </h4></div>
			<div class='col-md-7' align="left">
				<a href='?p=silmonitor&&rc=1' class='btn btn-primary'>WITHOUT SIL</a> 
				<a href='?p=silmonitor&&rc=2' class='btn btn-success'>WITH 5DAYS SIL</a> 
				<a href='?p=silmonitor&&rc=3' class='btn btn-warning'>WITH 7DAYS SIL</a> 
			</div>
			<div class='col-md-2' align="right"><h4>(<?php echo $sil;?>)</h4></div>
		</div>
	</div>

	<div class="panel-body">				
		<table id="placement_access" class='table table-striped' cellspacing="0" width="100%" style='font-size:11px'>
			<thead>
				<tr>							
					<th>NAME</th>
					<th>POSITION</th>	
					<th>BUSINESS UNIT</th>	
					<th>DEPARTMENT</th>					
					<th>EMPTYPE</th>
					<th>DATE HIRED</th> 
					<th width="100">YEARS IN SERVICE</th>					
					<?php
					if($rc =='1' || $rc =='2'){	
						echo "<th>ACTION</th>";					   
					}else{ echo "";  }?>
				</tr>
			</thead> 
			<tbody>
				<?php   
				while(@$row = mysql_fetch_array($query)){
					$dh = $nq->getOneField("date_hired","application_details"," app_id='$row[emp_id]'");
					if($dh == '' || $dh == '0000-00-00'){ 
						$dh = '';
					}else{
						$dh = $nq->changeDateFormat("m/d/Y",$dh);
					}

					$empId = $row['emp_id'];
					
					$yr = $contract->getNYears($dh,$row['startdate']);
					?>
					<tr class='tr_<?php echo $row['emp_id'];?>'>					
 						<td><a href='?p=employee&com=<?php echo $row['emp_id'];?>' target='_blank'> <?php echo ucwords(strtolower($row['name']));?></a></td>
						<td><?php echo ucwords(strtolower($row['position']));?></td>
						<td><?php echo $nq->getBUAcroname($row['bunit_code'],$row['company_code']);?></td> 
						<td><?php echo $nq->getDeptAcroname($row['dept_code'],$row['bunit_code'],$row['company_code']);?></td>
						<td><?php echo $row['emp_type'];?></td>  						
						<td><?php echo $dh;?> </td>
						<td><?php echo $contract->getYears($dh,$row['startdate']);?></td>
				<?php   if($rc =='1'){	
							echo "<td>";
								if( $yr >= 1){
							echo "<a href='javascript:void' data-toggle='modal' data-target='#companydetails' onclick=\"gethistory('$empId','$dh')\"> change </a>";
								}
							echo "</td>"; 
						}						
						else if($rc =='2'){	
							echo "<td>";
								if( $yr >= 6){
							echo "<a href='javascript:void' data-toggle='modal' data-target='#companydetails' onclick=\"gethistory('$empId','$dh')\"> change </a>";
								}
							echo "</td>"; 
						}
						else{ echo "";  } ?>
					</tr><?php                   
				} ?>
			</tbody>
		</table>
	</div>
</div>	

<!-- Modal  O P E N   E M P L O Y M E N T-->
<div class="modal fade" id="companydetails" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:80%">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">VIEW CONTRACT HISTORY DETAILS </h4>
			</div>
			<div class="modal-body">	
				<div id='chistories'>
				</div>
				<input type='text' id='empids' style='display:none'>
				<button class='btn btn-primary' onclick='changeSIL()'> Click to change to <span id='silname'></span>SIL</button>	
			</div>
			<div class='modal-footer'>
				<button class='btn btn-primary' data-dismiss='modal' >&times; Close</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>function gethistory(empid,dh)
{
	empid = empid.trim();
	var rc    = '<?php echo $rc;?>';	
	$.ajax({
		type : "POST",
		url : "../placement/functionquery.php?request=getSIL",
		data : { rc:rc },
		success : function(data){			
			$('#silname').html(data);			
		}
	}); 
	
	$('#empids').val(empid);
	$.ajax({
		type : "POST",
		url : "../placement/functionquery.php?request=gethistory",
		data : { empid : empid, dh: dh },
		success : function(data){			
			$('#chistories').html(data);
		}
	});	
}
function changeSIL()
{
	var empid = $('#empids').val();
	var rc    = '<?php echo $rc;?>';		
	
	var r = confirm("Are you sure to update the SIL?")
	if(r == true)
	{		
		$.ajax({
			type : "POST",
			url : "../placement/functionquery.php?request=changeSIL",
			data : { empid:empid, rc:rc },
			success : function(data){				
				alert(data);
				alert("Please close the modal.");
				$(".tr_"+empid).css({"background-color":"#d3d6ff"});
				$(".tr_"+empid).fadeOut();				
				//window.location = "sil_monitoring.php?rc="+<?php echo $rc;?>;
			}
		});
	}	
}
</script>
</body>
</html>