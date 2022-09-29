$(function(){	
	//used in comments sa index
	/*	
	function loadComments() {
		
		var t = setInterval(
			function(){
				$("#comments").load('./functionquery.php?request=comments').fadeIn("slow");
			},1000
		);
		setTimeout("loadComments()",500)
	}
	*/
	/*
	function loadBirthdays() {
	
		var t = setInterval(
			function(){
				$("#birthday").load('./functionquery.php?request=birthday').fadeIn("slow");
			},1000
		);
		setTimeout("loadBirthdays()",500)
	}*/
	function loadNewEmp() {
		
		var t = setInterval(
			function(){
				$("#newemp").load('./functionquery.php?request=newemp').fadeIn("slow");
			},1000
		);
		setTimeout("loadNewEmp()",500)
	}
	function loadRenewEmp() {
		var t = setInterval(
			function(){
				$("#renewemp").load('./functionquery.php?request=renewemp').fadeIn("slow");
			},1000
		);
		setTimeout("loadRenewEmp()",500)
	}
	/*
	function loadEndOfContract(){
		var t = setInterval(
			function(){
				$("#eoc").load('./functionquery.php?request=endofcontract').fadeIn("slow");
			},1000
		);
		setTimeout("loadRenewEmp()",500)		
	}
	*/

	function loadforClearance() {
		
		var t = setInterval(
			function(){
				$("#readyclearances").load('./functionquery.php?request=ready_for_clearance').fadeIn("slow");
			},1000
		);
		setTimeout("loadforClearance()",500)
	}
	function loadPendingClearance() {
		var t = setInterval(
			function(){
				$("#pgclearances").load('./functionquery.php?request=pending_clearances').fadeIn("slow");
			},1000
		);
		setTimeout("loadPendingClearance()",500)
	}
	function loadApprovedClearance(){
		var t = setInterval(
			function(){
				$("#okclearances").load('./functionquery.php?request=approved_clearances').fadeIn("slow");
			},1000
		);
		setTimeout("loadApprovedClearance()",500)		
	}
	function loadProcessEoc(){
		var t = setInterval(
			function(){
				$("#processeoc").load('./functionquery.php?request=process_eoc').fadeIn("slow");
			},1000
		);
		setTimeout("loadProcessEoc()",500)		
	}
	function loadNewInterval(){
		var t = setInterval(
			function(){
				$("#newinterval").load('./functionquery.php?request=new_interval').fadeIn("slow");
			},1000
		);
		setTimeout("loadNewInterval()",500)		
	}
	function loadPendingInterval(){
		var t = setInterval(
			function(){
				$("#pendinginterval").load('./functionquery.php?request=pending_interval').fadeIn("slow");
			},1000
		);
		setTimeout("loadPendingInterval()",500)		
	}
	

	//claire
	function tags() {
		var t = setInterval(
			function(){
				$("#scheds").load('./functionquery.php?request=checks&def=scheds').fadeIn("slow");
				$("#failings").load('./functionquery.php?request=checks&def=failings').fadeIn("slow");
				$("#genatt").load('./functionquery.php?request=checks&def=genatt').fadeIn("slow");
				$("#checkings").load('./functionquery.php?request=checks&def=checkings').fadeIn("slow");
				$("#gencert").load('./functionquery.php?request=checks&def=gencert').fadeIn("slow");
				$("#history").load('./functionquery.php?request=checks&def=history').fadeIn("slow");
				$("#tags1").load('./functionquery.php?request=tags1').fadeIn("slow");
				$("#tags2").load('./functionquery.php?request=tags2').fadeIn("slow");
				$("#tags3").load('./functionquery.php?request=tags3').fadeIn("slow");
				$("#tags4").load('./functionquery.php?request=tags4').fadeIn("slow");
				$("#tags5").load('./functionquery.php?request=tags5').fadeIn("slow");
				$("#tags6").load('./functionquery.php?request=tags6').fadeIn("slow");
				$("#tags7").load('./functionquery.php?request=tags7').fadeIn("slow");
			},1000
		);
		setTimeout("tags()",500)
	}
	function tagy(val) {
		var t = setInterval(
			function(){
				$("#imagelist").load('./functionquery.php?request=tags8&t='+val).fadeIn("slow");
			},1000
		);
		setTimeout("tagy()",500)
	}
	//window.onload = loadComments()
	//window.onload = loadEndOfContract()
	//window.onload = loadBirthdays()	
	//window.onload = loadNewEmp()
	//window.onload = loadRenewEmp()
	//window.onload = loadNewInterval()
	//window.onload = loadPendingInterval()

	//used sa clearance when renewing
	//window.onload = loadforClearance()	
	//window.onload = loadPendingClearance()
	//window.onload = loadApprovedClearance()
	//window.onload = loadProcessEoc()

	//claire
	//window.onload = tags()
	window.onload = tagy(document.getElementById('photoid').value)

});