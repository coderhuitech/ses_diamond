<div id="outer-div">
	<h1>Cash Receipt</h1>
	<div id="wall2">
		<div id="customer-div">
			<label for="">Customer Name</label>
			<input type="text" id="customer-id" name="customer_id" placeholder="Customer Id" title="Enter customer ID"/>
			<span id="customer-name">Customer Name</span>
		</div>
		<div id="customer-balance">
			Customer Balance
		</div>
		<div>
			<label for="">Receipt Mode</label>
			<?php echo form_dropdown('receipt_mode',$modes,1,'id="receipt-mode"'); ?>
			<input type="text" id="bank-details" class="hidden" placeholder="Cheque details" title="Enter Cheque details"/>
		</div>
		<div id="receipt-div">
			<label for="">Amount Received</label>
			<input type="text" id="received-id"  placeholder="00,000.00" title="Enter Received Amount"/>
		</div>
		<div id="customer-new-balance">
			<span id="lc-remaining"></span>
		</div>
		<div id="submit-div">
			<input type="submit" id="submit" value="submit"/>
		</div>
		<div id="report-div"></div>
	</div>
</div>
