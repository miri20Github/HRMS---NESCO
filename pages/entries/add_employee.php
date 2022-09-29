<?php

	//encoder empid
	$invalidID = array("04517-2015","03442-2015","18217-2013","04819-2015","02951-2016","01653-2013","00556-2017","00677-2017","06359-2013");
	
?>
<style type="text/css">
	.form-control, .btn {
		border-radius: 0px;
		font-size: 14px;
	}

	label {
		font-size: 14px;
	}

	.search-results {

        box-shadow: 5px 5px 5px #ccc; 
        margin-top: 1px; 
        margin-left : 0px; 
        background-color: #F1F1F1;
        width : 33%;
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
</style>

<div class="row" style="width:70%;margin-left:auto; margin-right:auto; background:white;padding:30px">
	
	<div class='panel panel-default'>
	<div class='panel-heading'>
		<div style='font-size:24px;text-indent:10px;'> Add New Employee </div>
	</div>      
    <div class="panel-body">


	  	<div class="form-group">
	  		<label>Search Applicant</label>
		    <div class="input-group">
		        <input type="text" name="appname" onkeyup="nameSearch(this.value)" class="form-control" placeholder="Search (Emp. ID, Lastname or Firstname)" value="" autocomplete="off">
		        <span class="input-group-btn">
		          <button class="btn btn-primary" name="search">Search &nbsp;<i class="glyphicon glyphicon-search"></i></button>
		        </span>
		    </div>
		    <div class="search-results" style="display:none;"></div>
		</div>
	  	<div class="form-group">
			<label>Employee Type</label>		
			<select class="form-control" name="emp_type" onchange="showValue(this.name)" required>
				<option value="">Select</option>
				<?php

					$query = mysql_query("SELECT * FROM employee_type WHERE emp_type = 'NESCO' or  emp_type = 'NESCO Contractual' or emp_type = 'NESCO-PTA' or emp_type = 'NESCO-PTP'")or die(mysql_error());
					while($r = mysql_fetch_array($query)){
						echo "<option value='".$r['emp_type']."'>".$r['emp_type']."</option>";
					}
				?>						
			</select>
			
	  	</div>
	   	<div class="form-group">
			<label>Position</label>
			<select class="form-control" name="position" onchange="showValue(this.name)" required>
				<option value="">Select</option>
				<!-- <option value="1">Others</option> -->
				<?php $query = mysql_query("SELECT position FROM positions ORDER BY position ASC")or die(mysql_error());  
				  while($rs = mysql_fetch_array($query))
				  { 
					echo "<option value='".$rs['position']."'>".$rs['position']."</option>";  
				  }?>
			</select>
	  	</div>
	  <?php
		$securityCheck = 0;
		for($x=0;$x<count($invalidID);$x++){
			if($invalidID[$x] == $_SESSION['emp_id']){
				$securityCheck = 1;
			}
		}
		if($securityCheck == 1) { ?>
		  	<div class="form-group">
				<label>Current Status</label>
				<select class="form-control" name="c_stat" required>
					<option value="">Select</option>
					<option value="Active">Active</option>
					<option value="End of Contract">EOC</option>
					<option value="Resigned">Resigned</option>
				</select>
		  	</div>
	<?php } ?>
	   <div class="form-group">
			<button class="btn btn-primary" onclick="addEmployee()"> Add </button>
			<button class="btn btn-danger" onclick="cancel()"> Cancel </button>
	   </div>
	   <div class="form-group">
			 <center><span class="msg"></span></center>
	   </div>

	</div>
	</div> 
</div>
<script>

	function nameSearch(key){

        $(".search-results").show();
        $("[name = 'appname']").css('border-color','#ccc');
        var str = key.trim();
        $(".search-results").hide();
        if(str == '') {
            $(".search-results-loading").slideUp(100);
        }
        else {
            $.ajax({
                type : "POST",
                url  : "functionquery.php?request=findThisApplicant",
                data : { str : str},
                success : function(data){
                    if(data){
                        $(".search-results").show().html(data);
                    }
                } 
            });
        }
    }

    function getId(id){

        var id = id.split("*");
        var empId = id[0].trim();
        var name = id[1].trim();

        $("[name='appname']").val(empId+" * "+name);
        $(".search-results").hide();
    }

    function showValue(name){

    	$("[name = '"+name+"']").css('border-color','#ccc');
    }

    function addEmployee(){

    	var appname  = $("[name = 'appname']").val().trim();
    	var position = $("[name = 'position']").val();
    	var emp_type = $("[name = 'emp_type']").val();
    	var c_stat 	 = $("[name = 'c_stat']").val();

    	if(appname == "" || position == "" || emp_type == ""){

    		alert("Fill up required fields!");
    		if(appname == ""){
    			$("[name = 'appname']").css('border-color','#E55B5B');
    		}

    		if(position == ""){
    			$("[name = 'position']").css('border-color','#E55B5B');
    		}

    		if(emp_type == ""){
    			$("[name = 'emp_type']").css('border-color','#E55B5B');
    		}
    	} else {

	    	$.ajax({
	            type : "POST",
	            url  : "functionquery.php?request=addEmployee",
	            data : { appname:appname, position:position, emp_type:emp_type, c_stat:c_stat },
	            success : function(data){
	            	data = data.trim();
	            	data = data.split("*");
	                if(data[0] == "Ok"){
	                    
	                    if(data[1] == 1){
	                		
	                		$(".msg").html("<div class='alert alert-success' role='alert'>New Employee Successfully Added!</div>");
	                    } else {
	                		$(".msg").html("<div class='alert alert-danger' role='alert'>This Applicant is already added as Employee!</div>");
	                    }
	                    cleardata();

	                } else {
	                	alert(data);
	                }
	            }
	        });
	    }
    }

    function cancel(){

    	var r = confirm("Are you sure to cancel Add New Employee?");
    	if(r == true){
	    	cleardata();
	    }
    }

    function cleardata(){

    	var appname  = $("[name = 'appname']").val("");
    	var position = $("[name = 'position']").val("");
    	var emp_type = $("[name = 'emp_type']").val("");
    	var c_stat 	 = $("[name = 'c_stat']").val("");
    }
</script>