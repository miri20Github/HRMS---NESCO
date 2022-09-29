<?php
	$record	= @$_GET['record'];	
	$key 	= @$_GET['search'];
	$emp 	= explode('*',$key);	
	$empid	= $emp[0];//$_GET['empid'];
	$res 	= $nq->getEmpInfo($empid);	

	while($row = mysql_fetch_array($res))
	{ 
		$name		= $row['name'];
		$pos		= $row['position'];
		$poslevel 	= $row['positionlevel'];
		$posdesc	= $row['position_desc'];
		$empcat 	= $row['emp_cat'];
		$emptype	= $row['emp_type'];
		$record		= $row['record_no'];
		
		$cc = $nq->getCompanyName($row['company_code']);
		$bc = $nq->getBusinessUnitName($row['bunit_code'],$row['company_code']);
		$dc = $nq->getDepartmentName($row['dept_code'],$row['bunit_code'],$row['company_code']);
		$sc = $nq->getSectionName($row['section_code'],$row['dept_code'],$row['bunit_code'],$row['company_code']);
		$ssc= $nq->getSubSectionName($row['sub_section_code'],$row['section_code'],$row['dept_code'],$row['bunit_code'],$row['company_code']);
	}	
	//$subordinate = $nq->getSubordinate($empid);
	//7/12/16 - miri
	$subordinate = mysql_query("SELECT l.record_no, l.subordinates_rater, l.ratee, e.emp_id, e.name, e.position, e.current_status, e.company_code, e.bunit_code, e.dept_code 
	FROM leveling_subordinates AS l INNER JOIN employee3 AS e on l.subordinates_rater = e.emp_id where ratee = '$empid' order by company_code, bunit_code, dept_code, current_status ");// nd current_status = 'Active'

	/*********GET THE CURRENT URL ****/
	$url  = @( $_SERVER["HTTPS"] != 'on' ) ? 'http://'.$_SERVER["SERVER_NAME"] :  'https://'.$_SERVER["SERVER_NAME"];
  	$url .= ( $_SERVER["SERVER_PORT"] !== 80 ) ? ":".$_SERVER["SERVER_PORT"] : "";
  	$url .= $_SERVER["REQUEST_URI"];

  	$url;
	/*****************/
	$employeetypee = "and (emp_type IN ('NESCO','NESCO Contractual','NESCO-PTA','NESCO-PTP','NESCO Regular','NESCO Regular Partimer','NESCO Probationary') )";
?>
<head>
	<!-- <script src="../jquery/jquery-2.0.3.min.js"></script> -->
	<link href='../datatables/jquery.dataTables.css' rel='stylesheet'/> 
	<script src="../datatables/jquery-1.11.1.min.js" type="text/javascript"></script>
	<script src="../datatables/jquery.dataTables.min.js" type="text/javascript"></script>

<script>
	$(document).ready(function() {
		$('#sub-table').DataTable();
	} );
</script>

<div class="panel panel-default" style='margin-left:auto;margin-right:auto;width:99%;'>
	<div class="panel-heading"> <b> SUBORDINATES SETUP</b> </div>
	<div class="panel-body">
		<div>
			<ul class="nav nav-tabs">
				<li id="tab-menu_ThisMonth" role="presentation" class="active" onclick="addsubordinate()"><a href="javascript:void(0)">ADD SUBORDINATE</a></li>
				<li id="tab-menu_NextMonth" role="presentation"><a href="javascript:void(0)"  onclick="addmultiple()">ADD MULTIPLE SUBORDINATES</a></li>
				<li id="tab-menu_NextMonth" role="presentation"><a href="javascript:void(0)"  onclick="removesubs()">REMOVE SUBORDINATES</a></li>	  
			</ul>   

		<div id='addsubordinate'> <br> 
			<label> Input Supervisor </label>
			<input list="se" id="supervisor" type="text" class="form-control" name="supervisor" autocomplete="off" placeholder="Lastname, Firstname Middlename"/>
			<datalist id="se">
				<?php 
				$res = mysql_query("SELECT employee3.emp_id, name FROM employee3 
						INNER JOIN users ON employee3.emp_id = users.emp_id
						WHERE usertype = 'supervisor' and current_status ='Active'");
				while($rs = mysql_fetch_array($res))
				{ 
					$ax=$rs['emp_id']."*".$rs['name'];?>
					<option value="<?php echo $ax;?>"><?php echo $ax;?></option><?php } ?>
			</datalist>
			<label> Input Subordinate</label>
			<input list="se" id="subordinates" type="text" class="form-control" name="subordinates" autocomplete="off" placeholder="Lastname, Firstname Middlename"/>
			<datalist id="se">
				<?php 
				$res = mysql_query("SELECT emp_id, name FROM employee3 
				WHERE current_status in ('active', 'End of Contract', 'Resigned', 'V-Resigned', 'Ad-Resigned') $employeetypee ");
				while($rs = mysql_fetch_array($res))
				{ 
					$ax=$rs['emp_id']."*".$rs['name'];?>
					<option value="<?php echo $ax;?>"><?php echo $ax;?></option><?php } ?>
			</datalist> <br>
			<input type='button' class="btn btn-primary" value='Submit' onclick='savesubordinate()'>
			<input type='button' class="btn btn-default" value='Clears' onclick='clears()'>
		</div> 

		<div id='addsupervisor' style="display:none">			
				<div class="form-group"> <BR>
					<label> Type the name of supervisor </label>
					<input list="se" id="multsupervisor" type="text" class="form-control" name="multsupervisor" autocomplete="off" placeholder="Search Name"/>
					<datalist id="se">
						<?php 
						$res = mysql_query("SELECT emp_id, name FROM employee3 WHERE current_status = 'active'  ");
						while($rs = mysql_fetch_array($res))
						{ 
							$ax=$rs['emp_id']."*".$rs['name'];?>
							<option value="<?php echo $ax;?>"><?php echo $ax;?></option><?php } ?>
					</datalist>
				</div>
				<?php include('companydetails.php'); ?>				
				<input type="button" class='btn btn-primary' value='Filter' onclick="showtable_subordates()" >		
				
				<div id='table-subordinates'>				
				</div>
		</div>

		<div id='removesubor' style="display:none">						
			<BR><label> Input Supervisor </label>
			<table>	
				<tr>		
					<td width='30%'>					 
						<input list="se" id="rem_sup" type="text" class="form-control" name="rem_sup" autocomplete="off" style='width:300px' placeholder="Search Name"/>
						<datalist id="se">
							<?php 
							$res = mysql_query("SELECT emp_id, name from employee3 where current_status = 'active'  ");
							while($rs = mysql_fetch_array($res))
							{ 
								$ax=$rs['emp_id']."*".$rs['name'];?>
								<option value="<?php echo $ax;?>"><?php echo $ax;?></option><?php } ?>
						</datalist>	
					</td>
					<td>
						<span class="input-group-btn">
							<input type='button' class='btn btn-primary' value='Go' onclick="removesubordinates()">	                        
						</span>     			            
					</td>
				</tr>
			</table>		
			
			<div id='removetable-subordinates'>
			</div>			
				
		</div>
	</div> 
</div>

<script>	
	function chk(x){
		if(x==0){
			if($(".chk_"+0).is(':checked')){
				$(".chkC").prop("checked", true);
			} else {			
				$(".chkC").prop("checked", false);
			}
		} else {
		}
	}

	function clears(){
		document.getElementById('supervisor').value = "";
		document.getElementById('subordinates').value = "";		
	}

	function showtable_subordates()
	{			
		var cc = $("[name = 'comp_code']").val();	
		var bc = $("[name = 'bunit_code']").val();	
		var dc = $("[name = 'dept_code']").val();	
		var sc = $("[name = 'sec_code']").val();
		var ssc = $("[name= 'ssec_code']").val();	

		var mulsup  = $("#multsupervisor").val();	
		var mulsup  = mulsup.split("*");
		var mulsup 	= mulsup[0];

		if(ssc !=""){ 	code = ssc; }
		else if(sc !=""){ 	code = sc; }
		else if(dc !=""){ 	code = dc; }
		else if(bc !=""){ 	code = bc; }
		else if(cc != ""){ 	code = cc; }
		else{ code = '';}	
		
		if(mulsup != "")
		{
			if(code == ''){ 
				alert('Please select a company'); 
			}else{ 				
				$.ajax({
					type : "POST",
					url : "functionquery.php?request=showtablesubordinates",
					data : { mulsup:mulsup,code:code },
					success : function(data){	
						$("#table-subordinates").html("<img src='../images/icons/ajax.gif'> please wait...");	
						setTimeout(function(){$("#table-subordinates").html(data);},500);
						//$("#table-subordinates").html(data);
					}
				});
			}	
		}else{
			alert('Please input first the name of supervisor');
		}
	}
	function addsubordinate(){
		$("#addsubordinate").show(); 
		$("#addsupervisor").hide(); 
		$("#removesubor").hide(); 
	}

	function addmultiple(){
		$("#addsubordinate").hide();
		$("#addsupervisor").show(); 
		$("#removesubor").hide(); 
	}

	function removesubs(){
		$("#addsubordinate").hide();
		$("#addsupervisor").hide(); 
		$("#removesubor").show(); 
	}

	function removesubordinates()
	{	
		var sup_val	= $("#rem_sup").val();	
		var sup   	= sup_val.split("*");
		var sup 	= sup[0];

		if(sup_val == ''){
			alert('Please do not leave empty field');
		}else{	
			$.ajax({
				type : "POST",
				url : "functionquery.php?request=removetablesubordinates",
				data : { sup:sup },
				success : function(data){	
					$("#removetable-subordinates").html("<img src='../images/icons/ajax.gif'> please wait...");		
					setTimeout(function(){$("#removetable-subordinates").html(data);},500);
				}
			});
		}
	}

	//mag erase nag sakop
	function removingsubordinate(){

		var rec_id	= document.getElementsByName('removesubordinates[]');	
		var rec 	= '';
		var x 		= 0; //counter sa number of checked employee
		var y 		= 0;

		for(var i=0;i<rec_id.length;i++)
		{
			if(rec_id[i].checked == true)
			{
				rec += rec_id[i].value+',';
				x++;
				$("input[name^='"+rec_id[i].value+"']").each(function(){
					if(this.value == ""){
						y++;
					}
				});   
			}
		}

		if(x == 0) {
			alert("You need to check at least one subordinate!");
			return false;
		}
		else if(y > 0){
			alert("Please dont leave empty subordinate");
			return false;
		}	
		else {		
			var r = confirm("Are you sure to remove subordinate/s?");
			if(r == true){
				$.ajax({
					type:"POST",
					url:"functionquery.php?request=deletesubordinates",
					data:{ rec:rec },
					success:function(data)
					{				
						alert(data);
						removesubordinates();
					}
				});	
			}
		}
	}

	function savesubordinate()
	{
		var sup_val	= $("#supervisor").val();	
		var sup   	= sup_val.split("*");
		var sup 	= sup[0];
		var sub_val	= $("#subordinates").val();
		var sub   	= sub_val.split("*");
		var sub 	= sub[0];
		
		if(sup == sub){
			alert('Supervisor and Subordinates CANNOT BE THE SAME PERSON! \nPlease choose another name.')
		}
		else if(sup == '' || sub == ''){
			alert('Please do not leave empty field!')
		}else{
			$.ajax({
				type : "POST",
				url : "functionquery.php?request=savesubordinate",
				data : { sub:sub, sup:sup },
				success : function(data){	
					alert(data)
					$("#supervisor").val("");
					$("#subordinates").val("");	
					
				}
			});
		}
	}

	function save_multsubordinate()
	{
		var sup_val	= $("#multsupervisor").val();	
		var sup   	= sup_val.split("*");
		var sup 	= sup[0]; //supervisors empid

		var sub_id	= document.getElementsByName('subordinates[]');	
		var emp 	= '';
		var x 		= 0; //counter sa number of checked employee
		var y 		= 0;

		for(var i=0;i<sub_id.length;i++)
		{
			if(sub_id[i].checked == true)
			{
				emp += sub_id[i].value+',';
				x++;
				$("input[name^='"+sub_id[i].value+"']").each(function(){
					if(this.value == ""){
						y++;
					}
				});   
			}
		}

		if(x == 0) {
			alert("You need to check at least one subordinate!");
			return false;
		}
		else if(y > 0){
			alert("Please dont leave empty subordinate");
			return false;
		}	
		else {		
			var r = confirm("Are you sure to save subordinates?");
			if(r == true){
				$.ajax({
					type:"POST",
					url:"functionquery.php?request=save_mult_subordinates",
					data:{ emp:emp, sup:sup },
					success:function(data)
					{				
						alert(data);
						showtable_subordates();
					}
				});	
			}
		}
	}

	jQuery(function($)
	{	 
		$("li[id^=tab-menu]").click(function(){
			var classname = this.className;
			var ids = this.id.split("_");
			$("li[id^=tab-menu]").removeClass("active");
			$(this).addClass("active");
			
		});

		var rater = '<?php echo $empid;?>';

		$("[name='comp_code']").change(function(){
			var id = this.value;
			$.ajax({
				type : "POST",
				url : "ajax.php?load=bunit",
				data : { id : id },
				success : function(data){
					$("[name='bunit_code']").html(data);
					$("[name='dept_code']").val('');
					$("[name='sec_code']").val('');
					$("[name='ssec_code']").val('');
					$("[name='unit_code']").val('');
				}
			});
				
			if(id == "05" || id == "06"){
				$("#sub-list").html('<img src="../images/system/10.gif" id="loading-gif" style="display:none; position:absolute; margin-left:370px;margin-top:200px;">');	
				$("#loading-gif").show();
				$.ajax({
					type : "POST",
					url : "add_new_subordinates.php?req=cc",
					data : { id : id, rater:rater },
					success : function(data){				
						$("#sub-list").html(data);				
						$("#loading-gif").hide();
					}
				});
			}
		});

		$("[name='bunit_code']").change(function(){
			var id = this.value;
			$.ajax({
				type : "POST",
				url : "ajax.php?load=dept",
				data : { id : id },
				success : function(data){
					$("[name='dept_code']").html(data);
					$("[name='sec_code']").val('');
					$("[name='ssec_code']").val('');
					$("[name='unit_code']").val('');
				}
			});


			if(id != "")
			{
				//this.attr('disabled',true);	
				$("#sub-list").html('<img src="../images/system/10.gif" id="loading-gif" style="display:none; position:absolute; margin-left:370px;margin-top:200px;">');	
				$("#loading-gif").show();
				$.ajax({
					type : "POST",
					url : "add_new_subordinates.php?req=bc",
					data : { id : id , rater: rater},
					success : function(data){	
						//alert(data)			
						$("#sub-list").html(data);				
						$("#loading-gif").hide();
					}
				});
			}
		});
		$("[name='dept_code']").change(function(){
			var id = this.value;
			$.ajax({
				type : "POST",
				url : "ajax.php?load=section",
				data : { id : id },
				success : function(data){
					$("[name='sec_code']").html(data);
					$("[name='ssec_code']").val('');
					$("[name='unit_code']").val('');
				}
			});

			if(id != "")
			{
				//this.attr('disabled',true);	
				$("#sub-list").html('<img src="../images/system/10.gif" id="loading-gif" style="display:none; position:absolute; margin-left:370px;margin-top:200px;">');	
				$("#loading-gif").show();
				$.ajax({
					type : "POST",
					url : "add_new_subordinates.php?req=dc",
					data : { id : id , rater:rater },
					success : function(data){	
						//alert(data)			
						$("#sub-list").html(data);				
						$("#loading-gif").hide();
					}
				});
			}

		});
		$("[name='sec_code']").change(function(){
			var id = this.value;
			$.ajax({
				type : "POST",
				url : "ajax.php?load=ssection",
				data : { id : id },
				success : function(data){
					$("[name='ssec_code']").html(data);
					$("[name='unit_code']").val('');
				}
			});

			if(id != "")
			{
				//this.attr('disabled',true);	
				$("#sub-list").html('<img src="../images/system/10.gif" id="loading-gif" style="display:none; position:absolute; margin-left:370px;margin-top:200px;">');	
				$("#loading-gif").show();
				$.ajax({
					type : "POST",
					url : "add_new_subordinates.php?req=sc",
					data : { id : id , rater:rater},
					success : function(data){	
						//alert(data)			
						$("#sub-list").html(data);				
						$("#loading-gif").hide();
					}
				});
			}

		});
		$("[name='ssec_code']").change(function(){
			var id = this.value;		
			$.ajax({
				type : "POST",
				url : "ajax.php?load=unit",
				data : { id : id },
				success : function(data){
					$("[name='unit_code']").html(data);
				}
			});

			if(id != "")
			{
				//this.attr('disabled',true);	
				$("#sub-list").html('<img src="../images/system/10.gif" id="loading-gif" style="display:none; position:absolute; margin-left:370px;margin-top:200px;">');	
				$("#loading-gif").show();
				$.ajax({
					type : "POST",
					url : "add_new_subordinates.php?req=ssc",
					data : { id : id , rater:rater },
					success : function(data){	
						//alert(data)			
						$("#sub-list").html(data);				
						$("#loading-gif").hide();
					}
				});
			}
		});	
	});
	function remove_sub(){
		//get sa ge checkan nga checkbox
		var rater = '<?php echo $empid;?>';
		var a = document.getElementsByName('recordsub[]');	
		var rec = '';
		var x = 0;
		for(var i = 0;i<a.length;i++) {
			if(a[i].checked == true) {
				rec += a[i].value+',';
				x++;
			}
		}
		if(x == 0) {
			alert("You need to check a subordinate to remove!");
			return false;
		} else {
			var r = confirm('Are you really sure to remove this/these subodinate/s?');
			if(r == true)
			{			
				$.ajax({
					type: "POST",
					url: "functionquery.php?request=removesubordinates",
					data: { rec:rec, rater:rater },
					success: function(data)
					{										
						alert(data);
						window.location = '<?php echo $url;?>';
					}
				});	
			}else{			
				window.location = '<?php echo $url;?>';	
			}			
		} 
	}
	function addsubordinates(){	
		var rater = '<?php echo $empid;?>';
		var a = document.getElementsByName('rater[]');	
		var emp = '';
		var x = 0;
		for(var i = 0;i<a.length;i++) {
			if(a[i].checked == true) {
				emp += a[i].value+',';
				x++;
			}
		}	

		if(x == 0) {
			alert("You need to check a subordinate to be added!");
			return false;
		} else {
			if(!confirm("Are you really sure to add this/these subodinate/s?")) return false;		
			$.ajax({
				type: "POST",
				url: "functionquery.php?request=addsubordinates",
				data: { emp:emp , rater:rater },
				success: function(data)
				{										
					alert(data);
					window.location = '<?php echo $url;?>';
				}
			});		
		} 
	}	
</script>