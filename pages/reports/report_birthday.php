<div class="panel panel-default" style='margin-left:auto;margin-right:auto;width:99%;'>
	<div class="panel-heading"> <b> BIRTHDAY CELEBRANTS REPORT </b> </div>
		<div class="panel-body">
			<?php include('companydetails.php'); ?>
			<tr>
				<td><label>Birth Month </label> <i style='color:red;'>(required)</i></td>    
				<td><select name="birthmonth" id="birthmonth" class="form-control" required onchange="showdisplay()">
					<option value="all">All</option>    
					<?php
						for($i=0;$i<count($nq->monthname());$i++)
						{
							echo "<option value='".$nq->monthno()[$i]."''>".$nq->monthname()[$i]."</option>";
						}
					?>
					</select></td>  
			</tr>	
			<tr>
				<td><div id='disopt'><label> <br> Display Options </label> <i style='color:red;'>(for excel report only)</i></div></td>   
				<td><select name="displayopt" id="displayopt" class="form-control" required>
					<option value=""></option>    
					<option value="1">Sort Per month</option> 
					<option value="2">Sort by Name, Birthday</option>  
				
					</select>
				</td>  
			</tr>		
			<br>		
			<tr>
				<td colspan='2' align='center'>
					<?php	
					echo "<button type='button' id='bday-excel' onclick='bday_excel()' class='btn btn-primary btn-sm'> Generate in EXCEL <img src='../images/icons/excel-xls-icon.png'/></button> ";
					?>
				</td>
			</tr>
		</div>
	</div>
</div>
<script type="text/javascript" src="pages/reports/report_js.js"> </script>