
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />


<style>#search {height:30px;}</style>
<title>HRMS</title>
</head>
<style type="text/css">
    .search-results{

       box-shadow: 5px 5px 5px #ccc; 
       margin-top: 1px; 
       margin-left : 0px; 
       background-color: #F1F1F1;
       width : 64%;
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

    .btn {

       border-radius: 0px;
    }

</style>
<body>
<div style="width:60%; margin-left:auto; margin-right:auto; ">   	
	<div class='panel panel-default'>
		<div class='panel-heading'>
			<div style='font-size:24px;text-indent:10px;'> Job Transfer</div>
		</div>      
	  
		<div class="panel-body">	
			<div class='row'>
				<div class='col-md-12'>				
					<table class="table" > 
						<tr>
							<td>To</td>
							<td>	
								<div class="form-group">
									<div class="input-group">
										<input type="text" name="app_id" onkeyup="namesearch(this.value)" class="form-control" placeholder="Search Employee (Emp. ID, Lastname or Firstname)" value="" autocomplete="off" required="">
										<span class="input-group-btn">
											<button class="btn btn-info" name="search" >Search &nbsp;<i class="glyphicon glyphicon-search"></i></button>
										</span>
									</div>
									<div class="search-results" style="display:none;"></div>
								</div>								
							</td>
						</tr>				
					</table>
				</div>        
			</div>
		</div>
	</div>
</div>


<script>
function namesearch(key){

	$(".search-results").show();

	var str = key.trim();
	$(".search-results").hide();
	if(str == '') {
		$(".search-results-loading").slideUp(100);
	}
	else {
		$.ajax({
			type : "POST",
			url  : "functionquery.php?request=findEmpActive",
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

        window.location = '?p=transfers&&empid='+empId;
    }
</script>
