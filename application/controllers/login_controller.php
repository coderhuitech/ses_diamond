<?php
	class Login_controller extends CI_Controller{
		function __construct(){
			parent::__construct();
			$this->load->library('form_validation');//form validation will be available at every function
			$this->load->model('main_model'); //main_model will be available at every function
		}
		function set_site_data($view_file,$main_data = array('temp1'=>'data1')) {//important code used in near about all actions
			$template_data['main_data'] = $main_data;
			$template_data['header_data']=array('java_script'=>$main_data['java_script'],'css'=>$main_data['css'],'page_title'=>$main_data['page_title']);
			$template_data['view_file']=$view_file;
			return $template_data;
		}
		/*function staff_login_facade($error=0) {
			$data['main_content']='user_view/login_form';
			$data['error']=$error;
			$this->load->view('includes/public/template',$data);
		}*/
		
		
		function staff_login_facade($error=0){
			$main_data=array();
			//this block of code is mandatory for all facade from now
			//-------------------------------------------------------------------------
			// extention should not be given
			$java_scripts=array('UI2/jquery-ui-1.10.2.custom/js/jquery-ui-1.10.2.custom'
							,'general'
							//,'general/form_validation'
							//,'sliding_label/jquery.slidinglabels.min'
							//,'ui/jquery-ui'
							,'login/login'
							,'table/jquery.dataTables.min'				// for data table
							,'popup/js/alertbox'
							,'popup/js/jquery.easing.1.3'
							,'encrypt/jquery.jcryption.min'
							,'md5/md5_js'
							);
		
			$css=array('login_form/login_form'
						,'ui-lightness/jquery-ui-1.10.2.custom'
						,'table_css/table/jquery.dataTables'		//for data table jquery
						,'UI2/jquery-ui-1.10.2.custom/css/ui-lightness/jquery-ui-1.10.2.custom'
						,'popup/js/style'
					  );	  
		
			$machines['Server']='Server';
			$machines['Machine1']='Machine1';
			$machines['Machine2']='Machine2';
			$machines['Machine3']='Machine3';
			$machines['Machine4']='Machine4';
			$machines['Machine5']='Machine5';
			$machines['Machine6']='Machine6';
			$main_data['machine']=$machines;
			$main_data['java_script']=$java_scripts;
			$main_data['css']=$css;
			$main_data['page_title']="Bengali Bangle Staff Area Receipts";
			$this -> load -> view('includes/public/template', $this -> set_site_data('login/login_form',$main_data));
		}
	
		function validate_credential_action_ajax(){
			$password=$_GET['password'];// already encrypted to MD5
			$userid=$_GET['username'];
			$result=$this->main_model->validate_user($userid,$password);
			
			if($result==NULL){
				echo '<div id="msg">';
				echo "Invalid Credential";
				echo '</div>';
				echo '<div id="error">error</div>';
				return;
			}
			$data=array(
					'user_id'=>$userid
					,'employee_name'=>$result->emp_name
					,'employee_id'=>$result->emp_id
					,'is_logged_in'=> true
					,'priv_value'=>$result->priv_value
					,'machine'=>$userid=$_GET['machine']
					
			);
			$this->session->set_userdata($data);
			echo '<div id="error">noerror</div>';
	
		}
		//***************** End of Item Status Report **************************		
}
?>