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
		$('#customer-id').live('blur',function(){
			var request=$.ajax({
		  				type:'get',
		   				url: base_url+"/report_controller/get_customer_balance_by_cust_id",
						data: {cust_id : $('#customer-id').val()
							   },
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
									$('#customer-balance').html(response.filter('#report').html());
									
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
		$('#received-id').live('blur',function(){
			$('#lc-remaining').text('Remaining : '+(parseInt($('#lc-due').text())-$('#received-id').val()));
		});
		$('#receipt-mode').live('change',function(){
			if($(this).val()==2){
				$('#bank-details').removeClass('hidden');
			}else{
				$('#bank-details').addClass('hidden');
			}
		});
		$('#submit').live('click',function(){
			var no_of_required=0;
			var received_amount=parseInt("0"+$('#received-id').val());
			var customerID=$('#customer-id').val();
			if(received_amount<=0){
				$('#received-id').addClass('required');
				no_of_required++;
			}
			if(customerID==""){
				$('#received-id').addClass('required');
				no_of_required++;
			}
			if(no_of_required>0){
				$.msgBox({
					title: "LC Receipt",
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
				   	title: "Save Transaction",
				   	content: "Confirm to save?",
				   	type: "confirm",
				   	buttons: [{ value: "Yes" }, { value: "No" }],
				   	success: function (result) {
				       	if (result == "Yes") {
							//your code here for yes
							var request=$.ajax({
								type:'get',
								url: base_url+"/cash_inward_controller/save_lc_received_from_customer",
								data: {cust_id: $('#customer-id').val()
									   ,receipt_mode: $('#receipt-mode').val()
									   ,cheque_details : $('#bank-details').val()
									   ,received_amount: $('#received-id').val()},//end of data
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
												    title: "LC Received",
												    content: msg,
												    type: "info",
												    showButtons: true,
												    opacity: 0.9,
													timeOut: 1000,
												    autoClose:true
												});
												$('#report-div').html(response.filter('#report').html());
											}else{
												$('#report-div').html(response.filter('#report').html());
												$.msgBox({
												    title: "LC Received Error",
												    content: msg,
												    type: "info",
												    showButtons: true,
												    opacity: 0.5,
												    autoClose:false
												});//end of message box
											}//end of if
										};//end of usereturndata
								}//end of success
							});//end of post request
							return;
				       	}//end of yes
						if (result == "No") {
				       		return;
				       	}//end of no
				  	}//end of success
			});//end of message confirmation
		});//end of Submit
		
	$("#left-div").on('click','#lc-receipt-from-customer',function(){
	
		var request=$.ajax({
			type:'get',
			url: base_url+"/new_transaction_controller/lc_receipt_facade",
			data: {
				   },//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#data-div').html(data);
			}//end of success
		});//end of get request
	});
	$("#data-div").on('change','#lc-receipt-div #agent-id',function(){
		var request=$.ajax({
			type:'get',
			url: base_url+"/new_transaction_controller/get_customers_by_agent_id",
			data: {
					agent_id: $('#agent-id').val()
				   },//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#customer-div').html(data);
			}//end of success
		});//end of get request
	});
	
	$("#data-div").on('change','#lc-receipt-div #customer-id',function(){
		var request=$.ajax({
			type:'get',
			url: base_url+"/new_transaction_controller/get_customer_lc_due_details_by_cust_id",
			data: {
					customer_id: $('#customer-id').val()
				   },//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#due-div').html(data);
			}//end of success
		});//end of get request
	});
	
	$("#data-div").on('keyup','#lc-received',function(){
		var lcReceived=parseFloat("0"+$('#lc-received').val());
		if(lcReceived>0){
			$('#lc-received').removeClass('required');
		}else{
			$('#lc-received').addClass('required');
		}
		$('#due-after').val('0'+parseInt($('#lc-due').val())-parseInt('0'+$('#lc-received').val()));
	});
	$("#data-div").on('click','#lc-submit',function(){
		var no_of_required=0;
		var lcReceived=parseFloat("0"+$('#lc-received').val());
		var customerID=$('#customer-id').val();
		var agentID=$('#agent-id').val();
		if(lcReceived<=0){
			$('#lc-received').addClass('required');
			no_of_required++;
		}
		if(customerID=="--"){
			$('#customer-id').addClass('required');
			no_of_required++;
		}
		if(no_of_required>0){
			$.msgBox({
				title: "LC Payment",
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
    		title: "LC Receipt Confirmation",
    		content: "Confirm?",
    		type: "confirm",
    		buttons: [{ value: "Yes" }, { value: "No" }],
    		success: function (result) {
        		if (result == "Yes") {
					var request=$.ajax({
		  						type:'get',
		   						url: base_url+"/new_transaction_controller/save_lc_received",
								data:  {cust_id: $('#customer-id').val()
										,agent_id: $('#agent-id').val()
										,lc_received: $('#lc-received').val()
										},
								beforeSend:function(){},
		   						success: function(data, textStatus, xhr) {
									var obj = $.parseJSON(data)[0];
									$.msgBox({
										title: "LC Receipt",
										content: obj['msg'],
										type: obj['message_type'],
										showButtons: true,
										opacity: 0.5,
										autoClose:false
									});
									$('#data-div').html(obj['transaction_no']);
		   						}//end of success
					});//end get 
					return;
        		}//end of yes
			
				if (result == "No") {
            		return;
        		}
    		}
		});
	});
	
});