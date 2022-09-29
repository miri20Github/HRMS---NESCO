<style type="text/css">	
	.panel, .btn, .form-control {
		border-radius: 0px;
	}

	.search-results{

       	box-shadow: 5px 5px 5px #ccc; 
       	margin-top: 1px; 
       	margin-left : 43px; 
       	background-color: #F1F1F1;
       	width : 46%;
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

    .size-emp {
    	max-height:400px;
       	overflow-y:scroll;
       	overflow:auto;
    }
</style>

<div class="container-fluid">
    <div class="panel panel-default">
        <div class="panel-heading">       
                <span><h4 class="text-center"> TAG FOR RESIGNATION </h4></span>	
        </div>  
        <div class="panel-body">
        	<input type="hidden" name="supId">
            <div class="form-group">
            	<div style="width:70%">
				    <div class="input-group">
				    	<span class="input-group-btn">
                            <button class="btn btn-default" name="search">&nbsp;<i class="glyphicon glyphicon-user"></i></button>
                        </span>
				        <input type="text" name="supName" onkeyup="nameSearch(this.value)" class="form-control textFocus" placeholder="Search Employee (Emp. ID, Lastname or Firstname)" value="" autocomplete="off">
				    </div>
				    <div class="search-results" style="display:none;"></div>
            	</div>
			</div>
			<br>
			<p>
		        <label>LEGEND :</label>
		        <span style="margin-left:20px;"><a class="btn btn-primary btn-xs disabled">&nbsp;</a></span> - Pending
		        &nbsp;<span><a class="btn btn-success btn-xs disabled">&nbsp;</a></span> - Done
        	</p>
			<div class="panel panel-default">
		        <div class="panel-heading">  
		            <div class="row">
		              <div class="col-md-12">      
		                <span class="text-center" style="font-size:16px; font-weight:bold;"> Subordinates </span>
		              </div>
		            </div>  
		        </div>  
		        <div class="panel-body listEmp">
		        	<small>Nothing to display</small>
		        </div>
		    </div>
        </div>
    </div>
</div>

<script>	
	function nameSearch(key){
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
                        $(".search-results").show().html(data);
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
        $(".search-results").hide();
       	tagResignation();
    }

    function tagResignation(){
    	var supId = $("[name = 'supId']").val();
    	$(".listEmp").html('<img src = "../images/ajax.gif"> <span class = loading_msg"><font size = "2">Please Wait....</font></span>');
    	
    	$.ajax({
            type : "POST",
            url  : "functionquery.php?request=load_subordinates",
            data : { supId:supId },
            success : function(data){
            	// alert(data);
                setTimeout(function(){

			     	$(".listEmp").html(data);  
			    },1000);
            } 
        });
    }

    function tagForReg(id,ids){
	  	if(!confirm("Are you sure?")) return false;

	  	$.ajax({
		  	type : "POST",
		  	url : "functionquery.php?request=tag_for_resignation",
		  	data: { id : id, ids : ids },
		  	success: function(data){
				if(data == "Ok"){

					alert("Successfully Tagged for Resignation");
					tagResignation();
				} else {
					alert(data);
				}
		  	}
	  	});
	}

	function unTagForReg(id,ids){
  		if(!confirm("Are you sure?")) return false;
  
  		$.ajax({
	  		type : "POST",
	  		url : "functionquery.php?request=untag_for_resignation",
	  		data: { id:id, ids:ids },
		  	success: function(data){
				if(data == "Ok"){

					alert("Successfully Untagged for Resignation");
					tagResignation();
				} else {
					alert(data);
				}
		  	}
  		});
	}
</script>