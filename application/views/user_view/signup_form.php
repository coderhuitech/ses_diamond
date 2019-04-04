<h1>Create an Account</h1>
<fieldset>
	<legend>Personal Information</legend>
	<?php 
	echo form_open('login/create_member');
	echo "<label>First Name</label>";
	echo form_input('emp_name',set_value('emp_name'),"placeholder='Name'");
	echo "<label>Address 1</label>";
	echo form_input('emp_address1',set_value('emp_address1'),"placeholder='Address 1'");
	echo "<label>Address 2</label>";
	echo form_input('emp_address2',set_value('emp_address2'),"placeholder='Address 2'");
	echo "<label>Department</label>";
	echo form_input('department_name',set_value('department_name'),"placeholder='Department Name'");
	echo "<label>Designation</label>";
	echo form_input('designation',set_value('designation'),"placeholder='designation'");
	echo "<label>Email Address</label>";
	echo form_input('emp_email',set_value('emp_email'),"placeholder='Email'");
	echo "<label>Phone 1</label>";
	echo form_input('emp_phone1',set_value('emp_phone1'),"placeholder='Phone 1'");
	echo "<label>Phone 2</label>";
	echo form_input('emp_phone2',set_value('emp_phone2'),"placeholder='Phone 2'");

	?>
</fieldset>

<fieldset>
	<legend>Login information</legend>
	<?php
	echo "<label>User Name</label>";
	echo form_input('username',set_value('username'),"placeholder='User Name'");
	echo "<label>Password </label>";
	echo form_password('password',set_value('password'),"placeholder='Password'");
	echo "<label>Confirm Password</label>";
	echo form_password('password2',set_value('password2'),"placeholder='Confirm Password'");
	echo form_submit('submit','Create Account');
	?>
	<?php echo validation_errors('<p class="error">');?>
</fieldset>


emp_address1
emp_address2
emp_department_id
emp_designation_id
emp_email
emp_id
emp_name
emp_phone1
emp_phone2
time_stamp