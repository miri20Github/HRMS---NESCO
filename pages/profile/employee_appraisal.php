<link rel="stylesheet" href="../../css/bootstrap.css" type="text/css" media="screen, projection" />
<?php
include("../../../connection.php");
$recordno = $_GET['rec'];
$empid 	  = $_GET['emp'];
$name 	  = $nq->getEmpName($empid);
$epascode = $_GET['epascode'];

if(strlen($epascode) > 10)
{	
	echo "<center><img src='$epascode' width='800' height='1000'></center>";
}
else
{

	$query    = mysql_query("SELECT * FROM appraisal_details where record_no = '$recordno' and emp_id = '$empid' ");
	while($row = mysql_fetch_array($query))
	{	
		
		$rater = $nq->getApplicantName($row['rater']);
		?>
		<h4><?php echo "[".$empid."] ".$name;?></h4>
		<table class="table table-bordered">		
		<tr>
			<td colspan='3'>GUIDE QUESTIONS</td>
			<td><b>RATE</b></td>
		  </tr>
		<?php	

		//$det = mysql_query("SELECT * FROM `appraisal` inner join appraisal_answer on appraisal.qno = appraisal_answer.qno where evaluatee = '$row[evaluatee]' and ratingno = '$row[epas_code]'");
		$det = mysql_query("SELECT * FROM `appraisal` inner join appraisal_answer on appraisal.appraisal_id = appraisal_answer.appraisal_id where details_id = '$row[details_id]' ");
		while($rr = mysql_fetch_array($det))
		{		
			echo "  	    
		  <tr>
				<td colspan='3'>".$rr['q_no'].") ".$rr['title']." <br> ".$rr['description']."</td>
				<td><i><b>".$rr['rate']."</b></i></td>          
			</tr>";
		} 
		switch($row['descrate'])
		{
			case "E"	: $drate = "Excellent"; break;
			case "VS"	: $drate = "Very Satisfactory"; break;
			case "S"	: $drate = "Satisfactory"; break;
			case "US"	: $drate = "Unsatisfactory"; break;
			case "VU"	: $drate = "Very Unsatisfactory"; break;
		}
		?>
		<tr>
			<td>Descriptive Rating</td>
			<td><i><b><?php echo $drate;?></b></i></td>
			<td>Numerical Rating</td>
			<td><i><b><?php echo $row['numrate'];?></b></i></td>
		</tr>
		<tr>
			<td>Rater's Comment</td>
			<td colspan="3"><i><b><?php echo $row['ratercomment'];?></b></i></td>
		</tr>
		<tr>    
			<td>Ratee's Comment</td>
			<td colspan="3"><i><b><?php echo $row['rateecomment'];?></b></i></td>
		</tr>
		<tr><td>record no : <?php echo $recordno;?></td></td>
	</table>
<?php } 
}?>