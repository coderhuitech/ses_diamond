$(function(){
	$('.select').live('click',function(){
		$('.current').addClass('select');
		$('.current').removeClass('current');
		$(this).addClass('current');
		$(this).closest('menu-div').find('show').removeClass('show');
		$(this).find('div').addClass('show');
		$(this).removeClass('select');
		/*$('#menu-div .current').addClass('select');
		$('#menu-div .current .select_sub').addClass('show');
		$('#menu-div .current').removeClass('current');
		$(this).addClass('current');
		$('.show').removeClass('show')*/
	});
});