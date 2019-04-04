<?php
class advance_report_controller extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->load->model('report_model'); //main_model will be available at every function
        $this->load->library('session');

    }
    function is_logged_in() {
        $is_logged_in = $this -> session -> userdata('is_logged_in');
        if (!isset($is_logged_in) || $is_logged_in != true) {
            echo 'you have no permission to use this page. <a href="'.site_url().'/login">Login</a>';
            die();
        }
    }
    function set_site_data($view_file,$main_data = array('temp1'=>'data1')) {//important code used in near about all actions
        $template_data['main_data'] = $main_data;
        $template_data['header_data']=array('java_script'=>$main_data['java_script'],'css'=>$main_data['css'],'page_title'=>$main_data['page_title']);
        $template_data['view_file']=$view_file;
        return $template_data;
    }
    function sales_gold_receipt_report() {
        $main_data=array();
        //this block of code is mandatory for all facade from now
        //-------------------------------------------------------------------------
        // extention should not be given
        $java_scripts=array('advance_report/sales_gold_receipt_report'
                            ,'general'
        );
        $css=array(
            'font-awesome/css/font-awesome.min'
        );


        $main_data['java_script']=$java_scripts;
        $main_data['css']=$css;
        $main_data['page_title']="Staff Area";

        $this -> load -> view('includes/advance_report/template', $this -> set_site_data('advance_report/gold_receipt_print',$main_data));
    }

    function show_lc_receipt_details(){
        $result=$this->report_model->get_lc_receipt_details_by_lc_receipt_id($_GET['lc_receipt_id']);
        echo '<br>'."Received Amount: Rs. ".$result->amount.'<br>';
        echo 'From '.$result->mailing_name.'<br>';
        echo 'of '.$result->city.'<br>';
        echo 'through '.$result->agent_name.'<br>';
        echo 'Received by '.$result->emp_name.'<br>';
        echo 'on '.$result->lc_receipt_date.'<br>';
    }
    function show_gold_receipt_details(){
        $result=$this->report_model->get_gold_receipt_details_by_gold_receipt_id($_GET['gold_receipt_id']);

        echo '<br>'."Received Amount: Rs. ".$result->gold_value.' g.<br>';
        echo 'From '.$result->mailing_name.'<br>';
        echo 'of '.$result->city.'<br>';
        echo 'through '.$result->agent_name.'<br>';
        echo 'Received by '.$result->emp_name.'<br>';
        echo 'on '.$result->tr_date.'<br>';
    }
    function show_lc_receipt_report() {
        $main_data=array();
        //this block of code is mandatory for all facade from now
        //-------------------------------------------------------------------------
        // extention should not be given
        $java_scripts=array('advance_report/sales_lc_receipt_report'
        ,'general'
        );
        $css=array(
            'font-awesome/css/font-awesome.min'
        );


        $main_data['java_script']=$java_scripts;
        $main_data['css']=$css;
        $main_data['page_title']="Staff Area";

        $this -> load -> view('includes/advance_report/template', $this -> set_site_data('advance_report/lc_receipt_print',$main_data));
    }
    function gold_receipt_report() {
        $main_data=array();
        //this block of code is mandatory for all facade from now
        //-------------------------------------------------------------------------
        // extention should not be given
        $java_scripts=array('advance_report/sales_gold_receipt_report'
        ,'general'
        );
        $css=array(
            'font-awesome/css/font-awesome.min'
        );


        $main_data['java_script']=$java_scripts;
        $main_data['css']=$css;
        $main_data['page_title']="Staff Area";

        $this -> load -> view('includes/advance_report/template', $this -> set_site_data('advance_report/lc_receipt_print',$main_data));
    }
}//end of controller

?>