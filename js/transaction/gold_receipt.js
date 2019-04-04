$(function(){
	
	$('#customer-div').on('change','#customer-id',function(){
		var request=$.ajax({
				type:'get',
				url: base_url+"/gold_inward_controller/get_customer_balance_by_id",
				data:  {cust_id: $('#customer-id').val()},
				beforeSend:function(){},
				success: function(data, textStatus, xhr) {
						$('#cust-balance').html(data);
				}//end of success
		});//end get 
	});
	$('#submit').live('click',function(){
		var no_of_required=0;
		var gold=parseFloat("0"+$('#gold').val());
		var customerID=$('#customer-id').val();
		var fine_gold_in_cash=$('#fine-gold-in-cash').val();
		if(gold<=0 && fine_gold_in_cash<=0){
			$('#gold').addClass('required');
			no_of_required++;
		}
		if(customerID=="--"){
			$('#customer-id').addClass('required');
			no_of_required++;
		}
		if(no_of_required>0){
			$.msgBox({
				title: "Gold Receipt",
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
    		title: "Receipt Confirmation",
    		content: "Confirm?",
    		type: "confirm",
    		buttons: [{ value: "Yes" }, { value: "No" }],
    		success: function (result) {
        		if (result == "Yes") {
					var request=$.ajax({
		  						type:'get',
		   						url: base_url+"/gold_inward_controller/save_gold_receipt",
								data:  {cust_id: $('#customer-id').val()
										,agent_id: $('#agent-id').val()
										,gold: $('#gold').val()
										,fine_gold: $('#fine-gold').text()
										,rm_id: $('#gold-id').val()
										,last_gold_due: $('#last-gold-due').text()
										,last_lc_due: $('#last-lc-due').text()
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
									
									//$('#outer-div').html(obj['receipt_no']);
		   						}//end of success
					});//end get 
					return;
        		}//end of yes
			
				if (result == "No") {
            		return;
        		}
    		}
		});
	});//end of submit
	$('#gold-id').live('change',function(){
		var request=$.ajax({
		  						type:'get',
		   						url: base_url+"/gold_inward_controller/get_gold_percent_by_id",
								data:  {gold_id: $('#gold-id').val(), gold: $('#gold').val()},
								beforeSend:function(){},
		   						success: function(data, textStatus, xhr) {
									$('#gold-text').html(data);
									
		   						}//end of success
					});//end get
	});
	$('#gold').live('change',function(){
		/*var request=$.ajax({
		  						type:'get',
		   						url: base_url+"/gold_inward_controller/get_gold_percent_by_id",
								data:  {gold_id: $('#gold-id').val(), gold: $('#gold').val()},
								beforeSend:function(){},
		   						success: function(data, textStatus, xhr) {
									$('#gold-text').html(data);
									
		   						}//end of success
					});//end get*/
		$('#gold-id').trigger('change');
	});
	$('#gold-rate').live('change',function(){
		$('#fine-gold-in-cash').val(($('#gold-in-cash').val()/(($('#gold-rate').val())/10)).toFixed(3));
		
		
		
		if(parseInt($('#gold-rate').val()+"0")==0){
			$('#fine-gold-in-cash').val("0");
		}
	});
	
	$('#gold-in-cash').live('change',function(){
		$('#fine-gold-in-cash').val(($('#gold-in-cash').val()/(($('#gold-rate').val())/10)).toFixed(3));
		$('#total-cash').val((parseFloat($('#gold-in-cash').val())+parseFloat($('#lc').val())).toFixed(2));
	});
	$('#lc').live('change',function(){
		$('#total-cash').val((parseFloat($('#gold-in-cash').val())+parseFloat($('#lc').val())).toFixed(2));
	});
	
	/***************************   Validation *************/
	/*
	$('#agent-id').live('change',function(){
		var request=$.ajax({
		  						type:'get',
		   						url: base_url+"/validation_controller/is_valid_agent",
								data:  {agent_id: $('#agent-id').val()},
								beforeSend:function(){},
		   						success: function(data, textStatus, xhr) {
									//$('#agent-validation').html(data);
									useReturnData(data);
									function useReturnData(data){
					   					var response=$(data);
										var msg=response.filter('#msg').html();
										var err=response.filter('#error').text();
										if(err!='error'){
											$.msgBox({
							    				title: "Receipt Area",
							    				content: msg,
							    				type: "info",
							    				showButtons: true,
							    				opacity: 0.9,
												timeOut: 600,
							    				autoClose:true
											});
											$('#submit').removeClass('hidden');
											
										}else{
											$.msgBox({
							    				title: "Receipt Area",
							    				content: msg,
							    				type: "info",
							    				showButtons: true,
							    				opacity: 0.9,
												timeOut: 600,
							    				autoClose:false
											});
											$('#submit').addClass('hidden');
										}
									}
		   						}//end of success
								
					});//end get 
	});
	*/
	$('#agent-id').live('change',function(){
		var request=$.ajax({
		  						type:'get',
		   						url: base_url+"/gold_inward_controller/get_customers_by_agent_id",
								data:  {agent_id: $('#agent-id').val()},
								beforeSend:function(){},
		   						success: function(data, textStatus, xhr) {
									useReturnData(data);
									function useReturnData(data){
					   					$('#customer-div').html(data);
									}
		   						}//end of success
								
		});//end get 
	});
	$('#gold-receipt-div').live('click',function(){
		var request=$.ajax({
		  						type:'get',
		   						url: base_url+"/gold_inward_controller/display_gold_inward_receipt_by_id",
								data:  {gold_receipt_no: $('#gold-receipt-no').text()},
								beforeSend:function(){},
		   						success: function(data, textStatus, xhr) {
									//$('#agent-validation').html(data);
									useReturnData(data);
									function useReturnData(data){
					   					$('#outer-div').html(data);
									}
		   						}//end of success
								
					});//end get 
	});//end of gold-receipt-no
});