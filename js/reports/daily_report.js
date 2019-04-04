$(function(){
	/*
	
	 $("#hrefPrint").click(function() {
// Print the DIV.
$("#printdiv").print();
return (false);
});
	*/
	$("#hrefPrint").live('click',function(){
		$("#result-div").print();
		return (false);
	});
	$('#daily-inventory').live('click',function(){
		var request=$.ajax({
			type:'get',
			url: base_url+"/report_controller/get_daily_inventory_report",
			data: {date_from: $('#date-from').val()
				   ,date_to: $('#date-to').val()
				   },//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#result-div').html(data);
			}//end of success
		});//end of post request
	});
	$('#daily-mathakata').live('click',function(){
		var request=$.ajax({
			type:'get',
			url: base_url+"/report_controller/get_daily_mathakata_report",
			data: {date_from: $('#date-from').val()
				   ,date_to: $('#date-to').val()
				   },//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#result-div').html(data);
			}//end of success
		});//end of post request
	});
	/*$('#daily-bill').live('click',function(){
		
	});*/
	
	$('#outer-div').on('click','#daily-bill',function(event){
		event.preventDefault();
		var request=$.ajax({
			type:'get',
			url: base_url+"/report_controller/get_daily_bill_report",
			data: {date_from: $('#date-from').val()
				   ,date_to: $('#date-to').val()
				   ,bill_type: 0
				   },//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#result-div').html(data);
			}//end of success
		});//end of post request
	});
	$('#outer-div').on('change','#bill-type-select',function(event){
		event.preventDefault();
		
		var request=$.ajax({
			type:'get',
			url: base_url+"/report_controller/get_daily_bill_report",
			data: {date_from: $('#date-from').val()
				   ,date_to: $('#date-to').val()
				   ,bill_type: $('#bill-type-select').val()
				   },//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#result-div').html(data);
			}//end of success
		});//end of post request
	});
	
	$('#job-report').live('click',function(){
		var request=$.ajax({
			type:'get',
			url: base_url+"/report_controller/get_daily_job_report",
			data: {date_from: $('#date-from').val()
				   ,date_to: $('#date-to').val()
				   },//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#result-div').html(data);
			}//end of success
		});//end of post request
	});
	$('#staff-gold-receipt-daily-div').live('click',function(){
		var request=$.ajax({
			type:'get',
			url: base_url+"/report_controller/get_daily_order_report",
			data: {date_from: $('#date-from').val()
				   ,date_to: $('#date-to').val()
				   },//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#result-div').html(data);
			}//end of success
		});//end of post request
	});
	$('#fine-to-gini').live('click',function(){
		var request=$.ajax({
			type:'get',
			url: base_url+"/report_controller/fine_to_gini_report",
			data: {date_from: $('#date-from').val()
				   ,date_to: $('#date-to').val()
				   },//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#result-div').html(data);
			}//end of success
		});//end of post request
	});
	$('#business-report').live('click',function(){
		var request=$.ajax({
			type:'get',
			url: base_url+"/report_controller/get_business_status_report",
			data: {date_from: $('#date-from').val()
				   ,date_to: $('#date-to').val()
				   },//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#result-div').html(data);
			}//end of success
		});//end of post request
	});
	$('#save-business-report').live('click',function(){
		var request=$.ajax({
			type:'get',
			url: base_url+"/report_controller/save_business_status_report",
			data: {},//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
						var obj = $.parseJSON(data)[0];
							$.msgBox({
							title: "Customer Updated",
							content: obj['msg'],
							type: obj['message_type'],
							showButtons: true,
							opacity: 0.5,
							autoClose:false
						});
							$('#result-div').html(obj['msg']);
			}//end of success
		});//end of post request
	});
	$('#left-div').on('click','#agent-wise-due-report',function(){
		var request=$.ajax({
			type:'get',
			url: base_url+"/report_controller/get_agentwise_dues",
			data: {date_from: $('#date-from').val()
				   ,date_to: $('#date-to').val()
				   },//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#result-div').html(data);
			}//end of success
		});//end of post request
	});
	
	$(".date").datepicker({ dateFormat: 'dd/mm/yy' });
});