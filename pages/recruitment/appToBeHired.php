<?php include("header.php"); ?>
	<style type="text/css">		
		.form-control, .btn {
			border-radius: 0px;
		}	

		.emp-size {
			overflow: auto;
			max-height: 460px;
		}

		.search-results {

	       	box-shadow: 5px 5px 5px #ccc; 
	       	margin-top: -1px; 
	       	margin-left : 1px; 
	       	background-color: #F1F1F1;
	       	width : 45%;
	       	border-radius: 3px 3px 3px 3px;
	       	font-size: 12px;
	       	padding: 8px 10px;
	       	display: block;
	       	position:absolute;
	       	z-index:99999;
	       	max-height:300px;
	       	overflow-y:scroll;
	       	overflow:auto; 
	    }

	    .asterisk {

	    	color: red;
	    }

	</style>

	<div class="panel panel-default">
      	<div class="panel-heading"><span style="font-size:18px;"> APPLICANTS TO BE HIRED </span></div>
      	<div class="panel-body">

			<table class="table table-striped" width="100%" id="appToBeHired" style='font-size:11px'>		
				<thead>
					<tr>
						<td><b>APP ID</b></td>
						<td><b>APPLICANT NAME</b></td>
						<td><b>APPLYING FOR</b></td>
						<td><b>ATTAINMENT</b></td>
						<td><b>DATE APPLIED</b></td>	
						<td align="center"><b>ACTION</b></td> 
					</tr>
				</thead>
			</table>
		</div>
	</div>

	<div id = "hiredNow" class="modal fade bs-example-modal-lg">
	  	<div class="modal-dialog" style="width: 60%; height:auto;">
	    	<div class="modal-content">
	      		<div class="modal-header alert-info">
		        	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		        	<h4 class="modal-title">Employment Details</h4>
	      		</div>
      		 	<div class="modal-body">
		      		<div class="hiredNow emp-size">
		      			
		      		</div>
		      	</div>
		      	<div class="modal-footer">
		        	<span class="loadingSave"></span>
		        	<button class="btn btn-primary" onclick="submitEmployment()">Submit for Employment</button>
		        	<button type="button" class="dis_ btn btn-default" data-dismiss="modal">Close</button>
		      	</div>
	    	</div><!-- /.modal-content -->
	  	</div><!-- /.modal-dialog -->
	</div>

	<div id = "viewManual" class="modal fade bs-example-modal-md">
	  	<div class="modal-dialog modal-md">
	    	<div class="modal-content">
	      		<div class="modal-header alert-info">
		        	<button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="back()">&times;</button>
		        	<h4 class="modal-title">Please click the following buttons</h4>
		      	</div>
		      	<div class="modal-body">
		        	<div class = "print"></div>
		      	</div>
		      	<div class="modal-footer">
		            <button type="button" class="dis_ btn btn-default " data-dismiss="modal" onclick="back()">Close</button>
		        </div>
		    </div><!-- /.modal-content -->
	  	</div><!-- /.modal-dialog -->
	</div>

	<div id = "viewContract" class="modal fade bs-example-modal-md">
	  	<div class="modal-dialog modal-md">
	    	<div class="modal-content">
	      		<div class="modal-header alert-info">
	        		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	       	 		<h4 class="modal-title">Print Contract</h4>
	      		</div>
	      		<div class="modal-body">
	        		<div class = "printContract"></div>
	      		</div>
	      		<div class="modal-footer">
	         		<input type='button' name='submit' class='btn btn-primary btn-md' value='Proceed' onclick="renewcontract()">
	            	<button type="button" class="dis_ btn btn-default " data-dismiss="modal">Close</button>
	      		</div>
	    	</div><!-- /.modal-content -->
	  	</div><!-- /.modal-dialog -->
	</div>

