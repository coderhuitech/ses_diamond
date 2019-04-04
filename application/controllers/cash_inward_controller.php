<?php
class Cash_inward_controller extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this -> load -> library('form_validation');
		//this libraru is used for validation of fields in a form
		$this->load->model('bill_model');
		$this->load->model('transaction_model');
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
	
	
	
	function cash_receipt_facade(){
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
			,'jobs/show_job_details'
			,'transaction/cash_receipt'
			
		);
		
		$css=array('ui-lightness/jquery-ui-1.10.2.custom'
					,'table_css/table/jquery.dataTables'
					,'jobs/show_job_details'
					,'message_box/Styles/msgBoxLight'
					,'transaction/cash_receipt'
					);	   //for data table jquery
		$modes[1]="Cash";
		$modes[2]="Cheque";
		
		$agents['AG2018']='Counter Agent';
		$main_data['user_id']=$this->session->userdata('user_id');
		$main_data['modes']=$modes;
		$main_data['agents']=$agents;
		$main_data['java_script']=$java_scripts;
		$main_data['css']=$css;
		$main_data['current_date']=get_current_date();
		$main_data['delivery_date']=date("d/m/Y",strtotime("+5 days"));
		//$main_data['user_key']=$this->session->userdata('ip_address').'-'.date_serial_number(get_current_date()).'-'.rand(1000,9999);
		$main_data['user_key']=uniqid('', true);
		$main_data['page_title']="Diamond New Order";
		$this -> load -> view('includes/staff/template', $this -> set_site_data('transaction/cash_receipt',$main_data));
	}
	function save_lc_received_from_customer(){
		$result=$this->transaction_model->insert_cash_received_from_customer();
		if($result['success']==1){
			echo '<div id="msg">';
			echo "Saved Successfully";
			echo '</div>';
			echo '<div id="error">noerror</div>';
			echo '<div id="report">';
			echo 'Transation ID ';
			echo '</div>';
		}else{
			echo '<div id="msg">';
			echo "Transaction Saving Error";
			echo '</div>';
			echo '<div id="error">error</div>';
			echo '<div id="report">';
			print_r($result);
			echo '</div>';
		}
	}
}
?>