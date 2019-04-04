$(function(){
	$('#add-customer').live('click',function(){
		var request=$.ajax({
			type:'get',
			url: base_url+"/customer_controller/add_customer_view",
			data: {},//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#result-div').html(data);
			}//end of success
		});//end of post request
	});
	$('#customer-submit').live('click',function(){
			var isValid = true;
        	//$('input[type="text"]').each(function() {
        	$('input[required="yes"]').each(function() {
	            if ($.trim($(this).val()) == '') {
	                isValid = false;
	                $(this).css({
	                    "border": "1px solid red",
	                    "background": "#FFCECE"
	                });
	            }else {
	                $(this).css({
	                    "border": "",
	                    "background": ""
	                });
            	}
        	});
        	$('textarea').each(function() {
	            if ($.trim($(this).val()) == '') {
	                isValid = false;
	                $(this).css({
	                    "border": "1px solid red",
	                    "background": "#FFCECE"
	                });
	            }else {
	                $(this).css({
	                    "border": "",
	                    "background": ""
	                });
            	}
        	});
        if (isValid == false){
			$.msgBox({
				title: "Empty fields",
				content: "Enter all fields",
				type: "error",
				showButtons: true,
				opacity: 0.5,
				autoClose:false
			});
			return;
		}
		/*var x=$('#security1').val();
		var y=$('#security2').val();
		var z=(x%y)*20;
		var security3=$('#security3').val()
		if(security3!=z){
			$.msgBox({
				title: "Security Check",
				content: "Security Check fail",
				type: "error",
				showButtons: true,
				opacity: 0.5,
				autoClose:false
			});
			return;
		}*/
		var request=$.ajax({
			type:'get',
			url: base_url+"/customer_controller/add_customer_ajax",
			data: {cust_id: $('#cust-id').val()
				   ,cust_name: $('#cust-name').val()
				   ,mailing_name: $('#mailing-name').val()
				   ,city: $('#city').val()
				   ,address: $('#address').val()
				   ,phone: $('#phone').val()
				   ,initial: $('#initial').val()
				   },//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
			var obj = $.parseJSON(data)[0];
				$.msgBox({
				title: "Customer Insertion",
				content: obj['msg'],
				type: obj['message_type'],
				showButtons: true,
				opacity: 0.5,
				autoClose:false
			});
				$('#result-div').html(obj['msg']);
			}//end of success
		});//end of post request
	});
	$('#set-limit').live('click',function(){
		var request=$.ajax({
			type:'get',
			url: base_url+"/customer_controller/set_customer_limit_view",
			data: {},//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#result-div').html(data);
			}//end of success
		});//end of post request
	});
	$('#set-limit-cust-id').live('blur',function(){
		var request=$.ajax({
			type:'get',
			url: base_url+"/customer_controller/get_customer_by_id",
			data: {cust_id: $('#set-limit-cust-id').val()},//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				var obj = $.parseJSON(data)[0];
				$('#cust-name').val(obj['cust_name']);
				$('#mailing-name').val(obj['mailing_name']);
				$('#address').val(obj['cust_address']);
				$('#phone').val(obj['phone']);
				$('#initial').val(obj['initial']);
				$('#gold-limit').val(obj['gold_limit']);
				$('#cash-limit').val(obj['cash_limit']);
				$('#mv').val(obj['mv']);
				
				//$('#result-div').html(data);
			}//end of success
		});//end of post request
	});
	$('#customer-update-submit').live('click',function(){
			var isValid = true;
        	//$('input[type="text"]').each(function() {
        	$('input[required="yes"]').each(function() {
	            if ($.trim($(this).val()) == '') {
	                isValid = false;
	                $(this).css({
	                    "border": "1px solid red",
	                    "background": "#FFCECE"
	                });
	            }else {
	                $(this).css({
	                    "border": "",
	                    "background": ""
	                });
            	}
        	});
        	$('textarea').each(function() {
	            if ($.trim($(this).val()) == '') {
	                isValid = false;
	                $(this).css({
	                    "border": "1px solid red",
	                    "background": "#FFCECE"
	                });
	            }else {
	                $(this).css({
	                    "border": "",
	                    "background": ""
	                });
            	}
        	});
        if (isValid == false){
			$.msgBox({
				title: "Empty fields",
				content: "Enter all fields",
				type: "error",
				showButtons: true,
				opacity: 0.5,
				autoClose:false
			});
			return;
		}
		/*  confirmation box */
		$.msgBox({
		   	title: "Update Customer",
		   	content: "Confirm to save?",
		   	type: "confirm",
		   	buttons: [{ value: "Yes" }, { value: "No" }],
		   	success: function (result) {
		       	if (result == "Yes") {
					//your code here for yes
					var request=$.ajax({
						type:'get',
						url: base_url+"/customer_controller/update_customer_ajax",
						data: {
							cust_id: $('#set-limit-cust-id').val()
							,cust_name: $('#cust-name').val()
							,mailing_name: $('#mailing-name').val()
							,address: $('#address').val()
							,phone: $('#phone').val()
							,initial: $('#initial').val()
							,gold_limit: $('#gold-limit').val()
							,cash_limit: $('#cash-limit').val()
							,mv: $('#mv').val()
							
						},//end of data
						beforeSend:function(){},
						success: function(data, textStatus, xhr) {
						var obj = $.parseJSON(data)[0];
							$.msgBox({
							title: "Customer Updated",
							content: obj['msg'],
							type: obj['message_type'],
							showButtons: true,
							opacity: 0.5,
							autoClose:false
						});
							$('#result-div').html(obj['msg']);
						}//end of success
					});//end of post request
					return;
		       	}//end of yes
				if (result == "No") {
		       		return;
		       	}//end of no
		  	}//end of success
		});
		
		/*                   */
		
	});
	$('#set-agent').live('click',function(){
		var request=$.ajax({
			type:'get',
			url: base_url+"/customer_controller/set_agent_to_customer_view",
			data: {},//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#result-div').html(data);
			}//end of success
		});//end of post request
	});
	$('#result-div').on('click',' #agent-to-customer-div #submit',function(){
		var isValid = true;
        	//$('input[type="text"]').each(function() {
        	$('input[required="yes"]').each(function() {
	            if ($.trim($(this).val()) == '') {
	                isValid = false;
	                $(this).css({
	                    "border": "1px solid red",
	                    "background": "#FFCECE"
	                });
	            }else {
	                $(this).css({
	                    "border": "",
	                    "background": ""
	                });
            	}
        	});
        if (isValid == false){
			$.msgBox({
				title: "Empty fields",
				content: "Enter all fields",
				type: "error",
				showButtons: true,
				opacity: 0.5,
				autoClose:false
			});
			return;
		}
		
		//post validation
		$.msgBox({
		   	title: "Set Agent to Customer",
		   	content: "Confirm to set?",
		   	type: "confirm",
		   	buttons: [{ value: "Yes" }, { value: "No" }],
		   	success: function (result) {
		       	if (result == "Yes") {
					//your code here for yes
					var request=$.ajax({
						type:'get',
						url: base_url+"/customer_controller/add_agent_to_customer_ajax",
						data: {
							cust_id: $('#cust-id').val(),
							agent_id: $('#agent-id').val()
						},//end of data
						beforeSend:function(){},
						success: function(data, textStatus, xhr) {
						var obj = $.parseJSON(data)[0];
							$.msgBox({
							title: "Customer Updated",
							content: obj['msg'],
							type: obj['message_type'],
							showButtons: true,
							opacity: 0.5,
							autoClose:false
						});
							$('#result-div').html(obj['msg']);
						}//end of success
					});//end of post request
					return;
		       	}//end of yes
				if (result == "No") {
		       		return;
		       	}//end of no
		  	}//end of success
		});
	});
	$('#outer-div').on('click','#check-cust-id', function() {
		var request=$.ajax({
			type:'get',
			url: base_url+"/customer_controller/get_customer_by_id",
			data: {cust_id: $('#cust-id').val()},//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				var obj = $.parseJSON(data)[0];
				if(obj['success']==1){
					$('#cust-name').val(obj['cust_name']);
					$('#mailing-name').val(obj['mailing_name']);
					$('#address').val(obj['cust_address']);
					$('#city').val(obj['city']);
					$('#phone').val(obj['phone']);
					$('#initial').val(obj['initial']);
					$('#op-gold').val(obj['opening_gold']);
					$('#op-gold').attr("disabled", "disabled"); 
					$('#op-lc').val(obj['opening_lc']); 
					$('#op-lc').attr("disabled", "disabled"); 
					$('#customer-update').remove();
					$('#submit-div').append('<input type="submit" id="customer-update" title="Update Customer" value="Update" />');
					$('#customer-submit').hide();
					$('#customer-update').show();
				}else{
					alert("No such customer found");
					$('#cust-name').val(obj['cust_name']);
					$('#mailing-name').val(obj['mailing_name']);
					$('#address').val(obj['cust_address']);
					$('#city').val(obj['city']);
					$('#phone').val(obj['phone']);
					$('#initial').val(obj['initial']);
					$('#op-gold').val(obj['opening_gold']);
					$('#op-gold').removeAttr("disabled");
					$('#op-lc').val(obj['opening_lc']); 
					$('#op-lc').removeAttr("disabled"); 
					$('#customer-submit').show();
					$('#customer-update').hide();
				}
			}//end of success
		});//end of post request
	});
	$('#right-div').on('click','#customer-update',function(){
			var isValid = true;
        	//$('input[type="text"]').each(function() {
        	$('input[required="yes"]').each(function() {
	            if ($.trim($(this).val()) == '') {
	                isValid = false;
	                $(this).css({
	                    "border": "1px solid red",
	                    "background": "#FFCECE"
	                });
	            }else {
	                $(this).css({
	                    "border": "",
	                    "background": ""
	                });
            	}
        	});
        	$('textarea').each(function() {
	            if ($.trim($(this).val()) == '') {
	                isValid = false;
	                $(this).css({
	                    "border": "1px solid red",
	                    "background": "#FFCECE"
	                });
	            }else {
	                $(this).css({
	                    "border": "",
	                    "background": ""
	                });
            	}
        	});
        if (isValid == false){
			$.msgBox({
				title: "Empty fields",
				content: "Enter all fields",
				type: "error",
				showButtons: true,
				opacity: 0.5,
				autoClose:false
			});
			return;
		}
		//confirmation
		$.msgBox({
		   	title: "Update Customer",
		   	content: "Confirm to change customer?",
		   	type: "confirm",
		   	buttons: [{ value: "Yes" }, { value: "No" }],
		   	success: function (result) {
		       	if (result == "Yes") {
					//your code here for yes
					var request=$.ajax({
						type:'get',
						url: base_url+"/customer_controller/update_customer_ajax",
						data: {cust_id: $('#cust-id').val()
							   ,cust_name: $('#cust-name').val()
							   ,mailing_name: $('#mailing-name').val()
							   ,city: $('#city').val()
							   ,address: $('#address').val()
							   ,phone: $('#phone').val()
							   ,initial: $('#initial').val()
							   },//end of data
						beforeSend:function(){},
						success: function(data, textStatus, xhr) {
						var obj = $.parseJSON(data)[0];
							$.msgBox({
							title: "Customer Insertion",
							content: obj['msg'],
							type: obj['message_type'],
							showButtons: true,
							opacity: 0.5,
							autoClose:false
						});
							$('#result-div').html(obj['bill_no']);
						}//end of success
					});//end of post request
					return;
		       	}//end of yes
				if (result == "No") {
		       		return;
		       	}//end of no
		  	}//end of success
		});
		// end of confirmation
		
	});
	
	$('#right-div').on('blur','#agent-to-customer-div #cust-id',function(){
		var request=$.ajax({
		type:'get',
			url: base_url+"/customer_controller/get_customer_by_id",
			data: {cust_id: $('#cust-id').val()},//end of data
			success: function(data, textStatus, xhr) {
				var obj = $.parseJSON(data)[0];
				$('#customer').remove();
				$( '<span id="customer">'+obj['cust_name']+'</span>' ).insertAfter( "#cust-id" );
			}//end of success
		});//end of post request
	});
});