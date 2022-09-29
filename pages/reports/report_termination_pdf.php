<?php	
	include('../../../report/fpdf/fpdf.php');
	include('../../../connection.php');

	class PDF extends FPDF
	{
		function Header(){}		
		function Footer(){}
	}	

	// Instanciation of inherited class
	$pdf = new PDF();
	$pdf->AliasNbPages();
	$pdf->AddPage("P","Letter");	
	$pdf->SetFont('Times','',12);
	$pdf->SetAutoPageBreak('on',5);	
	$pdf->SetTitle('Termination of Contract');	

	$code 	= $_GET['code'];
	$ec	 	= explode("/",$code);
	$cc	   	= @$ec[0];
	$bc		= @$ec[1];
	$dc		= @$ec[2];
	$sc		= @$ec[3];
	$ssc	= @$ec[4];
	$uc		= @$ec[5];

	if($cc != '')
	{		
		if($uc != ''){		@$loc = "company_code = '$cc' and bunit_code = '$bc' and dept_code = '$dc' and section_code = '$sc' and sub_section_code = '$ssc' and unit_code = '$uc' "; }
		else if($ssc !=''){	@$loc = "company_code = '$cc' and bunit_code = '$bc' and dept_code = '$dc' and section_code = '$sc' and sub_section_code = '$ssc' "; }
		else if($sc !=''){	@$loc = "company_code = '$cc' and bunit_code = '$bc' and dept_code = '$dc' and section_code = '$sc' ";  }
		else if($dc !=''){	@$loc = "company_code = '$cc' and bunit_code = '$bc' and dept_code = '$dc' ";  }
		else if($bc !=''){  @$loc = "company_code = '$cc' and bunit_code = '$bc' "; }    
		else if($cc !=''){  @$loc = "company_code = '$cc'"; }
	}
	
	$mo 	= @$_GET['mo'];
	$et 	= @$_GET['et'];			
	$date2d = date("F d, Y"); //date of today
	$dt    	= date('Y');
	if($mo == 'nextyear'){
		$dt    = $dt+1;
		$date  = $dt."-01";  
	}else{
		$mname = $nq->getmonthname(@$mo);		
		$date  = $dt."-".$mo;
	}
	
	$employeetypee = "and (emp_type IN ('NESCO','NESCO Contractual','NESCO-PTA','NESCO-PTP','NESCO Regular','NESCO Regular Partimer','NESCO Probationary') )";
	
	if($et == 'All'){ $employeetypee = $employeetypee; } else { $employeetypee = "and emp_type = '$et' "; }  	
	$query = mysql_query("SELECT record_no, emp_id, position, company_code, bunit_code, dept_code, section_code, sub_section_code firstname, lastname, eocdate, emp_type 
		FROM employee3 
		INNER JOIN applicant ON applicant.app_id = employee3.emp_id 
		WHERE $loc and eocdate like '$date%' and current_status = 'active' $employeetypee ");		
		

while ($row = mysql_fetch_array($query))
{
	$q1 = mysql_query("SELECT lastname, firstname, middlename, company_code, bunit_code, dept_code,  section_code, sub_section_code,  eocdate, emp_type 
		FROM applicant 
		INNER JOIN employee3 on applicant.app_id = employee3.emp_id 
		WHERE record_no = '$row[record_no]' ");
	
	while($r1 = mysql_fetch_array($q1))	
	{ 
		$name 	= $r1['lastname'].", ".$r1['firstname'];
		$ccc 	= $r1['company_code'];
		$bcc 	= $r1['bunit_code'];			
		$eocdate = $nq->changeDateFormat('F d, Y',$r1['eocdate']);
		
		$pdf->SetFont('Arial','B',12);		
		$pdf->Cell(85);		
		$emptype = $r1['emp_type'];

		$ques 		= mysql_query(" select * from termination_header ");
		while($rq 	= mysql_fetch_array($ques)){
			if($rq['cc'] == $ccc && $rq['bc'] == $bcc){
				$assignedat = $rq['assignedat'];
			}
		}
		
		//$eocdate = new DateTime($r1['eocdate']); 
		$pdf->SetFont('Arial','B',12);	

		if($emptype == "NESCO" || $emptype == "NESCO Contractual" || $emptype == "NESCO-PTA" || $emptype == "NESCO-PTP" || $emptype == "NESCO Probationary"){
			$pdf->Ln(5);
			$pdf->Cell(85);	
			$pdf->Cell(30,5,'Notice of End of Contract',0,0,'C');
			$pdf->Ln(5);
			$pdf->SetFont('Arial','',11);	
			$pdf->Cell(40);
			$pdf->Cell(120 ,4, 'NESCO Multi-Purpose Cooperative',0,0,'C'); //For Network Services Cooperative (NESCO) Member
			$pdf->Ln();
			$pdf->Cell(49);
			$pdf->Cell(100 ,4, 'Assigned at '.$assignedat,0,0,'C');
			$pdf->Ln(5);
		}
		else{
			$pdf->Ln(5);
			$pdf->Cell(85);		
			$pdf->Cell(30,5,strtoupper($assignedat),0,0,'C');
			$pdf->Ln(5);
			$pdf->SetFont('Arial','',12);	
			$pdf->Cell(75);
			$pdf->Cell(50 ,4, 'Termination of Contract',0,0,'C');
			$pdf->Ln(6);
		}		
			
		$pdf->Cell(150);
		$pdf->SetFont('Arial','B',11);	
		$pdf->Cell(1,7, 'Date:','U',0,0);	
		$pdf->SetFont('Arial','BU',11);		
		$pdf->Cell(30 ,7, $date2d);	
		$pdf->Ln(10);
		$pdf->Cell(5);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(30 ,7, 'TO');		
		$pdf->Cell(3 ,7, ": ");	
		$pdf->SetFont('Arial','BU',11);	
		$pdf->Cell(35 ,7,mb_convert_encoding(strtoupper($name), '', 'UTF-8'));	 //UCS-2LE	
		$pdf->Ln();
		$pdf->Cell(5);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(30 ,7, 'DEPT/SEC');	
		$pdf->Cell(3 ,7, ': ');	
		$pdf->SetFont('Arial','BU',11);	
		
		$cc = $r1['company_code'];		
		$bc = $r1['bunit_code'];		
		$dc = $r1['dept_code'];
		$sc = $r1['section_code'];
		$ssc= $r1['sub_section_code'];

		if($r1['sub_section_code'] != ''){ $sscn = " - ".$nq->getSubSectionName($ssc,$sc,$dc,$r1['bunit_code'],$cc);} else { $sscn = '';} 
		if($r1['dept_code'] != ''){ $dcn = $nq->getDepartmentName($dc,$r1['bunit_code'],$cc);	} else { $dcn = '';}
		if($r1['section_code'] != ''){ $scn = " - ".$nq->getSectionName($sc,$dc,$r1['bunit_code'],$cc); } else { $scn = '';}
		
		if($cc == '01' && $bc == '07' && $dc == '12'){
			$dept = "CDC".$scn.$sscn;
		}
		else if($cc == '01' && $bc == '07' && $dc == '01'){
			$dept = "UDC".$scn.$sscn;
		}
		else if($cc == '03' && $bc == '01' || $cc == '02' && $bc == '03' || $cc == '02' && $bc == '02' || $cc == '02' && $bc == '01'){
			if($dcn == "Home & Fashion" || $dcn == "Home and Fashion" || $dcn == "HOME & FASHION" || $dcn == "HOME & FASHION" ){
				$dept = "H&F".$scn.$sscn;
			}else{	
				$dept = $dcn.$scn.$sscn;
			}
		}else{
			$dept = $nq->getBusinessUnitName($r1['bunit_code'],$cc)." - ".$nq->getDepartmentName($dc,$r1['bunit_code'],$cc);				
		}		
		
		$pdf->Cell(100 ,7, $dept );
		$pdf->Ln();	
		$pdf->Cell(5);	
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(30 ,7, 'FROM');	
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(55 ,7, ': THE GENERAL MANAGER');	
		$pdf->Ln(10);
		$pdf->SetFont('Arial','',11);		
		$pdf->Cell(35);	

		if($emptype == "NESCO" || $emptype == "NESCO Contractual" || $emptype == "NESCO-PTA" || $emptype == "NESCO-PTP" || $emptype == "NESCO Probationary"){
			$pdf->Cell(150 ,7, 'Please be reminded that according to the notice we received from your cooperative,');
			$pdf->Ln(5);
			$pdf->Cell(5);		
			$pdf->Cell(91 ,7, 'your assignment on this establishment will expire on');				
			$pdf->SetFont('Arial','BU',12);
			$pdf->Cell(35 ,7, $eocdate.'.','U');

			$pdf->Ln(10);
			$pdf->SetFont('Arial','',11);	
			$pdf->Cell(35);
			$pdf->Cell(15 ,6, "In connection with this, you are advised to yield all company properties under your care,");	
			$pdf->Ln(5);
			
			$pdf->Cell(5);
			$pdf->SetX(15); 			
			$pdf->MultiCell(178, 5, "and seek clearance before you leave the premises of $assignedat at the close of business hours on such day.");

		}else{
			$pdf->Cell(150 ,7, 'Please be informed that your employment contract with our company will expire on');	
			$pdf->Ln(5);
			$pdf->Cell(5);		
			$pdf->SetFont('Arial','BU',12);
			$pdf->Cell(35 ,7, $eocdate.'.','U');
			$pdf->Ln();
			$pdf->SetFont('Arial','',11);	
			$pdf->Cell(35);
			$pdf->Cell(15 ,7, "Accordingly, you are advised to settle your accounts, yield all company's properties under");	
			$pdf->Ln(5);
			$pdf->Cell(5);	
			$pdf->Cell(15 ,7, 'your care, and seek clearance before you leave the company premises at the close of business hours on');	
			$pdf->Ln(5);		
			$pdf->Cell(5);	
			$pdf->Cell(15 ,7, 'such day.');	
			$pdf->Ln();		
			$pdf->Cell(35);
			$pdf->Cell(15 ,7, "We wish to take this opportunity to manifest our gratefulness to have you apart of us for");	
			$pdf->Ln(5);
			$pdf->Cell(5);	
			$pdf->Cell(15 ,7, 'these few months.');	
		}

		$pdf->Ln();	
		$pdf->Cell(35);			
		$pdf->Cell(15 ,7, 'Thank you and good luck!');	
			
		if($emptype == "NESCO" || $emptype == "NESCO Contractual" || $emptype == "NESCO-PTA" || $emptype == "NESCO-PTP" || $emptype == "NESCO Probationary")
		{		
			$pdf->SetFont('Arial','B',11);				
			$pdf->Ln(17);	
			$pdf->Cell(125);
			$pdf->Cell(15 ,7, 'MERCEDES C. NARCE');				
			$pdf->Ln(5);
			$pdf->Cell(128);
			$pdf->Cell(15 ,7, 'NESCO MANAGER');	
			$pdf->Ln(30);	
		}
		else
		{	
			$pdf->Ln(6);
			$pdf->SetFont('Arial','B',11);	
			$pdf->Cell(125);
			$pdf->Cell(15 ,7, "MR. MARLITO C. UY");	
			$pdf->Ln(5);
			$pdf->SetFont('Arial','',11);	
			$pdf->Cell(115);	
			$pdf->Cell(15 ,7, 'For');	
			$pdf->Ln(5);	
			$pdf->SetFont('Arial','B',11);	
			$pdf->Cell(125);	
			$pdf->Cell(15 ,7, 'MS. MARIA NORA A. PAHANG');	
			$pdf->Ln(5);	
			$pdf->Cell(136);	
			$pdf->Cell(15 ,7, 'HRD MANAGER');	
			$pdf->Ln(30);
		}

	//********************************* for report logs	*********************************************//
	$activity 		= "Generate Termination of Contract Report of ".$name;
	$date 			= date("Y-m-d");
	$time 			= date("H:i:s");
	$nq->savelogs($activity,$date,$time,@$_SESSION['emp_id'],@$_SESSION['username']);		
	/************************************************************************************************/
	}
}		
$pdf->Output();
?> 