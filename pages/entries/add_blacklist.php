<?php
$blno 	= @$_GET['blno'];
$empid	= @$_GET['emp']; 

$name 	= $nq->getAppName($empid);
$datead = date('m/d/Y');

$dates 	= date("F d, Y");
$blno 	= @$_GET['blno'];
$empid	= @$_GET['emp'];

if(isset($blno))
{
	//$name= $nq->getEmpName($emp);
	$editbl = mysql_query("SELECT * FROM blacklist where blacklist_no = '$blno' ");
	while($r = mysql_fetch_array($editbl))
	{
		$blno 	= $r['blacklist_no'];
		$datebl = $r['date_blacklisted'];
		$datead = $nq->changeDateFormat('m/d/Y',$r['date_added']);
		$reportedby = $r['reportedby'];
		$reason = $r['reason'];
		$name   = $r['name'];
		$emp    = $r['app_id'];		
		$bdays	= @$r['bday'];
		
		if($bdays == '' || $bdays == '0000-00-00'){
			$bdays = '';			
		}else{ $bdays = $nq->changeDateFormat('m/d/Y',$r['bday']); }
		$addr	= @$r['address'];
	}
	if($emp != ""){
		$employeename   = $emp."*".$name;
	}
	else{
		$employeename   = $name;
	}
}
else{
	$name = $nq->getAppName($empid);
	$datead = date('m/d/Y');
}

?>
<style type="text/css">	
	.size-emp { max-height: 400px;overflow-y: scroll; }
</style>

<div class="panel panel-default">
	<div class="panel-heading">
	<span style='font-size:20px'><?php if(!isset($blno)){ echo "Add New Blacklist Entry"; } else { echo "Update Blacklisted Employee";} ?> </span> </div>
	<div class="row" style="padding:10px 40px 10px 40px">
		
		<b>Employee (<i style='color:gray'>required</i>)</b></p>	
		<input type="hidden" id="creator"  value="<?php echo @$_SESSION['username'];?>" />	
		<input type="hidden" size="60" class="form-control" name="blno" id="blno" disabled='disabled' value="<?php echo @$blno;?>" />
		<div class="row">
			<div class="col-md-12">	
				<div class="input-group">
					<input type='text' id="namesearch" type="text" class="form-control" disabled="" name="namesearch" value="<?php if(@$_GET['emp']){ echo $empid."*".$name; } else{ echo @$employeename;} ?>"> 
					<span class='input-group-btn'>
						<button class='btn btn-primary' <?php if(isset($blno) || isset($_GET['emp'])){ echo "disabled";}?> data-backdrop='static' data-keyboard='false' data-toggle='modal' data-target='#viewexamdetails' onclick='clearfieldsonbrowse()'>Browse &nbsp;<i class='glyphicon glyphicon-search'></i></button>
					</span>
				</div>
			</div>
		</div>
		<br>
	<table class="table ">	
		<tr>
			<td colspan="2">
				Reason (<i style='color:gray'>required</i>)
			</td>
		</tr>
		<tr>
			<td colspan='2'>
				<textarea cols="70" class="form-control" required name="reason" rows="2" id="reason" <?php if(!isset($blno) && !isset($_GET['emp'])){ echo "disabled='disbled'"; }?>><?php echo @$reason;?></textarea>
			</td>
		</tr>
		<tr>
			<th>Date Blacklisted (<i style='color:gray'>required</i>)</th>			
			<th>Reported by (<i style='color:gray'>required</i>)</th>
		</tr>
		<tr>			
			<td><input type="text" size="60" class="form-control" required name="dateblacklist" id="datebls" placeholder='mm/dd/yyyy' <?php if(!isset($blno) && !isset($_GET['emp'])){ echo "disabled='disbled'"; }?> value='<?php echo $nq->changeDateFormat('m/d/Y',@$datebl);?>'/></td>
			<td><input type="text" required class="form-control" onKeyUp='checkName(this)' required size="60" name="reportedby" id="reportedby" placeholder="Firstname Lastname" <?php if(!isset($blno) && !isset($_GET['emp'])){ echo "disabled='disbled'"; }?> value='<?php echo @$reportedby;?>'/></td>
		</tr>
		<tr>
			<th>Birthday (<i style='color:gray'>optional</i>)</th>			
			<th>Address (<i style='color:gray'>optional</i>)</th>
		</tr>
		<tr>			
			<td><input type="text" size="60" class="form-control" required name="bdays" id="bdays" placeholder='mm/dd/yyyy' <?php if(!isset($blno) && !isset($_GET['emp'])){ echo "disabled='disbled'"; }?> value='<?php echo @$bdays;?>' /></td>
			<td><input type="text" required class="form-control" onKeyUp='checkName(this)' required size="60" name="addr" id="addr"  <?php if(!isset($blno) && !isset($_GET['emp'])){ echo "disabled='disbled'"; }?>  value='<?php echo @$addr;?>'/></td>
		</tr>
		<tr>
			<td rowspan='2'><i style='font-color:red'> Note: Please fill-in all the required fields. Thank you! </i> </td>
		</tr>
		<tr><td></td></tr>
		<tr>
			<td colspan='4'><center> 
				<?php if(!isset($blno) && !isset($_GET['emp'])){ ?>
					<input type="submit" class="btn btn-primary" name="submit" id="submit" value="Save" disabled='disbled'  onclick ='submitBL()'/>
					<input type="button" class="btn btn-default" name="reset" id="reset" value="Reset" onclick="resetform()" <?php if(!isset($blno) && !isset($_GET['emp'])){ echo "disabled='disbled'"; }?>/>
				<?php }else{?>
					<input type="submit" class="btn btn-primary" name="edit-submit" id="edit-submit" onclick='editSubmit()' value="Update"/>
					<input type="button" class="btn btn-default" name="cancel" onclick='cancel()' value="Back"/><?php
				}?>	</center>	
			</td>
		</tr>
	</table>	
