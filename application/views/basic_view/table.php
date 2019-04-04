<style type="text/css">
	.user_co_details{
		text-align:left;
	}
	#pagination a{
		background-color:#c0c0c0;
		padding:4px 7px;
		text-decoration:none;
		border:solid 2px #800000;
		margin-right:2px;
		color:#0000ff;
		font-size:13px;
	}
	#pagination a:hover{
	border:solid 3px #666666;
	color:#808080;
}
</style>
	<!--  start page-heading -->
	<div id="page-heading">
		<!--<h1>Welcome SCANCN Cable Network </h1>-->
	</div>
	<!-- end page-heading -->

	<table border="0" width="100%" cellpadding="0" cellspacing="0" id="content-table">
	<tr>
		<th rowspan="3" class="sized"><img src="images/shared/side_shadowleft.jpg" width="20" height="300" alt="" /></th>
		<th class="topleft"></th>
		<td id="tbl-border-top">&nbsp;</td>
		<th class="topright"></th>
		<th rowspan="3" class="sized"><img src="images/shared/side_shadowright.jpg" width="20" height="300" alt="" /></th>
	</tr>
	<tr>
		<td id="tbl-border-left"></td>
		<td>
		<!--  start content-table-inner ...................................................................... START -->
		<div id="content-table-inner">
		
			
			<div id="table-content">
			
				<div class="user_co_details">
					<!--<h2><marquee behavior="alternate">Welcome To SCANCN Cable Area Network</marquee></h2>-->
					
				</div>
				<div>Sort by <a href="<?php echo site_url(); ?>/site_controller/members_area?id=445">Customer</a></div><hr />
				
				<?php if(isset($records)){
				
				foreach($records as $record): ?>
				  <div class="entry">
				    <!--<h1 style="float:left;"><?php echo $record->customer_name; ?></h1>
				    <p style="float:left;"><?php echo $record->customer_address; ?></p>-->
					<h4><?php echo $record->customer_name; ?></h4>
				    <p><?php echo $record->customer_address; ?></p>
					<p><?php echo $record->customer_mobile; ?></p>
					<hr />
				  </div>
				<?php endforeach;  
					
				} else "fdfd";	?>
		<div align="right">Develop By:<b> ACE INFO SOLUTION</b><br />Barrackpore, kol-700120,Contact No:9143656893
		</div>
				
			</div>
			<?php echo $this->pagination->create_links(); ?>
		 
		</div>
	
		</td>
		<td id="tbl-border-right"></td>
	</tr>
	
	<tr>
		<th class="sized bottomleft"></th>
		<td id="tbl-border-bottom">&nbsp;</td>
		<th class="sized bottomright"></th>
	</tr>
	</table>
	<div class="clear">&nbsp;</div>