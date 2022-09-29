<?php
$columns = array("TRANSFERNO","EMPID","NAME","EFFECTIVITY","ENTRY DATE","ENTRY BY","PROCESS","OLD PAYROLLNO","ACTION");

//please do not delete this file
//5.10.2016
//check first if naay wla pa ma update sa employee3 nga effectivity with the process status nga 1
$date 		= date('Y-m-d');
$process 	= 'no';
$select 	= mysql_query("SELECT * FROM employee_transfer_details WHERE process = '$process' order by effectiveon"); //and effectiveon =  '$date'

?>
<div class="panel panel-default" style='margin-left:auto;margin-right:auto;width:99%;'>
	<div class="panel-heading"> <b> JOB TRANS FOR EFFECTIVITY UPDATE </b> </div>
	<div class="panel-body">
		<table class='table table-bordered table-striped'>
			<tr>
				<?php foreach($columns as $key => $value){
					echo "<th> $value </th>";
				}?>	
			</tr>
		<?php
		while($r = mysql_fetch_array($select))
		{	
			$empid	= $r['emp_id'];
			$name 	= $nq->getEmpName($empid);
			
			echo "<tr>
				<td>".$r['transfer_no']."</td>
				<td><a href='?p=employee&com=$empid'>".$empid."</a></td>
				<td>".ucwords(strtolower(utf8_encode($name)))."</td>
				<td>".$r['effectiveon']."</td>
				<td>".$nq->changeDateFormat("m/d/Y",$r['entry_date'])."</td>
				<td>".$nq->getName($r['entry_by'])."</td>
				<td>$r[process]</td>
				<td>$r[old_payroll_no]</td>
				<td><a href='#' onclick='updatenow(".$r['transfer_no'].")'>update</a></td>			
				</tr>";			
		}
		echo "</table>";
		?>
	</div>
</div>

<script>
function updatenow(transno){
	$.ajax({
		type:"POST",
		url:"functionquery.php?request=updatejobtrans",
		data:{ transno:transno },
		success:function(data)
		{	
			if(data == 1){
				alert('Successfully Updated');
				window.location = '?p=updatejobtrans&&db=setup';
			}else{
				alert('Updating Failed');
			}		
		}
	});	
}
</script>