</div>

<!-- Modal  O P E N   E M P L O Y M E N T-->
<div class="modal fade" id="viewexamdetails" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:40%; height:100%;width:60%">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Browse For Possible Names</i></h4>
			</div>
			<div class="modal-body" id='browsenames'> 
				1) You are advised to search the lastname first to find out if the one being searched is blacklisted.<br>
				2) if no results found, that indicates that the one being search is not an applicant nor an employee.<br>
				<b>Search</b> 
				<input type='text' id='lname' placeholder='lastname' style='height:29px;width:200px' >
				<input type='text' id='fname' placeholder='firstname' style='height:29px;width:200px' >
				<button onclick='browsenames()' class='btn btn-primary btn-sm'>Search</button>
				<!--<button class='btn btn-primary btn-sm' id='choosebtn' style='display:none;'>Choose to blacklist</button>-->
			
				<div id='nonemp' style='display:none;'>	
					<hr>
					<i style='color:red'>No Results found. Kindly fill up the textbox below to blacklist non-applicant or non-employee.</i><br>
					<table>
						<tr>
							<td>LASTNAME <i style='color:gray'>(required)</i></td>
							<td>FIRSTNAME <i style='color:gray'>(required)</i></td>
							<td>MIDDLENAME <i style='color:gray'>(if there is any)</i></td>
						</tr>
						<tr>
							<td><input type='text' id='lasname' onKeyUp='checkName(this)' placeholder='lastname' style='height:29px;width:200px' ></td>
							<td><input type='text' id='firsname' onKeyUp='checkName(this)' placeholder='firstname' style='height:29px;width:200px' ></td>
							<td><input type='text' id='middlename' onKeyUp='checkName(this)' placeholder='middlename' style='height:29px;width:200px' ></td>
							<td><button class='btn btn-primary btn-sm' id='choosebtn' onclick='choosetobl()' >Choose to blacklist</button>	</td>
						</tr>								
					</table>
				</div>
				
				<div id='resultbrowse' class="size-emp" style="display:none;"></div>
				<div class=".col-md-9 .col-md-push-3" id='sub-list'>
					<img src="../images/system/10.gif" id='loading-gif' style='display:none;'>	
				</div>		
			</div>
			<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>

 $(document).ready(function(){
	$("#lname").keypress(function(event){
        var inputValue = event.charCode;
        if((inputValue > 47 && inputValue < 58) && (inputValue != 32)){
            event.preventDefault();
        }
    });
	$("#fname").keypress(function(event){
        var inputValue = event.charCode;
        if((inputValue > 47 && inputValue < 58) && (inputValue != 32)){
            event.preventDefault();
        }
    });
    $("#lasname").keypress(function(event){
        var inputValue = event.charCode;
        if((inputValue > 47 && inputValue < 58) && (inputValue != 32)){
            event.preventDefault();
        }
    });
	$("#firsname").keypress(function(event){
        var inputValue = event.charCode;
        if((inputValue > 47 && inputValue < 58) && (inputValue != 32)){
            event.preventDefault();
        }
    });
	$("#middlename").keypress(function(event){
        var inputValue = event.charCode;
        if((inputValue > 47 && inputValue < 58) && (inputValue != 32)){
            event.preventDefault();
        }
    });
}); 
//clears the fields lasname, firstname, middlename on browse	
function clearfieldsonbrowse()
{	
	$("#resultbrowse").html('');
	//$("#resultbrowse").hide();
	$(".size-emp").hide();
	document.getElementById('lasname').value = '';
	document.getElementById('firsname').value = '';
	document.getElementById('middlename').value = '';
	//search 
	document.getElementById('lname').value = '';
	document.getElementById('fname').value = '';	
}
//to be click when lastname, firstname and middlename are inputted for blacklist if the results of searching is empty.
function choosetobl()
{
	var ln = document.getElementById('lasname').value;
	var fn = document.getElementById('firsname').value;
	var mn = document.getElementById('middlename').value;	
	
	if(ln =='' || fn == ''){ 
		alert('Please fill up the required fields'); 
		var m = ln+", "+fn+" "+mn;		
	}
	else{	
		var flag =0;
		if(ln != '' && fn != '')
		{
			var m = ln+", "+fn+" "+mn;	
			flag = 1;
			/*
			var r = confirm('Are you sure to Blacklist '+ m + " ?")
			if(r == true){					
				alert('Click close and supply other details.')
				enableds();
				document.getElementById('namesearch').value = m;
			}
				*/			
		}
		else if(mn == ''){ 
			alert('Middlename is not required but input if there is any.')
			var m = ln+", "+fn;
			flag = 1;			
		}
		
		if(flag == 1)
		{
			var r = confirm('Are you sure to Blacklist '+ m + " ?")
			if(r == true){
				$.ajax({
					type: "POST",
					url: "functionquery.php?request=checkblacklist_thrulnfn",
					data: { ln:ln,fn:fn },
					success: function(data){					
						if(data == 1){
							alert(fn+" "+ln +" is already blacklisted! Please input another one.")
							document.getElementById('lasname').value = '';
							document.getElementById('firsname').value = '';
							document.getElementById('middlename').value = '';
						}
						else{
							alert('Click close and supply other details.')
							enableds();
							document.getElementById('namesearch').value = m;
						}
					}
				});	
			}	
		}	
	}	
}

