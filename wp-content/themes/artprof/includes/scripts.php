<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<script src="/wp-content/themes/artprof/js/toggle-sidebar.js"></script>
<script src="/wp-content/themes/artprof/js/jquery.cubeportfolio.js"></script>

<?php if (is_front_page()) { ?>	
	
	<script>
	
	function setDataval(thisTarget) {
		var thisItem = thisTarget;
		var classNames = jQuery(thisItem).attr("class").match(/[\w%]*dataval-[\w%]*/g);  //include % symbol
		var singleClassname = classNames[0];
		var cleanClassname = singleClassname.replace("dataval-","");
		jQuery(thisItem).attr("data-value",cleanClassname);	
	}
	
    jQuery(function(){
		// field_7
		jQuery(document).on("click",".vc_tta-panel#experience .wpb_column *", function(e) {
			e.preventDefault();
			var thisParentCol = jQuery(this).closest(".wpb_column");
			setDataval(thisParentCol);
			if(thisParentCol.hasClass("selected")) { jQuery(thisParentCol).removeClass("selected");} else {
				jQuery(".vc_tta-panel#experience .wpb_column.selected").each(function() { jQuery(this).removeClass("selected"); });
				jQuery(thisParentCol).toggleClass("selected");				
			}
		});
		// field_11
		jQuery(document).on("click",".vc_tta-panel#composition .wpb_column *", function(e) {
			e.preventDefault();
			var thisParentCol = jQuery(this).closest(".wpb_column");
			setDataval(thisParentCol);
			if(thisParentCol.hasClass("selected")) { jQuery(thisParentCol).removeClass("selected");} else {
				jQuery(thisParentCol).toggleClass("selected");				
			}
		});	
		// field_18
		jQuery(document).on("click",".vc_tta-panel#interests .wpb_column *", function(e) {
			e.preventDefault();
			var thisParentCol = jQuery(this).closest(".wpb_column");
			setDataval(thisParentCol);
			if(thisParentCol.hasClass("selected")) { jQuery(thisParentCol).removeClass("selected");} else {
				jQuery(thisParentCol).toggleClass("selected");				
			}
		});	
		// field_28
		jQuery(document).on("click",".vc_tta-panel#topics .wpb_column *", function(e) {
			e.preventDefault();
			var thisParentCol = jQuery(this).closest(".wpb_column");
			setDataval(thisParentCol);
			if(thisParentCol.hasClass("selected")) { jQuery(thisParentCol).removeClass("selected");} else {
				jQuery(thisParentCol).toggleClass("selected");				
			}
		});	
		// field_34
		jQuery(document).on("click",".vc_tta-panel#formats .wpb_column *", function(e) {
			e.preventDefault();
			var thisParentCol = jQuery(this).closest(".wpb_column");
			setDataval(thisParentCol);
			if(thisParentCol.hasClass("selected")) { jQuery(thisParentCol).removeClass("selected");} else {
				jQuery(thisParentCol).toggleClass("selected");				
			}
		});	
		
		//check which fields are selected when user clicks submit, build URL string
		jQuery(document).on("click",".create-profile button", function(e) {
			e.preventDefault();
			var urlstring = '?';
			jQuery(".wpb_column.selected").each(function() {
				var parentID = jQuery(this).closest(".vc_tta-panel").attr("ID");
				var thisVal = jQuery(this).attr("data-value"); 
				if (parentID === "experience") { thisField = "field_7" };
				if (parentID === "composition") { thisField = "field_11" };
				if (parentID === "interests") { thisField = "field_18" };
				if (parentID === "topics") { thisField = "field_28" };
				if (parentID === "formats") { thisField = "field_34" };
				urlstring += '&' + thisField + "=" + thisVal;
			});
			window.location.replace("/register" + urlstring);
		});

	});	
	</script>
	<?php }?>


<?php if (bp_is_user() || is_page('register')) { ?>	
	<script>
    jQuery(function(){		
		var i = document.location.href.lastIndexOf('?');
		var experience = document.location.href.substr(i+1).replace(/field_7=/g,'').replace(/%20/g,' ').split('&');
		jQuery('input[name="field_7"]').prop('checked',function(){
		     return jQuery.inArray(this.value.toLowerCase(),experience) !== -1;
		});			
		var mediainterests = document.location.href.substr(i+1).replace(/field_11=/g,'').replace(/%20/g,' ').split('&');
		jQuery('input[name="field_11[]"]').prop('checked',function(){
		     return jQuery.inArray(this.value.toLowerCase(),mediainterests) !== -1;
		});
		var imageryinterests = document.location.href.substr(i+1).replace(/field_18=/g,'').replace(/%20/g,' ').split('&');
		jQuery('input[name="field_18[]"]').prop('checked',function(){
		     return jQuery.inArray(this.value.toLowerCase(),imageryinterests) !== -1;
		});
		var topics = document.location.href.substr(i+1).replace(/field_28=/g,'').replace(/%20/g,' ').split('&');
		jQuery('input[name="field_28[]"]').prop('checked',function(){
		     return jQuery.inArray(this.value.toLowerCase(),topics) !== -1;
		});
		var formats = document.location.href.substr(i+1).replace(/field_34=/g,'').replace(/%20/g,' ').split('&');
		jQuery('input[name="field_34[]"]').prop('checked',function(){
		     return jQuery.inArray(this.value.toLowerCase(),formats) !== -1;
		});
	});	
	</script>
	<?php }?>

