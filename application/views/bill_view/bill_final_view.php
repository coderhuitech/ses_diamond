<?php
if($bill_no!=NULL){
	echo '<fieldset>';
	echo '<legend> Bill show to Page</legend>';
	echo form_open('site/show_bill_facade','id="show_bill_facade"');
	echo form_input('bill_no',$bill_no,'id="bill_no"');
	echo 'Click here to ';
	echo form_submit('submit','print');
	echo ' the bill ';
	echo form_close();	
echo '</fieldset>';

echo '<fieldset>';
	echo '<legend> Bill Show PDF</legend>';
	echo form_open('site/make_bill_pdf_facade','id="make_bill_pdf_facade"');
	echo form_input('bill_no',$bill_no,'id="bill_no"');
	echo 'Click here to ';
	echo form_submit('submit','print');
	echo ' the bill ';
	echo form_close();	
echo '</fieldset>';
}else{
	echo 'No Bill Saved';
}
?>