//enabled the textfields
function enableds(){
	document.getElementById('reason').disabled = false;
	document.getElementById('datebls').disabled = false;
	document.getElementById('reportedby').disabled = false;
	document.getElementById('bdays').disabled = false;
	document.getElementById('addr').disabled = false;
	document.getElementById('submit').disabled = false;
	document.getElementById('reset').disabled = false;	
}
//disabled the textfields
function disableds(){
	document.getElementById('reason').disabled = true;
	document.getElementById('datebls').disabled = true;
	document.getElementById('bdays').disabled = false;
	document.getElementById('addr').disabled = false;
	document.getElementById('reportedby').disabled = true;
	document.getElementById('submit').disabled = true;
	document.getElementById('reset').disabled = true;	
}
//set the name of the blacklisted applicant or employee in the name textfield
function choose(n){	
	document.getElementById('namesearch').value = n;
	enableds();	
}
function browsenames()
{	
	var ln = document.getElementById('lname').value;
	var fn = document.getElementById('fname').value;
	if(ln == '' && fn == ''){
		alert('Please indicate either the employee lastname or firstname to be searched')
	}
	else{		
		$("#sub-list").html('<img src="../images/icons/ajax.gif" id="loading-gif" style="position:absolute; margin-left:400px;margin-top:30px;">');	
			
		$.ajax({
			type: "POST",
			url: "functionquery.php?request=browsenames",
			data: { ln:ln, fn:fn },
			success: function(data)
			{					
				if(data == ''){
					$('#nonemp').show();				
				}else{
					$('#nonemp').hide();
				}
				$('#resultbrowse').html(data);
				$("#loading-gif").hide();
				$(".size-emp").show();
			}
		});		
	}	
}

