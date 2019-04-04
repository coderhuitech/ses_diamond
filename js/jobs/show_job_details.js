$(function(){
	$('#job-id').live('keypress',function(e){
		var code = e.keyCode || e.which;
				if(code!=9 && code!=13)
					return;
		var request=$.ajax({
		  						type:'get',
		   						url: base_url+"/job_controller/get_sold_job_details_by_job_id",
								data:  {job_id: $('#job-id').val()},
								//data: data,
		   						//dataType:'text',
								beforeSend:function(){},
		   						success: function(data, textStatus, xhr) {
									useReturnData(data);
									function useReturnData(data){
							    var response=$(data);
								var msg=response.filter('#msg').text();
								var err=response.filter('#error').text();
								var report=response.filter('#report').text();
								if(err!='error'){
									/*$.msgBox({
									    title: "Job Report",
									    content: msg,
									    type: "info",
									    showButtons: true,
									    opacity: 0.9,
										timeOut: 1000,
									    autoClose:true
									});*/
									$('#result-div').html(response.filter('#report').html());
								}else{
									$.msgBox({
									    title: "Iten Deletion error",
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
	});
	
	$('#print-job').live('click',function(){
		//window.open(base_url+'/job_controller/print_job?job_id='+$('#job-id').val(), '_blank' , false);
		window.open(base_url+'/job_controller/print_job?job_id='+$('#job-id').val(),"_blank","toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=325pt, height=952pt");
	});
});