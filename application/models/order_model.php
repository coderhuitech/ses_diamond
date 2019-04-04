<?php
class Order_model extends CI_Model {

	function __construct() {
		parent::__construct();
	
		$this -> load -> helper(array('huiui'));
	}
	function select_gold_id_for_order(){
		$sql="select * from configuration where group_id=(select id from configuration_group where configuration_group.group_name='rm_gold_order')";
		$result=$this->db->query($sql);
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function select_default_gold_id_for_order(){
		$sql="select * from configuration where group_id=(select id from configuration_group where configuration_group.group_name='rm_gold_order_default')";
		$result=$this->db->query($sql);
		if($result->num_rows()>0){
			return $result->row();
		}else{
			return NULL;
		}
	}
	function insert_items_to_temp_order(){
		$sql="insert into temp_order (
			  cust_id
			  ,agent_id
			  ,product_code
			  ,user_key
			  ,qty
			  ,product_size
			  ,rm_id
			  ,price_code
			  ,gold_weight
			  ,price
			  ,p_loss
			  ,product_description
			) select 
			? as cust_id
			,? as agent_id
			,? as product_code
			,? as user_key
			,? as qty
			,? as product_size
			,? as rm_id
			, price_code
			,? as gold_weight
			,price
			,p_loss
			,? as product_description
			from price_master where price_master.price_cat=(select p_cat from customer_master where cust_id=?) and
			price_master.price_code =(select price_code from product_master where product_code=?)";
			$result=$this->db->query($sql,array($_POST['customer_id']
												,$_POST['agent_id']
												,$_POST['model_no']
												,$_POST['user_key']
												,$_POST['qty']
												,$_POST['model_size']
												,$_POST['rm_id']
												,$_POST['appx_gold']
												,$_POST['description']
												,$_POST['customer_id']
												,$_POST['model_no']
												)//end of array list
									 );
			$result_array['affected_rows']=mysql_affected_rows();
			$result_array['sql']=$this->db->last_query();
			$result_array['status']=$this->db->trans_status();
			$result_array['msg']=$this->db->_error_message();
			$sql="select * from temp_order where user_key=?";
			$result=$this->db->query($sql,array($_POST['user_key']));
			if($result->num_rows()>0){
				$result_array['table1']=$result;
			}else{
				$result_array['table1']=NULL;
			}
			return $result_array;
	}
	function delete_from_temp_orders_by_temp_order_id($temp_order_id,$user_key){
		$sql="delete from temp_order where temp_order_id=?";
		$result=$this->db->query($sql,array($temp_order_id));
		$result_array['affected_rows']=mysql_affected_rows();
		$result_array['sql']=$this->db->last_query();
		$result_array['status']=$this->db->trans_status();
		$result_array['msg']=$this->db->_error_message();
		$sql="select * from temp_order where user_key=?";
		$result=$this->db->query($sql,array($user_key));
		if($result->num_rows()>0){
			$result_array['table1']=$result;
		}else{
			$result_array['table1']=NULL;
		}
		return $result_array;
	}
	//save Order
	function save_orders(){
		$sql="select count(*) as no_of_record FROM temp_order where user_key=?";
		$return_array=array();
		$result=$this->db->query($sql,array($_POST['user_key']))->row();
		if ($result->no_of_record<=0){
    		$return_array['err_msg']="Please select your product then press TAB";
			$return_array['success']=0;
			return $return_array;
		} 
		
		try{
		//lock tables
		$sql="lock table order_master write, order_details write, temp_order write, maxtable write";
		$this->db->query($sql);
		$this->db->query("START TRANSACTION");
		$current_financial_year=get_financial_year();
		$sql="insert into maxtable (table_name, mainfield, financial_year)values(?,1,?)on duplicate key UPDATE table_id=last_insert_id(table_id), mainfield=mainfield+1";
		$result=$this->db->query($sql,array('order_master',$current_financial_year));
		if($result==FALSE){
			throw new Exception('maxtable ');
		}
		//step 2: select mainfield as table name and current financial year
		$sql="select * from maxtable where table_id=last_insert_id()";
		$mainfield=$this->db->query($sql)->row()->mainfield;
		if($result==FALSE){
			throw new Exception('mainfield ');
		}
		$prefix=$this->db->query($sql)->row()->prefix;
		$order_id=$_POST['user_id'].'/'.$mainfield.'/'.$current_financial_year;
		$return_array['order_id']=$order_id;
		$return_array['order_serial']=$mainfield;
		
		$sql="insert into order_master (
			   order_id
			  ,order_serial
			  ,cust_id
			  ,agent_id
			  ,DoO
			  ,DoD
			  ,Status_id
			  ,User_ID
			  ,tr_date
			  ,order_date
			  ,delivery_date
			) VALUES (?,?,?,?,?,?,0,?,?,?,?)";
		$result=$this->db->query($sql,array($order_id
									  		,$return_array['order_serial']
											,$_POST['customer_id']
											,$_POST['agent_id']
											,date_to_serial($_POST['order_date'])
											,date_to_serial($_POST['delivery_date'])
											,$_POST['user_id']
											,date_to_serial(get_current_date())
											,to_sql_date($_POST['order_date'])
											,to_sql_date($_POST['delivery_date'])
									  ));
		if($result==FALSE){
			throw new Exception('order_master ');
		}
		$return_array['test']=$this->db->last_query();
		$sql="set @count:=0";
		$result=$this->db->query($sql);
		
		//adding temp_order to order_details
		$sql="insert into order_details (
		   sl_no
		  ,order_id
		  ,product_code
		  ,price_code
		  ,price_method
		  ,price
		  ,p_loss
		  ,prd_size
		  ,gold_wt
		  ,rm_id
		  ,particulars
		  ,qty
		  ,status
		) SELECT 
		   @count:=@count+1,?,product_code,price_code,'Regular',price,p_loss,product_size,gold_weight
		  ,rm_id,product_description,qty,0
		FROM temp_order where user_key=?";
		$result=$this->db->query($sql,array($order_id
											,$_POST['user_key']
										));
		
