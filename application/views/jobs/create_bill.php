<div id="outer-div">
	<h1>Create Bill</h1>
	<div>
		<span id="show-customer">Show Customers</span>
		<div id="customer-div">
			<label for="">Customer Name</label>
			<?php echo form_dropdown('customer',$customers,'select','id="customer-id"') ;?>
		</div>
		
	</div>
	<div id="order-div">
	</div>
	<div id="job-div"></div>
	<div id="selected-job-div"></div>
	
</div>