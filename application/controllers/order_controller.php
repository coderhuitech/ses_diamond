<?php
class Order_controller extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this -> load -> library('form_validation');
		//this libraru is used for validation of fields in a form
		$this -> load -> model('order_model');
		$this->load->model('main_model');
		$this->load->model('Report_model');
		$this->load->model('Customer_model');
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
	
	
	
	function new_order_facade(){
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
			,'orders/orders'
			
		);
		
		$css=array('transaction/capital/gold_introduce'
					,'ui-lightness/jquery-ui-1.10.2.custom'
					,'table_css/table/jquery.dataTables'
					,'orders/orders'
					,'message_box/Styles/msgBoxLight'
					);	   //for data table jquery
		$result=$this->Report_model->select_agents();
		$all_agents['AG000']="--Select--";
		foreach($result->result() as $row){
			$all_agents[$row->agent_id]=$row->short_name;
		}
		$main_data['all_agents']=$all_agents;
		$agents['AG2018']='Counter Agent';
		$main_data['user_id']=$this->session->userdata('user_id');
		$main_data['agents']=$agents;
		$main_data['java_script']=$java_scripts;
		$main_data['css']=$css;
		$main_data['current_date']=get_current_date();
		$main_data['delivery_date']=date("d/m/Y",strtotime("+5 days"));
		$result=$this->order_model->select_gold_id_for_order();
		$gold=array();
		foreach($result->result() as $row){
			$gold[$row->key]=$row->value;
		}
		
		$main_data['gold']=$gold;
		$result=$this->order_model->select_default_gold_id_for_order();
		$main_data['default_gold_id']=$result->key;
		
		//$main_data['user_key']=$this->session->userdata('ip_address').'-'.date_serial_number(get_current_date()).'-'.rand(1000,9999);
		$main_data['user_key']=uniqid('', true);
		$main_data['page_title']="Diamond New Order";
		$this -> load -> view('includes/staff/template', $this -> set_site_data('orders/new_order',$main_data));
	}
	function get_all_models(){
		$result=$this->main_model->select_products($_GET['term']);
		$row_array=array();
		$return_arr=array();
		if($result==NULL){
			$row_array['label']="No model exists";
			$row_array['value']="No Description";
			$row_array['value']="";
		}
		
		
		//$models=array();
		foreach($result->result() as $row){
			$row_array['label']=$row->product_code.'-'.$row->price_code;
			$row_array['label2']=$row->product_description;
			$row_array['value']=$row->product_code;
			array_push($return_arr,$row_array);
		}
		echo json_encode($return_arr);
	}
	function get_all_bill_inforced_customers(){
		$result=$this->main_model->select_bill_inforced_customers($_GET['term']);
		$row_array=array();
		$return_arr=array();
		if($result==NULL){
			$row_array['label']="No Customer exists";
			$row_array['value']="";
		}
		
		
		//$models=array();
		foreach($result->result() as $row){
			$row_array['label']=$row->cust_name;
			$row_array['value']=$row->cust_id;
			array_push($return_arr,$row_array);
		}
		echo json_encode($return_arr);
	}
	function add_item_to_temp_order(){
		$this -> form_validation -> set_rules('customer_id', 'Customer', 'trim|required|is_exists[customer_master.cust_id]');
		$this -> form_validation -> set_rules('model_no', 'Model No.', 'trim|required|is_exists[product_master.product_code]');
		$this -> form_validation -> set_rules('model_size', 'Size', 'trim|required');
		$this -> form_validation -> set_rules('appx_gold', 'Gold', 'trim|required|numeric');
		$this -> form_validation -> set_rules('qty', 'Qty', 'trim|required|numeric');
		//$this -> form_validation -> set_rules('description', 'Description', 'trim|required');
		
		$this -> form_validation -> set_error_delimiters('<div class="error">', '</div>');
		if ($this -> form_validation -> run() == FALSE) {
			echo '<div id="msg">';
			echo validation_errors();
			echo '</div>';
			echo '<div id="error">error</div>';
			echo '<div id="report">';
			echo 'No Report';
			print_r($_POST);
			echo '</div>';
			return;
		}
		$result=$this->order_model->insert_items_to_temp_order();
		$this->load->library('table');
		if($result['table1']==NULL){
			$this->table->set_template(default_table_template());
		}else{
			$sl=0;
			$gold=0;
			$qty=0;
			foreach($result['table1']->result() as $row){
				$this->table->add_row(cell_format(++$sl,'text')
									  ,cell_format($row->product_code,'text')
									  ,cell_format($row->price_code,'text')
									  ,cell_format($row->product_size,'text')
									  ,cell_format($row->gold_weight,'gold')
									  ,cell_format($row->qty,'integer')
									  //,cell_format('Remove','text','text remove',$row->temp_order_id)
									  ,img(array('src'=>'img/cross_round_small.png','id'=>$row->temp_order_id,'class'=>'no_print remove','height'=>'15px','border'=>'0','alt'=>'Remove')).' '.img(array('src'=>'img/edit.jpg','id'=>$row->temp_order_id,'class'=>'no_print edit','height'=>'15px','border'=>'0','alt'=>'Edit'))
									  ,anchor_popup(base_url().'img/products/'.$row->product_code.'.jpg',img(array('src'=>'img/products/'.$row->product_code.'.jpg','class'=>'no_print','height'=>'25px','border'=>'0','alt'=>'No Image')))
									 );
				if(is_even($sl)){
					$this->table->add_row_class('even');
				}else{
					$this->table->add_row_class('odd');
				}
				
				$gold+=$row->gold_weight;
				$qty+=$row->qty;
			}
			$this->table->set_heading('SL','Model','Price Code','Size','Gold','Qty','Action','Sample');
			$this->table->set_footer(' ','Total',' ' ,' ',cell_format($gold,'gold'),$qty,' ','Sample');
		}
		$this->table->set_template(default_table_template('class="dataTable" id="temp_order_table"'));
		if($result['affected_rows']<0){
			echo '<div id="msg">';
			echo $result['msg'];
			echo '</div>';
			echo '<div id="error">error</div>';
			echo '<div id="report">';
			echo $this->table->generate();
			echo '</div>';
		}elseif($result['affected_rows']==1){
			echo '<div id="msg">';
			echo "Item in the list successfully";
			echo '</div>';
			echo '<div id="error">noerror</div>';
			echo '<div id="report">';
			echo $this->table->generate();
			echo '</div>';
		}else{
			echo '<div id="msg">';
			echo $result['msg'];
			echo '</div>';
			echo '<div id="error">error</div>';
			echo '<div id="report">';
			echo $this->table->generate();
			echo '</div>';
		}
	}
	function delete_record_by_temp_order_id(){
		$result=$this->order_model->delete_from_temp_orders_by_temp_order_id($_GET['temp_order_id'],$_GET['user_key']);
		$this->load->library('table');
		if($result['table1']==NULL){
			$this->table->set_template(default_table_template());
		}else{
			$sl=0;
			$gold=0;
			$qty=0;
			foreach($result['table1']->result() as $row){
				$this->table->add_row(cell_format(++$sl,'text')
									  ,cell_format($row->product_code,'text')
									  ,cell_format($row->price_code,'text')
									  ,cell_format($row->product_size,'text')
									  ,cell_format($row->gold_weight,'gold')
									  ,cell_format($row->qty,'integer')
									  ,img(array('src'=>'img/cross_round_small.png','id'=>$row->temp_order_id,'class'=>'no_print remove','height'=>'15px','border'=>'0','alt'=>'Remove')).' '.img(array('src'=>'img/edit.jpg','id'=>$row->temp_order_id,'class'=>'no_print edit','height'=>'15px','border'=>'0','alt'=>'Edit'))
									  ,anchor_popup(base_url().'img/products/'.$row->product_code.'.jpg',img(array('src'=>'img/products/'.$row->product_code.'.jpg','class'=>'no_print','height'=>'25px','border'=>'0','alt'=>'No Image')))
									  
									 );
				if(is_even($sl)){
					$this->table->add_row_class('even');
				}else{
					$this->table->add_row_class('odd');
				}
				
				$gold+=$row->gold_weight;
				$qty+=$row->qty;
			}
			$this->table->set_heading('SL','Model','Price Code','Size','Gold','Qty','Action','Sample');
			$this->table->set_footer(' ','Total',' ' ,' ',cell_format($gold,'gold'),$qty,' ','Sample');
		}
		$this->table->set_template(default_table_template('class="dataTable" id="temp_order_table"'));
		if($result['affected_rows']<0){
			echo '<div id="msg">';
			echo $result['msg'];
			echo '</div>';
			echo '<div id="error">error</div>';
			echo '<div id="report">';
			echo $this->table->generate();
			echo '</div>';
		}elseif($result['affected_rows']==1){
			echo '<div id="msg">';
			echo "Item deleted from the list successfully";
			echo '</div>';
			echo '<div id="error">noerror</div>';
			echo '<div id="report">';
			echo $this->table->generate();
			echo '</div>';
		}else{
			echo '<div id="msg">';
			echo $result['msg'];
			echo '</div>';
			echo '<div id="error">error</div>';
			echo '<div id="report">';
			echo $this->table->generate();
			echo '</div>';
		}
	}
	
	function save_order(){
		$this -> form_validation -> set_rules('customer_id', 'Customer', 'trim|required|is_exists[customer_master.cust_id]');
		$this -> form_validation -> set_error_delimiters('<div class="error">', '</div>');
		if ($this -> form_validation -> run() == FALSE) {
			echo '<div id="msg">';
			echo validation_errors();
			echo '</div>';
			echo '<div id="error">error</div>';
			echo '<div id="report">';
			echo 'No Report';
			echo '</div>';
			return;
		}
		//if no error save the order to order_master and order_details
		$result=$this->order_model->save_orders();
		if($result['success']==1){
			echo '<div id="msg">';
			echo 'Record saved successfully';
			echo '</div>';
			echo '<div id="error">noerror</div>';
			echo '<div id="report">';
			//echo 'Order recorded successfully, Order No. '.form_input('order_id',set_value('order_id',$result['order_id']));
			//echo form_button('show','Show Order','id="show-order"');
			echo "Order Saved : Order No. " .anchor_popup(site_url().'/order_controller/show_order_by_order_no?order_id='.$result['order_id'],$result['order_id']);
			echo '</div>';
		}else{
			echo '<div id="msg">';
			echo 'Error Saving Order';
			echo $result['err_msg'];
			echo '</div>';
			echo '<div id="error">error</div>';
			echo '<div id="report">';
			echo print_r($result);
			echo '</div>';
		}
		
	}
	function get_item_from_temp_order(){
		$temp_order_id=$_GET['temp_order_id'];
		$result_array=$this->order_model->select_item_from_temp_order_by_id($temp_order_id);
		$result=$result_array['result'];
		if($result==NULL){
			echo '<div id="msg">';
			echo $result_array['sql'];
			echo '</div>';
			echo '<div id="error">error</div>';
			echo '<div id="report">';
				echo form_input('model_no',set_value('model_no',$result->product_code),'id="model-no" title="Model" placeholder="Model No" required="yes"') ;
				echo form_input('model_size',set_value('model_size',$result->product_size),'id="model-size" title="Model Size" placeholder="Size" required="yes"') ;
				echo form_input('appx_gold',set_value('appx_gold',$result->gold_weight),'id="appx-gold" title="Total Gold" class="numericOnly gold-input" placeholder="Approx Gold" required="yes"') ;
				echo form_input('qty',set_value('qty',$result->qty),'id="qty" placeholder="QTY" title="Qty" class="integerOnly" required="yes"') ;
				echo form_input('description',set_value('description'),'id="description" placeholder="Description" title="Enter Description Here" class=""') ;
			echo '</div>';
		}else{
			echo '<div id="msg">';
			echo 'Selected for edit';
			echo '</div>';
			echo '<div id="error">noerror</div>';
			echo '<div id="report">';
				echo form_input('model_no',set_value('model_no',$result->product_code),'id="model-no" title="Model" placeholder="Model No" required="yes"') ;
				echo form_input('model_size',set_value('model_size',$result->product_size),'id="model-size" title="Model Size" placeholder="Size" required="yes"') ;
				echo form_input('appx_gold',set_value('appx_gold',$result->gold_weight),'id="appx-gold" title="Total Gold" class="numericOnly gold-input" placeholder="Approx Gold" required="yes"') ;
				echo form_input('qty',set_value('qty',$result->qty),'id="qty" placeholder="QTY" title="Qty" class="integerOnly" required="yes"') ;
				echo form_input('description',set_value('description'),'id="description" placeholder="Description" title="Enter Description Here" class=""') ;
			echo '</div>';
		}
	}
	
	function show_order_by_order_no(){
		$main_data=array();
		$order_id=$_GET['order_id'];
		$company=$this->db->query("select * from company_details")->row();
		$main_data['company_details']=$company;
		$result=$this->order_model->get_order_master_by_order_id($order_id);
		if($result==NULL){
			echo "Error fetching order";
			return;
		}else{
			$this->load->library('table');
			$this->table->add_row(cell_format('Order No.','text','column1')
								  ,cell_format($result->order_id,'text','column2')
								  ,cell_format(' ','text','column3')
								  ,cell_format('Order Date','text','column4')
								  ,cell_format(sql_date_to_dmy($result->order_date),'text','column5')
								  );
			$this->table->add_row(cell_format('Agent:&nbsp','text','column1')
								 // ,cell_format($result->agent_name,'text','column2')
								  ,cell_format('XXXXXXX','text','column2')
								   ,cell_format(' ','text','column3')
								  ,cell_format('Delivery Date','text','column4')
								  ,cell_format(sql_date_to_dmy($result->delivery_date),'text','column5')
								  );
			$this->table->set_template(default_table_template('id="order_master" class="dataTables"'));
			$this->table->set_caption('Customer : <b>'.$result->cust_name.'</b>');
			$main_data['order_master']=$this->table->generate();
			$this->table->clear();
		}
		
		$result=$this->order_model->get_order_details_by_order_id($order_id);
		$total_gold=0;
		$qty=0;
		$lc=0;
		foreach($result->result() as $row){
			$this->table->add_row(cell_format($row->sl_no,'text','column1')
								  ,cell_format($row->product_code.'-'.$row->price_code,'text','column2')
								  ,cell_format($row->prd_size,'text','column3')
								  ,cell_format($row->rm_name,'text','column3')
								  ,cell_format($row->qty,'integer','column4')
								  ,cell_format($row->gold_wt,'gold','column5')
								  ,cell_format($row->price*$row->qty,'currency','column6')
								 );
			$total_gold+=$row->gold_wt;
			$qty+=$row->qty;
			$lc+=$row->price*$row->qty;
		}
		$this->table->set_template(default_table_template('id="order_details" class="dataTables"'));
		$this->table->set_caption('');
		$this->table->set_heading('SL','Model','Size','Gold'
									,cell_format('Qty','text','column4')
									,cell_format('Appx. Gold','text','column5')
									,cell_format('L/C','text','column6'));
		$this->table->set_footer('','Total','',''
									,cell_format($qty,'integer','column4')
									,cell_format($total_gold,'gold','column5')
									,cell_format($lc,'currency','column6'));
		$main_data['order_details']=$this->table->generate();
		$this->table->clear();
		
		$java_scripts=array('UI2/jquery-ui-1.10.2.custom/js/jquery-ui-1.10.2.custom'
			,'general'
			,'table/jquery.dataTables.min'				// for data table
			,'popup/js/alertbox'					// for popup message
			,'popup/js/jquery.easing.1.3'
			,'message_box/Scripts/jquery.msgBox'
			,'general/validation'
			,'orders/orders'
			
		);
		
		$css=array('transaction/capital/gold_introduce'
					,'ui-lightness/jquery-ui-1.10.2.custom'
					,'table_css/table/jquery.dataTables'
					,'orders/orders'
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
		$this -> load -> view('includes/order/template', $this -> set_site_data('orders/print_order',$main_data));
	}
	function order_report_facade(){
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
			,'orders/order_report'
			
		);
		
		$css=array('transaction/capital/gold_introduce'
					,'ui-lightness/jquery-ui-1.10.2.custom'
					,'table_css/table/jquery.dataTables'
					,'orders/order_report'
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
		$this -> load -> view('includes/staff/template', $this -> set_site_data('orders/order_report',$main_data));
	}
	function get_orders_by_pattern(){
		$bill_no=$_GET['bill_no'];
		
		$order_date_from=to_sql_date($_GET['order_date_from']);
		$order_date_to=to_sql_date($_GET['order_date_to']);

		$result=$this->order_model->select_order_by_options($bill_no,$order_date_from,$order_date_to);
		if($result==NULL){
			echo '<div id="msg">';
			echo "Error getting record";
			echo '</div>';
			echo '<div id="error">error</div>';
			echo '<div id="report">';
			echo '</div>';
		}else{
			echo '<div id="msg">';
			echo "Order fetched";
			echo '</div>';
			echo '<div id="error">noerror</div>';
			echo '<div id="report">';
			$this->load->library('table');
			$sl=0;
			foreach($result->result() as $row){
				$this->table->add_row(cell_format(++$sl,'integer')
									  ,cell_format($row->order_id,'text')
									  ,cell_format($row->cust_name,'text')
									  ,cell_format(anchor(site_url().'/order_controller/edit_order_by_order_no_facade?order_id='.$row->order_id,img(array('src'=>'img/edit.jpg','class'=>'no_print edit','height'=>'25px','border'=>'0','alt'=>'Edit'))),'text')
									  ,cell_format(anchor_popup(site_url().'/order_controller/show_order_by_order_no?order_id='.$row->order_id,img(array('src'=>'img/printer.png','class'=>'no_print print','height'=>'25px','border'=>'0','alt'=>'Print'))),'text')
									  );
				//echo "Order No. " .anchor_popup(site_url().'/order_controller/show_order_by_order_no?order_id='.$row->order_id,$row->order_id);
			}
			$this->table->set_heading('SL','Bill No','Customer');
			echo $this->table->generate();
			echo '</div>';
		}
		
	}
	function edit_order_by_order_no_facade(){
		
		$main_data=array();
		$java_scripts=array('UI2/jquery-ui-1.10.2.custom/js/jquery-ui-1.10.2.custom'
			,'general'
			,'table/jquery.dataTables.min'				// for data table
			,'popup/js/alertbox'					// for popup message
			,'popup/js/jquery.easing.1.3'
			,'message_box/Scripts/jquery.msgBox'
			,'general/validation'
			,'orders/edit_order'
			
		);
		
		$css=array('transaction/capital/gold_introduce'
					,'ui-lightness/jquery-ui-1.10.2.custom'
					,'table_css/table/jquery.dataTables'
					,'orders/edit_order'
					,'message_box/Styles/msgBoxLight'
					);	   //for data table jquery
		$main_data['java_script']=$java_scripts;
		$main_data['css']=$css;
		$agents['AG2018']='Counter Agent';
		$main_data['user_id']=$this->session->userdata('user_id');
		$main_data['agents']=$agents;
		$main_data['current_date']=get_current_date();
		$main_data['delivery_date']=date("d/m/Y",strtotime("+5 days"));
		//$main_data['user_key']=$this->session->userdata('ip_address').'-'.date_serial_number(get_current_date()).'-'.rand(1000,9999);
		$main_data['user_key']=uniqid('', true);
		
		if(!isset($_GET['order_id'])){
			$main_data['page_title']="Diamond Edit Order";
			$this -> load -> view('includes/staff/template', $this -> set_site_data('orders/edit_order',$main_data));
			return;
		}
		$order_id=$_GET['order_id'];
		$company=$this->db->query("select * from company_details")->row();
		$main_data['company_details']=$company;
		$result=$this->order_model->get_order_master_by_order_id($order_id);
		if($result==NULL){
			echo "Error fetching order";
			return;
		}else{
			$this->load->library('table');
			$this->table->add_row(cell_format('Order No.','text','column1')
								  ,cell_format($result->order_id,'text','column2','order-id')
								  ,cell_format(' ','text','column3')
								  ,cell_format('Date of Order','text','column4')
								  ,cell_format(sql_date_to_dmy($result->order_date),'text','column5')
								  );
			$this->table->add_row(cell_format('Agent Name','text','column1')
								  ,cell_format($result->agent_name,'text','column2')
								   ,cell_format(' ','text','column3')
								  ,cell_format('Date of Delivery','text','column4')
								  ,cell_format(sql_date_to_dmy($result->delivery_date),'text','column5')
								  );
			$this->table->set_template(default_table_template('id="order_master" class="dataTables"'));
			$this->table->set_caption('Customer : <b>'.$result->cust_name.'</b>');
			$main_data['order_master']=$this->table->generate();
			$this->table->clear();
		}
		
		$main_data['order_details']=$this->get_order_details_by_order_id($order_id);
		
		
		
		$main_data['page_title']="Diamond New Order";
		$this -> load -> view('includes/staff/template', $this -> set_site_data('orders/edit_order',$main_data));
	}
	function get_order_details_by_order_id($order_id){
		$this->load->library('table');
		$result=$this->order_model->get_order_details_by_order_id($order_id);
		$total_gold=0;
		$qty=0;
		$lc=0;
		foreach($result->result() as $row){
			$ord_no=$row->order_no;
			$this->table->add_row(cell_format($row->sl_no,'text','column1')
								  ,'<span>'.form_input('model_'.$ord_no,$row->product_code,'class="column2" id="modelEditable-'.$ord_no.'"').'</span>'.'<span class="column2" id="modelDisplay-'.$ord_no.'">'.$row->product_code.'</span>'
								  ,'<span>'.form_input('size_'.$ord_no,$row->prd_size,'class="column3" id="sizeEditable-'.$ord_no.'"').'</span>'.'<span class="column3" id="sizeDisplay-'.$ord_no.'">'.$row->prd_size.'</span>'
								  ,'<span>'.form_input('qty_'.$ord_no,$row->qty,'class="column4" id="qtyEditable-'.$ord_no.'"').'</span>'.'<span class="column4" id="qtyDisplay-'.$ord_no.'">'.$row->qty.'</span>'
								  ,'<span>'.form_input('gold_'.$ord_no,number_format($row->gold_wt,3),'class="column5 gold-input" id="goldEditable-'.$ord_no.'"').'</span>'.'<span class="column5" id="goldDisplay-'.$ord_no.'">'.number_format($row->gold_wt,3).' g </span>'
								  ,'<span>'.form_input('status_'.$ord_no,$row->status_name,'class="column6" id="statusEditable-'.$ord_no.'"').'</span>'.'<span class="column6" id="statusDisplay-'.$ord_no.'">'.$row->status_name.'</span>'
								  ,img(array('src'=>'img/save.png','id'=>'save-'.$ord_no,'class'=>'save column7','height'=>'25px','border'=>'0','alt'=>'save')).img(array('src'=>'img/edit.jpg','id'=>'edit-'.$ord_no,'class'=>'edit column7','height'=>'25px','border'=>'0','alt'=>'edit')).img(array('src'=>'img/error.png','id'=>'cancel-'.$ord_no,'class'=>'cancel column7','height'=>'25px','border'=>'0','alt'=>'cancel'))
								  //,cell_format($row->price*$row->qty,'currency','column6')
								 );
			$this->table->add_row_id($ord_no);
			$total_gold+=$row->gold_wt;
			$qty+=$row->qty;
			$lc+=$row->price*$row->qty;
		}
		$this->table->set_template(default_table_template('id="order_details" class="dataTables"'));
		$this->table->set_caption('');
		$this->table->set_heading('SL','Model','Size'
									,cell_format('Qty','text','column4')
									,cell_format('Appx. Gold','text','column5')
									,cell_format('Status','text','column6')
									,cell_format('Action','text','Column7')
									//,cell_format('L/C','text','column6')
									);
		$this->table->set_footer('','Total',''
									,cell_format($qty,'integer','column4')
									,cell_format($total_gold,'gold','column5')
									,cell_format("",'text','column6')
									,cell_format("",'text','column7')
									);
		$order_details=$this->table->generate();
		return $order_details;
		
	}
	function update_order_by_order_details_no(){
		//$this -> form_validation -> set_rules('customer_id', 'Customer', 'trim|required|is_exists[customer_master.cust_id]');
		$this -> form_validation -> set_rules('product_code', 'Model No.', 'trim|required|is_exists[product_master.product_code]');
		$this -> form_validation -> set_rules('prd_size', 'Size', 'trim|required');
		$this -> form_validation -> set_rules('gold_wt', 'Gold', 'trim|required|numeric');
		$this -> form_validation -> set_rules('qty', 'Qty', 'trim|required|numeric');
		//$this -> form_validation -> set_rules('description', 'Description', 'trim|required');
		
		$this -> form_validation -> set_error_delimiters('<div class="error">', '</div>');
		if ($this -> form_validation -> run() == FALSE) {
			echo '<div id="msg">';
			echo validation_errors();
			echo '</div>';
			echo '<div id="error">error</div>';
			echo '<div id="report">';
			echo 'No Report';
			print_r($_POST);
			echo '</div>';
			return;
		}
		
		
		$result=$this->order_model->update_order_by_order_no();
		if($result==1){
			echo '<div id="msg">';
			echo "successfully Updated";
			echo '</div>';
			echo '<div id="error">noerror</div>';
			echo '<div id="report">';
			echo 'No Report';
			echo $this->get_order_details_by_order_id($_POST['order_id']);
			echo '</div>';
		}else{
			echo '<div id="msg">';
			echo 'Error updating item';
			echo '</div>';
			echo '<div id="error">error</div>';
			echo '<div id="report">';
			echo 'No Report';
			echo '</div>';
		}
	}
	function misc_order_facade(){
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
			,'orders/misc_order'
			
		);
		
		$css=array('transaction/capital/gold_introduce'
					,'ui-lightness/jquery-ui-1.10.2.custom'
					,'table_css/table/jquery.dataTables'
					,'orders/misc_order'
					,'message_box/Styles/msgBoxLight'
					);	   //for data table jquery
		$agents['AG2018']='Counter Agent';
		$main_data['user_id']=$this->session->userdata('user_id');
		$main_data['agents']=$agents;
		$main_data['java_script']=$java_scripts;
		$main_data['css']=$css;
		$main_data['current_date']=get_current_date();
		$main_data['delivery_date']=date("d/m/Y",strtotime("+5 days"));
		$result=$this->order_model->select_gold_id_for_order();
		$gold=array();
		foreach($result->result() as $row){
			$gold[$row->key]=$row->value;
		}
		
		$main_data['gold']=$gold;
		$result=$this->order_model->select_default_gold_id_for_order();
		$main_data['default_gold_id']=$result->key;
		
		//$main_data['user_key']=$this->session->userdata('ip_address').'-'.date_serial_number(get_current_date()).'-'.rand(1000,9999);
		$main_data['user_key']=uniqid('', true);
		$main_data['page_title']="Diamond New Order";
		$this -> load -> view('includes/staff/template', $this -> set_site_data('orders/misc_order',$main_data));
	}
	function delete_order_view(){
		start_div();
			start_div();
				echo form_label('Order Number ');
				echo form_input('order_no','','placeholder="Order No" id="order-no"');
				echo form_button('chec_status','Check','id="check-status"');
				echo start_div('id="result-div"');
		
				echo end_div();
			end_div();
		end_div();
	}
	function get_deleteable_orders(){
		$result=$this->order_model->select_deleteable_orders_by_term($_GET['term']);
		$row_array=array();
		$return_arr=array();
		if($result==NULL){
			$row_array['label']="No Order exists";
			array_push($return_arr,$row_array);
			echo json_encode($return_arr);
			return;
		}
		
		
		//$models=array();
		foreach($result->result() as $row){
			$row_array['label']=$row->order_id;
			array_push($return_arr,$row_array);
		}
		echo json_encode($return_arr);
	}
	function show_deleteable_orders_view(){
		$result=array();
		start_div();
			start_div();
				/*echo form_label('Order Number ');
				echo form_input('order_no','','placeholder="Order No" id="order-no"');
				echo form_button('chec_status','Check','id="check-status"');*/
				$result=$this->order_model->select_deleteable_orders();
				if($result==NULL){
					echo "No record exist";
					return;
				}
				echo start_div('id="result-div"');
					$this->load->library('table');
					$sl=0;
					foreach($result->result() as $row){
						$this->table->add_row(cell_format(++$sl,'text','slno')
											  ,cell_format($row->order_date,'text','date left')
											  ,cell_format($row->order_id,'text','order_id center')
											  ,cell_format($row->cust_name,'text','cust_name left')
											  ,cell_format(img(array('src'=>'img/error.png','class'=>'cancel-order','height'=>'25px','border'=>'0','alt'=>'cancel','order-id'=>$row->order_id)),'text','action')
											  );
						if($sl%2==0){
							$this->table->add_row_class('even order-row');
						}else{
							$this->table->add_row_class('odd order-row');
						}
						$this->table->add_row_id($row->order_id);
					}
					$this->table->set_heading(cell_format('SL','text','slno')
											  ,cell_format('Date','text','date left')
											  ,cell_format('Order No.','text','order_id center')
											  ,cell_format('Customer','text','cust_name left')
											  ,cell_format('Action','text','action')
											  );
					$this->table->set_template(default_table_template('id="deleteable_orders_table"'));
					echo $this->table->generate();
			end_div();
			start_div('id="sub-result-div"');
				
			end_div();
		end_div();
	}
	function show_order_details_by_order_id(){

		$result=$this->order_model->select_order_details_by_order_id_where_status_fresh();
		if($result==NULL){
					echo "No record exist";
					return;
		}
		$this->load->library('table');
		$sl=0;
		
		foreach($result->result() as $row){
				$this->table->add_row(cell_format(++$sl,'text','slno')
						,cell_format($row->product_code,'text','product_code center')
						,cell_format($row->prd_size,'text','prd_size center')
						,cell_format($row->gold_wt,'gold','gold right')
						,cell_format(img(array('src'=>'img/error2.png','class'=>'cancel-order-detail','height'=>'25px','border'=>'0','alt'=>'cancel','order-id'=>$row->order_no)),'text','action')
				);
				if($sl%2==0){
						$this->table->add_row_class('even');
				}else{
						$this->table->add_row_class('odd');
				}
		}
		
		$this->table->set_template(default_table_template('id="deleteable_order_details_table"'));
		$this->table->set_caption("Details for order : ".$_GET['order_no']);
		echo $this->table->generate();
	}
	function cancel_order(){
		$result=$this->order_model->update_order_master_set_status_cancel();
		echo "order cancel";
	}
	function show_orders_view(){
		start_div();
			start_div();
				echo 'Delivery Date from'.' <input class="date" pattern="(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.](19|20)\d\d" type="date" id="date-from" value="'.get_current_date().'" placeholder="DD/MM/20YY"/>';
				echo 'to';
				echo '<input class="date" pattern="(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.](19|20)\d\d" value="'.get_current_date().'" type="date" title="Date upto" id="date-to" placeholder="DD/MM/20YY"/>';
				echo '<input   type="text" title="Customer ID" id="cust-id" placeholder="CUST ID"/>';
				echo form_button('show','Show','id="show-orders"');
				echo start_div('id="result-div"');
		
				echo end_div();
			end_div();
		end_div();
	}
	function show_order_by_delivery_date(){
		$date_from=to_sql_date($_GET['date_from']);
		$date_upto=to_sql_date($_GET['date_upto']);
		$result=$this->order_model->select_order_by_delivery_date($date_from,$date_upto);
		if($result==NULL){
			echo "No record found";
			return;
		}
		$this->load->library('table');
		$sl=0;
		foreach($result->result() as $row){
				$this->table->add_row(cell_format(++$sl,'text','slno')
						//order_controller/show_order_by_order_no?order_id=BW/00000264/1415
						,cell_format($row->order_id==NULL?'None':anchor_popup(site_url()."/order_controller/show_order_by_order_no?order_id=".$row->order_id,$row->order_id),'text','order-no center width-150')
						//,cell_format($row->order_id,'text','order-no center')
						,cell_format($row->cust_id,'text','cust-id center')
						,cell_format($row->cust_name,'text','cust-name left')
						,cell_format($row->order_date,'text','order-date center')
						,cell_format($row->delivery_date,'text','delivery-date center')
						,cell_format($row->no_of_orders,'integer','canceled right')
						,cell_format($row->canceled,'integer','no-of-orders right')
						,cell_format($row->jobed,'integer','jobed right')
				);
				if($sl%2==0){
						$this->table->add_row_class('even');
				}else{
						$this->table->add_row_class('odd');
				}
				$this->table->add_row_id($row->order_id);
		}
		$this->table->set_heading(cell_format("SL",'text','slno')
						,cell_format("Order No",'text','order-no center')
						,cell_format("Cust ID",'text','cust-id center')
						,cell_format("Name",'text','cust-name left')
						,cell_format("Date",'text','order-date center')
						,cell_format("Date",'text','delivery-date center')
						,cell_format("Orders",'text','no-of-orders center')
						,cell_format("Canceled",'text','canceled center')
						,cell_format("Jobed",'text','jobed center')
				);
		$this->table->set_template(default_table_template('id="table-by-delivery-date"'));
		$this->table->set_caption("Details for order to be delivered between: ".$_GET['date_from']." and ".$_GET['date_upto']);
		echo $this->table->generate();
		start_div('id="result2-div"');
		echo "this is result2 div";
		end_div();
	}
	//end of order report
	function show_order_details_by_order(){
		echo '<hr>';
		$result=$this->order_model->select_order_details_by_order($_GET['order_no']);
		if($result==NULL){
			echo "No record found";
			return;
		}
		$this->load->library('table');
		$sl=0;
		foreach($result->result() as $row){
				$this->table->add_row(cell_format($row->sl_no,'text','slno')
						,cell_format($row->prd_size,'text','product-size center width-50')
						,cell_format($row->gold_wt,'gold','gold-weight right width-50')
						,cell_format($row->qty,'integer','qty right width-25')
						,cell_format($row->status_name,'text','status center width-75')
						,cell_format($row->job_id==NULL?'None':anchor_popup(site_url()."/job_controller/print_job?job_id=".$row->job_id,$row->job_id),'text','job center width-50')
						//,cell_format($row->bill_no,'text','bill center width-150')
						,cell_format($row->bill_no==NULL?'None':anchor_popup(site_url()."/bill_controller/display_bill?bill_no=".$row->bill_no,$row->bill_no),'text','bill center width-150')
						
						
				);
				$sl++;
				if($sl%2==0){
						$this->table->add_row_class('even');
				}else{
						$this->table->add_row_class('odd');
				}
				$this->table->add_row_id($row->order_id);
		}
		$this->table->set_heading(cell_format('SL','text','slno')
						,cell_format('Size','text','product-size center')
						,cell_format('Gold','text','gold-weight right')
						,cell_format('Qty','text','qty right')
						,cell_format('Status','text','status left')
						,cell_format('Job','text','job center')
						,cell_format('Bill','text','bill center')
						
						
				);
		$this->table->set_template(default_table_template('id="details_table-by-delivery-date"'));
		$this->table->set_caption("Order details of Challan No. ".$_GET['order_no']);
		echo $this->table->generate();
	}
	/*Update 17/03/2015	*/
	function get_customer_by_agent_id(){
		$agent_id=$_GET['agent_id'];
		if($agent_id=='AG000'){
			echo "All Customers";
			return;
		}
		$result=$this->Report_model->select_customer_by_agent_id($agent_id);
		if($result==NULL){
			echo "No Customer Assigned";
			return;
		}
		$customers['s0000']="--select--";
		foreach($result->result() as $row){
			if($row->order_inforce==1){
				$customers[$row->cust_id]=$row->cust_name;
			}	
		}
		echo '<div>';
		echo '<label>Customer </label>';
		echo form_dropdown('customer_id',$customers,'s0000','id="new-cust-id"');
		echo '</div>';
		?><div id="customer-dues-div"></div> <?php
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
}
?>