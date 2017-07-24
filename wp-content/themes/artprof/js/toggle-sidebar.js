jQuery(document).ready(function() {
	
    jQuery("#nav-bttn").click(function(e) {
		var thisTarget = jQuery(this);
        e.preventDefault();
        jQuery(thisTarget).toggleClass("active");
        jQuery("#wrapper").toggleClass("toggled");
		if ( jQuery("#wrapper").hasClass('toggled') ) {
	  	  // Cookies.set('sidebarClose', 'true');
		} else {
  	  	  // Cookies.set('sidebarClose', 'false');
		}		
    });	
	
	var isBreakPoint = function (bp) {
	    var bps = [0, 960, 3000],
	        w = jQuery(window).width(),
	        min, max
	    for (var i = 0, l = bps.length; i < l; i++) {
	      if (bps[i] === bp) {
	        min = bps[i-1] || 0
	        max = bps[i]
	        break
	      }
	    }
	    return w > min && w <= max
	}

	function menuCollapse() {
	    if (isBreakPoint(960)) { // Breakpoint between 0 and 960
	        jQuery("#wrapper").addClass("toggled"); //close sidebar by default on mobile
	        jQuery("#nav-bttn").removeClass("active");
		} else {
			// var sidebarClosed = Cookies.get('sidebarClose');
			// if ( sidebarClosed === 'true') {
	// 			jQuery("#wrapper").addClass('toggled');
	// 	        jQuery("#nav-bttn").removeClass("active");
	// 		} else {
	// 			jQuery("#wrapper").removeClass('toggled');
	// 	        jQuery("#nav-bttn").addClass("active");
	// 		}
		}
	}
	menuCollapse();
	
	jQuery(window).resize(function() {
		menuCollapse();
	});
	
	
    jQuery("#page-content-wrapper").click(function(e) {
	    if (isBreakPoint(960)) { // Breakpoint between 0 and 960
			if ( !jQuery("#wrapper").hasClass('toggled') ) {
				jQuery("#wrapper").addClass('toggled');
			}
		}
    });	

});

