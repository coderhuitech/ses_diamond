<style type="text/css">
.side_by_side{
	display: inline-block;
	margin-left: 5px;
}
#customer_name{
	display: inline-block;
	width: 250px;
}
#customer_id{
	display: inline-block;
	width: 75px;
}
</style>
<div id="duplicate_bill" style="width:642px;font:16px/26px Georgia, Garamond, Serif;">
 <script type="text/javascript">
	var customer= <?php echo json_encode($customers); ?>;
	var base_url= <?php echo json_encode(base_url()); ?>;
	$(function() {
		$( "#customer_name" ).autocomplete({
			source: customer,			//will get the data from this Array
			focus: function(event, ui) {
            	$("#customer_name").val(ui.item.label); //product field will show the product list only
            	return false;
        	},
    		select: function (event, ui) {
				$("#customer_id").val(ui.item.value); // box number will show the value
				 	/*$.get(base_url+'index.php/report_controller/show_all_bills_by_cust_id_ajax?cust_id='+ui.item.value,'',function(data){
						$('#bill_list_div').html(data);
        			});*/
				return false;
			}
		});
		
	});
</script>
<fieldset>
	<legend>Select Customer for Bill</legend>
	<?php
	echo form_open('report_controller/show_duplicate_bill_action','id="form_duplicate_view"');
	echo "<label>Customer ID</label>";
	echo form_error('customer_id');
	echo '<div class="side_by_side">';
	echo form_input('customer_name',set_value('customer_name'),'id="customer_name" placeholder="Type Customer name for ID" required="yes"');
	echo form_input('customer_id',set_value('customer_id'),'id="customer_id"  required="yes"');
	echo '<div>';
	
	echo form_button('show','show','id="show_bills"');
	?>
</fieldset>
<?php echo form_close();?>
	<div id="bill_list_div">
		<?php echo $bill_table;?>
	</div>
</div>
