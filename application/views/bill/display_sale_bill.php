<div id="outer-div">
	<div class="organisation_name"><?php echo form_textarea('company',$company_details->mailing_name,'id="mailing-name"'); ?></div>
	<!--<div class="organisation_name"><?php echo $company_details->mailing_name; ?></div>-->
	<h3 class="slogan">সোনার পাত বসান ব্রোঞ্জ চুড়ি</h3>
	<!--<h3 class="slogan"><?php echo utf8_encode($company_details->caption); ?></h3>-->
	<h3 class="address"><?php echo $company_details->address1; ?>, <?php echo $company_details->address2; ?></h3>
	<h3 class="contact">contact: <?php echo $company_details->land1 ;?>, Mob: <?php echo $company_details->mobile;?>/9836444999, email: <?php echo $company_details->email;?>, visit: <?php echo $company_details->website;?></h3>
	<h4 class="declaration">Received the following materials along with design in good condition for manufacturing of Bangles against labour charges only</h4>
	<hr />
	<div id="table1">
		<?php echo $table1;?>
	</div>
	<hr />
	<div id="table2">
		<?php echo $table2;?>
	</div>
	</br>
	<div id="amount_in_word">
		<b>Amount in word :</b> Rupees <?php echo $lc_in_word; ?> only
	</div>
	<div id="declaration-div">
		<?php echo $table3; ?>
	</div>

	<div id="customer-dues-div">
		<?php echo $customer_due_table;?>
	</div>
	<div>
		<p>শর্তাবলী - আপনার যাবতীয় লেনদেন ও দেনাপাওনা সম্পূর্ণভাবে বিবেকানন্দ ঘোষের সাথে সম্পর্কযুক্ত।</p>
	</div>
	<div id="bill-footer">
		<?php
			echo form_textarea('footer',$bill_footer,'id="bill-footer"');
		?>
	</div>
</div>