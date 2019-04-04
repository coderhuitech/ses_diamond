$(function(){
	$('#outer-div').on('click','.printer',function(){
		$('#'+$(this).parent().attr('id')).print();
		return (false);
	});
	$(".date").datepicker({ dateFormat: 'dd/mm/yy' });
	
	$('#left-div').on('click','#product-information',function(){
		var request=$.ajax({
			type:'get',
			url: base_url+"/report_controller/product_details_view",
			data: {date_from: $('#date-from').val()
				   ,date_to: $('#date-to').val()
				   },//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#right-working-area').html(data);
			}//end of success
		});//end of post request
	});
	$('#right-div').on('click','#search',function(){
		var request=$.ajax({
			type:'get',
			url: base_url+"/report_controller/get_product_details",
			data: {date_from: $('#date-from').val()
				   ,date_to: $('#date-to').val()
				   ,tag: $('#tag').val()
				   },//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#result-div').html(data);
			}//end of success
		});//end of post request
	});
});