function cancel(){
	var r = confirm('Are you sure to go back? This will not save any changes you made.')
	if(r == true){
		window.location = "?p=blacklists";
	}
}

function submitBL()
{	
	var namesearch	= document.getElementById('namesearch').value;///document.getElementsByName('namesearch').value;
	var datebls 	= document.getElementById('datebls').value;
	var reportedby 	= document.getElementById('reportedby').value;
	var reason 		= document.getElementById('reason').value;
	var creator 	= document.getElementById('creator').value;
	var bdays 		= document.getElementById('bdays').value;
	var addr	 	= document.getElementById('addr').value;
	
	var emp   		= namesearch.split("*");
	var empid 		= emp[0];

	if(datebls == "" || reportedby == "" || reason =="" ){
		alert('Please do not leave empty fields!')
	}	
	else
	{		
		var r = confirm('Are you sure you want to save this?')
		if(r == true)
		{
			$.ajax({
				type: "POST",
				url: "functionquery.php?request=saveblacklist",
				data: { empid:empid, namesearch:namesearch, datebls:datebls, reportedby:reportedby, reason:reason, creator:creator,bdays:bdays,addr:addr },
				success: function(data)
				{			
					if(data == "success"){
						alert('Blacklist Successfully Save!')						
						window.location = '?p=blacklists-add&&db=entries';						
					}
					else{
						alert('Blacklist Unsuccessful. There is an error in the program.')
					}
				}
			});		
		}
	}	
}	
function editSubmit()
{
	
	var blno 		= document.getElementById('blno').value;
	var name 		= document.getElementById('namesearch').value;///document.getElementsByName('namesearch').value;
	var datebls 	= document.getElementById('datebls').value;
	var reportedby 	= document.getElementById('reportedby').value;
	var reason 		= document.getElementById('reason').value;
	var creator 	= document.getElementById('creator').value;
	var bdays 		= document.getElementById('bdays').value;
	var addr	 	= document.getElementById('addr').value;
	
	var emp   		= name.split("*");
	var empid 		= emp[0];

	if(datebls == "" || reportedby == "" || reason =="" )
	{
		alert('Please do not leave empty fields!')
	}
	else{		
		var r = confirm("Are you sure to update any changes?")
		if(r == true)
		{			
			//alert(blno+" "+empid+" "+datebls+" "+reportedby+" "+reason+" "+creator+" "+bdays+" "+addr)
			$.ajax({
				type: "POST",
				url: "functionquery.php?request=updateblacklist",
				data: { blno:blno, empid:empid, datebls:datebls, reportedby:reportedby, reason:reason, creator:creator,bdays:bdays, addr:addr },
				success: function(data)
				{		
					if(data == "success")
					{
						alert('Blacklist Successfully Updated!');
						window.location = '?p=blacklists';//'<?php echo $nq->getUrl();?>';
					}
					else
					{
						alert('Updating Blacklist Failed')
					}
				}
			});	
		}	
	}
}
function keypress()
{
	var datebls =document.getElementById("datebls").value;
	var arrbls = datebls.split("/");
	if(arrbls[0]>12 || arrbls[0]==00)
	{
		alert("Invalid month.\n (01 - 12) only");
		$("#datebls").val("");
	}
	else if(arrbls[1]==00 || arrbls[1]>31)
	{
		alert("Invalid day.\n (01 - 31) only");
		$("#datebls").val("");
	}
}
function resetform()
{
	var r = confirm('Reset all fields?')
	if(r == true)
	{
		window.location = '?p=blacklists-add&&db=entries';
	}
}
</script>