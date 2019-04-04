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
	$('#employee-id').live('change',function(){
		if($('#employee-id').val()=='select'){
			$('#right-div').empty();
			return;
		}
		var request=$.ajax({
		  				type:'get',
		   				url: base_url+"/material_controller/show_material_by_employee",
						data: {employee_id : $('#employee-id').val()},
						beforeSend:function(){},
		   				success: function(data, textStatus, xhr) {
							$('#right-div').html(data);
		   				}//end of success
					});//end of post request
	});
	$('#submit').live('click',function(event){
		event.preventDefault();
		var no_of_required=0;
		var employee_id=$('#employee-id').val();
		var material_value=parseFloat($('#material-value').val());
		if(employee_id=='select'){
			$('#employee-id').addClass('required');
			no_of_required++;
		}else{
			$('#employee-id').removeClass('required');
		}
		
		if($('#material_category').val()=='select'){
			$('#material_category').addClass('required');
			no_of_required++;
		}else{
			$('#material_category').removeClass('required');
		}
		
		if(material_value<=0){
			$('#material-value').addClass('required');
			no_of_required++;
		}else{
			$('#material-value').removeClass('required');
		}
		
		if(no_of_required>0){
			$.msgBox({
				title: "Material Loss Adjustment Error",
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
	    	title: "Save Material Loss",
	    	content: "Confirm to save?",
	    	type: "confirm",
	    	buttons: [{ value: "Yes" }, { value: "No" }],
	    	success: function (result) {
	        	if (result == "Yes") {
							//your code here 
					var request=$.ajax({
		  				type:'get',
		   				url: base_url+"/material_controller/save_material_loss",
						data: {material_value : material_value
							   ,material_id : $('#materials').val()
							   ,employee_id : $('#employee-id').val()},
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
									    title: "Material Loss",
									    content: msg,
									    type: "info",
									    showButtons: true,
									    opacity: 0.9,
										timeOut: 1000,
									    autoClose:true
									});
									$('#refresh-closing-stock').trigger('click');
									$('#right-div').html(response.filter('#report').html());
								}else{
									$.msgBox({
									    title: "Material Loss",
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
						return;
	        	}//end of yes
				
				if (result == "No") {
	            		return;
	        	}
	    	}
		});
	});
	$('#refresh-closing-stock').trigger('click');
});