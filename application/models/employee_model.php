<?php
class Employee_model extends CI_Model {
	function __construct() {
		parent::__construct();
		$this -> load -> helper(array('huiui'));
	}
	function select_employees($term,$user_id){
		$sql="select employees.*
				       , ifnull(employees_cash_balance.op_balance,0) as op_balance
				       , ifnull(employees_cash_balance.inward,0) as inward
				       , ifnull(employees_cash_balance.outward,0) as outward
				       , ifnull(employees_cash_balance.balance,0) as balance
				       from employees
				left outer join 
				employees_cash_balance on employees.emp_id = employees_cash_balance.emp_id 
		      where emp_name like '%$term%' and employees.emp_id not in(?)";
		$result=$this->db->query($sql,array($user_id));
		return $result;
	}
	function get_employees_by_depaertment($deparment_id=15){
		$sql="select * from employees where department_id=? and inforce=1 order by emp_name";
		$result=$this->db->query($sql,array($deparment_id));
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
}//final
?>