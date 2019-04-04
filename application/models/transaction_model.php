<?php
class Transaction_model extends CI_Model {

	function __construct() {
		parent::__construct();
	
		$this -> load -> helper(array('huiui'));
	}
	function insert_cash_received_from_customer(){
		$return_array=array();
		try{
			$this->db->query("START TRANSACTION");
			//lock tables
			$current_financial_year=get_financial_year();
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
				) VALUES (?,?,1,?,?,?)";
			$result=$this->db->query($sql,array($lc_receipt_no
												,$_GET['cust_id']
												,$_GET['lc_received']
												,$_GET['agent_id']
												,$this->session->userdata('employee_id')
												));
			if($result==FALSE){
				throw new Exception('lc_receipt_master');
			}
			//insert into cash_to_employee_balance
			$sql="insert into employees_cash_balance (
					   emp_id
					  ,op_balance
					  ,inward
					  ,outward
					  ,balance
					) VALUES (
					  ?,0,?,0,?
					) on duplicate key update inward=inward+?,balance=balance+?";
			$result=$this->db->query($sql,array($this->session->userdata('employee_id')
												,$_GET['lc_received']
												,$_GET['lc_received']
												,$_GET['lc_received']
												,$_GET['lc_received']
												));
			if($result==FALSE){
				throw new Exception('employees_cash_balance');
			}
			//updating or adding transaction to customer_due
			$sql="insert into customer_balance (
				   cust_id
				  ,opening_gold
				  ,opening_lc
				  ,billed_gold
				  ,billed_lc
				  ,received_gold
				  ,received_lc
				) VALUES (?,0,0,0,0,0,?) on duplicate key update received_lc=received_lc+?";
			$result=$this->db->query($sql,array($_GET['cust_id']
												,$_GET['lc_received']
												,$_GET['lc_received']
												));
			if($result==FALSE){
				throw new Exception('customer_balance');
			}
			$this->db->query("COMMIT");
			$return_array['success']=1;
			$return_array['transaction_no']=$lc_receipt_no;
		}catch(Exception $e){
			$return_array['transaction_no']="No Receipt";
			$return_array['error_code']=create_log($this->db->last_query(),'transaction_mode','insert_cash_received_from_customer',"log_file.csv");
			$return_array['last_query']=$this->db->last_query();
			$this->db->query("ROLLBACK");
			$return_array['success']=0;
			$return_array['error']='Error : '. $e->getMessage()."\n";
		}
		return $return_array;
	}
	function insert_gold_receipt_from_customer(){
		$return_array=array();
		try{
			$this->db->query("START TRANSACTION");
			//lock tables
			$current_financial_year=get_financial_year();
			$sql="insert into maxtable (table_name, mainfield, financial_year)values(?,1,?)on duplicate key UPDATE table_id=last_insert_id(table_id), mainfield=mainfield+1";
			$result=$this->db->query($sql,array('gold_receipt_master',$current_financial_year));
			if($result==FALSE){
				throw new Exception('maxtable update');
			}
			//step 2: select mainfield as table name and current financial year
			$sql="select * from maxtable where table_id=last_insert_id()";
			$result=$this->db->query($sql)->row();
			if($result==FALSE){
				throw new Exception('maxtable insert');
			}
			$gold_receipt_id=$result->prefix.'/'.$result->mainfield.'/'.$current_financial_year;
			//insert into gold_receipt_master
			$cash=0;
			$cheque_value=0;
			
			$sql="insert into gold_receipt_master(
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
				  ?,?,?,36,?,1,0,0,'no bank',?,?,?
				)";
			$result=$this->db->query($sql,array($gold_receipt_id
												,$_GET['cust_id']
												,$_GET['agent_id']
												,$_GET['receive_gold']
												,$this->session->userdata('employee_id')
												,$_GET['gold_due']
												,$_GET['last_lc_due']
												));
												
			if($result==FALSE){
				throw new Exception('gold_receipt_master');
			}
			//insert or update material_to_employee_balance
			$sql="insert into material_to_employee_balance (
				  emp_id
				  ,rm_id
				  ,inward
				  ,outward
				  ,opening_balance
				  ,closing_balance
				) VALUES (
				    ?,36,?,0,0,?
				) on duplicate key update inward=inward+?,closing_balance=closing_balance+? ";
			$result=$this->db->query($sql,array($this->session->userdata('employee_id')
												,$_GET['receive_gold']
												,$_GET['receive_gold']
												,$_GET['receive_gold']
												,$_GET['receive_gold']
												));
			
			if($result==FALSE){
				throw new Exception('material_to_employee_balance');
			}
		/*
			//this part is not required now as we are not taking cash instead of Gold
			//insert into cash_to_employee_balance
			$sql="insert into employees_cash_balance (
					   emp_id
					  ,op_balance
					  ,inward
					  ,outward
					  ,balance
					) VALUES (
					  ?,0,?,0,?
					) on duplicate key update inward=inward+?,balance=balance+?";
			$result=$this->db->query($sql,array($this->session->userdata('employee_id')
												,$cash
												,$cash
												,$cash
												,$cash
												));
			if($result==FALSE){
				throw new Exception('employees_cash_balance');
			}
			*/
			// customer balance
			//$fine_gold_total=$_GET['fine_gold'];
			
			$sql="insert into customer_balance (
				   cust_id
				  ,received_gold
				) VALUES (
				  ?,?
				) on duplicate key update received_gold=received_gold+?";
			$result=$this->db->query($sql,array($_GET['cust_id'],$_GET['receive_gold'],$_GET['receive_gold']));
			
			if($result==FALSE){
				throw new Exception('customer_balance');
			}
			//update gold receipt master with current_balance
			$sql="update gold_receipt_master set 
				current_gold_balance=(select customer_balance.opening_gold+customer_balance.billed_gold-customer_balance.received_gold from customer_balance where customer_balance.cust_id = gold_receipt_master.cust_id),
				current_lc_balance=(select customer_balance.opening_lc+customer_balance.billed_lc-customer_balance.received_lc from customer_balance where customer_balance.cust_id = gold_receipt_master.cust_id)
				where gold_receipt_id=?";
			$result=$this->db->query($sql,array($gold_receipt_id));
			if($result==FALSE){
				throw new Exception('update gold_receipt_master');
			}
			//adding inventory transaction to table
			$comment='Received from Cust - '.$_GET['cust_id'].' by agent-'.$_GET['agent_id'];
			$sql="insert into inventory_day_book (
				  employee_id
				  ,rm_id
				  ,transaction_type
				  ,rm_value
				  ,reference
				  ,comment
				) VALUES (?,36,1,?,?,?)";
			$result=$this->db->query($sql,array($this->session->userdata('employee_id')
										,$_GET['receive_gold']
										,$gold_receipt_id
										,$comment
									    ));
			if($result==FALSE){
				throw new Exception('inventory_day_book');
			}
			$this->db->query("COMMIT");
			$return_array['success']=1;
			$return_array['receipt_no']=$gold_receipt_id;
		}catch(Exception $e){
			$return_array['error_code']=create_log($this->db->last_query(),'transaction_mode','insert_gold_receipt_from_customer',"log_file.csv");
			$return_array['last_query']=$this->db->last_query();
			$this->db->query("ROLLBACK");
			$return_array['success']=0;
			$return_array['error']='Error : '. $e->getMessage()."\n";
		}
		return $return_array;
	}
	function insert_cash_refund_from_employee($user_id,$emp_id,$refund_amount,$comment){
		$return_array=array();
		try{
			$this->db->query("START TRANSACTION");
			//lock tables
			$current_financial_year=get_financial_year();
			$sql="insert into maxtable (table_name, mainfield, financial_year)values(?,1,?)on duplicate key UPDATE table_id=last_insert_id(table_id), mainfield=mainfield+1";
			$result=$this->db->query($sql,array('cash_transaction_between_employees',$current_financial_year));
			if($result==FALSE){
				throw new Exception('maxtable ');
			}
			//step 2: select mainfield as table name and current financial year
			$sql="select * from maxtable where table_id=last_insert_id()";
			$result=$this->db->query($sql)->row();
			
			$CTE_no=$result->prefix.'/'.$result->mainfield.'/'.$current_financial_year;
			//adding record to lc_receipt_master
			$sql="insert into cash_transaction_between_employees (
				   cash_transaction_id
				  ,payee_id
				  ,payer_id
				  ,cash
				  ,comment
				) VALUES (?,?,?,?,?	)";
			$result=$this->db->query($sql,array($CTE_no,$user_id,$emp_id,$refund_amount,$comment));
			if($result==FALSE){
				throw new Exception('cash_transaction_between_employees');
			}
			//insert into cash_to_employee_balance for user
			$sql="insert into employees_cash_balance (
					   emp_id
					  ,op_balance
					  ,inward
					  ,outward
					  ,balance
					) VALUES (
					  ?,0,?,0,?
					) on duplicate key update inward=inward+?,balance=balance+?";
			$result=$this->db->query($sql,array($user_id
												,$refund_amount
												,$refund_amount
												,$refund_amount
												,$refund_amount
												));
			if($result==FALSE){
				throw new Exception('employees_cash_balance for user');
			}
			
			//insert into cash_to_employee_balance for employee
			$sql="insert into employees_cash_balance (
					   emp_id
					  ,op_balance
					  ,inward
					  ,outward
					  ,balance
					) VALUES (
					  ?,0,0,?,?
					) on duplicate key update outward=outward+?,balance=balance-?";
			$result=$this->db->query($sql,array($emp_id
												,$refund_amount
												,-$refund_amount
												,$refund_amount
												,$refund_amount
												));
			if($result==FALSE){
				throw new Exception('employees_cash_balance');
			}
			$this->db->query("COMMIT");
			$return_array['success']=1;
			$return_array['transaction_id']=$CTE_no;
		}catch(Exception $e){
			$return_array['last_query']=$this->db->last_query();
			$this->db->query("ROLLBACK");
			$return_array['success']=0;
			$return_array['error']='Error : '. $e->getMessage()."\n";
		}
		return $return_array;
	}
	function select_cash_refund_by_cte_no($cte_no){
		$sql="select cash_transaction_between_employees.cash_transaction_id
		      ,cash_transaction_between_employees.tr_date
		      ,cash_transaction_between_employees.cash
		      ,cash_transaction_between_employees.comment
		      ,payee.emp_name as payee_name
		      ,payer.emp_name as payer_name
		from cash_transaction_between_employees
		inner join employees as payee on cash_transaction_between_employees.payee_id=payee.emp_id
		inner join employees as payer on cash_transaction_between_employees.payer_id=payer.emp_id
		where cash_transaction_between_employees.cash_transaction_id=?";
		$result=$this->db->query($sql,array($cte_no));
		if($result->num_rows()>0)
			return $result->row();
		else
			return NULL;
	}
	function select_gold_receipt_record_by_receipt_id($gold_receipt_id){
		$sql="select gold_receipt_master.*
			  ,date_format(`tr_date`,'%d/%m/%Y %H:%i') as receipt_date
			  ,agent_master.agent_name,customer_master.mailing_name
			  ,customer_master.cust_address
			  ,customer_master.cust_phone from gold_receipt_master 
			inner join customer_master ON customer_master.cust_id = gold_receipt_master.cust_id
			inner join agent_master ON agent_master.agent_id = gold_receipt_master.agent_id
			where gold_receipt_id=?";
		$result=$this->db->query($sql,array($gold_receipt_id));
		if($result->num_rows()>0)
			return $result->row();
		else
			return NULL;
	}
}//final
?>