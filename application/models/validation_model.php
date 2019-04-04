
<?php
class Validation_model extends CI_Model {
	function __construct() {
		parent::__construct();
	
		$this -> load -> helper(array('huiui'));
	}
	function get_no_of_agents_by_id($agent_id){
		$sql="select count(*) no_of_agents from agents where agent_id=?";
		$result=$this->db->query($sql,array($agent_id));
		return $result->row();
	}
	function get_no_of_customer_by_id($customer_id){
		$sql="select count(*) no_of_customers from customers where cuscust_id=?";
		$result=$this->db->query($sql,array($customer_id));
		return $result->row();
	}
	
}//end of model
?>