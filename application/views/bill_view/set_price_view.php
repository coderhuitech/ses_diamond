<?php
echo form_open('site/create_items_price_action','id="price_input"');
if (isset($tables)) {
echo $tables;
	echo form_submit('submit','Submit','id="setPrice_submit"');
}else echo "Record Not Found";
echo form_hidden('count',$count);
echo form_hidden('client_id',$client_id);
echo form_hidden('weight_id',$weight_id);
echo form_hidden('zone',$zone);
echo form_close();
?>
<?php echo validation_errors('<p class="error">');?>
<?php echo $message ?>
