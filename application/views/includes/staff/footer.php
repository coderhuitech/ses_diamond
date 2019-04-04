</div>
<!--  end content -->
<div class="clear">&nbsp;</div>
</div>
<div class="clear">&nbsp;</div>
    
<!-- start footer -->         
<div id="footer">
	<!--  start footer-left -->
	<div id="footer-left">
	HUIUI &copy; Copyright Srikrishna Bangle Jewellery Workshop. <a href="">www.bengalibangle.co.in</a>. All rights reserved.</div>
	<!--  end footer-left -->
	<div class="clear">&nbsp;</div>
</div>
<!-- end footer -->
 
</body>
<script type="text/javascript">
	var url=location.href;
	var urlAux = url.split('/');

	var base_url=urlAux[0]+'/'+urlAux[1]+'/'+urlAux[2]+'/'+urlAux[3]+'/'+urlAux[4];
	var site_url=urlAux[0]+'/'+urlAux[1]+'/'+urlAux[2]+'/'+urlAux[3];
	var img_url='/'+urlAux[3]+'/'+'img';
	
	$(function(){
		$('#logout').live('click',function(){
				var request=$.ajax({
						type:'get',
						url: base_url+"/report_controller/save_business_status_report",
						data: {},//end of data
						beforeSend:function(){},
						success: function(data, textStatus, xhr) {
							var obj = $.parseJSON(data)[0];
							alert(obj['msg']);
							if(obj['success']==1){
								window.location=base_url;
							}else{
								return;
							}
						}//end of success
					});//end of post request
		});
	});
</script>
<style type="text/css">
	#logout{
		cursor: pointer;
	}
</style>
</html>