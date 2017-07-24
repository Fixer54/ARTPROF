<?php
/*
Plugin Name: Fast Gallery for Visual Composer
Plugin URI: http://plugins.ad-theme.com/fastgalleryvc/
Description: Fast Gallery for VC - Premium Wordpress Gallery Plugin
Author: Ad-theme.com
Version: 3.0
Author URI: http://www.ad-theme.com
Compatibility: WP 3.9.x - WP 4.x
*/

// Basic plugin definitions 
define ('PLG_NAME_VCFASTGALLERY', 'vcfg');
define( 'PLG_VERSION_VCFASTGALLERY', '3.0' );
define( 'AD_VCFG_URL', WP_PLUGIN_URL . '/' . str_replace( basename(__FILE__), '', plugin_basename(__FILE__) ));
define( 'AD_VCFG_DIR', WP_PLUGIN_DIR . '/' . str_replace( basename(__FILE__), '', plugin_basename(__FILE__) ));

// LANGUAGE
add_action('init', 'vcfg_localization_init');
function vcfg_localization_init() {
    $path = dirname(plugin_basename( __FILE__ )) . '/languages/';
    $loaded = load_plugin_textdomain( 'vcfg', false, $path);
}

// don't load directly
if (!defined('ABSPATH')) die('-1');

class VC_FastGallery_Class {
    function __construct() {
        add_action( 'init', array( $this, 'integrateFGWithVC' ) );
        add_shortcode( 'vcfastgallery', array( $this, 'vcfastgallery_function' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'fastgallery_loadCssAndJs' ) );
    }
 
