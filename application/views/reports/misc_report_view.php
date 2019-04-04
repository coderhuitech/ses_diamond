<div id="outer-div">
	<h1>Daily Report</h1>
	<hr>
	Date from <input class="date" pattern="(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.](19|20)\d\d" type="date" id="date-from" value="<?php echo get_current_date();?>"/>to<input class="date" pattern="(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.](19|20)\d\d" value="<?php echo get_current_date();?>" type="date" title="Date upto" id="date-to"/>
	<div id="left-div">
		
		<ol>
			<li><span id="product-information" class="link">Product Information</span></li>
		</ul>
			
			
		
		
	</div>
	<div id="right-div">
		<div id="right-working-area">
			
		</div>
		<div id="result-div"  class="printable">
			
			<?php echo img(array('src'=>'img/printer.png','class'=>'no-print printer','height'=>'25px','border'=>'0','alt'=>'No Image'));?>
		</div>
		
	</div>
	
</div>