 jQuery(document).ready(function ($) {

	$(".vcmp-video-thumb").click(function() {
	  $('.vcmp-video-thumb > img').removeClass("active");
	  $(this).children('img').addClass("active");
	});

	$('div.vcmp-video-thumb').click(function() {
	  $('.vcmp-video-iframe iframe').attr('src', ($(this).children('iframe').attr('src').replace('iframe')));
	});
	
	$(".vcmp-video-thumb").click(function(){
		$('body').scrollTo($('.vcmp-video-wrapper'), 1000, {offset: -110});
	});
});
