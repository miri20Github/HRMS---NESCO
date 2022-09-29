<?php
	$datefrom 	= $nq->changeDateFormat("Y-m-d",@$_GET['datefrom']);
	$dateto 	= $nq->changeDateFormat("Y-m-d",@$_GET['dateto']);
	$filter 	= @$_GET['filter'];

	if($filter != "")
	{	
		$yesterday 	= date("Y-m-d", strtotime( '-1 days' ));	
		switch($filter)
		{						
			case "01": $edate = "AND eocdate like '".date("Y-01")."%' "; $title = "[ JANUARY ]"; break;
			case "02": $edate = "AND eocdate like '".date("Y-02")."%' "; $title = "[ FEBRUARY ]"; break;
			case "03": $edate = "AND eocdate like '".date("Y-03")."%' "; $title = "[ MARCH ]"; break;
			case "04": $edate = "AND eocdate like '".date("Y-04")."%' "; $title = "[ APRIL ]"; break;
			case "05": $edate = "AND eocdate like '".date("Y-05")."%' "; $title = "[ MAY ]"; break;
			case "06": $edate = "AND eocdate like '".date("Y-06")."%' "; $title = "[ JUNE ]"; break;
			case "07": $edate = "AND eocdate like '".date("Y-07")."%' "; $title = "[ JULY ]"; break;
			case "08": $edate = "AND eocdate like '".date("Y-08")."%' "; $title = "[ AUGUST ]"; break;
			case "09": $edate = "AND eocdate like '".date("Y-09")."%' "; $title = "[ SEPTEMBER ]"; break;
			case "10": $edate = "AND eocdate like '".date("Y-10")."%' "; $title = "[ OCTOBER ]"; break;
			case "11": $edate = "AND eocdate like '".date("Y-11")."%' "; $title = "[ NOVEMBER ]"; break;
			case "12": $edate = "AND eocdate like '".date("Y-12")."%' "; $title = "[ DECEMBER ]"; break;	
		}
	}
	else if($_GET['datefrom'] !="" && $_GET['dateto'] !=""){
		$edate =  "AND (eocdate  between '$datefrom' and '$dateto')";
		$title = "[ Date From : ".$nq->changeDateFormat("M d, Y",$datefrom)." | "."Date To : ".$nq->changeDateFormat("M d, Y",$dateto)." ]";
	}
	else{		
		$edate = "AND eocdate like '".date("Y-m")."%' ";
	}
		
	$query_eoc 	= mysql_query("SELECT record_no,employee3.emp_id, name, position, emp_type, startdate, eocdate, company_code, bunit_code, dept_code, clearance, epas_code 
		FROM employee3 
		WHERE current_status ='Active' AND emp_type = 'NESCO Probationary' AND
			(company_code !='07' and company_code !='11' and company_code !='12' and company_code !='18' and company_code !='19')
			$edate					
		")or die(mysql_error());

	$columns = array("NAME","POSITION","DEPARTMENT","STARTDATE","EOCDATE","REMARKS","EPAS","ACTION");
?>	

	<link rel="stylesheet" href="../css/sweetalert.css" type="text/css" media="screen, projection" /> 
	<link rel="stylesheet" type="text/css" media="all" href="../css/jquery-ui.css" />
	<script type="text/javascript" src="assets/js/jquery-latest.min.js"></script>

	<style> #labelapp{ cursor:pointer;} </style>
	<script>
		$(document).ready(function() { $('#renewal').DataTable();	});
	</script>
   	
		<div class="panel panel-default">
			<div class="panel-heading"> <b> NESCO PROBATIONARY  LIST OF EMPLOYEES <?php echo @$title;?> </b> </div>
			<div class="panel-body">	

				<table class="table table-striped table-bordered" id="renewal">
					<thead>		
						<tr> 
							<?php foreach($columns as $key => $value){
								echo "<th> $value </th>";
							}?>
						</tr>
					</thead>
					<tbody>
						<?php
						while($r = mysql_fetch_array($query_eoc))
						{		
							$getEpas 	= mysql_query("SELECT numrate, ratercomment, rateecomment, rater, raterSO, details_id, rateeSO, remarks
								FROM appraisal_details		
								WHERE record_no = '$r[record_no]' and emp_id = '$r[emp_id]' limit 1 ");	
							$re 		= mysql_fetch_array($getEpas);	

							$numrate 	= $re['numrate'];
							$ratercom	= $re['ratercomment'];
							$rateecom 	= $re['rateecomment'];
							$raterSO	= $re['raterSO'];
							$rateeSO	= $re['rateeSO'];
							$did 		= $re['details_id'];
							$rater      = utf8_encode($nq->getEmpName($re['rater']));

							if($raterSO == 1){ $rso = "<span class='label label-success'>yes</span>";} else { $rso = "<span class='label label-warning'>no</span>";}
							if($rateeSO == 1){ $eso = "<span class='label label-success'>yes</span>";} else { $eso = "<span class='label label-warning'>no</span>";}
						?>
						<tr>
							<td> <a href='?p=employee&com=<?php echo $r['emp_id'];?>'> <?php echo ucwords(strtolower(utf8_encode($r['name'])));?> </a> </td>
							<td> <?php echo $r['position'];?></td>
							<td> <?php echo $nq->getBUAcroname($r['bunit_code'],$r['company_code']);?> 
								/ <?php echo $nq->getDeptAcroname($r['dept_code'],$r['bunit_code'],$r['company_code']);?></td>

							<td> <?php echo $nq->changeDateFormat("m/d/Y",$r['startdate']);?></td>
							<td> <?php echo $nq->changeDateFormat("m/d/Y",$r['eocdate']);?></td>
							<td> <?php echo strlen($re['remarks']) > 12 ? substr($re['remarks'],0,12)."..." : $re['remarks'];?></td>
							<?php	
							if($numrate > 0)
							{
								if($numrate == 100){							$label = "label label-success"; }
								else if($numrate >= 90 && $numrate <= 99.9){	$label = "label label-primary"; }
								else if($numrate >= 85 && $numrate <= 89.9){	$label = "label label-info";    }
								else if($numrate >= 70 && $numrate <= 84.9){    $label = "label label-warning"; }
								else if($numrate >= 0 && $numrate <= 69.9) { 	$label = "label label-danger";  }

								echo "<td><span class='$label' id='labelapp' title='Click to view Appraisal details' data-toggle='modal' data-target='#previewdetails' onclick='viewdetails($did)'>$numrate</span></td>";
								echo "<td>";
								if($numrate >=85){
									if($raterSO == 1 && $rateeSO == 1){
									echo "<select onchange=\"proceedto(this.value,'".$r['emp_id']."')\" >";							
											echo "<option>Proceed to </option>								 
											<option value='Show Regularization'>Show Regularization Form</option>																					
											<option value='Regularization'>Regularization</option>																					
										</select>";
									}							
								}							
								else{
									echo "<a href='#' onclick=confirm('".trim($r['emp_id'])."') class='btn btn-primary btn-xs'>  Confirm?? </a>";					
								}
								echo "</td>";							
							}
							else{
								echo "<td><span class='label label-default'>none</span></td><td></td>";
							} ?>	
						</tr>
					<?php } ?>
					</tbody>
				</table>
				
				<table width="100%">
			    	<tr>
			    		<td>
							<select name="filter" id="filter" onchange="filter_reload()">
							<option value="all">EOC Per Month</option>    
							<?php
								for($i=0;$i<count($nq->monthname());$i++){
									if($filter == $nq->monthno()[$i]){
										echo "<option value='".$nq->monthno()[$i]."' selected>".$nq->monthname()[$i]."</option>";
									}else{
										echo "<option value='".$nq->monthno()[$i]."'>".$nq->monthname()[$i]."</option>";
									}
								}
							?>
							</select>	

							&nbsp; Filter Dates: 
							<input type="text" placeholder='Date From' id='datefrom'>						
							<input type="text" placeholder='Date To' id='dateto'>		
							<input type="button" class="btn btn-success btn-sm" onclick="filterdate()" value="Go">
			    		</td>    	
			    	</tr>
			    </table> 	
    </div>

	<!-- Modal -->
	<div class="modal fade" id="preview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  	<div class="modal-dialog">
	    	<div class="modal-content">
		      	<div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			        <h4 class="modal-title" id="myModalLabel"> <span id='comtitle'><h1><b></b></h1></span> </h4>
		      	</div>
		      	<div class="modal-body">		
					<p><h4><span id='ratercomment'></span></h4></p>					
		      	</div> 
		    	<div class="modal-footer">
		    		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>		
			  	</div>          
		    </div> <!--/.modal-content -->
	  	</div> <!--/.modal-dialog -->
	</div><!-- /.modal -->

	<!-- Modal -->
	<div class="modal fade" id="previewdetails"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  	<div class="modal-dialog">
	    	<div class="modal-content">
		      	<div class="modal-header">
		        	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		        	<h4 class="modal-title" id="myModalLabel"> APPRAISAL DETAILS</h4>
		      	</div>
		      	<div class="modal-body" id='appdet'></div> 
		    	<div class="modal-footer">
		    		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>		
			  	</div>          
		    </div> <!--/.modal-content -->
	  	</div> <!--/.modal-dialog -->
	</div><!-- /.modal -->

<script src="../jquery/sweetalert.js" ></script>
<script type="text/javascript" src="../jquery/jquery-ui.js"></script> 
<script>

	$(document).ready(function() { 
		$("#datefrom" ).datepicker({ dateFormat: "mm/dd/yy", changeMonth: true, changeYear: true, showButtonPanel: true });	
		$("#dateto" ).datepicker({ dateFormat: "mm/dd/yy", changeMonth: true, changeYear: true, showButtonPanel: true });	
	});

	function proceedto(value,empid)
	{	
		$.ajax({
			type: "POST",
			url : "functionquery.php?request=getemployee_EOCdate",
			data: { empid:empid },
			success : function(data){						
				if(data.trim() == 'true'){ // dli pa pwede
					swal("Oppss","You can only print Regularization Form after the end of Probationary Date.","error");
				}
				else // false
				{	
					switch(value){
						case "Regularization": window.location = "?p=toregular&&db=entries"; break;	
						case "Show Regularization": 
							alert("Generating Regularization Form...");
							window.open("../report/regularization.php?empid="+empid,"new");
						break;	
					}
				}
			}
		});		
	}

	function viewdetails(dId){			
		$.ajax({
			type: "POST",
			url : "functionquery.php?request=viewDetails",
			data: { dId:dId },
			success : function(data){						
				$('#appdet').html(data);	
			}
		});
	}

	function filterdate()
	{			
		var datefrom= $("#datefrom").val();
		var dateto 	= $("#dateto").val();
		window.location = "index.php?p=probationary-list&&db=contracts&&datefrom="+datefrom+"&dateto="+dateto;
	}

	function filter_reload()
	{
	    var filter 	=  $("#filter").val(); 
		window.location = "index.php?p=probationary-list&&db=contracts&&filter="+filter;
	}

	function confirm(empid)
	{

		$.ajax({
			type: "POST",
			url : "functionquery.php?request=getemployee_EOCdate",
			data: { empid:empid },
			success : function(data){						
				if(data.trim() == 'true'){ // dli pa pwede
					swal("Oppss","You can only change status after the Probationary End Date.","error");
				}
				else // false
				{	
					swal({
						title: "Change of Status Confirmation",
						text: "You are about to confirm non-renewal of employment. Be sure that the supervisor has already confirmed the employee's status. Click 'YES' to change status to End of Contract (Uncleared).",
						type: "warning",
						showCancelButton: true,
						confirmButtonClass: "btn-danger",
						confirmButtonText: "Yes!",
						cancelButtonText: "No!",
						closeOnConfirm: false,
						closeOnCancel: true
					},

					function(isConfirm) { //if ni confirm either yes or no
					  	if(isConfirm) {			  		
					  		$.ajax({
								type: "POST",
								url : "functionquery.php?request=confirmed_Change_Status",
								data: { empid:empid },
								success : function(data){	
									
									if(data.trim() == "success"){
										swal("success","Employee's Status now Change to \n End of Contract (Uncleared)","success");
										setTimeout(function(){						
											location.reload(true);
										}, 1000);

									}else{
										swal("Oppss","Updating Failed!","error");
									}
								}
							});		
					  	}
					});	
				}
			}
		});
	}

</script>      