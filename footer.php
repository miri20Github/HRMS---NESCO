  <!-- /. WRAPPER  -->
    <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
    <!-- JQUERY SCRIPTS -->
    <script src="../datatables/jquery-1.11.1.min.js" type="text/javascript"></script>
      <!-- BOOTSTRAP SCRIPTS -->
	<script type="text/javascript" src="assets/js/jquery-latest.min.js"></script>
	<script type="text/javascript" src="assets/js/jquery-ui.js"></script>
    <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
      <!-- CUSTOM SCRIPTS -->
	<script type="text/javascript" src="../datatables/jquery.dataTables.min.js"></script>	
	<script type="text/javascript" src="assets/chart.plugin/Chart.js"></script>
		
	<!--script type="text/javascript" src="js/jquery-1.9.1.js" ></script> 
	<script type="text/javascript" src="js/jquery-latest.min.js"></script>-->
	<!-- <script type="text/javascript" src="js/jquery.maskedinput.js" ></script> -->	
	<!--<script type="text/javascript" language="javascript"></script>-->	
	<!--script type="text/javascript" src="js/script_add_applicant.js" charser="utf-8"></script-->
	
	<script>
	
	$(document).ready(function() {	
		//load NEWblacklists

		var emptype = "<?php echo @$_GET['emptype'];?>";
		var dtype 	= "("+emptype+")";
		$("#display_type").html(dtype);

		$('#changeusername').click(function(){ 
			window.location = '?p=changeusername&&db=setup';
		});	

		$('#changepassword').click(function(){ 
			window.location = '?p=changepassword&&db=setup'; 
		});

		$('#logout').click(function(){ 
			window.location = 'logout.php';
		});

		//for search employeee
		$("[name='searchs']").keypress(function(evt) {
			var val = this.value;
			if(evt.which == 13){
				
				var search = $("[name = 'searchs']").val();
				window.location = '?p=searchemployee&&search='+search;
			}
		});

		
		//for id incharge		
		$("[name='searchsID']").keypress(function(evt) {
			var val = this.value;
			if(evt.which == 13){
				
				var search = $("[name = 'searchsID']").val();
				window.location = '?p=searchID&&searchs='+search;
			}
		});

		//sent message	
		$("[name='replyMessage']").keypress(function(evt) {
			var val = this.value;
			if(evt.which == 13){
				
				var reply  = this.value;
		    	var sender = $("[name = 'senderAttach']").val();
		    	var cc     = $("[name = 'ccAttach']").val();

		    	$.ajax({
					type: "POST",
					url: "functionquery.php?request=replyMessage",
					data: { sender:sender, cc:cc, reply:reply },
					success: function(data){								
						
						data = data.trim();
						if(data == "Ok"){

							alert("Message Sent");
							$("[name = 'replyMessage']").val("");
						} else {
							alert(data);
						}					
					}
				});
			}
		});

		//search applicant on click sa b utton
		$("[name='submit_search']").click(function(evt) {
			var val = this.value;							
			var ln = $("[name = 'ln_search']").val();
			var fn = $("[name = 'fn_search']").val();
			window.location = '?p=searchApp&&db=searchApp&&ln='+ln+'&&fn='+fn+'&&q=recruitment';			
		});	
		
		//search for id
		$("[name='tag-as-done']").click(function(){
		var $val = this.id;
		if(!confirm("Click to OK to continue")) return false;
			$.ajax({
				type: "POST",
				url: "ajax.php?request=tagAsDone",
				data: { $val : $val },
				success: function(data){
					if(data == "Ok"){
						alert("Successfully Tag!");
						window.location.reload();
					} else {
						alert(data);
						window.location.reload();
					}
				}
			});
		});
		$("[name='generate-id']").click(function(){
			var $val = this.id;
			if(!confirm("Click to OK to continue")) return false;
			$.ajax({
				type: "POST",
				url: "../placement/ajax.php?request=genNewId",
				data: { $val : $val },
				success: function(data){
					if(data == "Ok"){
						alert("Successfully Generated!");
						alert("Please don't forget to click the Tag as done button!");
						window.location.reload();
					} else {
						alert(data);
						window.location.reload();
					}
				}
			});
		});
		
		var dataTable = $('#newblacklist').DataTable( {
			"processing": true,
			"serverSide": true,
			"order": [[1, 'desc']],
			"ajax":{
				url :"functions/functions_dashboard.php?request=loadNewBlacklist", // json datasource
				type: "post",  // method  , by default get
				error: function(){  // error handling
					$(".employee-grid-error").html("");
					$("#newblacklist").append('<tbody class="employee-grid-error"><tr><th colspan="7">No data found in the server</th></tr></tbody>');
					$("#newblacklist_processing").css("display","none");
					
				}
			}
		});

		//load blacklists
		var dataTable = $('#blacklists').DataTable( {
			"processing": true,
			"serverSide": true,
			"order": [[1, 'desc']],
			"columnDefs": [ {
			"targets": [6], // column or columns numbers
			"orderable": false,  // set orderable for selected columns
			}],

			"ajax":{
				url :"functions/functions_nesco.php?request=loadBlacklists", // json datasource
				type: "post",  // method  , by default get
				error: function(){  // error handling
					$(".employee-grid-error").html("");
					$("#blacklists").append('<tbody class="employee-grid-error"><tr><th colspan="7">No data found in the server</th></tr></tbody>');
					$("#blacklists_processing").css("display","none");
					
				}
			}
		});
			//load Masterfilevar 
		var dataTable = $('#masterfile').DataTable( {
			"processing": true,
			"serverSide": true,
			"order": [[1, 'asc']],
			"columnDefs": [ {
			"targets": [2, 3, 5, 6], // column or columns numbers
			"orderable": false,  // set orderable for selected columns
			}],

			"ajax":{
				url :"functions/functions_nesco.php?request=loadNescoMasterfile", // json datasource
				type: "post",  // method  , by default get
				error: function(){  // error handling
					$(".employee-grid-error").html("");
					$("#masterfile").append('<tbody class="employee-grid-error"><tr><th colspan="7">No data found in the server</th></tr></tbody>');
					$("#masterfile_processing").css("display","none");
					
				}
			}
		});

		
		//load termination
		var dataTable = $('#termination').DataTable( {
			"processing": true,
			"serverSide": true,
			"order": [[1, 'desc']],
			"ajax":{
				url :"functions/functions_nesco.php?request=loadNescoTermination", // json datasource
				type: "post",  // method  , by default get
				error: function(){  // error handling
					$(".employee-grid-error").html("");
					$("#termination").append('<tbody class="employee-grid-error"><tr><th colspan="7">No data found in the server</th></tr></tbody>');
					$("#termination_processing").css("display","none");
					
				}
			}
		});
		
		//load transfer
		var dataTable = $('#jobTransfer').DataTable( {
			"processing": true,
			"serverSide": true,
			"order": [[0, 'desc']],
			"columnDefs": [ {
			"targets": [6], // column or columns numbers
			"orderable": false,  // set orderable for selected columns
			}],

			"ajax":{
				url :"functions/functions_nesco.php?request=loadNescoJobTrans", // json datasource
				type: "post",  // method  , by default get
				error: function(){  // error handling
					$(".employee-grid-error").html("");
					$("#jobTransfer").append('<tbody class="employee-grid-error"><tr><th colspan="7">No data found in the server</th></tr></tbody>');
					$("#jobTransfer_processing").css("display","none");
					
				}
			}
		});
		
		//load employee users
		var dataTable = $('#employeeusers').DataTable( {
			"processing": true,
			"serverSide": true,
			"order": [[1, 'desc']],
			"ajax":{
				url :"functions/functions_setup.php?request=loadUserEmployee", // json datasource
				type: "post",  // method  , by default get
				error: function(){  // error handling
					$(".employee-grid-error").html("");
					$("#employeeusers").append('<tbody class="employee-grid-error"><tr><th colspan="7">No data found in the server</th></tr></tbody>');
					$("#employeeusers_processing").css("display","none");
					
				}
			}
		});	
		
		//load new employee users
		var dataTable = $('#newemployees').DataTable( {
			"processing": true,
			"serverSide": true,
			"order": [[1, 'desc']],
			"columnDefs": [ {
			"targets": [0, 3, 4, 5, 6], // column or columns numbers
			"orderable": false,  // set orderable for selected columns
			}],

			"ajax":{
				url :"functions/functions_dashboard.php?request=loadNewEmployee", // json datasource
				type: "post",  // method  , by default get
				error: function(){  // error handling
					$(".employee-grid-error").html("");
					$("#newemployees").append('<tbody class="employee-grid-error"><tr><th colspan="7">No data found in the server</th></tr></tbody>');
					$("#newemployees_processing").css("display","none");					
				}
			}
		});	
		
		//load new job trans
		var dataTable = $('#newjobtrans').DataTable( {
			"processing": true,
			"serverSide": true,
			"order": [[1, 'desc']],
			"ajax":{
				url :"functions/functions_dashboard.php?request=loadNewJobTrans", // json datasource
				type: "post",  // method  , by default get
				error: function(){  // error handling
					$(".employee-grid-error").html("");
					$("#newjobtrans").append('<tbody class="employee-grid-error"><tr><th colspan="7">No data found in the server</th></tr></tbody>');
					$("#newjobtrans_processing").css("display","none");					
				}
			}
		});	
		
		//load statistics details
		var dataTable = $('#statisticsdetails').DataTable({
			"processing": true,
			"serverSide": true,
			"order": [[1, 'desc']],
			"ajax":{
				url :"functions/functions_dashboard.php?request=loadStatisticsDetails&&emptype="+emptype, // json datasource
				type: "post",  // method  , by default get
				error: function(){  // error handling
					$(".employee-grid-error").html("");
					$("#statisticsdetails").append('<tbody class="employee-grid-error"><tr><th colspan="7">No data found in the server</th></tr></tbody>');
					$("#statisticsdetails_processing").css("display","none");					
				}
			}
		});	
	
		//load nesco regular
		var dataTable = $('#nescoregulars').DataTable({
			"processing": true,
			"serverSide": true,
			"order": [[1, 'desc']],
			"columnDefs": [ {
			"targets": [4, 5, 6], // column or columns numbers
			"orderable": false,  // set orderable for selected columns
			}],

			"ajax":{
				url :"functions/functions_nesco.php?request=loadNESCORegulars", // json datasource
				type: "post",  // method  , by default get
				error: function(){  // error handling
					$(".employee-grid-error").html("");
					$("#nescoregulars").append('<tbody class="employee-grid-error"><tr><th colspan="7">No data found in the server</th></tr></tbody>');
					$("#nescoregulars_processing").css("display","none");					
				}
			}
		});


		//load eoclist	
		var  eocToday = $("[name = 'eocToday']").val();
		var dataTable = $('#eoclist').DataTable( {
			"processing": true,
			"serverSide": true,
			"order": [[1, 'asc']],
			"columnDefs": [ {
			"targets": [2, 3, 7, 8, 9], // column or columns numbers
			"orderable": false,  // set orderable for selected columns
			}],

			"ajax":{
				url :"functionquery.php?request=loadEOClist", // json datasource
				type: "post",  // method  , by default get
				data: { eoc : eocToday },
				error: function(){  // error handling
					$(".employee-grid-error").html("");
					$("#eoclist").append('<tbody class="employee-grid-error"><tr><th colspan="10">No data found in the server</th></tr></tbody>');
					$("#eoclist_processing").css("display","none");					
				}
			}
		});
	
		
		//load setsalnum	
		var dataTable = $('#setupsalnum').DataTable( {
			"processing": true,
			"serverSide": true,
			"order": [[1, 'asc']],
			"ajax":{
				url :"functions/functions_setup.php?request=loadSetupSalnum", // json datasource
				type: "post",  // method  , by default get
				error: function(){  // error handling
					$(".employee-grid-error").html("");
					$("#setupsalnum").append('<tbody class="employee-grid-error"><tr><th colspan="10">No data found in the server</th></tr></tbody>');
					$("#setupsalnum_processing").css("display","none");					
				}
			}
		});	

		//load final completion
		var dataTable = $('#finalReq').DataTable( {
			"processing": true,
			"serverSide": true,
			"order": [[1, 'asc']],

			"ajax":{
				url :"functionquery.php?request=loadFinalReq", // json datasource
				type: "post",  // method  , by default get
				error: function(){  // error handling
					$(".employee-grid-error").html("");
					$("#finalReq").append('<tbody class="employee-grid-error"><tr><th colspan="5">No data found in the server</th></tr></tbody>');
					$("#finalReq_processing").css("display","none");					
				}
			}
		});	

		//load applicants to be hired
		var dataTable = $('#appToBeHired').DataTable( {
			"processing": true,
			"serverSide": true,
			"order": [[1, 'asc']],

			"ajax":{
				url :"functionquery.php?request=loadAppToBeHired", // json datasource
				type: "post",  // method  , by default get
				error: function(){  // error handling
					$(".employee-grid-error").html("");
					$("#appToBeHired").append('<tbody class="employee-grid-error"><tr><th colspan="6">No data found in the server</th></tr></tbody>');
					$("#appToBeHired_processing").css("display","none");					
				}
			}
		});	

		//load newly hired
		var dataTable = $('#newlyHired').DataTable( {
			"processing": true,
			"serverSide": true,
			"order": [[1, 'asc']],

			"ajax":{
				url :"functionquery.php?request=loadNewlyHired", // json datasource
				type: "post",  // method  , by default get
				error: function(){  // error handling
					$(".employee-grid-error").html("");
					$("#newlyHired").append('<tbody class="employee-grid-error"><tr><th colspan="6">No data found in the server</th></tr></tbody>');
					$("#newlyHired_processing").css("display","none");					
				}
			}
		});

		//load newly hired employee for deployment
		var dataTable = $('#empForDeployment').DataTable( {
			"processing": true,
			"serverSide": true,
			"order": [[1, 'asc']],

			"ajax":{
				url :"functionquery.php?request=loadEmpForDeployment", // json datasource
				type: "post",  // method  , by default get
				error: function(){  // error handling
					$(".employee-grid-error").html("");
					$("#empForDeployment").append('<tbody class="employee-grid-error"><tr><th colspan="6">No data found in the server</th></tr></tbody>');
					$("#empForDeployment_processing").css("display","none");					
				}
			}
		});

		//load newly deployed employee
		var dataTable = $('#newlyDeployed').DataTable( {
			"processing": true,
			"serverSide": true,
			"order": [[1, 'asc']],

			"ajax":{
				url :"functionquery.php?request=loadNewlyDeployed", // json datasource
				type: "post",  // method  , by default get
				error: function(){  // error handling
					$(".employee-grid-error").html("");
					$("#newlyDeployed").append('<tbody class="employee-grid-error"><tr><th colspan="6">No data found in the server</th></tr></tbody>');
					$("#newlyDeployed_processing").css("display","none");					
				}
			}
		});

		//load nesco benefits masterfile 
		var dataTable = $('#benefits').DataTable( {
			"processing": true,
			"serverSide": true,
			"order": [[1, 'asc']],
			"columnDefs": [ {
			"targets": [2, 3, 5, 6], // column or columns numbers
			"orderable": false,  // set orderable for selected columns
			}],

			"ajax":{
				url :"functions/functions_nesco.php?request=loadNescoBenefitsMasterfile", // json datasource
				type: "post",  // method  , by default get
				error: function(){  // error handling
					$(".employee-grid-error").html("");
					$("#benefits").append('<tbody class="employee-grid-error"><tr><th colspan="7">No data found in the server</th></tr></tbody>');
					$("#benefits_processing").css("display","none");
					
				}
			}
		});
		
	});

	function userfunction(userno,request)
	{
		if(request == 'resetPass'){ msg ='Are you sure to reset password for this User Account?'; }
		else if(request == 'activateAccount'){ msg = 'Are you sure to activate this User Account?'; }
		else if(request == 'deactivateAccount'){ msg= 'Are you sure to deactivate this User Account?'; }
		
		var r = confirm(msg)
		if(r == true)
		{
			$.ajax({
				type: "POST",
				url: "functionquery.php?request="+request,
				data: { userno:userno },
				success: function(data){	
					alert(data)
					window.location = '?p=manageuseraccounts&&db=setup';
				}
			});
		}	
	}

	$(function(){

		$( "#dateresign" ).datepicker({ dateFormat: "mm/dd/yy", changeMonth: true, changeYear: true, showButtonPanel: true });
		$( "#datebls" ).datepicker({ dateFormat: "mm/dd/yy", changeMonth: true, changeYear: true, showButtonPanel: true });
		$( "#bdays" ).datepicker({ dateFormat: "mm/dd/yy", changeMonth: true, changeYear: true, showButtonPanel: true });
		$( "#startdate" ).datepicker({ dateFormat: "mm/dd/yy", changeMonth: true, changeYear: true, showButtonPanel: true });
		$( "#eocdate" ).datepicker({ dateFormat: "mm/dd/yy", changeMonth: true, changeYear: true, showButtonPanel: true });
		$( "#issuedon" ).datepicker({ dateFormat: "mm/dd/yy", changeMonth: true, changeYear: true, showButtonPanel: true });
		$( "#contractdate" ).datepicker({ dateFormat: "mm/dd/yy", changeMonth: true, changeYear: true, showButtonPanel: true });
		$( "#datebirth" ).datepicker({ dateFormat: "mm/dd/yy", changeMonth: true, changeYear: true, showButtonPanel: true });
		$( "#effectiveon" ).datepicker({ dateFormat: "mm/dd/yy", changeMonth: true, changeYear: true, showButtonPanel: true });
		
		setTimeout(function(){
			$.ajax({
				url: "functionquery.php?request=birthday",
				success: function(data){
					$("#cbirthday").html(data);
				}
			});	
			$.ajax({
				url: "functionquery.php?request=newemp",
				success: function(data){
					$("#cnewemp").html(data);
				}
			});			
			$.ajax({
				url: "functionquery.php?request=newblacklists",
				success: function(data){
					$("#cblacklist").html(data);
				}
			});	
			$.ajax({
				url: "functionquery.php?request=jobtransthisweek",
				success: function(data){
					$("#cjobtrans").html(data);
				}
			});
			$.ajax({
				url: "functionquery.php?request=eocToday",
				success: function(data){
					$("#eocToday").html(data);
				}
			});
		},1000);		
	});

	//functions used for save nesco payroll no
	function savepid(e,id){
		// look for window.event in case event isn't passed in
		e = e || window.event;
		if (e.keyCode == 13)
		{
			var pid = $("[name^='pid_"+id+"']").val();			
			var pid2 = $("[name^='pid2_"+id+"']").val();
			
			var count = 13;
			var tpid = pid.trim();
			
			if(tpid != ''){
				$.ajax({
					type: "POST",
					url: "functionquery.php?request=savepid",
					data: { pid:pid, id:id },
					success: function(data){						
						if(data == 1){
							$("[name^='pid_"+id+"']").removeClass('loading');
							$("[name^='pid_"+id+"']").addClass('ok');
						}else{						
							alert(data)	
							$("[name^='pid_"+id+"']").val(pid2);	
							//$("[name^='pid_"+id+"']").removeClass('loading');
							//$("[name^='pid_"+id+"']").addClass('notok');						
						}
					}
				});
				return false;
			}else{
				alert("Please do not input an empty data!");
			}			
		}
		return true;
	}
	
	function numericFilter(ob) 
	{
	 	var invalidChars = /[a-d,f-m,o-r,t-z,_,-]/gi
	  	if(invalidChars.test(ob.value)) 
	  	{
	  		ob.value = ob.value.replace(invalidChars,"");
	  	}
	 

	} 

	/***** end here ***********/
</script>
</body>
</html>