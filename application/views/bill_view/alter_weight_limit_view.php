<?php
echo form_open('site/save_weight_limit_action','id="item_Weight_display"');
echo $tables;
//echo $x;
echo form_hidden('count',$count);
echo form_submit('submit','submit');
echo form_close();
?>
<?php echo validation_errors('<p class="error">');?>