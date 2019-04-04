<?php
class Customer_controller extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this -> load -> library('form_validation');
		//this libraru is used for validation of fields in a form
		$this -> load -> model('order_model');
		$this -> load -> model('customer_model');
		$this->load->model('main_model');
		$this->load->model('Report_model');
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
	
	
	
	function customer_master_facade(){
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
			,'customers/customer_master'
			
		);
		
		$css=array('transaction/capital/gold_introduce'
					,'ui-lightness/jquery-ui-1.10.2.custom'
					,'table_css/table/jquery.dataTables'
					,'customers/customer_master'
					,'message_box/Styles/msgBoxLight'
					);	   //for data table jquery
		$agents['AG2018']='Counter Agent';
		$main_data['user_id']=$this->session->userdata('user_id');
		$main_data['agents']=$agents;
		$main_data['java_script']=$java_scripts;
		$main_data['css']=$css;
		$main_data['current_date']=get_current_date();
		$main_data['delivery_date']=date("d/m/Y",strtotime("+5 days"));
		//$main_data['user_key']=$this->session->userdata('ip_address').'-'.date_serial_number(get_current_date()).'-'.rand(1000,9999);
		$main_data['user_key']=uniqid('', true);
		$main_data['page_title']="Diamond New Order";
		$this -> load -> view('includes/staff/template', $this -> set_site_data('customers/customer_master_view',$main_data));
	}
	function add_customer_view(){
		$result=$this->db->query("select right(cust_id,4) as last_id from customer_master order by RIGHT(cust_id, 4) desc limit 1")->row();
		$x=0;
		$x=$result->last_id;
		$x++;
		echo '<div id="new-customer">';
			echo '<h1>Add New Customer</h1>';
			echo '<div>';
				echo form_label('Customer ID: ');
				echo '<input type="text" id="cust-id" placeholder="ID" value="'.'S'.$x.'"  title="Enter Customer Unique ID" required="yes"/>';
				echo '<input type="button" id="check-cust-id" title="Search Customer" value="Search" />'; 
			echo '</div>';
			
			echo '<div>';
				echo form_label('Customer : ');
				echo '<input type="text" id="cust-name" placeholder="Customer Name" title="Enter Customer Name" required="yes"/>';
			echo '</div>';
			echo '<div>';
				echo form_label('Mailing Name : ');
				echo '<input type="text" id="mailing-name" placeholder="Mailing Name" title="Enter Customer Mailing Name" required="yes"/>';
			echo '</div>';
			echo '<div>';
				echo form_label('Address : ');
				echo '<textarea  id="address" placeholder="Address" title="Enter Address" required="yes">Barrackpore</textarea>';
			echo '</div>';
			
			echo '<div>';
				echo form_label('City : ');
				echo '<input type="text" id="city" placeholder="City" title="Enter Customer City" required="yes"/>';
			echo '</div>';
			
			echo '<div>';
				echo form_label('Phone : ');
				echo '<input type="text" id="phone" placeholder="Phone" title="Enter Customer Phone" required="yes"/>';
			echo '</div>';
			echo '<div>';
				echo form_label('Initial : ');
				echo '<input type="text" id="initial" placeholder="Initial" title="Enter Customer Initial" required="yes"/>';
			echo '</div>';
			echo '<div>';
				echo form_label('Opening Gold : ');
				echo '<input type="text" value="0" id="op-gold" placeholder="Opening Gold" title="Enter Opening Gold" required="yes"/>';
			echo '</div>';
			echo '<div>';
				echo form_label('Opening LC : ');
				echo '<input type="text" value="0" id="op-lc" placeholder="Opening LC" title="Enter Opening LC" required="yes"/>';
			echo '</div>';
			/*echo '<div>';
				echo form_label('Security1 : ');
				$x=rand()%100;
				$y=rand()%1000;
				$p=max($x,$y);
				$q=min($x,$y);
				echo '<input type="text" id="security1" value="'.$p. '" class="security" readonly="yes"/>';
				echo form_label('Security2 : ');
				echo '<input type="text" id="security2" value="'.$q. '" class="security" readonly="yes"/>';
				echo form_label('Admin Code : ');
				echo '<input type="text" id="security3" class="security" required="yes"/>';
			echo '</div>';*/
			echo '<div id="submit-div">';
				echo '<input type="submit" id="customer-submit" title="Submit Customer" value="Submit" />';
			echo '</div>';
			
			
		echo '</div';
	}
	function add_customer_ajax(){
		$result=$this->customer_model->add_fresh_customer();
		
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
		$row_array['msg']="Customer Inserted";
		$row_array['sql']="";
		$row_array['message_type']='information';
		array_push($return_arr,$row_array);
		echo json_encode($return_arr);
	}
	function set_customer_limit_view(){
		if(!($this->session->userdata('employee_id')==28 || $this->session->userdata('employee_id')==58)){
			echo "<h1>You are not authorised</h1>";
			return;
		}
		echo '<div id="customer-limit">';
			echo '<h1>Set Customer Limit</h1>';
			echo '<div>';
				echo form_label('Customer ID: ');
				echo '<input type="text" id="set-limit-cust-id" placeholder="ID" value="" title="Enter Customer Unique ID" required="yes"/>';
			echo '</div>';
			
			echo '<div>';
				echo form_label('Customer : ');
				echo '<input type="text" id="cust-name" placeholder="Customer Name" title="Enter Customer Name" required="yes"/>';
			echo '</div>';
			echo '<div>';
				echo form_label('Mailing Name : ');
				echo '<input type="text" id="mailing-name" placeholder="Mailing Name" title="Enter Customer Mailing Name" required="yes"/>';
			echo '</div>';
			echo '<div>';
				echo form_label('Address : ');
				echo '<textarea  id="address" placeholder="Address" title="Enter Address" required="yes">Barrackpore</textarea>';
			echo '</div>';
			echo '<div>';
				echo form_label('Phone : ');
				echo '<input type="text" id="phone" placeholder="Phone" title="Enter Customer Phone" required="yes"/>';
			echo '</div>';
			echo '<div>';
				echo form_label('Initial : ');
				echo '<input type="text" id="initial" placeholder="Initial" title="Enter Customer Initial" required="yes"/>';
			echo '</div>';
			echo '<div>';
				echo form_label('Gold Limit : ');
				echo '<input type="text" id="gold-limit" placeholder="Gold Limit" title="Enter Gold Limit" required="yes"/>';
			echo '</div>';
			
			echo '<div>';
				echo form_label('Cash Limit : ');
				echo '<input type="text" id="cash-limit" placeholder="Cash Limit" title="Enter Cash Limit" required="yes"/>';
			echo '</div>';
			
			echo '<div>';
				echo form_label('MV : ');
				echo '<input type="text" id="mv" placeholder="MV" title="Enter MV" required="yes"/>';
			echo '</div>';
		
			echo '<div>';
				echo '<input type="submit" id="customer-update-submit" title="Submit Customer" value="Submit" />';
			echo '</div>';
			
		echo '</div';
	}
	function get_customer_by_id(){
		$result=$this->customer_model->get_customer_by_cust_id($_GET['cust_id']);
		$row_array=array();
		$return_arr=array();
		if($result==NULL){
			$row_array['success']=0;
			$row_array['cust_id']="";
			$row_array['cust_name']="";
			$row_array['mailing_name']="";
			$row_array['cust_address']="";
			$row_array['city']="";
			$row_array['phone']='';
			$row_array['initial']='';
			$row_array['gold_limit']='';
			$row_array['cash-limit']='';
			$row_array['mv']='';
			array_push($return_arr,$row_array);
			echo json_encode($return_arr);
			return;
		}

		$row_array['success']=1;
		$row_array['cust_id']=$result->cust_id;
		$row_array['cust_name']=$result->cust_name;
		$row_array['mailing_name']=$result->mailing_name;
		$row_array['cust_address']=$result->cust_address;
		$row_array['city']=$result->city;
		$row_array['phone']=$result->cust_phone;
		$row_array['initial']=$result->short_name;
		$row_array['gold_limit']=$result->gold_limit;
		$row_array['cash_limit']=$result->cash_limit;
		$row_array['opening_gold']=$result->opening_gold;
		$row_array['opening_lc']=$result->opening_lc;
		$row_array['mv']=$result->markup_value;
		array_push($return_arr,$row_array);
		echo json_encode($return_arr);
	}
	function update_customer_ajax(){
		$result=$this->customer_model->update_existing_customer();
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
		$row_array['msg']="Customer Updated";
		$row_array['sql']="";
		$row_array['message_type']='information';
		array_push($return_arr,$row_array);
		echo json_encode($return_arr);
	}
	function set_agent_to_customer_view(){
		echo '<div id="agent-to-customer-div">';
		echo '<div>';
		echo form_label("Customer ID");
		echo form_input('customer_id','','placeholder="CUST ID" id="cust-id" required="yes"');
		
		echo '</div>';
		echo '<div>';
		echo form_label("Agent ID");
		$result=$this->Report_model->select_agents();
		$agents['AG000']="All";
		foreach($result->result() as $row){
			$agents[$row->agent_id]=$row->short_name;
		}
		
		echo 'Select Agent : '.form_dropdown('agents',$agents,'0','id="agent-id"');
		//echo form_input('agent_id','','placeholder="AGENT ID" id="agent-id" required="yes"');
		echo '</div>';
		echo '<div>';
		echo form_button('submit','Set','id="submit"');
		echo '</div>';
		echo '</div>';
	}
	
	function add_agent_to_customer_ajax(){
		$result=$this->customer_model->set_agent_to_customer($_GET['cust_id'],$_GET['agent_id']);
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
		$row_array['msg']="Agent Set";
		$row_array['sql']="";
		$row_array['message_type']='information';
		array_push($return_arr,$row_array);
		echo json_encode($return_arr);
	}
	/*function get_customer_by_id(){
		$result=$this->customer_model->select_customer_by_id($_GET['cust_id']);
		$row_array=array();
		$return_arr=array();
		if($result!=NULL){
			$row_array['agent_name']="no such customer";
			$row_array['city']="";
			$row_array['cust_phone']="";
			array_push($return_arr,$row_array);
			echo json_encode($return_arr);
			return;
		}
		$row_array['agent_name']=$result->cust_name;
		$row_array['city']=$result->city;
		$row_array['cust_phone']=$result->cust_phone;
		array_push($return_arr,$row_array);
		echo json_encode($return_arr);
	}*/
}
?>