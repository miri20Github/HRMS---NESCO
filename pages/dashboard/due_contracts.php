<div class="panel panel-default" style='margin-left:auto;margin-right:auto;width:99%;'>
	<div class="panel-heading"> <b> DUE CONTRACTS REPORT</b> </div>
		<div class="panel-body">
			<?php include('companydetails.php'); ?>
			<tr>
				<td colspan='2' align='center'>
					<button type='button' name='submit-pdf' class='btn btn-primary btn-sm' onclick='pdf_rep()'> 
					Generate in PDF <img src='../images/icons/pdf-icon.png'/></button> &nbsp;
				</td>
			</tr>
		</div>
	</div>
</div>

<script>
	function pdf_rep()
	{	
		var cc = $("[name='comp_code']").val();
		var bc = $("[name='bunit_code']").val();
		var dc = $("[name='dept_code']").val();
		var sc = $("[name='sec_code']").val();
		var ssc= $("[name='ssec_code']").val();
		
		if(ssc !=""){ 	code = ssc;}
		else if(sc !=""){ 	code = sc; }
		else if(dc !=""){ 	code = dc; }
		else if(bc !=""){ 	code = bc; }
		else if(cc != ""){ 	code = cc; }
		else{ code = '';}

		if(cc == "" || bc == ""){
			alert('Please do not leave required fields empty!')
		}else{
			window.open("../report/due_contract_report.php?code="+code, "_blank");		
		}
	}
</script>