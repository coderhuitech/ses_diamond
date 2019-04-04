<?php
class Product_model extends CI_Model {

	function __construct() {
		parent::__construct();
	
		$this -> load -> helper(array('huiui'));
	}
	function select_product_category(){
		$sql="select * from product_cat";
		$result=$this->db->query($sql);
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function select_product_by_product_code($product_code){
		$sql="select * from product_master  where product_code=?";
		$result=$this->db->query($sql,array($product_code));
		if($result->num_rows()>0){
			return $result->row();
		}else{
			return NULL;
		}
	}
	function update_or_add_product(){
		$return_array=array();
		try{
			$sql="insert into product_master (
				   product_code
				  ,product_description
				  ,product_category
				  ,price_code
				) VALUES (?,?,?,?)
				on duplicate key update product_description=?,product_category=?,price_code=?;";
			$result=$this->db->query($sql,array($_GET['product_code']
												,$_GET['description']
												,$_GET['category']
												,$_GET['price_code']
												,$_GET['description']
												,$_GET['category']
												,$_GET['price_code']
												));
			if($result==FALSE){
				throw new Exception('product_master ');
			}
			create_log("sfsfsfsfsfs");
			$return_array['success']=1;	
			$return_array['row_updated']=mysql_affected_rows();
		}catch(Exception $e){	
			$return_array['error']='Caught exception: '. $e->getMessage();
			$return_array['success']=0;
			$return_array['sql']=$this->db->last_query();
			$result_array['row_updated']=mysql_affected_rows();
		}	
		
		return $return_array;
	}
}
?>