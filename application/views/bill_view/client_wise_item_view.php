<div id="items_list_div" class="printable" style="width:642px;font:16px/26px Georgia, Garamond, Serif;">
</div>
<fieldset>
<legend><?php echo "Set Price"?></legend>
<?php
echo form_open('site/set_price_items_facade','id="set_price"');
echo "<label>Select Client Name </label>";
echo form_dropdown('client_id',$clients,set_value('client_id'), 'id="client_id" required="yes" class="dropdown"');
echo "<label>Select Weight </label>";
echo form_dropdown('weight_id',$weights,set_value('weight_id'), 'id="client_id" required="yes" class="dropdown"');
echo form_label('Select Zone');
echo form_dropdown('zone',$zones,set_value('zone'),'id="item_id" required="yes" class="dropdown"');
echo form_submit('submit','submit','id="price_submit"');
echo form_close();
?>
</fieldset>