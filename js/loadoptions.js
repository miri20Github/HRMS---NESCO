function load_options(id,index){	
	if(index=="businessunit"){		
		$("#businessunit").html('<option value="">No Business Unit </option>');
		$("#department").html('<option value="">No Department</option>');
		$("#section").html('<option value="">No Section</option>');		
		$("#subsection").html('<option value="">No Sub Section</option>');
		$("#unit").html('<option value="">No Unit</option>');
	}
	else if(index=="department"){ 	
		$("#department").html('<option value="">No Department</option>');		
		$("#section").html('<option value="">No Section</option>');		
		$("#subsection").html('<option value="">No Sub Section</option>');
		$("#unit").html('<option value="">No Unit</option>');
	}
	else if(index=="section"){ 	
		$("#section").html('<option value="">No Section</option>');		
		$("#subsection").html('<option value="">No Sub Section</option>');
		$("#unit").html('<option value="">No Unit</option>');
	}
	else if(index=="subsection"){ 	
		$("#subsection").html('<option value="">No Sub Section</option>');
		$("#unit").html('<option value="">No Unit</option>');
	}
	else if(index=="unit"){ 		
		$("#unit").html('<option value="">No Unit</option>');
	}
	else{
	}
	
	var com	= document.getElementById('company').value;
	var bu	= document.getElementById('businessunit').value;	
	var dept= document.getElementById('department').value;
	var sec = document.getElementById('section').value;
	var subs = document.getElementById('subsection').value;

	$.ajax({
			
			url: "../placement/subsectioning.php?index="+index+"&c="+com+"&b="+bu+"&d="+dept+"&s="+sec+"&ss="+subs,			
			complete: function(){$("#loading").hide();
				},
			success: function(data) {
				$("#"+index).html(data);
			}
		
	})
}