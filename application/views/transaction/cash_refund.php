<div id="outer-div">
	<h1>Cash transfer between Staffs</h1>
	<div id="wall">
		<div id="user-div">
			<label>User ID</label>
			<span id="user-id"><?php echo $this->session->userdata('employee_id');?></span>
		</div>
		<div id="employee-div">
			<label for="employee-name">Employee</label>
			<input type="tex" placeholder="Employee Name" title="Employee Name" id="employee-name"/>(যার কাছ থেকে পাওয়া গেল)
			<label>Cash in Hand </label>
			<span id="employee-balance">X</span>
		</div>
		<div>
			<label>Refund</label>
			<span class="currencyinput">&#8377<input type="text" pattern="[0-9]*" id="refund-amount"></span>
		</div>
		<div>
			<input type="text" id="comment" placeholder="Your comment here"></input>
		</div>
		<div>
			<input type="button" value="Submit" id="submit"/>
		</div>
	</div>
	<div id="result">
		
	</div>
	
</div>