<?php
class Site_controller extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this -> load -> library('form_validation');
		//this libraru is used for validation of fields in a form
		$this -> load -> model('main_model');
		$this -> load -> model('view_model');
		// this model for menus and navs
		$this -> is_logged_in();
		
		$this -> load -> helper(array('datagrid', 'url','huiui','csv','html'));
	}
	
	function is_logged_in() {
		$is_logged_in = $this -> session -> userdata('is_logged_in');
		if (!isset($is_logged_in) || $is_logged_in != TRUE) {
			echo 'you have no permission to use this page. <a href="../login/staff_login_facade">Login</a>';
			die();
		}
	}
	function set_site_data($view_file,$main_data = array('temp1'=>'data1')) {//important code used in near about all actions
		$template_data['main_data'] = $main_data;
		$template_data['header_data']=array('java_script'=>$main_data['java_script'],'css'=>$main_data['css'],'page_title'=>$main_data['page_title']);
		$template_data['view_file']=$view_file;
		return $template_data;
	}
	public function default_table_template($value = NULL) {
		$template = array('table_open' => '<table border="0" cellpadding="4" cellspacing="0">', 'table_close' => '</table>', 'heading_open' => '<thead>', 'heading_close' => '</thead>', 'heading_row_start' => '<tr>', 'heading_row_end' => '</tr>', 'heading_cell_start' => '<th>', 'heading_cell_end' => '</th>', 'sub_heading_row_start' => '<tr>', 'sub_heading_row_end' => '</tr>', 'sub_heading_cell_start' => '<th>', 'sub_heading_cell_end' => '</th>', 'body_open' => '<tbody>', 'body_close' => '</tbody>', 'row_start' => '<tr>', 'row_end' => '</tr>', 'cell_start' => '<td>', 'cell_end' => '</td>', 'row_alt_start' => '<tr class="alt">', 'row_alt_end' => '</tr>', 'cell_alt_start' => '<td>', 'cell_alt_end' => '</td>', 'footing_open' => '<tfoot>', 'footing_close' => '</tfoot>', 'footing_row_start' => '<tr>', 'footing_row_end' => '</tr>', 'footing_cell_start' => '<td>', 'footing_cell_end' => '</td>');
		return $template;
	}
	function members_area_facade() {
		$main_data=array();
		//this block of code is mandatory for all facade from now
		//-------------------------------------------------------------------------
		// extention should not be given
		$emp_id=$this->session->userdata('employee_id');
		$result=$this->main_model->get_closing_stock_report_by_emp_id($emp_id);
		$this->load->library('table');
		$this->table->set_template(default_table_template('id="material-closing-balance"'));
		$report_table=$this->table->generate($result);

		$java_scripts=array();
		$css=array('members_area/members_area');
		$main_data['java_script']=$java_scripts;
		$main_data['css']=$css;
		$main_data['page_title']="Bengali Bangle Staff Area";

		$main_data['report_table']=$report_table;
		//--------------------------------------------------------------------------
		$this -> load -> view('includes/staff/template', $this -> set_site_data('user_view/members_area',$main_data));
	}
	
	function logout_action(){
		$this->session->sess_destroy();
        redirect('login/staff_login_facade');
	}		
}
?>