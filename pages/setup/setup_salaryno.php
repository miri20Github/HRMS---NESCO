<?php 
$columns = array("EMP NO","NAME","DATE HIRED","CURRENT STATUS","EMPTYPE","POSITION","PAYROLL NO");
?>

<style>
.select{ height: 25px; width: 100%}
.loading { background-image: url('images/loading19.gif');background-repeat:no-repeat; background-position:right;}
.ok { background-image: url('../images/icons/icn_active.gif');background-repeat:no-repeat; background-position:right;}
.notok { background-image: url('../images/icons/icon-close-circled-20.png');background-repeat:no-repeat; background-position:right;}
</style>

<div class="panel panel-default" style='margin-left:auto;margin-right:auto;width:99%;'>
	<div class="panel-heading"> <b> SETUP NESCO SALARY NUMBER </b> </div>
	<div class="panel-body">
		<table class="table table-striped" width="100%" id="setupsalnum" style='font-size:11px'>
			<thead>	
				<tr>
					<?php foreach($columns as $key => $value){
						echo "<th> $value</th>";
					}?>		
				</tr>	
			</thead>				
		</table>    
	</div>
</div>