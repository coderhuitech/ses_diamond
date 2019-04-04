
<style type="text/css">
h3{
	text-align:center;
	font-size:20px;
}
</style>
<div id='bill_heading'>
	<h1> Srikrishna Bangle Jewellery Workshop </h1>
	<h5> Sewli, P.O. :- Sewli Telini Para, Barrackpore, Dt.-24-PGS(N), Kolkata-700121</h5>
	<h5> Contact (033)2535 3727 Mob. 9836444999, email:- bangle312@gmail.com, www.bengalibangle.com </h5>
	<hr/>
</div>
<?php
echo "<div class='bill_master'>";
	echo $table1;
echo "</div>";
echo '<hr/>';
?>
<div class="side_by_side" id="middle_div">
<?php
echo "<div class='bill_details' id='table2'>";
	echo $table2;
echo "</div>";

/*echo "<div class='bill_details'>";
	//echo $table3;
echo "</div>";*/

echo "<div id='final_table' id='table4'>";
	echo $table4;
echo "</div>";

?>
</div>
<?php
echo '<hr/>';
echo "<div id='due_bills'>";
	echo $table5;
echo "</div>";
	
?>
