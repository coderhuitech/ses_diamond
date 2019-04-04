<!DOCTYPE HTML>
<html>
<head>
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
<style type="text/css">
	@media print {
		.noprint {display:none;}
		.noshow {display:none;}
	}
</style>
<!-- style sheet -->
		<link href="<?php echo base_url();?>css/jobs/jobMasterCSS.css" rel="stylesheet" type="text/css">
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
</head>

<body>

<div id="container">
    	<div id="header">
  			<div id="hs1">
  				<!-- your logo here -->
            </div>
        	<div id="hs2">
        		<!--<h1>Balin & Company</h1>-->
        	</div>
		</div>
        
        
       <!--	<div id="borders">    	</div>-->
      	<div id="contents">
      		
            	<div id="working_area">