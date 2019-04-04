$(function(){
	$('#show-jobs').live('click',function(){
		var request=$.ajax({
		  				type:'get',
		   				url: base_url+"/job_controller/show_jobs_in_phase_pan_ajax",
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
									/*$.msgBox({
									    title: "Order Item Selection",
									    content: msg,
									    type: "info",
									    showButtons: true,
									    opacity: 0.9,
										timeOut: 1000,
									    autoClose:true
									});*/
									$('#jobs-div').html(response.filter('#report').html());
								}else{
									$('#jobs-div').html(response.filter('#report').html());
									$.msgBox({
									    title: "Job Selection error",
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
	$('.job-phseII-div').live('click',function(){
		var job_id = $(this).find('.job-id').text();
		$('.job-phseII-div').removeClass('selected');
		$(this).addClass('selected');
		var request=$.ajax({
		  				type:'get',
		   				url: base_url+"/job_controller/select_job_by_job_id_for_pan_ajax",
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
		   				url: base_url+"/job_controller/select_job_by_job_id_for_pan_ajax",
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
									    title: "JOB Selection error",
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
	$('#pan-send').live('click',function(){
		$(this).removeClass('required');
	});
	$('#pan-id').live('click',function(){
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
		var pan=parseFloat($('#pan-send').val());
		var panID=parseFloat($('#pan-id').val());
		if(panID==0){
			$('#pan-id').addClass('required');
			no_of_required++;
		}
		if(pan<=0){
			$('#pan-send').addClass('required');
			no_of_required++;
		}
		if(no_of_required>0){
			$.msgBox({
				title: "PHASE II Submit Error",
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
		   				url: base_url+"/job_controller/save_phase_pan_ajax",
						data: {job_id: $('#job-id').text()
							   ,pan: pan
							   ,pan_id: $('#pan-id').val()
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
		});
		
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
	$('.balance_machine').live('click',function(){
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
});