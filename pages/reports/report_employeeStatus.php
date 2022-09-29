<div class="panel panel-default" style='margin-left:auto;margin-right:auto;width:99%;'>
	<div class="panel-heading"> <b> EMPLOYEE STATUS REPORT </b> </div>
		<div class="panel-body">
			<?php include('companydetails.php'); ?>
			<tr>
				<td><label> Current Status <i style='color:gray'>(required)</i>	</label></td>
				<td>
					<select name="status" id="status" class="form-control" required="required"> 
						<option value=""></option>
						<?php
						$status = array('Active','End of Contract','Resigned','V-Resigned','Ad-Resigned','Retrenched','Deceased','Blacklisted');
						for($i=0;$i<count($status);$i++ ){
							echo "<option value='".$status[$i]."' >".$status[$i]."</option>";    
						} ?>
					</select>
				</td>
			</tr>	
			<tr>
				<td><label> <br> Employee Type <i style='color:gray'>(required)</i>	</label></td>
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
			<br>	
			<tr>
				<td colspan='2' align='center'>
					<button type='button' id='bday-excel' onclick='empStat_excel()' class='btn btn-primary btn-sm'> Generate in EXCEL <img src='../images/icons/excel-xls-icon.png'/></button>
				</td>
			</tr>
		</div>
	</div>
</div>	

<script type="text/javascript" src="pages/reports/report_js.js"> </script>