<div id="items_list_div" class="printable" style="width:642px;font:16px/26px Georgia, Garamond, Serif;">
</div>
<fieldset>
<legend><?php echo "Set Price"?></legend>
<?php
echo form_open('site/display_bill_action','id="bill_qty_view"');
echo "<label>Year </label>";
echo form_error('year');
echo form_dropdown('year',$years,$current_year, 'id="client_id" required="yes" class="dropdown"');
echo "<label>Select Month Name </label>";
echo form_dropdown('month',$month,$current_month, 'id="client_id" required="yes" class="dropdown"');
echo "<label>Select Client Name </label>";
echo form_error('client_id');
echo form_dropdown('client_id',$clients,set_value('client_id'), 'id="client_id" required="yes" class="dropdown"');
echo "</br>";
echo form_submit('submit','submit','id="price_submit"');
echo form_close();
?>
</fieldset>