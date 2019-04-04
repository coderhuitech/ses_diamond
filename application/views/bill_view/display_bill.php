
<style type="text/css">
h3{
	text-align:center;
	font-size:20px;
}
@media print {
		.donotprint {display:none;}
		.donotnoshow {display:none;}
	}

</style>
<a href="#" onclick="window.print(); return false;" class="donotprint">Print this Bill</a>
<div id='bill_heading'>
	<h1> SRIKRISHNA BANGLE JEWELLERY WORKSHOP </h1>
	<h3>এক কদম আগে</h3>
	<h5> Sewli, P.O. :- Sewli Telini Para, Barrackpore, Dt.-24-PGS(N), Kolkata-700121</h5>
	<h5> Contact (033)2535 3727 Mob. 9836444999, email:- bangle312@gmail.com, www.bengalibangle.com </h5>
	<hr/>
</div>
<?php
echo "<div class='bill_master'>";
	echo $table1;
echo "</div>";

echo "<div class='bill_details'>";
	echo $table2;
	echo "Amount in Word : <b>Rupees ".convert_number_to_words($total_lc).' Only</b>';
echo "</div>";

echo "<div class='bill_details'>";
	//echo $table3;
echo "</div>";

echo "<div id='final_table'>";
	echo $table4;
echo "</div>";

echo "<div id='paid_status'>";
	//echo 'Note: '.$table5;
echo "</div>";
	
?>
