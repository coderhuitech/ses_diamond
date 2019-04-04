<div class="nav-outer-repeat"> 
<!--  start nav-outer -->
<div class="nav-outer"> 
<!-- start nav-right -->
		<div id="nav-right">
		
			<div class="nav-divider">&nbsp;</div>
			<div class="showhide-account"><img src="<?php echo base_url();?>images/shared/nav/nav_myaccount.gif" width="93" height="14" alt="" /></div>
			<div class="nav-divider">&nbsp;</div>
			<!--<a href="<?php echo site_url(); ?>" id="logout"><img src="<?php echo base_url();?>images/shared/nav/nav_logout.gif" width="64" height="14" alt="" /></a>-->
			<span id="logout"> <img src="<?php echo base_url();?>images/shared/nav/nav_logout.gif" width="64" height="14" alt="" /></span>
			<div class="clear">&nbsp;</div>
		
			<!--  start account-content -->	
			<div class="account-content">
			<div class="account-drop-inner">
				<a href="" id="acc-settings">Settings</a>
				<div class="clear">&nbsp;</div>
				<div class="acc-line">&nbsp;</div>
				<a href="" id="acc-details">Personal details </a>
				<div class="clear">&nbsp;</div>
				<div class="acc-line">&nbsp;</div>
				<a href="" id="acc-project">Project details</a>
				<div class="clear">&nbsp;</div>
				<div class="acc-line">&nbsp;</div>
				<a href="" id="acc-inbox">Inbox</a>
				<div class="clear">&nbsp;</div>
				<div class="acc-line">&nbsp;</div>
				<a href="" id="acc-stats">Statistics</a> 
			</div>
			</div>
			<!--  end account-content -->
		
		</div>
