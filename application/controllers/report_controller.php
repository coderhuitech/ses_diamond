<?php
class Report_controller extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this -> load -> library('form_validation');
		//this libraru is used for validation of fields in a form
		$this->load->model('Customer_model');
		$this->load->model('Employee_model');
		$this->load->model('Report_model');
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
	function get_customer_balance_by_cust_id(){
		$cust_id="";
		if(isset($_GET['cust_id'])){
			$cust_id=$_GET['cust_id'];
		}
		if(isset($_POST['cust_id'])){
			$cust_id=$_POST['cust_id'];
		}
		$arr=array();
		$result=$this->Customer_model->select_customer_balance_by_id($cust_id);
		if($result==NULL){
			$arr['gold']=0;
			$arr['lc']=0;

		}else{
			$arr['gold']=$result->gold_due;
			$arr['lc']=$result->lc_due;
		}
		echo '<div id="msg">';
		echo "Item in the list successfully";
		echo '</div>';
		echo '<div id="error">noerror</div>';
		echo '<div id="report">';
		echo 'LC : <span id="lc-due">'.$arr['lc'].'</span> and gold : <span id="gold-due">'.$arr['gold'].'</span>';
		echo '</div>';
	}
	function customer_report_facade(){
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
			,'reports/customer'
			,'print_div/jQuery.print'
		);
		
		$css=array('ui-lightness/jquery-ui-1.10.2.custom'
					,'table_css/table/jquery.dataTables'
					,'jobs/show_job_details'
					,'message_box/Styles/msgBoxLight'
					,'reports/customer'
					);	   //for data table jquery
		
		$result=$this->Report_model->select_agents();
		$agents['AG000']="All";
		foreach($result->result() as $row){
			$agents[$row->agent_id]=$row->short_name;
		}
		$main_data['agents']=$agents;
		$main_data['java_script']=$java_scripts;
		$main_data['css']=$css;
		$main_data['current_date']=get_current_date();
		$main_data['page_title']="Customer";
		$this -> load -> view('includes/staff/template', $this -> set_site_data('reports/customer_report_view',$main_data));
	}
	function get_customer_balance(){
		$result=$this->Report_model->select_customer_balance(0,$_GET['row_number'],$_GET['agent_id']);
		$this->load->library('table');
		$total_gold=0;
		$total_lc=0;
		$sl=0;
		$caption=$_GET['agent_id']=='AG000'?("Customer Dues for all agents : "):("Customer Dues for the agent : ".$_GET['agent_id']);
		foreach($result->result() as $row){
			$this->table->add_row(cell_format(++$sl,'text','slno')
								  ,cell_format($row->cust_id,'text','id')
								  ,cell_format($row->city,'text','id')
								  ,cell_format('<span title="'.$row->cust_address.'">'.$row->cust_name.' '.$row->category.'</span>','text','name')
								  ,cell_format($row->cust_phone,'text','phone')
								  ,cell_format('<span title="'.$row->agent_name.' '.$row->agent_phone.'">'.$row->short_name.'</span>','text','agent_name')
								  ,cell_format($row->opening_gold+$row->billed_gold-$row->received_gold,'gold','gold-due')
								  ,cell_format($row->opening_lc+$row->billed_lc-$row->received_lc,'currency','lc-due')
								  );
			$total_gold+=$row->opening_gold+$row->billed_gold-$row->received_gold;
			$total_lc+=$row->opening_lc+$row->billed_lc-$row->received_lc;
			$this->table->add_row_id($row->cust_id);
			if($sl%2==0){
				$this->table->add_row_class('even');
			}else{
				$this->table->add_row_class('odd');
			}
		}
		$this->table->set_heading('SL','ID','City','Customer','Phone','Agent','Gold Due','LC Due');
		$this->table->set_footer(' ',' ','Total',' ','',' '
									,cell_format($total_gold,'gold','gold-due')
									,cell_format($total_lc,'currency','lc-due'));
		$this->table->set_template(default_table_template('id="customer"'));
		$this->table->set_caption($caption);
		echo '<div id="customer-table-div">';
			echo '<div id="customer-balance-div">';
			echo $this->table->generate();
			echo '</div>';
		echo '</div>';
		/*$result=$this->Report_model->select_customer_balance();
		echo '<div class="dontprint">';
		echo pagination($result->num_rows(),10);
		echo '</div>';*/
	}
	function get_customer_balance_with_pagination(){
		$x=(int)$_GET['pageVal'];
		$sl=$x;
		$result=$this->Report_model->select_customer_balance($x,10);
		$this->load->library('table');
		$total_gold=0;
		$total_lc=0;
		foreach($result->result() as $row){
			$this->table->add_row(cell_format(++$sl,'text','slno')
								  ,cell_format($row->cust_id,'text','id')
								  ,cell_format($row->cust_name,'text','name')
								  ,cell_format($row->cust_phone,'text','phone')
								  ,cell_format($row->opening_gold+$row->billed_gold-$row->received_gold,'gold','gold-due')
								  ,cell_format($row->opening_lc+$row->billed_lc-$row->received_lc,'currency','lc-due')
								  );
			$total_gold+=$row->opening_gold+$row->billed_gold-$row->received_gold;
			$total_lc+=$row->opening_lc+$row->billed_lc-$row->received_lc;
			if($sl%2==0){
				$this->table->add_row_class('even');
			}else{
				$this->table->add_row_class('odd');
			}
		}
		$this->table->set_heading('SL','ID','Customer','Phone','Gold Due','LC Due');
		$this->table->set_footer(' ',' ','Total',' '
									,cell_format($total_gold,'gold','gold-due')
									,cell_format($total_lc,'currency','lc-due'));
		$this->table->set_template(default_table_template('id="customer"'));
		echo $this->table->generate();
	}
	function staff_report_facade(){
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
			,'reports/staff_report'
			,'print_div/jQuery.print'
		);
		
		$css=array('ui-lightness/jquery-ui-1.10.2.custom'
					,'table_css/table/jquery.dataTables'
					,'jobs/show_job_details'
					,'message_box/Styles/msgBoxLight'
					,'reports/staff_report'
					,'materials/stock_all_employees'
					);	   //for data table jquery
		
		$main_data['java_script']=$java_scripts;
		$main_data['css']=$css;
		$main_data['current_date']=get_current_date();
		$main_data['page_title']="Staff Report";
		$this -> load -> view('includes/staff/template', $this -> set_site_data('reports/staff_report_view',$main_data));
	}
	function get_staff_cash_balance(){
		$result=$this->Report_model->select_staff_cash_balance();
		$this->load->library('table');
		$this->table->set_template(default_table_template('id="staff-cash-balance-table"'));
		$total_cash_balance=0;
		$row_class="odd";
		foreach($result->result() as $row){
			$this->table->add_row(cell_format($row->emp_name,'text','emp-name left')
								  ,cell_format($row->employee_balance,'currency','emp-balance right')
								  );
			$row_class=$row_class=="odd"?"even":"odd";
			$total_cash_balance+=$row->employee_balance;
			$this->table->add_row_class($row_class);
		}
		$this->table->set_heading('Name','Cash');
		$this->table->set_footer('Total',cell_format($total_cash_balance,'currency','total-cash emp-balance right'));
		echo $this->table->generate();
	}
	function get_daily_lc_receipt(){
		$date_from=to_sql_date($_GET['date_from']);
		$date_to=to_sql_date($_GET['date_to']);
		$result=$this->Report_model->select_daily_lc_receipt($date_from,$date_to);
		if($result==NULL){
			echo 'No Record found for today';
			return;
		}
		
		$this->load->library('table');
		$this->table->set_template(default_table_template('id="staff-daily-lc-receipt-div"'));
		$sl=0;
		$row_class="odd";
		$total=0;
		foreach($result->result() as $row){
			$this->table->add_row(cell_format(++$sl,'integer','sl')
								  ,cell_format($row->lc_receipt_date,'text','date right')
								  ,cell_format($row->lc_receipt_no,'text','lc-receipt-no left')
								  ,cell_format($row->cust_name,'text','cust-name left')
								  ,cell_format($row->agent_name,'text','agent-name left')
								  ,cell_format($row->emp_name,'text','emp-name left')
								  ,cell_format($row->receipt_mode,'text','receipt-mode left')
								  ,cell_format($row->amount,'currency','amount right')
								  ,img(array('src'=>'img/printer.png','id'=>'save-challan','class'=>'no_print','height'=>'30px','border'=>'0','alt'=>'Save'))
								  );
			$total+=$row->amount;
			$row_class=$row_class=="odd"?"even":"odd";
			$this->table->add_row_class($row_class);
		}
		
		$this->table->set_heading(cell_format('SL','text','sl')
								  ,cell_format('Date','text','date center')
								  ,cell_format('Receipt No.','text','lc-receipt-no center')
								  ,cell_format('Customer','text','cust-name center')
								  ,cell_format('Agent','text','agent-name center')
								  ,cell_format('Staff','text','emp-name center')
								  ,cell_format('Mode','text','receipt-mode center')
								  ,cell_format('Amount','text','amount center')
								  );
		$this->table->set_footer(cell_format('Total','text','total','','','','7'),cell_format($total,'currency','total-amount amount right'));
		echo $this->table->generate();
	}
	function daily_report_facade(){
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
			,'reports/daily_report'
			,'print_div/jQuery.print'
		);
		
		$css=array('ui-lightness/jquery-ui-1.10.2.custom'
					,'table_css/table/jquery.dataTables'
					,'jobs/show_job_details'
					,'message_box/Styles/msgBoxLight'
					,'reports/daily_report'
					);	   //for data table jquery
		
		$main_data['java_script']=$java_scripts;
		$main_data['css']=$css;
		$main_data['current_date']=get_current_date();
		$main_data['page_title']="Staff Report";
		$this -> load -> view('includes/staff/template', $this -> set_site_data('reports/daily_report_view',$main_data));
	}
	function admin_report_facade(){
		//this block of code is mandatory for all facade from now
		//-------------------------------------------------------------------------
		// extention should not be given
		if($this->session->userdata('employee_id')!=28){
			echo "You are not authorised ".$this->session->userdata('employee_id');
			return;
		}
		
		$java_scripts=array('UI2/jquery-ui-1.10.2.custom/js/jquery-ui-1.10.2.custom'
			,'general'
			,'table/jquery.dataTables.min'				// for data table
			,'popup/js/alertbox'					// for popup message
			,'popup/js/jquery.easing.1.3'
			,'message_box/Scripts/jquery.msgBox'
			,'general/validation'
			,'reports/admin_report'
			,'print_div/jQuery.print'
		);
		
		$css=array('ui-lightness/jquery-ui-1.10.2.custom'
					,'table_css/table/jquery.dataTables'
					,'jobs/show_job_details'
					,'message_box/Styles/msgBoxLight'
					,'reports/admin_report'
					);	   //for data table jquery
		
		$main_data['java_script']=$java_scripts;
		$main_data['css']=$css;
		$main_data['current_date']=get_current_date();
		$main_data['page_title']="Admin Report";
		$this -> load -> view('includes/staff/template', $this -> set_site_data('reports/admin_report_view',$main_data));
	}
	function get_daily_inventory_report(){
		$date_from=to_sql_date($_GET['date_from']);
		$date_to=to_sql_date($_GET['date_to']);
		$result=$this->Report_model->select_daily_inventory_movement($date_from,$date_to);
		if($result==NULL){
			echo 'No Record found for today';
			return;
		}
		
		$this->load->library('table');
		$this->table->set_template(default_table_template('id="daily-invntory-movement"'));
		$sl=0;
		$row_class="odd";
		$inflow=0;
		$outflow=0;
		foreach($result->result() as $row){
			$this->table->add_row(cell_format(++$sl,'integer','sl')
								  ,cell_format($row->transaction_date,'text','date right')
								  ,cell_format($row->reference,'text','reference center')
								  ,cell_format($row->comment,'text','comment left')
								  ,cell_format($row->emp_name,'text','emp_name left')
								  ,cell_format($row->rm_name,'text','rm-name left')
								  ,cell_format($row->inflow,'gold','inflow left')
								  ,cell_format($row->outflow,'gold','outflow right')
								  );
			$inflow+=$row->inflow;
			$outflow+=$row->outflow;
			$row_class=$row_class=="odd"?"even":"odd";
			$this->table->add_row_class($row_class);
		}
		
		$this->table->set_heading(cell_format('SL','text','sl')
								  ,cell_format('Date','text','date center')
								  ,cell_format('Reference','text','reference center')
								  ,cell_format('Comment','text','comment center')
								  ,cell_format('Employee','text','emp_name left')
								  ,cell_format('Material','text','rm-name center')
								  ,cell_format('Inflow','text','inflow center')
								  ,cell_format('Outflow','text','outflow center')
								  );
		//$this->table->set_footer(cell_format('Total','text','total','','','','7'),cell_format($total,'currency','total-amount amount right'));
	/*	echo '<div id="criteria">';
		echo "Daily inventory Report betwwen ";
		echo '<input type="date" id="daily-inventory-date-from" class="date" value="'.sql_date_to_dmy($date_from).'"/>';
		echo " to ";
		echo '<input type="date" id="daily-inventory-date-to" class="date" value="'.sql_date_to_dmy($date_to).'"/>';
		echo '<input type="button" id="show-daily-inventory" value="show" />';
		echo '</div>';*/
		//echo '<div>';
		echo $this->table->generate();
		//echo '</div>';
	}
	function get_daily_mathakata_report(){
		$date_from=to_sql_date($_GET['date_from']);
		$date_to=to_sql_date($_GET['date_to']);
		$result=$this->Report_model->select_mathakata_by_period($date_from,$date_to);
		if($result==NULL){
			echo "No record found";
			return;
		}
		$this->load->library('table');
		$this->table->set_template(default_table_template('id="mathakata-table"'));
		$sl=0;
		$row_class="odd";
		$mathakata=0;
		foreach($result->result() as $row){
			$this->table->add_row(cell_format(++$sl,'integer','sl center')
								  ,cell_format($row->date,'text','date center')
								  ,cell_format($row->emp_name,'text','emp_name left')
								  ,cell_format($row->job,'text','job center')
								  ,cell_format($row->mathakata,'gold','mathakata right')
								  );
			$row_class=$row_class=="odd"?"even":"odd";
			$this->table->add_row_class($row_class);
			$mathakata+=$row->mathakata;
		}
		$this->table->set_heading(cell_format('SL','text','sl center')
								  ,cell_format('Date','text','date center')
								  ,cell_format('emp_name','text','emp_name center')
								  ,cell_format('Job','text','job center')
								  ,cell_format('Mathakata','text','mathakata right')
								  );
		$this->table->set_footer(cell_format('Total','text','total center','','','',4)
								  ,cell_format($mathakata,'gold','mathakata right')
								  );						 
		$this->table->set_caption("Mathakata Report from <b>".$_GET['date_from'].' to '.$_GET['date_to'].'</b>');
		echo $this->table->generate();
		echo '</br></br>';
		$this->table->clear();
		$result=$this->Report_model->select_nitrick_by_period($date_from,$date_to);
		if($result==NULL){
			echo "No record found";
			return;
		}
		$this->table->set_template(default_table_template('id="nitrick-table"'));
		$sl=0;
		$row_class="odd";
		$nitrick=0;
		foreach($result->result() as $row){
			$this->table->add_row(cell_format(++$sl,'integer','sl center')
								  ,cell_format($row->date,'text','date center')
								  ,cell_format($row->emp_name,'text','emp_name left')
								  ,cell_format($row->job,'text','job center')
								  ,cell_format($row->nitrick,'gold','mathakata right')
								  );
			$row_class=$row_class=="odd"?"even":"odd";
			$this->table->add_row_class($row_class);
			$nitrick+=$row->nitrick;
		}
		$this->table->set_heading(cell_format('SL','text','sl center')
								  ,cell_format('Date','text','date center')
								  ,cell_format('emp_name','text','emp_name center')
								  ,cell_format('Job','text','job center')
								  ,cell_format('Nitrick','text','nitrick right')
								  );
		$this->table->set_footer(cell_format('Total','text','total center','','','',4)
								  ,cell_format($nitrick,'gold','nitrick right')
								  );						 
		$this->table->set_caption("Nitrick Report from <b>".$_GET['date_from'].' to '.$_GET['date_to'].'</b>');
		echo $this->table->generate();
	}
	function get_daily_bill_report(){
		$date_from=to_sql_date($_GET['date_from']);
		$date_to=to_sql_date($_GET['date_to']);
		$bill_type=$_GET['bill_type'];
		$result=$this->Report_model->select_bills_by_period($date_from,$date_to,$bill_type);
		if($result==NULL){
			echo "No record found";
			return;
		}
		$this->load->library('table');
		$this->table->set_template(default_table_template('id="bills-table"'));
		$sl=0;
		$row_class="odd";
		$qty=0;
		$gold=0;
		$lc=0;
		foreach($result->result() as $row){
			$this->table->add_row(cell_format(++$sl,'integer','sl center')
								  ,cell_format($row->bill_date,'text','date center')
								  
								  ,cell_format(anchor_popup(site_url()."/bill_controller/display_bill?bill_no=".$row->bill_no,$row->bill_no),'text','bill-no left')
								  ,cell_format($row->comment,'text',$row->comment)
								 // ,cell_format($row->cust_id,'text','cust-id left')
								  ,cell_format($row->cust_name,'text','cust-name left')
								  ,cell_format($row->qty,'integer','qty right')
								  ,cell_format($row->gold,'gold','gold right')
								  ,cell_format($row->lc,'currency','lc right')
								  
								  );
			$row_class=$row_class=="odd"?"even":"odd";
			$this->table->add_row_class($row_class);
			$qty+=$row->qty;
			$gold+=$row->gold;
			$lc+=$row->lc;
		}
		$test='<select id="bill-type-select">';
		if($bill_type==0)
			$test.='<option value=0 selected>--All--</option>';
		else
			$test.='<option value=0>--All--</option>';
			
		if($bill_type==1)
			$test.='<option value=1 selected>Order</option>';
		else
			$test.='<option value=1>Order</option>';
			
		if($bill_type==2)
			$test.='<option value=2 selected>Readymade</option>';
		else
			$test.='<option value=2>Readymade</option>';
			
		$test.='</select>';
		$this->table->set_heading(cell_format('SL','text','sl center')
								  ,cell_format('Date','text','date center')
								  ,cell_format('Bill No','text','bill-no center')
								  ,cell_format($test,'text','comment')
								  //,cell_format('Cust ID','text','cust-id center')
								  ,cell_format('Customer','text','cust-name center')
								  ,cell_format('Qty','text','qty center')
								  ,cell_format('Gold','text','gold center')
								  ,cell_format('LC','text','lc center')
								  );					
		$this->table->set_footer(cell_format('Total','text','total center','','','','5')
								 ,cell_format($qty,'integer','qty right')
								 ,cell_format($gold,'gold','gold right')
								 ,cell_format($lc,'currency','lc right')
								 );	 
		$this->table->set_caption("Sales Report from <b>".$_GET['date_from'].' to '.$_GET['date_to'].'</b>');
		echo $this->table->generate();
				
	}
	function get_daily_job_report(){
		$date_from=to_sql_date($_GET['date_from']);
		$date_to=to_sql_date($_GET['date_to']);
		$result=$this->Report_model->select_jobs_by_period($date_from,$date_to);
		if($result==NULL){
			echo "No record found";
			return;
		}
		$this->load->library('table');
		$this->table->set_template(default_table_template('id="job-table"'));
		$sl=0;
		$row_class="odd";
		$qty=0;
		$gold_send=0;
		$mathaakata=0;
		$dal=0;
		$pan=0;
		$nitrick=0;
		foreach($result->result() as $row){
			$this->table->add_row(cell_format(++$sl,'integer','sl center')
								  ,cell_format($row->job_date,'text','date center')
								  ,cell_format($row->job_id,'text','job center')
								  ,cell_format($row->status,'text','status center')
								  //,cell_format(anchor_popup(site_url()."/bill_controller/display_bill?bill_no=".$row->bill_no,$row->bill_no),'text','bill-no left')
								  ,cell_format($row->order_id,'text','order center')								 
								  ,cell_format($row->product_code,'text','product center')
								  ,cell_format($row->emp_name,'text','emp center')
								  ,cell_format($row->qty,'integer','qty right')
								  ,cell_format($row->product_size,'text','product_size center')
								  ,cell_format($row->expected_gold,'gold','expected_gold right')
								  ,cell_format($row->gold_send,'gold','gold_send right')
								  ,cell_format($row->gold_returned,'gold','gold_returned right')
								  ,cell_format($row->dal_send,'gold','dal_send right')
								  ,cell_format($row->pan_send,'gold','pan_send right')
								  ,cell_format($row->nitrick_returned,'gold','nitrick_returned right')
								  );
			$row_class=$row_class=="odd"?"even":"odd";
			$this->table->add_row_class($row_class);
			$qty+=$row->qty;
			$gold_send+=$row->gold_send;
			$mathaakata+=$row->gold_returned;
			$dal+=$row->dal_send;
			$pan+=$row->pan_send;
			$nitrick+=$row->nitrick_returned;
		}
		$this->table->set_heading(cell_format('SL','text','sl center')
								  ,cell_format('Date','text','date center')
								  ,cell_format('Job','text','job center')
								  ,cell_format('Status','text','status center')
								  //,cell_format(anchor_popup(site_url()."/bill_controller/display_bill?bill_no=".$row->bill_no,$row->bill_no),'text','bill-no left')
								  ,cell_format('Order No.','text','order center')								 
								  ,cell_format('Model','text','product left')
								  ,cell_format('Employee','text','emp left')
								  ,cell_format('Qty','text','qty right')
								  ,cell_format('Size','text','product_size center')
								  ,cell_format('Exp. Gold','text','expected_gold center')
								  ,cell_format('Godl Send','text','gold_send center')
								  ,cell_format('Mathakata','text','gold_returned center')
								  ,cell_format('Dal','text','dal_send center')
								  ,cell_format('Pan','text','pan_send center')
								  ,cell_format('Nitrick','text','nitrick_returned center')
								  );	
	/*	$this->table->set_footer( cell_format('Total','text','total center','','','','6')
								  ,cell_format($qty,'integer','qty right')
								  ,cell_format('','text','product_size center')
								  ,cell_format('','text','expected_gold center')
								  ,cell_format($gold_send,'gold','gold_send right')
								  ,cell_format($mathaakata,'gold','gold_returned right')
								  ,cell_format($dal,'gold','dal_send right')
								  ,cell_format($pan,'gold','pan_send right')
								  ,cell_format($nitrick,'gold','nitrick_returned right')
								  );*/
		$this->table->set_caption("Job Report from <b>".$_GET['date_from'].' to '.$_GET['date_to'].'</b>');
		echo $this->table->generate();
	}
	function get_daily_order_report(){
		$date_from=to_sql_date($_GET['date_from']);
		$date_to=to_sql_date($_GET['date_to']);
		$result=$this->Report_model->select_orders_by_period($date_from,$date_to);
		if($result==NULL){
			echo "No record found";
			return;
		}
		$this->load->library('table');
		$this->table->set_template(default_table_template('id="bills-table"'));
		$sl=0;
		$row_class="odd";
		
		foreach($result->result() as $row){
			$this->table->add_row(cell_format(++$sl,'integer','sl center')
								  ,cell_format($row->order_date,'text','date center')
								  ,cell_format($row->order_id,'text','order center')
								  ,cell_format($row->cust_name,'text','cust-name left')
								  );
			$row_class=$row_class=="odd"?"even":"odd";
			$this->table->add_row_class($row_class);
			
		}
		$this->table->set_heading(cell_format('SL','text','sl center')
								  ,cell_format('Date','text','date center')
								  ,cell_format('Order No','text','bill-no center')
								  ,cell_format('Customer','text','cust-name center')
								  );						 
		$this->table->set_caption("Order Report from <b>".$_GET['date_from'].' to '.$_GET['date_to'].'</b>');
		echo $this->table->generate();
				
	}
	function get_gold_receipt_report(){
		$date_from=to_sql_date($_GET['date_from']);
		$date_to=to_sql_date($_GET['date_to']);
		
		$result=$this->Report_model->select_gold_receipt_by_period($date_from,$date_to);
		
		if($result==NULL){
			echo "No record found";
			return;
		}
		$this->load->library('table');
		$this->table->set_template(default_table_template('id="gold-receipt-table"'));
		$sl=0;
		$row_class="odd";
		$gold=0;
		$cash=0;
		$total_gold=0;
		foreach($result->result() as $row){
			
			$this->table->add_row(cell_format(++$sl,'integer','sl center')
								  ,cell_format($row->receipt_date,'text','date center')
								  ,cell_format($row->gold_receipt_id,'text','order center gold-receipt-id')
								  ,cell_format($row->cust_name,'text','cust-name left')
								  ,cell_format($row->agent_name,'text','agent-name left')
								  ,cell_format($row->gold_value,'gold','gold-value right')
								  ,cell_format($row->gold_rate,'currency','gold-rate right')
								  ,cell_format($row->cash,'currency','cash right')
								  ,cell_format($row->cash>0?($row->cash/($row->gold_rate/10))+$row->gold_value:$row->gold_value,'gold','total-gold right')
								  );
			
			$gold+=$row->gold_value;
			$cash+=$row->cash;
			$total_gold+=$row->cash>0?($row->cash/($row->gold_rate/10))+$row->gold_value:$row->gold_value;
			$row_class=$row_class=="odd"?"even":"odd";
			$this->table->add_row_class($row_class);
			
		}
		$this->table->set_heading(cell_format('SL','text','sl center')
								  ,cell_format('Date','text','date center')
								  ,cell_format('Order No','text','bill-no center')
								  ,cell_format('Customer','text','cust-name center')
								  ,cell_format('Agent','text','agent-name center')
								  ,cell_format('Gold','text','gold-value center')
								  ,cell_format('Gold Rate','text','gold-rate center')
								  ,cell_format('Cash','text','cash center')
								  ,cell_format('Total Gold','text','total-gold center')
								  );	
		$this->table->set_footer(cell_format('Total','text','sl center','','','','5')
								  ,cell_format($gold,'gold','gold-value right')
								  ,cell_format('','text','gold-rate center')
								  ,cell_format($cash,'currency','cash right')
								  ,cell_format($total_gold,'gold','total-gold right')
								  );						 
		$this->table->set_caption("Order Report from <b>".$_GET['date_from'].' to '.$_GET['date_to'].'</b>');
		echo $this->table->generate();
				
	}
	function fine_to_gini_report(){
		$date_from=to_sql_date($_GET['date_from']);
		$date_to=to_sql_date($_GET['date_to']);
		$result=$this->Report_model->select_fine_to_gini_gross($date_from,$date_to);
		if($result==NULL){
			echo "No record found";
			return;
		}
		$this->load->library('table');
		foreach($result->result() as $row){
			$this->table->add_row(cell_format($row->material_name,'text','material-name'),cell_format($row->material_value,'gold','material-value right'));
		}
		$this->table->set_template(default_table_template('id="fine-to-gini-table"'));
		$this->table->set_caption("Fine to Gini Report from <b>".$_GET['date_from'].' to '.$_GET['date_to'].'</b>');
		echo $this->table->generate();
	}
	function get_business_status_report(){
		$date_from=to_sql_date($_GET['date_from']);
		$date_to=to_sql_date($_GET['date_to']);
		$result=$this->Report_model->select_company_info();
		echo '<h1>';
		echo 'Report for '.$result->pos ;
		echo '</h1></br></br>';
		$result=$this->Report_model->select_material_valuation();
		
		if($result==NULL){
			echo "No record found";
			return;
		}
		$this->load->library('table');
		$this->table->set_template(default_table_template('id="material-valuation"'));
		$sl=0;
		$material_in_pure=0;
		$row_class="odd";
		foreach($result->result() as $row){
			
			$this->table->add_row(cell_format(++$sl,'integer','sl center')
								  ,cell_format($row->rm_name,'text','rm-name left')
								  ,cell_format($row->material,'gold','material right')
								  ,cell_format($row->pure,'gold','pure right')
								  );
			$material_in_pure+=$row->pure;
			$row_class=$row_class=="odd"?"even":"odd";
			$this->table->add_row_class($row_class);
			
		}
		$this->table->set_heading(cell_format('SL','text','sl center')
								  ,cell_format('Material','text','date center')
								  ,cell_format('Value','text','bill-no center')
								  ,cell_format('Pure','text','cust-name center')
								  );	
		$this->table->set_footer(cell_format('Total','text','total','','','','3')
								,cell_format($material_in_pure,'gold','pure right')
								);
		$this->table->set_caption("Material valuation Now : Converted to Pure ");
		echo $this->table->generate();
		$this->table->clear();
		$result=$this->Report_model->select_cash_n_hand_to_employee();
		$staff_lc=0;
		if($result==NULL){
			$staff_lc=0;
		}
		$staff_lc=$result->cash;
		echo '</br> Employees Cash in Hand: '.$staff_lc;
		echo '<hr>';
		$result=$this->Report_model->select_customer_valuation();
		
		if($result==NULL){
			echo "No record found";
			return;
		}
		$customer_in_pure=$result->gold;
		$customer_in_lc=$result->lc;
		echo "</br>Dues to Customer : ";
		echo "</br>Gold ".number_format($result->gold,3)." g";
		echo "</br>LC  Rs. ".number_format($result->lc,2);
		echo '<hr>';	
		$result=$this->Report_model->select_item_valuation();	
		if($result==NULL){
			echo "No record found";
			return;
		}
		$stock_in_pure=$result->gold;
		$stock_in_lc=$result->lc;
		echo "</br>Item Stock Validation : ";
		echo "</br>Qty ".$result->qty.' pcs';
		echo "</br>Gold ".number_format($result->gold,3)." g";
		echo "</br>LC  Rs. ".number_format($result->lc,2);
		echo '<hr>';	
		$result=$this->Report_model->select_job_valuation();	
		if($result==NULL){
			echo "No record found";
			return;
		}
		$job_in_pure=$result->gold;
		$job_in_lc=$result->lc;
		echo "</br>Working progress valuation: ";
		echo "</br>Qty ".$result->qty.' pcs';
		echo "</br>Gold ".number_format($result->gold,3)." g";
		echo "</br>LC  Rs. ".$job_in_lc;
		echo '<hr>';
		
		echo "</br><b>Consolidated Valuation</b>";
		echo "</br>Gold ";
		echo number_format($job_in_pure+$stock_in_pure+$customer_in_pure+$material_in_pure,3)." g";
		echo "</br>LC  Rs. ";
		echo $job_in_lc+$stock_in_lc+$customer_in_lc+$staff_lc;
		echo '<hr>';
		//echo 'AS on :' . $this->db->query("select date_format(now(),'%D-%M-%Y %H:%m') as tr_time")->row()->tr_time;
		$date = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
 		echo 'Time : '. $date->format('d/m/Y H:i:s');
	}
	function display_individual_customer_payment_details(){
		if(!isset($_GET['cust_id'])){
			return;
		}
		$result=$this->Report_model->select_individual_customer_report_by_cust_id($_GET['cust_id']);
		if($result==NULL){
			echo "No Record Found";
			return;
		}
		$this->load->library('table');
		$gold_balance=0;
		$lc_balance=0;
		foreach($result->result() as $row){
			$gold_balance+=$row->gold*$row->tr_type;
			$lc_balance+=$row->lc*$row->tr_type;
			$this->table->add_row(cell_format($row->tr_date2,'text','date center')
								  ,cell_format($row->particulars,'text','particulars left')
								  ,cell_format(anchor_popup(site_url()."/bill_controller/display_bill?bill_no=".$row->reference,$row->reference),'text','reference left')
								  //,cell_format($row->reference,'text','reference left')
								  ,cell_format($row->qty,'integer','qty right')
								  ,cell_format($row->gold,'gold','gold right')
								  ,cell_format($row->lc,'currency','lc right')
								  ,cell_format($gold_balance,'gold','gold-balance right')
								  ,cell_format($lc_balance,'currency','lc-balance right')
								  );
			$this->table->add_row_class($row->comment);
		}
		$this->table->set_template(default_table_template('id="individual-customer-status"'));
		$this->table->set_heading(cell_format('Date','tex','date center')
								  ,cell_format('Particulars','tex','particulars left')	
								  ,cell_format('Reference','text','reference left')
								  ,cell_format('Pcs','text','qty center')
								  ,cell_format('Gold','text','gold center')
								  ,cell_format('LC','text','lc center')
								  ,cell_format('Gold Balance','text','gold-balance center')
								  ,cell_format('LC Balance','text','lc-balance center')
		);
		$result=$this->Customer_model->get_customer_by_cust_id($_GET['cust_id']);
		$this->table->set_caption('Customer Name: '.$result->cust_name.'</br>'.' Address: '.$result->cust_address.' '.'City '.$result->city);
		echo $this->table->generate();
	}
	function material_withdrawn_by_date(){
		$date_from=to_sql_date($_GET['date_from']);
		$date_to=to_sql_date($_GET['date_to']);
		$material_id=0;
		if(isset($_GET['material_id'])){
			$material_id=$_GET['material_id'];
		}
		?>
		<label>Select Material</label>
		<select id="material-id">
			<option value="0">--Select--</option>
			<option value="31">Bangle Pan</option>
			<option value="33">DAL</option>
			<option value="36">Pure Gold</option>
			<option value="41">88 Guine</option>
			<option value="42">90 Guine</option>
			<option value="48">92 Guine</option>
			<option value="45">Nitick</option>
		</select>
		<input type="button" id="show-materials" value="Select"/>
		<?php

		$result=$this->Report_model->material_withdrawn_by_employee($this->session->userdata('employee_id'),$date_from,$date_to,2,$material_id);
		if($result==NULL){
			echo "No Record Found";
			return;
		}
		?>

		<?php
		$this->load->library('table');
		foreach($result->result() as $row){
			$this->table->add_row(cell_format($row->tr_date,'text','date center')
								  ,cell_format($row->rm_name,'text','material left')
								  ,cell_format($row->inward,'gold','reference right')
								  );
		}
		$this->table->set_template(default_table_template('id="rm-withdrawn-by-employee"'));
		$this->table->set_heading(cell_format('Date','tex','date center')
								  ,cell_format('Material','tex','material left')	
								  ,cell_format('Qty','text','qty center')
								  
								  );
		
		
		echo $this->table->generate();
		echo '<hr>';
		$result=$this->Report_model->aggregate_material_withdrawn_by_employee($this->session->userdata('employee_id'),$date_from,$date_to,2);
		$this->table->clear();
		if($result==NULL){
			echo "No Record Found";
			return;
		}
		
		$this->load->library('table');
		foreach($result->result() as $row){
			$this->table->add_row(cell_format($row->rm_name,'text','material left')
								  ,cell_format($row->inward,'gold','reference right')
								  );
		}
		$this->table->set_template(default_table_template('id="aggregate-rm-withdrawn-by-employee"'));
		$this->table->set_heading(cell_format('Material','tex','material left')	
								  ,cell_format('Qty','text','qty center')
								  
								  );
		
		
		echo $this->table->generate();
		echo '<hr>';
		$result=$this->Report_model->cash_withdrawn_by_emp($this->session->userdata('employee_id'),$date_from,$date_to);
		$this->table->clear();
		if($result==NULL){
			echo "No Record Found";
			return;
		}
		
		$this->load->library('table');
		$cash=0;
		foreach($result->result() as $row){
			$this->table->add_row(cell_format($row->tr_date,'text','date center')
								  ,cell_format($row->cash,'currency','reference right')
								  );
			$cash+=$row->cash;
		}
		$this->table->set_template(default_table_template('id="cash-withdrawn-by-employee"'));
		$this->table->set_heading(cell_format('Date','tex','date left')	
								  ,cell_format('Cash','text','cash center')
								  
								  );
		
		$this->table->set_footer(cell_format('Total','tex','total center')
								,cell_format($cash,'currency')
								);
		echo $this->table->generate();
	}
	function get_admin_job_report(){
		$date_from=to_sql_date($_GET['date_from']);
		$date_to=to_sql_date($_GET['date_to']);
		$result=$this->Report_model->select_admin_job_report($date_from, $date_to);
		if($result==NULL){
			echo "No Record Found";
			return;
		}
		$this->load->library('table');
		$total_ploss=0;
		$total_pan=0;
		$total_mv=0;
		$total_gain=0;
		$pcs=0;
		$amount=0;
		foreach($result->result() as $row){
			$ploss_fine=$row->actual_ploss*$row->rm_gold/100;
			$pan_fine=$row->pan_send*($row->rm_gold/100)/3;
			$mv_fine=$row->actual_mv;
			$gain=$ploss_fine+$mv_fine;
			$total_gain+=$gain;
			$total_ploss+=$ploss_fine;
			$total_pan+=$pan_fine;
			$total_mv+=$mv_fine;
			$pcs+=$row->pieces;
			$amount+=$row->actual_price;
			$this->table->add_row(cell_format($row->tr_date,'text','date center')
								  ,cell_format($row->job_id,'text','job right',$row->job_id)
								  ,cell_format($row->pieces,'integer','qty right')
								  ,cell_format($ploss_fine,'gold','actual-ploss right')
								  ,cell_format($pan_fine,'gold','pan_send right')
								  ,cell_format($mv_fine,'gold','actual_mv right')
								  ,cell_format($gain,'gold','total-gain right')
								  ,cell_format($row->actual_price,'currency','actual-price right')
								  );
		}
		$this->table->set_heading(cell_format('Date','tex','date center')	
								  ,cell_format('JOB ID','text','job center')
								  ,cell_format('Pcs','text','qty center')
								  ,cell_format('PLOSS(f)','text','actual-ploss center')
								  ,cell_format('PAN(f)','text','pan_send center')
								  ,cell_format('MV(f)','text','actual_mv center')
								  ,cell_format('Gain(f)','text','total-gain center')
								  ,cell_format('Amount','text','actual-price center')
								  );
		$this->table->set_footer(	
								  cell_format('Total','text','job center','','','','2')
								  ,cell_format($pcs,'text','qty right')
								  ,cell_format($total_ploss,'gold','actual-ploss right')
								  ,cell_format($total_pan,'gold','pan_send right')
								  ,cell_format($total_mv,'gold','actual_mv right')
								  ,cell_format($total_gain,'gold','total-gain right')
								  ,cell_format($amount,'currency','actual-price right')
								  );
		
		$this->table->set_template(default_table_template('id="admin-job-details"'));
		echo $this->table->generate();
	}
	function get_owner_material_submit(){
		$date_from=to_sql_date($_GET['date_from']);
		$date_to=to_sql_date($_GET['date_to']);
		$this->load->library('table');
		$this->table->clear();
		$result=$this->Report_model->select_owner_material_group_submit($date_from, $date_to);
		if($result==NULL){
			echo "No Record Found";
			return;
		}
		foreach($result->result() as $row){
		$this->table->add_row(cell_format($row->emp_name,'text','emp_name left')
							,cell_format($row->rm_name,'text','rm_name left')
							,cell_format($row->total_inward,'gold','total_inward right')
									
							);
		}
		$this->table->set_heading(cell_format('Employee','text','emp_name center')
							,cell_format('Material','text','rm_name center')
							,cell_format('Value','text','total_inward center')
							);
		$this->table->set_template(default_table_template('id="admin-submit-details2"'));
		$this->table->set_caption("Summary between ".$_GET['date_from']." to ".$_GET['date_to']);
		echo $this->table->generate();
		echo '<hr>';
		$result=$this->Report_model->select_owner_material_submit($date_from, $date_to);
		if($result==NULL){
			echo "No Record Found";
			return;
		}
		
		
		foreach($result->result() as $row){
			$this->table->add_row(	cell_format($row->tr_date,'text','date center')
									,cell_format($row->emp_name,'text','emp_name left')
									,cell_format($row->rm_name,'text','rm_name left')
									,cell_format($row->inward,'gold','inward right')
									
								 );
		}
		$this->table->set_heading(  cell_format('Date','text','date center')
									,cell_format('Employee','text','emp_name left')
									,cell_format('Material','text','rm_name left')
									,cell_format('Value','text','inward right')
									
								 );
		$this->table->set_template(default_table_template('id="admin-submit-details"'));
		$this->table->set_caption("Material Submit by owner between ".$_GET['date_from']." to ".$_GET['date_to']);
		echo $this->table->generate();
	}
	function get_owner_readymade_item_submit(){
		
		$date_from=to_sql_date($_GET['date_from']);
		$date_to=to_sql_date($_GET['date_to']);
		$this->load->library('table');
		$result=$this->Report_model->select_owner_readymade_item_submit($date_from, $date_to);
		
		if($result==NULL){
			echo "No Record Found";
			return;
		}
		$sl=0;
		$lc=0;
		$gold=0;
		$pcs=0;
		foreach($result->result() as $row){
			$lc+=$row->labour_charge;
			$gold+=$row->gold;
			$pcs+=$row->qty;
			$this->table->add_row(	cell_format(++$sl,'integer','sl center')
									,cell_format($row->tr_date,'text','date center')
									,cell_format($row->tag,'text','tag center')
									,cell_format($row->model_no,'text','model_no center')
									,cell_format($row->model_size,'text','model_size right')
									,cell_format($row->qty,'integer','qty right')
									,cell_format($row->gold,'gold','gold right')
									,cell_format($row->labour_charge,'currency','labour_charge right')
									
									,cell_format('<span title="'.$row->agent_name.'">'.$row->agent.'</span>','text','agent left')
									,cell_format($row->employee,'text','employee left')
									//,cell_format($row->bill_no,'text','bill_no center')
									,cell_format(anchor_popup(site_url()."/bill_controller/display_bill?bill_no=".$row->bill_no,$row->bill_no),'text','bill_no center')
			);
			$this->table->set_heading(cell_format('SL','text','sl center')
									,cell_format('Date','text','date center')
									,cell_format('Tag','text','tag center')
									,cell_format('Model','text','model_no center')
									,cell_format('Size','text','model_size center')
									,cell_format('Qty','text','qty center')
									,cell_format('Gold','text','gold center')
									,cell_format('LC','text','labour_charge center')
									,cell_format('Agent','text','agent center')
									,cell_format('Employee','text','employee center')
									,cell_format('Bill','text','bill_no center')
			);
			$this->table->set_footer(cell_format('Total','text','sl center','','','','5')
									,cell_format($pcs,'text','qty right')
									,cell_format($gold,'gold','gold right')
									,cell_format($lc,'currency','labour_charge right')
									,cell_format('','text','agent right')
									,cell_format('','text','employee right')
									,cell_format('','text','bill_no right')
			);
		}
		
		$this->table->set_template(default_table_template('id="admin-readymade-submit-details"'));
		$this->table->set_caption("Readymade Item Submit by owner between ".$_GET['date_from']." to ".$_GET['date_to']);
		echo $this->table->generate();
	}
	function save_business_status_report(){
		$result=$this->Report_model->select_material_valuation();
		$material_in_pure=0;
		if($result!=null){
			foreach($result->result() as $row){
				$material_in_pure+=$row->pure;
			}
		}
		
		
		
		$result=$this->Report_model->select_cash_n_hand_to_employee();
		$staff_lc=0;
		if($result==NULL){
			$staff_lc=0;
		}
		$staff_lc=$result->cash;
		
		$result=$this->Report_model->select_customer_valuation();
		$customer_in_pure=$result->gold;
		$customer_in_lc=$result->lc;
		
		
		$result=$this->Report_model->select_item_valuation();	
		if($result==NULL){
			echo "No record found";
			return;
		}
		$stock_in_pure=$result->gold;
		$stock_in_lc=$result->lc;
		
		$result=$this->Report_model->select_job_valuation();	
		if($result==NULL){
			echo "No record found";
			return;
		}
		$job_in_pure=$result->gold;
		$job_in_lc=$result->lc;
		$consolidated_gold=$job_in_pure+$stock_in_pure+$customer_in_pure+$material_in_pure;
		$consolidated_lc= $job_in_lc+$stock_in_lc+$customer_in_lc+$staff_lc;
		$result=$this->Report_model->insert_business_status($material_in_pure,$staff_lc,$customer_in_pure,$customer_in_lc,$stock_in_pure,$stock_in_lc,$job_in_pure,$job_in_lc,$consolidated_gold,$consolidated_lc);
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
		$row_array['msg']="Status Updated";
		$row_array['sql']="";
		$row_array['message_type']='information';
		array_push($return_arr,$row_array);
		echo json_encode($return_arr);
	}
	function get_status_report_admin(){
		$date_from=to_sql_date($_GET['date_from']);
		$date_to=to_sql_date($_GET['date_to']);
		$this->load->library('table');
		$result=$this->Report_model->select_admin_business_status($date_from, $date_to);
		if($result==NULL){
			echo "No Record Found";
			return;
		}
		$this->table->set_template(default_table_template('id="admin-business-report"'));
		foreach($result->result() as $row){
			$this->table->add_row(cell_format($row->tr_date,'text','date center')
								  ,cell_format($row->consolidated_gold,'gold','consolidated-gold right')
								  ,cell_format($row->gold_change,'gold','gold-change right')
								  
								  ,cell_format($row->consolidated_lc,'currency','consolidated-lc right')
								  ,cell_format($row->lc_change,'currency','lc-change right')
								  
								  
								  ,cell_format($row->qty_change,'integer','qty-change right')
								  
								  
								 // ,cell_format($row->material_valuation,'gold','material-valuation right')
								 // ,cell_format($row->cash_in_hand,'currency','cash-in-hand right')
								  ,cell_format($row->customer_gold,'gold','customer-gold right')
								  ,cell_format($row->customer_lc,'currency','customer-lc right')
								  
								  ,cell_format($row->stock_gold,'gold','stock-gold right')
								  ,cell_format($row->stock_lc,'currency','stock-lc right')
								  ,cell_format($row->job_gold,'gold','job-gold right')
								  ,cell_format($row->job_lc,'currency','job-lc right')
								  );
		}
		$this->table->set_heading(cell_format('Date','text','date center')
								  ,cell_format('Gold Valuation','text','consolidated-gold center')
								  ,cell_format('Gold Change','text','gold-change center')
								  
								  ,cell_format('LC valuation','text','consolidated-lc center')
								  ,cell_format('LC Change','text','lc-change center')
								  
								  ,cell_format('Qty Change','text','qty-change center')
								 
								 // ,cell_format('Material Gold','text','material-valuation center')
								 // ,cell_format('Cash in Hand','text','cash-in-hand center')
								  ,cell_format('Customer Gold','text','customer-gold center')
								  ,cell_format('Customer LC','text','customer-lc center')
								 
								  ,cell_format('Stock Gold','text','stock-gold center')
								  ,cell_format('Stock LC','text','stock-lc center')
								  ,cell_format('Job Gold','text','job-gold center')
								  ,cell_format('Job LC','text','job-lc center')
								  );
		echo $this->table->generate();
	}
	function get_karigar_wise_job_report(){
		$result=$this->Employee_model->get_employees_by_depaertment(15);//15 is gorit
		$gorit_employees[0]='--';
		foreach($result->result() as $row){
			$gorit_employees[$row->emp_id]=$row->emp_name;
		}
		start_div('id="karigar-div"');
		echo form_dropdown('emp_id',$gorit_employees,0,'id="emp-id"');
		echo form_button('Show','Show','id="show"');
		end_div();
	}
	function show_karigar_wise_job_report(){
		$date_from=to_sql_date($_GET['date_from']);
		$date_to=to_sql_date($_GET['date_to']);
		$emp_id=$_GET['emp_id'];
		$this->load->library('table');
		$this->table->clear();
		$result=$this->Report_model->select_job_report_by_emp_id($emp_id);
		$this->table->set_template(default_table_template('id="test-table"'));
		echo $this->table->generate($result);
		echo form_button('export_to_excel','Export to Excel','id="exportToExcel"');
		
	}
	function get_agentwise_dues(){
		$date_from=to_sql_date($_GET['date_from']);
		$date_to=to_sql_date($_GET['date_to']);
		$this->load->library('table');
		$result=$this->Report_model->select_agentwise_dues();
		if($result==NULL){
			echo "No Record Found";
			return;
		}
		$this->table->set_template(default_table_template('id="agent-wise-dues"'));
		$total_gold_due=0;
		$total_lc_due=0;
		foreach($result->result() as $row){
			$total_gold_due+=$row->gold_due;
			$total_lc_due+=$row->lc_due;
			$this->table->add_row(cell_format($row->agent_id,'text','agent-id left')
								  ,cell_format($row->agent_name,'text','agent-name left')
								  ,cell_format($row->gold_due,'gold','gold-due right')
								  ,cell_format($row->lc_due,'currency','lc-due right')
								  );
		}
		$this->table->add_row(cell_format('Total','text','agent-id left')
								  ,cell_format(' ','text','agent-name left')
								  ,cell_format($total_gold_due,'gold','gold-due right')
								  ,cell_format($total_lc_due,'currency','lc-due right')
								  );
		$this->table->set_heading(cell_format('Agent ID','text','agent-id center')
								  ,cell_format('Agent Name','text','agent-name center')
								  ,cell_format('Gold Due','text','gold-due center')
								  ,cell_format('LC Due','text','lc-due center')
								  );
		$this->table->set_caption("Agentwise Dues");
		echo $this->table->generate();
	}
	function get_customer_inward_and_outward_report(){
		$date_from=to_sql_date($_GET['date_from']);
		$date_to=to_sql_date($_GET['date_to']);
		$agent_id=$_GET['agent_id'];
		$this->load->library('table');
		$result=$this->Report_model->select_customer_inward_and_outward_record($date_from, $date_to,$agent_id);
		if($result==NULL){
			echo "No Record Found";
			return;
		}
		$this->table->set_template(default_table_template('id="agent-wise-customer-inward-outward-report"'));
		$sl=0;
		$qty=0;
		$gold=0;
		$gold_received=0;
		$lc=0;
		$lc_received=0;
		foreach($result->result() as $row){
			
			$this->table->add_row(cell_format(++$sl,'integer','sl right')
								  ,cell_format($row->cust_id,'text','cust-id left')
								  ,cell_format($row->customer,'text','cust-name left')
								  ,cell_format($row->city,'text','city left')
								  ,cell_format($row->qty,'integer','qty right')
								  ,cell_format((double)number_format($row->gold,3),'gold','gold right')
								  ,cell_format((double)number_format($row->received_gold,3),'gold','received-gold right')
								  ,cell_format($row->lc,'currency','lc right')
								  ,cell_format($row->received_lc,'currency','received-lc right')
								  ,cell_format(number_format(($row->gold) - ($row->received_gold),3),'gold','net-gold right')
								  ,cell_format($row->lc-$row->received_lc,'currency','received-lc right')
								  ); 
			$qty+=$row->qty;
			$gold+=(double)$row->gold;
			$gold_received+=$row->received_gold;
			$lc+=$row->lc;
			$lc_received+=$row->received_lc;
		}
		$this->table->set_footer(cell_format('Total','text','center','','','','4')
								 // ,cell_format('','text','cust-id left')
								 // ,cell_format('Total','text','cust-name left')
								 // ,cell_format('','text','city left')
								  ,cell_format($qty,'integer','qty right')
								  ,cell_format((double)number_format($gold,3),'gold','gold right')
								  ,cell_format((double)number_format($gold_received,3),'gold','received-gold right')
								  ,cell_format($lc,'currency','lc right')
								  ,cell_format($lc_received,'currency','received-lc right')
								  ,cell_format((double)number_format($gold - $gold_received,3),'gold','net-gold right')
								  ,cell_format($lc-$lc_received,'currency','net-lc right')
								  );
		$this->table->set_heading(cell_format('SL','text','sl right')
								  ,cell_format('ID','text','cust-id left')
								  ,cell_format('Customer','text','cust-name left')
								  ,cell_format('City','text','city left')
								  ,cell_format('Qty','text','qty right')
								  ,cell_format('Gold','text','gold right')
								  ,cell_format('Gold Received','text','received-gold right')
								  ,cell_format('LC','text','lc right')
								  ,cell_format('LC Received','text','received-lc right')
								  ,cell_format('Net Gold','text','net-gold right')
								  ,cell_format('Net LC','text','net-lc right')
								  );
		echo $this->table->generate();
	}
	// version 10.1
	function misc_report_facade(){
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
			,'reports/misc_report'
			,'print_div/jQuery.print'
		);
		
		$css=array('ui-lightness/jquery-ui-1.10.2.custom'
					,'table_css/table/jquery.dataTables'
					,'jobs/show_job_details'
					,'message_box/Styles/msgBoxLight'
					,'reports/misc_report'
					);	   //for data table jquery
		
		$main_data['java_script']=$java_scripts;
		$main_data['css']=$css;
		$main_data['current_date']=get_current_date();
		$main_data['page_title']="Staff Report";
		$this -> load -> view('includes/staff/template', $this -> set_site_data('reports/misc_report_view',$main_data));
	}
	function product_details_view(){
		start_div();
			echo form_label('Tag or Job ID : ');
			echo '<input type="text" placeholder="Search..." required id="tag">';
        	echo '<input type="button" value="Search" id="search">';
		end_div();
	}
	function get_product_details(){
		$result=$this->Report_model->select_product_details($_GET['tag']);
		if($result!=NULL){
			$this->load->library('table');
			$this->table->add_row(cell_format('Order No.','text','tag'),cell_format($result->order_id,'text','description'));
			$this->table->add_row(cell_format('Product Code','text','tag'),cell_format($result->model_no,'text','description'));
			$this->table->add_row(cell_format('Product Size','text','tag'),cell_format($result->model_size,'text','description'));
			$this->table->add_row(cell_format('Qty','text','tag'),cell_format($result->qty,'text','description'));
			$this->table->add_row(cell_format('Status','text','tag'),cell_format($result->status,'text','description'));
			$this->table->add_row(cell_format('Bill No.','text','tag'), cell_format(anchor_popup(site_url()."/bill_controller/display_bill?bill_no=".$result->bill_no,$result->bill_no),'text','bill left'));
			
			$this->table->set_template(default_table_template('id="product-details-table"'));
			
			echo $this->table->generate();
//			echo "Product Code ".$result->product_code.'<br>';
			
			
		}
		echo '<br>'.img(array('src'=>'img/printer.png','class'=>'no-print printer','height'=>'25px','border'=>'0','alt'=>'No Image'));
	}
	//end of version 10.
}
?>