		if($result==FALSE){
			throw new Exception('order_details ');
		}
		$sql="delete from temp_order where user_key=?";
		$result=$this->db->query($sql,array($_POST['user_key']));
		if($result==FALSE){
			throw new Exception('temp_order ');
		}
		$this->db->query("COMMIT");
		}catch(Exception $e){
			$this->db->query("ROLLBACK");
			$return_array['error']='Caught exception: '. $e->getMessage()."\n";
		}
		$this->db->query("unlock tables");
		if ($this->db->trans_status() === FALSE){
    		$return_array['err_msg']=$this->db->_error_message();
			$return_array['success']=0;
		} else{
			$return_array['success']=1;
		}
		
		return $return_array;
	}
	function select_item_from_temp_order_by_id($temp_order_id){
		$sql="select * from temp_order where temp_order_id=?";
		$result=$this->db->query($sql,array($temp_order_id));
		if($result->num_rows()>0){
			$result_array['result']=$result->row();
			return $result_array;
		}else{
			$result_array['result']=NULL;
			$result_array['sql']=$this->db->last_query();
			return $result_array;
			
		}
	}
	function get_order_master_by_order_id($order_id){
		$sql="select *
        ,date(order_master.order_date) as order_date
        ,date(order_master.delivery_date) as delivery_date  from order_master
				inner join customer_master
				on order_master.cust_id = customer_master.cust_id
				inner join agent_master
				on order_master.agent_id = agent_master.agent_id
				where order_id=?";
		$result=$this->db->query($sql,array($order_id));
		if($result->num_rows()>0){
			return $result->row();
		}else{
			return NULL;
		}
	}
	function get_order_details_by_order_id($order_id){
		$sql="select * from order_details
			inner join table_status
			on order_details.status = table_status.status_ID 
      inner join rm_master on order_details.rm_id=rm_master.rm_id
			where order_id=?";
		$result=$this->db->query($sql,array($order_id));
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function select_order_by_options($bill_no,$order_date_from,$order_date_to){
		$sql="select * from order_master 
		inner join customer_master
		on order_master.cust_id = customer_master.cust_id
		where  date(order_date) between ? and ?";
		$result=$this->db->query($sql,array($order_date_from,$order_date_to));
		//return $this->db->last_query();
		create_log($this->db->last_query(),'order_model','select_order',"log_file.csv");
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function update_order_by_order_no(){
		$sql="update order_details SET
			  product_code = ?
			  ,prd_size = ? 
			  ,gold_wt = ?
			  ,qty = ? 
			WHERE order_no =?";
		$result=$this->db->query($sql,array($_POST['product_code']
											,$_POST['prd_size']
											,$_POST['gold_wt']
											,$_POST['qty']
											,$_POST['order_no']
											));
		return mysql_affected_rows();
	}
	function select_deleteable_orders_by_term($term){
		$sql="select * from order_master
			where order_id like '%".$term."%' and
			order_id in (select order_id from order_details where order_no not in(select order_no from job_master))";
		$result=$this->db->query($sql);
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function select_deleteable_orders(){
		$sql="select order_master.order_id
		      ,customer_master.cust_name
		      ,date_format(order_master.tr_time,'%D-%M-%y %H:%i') as order_date
		      from order_master 
			inner join customer_master on order_master.cust_id = customer_master.cust_id
			where order_id not in(select job_master.order_id from job_master) and Status_id=0";
		$result=$this->db->query($sql);
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function select_order_details_by_order_id_where_status_fresh(){
		$sql="select * from order_details where order_id=? and status=0";
		$result=$this->db->query($sql,array($_GET['order_no']));
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function update_order_master_set_status_cancel(){
		$sql="update order_master set Status_id=4 where order_id=?";
		$result=$this->db->query($sql,array($_GET['order_no']));
	}
	function select_order_by_delivery_date($date_from,$date_upto){
		$sql="SELECT order_master.order_id
        ,order_master.cust_id
        ,customer_master.cust_name
        ,order_master.order_date
        ,order_master.delivery_date
        ,(select COUNT(*) from order_details  where order_details.order_id=order_master.order_id group by order_details.order_id) no_of_orders
        ,(select COUNT(*) from order_details  where order_details.order_id=order_master.order_id and order_details.status=4 group by order_details.order_id) as canceled
        ,(select COUNT(*) from order_details  where order_details.order_id=order_master.order_id and order_details.status=5 group by order_details.order_id) as jobed
		FROM order_master 
		inner join customer_master on order_master.cust_id = customer_master.cust_id
		where order_master.delivery_date between ? and ? and order_master.Status_id<>4";
		$result=$this->db->query($sql,array($date_from,$date_upto));
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function select_order_details_by_order($order_no){
		$sql="select      sl_no 
            ,order_details.order_no
			      ,order_details.order_id
			      ,prd_size
			      ,order_details.gold_wt
			      ,order_details.qty
			      ,status_name
            ,job_master.job_id
            ,bill_details.bill_no
			from order_details
			inner join table_status on order_details.status=table_status.status_ID
			left outer join job_master on order_details.order_no = job_master.order_no
      left outer join bill_details on job_master.job_id = bill_details.job_id
      where order_details.order_id = ?";
		$result=$this->db->query($sql,array($order_no));

		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
}//final
?>