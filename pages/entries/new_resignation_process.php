<style type="text/css">    
    .search-results{
       box-shadow: 5px 5px 5px #ccc; 
       margin-top: 1px; 
       margin-left : 0px; 
       background-color: #F1F1F1;
       width : 57%;
       border-radius: 3px 3px 3px 3px;
       font-size: 18x;
       padding: 8px 10px;
       display: block;
       position:absolute;
       z-index:9999;
       max-height:300px;
       overflow-y:scroll;
       overflow:auto; 
    } 

    .rqd{
    	color:red;
    }
</style>

	<body onload="msgOption('div_secureclearance')"></body> 

		<div class='row' style='width:100%;margin:auto'>
			<span style='font-size:24px'> &nbsp;  CLEARANCE PROCESSING </span> <br><br>
			
			<div class='col-md-2'>
				<div class="list-group">
					<a href='javascript:void(0)' class="list-group-item" id='secureclearance' onclick="msgOption('div_secureclearance')">
						■ &nbsp; <b> SECURE </b> Clearance 
					</a>					
					<a href='javascript:void(0)' class="list-group-item" id='uploadclearance' onclick="msgOption('div_uploadclearance')">
						■ &nbsp; <b> UPLOAD </b>Clearance and Change Status 
					</a>
					<a href='javascript:void(0)' class="list-group-item" id='reprintclearance' onclick="msgOption('div_reprintclearance')">
						■ &nbsp; <b> REPRINT </b> Clearance 
					</a>
					<a href='javascript:void(0)' class="list-group-item" id='listofwhosecure' onclick="msgOption('div_listofwhosecure')">
						■ &nbsp; <b> LIST </b> of  Employees who secured clearance 
					</a>
					<!-- <a href='javascript:void(0)' class="list-group-item" id='taggingstatus' onclick="msgOption('div_taggingstatus')">
						■ &nbsp; <b> TAGGING </b> of status 
					</a> -->
					<a href='javascript:void(0)' class="list-group-item" id='processflow' onclick="msgOption('div_clearanceprocessflow')">
						■ &nbsp; <b> PROCESS </b> Flow 
					</a> 
				</div>
			</div>
			<div class='col-md-10'>	
				<input type="hidden" name="emp_id">
				<span id='_loading'></span>		
				<div style="border:solid 1px #ccc; background-color:white;" class="row">
					<div class="col-md-12 show-form">					
					</div>				
				</div>
			</div> 
		</div>	
	<br>

	<div class="modal fade" id="modal_rl" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">  </h4>
				</div>
				<div class="modal-body" id='body_rl'>					
				</div>		
				<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>          
			</div> 
		</div> 
	</div>


<link rel="stylesheet" href="../css/sweetalert.css" type="text/css" media="screen, projection" /> 
<script src="../jquery/sweetalert.js" ></script>

<link rel="stylesheet" type="text/css" media="all" href="../css/jquery-ui.css" />
 <script type="text/javascript" src="../jquery/jquery-latest.min.js"></script>
<script type="text/javascript" src="../jquery/jquery-ui.js"></script> 

