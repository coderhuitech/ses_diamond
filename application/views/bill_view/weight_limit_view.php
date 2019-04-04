<div id="items_list_div" class="printable" style="width:642px;font:16px/26px Georgia, Garamond, Serif;">
</div>
<fieldset>
<legend><?php echo "Set Weight"?></legend>
<?php
echo form_open('site/create_item_weight_action','id="set_weight"');
echo "<label>Lower Limit</label>";
echo form_input('lower_limit',set_value('lower_limit'),'placeholder="Lower Limit" required="yes" class="dropdown"');
echo "<label>Upper Limit</label>";
echo form_input('upper_limit',set_value('upper_limit'), 'placeholder="Upper Limit" required="yes" class="dropdown"');
echo form_submit('submit','submit','id="price_limit_submit"');
echo form_close();
?>
</fieldset>