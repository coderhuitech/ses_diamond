<div id="_div" style="width:642px;font:16px/26px Georgia, Garamond, Serif;">
<h1><?php echo $message;?></h1>
<fieldset>
	<legend>Item Information</legend>
	<?php 
	echo form_open('site/_action','id="item_inward_alter"');
	echo "<label>Run Date</label>";
	echo form_error('run_date');
	echo form_input('run_date',isset($items_inward->run_date)?$items_inward->run_date:set_value('run_date'),'placeholder="dd/mm/yyyy" required="yes" class="dateInput"');
	?>
	<?php echo form_close();?>
</fieldset>
</div>
echo form_submit('submit','Submit','id="_submit"');