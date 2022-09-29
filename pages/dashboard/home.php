<?php
include('connection.php');

	function getcountnesco_gender($gender)
	{
		$condition 	= "gender = '$gender' and current_status = 'Active' and coe !='nesco'";
		$condition .= "and (emp_type IN ('NESCO-BACKUP','NESCO Contractual','NESCO Partimer','NESCO-PTA','Promo-NESCO','NESCO-PTP','NESCO Regular','NESCO Probationary','NESCO Regular Partimer') )";
	
		$select = mysql_query("SELECT count(emp_id) as ncount 
		FROM employee3
		INNER JOIN applicant ON applicant.app_id = employee3.emp_id
		WHERE $condition");
		$row = mysql_fetch_array($select);
		return $row['ncount'];
	}

	function getcountnesco_total()
	{
		$condition 	= "current_status = 'Active' and coe !='nesco'";
		$condition .= "and (emp_type IN ('NESCO-BACKUP','NESCO Contractual','NESCO Partimer','NESCO-PTA','Promo-NESCO','NESCO-PTP','NESCO Regular','NESCO Probationary','NESCO Regular Partimer') )";
	
		$select = mysql_query("SELECT count(emp_id) as ncount FROM employee3 WHERE $condition ");
		$row 	= mysql_fetch_array($select);
		return $row['ncount'];
	}

$total = 0;
$etype = array();
$counte = array();
$etype_query = mysql_query("SELECT emp_type FROM employee_type WHERE emp_type like '%nesco%' and emp_type !='NESCO'");
while($r = mysql_fetch_array($etype_query)){	
	$c = $nq->getCountEmptype($r['emp_type']);
	$etype[] = $r['emp_type'];
	$counte[] = $c;
	$total += $c;
	
}
//get the percentage			
$percent = array();
for($m=0;$m<count($counte);$m++){	
	$percent[$m] = round(($counte[$m]/$total)*100,2);
}

function random_color_part() { return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT); }
function random_color() {	return random_color_part() . random_color_part() . random_color_part();}
$color = array();
for($n=0;$n<count($etype);$n++){ $color[] = "#".random_color(); }
?>
<style> a,a:hover { text-decoration:none;}
.text { font-size:15px;color:red }
.li_d { border-style:dotted;border-width: 1px;border-color:#ccc;padding: 11px } </style>

<H1> Dashboard</H1>

</div>
<div class="panel panel-default">
	<div class="panel-body" >
		<div class='col-xs-12 col-md-4'>
			<a href='?p=birthdaytoday'><li class='list-group-item li_d' >
				<img src="../images/icons/circle-birthday.png" width='30' height='30'> 
				<b id='cbirthday' class='text' > 0 </b> Birthday's Today
			</li></a>
			<a href='?p=newemployees'><li class='list-group-item li_d' >
				<img src="../images/icons/circle-new.png" width='33' height='33'> 
				<b id='cnewemp'  class='text'>0</b> New Employee's
			</li></a>
			<a href='?p=newblacklist'><li class='list-group-item li_d' >
				<img src="../images/icons/circle-blacklist.png" width='30' height='30'> 
				<b id='cblacklist' class='text'>0</b> New Blacklists
			</li></a>
			<a href='?p=newjobtrans'><li class='list-group-item li_d' >
				<img src="../images/icons/circle-jobtrans.png" width='30' height='30'> 
				<b id='cjobtrans' class='text' >0</b> New Job Transfer
			</li></a>
			
			<a href='?p=dueContracts'><li class='list-group-item li_d'>
				<img src="../images/icons/doc-icon.png" width='28' height='28'> 
				<b id='eocToday' class='text' >0</b> Due Contracts Report					
			</li></a>
		</div>
		
		<div class="col-xs-12 col-md-4">
			<div><!-- class="panel-body table-responsive"-->
				<table class='table table-bordered'  width='100%'> <tr align='center'><td><b> NESCO EMPLOYEES</b></td><td><b>%</b></td><td><b>COUNT</b></td></tr>
				<?php
				for($n=0;$n<count($etype);$n++){
					$color[] = "#".random_color();											
					echo "<tr>
						<td height='12px'><span class='glyphicon glyphicon-stop' style='color:$color[$n]'></span> ";
					echo $etype[$n]."</td> <td>".$percent[$n]."</td>
					<td><a href='?p=statistics_details&emptype=$etype[$n]'>".$counte[$n]."</a></td></tr>";											
				}?>
				</table>	
			</div>
		</div>	
		<div class="col-xs-12 col-md-4" > <!-- style='background:white;border:1px #ccc solid;' -->
			<div><!--  class="panel-body table-responsive"-->				
				<div id="canvas-holder">
					<canvas id="chart-area" width="300" height="300"/>
					
				</div>	
																			
			</div>
		</div>			
	</div>	
</div>

<div class='panel panel-default'>
	<div class='panel-heading'>
		<div style='font-size:20px;text-indent:10px;'> Employees Without Benefits Number</div>
	</div>        
    <div class="panel-body">
		<a href='?p=nobenefits&&bt=sss'><li class='list-group-item' >NO SSS</li></a>
		<a href='?p=nobenefits&&bt=ph'><li class='list-group-item' >NO PHILHEALTH</li></a>
		<a href='?p=nobenefits&&bt=pg'><li class='list-group-item' >NO PAGIBIG MID NO</li></a>
		<a href='?p=nobenefits&&bt=pgrtn'><li class='list-group-item' >NO PAGIBIG RTN</li></a>

	</div>
</div>

<div class='panel panel-default'>
	<div class='panel-heading'>
		<div style='font-size:20px;text-indent:10px;'> NESCO EMPLOYEES GENDER COUNT</div>
	</div>        
    <div class="panel-body">
		<table class="table table-bordered table-striped">
			<tr>
				<th> TOTAL NESCO </th>	
				<th> MALE </th>	
				<th> FEMALE </th>	
			</tr>
			<tr>
				<td> <b> <?php echo getcountnesco_total();?> </b> </td>	
				<td> <b> <?php echo getcountnesco_gender("Male");?> </b> </td>	
				<td> <b> <?php echo getcountnesco_gender("Female");?> </b> </td>	
			</tr>
		</table>
	</div>
</div>
	
<script>
	var pieData = [		
		<?php
		for($i=0;$i<count($etype);$i++){ ?>
			{
				value: <?php echo $percent[$i];?>,
				color:"<?php echo $color[$i];?>",
				highlight: "#FF5A5E",
				label: "<?php echo $etype[$i];?>"
			},
		<?php }?>					
	];		
		
	window.onload = function(){
		var ctx = document.getElementById("chart-area").getContext("2d");
		window.myPie = new Chart(ctx).Pie(pieData);		
	};
</script>