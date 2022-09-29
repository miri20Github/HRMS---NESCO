<div class="panel panel-default" style='margin-left:auto;margin-right:auto;width:99%;'>
	<div class="panel-heading"> <b> EMPLOYEE STATISTICS REPORT </b> </div>
		<div class="panel-body">
				<?php include('companydetails.php'); ?>
				<b>Click checkbox to include in the report </b>
				<br>
				<input type='checkbox'  id='showsections' > Show Sections <br>
				<input type='checkbox'  id='showsubsections' > Show Sub Sections <br>
				<br>		
			<button type='button' id='bday-excel' onclick='statistics_excel()' class='btn btn-primary btn-sm'> Generate in EXCEL <img src='../images/icons/excel-xls-icon.png'/></button>
		</div>
	</div>
</div>	
<script type="text/javascript" src="pages/reports/report_js.js"> </script>