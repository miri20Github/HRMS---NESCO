$(document).ready(function(){

  $('#pollSlider-button').click(function() {
    if($(this).css("margin-right") == "350px"){
        $('.pollSlider').animate({"margin-right": '-=350'});
        $('#pollSlider-button').animate({"margin-right": '-=350'});
    }
    else{
        $('.pollSlider').animate({"margin-right": '+=350'});
        $('#pollSlider-button').animate({"margin-right": '+=350'});
    }
  });
 });  
    
 function gothere(){
	 window.parent.window.location = 'view_list_applicants.php';
	 }
 function gotheres(){
	 window.parent.window.location = 'view_list_blacklisted.php';
	 }

$().ready(function() {
	$("#search").autocomplete("get_data_list.php", {
		width: 260,
		matchContains: true,
		selectFirst: false
	});
});
