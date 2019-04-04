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
        $current_received=0;
        $result=$this->report_model->get_lc_receipt_details_by_lc_receipt_id($_GET['lc_receipt_id']);
        $current_received=$result->amount;
        //echo '<br>'."Received Amount: Rs. ".$result->amount.'<br>';
        echo 'ID: '.$_GET['lc_receipt_id'].'<br>';
		echo 'From '.$result->mailing_name.'<br>';
        echo 'of '.$result->city.'<br>';
        echo 'through '.$result->agent_name.'<br>';
        echo 'Received by '.$result->emp_name.'<br>';
        $emp=$result->emp_name;
        echo 'on '.$result->lc_receipt_date.'<br>';
        //getting customer LC dues
        $previous_balance=0;
        $result=$this->report_model->customer_lc_opening_balance_by_lc_receipt_no($_GET['lc_receipt_id']);
        echo '<table>';
            echo '<tbody>';
            //echo "<tr>";
            //echo '<td>Opening LC:    </td><td align="right">'.$result->opening_lc.'</td>';
            //echo "</tr>";
            $previous_balance+=$result->opening_lc;
            $result=$this->report_model->select_total_billed_lc_before_lc_receipt_no($_GET['lc_receipt_id']);
            $transaction_time=$result->virtual_date;
            //echo "<tr>";
            //echo '<td>Add: Billed LC:    </td><td align="right">'.$result->total_billed_lc.'</td>';
            //echo "</tr>";
            $previous_balance+=$result->total_billed_lc;
            $result=$this->report_model->select_total_lc_receipt_before_lc_receipt_no($_GET['lc_receipt_id']);
            //echo "<tr>";
            //echo '<td>Less: LC Receied:    </td><td  align="right">'.$result->total_lc_received."</td>";
            //echo "</tr>";
            $previous_balance-=$result->total_lc_received;
            //echo "<br>---------------------------------";
            echo '<tr style="border-top: 1px solid black">';
            echo '<td>Previous Dues:    </td><td  align="right">'.$previous_balance."</td>";
            echo "</tr><tr>";
            echo '<td>Less: Now refund</td><td align="right">'.        $current_received."</td>";
            echo "</tr>";
            // echo "<br>---------------------------------";
            echo '<tr style="border-top: 1px solid black; border-bottom: 1px double black">';
            echo '<td>Current Due     </td><td align="right">'. ($previous_balance-$current_received)."</td>";
            echo "</tr>";
            echo '</tbody>';
        echo '</table>';

        echo 'Signatory<br><br><br>['.$emp.']<br>'.$transaction_time;
    }
    function show_gold_receipt_details(){
        $result=$this->report_model->get_gold_receipt_details_by_gold_receipt_id($_GET['gold_receipt_id']);

        //echo '<br>'."Received Gold. ".$result->gold_value.' g.<br>';
        $current_received=number_format($result->gold_value,3);
		echo 'ID: '.$_GET['gold_receipt_id'].'<br>';
        echo 'From '.$result->mailing_name.'<br>';
        echo 'of '.$result->city.'<br>';
        echo 'through '.$result->agent_name.'<br>';
        echo 'Received by '.$result->emp_name.'<br>';
        $emp=$result->emp_name;
        echo 'on '.$result->tr_date.'<br>';
        //getting customer dues
        $previous_balance=0;
        $result=$this->report_model->customer_gold_opening_balance_by_gold_receipt_id($_GET['gold_receipt_id']);
        echo '<table>';
        echo '<tbody>';
        //echo "<tr>";
        //echo '<td>Opening Gold:    </td><td align="right">'.$result->opening_gold.'</td>';
        //echo "</tr>";
        $previous_balance+=$result->opening_gold;
        $result=$this->report_model->select_total_billed_gold_before_gold_receipt_no($_GET['gold_receipt_id']);
        $transaction_time=$result->virtual_date;
       // echo "<tr>";
       // echo '<td>Add: Billed gold:    </td><td align="right">'.$result->total_billed_gold.'</td>';
       // echo "</tr>";
        $previous_balance+=$result->total_billed_gold;
        $result=$this->report_model->select_total_gold_receipt_before_gold_receipt_no($_GET['gold_receipt_id']);
        //echo "<tr>";
       // echo '<td>Less:Returned    </td><td  align="right">'.$result->total_gold_received."</td>";
       // echo "</tr>";
        $previous_balance-=$result->total_gold_received;
        //echo "<br>---------------------------------";
        echo '<tr style="border-top: 1px solid black">';
        echo '<td>Previous Dues:    </td><td  align="right">'.$previous_balance."</td>";
        echo "</tr><tr>";
        echo '<td>Less: Gold Received</td><td align="right">'.        $current_received."</td>";
        echo "</tr>";
        // echo "<br>---------------------------------";
        echo '<tr style="border-top: 1px solid black; border-bottom: 1px double black">';
        $final_due=($previous_balance-$current_received);
        if($final_due<=.001)
            $final_due=0;
        echo '<td>Current Due     </td><td align="right">'.number_format($final_due,3) ."</td>";
        echo "</tr>";
        echo '</tbody>';
        echo '</table>';

        echo '<br>Signatory<br><br><br>['.$emp.']<br>'.$transaction_time;
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