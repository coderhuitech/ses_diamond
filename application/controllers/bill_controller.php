<?php
class Bill_controller extends CI_Controller {
	
	function __construct() {
		
	   	
   	
		parent::__construct();
		$this -> load -> library('form_validation');
		//this libraru is used for validation of fields in a form
		
		$this -> load -> model('Bill_model');
		$this -> load -> helper(array('datagrid', 'url','huiui','csv'));
		if (file_exists('organisation.xml')) {
				$organisation = simplexml_load_file('organisation.xml');
		} else {
		    exit('Failed to open test.xml.');
		}
	}
	
	function show_message($message = array('No message from server')) {
		$main_data['message'] = $message;
		$this -> load -> view('includes/staff/template', $this -> set_view_data(0, 'basic_view/message_view', $main_data));
	}
	public function default_table_template($value = NULL) {
		$template = array('table_open' => '<table border="0" cellpadding="4" cellspacing="0">', 'table_close' => '</table>', 'heading_open' => '<thead>', 'heading_close' => '</thead>', 'heading_row_start' => '<tr>', 'heading_row_end' => '</tr>', 'heading_cell_start' => '<th>', 'heading_cell_end' => '</th>', 'sub_heading_row_start' => '<tr>', 'sub_heading_row_end' => '</tr>', 'sub_heading_cell_start' => '<th>', 'sub_heading_cell_end' => '</th>', 'body_open' => '<tbody>', 'body_close' => '</tbody>', 'row_start' => '<tr>', 'row_end' => '</tr>', 'cell_start' => '<td>', 'cell_end' => '</td>', 'row_alt_start' => '<tr class="alt">', 'row_alt_end' => '</tr>', 'cell_alt_start' => '<td>', 'cell_alt_end' => '</td>', 'footing_open' => '<tfoot>', 'footing_close' => '</tfoot>', 'footing_row_start' => '<tr>', 'footing_row_end' => '</tr>', 'footing_cell_start' => '<td>', 'footing_cell_end' => '</td>');
		return $template;
	}
	function set_bill_data($view_file,$main_data = array('temp1'=>'data1')) {//important: code used,  to generate report
		$template_data['main_data'] = $main_data;
		$template_data['header_data']=array('java_script'=>$main_data['java_script'],'css'=>$main_data['css'],'page_title'=>$main_data['page_title']);
		$template_data['view_file']=$view_file;
		return $template_data;
	}
	function set_site_data($view_file,$main_data = array('temp1'=>'data1')) {//important code used in near about all actions
		$template_data['main_data'] = $main_data;
		$template_data['header_data']=array('java_script'=>$main_data['java_script'],'css'=>$main_data['css'],'page_title'=>$main_data['page_title']);
		$template_data['view_file']=$view_file;
		return $template_data;
	}
	function sql_to_csv($sql="",$file_name="report.csv"){
		$this->load->dbutil();
		$result = $this->db->query($sql);
		$this->load->helper('file');
		$this->load->helper('download');
		$data= $this->dbutil->csv_from_result($result);
		force_download($file_name, $data);
	}
	
	
	function customer_invoicde_and_payment_action(){
		$subject_id=0;
		$this -> form_validation -> set_rules('customer_id', 'Customer ID', 'trim|required');
		$this -> form_validation -> set_error_delimiters('<div class="error">', '</div>');
		if ($this -> form_validation -> run() == FALSE) {
			$this -> customer_wise_receipt_facade();
			} else {
			$result=$this->report_model->get_customer_invoice_and_payment($_POST['customer_id']);
			$this -> load -> library('table');
			$this -> table -> set_heading('date','Bill No.','Particulars','Gold','L/C','Gold Due','LC Due');
			foreach ($result as $row) {
				$temp_text=explode(' ',$row['particulars']);
				$particulars=$temp_text[0]=='Less:'?array('data'=>$row['particulars'],'class'=>'greenLine'):array('data'=>$row['particulars']);
				$gold=$row['gold']<0?array('data'=>number_format($row['gold'],3, '.', ','),'class' => 'numeric negetive'):array('data'=>number_format($row['gold'],3, '.', ','),'class' => 'numeric');
				$lc=$row['labour_charge']<0?array('data'=>number_format($row['labour_charge'],2, '.', ','),'class' => 'numeric negetive'):array('data'=>number_format($row['labour_charge'],2, '.', ','),'class' => 'numeric');
				$gold_balance=$row['gold_balance']<0?array('data'=>number_format($row['gold_balance'],3, '.', ','),'class' => 'numeric negetive'):array('data'=>number_format($row['gold_balance'],3, '.', ','),'class' => 'numeric');
				$lc_balance=$row['lc_balance']<0?array('data'=>number_format($row['lc_balance'],2, '.', ','),'class' => 'numeric negetive'):array('data'=>number_format($row['lc_balance'],2, '.', ','),'class' => 'numeric');
				$this -> table -> add_row($row['date'],$row['bill_no'],$particulars,$gold,$lc,$gold_balance,$lc_balance);
			}
			$this -> table -> set_caption("Agent Name: ");
			$this -> table -> set_template($this -> default_table_template());
			$main_data['tables'] = $this -> table -> generate();
			$this -> load -> view('includes/report/template', $this->set_view_data($subject_id, 'report_view/common_report_view', $main_data));
		}
	}
	//Agent wise due Report
	function agent_wise_customer_due_action(){
		//print_r($_POST);
		$subject_id=0;
		$this -> form_validation -> set_rules('agent_id', 'Agent ID', 'trim|required');
		$this -> form_validation -> set_error_delimiters('<div class="error">', '</div>');
		if ($this -> form_validation -> run() == FALSE) {
			$this -> customer_wise_receipt_facade();
			} else {
			$result=$this->report_model->get_agent_wise_due($_POST['agent_id']);
			$this -> load -> library('table');
			$this -> table -> set_heading('Date','Bill No.','Particulars','Gold','L/C','Gold Due','LC Due');
			foreach ($result as $row) {
				$temp_text=explode(' ',$row['particulars']);
				$particulars=$temp_text[0]=='Less:'?array('data'=>$row['particulars'],'class'=>'greenLine'):array('data'=>$row['particulars']);
				$gold=$row['gold']<0?array('data'=>number_format($row['gold'],3, '.', ','),'class' => 'numeric negetive'):array('data'=>number_format($row['gold'],3, '.', ','),'class' => 'numeric');
				$lc=$row['labour_charge']<0?array('data'=>number_format($row['labour_charge'],2, '.', ','),'class' => 'numeric negetive'):array('data'=>number_format($row['labour_charge'],2, '.', ','),'class' => 'numeric');
				$gold_balance=$row['gold_balance']<0?array('data'=>number_format($row['gold_balance'],3, '.', ','),'class' => 'numeric negetive'):array('data'=>number_format($row['gold_balance'],3, '.', ','),'class' => 'numeric');
				$lc_balance=$row['lc_balance']<0?array('data'=>number_format($row['lc_balance'],2, '.', ','),'class' => 'numeric negetive'):array('data'=>number_format($row['lc_balance'],2, '.', ','),'class' => 'numeric');
				$this -> table -> add_row($row['date'],$row['bill_no'],$particulars,$gold,$lc,$gold_balance,$lc_balance);
			}
			$this -> table -> set_caption("Agent Name: ");
			$this -> table -> set_template($this -> default_table_template());
			$main_data['tables'] = $this -> table -> generate();
			$this -> load -> view('includes/report/template', $this->set_view_data($subject_id, 'report_view/agent_wise_customer_due', $main_data));
		}
	}
	
