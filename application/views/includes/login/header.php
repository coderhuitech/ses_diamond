<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Internet Dreams</title>
<link rel="stylesheet" href="<?php echo base_url();?>css/screen.css" type="text/css" media="all" title="default" />
<!--  jquery core -->
<script src="<?php echo base_url();?>js/jquery-1.8.1.js" type="text/javascript"></script>
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

<!-- Custom jquery scripts -->

<script src="js/jquery/custom_jquery.js" type="text/javascript"></script>
<!-- MUST BE THE LAST SCRIPT IN <HEAD></HEAD></HEAD> png fix -->
<!--<script src="js/jquery/jquery.pngFix.pack.js" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$(document).pngFix( );
	});
</script>-->
</head>
<!--start of body-->
<body id="login-bg"> 
