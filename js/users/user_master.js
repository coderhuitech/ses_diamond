$(function(){
	$('#left-div').on('click','#chang-password', function() {
		var request=$.ajax({
			type:'get',
			url: base_url+"/user_controller/change_password_view",
			data: {},//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#result-div').html(data);
			}//end of success
		});//end of post request
	});
	
	$('#submit-new-password').live('click', function() {
		alert("adsfad");
	});
});