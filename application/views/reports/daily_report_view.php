<div id="outer-div">
	<h1>Daily Report</h1>
	<hr>
	Date from <input class="date" pattern="(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.](19|20)\d\d" type="" id="date-from" value="<?php echo get_current_date();?>"/>to<input class="date" pattern="(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.](19|20)\d\d" value="<?php echo get_current_date();?>" type="" title="Date upto" id="date-to"/>
	<div id="left-div">
		
		<ol>
			<li><span id="daily-order" class="link">Daily Order</span></li>
			<li><span id="daily-inventory" class="link">Daily Inventory </span></li>
			<li><span id="daily-mathakata" class="link">Daily Mathakata and Nitrick </span></li>
			<li><span id="daily-bill" class="link">Daily Bills </span></li>
			<li><span id="job-report" class="link">Daily Jobs </span></li>
			<li><span id="job-report" class="link">Daily Jobs </span></li>
			<li><span id="fine-to-gini" class="link">Fine to Gini </span></li>
			<li><span id="business-report" class="link">Business Status now </span></li>
			<li><span id="save-business-report" class="link">Save Business Report </span></li>
			<li><span id="agent-wise-due-report" class="link">Show agent Wise Dues</span></li>
		</ul>
			
			
		
		
	</div>
	<div id="right-div">
		<!--<div id="result-div" class="printable">-->
		<div id="result-div"  class="printable">
			
		</div>
		 <h2>
				&nbsp;&nbsp;&nbsp;<a href="#" id="hrefPrint">Print Report</a>
		</h2>
	</div>
</div>