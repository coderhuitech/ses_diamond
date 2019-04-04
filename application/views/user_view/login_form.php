<script type="text/javascript">
$(function(){
	var base_url= <?php echo json_encode(base_url()); ?>;
	jQuery.fn.exists = function(){return this.length>0;}
	jQuery.fn.notExists = function(){return !this.length>0;}
	$('#submit').live('click',function(event){
			event.preventDefault();
			$("#result_div").show(3000);
			$.post(base_url+"index.php/login/validate_credential_action_ajax", $("#user_login_form").serialize(), function(data,status) {		
				$('#result_div').html(data);
			});
			/*$("#result_div").hide(28000);*/
	});
});
</script>
<div id="login_form">
    
	<div id="login_box">
		<div id="login_4">
		<?php echo form_open('','id="user_login_form"');?>
		<label for="user_id">User Name : </label>
		<?php echo form_input('user_id','','placeholder="USER NAME" required="yes" id="user_name"');?>
		<label for="password" id="password_label">Password :   </label>
		<?php echo form_password('password','','placeholder="PASSWORD"  required="yes" id="password"');?>
		<?php echo form_submit('submit','login','id="submit"');?>
		<?php echo form_close();?>
		</div>
	</div>
	<div id="result_div"></div>
</div>
