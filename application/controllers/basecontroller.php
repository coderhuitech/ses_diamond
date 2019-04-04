<?php
    class Basecontroller extends CI_Controller{
		function __construct(){
			parent::__construct();
			$this -> load -> helper(array('datagrid', 'url','huiui','csv','html'));
			
		}
		function set_site_data($view_file,$main_data = array('temp1'=>'data1')) {//important code used in near about all actions
			$template_data['main_data'] = $main_data;
			$template_data['header_data']=array('java_script'=>$main_data['java_script'],'css'=>$main_data['css'],'page_title'=>$main_data['page_title']);
			$template_data['view_file']=$view_file;
			return $template_data;
		}
		function index() {
			/*$data['main_content']='basic_view/basic_view';
			$this->load->view('includes/public/template',$data);*/
			
			//--------------------------------------------------
			$main_data=array();
			//this block of code is mandatory for all facade from now
			//-------------------------------------------------------------------------
			// extention should not be given
			$java_scripts=array('UI2/jquery-ui-1.10.2.custom/js/jquery-ui-1.10.2.custom'
							,'general'
							//,'general/form_validation'
							//,'sliding_label/jquery.slidinglabels.min'
							//,'ui/jquery-ui'
							,'table/jquery.dataTables.min'				// for data table
							,'transaction/receipts/receipts_js'
							);
		
			$css=array('transaction/receipts/receipts_css'
					,'ui-lightness/jquery-ui-1.10.2.custom'
					,'table_css/table/jquery.dataTables'		//for data table jquery
					,'UI2/jquery-ui-1.10.2.custom/css/ui-lightness/jquery-ui-1.10.2.custom'
					);	  
		
			$main_data['java_script']=$java_scripts;
			$main_data['css']=$css;
			$main_data['page_title']="Bengali Bangle Staff Area Receipts";
			$this -> load -> view('includes/public/template', $this -> set_site_data('basic_view/basic_view',$main_data));
		}
	}
?>