<script>

	function show_RL(imgSrc)
	{
		$("#myModalLabel").html("DOCUMENT");
		$("#body_rl").html("<img src='"+imgSrc+"' width='100%' height='90%'>");
	}

	function show_Clearance()
	{	
		$("#myModalLabel").html("CLEARANCE");
		//$("#body_rl").html("<embed src='"+pdf+"' type='application/pdf' width='100%' height='450'></embed>");
	}

	function namesearch(key)
	{

	    $(".search-results").show();

	    var str = key.trim();
	    $(".search-results").hide();
	    if(str == '') {
	        $(".search-results-loading").slideUp(100);
	    }
	    else
	    {
			$.ajax({
				type : "POST",
				url  : "functionquery.php?request=findEmployeeforClearance",
				data : { str : str},
				success : function(data){
			  		data = data.trim();
					if(data != ""){
					  	$(".search-results").show().html(data);
					} else {
						$(".search-results").hide();
					}
			    } 
			});
	    }
	}

	function getEmpId(id)
	{
		var id = id.split("*");
		var empId = id[0].trim();  
		var name = id[1].trim();	

		//check existing if nag secure ug clearance
	 	$.ajax({
			type : "POST",
			url  : "functionquery.php?request=check_employee_secure_clearance",
			data : { empId : empId },
			success : function(data){			
				if(data.trim() == "error"){
					swal("Oppss","Employee has already secured Clearance!","error");
					$("[name='empid']").val("");
				}			
		    } 
		});		

	 	//check age for retirement
	 	var reason = $("[name='reason']").val();
	 	if(reason == "Retirement")
	 	{
		 	$.ajax({
				type : "POST",
				url  : "functionquery.php?request=check_employee_age",
				data : { empId : empId },
				success : function(data){							
					if(data.trim() == "error"){
						swal("Oppss","Employee's age does not qualify for retirement!","error");
						$("[name='empid']").val("");
					}		
			    } 
			});
		}

		if(reason == "Termination"){
			//check eoc date and emptype // contractual employees only
			$.ajax({
				type : "POST",
				url  : "functionquery.php?request=getEOCdate",
				data : { empId : empId },
				success : function(data){	
					if(data.trim() == "error"){
						swal("Oppss","Please check the employee type!","error");
						$("[name='empid']").val("");
					}else{
						data1		= data.split("+");
						$("[name='date_resignation']").val(data1[1]); 
					}
			    } 
			});
		}
	 	
		$("[name='empid']").val(empId+" * "+name);
		$(".search-results").hide();  
	}

	function namesearch_2(key)
	{

	    $(".search-results").show();

	    var str = key.trim();
	    $(".search-results").hide();
	    if(str == '') {
	        $(".search-results-loading").slideUp(100);
	    }
	    else {
			$.ajax({
				type : "POST",
				url  : "functionquery.php?request=findEmployeeforUploadSignedClearance",
				data : { str : str},
				success : function(data){
			  		data = data.trim();
					if(data != ""){
					  $(".search-results").show().html(data);
					} else {

						$(".search-results").hide();
					}
			    } 
			});
	    }
	}

	function getEmpId_2(id)
	{

		var id 		= id.split("*");
		var empId 	= id[0].trim();  
		var name 	= id[1].trim();
		var status 	= id[2].trim();

		if(status  == 'Deceased'){

		}else{
			$.ajax({
				type : "POST",
				url  : "functionquery.php?request=getEPAS",
				data : { empId : empId },
				success : function(data){	
									
					if(data == 0){
						swal("Oppss","Employee must secure EPAS first!","error");
						
						$("#remarks").attr("disabled", "disabled");
						$("#status").attr("disabled", "disabled");
						$("#clearance").attr("disabled", "disabled");
						$("#submit_printclearance_btn").attr("disabled", "disabled");								
						$("#epas").val("");				
						$("#epas").attr("disabled", "disabled");				
					}else{
						$("#remarks").removeAttr("disabled");
						$("#status").removeAttr("disabled");
						$("#clearance").removeAttr("disabled");
						$("#submit_printclearance_btn").removeAttr("disabled");			
						$("#epas").removeAttr("disabled");	
						$("#showEpas").html(data);	
					}		
			    } 
			});		
		}		

		$("[name='empid']").val(empId+" * "+name);
		$(".search-results").hide();  
	}

	//search for employees who secure clearance for reprint
	function namesearch_reprint(key)
	{

	    $(".search-results").show();

	    var str = key.trim();
	    $(".search-results").hide();
	    if(str == '') {
	        $(".search-results-loading").slideUp(100);
	    }
	    else {
			$.ajax({
				type : "POST",
				url  : "functionquery.php?request=findEmployeeforClearanceReprint",
				data : { str : str},
				success : function(data){
			  		data = data.trim();
					if(data != ""){
					  $(".search-results").show().html(data);
					} else {

						$(".search-results").hide();
					}
			    } 
			});
	    }
	}

	function getEmpId_reprint(id)
	{
		var id 		= id.split("*");
		var empId 	= id[0].trim();  
		var name 	= id[1].trim();
		var scid 	= id[2].trim();
		var clearance= id[3].trim();

		$("[name='empid']").val(empId+" * "+name);
		$("input[name = 'emp_id']").val(empId);
		$(".search-results").hide();  			

		// console.log(id, empId, name, scid, clearance);
		//functionquery.php | request =  div_reprintclearance
		$("#myModalLabel").html("CLEARANCE");				
		$("#body_rl").html("<embed src='"+clearance+"' type='application/pdf' width='100%' height='450'></embed> ");
		$("#scid").val(scid);
	}
	
	function show_clearance_reprint(){//saving here
				
		$.ajax({
			type : "POST",
			url  : "functionquery.php?request=record_clearance_reprint",
			data : { str : str},
			success : function(data){
		  		data = data.trim();
				if(data != ""){
				  $(".search-results").show().html(data);
				} else {

					$(".search-results").hide();
				}
		    } 
		});
	}

	function getRL(reason)
	{
		$("#date_resignation").show();
		var lbl = '';
		var lbl2= '';
		if(reason == "Deceased")
		{				

			$(".deceased_form").html(
				'<div class="form-group">'+
					'<label> <span class="rqd"> * </span> Name of Claimant </label>'+
					'<input type="text" required class="form-control" name="claimant" id="claimant">'+
				'</div>'+
				'<div class="form-group">'+
					'<label> <span class="rqd"> * </span>  Relation to the deceased employee </label>'+
					'<select class="form-control" name="relation" id="relation">'+
						'<option> - Choose Relationship - </option>'+
						'<option value="Father"> Father </option>'+
						'<option value="Mother"> Mother </option>'+
						'<option value="Spouse"> Spouse </option>'+
						'<option value="Son"> Son </option>'+
						'<option value="Daughter"> Daughter</option>'+
					'</select>'+
				'</div>'+
				'<div class="form-group">'+
					'<label> <span class="rqd"> * </span> Date of Death </label>'+
					'<input type="text" required class="form-control" name="dateofdeath" id="dateofdeath" placeholder="mm/dd/yyyy">'+
				'</div>'+
				'<div class="form-group">'+
					'<label> <span class="rqd"> * </span> Cause of Death </label>'+
					'<input type="text" required class="form-control" name="causeofdeath" id="causeofdeath">'+
				'</div>'+
				'<div class="rl_form">'+
					'<div class="form-group">'+
						'<label> <span class="rqd"> * </span> Required Document (Scanned Death Certificate) </label>'+
						'<input type="file" required accept="image/*" name="resignationletter" id="resignationletter" class="btn btn-default"  size="50" >'+
					'</div>'+
				'</div>'+
				'<div class="rl_form">'+
					'<div class="form-group" id="autholetter">'+
						'<label> Required Document (Scanned Authorization Letter) </label>'+
						'<input type="file" accept="image/*" name="authorizationletter" id="authorizationletter" class="btn btn-default"  size="50" >'+
					'</div>'+
				'</div>'
				
			);
			$(".non_deceased_form").html('');
		}
		else
		{			
			if(reason == "V-Resigned" || reason  == "Ad-Resigned"){
				lbl = "<span class='rqd'> * </span>  Date of Resignation ";
				lbl2= "<span class='rqd'> * </span>  Required Document (Scanned Resignation Letter) ";
			}else if(reason == "Retrenchment"){
				lbl = "<span class='rqd'> * </span>  Date of Retrenchment ";
				lbl2= "<span class='rqd'> * </span>  Required Document (Scanned Retrenchment Memo) ";
			}else if(reason == "Retirement"){
				lbl = "<span class='rqd'> * </span>  Date of Retirement ";
				lbl2= "<span class='rqd'> * </span>  Required Document (Scanned Retirement Letter) ";
			}else if(reason == "Termination"){
				
				lbl = "<span class='rqd'> * </span> EOC Date ";
				lbl2= "<span class='rqd'> * </span>  Required Document (Scanned Notice of End of Contract) ";
				$("#resignationletter").removeAttr("required");
				$("#resignationletter").hide();
				$("#rl_form").hide();
			}


			$(".deceased_form").html('');
			$(".non_deceased_form").html(
				'<div class="form-group">'+
			    	'<label class="label_date">'+lbl+'</label>'+
			    	'<input type="text" required class="form-control"  name="date_resignation" id="date_resignation" placeholder="mm/dd/yyyy" >'+    	
			    '</div>'+	   		
				
				'<div class="rl_form" id="rl_form">'+
					'<div class="form-group">'+
						'<label> '+ lbl2 +' </label>'+
						'<input type="file" required accept="image/*"   name="resignationletter" id="resignationletter" class="btn btn-default"  size="50" >'+
					'</div>'+
				'</div>'
			);
			
			if(reason == "Termination"){				
				
				$("#resignationletter").removeAttr("required");
				$("#resignationletter").hide();
				$("#rl_form").hide();
			}
		
		}

		$("#date_resignation" ).datepicker({ dateFormat: "mm/dd/yy", changeMonth: true, changeYear: true, showButtonPanel: true });	
		$("#dateofdeath" ).datepicker({ dateFormat: "mm/dd/yy", changeMonth: true, changeYear: true, showButtonPanel: true });
	
	}

	function get_reason(){
		var reason = "";
		// var empid  = $("[name='empid']").val();
		var empid  = $("input[name = 'emp_id']").val();
		console.log('emp_id', empid)		
		$.ajax({
			type : "POST",
			url  : "functionquery.php?request=getclearancedetails",
			data : { empid:empid },
			success : function(data){
		  		reason =  data.trim();
		    } 
		});		
		print_clearance(reason,empid)
	}

	function print_clearance(reason,empId)
	{				
        $.ajax({
	        type : "POST",
	        url  : "functionquery.php?request=getCCandBC",
	        data : { empId : empId },
	        success : function(data)
	        {	   				        	
	        	var data    = data.split("."); 
	        	var cc 		= data[0].trim();	
	        						    					       		
	       		if(reason == "Deceased"){
	       			alert("Generating Clearance...");
					window.open('../report/deceased_clearance.php?empid='+empId,'new'); 	
	       		}else{	   

				    if(data[0].trim() == "NESCO"){
			            alert("Printing NESCO...");
			            window.open('../report/nesco_clearance.php?empid='+empId,'new');     
		        	}
		        	else if(cc == '10'){
		        		alert("Generating SPRP Clearance...");
						window.open('../report/sprp_clearance.php?empid='+empId,'new');
		        	}
			        else
			        {	
			        	alert("Generating Clearance...");
						window.open('../report/ae_clearance.php?empid='+empId,'new'); 		 
					} 
		       	}					
		    }
	    });
	}

	function msgOption(code)
	{	
		$("#_loading").html('<i>Loading, Please wait.....</i>');	

		if(code == "div_secureclearance"){		
			$("#secureclearance").addClass("list-group-item active");
			$("#uploadclearance").removeClass("active");
			$("#processflow").removeClass("active");
			$("#listofwhosecure").removeClass("active");	
			$("#reprintclearance").removeClass("active");	
			$("#taggingstatus").removeClass("active");	

		}else if(code == "div_uploadclearance" ){

			$("#uploadclearance").addClass("list-group-item active");
			$("#secureclearance").removeClass("active");
			$("#processflow").removeClass("active");
			$("#listofwhosecure").removeClass("active");
			$("#reprintclearance").removeClass("active");
			$("#taggingstatus").removeClass("active");	

		}else if(code == 'div_clearanceprocessflow' ){
			
			$("#processflow").addClass("list-group-item active");
			$("#secureclearance").removeClass("active");
			$("#uploadclearance").removeClass("active");
			$("#listofwhosecure").removeClass("active");
			$("#reprintclearance").removeClass("active");
			$("#taggingstatus").removeClass("active");	
		}
		else if(code == 'div_listofwhosecure'){		

			$("#listofwhosecure").addClass("list-group-item active");
			$("#processflow").removeClass("active");
			$("#secureclearance").removeClass("active");
			$("#uploadclearance").removeClass("active");
			$("#reprintclearance").removeClass("active");
			$("#taggingstatus").removeClass("active");	
		}
		else if(code == 'div_reprintclearance'){		

			$("#reprintclearance").addClass("list-group-item active");
			$("#processflow").removeClass("active");
			$("#secureclearance").removeClass("active");
			$("#uploadclearance").removeClass("active");
			$("#listofwhosecure").removeClass("active");
			$("#taggingstatus").removeClass("active");	

		}else if(code == "div_taggingstatus"){
			
			$("#taggingstatus").addClass("list-group-item active");
			$("#processflow").removeClass("active");
			$("#secureclearance").removeClass("active");
			$("#uploadclearance").removeClass("active");
			$("#listofwhosecure").removeClass("active");
			$("#reprintclearance").removeClass("active");	
		}

		$.ajax({
			type: "POST",
			url: "functionquery.php?request="+code,
			success: function(data){		

				$('.show-form').html(data);	
				$("#_loading").html('');		

				//if form secure clearance submit / print clearance is click
				$("form#printClearance_form").submit(function(e){

					e.preventDefault();   
					var formData = new FormData(this); 				
					
					var empid 	= $("#empidClearance").val();  			    
				   	var id    	= empid.split("*");  			   
				    var reason 	= $("#reason").val();  
				    var dateres = $("#date_resignation").val();  
				    var resLetter= "";
							  		  
			    	var empId = id[0].trim();   
			  		var name  = id[1].trim();

			  		swal({
						title: "",
						text: "Are you sure to submit now?",
						type: "warning",
						showCancelButton: true,
						confirmButtonClass: "btn-danger",
						confirmButtonText: "Yes!",		
						cancelButtonText: "No!",
						closeOnConfirm: false,
						closeOnCancel: true
					},

					function(isConfirm){ 
					  	if(isConfirm){

						    $.ajax({
						        type : "POST",
						        url  : "functionquery.php?request=getCCandBC",
						        data : { empId : empId },
						        success : function(data)
						        {	   			        	
						        	$.ajax({						        
								        url: "functionquery.php?request=insert_secure_clearance",
								        type: 'POST',
								        data: formData,
								        enctype: 'multipart/form-data',
								    	async: true,
										cache: false,
										contentType: false,
										processData: false,
								        success: function (data){    
								       		
								       		var data    = data.split("+"); 

								       		if(data[0].trim() == "success")
								        	{									       		
									       		if(reason == "Deceased"){
									       			alert("Generating Clearance...");
													window.open('../report/deceased_clearance.php?empid='+empId,'new'); 	
									       		}else{
									       			if(data[0].trim() == "success")
									       			{		
														$("#empidClearance").val("");  
													    $("#reason").val("");  
													    $(".label_date").val(""); 
													    $("#date_resignation").hide();	

													    if(data[1].trim() == "NESCO"){
												            alert("Printing NESCO...");
												            window.open('../report/nesco_clearance.php?empid='+empId,'new');
											        	}
												        else
												        {	
												        	alert("Generating Clearance...");
															window.open('../report/ae_clearance.php?empid='+empId,'new'); 	 
														}       				 
													}	
										       	}
										    }else{
								        		swal("Error","Clearance Processing Error!","error");
								        	}
										},
								    });	
							    }
						    });
				    	}else{
							swal("Error","Nothing is saved!!","error");
						}	
					});   	
				});
					
				//UPLOAD SIGNED CLEARANCE
				$("form#uploadSignedClearance").submit(function(e){

					e.preventDefault();   
					var formData = new FormData(this);     
					
					var empid 	= $("#empid").val();  			    
				   	var id    	= empid.split("*");   			  		  
			    	var empId 	= id[0].trim();   

		  		  	swal({
						title: "Confirm Signed Clearance Submission",
						text: "Are you sure to submit now?",
						type: "warning",
						showCancelButton: true,
						confirmButtonClass: "btn-danger",
						confirmButtonText: "YES!",		
						cancelButtonText: "NO!",
						closeOnConfirm: false,
						closeOnCancel: true
					},

					function(isConfirm) { 
					  	if(isConfirm) { 		  	
						          	
				        	$.ajax({						        
						        url: "functionquery.php?request=upload_signed_clearance",
						        type: 'POST',
						        data: formData,
						        enctype: 'multipart/form-data',
						    	async: true,
								cache: false,
								contentType: false,
								processData: false,
						        success: function (data){	

						        	var data    = data.split("+"); 	               	
									swal(data[0],data[1],data[2]);	

									if(data[0]!="error"){
										setTimeout(function(){
											window.location = window. location.href;//'http://172.16.43.95/hrms/employee/clinic/forms/';	
										}, 2000); 
									}
									setTimeout(function(){
										location.reload();
									}, 2000); 
																	           	
								},
						    });				  
					  	}		
					});
				});
			}
		});
	}
	
	function reprint_clearance()
	{		
		var scid 	= $("#scid").val();
		var reason 	= $("#reasonreprint").val();

		if(scid !="" && reason !=""){
			$.ajax({
				type : "POST",
				url  : "functionquery.php?request=record_clearance_reprint",
				data : { scid:scid, reason:reason },
				success : function(data){
				
			  		if(data.trim() == "ok"){
			  			swal("","You can now view the Clearance!","success");
			  			
			  			//$("#empid").val("");
			  			$("#scid").val("");
						$("#reasonreprint").val("");
			  			$("#submit_reprintclearance_btn").attr("disabled",true);
			  			$("#view_reprintclearance_btn").attr("disabled",false);
			  		}
			    } 
			});
		}else{
			swal("Oppss","Reprint Not Allowed!","error");
		}		
	}

	filter_year = function(year)
	{	
		$("#_loading").html('<i>Loading, Please wait.....</i>');
		
		$("#listofwhosecure").addClass("list-group-item active");
		$("#processflow").removeClass("active");
		$("#secureclearance").removeClass("active");
		$("#uploadclearance").removeClass("active");
		$("#reprintclearance").removeClass("active");
		$("#taggingstatus").removeClass("active");	

		$.ajax({
			type: "POST",
			url: "functionquery.php?request=div_listofwhosecure",
			data: { year:year },
			success: function(data){		

				$('.show-form').html(data);	
				$("#_loading").html('');
			},
		});

	}

</script>