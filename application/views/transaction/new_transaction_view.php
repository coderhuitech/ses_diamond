<div id="outer-div">
	<h1>Transactions</h1>
	<hr>
	<div id="left-div">
		
		<ol>
			<li><span id="lc-receipt-from-customer" class="link">LC from Customer</span></li>
			<li><span id="gold-receipt-from-customer" class="link">Gold from Customer</span></li>
			<li>____________________</li>
			<li><?php echo anchor_popup('advance_report_controller/sales_gold_receipt_report?gold_receipt_id=0000', "View Previous Gold Receipt");?></li>
			<li><?php echo anchor_popup('advance_report_controller/show_lc_receipt_report?lc_receipt_id=0000', "View Previous LC Receipt");?></li>
			<li>____________________</li>
		</ol>
			
	
	</div>
	<div id="right-div">
		<div id="data-div">
			
		</div>
		<div id="result-div"  class="printable">
			
			<?php echo img(array('src'=>'img/printer.png','class'=>'no-print printer','height'=>'25px','border'=>'0','alt'=>'No Image'));?>
		</div>
		
	</div>
	
</div>