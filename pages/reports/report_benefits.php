<div class="panel panel-default" style='margin-left:auto;margin-right:auto;width:99%;'>
	<div class="panel-heading"> <b> EMPLOYEE BENEFITS REPORT </b> </div>
		<div class="panel-body">
			<?php include('companydetails.php'); ?>
			<tr>
				<td><label> Employee Type <i style='color:gray'>(required)</i>	</label></td>
				<td>
					<select name="etype" id="etype" class="form-control" required="required"> 
						<?php
						$emptype = array('All','NESCO-PTA','NESCO-PTP','NESCO Regular','NESCO Contractual','NESCO Partimer','NESCO Regular Partimer','NESCO Probationary','NESCO-BACKUP');
						for($i=0;$i<count($emptype);$i++ ){
							echo "<option value='".$emptype[$i]."' >".$emptype[$i]."</option>";    
						} ?>	
					</select>
				</td>
			</tr>
			<tr>
				<td><label> <br> BENEFITS NUMBER <i style='color:gray'>(required)</i>	</label></td>
				<td>
					<select name="benefitsno" id="benefitsno" class="form-control" required="required"> 
						<option value="all">All</option>		
						<option value="sss_no"> SSS NUM </option>
						<option value="philhealth"> PHILHEALTH </option>
						<option value="pagibig_tracking"> PAG IBIG RTN </option>
						<option value="pagibig"> PAG IBIG MID NO</option>
						<option value="tin_no"> TIN NO</option>
					</select>
				</td>
			</tr>	
			<br>
			<tr>
				<td colspan='2' align='center'>
					<?php echo "<button type='button' id='bday-excel' onclick='benefits_excel()' class='btn btn-primary btn-sm'> Generate in EXCEL <img src='../images/icons/excel-xls-icon.png'/></button> ";?>
				</td>
			</tr>
		</div>
	</div>
</div>

<script type="text/javascript" src="pages/reports/report_js.js"> </script>