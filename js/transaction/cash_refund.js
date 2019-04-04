$(function(){
	$( "#employee-name" ).autocomplete({
			//auto complete with extra parameters
			source: function(request, response) {
	            $.ajax({
	                url: base_url+"/cash_refund_controller/get_employees",
	                dataType: "json",
	                data: {
	                    term : request.term,
	                    user_id : $("#user-id").text()
	                },
	                success: function(data) {
	                    response(data);
	                }
	            });
        	},
			 minLength: 2,
			focus: function(event, ui) {
            	$("#employee-name").val(ui.item.label); //product field will show the product list only
            	return false;
        	},
    		select: function (event, ui) {
				$('#employee-name').text(ui.item.label);
				$('#employee-name').attr('emp_id',ui.item.emp_id);
				$('#employee-balance').text(ui.item.balance);
				return false;
			}
	});
	$('#submit').live('click',function(){
		var user_id=$('#user-id').text();
		var emp_id=$('#employee-name').attr('emp_id');
		var refund_amount=$('#refund-amount').val()
		var no_of_required=0;
		
		
		if(emp_id=='0'){
			$('#employee-name').addClass('required');
			no_of_required++;
		}
		if(refund_amount<=0){
			$('#refund-amount').addClass('required');
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
   	title: "Save JOB",
   	content: "Confirm to save?",
   	type: "confirm",
   	buttons: [{ value: "Yes" }, { value: "No" }],
   	success: function (result) {
       	if (result == "Yes") {
			//your code here for yes
			var request=$.ajax({
					type:'get',
						url: base_url+"/cash_refund_controller/save_refund_transaction",
						data: {user_id: $('#user-id').text()
							   ,emp_id: $('#employee-name').attr('emp_id')
							   ,refund_amount: $('#refund-amount').val()
							   ,comment: $('#comment').val()},//end of data
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
									    title: "Cahs Refund",
									    content: msg,
									    type: "info",
									    showButtons: true,
									    opacity: 0.9,
										timeOut: 1000,
									    autoClose:true
										});
										$('#wall').html(response.filter('#report').html());
										$('#result').html('');
									}else{
										$.msgBox({
									    title: "Cahs Refund",
									    content: msg,
									    type: "info",
									    showButtons: true,
									    opacity: 0.9,
										timeOut: 1000,
									    autoClose:true
										});
										$('#result').html(response.filter('#report').html());
									}
								};//end of usereturndata
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
	$( "#employee-name" ).live('keypress',function(event){
		$( "#employee-name" ).attr('emp_id','0');
	});
	/*$('#test').live('click',function(){
		alert("adsf");
	});*/
	$('#outer-div').on('click', '#test', function() { 
			alert("asdfasdf");
	});
});