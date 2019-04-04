<?php
class Report_model extends CI_Model {

	function __construct() {
		parent::__construct();
	
		$this -> load -> helper(array('huiui'));
	}
	function select_customer_balance($page_value=0,$lines=0,$agent_id='AG000'){
		$result=array();
		$limit=" ";
		if($lines>0){
			$limit.=" Limit ";
			$limit.=$page_value;
			$limit.=", ";
			$limit.=$lines;
		}
		if($agent_id=='AG000'){
			$sql="select 
		      customer_master.cust_id
		      , customer_master.city
		      , left(customer_master.cust_name,35) as cust_name
          ,cust_category.category
		      , customer_master.cust_address
		      , customer_master.cust_phone
		      , agent_master.agent_name
		      , agent_master.short_name
		      , agent_master.agent_phone
		      , ifnull(customer_balance.opening_gold,0) as opening_gold
		      , ifnull(customer_balance.opening_lc,0) as opening_lc
		      , ifnull(customer_balance.billed_gold,0) as billed_gold
		      , ifnull(customer_balance.billed_lc,0) as billed_lc
		      , ifnull(customer_balance.received_gold,0) as received_gold
		      , ifnull(customer_balance.received_lc,0) as received_lc
		from customer_master
		left outer join customer_balance on customer_master.cust_id = customer_balance.cust_id
		left outer join agent_to_customer on  customer_master.cust_id=agent_to_customer.cust_id
		left outer join agent_master on agent_to_customer.agent_id=agent_master.agent_id
    left outer join cust_category on customer_master.p_cat=cust_category.ID
		where 1 
		order by customer_master.city";
		$sql.=$limit;
			$result=$this->db->query($sql);
		}else{
			$sql="select 
		      customer_master.cust_id
		      , customer_master.city
		      , left(customer_master.cust_name,35) as cust_name
		      ,cust_category.category
		      , customer_master.cust_address
		      , customer_master.cust_phone
		      , agent_master.agent_name
		      , agent_master.short_name
		      , agent_master.agent_phone
		      , ifnull(customer_balance.opening_gold,0) as opening_gold
		      , ifnull(customer_balance.opening_lc,0) as opening_lc
		      , ifnull(customer_balance.billed_gold,0) as billed_gold
		      , ifnull(customer_balance.billed_lc,0) as billed_lc
		      , ifnull(customer_balance.received_gold,0) as received_gold
		      , ifnull(customer_balance.received_lc,0) as received_lc
			from customer_master
			left outer join customer_balance on customer_master.cust_id = customer_balance.cust_id
			left outer join agent_to_customer on  customer_master.cust_id=agent_to_customer.cust_id
			left outer join agent_master on agent_to_customer.agent_id=agent_master.agent_id
			left outer join cust_category on customer_master.p_cat=cust_category.ID
			where 1 and agent_master.agent_id=?
			order by customer_master.city";
    		$sql.=$limit;
			$result=$this->db->query($sql,array($agent_id));
		}
		if($result->num_rows()>0){
				return $result;
			}else{
				return NULL;
		}
	}
	function select_staff_cash_balance(){
		$sql="select employees.emp_id, employees.emp_name, op_balance+inward-outward as employee_balance from employees_cash_balance
			inner join employees ON employees.emp_id = employees_cash_balance.emp_id
      where employees.emp_id<>28";
		$result=$this->db->query($sql);
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function select_daily_lc_receipt($date_from,$date_to){
		$sql="select lc_receipt_no
			,date_format(lc_receipt_date,'%d/%m/%Y %H:%i') as lc_receipt_date
			,lc_receipt_master.cust_id
			,lc_receipt_master.emp_id
			,lc_receipt_master.agent_id
			,emp_name,cust_name
			,agent_name
			,if(mode=1,'Cash','Cheque') as receipt_mode
			,amount
			from lc_receipt_master 
			inner join employees ON employees.emp_id = lc_receipt_master.emp_id
			inner join customer_master ON customer_master.cust_id = lc_receipt_master.cust_id
			inner join agent_master ON agent_master.agent_id = lc_receipt_master.agent_id
			where date(lc_receipt_date)>=? and date(lc_receipt_date)<=?
			order by lc_receipt_date desc";
		$result=$this->db->query($sql,array($date_from,$date_to));
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function select_daily_inventory_movement($date_from,$date_to){
		$sql="select date_format(timestamp,'%d/%m/%Y %H:%i') as transaction_date
      		,reference,comment,emp_name,rm_name
			,FORMAT(if(inventory_day_book.transaction_type=1,rm_value,0),3) as inflow
			,FORMAT(if(inventory_day_book.transaction_type=-1,rm_value,0),3) as outflow
			from inventory_day_book
			inner join employees ON employees.emp_id = inventory_day_book.employee_id
			inner join rm_master ON rm_master.rm_ID = inventory_day_book.rm_id
			where date(timestamp)>=? and date(timestamp)<=?";
		$result=$this->db->query($sql,array($date_from,$date_to));
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function select_mathakata_by_period($date_from,$date_to){
		$sql="select date_format(`timestamp`,'%d/%m/%Y %H:%i') as date,emp_name,reference as job,rm_value*transaction_type as mathakata from inventory_day_book 
				inner join employees ON employees.emp_id = inventory_day_book.employee_id
				where comment='Mathakata Returned from Job' 
				and date(`timestamp`)>=? and date(`timestamp`)<=?
				order by inventory_day_book.`timestamp`";
		$result=$this->db->query($sql,array($date_from,$date_to));
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function select_nitrick_by_period($date_from,$date_to){
		$sql="select date_format(`timestamp`,'%d/%m/%Y %H:%i') as date,emp_name,reference as job,rm_value*transaction_type as nitrick from inventory_day_book 
				inner join employees ON employees.emp_id = inventory_day_book.employee_id
				where comment='Nitric returned from job Job' 
				and date(`timestamp`)>=? and date(`timestamp`)<=?
				order by inventory_day_book.`timestamp`";
		$result=$this->db->query($sql,array($date_from,$date_to));
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function select_bills_by_period($date_from,$date_to,$bill_type=0){
		
		$sql="select * from (select date_format(bill_master.tr_time,'%D %M %Y %T') as bill_date,bill_details.bill_no,bill_master.cust_id,customer_master.cust_name,if(bill_master.comments='NONE','Order','Ready') as comment
				,sum(qty) as qty
				,sum(fine_gold) as gold
				,sum(labour_charge) as lc
				,sum(ploss) as ploss
				,sum(bill_details.markup_value*qty)  as mv
				from bill_details 
				inner join bill_master ON bill_master.bill_no = bill_details.bill_no
				inner join customer_master ON customer_master.cust_id = bill_master.cust_id
				where date(bill_master.tr_time)>=? and date(bill_master.tr_time)<=?
				group by bill_details.bill_no
				order by bill_master.tr_time desc) as final_table where 1";
		if($bill_type==1){
			$sql.=" and comment='Order' ";
		}
		if($bill_type==2){
			$sql.=" and comment='Ready' ";
		}
		$result=$this->db->query($sql,array($date_from,$date_to));
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function select_jobs_by_period($date_from,$date_to){
		$sql="select date_format(tr_time,'%D-%M-%Y %H:%i') as job_date,job_master.job_id,order_id, product_code, pieces as qty, product_size, expected_gold, tr_time, gold_send, dal_send, pan_send, nitrick_returned, product_wt, gold_returned,(select table_status.status_name from table_status where table_status.status_ID = job_master.status) as status
			  ,employees.emp_id
			  ,employees.emp_name
			  from job_master
			  inner join employees on job_master.emp_id = employees.emp_id
			where date(`tr_time`)>=? and date(`tr_time`)<=?";
		$result=$this->db->query($sql,array($date_from,$date_to));
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function select_orders_by_period($date_from,$date_to){
		$sql="select date_format(order_master.tr_time,'%D-%M-%y %H:%i') as order_date,order_id,cust_name,status_id from order_master
			inner join customer_master on order_master.cust_id = customer_master.cust_id
				where date(`tr_time`)>=? and date(`tr_time`)<=?
				order by order_master.tr_time desc";
		$result=$this->db->query($sql,array($date_from,$date_to));
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function select_gold_receipt_by_period($date_from,$date_to){
		$sql="select customer_master.cust_name
				      ,agent_master.agent_name
				      ,gold_receipt_master.gold_value
				      , gold_receipt_master.gold_rate
				      , gold_receipt_master.cash
				      , gold_receipt_master.cheque_value
				      ,ifnull(ifnull(gold_value,0)+(ifnull(cash,0)/(ifnull(gold_rate,0)/10)),0) as total_gold
				      ,date_format(gold_receipt_master.tr_date,'%D-%M-%y %H:%i') as receipt_date
				      , gold_receipt_master.gold_receipt_id from gold_receipt_master 
				inner join customer_master ON customer_master.cust_id = gold_receipt_master.cust_id
				inner join agent_master ON agent_master.agent_id = gold_receipt_master.agent_id
				where date(`tr_date`)>=? and date(`tr_date`)<=?
				order by gold_receipt_master.tr_date desc";
		$result=$this->db->query($sql,array($date_from,$date_to));
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function select_material_valuation(){
		$sql="select db2.rm_id,rm_name,material,round(material*(rm_master.rm_gold/100),3) as pure from
				(select rm_id,round(sum(closing_balance),3) as material from(select rm_id,emp_id, closing_balance from material_to_employee_balance where emp_id not in(28,72)) as db1 group by rm_id
				) as db2
				inner join rm_master on db2.rm_id = rm_master.rm_ID";
		$result=$this->db->query($sql);
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function select_customer_valuation(){
		$sql="select 'All Customer Dues' as all_customers
			,sum(opening_gold+billed_gold-received_gold) as gold
			,sum(opening_lc+billed_lc-received_lc) as lc
			from customer_balance";
		$result=$this->db->query($sql);
		if($result->num_rows()>0){
			return $result->row();
		}else{
			return NULL;
		}
	}
	function select_cash_n_hand_to_employee(){
		$sql="select sum(balance) as cash from
		(select * from employees_cash_balance where emp_id not in(28,72)) as db1";
		$result=$this->db->query($sql);
		if($result->num_rows()>0){
			return $result->row();
		}else{
			return NULL;
		}
	}
	function select_item_valuation(){
		$sql="select sum(qty) as qty,round(sum(gold)*.92,3) as gold,sum(labour_charge)as lc from item_stock_ready_made where in_stock=1";
		$result=$this->db->query($sql);
		if($result->num_rows()>0){
			return $result->row();
		}else{
			return NULL;
		}
	}
	function select_job_valuation(){
		$sql="select sum(pieces) as qty,sum(pieces*price) as lc, round(sum((gold_send*rm_master.rm_gold/100)+(pan_send*40/100)-(gold_returned*rm_master.rm_gold/100)-(nitrick_returned*.88)),3) as gold from job_master
				inner join rm_master on job_master.rm_id = rm_master.rm_ID
				where job_master.status in(5,6,7,8,51) and in_stock=0";
		$result=$this->db->query($sql);
		if($result->num_rows()>0){
			return $result->row();
		}else{
			return NULL;
		}
	}
	function select_company_info(){
		$sql="select * from company_details";
		$result=$this->db->query($sql);
		if($result->num_rows()>0){
			return $result->row();
		}else{
			return NULL;
		}
	}
	function select_fine_to_gini_gross($date_from,$date_to){
		$sql="select (select rm_name from rm_master where rm_master.rm_id=db1.rm_id) material_name,sum(rm_value*transaction_type) as material_value from
		(select * from inventory_day_book where reference in (select trnasformation_id from material_transformation 
		where date(material_transformation.tr_time)>=? and date(material_transformation.tr_time)<=?)) as db1
		group by rm_id";
		$result=$this->db->query($sql,array($date_from,$date_to));
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function select_individual_customer_report_by_cust_id($cust_id){
		$sql="select tr_date,date_format(tr_date,'%D-%M-%y %H:%i') as tr_date2,tr_type,cust_id,particulars,reference,qty,gold,lc,comment from
				(select 'Opening Balance' as 'particulars',customer_balance.cust_id,'Current Due' as reference,0 as qty ,customer_balance.opening_gold as gold,customer_balance.opening_lc as lc,op_date as tr_date, 1 as 'tr_type','opn' as 'comment' from customer_balance where cust_id=?
				union
				select 'Add: Sale' as 'particulars',cust_id,bill_master.bill_no ,(select sum(qty) from bill_details where bill_no=bill_master.bill_no) as qty,bill_gold,bill_labour_charge,tr_time as tr_date,1 as 'tr_type','bill' as 'comment' from bill_master where cust_id=?
				union 
				select 'Less: Gold Return' as 'particulars',gold_receipt_master.cust_id,gold_receipt_master.gold_receipt_id ,0 as qty ,(gold_receipt_master.gold_value+if(gold_receipt_master.cash>0,(gold_receipt_master.cash/(gold_receipt_master.gold_rate/10)),0)) as gold,0 as 'lc',gold_receipt_master.tr_date as tr_date, -1 as 'tr_type','gold' as 'comment' from gold_receipt_master where cust_id=?
				union 
				select  'Less: Cash Refund' as 'particulars',lc_receipt_master.cust_id,lc_receipt_master.lc_receipt_no,0 as qty,  0 as 'gold',lc_receipt_master.amount,lc_receipt_master.lc_receipt_date as tr_date, -1 as 'tr_type','lc' as 'comment' from lc_receipt_master where cust_id=?
				) as db1 order  by tr_date";
		$result=$this->db->query($sql,array($cust_id,$cust_id,$cust_id,$cust_id));
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function material_withdrawn_by_employee($emp_id,$date_from,$date_to,$transaction_type_id,$material_id=0){
		if($material_id==0){
			$sql="select mt.transaction_id, mt.employee_id, mt.rm_id, mt.inward, mt.outward, mt.job_id, mt.reference,date_format(mt.record_time,'%D-%M-%y %H:%m') as tr_date
			, mt.transaction_type_id,rm_master.rm_name
			from material_transaction as mt
			inner join rm_master ON rm_master.rm_ID = mt.rm_id
			where mt.transaction_type_id=? and mt.employee_id=?
			and date(mt.record_time)>=? and date(mt.record_time)<=?
			";
			$result=$this->db->query($sql,array($transaction_type_id,$emp_id,$date_from,$date_to));
		}else{
			$sql="select mt.transaction_id, mt.employee_id, mt.rm_id, mt.inward, mt.outward, mt.job_id, mt.reference,date_format(mt.record_time,'%D-%M-%y %H:%m') as tr_date
			, mt.transaction_type_id,rm_master.rm_name
			from material_transaction as mt
			inner join rm_master ON rm_master.rm_ID = mt.rm_id
			where mt.transaction_type_id=? and mt.employee_id=?
			and date(mt.record_time)>=? and date(mt.record_time)<=? and mt.rm_id=?
			";
			$result=$this->db->query($sql,array($transaction_type_id,$emp_id,$date_from,$date_to,$material_id));
		}



		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}	
	function aggregate_material_withdrawn_by_employee($emp_id,$date_from,$date_to,$transaction_type_id){
		$sql="select rm_name,sum(inward) as inward from (select mt.transaction_id, mt.employee_id, mt.rm_id, mt.inward, mt.outward, mt.job_id, mt.reference,date_format(mt.record_time,'%D-%M-%y %H:%m') as tr_date
		, mt.transaction_type_id,rm_master.rm_name
		from material_transaction as mt
			inner join rm_master ON rm_master.rm_ID = mt.rm_id
			where mt.transaction_type_id=? and mt.employee_id=? and date(mt.record_time)>=? and date(mt.record_time)<=? ) as table1
      group by rm_name";
		$result=$this->db->query($sql,array($transaction_type_id,$emp_id,$date_from,$date_to));
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function cash_withdrawn_by_emp($emp_id,$date_from,$date_to){
		$sql="select cash_transaction_id,date_format(tr_date,'%D-%M-%y %H:%i') as tr_date, cash from cash_transaction_between_employees
			where payee_id=? and date(tr_date)>=? and date(tr_date)<=?";
		$result=$this->db->query($sql,array($emp_id,$date_from,$date_to));
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function select_admin_job_report($date_from,$date_to){
		$sql="select date_format(tr_time,'%D-%M-%y %H:%i') as tr_date
			      ,job_id,pieces
			      ,p_loss,p_loss* pieces as actual_ploss
			      ,price,pieces*price as actual_price
			      ,gold_send
			      ,rm_master.rm_name
			      ,rm_master.rm_gold
			      ,pan_send,gold_returned
			      ,nitrick_returned
			      ,markup_value*pieces *(rm_master.rm_gold/100) as actual_mv
			      ,gold_send+pan_send-gold_returned-nitrick_returned as gold_used
			      ,gold_send+pan_send-gold_returned-nitrick_returned +(p_loss* pieces)+(markup_value*pieces) as billed_gold
			from job_master
			inner join rm_master on job_master.rm_id = rm_master.rm_ID ";
		$sql.=" where date(tr_time)>=? and date(tr_time)<=?";
		
		$result=$this->db->query($sql,array($date_from,$date_to));
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function select_owner_material_submit($date_from,$date_to){
		$sql="select material_transaction.transaction_id
		       ,date_format(material_transaction.record_time,'%D-%M-%y %H:%i') as tr_date
		       ,material_transaction.rm_id
		       ,material_transaction.inward
		       ,material_transaction.employee_id
		       ,employees.emp_name 
		       ,rm_master.rm_name
		       from material_transaction 
		inner join employees ON employees.emp_id = material_transaction.employee_id
		inner join rm_master ON rm_master.rm_ID = material_transaction.rm_id ";
		
		$sql.=" where comment like '%Transfer from owner%' and inward>0";
		
		$sql.=" and date(material_transaction.record_time)>=? and date(material_transaction.record_time)<=?";
		$sql.=" order by material_transaction.record_time";
		
		$result=$this->db->query($sql,array($date_from,$date_to));
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function select_owner_material_group_submit($date_from,$date_to){
		$sql="select emp_name,rm_name,sum(inward) total_inward from 
			(select material_transaction.transaction_id
					       ,date_format(material_transaction.record_time,'%D-%M-%y %H:%i') as tr_date
					       ,material_transaction.rm_id
					       ,material_transaction.inward
					       ,material_transaction.employee_id
					       ,employees.emp_name 
					       ,rm_master.rm_name
					       from material_transaction 
					inner join employees ON employees.emp_id = material_transaction.employee_id
					inner join rm_master ON rm_master.rm_ID = material_transaction.rm_id 
			    where comment like '%Transfer from owner%' and inward>0
				  and date(material_transaction.record_time)>=? and date(material_transaction.record_time)<=?
					order by material_transaction.record_time) as table1
			    group by emp_name,rm_name";
		$result=$this->db->query($sql,array($date_from,$date_to));
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function select_owner_readymade_item_submit($date_from,$date_to){
		$sql="select
				  item_stock_ready_made.tag
				  ,item_stock_ready_made.model_no
				  ,item_stock_ready_made.model_size
				  ,item_stock_ready_made.qty
				  ,item_stock_ready_made.gold
				  ,item_stock_ready_made.labour_charge
				  ,item_stock_ready_made.gross_weight
				  ,item_stock_ready_made.package_weight
				  ,if(in_stock=1,'Yes','false') as is_in_stock
				  ,ifnull((select short_name from agent_master where agent_id=item_stock_ready_made.agent_id),'NA') as agent
				  ,ifnull((select agent_name from agent_master where agent_id=item_stock_ready_made.agent_id),'NA') as agent_name
				  ,date_format(record_time,'%D-%M-%y %H:%m') as tr_date
				  ,(select emp_name from employees where emp_id=item_stock_ready_made.employee_id) as employee
				  ,item_stock_ready_made.inforce
          		  ,bill_details.bill_no
          		  ,ifnull(bill_details.bill_no,if(in_stock=1,'In Stock','Not in Stock'))as bill_no
				from item_stock_ready_made
        		left outer join bill_details on item_stock_ready_made.tag = bill_details.tag
				where date(item_stock_ready_made.record_time) >=? and item_stock_ready_made.record_time <=?";
		
		$result=$this->db->query($sql,array($date_from,$date_to));
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function insert_business_status($material_in_pure,$staff_lc,$customer_in_pure,$customer_in_lc,$stock_in_pure,$stock_in_lc,$job_in_pure,$job_in_lc,$consolidated_gold,$consolidated_lc){
		$return_array=array();
		try{
			
			$sql="insert into business_status (
				  material_valuation
				  ,cash_in_hand
				  ,customer_gold
				  ,customer_lc
				  ,stock_gold
				  ,stock_lc
				  ,job_gold
				  ,job_lc
				  ,consolidated_gold
				  ,consolidated_lc
				  ,qty
				) VALUES ( 
				  ?
				  ,? 
				  ,?
				  ,?  
				  ,?
				  ,?
				  ,?
				  ,?
				  ,?
				  ,?
				  ,(select sum(pieces) from job_master)
				)";
			$result=$this->db->query($sql,array($material_in_pure,$staff_lc,$customer_in_pure,$customer_in_lc,$stock_in_pure,$stock_in_lc,$job_in_pure,$job_in_lc,$consolidated_gold,$consolidated_lc));
			if($result==FALSE){
					throw new Exception('customer master');
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
	function select_admin_business_status($date_from, $date_to){
		  $result=$this->db->query("set @gold:=0");
		  $result=$this->db->query("set @lc:=0");
		  $result=$this->db->query("set @qty:=0");
		  
			$sql="select date_format(record_time,'%d-%c-%y %T') as tr_date
	      , consolidated_gold
        ,consolidated_gold-@gold as gold_change
        ,@gold:=consolidated_gold as new_prev_gold
        ,consolidated_lc-@lc as lc_change
        ,@lc:=consolidated_lc as new_prev_lc
        ,qty-@qty as qty_change
        ,@qty:=qty as new_prev_qty
	      , consolidated_lc
	      , material_valuation
	      , cash_in_hand
	      , customer_lc
	      , customer_gold
	      , stock_gold
	      , stock_lc
	      , job_gold
	      , job_lc 
	      from business_status
	      where date(record_time)>=? and date(record_time)<=?";
		$result=$this->db->query($sql,array($date_from,$date_to));
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	
	function select_job_report_by_emp_id($emp_id){
		$sql="select job_master.job_id
        , order_id
        , product_code
        , pieces
        , product_size
        , TRUNCATE(p_loss*pieces,3) as ploss
        , date_format(tr_time,'%d-%c-%y %T') as job_date
        , price*pieces as lc
        , gold_send
        , gold_returned
        , dal_send
        , pan_send
        , bronze_send
        , copper_send
        , dal_returned
        , nitrick_returned
        , gold_send-gold_returned+pan_send-nitrick_returned as actual_gold_used
        , COALESCE(bill_details.bill_no,table_status.status_name) as status
        , job_master.markup_value 
        from job_master 
        inner join table_status on job_master.status=table_status.status_ID
        left outer join bill_details on job_master.job_id = bill_details.job_id 
		where emp_id=?";
		$result=$this->db->query($sql,array($emp_id));
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function select_agents(){
		$sql="select * from agent_master where inforce=1 order by short_name";
		$result=$this->db->query($sql);
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}   
	}
	function select_customer_inward_and_outward_record($date_from, $date_to,$agent_id){
		
		$sql="select cust_id
      ,(select cust_name from customer_master where customer_master.cust_id=final_table.cust_id) as customer
      ,(select customer_master.city from customer_master where customer_master.cust_id=final_table.cust_id) as city
      ,qty,gold,received_gold
      ,lc
      ,received_lc from (select cust_id,sum(qty) as qty,sum(outward_gold) as gold
      ,sum(inward_gold) as received_gold
      ,sum(outward_lc) as lc
      ,sum(inward_lc) as received_lc from
			(select cust_id,sum(qty) as qty,sum(bill_gold) as outward_gold,0 as inward_gold,sum(bill_labour_charge) as outward_lc,0 as inward_lc from (select cust_id,(select sum(qty) from bill_details where bill_no=bill_master.bill_no) as qty,bill_gold,bill_labour_charge from bill_master where (date(bill_master.tr_time) between ? and ?)) as bill group by cust_id
			union
			select cust_id,0 as qty,0 as outward_gold,sum(gold_value) as inward_gold,0 as outward_lc,sum(lc) as inward_lc from
			(select cust_id,gold_receipt_master.gold_value,0 as lc from gold_receipt_master where (date(tr_date) between ? and ?) group by cust_id
			union
			select cust_id,0 as gold_value,lc_receipt_master.amount from lc_receipt_master where (date(lc_receipt_date) between ? and ?) group by cust_id) as receipt_table group by cust_id) as inward_outward_table group by cust_id) 
      as final_table where 1 ";
      	if($agent_id!='AG000'){
			$sql.=" and cust_id in(select cust_id from agent_to_customer where agent_to_customer.agent_id=?)";
		}
		$result=$this->db->query($sql,array($date_from,$date_to,$date_from,$date_to,$date_from,$date_to,$agent_id));
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}   	
		
	}
	// version 10.1
	function select_product_details($tag){
		$sql="select * from(select job_master.order_id as order_id
                    ,job_master.job_id
                    ,job_master.product_code as model_no
                    ,job_master.pieces as qty
                    ,job_master.product_size as model_size
                    ,table_status.status_name as status
                    ,ifnull(bill_master.bill_no,'none') as bill_no
                    from job_master
              inner join table_status on job_master.status=table_status.status_ID
			        left outer join bill_details on job_master.job_id = bill_details.job_id
			        left outer join bill_master ON bill_master.bill_no = bill_details.bill_no
            union
        			select 'none' as order_id
                  ,item_stock_ready_made.tag
                  ,item_stock_ready_made.model_no
                  , item_stock_ready_made.qty
                  ,item_stock_ready_made.model_size
                  ,'Readymade' as status
                  ,bill_details.bill_no 
                  from item_stock_ready_made
              left outer join bill_details on item_stock_ready_made.tag = bill_details.tag) as table1
			where table1.job_id=?";
		$result=$this->db->query($sql,array($tag));
		if($result->num_rows()>0){
			return $result->row();
		}else{
			return NULL;
		}   
	}
	function select_agentwise_dues(){
		$sql="select agent_id,agent_name,sum(gold_due) as gold_due,sum(lc_due) as lc_due from(select agent_master.agent_id
	      ,agent_master.agent_name
	      ,customer_dues.cust_id
	      , customer_dues.gold_due
	      , customer_dues.lc_due from(select cust_id
	      ,opening_gold+billed_gold-received_gold as gold_due
	      ,opening_lc+billed_lc-received_lc as lc_due from customer_balance) as customer_dues
	      inner join agent_to_customer on customer_dues.cust_id = agent_to_customer.cust_id
	      inner join agent_master on agent_master.agent_id = agent_to_customer.agent_id) as agent_wise_dues
	      group by agent_id, agent_name";
		  $result=$this->db->query($sql);
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}   
	}
	// end of version 10.1
	/*Update 17/03/2015*/
	function select_customer_by_agent_id($agent_id){
		$sql="select customer_master.cust_id
				  ,order_inforce
			      ,customer_master.cust_name from customer_master
			inner join agent_to_customer on agent_to_customer.cust_id = customer_master.cust_id
			where agent_to_customer.agent_id=?";
		$result=$this->db->query($sql,array($agent_id));
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}   
	}
	function get_gold_receipt_details_by_gold_receipt_id($gold_receipt_id){
		$sql="select
			gold_receipt_master.gold_value
			,gold_receipt_master.tr_date
			,employees.emp_name
			,agent_master.agent_name
			,customer_master.mailing_name
			,customer_master.city
			from gold_receipt_master
			inner join agent_master ON agent_master.agent_id = gold_receipt_master.agent_id
			inner join customer_master ON customer_master.cust_id = gold_receipt_master.cust_id
			inner join employees ON employees.emp_id = gold_receipt_master.emp_id
			where gold_receipt_master.gold_receipt_id=?";
		$result=$this->db->query($sql,array($gold_receipt_id));
		if($result->num_rows()>0){
			return $result->row();
		}else{
			return NULL;
		}
	}
	function get_lc_receipt_details_by_lc_receipt_id($lc_receipt_id){
		$sql="select
			lc_receipt_master.amount
			,lc_receipt_master.lc_receipt_date
			,employees.emp_name
			,agent_master.agent_name
			,customer_master.mailing_name
			,customer_master.city
			from lc_receipt_master
			inner join agent_master ON agent_master.agent_id = lc_receipt_master.agent_id
			inner join customer_master ON customer_master.cust_id = lc_receipt_master.cust_id
			inner join employees ON employees.emp_id = lc_receipt_master.emp_id
			where lc_receipt_master.lc_receipt_no=?";
		$result=$this->db->query($sql,array($lc_receipt_id));
		if($result->num_rows()>0){
			return $result->row();
		}else{
			return NULL;
		}
	}
	//finding customer billed gold before gold receipt
	function select_total_billed_gold_before_gold_receipt_no($gold_receipt_id){
		$sql="select (select DATE_FORMAT(gold_receipt_master.tr_date,'%D %b %Y at %r') from gold_receipt_master where gold_receipt_master.gold_receipt_id=?) as virtual_date
					,round(sum(bill_details.fine_gold),3) as total_billed_gold from bill_master
				inner join bill_details on bill_master.bill_no = bill_details.bill_no
				where tr_time<(select gold_receipt_master.tr_date from gold_receipt_master
							  where gold_receipt_master.gold_receipt_id=?)
					  and cust_id=(select gold_receipt_master.cust_id from gold_receipt_master
							  where gold_receipt_master.gold_receipt_id=?)";
		$result=$this->db->query($sql,array($gold_receipt_id,$gold_receipt_id,$gold_receipt_id));
		if($result->num_rows()>0){
			return $result->row();
		}else{
			return NULL;
		}
	}
	//finding customer billed lc before gold receipt
	function select_total_billed_lc_before_lc_receipt_no($lc_receipt_id){
		$sql="select (select DATE_FORMAT(lc_receipt_date,'%D %b %Y at %r') from lc_receipt_master where lc_receipt_no=?) as virtual_date
        	,sum(bill_details.labour_charge) as total_billed_lc from bill_master
			inner join bill_details on bill_master.bill_no = bill_details.bill_no
			where tr_time<(select lc_receipt_date from lc_receipt_master
								where lc_receipt_no=?)
				  and cust_id=(select cust_id from lc_receipt_master
								where lc_receipt_no=?)";
		$result=$this->db->query($sql,array($lc_receipt_id,$lc_receipt_id,$lc_receipt_id));
		if($result->num_rows()>0){
			return $result->row();
		}else{
			return NULL;
		}
	}
	function select_total_gold_receipt_before_gold_receipt_no($gold_receipt_id){
		$sql="select tr_date,round(sum(gold_value),3 )as total_gold_received from gold_receipt_master where tr_date<(select tr_date from gold_receipt_master
				where gold_receipt_master.gold_receipt_id=?)
				and cust_id=(select cust_id from gold_receipt_master where gold_receipt_master.gold_receipt_id=?)";
		$result=$this->db->query($sql,array($gold_receipt_id,$gold_receipt_id));
		if($result->num_rows()>0){
			return $result->row();
		}else{
			return NULL;
		}
	}

	function select_total_lc_receipt_before_lc_receipt_no($lc_receipt_no){
		$sql="select lc_receipt_date,sum(amount) as total_lc_received
				from lc_receipt_master where lc_receipt_date<(select lc_receipt_date from lc_receipt_master
				where lc_receipt_master.lc_receipt_no=?)
				and cust_id=(select cust_id from lc_receipt_master
				where lc_receipt_master.lc_receipt_no=?)";
		$result=$this->db->query($sql,array($lc_receipt_no,$lc_receipt_no));
		if($result->num_rows()>0){
			return $result->row();
		}else{
			return NULL;
		}
	}
	function customer_lc_opening_balance_by_lc_receipt_no($lc_receipt_no){
		$sql="select opening_lc from customer_balance where cust_id=(select cust_id from lc_receipt_master
				where lc_receipt_master.lc_receipt_no=?)";
		$result=$this->db->query($sql,array($lc_receipt_no));
		if($result->num_rows()>0){
			return $result->row();
		}else{
			return NULL;
		}
	}
	function customer_gold_opening_balance_by_gold_receipt_id($gold_receipt_id){
		$sql="select opening_gold from customer_balance
			where cust_id=(select cust_id from gold_receipt_master where gold_receipt_master.gold_receipt_id=?)";
		$result=$this->db->query($sql,array($gold_receipt_id));
		if($result->num_rows()>0){
			return $result->row();
		}else{
			return NULL;
		}
	}
}
?>