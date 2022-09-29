<div class="panel panel-default" style='margin-left:auto;margin-right:auto;width:99%;'>
	<div class="panel-heading"> <b> YEARS IN SERVICE REPORT </b> </div>
		<div class="panel-body">
			<?php include('companydetails.php'); ?>				
			<tr>
				<td><label>Order by</label> <i style='color:red'>(required)</i></td>
				<td>
					<select class="form-control" name="orderby">
						<option value=''>Select</option>
						<option value='1'>order by name</option>
						<option value='2'>order by employee type</option>
						<option value='3'>order by department, name</option>
					<select>
				</td>
			</tr>
			<br>				
			<tr>
				<td colspan='2' align='center'>
					<button type='submit' name='submit-excel' onclick='yrsInService_excel()' class='btn btn-primary btn-sm'> Generate in EXCEL <img src='../images/icons/excel-xls-icon.png'/></button> &nbsp;
				</td>
			</tr>
		</div>
	</div>      								
</div> 
<script type="text/javascript" src="pages/reports/report_js.js"> </script>