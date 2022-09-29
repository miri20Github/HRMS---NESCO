<?php 
$columns = array("NO","EMPLOYEE'S NAME","USERNAME","POSITION","EMPTYPE","CURRENT STATUS","USER STATUS","ACTIONS");
?>

<div class="panel panel-default" style='margin-left:auto;margin-right:auto;width:99%;'>
	<div class="panel-heading"> <b> MANAGE USER ACCOUNT</b> </div>
	<div class="panel-body">
	    <table class="table table-striped" width="100%" id="employeeusers" style='font-size:11px'>
			<thead>	
            	<tr>
					<?php foreach($columns as $key => $value){
						echo "<th> $value </th>";
					}?>			
                </tr>	
			</thead>				
        </table>    
	</div>
</div>		 