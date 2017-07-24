<?php

class VC_FastGallery_Mosaic_Class {
    function __construct() {
        add_action( 'init', array( $this, 'integrateFGWithVC' ) );
        add_shortcode( 'vcfastgallery_mosaic', array( $this, 'vcfastgallery_mosaic_function' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'fastgallery_mosaic_loadCssAndJs' ) );
    }
 
    public function integrateFGWithVC() {
        if ( ! defined( 'WPB_VC_VERSION' ) ) {
            add_action('admin_notices', array( $this, 'showVcVersionNotice' ));
            return;
        }
		
		include('animate-list.php');
        
		vc_map( array(
            "name" => __("Fast Gallery Mosaic", 'vcfg'),
            "description" => __("Build your mosaic gallery", 'vcfg'),
            "base" => "vcfastgallery_mosaic",
            "class" => "",
            "controls" => "full",
            "icon" => plugins_url('assets/img/fastgallery-vc.png', __FILE__),
            "category" => __('Fast Gallery', 'js_composer'),
            "params" => array(
			  array(
				  "type" => "textfield",
				  "class" => "",
				  "heading" => __("Gallery Name",'vcfg'),
				  "param_name" => "fg_gallery_name",
				  "admin_label" => true,
			  ),
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Show Name", 'vcfg'),
                  "param_name" => "fg_gallery_name_show",
                  "value" => array(	
				  					'Hidden' => 'false',
									'Show' 	 => 'true',									
				   )
              ),			  			
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Layout", 'vcfg'),
                  "param_name" => "fgm_layout",
                  "value" => array(
									'Layout 1' => 'fg_layout1',
									'Layout 2' => 'fg_layout2',
									'Layout 3' => 'fg_layout3',
									'Layout 4' => 'fg_layout4',
									'Layout 5' => 'fg_layout5'
				   )
              ),
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Type Gallery", 'vcfg'),
                  "param_name" => "fg_type",
                  "value" => array(
									'PrettyPhoto' => 'prettyphoto',
									'PhotoBox' => 'photobox',
									'Magnific Popup' => 'magnific-popup',
									'Light Gallery' => 'lightgallery',
									'Custom Url' => 'custom_url'
				   )
              ),
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Standard Image Height", 'vcfg'),
                  "param_name" => "fgm_height",
                  "value" => array(
									'Very small (100px)' 	=> '100',
									'Small (150px)' 		=> '150',
									'Medium (200px)' 		=> '200',
									'Big (300px)' 			=> '300',
									'Very Big (500px)' 		=> '500',
									'Custom Value'			=> 'custom_value',
				   )
              ),	
			  array(
				  "type" => "textfield",
				  "class" => "",
				  "heading" => __("Custom Image Height",'vcfg'),
				  "param_name" => "fgm_custom_height",
				  "value" => "150",
				  "description" => __("height in px . Example: 200"),
				  'dependency' => array(
						'element' => 'fgm_height',
						'value' => array( 'custom_value' )
				  ),
			  ),			  
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Fix Last Image", 'vcfg'),
                  "param_name" => "fgm_allow",
                  "value" => array(
									'Off' => 'false',
									'On'  => 'true'
				   ),
				   "description" => __("Sometimes there is just one image on the last row and it gets blown up to a huge size to fit the parent div width. To stop this behaviour, set this to ON",'vcfg'),
              ),			    		
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Responsive / Mosaic", 'vcfg'),
                  "param_name" => "fg_responsive",
                  "value" => array(
									'Responsive' => 'fg_responsive',
									'Mosaic' 	 => 'fg_mosaic'
				   )
              ),		  		  
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Over Image", 'vcfg'),
                  "param_name" => "fg_over_image",
                  "value" => array(
									'On' => 'fg_over_image_on',
									'Off'  => 'fg_over_image_off'
				   )
              ),
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Pagination", 'vcfg'),
                  "param_name" => "fg_pagination_active",
                  "value" => array(
									'Off' => 'off',
									'On'  => 'on'
				   )
              ),
			  array(
				  "type" => "textfield",
				  "class" => "",
				  "heading" => __("Number of images for each page",'vcfg'),
				  "param_name" => "fg_pagination_number",
				  "value" => "10",
				  "description" => __("For example: 15 (Default value is 10)",'vcfg'),
				  'dependency' => array(
						'element' => 'fg_pagination_active',
						'value' => array( 'on' )
				  ),			  
			  ),			  			  			  
              array(
                  "type" => "attach_images",
                  "class" => "",
                  "heading" => __("Upload Your Images", 'vcfg'),
                  "param_name" => "images",
				  "description" => __('If you get image from library (not uploaded) we reccomended you to regenerate your thumbs','vcfg'),
              ),
			  			  
			  /* STYLE */ 
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Style", 'vcfg'),
                  "param_name" => "fg_style",
                  "value" => array(
									'Style 1' => 'fg_style1',
									'Style 2' => 'fg_style2',
									'Style 3' => 'fg_style3',
									'Style 4' => 'fg_style4',
									'Style 5' => 'fg_style5',
									'Style 6' => 'fg_style6',
									'Style 7' => 'fg_style7',
									'Style 8' => 'fg_style8',
									'Style 9' => 'fg_style9',
									'Style 10' => 'fg_style10'																	
				   ),
				  "group" => "Style",
              ),			  			  	  			  	
              array(
                  "type" => "colorpicker",
                  "class" => "",
                  "heading" => __("Main Color", 'vcfg'),
                  "param_name" => "fg_main_color",
                  "value" => '#FC615D',
				  "group" => "Style",
              ),
			  array(
				  "type" => "textfield",
				  "class" => "",
				  "heading" => __("Opacity",'vcfg'),
				  "param_name" => "fg_main_color_opacity",
				  "value" => "1",
				  "description" => __("Main Color Opacity (0.1 to 1)",'vcfg'),
				  "group" => "Style",
			  ),			  
              array(
                  "type" => "colorpicker",
                  "class" => "",
                  "heading" => __("Secondary Color", 'vcfg'),
                  "param_name" => "fg_secondary_color",
                  "value" => '#FFFFFF',
				  "group" => "Style",
              ),	
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Custom spacing between the photos", 'vcfg'),
                  "param_name" => "fg_spacing_active",
                  "value" => array(
									'Off' => 'off',
									'On' => 'on'																	
				   ),
				   "group" => "Style",			   				   
              ),
			  array(
				  "type" => "textfield",
				  "class" => "",
				  "heading" => __("spacing between the photos",'vcfg'),
				  "param_name" => "fgm_padding",
				  "value" => "20",
				  "description" => __("Enter pixel value (examples: 20)",'vcfg'),
				  'dependency' => array(
						'element' => 'fg_spacing_active',
						'value' => array( 'on' )
				  ),
				  "group" => "Style",
			  ),
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Icon Image Lightbox", 'vcfg'),
                  "param_name" => "fgm_image_lightbox",
                  "value" => array(
									'Plus' => 'plus',
									'Zoom In' => 'zoomin',
									'Image' => 'image',
									'Images' => 'images',
									'Spinner' => 'spinner_icon',
									'Search' => 'search_icon'																	
				   ),
				  "group" => "Style",
              ),
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Icon Image Lightbox Width", 'vcfg'),
                  "param_name" => "fgm_image_width",
                  "value" => array(
									'Small (Default)' => 'small',
									'Medium' => 'medium',
									'Large' => 'large'																	
				   ),
				  "group" => "Style",
              ),			  			  			  			  		  
			  array(
				  "type" => "textfield",
				  "class" => "",
				  "heading" => __("Gallery Name Font Size",'vcfg'),
				  "param_name" => "fg_gallery_name_font_size",
				  "value" => "20",
				  "description" => __("Enter pixel value (examples: 20)",'vcfg'),
				  'dependency' => array(
						'element' => 'fg_gallery_name_show',
						'value' => array( 'true' )
				  ),
				  "group" => "Style",
			  ),
			  array(
                  "type" => "colorpicker",
                  "class" => "",
                  "heading" => __("Gallery Name Font Color", 'vcfg'),
                  "param_name" => "fg_gallery_name_font_color",
                  "value" => '#FC615D',
				  'dependency' => array(
						'element' => 'fg_gallery_name_show',
						'value' => array( 'true' )
				  ),
				  "group" => "Style",
			  ),
			  array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Gallery Name Text Align", 'vcfg'),
                  "param_name" => "fg_gallery_name_text_align",
                  "value" => array(
									'Center' => 'center',
									'Left' => 'left',
									'Right' => 'right'																	
				   ),
				  'dependency' => array(
						'element' => 'fg_gallery_name_show',
						'value' => array( 'true' )
				  ),
				  "group" => "Style",
			  ),
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Pagination Style", 'vcfg'),
                  "param_name" => "fg_pagination_style",
                  "value" => array(
									'Style 1'  => 'fg_pagination_style1',
									'Style 2'  => 'fg_pagination_style2'
				   ),
				  'dependency' => array(
						'element' => 'fg_pagination_active',
						'value' => array( 'on' )
				  ),	
				  "group" => "Style",			   
              ),	


			  			  
			  /* PRETTYPHOTO */
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Animation Speed", 'vcfg'),
                  "param_name" => "pp_animation_speed",
                  "value" => array(
									'Fast' 	 => 'fast',
									'Normal' => 'normal',
									'Slow' 	 => 'slow'																		
				   ),
				  'dependency' => array(
						'element' => 'fg_type',
						'value' => array( 'prettyphoto' )
				  ),
				  "group" => "Prettyphoto Options",				   
              ),
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Autoplay", 'vcfg'),
                  "param_name" => "pp_autoplay",
                  "value" => array(
									'On' => 'true',
									'Off' => 'false'																		
				   ),
				  'dependency' => array(
						'element' => 'fg_type',
						'value' => array( 'prettyphoto' )
				  ),
				  "group" => "Prettyphoto Options",				   
              ),		  
			  array(
				  "type" => "textfield",
				  "class" => "",
				  "heading" => __("Autoplay Time",'vcfg'),
				  "param_name" => "pp_time",
				  "value" => "2000",
				  "description" => __("time in ms (1000 = 1sec). Example: 2000",'vcfg'),
				  'dependency' => array(
						'element' => 'fg_type',
						'value' => array( 'prettyphoto' )
				  ),
				  "group" => "Prettyphoto Options",
			  ),
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Show Title", 'vcfg'),
                  "param_name" => "pp_title",
                  "value" => array(
									'On' => 'true',
									'Off' => 'false'																		
				   ),
				  'dependency' => array(
						'element' => 'fg_type',
						'value' => array( 'prettyphoto' )
				  ),
				  "group" => "Prettyphoto Options",				   
              ),
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Show Social", 'vcfg'),
                  "param_name" => "pp_social",
                  "value" => array(
									'On' => 'true',
									'Off' => 'false'																		
				   ),
				  'dependency' => array(
						'element' => 'fg_type',
						'value' => array( 'prettyphoto' )
				  ),
				  "group" => "Prettyphoto Options",				   
              ),
			  			  
			  /* PHOTOBOX */
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Thumbs", 'vcfg'),
                  "param_name" => "pb_thumbs",
                  "value" => array(
									'On' => 'true',
									'Off' => 'false'																		
				   ),
				  'dependency' => array(
						'element' => 'fg_type',
						'value' => array( 'photobox' )
				  ),
				  "group" => "Photobox Options",				   
              ),
			  			  			  
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Autoplay", 'vcfg'),
                  "param_name" => "pb_autoplay",
                  "value" => array(
									'On' => 'true',
									'Off' => 'false'																		
				   ),
				  'dependency' => array(
						'element' => 'fg_type',
						'value' => array( 'photobox' )
				  ),
				  "group" => "Photobox Options",				   
              ),		  
			  array(
				  "type" => "textfield",
				  "class" => "",
				  "heading" => __("Autoplay Time",'vcfg'),
				  "param_name" => "pb_time",
				  "value" => "2000",
				  "description" => __("time in ms (1000 = 1sec). Example: 2000",'vcfg'),
				  'dependency' => array(
						'element' => 'fg_type',
						'value' => array( 'photobox' )
				  ),
				  "group" => "Photobox Options",
			  ),
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Counter", 'vcfg'),
                  "param_name" => "pb_counter",
                  "value" => array(
									'On' => 'true',
									'Off' => 'false'																		
				   ),
				  'dependency' => array(
						'element' => 'fg_type',
						'value' => array( 'photobox' )
				  ),
				  "group" => "Photobox Options",				   
              ),		  
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("History", 'vcfg'),
                  "param_name" => "pb_history",
				  "description" => __("Enable/disable HTML5 history using hash urls",'vcfg'),
                  "value" => array(
				  					'Off' => 'false',
									'On' => 'true',																										
				   ), 
				  'dependency' => array(
						'element' => 'fg_type',
						'value' => array( 'photobox' )
				  ),
				  "group" => "Photobox Options",				   
              ),
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Loop", 'vcfg'),
                  "param_name" => "pb_loop",
                  "value" => array(
				  					'On' => 'true',	
				  					'Off' => 'false'
																																		
				   ), 
				  'dependency' => array(
						'element' => 'fg_type',
						'value' => array( 'photobox' )
				  ),
				  "group" => "Photobox Options",				   
              ),
			  
			  /* MAGNIFIC POPUP */
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Gallery", 'vcfg'),
                  "param_name" => "mp_gallery",
                  "value" => array(
									'On' 	 => 'true',
									'Off' 	 => 'false'																	
				   ),
				  'dependency' => array(
						'element' => 'fg_type',
						'value' => array( 'magnific-popup' )
				  ),
				  "group" => "Magnific Popup Options",				   
              ),
			  
			  /* LIGHT GALLERY */
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Mode", 'vcfg'),
                  "param_name" => "lg_mode",
                  "value" => array(
									'Slide' => 'lg-slide',
									'Fade' => 'lg-fade'																		
				   ),
				  'dependency' => array(
						'element' => 'fg_type',
						'value' => array( 'lightgallery' )
				  ),
				  "group" => "Light Gallery",				   
              ),
			  array(
				  "type" => "textfield",
				  "class" => "",
				  "heading" => __("Speed",'vcfg'),
				  "param_name" => "lg_speed",
				  "value" => "2000",
				  "description" => __("time in ms (1000 = 1sec). Example: 2000",'vcfg'),
				  'dependency' => array(
						'element' => 'fg_type',
						'value' => array( 'lightgallery' )
				  ),
				  "group" => "Light Gallery",
			  ),			  			  			  
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Thumbnail", 'vcfg'),
                  "param_name" => "lg_thumbnail",
                  "value" => array(
									'On' => 'true',
									'Off' => 'false'																		
				   ),
				  'dependency' => array(
						'element' => 'fg_type',
						'value' => array( 'lightgallery' )
				  ),
				  "group" => "Light Gallery",				   
              ),
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Controls", 'vcfg'),
                  "param_name" => "lg_controls",
                  "value" => array(
									'On' => 'true',
									'Off' => 'false'																		
				   ),
				  'dependency' => array(
						'element' => 'fg_type',
						'value' => array( 'lightgallery' )
				  ),
				  "group" => "Light Gallery",				   
              ),		  

			  /* CUSTOM URL */
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Target", 'vcfg'),
                  "param_name" => "custom_url_target",
                  "value" => array(
									'Blank (New Window)' => '_blank',
									'Self (Same Widow)'	 => '_self'																		
				   ),
				  'dependency' => array(
						'element' => 'fg_type',
						'value' => array( 'custom_url' )
				  ),
				  "group" => "Custom Url",				   
              ),
			  
			  // THUMBNAILS CONTROLS
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Image Lightbox Size", 'vcfg'),
                  "param_name" => "fgm_image_lightbox_size",
                  "value" => array(	
				  					'Default: 1000px x 800px cropped' 	=> 'fgm_default',
				  					'Large'  							=> 'large',
									'Full' 	 							=> 'full',									
									'Medium' 							=> 'medium',									
									'Thumbnail'  						=> 'thumbnail',
				   ),
				  "group" => "Thumbnails",
              )	,
			  
			  /* SEO SETTINGS */
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("SEO Setting", 'vcfg'),
                  "param_name" => "fg_seo",
                  "value" => array(
									'Off' => 'off',
									'On'  => 'on',
				  ),
				  "description" => __('If you set ON you need to insert all ALT and TITLE Tags image for correct settings','vcfg'),				   
				  "group" => "SEO",
              ),
			  
			  /* ANIMATION */
												 				 												  			  			  
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Animate",'vcfg'),
                  "param_name" => "fg_animate",
                  "value" => array(
				  					__('Off','vcmegapack') 	  => 'off',
									__('On','vcmegapack') => 'on'
									
				   ),
				   "group" => "Animation"
			  ),			  
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Animate Effects",'vcfg'),
                  "param_name" => "fg_animate_effect",
                  "value" => $animate_effect,
				  'dependency' => array(
						'element' => 'fg_animate',
						'value' => array( 'on' )
				  ),
				  "group" => "Animation"				   
			  ),				  
			  array(
				  "type" => "textfield",
				  "class" => "",
				  "heading" => __("Animate Delay (ms)",'vcfg'),
				  "param_name" => "fg_delay",
				  "description" => "ex 1000",					  
				  'dependency' => array(
						'element' => 'fg_animate',
						'value' => array( 'on' )
				  ),
				  "value" => "0",
				  "group" => "Animation"					  
			  ),			    		  			  			  			  			  		  			  			  			  		  			  			  			  			  			  
            )
        ) );
    }

    public function fastgallery_mosaic_loadCssAndJs() {
	  
		// Load WP jQuery if not included
		wp_enqueue_script('jquery');

		wp_register_script('fgm_removeWhitespace', AD_VCFG_URL . 'assets/js/jquery.removeWhitespace.min.js', array('jquery'), '', true);
		wp_register_script('fgm_collagePlus', AD_VCFG_URL . 'assets/js/jquery.collagePlus.min.js', array('jquery'), '', true);
		
		// Load main js script
		wp_register_script('fastgallery_mosaic-frontend-script', AD_VCFG_URL . 'assets/js/frontend.js', array('jquery'), '', true);
			
		// Main
		wp_register_style( 'fastgallery_mosaic-vc-main-style',  AD_VCFG_URL . 'assets/css/mosaic_style.css' );
		wp_register_style( 'fonts-vc',  AD_VCFG_URL . 'assets/css/fonts.css' );  
		
		// PHOTOBOX
		wp_register_script('photobox-js', AD_VCFG_URL . 'assets/js/photobox.js', array('jquery'), '', true);
		wp_register_style( 'photobox-vc',  AD_VCFG_URL . 'assets/css/photobox.css' );	
		wp_register_style( 'photoboxie-vc',  AD_VCFG_URL . 'assets/css/photobox.ie.css' );	
		wp_register_style( 'photobox-style-vc',  AD_VCFG_URL . 'assets/css/photobox-style.css' );
		
		// PRETTYPHOTO		   
		wp_register_script('prettyPhoto-js', AD_VCFG_URL . 'assets/js/jquery.prettyPhoto.js', array('jquery'), '', true);
		wp_register_style( 'prettyPhoto-vc',  AD_VCFG_URL . 'assets/css/prettyPhoto.css' );
		
		// MAGNIFIC POPUP
		wp_register_script('magnific-popup-js', AD_VCFG_URL . 'assets/js/jquery.magnific-popup.js', array('jquery'), '', true);		
		wp_register_style( 'magnific-popup-vc',  AD_VCFG_URL . 'assets/css/magnific-popup.css' );
		
		// LIGHT GALLERY
		wp_register_style( 'fg-lightgallery',  AD_VCFG_URL . 'assets/css/lightGallery.css' );
		wp_register_script( 'fg-lightgallery-js',  AD_VCFG_URL . 'assets/js/lightGallery.min.js' , array('jquery'), '', true);		
		
		// ANIMATE
		wp_register_style( 'fg-animations',  AD_VCFG_URL . 'assets/css/animations.min.css' );
		wp_register_script( 'fg-appear-js',  AD_VCFG_URL . 'assets/js/appear.min.js' , array('jquery'), '', true);				
		wp_register_script( 'fg-animate-js',  AD_VCFG_URL . 'assets/js/animations.min.js' , array('jquery'), '', true);		
							  
    }

    public function showVcVersionNotice() {
        $plugin_data = get_plugin_data(__FILE__);
        echo '
        <div class="updated">
          <p>'.sprintf(__('<strong>%s</strong> requires <strong><a href="http://codecanyon.net/item/visual-composer-page-builder-for-wordpress/242431?ref=ad-theme" target="_blank">Visual Composer</a></strong> plugin to be installed and activated on your site.', 'vcfg'), $plugin_data['Name']).'</p>
        </div>';
    }
}
// Finally initialize code
new VC_FastGallery_Mosaic_Class();

?>