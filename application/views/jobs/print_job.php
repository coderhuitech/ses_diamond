<div id="outer-div">
	<div id="gap-div">
		
	</div>
	<a href="#" onclick="window.print(); return false;" class="noprint">Print this JOB</a>
	<?php //echo img(array('src'=>'img/products/'.$model.'.jpg','id'=>'model-image','height'=>'55px','width'=>'200px','border'=>'0','alt'=>'no Image'));?>
	<?php echo '</br>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<b>JOB-ID '.$job_id.'</b>';?>
	<?php echo '</br>Challan No.: '.$challan;?>
	<?php echo $date;?></br>
	<?php echo 'Model <b>'.$model.'</b> &nbsp&nbsp Pcs <b>'.$pcs.'</b> &nbsp&nbspSize <b>'.$size.'</b>';?>
	
	<?php echo $job_table;?>
	<label for="">Karigar : </label>
	<b><?php echo $karigar;?></b>
	<?php echo '</br>User: '.$user;?>
	<?php echo '</br>POS : '.$pos;?>
	 <a href="#" onclick="window.print(); return false;" class="noprint">Print</a>  
</div>