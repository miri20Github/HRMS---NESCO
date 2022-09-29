<?php 
session_start();
if($_SESSION['emp_id']){ ?>

<link rel="stylesheet" href="../../css/bootstrap.css" type="text/css" media="screen, projection" />
<?php
	
	include("../../../connection.php");
 	$rec = $_GET['rec'];
	$emp = $_GET['empid'];
	
		$dh = $nq->getOneField('date_hired','application_details',"app_id='$emp' "); 
        $queryemp = $nq->getEmpInfoByRecord($rec);
	        $rr = mysql_fetch_array($queryemp);
		
    		$epas = $nq->getEpas($rr['record_no'],$emp);
    		$re = mysql_fetch_array($epas);
	  ?>     	
      
      	<table class="table table-bordered">
       		<tr>
            	<td width="15%">Position</td>
                <td width="35%"><i><b><?php echo $rr['position'];?></b></i></td>
				<td>Current Status</td>
                <td><i><b><?php echo $rr['current_status'];?></b></i></td>
            </tr>
            <tr>
            	<td>Position Level</td>
                <td><i><b><?php echo $rr['positionlevel'];?></b></i></td>				
            	<td>Start Date</td>
                <td><i><b><?php echo $nq->changeDateFormat('m/d/Y',$rr['startdate']);?></b></i></td>            
            </tr>
            <tr>
            	<td>Position Description</td>
                <td><i><b><?php echo $rr['position_desc'];?></b></i></td>
				<td>EOC Date</td>
                <td><i><b><?php echo $nq->changeDateFormat('m/d/Y',$rr['eocdate']);?></b></i></td>
            </tr>
        	<tr>
            	<td>Company</td>
                <td><i><b><?php echo $company = $nq->getCompanyName($rr['company_code']);?></b></i></td>					
				<td>Lodging</td>
                <td><i><b><?php echo $rr['lodging'];?></b></i></td>
            </tr>         
            <tr>
            	<td>Business Unit</td>
                <td><i><b><?php echo $bunit = $nq->getBusinessUnitName($rr['bunit_code'],$rr['company_code']); ?></b></i></td>
				<td>Remarks</td>
                <td><i><b><?php echo $rr['remarks'];?></b></i></td>
            </tr>
            <tr>
            	<td>Department</td>
                <td><i><b><?php echo $dept = $nq->getDepartmentName($rr['dept_code'],$rr['bunit_code'],$rr['company_code']);?></b></i></td>
				<td>Comments</td>
                <td><i><b><?php echo $rr['comments'];?></b></i></td>
            </tr>
            <tr>
            	<td>Section</td>
                <td><i><b><?php echo $sec = $nq->getSectionName($rr['section_code'],$rr['dept_code'],$rr['bunit_code'],$rr['company_code']);?></b></i></td>
				<td>Clearance</td>
                <td>
				<?php //echo $rr['clearance'];
				if($rr['clearance'] != ""){ ?>
				<input type='button' class='btn btn-primary btn-sm' name='clearance' id='clearance' value='view clearance' onclick=viewclearance('<?php echo $rr['clearance'];?>')></td>
				<?php } ?>   
            </tr>
            <tr>
            	<td>Sub Section</td>
				<td><i><b><?php echo $sub = $nq->getSubSectionName($rr['sub_section_code'],$rr['section_code'],$rr['dept_code'],$rr['bunit_code'],$rr['company_code']);?></b></i></td>
				<td>EPAS</td>
                <td>
				<?php echo $re['numrate']." - ".$re['descrate'];//echo $rr['clearance'];				
				if($rr['epas_code'] != 0 && $rr['epas_code'] != ''){ ?>
					<a href='#' onclick=viewepas('<?php echo $rr['record_no'];?>','<?php echo $emp;?>')><i>[ view epas details here ]</i></a></td>
				<?php } ?>
            </tr>
            <tr>
            	<td>Unit</td>
                <td><i><b><?php echo $runit = $nq->getUnitName($rr['unit_code'],$rr['sub_section_code'],$rr['section_code'],$rr['dept_code'],$rr['bunit_code'],$rr['company_code']);?></b></i></td>
				<td>Contract</td>
                <td>
                <?php 	
						
				$allowedq = mysql_query("SELECT * FROM allowed_view_contract WHERE emp_id = '".@$_SESSION['emp_id']."' ");
				if(mysql_num_rows($allowedq) !=0 ){					
					if($rr['contract'] != ""){ ?>
						<input type='button' class='btn btn-primary btn-sm' name='clearance' id='clearance' value='view contract' onclick=viewcontract('<?php echo $rr['contract'];?>')></td>
                <?php } 
					else{	echo "<span class='label label-danger'>No Scanned Contract Uploaded!!!</span>";
					}
				}?>    </td>
		    </tr>
            <tr>
            	<td>Employe Type</td>
                <td><i><b><?php echo $rr['emp_type'];?></b></i></td>
                <?php
                if($rr['emp_type'] == 'Regular'){
                	echo "
                	<td>Date Regular</td>
					<td>".$nq->changeDateFormat('m/d/Y',$rr['startdate'])."</td>";
                }
                ?>
            </tr>
            <tr>
             	<td>Record No</td>
				<td><?php echo $rec;?></td>

                <td>Date Hired</td>
				<td><?php echo $nq->changeDateFormat('m/d/Y',$dh);?></td>

            </tr> 			
        </table>      
      	<?php 
}
else{
echo "You are not login to HRMS or your current session has expired. Please log in <a href='../'>here</a>";
}		?>
<script>
function viewclearance(clearance){
	window.open("clearanceview.php?c="+clearance,'_blank');
}
function viewepas(rec,emp){
	window.open("epasview.php?table=employee3&rec="+rec+"&empid="+emp,'_blank');
}
function viewcontract(contract){
    window.open("contractview.php?c="+contract,'_blank');
}
</script>	
		