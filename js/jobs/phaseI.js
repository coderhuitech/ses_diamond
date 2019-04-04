$(function(){
	$('#show-jobs').live('click',function(){
		var request=$.ajax({
		  				type:'get',
		   				url: base_url+"/job_controller/show_jobs_in_phaseI_ajax",
				/*		data: {model_no:$('#model-no').val()
							   ,model_size: $('#model-size').val()
							   ,appx_gold: $('#appx-gold').val()
							   ,qty: $('#qty').val()
							   ,description: $('#description').val()
							   },*/
						data: {},
						beforeSend:function(){},
		   				success: function(data, textStatus, xhr) {
								//$('#result-div').html(data);
							useReturnData(data);
							function useReturnData(data){
							    var response=$(data);
								var msg=response.filter('#msg').text();
								var err=response.filter('#error').text();
								var report=response.filter('#report').text();
								if(err!='error'){
									$('#jobs-div').html(response.filter('#report').html());
								}else{
									$.msgBox({
									    title: "Job Selection error",
									    content: msg,
									    type: "info",
									    showButtons: true,
									    opacity: 0.5,
									    autoClose:false
									});
									$('#jobs-div').html(response.filter('#report').html());
								}
							};//end of usereturndata
		   				}//end of success
					});//end of post request
	});
	$('.job-phseI-div').live('click',function(){
		var job_id = $(this).find('.job-id').text();
		$('.job-phseI-div').removeClass('selected');
		$(this).addClass('selected');
		var request=$.ajax({
		  				type:'get',
		   				url: base_url+"/job_controller/select_job_by_job_id_ajax",
						data: {job_id: job_id},
						beforeSend:function(){},
		   				success: function(data, textStatus, xhr) {
								//$('#result-div').html(data);
							useReturnData(data);
							function useReturnData(data){
							    var response=$(data);
								var msg=response.filter('#msg').text();
								var err=response.filter('#error').text();
								var report=response.filter('#report').text();
								if(err!='error'){
									/*$.msgBox({
									    title: "Order Item Selection",
									    content: msg,
									    type: "info",
									    showButtons: true,
									    opacity: 0.9,
										timeOut: 1000,
									    autoClose:true
									});*/
									$('#job-details-div').html(response.filter('#report').html());
									$( "#show-material-in-hand-karigar" ).trigger( "click" );
									$( "#karigar-balance" ).trigger( "click" );
									$('#show-material-in-hand-user').trigger( "click" );
								}else{
									$.msgBox({
									    title: "Order Item Selection error",
									    content: msg,
									    type: "info",
									    showButtons: true,
									    opacity: 0.5,
									    autoClose:false
									});
								}
							};//end of usereturndata
		   				}//end of success
					});//end of post request
	});
	$('#go').live('click',function(){
		var job_id = $('#job-id-special').val();
		var request=$.ajax({
		  				type:'get',
		   				url: base_url+"/job_controller/select_job_by_job_id_ajax",
						data: {job_id: job_id},
						beforeSend:function(){},
		   				success: function(data, textStatus, xhr) {
								//$('#result-div').html(data);
							useReturnData(data);
							function useReturnData(data){
							    var response=$(data);
								var msg=response.filter('#msg').text();
								var err=response.filter('#error').text();
								var report=response.filter('#report').text();
								if(err!='error'){
									/*$.msgBox({
									    title: "Order Item Selection",
									    content: msg,
									    type: "info",
									    showButtons: true,
									    opacity: 0.9,
										timeOut: 1000,
									    autoClose:true
									});*/
									$('#job-details-div').html(response.filter('#report').html());
									$( "#show-material-in-hand-karigar" ).trigger( "click" );
									$( "#karigar-balance" ).trigger( "click" );
									$('#show-material-in-hand-user').trigger( "click" );
								}else{
									$('#job-details-div').html(response.filter('#report').html());
									$.msgBox({
									    title: "Order Item Selection error",
									    content: msg,
									    type: "info",
									    showButtons: true,
									    opacity: 0.5,
									    autoClose:false
									});
								}
							};//end of usereturndata
		   				}//end of success
					});//end of post request
	});
	$('#return-gold').live('click',function(){
		$(this).removeClass('required');
	});
	$('#dal_used').live('click',function(){
		
		$(this).removeClass('required');
	});
	$('#bronze_used').live('click',function(){
		$(this).removeClass('required');
	});
	$('#submit').live('keypress',function(e){
		if (e.keyCode == 13) {               
    		e.preventDefault();
    		return false;
  		}
	});
	$('#submit').live('click',function(event){
		event.preventDefault();
		var no_of_required=0;
		var return_gold=parseFloat($('#return-gold').val());
		var dal_used=parseFloat($('#dal_used').val());
		var bronze_used=parseFloat($('#bnz_used').val());
		if(return_gold<=0){
			$('#return-gold').addClass('required');
			no_of_required++;
		}
		if(dal_used<=0){
			$('#dal_used').addClass('required');
			no_of_required++;
		}
		if(bronze_used<=0){
			$('#bnz_used').addClass('required');
			no_of_required++;
		}
		if(no_of_required>0){
			$.msgBox({
				title: "PHASE I Submit Error",
				content: no_of_required+" Required Fields are Missing",
				type: "info",
				showButtons: true,
				opacity: 0.9,
				timeOut: 1000,
				autoClose:false
			});
			return;
		}
		$.msgBox({
	    	title: "Save JOB",
	    	content: "Confirm to save?",
	    	type: "confirm",
	    	buttons: [{ value: "Yes" }, { value: "No" }, { value: "Cancel"}],
	    	success: function (result) {
	        	if (result == "Yes") {
							//your code here 
					var request=$.ajax({
		  				type:'post',
		   				url: base_url+"/job_controller/save_phaseI_ajax",
						data: {job_id: $('#job-id').text()
							   ,return_gold: return_gold
							   ,dal_used: dal_used
							   ,bronze_used: bronze_used
							   ,rm_id: $('#rm-id').text()
							   ,user_id: $('#user-id').text()
							   ,employee_id: $('#employee-id').text()},
						beforeSend:function(){},
		   				success: function(data, textStatus, xhr) {
								//$('#result-div').html(data);
							useReturnData(data);
							function useReturnData(data){
							    var response=$(data);
								var msg=response.filter('#msg').text();
								var err=response.filter('#error').text();
								var report=response.filter('#report').text();
								if(err!='error'){
									$.msgBox({
									    title: "JOB PHASE II",
									    content: msg,
									    type: "info",
									    showButtons: true,
									    opacity: 0.9,
										timeOut: 1000,
									    autoClose:true
									});
									$( "#show-material-in-hand-karigar" ).trigger( "click" );
									$( "#karigar-balance" ).trigger( "click" );
									$('#show-material-in-hand-user').trigger( "click" );
									$('#job-details-div').html(response.filter('#report').html());
									$('#show-jobs').trigger('click');
								}else{
									$.msgBox({
									    title: "JOB PHASE II",
									    content: msg,
									    type: "info",
									    showButtons: true,
									    opacity: 0.5,
									    autoClose:false
									});
								}
							};//end of usereturndata
		   				}//end of success
					});//end of post request
						return;
	        	}//end of yes
				
				if (result == "No") {
	            		return;
	        	}
				
				if (result == "Cancel") {
	            		return;
	        	}
	    	}
		}); //end of message
		
	});
	$('#karigar-balance').live('click',function(event){
		event.preventDefault();
		var request=$.ajax({
		  				type:'get',
		   				url: base_url+"/material_controller/create_rm_master_pivot_table",
						data: {employee_id: $('#employee-id').text()},
						beforeSend:function(){},
		   				success: function(data, textStatus, xhr) {
							$('#all-karigar-balance-div').html(data);
		   				}//end of success
					});//end of post request
	});
	$('#show-material-in-hand-karigar').live('click',function(){
		var request=$.ajax({
		  				type:'get',
		   				url: base_url+"/material_controller/show_material_by_employee",
						data: {employee_id: $('#employee-id').text()},
						beforeSend:function(){},
		   				success: function(data, textStatus, xhr) {
							$('#left-div-karigar-result').html(data);
		   				}//end of success
					});//end of post request
	});
	$('#show-material-in-hand-user').live('click',function(){
		var request=$.ajax({
		  				type:'get',
		   				url: base_url+"/material_controller/show_material_by_employee",
						data: {employee_id: $('#user-id').text()},
						beforeSend:function(){},
		   				success: function(data, textStatus, xhr) {
							$('#left-div-user-result').html(data);
		   				}//end of success
					});//end of post request
	});
	$('.balance_machine2').live('click',function(){
		var myID=$(this).attr('id');
		var request=$.ajax({
		  				type:'get',
		   				url: base_url+"/job_controller/get_balance_from_machine_ajax",
						data: {},
						beforeSend:function(){},
		   				success: function(data, textStatus, xhr) {
							useReturnData(data);
							function useReturnData(data){
							    var response=$(data);
								var msg=response.filter('#msg').text();
								var err=response.filter('#error').text();
								var report=response.filter('#report').text();
								if(err!='error'){
								/*	$.msgBox({
									    title: "Balance Machine",
									    content: msg,
									    type: "info",
									    showButtons: true,
									    opacity: 0.9,
										timeOut: 1000,
									    autoClose:true
									});*/
									
									$("#"+myID).val(response.filter('#report').text());
								}else{
									$.msgBox({
									    title: "Balance Machine",
									    content: msg,
									    type: "info",
									    showButtons: true,
									    opacity: 0.5,
									    autoClose:false
									});
								}
							};//end of usereturndata
		   				}//end of success
					});//end of post request
	});
	$('#show-jobs').trigger('click');
	$('#karigar-balance').trigger('click');
});