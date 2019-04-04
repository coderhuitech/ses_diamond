<?php
class Validation_controller extends CI_Controller{
	
	function __construct(){
		parent::__construct();
		$this -> load -> library('form_validation');
		//this libraru is used for validation of fields in a form
		$this -> load -> model('main_model');
		$this -> load -> model('validation_model');
		$this -> is_logged_in();
		
		$this -> load -> helper(array('datagrid', 'url','huiui','csv','html'));
	}
	
	function is_logged_in(){
		$is_logged_in = $this -> session -> userdata('is_logged_in');
		if(!isset($is_logged_in) || $is_logged_in != TRUE){
			echo 'you have no permission to use this page. <a href="../login">Login</a>';
			die();
		}
	}
	function set_site_data($view_file,$main_data = array('temp1'=>'data1')){//important code used in near about all actions
		$template_data['main_data'] = $main_data;
		$template_data['header_data']=array('java_script'=>$main_data['java_script'],'css'=>$main_data['css'],'page_title'=>$main_data['page_title']);
		$template_data['view_file']=$view_file;
		return $template_data;
	}
	function is_valid_agent(){
		$result=$this->validation_model->get_no_of_agents_by_id($_GET['agent_id']);
		if($result->no_of_agents>0){
			echo '<div id="validation">true</div>';
			echo '<div id="error">';
			echo 'noerror';
			echo '</div>';
			echo '<div id="msg">';
			echo 'Agent Exists</br>';
			echo '</div>';
		}else{
			echo '<div id="validation">false</div>';
			echo '<div id="error">';
			echo 'error';
			echo '</div>';
			echo '<div id="msg">';
			echo 'No Such Agent</br>';
			echo '</div>';
		}
	}
	function is_valid_customer(){
		$result=$this->validation_model->get_no_of_customer_by_id($_GET['customer_id']);
		if($result->no_of_customers>0){
			echo '<div id="validation">true</div>';
			echo '<div id="error">';
			echo 'noerror';
			echo '</div>';
			echo '<div id="msg">';
			echo 'Customer Exists</br>';
			echo '</div>';
		}else{
			echo '<div id="validation">false</div>';
			echo '<div id="error">';
			echo 'error';
			echo '</div>';
			echo '<div id="msg">';
			echo 'No Such Customer</br>';
			echo '</div>';
		}
	}
}
?>