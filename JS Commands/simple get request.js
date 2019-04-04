	$('.tst').live('click',function(event){
		event.preventDefault();
		var x=$(this).attr("href").match(/[\d]+$/);
		var request=$.ajax({
		type:'get',
			url: base_url+"/report_controller/test_pagination",
			data: {xval: x},//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
					useReturnData(data);
					function useReturnData(data){
						
					};//end of usereturndata
			}//end of success
		});//end of post request
	});//end of live