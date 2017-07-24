function deleteCookies() {
	var allCookies = Cookies.get(); 
	jQuery.each( allCookies, function( key, value ) {
	  Cookies.set(key, 'false');
	  Cookies.remove(key); // remove cookie completely	  
 	 });
}
jQuery(document).ready(function() {
	jQuery('.deletecookies').click(function(){
	  deleteCookies();
	  });	
});