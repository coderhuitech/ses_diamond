<?php
class Product_controller extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this -> load -> library('form_validation');
		//this libraru is used for validation of fields in a form
		$this->load->model('product_model');
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
	function set_blank_data($view_file,$main_data = array('temp1'=>'data1')) {//important code used in near about all actions
		$template_data['main_data'] = $main_data;
		$template_data['view_file']=$view_file;
		return $template_data;
	}
	function set_site_data($view_file,$main_data = array('temp1'=>'data1')) {//important code used in near about all actions
		$template_data['main_data'] = $main_data;
		$template_data['header_data']=array('java_script'=>$main_data['java_script'],'css'=>$main_data['css'],'page_title'=>$main_data['page_title']);
		$template_data['view_file']=$view_file;
		return $template_data;
	}
	function product_master_facade(){
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
			,'products/product_master'
		);
		
		$css=array('transaction/capital/gold_introduce'
					,'ui-lightness/jquery-ui-1.10.2.custom'
					,'table_css/table/jquery.dataTables'
					,'jobs/new_job'
					,'message_box/Styles/msgBoxLight'
					,'products/product_master'
					);	   //for data table jquery
		$main_data['java_script']=$java_scripts;
		$main_data['css']=$css;
		$main_data['page_title']="Diamond New Order";
		$this -> load -> view('includes/staff/template', $this -> set_site_data('products/product_master',$main_data));	
	}
	function add_edit_product(){
		$main_data=array();
		$product_category[0]="--select-";
		$result=$this->product_model->select_product_category();
		foreach($result->result() as $row){
				$product_category[$row->ID]=$row->category;		
		}
		$main_data['product_category']=$product_category;
		
		$this -> load -> view('includes/blank/template', $this -> set_blank_data('products/add_edit_product',$main_data));	
	}
	
	function get_product_by_product_code(){
		$result=$this->product_model->select_product_by_product_code($_GET['product_code']);
		$row_array=array();
		$return_arr=array();
		if($result==NULL){
			$row_array['model']="None";
			$row_array['description']="New Model";
			$row_array['product_category']=0;
			$row_array['price_code']="";
			array_push($return_arr,$row_array);
			echo json_encode($return_arr);
			return;
		}

		$row_array['model']=$result->product_code;
		$row_array['description']=$result->product_description;
		$row_array['product_category']=$result->product_category;
		$row_array['price_code']=$result->price_code;
		array_push($return_arr,$row_array);
		echo json_encode($return_arr);
	}
	function add_product(){
		$result=$this->product_model->update_or_add_product();
		$row_array=array();
		$return_arr=array();
		if($result['success']==0){
			$row_array['success']=0;
			$row_array['msg']=$result['error'];
			$row_array['sql']=$result['sql'];
			$row_array['row_updated']=$result['row_updated'];
			
			array_push($return_arr,$row_array);
			echo json_encode($return_arr);
			return;
		}

		$row_array['success']=1;
		$row_array['msg']="Item Saved";
		$row_array['sql']="";
		$row_array['row_updated']=$result['row_updated'];
			
		array_push($return_arr,$row_array);
		echo json_encode($return_arr);
	}
}
?>