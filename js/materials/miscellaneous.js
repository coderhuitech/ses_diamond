$(function(){
	$('#fine-to-92').live('click',function(){
		var request=$.ajax({
			type:'get',
			url: base_url+"/material_controller/fine_to_92_trnasformation_facade",
			data: {},//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#result-div').html(data);
			}//end of success
		});//end of post request
	});
	$('#fine-to-90').live('click',function(){
		var request=$.ajax({
			type:'get',
			url: base_url+"/material_controller/fine_to_90_trnasformation_facade",
			data: {},//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#result-div').html(data);
			}//end of success
		});//end of post request
	});
	$('#left-div').on('click','#fine-to-88',function(){
		var request=$.ajax({
			type:'get',
			url: base_url+"/material_controller/fine_to_88_trnasformation_facade",
			data: {},//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#result-div').html(data);
			}//end of success
		});//end of post request
	});
	
	$('#left-div').on('click','#stock-reference-entry',function(){
		var request=$.ajax({
			type:'get',
			url: base_url+"/material_controller/stock_reference_entry_view",
			data: {},//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#result-div').html(data);
			}//end of success
		});//end of post request
	});
	$('#right-div').on('click','#stock-reference-save',function(){
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
		//------------------------------------
		$.msgBox({
		   	title: "Save Reference",
		   	content: "Confirm to save?",
		   	type: "confirm",
		   	buttons: [{ value: "Yes" }, { value: "No" }],
		   	success: function (result) {
		       	if (result == "Yes") {
					//your code here for yes
					SaveData();
					return;
		       	}//end of yes
				if (result == "No") {
		       		return;
		       	}//end of no
		  	}//end of success
		});
		
		//------------------------------------
		function SaveData(){
			var request=$.ajax({
			type:'get',
			url: base_url+"/material_controller/save_stock_reference",
			data: {reference_id: $('#reference-code').val()
				  ,product_set: $('#product-set').val()
				  ,qty: $('#qty').val()
				  ,appx_gold: $('#appx-gold').val()
				  ,total_weight: $('#total-weight').val()
				   },//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
						 useReturnData(data);
						function useReturnData(data){
							var obj = $.parseJSON(data)[0];
							$.msgBox({
								title: "Reference Entered",
								content: obj['msg'],
								type: obj['message_type'],
								showButtons: true,
								opacity: 0.5,
								autoClose:false
							});
							$('#result-div').html(obj['msg']);
						};//end of usereturndata
			}//end of success
		});//end of post request
		}
	});
	
	$('#transform-92').live('click',function(){
		$.msgBox({
		   	title: "Transfer Material",
		   	content: "Confirm to save?",
		   	type: "confirm",
		   	buttons: [{ value: "Yes" }, { value: "No" }],
		   	success: function (result) {
		       	if (result == "Yes") {
					//your code here for yes
					var request=$.ajax({
						type:'get',
						url: base_url+"/material_controller/fine_to_92_trnasformation_action",
						data: {fine_gold: $('#fine-gold-92').val()
							   ,copper: $('#copper-92').val()
							   ,gini: $('#gini-92').val()	
							   ,comment: $('#comment').val()
							   },//end of data
						beforeSend:function(){},
						success: function(data, textStatus, xhr) {
							var result=jQuery.parseJSON(data)[0];
							$.msgBox({
							    title: "Fine to 92 Conversion",
							    content: result['msg'],
							    type: "info",
							    showButtons: true,
							    opacity: 0.5,
								autoClose:false
							});
							if(result['success']==1){
								$('#result-div').html(result['success']);
							}
						}//end of success
					});//end of post request
					return;
		       	}//end of yes
				if (result == "No") {
		       		return;
		       	}//end of no
		  	}//end of success
		});
	});//end of transformation
	$('#transform-90').live('click',function(){
		$.msgBox({
		   	title: "Transfer Material",
		   	content: "Confirm to save?",
		   	type: "confirm",
		   	buttons: [{ value: "Yes" }, { value: "No" }],
		   	success: function (result) {
		       	if (result == "Yes") {
					//your code here for yes
					var request=$.ajax({
						type:'get',
						url: base_url+"/material_controller/fine_to_90_trnasformation_action",
						data: {fine_gold: $('#fine-gold-90').val()
							   ,copper: $('#copper-90').val()
							   ,gini: $('#gini-90').val()	
							   ,comment: $('#comment').val()
							   },//end of data
						beforeSend:function(){},
						success: function(data, textStatus, xhr) {
							var result=jQuery.parseJSON(data)[0];
							$.msgBox({
							    title: "Fine to 90 Conversion",
							    content: result['msg'],
							    type: "info",
							    showButtons: true,
							    opacity: 0.5,
								autoClose:false
							});
							if(result['success']==1){
								$('#result-div').html(result['msg']);
							}
						}//end of success
					});//end of post request
					return;
		       	}//end of yes
				if (result == "No") {
		       		return;
		       	}//end of no
		  	}//end of success
		});
	});//end of 90
	$('#transform-88').live('click',function(){
		$.msgBox({
		   	title: "Transfer Material",
		   	content: "Confirm to save?",
		   	type: "confirm",
		   	buttons: [{ value: "Yes" }, { value: "No" }],
		   	success: function (result) {
		       	if (result == "Yes") {
					//your code here for yes
					var request=$.ajax({
						type:'get',
						url: base_url+"/material_controller/fine_to_88_trnasformation_action",
						data: {fine_gold: $('#fine-gold-88').val()
							   ,copper: $('#copper-88').val()
							   ,gini: $('#gini-88').val()	
							   ,comment: $('#comment').val()
							   },//end of data
						beforeSend:function(){},
						success: function(data, textStatus, xhr) {
							var result=jQuery.parseJSON(data)[0];
							$.msgBox({
							    title: "Fine to 88 Conversion",
							    content: result['msg'],
							    type: "info",
							    showButtons: true,
							    opacity: 0.5,
								autoClose:false
							});
							if(result['success']==1){
								$('#result-div').html(result['msg']);
							}
						}//end of success
					});//end of post request
					return;
		       	}//end of yes
				if (result == "No") {
		       		return;
		       	}//end of no
		  	}//end of success
		});
	});//end of 88
	

	$('#outer-div').on('keyup','#fine-gold-92',function(){
		var x=$(this).val();
		$('#copper-92').val($.number(x*0.105,3));
		$('#gini-92').val($.number(x*1.105,3));
	});

	$('#outer-div').on('keyup','#fine-gold-90',function(){
		var x=$(this).val();
		$('#copper-90').val($.number(x*.127,3));
		$('#gini-90').val($.number(x*1.127,3));
	});

	$('#outer-div').on('keyup','#fine-gold-88',function(){

		var x=$(this).val();
		$('#copper-88').val($.number(x*.152,3));
		$('#gini-88').val($.number(x*1.152,3));
	});

	$(".date").datepicker({ dateFormat: 'dd/mm/yy' });

	
	
	/*$('#fine-gold-92').live('keyup',function(){
		var x=$(this).val();
		$('#copper-92').attr("placeholder", $.number(x*.095,3));
		$('#gini-92').attr("placeholder", $.number(x*1.095,3))
	});*/
	/*$('#fine-gold-90').live('keyup',function(){
		var x=$(this).val();
		$('#copper-90').attr("placeholder", $.number(x*.117,3));
		$('#gini-90').attr("placeholder", $.number(x*1.117,3))
	});
	$('#fine-gold-88').live('keyup',function(){
		var x=$(this).val();
		$('#copper-88').attr("placeholder", $.number(x*.142,3));
		$('#gini-88').attr("placeholder", $.number(x*1.142,3))
	});*/
	$(".date").datepicker({ dateFormat: 'dd/mm/yy' });
});