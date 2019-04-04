var url=location.href;
var urlAux = url.split('/');

var base_url=urlAux[0]+'/'+urlAux[1]+'/'+urlAux[2]+'/'+urlAux[3]+'/'+urlAux[4];
var site_url=urlAux[0]+'/'+urlAux[1]+'/'+urlAux[2]+'/'+urlAux[3];
var img_url='/'+urlAux[3]+'/'+'img';

/*
window.huiui =
{
    //base_url : "http://localhost/bengali_bangle/index.php",
	base_url=urlAux[0]+'/'+urlAux[1]+'/'+urlAux[2]+'/'+urlAux[3]+'/'+urlAux[4],
   // site_url : "http://localhost/bengali_bangle"
	site_url : urlAux[0]+'/'+urlAux[1]+'/'+urlAux[2]+'/'+urlAux[3]
};*/
/*$.mynamespace = {};
	$.mynamespace.l = window.location;
	$.mynamespace.myVar2 = "somethingElse";*/
$(function(){
	//validate date
	$('.keyup_date').change(function(){
		$('span.error-keyup-5').remove();
    	var inputVal = $(this).val();
    	var dateReg = /^(([0-2]?\d{1})|([3][0,1]{1}))\/[0,1]?\d{1}\/(([1]{1}[9]{1}[9]{1}\d{1})|([2-9]{1}\d{3}))$/;
    	if(!dateReg.test(inputVal)) {
        	$(this).after('<span class="error error-keyup-5">Invalid date format.</span>');
    	}
	});
	
	// Numeric only control handler
	jQuery.fn.ForceNumericOnly =function(){
    	return this.each(function(){
        	$(this).keydown(function(e){
            	var key = e.charCode || e.keyCode || 0;
            	// allow backspace, tab, delete, arrows, numbers and keypad numbers ONLY
            	// home, end, period, and numpad decimal
            	return (key == 8 ||key == 9 ||key == 46 ||key == 110 ||key == 190 ||(key >= 35 && key <= 40)||(key >= 48 && key <= 57) ||(key >= 96 && key <= 105));
        	});
    	});
	};

	$('.integerOnly').keyup(function () { 
    	this.value = this.value.replace(/[^0-9]/g,'');
	});
	
	/*  The following code to make sam size label as per highest length*/
	jQuery.fn.autoWidth = function(options){
		var settings ={limitWidth   : false}
		if(options)	{
			jQuery.extend(settings, options);
		};
		var maxWidth = 0;
		this.each(function(){
				if ($(this).width() > maxWidth){
					if(settings.limitWidth && maxWidth >= settings.limitWidth){
						maxWidth = settings.limitWidth;
					} else{
						maxWidth = $(this).width();
					}
				}
		});
		this.width(maxWidth);
	}
	/* end of label width code */
	//$("label").autoWidth(); //this will make all the labels as highest width
	/*var l = window.location;
	var base_url = l.protocol + "//" + l.host + "/" + l.pathname.split('/')[1]+ "/" + l.pathname.split('/')[2];*/
	//alert(l.protocol);
	
	$('.properText').live("keyup", function(eventt) {
		var txt = $(this).val();

		// Regex taken from php.js (http://phpjs.org/functions/ucwords:569)
		$(this).val(txt.replace(/^(.)|\s(.)/g, function($1) {
			return $1.toUpperCase();
		}));
	});
	
	$('.keyup-numeric').keyup(function() {
    	$('span.error-keyup-1').hide();
    	var inputVal = $(this).val();
    	var numericReg = /^\d*[0-9](|.\d*[0-9]|,\d*[0-9])?$/;
   		if(!numericReg.test(inputVal)) {
        	$(this).after('<span class="error error-keyup-1">Numeric characters only.</span>');
			return;
    	}
	});
	
	$(".numericOnly").keypress(function(event) {
		//$('span.error-keyup-1').hide();
		// Backspace, tab, enter, end, home, left, right
		// We don't support the del key in Opera because del == . == 46.
		var controlKeys = [8, 9, 13, 35, 36, 37, 39,46];
		// IE doesn't support indexOf
		var isControlKey = controlKeys.join(",").match(new RegExp(event.which));
		// Some browsers just don't raise events for control keys. Easy.
		// e.g. Safari backspace.
		if(!event.which || // Control keys in most browsers. e.g. Firefox tab is 0
		(49 <= event.which && event.which <= 57) || // Always 1 through 9
		(48 == event.which && $(this).attr("value")) || // No 0 first digit
		isControlKey) {// Opera assigns values for control keys.
			return;
		} else {
			//$(this).after('<span class="error-keyup-1">Numeric characters only.</span>');
			$("#error_span").css("color", "white" );
			$("#error_span").css("background-color", "red" );
			$("#error_span").text("Not valid!").show().fadeOut(2000);
			event.preventDefault();
		}
	});
	$('#data-table').live().dataTable();
	$('#toggle').live('click',function(){
		$('#data-table').removeClass('dataTable');
	});
	//the following code will report any ajax error for developer
	$( document ).ajaxError(function(event, request, settings ) {
			$.msgBox({
					title: "Ajax Error",
					content: "Error requesting page " + settings.url,
					type: "info",
					showButtons: true,
					opacity: 0.9,
					timeOut: 1000,
					autoClose:false
			});
	});
	$("input:text").focus(function() { 
		$(this).select(); 
	} );
	// tooltip autoclose after a certain duration(du)
	var du = 2000;
	$( document ).tooltip({
		show: {
            effect: 'slideDown'
        },
        track: true,
        open: function (event, ui) {
            setTimeout(function () {
                //$(ui.tooltip).hide('explode');
                $(ui.tooltip).hide('slideDown');
            }, du);
        }
	});
	$("input:text").focusin(function(){
 		 $(this).css("background-color","#FFFFCC");
	});
	$("input:text").focusout(function(){
 		 $(this).css("background-color","#FFFFFF");
	});
	$('.properText').live("keyup", function(eventt) {
		var txt = $(this).val();

		// Regex taken from php.js (http://phpjs.org/functions/ucwords:569)
		$(this).val(txt.replace(/^(.)|\s(.)/g, function($1) {
			return $1.toUpperCase();
		}));
	});
	$("input:text:visible:first").focus();
});