<div id="outer-div">
	
	<h1>Customer Related Report</h1>
	<hr>
	<div id="date-div">
		<?php 
			echo 'Select Agent : '.form_dropdown('agents',$agents,'0','id="agent-id"');
		?>
		Date from <input class="date" pattern="(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.](19|20)\d\d" type="date" id="date-from" value="<?php echo get_current_date();?>"/>to<input class="date" pattern="(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.](19|20)\d\d" value="<?php echo get_current_date();?>" type="date" title="Date upto" id="date-to"/>
	</div>
	<!--<div id="customer-div">
	<label for="cust_id">Cusomer ID</label><input type="text" id="cust-id" placeholder="Cust ID" title="Enter customer ID"/>
	</div>-->
	<div>
		<span id="customer-dues" class="link">Customer Dues<input type="text" value="500" id="row-number"/></span>
	</div>
	<div>
		<span id="customer-inward-outward-report" class="link">Custpmer Inward Outward Analysis</span>
	</div>
	<div id="result-div" class="printable">
		
	</div>
	<h2>
				&nbsp;&nbsp;&nbsp;<a href="#" id="hrefPrint">Print Report</a>
	</h2>
</div>