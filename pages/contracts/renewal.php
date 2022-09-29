<?php
	$yesterday 	= date("Y-m-d", strtotime( '-1 days' ));	
	$datefrom 	= $nq->changeDateFormat("Y-m-d",@$_GET['datefrom']);
	$dateto 	= $nq->changeDateFormat("Y-m-d",@$_GET['dateto']); 
	$filter_one = @$_GET['filter1'];
	
	if($filter_one != "")
	{	
		switch($filter_one)
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
			case "today": $edate = date("Y-m-d"); break;
			case "yesterday": $edate = $yesterday;  break;						
		}
	}else if($datefrom !="" && $dateto !=""){		
		$edate =  "AND (eocdate  between '$datefrom' and '$dateto')";
		$title = "[ Date From : ".$nq->changeDateFormat("M d, Y",$datefrom)." | "."Date To : ".$nq->changeDateFormat("M d, Y",$dateto)." ]";
	}elseif(@$_GET['filter1'] == ""){		
		$edate = date("Y-m");
	}
	
	$query_eoc 	= mysql_query("SELECT record_no,employee3.emp_id, name, position, emp_type, startdate, eocdate, company_code, bunit_code, dept_code, clearance, epas_code 
		FROM employee3 
		WHERE current_status ='Active' AND eocdate !='' AND eocdate !='0000-00-00' AND eocdate !='0001-11-30' AND eocdate != '0000-11-30' AND
			(emp_type = 'NESCO Contractual' OR emp_type ='NESCO-PTA' OR emp_type ='NESCO-PTP') AND 
			(company_code !='07' and company_code !='11' and company_code !='12' and company_code !='18' and company_code !='19')
			$edate					
		")or die(mysql_error());	

	$columns = array("NAME","POSITION","DEPARTMENT","STARTDATE","EOCDATE","REMARKS","EPAS","ACTION");	
?>	
	<script type="text/javascript" src="../jquery/jquery-latest.min.js"></script> 
	<link rel="stylesheet" type="text/css" href="../css/sweetalert.css" media="screen, projection" /> 
	<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css" media="all"  />
	
	<style> #labelapp{ cursor:pointer;} </style>
	<script>
		$(document).ready(function() { $('#renewal').DataTable();	});
	</script>
   	
	<div class="panel panel-default">
		<div class="panel-heading"> <b> NESCO - END OF CONTRACT LIST OF EMPLOYEES <?php echo @$title;?> </b> </div>
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
						<td> <?php echo $re['remarks'];?></td>
						
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
										echo " <a href='#' class='btn btn-success btn-xs' onclick=getProbi_Dates('".trim($r['emp_id'])."') data-toggle='modal' data-target='#probationary_form'> For Probationary ?? </a> ";
									}							
								}
								else if($re['remarks'] == "Rescinds Employment" && $numrate < 85){
									echo "<a href='#' onclick=confirm('".trim($r['emp_id'])."') class='btn btn-primary btn-xs'>  Confirm??? </a>";					
								}
							echo "</td>";												
						}
				      	else{
							echo "<td><span class='label label-default'>none</span></td><td></td>";
				      	}?>	
					</tr>
				<?php } ?>
				</tbody>
			</table>
			
			<table width="100%">
		    	<tr>
		    		<td>
		    			Filter
		    			<select name='filter' id='filter' onchange="filter_reload('')">
		    				<option value='all'>All Company</option>
		    				<?php
		    				$res = $nq->getAllCompanyAcroname();
		    				while($rres = mysql_fetch_array($res))
		    				{?>
		    					<option value='<?php echo $rres['company_code'];?>' <?php if($rres['company_code'] == @$_GET['filter']){ echo "selected='selected'";}?> ><?php echo $rres['acroname'];?></option><?php
		    				}?>
		    			</select>
						<select name='filter1' id='filter1' onchange="filter_reload('')">
		                    <option value=''>All</option>
		                    <option value='today' <?php if($_GET['filter1'] == "today"){ echo "selected='selected'";}?> >Today</option>
							<option value='yesterday' <?php if($_GET['filter1'] == "yesterday"){ echo "selected='selected'";}?>>Yesterday</option>
		                </select>	
						<select name="month" id="month" onchange="filter_reload('month')">
						<option value="all">EOC Per Month</option>    
						<?php
							for($i=0;$i<count($nq->monthname());$i++){
								if($filter_one == $nq->monthno()[$i]){
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

	<!-- Modal -->
	<div class="modal fade" id="probationary_form"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  	<div class="modal-dialog modal-sm">
	    	<div class="modal-content">
		      	<div class="modal-header">
		        	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		        	<h4 class="modal-title" id="myModalLabel"> FOR PROBATIONARY </h4>
		      	</div>
		      	<div class="modal-body">
		      		<p> <i> Please Confirm the Employee's Probationary Period </i> </p>
		      		<hr>
		      		<input type='hidden' name='empid' id='empid'>
		      		<label> Start Date </label>
		      		<input type="text" class="form-control" placeholder='mm/dd/YYYY' name='startdate' id='startdate'>
		      		<br>
		      		<label> EOC Date </label>
		      		<input type="text" class="form-control" placeholder='mm/dd/YYYY' name='eocdate' id='eocdate'>
		      		<br><span id='probi_msg' style='color:red; font-style:italic'> </span>
		      	</div> 
		    	<div class="modal-footer">
		    		<button type="button" class="btn btn-success" id='submitprobi' onclick="save_Probi()">Submit</button>		
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
		$("#startdate" ).datepicker({ dateFormat: "mm/dd/yy", changeMonth: true, changeYear: true, showButtonPanel: true });	
		$("#eocdate" ).datepicker({ dateFormat: "mm/dd/yy", changeMonth: true, changeYear: true, showButtonPanel: true });	
	});	


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
		var filter 	=  $("#filter").val();  	
		var filter1 = $("#filter1").val();
		var datefrom= $("#datefrom").val();
		var dateto 	= $("#dateto").val();
		window.location = "index.php?p=renewal-list&&db=contracts&&filter="+filter+"&filter1="+filter1+"&datefrom="+datefrom+"&dateto="+dateto;
	}

	function filter_reload(mo)
	{
	    var filter =  $("#filter").val();  	
		if(mo == ''){
			var filter1 = $("#filter1").val();
		}else{
			var filter1 = $("#month").val();
		} 
		window.location = "index.php?p=renewal-list&&db=contracts&&filter="+filter+"&filter1="+filter1;
	}

	function confirm(empid)
	{	
		$.ajax({
			type: "POST",
			url : "functionquery.php?request=getemployee_EOCdate",
			data: { empid:empid },
			success : function(data){	
				if(data.trim() == 'true'){
					swal("Oppss","Please confirm on the day after the EOC Date. \n Error: Date Today <= EOC Date","error");
				}
				else // false
				{
					swal({
						title: "Confirm Status",
						text: "You are about to change employee's status to End of Contract (Uncleared), do it now?",
						type: "warning",
						showCancelButton: true,
						confirmButtonClass: "btn-danger",
						confirmButtonText: "YES!",		
						cancelButtonText: "NO!",
						closeOnConfirm: false,
						closeOnCancel: true
					},

					function(isConfirm) { 
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

	function getProbi_Dates(empid){
		$.ajax({
			type: "POST",
			url : "functionquery.php?request=getProbiDates",
			data: { empid:empid },
			success : function(data)
			{				
				var id = data.split("*");
				var res= id[0].trim();  
				var sd = id[1].trim();  
				var ed = id[2].trim();	

				$("[name='startdate']").val(sd);	
				$("[name='eocdate']").val(ed);	
				$("[name='empid']").val(empid);	
			}
		});
	}

	function save_Probi()
	{
		var startdate 	= $("[name='startdate']").val();	
		var eocdate 	= $("[name='eocdate']").val();
		var empid 		= $("[name='empid']").val();
		
		if(startdate == '' || startdate == '00/00/0000' || eocdate == '' || eocdate == '00/00/0000' ){
			swal("Oppss","Start Date and EOC Date must not be empty!","error");
		}else{	
			swal({
				title: "Confirm Employee For Probationary",
				text: "You are about to change employee's status to Probationary, do it now?",
				type: "warning",
				showCancelButton: true,
				confirmButtonClass: "btn-danger",
				confirmButtonText: "YES!",		
				cancelButtonText: "NO!",
				closeOnConfirm: false,
				closeOnCancel: true
			},

			function(isConfirm) { 
			  	if(isConfirm) { 		  	
				    
				    $.ajax({
						type: "POST",
						url : "functionquery.php?request=add_new_employment_probi",
						data: { empid:empid, startdate:startdate, eocdate:eocdate },
						success : function(data)
						{							
							if(data.trim() == "success"){
								swal("success","Employee is now on Probationary status!","success");
								setTimeout(function(){						
									location.reload(true);
								}, 2000);
							}else{
								swal("Oppss","Updating Error!","error");					
							}
						}
					});  
			  	}		
			});	
		}				
	}
</script>      