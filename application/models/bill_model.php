<?php
class Bill_model extends CI_Model {

	function __construct() {
		parent::__construct();
	
		$this -> load -> helper(array('huiui'));
	}
	function get_customers_have_bill(){
		$sql="select distinct customer_master.cust_id,customer_master.cust_name from bill_master
			inner join customer_master on bill_master.cust_id = customer_master.cust_id";
		$result=$this->db->query($sql);
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function select_bills_by_customer($term,$cust_id){
		$sql="select * from bill_master
			where cust_id=? and bill_no like '%".$term."%'";
		$result=$this->db->query($sql,array($cust_id));
		
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function get_bills_by_cust_id(){
		$sql="select * from bill_master
		inner join customer_master ON customer_master.cust_id = bill_master.cust_id limit 100";
		$result=$this->db->query($sql);
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function get_bill_master_data($bill_no){
		$sql="select 
				bill_no
			  ,bill_master.order_id
				,customer_master.mailing_name
				,customer_master.cust_address
				,customer_master.cust_phone
				,agent_master.agent_name
        ,dstodate(order_master.DoO) as DoO
        ,bill_master.tr_time as bill_date
				from bill_master 
				inner join customer_master
				on bill_master.cust_id = customer_master.cust_id
				inner join agent_master
				on bill_master.agent_id = agent_master.agent_id
     		inner join order_master on bill_master.order_id=order_master.order_id
		  	where bill_master.bill_no=?";
		$result = $this -> db -> query($sql,array($bill_no));
		if ($result -> num_rows() > 0) {
			return $result -> row();
		} else {
			return NULL;
		}
	}
	function get_bill_print_details($bill_no){
	$sql="SELECT bill_master.bill_no,
       bill_master.bill_date
       ,bill_master.cust_id
       ,bill_master.order_id
       ,bill_master.bill_gold
       ,bill_master.bill_labour_charge
       ,bill_details.job_id
       ,bill_details.model_no
       ,bill_details.price_code
       ,bill_details.ploss
       ,bill_details.gross_wt
       ,bill_details.wastage_percentage
       ,bill_details.wastage
       ,bill_details.price_method
       ,bill_details.gold_quality
       ,bill_details.total_gold
       ,bill_details.fine_gold
       ,bill_details.qty
       ,bill_details.size
       ,bill_details.labour_charge
       ,order_master.DoO
       ,bill_master.agent_id
      from bill_master
      inner join bill_details ON bill_details.bill_no = bill_master.bill_no
      inner join order_master on bill_master.order_id=order_master.order_id
      where bill_details.bill_no=?";
		$result = $this -> db -> query($sql,array($bill_no));
		if ($result -> num_rows() > 0) {
			return $result;
		} else {
			return NULL;
		}
	}
	function get_bill_master_by_bill_no($bill_no){
		$sql="select 
			bill_master.cust_id,
			customer_master.city,
			bill_no,
			bill_master.order_id,
			customer_master.mailing_name,
			customer_master.cust_address,
			customer_master.cust_phone,
			agent_master.agent_name,
      		order_master.tr_time,
			DATE_FORMAT(bill_master.tr_time, '%d-%m-%Y') as bill_date,
			total_lc_inward 
			from bill_master 
			inner join customer_master
			on bill_master.cust_id = customer_master.cust_id
			inner join agent_master
			on bill_master.agent_id = agent_master.agent_id
      		left outer join order_master on bill_master.order_id=order_master.order_id
			where bill_master.bill_no=?";
		$result=$this->db->query($sql,array($bill_no));
		if($result->num_rows()>0){
			return $result->row();
		}else{
			return NULL;
		}
	}
	function get_bill_details_for_bii_by_bill_no($bill_no){
		$sql="SELECT bill_master.bill_no,
	       bill_master.bill_date,
	       bill_master.cust_id,
	       bill_master.order_id,
	       bill_master.bill_gold,
	       bill_master.bill_labour_charge,
	        if(bill_details.job_id=0,tag,bill_details.job_id) as job_id,
	       bill_details.model_no,
	       bill_details.price_code,
	       bill_details.gold_wt,
	       bill_details.gross_wt,
	       bill_details.wastage_percentage,
	       bill_details.wastage,
	       bill_details.price_method,
	       bill_details.gold_quality,
	       bill_details.total_gold,
	       bill_details.fine_gold,
	       bill_details.qty,
	       bill_details.size,
	       bill_details.labour_charge,
	       order_master.DoO,
	       bill_master.agent_id
	      from bill_master
	      inner join bill_details on bill_details.bill_no = bill_master.bill_no
	      left outer join order_master on bill_master.order_id=order_master.order_id
	      where bill_details.bill_no=?";
      	  $result=$this->db->query($sql,array($bill_no));
      	  
      	  if($result->num_rows()>0){
			return $result;
		  }else{
			return NULL;
		  } 
	}
	function select_customer_dues_by_cust_id($cust_id){
		$sql="select ifnull(opening_gold,0)+ifnull(billed_gold,0)-ifnull(received_gold,0) as gold_due, ifnull(opening_lc,0)+ifnull(billed_lc,0)-ifnull(received_lc,0) as lc_due from customer_balance where cust_id=?";
		$result=$this->db->query($sql,array($cust_id));
		if($result->num_rows()>0){
			return $result->row();
		}else{
			return NULL;
		}
	}
	function get_agent_by_cust_id($cust_id){
		$sql="select * from agent_to_customer
				inner join agent_master on agent_to_customer.agent_id=agent_master.agent_id
				where cust_id=?";
		$result=$this->db->query($sql,array($cust_id));
		 if($result->num_rows()>0){
			return $result->row();
		  }else{
			return NULL;
		  } 
	}
}//final
?>