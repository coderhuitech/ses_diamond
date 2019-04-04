<?php
class Customer_model extends CI_Model {

	function __construct() {
		parent::__construct();
	
		$this -> load -> helper(array('huiui'));
	}
	function select_customer_balance_by_id($cust_id){
		$sql="select customer_master.cust_id
			      , cust_name
			      , coalesce(opening_gold+billed_gold-received_gold,0) as gold_due
			      , coalesce(opening_lc+billed_lc-received_lc,0) as lc_due
			from customer_master
			left outer join customer_balance on customer_master.cust_id = customer_balance.cust_id
			where customer_master.cust_id=?";
		$result=$this->db->query($sql,array($cust_id));
		if($result->num_rows()>0){
			return $result->row();
		}else{
			return NULL;
		}
	}
	function select_customers($term="",$cust_id=""){
		$sql="select * from customer_master where 1";
		$where=" ";
		if($cust_id!=""){
			$where+=" and cust_id='$cust_id' ";
		}
		if($term!=""){
			$where.="and cust_name like '%$term%' ";
		}
		$sql.=$where;
		$result=$this->db->query($sql);
		
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function select_agent_by_cust_id($cus_id){
		$sql="select * from agent_to_customer
			inner join agent_master ON agent_master.agent_id = agent_to_customer.agent_id
			where agent_to_customer.cust_id=?";
		$result=$this->db->query($sql,array($cus_id));
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function get_customer_by_cust_id($cust_id){
		$sql="select customer_master.cust_id
			,customer_master.cust_name
			,customer_master.mailing_name
			,customer_master.city
			,customer_master.cust_address
			,customer_master.cust_phone
			,customer_master.short_name
			,customer_balance.opening_lc
      ,customer_master.markup_value
      ,customer_master.gold_limit
      ,customer_master.cash_limit
			,customer_balance.opening_gold from customer_master 
			left outer join customer_balance on customer_balance.cust_id = customer_master.cust_id
			where customer_master.cust_id=?";
		$result=$this->db->query($sql,array($cust_id));
		if($result->num_rows()>0){
			return $result->row();
		}else{
			return NULL;
		}
	}
	function add_fresh_customer(){
		$return_array=array();
		try{
			$this->db->query("START TRANSACTION");
			$sql="insert into customer_master (
					   cust_id
					  ,cust_name
					  ,mailing_name
					  ,cust_address
					  ,city
					  ,cust_phone
					  ,p_cat
					  ,gold_limit
					  ,cash_limit
					  ,markup_value
					  ,markuped
					  ,user_id
					  ,order_inforce
					  ,bill_inforce
					  ,short_name
					) VALUES (?,?,?,?,?,?,1,100,2000,0.08,1,'admin',1,1,?)";
			$result=$this->db->query($sql,array($_GET['cust_id']
												,$_GET['cust_name']
												,$_GET['mailing_name']
												,$_GET['address']
												,$_GET['city']
												,$_GET['phone']
												,$_GET['initial']
												));
			if($result==FALSE){
				throw new Exception('customer master');
			}
			$sql="insert into customer_balance (
			   cust_id
			  ,opening_gold
			  ,opening_lc
			  ,billed_gold
			  ,billed_lc
			  ,received_gold
			  ,received_lc
			) VALUES (?,0,0,0,0,0,0)";
			$result=$this->db->query($sql,array($_GET['cust_id']));
			if($result==FALSE){
				throw new Exception('customer_balance');
			}
			$this->db->query("COMMIT");
			$return_array['success']=1;	
			
		}catch(Exception $e){
			$return_array['sql']=$this->db->last_query();
			$return_array['error']=create_log($this->db->last_query(),'customer_model','add_fresh_customer',"log_file.csv");
			$return_array['success']=0;
			
			$this->db->query("ROLLBACK");	
		}	
		return $return_array;
	}
	function update_existing_customer(){
		$return_array=array();
		try{
			$this->db->query("START TRANSACTION");
			$sql="update customer_master set cust_phone=?,cust_address=?,mailing_name=?,city=?,short_name=? 
					where cust_id=?";
			$result=$this->db->query($sql,array($_GET['phone']
										,$_GET['address']
										,$_GET['mailing_name']
										,$_GET['city']
										,$_GET['initial']
										,$_GET['cust_id']));
			if($result==FALSE){
				throw new Exception('customer master');
			}
			$this->db->query("COMMIT");
			$return_array['success']=1;	
			
		}catch(Exception $e){
			$return_array['sql']=$this->db->last_query();
			$return_array['error']=create_log($this->db->last_query(),'customer_model','update_existing_customer',"log_file.csv");
			$return_array['success']=0;
			
			$this->db->query("ROLLBACK");	
		}	
		return $return_array;
	}
	function update_customer(){
		$return_array=array();
		try{
			$sql="update customer_master SET
				  cust_name = ?
				  ,mailing_name = ?
				  ,cust_address = ?
				  ,cust_phone = ?
				  ,short_name=?
				  ,gold_limit = ?
				  ,cash_limit = ?
				  ,markup_value = ?
				WHERE cust_id = ?";
			$result=$this->db->query($sql,array(
												 $_GET['cust_name']
												,$_GET['mailing_name']
												,$_GET['address']
												,$_GET['phone']
												,$_GET['initial']
												,$_GET['gold_limit']
												,$_GET['cash_limit']
												,$_GET['mv']
												,$_GET['cust_id']
												));
			if($result==FALSE){
				throw new Exception('customer_balance');
			}
			
			$return_array['success']=1;	
		}catch(Exception $e){
			$return_array['sql']=$this->db->last_query();
			$return_array['error']=create_log($this->db->last_query(),'customer_model','update_customer',"log_file.csv");
			$return_array['success']=0;
		}	
		return $return_array;										
											
	}
	// change 221214
	function set_agent_to_customer($cust_id,$agent_id){
		$return_array=array();
		try{
			$sql="insert into agent_to_customer (
				   cust_id
				  ,agent_id
				) VALUES (?,?)
				ON DUPLICATE KEY UPDATE    
				cust_id=?, agent_id=?";
			$result=$this->db->query($sql,array($cust_id,$agent_id,$cust_id,$agent_id));
			if($result==FALSE){
				throw new Exception('agent_to_customer');
			}
		}catch(Exception $e){
			$return_array['sql']=$this->db->last_query();
			$return_array['error']=create_log($this->db->last_query(),'customer_model','set_agent_to_customer',"log_file.csv");
			$return_array['success']=0;
		}	
		return $return_array;	
	}
	/*function get_customer_by_id($cust_id){
		$sql="select * from customer_master where cust_id=?";
		$result=$this->db->query($sql,array($cust_id));
		if($result->num_rows()>0){
			return $result->row();
		}else{
			return NULL;
		}
	}*/
}
?>