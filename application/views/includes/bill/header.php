<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $page_title ?></title>
<!-- style sheet -->
		<!--<link href="<?php echo base_url();?>css/bill_print_style.css" rel="stylesheet" type="text/css" media="print">
		<link href="<?php echo base_url();?>css/general/classCSS.css" rel="stylesheet" type="text/css" media="all">-->
		<link href="<?php echo base_url();?>css/general/classCSS.css" rel="stylesheet" type="text/css" media="all">
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
		?>
</head>

<body>
<div id="bill_container">
	<div id="header">
				
	</div>
	<div id="bill_area">

    	