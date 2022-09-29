<?php
	mysql_query('SET NAMES utf8');	    	
	$fn = mysql_real_escape_string(trim(@$_GET['fn']));
	$ln = mysql_real_escape_string(trim(@$_GET['ln']));
	if($ln != "")
	{
		$fields_app = "app_id, lastname, firstname, middlename, birthdate, suffix, home_address, civilstatus, photo";
		$query = mysql_query("SELECT $fields_app FROM applicant where lastname = '$ln' and firstname like '%$fn%' order by lastname,firstname");	
		$count = mysql_num_rows($query);
		if($count == 0){
			$msg =  "<i style='color:red;font-size:24px'>No Result found!</i>";
		}			
	}			
?>
<div style="width:90%;margin-left:auto; margin-right:auto">	   
	<table width="90%">
		<tr>
			<td width="200"><h3>Search Applicant</h3></td>
			<td> <input id="ln" type="text" class="form-control" name="ln_search" placeholder="LastName" value="<?php echo @$ln;?>"/></td>
			<td> <input id="fn" type="text" class="form-control" name="fn_search" placeholder="FirstName" value="<?php echo @$fn;?>"/></td>
			<td> &nbsp; <input type="submit" class="btn btn-primary" name="submit_search" value="Search"/></td>
		</tr>
	</table>
	<hr>
	<div class="row">
	<?php
		$i =0;	
		while(@$row = mysql_fetch_array(@$query))
		{	
			$select = mysql_query("SELECT emp_type from employee3 where emp_id = '$row[app_id]' ");
		 	$rr = mysql_fetch_array($select);
			
			if($rr['emp_type'] == '' || 
				$rr['emp_type'] =='NESCO' ||
				$rr['emp_type'] == 'NESCO-PTA' || 
				$rr['emp_type'] == 'NESCO-PTP' || 
				$rr['emp_type'] == 'NESCO Regular' || 
				$rr['emp_type'] == 'NESCO Probationary' ||
				$rr['emp_type'] == 'NESCO Regular Partimer')				
			{
			
				$i++;
				$bd 	= new DateTime($row['birthdate']); 
				$home 	= $row['home_address'];
				$cv 	= $row['civilstatus'];
					
				$photo = $row['photo'];
				if($photo == ''){
					$photo = '../images/users/icon-user-default.png';
				}	
				
				if($row['suffix']){
					$name = $row['lastname'].", ".$row['firstname']." ".$row['suffix'].", ".$row['middlename'];	
				}else{
					$name = $row['lastname'].", ".$row['firstname']." ".$row['middlename'];	
				}

			echo "<div>
				<img style='float:left' src='$photo' width='55' height='55'> &nbsp; [".$i."] <a id='sear' href=?p=employee&com=$row[app_id] style='font-size:16px'>".$row['app_id']." ".$name."</a><br>					
				&nbsp; Civil Status: <i>".ucwords(strtolower($cv))."</i> &nbsp; Birthdate: <i>".$bd->format('M d, Y')."</i> &nbsp; Home Address: <i>".ucwords(strtolower($home))."</i>
			</div><br>";
			}			
		}
		echo  @$msg; ?>
	</div>	
</div>