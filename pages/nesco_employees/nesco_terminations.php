<div class="panel panel-default" style="width:100%; margin-left:auto; margin-right:auto;">
	<div class="panel-heading"> <b> <?= $title;?> </b> </div>  	  
	<div class="panel-body">
	    <table class="table table-striped" width="100%" id="termination" style="font-size:11px">
			<thead>	
            	<tr>
                    <th width="3%">NO</th>
					<th width="6%">EMPID</th>
                    <th width="17%">NAME</th>
					<th width="7%">DATE RESIGNED</th>
                    <th width="7%">DATE UPDATED</th>
                    <th width="20%">REMARKS</th>					
					<th width="8%">RESIGNATION LETTER</th>                               
                </tr>
			</thead>
            </table>
		</div>
	</div>
</div>
<br>  

<!-- Modal for upload profile photo -->
<div class="modal fade" id="viewresignation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="height:530px;width:70%"> 
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Resignation Letter</i></h4>
      </div>
    <div class="modal-body" align="center"> 
		<div id='resig'></div>
      </div> 
    </div>
    <!--<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>-->
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
function viewresig(recno)
{   
	$.ajax({
		type: "POST",
		url: "functionquery.php?request=viewresig",
		data: { recno:recno },
		success: function(data)
		{		
			$('#resig').html("<center><img src='"+data+"' style='border:1px solid #ccc;width:100%' ></center>");
			
		}
	});
}
</script>