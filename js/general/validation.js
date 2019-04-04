$(function(){
	$('.gold-input').live('change', function (event) {
		if($(this).val().length==0){
			$(this).val('0');
			return;
		}
		if($(this).val().length<=2){
			return;
		}
		
		if($(this).val().indexOf('.')<0){
			var x=parseFloat($(this).val());
			$(this).val((x/1000).toFixed(3));
			return;
		}
		if($(this).val().indexOf('.')>0){
			var x=parseFloat($(this).val());
			x=x*1000;
			$(this).val((x/1000).toFixed(3));
			return;
		}
		
		
	});
});