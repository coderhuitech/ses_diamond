$(function(){
	$('#material_category').live('change',function(){
		var request=$.ajax({
		  				type:'get',
		   				url: base_url+"/material_controller/get_rm_by_category_ajax",
						data: {rm_cat_id:$('#material_category').val()},
						beforeSend:function(){},
		   				success: function(data, textStatus, xhr) {
								$('#material-div').html(data);
							//useReturnData(data);
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
	$('.required').live('click',function(){
		$(this).removeClass('required');
	});
	$('#submit').live('click',function(event){
		event.preventDefault();
		/********************************************************/
		var no_of_required=0;
		var material_qty=parseFloat($('#material-qty').val());
		if(material_qty<=0){
			$('#material-qty').addClass('required');
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
	    	title: "Saving Transaction",
	    	content: "Confirm to Commit?",
	    	type: "confirm",
	    	buttons: [{ value: "Yes" }, { value: "No" }],
	    	success: function (result) {
	        	if (result == "Yes") {
					var request=$.ajax({
		  				type:'get',
		   				url: base_url+"/material_controller/add_transfer_material_between_employees",
						data: {employee_id:$('#employee_id').val()
							  ,material_id:$('#materials').val()
							  ,material_value:$('#material-qty').val()
							  ,comment: $('#comment').val()},
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
									    title: "Material Transfer between Employees",
									    content: msg,
									    type: "info",
									    showButtons: true,
									    opacity: 0.9,
										timeOut: 1000,
									    autoClose:true
									});
									$('#report-div').html(response.filter('#report').html());
									$('#refresh-closing-stock').trigger( "click" );
								}else{
									$.msgBox({
									    title: "Material Transfer between Employees",
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
	        	}//end of yes
				
				if (result == "No") {return;}
				
	    	}//end of success
		});
		/********************************************************/
		
		
	});
	$('#refresh-closing-stock').live('click',function(){
		var request=$.ajax({
		  				type:'get',
		   				url: base_url+"/material_controller/create_rm_master_pivot_table",
						data: {},
						beforeSend:function(){},
		   				success: function(data, textStatus, xhr) {
							$('#closing-stock-div').html(data);
		   				}//end of success
					});//end of post request
	});
	$('#refresh-closing-stock').trigger('click');
});