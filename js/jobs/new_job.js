$(function(){
	//$('#').live('clicke',function(){});
	$('#show-orders').live('click',function(){
		var request=$.ajax({
		  				type:'get',
		   				url: base_url+"/job_controller/show_orders_ajax",
				/*		data: {model_no:$('#model-no').val()
							   ,model_size: $('#model-size').val()
							   ,appx_gold: $('#appx-gold').val()
							   ,qty: $('#qty').val()
							   ,description: $('#description').val()
							   },*/
						data: {},
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
									$('#order-no-div').html(response.filter('#report').html());
									$('#karigar-balance').trigger('click');
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
	$('.order-id').live('click',function(){
		var request=$.ajax({
		  				type:'get',
		   				url: base_url+"/job_controller/select_order_no_by_order_id_ajax",
				/*		data: {model_no:$('#model-no').val()
							   ,model_size: $('#model-size').val()
							   ,appx_gold: $('#appx-gold').val()
							   ,qty: $('#qty').val()
							   ,description: $('#description').val()
							   },*/
						data: {order_id: $(this).text()},
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
									$('#order-no-div').html(response.filter('#report').html());
									
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
	$('#order-no-table tr').live('click',function(){
		var row_id=$(this).closest('tr').attr('id');
		$('#order-no-table tr').removeClass('selected');
		$('#'+row_id).addClass('selected');
		var request=$.ajax({
		  				type:'get',
		   				url: base_url+"/job_controller/send_to_job_ajax",
						data: {order_no: row_id},
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
									$('#job-details-div').html(response.filter('#report').html());
									$('#show-material-in-hand-user').trigger('click');
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
	$('#print').live('click',function(){
		$("#job-details-div").printThis({
			debug: false,   
			importCSS: true,
			pageTitle: "test2",
			printContainer: true
			/*      debug: false,              * show the iframe for debugging
     importCSS: true,           * import page CSS
*      printContainer: true,      * grab outer container as well as the contents of the selector
*      loadCSS: "path/to/my.css", * path to additional css file
*      pageTitle: "",             * add title to print page
*      removeInline: false        * remove all inline styles from print elements*/
        });
	});
	$('.required').live('click',function(){
		$(this).removeClass('required');
	});
	$('#send-to-job').live('keypress',function(e){
		if (e.keyCode == 13) {               
    		e.preventDefault();
    		return false;
  		}
	});
	$('#send-to-job').live('click',function(e){
		var order_id=$('#challan-no').val();
		var no_of_required=0;
		var gold_send=parseFloat($('#send-gold').val());
		if(gold_send<=0){
			$('#send-gold').addClass('required');
			no_of_required++;
		}
		var karigarID=parseFloat($('#karigar').val());
		if(karigarID<=0){
			$('#karigar').addClass('required');
			no_of_required++;
		}
		if(no_of_required>0){
			$.msgBox({
				title: " Submit Error",
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
    			title: "Send order to Job",
    			content: "confirm to send",
    			type: "confirm",
    			buttons: [{ value: "Yes" }, { value: "No" }],
    			success: function (result) {
        			if (result == "Yes") {
						var request=$.ajax({
		  						type:'get',
		   						url: base_url+"/job_controller/save_order_to_job_master_ajax",
								data:  {gold_send: $('#send-gold').val()
										,rm_id: $('#rm-id').val()
										,karigar: $('#karigar').val()
										,order_details_order_no: $('#order-no').val()
										,challan_no: $('#challan-no').val()
										},
								//data: data,
		   						//dataType:'text',
								beforeSend:function(){},
		   						success: function(data, textStatus, xhr) {
									//$('#job-send-report').html(data);
									useReturnData(data);
									function useReturnData(data){
							    var response=$(data);
								var msg=response.filter('#msg').text();
								var err=response.filter('#error').text();
								var report=response.filter('#report').text();
								if(err!='error'){
									$.msgBox({
									    title: "Send to Job",
									    content: msg,
									    type: "info",
									    showButtons: true,
									    opacity: 0.9,
										timeOut: 1000,
									    autoClose:true
									});
									$('#job-details-div').html(response.filter('#report').html());
									$('#karigar-balance').trigger('click');
									$('#show-material-in-hand-user').trigger('click');
									$('#show-material-in-hand-user').trigger('click');
									var request=$.ajax({
						  				type:'get',
						   				url: base_url+"/job_controller/select_order_no_by_order_id_ajax",
										data: {order_id: order_id},
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
													$('#order-no-div').html(response.filter('#report').html());
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
									});//end of second post request
									
								}else{
									$.msgBox({
									    title: "Error Saving Order",
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
    		}
		});
	});
	$('#karigar-balance').live('click',function(event){
		event.preventDefault();
		var request=$.ajax({
		  				type:'get',
		   				url: base_url+"/material_controller/create_rm_master_pivot_table",
						data: {employee_id: $('#employee-id').text()},
						beforeSend:function(){},
		   				success: function(data, textStatus, xhr) {
							$('#all-karigar-balance-div').html(data);
		   				}//end of success
					});//end of post request
	});
	$('#show-material-in-hand-karigar').live('click',function(){
		var request=$.ajax({
		  				type:'get',
		   				url: base_url+"/material_controller/show_material_by_employee",
						data: {employee_id: $('#karigar').val()},
						beforeSend:function(){},
		   				success: function(data, textStatus, xhr) {
							$('#left-div-karigar-result').html(data);
		   				}//end of success
					});//end of post request
	});
	$('#show-material-in-hand-user').live('click',function(){
		var request=$.ajax({
		  				type:'get',
		   				url: base_url+"/material_controller/show_material_by_employee",
						data: {employee_id: $('#user-id').text()},
						beforeSend:function(){},
		   				success: function(data, textStatus, xhr) {
							$('#left-div-user-result').html(data);
		   				}//end of success
					});//end of post request
	});
	$('#send-gold-test').live('click',function(){
		var request=$.ajax({
		  				type:'get',
		   				url: base_url+"/job_controller/get_balance_from_machine_ajax",
				/*		data: {model_no:$('#model-no').val()
							   ,model_size: $('#model-size').val()
							   ,appx_gold: $('#appx-gold').val()
							   ,qty: $('#qty').val()
							   ,description: $('#description').val()
							   },*/
						data: {},
						beforeSend:function(){},
		   				success: function(data, textStatus, xhr) {
							useReturnData(data);
							function useReturnData(data){
							    var response=$(data);
								var msg=response.filter('#msg').text();
								var err=response.filter('#error').text();
								var report=response.filter('#report').text();
								if(err!='error'){
									$('#send-gold').val(response.filter('#report').text());
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
	$('#karigar').live('change',function(){
		$('#show-material-in-hand-karigar').trigger('click');
	});
	$('#show-orders').trigger('click');
	$('#karigar-balance').trigger('click');
});