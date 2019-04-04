<div id="outer_div">
	<div id="working_div">
	<form action="" id="purchase_form">
		<div id="account_area_div">
			<div id="debit_account_div">
					<label for="">Purchase Account</label>
					<?php echo form_dropdown('vendor_id',$purchase_accounts,0,'id="debit_account" class="dropdown"') ;?>
			</div>		
			
			<div id="crebit_account_div">
					<label for="">Credit Account</label>
					<?php echo form_dropdown('credit_account',$credit_accounts,0,'id="credit_account" class="dropdown"') ;?>
			</div>
		</div>
		<div id="materials_area_div">
			<div id="items_div" class='abc'>
					<?php echo form_dropdown('materials',$materials,0,'id="materials"') ;?>
					<?php echo form_input('qty',set_value('qty'),'id="qty" placeholder="QTY" required="yes" class="integerOnly"')?>
					<?php echo form_input('price',set_value('price'),'id="price" placeholder="Price" required="yes" class="numericOnly"')?>
					<?php echo form_input('amount',set_value('amount'),'id="amount" readonly="true" class="last"')?>
			</div>	
		</div>
	</form>
	</div>
</div>