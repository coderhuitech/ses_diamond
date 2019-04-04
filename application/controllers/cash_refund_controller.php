<?php
class Cash_refund_controller extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this -> load -> library('form_validation');
		//this libraru is used for validation of fields in a form
		$this->load->model('transaction_model');
		$this -> load -> model('Employee_model');
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
	
	function cash_refund_facade(){
		$main_data=array();
		//this block of code is mandatory for all facade from now
		//-------------------------------------------------------------------------
		// extention should not be given
		$java_scripts=array('UI2/jquery-ui-1.10.2.custom/js/jquery-ui-1.10.2.custom'
			,'general'
			,'table/jquery.dataTables.min'				// for data table
			,'popup/js/alertbox'					// for popup message
			,'popup/js/jquery.easing.1.3'
			,'message_box/Scripts/jquery.msgBox'
			,'transaction/cash_refund'
		);
		
		$css=array('ui-lightness/jquery-ui-1.10.2.custom'
			,'table_css/table/jquery.dataTables'		//for data table jquery
			,'UI2/jquery-ui-1.10.2.custom/css/ui-lightness/jquery-ui-1.10.2.custom'
			,'message_box/Styles/msgBoxLight'
			,'transaction/cah_refund'
		);
		$modes[1]="Cash";
		$modes[2]="Cheque";
		
		$golds=array();	
		$golds[36]="Fine Gold";
		$golds[48]="92 Gold";
		$main_data['modes']=$modes;
		$main_data['golds']=$golds;
		$main_data['java_script']=$java_scripts;
		$main_data['css']=$css;
		$main_data['page_title']="Diamond Receipt Gold";
		$main_data['current_date']=get_current_date();
		//--------------------------------------------------------------------------
		$this -> load -> view('includes/staff/template', $this -> set_site_data('transaction/cash_refund',$main_data));
	}
	function get_employees(){
		$user_id=$_GET['user_id'];
		$term=$_GET['term'];
		$result=$this->Employee_model->select_employees($term,$user_id);
		$row_array=array();
		$return_arr=array();
		if($result==NULL){
			$row_array['label']="No Employee exists";
			$row_array['emp_id']="";
			$row_array['balance']=0;
		}
		//$models=array();
		foreach($result->result() as $row){
			$row_array['label']=$row->emp_name;
			$row_array['emp_id']=$row->emp_id;
			$row_array['balance']=$row->balance;
			array_push($return_arr,$row_array);
		}
		echo json_encode($return_arr);
	}
	function save_refund_transaction(){
		$result=$this->transaction_model->insert_cash_refund_from_employee($_GET['user_id'],$_GET['emp_id'],$_GET['refund_amount'],$_GET['comment']);
		if($result['success']==1){
			echo '<div id="msg">';
			echo 'Successfully Recorded';
			echo '</br>'.anchor_popup(base_url().'/'.$result['transaction_id'],$result['transaction_id']);
			echo '</div>';
			echo '<div id="error">noerror</div>';
			echo '<div id="report">';
			echo '</br>'.anchor_popup(base_url().'index.php/cash_refund_controller/show_cash_refund_receipt?cte_no='.$result['transaction_id'],$result['transaction_id']);
			echo '</div>';	
		}else{
			echo '<div id="msg">';
			echo 'Refund Error';
			echo '</div>';
			echo '<div id="error">error</div>';
			echo '<div id="report">';
			echo 'Failed to save record';
			echo '</div>';	
		}
	}
	function show_cash_refund_receipt(){
		$result=$this->transaction_model->select_cash_refund_by_cte_no($_GET['cte_no']);
		if($result==NULL){
			echo "No record found";
			return;	
		}
		echo '<br>'.'Transaction No : '.$result->cash_transaction_id;
		echo '<br>'."Cash Received from ".$result->payer_name.' &#8377 '.$result->cash.' on '. date('M j Y g:i A', strtotime($result->tr_date));
		echo '</br></br></br></br></br>';
		echo $result->comment.'</br>';
		echo 'Received by</br></br>';
		echo '</br>';
		echo $result->payee_name;
		
	}
}
?>