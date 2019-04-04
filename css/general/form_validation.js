$(function() {	
	//proper text
	$('.properText').live("keyup", function(evt) {
	var txt = $(this).val();

		// Regex taken from php.js (http://phpjs.org/functions/ucwords:569)
		$(this).val(txt.replace(/^(.)|\s(.)/g, function($1) {
			return $1.toUpperCase();
		}));
	});
	
	//-----------------------------------------------
	$('form#info .slider label').each(function(){
	var labelColor = '#999';
	var restingPosition = '5px';

	// style the label with JS for progressive enhancement
	$(this).css({
		'color' : labelColor,
		 	'position' : 'absolute',
	 		'top' : '6px',
			'left' : restingPosition,
			'display' : 'inline',
    		        'z-index' : '99'
	});

	var inputval = $(this).next().val();

	// grab the label width, then add 5 pixels to it
	var labelwidth = $(this).width();
	var labelmove = labelwidth + 5 +'px';

	// onload, check if a field is filled out, if so, move the label out of the way
	if(inputval !== ''){
		$(this).stop().animate({ 'left':'-'+labelmove }, 1);
	}    	

	// if the input is empty on focus move the label to the left
	// if it's empty on blur, move it back
	$('input, textarea').focus(function(){
		var label = $(this).prev('label');
		var width = $(label).width();
		var adjust = width + 5 + 'px';
		var value = $(this).val();

		if(value == ''){
			label.stop().animate({ 'left':'-'+adjust }, 'fast');
		} else {
			label.css({ 'left':'-'+adjust });
		}
	}).blur(function(){
		var label = $(this).prev('label');
		var value = $(this).val();

		if(value == ''){
			label.stop().animate({ 'left':restingPosition }, 'fast');
		}	

	});
	}); // End "each" statement
	
});