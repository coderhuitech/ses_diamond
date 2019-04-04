<?php
class User_controller extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this -> load -> library('form_validation');
		//this libraru is used for validation of fields in a form
		$this -> load -> model('order_model');
		$this -> load -> model('customer_model');
		$this->load->model('main_model');
		// this model for menus and navs
		$this -> is_logged_in();
		
		$this -> load -> helper(array('datagrid', 'url','huiui','html'));
	}
	
	function is_logged_in() {
		$is_logged_in = $this -> session -> userdata('is_logged_in');
		if (!isset($is_logged_in) || $is_logged_in != TRUE) {
			echo 'you have no permission to use this page. <a href="../login">Login</a>';
			die();
		}
	}
	function set_site_data($view_file,$main_data = array('temp1'=>'data1')) {//important code used in near about all actions
		$template_data['main_data'] = $main_data;
		$template_data['header_data']=array('java_script'=>$main_data['java_script'],'css'=>$main_data['css'],'page_title'=>$main_data['page_title']);
		$template_data['view_file']=$view_file;
		return $template_data;
	}
	function user_master_facade(){
		//this block of code is mandatory for all facade from now
		//-------------------------------------------------------------------------
		// extention should not be given
		$java_scripts=array('UI2/jquery-ui-1.10.2.custom/js/jquery-ui-1.10.2.custom'
			,'general'
			,'table/jquery.dataTables.min'				// for data table
			,'popup/js/alertbox'					// for popup message
			,'popup/js/jquery.easing.1.3'
			,'message_box/Scripts/jquery.msgBox'
			,'general/validation'
			,'users/user_master'
			
		);
		
		$css=array('transaction/capital/gold_introduce'
					,'ui-lightness/jquery-ui-1.10.2.custom'
					,'table_css/table/jquery.dataTables'
					,'users/user_master'
					,'message_box/Styles/msgBoxLight'
					);	   //for data table jquery
		$agents['AG2018']='Counter Agent';
		$main_data['user_id']=$this->session->userdata('user_id');
		$main_data['agents']=$agents;
		$main_data['java_script']=$java_scripts;
		$main_data['css']=$css;
		$main_data['current_date']=get_current_date();
		$main_data['delivery_date']=date("d/m/Y",strtotime("+5 days"));
		$main_data['user_key']=uniqid('', true);
		$main_data['page_title']="User Area";
		$this -> load -> view('includes/staff/template', $this -> set_site_data('users/user_master_view',$main_data));
	}
	function change_password_view(){
		echo '<div id="new-password">';
			echo '<h1>Change Password</h1>';
			echo '<div>';
				echo form_label('Current Password  ');
				echo '<input type="password" id="current-password" placeholder="Current Password" title="Enter Current Password" required="yes"/>';
			echo '</div>';
			echo '<div>';
				echo form_label('New Password');
				echo '<input type="password" id="new-password" placeholder="New Password" title="Enter New Password" required="yes"/>';
			echo '</div>';
			echo '<div>';
				echo form_label('Confirm Password ');
				echo '<input type="password" id="confirm-password" placeholder="Confirm Password" title="Retype the Password" required="yes"/>';
			echo '</div>';
			echo '<div>';
				echo '<input type="button" id="submit-new-password" title="Submit Customer" value="Submit" />';
			echo '</div>';
			
		echo '</div';
	}
	
}
?>