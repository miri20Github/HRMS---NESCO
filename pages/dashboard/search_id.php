<?php
$emp 	= $_SESSION['emp_id'];
$id_ac 	= $nq->getOneField('emp_id','special_roles'," employeeid_viewing ='1' and emp_id = '$emp' ");
$employeetype = "and (emp_type IN ('NESCO','NESCO-BACKUP','NESCO Contractual','NESCO Partimer','NESCO-PTA','Promo-NESCO','NESCO-PTP','NESCO Regular','NESCO Probationary','NESCO Regular Partimer') ) ";
	
if($id_ac){
	if(@$_GET['searchs']){
		$val = mysql_real_escape_string($_GET['searchs']);
		$new_q = mysql_query(
				"SELECT 
					`emp_no`,
					`emp_id`,
					`startdate`,
					`tag_as`,
					`emp_pins`,
					`name`,
					`position`,
					`company_code`,
					`bunit_code`,
					`dept_code`
				 FROM `employee3`
				 WHERE `name` LIKE '%".$val."%'
				 $employeetype 
				 ORDER BY `name` ASC"
				) or die(mysql_error());
	}
	function checkID($id){
		$sql = mysql_query(
				"SELECT count(`emp_id`) FROM `ids_and_pins`
				 WHERE `emp_id` = '".$id."'"
			) or die(mysql_error());
		$res = mysql_fetch_array($sql);
		return $res['count(`emp_id`)'];
	}
	function getGenBy($eid){
		$sql = mysql_query(
				"SELECT `genBy` FROM `ids_and_pins_genby` WHERE `emp_id` = '".$eid."'"
			) or die(mysql_error());
		$res = mysql_fetch_array($sql);
		return $res['genBy'];
	}

	$columns = array("EMP NO","EMP PIN","NAME","POSITION","BUSINESS UNIT","DEPARTMENT","STARTDATE","EMP STATUS","STATUS","GENERATEDBY","ACTION");
?>

<div class="panel panel-default" style="width:100%; margin-left:auto; margin-right:auto;">
	<div class="panel-heading"> <b> SEARCH ID <I>FOR ID INCHARGE </b> </div>  	  
	<div class="panel-body">
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
				<?php foreach($columns as $key => $value){
					echo "<th><b> $value </b></th>";
				}?>
				</tr>
			</thead>
			<tbody>
			<?php while(@$res=mysql_fetch_array(@$new_q)){?>
				<tr <?php if(checkID($res['emp_id']) > 0):echo"class='success'";endif;?>>
					<td><?php echo $res['emp_no']?></td>
					<td><?php echo $res['emp_pins']?></td>
					<td><a href='?p=employee&&com=<?php echo $res['emp_id'];?>'><?php echo utf8_encode($res['name'])?></a></td>
					<td><?php echo $res['position']?></td>
					<td><?php echo $nq->getBusinessUnitName($res['bunit_code'],$res['company_code'])?></td>
					<td><?php echo $nq->getDepartmentName($res['dept_code'],$res['bunit_code'],$res['company_code'])?></td>
					<td><?php echo $nq->changeDateFormat('m/d/Y',$res['startdate'])?></td>
					<td><?php
						if($res['tag_as'] == "new"){
							echo "<span class='label label-primary'>".$res['tag_as']."</span>";
						}else{ echo "";}?>
					</td>
					<td><?php if(checkID($res['emp_id']) > 0):echo"Done";endif;?></td>
					<td><?php echo $nq->getApplicantName(getGenBy($res['emp_id']));?></td>
					<td>
						<?php if(checkID($res['emp_id']) == 0 && $res['emp_no']): ?>
							<button class="btn btn-sm btn-primary" id="<?php echo $res['emp_id'];?>" name="tag-as-done">Tag as Done</button>
						<?php elseif(empty($res['emp_no']) && empty($res['emp_pins'])):?>
							<button class="btn btn-sm btn-primary" id="<?php echo $res['emp_id'];?>" name="generate-id">Generate</button>
						<?php endif;?>
					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	</div>
</div>
<script>
	
</script>
<?php
}else { ?>
	<div class="alert alert-danger" role="alert">
		Your not Allowed to access this page!
	</div>
<?php } ?>