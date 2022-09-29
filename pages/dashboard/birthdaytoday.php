<?php
mysql_set_charset("UTF-8");
$month_day = date('m-d');	
$bday = mysql_query(
		"SELECT 
			employee3.emp_id,
			employee3.name,
			applicant.birthdate,
			applicant.gender,
			employee3.current_status,
			employee3.position,
			employee3.company_code,
			employee3.bunit_code,
			employee3.dept_code,
			employee3.section_code 
		 FROM 
			employee3 
		 INNER JOIN applicant ON 
			employee3.emp_id = applicant.app_id 
		 WHERE 
			applicant.birthdate like '%$month_day' 
		 AND 
			employee3.current_status = 'active'
		and
			($employeetype)
		ORDER BY employee3.name");
?>

<div class="panel panel-default" style='margin-left:auto;margin-right:auto;width:99%;'>
	<div class="panel-heading"> <b> BIRTHDAYS TODAY (ACTIVE NESCO ONLY) </b> </div>
		<div class="panel-body">		
        <table width="100%" class="table table-striped table-bordered" style='font-size:12px'>
            <tr>
    	       	<th>NO</th>              
                <th>NAME</th>
				<th>GENDER</th>
                <th>BIRTHDATE</th>				
				<th>BUSINESS UNIT</th>
                <th>DEPARTMENT</th>
				<th>SECTION</th>
                <th>POSITION</th>
            </tr>            
            <?php $ctr = 0;
            while($row = mysql_fetch_array($bday)){
				$ctr++; 
				$date1 = new DateTime($row['birthdate']);
				echo "<tr>
					<td>".$ctr."</td>				
					<td><a href='?p=employee&com=".$row['emp_id']."'>".ucwords(strtolower(utf8_encode($row['name'])))."</a></td>
					<td>".$row['gender']."</td>
					<td>".$date1->format("m/d/Y")."</td>				
					<td>".ucwords(strtolower($nq->getBusinessUnitName($row['bunit_code'],$row['company_code'])))."</td>
					<td>".ucwords(strtolower($nq->getDepartmentName($row['dept_code'],$row['bunit_code'],$row['company_code'])))."</td>
					<td>".ucwords(strtolower($nq->getSectionName($row['section_code'],$row['dept_code'],$row['bunit_code'],$row['company_code'])))."</td>
					<td>".ucwords(strtolower($row['position']))."</td>			
				</tr>";
            }
			mysql_close($conns);
			?>
        </table>  
        </div>       
    </div>
</div>