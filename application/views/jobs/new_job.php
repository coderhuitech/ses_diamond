

<div id="outer-div">
	<div id="left-div">
		<input type="button" id="show-material-in-hand-karigar" value="Stock in Hand Karigar">
		<div id="left-div-karigar-result"></div>
		<input type="button" id="show-material-in-hand-user" value="Stock in Hand User">
		<span id="user-id" class="hidden"><?php echo $this->session->userdata('employee_id');?></span>
		<div id="left-div-user-result"></div>
	</div>
	<div id="mid-div">
	<div id="job-details-div">Job Details</div>
	<div id="job-send-report"></div>
	</div>
	<div id="right-div">
		<input type="button" value="Show Fresh Orders" id="show-orders"/>
		<div id="order-no-div"></div>
	</div>
	<div id="machine-gold-div"></div>
</div>
<div id="karigar-balance-div" style="overflow:scroll; height:400px;">
	<input type="submit" id="karigar-balance" value="Show Karigar Balance"/>
	<div id="all-karigar-balance-div"></div>
</div>