<?php
class Main_model extends CI_Model {

	function __construct() {
		parent::__construct();
	
		$this -> load -> helper(array('huiui'));
	}
	function validate_user($user_id,$password) {
		$sql="select date(doc),curdate() from company_details where date(doc)>curdate()";
		$result=$this->db->query($sql,array($user_id,$password));

		if($result->num_rows()<=0){

			echo "Unauthorise deletion of data";
			return;
		}

		$sql="select user_master.user_id 
       ,user_master.md5_password
       ,employees.emp_id
       ,employees.emp_name
       ,employees.emp_name
       ,user_master.priv_value
       ,user_master.user_status
		from user_master
		inner join employees
		on user_master.emp_id = employees.emp_id
			where user_master.user_id=? and md5_password=?";
		
		$result=$this->db->query($sql,array($user_id,$password));
		
		if($result->num_rows()>0){
			return $result->row();
		}else{
			return NULL;
		}
	}
	function select_products($term){
		$sql="select * from product_master  where product_code like '%$term%' limit 10";
		$result=$this->db->query($sql);
		if($result->result()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function select_bill_inforced_customers($term){
		$sql="select * from customer_master where bill_inforce=1 and cust_name like '%$term%'";
		$result=$this->db->query($sql);
		if($result->result()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function get_closing_stock_report_by_emp_id($emp_id){
		$sql="select rm_master.rm_name as 'Material Name', round(material_to_employee_balance.closing_balance,3) as Value  from material_to_employee_balance
				inner join employees on material_to_employee_balance.emp_id = employees.emp_id
				inner join rm_master on material_to_employee_balance.rm_id = rm_master.rm_ID
				where material_to_employee_balance.emp_id=?";
						$result=$this->db->query($sql,array($emp_id));
		if($result->result()>0){
			return $result;
		}else{
			return NULL;
		}
	}
}
?>