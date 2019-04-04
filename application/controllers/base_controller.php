<?php
    class Base_controller extends CI_Controller{
		function __construct(){
			parent::__construct();
			
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
			$java_scripts=array('login/login'
								,'general/general'
								,'message_box/Scripts/jquery.msgBox'
								,'popup/js/alertbox'
								,'popup/js/jquery.easing.1.3'
								,'md5/md5_js');
		
			$css=array('login/login'
						,'message_box/Styles/msgBoxLight'
						,'popup/js/style');	  
		
			$main_data['java_script']=$java_scripts;
			$main_data['css']=$css;
			$main_data['page_title']="Bengali Bangle Staff Area Receipts";
			$this -> load -> view('includes/login/template', $this -> set_site_data('login/login',$main_data));
		}
	}
?>