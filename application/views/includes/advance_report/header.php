<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <?php
	   	if (file_exists('organisation.xml')) {
	    $organisation = simplexml_load_file('organisation.xml');
		} else {
		    exit('Failed to open test.xml.');
		}
   	?>
	<?php echo '<link href="'.base_url().'assets/tree.png" rel="icon">';?>
    <title>Report</title>
	<link href="<?php echo base_url(); ?>/css/bootstrap-3.3.6-dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>/css/font-awesome-4.5.0/font-awesome-4.5.0/css/font-awesome.min.css" rel="stylesheet">
    
    <!-- Custom styles for this template -->
    <!--<link href="css/navbar.css" rel="stylesheet">-->
<?php
	if(isset($css)){
		foreach($css as $my_css){
				echo '<link href="'.base_url().'css/'.$my_css.'.css" rel="stylesheet" type="text/css">'.PHP_EOL;
		}
	}
;?>
	
<script src="<?php echo base_url(); ?>js/jquery-1.12.0.min.js" type="text/javascript"></script>

</head>

<body>
   	
    
  <?php //$this->load->view('includes/menu_advance_report.php');?>
      


