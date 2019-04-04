$(function(){

	$('#show_bills').live('click',function(){
        $.get(base_url+'index.php/report_controller/show_all_bills_by_cust_id_ajax?cust_id='+$('#customer_id').val(),'',function(data){
			$('#bill_list_div').html(data);
        });
   	});
});