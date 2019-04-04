$(function(){
	var tableToExcel = (function() {
  var uri = 'data:application/vnd.ms-excel;base64,'
    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
    , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
    , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
  return function(table, name) {
    if (!table.nodeType) table = document.getElementById(table)
    var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
    window.location.href = uri + base64(format(template, ctx))
  }
})()
	/*************/
	$("#outer-div").on('click','#hrefPrint',function(){
		$("#result-div").print();
		return (false);
	});
	$("#outer-div").on('click','#job-report',function(){
		var request=$.ajax({
			type:'get',
			url: base_url+"/report_controller/get_admin_job_report",
			data: {date_from: $('#date-from').val()
				   ,date_to: $('#date-to').val()
				   },//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#result-div').html(data);
			}//end of success
		});//end of post request
	});
	$("#outer-div").on('click','#material-submit',function(){
		var request=$.ajax({
			type:'get',
			url: base_url+"/report_controller/get_owner_material_submit",
			data: {date_from: $('#date-from').val()
				   ,date_to: $('#date-to').val()
				   },//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#result-div').html(data);
			}//end of success
		});//end of post request
	});
	$("#outer-div").on('click','#readymade-item-submit',function(){
		var request=$.ajax({
			type:'get',
			url: base_url+"/report_controller/get_owner_readymade_item_submit",
			data: {date_from: $('#date-from').val()
				   ,date_to: $('#date-to').val()
				   },//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#result-div').html(data);
			}//end of success
		});//end of post request
	});
	
	$('#outer-div').on('click','.job',function(){
		//window.open(base_url+'/job_controller/print_job?job_id='+$('#job-id').val(), '_blank' , false);
		window.open(base_url+'/job_controller/print_job_admin?job_id='+$(this).attr( "id" ),"_blank","toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=325pt, height=952pt");
	});
	$("#left-div").on('click','#show-business-status',function(){
		var request=$.ajax({
			type:'get',
			url: base_url+"/report_controller/get_status_report_admin",
			data: {date_from: $('#date-from').val()
				   ,date_to: $('#date-to').val()
				   },//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#result-div').html(data);
			}//end of success
		});//end of get request
	});
	$("#left-div").on('click','#karigar-job-report',function(){
		var request=$.ajax({
			type:'get',
			url: base_url+"/report_controller/get_karigar_wise_job_report",
			data: {date_from: $('#date-from').val()
				   ,date_to: $('#date-to').val()
				   },//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#result-div').html(data);
			}//end of success
		});//end of get request
	});
	$("#result-div").on('click','#show',function(){
		var request=$.ajax({
			type:'get',
			url: base_url+"/report_controller/show_karigar_wise_job_report",
			data: {date_from: $('#date-from').val()
				   ,date_to: $('#date-to').val()
				   ,emp_id: $('#emp-id').val()
				   },//end of data
			beforeSend:function(){},
			success: function(data, textStatus, xhr) {
				$('#result-div').html(data);
			}//end of success
		});//end of get request
	});
	$("#result-div").on('click','#exportToExcel',function(e){
		tableToExcel('test-table', 'W3C Example Table');
	});
	
	
	
	$(".date").datepicker({ dateFormat: 'dd/mm/yy' });
});