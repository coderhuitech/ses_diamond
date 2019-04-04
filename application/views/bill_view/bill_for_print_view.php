<?php
	if (!isset($bill_table)) {
		echo "Record Not Found";
	}else {
	echo "<div class='right_aligned_text'>"."Bill No: " .$bill_master['bill_no'],"</div>";
	echo "<div class='right_aligned_text'>"."For the period of:  ".month_name_from_value($bill_master['bill_month']),', '.$bill_master['bill_year']."</div>";
	print_line("Client Name: " .$bill_master['client_name']);
	print_line("Address :".$bill_master['client_address1']);
	print_line("Date : ".sql_date_to_dmy($bill_master['bill_date']));
	echo "<div id='bill'>";
	echo $bill_table;
	echo "</div>";
	print_line("Amount in Word : ".convert_number_to_words($bill_master['net_amount']));
		
	}
	//print_r($bill_master);
	//print_line('-----------------------------------------------------------------------');
	//print_line('Bill No.',$bill_master['bill_no']);
	///print_line('For the period of:  ',month_name_from_value($bill_master['bill_month']),', ',$bill_master['bill_year']);
	//foreach($bill_details as $row){
		//print_line('---------------------------------------------------------------------------');
		//echo '<br/>';
		//print_r($row);
		//print_line('---------------------------------------------------------------------------');
		//echo '<br/>';
	//}
?>
