<?php
$btype 		= $_GET['bt'];
$condition 	= "current_status = 'Active' and ";
switch($btype){
	case "sss"	:   $condition .= " (sss_no = '' or sss_no = '00-0000000-0' or sss_no is NULL)";
					$th 	= "SSS NO";
					$th_val = "sss_no";
					break;
	case "ph"	: 	$condition .= " (philhealth = '' or philhealth = '00-000000000-0' or philhealth = '000000000000' or philhealth is NULL)"; 
					$th		= "PHILHEALTH NO";
					$th_val = "philhealth";
					break;
	case "pg"	: 	$condition .= " (pagibig = '' or pagibig = '0000-0000-0000' or pagibig = '000000000000' or pagibig is NULL)";
					$th 	= "PAGIBIG MID NO";
					$th_val = "pagibig";
					break;
	case "pgrtn": 	$condition .= " (pagibig_tracking = '' or pagibig_tracking = '0000-0000-0000' or pagibig_tracking = '000000000000' or pagibig_tracking is NULL)";
					$th 	= "PAGIBIG RTN";
					$th_val = "pagibig_tracking";
					break;		 	 
}
$condition .= "and (emp_type IN ('NESCO-PTA','NESCO-PTP','NESCO Regular','NESCO Probationary','NESCO Regular Partimer') )";

$query = "SELECT sss_no, pagibig_tracking, pagibig, philhealth, emp_id, name, emp_type, company_code, bunit_code, dept_code, current_status, position 
	FROM `applicant_otherdetails` 
	INNER JOIN employee3 ON applicant_otherdetails.app_id = employee3.emp_id
	WHERE $condition order by company_code, bunit_code  ";
$sql = mysql_query($query);
?>

<link href='../datatables/jquery.dataTables.css' rel='stylesheet'/> 
<script src="../datatables/jquery-1.11.1.min.js" type="text/javascript"></script>
<script src="../datatables/jquery.dataTables.min.js" type="text/javascript"></script>

<script> $(document).ready(function() {    $('#nobenefits').DataTable();  } ); </script>

<div class="panel panel-default" style='margin-left:auto;margin-right:auto;width:99%;'>
	<div class="panel-heading"> <b> NESCO EMPLOYEES WITH NO <?php echo $th;?> </b> </div>
		<div class="panel-body">	

			<table id="nobenefits" class="table" cellspacing="0">
				<thead>
					<tr>
						<th>EMPID</th>
						<th>NAME</th>
						<th>POSITION</th>
						<th>EMPTYPE</th>
						<th>BUSINESS UNIT</th>
						<th>DEPT</th>
						<th><?php echo $th;?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					while($r = mysql_fetch_array($sql))
					{
						echo "			
						<tr>
							<td><a href='employee_details.php?com=$r[emp_id]' target='_blank'>$r[emp_id]</a></td>
							<td>$r[name]</td>
							<td>$r[position]</td>
							<td>$r[emp_type]</td>
							<td>".$nq->getBusinessUnitName($r['bunit_code'],$r['company_code'])."</td>
							<td>".$nq->getDepartmentName($r['dept_code'],$r['bunit_code'],$r['company_code'])."</td>
							<td>".$r[$th_val]."</td>
						</tr>
						";
					}?>
				</tbody>
			</table>
		</div>	
	</div>
</div>