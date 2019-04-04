$(function(){
	$('#left-div').on("click","#menu-delete-order",function(){
		
		var request=$.ajax({
			type: 'get',
			url: base_url+"/order_controller/delete_order_view",
			success: function(data,textStatus,xhr){
						 $('#right-div').html(data);
					 }
		});
	});
	
	
	
	
	$("#right-div").on("focus","#order-no",function(e) {
    	if ( !$(this).data("autocomplete") ) { // If the autocomplete wasn't called yet:
        	$(this).autocomplete({             //   call it
	            //auto complete with extra parameters
				source: function(request, response) {
		            $.ajax({
		                url: base_url+"/order_controller/get_deleteable_orders",
		                dataType: "json",
		                data: {
		                    term : request.term
		                },
		                
		                success: function(data) {
		                    response(data);
		                }
		            });
	        	},
				minLength: 2,
				focus: function(event, ui) {
	            	$("#order-no").val(ui.item.label); //product field will show the product list only
	            	return false;
	        	},
	    		select: function (event, ui) {
					$('#order-no').text(ui.item.label);
					return false;
				}
        	});
    	}//end of if
	});//end of focus function
	
	$("#right-div").on("click","#check-status",function(e) {
		var request=$.ajax({
				type:'get',
				url: base_url+"/order_controller/show_deleteable_detailed_orders",
				data: { order_no: $('#order-no').val()},//end of data
				beforeSend:function(){},
				success: function(data, textStatus, xhr) {
					$('#result-div').html(data);
				}//end of success
		});//end of get request
	});
	$('#left-div').on("click","#show-deleteable-order",function(){
		var request=$.ajax({
			type: 'get',
			url: base_url+"/order_controller/show_deleteable_orders_view",
			success: function(data,textStatus,xhr){
						 $('#right-div').html(data);
					 }
		});
	});
	
	
	
	$("#right-div").on("focus","#order-no",function(e) {
    	if ( !$(this).data("autocomplete") ) { // If the autocomplete wasn't called yet:
        	$(this).autocomplete({             //   call it
	            //auto complete with extra parameters
				source: function(request, response) {
		            $.ajax({
		                url: base_url+"/order_controller/get_deleteable_orders",
		                dataType: "json",
		                data: {
		                    term : request.term
		                },
		                
		                success: function(data) {
		                    response(data);
		                }
		            });
	        	},
				minLength: 2,
				focus: function(event, ui) {
	            	$("#order-no").val(ui.item.label); //product field will show the product list only
	            	return false;
	        	},
	    		select: function (event, ui) {
					$('#order-no').text(ui.item.label);
					return false;
				}
        	});
    	}//end of if
	});//end of focus function
	
	$("#right-div").on("click","#check-status",function(e) {
		var request=$.ajax({
				type:'get',
				url: base_url+"/order_controller/show_deleteable_detailed_orders",
				data: { order_no: $('#order-no').val()},//end of data
				beforeSend:function(){},
				success: function(data, textStatus, xhr) {
					$('#result-div').html(data);
				}//end of success
		});//end of get request
	});
	
	$("#content").on("click",".order-row",function() {
		var order_no=$(this).attr('id');
		var request=$.ajax({
				type:'get',
				url: base_url+"/order_controller/show_order_details_by_order_id",
				data: { order_no: order_no},//end of data
				beforeSend:function(){},
				success: function(data, textStatus, xhr) {
					$('#message-panel').html(data);
				}//end of success
		});//end of get request
		
	});
	$("#content").on("click",".cancel-order",function() {
		var order_no=$(this).attr('order-id');
			$.msgBox({
			   	title: "Cancel Order",
			   	content: "Confirm to Cancel order: "+order_no,
			   	type: "confirm",
			   	buttons: [{ value: "Yes" }, { value: "No" }],
			   	success: function (result) {
			       	if (result == "Yes") {
						//your code here for yes
						var request=$.ajax({
								type:'get',
								url: base_url+"/order_controller/cancel_order",
								data: { order_no: order_no},//end of data
								beforeSend:function(){},
								success: function(data, textStatus, xhr) {
									$('#message-panel').html(data);
									$('#show-deleteable-order').trigger('click');
								}//end of success
						});//end of get request
						//$('#'+order_no).addClass('deleted');
						return;
			       	}//end of yes
					if (result == "No") {
			       		return;
			       	}//end of no
			  	}//end of success
			});
	});
	$('#content').on('click','#show-orders',function(){
		var request=$.ajax({
			type: 'get',
			url: base_url+"/order_controller/show_orders_view",
			success: function(data,textStatus,xhr){
						 $('#right-div').html(data);
					 }
		});
	});
	$("#right-div").on("click","#show-orders",function(e) {
		var request=$.ajax({
				type:'get',
				url: base_url+"/order_controller/show_order_by_delivery_date",
				data: {date_from: $('#date-from').val()
						,date_upto: $('#date-to').val()
						},//end of data
				beforeSend:function(){},
				success: function(data, textStatus, xhr) {
					$('#result-div').html(data);
				}//end of success
		});//end of get request
	});
	$("#right-div").on('click','#table-by-delivery-date tr',function(){
		var order_no=$(this).attr('id');
		var request=$.ajax({
				type:'get',
				url: base_url+"/order_controller/show_order_details_by_order",
				data: {date_from: $('#date-from').val()
						,date_upto: $('#date-to').val()
						,order_no: order_no
						},//end of data
				beforeSend:function(){},
				success: function(data, textStatus, xhr) {
					$('#result2-div').html(data);
				}//end of success
		});//end of get request
	});
});