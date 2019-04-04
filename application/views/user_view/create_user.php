<h1>Create an Account</h1>
<fieldset>
	<legend>Personal Information</legend>
	<?php 
	echo form_open('login/create_member');
	echo "<label>First Name</label>";
	echo form_input('first_name',set_value('first_name'),"placeholder='First Name'");
	echo "<label>Last Name</label>";
	echo form_input('last_name',set_value('last_name'),"placeholder='Last Name'");
	echo "<label>Email Address</label>";
	echo form_input('email_address',set_value('email_address'),"placeholder='your_email@huiui.co.in'");
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