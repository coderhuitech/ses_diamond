<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Diamond Project</title>
<link rel="stylesheet" href="<?php echo base_url();?>css/screen.css" type="text/css" media="screen" title="default" />
<script src="<?php echo base_url();?>js/jquery-1.8.1.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>js/date/datetime.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>js/menu.js" type="text/javascript"></script>
	<!--CSS-->
<?php
	if(isset($css)){
		foreach($css as $my_css){
				echo '<link href="'.base_url().'css/'.$my_css.'.css" rel="stylesheet" type="text/css">'.PHP_EOL;
		}
	}
?>
	<!--jquery-->
<?php
	if(isset($java_script)){
		foreach($java_script as $my_java_script){
				echo '<script src="'.base_url().'JS/'.$my_java_script.'.js" type="text/javascript"></script>'.PHP_EOL;
		}
	}
;?>
 

</head>
<body> 
<!-- Start: page-top-outer -->
<div id="page-top-outer">    

<!-- Start: page-top -->
<div id="page-top">

	<!-- start logo -->
	<!--<div id="logo">
	<a href=""><img src="<?php echo base_url();?>images/shared/logo.png" width="156" height="40" alt="" /></a>
	</div>-->
	<!--<div>
		<?php $user_picture='img/users/'.$this->session->userdata('user_id').'.jpg';?>
		<?php echo img(array('src'=>$user_picture,'id'=>'search','class'=>'no_print remove','height'=>'80px','border'=>'1','alt'=>'Remove'));?>
	</div>-->
	<!-- end logo -->
	
	<!--  start top-search -->
	<div id="top-search">
		<table border="0" cellpadding="0" cellspacing="0">
		<tr>
		<!--
		<td><input type="text" value="Search" onblur="if (this.value=='') { this.value='Search'; }" onfocus="if (this.value=='Search') { this.value=''; }" class="top-search-inp" /></td>
		
		<td>
		
		<select  class="styledselect">
			<option value="">All</option>
			<option value="">Products</option>
			<option value="">Categories</option>
			<option value="">Clients</option>
			<option value="">News</option>
		</select> 
		 
		</td>
		
		<td>
		<input type="image" src="images/shared/top_search_btn.gif"  />
		</td>
		 -->
		<?php $user_picture='img/users/'.$this->session->userdata('user_id').'.jpg';?>
		<td><?php echo img(array('src'=>$user_picture,'id'=>'user_picture','height'=>'40px','border'=>'1','alt'=>'Remove'));?></td>
		<td><span id="global-user-id"><?php echo $this->session->userdata('employee_name').' at '.$this->db->query('select * from company_details')->row()->pos.' of '.$this->db->query('select * from company_details')->row()->company_name; ?></span></td>
		
		</tr>
		</table>
	</div>
 	<!--  end top-search -->
 	<div class="clear"></div>

</div>
<!-- End: page-top -->

</div>
<!-- End: page-top-outer -->
	
<div class="clear">&nbsp;</div>
 
<!--  start nav-outer-repeat................................................................................................. START -->

		<!--menu is here from menu.php-->
		<?php $this->load->view('includes/menu.php');?>
		<!--menu end-->

<!--  start nav-outer-repeat................................................... END -->
 
 <div class="clear"></div>
 
<!-- start content-outer -->
<div id="content-outer">
<!-- start content -->
<div id="content">