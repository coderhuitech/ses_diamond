$(function(){
	$("#left-div").on('click','#gold-receipt-from-customer',function(){
	
		var request=$.ajax({
			type:'get',
			url: base_url+"/new_transaction_controller/gold_receipt_facade",
			data: {
				   },//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#data-div').html(data);
			}//end of success
		});//end of get request
	});//end
	
	$("#data-div").on('change','#gold-receipt-div #agent-id',function(){
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
	
	$("#data-div").on('change','#gold-receipt-div #customer-id',function(){
		var request=$.ajax({
			type:'get',
			url: base_url+"/new_transaction_controller/get_customer_gold_due",
			data: {
					customer_id: $('#customer-id').val()
				   },//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#due-div').html(data);
			}//end of success
		});//end of get request
	});
	$("#data-div").on('keyup change','#gold-received',function(){
						var goldReceived=parseFloat("0"+$('#gold-received').val());
						if(goldReceived>0){
							$('#gold-received').removeClass('required');
						}else{
							$('#gold-received').addClass('required');
						}
						$('#next-gold-due').val('0'+parseFloat($('#gold-due').val())-parseFloat('0'+$('#gold-received').val()));
						$('#next-gold-due').val($('#next-gold-due').toFixed(3));
	});
	$('#data-div').on('click','#submit-gold-received',function(){
			var no_of_required=0;
			var gold=parseFloat("0"+$('#gold-received').val());
			if(gold<=0){
				$('#gold-received').addClass('required');
				no_of_required++;
			}
			
			if(no_of_required>0){
				$.msgBox({
					title: "Gold Receipt",
					content: "Received Gold is required",
					type: "info",
					showButtons: true,
					opacity: 0.9,
					timeOut: 1000,
					autoClose:false
				});
				return;
			}
			
			$.msgBox({
	    		title: "Receipt Confirmation",
				content: "Confirm?",
				type: "confirm",
	    		buttons: [{ value: "Yes" }, { value: "No" }],
				  		success: function (result) {
				       		if (result == "Yes") {
								var request=$.ajax({
									type:'get',
									url: base_url+"/new_transaction_controller/save_gold_receipt",
									data:  {cust_id: $('#customer-id').val()
											,agent_id: $('#agent-id').val()
											,gold_due: $('#gold-due').val()
											,receive_gold: $('#gold-received').val()
											,next_gold_due: $('#next-gold-due').val()
											,last_lc_due: $('#lc-due').val()
											},
									beforeSend:function(){},
			   						success: function(data, textStatus, xhr) {
										var obj = $.parseJSON(data)[0];
										$.msgBox({
											title: "Gold Receipt Insertion",
											content: obj['msg'],
											type: obj['message_type'],
											showButtons: true,
											opacity: 0.5,
											autoClose:false
										});
										
										$('#data-div').html(obj['receipt_no']);
									}//end of success
								});//end get 
									return;
				        	}//end of yes
							
							if (result == "No") {
				            		return;
				       		}
    				}
			});//end of msgbox
	});
	
});