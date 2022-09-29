<div class="panel panel-default" style='margin-left:auto;margin-right:auto;width:99%;'>
	<div class="panel-heading"> <b> <?= $title;?> </b> </div>
	<div class="panel-body">				
		<table id="jobTransfer" class='table table-striped' cellspacing="0" width="100%" style='font-size:11px'> 
			<thead>
				<tr>		
					<th>TRANSNO</th>
					<th>EMP NO</th>
					<th>NAME</th>
          <th>EMPTYPE</th>
					<th>EFFECTIVE</th>	
					<th>OLD POSITION</th>	
					<th>NEW POSITION</th>
					<th>ACTION</th>					
				</tr>
			</thead> 			
		</table>
	</div>
</div>	

<!-- view job transfer -->
<div id = "jobtransfer" class="modal fade bs-example-modal-lg">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header alert-info">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="closeModal('photo')">&times;</button>
        <h4 class="modal-title">Job Transfer Report</h4>
      </div>
      <div class="modal-body">
          <div class = "jobtrans">
              <embed id="view_jobTrans" src="" width="100%" height="480"></embed>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="dis_ btn btn-default " data-dismiss="modal" onclick="closeModal('photo')">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
<!-- end here -->

<script>
  function viewJobTrans(transNo)
  {
	  $("#jobtransfer").modal({
        backdrop: 'static',
        keyboard: false
    });

    $("#jobtransfer").modal("show");
    $.ajax({
      type : "POST",
      url  : "functionquery.php?request=getEmpJobTransFile",
      data : { transNo:transNo },
      success : function(data){
        data = data.trim();
        if(data != ''){
            document.getElementById("view_jobTrans").src = data;  
        }
      }
    });
  }
</script>