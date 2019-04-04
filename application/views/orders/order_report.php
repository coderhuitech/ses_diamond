<div id="outer-div">
	<div id="date-div">
		<label for="">Bill Date Between</label>
		<?php echo form_input('bill_from',set_value('bill_from',$current_date),'id="bill-from"'); ?>
		<label for="">to</label>
		<?php echo form_input('bill_from',set_value('bill_to',$current_date),'id="bill-to"'); ?>
	</div>
	<div id="customer-div">
		<label for="">Customer Name</label>
		<input type="text" id="customer-id" name="customer_id" placeholder="Customer Id" title="Enter customer ID"/>
		<span id="customer-name"></span>
	</div>
	<div id="bill-id-div">
		<label for="">Bill Number</label>
		<input type="text" id="bill-no" name="bill_no" placeholder="Bill No" title="Enter Bill No"/>
	</div>
	<div id="loader-div">
		<?php echo img(array('src'=>'img/ajax-loader.gif','class'=>'save','height'=>'50px','border'=>'0','alt'=>'save')) ;?>
		Please wait while loading ....
	</div>
	<div id="order-no-div">Order Numbers</div>
</div>