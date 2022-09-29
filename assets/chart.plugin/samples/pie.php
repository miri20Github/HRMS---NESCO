<?php
$hostname = 'localhost';         // Your MySQL hostname. Usualy named as 'localhost'.
$dbname   = 'pis';               // Your database name.
$username = 'CorporateIT';       // Your database username.
$password = 'agcCorporateIT';    // Your database password. If your database has no password, leave it empty.
$table2   = 'employee3';
$table3   = 'application_interviewers';
$pass     = 'Hrms2014';
// host connect
mysql_connect($hostname, $username, $password) or DIE('Connection to host is failed, perhaps the service is down!');
// database select
mysql_select_db($dbname) or DIE('Database name is not available!');


$stores = array('Island City Mall','Alturas Mall','Plaza Marcela','Alturas Talibon');
$color = array('Orange','Red','Yellow','Green');
$cc = array("02","02","03","02");
$bc = array("03","01","01","02");

$sel_etype = mysql_query("SELECT * FROM employee_type");
while($r = mysql_fetch_array($sel_etype)){		
	$etype_count = getCountEmptype($r['emp_type']);
	echo $etype_count." ";
}

function getCountEmptype($etype)
	{
		//$this->Connect();
		$query = mysql_query("SELECT count(emp_id) from employee3 where emp_type = '$etype' and current_status = 'Active' ");
		$r = mysql_fetch_array($query);
		//$result = $this->makeQuery($query);			
		//$fetch = $this->fetchArray($result);
		$fetch_c = $r['count(emp_id)'];
		if($etype=='NESCO-PTA' || $etype=='NESCO-PTP' || $etype=='PTA' || $etype=='PTP'){
			$fetchs = $fetch_c * 0.5; 
			if($fetchs != 0){
				return $fetchs;
			}
		}else if($r['count(emp_id)'] != '0'){
			return $fetch_c;
		}else{
			return '';
		}
	}
?>

<!doctype html>
<html>
	<head>
		<title>Pie Chart</title>
		<script src="../Chart.js"></script>
		<style>
			body{
				padding: 0;
				margin: 0;
			}
			#canvas-holder{
				width:30%;
			}dai
		</style>
	</head>
	<body>
		<div id="canvas-holder">
			<canvas id="chart-area" width="300" height="300"/>
			<canvas id="chart-area" width="200" height="200"/>			
		</div>
	
		
	<script>
					
			var pieData = [		
				<?php
				for($i=0;$i<count($stores);$i++){
					$act_q = mysql_query("SELECT COUNT(emp_id) as num from employee3 where company_code ='$cc[$i]' and bunit_code ='$bc[$i]' and current_status = 'active' ") or die(mysql_error());
					$act   = mysql_fetch_array($act_q);
					$act_c = $act['num'];
					?>
					{
						value: <?php echo $act_c;?>,
						color:"<?php echo $color[$i];?>",
						highlight: "#FF5A5E",
						label: "<?php echo $stores[$i];?>"
					},
				<?php }?>					
			];			

			window.onload = function(){
				var ctx = document.getElementById("chart-area").getContext("2d");
				window.myPie = new Chart(ctx).Pie(pieData);
			};
			
				var doughnutData = [
				{
					value: 300,
					color:"#F7464A",
					highlight: "#FF5A5E",
					label: "Red"
				},
				{
					value: 50,
					color: "#46BFBD",
					highlight: "#5AD3D1",
					label: "Green"
				},
				{
					value: 100,
					color: "#FDB45C",
					highlight: "#FFC870",
					label: "Yellow"
				},
				{
					value: 40,
					color: "#949FB1",
					highlight: "#A8B3C5",
					label: "Grey"
				},
				{
					value: 120,
					color: "#4D5360",
					highlight: "#616774",
					label: "Dark Grey"
				}

			];

			window.onload = function(){
				var ctx = document.getElementById("chart-area").getContext("2d");
				window.myDoughnut = new Chart(ctx).Doughnut(doughnutData, {responsive : true});
			};

	</script>
	</body>
</html>
