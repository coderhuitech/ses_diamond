<div id="outer-div">
	<h1>Staff Related Report</h1>
	Date from <input class="date" pattern="(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.](19|20)\d\d" type="date" id="date-from" value="<?php echo get_current_date();?>"/>to<input class="date" pattern="(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.](19|20)\d\d" value="<?php echo get_current_date();?>" type="date" title="Date upto" id="date-to"/>
	<div id="left-div">
		<div>
			<span id="staff-cash-in-hand-div" class="link">Cash Balance</span>
		</div>
		<div>
			<span id="staff-material-in-hand-div" class="link">Material Balance</span>
		</div>
		<div>
			<span id="staff-lc-receipt-daily-div" class="link">Daily LC Receipt</span>
		</div>
		<div>
			<span id="get-gold-receipt-report" class="link">Daily Gold Receipt</span>
		</div>
		<div>
			<span id="material-withdrawn" class="link">Material Withdrawn</span>
		</div>		
	</div>
	<div id="right-div">
		<div id="result-div">
		
		</div>
	</div>
	<span id="printDiv" class="link">Print Report</span>
	
</div>