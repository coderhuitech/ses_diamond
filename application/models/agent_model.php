<?php
class Agent_model extends CI_Model {

	function __construct() {
		parent::__construct();
	
		$this -> load -> helper(array('huiui'));
	}
	function select_agents(){
		$sql="select * from agent_master";
		$result=$this->db->query($sql);
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function select_customers_by_agent_id($agent_id){
		$sql="select * from agent_to_customer
			inner join customer_master ON customer_master.cust_id = agent_to_customer.cust_id
			where agent_id=?";
		$result=$this->db->query($sql,array($agent_id));
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
}
?>