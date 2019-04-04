<?php
class Job_controller extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this -> load -> library('form_validation');
		//this libraru is used for validation of fields in a form
		$this -> load -> model('order_model');
		$this->load->model('job_model');
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
	
	
	
	function job_report_facade(){
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
			
		);
		
		$css=array('transaction/capital/gold_introduce'
					,'ui-lightness/jquery-ui-1.10.2.custom'
					,'table_css/table/jquery.dataTables'
					,'jobs/show_job_details'
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
		$this -> load -> view('includes/staff/template', $this -> set_site_data('jobs/show_job_details',$main_data));
	}
	function get_sold_job_details_by_job_id(){
		
		$result=$this->job_model->select_sold_job_by_job_id($_GET['job_id']);
		if($result==NULL){
			echo '<div id="msg">';
			echo 'Such item not sold to any customer';
			echo '</div>';
			echo '<div id="error">error</div>';
			echo '<div id="report">';
			echo "Select another item";
			echo '</div>';	
		}else{
			echo '<div id="msg">';
			echo 'Item selected';
			echo '</div>';
			echo '<div id="error">noerror</div>';
			echo '<div id="report">';
			echo '<b>Bill No. : </b>'.$result->bill_no.'</br>';
			echo '<b>Bill Date : </b>'.sql_date_to_dmy($result->date_of_bill).'</br>';
			echo '<b>Customer : </b>'.$result->cust_name.'</br>';
			echo '<b>Model : </b>'.$result->model_no.'-'.$result->price_code.'</br>';
			echo '<b>92 GOLD : </b>'.$result->gold_wt.' g (in Fine: <b>'.number_format($result->fine_gold,3). '</b>)'.'</br>';
			echo '<b>Gross Weight : </b>'.$result->gross_wt.' g. </br>';
			echo '<b>LC : </b> Rs.'.number_format($result->labour_charge,2).' (Rupees '.convert_number_to_words($result->labour_charge).')</br>';
			echo '</div>';	
		}
	}
	
	function new_job_facade(){
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
			,'jobs/new_job'
			,'print_element/printThis'
		);
		
		$css=array('transaction/capital/gold_introduce'
					,'ui-lightness/jquery-ui-1.10.2.custom'
					,'table_css/table/jquery.dataTables'
					,'jobs/new_job'
					,'message_box/Styles/msgBoxLight'
					,'materials/stock_all_employees'
					);	   //for data table jquery
		
		$main_data['user_id']=$this->session->userdata('user_id');
		$main_data['java_script']=$java_scripts;
		$main_data['css']=$css;
		$main_data['current_date']=get_current_date();
		$main_data['delivery_date']=date("d/m/Y",strtotime("+5 days"));
		//$main_data['user_key']=$this->session->userdata('ip_address').'-'.date_serial_number(get_current_date()).'-'.rand(1000,9999);
		$main_data['user_key']=uniqid('', true);
		$main_data['page_title']="Diamond New Order";
		$this -> load -> view('includes/staff/template', $this -> set_site_data('jobs/new_job',$main_data));
	}
	function show_orders_ajax(){
		$result=$this->job_model->select_fresh_orders();
		if($result==NULL){
			echo '<div id="msg">';
			echo "No Order Found";
			echo '</div>';
			echo '<div id="error">error</div>';
			echo '<div id="report">';
			echo 'No fresh order selected';
			echo '</div>';
		}else{
			echo '<div id="msg">';
			echo "Fresh order selected";
			echo '</div>';
			echo '<div id="error">noerror</div>';
			echo '<div id="report">';
			foreach($result->result() as $row){
				echo '<span class="order-id">'.$row->order_id.'</span>';
				echo img(array('src'=>'img/gold_send.png','class'=>'save column7','height'=>'15px','border'=>'0','alt'=>'save'));
				echo $row->cust_name.'</br>';
			}
			
			echo '</div>';
		}
	}
	function select_order_no_by_order_id_ajax(){
		$result=$this->job_model->select_fresh_orders_from_order_details_by_order_id($_GET['order_id']);
		if($result==NULL){
			echo '<div id="msg">';
			echo "No Order Found";
			echo '</div>';
			echo '<div id="error">error</div>';
			echo '<div id="report">';
			echo 'No fresh order selected';
			echo '</div>';
		}else{
			echo '<div id="msg">';
			echo "Fresh order selected";
			echo '</div>';
			echo '<div id="error">noerror</div>';
			echo '<div id="report">';
			echo 'Challan No. '.$_GET['order_id'];
			$this->load->library('table');
			foreach($result->result() as $row){
				$this->table->add_row(cell_format($row->sl_no,'text','sl')
									  ,cell_format($row->product_code,'text','model')
									  ,cell_format($row->qty,'integer','qty')
									  ,img(array('src'=>'img/products/'.$row->product_code.'.jpg','class'=>'save column7','height'=>'25px','width'=>'100px','border'=>'0','alt'=>'no Image'))
									  ,$row->status>0?'Jobed':'Fresh'
									  );
				$this->table->add_row_id($row->order_no);
				if($row->status>0){
					$this->table->add_row_class('jobed');
				}else{
					$this->table->add_row_class('fresh');
				}
			}
			$this->table->set_template(default_table_template('id="order-no-table" class="data-table"'));
			$this->table->set_heading('SL','Model','Qty','sample','Status');
			echo $this->table->generate();
			echo '</div>';
		}
	}
	function send_to_job_ajax(){
		$roder_details_order_no=$_GET['order_no'];
		$result=$this->job_model->get_order_from_order_details_by_order_no($roder_details_order_no);
		if($result==NULL){
			echo '<div id="msg">';
			echo "No Order Found";
			echo '</div>';
			echo '<div id="error">error</div>';
			echo '<div id="report">';
			echo 'No order found';
			echo '</div>';
		}else{
			if($result->status>0){
				echo '<div id="msg">';
				echo "Already Jobed";
				echo '</div>';
				echo '<div id="error">noerror</div>';
				echo '<div id="report">';
				
				$job_details=$this->job_model->get_job_by_order_id_and_order_no($roder_details_order_no);
				echo '</br>JOB ID: '.$job_details->job_id.form_input('job_id',set_value('job_id',$job_details->job_id),'id="job-id" class="hidden"');
				echo '</br>Challan No.: '.$job_details->order_id.'/'.$job_details->order_serial;
				echo '</br>Model : &nbsp <span id="model-no">'.$job_details->product_code.'<span>&nbsp&nbsp pcs : '.$job_details->pieces.'&nbsp&nbsp';
				echo '</br>Size : &nbsp'.$job_details->product_size;
				echo '</br>Date : '.sql_date_to_dmy($job_details->job_date2);
				echo '&nbsp&nbsp&nbsp&nbsp&nbspDD : '.sql_date_to_dmy($job_details->dd);
				
				$this->load->library('table');
				$this->table->add_row(cell_format('Thokai :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('Tonch :','text','column3'),cell_format(number_format($job_details->rm_gold,2),'text','column4'));
				$this->table->add_row(cell_format('Ring W:','text','column1'),cell_format(' ','text','column2'),cell_format('A. Wt. :','text','column3'),cell_format(number_format($job_details->expected_gold,3),'text','column4'));
				$this->table->add_row(cell_format('Mina :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('Forming :','text','column3'),cell_format('&nbsp','text','column4'));
				$this->table->add_row(cell_format('M.C. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('Gold :','text','column3'),cell_format(number_format($job_details->gold_send,3),'text','column4 gold-input'));
				$this->table->add_row(cell_format('H.M.C. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('RGW- :','text','column3'),cell_format('&nbsp','text','column4'));
				$this->table->add_row(cell_format('H.C. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('P.wt. :','text','column3'),cell_format('&nbsp','text','column4'));
				$this->table->add_row(cell_format('Polis :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('NGR- :','text','column3'),cell_format('&nbsp','text','column4'));
				$this->table->add_row(cell_format('Dal :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('PAN+ :','text','column3'),cell_format('&nbsp','text','column4'));
				$this->table->add_row(cell_format('Bnz. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('P.Loss+ :','text','column3'),cell_format('&nbsp','text','column4'));
				$this->table->add_row(cell_format('Bati No. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('FG Wt.= :','text','column3'),cell_format('&nbsp','text','column4'));
				$this->table->add_row(cell_format('&nbsp','text','column1'),cell_format('&nbsp','text','column2'),cell_format('B.Wt=:','text','column3'),cell_format('&nbsp','text','column4'));
				
				
				$this->table->set_template(default_table_template('id="old-job-table" class="job-table"'));
				echo $this->table->generate();
				
				
				echo 'Karigar : '.$job_details->emp_name;
				echo '</br></br>';
				echo anchor_popup(site_url().'/job_controller/print_job?job_id='.$job_details->job_id,'Print this job : '.$job_details->job_id);
				echo '</div>';
			}else{
				echo '<div id="msg">';
				echo "Fresh order selected";
				echo '</div>';
				echo '<div id="error">noerror</div>';
				echo '<div id="report">';
				
				$order_details=$this->job_model->get_order_by_order_no($roder_details_order_no);
				
				echo 'Challan No.: '.form_input('challan_no',$order_details->order_id,'id="challan-no"');
				echo '<div class="hidden">Challan Serial: '.form_input('challan_sl',$order_details->sl_no,'id="challan-sl"').'</div>';
				echo '<div class="hidden"></br>Order Details No.: '.form_input('order_no',$roder_details_order_no,'id="order-no" readonly="yes"').'</div>';
				echo '</br>Model : &nbsp <span id="model-no">'.$order_details->product_code.'<span>&nbsp&nbsp pcs : '.$order_details->qty.'&nbsp&nbsp';
				echo '</br>Size : &nbsp'.$order_details->prd_size;
				echo '<input id="rm-id" value="'.$order_details->rm_id.'" class="hidden"/>';
				//echo '</br>Job Date : '.form_input('job_date',date_serial_number(get_current_date()),'id="job-date"');
				$this->load->library('table');
				$this->table->add_row(cell_format('Thokai :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('Tonch :','text','column3'),cell_format(number_format($result->rm_gold,2),'text','column4'));
				$this->table->add_row(cell_format('Ring W:','text','column1'),cell_format(' ','text','column2'),cell_format('A. Wt. :','text','column3'),cell_format(number_format($result->gold_wt,3),'text','column4'));
				$this->table->add_row(cell_format('Mina :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('Forming :','text','column3'),cell_format('&nbsp','text','column4'));
				$this->table->add_row(cell_format('M.C. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('Gold :','text','column3'),cell_format(form_input('gold',set_value('gold',0.000),'id="send-gold" class="user-input gold-input"'),'text','column4'));
				$this->table->add_row(cell_format('H.M.C. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('RGW- :','text','column3'),cell_format('&nbsp','text','column4'));
				$this->table->add_row(cell_format('H.C. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('P.wt. :','text','column3'),cell_format('&nbsp','text','column4'));
				$this->table->add_row(cell_format('Polis :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('NGR- :','text','column3'),cell_format('&nbsp','text','column4'));
				$this->table->add_row(cell_format('Dal :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('PAN+ :','text','column3'),cell_format('&nbsp','text','column4'));
				$this->table->add_row(cell_format('Bnz. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('P.Loss+ :','text','column3'),cell_format('&nbsp','text','column4'));
				$this->table->add_row(cell_format('Bati No. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('FG Wt.= :','text','column3'),cell_format('&nbsp','text','column4'));
				$this->table->add_row(cell_format('&nbsp','text','column1'),cell_format('&nbsp','text','column2'),cell_format('B.Wt=:','text','column3'),cell_format('&nbsp','text','column4'));
				
				
				$this->table->set_template(default_table_template('id="fresh-job-table" class="job-table"'));
				echo $this->table->generate();
				
				$garit_list=$this->job_model->get_employees_from_gorit();
				$karigar[0]='Select';
				foreach($garit_list->result() as $row){
					$karigar[$row->emp_id]=$row->emp_name;
				}
				echo form_label('Karigar : ');
				echo form_dropdown('karigar',$karigar,0,'id="karigar" class="user-input"');
				echo '</br>Current User : '.$this->session->userdata('employee_name').'('.$this->session->userdata('user_id').')';
				echo '</br></br></br><input type="button" value="Send to Job" id="send-to-job" />';
				echo '</div>';
			}
		}
	}
	function save_order_to_job_master_ajax(){
		//print_r($_GET);
		echo $_SERVER['REMOTE_ADDR'];
		$order_id=$_GET['challan_no'];
		$order_no=$_GET['order_details_order_no'];
		$current_ip=$_SERVER['REMOTE_ADDR'];
		echo $current_ip;
		echo '</br>';
		$result=$this->job_model->save_order_to_job_master();
		if($result['success']==1){
			echo '<div id="msg">';
			echo "order send to job";
			echo '</div>';
			echo '<div id="error">noerror</div>';
			echo '<div id="report">';
			$job_details=$this->job_model->get_job_by_job_id($result['job_id']);
				
				echo '</br>Challan No.: '.$job_details->order_id.'/'.$job_details->order_serial;
				echo '</br>Model : &nbsp <span id="model-no">'.$job_details->product_code.'<span>&nbsp&nbsp pcs : '.$job_details->pieces.'&nbsp&nbsp';
				echo '</br>Size : &nbsp'.$job_details->product_size;
				echo '</br>Date : '.sql_date_to_dmy($job_details->job_date2);
				echo '&nbsp&nbsp&nbsp&nbsp&nbspDD : '.sql_date_to_dmy($job_details->dd);
				$this->load->library('table');
				$this->table->add_row(cell_format('Thokai :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('Tonch :','text','column3'),cell_format(number_format($job_details->rm_gold,2),'text','column4'));
				$this->table->add_row(cell_format('Ring W:','text','column1'),cell_format(' ','text','column2'),cell_format('A. Wt. :','text','column3'),cell_format(number_format($job_details->expected_gold,3),'text','column4'));
				$this->table->add_row(cell_format('Mina :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('Forming :','text','column3'),cell_format('&nbsp','text','column4'));
				$this->table->add_row(cell_format('M.C. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('Gold :','text','column3'),cell_format(number_format($job_details->gold_send,3),'text','column4 gold-input'));
				$this->table->add_row(cell_format('H.M.C. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('RGW- :','text','column3'),cell_format('&nbsp','text','column4'));
				$this->table->add_row(cell_format('H.C. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('P.wt. :','text','column3'),cell_format('&nbsp','text','column4'));
				$this->table->add_row(cell_format('Polis :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('NGR- :','text','column3'),cell_format('&nbsp','text','column4'));
				$this->table->add_row(cell_format('Dal :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('PAN+ :','text','column3'),cell_format('&nbsp','text','column4'));
				$this->table->add_row(cell_format('Bnz. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('P.Loss+ :','text','column3'),cell_format('&nbsp','text','column4'));
				$this->table->add_row(cell_format('Bati No. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('FG Wt.= :','text','column3'),cell_format('&nbsp','text','column4'));
				$this->table->add_row(cell_format('&nbsp','text','column1'),cell_format('&nbsp','text','column2'),cell_format('B.Wt=:','text','column3'),cell_format('&nbsp','text','column4'));
				
				
				$this->table->set_template(default_table_template('id="old-job-table" class="job-table"'));
				echo $this->table->generate();
				echo 'Karigar : '.$job_details->emp_name;
				echo '</br></br></br>';
				echo anchor_popup(site_url().'/job_controller/print_job?job_id='.$job_details->job_id,'Print this job : '.$job_details->job_id);
				
				echo '</div>';
		}else{
			echo '<div id="msg">';
			echo "Error Saving JOB Code: ".$result['error_code'];
			echo '</div>';
			echo '<div id="error">error</div>';
			echo '<div id="report">';
			echo 'Error Saving Job';
			echo '</div>';
		}
	}
	function print_job(){
		
		//this block of code is mandatory for all facade from now
		//-------------------------------------------------------------------------
		// extention should not be given
		$java_scripts=array('UI2/jquery-ui-1.10.2.custom/js/jquery-ui-1.10.2.custom'
			,'general'
			,'message_box/Scripts/jquery.msgBox'
			,'general/validation'
		);
		
		$css=array(''
					,'ui-lightness/jquery-ui-1.10.2.custom'
					,'table_css/table/jquery.dataTables'
					,'message_box/Styles/msgBoxLight'
					);	   //for data table jquery
		
		$job_details=$this->job_model->get_job_by_job_id($_GET['job_id']);
			$main_data['challan']=$job_details->order_id.'/'.$job_details->order_serial;
			$main_data['model']= $job_details->product_code;
			$main_data['pcs']=$job_details->pieces;
			$main_data['size']=$job_details->product_size;
			$main_data['date']= '</br>Date : '.sql_date_to_dmy($job_details->job_date2).'&nbsp&nbsp&nbsp&nbsp&nbspDD : '.sql_date_to_dmy($job_details->dd);
			$this->load->library('table');
			$this->table->add_row(cell_format('Thokai :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('Tonch :','text','column3'),cell_format(number_format($job_details->rm_gold,2),'text','column4'));
			$this->table->add_row(cell_format('Ring W:','text','column1'),cell_format(' ','text','column2'),cell_format('A. Wt. :','text','column3'),cell_format(number_format($job_details->expected_gold,3),'text','column4'));
			$this->table->add_row(cell_format('Mina :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('Forming :','text','column3'),cell_format('&nbsp','text','column4'));
			$this->table->add_row(cell_format('M.C. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('Gold :','text','column3'),cell_format(number_format($job_details->gold_send,3),'text','column4 gold-input'));
			$this->table->add_row(cell_format('H.M.C. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('RGW- :','text','column3'),cell_format($job_details->gold_returned>0?number_format($job_details->gold_returned,3):'&nbsp','text','column4'));
			$this->table->add_row(cell_format('H.C. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('P.wt. :','text','column3'),cell_format('&nbsp','text','column4'));
			$this->table->add_row(cell_format('Polis :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('NGR- :','text','column3'),cell_format($job_details->nitrick_returned>0?number_format($job_details->nitrick_returned,3):'&nbsp','text','column4'));
			$this->table->add_row(cell_format('Dal :','text','column1'),cell_format($job_details->dal_send>0?number_format($job_details->dal_send,3):'&nbsp','text','column2'),cell_format('PAN+ :','text','column3'),cell_format($job_details->pan_send>0?number_format($job_details->pan_send,3):'&nbsp','text','column4'));
			$this->table->add_row(cell_format('Bnz. :','text','column1'),cell_format($job_details->bronze_send>0?number_format($job_details->bronze_send,3):'&nbsp','text','column2'),cell_format('P.Loss+ :','text','column3'),cell_format($job_details->p_loss>0?number_format((($job_details->p_loss)*($job_details->pieces)),3):'&nbsp','text','column4'));
			$this->table->add_row(cell_format('Bati No. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('FG Wt.= :','text','column3'),cell_format('&nbsp','text','column4'));
			$this->table->add_row(cell_format('&nbsp','text','column1'),cell_format('&nbsp','text','column2'),cell_format('B.Wt=:','text','column3'),cell_format($job_details->product_wt>0?number_format($job_details->product_wt,3):'&nbsp','text','column4'));
				
				
			$this->table->set_template(default_table_template('id="old-job-table" class="job-table"'));
			$job_table=$this->table->generate();
		
		
		$main_data['job_id']=$job_details->job_id;
		$main_data['user']=$this->session->userdata('employee_name').'('.$this->session->userdata('user_id').')';
		$main_data['java_script']=$java_scripts;
		$main_data['css']=$css;
		$main_data['page_title']=$_GET['job_id'];
		$main_data['job_table']=$job_table;
		$main_data['karigar']=$job_details->emp_name;
		$main_data['model']=$job_details->product_code;
		$main_data['pos']=$this->db->query("select * from company_details")->row()->pos;
		$this -> load -> view('includes/job/template', $this -> set_site_data('jobs/print_job',$main_data));
	}
	function print_job_admin(){
		
		//this block of code is mandatory for all facade from now
		//-------------------------------------------------------------------------
		// extention should not be given
		$java_scripts=array('UI2/jquery-ui-1.10.2.custom/js/jquery-ui-1.10.2.custom'
			,'general'
			,'message_box/Scripts/jquery.msgBox'
			,'general/validation'
		);
		
		$css=array(''
					,'ui-lightness/jquery-ui-1.10.2.custom'
					,'table_css/table/jquery.dataTables'
					,'message_box/Styles/msgBoxLight'
					);	   //for data table jquery
		
		$job_details=$this->job_model->get_job_by_job_id($_GET['job_id']);
			$main_data['challan']=$job_details->order_id.'/'.$job_details->order_serial;
			$main_data['customer']=$job_details->cust_name;
			$main_data['model']= $job_details->product_code;
			$main_data['pcs']=$job_details->pieces;
			$main_data['size']=$job_details->product_size;
			$main_data['date']= '</br>Date : '.sql_date_to_dmy($job_details->job_date2).'&nbsp&nbsp&nbsp&nbsp&nbspDD : '.sql_date_to_dmy($job_details->dd);
			$this->load->library('table');
			$this->table->add_row(cell_format('Customer ','text','column1'),cell_format('<span title="'.$job_details->cust_name.'">'.$job_details->cust_id.'</span>','text','column2'),cell_format('Agent ','text','column3'),cell_format($job_details->agent_id,'text','column4'));
			$this->table->add_row(cell_format('Status :','text','column1'),cell_format($job_details->status_name,'text','column2'),cell_format('Agent :','text','column3'),cell_format($job_details->agent_id,'text','column4'));
			$this->table->add_row(cell_format('Thokai :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('Tonch :','text','column3'),cell_format(number_format($job_details->rm_gold,2),'text','column4'));
			$this->table->add_row(cell_format('Ring W:','text','column1'),cell_format(' ','text','column2'),cell_format('A. Wt. :','text','column3'),cell_format(number_format($job_details->expected_gold,3),'text','column4'));
			$this->table->add_row(cell_format('Mina :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('Forming :','text','column3'),cell_format('&nbsp','text','column4'));
			$this->table->add_row(cell_format('M.C. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('Gold :','text','column3'),cell_format(number_format($job_details->gold_send,3),'text','column4 gold-input'));
			$this->table->add_row(cell_format('H.M.C. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('RGW- :','text','column3'),cell_format($job_details->gold_returned>0?number_format($job_details->gold_returned,3):'&nbsp','text','column4'));
			$this->table->add_row(cell_format('H.C. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('P.wt. :','text','column3'),cell_format(number_format($job_details->gold_send-$job_details->gold_returned,3),'text','column4'));
			$this->table->add_row(cell_format('Polis :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('NGR- :','text','column3'),cell_format($job_details->nitrick_returned>0?number_format($job_details->nitrick_returned,3):'&nbsp','text','column4'));
			$this->table->add_row(cell_format('Dal :','text','column1'),cell_format($job_details->dal_send>0?number_format($job_details->dal_send,3):'&nbsp','text','column2'),cell_format('PAN+ :','text','column3'),cell_format($job_details->pan_send>0?number_format($job_details->pan_send,3):'&nbsp','text','column4'));
			$this->table->add_row(cell_format('Bnz. :','text','column1'),cell_format($job_details->bronze_send>0?number_format($job_details->bronze_send,3):'&nbsp','text','column2'),cell_format('P.Loss+ :','text','column3'),cell_format($job_details->p_loss>0?number_format((($job_details->p_loss)*($job_details->pieces)),3):'&nbsp','text','column4'));
			$this->table->add_row(cell_format('Bati No. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('FG Wt.= :','text','column3'),cell_format('&nbsp','text','column4'));
			$this->table->add_row(cell_format('MV','text','column1'),cell_format(number_format($job_details->pieces*$job_details->markup_value,3),'text','column2'),cell_format('B.Wt=:','text','column3'),cell_format($job_details->product_wt>0?number_format($job_details->product_wt,3):'&nbsp','text','column4'));
				
				
			$this->table->set_template(default_table_template('id="old-job-table" class="job-table"'));
			$job_table=$this->table->generate();
		
		
		$main_data['job_id']=$job_details->job_id;
		$main_data['user']=$this->session->userdata('employee_name').'('.$this->session->userdata('user_id').')';
		$main_data['java_script']=$java_scripts;
		$main_data['css']=$css;
		$main_data['page_title']=$_GET['job_id'];
		$main_data['job_table']=$job_table;
		$main_data['karigar']=$job_details->emp_name;
		$main_data['model']=$job_details->product_code;
		$main_data['pos']=$this->db->query("select * from company_details")->row()->pos;
		$this -> load -> view('includes/job/template', $this -> set_site_data('jobs/print_job',$main_data));
	}
	function get_balance_from_machine_ajax(){
		$return_array=$this->job_model->get_balance_table_by_machine($this->session->userdata('machine'));
		if($return_array['result']==NULL){
				echo '<div id="msg">';
				echo "Please select from Balance Machine";
				echo '</div>';
				echo '<div id="error">error</div>';
				echo '<div id="report">';
				echo '0.00';
				echo '</div>';
		}else{
				echo '<div id="msg">';
				echo "Balance Selected";
				echo '</div>';
				echo '<div id="noerror">noerror</div>';
				echo '<div id="report">';
				echo $return_array['result']->balance;
				echo '</div>';
		}
	}
	function job_phase1_facade(){
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
			,'jobs/phaseI'
			,'print_element/printThis'
		);
		
		$css=array('transaction/capital/gold_introduce'
					,'ui-lightness/jquery-ui-1.10.2.custom'
					,'table_css/table/jquery.dataTables'
					,'message_box/Styles/msgBoxLight'
					,'jobs/job_phase1'
					,'materials/stock_all_employees'
					);	   //for data table jquery
		
		$main_data['user_id']=$this->session->userdata('user_id');
		$main_data['java_script']=$java_scripts;
		$main_data['css']=$css;
		$main_data['current_date']=get_current_date();
		$main_data['delivery_date']=date("d/m/Y",strtotime("+5 days"));
		//$main_data['user_key']=$this->session->userdata('ip_address').'-'.date_serial_number(get_current_date()).'-'.rand(1000,9999);
		$main_data['user_key']=uniqid('', true);
		$main_data['page_title']="Diamond New Order";
		$this -> load -> view('includes/staff/template', $this -> set_site_data('jobs/job_phase1',$main_data));
	}
	function show_jobs_in_phaseI_ajax(){
		$result=$this->job_model->get_phaseI_jobs();
		if($result==NULL){
			echo '<div id="msg">';
			echo "No Order Found";
			echo '</div>';
			echo '<div id="error">error</div>';
			echo '<div id="report">';
			echo 'No fresh order selected';
			echo '</div>';
		}else{
			echo '<div id="msg">';
			echo "Fresh order selected";
			echo '</div>';
			echo '<div id="error">noerror</div>';
			echo '<div id="report">';
			foreach($result->result() as $row){
				echo '<div class="job-phseI-div">';
				echo '<span class="job-id">'.$row->job_id.'</span>';
				echo img(array('src'=>'img/gold_send.png','class'=>'save column7','height'=>'15px','border'=>'0','alt'=>'save'));
				echo $row->emp_name;
				echo img(array('src'=>'img/gold_send.png','class'=>'save column7','height'=>'15px','border'=>'0','alt'=>'save'));
				echo $row->order_id.'/'.$row->order_serial.'</br>';
				echo '</div>';
			}
			echo '</div>';
		}
	}
	function select_job_by_job_id_ajax(){
			$job_id=$_GET['job_id'];
			$job_details=$this->job_model->get_job_by_job_id($_GET['job_id']);
			if($job_details==NULL){
				echo '<div id="error">error</div>';
				echo '<div id="msg">';
					echo "No such job in this phase";
				echo '</div>';
				echo '<div id="report">';
					echo 'Select proper JOB';
				echo '</div>';
				return;
			}
				
			echo '<div id="error">noerror</div>';
			echo '<div id="report">';
			$challan=$job_details->order_id.'/'.$job_details->order_serial;
			$model= $job_details->product_code;
			$pcs=$job_details->pieces;
			$size=$job_details->product_size;
			$date= '</br>Date : '.sql_date_to_dmy($job_details->job_date2).'&nbsp&nbsp&nbsp&nbsp&nbspDD : '.sql_date_to_dmy($job_details->dd);
			echo '<span id="rm-id" class="hidden">'.$job_details->rm_id.'</span>';
			echo '</br>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<b>JOB-ID '.'<span id="job-id">'.$job_id.'</span></b></b>';
			echo '</br>Challan No.: '.$challan;
			echo $date;
			echo '</br>Model <b>'.$model.'-'.$job_details->price_code.'</b> &nbsp&nbsp Pcs <b>'.$pcs.'</b> &nbsp&nbspSize <b>'.$size.'</b>';
			
			$this->load->library('table');
			$this->table->add_row(cell_format('Thokai :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('Tonch :','text','column3'),cell_format(number_format($job_details->rm_gold,2),'text','column4'));
			$this->table->add_row(cell_format('Ring W:','text','column1'),cell_format(' ','text','column2'),cell_format('A. Wt. :','text','column3'),cell_format(number_format($job_details->expected_gold,3),'text','column4'));
			$this->table->add_row(cell_format('Mina :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('Forming :','text','column3'),cell_format('&nbsp','text','column4'));
			$this->table->add_row(cell_format('M.C. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('Gold :','text','column3'),cell_format(number_format($job_details->gold_send,3),'text','column4'));
			$this->table->add_row(cell_format('H.M.C. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('RGW- :','text','column3'),cell_format(form_input('return_gold',set_value('return_gold',0.000),'id="return-gold" class="balance_machine gold-input"'),'text','column4'));
			$this->table->add_row(cell_format('H.C. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('P.wt. :','text','column3'),cell_format('&nbsp','text','column4'));
			$this->table->add_row(cell_format('Polis :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('NGR- :','text','column3'),cell_format('&nbsp','text','column4'));
			$this->table->add_row(cell_format('Dal :','text','column1'),cell_format(form_input('dal_used',set_value('dal_used',0.000),'id="dal_used" class="balance_machine gold-input"'),'text','column2'),cell_format('PAN+ :','text','column3'),cell_format('&nbsp','text','column4'));
			$this->table->add_row(cell_format('Bnz. :','text','column1'),cell_format(form_input('bnz_used',set_value('bnz_used',0.000),'id="bnz_used" class="balance_machine gold-input"'),'text','column2'),cell_format('P.Loss+ :','text','column3'),cell_format(number_format(($job_details->p_loss)*($job_details->pieces),3),'text','column4'));
			$this->table->add_row(cell_format('Bati No. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('FG Wt.= :','text','column3'),cell_format('&nbsp','text','column4'));
			$this->table->add_row(cell_format('&nbsp','text','column1'),cell_format('&nbsp','text','column2'),cell_format('B.Wt=:','text','column3'),cell_format('&nbsp','text','column4'));
		
			$this->table->set_template(default_table_template('id="old-job-table" class="job-table"'));
			$job_table=$this->table->generate();
			echo $job_table;
			echo '<span id="employee-id" class="hidden">'.$job_details->emp_id.'</span>';
			echo '<label for="">Karigar : </label>';
			echo '<b>'.$job_details->emp_name.'</b>';
			echo '</br><span id="user-id" class="hidden">'.$this->session->userdata('employee_id').'</span>';
			$user=$this->session->userdata('employee_name').'('.$this->session->userdata('user_id').')';
			echo '</br>User: <b>'.$user.'</b>';
			echo '</br>Click Here to '.form_submit('submit','Submit','id=submit');
			echo '</div>';
	}
	function save_phaseI_ajax(){
			$result=$this->job_model->save_phaseI();
			if($result['success']==1){
				echo '<div id="msg">';
				echo "JOB SEND TO PHASE II";
				echo '</div>';
				echo '<div id="error">noerror</div>';
				echo '<div id="report">';
				echo 'Job send to Phase II';
				echo '</div>';
			}else{
				echo '<div id="msg">';
				echo "Error Saving Job";
				echo '</div>';
				echo '<div id="error">error</div>';
				echo '<div id="report">';
				echo 'Error to Save Job';
				echo '</div>';
			}
			
	}
	function job_pan_phase_facade(){
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
			,'jobs/job_phase_pan'
			,'print_element/printThis'
		);
		
		$css=array('transaction/capital/gold_introduce'
					,'ui-lightness/jquery-ui-1.10.2.custom'
					,'table_css/table/jquery.dataTables'
					,'message_box/Styles/msgBoxLight'
					,'jobs/job_phase_pan'
					,'materials/stock_all_employees'
					);	   //for data table jquery
		
		$main_data['user_id']=$this->session->userdata('user_id');
		$main_data['java_script']=$java_scripts;
		$main_data['css']=$css;
		$main_data['current_date']=get_current_date();
		$main_data['delivery_date']=date("d/m/Y",strtotime("+5 days"));
		//$main_data['user_key']=$this->session->userdata('ip_address').'-'.date_serial_number(get_current_date()).'-'.rand(1000,9999);
		$main_data['user_key']=uniqid('', true);
		$main_data['page_title']="Diamond Phase Pan";
		$this -> load -> view('includes/staff/template', $this -> set_site_data('jobs/job_phase_pan',$main_data));
	}
	function show_jobs_in_phase_pan_ajax(){
		$result=$this->job_model->get_jobs_by_status(51);
		if($result==NULL){
			echo '<div id="msg">';
			echo "No Phase Pan Job selected";
			echo '</div>';
			echo '<div id="error">error</div>';
			echo '<div id="report">';
			echo 'No Phase Pan Job Exist';
			echo '</div>';
		}else{
			echo '<div id="msg">';
			echo "Fresh order selected";
			echo '</div>';
			echo '<div id="error">noerror</div>';
			echo '<div id="report">';
			foreach($result->result() as $row){
				echo '<div class="job-phseII-div">';
				echo '<span class="job-id">'.$row->job_id.'</span>';
				echo img(array('src'=>'img/gold_send.png','class'=>'save column7','height'=>'15px','border'=>'0','alt'=>'save'));
				echo $row->emp_name;
				echo img(array('src'=>'img/gold_send.png','class'=>'save column7','height'=>'15px','border'=>'0','alt'=>'save'));
				echo $row->order_id.'/'.$row->order_serial.'</br>';
				echo '</div>';
			}
			
			echo '</div>';
		}
	}
	function select_job_by_job_id_for_pan_ajax(){
			
			$job_id=$_GET['job_id'];
			$job_details=$this->job_model->get_job_by_job_id($_GET['job_id']);
			if($job_details==NULL){
				echo '<div id="error">error</div>';
				echo '<div id="msg">';
					echo "No such job in this phase";
				echo '</div>';
				echo '<div id="report">';
					echo 'Select proper JOB';
				echo '</div>';
				return;
			}
				
			echo '<div id="error">noerror</div>';
			echo '<div id="report">';
			$challan=$job_details->order_id.'/'.$job_details->order_serial;
			$model= $job_details->product_code;
			$pcs=$job_details->pieces;
			$size=$job_details->product_size;
			$date= '</br>Date : '.sql_date_to_dmy($job_details->job_date2).'&nbsp&nbsp&nbsp&nbsp&nbspDD : '.sql_date_to_dmy($job_details->dd);
			echo '<span id="rm-id" class="hidden">'.$job_details->rm_id.'</span>';
			echo '</br>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<b>JOB-ID '.'<span id="job-id">'.$job_id.'</span></b></b>';
			echo '</br>Challan No.: '.$challan;
			echo $date;
			echo '</br>Model <b>'.$model.'-'.$job_details->price_code.'</b> &nbsp&nbsp Pcs <b>'.$pcs.'</b> &nbsp&nbspSize <b>'.$size.'</b>';
			$this->load->library('table');
			$this->table->add_row(cell_format('Thokai :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('Tonch :','text','column3'),cell_format(number_format($job_details->rm_gold,2),'text','column4'));
			$this->table->add_row(cell_format('Ring W:','text','column1'),cell_format(' ','text','column2'),cell_format('A. Wt. :','text','column3'),cell_format(number_format($job_details->expected_gold,3),'text','column4'));
			$this->table->add_row(cell_format('Mina :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('Forming :','text','column3'),cell_format('&nbsp','text','column4'));
			$this->table->add_row(cell_format('M.C. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('Gold :','text','column3'),cell_format(number_format($job_details->gold_send,3),'text','column4'));
			$this->table->add_row(cell_format('H.M.C. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('RGW- :','text','column3'),cell_format($job_details->gold_returned,'text','column4'));
			$this->table->add_row(cell_format('H.C. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('P.wt. :','text','column3'),cell_format('&nbsp','text','column4'));
			$this->table->add_row(cell_format('Polis :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('NGR- :','text','column3'),cell_format('&nbsp','text','column4'));
			$this->table->add_row(cell_format('Dal :','text','column1'),cell_format($job_details->dal_send,'text','column2'),cell_format('PAN+ :','text','column3'),cell_format(form_input('pan',set_value('pan',0.000),'id="pan-send"'),'text','column4 gold-input'));
			$this->table->add_row(cell_format('Bnz. :','text','column1'),cell_format($job_details->bronze_send,'text','column2'),cell_format('P.Loss+ :','text','column3'),cell_format(number_format(($job_details->p_loss)*($job_details->pieces),3),'text','column4'));
			$this->table->add_row(cell_format('Bati No. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('FG Wt.= :','text','column3'),cell_format('&nbsp','text','column4'));
			$this->table->add_row(cell_format('&nbsp','text','column1'),cell_format('&nbsp','text','column2'),cell_format('B.Wt=:','text','column3'),cell_format('&nbsp','text','column4'));
		
			$this->table->set_template(default_table_template('id="old-job-table" class="job-table"'));
			$job_table=$this->table->generate();
			echo $job_table;
			echo '<span id="show-pan">Select Pan</span> '.$this->dropdown_pan();
			echo '</br><span id="employee-id" class="hidden">'.$job_details->emp_id.'</span>';
			echo '<label for="">Karigar : </label>';
			echo '<b>'.$job_details->emp_name.'</b>';
			echo '<span id="user-id" class="hidden">'.$this->session->userdata('employee_id').'</span>';
			$user=$this->session->userdata('employee_name').'('.$this->session->userdata('user_id').')';
			echo '</br>User: <b>'.$user.'</b>';
			echo '</br>Click Here to '.form_submit('submit','Submit','id=submit');
			echo '</div>';
	}
	function dropdown_pan(){
		$result=$this->db->query("select * from rm_master where rm_name like '%pan%'");
		$pan[0]="Select";
		foreach($result->result() as $row){
			$pan[$row->rm_ID]=$row->rm_name;
		}
		$ans=form_dropdown('pan',$pan,0,'id="pan-id"');
		return $ans;
	}
	function save_phase_pan_ajax(){
			$result=$this->job_model->save_phase_pan();
			if($result['success']==1){
				echo '<div id="msg">';
				echo "JOB SEND TO PHASE II";
				echo '</div>';
				echo '<div id="error">noerror</div>';
				echo '<div id="report">';
				echo 'Job send to Phase II';
				echo '</div>';
			}else{
				echo '<div id="msg">';
				echo "Error Saving Job";
				echo '</div>';
				echo '<div id="error">error</div>';
				echo '<div id="report">';
				echo 'Error to Save Job';
				print_r($_POST);
				echo '</div>';
			}
	}
	function job_phaseII_facade(){
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
			,'jobs/job_phaseII'
		);
		
		$css=array('transaction/capital/gold_introduce'
					,'ui-lightness/jquery-ui-1.10.2.custom'
					,'table_css/table/jquery.dataTables'
					,'message_box/Styles/msgBoxLight'
					,'jobs/job_phaseII'
					,'materials/stock_all_employees'
					);	   //for data table jquery
		
		$main_data['user_id']=$this->session->userdata('user_id');
		$main_data['java_script']=$java_scripts;
		$main_data['css']=$css;
		$main_data['current_date']=get_current_date();
		$main_data['delivery_date']=date("d/m/Y",strtotime("+5 days"));
		//$main_data['user_key']=$this->session->userdata('ip_address').'-'.date_serial_number(get_current_date()).'-'.rand(1000,9999);
		$main_data['user_key']=uniqid('', true);
		$main_data['page_title']="Diamond PhaseII";
		$this -> load -> view('includes/staff/template', $this -> set_site_data('jobs/job_phaseII',$main_data));
	}
	function show_jobs_in_phaseII_ajax(){
		$result=$this->job_model->get_jobs_by_status(6);
		if($result==NULL){
			echo '<div id="msg">';
			echo "No Phase Pan Job selected";
			echo '</div>';
			echo '<div id="error">error</div>';
			echo '<div id="report">';
			echo 'No Phase Pan Job Exist';
			echo '</div>';
		}else{
			echo '<div id="msg">';
			echo "Fresh order selected";
			echo '</div>';
			echo '<div id="error">noerror</div>';
			echo '<div id="report">';
			foreach($result->result() as $row){
				echo '<div class="job-phseII-div">';
				echo '<span class="job-id">'.$row->job_id.'</span>';
				echo img(array('src'=>'img/gold_send.png','class'=>'save column7','height'=>'15px','border'=>'0','alt'=>'save'));
				echo $row->emp_name;
				echo img(array('src'=>'img/gold_send.png','class'=>'save column7','height'=>'15px','border'=>'0','alt'=>'save'));
				echo $row->order_id.'/'.$row->order_serial.'</br>';
				echo '</div>';
			}
			
			echo '</div>';
		}
	}
	function select_job_by_job_id_for_phaseII_ajax(){
			
			$job_id=$_GET['job_id'];
			$job_details=$this->job_model->get_job_by_job_id($_GET['job_id']);
			if($job_details==NULL){
				echo '<div id="error">error</div>';
				echo '<div id="msg">';
					echo "No such job in this phase";
				echo '</div>';
				echo '<div id="report">';
					echo 'Select proper JOB';
				echo '</div>';
				return;
			}
				
			echo '<div id="error">noerror</div>';
			echo '<div id="report">';
			$challan=$job_details->order_id.'/'.$job_details->order_serial;
			$model= $job_details->product_code;
			$pcs=$job_details->pieces;
			$size=$job_details->product_size;
			$date= '</br>Date : '.sql_date_to_dmy($job_details->job_date2).'&nbsp&nbsp&nbsp&nbsp&nbspDD : '.sql_date_to_dmy($job_details->dd);
			echo '<span id="rm-id" class="hidden">'.$job_details->rm_id.'</span>';
			echo '</br>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<b>JOB-ID '.'<span id="job-id">'.$job_id.'</span></b></b>';
			echo '</br>Challan No.: '.$challan;
			echo $date;
			echo '</br>Model <b>'.$model.'-'.$job_details->price_code.'</b> &nbsp&nbsp Pcs <b>'.$pcs.'</b> &nbsp&nbspSize <b>'.$size.'</b>';
			$this->load->library('table');
			$this->table->add_row(cell_format('Thokai :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('Tonch :','text','column3'),cell_format(number_format($job_details->rm_gold,2),'text','column4'));
			$this->table->add_row(cell_format('Ring W:','text','column1'),cell_format(' ','text','column2'),cell_format('A. Wt. :','text','column3'),cell_format(number_format($job_details->expected_gold,3),'text','column4'));
			$this->table->add_row(cell_format('Mina :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('Forming :','text','column3'),cell_format('&nbsp','text','column4'));
			$this->table->add_row(cell_format('M.C. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('Gold :','text','column3'),cell_format(number_format($job_details->gold_send,3),'text','column4'));
			$this->table->add_row(cell_format('H.M.C. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('RGW- :','text','column3'),cell_format($job_details->gold_returned,'text','column4'));
			$this->table->add_row(cell_format('H.C. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('P.wt. :','text','column3'),cell_format('&nbsp','text','column4'));
			$this->table->add_row(cell_format('Polis :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('NGR- :','text','column3'),cell_format(form_input('ngr',set_value('ngr',0.000),'id="ngr" class="balance_machine gold-input"'),'text','column4'));
			$this->table->add_row(cell_format('Dal :','text','column1'),cell_format(number_format($job_details->dal_send,3),'text','column2'),cell_format('PAN+ :','text','column3'),cell_format(number_format($job_details->pan_send,3),'text','column4'));
			$this->table->add_row(cell_format('Bnz. :','text','column1'),cell_format(number_format($job_details->bronze_send,3),'text','column2'),cell_format('P.Loss+ :','text','column3'),cell_format(($job_details->p_loss)*($job_details->pieces),'text','column4'));
			$this->table->add_row(cell_format('Bati No. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('FG Wt.= :','text','column3'),cell_format('&nbsp','text','column4'));
			$this->table->add_row(cell_format('&nbsp','text','column1'),cell_format('&nbsp','text','column2'),cell_format('B.Wt=:','text','column3'),cell_format('&nbsp','text','column4'));
		
			$this->table->set_template(default_table_template('id="old-job-table" class="job-table"'));
			$job_table=$this->table->generate();
			echo $job_table;
			echo '</br><span id="employee-id" class="hidden">'.$job_details->emp_id.'</span>';
			echo '<label for="">Karigar : </label>';
			echo '<b>'.$job_details->emp_name.'</b>';
			echo '<span id="user-id" class="hidden">'.$this->session->userdata('employee_id').'</span>';
			$user=$this->session->userdata('employee_name').'('.$this->session->userdata('user_id').')';
			echo '</br>User: <b>'.$user.'</b>';
			echo '</br>Click Here to '.form_submit('submit','Submit','id=submit');
			echo '</div>';
	}
	function save_phaseII_ajax(){
			$result=$this->job_model->save_phaseII();
			if($result['success']==1){
				echo '<div id="msg">';
				echo "JOB SEND TO PHASE II";
				echo '</div>';
				echo '<div id="error">noerror</div>';
				echo '<div id="report">';
				echo 'Job send to Phase II';
				echo '</div>';
			}else{
				echo '<div id="msg">';
				echo "Error Saving Job PHASE II";
				echo '</div>';
				echo '<div id="error">error</div>';
				echo '<div id="report">';
				echo 'Error to Save Job';
				print_r($result);
				echo '</div>';
			}
	}
	function job_finish_facade(){
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
			,'jobs/job_finish'
		);
		
		$css=array('transaction/capital/gold_introduce'
					,'ui-lightness/jquery-ui-1.10.2.custom'
					,'table_css/table/jquery.dataTables'
					,'message_box/Styles/msgBoxLight'
					,'jobs/job_finish'
					,'materials/stock_all_employees'
					);	   //for data table jquery
		
		$main_data['user_id']=$this->session->userdata('user_id');
		$main_data['java_script']=$java_scripts;
		$main_data['css']=$css;
		$main_data['current_date']=get_current_date();
		$main_data['delivery_date']=date("d/m/Y",strtotime("+5 days"));
		//$main_data['user_key']=$this->session->userdata('ip_address').'-'.date_serial_number(get_current_date()).'-'.rand(1000,9999);
		$main_data['user_key']=uniqid('', true);
		$main_data['page_title']="Diamond Finish";
		$this -> load -> view('includes/staff/template', $this -> set_site_data('jobs/job_finish',$main_data));
	}
	function show_jobs_in_finish_ajax(){
		$result=$this->job_model->get_jobs_by_status(7);
		if($result==NULL){
			echo '<div id="msg">';
			echo "No Phase Pan Job selected";
			echo '</div>';
			echo '<div id="error">error</div>';
			echo '<div id="report">';
			echo 'No Phase Pan Job Exist';
			echo '</div>';
		}else{
			echo '<div id="msg">';
			echo "Fresh order selected";
			echo '</div>';
			echo '<div id="error">noerror</div>';
			echo '<div id="report">';
			foreach($result->result() as $row){
				echo '<div class="job-phseII-div">';
				echo '<span class="job-id">'.$row->job_id.'</span>';
				echo img(array('src'=>'img/gold_send.png','class'=>'save column7','height'=>'15px','border'=>'0','alt'=>'save'));
				echo $row->emp_name;
				echo img(array('src'=>'img/gold_send.png','class'=>'save column7','height'=>'15px','border'=>'0','alt'=>'save'));
				echo $row->order_id.'/'.$row->order_serial.'</br>';
				echo '</div>';
			}
			
			echo '</div>';
		}
	}
	function select_job_by_job_id_for_finish_ajax(){
			echo '<div id="msg">';
			echo "No Order Found";
			echo '</div>';
			echo '<div id="error">noerror</div>';
			echo '<div id="report">';
			$job_id=$_GET['job_id'];
			$job_details=$this->job_model->get_job_by_job_id($_GET['job_id']);
			$challan=$job_details->order_id.'/'.$job_details->order_serial;
			$model= $job_details->product_code;
			$pcs=$job_details->pieces;
			$size=$job_details->product_size;
			$date= '</br>Date : '.sql_date_to_dmy($job_details->job_date2).'&nbsp&nbsp&nbsp&nbsp&nbspDD : '.sql_date_to_dmy($job_details->dd);
			echo '<span id="rm-id" class="hidden">'.$job_details->rm_id.'</span>';
			echo '</br>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<b>JOB-ID '.'<span id="job-id">'.$job_id.'</span></b></b>';
			echo '</br>Challan No.: '.$challan;
			echo $date;
			echo '</br>Model <b>'.$model.'-'.$job_details->price_code.'</b> &nbsp&nbsp</br> Pcs '.form_input('pieces',set_value('pcs',$pcs),'id="pieces"').' &nbsp&nbspSize <b>'.$size.'</b>';
			$total_gold_used=$job_details->gold_send-$job_details->gold_returned-$job_details->nitrick_returned+$job_details->pan_send+(($job_details->p_loss)*($job_details->pieces));
			$this->load->library('table');
			$this->table->add_row(cell_format('Thokai :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('Tonch :','text','column3'),cell_format(number_format($job_details->rm_gold,2),'text','column4'));
			$this->table->add_row(cell_format('Ring W:','text','column1'),cell_format(' ','text','column2'),cell_format('A. Wt. :','text','column3'),cell_format(number_format($job_details->expected_gold,3),'text','column4'));
			$this->table->add_row(cell_format('Mina :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('Forming :','text','column3'),cell_format('&nbsp','text','column4'));
			$this->table->add_row(cell_format('M.C. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('Gold :','text','column3'),cell_format(number_format($job_details->gold_send,3),'text','column4'));
			$this->table->add_row(cell_format('H.M.C. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('RGW- :','text','column3'),cell_format($job_details->gold_returned,'text','column4'));
			$this->table->add_row(cell_format('H.C. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('P.wt. :','text','column3'),cell_format('&nbsp','text','column4'));
			$this->table->add_row(cell_format('Polis :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('NGR- :','text','column3'),cell_format($job_details->nitrick_returned,'text','column4'));
			$this->table->add_row(cell_format('Dal :','text','column1'),cell_format($job_details->dal_send,'text','column2'),cell_format('PAN+ :','text','column3'),cell_format($job_details->pan_send,'text','column4'));
			$this->table->add_row(cell_format('Bnz. :','text','column1'),cell_format($job_details->bronze_send,'text','column2'),cell_format('P.Loss+ :','text','column3'),cell_format(number_format(($job_details->p_loss)*($job_details->pieces),3),'text','column4'));
			$this->table->add_row(cell_format('Bati No. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('FG Wt.= :','text','column3'),cell_format(number_format($total_gold_used,3),'text','column4'));
			$this->table->add_row(cell_format('&nbsp','text','column1'),cell_format('&nbsp','text','column2'),cell_format('B.Wt=:','text','column3'),cell_format(form_input('gross_weight',set_value('gross_weight',0.000),'id="gross_weight" class="balance_machine gold-input"'),'text','column4'));
		
			$this->table->set_template(default_table_template('id="old-job-table" class="job-table"'));
			$job_table=$this->table->generate();
			echo $job_table;
			echo '</br><span id="employee-id" class="hidden">'.$job_details->emp_id.'</span>';
			echo '<label for="">Karigar : </label>';
			echo '<b>'.$job_details->emp_name.'</b>';
			echo '<span id="user-id" class="hidden">'.$this->session->userdata('employee_id').'</span>';
			$user=$this->session->userdata('employee_name').'('.$this->session->userdata('user_id').')';
			echo '</br>User: <b>'.$user.'</b>';
			echo '</br>Click Here to '.form_submit('submit','Submit','id=submit');
	}
	function save_job_finish_ajax(){
			$result=$this->job_model->save_job_finish();
			if($result['success']==1){
				echo '<div id="msg">';
				echo "JOB Finished, Ready to Bill ";
				echo '</div>';
				echo '<div id="error">noerror</div>';
				echo '<div id="report">';
				echo 'Job send to Phase II';
				echo '</div>';
			}else{
				echo '<div id="msg">';
				echo "Error Saving Job PHASE II";
				echo '</div>';
				echo '<div id="error">error</div>';
				echo '<div id="report">';
				echo 'Error to Save Job';
				print_r($result);
				echo '</div>';
			}
	}
	function create_bill_facade(){
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
			,'jobs/create_bill'
		);
		
		$css=array('transaction/capital/gold_introduce'
					,'ui-lightness/jquery-ui-1.10.2.custom'
					,'table_css/table/jquery.dataTables'
					,'message_box/Styles/msgBoxLight'
					,'jobs/create_bill'
					
					);	   //for data table jquery
		
		$result=$this->job_model->get_billable_customers();
		$customers['select']='--Select--';
		if($result!=NULL){
			foreach($result->result() as $row){
				$customers[$row->cust_id]=$row->cust_name;
			}
				
		}
		$main_data['customers']=$customers;
		$main_data['user_id']=$this->session->userdata('user_id');
		$main_data['java_script']=$java_scripts;
		$main_data['css']=$css;
		$main_data['current_date']=get_current_date();
		$main_data['delivery_date']=date("d/m/Y",strtotime("+5 days"));
		//$main_data['user_key']=$this->session->userdata('ip_address').'-'.date_serial_number(get_current_date()).'-'.rand(1000,9999);
		$main_data['user_key']=uniqid('', true);
		$main_data['page_title']="Diamond Finish";
		$this -> load -> view('includes/staff/template', $this -> set_site_data('jobs/create_bill',$main_data));
	}
	function get_customers(){
		$result=$this->job_model->get_billable_customers();
		$customers['select']='--Select--';
		if($result!=NULL){
			foreach($result->result() as $row){
				$customers[$row->cust_id]=$row->cust_name;
			}
		}
		echo '<label for="">Customer Name</label>';
		echo form_dropdown('customer',$customers,0,'id="customer-id"');
	}
	function get_orders_by_cust_id_for_bill_ajax(){
		if($_GET['cust_id']=='select'){
			echo '<span class="error">Please select a Customer</span>';
			return;
		}
			
		$result=$this->job_model->select_billable_order_by_customer($_GET['cust_id']);
		$bills['select']='--Select--';
		if($result!=NULL){
			foreach($result->result() as $row){
				$bills[$row->order_id]=$row->order_id;
			}
		}
		echo '<label for="">Order No.</label>';
		echo form_dropdown('order_id',$bills,'select','id="order-id"') ;
		$result=$this->job_model->get_customer_by_cust_id($_GET['cust_id']);
		
		echo '<span id="customer-markup" class="hidden_huitech">';
		echo ($result->markup_value)*($result->markuped);
		echo '</span>';
	}
	function get_jobs_for_bill_ajax(){
		$result=$this->job_model->select_jobs_ready_for_bill($_GET['cust_id'],$_GET['bill_id']);
		if($result!=NULL){
			echo '<div id="jobs-ready-for-bill-div">';
				echo 'Following Jobs are ready to Bill</br>';
				echo '<hr>';
				echo '<span>Job Id</span><span>Weight</span><span>Model</span><span>pcs</span><span>Action</span>';
				echo '<hr>';
				foreach($result->result() as $row){
					echo '<div class="job" id="'.$row->job_id.'">';
					echo '<span>'.$row->job_id.'<span>';
					echo '<span>'.number_format($row->product_wt,3).'</span>';
					echo '<span>'.$row->product_code.'</span>';
					echo '<span>'.$row->pieces.'</span>';
					echo '<span id="remove-'.$row->job_id.'" class="remove">';
					echo 'Remove';
					echo '</span>';
					echo '</div>';
				}
				echo '<span id="send-job-for-bill">Create Bill</span>';
			echo '</div>';
			
		}
		$result=$this->job_model->select_jobs_not_ready_for_bill($_GET['cust_id'],$_GET['bill_id']);
		if($result!=NULL){
			
			echo '<div id="jobs-not-ready-for-bill-div">';
				echo 'Following jobs are yet to Complete</br>';
				echo '<hr>';
				foreach($result->result() as $row){
					echo '<span class="job">';
					echo $row->job_id;
					echo '</span></br>';
				}
			echo '</div>';
		}
		//echo '<label for="">JOB ID</label>';
		//echo form_dropdown('job_id',$jobs,0,'id="job-id"');
	}
	function create_bill_ajax(){
		$result=$this->job_model->create_n_save_bill();
		if($result['success']==1){
				echo '<div id="msg">';
				echo "Bill created";
				echo '</div>';
				echo '<div id="error">noerror</div>';
				echo '<div id="report">';
				echo 'Bill Created '.anchor_popup(site_url()."/bill_controller/display_bill?bill_no=".$result['bill_no'],$result['bill_no']);
				//echo 'Bill No. <span id="print-bill" class="link">'.$result['bill_no'].'</span>';
				echo '</div>';
			}else{
				echo '<div id="msg">';
				echo "Error Saving Bill";
				echo '</div>';
				echo '<div id="error">error</div>';
				echo '<div id="report">';
				echo 'Error in Bill creation';
				echo 'Error Code '.$result['error'];
				echo '</div>';
			}
	}
	function add_topup_pan_facade(){
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
			,'jobs/topup_pan'
		);
		
		$css=array('transaction/capital/gold_introduce'
					,'ui-lightness/jquery-ui-1.10.2.custom'
					,'table_css/table/jquery.dataTables'
					,'message_box/Styles/msgBoxLight'
					,'jobs/topup_pan'
					,'materials/stock_all_employees'
					);	   //for data table jquery
		
		$main_data['user_id']=$this->session->userdata('user_id');
		$main_data['java_script']=$java_scripts;
		$main_data['css']=$css;
		$main_data['current_date']=get_current_date();
		$main_data['delivery_date']=date("d/m/Y",strtotime("+5 days"));
		//$main_data['user_key']=$this->session->userdata('ip_address').'-'.date_serial_number(get_current_date()).'-'.rand(1000,9999);
		$main_data['user_key']=uniqid('', true);
		$main_data['page_title']="Topup PAN";
		$this -> load -> view('includes/staff/template', $this -> set_site_data('jobs/topup_pan',$main_data));
	}
	function show_jobs_for_topup_pan_ajax(){
		$result=$this->job_model->get_jobs_by_status_for_topup_pan();
		if($result==NULL){
			echo '<div id="msg">';
			echo "No Phase Pan Job selected";
			echo '</div>';
			echo '<div id="error">error</div>';
			echo '<div id="report">';
			echo 'No Phase Pan Job Exist';
			echo '</div>';
		}else{
			echo '<div id="msg">';
			echo "Fresh order selected";
			echo '</div>';
			echo '<div id="error">noerror</div>';
			echo '<div id="report">';
			foreach($result->result() as $row){
				echo '<div class="job-phseII-div">';
				echo '<span class="job-id">'.$row->job_id.'</span>';
				echo img(array('src'=>'img/gold_send.png','class'=>'save column7','height'=>'15px','border'=>'0','alt'=>'save'));
				echo $row->emp_name;
				echo img(array('src'=>'img/gold_send.png','class'=>'save column7','height'=>'15px','border'=>'0','alt'=>'save'));
				echo $row->order_id.'/'.$row->order_serial.'</br>';
				echo '</div>';
			}
			
			echo '</div>';
		}
	}
	function select_job_by_job_id_for_topup_pan_ajax(){
			echo '<div id="msg">';
			echo "No Order Found";
			echo '</div>';
			echo '<div id="error">noerror</div>';
			echo '<div id="report">';
			$job_id=$_GET['job_id'];
			$job_details=$this->job_model->get_job_by_job_id($_GET['job_id']);
			$challan=$job_details->order_id.'/'.$job_details->order_serial;
			$model= $job_details->product_code;
			$pcs=$job_details->pieces;
			$size=$job_details->product_size;
			$date= '</br>Date : '.sql_date_to_dmy($job_details->job_date2).'&nbsp&nbsp&nbsp&nbsp&nbspDD : '.sql_date_to_dmy($job_details->dd);
			echo '<span id="rm-id" class="hidden">'.$job_details->rm_id.'</span>';
			echo '</br>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<b>JOB-ID '.'<span id="job-id">'.$job_id.'</span></b></b>';
			echo '</br>Challan No.: '.$challan;
			echo $date;
			echo '</br>Model <b>'.$model.'-'.$job_details->price_code.'</b> &nbsp&nbsp Pcs <b>'.$pcs.'</b> &nbsp&nbspSize <b>'.$size.'</b>';
			$this->load->library('table');
			$this->table->add_row(cell_format('Thokai :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('Tonch :','text','column3'),cell_format(number_format($job_details->rm_gold,3),'text','column4'));
			$this->table->add_row(cell_format('Ring W:','text','column1'),cell_format(' ','text','column2'),cell_format('A. Wt. :','text','column3'),cell_format(number_format($job_details->expected_gold,3),'text','column4'));
			$this->table->add_row(cell_format('Mina :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('Forming :','text','column3'),cell_format('&nbsp','text','column4'));
			$this->table->add_row(cell_format('M.C. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('Gold :','text','column3'),cell_format(number_format($job_details->gold_send,3),'text','column4'));
			$this->table->add_row(cell_format('H.M.C. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('RGW- :','text','column3'),cell_format($job_details->gold_returned,'text','column4'));
			$this->table->add_row(cell_format('H.C. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('P.wt. :','text','column3'),cell_format('&nbsp','text','column4'));
			$this->table->add_row(cell_format('Polis :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('NGR- :','text','column3'),cell_format('&nbsp','text','column4'));
			$this->table->add_row(cell_format('Dal :','text','column1'),cell_format($job_details->dal_send,'text','column2'),cell_format('PAN+ :','text','column3'),cell_format(form_input('pan',set_value('pan',0.000),'id="pan-send"'),'text','column4 gold-input'));
			$this->table->add_row(cell_format('Bnz. :','text','column1'),cell_format($job_details->bronze_send,'text','column2'),cell_format('P.Loss+ :','text','column3'),cell_format(($job_details->p_loss)*($job_details->pieces),'text','column4'));
			$this->table->add_row(cell_format('Bati No. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('FG Wt.= :','text','column3'),cell_format('&nbsp','text','column4'));
			$this->table->add_row(cell_format('&nbsp','text','column1'),cell_format('&nbsp','text','column2'),cell_format('B.Wt=:','text','column3'),cell_format('&nbsp','text','column4'));
		
			$this->table->set_template(default_table_template('id="old-job-table" class="job-table"'));
			$job_table=$this->table->generate();
			echo $job_table;
			echo '<span id="show-pan">PAN Used: </span><span id="pan-id" class="hidden">'.$job_details->pan_id.'</span></span><span class="highlight">'.number_format($job_details->pan_send,3).'</span>';
			echo '</br><span id="employee-id" class="hidden">'.$job_details->emp_id.'</span>';
			echo '<label for="">Karigar : </label>';
			echo '<b>'.$job_details->emp_name.'</b>';
			echo '<span id="user-id" class="hidden">'.$this->session->userdata('employee_id').'</span>';
			$user=$this->session->userdata('employee_name').'('.$this->session->userdata('user_id').')';
			echo '</br>User: <b>'.$user.'</b>';
			echo '</br>Click Here to '.form_submit('submit','Submit','id=submit');
	}
	function save_topup_pan_ajax(){
			$result=$this->job_model->save_topup_pan();
			if($result['success']==1){
				echo '<div id="msg">';
				echo "TOPUP PAN SAVED";
				echo '</div>';
				echo '<div id="error">noerror</div>';
				echo '<div id="report">';
				echo 'Job send to Phase II';
				echo '</div>';
			}else{
				echo '<div id="msg">';
				echo "Error Saving Job";
				echo '</div>';
				echo '<div id="error">error</div>';
				echo '<div id="report">';
				echo 'Error to Save Job';
				print_r($_POST);
				echo '</div>';
			}
	}
	function pan_return_facade(){
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
			,'jobs/pan_return'
		);
		
		$css=array('transaction/capital/gold_introduce'
					,'ui-lightness/jquery-ui-1.10.2.custom'
					,'table_css/table/jquery.dataTables'
					,'message_box/Styles/msgBoxLight'
					,'jobs/topup_pan'
					,'materials/stock_all_employees'
					);	   //for data table jquery
		
		$main_data['user_id']=$this->session->userdata('user_id');
		$main_data['java_script']=$java_scripts;
		$main_data['css']=$css;
		$main_data['current_date']=get_current_date();
		$main_data['delivery_date']=date("d/m/Y",strtotime("+5 days"));
		//$main_data['user_key']=$this->session->userdata('ip_address').'-'.date_serial_number(get_current_date()).'-'.rand(1000,9999);
		$main_data['user_key']=uniqid('', true);
		$main_data['page_title']="PAN Return";
		$this -> load -> view('includes/staff/template', $this -> set_site_data('jobs/topup_pan',$main_data));
	}
	
	function show_jobs_for_pan_return_ajax(){
		$result=$this->job_model->get_jobs_by_status_for_pan_return();
		if($result==NULL){
			echo '<div id="msg">';
			echo "No JOB in Phase II";
			echo '</div>';
			echo '<div id="error">error</div>';
			echo '<div id="report">';
			echo 'No JOB in Phase II';
			echo '</div>';
		}else{
			echo '<div id="msg">';
			echo "Fresh order selected";
			echo '</div>';
			echo '<div id="error">noerror</div>';
			echo '<div id="report">';
			foreach($result->result() as $row){
				echo '<div class="job-phseII-div">';
				echo '<span class="job-id">'.$row->job_id.'</span>';
				echo img(array('src'=>'img/gold_send.png','class'=>'save column7','height'=>'15px','border'=>'0','alt'=>'save'));
				echo $row->emp_name;
				echo img(array('src'=>'img/gold_send.png','class'=>'save column7','height'=>'15px','border'=>'0','alt'=>'save'));
				echo $row->order_id.'/'.$row->order_serial.'</br>';
				echo '</div>';
			}
			
			echo '</div>';
		}
	}
	function select_job_by_job_id_for_pan_return_ajax(){
			echo '<div id="msg">';
			echo "No Order Found";
			echo '</div>';
			echo '<div id="error">noerror</div>';
			echo '<div id="report">';
			$job_id=$_GET['job_id'];
			$job_details=$this->job_model->get_job_by_job_id($_GET['job_id']);
			$challan=$job_details->order_id.'/'.$job_details->order_serial;
			$model= $job_details->product_code;
			$pcs=$job_details->pieces;
			$size=$job_details->product_size;
			$date= '</br>Date : '.sql_date_to_dmy($job_details->job_date2).'&nbsp&nbsp&nbsp&nbsp&nbspDD : '.sql_date_to_dmy($job_details->dd);
			echo '<span id="rm-id" class="hidden">'.$job_details->rm_id.'</span>';
			echo '</br>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<b>JOB-ID '.'<span id="job-id">'.$job_id.'</span></b></b>';
			echo '</br>Challan No.: '.$challan;
			echo $date;
			echo '</br>Model <b>'.$model.'-'.$job_details->price_code.'</b> &nbsp&nbsp Pcs <b>'.$pcs.'</b> &nbsp&nbspSize <b>'.$size.'</b>';
			$this->load->library('table');
			$this->table->add_row(cell_format('Thokai :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('Tonch :','text','column3'),cell_format(number_format($job_details->rm_gold,3),'text','column4'));
			$this->table->add_row(cell_format('Ring W:','text','column1'),cell_format(' ','text','column2'),cell_format('A. Wt. :','text','column3'),cell_format(number_format($job_details->expected_gold,3),'text','column4'));
			$this->table->add_row(cell_format('Mina :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('Forming :','text','column3'),cell_format('&nbsp','text','column4'));
			$this->table->add_row(cell_format('M.C. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('Gold :','text','column3'),cell_format(number_format($job_details->gold_send,3),'text','column4'));
			$this->table->add_row(cell_format('H.M.C. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('RGW- :','text','column3'),cell_format($job_details->gold_returned,'text','column4'));
			$this->table->add_row(cell_format('H.C. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('P.wt. :','text','column3'),cell_format('&nbsp','text','column4'));
			$this->table->add_row(cell_format('Polis :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('NGR- :','text','column3'),cell_format('&nbsp','text','column4'));
			$this->table->add_row(cell_format('Dal :','text','column1'),cell_format($job_details->dal_send,'text','column2'),cell_format('PAN-:','text','column3'),cell_format(form_input('pan',set_value('pan',0.000),'id="pan-send"'),'text','column4 gold-input'));
			$this->table->add_row(cell_format('Bnz. :','text','column1'),cell_format($job_details->bronze_send,'text','column2'),cell_format('P.Loss+ :','text','column3'),cell_format(($job_details->p_loss)*($job_details->pieces),'text','column4'));
			$this->table->add_row(cell_format('Bati No. :','text','column1'),cell_format('&nbsp','text','column2'),cell_format('FG Wt.= :','text','column3'),cell_format('&nbsp','text','column4'));
			$this->table->add_row(cell_format('&nbsp','text','column1'),cell_format('&nbsp','text','column2'),cell_format('B.Wt=:','text','column3'),cell_format('&nbsp','text','column4'));
		
			$this->table->set_template(default_table_template('id="old-job-table" class="job-table"'));
			$this->table->set_caption("PAN Return");
			$job_table=$this->table->generate();
			echo $job_table;
			echo '<span id="show-pan">PAN Used: </span><span id="pan-id" class="hidden">'.$job_details->pan_id.'</span></span><span class="highlight">'.number_format($job_details->pan_send,3).'</span>';
			echo '</br><span id="employee-id" class="hidden">'.$job_details->emp_id.'</span>';
			echo '<label for="">Karigar : </label>';
			echo '<b>'.$job_details->emp_name.'</b>';
			echo '<span id="user-id" class="hidden">'.$this->session->userdata('employee_id').'</span>';
			$user=$this->session->userdata('employee_name').'('.$this->session->userdata('user_id').')';
			echo '</br>User: <b>'.$user.'</b>';
			echo '</br>Click Here to '.form_submit('submit','Submit','id=submit');
	}
	function save_pan_return_ajax(){
			$result=$this->job_model->save_pan_return();
			if($result['success']==1){
				echo '<div id="msg">';
				echo "PAN Return SAVED";
				echo '</div>';
				echo '<div id="error">noerror</div>';
				echo '<div id="report">';
				echo 'PAN Return SAVED';
				echo '</div>';
			}else{
				echo '<div id="msg">';
				echo "Error Saving PAN Return";
				echo '</div>';
				echo '<div id="error">error</div>';
				echo '<div id="report">';
				echo 'Error Saving PAN Return';
				print_r($_POST);
				echo '</div>';
			}
	}
	function misc_job_facade(){
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
			,'jobs/misc_job'
			
		);
		
		$css=array('transaction/capital/gold_introduce'
					,'ui-lightness/jquery-ui-1.10.2.custom'
					,'table_css/table/jquery.dataTables'
					,'jobs/misc_job'
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
		$this -> load -> view('includes/staff/template', $this -> set_site_data('jobs/misc_job_view',$main_data));
	}
	
	function edit_product_view(){
		start_div('id="edit-product-div"');
			start_div();
			echo form_label("Job ID : ");
			echo form_input('job_id','','id="job-id" placeholder="JOB ID"');
			end_div();
			start_div();
			echo form_label("Product Code : ");
			echo form_input('product_id','','id="product-id" placeholder="Product Code"');
			end_div();
			start_div();
			echo form_label("Price Code : ");
			echo form_input('price_id','','id="price-id"');
			end_div();
		end_div();
	}
	function get_job_details_by_id(){
		$result=$this->job_model->select_job_details_by_id($_GET['job_id']);
		$row_array=array();
		$return_arr=array();
		if($result==NULL){
			$row_array['job_id']="invalid";
			$row_array['product_code']="invalid";
			$row_array['price_code']="invalid";
			array_push($return_arr,$row_array);
			echo json_encode($return_arr);
			return;
		}

		$row_array['job_id']=$result->job_id;
		$row_array['product_code']=$result->product_code;
		$row_array['price_code']=$result->price_code;
		array_push($return_arr,$row_array);
		echo json_encode($return_arr);
	}
}//end of controller
?>