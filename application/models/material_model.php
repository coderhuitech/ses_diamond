<?php
class Material_model extends CI_Model {

	function __construct() {
		parent::__construct();
	
		$this -> load -> helper(array('huiui'));
	}
	function get_employees_inforce($employee_id){
		$result=$this->db->query("select emp_id,emp_name from employees where inforce and emp_id not in(?)",array($employee_id));
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function get_rm_category(){
		$sql="select * from rm_category where rm_cat_id in (select rm_master.rm_cat_id from rm_master)";
		$result=$this->db->query($sql);
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function get_materials_by_category($rm_category_id){
		$sql="select rm_id,rm_name from rm_master where rm_cat_id=?";
		$result=$this->db->query($sql,array($rm_category_id));
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function add_material_from_owner(){
		$return_array=array();
		try{
		
		//lock tables
		$sql="lock table maxtable write, material_transaction write, material_to_employee_balance write, material_transaction_map write";
		$this->db->query($sql);
		$this->db->query("START TRANSACTION");
		// creating material_transaction_map_id
		$current_financial_year=get_financial_year();
		$sql="insert into maxtable (table_name, mainfield, financial_year)values(?,1,?)on duplicate key UPDATE table_id=last_insert_id(table_id), mainfield=mainfield+1";
		$result=$this->db->query($sql,array('material_transaction_map',$current_financial_year));
		if($result==FALSE){
				throw new Exception('maxtable ');
		}
		//step 2: select mainfield as table name and current financial year
		$sql="select * from maxtable where table_id=last_insert_id()";
		$mainfield=$this->db->query($sql)->row()->mainfield;
		$prefix=$this->db->query($sql)->row()->prefix;
		$material_transaction_map_id=$prefix.'/'.$mainfield.'/'.$current_financial_year;
		//end of material_transaction_map
		if($result==FALSE){
				throw new Exception('maxtable ');
		}
		
		$sql="insert into maxtable (table_name, mainfield, financial_year)values(?,1,?)on duplicate key UPDATE table_id=last_insert_id(table_id), mainfield=mainfield+1";
		$result=$this->db->query($sql,array('material_transaction',$current_financial_year));
		if($result==FALSE){
				throw new Exception('maxtable ');
		}
		//step 2: select mainfield as table name and current financial year
		$sql="select * from maxtable where table_id=last_insert_id()";
		$mainfield=$this->db->query($sql)->row()->mainfield;
		$prefix=$this->db->query($sql)->row()->prefix;
		$transaction_no=$prefix.'/'.$mainfield.'/'.$current_financial_year;
		$return_array['transaction_no']=$transaction_no;
		//adding record to material_transaction for receiver
		$sql="insert into material_transaction (
			   transaction_id
			  ,employee_id
			  ,rm_id
			  ,inward
			  ,outward
			  ,comment
			  ,transaction_type_id
			) VALUES (?,?,?,?,0,?,1)";
		$result=$this->db->query($sql,array($transaction_no
											,$_GET['employee_id']
											,$_GET['material_id']
											,$_GET['material_value']
											,$_GET['comment']
											));
		if($result==FALSE){
				throw new Exception('material_transaction ');
		}
		//adding transaction to material_balance for receiver
		$sql="insert into material_to_employee_balance (
			  emp_id
			  ,rm_id
			  ,inward
			  ,outward
			  ,opening_balance
			  ,closing_balance
			) VALUES (?,?,?,0,0,?) on duplicate key update 
			inward=inward+?
			, closing_balance=closing_balance+?";
		$result=$this->db->query($sql,array($_GET['employee_id']
											,$_GET['material_id']
											,$_GET['material_value']
											,$_GET['material_value']
											,$_GET['material_value']
											,$_GET['material_value']
											));
		if($result==FALSE){
				throw new Exception('material_to_employee_balance ');
		}
		
		$sql="insert into maxtable (table_name, mainfield, financial_year)values(?,1,?)on duplicate key UPDATE table_id=last_insert_id(table_id), mainfield=mainfield+1";
		$result=$this->db->query($sql,array('material_transaction',$current_financial_year));
		$sql="select * from maxtable where table_id=last_insert_id()";
		$mainfield=$this->db->query($sql)->row()->mainfield;
		$prefix=$this->db->query($sql)->row()->prefix;
		$transaction_no2=$prefix.'/'.$mainfield.'/'.$current_financial_year;
		//adding record to material_transaction for giver
		$sql="insert into material_transaction (
			   transaction_id
			  ,employee_id
			  ,rm_id
			  ,inward
			  ,outward
			  ,comment
			  ,transaction_type_id
			) VALUES (?,?,?,0,?,?,1)";
		$result=$this->db->query($sql,array($transaction_no2
											,$this->session->userdata('employee_id')
											,$_GET['material_id']
											,$_GET['material_value']
											,$_GET['comment']
											));
		
		if($result==FALSE){
				throw new Exception('material_transaction ');
		}
		$sql="insert into material_to_employee_balance (
			  emp_id
			  ,rm_id
			  ,inward
			  ,outward
			  ,opening_balance
			  ,closing_balance
			) VALUES (?,?,0,?,0,?) on duplicate key update 
			outward=outward+?
			, closing_balance=closing_balance-?";
		$result=$this->db->query($sql,array($this->session->userdata('employee_id')
											,$_GET['material_id']
											,-$_GET['material_value']
											,$_GET['material_value']
											,$_GET['material_value']
											,$_GET['material_value']
											));
		
		if($result==FALSE){
				throw new Exception('material_to_employee_balance ');
		}
		//adding to map table
		$sql="insert into material_transaction_map (
			   map_id
			  ,transaction_id
			  ,emp_id
			) VALUES (?,?,?)";
		$result=$this->db->query($sql,array($material_transaction_map_id
											,$transaction_no
											,$this->session->userdata('employee_id')
											));
		if($result==FALSE){
				throw new Exception('material_transaction_map ');
		}
		$sql="insert into material_transaction_map (
			   map_id
			  ,transaction_id
			  ,emp_id
			) VALUES (?,?,?)";
		$result=$this->db->query($sql,array($material_transaction_map_id
											,$transaction_no2
											,$this->session->userdata('employee_id')
											));
		if($result==FALSE){
				throw new Exception('material_transaction_map ');
		}
		// adding record to inventory_day_book
		$sql="insert into inventory_day_book (
			  employee_id
			  ,rm_id
			  ,transaction_type
			  ,rm_value
			  ,reference
			  ,comment
			) VALUES (?,?,1,?,?,'Received from owner')";
		$result=$this->db->query($sql,array($_GET['employee_id']
											,$_GET['material_id']
											,$_GET['material_value']
											,$material_transaction_map_id
											));
		if($result==FALSE){
				throw new Exception('inventory_day_book ');
		}
		//end of adding to map table
		$sql="select * from material_to_employee_balance where emp_id=? and rm_id=?";
		$material_balance=$this->db->query($sql,array($_GET['employee_id'],$_GET['material_id']))->row();
		if($result==FALSE){
				throw new Exception('material_to_employee_balance ');
		}
		$return_array['closing_balance']=$material_balance->closing_balance;
		$this->db->query("COMMIT");
		$return_array['success']=1;
		}catch(Exception $e){
			$return_array['error']=create_log($this->db->last_query(),'material_model','add_material_from_owner');
			$this->db->query("ROLLBACK");
			$return_array['success']=0;
			$return_array['error']='Caught exception: '. $e->getMessage()."\n";
		}
		$result=$this->db->query('unlock tables');
		return $return_array;
	}
	
	function rm_closing_balance_pivot_table(){
		$return_array=array();
		$sql="select distinct material_to_employee_balance.rm_id,rm_master.rm_name FROM material_to_employee_balance
				inner join rm_master on material_to_employee_balance.rm_id = rm_master.rm_ID";
		$result=$this->db->query($sql);
		
		
		
		$sql="select employees.emp_name ";
			
		foreach($result->result() as $row){
			$sql.=",case when rm_id=".$row->rm_id." then round(balance,3) else 0 end as '".$row->rm_name."'";
		}
		
		$sql.="from
				(select emp_id,rm_id
				,sum(closing_balance) as balance from material_to_employee_balance
				group by material_to_employee_balance.emp_id,material_to_employee_balance.rm_id) as table1
				inner join employees on table1.emp_id = employees.emp_id
				where designation_id not  in (1) and employees.emp_id<>72";
				
		
		$sql2="select ifnull(emp_name,'Total') as Karigar ";
		foreach($result->result() as $row){
			$sql2.=",sum(`".$row->rm_name."`) as `".$row->rm_name."`";
		}
		$sql2.=" from(".$sql;
		$sql2.=") as table2 ";
		$sql2.=" group by emp_name with rollup";
		$result=$this->db->query($sql2);
		$return_array['sql']=$this->db->last_query();
		$return_array['result']=$result;
		return $return_array;
	}
	function get_employee_material_balance_by_id($employee_id){
		$sql="select employees.emp_name,rm_master.rm_name as Material,closing_balance as Stock from material_to_employee_balance
			inner join rm_master on material_to_employee_balance.rm_id = rm_master.rm_ID
			inner join employees on material_to_employee_balance.emp_id = employees.emp_id
			where material_to_employee_balance.emp_id=?;";
		$result=$this->db->query($sql,array($employee_id));
		if($result->num_rows()>0){
			return $result;
		}else{
			return NULL;
		}
	}
	function transfer_material_between_employees(){
		$return_array=array();
		try{
		//lock tables
		$sql="lock table maxtable write, material_transaction write, material_to_employee_balance write, material_transaction_map write";
		$this->db->query($sql);
		$this->db->query("START TRANSACTION");
		// creating material_transaction_map_id
		$current_financial_year=get_financial_year();
		$sql="insert into maxtable (table_name, mainfield, financial_year)values(?,1,?)on duplicate key UPDATE table_id=last_insert_id(table_id), mainfield=mainfield+1";
		$result=$this->db->query($sql,array('material_transaction_map',$current_financial_year));
		if($result==FALSE){
			throw new Exception('maxtable ');
		}
		$sql="select * from maxtable where table_id=last_insert_id()";
		$mainfield=$this->db->query($sql)->row()->mainfield;
		$prefix=$this->db->query($sql)->row()->prefix;
		$material_transaction_map_id=$prefix.'/'.$mainfield.'/'.$current_financial_year;
		//end of mapping
		$sql="insert into maxtable (table_name, mainfield, financial_year)values(?,1,?)on duplicate key UPDATE table_id=last_insert_id(table_id), mainfield=mainfield+1";
		$result=$this->db->query($sql,array('material_transaction',$current_financial_year));
		if($result==FALSE){
			throw new Exception('maxtable ');
		}
		//step 2: select mainfield as table name and current financial year
		$sql="select * from maxtable where table_id=last_insert_id()";
		$mainfield=$this->db->query($sql)->row()->mainfield;
		$prefix=$this->db->query($sql)->row()->prefix;
		$transaction_no=$prefix.'/'.$mainfield.'/'.$current_financial_year;
		$return_array['transaction_no']=$transaction_no;
		$reference_no=$transaction_no;
		//adding record to material_transaction for receiver
		$sql="insert into material_transaction (
			   transaction_id
			  ,employee_id
			  ,rm_id
			  ,inward
			  ,outward
			  ,comment
			  ,transaction_type_id
			  ,reference
			) VALUES (?,?,?,?,0,?,2,?)";
		$result=$this->db->query($sql,array($transaction_no
											,$this->session->userdata('employee_id')
											,$_GET['material_id']
											,$_GET['material_value']
											,$_GET['comment']
											,$reference_no
											));
		if($result==FALSE){
			throw new Exception('material_transaction ');
		}
		//adding transaction to material_balance for receiver
		$sql="insert into material_to_employee_balance (
			  emp_id
			  ,rm_id
			  ,inward
			  ,outward
			  ,opening_balance
			  ,closing_balance
			) VALUES (?,?,?,0,0,?) on duplicate key update 
			inward=inward+?
			, closing_balance=closing_balance+?";
		$result=$this->db->query($sql,array($this->session->userdata('employee_id')
											,$_GET['material_id']
											,$_GET['material_value']
											,$_GET['material_value']
											,$_GET['material_value']
											,$_GET['material_value']
											));
		if($result==FALSE){
			throw new Exception('material_to_employee_balance ');
		}
		
		//adding to map table
		$sql="insert into material_transaction_map (
			   map_id
			  ,transaction_id
			  ,emp_id
			) VALUES (?,?,?)";
		$result=$this->db->query($sql,array($material_transaction_map_id
											,$transaction_no
											,$this->session->userdata('employee_id')
											));
		if($result==FALSE){
			throw new Exception('material_transaction_map ');
		}
		// adding record to inventory_day_book for receiver
		$sql="insert into inventory_day_book (
			  employee_id
			  ,rm_id
			  ,transaction_type
			  ,rm_value
			  ,reference
			  ,comment
			) VALUES (?,?,1,?,?,'Returned from other employee')";
		$result=$this->db->query($sql,array($this->session->userdata('employee_id')
											,$_GET['material_id']
											,$_GET['material_value']
											,$material_transaction_map_id
											));
		if($result==FALSE){
				throw new Exception('inventory_day_book ');
		}
		//end of adding to map table
		
		//generating new transaction no
		$sql="insert into maxtable (table_name, mainfield, financial_year)values(?,1,?)on duplicate key UPDATE table_id=last_insert_id(table_id), mainfield=mainfield+1";
		$result=$this->db->query($sql,array('material_transaction',$current_financial_year));
		if($result==FALSE){
			throw new Exception('maxtable ');
		}
		//step 2: select mainfield as table name and current financial year
		$sql="select * from maxtable where table_id=last_insert_id()";
		$mainfield=$this->db->query($sql)->row()->mainfield;
		$prefix=$this->db->query($sql)->row()->prefix;
		$transaction_no=$prefix.'/'.$mainfield.'/'.$current_financial_year;
		$return_array['transaction_no']=$transaction_no;
		
		
		//adding record to material_transaction for sender
		$sql="insert into material_transaction (
			   transaction_id
			  ,employee_id
			  ,rm_id
			  ,inward
			  ,outward
			  ,comment
			  ,transaction_type_id
			  ,reference
			) VALUES (?,?,?,0,?,?,2,?)";
		$result=$this->db->query($sql,array($transaction_no
											,$_GET['employee_id']
											,$_GET['material_id']
											,$_GET['material_value']
											,$_GET['comment']
											,$reference_no
											));
		if($result==FALSE){
			throw new Exception('material_transaction ');
		}
		
		
		//adding transaction to material_balance for receiver
		$sql="insert into material_to_employee_balance (
			  emp_id
			  ,rm_id
			  ,inward
			  ,outward
			  ,opening_balance
			  ,closing_balance
			) VALUES (?,?,0,?,0,?) on duplicate key update 
			inward=inward+?
			, closing_balance=closing_balance-?";
		$result=$this->db->query($sql,array($_GET['employee_id']
											,$_GET['material_id']
											,$_GET['material_value']
											,$_GET['material_value']*-1
											,$_GET['material_value']
											,$_GET['material_value']
											));
		if($result==FALSE){
			throw new Exception('material_to_employee_balance ');
		}
			
		//adding to map table
		$sql="insert into material_transaction_map (
			   map_id
			  ,transaction_id
			  ,emp_id
			) VALUES (?,?,?)";
		$result=$this->db->query($sql,array($material_transaction_map_id
											,$transaction_no
											,$this->session->userdata('employee_id')
											));
		if($result==FALSE){
			throw new Exception('material_transaction_map ');
		}
		
		//end of adding to map table
		// adding record to inventory_day_book for receiver
		$sql="insert into inventory_day_book (
			  employee_id
			  ,rm_id
			  ,transaction_type
			  ,rm_value
			  ,reference
			  ,comment
			) VALUES (?,?,-1,?,?,'Returned to other employee')";
		$result=$this->db->query($sql,array($_GET['employee_id']
											,$_GET['material_id']
											,$_GET['material_value']
											,$material_transaction_map_id
											));
		if($result==FALSE){
				throw new Exception('inventory_day_book ');
		}
		
		
		$sql="select * from material_to_employee_balance where emp_id=? and rm_id=?";
		$result=$this->db->query($sql,array($_GET['employee_id'],$_GET['material_id']))->row();
		
		if($result==FALSE){
			throw new Exception('material_to_employee_balance ');
		}
		$return_array['closing_balance']=$result->closing_balance;
		$return_array['error']='no Error';
		$this->db->query("COMMIT");
		$return_array['success']=1;
		
		}catch(Exception $e){
			$return_array['error']=create_log($this->db->last_query(),'Material_model','transfer_material_between_employees',"log_file.csv");
			$this->db->query("ROLLBACK");
			$return_array['success']=0;
			//$return_array['error']='Caught exception: '. $e->getMessage()."\n";
		}
		$this->db->query("unlock tables");
		return $return_array;
	}
	function adjust_material_loss(){
		$return_array=array();
		try{
			//lock tables
			$sql="lock table material_to_employee_balance write, inventory_day_book write";
			$this->db->query($sql);
			$this->db->query("START TRANSACTION");
			//adding inventory transaction to table
			$sql="insert into inventory_day_book (
			  employee_id
			  ,rm_id
			  ,transaction_type
			  ,rm_value
			  ,reference
			  ,comment
			) VALUES (?,?,-1,?,'nil','Material Loss Adjustment')";
			$result=$this->db->query($sql,array(
												$_GET['employee_id']
												,$_GET['material_id']
												,$_GET['material_value']
								    ));
			if($result==FALSE){
				throw new Exception('inventory_day_book ');
			}
			//adding record to material_to_employee_balance
			$sql="insert into material_to_employee_balance (
			  emp_id
			  ,rm_id
			  ,opening_balance
			  ,outward
			  ,closing_balance
			) VALUES (?,?,0,?,?) on duplicate key update 
			outward=outward+?
			, closing_balance=closing_balance-?";
			$result=$this->db->query($sql,array($_GET['employee_id']
											,$_GET['material_id']
											,$_GET['material_value']
											,$_GET['material_value']
											,$_GET['material_value']
											,$_GET['material_value']
											));
			if($result==FALSE){
				throw new Exception('material_to_employee_balance');
			}
			$this->db->query("COMMIT");
			$return_array['success']=1;
		}catch(Exception $e){
			$this->db->query("ROLLBACK");
			$return_array['success']=0;
			$return_array['error']='Caught exception: '. $e->getMessage()."\n";
		}
		$this->db->query("unlock tables");
		return $return_array;
	}
	function get_material_by_id($material_id){
		$sql="select * from rm_master where rm_ID=?";
		$result=$this->db->query($sql,array($material_id));
		if($result->num_rows()>0){
			return $result->row();
		}else{
			return null;
		}
	}
	function insert_material_transformation_for_92($fine_gold,$copper,$gini,$comment){
		try{
			$this->db->query("START TRANSACTION");
			// creating material_transformation
			$current_financial_year=get_financial_year();
			$sql="insert into maxtable (table_name, mainfield, financial_year,prefix)values(?,1,?,'MT')
					on duplicate key UPDATE table_id=last_insert_id(table_id), mainfield=mainfield+1";
			$result=$this->db->query($sql,array('material_transformation',$current_financial_year));
			if($result==FALSE){
				throw new Exception('maxtable ');
			}
			//step 2: select mainfield as table name and current financial year
			$sql="select * from maxtable where table_id=last_insert_id()";
			$result=$this->db->query($sql)->row();
			$transaction_no=$result->prefix.'/'.$result->mainfield.'/'.$current_financial_year;
			$return_array['transaction_no']=$transaction_no;
			
			//adding to material_transformation
			$sql="insert into material_transformation (
				   trnasformation_id
				  ,particulars
				) VALUES (?,?)";
			$result=$this->db->query($sql,array($transaction_no,$comment));
			if($result==FALSE){
				throw new Exception('material_transformation');
			}
			$sql="insert into inventory_day_book (
			  employee_id
			  ,rm_id
			  ,transaction_type
			  ,rm_value
			  ,reference
			  ,comment
			) VALUES (?,36,-1,?,?,'Material Conversion')";
			$result=$this->db->query($sql,array($this->session->userdata('employee_id')
												,$fine_gold
												,$transaction_no
												));
			if($result==FALSE){
				throw new Exception('inventory_day_book Fine Gold');
			}
			$sql="insert into inventory_day_book (
			  employee_id
			  ,rm_id
			  ,transaction_type
			  ,rm_value
			  ,reference
			  ,comment
			) VALUES (?,37,-1,?,?,'Material Conversion')";
			$result=$this->db->query($sql,array($this->session->userdata('employee_id')
												,$copper
												,$transaction_no
												));
			if($result==FALSE){
				throw new Exception('inventory_day_book Copper');
			}
			$sql="insert into inventory_day_book (
			  employee_id
			  ,rm_id
			  ,transaction_type
			  ,rm_value
			  ,reference
			  ,comment
			) VALUES (?,48,1,?,?,'Material Conversion')";
			$result=$this->db->query($sql,array($this->session->userdata('employee_id')
												,$gini
												,$transaction_no
												));
			if($result==FALSE){
				throw new Exception('inventory_day_book 92 Gold');
			}
			$sql="insert into material_to_employee_balance (
				  emp_id
				  ,rm_id
				  ,inward
				  ,outward
				  ,opening_balance
				  ,closing_balance
				) VALUES (?,36,0,?,0,?) on duplicate key update 
				outward=outward+?
				, closing_balance=closing_balance-?";
			$result=$this->db->query($sql,array($this->session->userdata('employee_id')
												,$fine_gold
												,-$fine_gold
												,$fine_gold
												,$fine_gold
												));
			
			if($result==FALSE){
					throw new Exception('material_to_employee_balance fine gold ');
			}
			$sql="insert into material_to_employee_balance (
				  emp_id
				  ,rm_id
				  ,inward
				  ,outward
				  ,opening_balance
				  ,closing_balance
				) VALUES (?,37,0,?,0,?) on duplicate key update 
				outward=outward+?
				, closing_balance=closing_balance-?";
			$result=$this->db->query($sql,array($this->session->userdata('employee_id')
												,$copper
												,-$copper
												,$copper
												,$copper
												));
			
			if($result==FALSE){
					throw new Exception('material_to_employee_balance copper ');
			}
			$sql="insert into material_to_employee_balance (
				  emp_id
				  ,rm_id
				  ,inward
				  ,outward
				  ,opening_balance
				  ,closing_balance
				) VALUES (?,48,?,0,0,?) on duplicate key update 
				inward=inward+?
				, closing_balance=closing_balance+?";
			$result=$this->db->query($sql,array($this->session->userdata('employee_id')
												,$gini
												,$gini
												,$gini
												,$gini
												));
			
			if($result==FALSE){
					throw new Exception('material_to_employee_balance Gini ');
			}
			$this->db->query("COMMIT");
			$return_array['success']=1;
		}catch(Exception $e){
			$return_array['error']=create_log($this->db->last_query(),'Material_model','insert_material_transformation',"log_file.csv");
			$this->db->query("ROLLBACK");
			$return_array['success']=0;
		}
		return $return_array;
	}
	function insert_material_transformation_for_90($fine_gold,$copper,$gini,$comment){
		try{
			$this->db->query("START TRANSACTION");
			// creating material_transformation
			$current_financial_year=get_financial_year();
			$sql="insert into maxtable (table_name, mainfield, financial_year,prefix)values(?,1,?,'MT')
					on duplicate key UPDATE table_id=last_insert_id(table_id), mainfield=mainfield+1";
			$result=$this->db->query($sql,array('material_transformation',$current_financial_year));
			if($result==FALSE){
				throw new Exception('maxtable ');
			}
			//step 2: select mainfield as table name and current financial year
			$sql="select * from maxtable where table_id=last_insert_id()";
			$result=$this->db->query($sql)->row();
			$transaction_no=$result->prefix.'/'.$result->mainfield.'/'.$current_financial_year;
			$return_array['transaction_no']=$transaction_no;
			
			//adding to material_transformation
			$sql="insert into material_transformation (
				   trnasformation_id
				  ,particulars
				) VALUES (?,?)";
			$result=$this->db->query($sql,array($transaction_no,$comment));
			if($result==FALSE){
				throw new Exception('material_transformation');
			}
			$sql="insert into inventory_day_book (
			  employee_id
			  ,rm_id
			  ,transaction_type
			  ,rm_value
			  ,reference
			  ,comment
			) VALUES (?,36,-1,?,?,'Material Conversion')";
			$result=$this->db->query($sql,array($this->session->userdata('employee_id')
												,$fine_gold
												,$transaction_no
												));
			if($result==FALSE){
				throw new Exception('inventory_day_book Fine Gold');
			}
			$sql="insert into inventory_day_book (
			  employee_id
			  ,rm_id
			  ,transaction_type
			  ,rm_value
			  ,reference
			  ,comment
			) VALUES (?,37,-1,?,?,'Material Conversion')";
			$result=$this->db->query($sql,array($this->session->userdata('employee_id')
												,$copper
												,$transaction_no
												));
			if($result==FALSE){
				throw new Exception('inventory_day_book Copper');
			}
			$sql="insert into inventory_day_book (
			  employee_id
			  ,rm_id
			  ,transaction_type
			  ,rm_value
			  ,reference
			  ,comment
			) VALUES (?,42,1,?,?,'Material Conversion')";
			$result=$this->db->query($sql,array($this->session->userdata('employee_id')
												,$gini
												,$transaction_no
												));
			if($result==FALSE){
				throw new Exception('inventory_day_book 92 Gold');
			}
			$sql="insert into material_to_employee_balance (
				  emp_id
				  ,rm_id
				  ,inward
				  ,outward
				  ,opening_balance
				  ,closing_balance
				) VALUES (?,36,0,?,0,?) on duplicate key update 
				outward=outward+?
				, closing_balance=closing_balance-?";
			$result=$this->db->query($sql,array($this->session->userdata('employee_id')
												,$fine_gold
												,-$fine_gold
												,$fine_gold
												,$fine_gold
												));
			
			if($result==FALSE){
					throw new Exception('material_to_employee_balance fine gold ');
			}
			$sql="insert into material_to_employee_balance (
				  emp_id
				  ,rm_id
				  ,inward
				  ,outward
				  ,opening_balance
				  ,closing_balance
				) VALUES (?,37,0,?,0,?) on duplicate key update 
				outward=outward+?
				, closing_balance=closing_balance-?";
			$result=$this->db->query($sql,array($this->session->userdata('employee_id')
												,$copper
												,-$copper
												,$copper
												,$copper
												));
			
			if($result==FALSE){
					throw new Exception('material_to_employee_balance copper ');
			}
			$sql="insert into material_to_employee_balance (
				  emp_id
				  ,rm_id
				  ,inward
				  ,outward
				  ,opening_balance
				  ,closing_balance
				) VALUES (?,42,?,0,0,?) on duplicate key update 
				inward=inward+?
				, closing_balance=closing_balance+?";
			$result=$this->db->query($sql,array($this->session->userdata('employee_id')
												,$gini
												,$gini
												,$gini
												,$gini
												));
			
			if($result==FALSE){
					throw new Exception('material_to_employee_balance Gini ');
			}
			$this->db->query("COMMIT");
			$return_array['success']=1;
		}catch(Exception $e){
			$return_array['error']=create_log($this->db->last_query(),'Material_model','insert_material_transformation',"log_file.csv");
			$this->db->query("ROLLBACK");
			$return_array['success']=0;
		}
		return $return_array;
	}
	function insert_material_transformation_for_88($fine_gold,$copper,$gini,$comment){
		try{
			$this->db->query("START TRANSACTION");
			// creating material_transformation
			$current_financial_year=get_financial_year();
			$sql="insert into maxtable (table_name, mainfield, financial_year,prefix)values(?,1,?,'MT')
					on duplicate key UPDATE table_id=last_insert_id(table_id), mainfield=mainfield+1";
			$result=$this->db->query($sql,array('material_transformation',$current_financial_year));
			if($result==FALSE){
				throw new Exception('maxtable ');
			}
			//step 2: select mainfield as table name and current financial year
			$sql="select * from maxtable where table_id=last_insert_id()";
			$result=$this->db->query($sql)->row();
			$transaction_no=$result->prefix.'/'.$result->mainfield.'/'.$current_financial_year;
			$return_array['transaction_no']=$transaction_no;
			
			//adding to material_transformation
			$sql="insert into material_transformation (
				   trnasformation_id
				  ,particulars
				) VALUES (?,?)";
			$result=$this->db->query($sql,array($transaction_no,$comment));
			if($result==FALSE){
				throw new Exception('material_transformation');
			}
			$sql="insert into inventory_day_book (
			  employee_id
			  ,rm_id
			  ,transaction_type
			  ,rm_value
			  ,reference
			  ,comment
			) VALUES (?,36,-1,?,?,'Material Conversion')";
			$result=$this->db->query($sql,array($this->session->userdata('employee_id')
												,$fine_gold
												,$transaction_no
												));
			if($result==FALSE){
				throw new Exception('inventory_day_book Fine Gold');
			}
			$sql="insert into inventory_day_book (
			  employee_id
			  ,rm_id
			  ,transaction_type
			  ,rm_value
			  ,reference
			  ,comment
			) VALUES (?,37,-1,?,?,'Material Conversion')";
			$result=$this->db->query($sql,array($this->session->userdata('employee_id')
												,$copper
												,$transaction_no
												));
			if($result==FALSE){
				throw new Exception('inventory_day_book Copper');
			}
			$sql="insert into inventory_day_book (
			  employee_id
			  ,rm_id
			  ,transaction_type
			  ,rm_value
			  ,reference
			  ,comment
			) VALUES (?,49,1,?,?,'Material Conversion')";
			$result=$this->db->query($sql,array($this->session->userdata('employee_id')
												,$gini
												,$transaction_no
												));
			if($result==FALSE){
				throw new Exception('inventory_day_book 92 Gold');
			}
			$sql="insert into material_to_employee_balance (
				  emp_id
				  ,rm_id
				  ,inward
				  ,outward
				  ,opening_balance
				  ,closing_balance
				) VALUES (?,36,0,?,0,?) on duplicate key update 
				outward=outward+?
				, closing_balance=closing_balance-?";
			$result=$this->db->query($sql,array($this->session->userdata('employee_id')
												,$fine_gold
												,-$fine_gold
												,$fine_gold
												,$fine_gold
												));
			
			if($result==FALSE){
					throw new Exception('material_to_employee_balance fine gold ');
			}
			$sql="insert into material_to_employee_balance (
				  emp_id
				  ,rm_id
				  ,inward
				  ,outward
				  ,opening_balance
				  ,closing_balance
				) VALUES (?,37,0,?,0,?) on duplicate key update 
				outward=outward+?
				, closing_balance=closing_balance-?";
			$result=$this->db->query($sql,array($this->session->userdata('employee_id')
												,$copper
												,-$copper
												,$copper
												,$copper
												));
			
			if($result==FALSE){
					throw new Exception('material_to_employee_balance copper ');
			}
			$sql="insert into material_to_employee_balance (
				  emp_id
				  ,rm_id
				  ,inward
				  ,outward
				  ,opening_balance
				  ,closing_balance
				) VALUES (?,49,?,0,0,?) on duplicate key update 
				inward=inward+?
				, closing_balance=closing_balance+?";
			$result=$this->db->query($sql,array($this->session->userdata('employee_id')
												,$gini
												,$gini
												,$gini
												,$gini
												));
			
			if($result==FALSE){
					throw new Exception('material_to_employee_balance Gini ');
			}
			$this->db->query("COMMIT");
			$return_array['success']=1;
		}catch(Exception $e){
			$return_array['error']=create_log($this->db->last_query(),'Material_model','insert_material_transformation',"log_file.csv");
			$this->db->query("ROLLBACK");
			$return_array['success']=0;
		}
		return $return_array;
	}
	function get_material_balance_by_rmid_and_empid($rm_id){
		$sql="select * from material_to_employee_balance where emp_id=? and rm_id=?";
		$result=$this->db->query($sql,array($this->session->userdata('employee_id'),$rm_id));
		if($result->num_rows()>0){
			return $result->row();
		}else{
			return NULL;
		}
	}
	function insert_readymade_reference(){
		$return_array=array();
		try{
			$sql="insert into readymady_reference (
				   reference_id
				  ,product_set
				  ,qty
				  ,gold
				  ,total_weight
				) VALUES (?,?,?,?,?)";
			$result=$this->db->query($sql,array($_GET['reference_id']
												,$_GET['product_set']
												,$_GET['qty']
												,$_GET['appx_gold']
												,$_GET['total_weight']
									));
			if($result==FALSE){
				throw new Exception('readymade_reference');
			}
			$return_array['success']=1;	
		}catch(Exception $e){
			$return_array['sql']=$this->db->last_query();
			$return_array['error_message']=$this->db->_error_message();
			$return_array['error_log_code']=create_log($this->db->last_query(),'material_model','insert_readymade_reference',"log_file.csv");
			$return_array['error_number']=$this->db->_error_number();;
			$return_array['success']=0;
		}	
		return $return_array;
	}
}//final
?>