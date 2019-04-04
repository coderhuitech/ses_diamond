var request=$.ajax({
	type:'get',
	url: base_url+"/job_controller/save_job_finish_ajax",
	data: {job_id: $('#job-id').text()
		   ,gross_weight: gross_weight
		   ,user_id: $('#user-id').text()},//end of data
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
					    title: "JOB PHASE FINISH",
					    content: msg,
					    type: "info",
					    showButtons: true,
					    opacity: 0.9,
						timeOut: 1000,
					    autoClose:true
					});
					$('#show-jobs').trigger('click');
				}else{
					$('#job-details-div').html(response.filter('#report').html());
					$.msgBox({
					    title: "JOB PHASE FINISH",
					    content: msg,
					    type: "info",
					    showButtons: true,
					    opacity: 0.5,
					    autoClose:false
					});//end of message box
				}//end of if
			};//end of usereturndata
	}//end of success
});//end of post request