	function find_dues_as_agent_facade(){
		$subject_id=0;
		$main_data=array();
		$result=$this->customer_model->get_agents();
		$agents=array();
		foreach($result as $row){
			$agents[]=array('label'=>$row['agent_id'].' '.$row['agent_name'],'value'=>$row['agent_id']);
		}
		$main_data['agents']=$agents;
		$this -> load -> view('includes/staff/template', $this->set_view_data($subject_id, 'report_view/agent_wise_due_view', $main_data));
	}
	function show_bill_by_bill_no(){
		$bill_partst=explode('/',$_GET['bill_no']);
		if($bill_partst[0]=='SBJW'){
			$bill_id=$this->bill_model->get_bill_id_by_bill_no($_GET['bill_no']);
			if($bill_id!=NULL){
				$this->bill_print_action($bill_id);
			}
			}elseif($bill_partst[0]=='GSB'){
			echo "Gold";
			}elseif($bill_partst[0]=='CSB'){
			echo "Cash";
			}else{
			echo "Not a valid bill to print";
		}
		
		
	}

	
	function show_duplicate_bill_facade(){
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
			,'bills/show_duplicate_bill'
			
		);
		
		$css=array('ui-lightness/jquery-ui-1.10.2.custom'
					,'table_css/table/jquery.dataTables'
					,'bills/show_duplicate_bill'
					,'message_box/Styles/msgBoxLight'
					);	   //for data table jquery
		
