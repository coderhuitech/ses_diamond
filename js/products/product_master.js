$(function(){
	$('#add-edit-product').live('click',function(){
		var request=$.ajax({
			type:'get',
			url: base_url+"/product_controller/add_edit_product",
			data: {},//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#result-div').html(data);
			}//end of success
		});//end of post request
	});
	$('#product-code').live('blur',function(){
		var request=$.ajax({
			type:'get',
			url: base_url+"/product_controller/get_product_by_product_code",
			data: {product_code: $('#product-code').val()},//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				 var obj = $.parseJSON(data)[0];
				 /* $.each(obj, function() {
				      lang += this['label'] + "<br/>";
				  });*/
				  $('#Price-code').val(obj['price_code']);
				  $('#product-category').val(obj['product_category']);
				  $('#product-description').val(obj['description']);
				  $('#price-code').val(obj['price_code']);
				  //$('#result-div').html(data);
			}//end of success
		});//end of post request
	});
	/*$('#submit').live('click',function(event){
		event.preventDefault();
		
	});*/
	
	$('#submit').live('click',function(){
		var product_category=parseInt($('#product-category option:selected').val());
		var valid = true;
	    $('#myform').find('input').each(function(){
	        if($(this).prop('pattern')){
				if(!$(this).val().match($(this).prop('pattern'))){
					valid = false;
					$(this).addClass('error');
				}else{
					$(this).removeClass('error');
				}
	            
			}
	    });
		if(product_category==0){
			vslid=false;
			$('#product-category').addClass('error');
		}else{
			$('#product-category').removeClass('error');
		}
	    if(!valid){
	        //invalidate form
	        $.msgBox({
				title: "Enter Proper Data",
				content: "Error",
				type: "info",
				showButtons: true,
				opacity: 0.5,
				autoClose:false
			});
			return;
	    }
	    var request=$.ajax({
			type:'get',
			url: base_url+"/product_controller/add_product",
			data: {product_code: $('#product-code').val()
				  ,description: $('#product-description').val()
				  ,category: $('#product-category').val()
				  ,price_code: $('#price-code').val()
				  },//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				 var obj = $.parseJSON(data)[0];
				$('#result-div').html(data);
			}//end of success
			});//end of post request
		});
		var timer;
        var delay = 4000; // 0.6 seconds delay after last input
 
        $('#product-code').live('keyup', function() {
            window.clearTimeout(timer);
            timer = window.setTimeout(function(){
                  //insert delayed input change action/event here
                $('#product-code').trigger('blur');
 
            }, delay);
        })
	$(".date").datepicker({ dateFormat: 'dd/mm/yy' });
});