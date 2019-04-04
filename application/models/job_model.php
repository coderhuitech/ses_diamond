<?php
class Job_model extends CI_Model {

	function __construct() {
		parent::__construct();
	
		$this -> load -> helper(array('huiui'));
	}
	function select_sold_job_by_job_id($job_id){
		$sql="select *,dstodate(bill_date) as date_of_bill from bill_details 
				inner join bill_master ON bill_master.bill_id = bill_details.bill_id
				inner join customer_master ON customer_master.cust_id = bill_master.cust_id
				inner join agent_master ON agent_master.agent_id = bill_master.agent_id
				where job_id=?";
		$result=$this->db->query($sql,array($job_id));
		if($result->num_rows()>0){
			return $result->row();
		}else{
			return NULL;
		}
	}
	function select_fresh_orders(){
		$sql="select distinct order_details.order_id
       ,left(customer_master.cust_name,15) as cust_name
		from order_details 
		inner join order_master 
		on order_details.order_id = order_master.order_id
		inner join customer_master on order_master.cust_id = customer_master.cust_id
    inner join customer_balance on customer_balance.cust_id = customer_master.cust_id
		where status=0 and order_master.status_id=0 and customer_master.gold_limit>(customer_balance.opening_gold+customer_balance.billed_gold-customer_balance.received_gold)";
		$result=$this->db->query($sql);
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function select_fresh_orders_from_order_details_by_order_id($order_id){
		$sql="select * from order_details
			inner join table_status
			on order_details.status=table_status.status_ID
				where order_id=?";
		$result=$this->db->query($sql,array($order_id));
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function get_order_from_order_details_by_order_no($roder_details_order_no){
		$sql="select * from order_details
			inner join table_status
			on order_details.status = table_status.status_ID
			inner join rm_master
			on order_details.rm_id = rm_master.rm_ID
			where order_no=?";
		$result=$this->db->query($sql,array($roder_details_order_no));
		if($result->num_rows()>0){
			return $result->row();
		}else{
			return NULL;
		}
	}
	
	function get_employees_from_gorit(){
		$sql="select * from employees where department_id=15 order by emp_name";
		$result=$this->db->query($sql);
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function get_order_by_order_no($roder_details_order_no){
		$sql="select * from order_details where order_no=?";
		$result=$this->db->query($sql,array($roder_details_order_no));
		if($result->num_rows()>0){
			return $result->row();
		}else{
			return NULL;
		}
	}
	
	function get_job_by_order_id_and_order_no($roder_details_order_no){
		$sql="select *
				,dstodate(job_master.job_date) as job_date2
				,dstodate(order_master.delivery_date) as dd from job_master 
				inner join rm_master on job_master.rm_id = rm_master.rm_ID
				inner join employees on job_master.emp_id = employees.emp_id
				where order_no=?";
		$result=$this->db->query($sql,array($roder_details_order_no));
		if($result->num_rows()>0){
			return $result->row();
		}else{
			return NULL;
		}
	}
	function get_job_by_job_id($job_id){
		$sql="select *
				,date(job_master.tr_time) as job_date2
				,date(order_master.delivery_date) as dd from job_master 
				inner join rm_master on job_master.rm_id = rm_master.rm_ID
				inner join employees on job_master.emp_id = employees.emp_id
        inner join order_master on job_master.order_id = order_master.order_id
        inner join customer_master on order_master.cust_id = customer_master.cust_id
        inner join table_status on job_master.status=table_status.status_ID
				where job_master.job_id=?";
		$result=$this->db->query($sql,array($job_id));
		if($result->num_rows()>0){
			return $result->row();
		}else{
			return NULL;
		}
	}
	function save_order_to_job_master(){
		$return_array=array();
		try{
		$this->db->query("START TRANSACTION");
		//get order qty
		$sql="select qty from order_details where order_no=? and order_id=?";
		$result=$this->db->query($sql,array($_GET['order_details_order_no'],$_GET['challan_no']))->row();
		if($result==FALSE){
			throw new Exception('order_master');
		}
		$qty=$result->qty;
		//get markup value
		$sql="select * from order_master 
				inner join customer_master on order_master.cust_id = customer_master.cust_id
				where order_id=?";
		$result=$this->db->query($sql,array($_GET['challan_no']))->row();
		if($result==FALSE){
			throw new Exception('order_master');
		}
		$markup_value=($result->markup_value)*($result->markuped);
		//auto testing mode is off from 20-04-2016
		/*if ( $qty & 1 ) {
		  	$markup_value=-0.06;
		} */
		
		$result=$this->db->query("update maxtable 
								set mainfield=last_insert_id(mainfield+1) 
								where table_name='job_master'");
		if($result==FALSE){
			throw new Exception('maxtable ');
		}
		$job_id=$this->db->query("select LAST_INSERT_ID() as job_id")->row()->job_id;
		$return_array['job_id']=$job_id;
		
		$sql="insert into job_master (
				   job_id
				  ,order_id
				  ,order_serial
				  ,order_no
				  ,product_code
				  ,rm_id
				  ,pieces
				  ,product_size
				  ,job_date
				  ,delivery_date
				  ,expected_gold
				  ,p_loss
				  ,tr_date
				  ,status
				  ,emp_id
				  ,price_method
				  ,price_code
				  ,price
				  ,markup_value
				  ,gold_send
				  ,comments
				  ,ip
				) SELECT ?,order_id,sl_no ,order_no,product_code,rm_id,qty,prd_size,?,?,gold_wt,p_loss,?,5,?,'Regular',price_code,price,?,?,'--',?
				FROM order_details
				where  order_no=? and order_id=? and status=0";
		
		$result=$this->db->query($sql,array($return_array['job_id']
										   	,date_to_serial(get_current_date())
											,date_to_serial(get_current_date())+5
											,date_to_serial(get_current_date())
											,$_GET['karigar']
											,$markup_value
											,$_GET['gold_send']
											,$_SERVER['SERVER_ADDR']
											,$_GET['order_details_order_no']
											,$_GET['challan_no']
										   ));
		if($result==FALSE){
			throw new Exception('job_master');
		}
		
		
		$sql="update order_details set status=5 WHERE order_no=? and order_id=? and status=0";
		$this->db->query($sql,array($_GET['order_details_order_no']
									,$_GET['challan_no']
									));
		if($result==FALSE){
			throw new Exception('order_details');
		}							
		//adding inventory transaction to table
		$sql="insert into inventory_day_book (
			  employee_id
			  ,rm_id
			  ,transaction_type
			  ,rm_value
			  ,reference
			  ,comment
			) VALUES (?,?,-1,?,?,'Gold send to Job')";
		$result=$this->db->query($sql,array($this->session->userdata('employee_id')
									,$_GET['rm_id']
									,$_GET['gold_send']
									,$return_array['job_id']
								    ));
		if($result==FALSE){
			throw new Exception('material_to_employee_balance');
		}
		
		//adding record to material_to_employee_balance
		$sql="insert into material_to_employee_balance (
			  emp_id
			  ,rm_id
			  ,opening_balance
			  ,outward
			  ,closing_balance
			) VALUES (?,?,0,?,?) on duplicate key update 
			outward=outward+?
			, closing_balance=closing_balance-?";
		$result=$this->db->query($sql,array($this->session->userdata('employee_id')
											,$_GET['rm_id']
											,$_GET['gold_send']
											,-$_GET['gold_send']
											,$_GET['gold_send']
											,$_GET['gold_send']
											));
		if($result==FALSE){
			throw new Exception('material_to_employee_balance');
		}
		//add used gold to pettystock
		$sql="insert into stocktoemployees (
		   emp_id
		  ,rm_id
		  ,job_id
		  ,tr_date
		  ,tr_value
		  ,tr_type
		  ,comments
		  ,inforce
		) VALUES (?,?,?,?,?,-1,'used',1)";
		$this->db->query($sql,array($this->session->userdata('employee_id')
									,$_GET['rm_id']
									,$return_array['job_id']
									,date_to_serial(get_current_date())
									,$_GET['gold_send']
								    ));
		if($result==FALSE){
			throw new Exception('stocktoemployees');
		}
		
		$this->db->query("COMMIT");
		$return_array['success']=1;
		}catch(Exception $e){
			$return_array['error_code']=create_log($this->db->last_query(),'job_model','save_order_to_job_master',"log_file.csv");
			$this->db->query("ROLLBACK");
			$return_array['success']=0;
			$return_array['error']='Caught exception: '. $e->getMessage()."\n";
		}
		return $return_array;
	}
	function get_balance_table_by_machine($machine){
		$sql="select * from balance_table where machine_name=?";
		$result=$this->db->query($sql,array($machine));
		$return_array['sql']=$this->db->last_query();
		$this->db->query("delete from balance_table where machine_name=?",array($machine));
		if($result->num_rows()>0){
			$return_array['result']=$result->row();
		}else{
			$return_array['result']=NULL;
		}
		return $return_array;
	}
	function get_phaseI_jobs(){
		$sql="select * from job_master 
		inner join employees on job_master.emp_id = employees.emp_id
		where status=5 and in_stock=0";
		$result=$this->db->query($sql);
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function save_phaseI(){
		$return_array=array();
		try{
				$this->db->query("START TRANSACTION");
				//adding record to job_master
				$sql="update job_master SET
					  dal_send = ?
					  ,bronze_send = ?
					  ,gold_returned = ?
					  ,dal_id = 33
					  ,bronze_id = 44
					  ,status=51
					WHERE job_id = ?";
				$result=$this->db->query($sql,array(
											$_POST['dal_used']
											,$_POST['bronze_used']
											,$_POST['return_gold']
											,$_POST['job_id']
										 ));
				if($result==FALSE){
					throw new Exception('job_master ');
				}
				// adding dal to inventory day book
				$sql="insert into inventory_day_book (
					  employee_id
					  ,rm_id
					  ,transaction_type
					  ,rm_value
					  ,reference
					  ,comment
					) VALUES (?,33,-1,?,?,'DAL used in Job')";
				$this->db->query($sql,array($_POST['employee_id']
											,$_POST['dal_used']
											,$_POST['job_id']
										    ));
				if($result==FALSE){
					throw new Exception('inventory_day_book ');
				}
				//adding karigar dal used to material_to_employees table
				$sql="insert into material_to_employee_balance (
					  emp_id
					  ,rm_id
					  ,opening_balance
					  ,outward
					  ,closing_balance
					) VALUES (?,33,0,?,?) on duplicate key update 
					outward=outward+?
					, closing_balance=closing_balance-?";
				$result=$this->db->query($sql,array($_POST['employee_id']
													,$_POST['dal_used']
													,-$_POST['dal_used']
													,$_POST['dal_used']
													,$_POST['dal_used']
													));
				if($result==FALSE){
					throw new Exception('material_to_employee_balance ');
				}
				//add bronze to inventory day book
				$sql="insert into inventory_day_book (
					  employee_id
					  ,rm_id
					  ,transaction_type
					  ,rm_value
					  ,reference
					  ,comment
					) VALUES (?,44,-1,?,?,'Bronze used in Job')";
				$this->db->query($sql,array($_POST['employee_id']
											,$_POST['bronze_used']
											,$_POST['job_id']
										    ));
				if($result==FALSE){
					throw new Exception('inventory_day_book ');
				}
				//adding karigar bronze used to material_to_employees table
				$sql="insert into material_to_employee_balance (
					  emp_id
					  ,rm_id
					  ,opening_balance
					  ,outward
					  ,closing_balance
					) VALUES (?,44,0,?,?) on duplicate key update 
					outward=outward+?
					, closing_balance=closing_balance-?";
				$result=$this->db->query($sql,array($_POST['employee_id']
													,$_POST['bronze_used']
													,-$_POST['bronze_used']
													,$_POST['bronze_used']
													,$_POST['bronze_used']
													));
				if($result==FALSE){
					throw new Exception('material_to_employee_balance ');
				}
				//add gold to inventory day book
				$sql="insert into inventory_day_book (
					  employee_id
					  ,rm_id
					  ,transaction_type
					  ,rm_value
					  ,reference
					  ,comment
					) VALUES (?,?,1,?,?,'Mathakata Returned from Job')";
				$this->db->query($sql,array($_POST['user_id']
											,$_POST['rm_id']
											,$_POST['return_gold']
											,$_POST['job_id']
										    ));
				if($result==FALSE){
					throw new Exception('gold to inventory day book ');
				}
				//adding employees mathakata used to material_to_employees table
				$sql="insert into material_to_employee_balance (
					  emp_id
					  ,rm_id
					  ,opening_balance
					  ,inward
					  ,closing_balance
					) VALUES (?,?,0,?,?) on duplicate key update 
					inward=inward+?
					, closing_balance=closing_balance+?";
				$result=$this->db->query($sql,array($_POST['user_id']
													,$_POST['rm_id']
													,$_POST['return_gold']
													,$_POST['return_gold']
													,$_POST['return_gold']
													,$_POST['return_gold']
													));
				if($result==FALSE){
					throw new Exception('material_to_employee_balance ');
				}
				$this->db->query("COMMIT");
				$return_array['success']=1;
		}catch(Exception $e){
			$this->db->query("ROLLBACK");
			$return_array['error']='Caught exception: '. $e->getMessage()."\n";
			$return_array['success']=0;
		}	
		
		return $return_array;
	}
	function get_jobs_by_status($status_id){
		$sql="select 
		job_id
       , order_id
       , order_serial
       , order_no
       , product_code
       , rm_id
       , pieces
       , product_size
       , job_date
       , delivery_date
       , expected_gold
       , TRUNCATE(p_loss,3)AS p_loss
       , tr_date
       , tr_time
       , status
       , JOB_MASTER.emp_id
       , price_method
       , price_code
       , price
       , TRUNCATE(gold_send,3)as gold_send
       , TRUNCATE(dal_send,3)as dal_send
       , TRUNCATE(pan_send,3)as pan_send
       , bronze_send
       , copper_send
       , TRUNCATE(gold_returned,3)as gold_returned
       , dal_returned
       , pan_returned
       , bronze_returned
       , TRUNCATE(nitrick_returned,3)as nitrick_returned
       , copper_return
       , TRUNCATE(product_wt,3)as product_wt
       , comments
       , dal_id
       , pan_id
       , bronze_id
       , copper_id
       , TRUNCATE(markup_value,3)as markup_value
       , employees.*
       from job_master 
		inner join employees on job_master.emp_id = employees.emp_id
		where status=? and in_stock=0";
		$result=$this->db->query($sql,array($status_id));
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function get_jobs_by_status_for_pan($status_id){
		$sql="select * from job_master 
		inner join employees on job_master.emp_id = employees.emp_id
		where status=? and in_stock=0";
		$result=$this->db->query($sql,array($status_id));
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function get_jobs_by_status_for_topup_pan(){
		$sql="select * from job_master 
		inner join employees on job_master.emp_id = employees.emp_id
		where status>5 and status<8 and in_stock=0";
		$result=$this->db->query($sql,array($status_id));
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function get_jobs_by_status_for_pan_return(){
		$sql="select * from job_master 
		inner join employees on job_master.emp_id = employees.emp_id
		where status=6 and in_stock=0";
		$result=$this->db->query($sql,array($status_id));
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	
	function save_phase_pan(){
		$return_array=array();
		try{
				$this->db->query("START TRANSACTION");
				//adding record to job_master
				$sql="update job_master SET
					   pan_id=?
					   ,pan_send=TRUNCATE(?,3)
					  ,status=6
					WHERE job_id = ?";
				$result=$this->db->query($sql,array(
											$_POST['pan_id']
											,$_POST['pan']
											,$_POST['job_id']
										 ));
				if($result==FALSE){
					throw new Exception('job_master ');
				}
				// adding dal to inventory day book
				$sql="insert into inventory_day_book (
					  employee_id
					  ,rm_id
					  ,transaction_type
					  ,rm_value
					  ,reference
					  ,comment
					) VALUES (?,?,-1,TRUNCATE(?,3),?,'PAN used in Job')";
				$result=$this->db->query($sql,array($_POST['employee_id']
											,$_POST['pan_id']
											,$_POST['pan']
											,$_POST['job_id']
										    ));
				if($result==FALSE){
					throw new Exception('inventory_day_book ');
				}
				//adding karigar dal used to material_to_employees table
				$sql="insert into material_to_employee_balance (
					  emp_id
					  ,rm_id
					  ,opening_balance
					  ,outward
					  ,closing_balance
					) VALUES (?,?,0,TRUNCATE(?,3),TRUNCATE(?,3)) on duplicate key update 
					outward=outward+TRUNCATE(?,3)
					, closing_balance=closing_balance-TRUNCATE(?,3)";
				$result=$this->db->query($sql,array($_POST['employee_id']
													,$_POST['pan_id']
													,$_POST['pan']
													,-$_POST['pan']
													,$_POST['pan']
													,$_POST['pan']
													));
				if($result==FALSE){
					throw new Exception('material_to_employee_balance ');
				}
				$this->db->query("COMMIT");
				$return_array['success']=1;
		}catch(Exception $e){
					$this->db->query("ROLLBACK");
					$return_array['error']='Caught exception: '. $e->getMessage();
					$return_array['success']=0;
		}	
		
		return $return_array;
	}
	function save_phaseII(){
		$return_array=array();
		try{
				$this->db->query("START TRANSACTION");
				//adding record to job_master
				$sql="update job_master SET
					   nitrick_returned=TRUNCATE(?,3)
					  ,status=7
					WHERE job_id = ?";
				$result=$this->db->query($sql,array(
											$_POST['ngr']*.96
											,$_POST['job_id']
										 ));
				if($result==FALSE){
					throw new Exception('job_master');
				}
				// adding dal to inventory day book
				$sql="insert into inventory_day_book (
					  employee_id
					  ,rm_id
					  ,transaction_type
					  ,rm_value
					  ,reference
					  ,comment
					) VALUES (?,45,1,TRUNCATE(?,3),?,'Nitric returned from job Job')";
				$this->db->query($sql,array($_POST['user_id']
											,$_POST['ngr']
											,$_POST['job_id']
										    ));
				if($result==FALSE){
					throw new Exception('inventory_day_book');
				}
				//adding nitric gold returned to material_to_employees table
				$sql="insert into material_to_employee_balance (
					  emp_id
					  ,rm_id
					  ,opening_balance
					  ,outward
					  ,inward
					  ,closing_balance
					) VALUES (?,45,0,0,TRUNCATE(?,3),TRUNCATE(?,3)) on duplicate key update 
					inward=inward+TRUNCATE(?,3)
					, closing_balance=closing_balance+TRUNCATE(?,3)";
				$result=$this->db->query($sql,array($_POST['user_id']
													,$_POST['ngr']
													,$_POST['ngr']
													,$_POST['ngr']
													,$_POST['ngr']
													));
				if($result==FALSE){
					throw new Exception('material_to_employee_balance');
				}
				$this->db->query("COMMIT");
				$return_array['success']=1;
		}catch(Exception $e){
			$this->db->query("ROLLBACK");	
			$return_array['error']='Caught exception: '. $e->getMessage()."\n";
			$return_array['success']=0;
		}	
		
		return $return_array;
	}
	function save_job_finish(){
		$return_array=array();
		try{
				$this->db->query("START TRANSACTION");
				//adding record to job_master
				if(!isset($_POST['pieces']) || $_POST['pieces']<=0){
					throw new Exception('pieces 0');
				}
				$sql="update job_master SET
					   product_wt=TRUNCATE(?,3)
					   ,pieces=?
					  ,status=8
					WHERE job_id = ?";
				$result=$this->db->query($sql,array(
											$_POST['gross_weight']
											,$_POST['pieces']
											,$_POST['job_id']
										 ));
				
				if($result==FALSE){
					throw new Exception('job_master');
				}
				
				$this->db->query("COMMIT");
				$return_array['success']=1;	
		}catch(Exception $e){
			$this->db->query("ROLLBACK");	
			$return_array['error']='Caught exception: '. $e->getMessage();
			$return_array['success']=0;
		}	
		
		return $return_array;
	}
	function get_billable_customers(){
		$sql="select distinct customer_master.cust_id,customer_master.cust_name
			from job_master 
			inne join order_master on inne.order_id = order_master.order_id
			inner join customer_master on order_master.cust_id = customer_master.cust_id
			where status=8";
		$result=$this->db->query($sql);
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function select_billable_order_by_customer($customer_id){
		$sql="select distinct order_master.order_id
			from job_master 
			inne join order_master on inne.order_id = order_master.order_id
			inner join customer_master on order_master.cust_id = customer_master.cust_id
			where status=8 and customer_master.cust_id=?";
		$result=$this->db->query($sql,array($customer_id));
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	
	
	function select_jobs_ready_for_bill($customer_id,$bill_id){
		$sql="select *
			from job_master 
			inne join order_master on inne.order_id = order_master.order_id
			inner join customer_master on order_master.cust_id = customer_master.cust_id
			where status=8 and pieces>0 and customer_master.cust_id=? and order_master.order_id=?";
		$result=$this->db->query($sql,array($customer_id,$bill_id));
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function select_jobs_not_ready_for_bill($customer_id,$bill_id){
		$sql="select *
			from job_master 
			inne join order_master on inne.order_id = order_master.order_id
			inner join customer_master on order_master.cust_id = customer_master.cust_id
			where status<8 and customer_master.cust_id=? and order_master.order_id=?";
		$result=$this->db->query($sql,array($customer_id,$bill_id));
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function get_customer_by_cust_id($cust_id){
		$sql="select * from customer_master where cust_id=?";
		$result=$this->db->query($sql,array($cust_id));
		return $result->row();
	}
	
	function create_n_save_bill(){
		//$this->db->trans_start();//begining of transaction
		try {
			//$this->db->trans_start();//begining of transaction
			/*$sql="lock table bill_master write, maxtable write, bill_details write, product_master write, rm_master write,job_master write";
            $result=$this->db->query($sql);
            if($result==FALSE){
                throw new Exception('Locking error');
            }*/
			$this->db->query("START TRANSACTION");
			$current_financial_year = get_financial_year();
			$sql = "insert into maxtable (table_name, mainfield, financial_year)values(?,1,?)on duplicate key UPDATE table_id=last_insert_id(table_id), mainfield=mainfield+1";
			$result = $this->db->query($sql, array('bill_master', $current_financial_year));
			if ($result == FALSE) {
				throw new Exception('maxtable ' . mysql_affected_rows());
			}
			//step 2: select mainfield as table name and current financial year
			$sql = "select * from maxtable where table_id=last_insert_id()";
			$result = $this->db->query($sql)->row();
			if ($result == FALSE) {
				throw new Exception('maxtable-get error ');
			}
			$bill_no = $result->prefix . '/' . $result->mainfield . '/' . $current_financial_year;
			$return_array['bill_no'] = $bill_no;
			$sql = "insert into bill_master (
			   bill_no
			  ,order_id
			  ,cust_id
			  ,emp_id
			) VALUES (?,?,?,?)";
			$result = $this->db->query($sql, array($bill_no
			, $_GET['order_id']
			, $_GET['customer_id']
			, $this->session->userdata('employee_id')
			));
			if ($result == FALSE) {
				throw new Exception('bill_master insertion error ');
			}

			$sql = "insert into bill_details (
			   bill_details_id
			  ,bill_no
			  ,job_id
			  ,model_no
			  ,price_code
			  ,gold_wt
			  ,gross_wt
			  ,total_gold
			  ,gold_quality
			  ,fine_gold
			  ,qty
			  ,size
			  ,ploss
			  ,labour_charge
			  ,markup_value
			) SELECT
			          ?
			         ,?
			         ,job_id
			         ,job_master.product_code
			         ,job_master.price_code
			         ,TRUNCATE(gold_send+(pan_send*.4)-gold_returned-nitrick_returned+(p_loss*pieces),3) as actual_gold
			         ,TRUNCATE(job_master.product_wt,3)
			         ,TRUNCATE(gold_send+(pan_send*.4)-gold_returned-nitrick_returned+(p_loss*pieces)+(markup_value*pieces),3) as gold_used
			         ,rm_master.rm_gold
			         ,TRUNCATE((gold_send+(pan_send*.4)-gold_returned-nitrick_returned+(p_loss*pieces)+(markup_value*pieces))*((rm_master.rm_gold)/100),3) as Fine
			         ,job_master.pieces
			         ,job_master.product_size
			         ,TRUNCATE(p_loss*pieces,3)  as total_ploss
			         ,job_master.price*pieces as LC
			         ,markup_value
			FROM job_master
			inner join rm_master on job_master.rm_id = rm_master.rm_ID
			inner join product_master on job_master.product_code = product_master.product_code
			where job_id=?";
			$count = 0;
			if (!isset($_GET['ar']) || count($_GET['ar']) <= 0) {
				throw new Exception('no item to bill');
			}
			$bill_details_array = $_GET['ar'];

			foreach ($bill_details_array as $job_id) {
				$bill_details_id = $bill_no . '-' . ++$count;
				$result = $this->db->query($sql, array($bill_details_id
				, $bill_no
				, $job_id
				));
				if ($result == FALSE) {
					throw new Exception('insert bill_details error ');
				}
			}
			if($_GET['customer_id']=='S1217') {
				//getting total bill
				$sql = "select sum(fine_gold) as total_fine_gold,sum(labour_charge) as total_lc from bill_details where bill_no=?";
				$result_bill_total = $this->db->query($sql, array($bill_no))->row();
				if ($result == FALSE) {
					throw new Exception('error getting billed gold and billed_lc');
				}

				//getting gold receipt number
				$sql = "insert into maxtable (table_name, mainfield, financial_year)values(?,1,?)on duplicate key UPDATE table_id=last_insert_id(table_id), mainfield=mainfield+1";
				$result = $this->db->query($sql, array('gold_receipt_master', $current_financial_year));
				if ($result == FALSE) {
					throw new Exception('maxtable update');
				}
				//step : select mainfield as table name and current financial year
				$sql = "select * from maxtable where table_id=last_insert_id()";
				$result = $this->db->query($sql)->row();
				if ($result == FALSE) {
					throw new Exception('maxtable insert');
				}
				$gold_receipt_id = $result->prefix . '/' . $result->mainfield . '/' . $current_financial_year;

				$sql = "insert into gold_receipt_master(
				   gold_receipt_id
				  ,cust_id
				  ,agent_id
				  ,rm_id
				  ,gold_value
				  ,gold_rate
				  ,cash
				  ,cheque_value
				  ,bank_details
				  ,emp_id
				  ,last_gold_balance
				  ,last_lc_balance
				) VALUES (
				  ?
				  ,'S1217'
				  ,'AG2022',36,?,1,0,0,'no bank',28,0,0
				)";
				$result = $this->db->query($sql, array($gold_receipt_id
				, $result_bill_total->total_fine_gold
				));

				if ($result == FALSE) {
					throw new Exception('gold_receipt_master');
				}

				//adding lc to lc receipt for readymade
				$sql="insert into maxtable (table_name, mainfield, financial_year)values(?,1,?)on duplicate key UPDATE table_id=last_insert_id(table_id), mainfield=mainfield+1";
				$result=$this->db->query($sql,array('lc_receipt_master',$current_financial_year));
				if($result==FALSE){
					throw new Exception('maxtable update');
				}
				//step 2: select mainfield as table name and current financial year
				$sql="select * from maxtable where table_id=last_insert_id()";
				$result=$this->db->query($sql)->row();
				if($result==FALSE){
					throw new Exception('maxtable select ');
				}
				$lc_receipt_no=$result->prefix.'/'.$result->mainfield.'/'.$current_financial_year;
				//adding record to lc_receipt_master
				$sql="insert into lc_receipt_master (
				   lc_receipt_no
				  ,cust_id
				  ,mode
				  ,amount
				  ,agent_id
				  ,emp_id
				) VALUES (?,'S1217',1,?,'AG2022',28)";
				$result=$this->db->query($sql,array($lc_receipt_no
				,$result_bill_total->total_lc
				));
				if($result==FALSE){
					throw new Exception('lc_receipt_master');
				}

			}

		//updating status of job master
		$sql="update job_master set status=9 where job_id=?";
		foreach($bill_details_array as $job_id){
			$result=$this->db->query($sql,array($job_id));
			if($result==FALSE){
				throw new Exception('update job_master error ');
			}
		}

		//updating bill_master by bill details
		$sql="call update_bill_master_by_bill_details(?)";
		$result=$this->db->query($sql,array($bill_no));
		if($result==FALSE){
				throw new Exception('call update_bill_master_by_bill_details error ');
		}
		//$this->db->query("unlock tables");
		//$this->db->trans_complete();
		//$this->db->query("unlock tables");
		$this->db->query("COMMIT");
		$return_array['success']=1;
		}catch(Exception $e){
			$return_array['error']=create_log($this->db->last_query(),'job_model','create_n_save_bill');
			$this->db->query("ROLLBACK");
			$return_array['success']=0;
		}	
		
		return $return_array;
	}
	
	
	//need to be modified
	function save_topup_pan(){
		$return_array=array();
		try{
			$this->db->query("START TRANSACTION");
			//adding record to job_master
			$sql="update job_master SET
				   pan_send=TRUNCATE(pan_send+?,3)
				WHERE job_id = ?";
			$result=$this->db->query($sql,array(
										 $_POST['pan']
										,$_POST['job_id']
									 ));
			if($result==FALSE){
				throw new Exception('job_master ');
			}
			// adding dal to inventory day book
			$sql="insert into inventory_day_book (
				  employee_id
				  ,rm_id
				  ,transaction_type
				  ,rm_value
				  ,reference
				  ,comment
				) VALUES (?,?,-1,TRUNCATE(?,3),?,'PAN used in Job')";
			$this->db->query($sql,array($_POST['employee_id']
										,$_POST['pan_id']
										,$_POST['pan']
										,$_POST['job_id']
									    ));
			if($result==FALSE){
				throw new Exception('inventory_day_book ');
			}
			//adding karigar dal used to material_to_employees table
			$sql="insert into material_to_employee_balance (
				  emp_id
				  ,rm_id
				  ,opening_balance
				  ,outward
				  ,closing_balance
				) VALUES (?,?,0,?,?) on duplicate key update 
				outward=outward+?
				, closing_balance=closing_balance-?";
			$result=$this->db->query($sql,array($_POST['employee_id']
												,$_POST['pan_id']
												,$_POST['pan']
												,-$_POST['pan']
												,$_POST['pan']
												,$_POST['pan']
												));
			if($result==FALSE){
				throw new Exception('material_to_employee_balance ');
			}
			$this->db->query("COMMIT");
			$return_array['success']=1;	
		}catch(Exception $e){
			$this->db->query("ROLLBACK");	
			$return_array['error']='Caught exception: '. $e->getMessage();
			$return_array['success']=0;
		}	
		
		return $return_array;
	}
	function save_pan_return(){
		$return_array=array();
		try{
			$this->db->query("START TRANSACTION");
			//adding record to job_master
			$sql="update job_master SET
				   pan_send=TRUNCATE(pan_send-?,3)
				WHERE job_id = ?";
			$result=$this->db->query($sql,array(
										 $_POST['pan']
										,$_POST['job_id']
									 ));
			if($result==FALSE){
				throw new Exception('job_master ');
			}
			
			// adding dal to inventory day book
			$sql="insert into inventory_day_book (
				  employee_id
				  ,rm_id
				  ,transaction_type
				  ,rm_value
				  ,reference
				  ,comment
				) VALUES (?,?,1,TRUNCATE(?,3),?,'PAN Return in Job')";
			$this->db->query($sql,array($this->session->userdata('employee_id')
										,$_POST['pan_id']
										,$_POST['pan']
										,$_POST['job_id']
									    ));
			if($result==FALSE){
				throw new Exception('inventory_day_book ');
			}
			
			//adding karigar dal used to material_to_employees table
			$sql="insert into material_to_employee_balance (
				  emp_id
				  ,rm_id
				  ,opening_balance
				  ,inward
				  ,closing_balance
				) VALUES (?,?,0,?,?) on duplicate key update 
				inward=inward+?
				, closing_balance=closing_balance+?";
			$result=$this->db->query($sql,array($this->session->userdata('employee_id')
												,$_POST['pan_id']
												,$_POST['pan']
												,$_POST['pan']
												,$_POST['pan']
												,$_POST['pan']
												));
			if($result==FALSE){
				throw new Exception('material_to_employee_balance ');
			}
			
			$this->db->query("COMMIT");
			$return_array['success']=1;	
		}catch(Exception $e){
			$return_array['error']=create_log($this->db->last_query(),'customer_model','add_fresh_customer',"log_file.csv");
			$this->db->query("ROLLBACK");	
			$return_array['success']=0;
		}	
		return $return_array;
	}
	
	function select_job_details_by_id($job_id){
		$sql="select * from job_master where job_id=? and status in (3,4,5,6,7,8,51)";
		$result=$this->db->query($sql,array($job_id));
		if($result->num_rows()>0){
			return $result->row();
		}else{
			return NULL;
		}
	}
}//final
?>