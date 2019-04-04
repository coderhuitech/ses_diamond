$(function(){
		$( "#customer-id" ).autocomplete({
			//auto complete with extra parameters
			source: function(request, response) {
	            $.ajax({
	                url: base_url+"/order_controller/get_all_bill_inforced_customers",
	                dataType: "json",
	                data: {
	                    term : request.term,
	                    //agent_id : $("#agent_id").val()
	                },
	                success: function(data) {
	                    response(data);
	                }
	            });
        	},
			 minLength: 2,
			focus: function(event, ui) {
            	$("#customer-id").val(ui.item.label); //product field will show the product list only
            	return false;
        	},
    		select: function (event, ui) {
				$('#customer-name').text(ui.item.label);
				$("#customer-id").val(ui.item.value); // box number will show the value
				return false;
			}
		});
		//model no
		$( "#model-no" ).live('click',function(){
			$( "#model-no" ).autocomplete({
			//auto complete with extra parameters
			source: function(request, response) {
	            $.ajax({
	                url: base_url+"/order_controller/get_all_models",
	                dataType: "json",
	                data: {
	                    term : request.term,
	                    //agent_id : $("#agent_id").val()
	                },
	                success: function(data) {
	                    response(data);
	                }
	            });
        	},
			 minLength: 2,
			focus: function(event, ui) {
            	$("#model-no").val(ui.item.label); //product field will show the product list only
            	return false;
        	},
    		select: function (event, ui) {
        		$("#model-no").val(ui.item.label); //product will show the productID
				$("#model-no").val(ui.item.value); // box number will show the value
				$("#description").val(ui.item.label2);
				return false;
			}
		});
		});
		$('#description').live('keypress',function(e){
			var code = e.keyCode || e.which;
				if(code!=9)
					return;
			var request=$.ajax({
		  				type:'post',
		   				url: base_url+"/order_controller/add_item_to_temp_order",
				/*		data: {model_no:$('#model-no').val()
							   ,model_size: $('#model-size').val()
							   ,appx_gold: $('#appx-gold').val()
							   ,qty: $('#qty').val()
							   ,description: $('#description').val()
							   },*/
						data: $('#new-order-form').serialize(),
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
									$('#result-div').html(response.filter('#report').html());
									$('#model-no').focus();
									$('#model-size').val('');
									$('#appx-gold').val('');
									$('#gold').val('');
									$('#model-no').val('');
									$('#qty').val('');
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
		$('.remove').live('click',function(){
			var temp_order_id=$(this).attr("id");
			var user_key=$('#user_key').val();
			$.msgBox({
    			title: "Delete Current Order",
    			content: "Sure to delete?",
    			type: "confirm",
    			buttons: [{ value: "Yes" }, { value: "No" }, { value: "Cancel"}],
    			success: function (result) {
        			if (result == "Yes") {
						var request=$.ajax({
		  						type:'get',
		   						url: base_url+"/order_controller/delete_record_by_temp_order_id",
								data:  {temp_order_id: temp_order_id,user_key: user_key},
								//data: data,
		   						//dataType:'text',
								beforeSend:function(){},
		   						success: function(data, textStatus, xhr) {
									useReturnData(data);
									function useReturnData(data){
							    var response=$(data);
								var msg=response.filter('#msg').text();
								var err=response.filter('#error').text();
								var report=response.filter('#report').text();
								if(err!='error'){
									$.msgBox({
									    title: "Item Deletion Error",
									    content: msg,
									    type: "info",
									    showButtons: true,
									    opacity: 0.9,
										timeOut: 1000,
									    autoClose:true
									});
									$('#result-div').html(response.filter('#report').html());
								}else{
									$.msgBox({
									    title: "Iten Deletion error",
									    content: msg,
									    type: "info",
									    showButtons: true,
									    opacity: 0.5,
									    autoClose:false
									});
								}
							};//end of usereturndata
		   						}//end of success
					});//end get 
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
	$('#save-order').live('keypress',function(e){
		if (e.keyCode == 13) {               
    		e.preventDefault();
    		return false;
  		}
	});
	$('#save-order').live('click',function(){
		$.msgBox({
    			title: "Save Current Order",
    			content: "Confirm to save?",
    			type: "confirm",
    			buttons: [{ value: "Yes" }, { value: "No" }, { value: "Cancel"}],
    			success: function (result) {
        			if (result == "Yes") {
						var request=$.ajax({
		  						type:'post',
		   						url: base_url+"/order_controller/save_order",
								data:  $('#new-order-form').serialize(),
								//data: data,
		   						//dataType:'text',
								beforeSend:function(){},
		   						success: function(data, textStatus, xhr) {
									useReturnData(data);
									function useReturnData(data){
							    var response=$(data);
								var msg=response.filter('#msg').text();
								var err=response.filter('#error').text();
								var report=response.filter('#report').text();
								if(err!='error'){
									$.msgBox({
									    title: "Order saving",
									    content: msg,
									    type: "info",
									    showButtons: true,
									    opacity: 0.9,
										timeOut: 1000,
									    autoClose:true
									});
									$('#final-result').html(response.filter('#report').html());
								}else{
									$.msgBox({
									    title: "Order Save error",
									    content: msg,
									    type: "info",
									    showButtons: true,
									    opacity: 0.5,
									    autoClose:false
									});
								}
							};//end of usereturndata
		   						}//end of success
					});//end post 
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
	$("input:text").focusin(function(){
 		 $(this).css("background-color","#FFFFCC");
	});
	$("input:text").focusout(function(){
 		 $(this).css("background-color","#FFFFFF");
	});
	$("input:text:visible:first").focus();
	$('.edit').live('click',function(){
		var temp_order_id=$(this).attr("id");
		var request=$.ajax({
		  				type:'get',
		   				url: base_url+"/order_controller/get_item_from_temp_order",
						data: {temp_order_id: temp_order_id},
						beforeSend:function(){},
		   				success: function(data, textStatus, xhr) {
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
									$('#order_input_div').html(response.filter('#report').html());
								}else{
									$.msgBox({
									    title: "Selection error",
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
	$('#outer-div').on('change','#new-agent-id',function(){
		var request=$.ajax({
			type:'get',
			url: base_url+"/order_controller/get_customer_by_agent_id",
			data: {agent_id: $('#new-agent-id').val()
				   },//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#new-customer-div').html(data);
			}//end of success
		});//end of post request
	}); 
	$('#outer-div').on('change','#new-cust-id',function(){
		var request=$.ajax({
			type:'get',
			url: base_url+"/order_controller/get_customer_balance_by_id",
			data: {cust_id: $('#new-cust-id').val()
				   },//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#customer-dues-div').html(data);
			}//end of success
		});//end of post request
	});                    
});