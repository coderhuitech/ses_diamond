<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>

</title>
<!-- style sheet -->
	
		<!--------------------------------------------------------------------------------------------->
		
		<!--<script src="<?php echo base_url();?>JS/ui/jquery-1.8.3.js" type="text/javascript" ></script>-->
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
		;?>
		
		<div id="copyright">Copyright &copy; 2012 <a href="http://ais.com/">www.ais.co.in</a></div>
</head>

<body>

    
        