<script>
	$(function() {
		$( document ).tooltip();		//to enable tooltip
	});
</script>

<style type="text/css">
	.hiddenDiv{
		display: none;
	}
</style>

<div id="outer_div">
	<h3>Gold Introduction Area</h1>
	<form action='' id="gold_introduction_form" method="post">
	<div id="area1">
		<div id="container1">
			<label for="gold_received">Gold Received</label>
			<input name="gold_received" type="text" id="received_gold" class="numericOnly" title="Your Gold" placeholder="Gold quantity"/>
			<label for="gold_received">Received from</label>
			<input name="received_from" type="text" id="received_from" class="properText" title="Enter the source name" placeholder="Source"/>
			<textarea name="comment" id="comment" placeholder="Comment Here"></textarea>
			<br><span id="error_span"></span>
		</div>
		
		<input type="submit" value="Submit Gold" id="submit_gold""/>
	</div>
	</<form>
	<div id="result_div" class="hiddenDiv"></div>
	<div id="messageBox" class="hiddenDiv" title="Basic modal dialog"></div>
</div>