<style type="text/css">    
    .search-results{
       box-shadow: 5px 5px 5px #ccc; 
       margin-top: 1px; 
       margin-left : 0px; 
       background-color: #F1F1F1;
       width : 78%;
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
<div class='col-md-8 col-md-offset-2'>
	<div class='row'>
		<br>
		<div class='panel panel-default'>
			
			<div class='panel-heading'><center><h4> CREATE CONTRACT & PERMIT FOR REAPPLY EMPLOYEE </h4></center></div>
			<div class='panel-body'>
				<div style='background:white;padding:8px 40px; 8px 40px '>
					
					<input type="hidden" name="empId">
					<input type="hidden" name="recordNo">

					<center><label> Search Employee</label></center>			
					<input type="text" name="app_id" onkeyup="nameSearch(this.value)" class="form-control textFocus" placeholder="Search Employee (Emp. ID, Lastname or Firstname)" value="" autocomplete="off">
					<div class="search-results" style="display:none;"></div>

					<br>
					<center><button class='btn btn-primary btn-sm' onclick='proceed()'>Proceed</button></center>				
					<hr>
					<i style='color:green'><b>Note:</b> Please make sure that all requirements have been submitted for reapplication before creating the contract. Also, only <b>End of Contract</b> and <b>Resigned</b> employee status are allowed for this process.</i>
				</div>
			</div>
		</div>
	</div>
</div>

<script>

	function nameSearch(key){

        $("[name = 'app_id']").css('border-color','#ccc');
        $(".search-results").show();

        var str = key.trim();
        $(".search-results").hide();
        if(str == '') {
            $(".search-results-loading").slideUp(100);
        }
        else {
            $.ajax({
                type : "POST",
                url  : "functionquery.php?request=findEmpEOCandResign",
                data : { str : str},
                success : function(data){
                    if(data){
                        $(".search-results").show().html(data);
                    }
                } 
            });
        }
    }

    function getEmpId(id){

        var id = id.split("*");
        var empId = id[0].trim();
        var recordNo = id[1].trim();
        var name = id[2].trim();

        $("[name='app_id']").val(empId+" * "+name);
        $("[name='recordNo']").val(recordNo);
        $("[name='empId']").val(empId);
        $(".search-results").hide();
    }

	function proceed(){

        var recordNo = $("[name='recordNo']").val();
        var empId = $("[name='empId']").val();
        var app_id = $("[name='app_id']").val();

        if(app_id == ""){
            alert("Please SEARCH EMPLOYEE first!");
            $("[name = 'app_id']").css('border-color','#E55B5B');
        } else {
		    window.location = "?p=employment&&db=contracts&&rec="+recordNo+"&&e="+empId+"&&reap=reapply"; 
        }
	}
</script>
</html>