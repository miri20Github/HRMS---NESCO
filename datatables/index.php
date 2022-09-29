<?php
include("../placement/connection.php");
?>
<html>
<head>
<link href='jquery.dataTables.css' rel='stylesheet'/> 
<script src="jquery-1.11.1.min.js" type="text/javascript"></script>
<script src="jquery.dataTables.min.js" type="text/javascript"></script>

<script>
$(document).ready(function() {
    $('#placement_access').DataTable();
} );
</script>
</head>
<body>

<table id="placement_access" class='display' cellspacing="0" width="100%">
        <thead>
            <tr>				
				<th width='5%'>&nbsp; ACTION</th>
				<th width='2%'>BL_NO</a>
				<th width='4%'>EMP NO</th>
				<th width='18%'>NAME</th>				
				<th width='12%'>DATE BLACKLISTED</th>
			</tr>
        </thead> 
        <tbody>
			<?php
			$blq = mysql_query("SELECT record_no, emp_id, name, position from employee3 where company_code = '01' and current_status = 'active'");	
			while($row = mysql_fetch_array($blq))
			{						
				$name = $row['name'];//str_replace(" ","_",$nq->getEmpName($row['emp_id']));				
				echo "
				<tr>
					<td>&nbsp; 
						<a href='#'><span class='glyphicon glyphicon-pencil' aria-hidden='true'></span></a>
						<a href='#'><span class='glyphicon glyphicon-search' aria-hidden='true'></span></a>
						<a href='#'><span class='glyphicon glyphicon-trash' aria-hidden='true'></span></a>
					</td>		
					<td>".$row['record_no']."</td>
					<td><a href='employee_details.php?com=$row[emp_id]'>".$row['emp_id']."</a></td>
					<td>".ucwords(strtolower($name))."</td>					
					<td>".$row['position']."</td>
				</tr>"; 		          
			}  ?>
        </tbody>
    </table>
</body>
</html>	
