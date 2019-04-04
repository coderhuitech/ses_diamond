<?php
class New_transaction_controller extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this -> load -> library('form_validation');
		//this libraru is used for validation of fields in a form
		$this->load->model('Customer_model');
		$this->load->model('Employee_model');
		$this->load->model('Report_model');
		$this->load->model('Agent_model');
		$this->load->model('transaction_model');
		// this model for menus and navs
		//$this -> is_logged_in();
		
		$this -> load -> helper(array('datagrid', 'url','huiui','html','download'));
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
	
	
	
	function new_transaction_facade(){
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
			,'transaction/cash_receipt'
			,'transaction/new_gold_receipt'
			
		);
		$css=array('ui-lightness/jquery-ui-1.10.2.custom'
					,'table_css/table/jquery.dataTables'
					,'message_box/Styles/msgBoxLight'
					,'transaction/cash_receipt'
					//,'transaction/new_gold_receipt'
					);	   //for data table jquery
		
		$main_data['java_script']=$java_scripts;
		$main_data['css']=$css;
		$main_data['current_date']=get_current_date();
		$main_data['page_title']="Admin Report";
		$this -> load -> view('includes/staff/template', $this -> set_site_data('transaction/new_transaction_view',$main_data));
	}
	function lc_receipt_facade(){
		$agents=$this->Agent_model->select_agents();
		$agents_array=array();
		$agents_array['AG0000']="--";
		foreach($agents->result() as $agent){
			$agents_array[$agent->agent_id]=$agent->agent_name;
		}
		?>
		<div id="lc-receipt-div">
		<label>Agent</label>
		<?php echo form_dropdown('agent_id',$agents_array,'AG0000','id=agent-id');?>
		<div id="customer-div"></div>
		</div>
		<?php
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
		start_div('id="due-div"');
		end_div();
	}
	function get_customer_lc_due_details_by_cust_id(){
		echo 'Customer ID: '.$_GET['customer_id'];
		$result=$this->Customer_model->select_customer_balance_by_id($_GET['customer_id']);
		
		if($result==NULL){
			echo '</br>Gold: <span id="last-gold-due">0</span>gram</br>';
			echo '<label>LC Due : '.form_input('lc_due','0','class="right" id="lc-due" readonly="yes"');
		}else{
			echo '</br>Gold <span id="last-gold-due">'.round($result->gold_due,3).'</span>gram</br>';
			echo '<label>LC Due</label>'.form_input('lc_due',round($result->lc_due,2),'class="right" id="lc-due" readonly="yes"');
		}
		echo '</br><label>LC Received</label>'.form_input('lc_due',0,'class="right" id="lc-received"');
		echo '</br><label>Due after</label>'.form_input('lc_due',0,'class="right" id="due-after" readonly="yes"');
		echo '</br>'.form_button('lc_submit','Submit','id="lc-submit"');
		start_div('id="lc-receipt-report-div"');
		end_div();
	}
	function save_lc_received(){
		$result=$this->transaction_model->insert_cash_received_from_customer();
		$row_array=array();
		$return_arr=array();
		if($result['success']==0){
			$row_array['success']=0;
			$row_array['msg']=$result['error'];
			$row_array['sql']=$result['sql'];
			$row_array['message_type']='error';
			$row_array['transaction_no']='No Transaction No';
			array_push($return_arr,$row_array);
			echo json_encode($return_arr);
			return;
		}
		$row_array['transaction_no']=$result['transaction_no'];
		$row_array['success']=1;
		$row_array['msg']="Transaction recorded successfully";
		$row_array['sql']="";
		$row_array['message_type']='information';
		array_push($return_arr,$row_array);
		echo json_encode($return_arr);
	}
	function gold_receipt_facade(){
		$agents=$this->Agent_model->select_agents();
		$agents_array=array();
		$agents_array['AG0000']="--";
		foreach($agents->result() as $agent){
			$agents_array[$agent->agent_id]=$agent->agent_name;
		}
		?>
		<div id="gold-receipt-div">
			<label>Agent</label>
			<?php echo form_dropdown('agent_id',$agents_array,'AG0000','id=agent-id');?>
			<div id="customer-div"></div>
		</div>
		<?php
	}
	function get_customer_gold_due(){
		$golds=array();	
		$golds[36]="Fine Gold";
		$golds[48]="92 Gold";
		$golds[42]="90 Gold";
		echo 'Customer ID: '.$_GET['customer_id'];
		$result=$this->Customer_model->select_customer_balance_by_id($_GET['customer_id']);
		?>
			<style type="text/css">
				#1001 label{
					width: 200px;
				}
				#1001 .right{
					text-align: right;
				}
				#submit-gold-received{
					margin-left: 225px;
				}
				#gold-received{
					background-color: #ecf7f9;
				}
				
			</style>
			<div id="1001">
				<br><label>LC Due</label>
				<input type="text" id="lc-due" value="<?php echo number_format($result->lc_due,2); ?>" placeholder="0.00" readonly="yes"  class="right"/>
				
				<br><label>Gold Due</label>
				<input type="text" id="gold-due" value="<?php echo number_format($result->gold_due,3); ?>" placeholder="0.000" readonly="yes"  class="right"/><span> Fine</span>
				<br><label>Gold Receipt</label>
				<input type="number" id="gold-received" value="0.000"  placeholder="0.000" step="0.001" class="right"/><span> Fine</span>
				<br><label>Gold Due</label>
				<input type="text" id="next-gold-due" value="0.000" placeholder="0.000" readonly="yes" class="right"/><span> Fine</span><br>
				<input type="submit" value="Submit" name="submit" id="submit-gold-received" />
				
			</div>
			<!--<div id="cust-balance">Customer Balance</div>
			<div>
				<div id="gold-div">
					<label for="">Gold </label>
					<input type="text" class="number" id="gold" value="0"/>
					<?php echo form_dropdown('gold_type',$golds,2,'id="gold-id"') ;?>
					<span id="gold-text">text</span>
				</div>
				<input type="button" value="Submit" id="submit"/>
				
			</div>-->
		<?php
	}
	function save_gold_receipt(){
		//print_r($_GET);
		$result=$this->transaction_model->insert_gold_receipt_from_customer();
		$row_array=array();
		$return_arr=array();
		if($result['success']==0){
			$row_array['success']=0;
			$row_array['receipt_no']='xx/xxxxxx/xxxx';
			$row_array['msg']=$result['error'];
			$row_array['sql']='';
			$row_array['message_type']='error';
			array_push($return_arr,$row_array);
			echo json_encode($return_arr);
			return;
		}

		$row_array['success']=1;
		$row_array['receipt_no']=$result['receipt_no'];
		$row_array['msg']="Gold Receipt Completed";
		$row_array['sql']="";
		$row_array['message_type']='info';
		array_push($return_arr,$row_array);
		echo json_encode($return_arr);
	}
}
?>