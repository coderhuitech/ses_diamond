<!DOCTYPE HTML>
<html>
<head class="noPrint">
<meta charset="utf-8">
<title>
	<?php 
		if(isset($page_title)){
			echo $page_title;
		}else{
			echo "No Title";
		}
	?>
</title>
<!-- style sheet -->
		<link href="<?php echo base_url();?>css/masterCSS.css" rel="stylesheet" type="text/css">		
        <link href="<?php echo base_url();?>css/menu.css" rel="stylesheet" type="text/css">	
        <link href="<?php echo base_url();?>css/general/classCSS.css" rel="stylesheet" type="text/css" media="all">
		<!--------------------------------------------------------------------------------------------->
		
		<script src="<?php echo base_url();?>JS/ui/jquery-1.8.3.js" type="text/javascript" ></script>
		<!--<script src="<?php echo base_url();?>JS/general/jquery.js" type="text/javascript" ></script>-->
 		<script src="<?php echo base_url();?>JS/general/menu.js" type="text/javascript" ></script>
		
		
		<!--<link href="<?php echo base_url();?>css/masterCSS.css" rel="stylesheet" type="text/css">	
        <link href="<?php echo base_url();?>css/menu.css" rel="stylesheet" type="text/css">	
		 <link href="<?php echo base_url();?>css/jquery-ui.css" rel="stylesheet" type="text/css">
		 <link href="<?php echo base_url();?>JS/themes/base/jquery.ui.all.css" rel="stylesheet" type="text/css">		
		<script src="<?php echo base_url();?>JS/ui/jquery.ui.core.js" type="text/javascript" ></script>
		<script src="<?php echo base_url();?>JS/ui/jquery.ui.widget.js" type="text/javascript"></script>
		<script src="<?php echo base_url();?>JS/ui/jquery.ui.mouse.js" type="text/javascript"></script>
		<script src="<?php echo base_url();?>JS/ui/jquery.ui.sortable.js" type="text/javascript"></script>
		<script src="<?php echo base_url();?>JS/ui/jquery.ui.accordion.js" type="text/javascript"></script>

		<script src="<?php echo base_url();?>JS/jquery.js" type="text/javascript" ></script>
		
		<script src="<?php echo base_url();?>JS/jquery-1.7.2.min.js" type="text/javascript"></script>
       	<script src="<?php echo base_url();?>JS/huiuiJquery.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>JS/jquery.min.js" type="text/javascript" ></script>
		<script src="<?php echo base_url();?>JS/jquery-ui.min.js" type="text/javascript" ></script>
		<script src="<?php echo base_url();?>JS/qtip.js" type="text/javascript" ></script>-->
		
		<?php
			if(isset($css)){
				foreach($css as $my_css){
					echo '<link href="'.base_url().'css/'.$my_css.'.css" rel="stylesheet" type="text/css">';
				}
			}
			if(isset($java_script)){
				foreach($java_script as $my_java_script){
					echo '<script src="'.base_url().'JS/'.$my_java_script.'.js" type="text/javascript"></script>';
				}
			}
		;?>
		
		<div id="copyright">Copyright &copy; 2012 <a href="http://ais.com/">www.ais.co.in</a></div>
</head>

<body>

<style type="text/css">
* { margin:0;
    padding:0;
}
body { background:rgb(74,81,85); }
div#menu { margin:5px auto; }
div#copyright {
    font:11px 'Trebuchet MS';
    color:#222;
    text-indent:30px;
    padding:40px 0 0 0;
}
div#copyright a { color:#eee; }
div#copyright a:hover { color:#222; }
</style>



<div id="container">
    	<div id="header">
  			<div id="hs1">
  				<!-- your logo here -->
            </div>
        	<div id="hs2">
        		<!--<h1>Balin & Company</h1>-->
        	</div>
        	<div id="menu">
				<ul class="menu">
					<li> <a class="parent"><span>Master</span></a>
				        <ul>
				            <li><a href=<?php echo base_url().'index.php/challan_controller/delivery_challan_facade';?>><span>Delivery Challan</span></a></li>
				            <li><a href=<?php echo base_url().'index.php/challan_controller/material_challan_facade';?>><span>Material Challan</span></a></li>
				            <li><a href=<?php echo base_url().'index.php/challan_controller/road_challan_facade';?>><span>Vendor</span></a></li>
				            </ul>
				        </li>
						
				        <li><a  class="parent"><span>Transaction</span></a>
				            <ul>
				                <li><a class="parent"><span>Product</span></a>
				                    <ul>
				                        <li><a href=<?php echo base_url().'index.php/consignees_controller/create_consignee_facade';?>><span>Product Inward</span></a></li>
				                        <li><a href=<?php echo base_url().'index.php/consignees_controller/alter_consignee_facade';?>><span>Alter Consignee</span></a></li>
				                    </ul>
				                </li>
				                <li><a href="#" class="parent"><span>Sub Item 2</span></a>
				                    <ul>
				                        <li><a href="#"><span>Sub Item 2.1</span></a></li>
				                        <li><a href="#"><span>Sub Item 2.2</span></a></li>
				                    </ul>
				                </li>
				                <li><a href="#"><span>Sub Item 3</span></a></li>
								
				                <li><a href="#"><span>Voucher</span></a>
									<ul>
										<li><a href=<?php echo base_url().'index.php/transaction_controller/receipt_facade';?>><span>Receipt</span></a></li>
									</ul>
								</li>
								
				                <li><a href="#"><span>Sub Item 5</span></a></li>
				                <li><a href="#"><span>Sub Item 6</span></a></li>
				                <li><a href="#"><span>Capital Introduce</span></a>
									 <ul>
				                        <li><a href=<?php echo base_url().'index.php/transaction_controller/capital_introduce_facade';?>><span>Gold Capital</span></a></li>
				                        <li><a href=<?php echo base_url().'index.php/transaction_controller/alter_consignee_facade';?>><span>Alter Consignee</span></a></li>
				                    </ul>
								</li>
				            </ul>
				        </li>
				        <li><a href="#"><span>Help</span></a></li>
				        <li class="last"><a href="#"><span>Contacts</span></a></li>
						<li class="last"><a href="<?php echo site_url(); ?>/login/staff_login_facade"><span>Login</span></a></li>
				    </ul>
				</div>
				
				
		</div>
        
        
       <!--	<div id="borders">    	</div>-->
      	<div id="contents">
      		
            	<div id="working_area">