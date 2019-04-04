
<!--</div>-->
<!-- Placed at the end of the document so the pages load faster -->

	
<script src="<?php echo base_url_with_port() ;?>/bootstrap-3.3.6-dist/js/bootstrap.min.js" type="text/javascript"></script>
<?php
	if(isset($java_script)){
		foreach($java_script as $my_java_script){
				echo '<script src="'.base_url().'js/'.$my_java_script.'.js" type="text/javascript"></script>'.PHP_EOL;
		}
	}
;?>

<!--<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>-->
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<!--<script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>-->
</body>
</html>