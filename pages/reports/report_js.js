
    //this function is used by all reports in this report folder
    //date created: 09212022 by miri
    function getCode()
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

        return code;
    }

	//report_benefits.php
	function benefits_excel()
	{	
		var etype 	= $("[name='etype']").val();
		var ben_no 	= $("[name='benefitsno']").val();
		code 		= getCode();		
		if(code == '' || etype ==''){
			alert('Please fill up the required fields.')
		}else{
			window.location = "pages/reports/excel_reports.php?code="+code+"&etype="+etype+"&filename=benefits_report&rname=benefits-report&ben_no="+ben_no;	
		}
	}

	//report_birthday.php
	function showdisplay(){
		var m = $('#birthmonth').val();
		if(m =='all'){		
			$('#displayopt').show();
			$('#disopt').show();		
		}else{		
			$('#displayopt').hide();
			$('#disopt').hide();
		}
	}  

	//report_birthday.php
	function bday_excel()
	{
		var opt  	= $("[name='displayopt']").val(); 
		var bmonth  = $("[name='birthmonth']").val();		
		code 		= getCode();
		if(code !=''){
			if(opt == 1){
				window.location = "pages/reports/excel_reports.php?code="+code+"&filename=bday_report&rname=bday-report&mode=1";	
			}else {
				window.location = "pages/reports/excel_reports.php?code="+code+"&filename=bday_report&rname=bday-report&bmonth="+bmonth+"&mode=2";
			}
		}else{
			alert('Please select a department. ')
		}
	}

	//report_employeeStatus.php
	function empStat_excel()
	{	
		var stat= $("[name='status']").val();
		var etype= $("[name='etype']").val();
		code 	= getCode();
		
		if(code == '' || stat =='' || etype ==''){
			alert('Please fill-up the required fields.')
		}else{	
			window.location = "pages/reports/excel_reports.php?code="+code+"&stat="+stat+"&etype="+etype+"&filename=status_report&rname=status-report";	
		}
	}

	//report_statistics.php
	function statistics_excel()
	{	
		code 	= getCode();		
		if($('#showsections').is(':checked')){
			var showsections = 1;
		}else{
			var showsections = 0;
		}

		if($('#showsubsections').is(':checked')){
			var showsubsections = 1;
		}else{
			var showsubsections = 0;
		}		
		window.location = "pages/reports/excel_reports.php?code="+code+"&filename=statistics_report&rname=statistics-report&showsections="+showsections+"&showsubsections="+showsubsections;	
	}

	//report_username.php
	function username_excel(){	
		code 	= getCode();		
		window.location = "pages/reports/excel_reports.php?code="+code+"&filename=username_report&rname=username-report";	
	}

	//report_yearsInService.php
	function yrsInService_excel()
	{	
		var ord = $("[name='orderby']").val();		
		code 	= getCode();
			
		if(ord == "" ){	
			alert('Please do not leave required fields empty!')
		}else{
			window.location = "pages/reports/excel_reports.php?code="+code+"&orderby="+ord+"&filename=yearsInService_report&rname=yearsInService-report";	
		}
	}