<?php
class Material_controller extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this -> load -> library('form_validation');
		//this libraru is used for validation of fields in a form
		$this->load->model('material_model');
		// this model for menus and navs
		$this -> is_logged_in();
		
		$this -> load -> helper(array('datagrid', 'url','huiui','html'));
	}
	function is_logged_in() {
		$is_logged_in = $this -> session -> userdata('is_logged_in');
		if (!isset($is_logged_in) || $is_logged_in != TRUE) {
			echo 'you have no permission to use this page. <a href="../login">Login</a>';
			die();
		}
	}
	function set_site_data($view_file,$main_data = array('temp1'=>'data1')) {//important code used in near about all actions
		$template_data['main_data'] = $main_data;
		$template_data['header_data']=array('java_script'=>$main_data['java_script'],'css'=>$main_data['css'],'page_title'=>$main_data['page_title']);
		$template_data['view_file']=$view_file;
		return $template_data;
	}
	function job_report_facade(){
		//this block of code is mandatory for all facade from now
		//-------------------------------------------------------------------------
		// extention should not be given
		$java_scripts=array('UI2/jquery-ui-1.10.2.custom/js/jquery-ui-1.10.2.custom'
			,'general'
			,'table/jquery.dataTables.min'				// for data table
			,'popup/js/alertbox'					// for popup message
			,'popup/js/jquery.easing.1.3'
			,'message_box/Scripts/jquery.msgBox'
			,'general/validation'
			,'jobs/show_job_details'
			
		);
		
		$css=array('transaction/capital/gold_introduce'
					,'ui-lightness/jquery-ui-1.10.2.custom'
					,'table_css/table/jquery.dataTables'
					,'jobs/show_job_details'
					,'message_box/Styles/msgBoxLight'
					);	   //for data table jquery
		$agents['AG2018']='Counter Agent';
		$main_data['user_id']=$this->session->userdata('user_id');
		$main_data['agents']=$agents;
		$main_data['java_script']=$java_scripts;
		$main_data['css']=$css;
		$main_data['current_date']=get_current_date();
		$main_data['delivery_date']=date("d/m/Y",strtotime("+5 days"));
		//$main_data['user_key']=$this->session->userdata('ip_address').'-'.date_serial_number(get_current_date()).'-'.rand(1000,9999);
		$main_data['user_key']=uniqid('', true);
		$main_data['page_title']="Diamond New Order";
		$this -> load -> view('includes/staff/template', $this -> set_site_data('jobs/show_job_details',$main_data));
	}
	function owner_to_employee_facade(){
		
		//this block of code is mandatory for all facade from now
		//-------------------------------------------------------------------------
		// extention should not be given
		$java_scripts=array('UI2/jquery-ui-1.10.2.custom/js/jquery-ui-1.10.2.custom'
			,'general'
			,'table/jquery.dataTables.min'				// for data table
			,'popup/js/alertbox'					// for popup message
			,'popup/js/jquery.easing.1.3'
			,'message_box/Scripts/jquery.msgBox'
			,'general/validation'
			,'materials/owner_to_employee'
		);
		
		$css=array('transaction/capital/gold_introduce'
					,'ui-lightness/jquery-ui-1.10.2.custom'
					,'table_css/table/jquery.dataTables'
					,'jobs/new_job'
					,'message_box/Styles/msgBoxLight'
					,'materials/owner_to_employee'
					,'materials/stock_all_employees'
					);	   //for data table jquery
		
		$employees=array();
		$result=$this->material_model->get_employees_inforce($this->session->userdata('employee_id'));
		if($result!=NULL){
			foreach($result->result() as $row){
				$employees[$row->emp_id]=$row->emp_name;
			}
			
		}
		$rm_categories=array();
		$result=$this->material_model->get_rm_category();
		if($result!=NULL){
			foreach($result->result() as $row){
				$rm_categories[$row->rm_cat_id]=$row->rm_cat_name;
			}
			
		}
		$materials=array();
		$result=$this->material_model->get_materials_by_category(1);
		if($result!=NULL){
			foreach($result->result() as $row){
				$materials[$row->rm_id]=$row->rm_name;
			}
			
		}
		$main_data['is_authorised']=is_authorised($this->session->userdata('priv_value'),65536);
	
		$main_data['employees']=$employees;
		$main_data['rm_categories']=$rm_categories;
		$main_data['materials']=$materials;
		
		$main_data['user_id']=$this->session->userdata('user_id');
		$main_data['java_script']=$java_scripts;
		$main_data['css']=$css;
		$main_data['page_title']="Diamond New Order";
		$this -> load -> view('includes/staff/template', $this -> set_site_data('materials/owner_to_employee',$main_data));
	}
	function get_rm_by_category_ajax(){
		$materials=array();
		$result=$this->material_model->get_materials_by_category($_GET['rm_cat_id']);
		if($result!=NULL){
			foreach($result->result() as $row){
				$materials[$row->rm_id]=$row->rm_name;
			}
		}
		echo form_label('Material');
		echo form_dropdown('materials',$materials,0,'id="materials"');
	}
	function send_material_to_employee_by_owner(){
		$result=$this->material_model->add_material_from_owner();
		if($result['success']==1){
			echo '<div id="msg">';
			echo "Transfered to Employee";
			echo '</div>';
			echo '<div id="error">noerror</div>';
			echo '<div id="report">';
			echo 'Current Balance is '.number_format($result['closing_balance'],3);
			echo '</div>';
		}else{
			echo '<div id="msg">';
			echo "Error sending Material";
			echo '</div>';
			echo '<div id="error">error</div>';
			echo '<div id="report">';
			print_r($result);
			echo '</div>';
		}
				
	}
	function create_rm_master_pivot_table(){
		//$this->sql_to_csv("select * from employees");
		$date = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
		$subject_id=0;
		$result=$this->material_model->rm_closing_balance_pivot_table();
		$this->load->library('table');
		$main_data=array();
		$this -> table -> set_template(default_table_template());
		if($result!=NULL){
			$this->table->set_template(default_table_template('class="dataTable" id="stock_at_all_employee"'));
			$this->table->set_caption('Time : '. $date->format('d/m/Y H:i:s'));
			echo '<div class="material-table-div">';
			echo $this->table->generate($result['result']);
			echo '</div>';
		}else{
			echo "Error fetching record";
			return;
		}
   }
	function show_material_by_employee(){
   	 $result=$this->material_model->get_employee_material_balance_by_id($_GET['employee_id']);
	 $this->load->library('table');
	 if($result!=NULL){
	 	foreach($result->result() as $row){
	 		$this->table->add_row(cell_format($row->Material,'text'),cell_format($row->Stock,'gold'));
	 	}
		$this->table->set_caption($row->emp_name);
	 }else{
	 	$this->table->set_caption('No Record found');
	 }
	 $this->table->set_heading('Material','Stock');
	 $this->table->set_template(default_table_template('class="dataTable"'));
	 echo $this->table->generate();
   }
	function material_transfer_facade(){
   		//this block of code is mandatory for all facade from now
		//-------------------------------------------------------------------------
		// extention should not be given
		$java_scripts=array('UI2/jquery-ui-1.10.2.custom/js/jquery-ui-1.10.2.custom'
			,'general'
			,'table/jquery.dataTables.min'				// for data table
			,'popup/js/alertbox'					// for popup message
			,'popup/js/jquery.easing.1.3'
			,'message_box/Scripts/jquery.msgBox'
			,'general/validation'
			,'materials/transfer'
		);
		
		$css=array('ui-lightness/jquery-ui-1.10.2.custom'
					,'table_css/table/jquery.dataTables'
					,'message_box/Styles/msgBoxLight'
					,'materials/transfer'
					,'materials/stock_all_employees'
					);	   //for data table jquery
		
		$employees=array();
		$result=$this->material_model->get_employees_inforce($this->session->userdata('employee_id'));
		if($result!=NULL){
			foreach($result->result() as $row){
				$employees[$row->emp_id]=$row->emp_name;
			}
			
		}
		$rm_categories=array();
		$result=$this->material_model->get_rm_category();
		if($result!=NULL){
			foreach($result->result() as $row){
				$rm_categories[$row->rm_cat_id]=$row->rm_cat_name;
			}
			
		}
		$materials=array();
		$result=$this->material_model->get_materials_by_category(1);
		if($result!=NULL){
			foreach($result->result() as $row){
				$materials[$row->rm_id]=$row->rm_name;
			}
			
		}
		$main_data['employees']=$employees;
		$main_data['rm_categories']=$rm_categories;
		$main_data['materials']=$materials;
		
		$main_data['user_id']=$this->session->userdata('user_id');
		$main_data['java_script']=$java_scripts;
		$main_data['css']=$css;
		$main_data['page_title']="Transfer";
		$this -> load -> view('includes/staff/template', $this -> set_site_data('materials/transfer',$main_data));
   }
	function add_transfer_material_between_employees(){
		$result=$this->material_model->transfer_material_between_employees();
		if($result['success']==1){
			echo '<div id="msg">';
			echo "Transfered to Employee";
			echo '</div>';
			echo '<div id="error">noerror</div>';
			echo '<div id="report">';
			echo 'Current Balance is '.number_format($result['closing_balance'],3);
			echo '</div>';
		}else{
			echo '<div id="msg">';
			echo "Error sending Material Code ".$result['error'];
			echo '</div>';
			echo '<div id="error">error</div>';
			echo '<div id="report">';
			echo $result['error'];
			echo '</div>';
		}
				
   }
	function material_loss_facade(){
		//this block of code is mandatory for all facade from now
		//-------------------------------------------------------------------------
		// extention should not be given
		$java_scripts=array('UI2/jquery-ui-1.10.2.custom/js/jquery-ui-1.10.2.custom'
			,'general'
			,'table/jquery.dataTables.min'				// for data table
			,'popup/js/alertbox'					// for popup message
			,'popup/js/jquery.easing.1.3'
			,'message_box/Scripts/jquery.msgBox'
			,'general/validation'
			,'materials/material_loss'
		);
		
		$css=array('transaction/capital/gold_introduce'
					,'ui-lightness/jquery-ui-1.10.2.custom'
					,'table_css/table/jquery.dataTables'
					,'jobs/new_job'
					,'message_box/Styles/msgBoxLight'
					,'materials/material_loss'
					);	   //for data table jquery
		
		$employees=array();
		$result=$this->material_model->get_employees_inforce($this->session->userdata('employee_id'));
		$employees['select']='--select--';
		if($result!=NULL){
			foreach($result->result() as $row){
				$employees[$row->emp_id]=$row->emp_name;
			}
			
		}
		$rm_categories=array();
		$result=$this->material_model->get_rm_category();
		$rm_categories['select']='--select--';
		if($result!=NULL){
			foreach($result->result() as $row){
				$rm_categories[$row->rm_cat_id]=$row->rm_cat_name;
			}
			
		}
		$materials=array();
		//$result=$this->material_model->get_materials_by_category(1);
		$materials['select']='--select--';
		/*if($result!=NULL){
			foreach($result->result() as $row){
				$materials[$row->rm_id]=$row->rm_name;
		}
			
		}*/
		$main_data['employees']=$employees;
		$main_data['rm_categories']=$rm_categories;
		$main_data['materials']=$materials;
		
		$main_data['user_id']=$this->session->userdata('user_id');
		$main_data['java_script']=$java_scripts;
		$main_data['css']=$css;
		$main_data['page_title']="Diamond New Order";
		$this -> load -> view('includes/staff/template', $this -> set_site_data('materials/material_loss',$main_data));	
	}
	function save_material_loss(){
		$result=$this->material_model->adjust_material_loss();
		if($result['success']==1){
			echo '<div id="msg">';
			echo "Loss Adjusted";
			echo '</div>';
			echo '<div id="error">noerror</div>';
			echo '<div id="report">';
			echo 'Loss Adjusted';
			echo '</div>';
		}else{
			echo '<div id="msg">';
			echo "Error sending Material";
			echo '</div>';
			echo '<div id="error">error</div>';
			echo '<div id="report">';
			print_r($result);
			echo '</div>';
		}
	}
	function miscellaneous_facade(){
   		//this block of code is mandatory for all facade from now
		//-------------------------------------------------------------------------
		// extention should not be given
		$java_scripts=array('UI2/jquery-ui-1.10.2.custom/js/jquery-ui-1.10.2.custom'
			,'general'
			,'table/jquery.dataTables.min'				// for data table
			,'popup/js/alertbox'					// for popup message
			,'popup/js/jquery.easing.1.3'
			,'message_box/Scripts/jquery.msgBox'
			,'general/validation'
			,'materials/miscellaneous'
			,'jquery-numberformat'
		);
		
		$css=array('ui-lightness/jquery-ui-1.10.2.custom'
					,'table_css/table/jquery.dataTables'
					,'message_box/Styles/msgBoxLight'
					,'materials/miscellaneous'
					);	   //for data table jquery
		$main_data['user_id']=$this->session->userdata('user_id');
		$main_data['java_script']=$java_scripts;
		$main_data['css']=$css;
		$main_data['page_title']="Miscellaneous";
		$this -> load -> view('includes/staff/template', $this -> set_site_data('materials/miscellaneous',$main_data));
   }
	function fine_to_92_trnasformation_facade(){
		?>
   		<div id="material-trnasformation">
	   		<h2>Material Transformation পাকা থেকে 92 গিনি</h2>
	   		<div>
	   			<label>Fine Gold</label>
	   			<input type="text" id="fine-gold-92" class="keyup-numeric" placeholder="Fine Gold" title="Enter Fine Gold"  />
	   		</div>
	   		<div>
	   			<label>Copper</label>
	   			<input type="text" id="copper-92" placeholder="Copper" title="Enter Copper" readonly="yes"/>
	   		</div>
	   		<div>
	   			<label>92 Gold</label>
	   			<input type="text" id="gini-92" placeholder="92 Gold" title="Enter Converted 92 Gold" readonly="yes"/>
	   		</div>
	   		<div>
	   			<label>Comment</label>
	   			<?php echo form_textarea('material_transform','Material Transformed','id="comment"');?>
	   		</div>
	   		</br>
	   		<?php echo form_button('transform','Transform','id="transform-92"');?>
	   	</div>
	   	<?php
    }
	function fine_to_90_trnasformation_facade(){
		$result=$this->material_model->get_material_balance_by_rmid_and_empid(36);
   		?>
   		<div id="material-trnasformation">
	   		<h2>Material Transformation পাকা থেকে 90 গিনি</h2>
	   		<div>
	   			<label>Fine Gold</label>
	   			<input type="text" id="fine-gold-90" class="keyup-numeric" placeholder="Fine Gold" title="Enter Fine Gold"  />
	   		
	   		<?php
	   			if($result==NULL){
					echo ' In Hand : 0 g.';
				}else{
					echo ' In Hand : '.number_format($result->closing_balance,3).' g.';
				}
			?>
	   		</div>
	   		<?php $result=$this->material_model->get_material_balance_by_rmid_and_empid(37);?>
	   		<div>
	   			<label>Copper</label>
	   			<input type="text" id="copper-90" placeholder="Copper" title="Enter Copper"/ readonly="yes">
	   		<?php
	   		if($result==NULL){
				echo ' In Hand : 0 g.';
			}else{
				echo ' In Hand : '.number_format($result->closing_balance,3).' g.';
			}
			?>
	   		</div>
	   		<?php $result=$this->material_model->get_material_balance_by_rmid_and_empid(42);?>
	   		<div>
	   			<label>90 Gold</label>
	   			<input type="text" id="gini-90" placeholder="90 Gold" title="Enter Converted 90 Gold"/ readonly="yes">
	   		<?php
		   		if($result==NULL){
					echo ' In Hand : 0 g.';
				}else{
					echo ' In Hand : '.number_format($result->closing_balance,3).' g.';
				}
				?>
	   		</div>
	   		<div>
	   			<label>Comment</label>
	   			<?php echo form_textarea('material_transform','Material Transformed','id="comment"');?>
	   		</div>
	   		</br>
	   		<?php echo form_button('transform','Transform','id="transform-90"');?>
	   	</div>
	   	<?php
   }
   function fine_to_88_trnasformation_facade(){
   		$result=$this->material_model->get_material_balance_by_rmid_and_empid(36);
   		?>
   		<div id="material-trnasformation">
	   		<h2>Material Transformation পাকা থেকে 88 গিনি</h2>
	   		<div>
	   		
	   		<label>Fine Gold</label>
	   		<input type="text" id="fine-gold-88" class="keyup-numeric" placeholder="Fine Gold" title="Enter Fine Gold"  />
	   		<?php
	   		if($result==NULL){
				echo ' In Hand : 0 g.';
			}else{
				echo ' In Hand : '.number_format($result->closing_balance,3).' g.';
			}
			?>
	   		</div>
	   		<?php $result=$this->material_model->get_material_balance_by_rmid_and_empid(37);?>
	   		<div>
	   			<label>Copper</label>
	   			<input type="text" id="copper-88" placeholder="Copper" title="Enter Copper"/>
		   		<?php
		   		if($result==NULL){
					echo ' In Hand : 0 g.';
				}else{
					echo ' In Hand : '.number_format($result->closing_balance,3).' g.';
				}
				?>
	   		</div>
	   		<?php $result=$this->material_model->get_material_balance_by_rmid_and_empid(49);?>
	   		<div>	
	   			<label>Gold</label>
	   			<input type="text" id="gini-88" placeholder="88 Gold" title="Enter Converted 88 Gold"/>
		   		<?php
		   		if($result==NULL){
					echo ' In Hand : 0 g.';
				}else{
					echo ' In Hand : '.number_format($result->closing_balance,3).' g.';
				}
				?>
	   		</div>
	   		<div>
	   			<label>Comment</label>
	   			<?php echo form_textarea('material_transform','Material Transformed','id="comment"');?>
	   		</div>
	   		</br>
	   		<?php echo form_button('transform','Transform','id="transform-88"');?>
	   	</div>
	   	<?php
   }
	function fine_to_92_trnasformation_action(){
   		$row_array=array();
		$return_arr=array();
   		try{
			
			if($_GET['fine_gold']<=0)
   				throw new Exception('Enter valid Fine Gold value');
   			if($_GET['copper']<=0)
   				throw new Exception('Enter valid Copper value');
   			if($_GET['gini']<=0)
   				throw new Exception('Enter valid Gini 92 value');
   			
			if($_GET['gini']<($_GET['fine_gold']+$_GET['copper'])*0.997)
				throw new Exception('Gini Should be at least '.($_GET['fine_gold']+$_GET['copper'])*0.997);
			$result=$this->material_model->insert_material_transformation_for_92($_GET['fine_gold'],$_GET['copper'],$_GET['gini'],$_GET['comment']);
				if($result['success']==0){
					throw new Exception($result['error']);
				}
				$row_array['success']=1;
				$row_array['msg']="Material Transformed to 92 Gold";
				array_push($return_arr,$row_array);
				echo json_encode($return_arr);
		 } catch (Exception $e) {
		   	$row_array['success']=0;
		   	$row_array['msg']=$e->getMessage();
		   	array_push($return_arr,$row_array);
		   	echo json_encode($return_arr);
		 }
   }
	function fine_to_90_trnasformation_action(){
   		$row_array=array();
		$return_arr=array();
   		try{
			
			if($_GET['fine_gold']<=0)
   				throw new Exception('Enter valid Fine Gold value');
   			if($_GET['copper']<=0)
   				throw new Exception('Enter valid Copper value');
   			if($_GET['gini']<=0)
   				throw new Exception('Enter valid Gini 92 value');
   			
			if($_GET['gini']<($_GET['fine_gold']+$_GET['copper'])*0.997)
				throw new Exception('Gini Should be at least '.($_GET['fine_gold']+$_GET['copper'])*0.997);
			$result=$this->material_model->insert_material_transformation_for_90($_GET['fine_gold'],$_GET['copper'],$_GET['gini'],$_GET['comment']);
				if($result['success']==0){
					throw new Exception($result['error']);
				}
				$row_array['success']=1;
				$row_array['msg']="Material Transformed to 90 Gold";
				array_push($return_arr,$row_array);
				echo json_encode($return_arr);
		 } catch (Exception $e) {
		   	$row_array['success']=0;
		   	$row_array['msg']=$e->getMessage();
		   	array_push($return_arr,$row_array);
		   	echo json_encode($return_arr);
		 }
   }
   function fine_to_88_trnasformation_action(){
   		$row_array=array();
		$return_arr=array();
   		try{
			
			if($_GET['fine_gold']<=0)
   				throw new Exception('Enter valid Fine Gold value');
   			if($_GET['copper']<=0)
   				throw new Exception('Enter valid Copper value');
   			if($_GET['gini']<=0)
   				throw new Exception('Enter valid Gini 92 value');
   			
			if($_GET['gini']<($_GET['fine_gold']+$_GET['copper'])*0.997)
				throw new Exception('Gini Should be at least '.($_GET['fine_gold']+$_GET['copper'])*0.997);
			$result=$this->material_model->insert_material_transformation_for_88($_GET['fine_gold'],$_GET['copper'],$_GET['gini'],$_GET['comment']);
				if($result['success']==0){
					throw new Exception($result['error']);
				}
				$row_array['success']=1;
				$row_array['msg']="Material Transformed to 88 Gold";
				array_push($return_arr,$row_array);
				echo json_encode($return_arr);
		 } catch (Exception $e) {
		   	$row_array['success']=0;
		   	$row_array['msg']=$e->getMessage();
		   	array_push($return_arr,$row_array);
		   	echo json_encode($return_arr);
		 }
   }
   function stock_reference_entry_view(){
   		start_div('id="stock-reference-entry-div"');
	   		start_div();
	   		echo form_label('Reference Code');
	   		echo form_input('reference_code','','id="reference-code" placeholder="Reference Code" title="Enter New Reference" required="yes"');
	   		end_div();
	   		
	   		start_div();
	   		echo form_label('Set');
	   		echo form_input('product_set','','id="product-set" placeholder="0" title="Enter Minimum 1" required="yes"');
	   		end_div();
	   		
	   		start_div();
	   		echo form_label('Qty');
	   		echo form_input('qty','','id="qty" placeholder="0" title="Enter Minimum 1" required="yes"');
	   		end_div();
	   		
	   		start_div();
	   		echo form_label('Appx Gold');
	   		echo form_input('appx_gold','','id="appx-gold" placeholder="0.000" title="Enter Approx Gold" required="yes"');
	   		end_div();
	   		
	   		start_div();
	   		echo form_label('Total Weight');
	   		echo form_input('total-weight','','id="total-weight" placeholder="0.000" title="Enter Totla Weight" required="yes"');
	   		end_div();
	   		
	   		start_div();
	   		echo form_button('reference_save','Save','id="stock-reference-save"');
	   		end_div();
	   	end_div();
   } 
   function save_stock_reference(){
   		$result=$this->material_model->insert_readymade_reference();
		$row_array=array();
		$return_arr=array();
		if($result['success']==0){
			$row_array['success']=0;
			$row_array['msg']=$result['error_message'];
			$row_array['error_log_code']=$result['error_log_code'];
			$row_array['error_message']=$result['error_message'];
			$row_array['error_number']=$result['error_number'];
			$row_array['sql']=$result['sql'];
			$row_array['message_type']='error';
			array_push($return_arr,$row_array);
			echo json_encode($return_arr);
			return;
		}

		$row_array['success']=1;
		$row_array['msg']="Reference Inserted";
		$row_array['sql']="";
		$row_array['message_type']='information';
		array_push($return_arr,$row_array);
		echo json_encode($return_arr);
   }
}
?>