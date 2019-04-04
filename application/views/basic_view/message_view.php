<div id="items_list_div" class="printable" style="width:642px;font:16px/26px Georgia, Garamond, Serif;">
<?php
if (isset($message)) {
	foreach ($message as $msg) {
		echo $msg;
		echo '<br>';
	}
}


?>
</div>
<input type="button" value="Print" id="printBtn" />