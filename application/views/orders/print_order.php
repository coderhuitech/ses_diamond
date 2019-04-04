<div id=outer-div>
	<div id="company-info">
		<div id="company-anme"><?php echo $company_details->mailing_name; ?> </div>
		<div id="address">
			<?php 
			echo $company_details->address1; 
			echo '</br>';
			echo $company_details->address2;  
			?> 
		</div>
		<div id="contact">
			 	<?php echo img(array('src'=>'img/land_line.jpg','class'=>'no_print remove','height'=>'15px','border'=>'0','alt'=>'Remove')); ?>
				<?php echo $company_details->land1.' ';?>
				<?php echo $company_details->land2.' ';?>
				<?php echo img(array('src'=>'img/mobile.jpg','class'=>'no_print remove','height'=>'15px','border'=>'0','alt'=>'Remove')); ?>
				<?php echo $company_details->mobile.' ';?>
				<?php echo img(array('src'=>'img/email.jpg','class'=>'no_print remove','height'=>'15px','border'=>'0','alt'=>'Remove')); ?>
				<?php echo $company_details->email.' ';?>
		</div>
	</div>
	<hr />
	<div id="order-master-div">
		<?php echo $order_master; ?>
	</div>
	<div id="order-details-div">
		<?php echo $order_details; ?>
	</div>
</div>