<?php if (bp_is_user() || is_page('edit')) { ?>	
	<script>
    jQuery(function(){		
  		  var all = jQuery("input[checked=checked]");
		  jQuery(all).each(function() {
			 // jQuery(this).trigger('click'); 
			  jQuery(this).attr('checked', true); 
		  });			
	});	
	</script>
	<?php }?>


<?php if (is_page('Artwork Submission Form')) { ?>	
	<script>
    jQuery(function(){		
		var i = document.location.href.lastIndexOf('?');
		var refcourse = document.location.href.substr(i+1).replace(/course_ref=/g,'').replace(/%20/g,' ');
		jQuery('input[name="course_ref"]').val(refcourse);
	});	
	</script>
	<?php }?>


<script>

	jQuery(function(){
	
	// 	jQuery.fn.focusWithoutScrolling = function(){
	// 	  var x = jQuery(document).scrollLeft(), y = jQuery(document).scrollTop();
	// 	  this.focus();
	// 	  window.scrollTo(x, y);
	// 	  return this; //chainability
	// 	};
	//
	// function setFocus() {
	// 		// jQuery("form:not(#commentform)").first().find(":input:not(input[type=button],input[type=submit],button):visible:first").focus();
	// 		jQuery("form:not(#commentform)").first().find(":input:not(input[type=button],input[type=submit],button):visible:first").focusWithoutScrolling()
	// }
	// setFocus();
	
	   jQuery('#preload').fadeIn();
	   jQuery('#ajax-loader').hide();

	function filterDropdowns() {
	  	jQuery(".filter-group").each(function() {
	  		var thisID = jQuery(this).attr("ID");
			var thisDiv = jQuery("div#" + thisID);
				  		jQuery("<select class='filter option-set' data-filter-group='subject' />").appendTo(thisDiv);
			var thisSelect = jQuery(thisDiv).find("select");
			var thisLinks = jQuery(thisDiv).find(".cbp-filter-item");

			  jQuery(thisLinks).each(function() {
			  	 var el = jQuery(this);
			   	 jQuery("<option />", {
			       	"value"   : el.attr("data-filter"),
			      	"data-filter"   : el.attr("data-filter"),
			       	"text"    : el.text()
			   	 }).appendTo(thisSelect);
			  }); // end of each link

				  		    jQuery(thisSelect).change(function() {
				  		    	var thisTarget = jQuery(this);
				  		    	var group = jQuery(thisTarget).attr('data-filter-group');
				  		    	var filters = [];
				  		    	var selectedFilters = [];
				  		    	filters[ group ] = jQuery(thisTarget).find(':selected').attr('data-filter');
				  		    	jQuery(".filter-group:not(#" + thisID + ") select").each(function() {
					var getSelected = jQuery(this).find(':selected').attr('data-filter');
					if(!getSelected) {
						var getSelected = "";
					}
					  		    	var thisgroup = jQuery(this).attr('data-filter-group');
					  		    	selectedFilters[ thisgroup ] = getSelected;
				});
				  		    	var isoFilters = [];
				  		    	for ( var prop in filters ) {
				  					isoFilters.push( filters[ prop ] );
				}
				  		    	for ( var selectedprop in selectedFilters ) {
				  					isoFilters.push( selectedFilters[ selectedprop ] );
				}
				var selector = isoFilters.join('');
				if(!selector) {
					var selector = "*";
				}
				jQuery('#js-grid-masonry').cubeportfolio('filter', selector);
				  		    	return false;
				  			}); // end of change function
			}); //end of each function
	}

	function removeFilterDropdowns() {
		  	jQuery(".filter-group").each(function() {
				var thisSelect = jQuery(this).find("select");
				thisSelect.remove();
			});
	}
	
	filterDropdowns();
	
	function noPosts() {		
		var numcbpPosts = jQuery("#js-grid-masonry .cbp-item").length;
		var numinactivePosts = jQuery("#js-grid-masonry .cbp-item.cbp-item-off").length;		
		if(numcbpPosts === numinactivePosts) {
			jQuery(".nopoststoshow").addClass("activated");
		}	else {
			jQuery(".nopoststoshow").removeClass("activated");			
		}	
	}


	  <?php if(is_page('Courses')) { ?>
//		  var filterList = '<?php echo do_shortcode('[artprof_cp_categories posttype="course"]'); ?>';
		var filterList = '<?php echo do_shortcode('[artprof_cp_categories]'); ?>';
	  <?php } else { ?>
		  var filterList = '<?php echo do_shortcode('[artprof_cp_categories]'); ?>';
	  <?php } 
	  
  	$initFilters = "var initfilter = [];
  	initfilter = initfilter.join('');
  	if(initfilter.length<1){
  		initfilter = '*';
  	}";
	  
	$portfolioOptions = "filters: filterList,
	          layoutMode: 'grid',
	          defaultFilter: initfilter,
	          animationType: 'quicksand',
	          gapHorizontal: 20,
	          gapVertical: 20,
	          gridAdjustment: 'responsive',
	          caption: 'overlayBottomAlong',
	          displayType: 'bottomToTop',
	          displayTypeSpeed: 100,
        plugins: {
            loadMore: {
                selector: '#loadMore',
                action: 'click',
                loadItems: 3,
            }
        },



";

	$listViewOptions =	"mediaQueries: [{
            		width: 1500,
            		cols: 1
          	  	}, {
              	  	width: 1100,
              	  	cols: 1
          	  	}, {
              	  	width: 800,
              	  	cols: 1
          	  	}, {
              	  	width: 480,
              	  	cols: 1
          	  	}, {
              	  	width: 320,
              	  	cols: 1
          	  	}],";
	 $gridViewOptions =  "mediaQueries: [{
	              width: 1500,
	              cols: 5
	          }, {
	              width: 1100,
	              cols: 4
	          }, {
	              width: 800,
	              cols: 3
	          }, {
	              width: 480,
	              cols: 2
	          }, {
	              width: 320,
	              cols: 1
	          }],";			 