		$main_data['java_script']=$java_scripts;
		$main_data['css']=$css;
		$main_data['current_date']=get_current_date();
		$main_data['page_title']="Diamond Bill Print";
		$this->load->library('table');
   		$result=$this->Bill_model->get_customers_have_bill();
		$customers=array();
		if(is_object($result)){
			foreach($result->result() as $row){
				$customers[]=array('label'=>$row->cust_id.' - '.$row->cust_name,'value'=>$row->cust_id);
			}
		}
		$main_data['customers']=$customers;
		$result=$this->Bill_model->get_bills_by_cust_id();
		if($result==NULL){
			echo 'Error fetching record';
			return;
		}
		foreach($result->result() as $row){
			$this->table->add_row(
				cell_format(anchor_popup(site_url().'/bill_controller/bill_print_action?bill_no='.$row->bill_no,$row->bill_no,'text'),'text'),
				cell_format($row->bill_date,'text'),
				cell_format($row->cust_name,'text')
			);
		}
		$main_data['bill_table']=$this->table->generate();
		$this -> load -> view('includes/staff/template', $this -> set_site_data('bill_view/show_duplicate_bill_view',$main_data));
   }
	
	
	function test($cust_id){
		$resultCash=$this->bill_model->get_last_cash_received_by_cust_id($cust_id);
		print_r($resultCash);
	}
	
	function gold_received_bill_facade($receipt_id=''){
		$result=$this->bill_model->get_gold_received_information($receipt_id);
		$this->load->library('table');
		$this->table->add_row(cell_format($result->mailing_name,'text','column1','mailing_name'),
		cell_format('Receipt No.','text','column2'),
		cell_format($result->Receipt_no,'text','column3')
			);
		$this->table->add_row($result->cust_address,'Date',sql_date_to_dmy($result->receipt_date));
		$this->table->add_row($result->cust_phone,'Relationship No.',$result->Cust_ID);
		
		$main_data['table1']= $this->table->generate();
		$this->table->clear();
		
		$this->table->add_row(
		cell_format('Opening Balance','text','column1'),
		cell_format('Received','text','column2'),
		cell_format('current Balance','text','column2'),
		cell_format('Received Through','text','column2')
			);
		
		$this->table->add_row(
		cell_format($result->prev_gold_due,'gold'),
		cell_format($result->received_gold,'gold','received_gold'),
		cell_format($result->prev_gold_due-$result->received_gold,'gold'),
		cell_format($result->agent_name,'text')
			);
		$this->table->set_caption('Transaction information as on '.sql_date_to_dmy($result->receipt_date));
		$main_data['table2']= $this->table->generate();
		
		$this->table->clear();
		//*********************************   customer current status *********************************
		$cust_id=$result->Cust_ID;
		$result=$this->report_model->get_customer_dues_by_cust_id($cust_id);
		$lc_due=0;
		$gold_due=0;
		if($result!=NULL){
			$lc_due=$result->total_lc_due;
			$gold_due=$result->total_gold_due;
		}
		$this->load->library('table');
		
		
		$result=$this->report_model->get_job_in_progress_by_cust_id($cust_id);
		if($result!=NULL){
			$working_gold=0;
			$working_lc=0;
			foreach($result->result() as $row){
				$working_gold+=$row->gold_send+$row->pan_send+$row->p_loss-$row->gold_returned-$row->nitrick_returned;
				$working_lc+=$row->lc_working;
				$this->table->add_row(
				$row->job_id,
				$row->job_date,
				$row->gold_send,
				cell_format($row->pan_send),
				cell_format($row->p_loss,'gold'),
				cell_format($row->gold_returned,'gold'),
				cell_format($row->nitrick_returned,'gold'),
				cell_format($row->gold_send+$row->pan_send+$row->p_loss-$row->gold_returned-$row->nitrick_returned,'gold'),
				cell_format($row->lc_working)
					);
			}
		}
		$this -> table -> set_template($this -> default_table_template());
		$this->table->set_heading('Job ID','Job Date','Gold Send','Pan','p Loss','Gold Returned','Nitric Returned','Used Gold','LC');
		date_default_timezone_set('Asia/Kolkata');
		$this->table->set_caption('Current working Jobs as on '.date('d-m-Y H:i:s a'));
		$main_data['table3']= $this->table->generate();
		$this->table->clear();
		
		$this->table->add_row(cell_format('Current Due','text','column1')
			,cell_format($gold_due,'gold','column2'),
		cell_format($lc_due,'currency','column3 number')
			);
		$this->table->add_row('Working Due',
		cell_format($working_gold,'gold'),
		cell_format($working_lc,'currency','number')
			);
		$lc_due+=$working_lc;
		$gold_due+=$working_gold;
		$this->table->add_row('Total Due',cell_format($gold_due,'gold'),cell_format($lc_due,'currency'));
		$this->table->set_heading('Particulars','Gold Due','LC DUE');
		
		
		$current_time = date('d-m-Y G:i:s a');
		
		$this->table->set_caption("Customer Status  as on ".$current_time);
		$main_data['table4']= $this->table->generate();
		$this->table->clear();
		
		//*********************************************** end of current status ***********************
		
		$result=$this->report_model->get_due_bills_by_cust_id($cust_id);
		if($result==NULL){
			echo 'Error fetching record';
			return;
		}
		$this->load->library('table');
		$gold_due=0;
		$lc_due=0;
		foreach($result->result() as $row){
			$gold_due+=$row->gold_due;
			$lc_due+=$row->lc_due;
			$this->table->add_row(
			cell_format($row->bill_no,'text'),
			cell_format(sql_date_to_dmy($row->bill_date),'text'),
			cell_format($row->bill_gold,'gold'),
			cell_format($row->gold_cleared,'gold'),
			cell_format($row->gold_due,'gold'),
			cell_format($row->bill_labour_charge,'currency'),
			cell_format($row->cash_cleared,'currency'),
			cell_format($row->lc_due,'currency')
				);
		}
		$this->table->add_row(
		cell_format('Total Gold Due','text','','','','','4'),
		cell_format($gold_due,'gold','','','',''),
		cell_format('Total LC Due','text','','','','','2'),
		cell_format($lc_due,'currency','','','','')
			);
		$this -> table -> set_template(default_table_template());
		$result=$this->report_model->get_customer_by_cust_id($cust_id);
		$this->table->set_heading('Bill No.','Bill Date','Bill Gold','Received','Gold Due','Bill LC','Received','LC Due');
		$this->table->set_caption($result->cust_name.' '.date('d-m-Y H:i:s a'));
		$main_data['table5']=$this->table->generate();
		
		
		//---------------------------------------------------------------------------------------------
		$this -> load -> view('includes/gold_receipt/template', $this->set_bill_data('bill_view/display_gold_receipt', $main_data));
	}
	function show_daybook_by_period(){
		$start_date=$_GET['start_date'];
		$end_date=$_GET['end_gate'];
		
	}
	function to_excel($query, $filename='exceloutput'){
		$headers = ''; // just creating the var for field headers to append to below
		$data = ''; // just creating the var for field data to append to below
		
		// $obj =& get_instance();
		
		$fields = $query->field_data();
		if ($query->num_rows() == 0) {
			echo '<p>The table appears to have no data.</p>';
			} else {
			foreach ($fields as $field) {
				$headers .= $field->name . "\t";
			}
			
			foreach ($query->result() as $row) {
				$line = '';
				foreach($row as $value) {                                            
					if ((!isset($value)) OR ($value == "")) {
						$value = "\t";
						} else {
						$value = str_replace('"', '""', $value);
						$value = '"' . $value . '"' . "\t";
					}
					$line .= $value;
				}
				$data .= trim($line)."\n";
			}
			
			$data = str_replace("\r","",$data);
			
			//header("Content-type: application/x-msdownload");
			header("Content-Disposition: attachment; filename=$filename.xls");
			echo "$headers\n$data";  
		}
	}
	
	
	
	function test_action(){
		//$this->sql_to_csv('select * from agent_master','abcd.csv');
		$this->backup_tables();
	
		
	}
	
	/* backup the db OR just a table */
	function backup_tables($tables = '*'){
		//get all of the tables
		$return='';
		if($tables == '*'){
			$tables = array();
			$result = $this->db->query('SHOW TABLES');
			foreach($result->result() as $row){
				$tables[] = $row->Tables_in_gold;
			}
		}else{
			$tables = is_array($tables) ? $tables : explode(',',$tables);
		}
		
		//cycle through
		foreach($tables as $table){
			$result = mysql_query('SELECT * FROM '.$table);
			$num_fields = mysql_num_fields($result);
			$return.= 'DROP TABLE '.$table.';';
			$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
			$return.= "\n\n".$row2[1].";\n\n";
			
			for ($i = 0; $i < $num_fields; $i++) 
			{
				while($row = mysql_fetch_row($result))
				{
					$return.= 'INSERT INTO '.$table.' VALUES(';
					for($j=0; $j<$num_fields; $j++) 
					{
						$row[$j] = addslashes($row[$j]);
						//$row[$j] = ereg_replace("\n","\\n",$row[$j]);
					if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
					if ($j<($num_fields-1)) { $return.= ','; }
					}
					$return.= ");\n";
				}
			}
			$return.="\n\n\n";
		}
		
		//save file
		$this->load->helper('file');
		$this->load->helper('download');
		force_download('gold.sql', $return);
		$handle = fopen('db-backup-'.time().'-'.'.sql','w+');
		fwrite($handle,$return);
		fclose($handle);
	}
	
	function bill_print_action($bill_no=""){
		//this block of code is mandatory for all facade from now
		//-------------------------------------------------------------------------
		// extention should not be given
		$java_scripts=array(''
		);
		
		$css=array('bills/bill_style'
					);
		
		if(isset($_GET['bill_no'])){
			$bill_no=$_GET['bill_no'];
		}
		if($bill_no==""){
			echo "Error fetching record";
			return;
		}
		
		$SlNo=1;
		$TotalFineGold=0;
		$TotalLc=0;
		$cell_total_qty=0;
		$bill_text = array('data' => 'Received the following materials along with design in good condition for manufacturing of Bangles against Labour Charges Only.','colspan' => 4,'id'=>'bill_text');
		
		$result=$this->bill_model->get_bill_master_data($bill_no);
		if($result==NULL){
			echo "Error fetching bill master";
			return;
		}
		
		$this->load->library('table');
		
		$this->table->add_row($bill_text,'','','');
		$row1_col1=array('data'=>'Customer Name & Address','class'=>'column1','id'=>'consignee_name_n_address');
		$row1_col2=array('data'=>'','class'=>'column2');
		$row1_col3=array('data'=>'<b>'.'Bill No.'.'</b>','class'=>'column3');
		$row1_col4=array('data'=>$result->bill_no,'class'=>'column4');
		$this->table->add_row($row1_col1,$row1_col2,$row1_col3,$row1_col4);
		
		
		$row2_col1=array('data'=>$result->mailing_name,'class'=>'column1','id'=>'mailing_name');
		$row2_col2=array('data'=>'','class'=>'column2');
		$row2_col3=array('data'=>'<b>'.'Bill Date'.'</b>','class'=>'column3');
		$row2_col4=array('data'=>timestamp_to_dmy($result->bill_date),'class'=>'column4');
		$this->table->add_row($row2_col1,$row2_col2,$row2_col3,$row2_col4);
		
		$row3_col1=array('data'=>$result->cust_address,'class'=>'column1','id'=>'customer_address');
		$row3_col2=array('data'=>'','class'=>'column2');
		$row3_col3=array('data'=>'<b>'.'Order No'.'</b>','class'=>'column3');
		$row3_col4=array('data'=>$result->order_id,'class'=>'column4');
		$this->table->add_row($row3_col1,$row3_col2,$row3_col3,$row3_col4);
		
		$row4_col1=array('data'=>'Ph:'.$result->cust_phone,'class'=>'column1','id'=>'customer_phone');
		$row4_col2=array('data'=>'','class'=>'column2');
		$row4_col3=array('data'=>'<b>'.'Order Date'.'</b>','class'=>'column3');
		$row4_col4=array('data'=>sql_date_to_dmy($result->DoO),'class'=>'column4');
		$this->table->add_row($row4_col1,$row4_col2,$row4_col3,$row4_col4);
		
		//$this->table->add_row($bill_text,'','','');
		$row_gap=cell_format('&nbsp','text','row_gap');
		$this->table->add_row($row_gap,'','','');
		
		$this -> table -> set_template(default_table_template('id="bill-master-table"'));
		$main_data['table1'] = $this -> table -> generate();
		$this->table->clear();
		//following codes for bill details
		$result=$this->bill_model->get_bill_print_details($bill_no);
		//-------------- starat with Second Table -------------------------------
		$this -> load -> library('table');
		$this -> table -> set_heading('SL No.','Job ID.','Model','Size','Gold Used','Gross Weight','Wastage (%)','Wastage','Gold Quality','Total Gold','Fine Gold','Qty','Making Charge');
		$SlNo=0;
		$goldUsed=0;
		$GrossWt=0;
		$TotalGold=0;
		$FineGold=0;
		$lc=0;
		$total_qty=0;
		foreach ($result->result() as $row) {	
			$this -> table -> add_row(cell_format(++$SlNo,'integer')
									  ,cell_format($row->job_id,'job')
									  ,cell_format($row->model_no.'-'.$row->price_code,'text')
									  ,cell_format($row->size,'text')
									  ,cell_format($row->total_gold,'gold')
									  ,cell_format($row->gross_wt,'gold')
									  ,cell_format($row->wastage_percentage,'percent')
									  ,cell_format($row->wastage,'gold')
									  ,cell_format($row->gold_quality,'percent','gold_quality')
									  ,cell_format($row->total_gold,'gold')
									  ,cell_format($row->fine_gold,'gold')
									  ,cell_format($row->qty,'integer')
									  ,cell_format($row->labour_charge,'currency')
									  );
			$goldUsed+=$row->total_gold;
			$GrossWt+=$row->gross_wt;
			$TotalGold+=$row->total_gold;
			$FineGold+=$row->fine_gold;
			$total_qty+=$row->qty;
			$lc+=$row->labour_charge;
		}
		$this->table->set_footer(cell_format('Total','text','','','','','4')
								 ,cell_format($goldUsed,'gold')
								 ,cell_format($GrossWt,'gold')
								 ,cell_format('','text')
								 ,cell_format('0.000','text')
								 ,cell_format('','text')
								 ,cell_format($TotalGold,'gold')
								 ,cell_format($FineGold,'gold')
								 ,cell_format($total_qty,'integer')
								 ,cell_format($lc,'currency')
								);
		
		$this -> table -> set_template(default_table_template());
		$main_data['table2'] = $this -> table -> generate();
		$main_data['total_lc']=$lc;
		$this->table->clear();
		//footer area
		$row1_col1=cell_format('Customer Declaration ','text','table4_col1');
		$row1_col2=cell_format(' ','text','table4_col2');
		$row1_col3=cell_format('Authorised Signatory','text','table4_col3');
		$this -> table -> add_row($row1_col1,$row1_col2,$row1_col3);
		
		$row2_col1=cell_format('Received goods as per requirement and in good condition ','text','table4_col1');
		$row2_col2=cell_format(' ','text','table4_col2');
		$row2_col3=cell_format('for Srikrishna Bangle Jewellery Workshop','text','table4_col3');
		$this -> table -> add_row($row2_col1,$row2_col2,$row2_col3);
		
		
		$row3_col1=cell_format(' ','text','table4_col1');
		$row3_col2=cell_format(' ','text','table4_col2');
		$row3_col3=cell_format('E. & O.E.','text','table4_col3');
		$this -> table -> add_row($row3_col1,$row3_col2,$row3_col3);
		
		$row4_col1=cell_format(' ','text','table4_col1');
		$row4_col2=cell_format(' ','text','table4_col2');
		$row4_col3=cell_format('','text','table4_col3');
		$this -> table -> add_row($row4_col1,$row4_col2,$row4_col3);
		
		$row5_col1=cell_format('Customer Signature ','text','table4_col1');
		$row5_col2=cell_format(' ','text','table4_col2');
		$row5_col3=cell_format('','text','table4_col3');
		$this -> table -> add_row($row5_col1,$row5_col2,$row5_col3);
		
		$this -> table -> set_caption("");
		$this->table->set_footer('');
		$this -> table -> set_template(default_table_template('id="footer-table"'));
		$main_data['table4'] = $this -> table -> generate();
		$this->table->clear();
		//end of footer
		$main_data['java_script']=$java_scripts;
		$main_data['css']=$css;
		$main_data['page_title']="Bill ".$bill_no;
		$this -> load -> view('includes/bill/template', $this->set_bill_data('bill_view/display_bill', $main_data));
	}
	
	//new bill print
	function display_bill(){
		$main_data=array();
		//this block of code is mandatory for all facade from now
		//-------------------------------------------------------------------------
		// extention should not be given
		$java_scripts=array('ui/jquery-1.8.3'
							,'general'
							,'message_box/Scripts/jquery.msgBox'
							);
		$css=array('message_box/Styles/msgBoxLight'
					,'bills/display_sale_bill');	  
		$main_data['java_script']=$java_scripts;
		$main_data['css']=$css;
		$main_data['page_title']="Bill - ".$_GET['bill_no'];
		$company_details=$this->db->query("select * from company_details")->row();
		$bill_master=$this->Bill_model->get_bill_master_by_bill_no($_GET['bill_no']);
		if($bill_master==NULL){
			echo "This is not a valid Bill";
			return;
		}
		$cust_id=$bill_master->cust_id;
		$this->load->library('table');
		$this->table->add_row(cell_format('Customer Name & Address','text','column1'),cell_format('','text','column2'),cell_format('Bill No.','text','column3'),cell_format($bill_master->bill_no,'text','column4'));
		$this->table->add_row(cell_format($bill_master->mailing_name,'text','column1','cust_name'),cell_format('','text','column2'),cell_format('Bill Date','text','column3'),cell_format($bill_master->bill_date,'text','column4'));
		$this->table->add_row(cell_format($bill_master->cust_address.'-'.$bill_master->city,'text','column1'),cell_format('','text','column2'),cell_format('Order No.','text','column3'),cell_format($bill_master->order_id,'text','column4'));
		$this->table->add_row(cell_format($bill_master->cust_phone,'text','column1'),cell_format('','text','column2'),cell_format('Order Date.','text','column3'),cell_format($bill_master->tr_time,'text','column4'));
		$cust_id=$bill_master->cust_id;
		$this->table->set_template(default_table_template('id="table1"'));
		$main_data['table1']=$this->table->generate();
		$bill_details=$this->Bill_model->get_bill_details_for_bii_by_bill_no($_GET['bill_no']);
		$sl=0;
		$qty=0;
		$lc=0;
		$fine=0;
		$total_gold=0;
		foreach($bill_details->result() as $row){
			$this->table->add_row(	cell_format(++$sl,'integer','sl center')
									,cell_format($row->job_id,'text','job_id center')
									,cell_format($row->model_no.'-'.$row->price_code,'text','model')
									,cell_format($row->size,'text','size')
									,cell_format($row->gross_wt,'gold','gross center')
									,cell_format($row->total_gold,'gold','total_gold right')
									,cell_format($row->gold_quality,'percent','gold_quality center')
									,cell_format($row->fine_gold,'gold','fine_gold right')
									,cell_format($row->qty,'integer','qty right')
									,cell_format($row->labour_charge,'currency','lc right')
								);
								$qty+=$row->qty;
								$lc+=$row->labour_charge;
								$fine+=$row->fine_gold;
								$total_gold+=$row->total_gold;
			}
			$this->table->set_heading(
										cell_format('SL','text','sl')
										,cell_format('JOB/TAG','text','job_id')									
										,cell_format('Model','text','model')									
										,cell_format('Size','text','size')	
										,cell_format('Gross','text','gross')									
										,cell_format('USED GOLD','text','total_gold')									
										,cell_format('GOLD CATEGORY','text','gold_quality')									
										,cell_format('PURE','text','fine_gold')									
										,cell_format('QTY','text','qty')									
										,cell_format('LABOUR CHARGE','text','lc')									
									);
			$this->table->set_footer(
										cell_format('TOTAL','text','total','','','','5')
										//,cell_format('JOB/TAG','text','job_id')									
										//,cell_format('Model','text','model')									
										//,cell_format('Size','text','size')									
										,cell_format($total_gold,'gold','total_gold right')									
										,cell_format('','text','gold_quality')									
										,cell_format($fine,'gold','fine_gold right')									
										,cell_format($qty,'integer','qty right')									
										,cell_format($lc,'currency','lc right')									
									);
		
		$this->table->set_template(default_table_template('id="bill_details"'));
		$main_data['table2']=$this->table->generate();
		$main_data['lc_in_word']=convert_number_to_words($lc);
		$main_data['company_details']=$company_details;
		$this->table->clear();
		//add your customer declaration
		$this->table->add_row(cell_format('','text','column1')
							  ,cell_format(' ','text','column2')
							  ,cell_format('<strong>Party Signature with Stamp, Date & Time</strong>','text','column3')
							 );
		$this->table->add_row(cell_format('','text','column1')
							  ,cell_format(' ','text','column2')
							  ,cell_format('Received goods as per requirement and in good condition.','text','column3')
							 );
		$this->table->add_row(cell_format(' ','text','column1')
							  ,cell_format(' ','text','column2')
							  ,cell_format('','text','column3')
							 );
		$agent_details=$this->Bill_model->get_agent_by_cust_id($cust_id);
		$agent_name='None';
		$agent_phone='None';
		if($agent_details!=NULL){
			$agent_name=$agent_details->agent_name;
			$agent_phone=$agent_details->agent_phone;
		}
			
			
		$this->table->add_row(cell_format(' ','text','column1')
							  ,cell_format('','text','column2')
							  //,cell_format('TC : '.$agent_name.'</br>Phone : '.$agent_phone,'text','column3')
							  ,cell_format('','text','column3')
							 );
		$this->table->set_footer(
										cell_format('','text','total','','','','4')
										//,cell_format('JOB/TAG','text','job_id')									
										//,cell_format('Model','text','model')									
										//,cell_format('Size','text','size')									
										,cell_format('','text','total_gold right')									
										,cell_format('','text','gold_quality')									
										,cell_format('','text','fine_gold right')									
										,cell_format('','text','qty right')									
										,cell_format('','text','lc right')									
									);
		$this->table->set_template(default_table_template('id="declaration"'));
		$main_data['table3']=$this->table->generate();
		$this->table->clear();
		$result=$this->Bill_model->select_customer_dues_by_cust_id($cust_id);
		$customer_dues['gold']=0;
		$customer_dues['lc']=0;
		if($result!=NULL){
			$customer_dues['gold']=$result->gold_due;
			$customer_dues['lc']=$result->lc_due;
		}
		$main_data['customer_dues']=$customer_dues;
		$this->table->clear();
		
		$this->table->add_row(cell_format('','text','column1')
							  ,cell_format('Gold','text','column2')
							  ,cell_format('LC','text','column2')
							  );
		$this->table->add_row(cell_format('','text','column1')
							  ,cell_format($customer_dues['gold'],'gold','column2 right')
							  ,cell_format($customer_dues['lc'],'currency','column2 right')
							  );
	
		$this->table->set_caption("Your Current Status");
		$this->table->set_template(default_table_template('id="due-table"'));
		$main_data['customer_due_table']=$this->table->generate();
		$result=$this->db->query("select * from system_variables where variable_key='bill_instruction_1'")->row();
		$main_data['bill_footer']=$result->variable_value;
		$this -> load -> view('includes/bill/template', $this -> set_site_data('bill/display_sale_bill',$main_data));
	}
}// end of report_controller



?>