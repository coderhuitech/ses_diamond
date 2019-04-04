
<div id="outer-div">
	<h1>Receipt Gold</h1>
	<div>
	<label for="">Agent Id</label>
	<!--<input type="text" id="agent-id" value="AG2018" readonly="yes"/><span id="agent-validation"></span>-->
	<?php echo form_dropdown('agent_id',$agents,'AG0000','id=agent-id');?>
	<div id="customer-div">	</div>
	</div>
	<div id="cust-balance">Customer Balance</div>
	<div>
		<div id="gold-div">
			<label for="">Gold </label>
			<input type="text" class="number" id="gold" value="0"/>
			<?php echo form_dropdown('gold_type',$golds,2,'id="gold-id"') ;?>
			<span id="gold-text">text</span>
			
			<!--<div id="gold-in_cash-div">
			<label for="">Gold Rate </label>
			<input type="text" class="number" value="0" id="gold-rate" readonly="yes" />
			<label for="">Gold in Cash </label>
			<input type="text" class="number" value="0"  id="gold-in-cash" readonly="yes" />
			<label>Mode</label>
			<?php echo form_dropdown('mode',$modes,1,'id="mode"') ;?>
			</br><input type="text" placeholder="Bank Details" placeholder="Bank Details" title="Bank Details" id="bank-details"/>
			</br><label for="">Fine </label>
			<input type="text" class="number" value="0" id="fine-gold-in-cash" readonly="yes"/>
			</div>-->
		</div>
		<input type="button" value="Submit" id="submit"/>
		
	</div>
	<div id="final-div"></div>
</div>