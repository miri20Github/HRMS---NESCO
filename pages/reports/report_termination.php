
<div class="panel panel-default" style='margin-left:auto;margin-right:auto;width:99%;'>
	<div class="panel-heading"> <b> TERMINATION REPORT </b> </div>
		<div class="panel-body">
			<?php include('companydetails.php'); ?>
			<tr>
				<td><label>Month</label> <i style='color:red'>(required)</i></td>
				<td>
					<select name='month' id='month' class="form-control" >
						<option></option>
						<?php $y = date('Y')+1;
							for($i=0;$i<count($nq->monthname());$i++){
								echo "<option value='".$nq->monthno()[$i]."''>".$nq->monthname()[$i]."</option>";
							}
							echo "<option value='nextyear'>January ".$y."</option>";
						?>
					</select>
				</td>
			</tr>
			<tr>	
				<td> <label> <br>Employee Type </label> </b></td> 
				<td>      
					<select class="form-control" name="emptype" id='emptype'>				
						<?php
						$emptype = array('All','NESCO-PTA','NESCO-PTP','NESCO Regular','NESCO Contractual','NESCO Partimer','NESCO Regular Partimer','NESCO Probationary','NESCO-BACKUP');
						for($i=0;$i<count($emptype);$i++ ){
							echo "<option value='".$emptype[$i]."' >".$emptype[$i]."</option>";    
						} ?>						
					</select>
				</td>   
			</tr>
			<br>
			<tr>
				<td colspan='2' align='center'>
					<button type='submit' name='submit-excel' onclick='xls_rep()' class='btn btn-primary btn-sm'> Generate in EXCEL <img src='../images/icons/excel-xls-icon.png'/></button> &nbsp;
					<button type='button' name='submit-pdf' class='btn btn-primary btn-sm' onclick='pdf_rep()'> Generate in PDF <img src='../images/icons/pdf-icon.png'/></button> &nbsp;
				</td>
			</tr>
		</div>
	</div>
</div>

<script type="text/javascript" src="pages/reports/report_js.js"> </script>
<script>
	function xls_rep()
	{		
		code = getCode();
		var mo = document.getElementById('month').value;
		var et = document.getElementById('emptype').value;
		
		window.location = "pages/reports/excel_reports.php?code="+code+"&filename=termination_report&rname=termination-report&et="+et+"&mo="+mo;	
	}

	function pdf_rep()
	{	
		code 	= getCode();		
		var mo 	= document.getElementById('month').value;
		var et 	= document.getElementById('emptype').value;
		window.open("pages/reports/report_termination_pdf.php?code="+code+"&et="+et+"&mo="+mo, "_blank");		
	}
</script>