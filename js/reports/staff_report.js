$(function(){
	$('#staff-cash-in-hand-div').live('click',function(){
		var request=$.ajax({
			type:'get',
			url: base_url+"/report_controller/get_staff_cash_balance",
			data: {},//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#result-div').html(data);
			}//end of success
		});//end of post request
	});
	$('#staff-material-in-hand-div').live('click',function(){
		var request=$.ajax({
			type:'get',
			url: base_url+"/material_controller/create_rm_master_pivot_table",
			data: {},//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#result-div').html(data);
			}//end of success
		});//end of post request
	});
	$('#staff-lc-receipt-daily-div').live('click',function(){
		var request=$.ajax({
			type:'get',
			url: base_url+"/report_controller/get_daily_lc_receipt",
			data: {
				date_from: $('#date-from').val()
				   ,date_to: $('#date-to').val()
			},//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#result-div').html(data);
			}//end of success
		});//end of post request
	});
	$('#get-gold-receipt-report').live('click',function(){
		var request=$.ajax({
			type:'get',
			url: base_url+"/report_controller/get_gold_receipt_report",
			data: {date_from: $('#date-from').val()
				   ,date_to: $('#date-to').val()
				   },//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#result-div').html(data);
			}//end of success
		});//end of post request
	});
	$('.gold-receipt-id').live('click',function(){
		var request=$.ajax({
		  		type:'get',
		   		url: base_url+"/gold_inward_controller/display_gold_inward_receipt_by_id",
				data:  {gold_receipt_no: $(this).text()},
				beforeSend:function(){},
		   		success: function(data, textStatus, xhr) {
						//$('#agent-validation').html(data);
				useReturnData(data);
						function useReturnData(data){
					   			$('#result-div').html(data);
						}
		   		}//end of success
								
		});//end get 
	});
	$("#printDiv").live('click',function(){
		$("#result-div").print();
		return (false);
	});
		$('#material-withdrawn').live('click',function(){
		var request=$.ajax({
			type:'get',
			url: base_url+"/report_controller/material_withdrawn_by_date",
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
	$('#right-div').on('click','#show-materials',function(){
		alert("asdfasdf");
		var request=$.ajax({
			type:'get',
			url: base_url+"/report_controller/material_withdrawn_by_date",
			data: {date_from: $('#date-from').val()
				,date_to: $('#date-to').val()
				,material_id: $('#material-id').val()
			},//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#result-div').html(data);
			}//end of success
		});//end of post request
	});
});