<script type="text/javascript">
	
	function hiredNow(appId,appCode) {
		
		$("#hiredNow").modal({
            backdrop: 'static',
            keyboard: false
        });

        $("#hiredNow").modal("show");

        $.ajax({
			type: "POST",
			url : "functionquery.php?request=hiredNow",
			data: { appId:appId, appCode:appCode },
			success : function(data){	
				
				$('.hiredNow').html(data);		
			}
		});
	}

	function company(id){

		$("[name = 'company']").css('border-color','#ccc');
		$.ajax({
			type : "POST",
			url : "ajax.php?load=bunit",
			data : { id : id },
			success : function(data){
				$("[name='bunit']").html(data);
				$("[name='dept']").html('');
				$("[name='section']").html('');
				$("[name='ssection']").html('');
				$("[name='unit']").html('');
			}
		});
	}

	function bunit(id){

		$("[name = 'bunit']").css('border-color','#ccc');
		$.ajax({
			type : "POST",
			url : "ajax.php?load=dept",
			data : { id : id },
			success : function(data){
				$("[name='dept']").html(data);
				$("[name='section']").html('');
				$("[name='ssection']").html('');
				$("[name='unit']").html('');
			}
		});
	}

	function dept(id){

		$("[name = 'dept']").css('border-color','#ccc');
		$.ajax({
			type : "POST",
			url : "ajax.php?load=section",
			data : { id : id },
			success : function(data){
				$("[name='section']").html(data);
				$("[name='ssection']").html('');
				$("[name='unit']").html('');
			}
		});
	}

	function section(id){
		$.ajax({
			type : "POST",
			url : "ajax.php?load=ssection",
			data : { id : id },
			success : function(data){
				$("[name='ssection']").html(data);
				$("[name='unit']").html('');
			}
		});
	}

	function ssection(id){
		$.ajax({
			type : "POST",
			url : "ajax.php?load=unit",
			data : { id : id },
			success : function(data){
				$("[name='unit']").html(data);
			}
		});
	}

	function numericVal(txb){
	
	   	
	    var invalidChars = /[^0-9]/g
	  	if(invalidChars.test(txb.value)) 
	  	{
	  		txb.value = txb.value.replace(invalidChars,"");
	  	}
	}

	function nameSearch(key){

		$("[name = 'supName']").css("border-color","#ccc");
        $(".search-results").show();

        var str = key.trim();
        $(".search-results").hide();
        if(str == '') {
            $(".search-results-loading").slideUp(100);
        }
        else {
            $.ajax({
                type : "POST",
                url  : "functionquery.php?request=findSup",
                data : { str : str},
                success : function(data){
                    if(data){
                	console.log(data);
                        $(".search-results").show().html(data);
                    }
                } 
            });
     	}
    }

    function searchSupervisorName(key){

		$("[name = 'supName']").css("border-color","#ccc");
        $(".search-results2").show();

        var str = key.trim();
        $(".search-results2").hide();
        if(str == '') {
            $(".search-results-loading").slideUp(100);
        }
        else {
            $.ajax({
                type : "POST",
                url  : "functionquery.php?request=findSup",
                data : { str : str},
                success : function(data){
                    if(data){
                	console.log(data);
                        $(".search-results2").show().html(data);
                    }
                } 
            });
     	}
    }

    function getEmpId(id){

        $("[name='supName']").val(id);

        var id = id.split("*");
        var empId = id[0].trim();

        $("[name='supId']").val(empId);
        $(".search-results2").hide();
    }

    function inputText(name){

    	var inputVal = $("[name = '"+name+"']").val();

    	if (inputVal.trim() != "") {
    		
    		$("[name = '"+name+"']").css("border-color","#ccc");
    	}
    }

    function positionLevel(value) {
    	console.log(value);
    	$.ajax({
            type : "POST",
            url  : "functionquery.php?request=positionLevel",
            data : { position_no : value},
            success : function(data){
               
                $("[name = 'posLevel']").val(data);
            } 
        });
    }

    function onkeyupWitness(id){

		var witness = $("#"+id).val();

		if(witness.trim() != ""){

			$("#"+id).css('border-color','#ccc');
		}
	}

    function submitEmployment(){

    	var appId 	= $("[name = 'appId']").val();
    	var appCode = $("[name = 'appCode']").val();
    	var appName = $("[name = 'appName']").val();
    	var company = $("[name = 'company']").val();
    	var bunit 	= $("[name = 'bunit']").val();
    	var dept 	= $("[name = 'dept']").val();
    	var section = $("[name = 'section']").val();
    	var ssection = $("[name = 'ssection']").val();
    	var unit 	= $("[name = 'unit']").val();
    	var position = $("[name = 'position']").val();
    	var posLevel = $("[name = 'posLevel']").val();
    	var lodging = $("[name = 'lodging']").val();
    	var empType = $("[name = 'empType']").val();
    	var startdate = $("[name = 'startdate']").val();
    	var eocdate = $("[name = 'eocdate']").val();
    	var duration = $("[name = 'duration']").val();
    	var ojtHrs = $("[name = 'ojtHrs']").val();
    	var partimerSched = $("[name = 'partimerSched']").val();
    	var supId = $("[name = 'supId']").val();
    	var supName = $("[name = 'supName']").val();
    	var witness1 = $("[name = 'witness1']").val();
    	var witness2 = $("[name = 'witness2']").val();
    	var comment = $("[name = 'comment']").val();
    	var remarks = $("[name = 'remarks']").val();

    	if(company == "" || bunit == "" || dept == "" || position == "" || empType == "" || startdate == "" || eocdate == "" || duration == "" || supId == "" || supName == "" || witness1 == "" || witness2 == "") {

    		alert("Please fill-up required field");

    		if(company == "") {

    			$("[name = 'company']").css('border-color','red');
				$("[name = 'company']").focus();
    		}

    		if(bunit == "") {

    			$("[name = 'bunit']").css('border-color','red');
				$("[name = 'bunit']").focus();
    		}

    		if(dept == "") {

    			$("[name = 'dept']").css('border-color','red');
				$("[name = 'dept']").focus();
    		}

    		if(position == "") {

    			$("[name = 'position']").css('border-color','red');
				$("[name = 'position']").focus();
    		}

    		if(empType == "") {

    			$("[name = 'empType']").css('border-color','red');
				$("[name = 'empType']").focus();
    		}

    		if(startdate == "") {

    			$("[name = 'startdate']").css('border-color','red');
    		}

    		if(eocdate == "") {

    			$("[name = 'eocdate']").css('border-color','red');
    		}

    		if(duration == "") {

    			$("[name = 'duration']").css('border-color','red');
				$("[name = 'duration']").focus();
    		}

    		if(supId == "" || supName == "") {

    			$("[name = 'supName']").css('border-color','red');
				$("[name = 'supName']").focus();
    		}

    		if(witness1 == "") {

    			$("[name = 'witness1']").css('border-color','red');
				$("[name = 'witness1']").focus();
    		}

    		if(witness2 == "") {

    			$("[name = 'witness2']").css('border-color','red');
				$("[name = 'witness2']").focus();
    		}

    	} else {

    		$.ajax({

			    type: "POST",
				url : "functionquery.php?request=processHiring",
				data: { appId:appId, appCode:appCode, appName:appName, company:company, bunit:bunit, dept:dept, section:section, ssection:ssection, unit:unit, 
						lodging:lodging, position:position, empType:empType, posLevel:posLevel, duration:duration, startdate:startdate, eocdate:eocdate, 
						witness1:witness1, witness2:witness2, ojtHrs:ojtHrs, partimerSched:partimerSched, comment:comment, remarks:remarks, supId:supId },
				success: function(data){	 	
				  
				  	data = data.trim();
				  	if(data == "Ok"){
				  		alert("New Employee Successfully Added!");
				  		printContractPermit(appId);
				  	} else {
				  		alert(data);
				  	}
				}
			});
    	}
    }

    function printContractPermit(empId){

		$("#viewManual").modal({
	        backdrop: 'static',
	        keyboard: false
	      });

	    $("#viewManual").modal("show");

	    $.ajax({

		    type: "POST",
			url : "functionquery.php?request=generateContractPermit",
			data : { empId:empId },
			success: function(data){	 	
			  
			  	$(".print").html(data);
			}
		});
				
	}

	function back(){

		var r = confirm("Are you sure you want to EXIT?");

		if(r == true){
			window.location = "?p=appToBeHired&&db=hiring&&q=recruitment";
		}
	}

	function renewcontract()
	{

		var r1		= $("#r1").val();
		var r2		= $("#r2").val();
		var cleartf = $("[name = 'cleartf']").val();
		var ssstf	= $("[name = 'ssstf']").val();
		var issuedon= $("[name = 'issuedon']").val();
		var issuedat= $("[name = 'issuedat']").val();
		var cdate 	= $("[name = 'contractdate']").val();
		var recordNo= $("[name = 'newRecordNo']").val();
		var empType = $("[name = 'empType']").val();
		var clear 	= "";
		
		if($("#r1").is(':checked')){
			
			clear = r1;	
		} else if($("#r2").is(':checked')){
			
			clear = r2;
		} else {

			clear = "";
		}
		//checks empty	
		if(clear == ""){

			alert('Please choose either to use Cedula (CTC No.) or SSS No.');
		}

		if($("#r1").is(':checked')){
			if(cleartf == ""){

				alert("Please fill up CEDULA (CTC NO.) first!");
				$("#cleartf").css('border-color','red');
				$("#cleartf").focus();

			} else if(issuedon == ""){

				alert("Please fill up ISSUED ON first!");
				$("#issuedon").css('border-color','red');
				$("#issuedon").focus();
			
			} else if(issuedat == ""){

				alert("Please fill up ISSUED AT first!");
				$("#issuedat").css('border-color','red');
				$("#issuedat").focus();

			} else if(cdate == ""){

				alert("Please fill up ISSUED AT first!");
				$("#contractdate").css('border-color','red');
				$("#contractdate").focus();
			}
		}

		if($("#r2").is(':checked')){

			if(ssstf == ""){

				alert("Please fill up SSS NO. first!");
				$("#ssstf").css('border-color','red');
				$("#ssstf").focus();

			} else if(issuedat == ""){

				alert("Please fill up ISSUED AT first!");
				$("#issuedat").css('border-color','red');
				$("#issuedat").focus();

			} else if(cdate == ""){

				alert("Please fill up DATE OF SIGNING OF CONTRACT/EMPLOYEE first!");
				$("#contractdate").css('border-color','red');
				$("#contractdate").focus();
			}
		}
		
		if(clear != "" && clear != "" && cdate != ""){	

			window.open("../report/contract_NESCCO.php?clear="+clear+"&ssstf="+ssstf+"&cleartf="+cleartf+"&issuedon="+issuedon+"&issuedat="+issuedat+"&rec="+recordNo+"&cdate="+cdate,"_blank");			
		}
	}

	function contract(recordNo,empType,empId){


		$("#viewContract").modal({
	        backdrop: 'static',
	        keyboard: false
	      });

	    $("#viewContract").modal("show");

	    $.ajax({

		    type: "POST",
			url : "functionquery.php?request=printContract",
			data : { recordNo:recordNo, empType:empType, empId:empId },
			success: function(data){	 	
			  
			  	$(".printContract").html(data);
			}
		});
	}

	function sssctc(val) {

		if(val == 'ctc'){
			$("#cleartf").prop("disabled",false);			
			$("#ssstf").prop("disabled",true);			
			$('#ssstf').hide();
			$('#cleartf').show();
			$('#issuedon').show();	
			$('#is').show();		
		}
		else if(val == 'sss'){
			$("#ssstf").prop("disabled",false);			
			$("#cleartf").prop("disabled",true);			
			$('#ssstf').show();
			$('#cleartf').hide();
			$('#issuedon').hide();	
			$('#is').hide();
		}	
	}

	function permit(rec){

		var r = confirm("Generate Permit-To-Work now?")
		if(r == true){
			
			window.open("../report/permittowork_NESCO.php?rec="+rec,"_blank");

		}
	}
</script>