?>	  
	  function cubePortfolio() {
		  
		  <?php echo $initFilters; ?>
		  
	      jQuery('#js-grid-masonry').cubeportfolio({
			  <?php echo $portfolioOptions . $gridViewOptions; ?>
	  	  });
		  jQuery('#js-grid-masonry').on('filterComplete.cbp', noPosts);
	  }
	  
	  function cubePortfolioList() {
		  <?php echo $initFilters; ?>
    	
	      jQuery('#js-grid-masonry').cubeportfolio({
			  <?php echo $portfolioOptions . $listViewOptions; ?>
	  	  });
		  jQuery('#js-grid-masonry').on('filterComplete.cbp', noPosts);
	  }
	  
	  function destroyPortfolio() {
	      jQuery('#js-grid-masonry').cubeportfolio('destroy');
	  }
	  
	  <?php 
	  global $listview;
	  if($listview === 'true'){ ?>
		  cubePortfolioList();
		  <?php } else { ?>
			  cubePortfolio();
		  <?php } ?>
			

    // click event
    jQuery('.filter div').click(function(e){
		e.preventDefault();
		jQuery(".nopoststoshow").removeClass("activated");			
	    var thisTarget = e.target;
	    if ( jQuery(thisTarget).hasClass('selected') ) { 	    // don't proceed if already selected
	    //	toggleSelected(thisTarget);
		  	return;
	   }	   
	    var optionSet = jQuery(thisTarget).parents('.option-set'); //ul
	    var optionSelected = jQuery(optionSet).find('.selected'); // active items
		jQuery(optionSelected).each(function() {
			jQuery(this).removeClass("selected cbp-filter-item-active");
		});
	    jQuery(thisTarget).addClass('selected'); // mark this option as selected
			    return false;
    });
	
	
	
	
	
	// list / grid toggle	
	listButton = jQuery('button.list-view');
	gridButton = jQuery('button.grid-view');
	wrapper = jQuery('div#js-grid-masonry');

	listButton.on('click',function(){	
	  gridButton.removeClass('on');
	  listButton.addClass('on');
	  wrapper.removeClass('grid').addClass('list'); 
	  destroyPortfolio();
	  cubePortfolioList();
	});

	gridButton.on('click',function(){
	  listButton.removeClass('on');
	  gridButton.addClass('on');
	  wrapper.removeClass('list').addClass('grid');  	  
	  destroyPortfolio();
	  cubePortfolio();	  
 	}); // end of grid button click
		
  }); //end of doc ready
  
</script>










		