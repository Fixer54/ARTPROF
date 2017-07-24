// JavaScript Document
jQuery(window).load(function() {
	jQuery(document).ready(function($){
		$('.fastgallery.brick-masonry').masonry({
			singleMode: true,
			itemSelector: '.fg-gallery-item'
		});
	});
});