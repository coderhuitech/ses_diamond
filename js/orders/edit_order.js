$(function(){
		
		$('a[title*="PDF"]').css({'background':'red'});
		
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
	
	$("input:text").focusin(function(){
 		 $(this).css("background-color","#FFFFCC");
	});
	$("input:text").focusout(function(){
 		 $(this).css("background-color","#FFFFFF");
	});
	$("input:text:visible:first").focus();
	
	$('#bill-no').live('keypress',function(e){
			var code = e.keyCode || e.which;
				if(code!=9)
					return;
		var request=$.ajax({
		  				type:'get',
		   				url: base_url+"/order_controller/get_orders_by_pattern",
						data: {bill_no: $('#bill-no').val()
							   ,order_date_from: $('#bill-from').val()
							   ,order_date_to: $('#bill-to').val()},
						beforeSend:function(){},
		   				success: function(data, textStatus, xhr) {
							//var obj= $.parseJSON(data);
							//console.log(obj);
							//$('#outer-div').html(data);
							//return;
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
									$('#order-no-div').html(response.filter('#report').html());
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
	
	$('.edit').live('click',function(){
		var id=$(this).closest('tr').attr('id');
		$('#modelEditable-'+id).show();
		$('#modelDisplay-'+id).hide();
		
		$('#sizeEditable-'+id).show();
		$('#sizeDisplay-'+id).hide();
		
		$('#qtyEditable-'+id).show();
		$('#qtyDisplay-'+id).hide();
		
		$('#goldEditable-'+id).show();
		$('#goldDisplay-'+id).hide();
		$('#cancel-'+id).show();
		$(this).hide();
	});
	$('.cancel').live('click',function(){
		var id=$(this).closest('tr').attr('id');
		
		$('#modelEditable-'+id).val($('#modelDisplay-'+id).text());
		$('#modelEditable-'+id).hide();
		$('#modelDisplay-'+id).show();
		
		$('#sizeEditable-'+id).val($('#sizeDisplay-'+id).text());
		$('#sizeEditable-'+id).hide();
		$('#sizeDisplay-'+id).show();
		
		$('#qtyEditable-'+id).val($('#qtyDisplay-'+id).text());
		$('#qtyEditable-'+id).hide();
		$('#qtyDisplay-'+id).show();
		
		$('#goldEditable-'+id).val(getId($('#goldDisplay-'+id).text()));
		$('#goldEditable-'+id).hide();
		$('#goldDisplay-'+id).show();
		
		$('#edit-'+id).show();
		$('#save-'+id).hide();
		$(this).hide();
	});
	function getId(str){
    	return str.replace(/[^\d.]+/,'');
	}
	
	$('.save').live('click',function(){
		var id=$(this).closest('tr').attr('id');
		$.msgBox({
    			title: "Update Order",
    			content: "Confirm to change?",
    			type: "confirm",
    			buttons: [{ value: "Yes" }, { value: "No" }, { value: "Cancel"}],
    			success: function (result) {
        			if (result == "Yes") {
						var request=$.ajax({
		  						type:'post',
		   						url: base_url+"/order_controller/update_order_by_order_details_no",
								data:  {
										product_code: $('#modelEditable-'+id).val()
										,prd_size: $('#sizeEditable-'+id).val()
										,qty: $('#qtyEditable-'+id).val()
										,gold_wt: $('#goldEditable-'+id).val()	
										,order_no: id
										,order_id: $('#order-id').text()
								},
								//data: data,
		   						//dataType:'text',
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
									    title: "Order saving",
									    content: msg,
									    type: "info",
									    showButtons: true,
									    opacity: 0.9,
										timeOut: 1000,
									    autoClose:true
									});
									$('#order-details-div').html(response.filter('#report').html());
									
									
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
	$('tr').live('change',function(){
		var id=$(this).closest('tr').attr('id');
		$('#edit-'+id).hide();
		$('#save-'+id).show();
	});
});