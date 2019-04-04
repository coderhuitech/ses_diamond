$(function(){
	$("#hrefPrint").live('click',function(){
		$("#result-div").print();
		return (false);
	});
	$('#customer-dues').live('click',function(){
		var request=$.ajax({
			type:'get',
			url: base_url+"/report_controller/get_customer_balance",
			data: {row_number: $('#row-number').val()
					,agent_id: $('#agent-id').val()	
					},//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#result-div').html(data);
			}//end of success
		});//end of post request
	});
	$('.page').live('click',function(event){
		event.preventDefault();
		
		//var x=parseInt($(this).attr("href").match(/[\d]+$/));
		var pageVal=$(this).attr('pageVal');
		var request=$.ajax({
		type:'get',
			url: base_url+"/report_controller/get_customer_balance_with_pagination",
			data: {pageVal: pageVal},//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#customer-table-div').html(data);
			}//end of success
		});//end of post request
	});//end of live
	$('#pagination-div .page').live('click',function(){
		$('#pagination-div .selected').removeClass('selected');
		$(this).addClass('selected');
	});
	
	$('#customer-payment-details').live('click',function(){
		var request=$.ajax({
			type:'get',
			url: base_url+"/report_controller/display_individual_customer_payment_details",
			data: {cust_id: $('#cust-id').val()},//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#result-div').html(data);
			}//end of success
		});//end of post request
	});
	$('#outer-div').on('click','#customer tr', function() {
		var cust_id = $(this).attr( "id" );
		var request=$.ajax({
			type:'get',
			url: base_url+"/report_controller/display_individual_customer_payment_details",
			data: {cust_id: cust_id},//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#result-div').html(data);
			}//end of success
		});//end of post request
	});
	$('#outer-div').on('click','#customer-inward-outward-report', function() {
		var request=$.ajax({
			type:'get',
			url: base_url+"/report_controller/get_customer_inward_and_outward_report",
			data: {date_from: $('#date-from').val()
				   ,date_to: $('#date-to').val()
				   ,agent_id: $('#agent-id').val()
				   },//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#result-div').html(data);
			}//end of success
		});//end of post request
	});
	
	$(".date").datepicker({ dateFormat: 'dd/mm/yy' });
});