    public function integrateFGWithVC() {
        if ( ! defined( 'WPB_VC_VERSION' ) ) {
            add_action('admin_notices', array( $this, 'showVcVersionNotice' ));
            return;
        }
		
		include('animate-list.php');
        
		vc_map( array(
            "name" => __("Fast Gallery", 'vcfg'),
            "description" => __("Build your gallery", 'vcfg'),
            "base" => "vcfastgallery",
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
                  "heading" => __("Columns", 'vcfg'),
                  "param_name" => "columns",
                  "value" => array(
									'1' => '1',
									'2' => '2',
									'3' => '3',
									'4' => '4',
									'5' => '5',
									'6' => '6',
									'7' => '7',
									'8' => '8',
									'9' => '9'
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
									'Fotorama Slideshow' => 'fotorama',
									'Light Gallery' => 'lightgallery',
									'Photoswipe' => 'photoswipe',
									'Custom Url' => 'custom_url',
									'Only Image (no Lightbox)' => 'only_image'
				   )
              ),
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Masonry / Grid", 'vcfg'),
                  "param_name" => "size",
                  "value" => array(
									'Grid' => 'fg-normal',
									'Masonry' => 'fg-masonry'
				   )
              ),		
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Responsive / Fluid", 'vcfg'),
                  "param_name" => "fg_responsive",
                  "value" => array(
									'Responsive' => 'fg_responsive',
									'Fluid' => 'fg_fluid'
				   )
              ),
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Over Image", 'vcfg'),
                  "param_name" => "fg_over_image",
                  "value" => array(
									'On' => 'fg_over_image_on',
									'Off' => 'fg_over_image_off'
				   )
              ),			  			  
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Show Caption Image", 'vcfg'),
                  "param_name" => "fg_caption",
                  "value" => array(
									'Off' => 'off',
									'On' => 'on'
				   )
              ),			  			  
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("One Thumbs for Gallery", 'vcfg'),
                  "param_name" => "fg_thumbs_one",
                  "value" => array(
									'Off' => 'off',
									'On' => 'fg_thumbs_one'
				   )
              ),
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Lazy Load", 'vcfg'),
                  "param_name" => "fg_lazyload",
                  "value" => array(
									'Off' => 'off',
									'On'  => 'on'
				   )
              ),
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Lazy Load Animation (Fade in Effect)", 'vcfg'),
                  "param_name" => "fg_lazyload_effect",
                  "value" => array(
									'Yes' => 'yes',
									'No'  => 'no'
				   ),
				  'dependency' => array(
						'element' => 'fg_lazyload',
						'value' => array( 'on' )
				  ),				   
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
									'Style 10' => 'fg_style10',
									'Style 11' => 'fg_style11',
									'Style 12' => 'fg_style12',
									'No Style' => 'fg_no_style',																		
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
				  "param_name" => "fg_spacing",
				  "value" => "20",
				  "description" => __("Enter pixel value (examples: 20)",'vcfg'),
				  'dependency' => array(
						'element' => 'fg_spacing_active',
						'value' => array( 'on' )
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
                  "heading" => __("Image Lightbox", 'vcfg'),
                  "param_name" => "fg_image_lightbox",
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
				  "description" => __('if you have selected lazyload effect is automatically set on "OFF"','vcfg'),
				  "dependency" => array(
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
			  			  
			  /* FOTORAMA */
              array(
			  	  "type" => "textfield",
                  "class" => "",
                  "heading" => "",
				  "param_name" => "fg_title",
				  "value" => 'Fotorama Extra Option',
				  'dependency' => array(
						'element' => 'fg_type',
						'value' => array( 'fotorama' )
				  ),
				  "group" => "Fotorama Options",				  
              ),			  
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Autoplay", 'vcfg'),
                  "param_name" => "fg_autoplay",
                  "value" => array(
									'On' => 'true',
									'Off' => 'false'																		
				   ),
				  'dependency' => array(
						'element' => 'fg_type',
						'value' => array( 'fotorama' )
				  ),
				  "group" => "Fotorama Options",				   
              ),
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Nav", 'vcfg'),
                  "param_name" => "fg_nav",
                  "value" => array(
									'Thumbs' => 'thumbs',
									'dot' => 'dot',
									'Hidden' => 'hidden'
				   ),
				  'dependency' => array(
						'element' => 'fg_type',
						'value' => array( 'fotorama' )
				  ),
				  "group" => "Fotorama Options",				   
              ),			  			  
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Nav Position", 'vcfg'),
                  "param_name" => "fg_navposition",
                  "value" => array(
									'Bottom' => 'bottom',
									'Top' => 'top'
				   ),
				  'dependency' => array(
						'element' => 'fg_type',
						'value' => array( 'fotorama' )
				  ),
				  "group" => "Fotorama Options",
              ),
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Allow fullscreen", 'vcfg'),
                  "param_name" => "fg_allowfullscreen",
                  "value" => array(
									'On' => 'true',
									'Off' => 'false'
				   ),
				  'dependency' => array(
						'element' => 'fg_type',
						'value' => array( 'fotorama' )
				  ),
				  "group" => "Fotorama Options",
              ),
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Transition", 'vcfg'),
                  "param_name" => "fg_transition",
                  "value" => array(
									'Slide' => 'slide',
									'Crossfade' => 'crossfade',
									'Dissolve' => 'dissolve'
				   ),
				  'dependency' => array(
						'element' => 'fg_type',
						'value' => array( 'fotorama' )
				  ),
				  "group" => "Fotorama Options",
              ),
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Arrow", 'vcfg'),
                  "param_name" => "fg_arrow",
                  "value" => array(
									'On' => 'true',
									'Off' => 'false'
				   ),
				  'dependency' => array(
						'element' => 'fg_type',
						'value' => array( 'fotorama' )
				  ),
				  "group" => "Fotorama Options",
              ),
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Fit", 'vcfg'),
                  "param_name" => "fg_fit",
                  "value" => array(
									'None' => 'none',
									'Contain' => 'contain',
									'Cover' => 'cover',
									'Scaledown' => 'scaledown'									
				   ),
				  'dependency' => array(
						'element' => 'fg_type',
						'value' => array( 'fotorama' )
				  ),
				  "group" => "Fotorama Options",
              ),
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Loop", 'vcfg'),
                  "param_name" => "fg_loop",
                  "value" => array(
									'On' => 'true',
									'Off' => 'false'
				   ),
				  'dependency' => array(
						'element' => 'fg_type',
						'value' => array( 'fotorama' )
				  ),
				  "group" => "Fotorama Options",
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
                  "heading" => __("Grid Thumbnails Size", 'vcfg'),
                  "param_name" => "fg_thumbs_grid",
                  "value" => array(
									'Default (800px)' => 'fg-normal',
									'Thumbnail' => 'thumbnail',
									'Medium' => 'medium',
									'Large' => 'large',
									'Full' => 'full'
				   ),
				  'dependency' => array(
						'element' => 'size',
						'value' => array( 'fg-normal' )
				  ),
				  "group" => "Thumbnails",
              )	,			  
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Masonry Thumbnails Size", 'vcfg'),
                  "param_name" => "fg_thumbs_masonry",
                  "value" => array(
									'Default (500px)' => 'fg-masonry',
									'Thumbnail' => 'thumbnail',
									'Medium' => 'medium',
									'Large' => 'large',
									'Full' => 'full'
				   ),
				  'dependency' => array(
						'element' => 'size',
						'value' => array( 'fg-masonry' )
				  ),
				  "group" => "Thumbnails",
              )	,
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Lightbox Thumbs", 'vcfg'),
                  "param_name" => "fg_thumbs_lightbox",
                  "value" => array(	
				  					'Large (Default)'  => 'large',
									'Full' 	 => 'full',									
									'Medium' => 'medium',									
									'Thumbnail'  => 'thumbnail',
				   ),
				  "group" => "Thumbnails",
              )	,
			  
			  /* MOBILE COLUMNS */
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Active Custom Responsive", 'vcfg'),
                  "param_name" => "fg_active_custom_responsive",
                  "value" => array(
									'Defatul Value' => 'fg_responsive',
									'Active Custom Responsive' => 'active_custom_responsive',
				  ),
				  'dependency' => array(
						'element' => 'fg_responsive',
						'value' => array( 'fg_responsive' )
				  ),				   
				  "group" => "Responsive",
              ),			  
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Smartphone Portrait", 'vcfg'),
                  "param_name" => "fg_smartphone_p_columns",
                  "value" => array(
									'1' => '1',
									'2' => '2',
									'3' => '3',
									'4' => '4',
									'5' => '5',
									'6' => '6',
									'7' => '7',
									'8' => '8',
									'9' => '9'
				  ),
				  'dependency' => array(
						'element' => 'fg_responsive',
						'value' => array( 'fg_responsive' )
				  ),				   
				  "group" => "Responsive",
              ),
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Smartphone Landscape", 'vcfg'),
                  "param_name" => "fg_smartphone_l_columns",
                  "value" => array(
									'1' => '1',
									'2' => '2',
									'3' => '3',
									'4' => '4',
									'5' => '5',
									'6' => '6',
									'7' => '7',
									'8' => '8',
									'9' => '9'
				  ),
				  'dependency' => array(
						'element' => 'fg_responsive',
						'value' => array( 'fg_responsive' )
				  ),				   
				  "group" => "Responsive",
              ),
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Tablet Portrait", 'vcfg'),
                  "param_name" => "fg_tablet_p_columns",
                  "value" => array(
									'1' => '1',
									'2' => '2',
									'3' => '3',
									'4' => '4',
									'5' => '5',
									'6' => '6',
									'7' => '7',
									'8' => '8',
									'9' => '9'
				  ),
				  'dependency' => array(
						'element' => 'fg_responsive',
						'value' => array( 'fg_responsive' )
				  ),				   
				  "group" => "Responsive",
              ),			  	
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Tablet Landscape", 'vcfg'),
                  "param_name" => "fg_tablet_l_columns",
                  "value" => array(
									'1' => '1',
									'2' => '2',
									'3' => '3',
									'4' => '4',
									'5' => '5',
									'6' => '6',
									'7' => '7',
									'8' => '8',
									'9' => '9'
				  ),
				  'dependency' => array(
						'element' => 'fg_responsive',
						'value' => array( 'fg_responsive' )
				  ),				   
				  "group" => "Responsive",
              ),
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Desktop (min value 640px - max value 1024px)", 'vcfg'),
                  "param_name" => "fg_desktop_medium_columns",
                  "value" => array(
									'1' => '1',
									'2' => '2',
									'3' => '3',
									'4' => '4',
									'5' => '5',
									'6' => '6',
									'7' => '7',
									'8' => '8',
									'9' => '9'
				  ),
				  'dependency' => array(
						'element' => 'fg_responsive',
						'value' => array( 'fg_responsive' )
				  ),				   
				  "group" => "Responsive",
              ),
              array(
                  "type" => "dropdown",
                  "class" => "",
                  "heading" => __("Desktop (max 639px)", 'vcfg'),
                  "param_name" => "fg_desktop_small_columns",
                  "value" => array(
									'1' => '1',
									'2' => '2',
									'3' => '3',
									'4' => '4',
									'5' => '5',
									'6' => '6',
									'7' => '7',
									'8' => '8',
									'9' => '9'
				  ),
				  'dependency' => array(
						'element' => 'fg_responsive',
						'value' => array( 'fg_responsive' )
				  ),				   
				  "group" => "Responsive",
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

    public function fastgallery_loadCssAndJs() {
	  
		// Load WP jQuery if not included
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-masonry');
		
		// Load main js script
		wp_register_script('fastgallery-frontend-script', AD_VCFG_URL . 'assets/js/frontend.js', array('jquery'), '', true);
		wp_enqueue_style( 'fastgallery-vc-main-style' );
			
		// Main
		wp_register_style( 'fastgallery-vc-main-style',  AD_VCFG_URL . 'assets/css/style.css' );
		wp_register_style( 'fonts-vc',  AD_VCFG_URL . 'assets/css/fonts.css' ); 
		wp_register_style( 'custom-responsive-vc',  AD_VCFG_URL . 'assets/css/custom_responsive.css' ); 
		
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
		
		// FOTORAMA
		wp_register_script('fotorama-js', AD_VCFG_URL . 'assets/js/fotorama.js', array('jquery'), '', true);			
		wp_register_style( 'fotorama-css-vc',  AD_VCFG_URL . 'assets/css/fotorama.css' );
		
		// LIGHT GALLERY
		wp_register_style( 'fg-lightgallery',  AD_VCFG_URL . 'assets/css/lightGallery.css' );
		wp_register_script( 'fg-lightgallery-js',  AD_VCFG_URL . 'assets/js/lightGallery.min.js' , array('jquery'), '', true);		
		
		// PHOTOSWIPE
		wp_register_style( 'fg-photoswipe',  AD_VCFG_URL . 'assets/css/photoswipe.css' );
		wp_register_style( 'fg-photoswipe-default-skin',  AD_VCFG_URL . 'assets/css/default-skin.css' );
		wp_register_script( 'fg-photoswipe-js',  AD_VCFG_URL . 'assets/js/photoswipe.min.js' , array('jquery'), '', true);	
		wp_register_script( 'fg-photoswipe-ui-default-js',  AD_VCFG_URL . 'assets/js/photoswipe-ui-default.min.js' , array('jquery'), '', true);
		wp_register_script( 'fg-photoswipe-general-js',  AD_VCFG_URL . 'assets/js/photoswipe-general.js' , array('jquery'), '', true);
		
		// ANIMATE
		wp_register_style( 'fg-animations',  AD_VCFG_URL . 'assets/css/animations.min.css' );
		wp_register_script( 'fg-appear-js',  AD_VCFG_URL . 'assets/js/appear.min.js' , array('jquery'), '', true);				
		wp_register_script( 'fg-animate-js',  AD_VCFG_URL . 'assets/js/animations.min.js' , array('jquery'), '', true);		
		
		// LAZY LOAD EFFECT
		wp_register_script( 'fg-lazyload-js',  AD_VCFG_URL . 'assets/js/jquery.lazyload.js' , array('jquery'), '', true);				
		wp_register_script( 'fg-imagesLoaded-js',  AD_VCFG_URL . 'assets/js/imagesLoaded.js' , array('jquery'), '', true);				  
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
new VC_FastGallery_Class();

require('mosaic_class.php');
require('mosaic_functions.php');

require('functions.php');
require('medias_fields.php');