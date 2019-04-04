$(function(){
	$('#submit-login').live('click',function(){
		var username = $('#user-id').val();
		var password = $.md5($('#user-password').val());
		
		if(username.length==0 || password.length==0){
			$.msgBox({
				title: "Incomplete Entry",
				content: "User name and Password are required",
				type: "error",
				showButtons: true,
				opacity: 0.9,
				autoClose:true
			});
			return;
		}
		if (base_url.toLowerCase().indexOf("index.php") <= 0){
			base_url=base_url+'index.php';
		}
		var request=$.ajax({
		    	type:'get',
		    	url: base_url+"/login_controller/validate_credential_action_ajax",
				data:  { username: username, password: password },
				//data: data,
		    	//dataType:'text',
				beforeSend:function(){
					//****
					var request=$.ajax({
						type:'get',
						url: base_url+"/report_controller/save_business_status_report",
						data: {},//end of data
						beforeSend:function(){},
						success: function(data, textStatus, xhr) {
							
						}//end of success
					});//end of post request
					//*****
				},
		    	success: function(data, textStatus, xhr) {
					
					useReturnData(data);
		    	}
				
		});
		function useReturnData(data){
		    	var response=$(data);
				var msg=response.filter('#msg').text();
				var err=response.filter('#error').text();

				if(err!='error'){
					window.location=base_url+'/site_controller/members_area_facade';	//redirect to valid url
				}else{
					csscody.error(msg);
				}
		};
	});
	$('#user-password').live('keypress',function(e){
		if (e.keyCode == 13) {               
    		e.preventDefault();
    		$('#submit-login').trigger('click');
  		}
	});
});