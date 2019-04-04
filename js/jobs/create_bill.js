$(function(){
	var greet = function( person, greeting ) {
    	var text = greeting + ", " + person;
    	console.log( text );
	};
	$('#show-customer').live('click',function(){
		var request=$.ajax({
		  				type:'get',
		   				url: base_url+"/job_controller/get_customers",
						data: {},
						beforeSend:function(){},
		   				success: function(data, textStatus, xhr) {
							$('#customer-div').html(data);
		   				}//end of success
					});//end of post request
	});
	$('#customer-id').live('change',function(){
		var request=$.ajax({
		  				type:'get',
		   				url: base_url+"/job_controller/get_orders_by_cust_id_for_bill_ajax",
						data: {cust_id: $('#customer-id').val()},
						beforeSend:function(){},
		   				success: function(data, textStatus, xhr) {
							$('#order-div').html(data);
		   				}//end of success
					});//end of post request
					
	});
	$('#order-id').live('change',function(){
		var request=$.ajax({
		  				type:'get',
		   				url: base_url+"/job_controller/get_jobs_for_bill_ajax",
						data: {cust_id: $('#customer-id').val()
						       ,bill_id: $('#order-id').val()
							   },
						beforeSend:function(){},
		   				success: function(data, textStatus, xhr) {
							$('#job-div').html(data);
		   				}//end of success
					});//end of post request
	});
	$('#job-id').live('change',function(){
		var job='<span>'+$(this).val()+'</span></br>';
		$("#selected-job-div").append(job);
	});
	$('#test-div2').live('click',function(){
		var ar = jQuery('div#test-div2').children('.but').map( function() {
	    return jQuery(this).text();
		}).get();
		alert(ar);
	});
	$('#send-job-for-bill').live('keypress',function(e){
		if (e.keyCode == 13) {               
    		e.preventDefault();
    		return false;
  		}
	});
	$('#send-job-for-bill').live('click',function(){
		// to map the values to an array
		var ar = jQuery('div#jobs-ready-for-bill-div').children('.job').map( function() {
	    	return jQuery(this).attr('id');
		}).get();
		//**********************************************
		$.msgBox({
    	title: "Create Bill",
	    	content: "Are you sure?",
	    	type: "confirm",
	    	buttons: [{ value: "Yes" }, { value: "No" }],
	    	success: function (result) {
	        	if (result == "Yes") {
							//your code here 
					var request=$.ajax({
		  				type:'get',
		   				url: base_url+"/job_controller/create_bill_ajax",
						data: {ar: ar
							   ,customer_markup: $('#customer-markup').text()
							   ,customer_id: $('#customer-id').val()
							   ,order_id: $('#order-id').val()
							   },
						beforeSend:function(){},
		   				success: function(data, textStatus, xhr) {
							useReturnData(data);
							function useReturnData(data){
							    var response=$(data);
								var msg=response.filter('#msg').text();
								var err=response.filter('#error').text();
								var report=response.filter('#report').html();
								if(err!='error'){
									$.msgBox({
									    title: "New Bill Creation",
									    content: msg,
									    type: "info",
									    showButtons: true,
									    opacity: 0.9,
										timeOut: 1000,
									    autoClose:true
									});
									$('#outer-div').html(report);
								}else{
									$.msgBox({
									    title: "New Bill Creation",
									    content: msg,
									    type: "info",
									    showButtons: true,
									    opacity: 0.5,
									    autoClose:false
									});
									$('#outer-div').html(data);
								}
							};//end of usereturndata
		   				}//end of success
					});//end of post request
						return;
	        	}//end of yes
				
				if (result == "No") {
	            		return;
	        	}
	    	}
		});
		
		
		//***********************************************
		
	});
	$('.remove').live('click',function(){
		$(this).closest("div").remove();
	});
	$('#customer-id').trigger('change');
	$('#order-id').trigger('change');
	$('#print-bill').live('click',function(){
		//window.open(base_url+'/job_controller/print_job?job_id='+$('#job-id').val(), '_blank' , false);
		window.open(base_url+'/bill_controller/bill_print_action?bill_no='+$('#print-bill').text(),"_blank","toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, ");
	});
});