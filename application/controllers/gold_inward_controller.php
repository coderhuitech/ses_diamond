<?php
class Gold_inward_controller extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this -> load -> library('form_validation');
		//this libraru is used for validation of fields in a form
		$this->load->model('bill_model');
		$this->load->model('transaction_model');
		$this->load->model('Customer_model');
		$this->load->model('Agent_model');
		$this->load->model('Material_model');
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
	
	function gold_receipt_facade(){
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
			,'transaction/gold_receipt'
			,'print_div/jQuery.print'
		);
		
		$css=array('ui-lightness/jquery-ui-1.10.2.custom'
			,'table_css/table/jquery.dataTables'		//for data table jquery
			,'UI2/jquery-ui-1.10.2.custom/css/ui-lightness/jquery-ui-1.10.2.custom'
			,'message_box/Styles/msgBoxLight'
			,'transaction/gold_receipt'
		);
		$agents=$this->Agent_model->select_agents();
		$agents_array=array();
		$agents_array['AG0000']="--";
		foreach($agents->result() as $agent){
			$agents_array[$agent->agent_id]=$agent->agent_name;
		}
		$modes[1]="Cash";
		$modes[2]="Cheque";
		
		$golds=array();	
		$golds[36]="Fine Gold";
		$golds[48]="92 Gold";
		$golds[42]="90 Gold";
		$main_data['agents']=$agents_array;
		$main_data['modes']=$modes;
		$main_data['golds']=$golds;
		$main_data['java_script']=$java_scripts;
		$main_data['css']=$css;
		$main_data['page_title']="Diamond Receipt Gold";
		$main_data['current_date']=get_current_date();
		//--------------------------------------------------------------------------
		$this -> load -> view('includes/staff/template', $this -> set_site_data('transaction/gold_receipt',$main_data));
	}
	function get_customers_by_agent_id(){
		$result=$this->Agent_model->select_customers_by_agent_id($_GET['agent_id']);
		if($result==NULL){
			echo "No Customer found";
			return;
		}
		$customers=array();
		$customers['S0000']="--";
		foreach($result->result() as $row){
			$customers[$row->cust_id]=$row->cust_name;
		}
		echo 'Customer Name: ';
		echo form_dropdown('cust_id',$customers,'S0000','id="customer-id"');
	}
	function get_customers(){
		$result=$this->Customer_model->select_customers($_GET['term'],"");
		$row_array=array();
		$return_arr=array();
		if($result==NULL){
			$row_array['label']="No Customer exists";
			$row_array['cust_id']="";
		}
		//$models=array();
		foreach($result->result() as $row){
			$row_array['label']=$row->cust_name;
			$row_array['cust_id']=$row->cust_id;
			array_push($return_arr,$row_array);
		}
		echo json_encode($return_arr);
	}
	function get_agent_by_cust_id(){
		$result=$this->Customer_model->select_agent_by_cust_id($_GET['cust_id'],"");
		$row_array=array();
		$return_arr=array();
		if($result==NULL){
			$row_array['label']="No Agent exists";
			$row_array['agent_id']="";
		}
		//$models=array();
		foreach($result->result() as $row){
			$row_array['label']=$row->cust_name;
			$row_array['cust_id']=$row->cust_id;
			array_push($return_arr,$row_array);
		}
		echo json_encode($return_arr);
	}
	function get_gold_percent_by_id(){
		$material_id=$_GET['gold_id'];
		$result=$this->Material_model->get_material_by_id($material_id);
		if($material_id==36){
			echo '<span id="gini-gold">'. number_format(round($_GET['gold']*(100/92),3),3,'.','').'</span>'.'g. 92 Gold';
			echo ' and <span id="fine-gold">'. number_format(round($_GET['gold'],3),3,'.','').'</span>'.'g. Fine Gold';
		}
		if($material_id==48){
			echo '<span id="fine-gold">'. number_format(round($_GET['gold']*(92/100),3),3,'.','').'</span>'.'g. Fine Gold';
			echo ' and <span id="gini-gold">'. number_format(round($_GET['gold'],3),3,'.','').'</span>'.'g. 92 Gold';
		}
		if($material_id==42){
			echo '<span id="fine-gold">'. number_format(round($_GET['gold']*(90/100),3),3,'.','').'</span>'.'g. Fine Gold';
			echo ' and <span id="gini-gold">'. number_format(round($_GET['gold'],3),3,'.','').'</span>'.'g. 90 Gold';
		}
	}
	function save_gold_receipt(){
		$result=$this->transaction_model->insert_gold_receipt_from_customer();
		$row_array=array();
		$return_arr=array();
		if($result['success']==0){
			$row_array['success']=0;
			$row_array['msg']=$result['error'];
			$row_array['sql']=$result['sql'];
			$row_array['message_type']='error';
			array_push($return_arr,$row_array);
			echo json_encode($return_arr);
			return;
		}

		$row_array['success']=1;
		$row_array['receipt_no']=$result['receipt_no'];
		$row_array['msg']="Gold Receipt Completed";
		$row_array['sql']="";
		$row_array['message_type']='information';
		array_push($return_arr,$row_array);
		echo json_encode($return_arr);
	}
	
	function get_customer_balance_by_id(){
		
		$cust_id=$_GET['cust_id'];
		$result=$this->Customer_model->select_customer_balance_by_id($cust_id);
		if($result==NULL){
			echo ' Gold: <span id="last-gold-due">0</span>gram and LC: <span id="last-lc-due">0</span>';
		}else{
			echo ' Gold: <span id="last-gold-due">'.round($result->gold_due,3).'</span>gram and LC:  <span id="last-lc-due">'.round($result->lc_due,2).'</span>';
		}
	}
	function display_gold_inward_receipt_by_id(){
		$result=$this->transaction_model->select_gold_receipt_record_by_receipt_id($_GET['gold_receipt_no']);
		echo 'Fine gold Received from :'.'</br>';
		echo $result->mailing_name.'</br>';
		echo $result->cust_address.'</br>';
		echo $result->cust_phone.'</br>';
		echo 'Receipt ID : '.$result->gold_receipt_id.'</br>';
		echo 'Receipt Date : '.$result->receipt_date.'</br>';
		echo '___________________________________';
		$this->load->library('table');
		$this->table->add_row(	  cell_format('Last Balance','text','column1 left')
								  ,cell_format($result->last_gold_balance,'gold','gold_balance right')
								  ,cell_format($result->last_lc_balance,'currency','lc_balance right')
								  );
		$this->table->add_row(	  cell_format('Less: Receipt','text','column1')
								  ,cell_format($result->gold_value,'gold','gold_balance right')
								  ,cell_format(0,'currency','lc_balance right')
								  );
		$this->table->set_heading(cell_format('Particulars','text','column1 center')
								  ,cell_format('Gold','text','gold_balance center')
								  ,cell_format('LC','text','lc_balance center')
								  );						  
		$this->table->set_footer(cell_format('Current Balance','text','column1')
								  ,cell_format($result->current_gold_balance,'gold','gold_balance right')
								  ,cell_format($result->current_lc_balance,'currency','lc_balance right')
								  );
		$this->table->set_template(default_table_template('id="gold-receipt-table"'));
		echo $this->table->generate();
	}
}
?>