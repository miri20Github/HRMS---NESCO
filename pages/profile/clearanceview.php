<?php
session_start();
if($_SESSION['emp_id'] != ""){
?>
<title>SCANNED CLEARANCE</title>
<body oncontextmenu="return false;"> 
<center><img src='<?php echo "../../".$_GET['c'];?>' width='700px' height='900px'></center>
</body>
<?php }
else
{ 
	echo "You are not login to HRMS or your current session has expired. Please log in <a href='../'>here</a>";

} ?>