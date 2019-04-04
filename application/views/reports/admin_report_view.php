<div id="outer-div">
	<h1>Daily Report</h1>
	<hr>
	Date from <input class="date" pattern="(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.](19|20)\d\d" type="date" id="date-from" value="<?php echo get_current_date();?>"/>to<input class="date" pattern="(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.](19|20)\d\d" value="<?php echo get_current_date();?>" type="date" title="Date upto" id="date-to"/>
	<div id="left-div">
		
		<ul>
			<li><span id="job-report" class="link">Job Report</span></li>
			<li><span id="material-submit" class="link">Material Submit</span></li>
			<li><span id="readymade-item-submit" class="link">Readymade Item Submit</span></li>
			<li><span id="show-business-status" class="link">Show Business Status</span></li>
			<li><span id="karigar-job-report" class="link">Karigar wise Job Report</span></li>
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