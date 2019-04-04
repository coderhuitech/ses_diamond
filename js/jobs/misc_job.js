$(function(){
	$('#left-div').on('click','#edit-product',function(){
		var request=$.ajax({
			type:'get',
			url: base_url+"/job_controller/edit_product_view",
			data: {},//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#result-div').html(data);
			}//end of success
		});//end of post request
	});//end
	
	$('#right-div').on('change','#job-id',function(){
		var request=$.ajax({
			type:'get',
			url: base_url+"/job_controller/get_job_details_by_id",
			data: {job_id: $('#job-id').val()},//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				var obj = $.parseJSON(data)[0];
				$('#jobt-id').val(obj['job_id']);
				$('#product-id').val(obj['product_code']);
				$('#price-id').val(obj['price_code']);
				//$('#result-div').html(data);
			}//end of success
		});//end of post request
	});//end
});