<!-- end nav-right -->
<!--  start nav -->
		<div class="nav">
		<div class="table" id="menu-div">
		
		<ul class="select"><li><a href="#nogo"><b>Master</b><!--[if IE 7]><!--></a><!--<![endif]-->
		<!--[if lte IE 6]><table><tr><td><![endif]-->
			<div class="select_sub">
				<ul class="sub">
					<li><a href=<?php echo base_url().'index.php/customer_controller/customer_master_facade';?>><span>Customer</span></a></li>
					<li><a href=<?php echo base_url().'index.php/material_controller/customer_master_facade';?>><span>Materials</span></a></li>
					<li><a href=<?php echo base_url().'index.php/product_controller/product_master_facade';?>><span>Products</span></a></li>
					<li><a href=<?php echo base_url().'index.php/user_controller/user_master_facade';?>><span>User</span></a></li>
				</ul>
			</div>
		<!--[if lte IE 6]></td></tr></table></a><![endif]-->
		</li>
		</ul>
		
		<ul class="select"><li><a href="#nogo"><b>Order</b><!--[if IE 7]><!--></a><!--<![endif]-->
		<!--[if lte IE 6]><table><tr><td><![endif]-->
			<div class="select_sub">
				<ul class="sub">
					<li><a href=<?php echo base_url().'index.php/order_controller/new_order_facade';?>><span>New Order</span></a></li>
				    <li><a href=<?php echo base_url().'index.php/material_controller/material_inward_facade';?>><span>Material Inward</span></a></li>
					<li><a href=<?php echo base_url().'index.php/item_distribution_controller/undistributed_items_facade';?>><span>Distribute Inward</span></a></li>
				    <li><a href=<?php echo base_url().'index.php/order_controller/edit_order_by_order_no_facade';?>><span>Edit Order</span></a></li>
				    <li><a href=<?php echo base_url().'index.php/order_controller/misc_order_facade';?>><span>Misc</span></a></li>
					
				</ul>
			</div>
		<!--[if lte IE 6]></td></tr></table></a><![endif]-->
		</li>
		</ul>
		
		<div class="nav-divider">&nbsp;</div>
		                    
		<ul class="current"><li><a href="#nogo"><b>JOB</b><!--[if IE 7]><!--></a><!--<![endif]-->
		<!--[if lte IE 6]><table><tr><td><![endif]-->
		<div class="select_sub show">
			<ul class="sub">
				 <li><a href=<?php echo base_url().'index.php/job_controller/new_job_facade';?>><span>New Job</span></a></li>
				 <li><a href=<?php echo base_url().'index.php/job_controller/job_phase1_facade';?>><span>Phase I</span></a></li>
				 <li><a href=<?php echo base_url().'index.php/job_controller/job_pan_phase_facade';?>><span>Phase PAN</span></a></li>
				 <li><a href=<?php echo base_url().'index.php/job_controller/job_phaseII_facade';?>><span>Phase II</span></a></li>
				 <li><a href=<?php echo base_url().'index.php/job_controller/job_finish_facade';?>><span>JOB Finish</span></a></li>
				 <li><a href=<?php echo base_url().'index.php/job_controller/create_bill_facade';?>><span>Create Bill</span></a></li>
				 <li><a href=<?php echo base_url().'index.php/job_controller/add_topup_pan_facade';?>><span>TOPUP PAN</span></a></li>
				 <li><a href=<?php echo base_url().'index.php/job_controller/pan_return_facade';?>><span>PAN Return</span></a></li>
				 <li><a href=<?php echo base_url().'index.php/job_controller/misc_job_facade';?>><span>Misc</span></a></li>
			</ul>
		</div>
		<!--[if lte IE 6]></td></tr></table></a><![endif]-->
		</li>
		</ul>
		
		<div class="nav-divider">&nbsp;</div>
		
		<ul class="select"><li><a href="#nogo"><b>Material</b><!--[if IE 7]><!--></a><!--<![endif]-->
		<!--[if lte IE 6]><table><tr><td><![endif]-->
		<div class="select_sub">
			<ul class="sub">
				<li><a href=<?php echo base_url().'index.php/material_controller/owner_to_employee_facade';?>><span>Inward Material</span></a></li>
				<li><a href=<?php echo base_url().'index.php/material_controller/material_transfer_facade';?>><span>Transfer</span></a></li>
				<li><a href=<?php echo base_url().'index.php/material_controller/material_loss_facade';?>><span>Material Loss</span></a></li>
				<li><a href=<?php echo base_url().'index.php/material_controller/miscellaneous_facade';?>><span>Miscellaneous</span></a></li>
				<!--<li><a href="#nogo">Categories Details 2</a></li>
				<li><a href="#nogo">Categories Details 3</a></li>-->
			</ul>
		</div>
		<!--[if lte IE 6]></td></tr></table></a><![endif]-->
		</li>
		</ul>
		
		<div class="nav-divider">&nbsp;</div>
		
		<ul class="select"><li><a href="#nogo"><b>Report</b><!--[if IE 7]><!--></a><!--<![endif]-->
		<!--[if lte IE 6]><table><tr><td><![endif]-->
		<div class="select_sub">
			<ul class="sub">
				<li><a href=<?php echo base_url().'index.php/bill_controller/show_duplicate_bill_facade';?>><span>Show Bills</span></a></li>
				<li><a href=<?php echo base_url().'index.php/order_controller/order_report_facade';?>><span>Print Order</span></a></li>
				<li><a href=<?php echo base_url().'index.php/job_controller/job_report_facade';?>><span>Job Details</span></a></li>
				<li><a href=<?php echo base_url().'index.php/report_controller/customer_report_facade';?>><span>Customers</span></a></li>
				<li><a href=<?php echo base_url().'index.php/report_controller/staff_report_facade';?>><span>Staff</span></a></li>
				<li><a href=<?php echo base_url().'index.php/report_controller/daily_report_facade';?>><span>Daily</span></a></li>
				<li><a href=<?php echo base_url().'index.php/report_controller/misc_report_facade';?>><span>Misc</span></a></li>
				<li><a href=<?php echo base_url().'index.php/report_controller/admin_report_facade';?>><span>Admin</span></a></li>
			</ul>
		</div>
		<!--[if lte IE 6]></td></tr></table></a><![endif]-->
		</li>
		</ul>
		
		<div class="nav-divider">&nbsp;</div>
		
		<ul class="select"><li><a href="#nogo"><b>Transaction</b><!--[if IE 7]><!--></a><!--<![endif]-->
		<!--[if lte IE 6]><table><tr><td><![endif]-->
		<div class="select_sub">
			<ul class="sub">
			<!--	<li><a href=<?php echo base_url().'index.php/Cash_inward_controller/cash_receipt_facade';?>><span>Cash Receipt</span></a></li>
				<li><a href=<?php echo base_url().'index.php/Gold_inward_controller/gold_receipt_facade';?>><span>Gold Receipt</span></a></li>-->
				<li><a href=<?php echo base_url().'index.php/cash_refund_controller/cash_refund_facade';?>><span>Cash Refund</span></a></li>
				<li><a href=<?php echo base_url().'index.php/new_transaction_controller/new_transaction_facade';?>><span>New Transaction</span></a></li>
			</ul>
		</div>
		<!--[if lte IE 6]></td></tr></table></a><![endif]-->
		</li>
		</ul>
		
		<div class="clear"></div>
		</div>
		<div class="clear"></div>
		</div>
		<!--  start nav -->
</div>
<div class="clear"></div>
<!--  start nav-outer -->
</div>
