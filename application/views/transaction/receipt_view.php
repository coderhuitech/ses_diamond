<script>
	$(function() {
		$( document ).tooltip();		//to enable tooltip
	});
</script>

<style type="text/css">
	.hiddenDiv{
		display: none;
	}
</style>

<div id="outer_div">
	<h3>Receipt</h1>
	<form action='' id="receipt_form" method="post">
	<div id="area1">
		<div id="container1">
			<label for="receipt_date">Receipt Date</label>
			<?php echo form_input('receipt_date',set_value('receipt_date',$current_date),'type="datetime" placeholder="DD/MM/YYYY"');?>
			<br>
			<label for="debit_ledger">Debit Ledger(Cash/Bank)</label>
			<?php echo form_dropdown('debit_ledger',$debit_data,0,'id="debit_ledger"');?>
			<?php echo form_input('received_debit_amount',set_value('received_debit_amount'),'readonly="yes" class="input_amount numericOnly" id="received_debit_amount"') ;?>
			<div id="ledger_balance"></div>
			
			<div id="credit_ledgers_div">
				
			</div>
			<input type="button" id="delete_button" name="delete_button" value="Delete"/>
			<br><span id="error_span"></span>
		</div>
		<input name="submit" type="submit" value="Submit" id="submit" disabled="disabled"/>
	</div>
	</<form>
	<div id="result_div" class="hiddenDiv"></div>
	<div id="messageBox" class="hiddenDiv" title="Basic modal dialog"> </div>
</div>