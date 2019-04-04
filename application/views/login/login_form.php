
<div id="login_form">
    
	<div id="login_box">
		<div id="login_4">
		<label for="">Machine</label>
		<?php echo form_dropdown('machine',$machine,0,'id="machine"') ;?>
		<?php echo form_open('','id="user_login_form"');?>
		<label for="user_id">User Name : </label>
		<?php echo form_input('user_id','','placeholder="USER NAME" required="yes" id="user_name"');?>
		<label for="password" id="password_label">Password :   </label>
		<?php echo form_password('password','','placeholder="PASSWORD"  required="yes" id="password"');?>
		<?php echo form_submit('submit','login','id="submit"');?>
		<?php echo form_close();?>
		</div>
	</div>
	<div id="result3_div" ></div>
	<div id="messageBox"  title="Basic modal dialog"> </div>
</div>
