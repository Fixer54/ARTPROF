<?php
if ( ! defined( 'ABSPATH' ) )  exit; // Exit if accessed directly

/*
 * VCMP Module: Video Gallery
 * Description: Vimeo Gallery shortcode for Visual Composer
 * Author Name: ThemeofWP.com
 * Author URL: http://themeofwp.com/
 * Version: 1.0.2
 */

class VcmpVideoGallery extends VcmpModule{

	const slug = 'video_gallery';
	const base = 'video_gallery';

	public function __construct(){
	
		
		add_action( 'vc_before_init', array( $this, 'video_gallery_shortcode_vc' ) );
		add_action( 'vc_before_init', array( $this, 'video_gallery_content_shortcode_vc' ) );
		
		add_shortcode( 'video_gallery', array( $this, 'vc_video_gallery_shortcode' ));
		add_shortcode( 'video_gallery_content', array( $this, 'video_gallery_content_shortcode' ));

		// Register CSS and JS
        add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );
		
	}

	/*
    Load plugin css and javascript files which you may need on front end of your site
    */
    public function loadCssAndJs() {
		global $post;
		if( has_shortcode( $post->post_content, 'video_gallery') ) {
			wp_enqueue_style( 'vc_videogallery', VCMP_URL . 'modules/vcmp-videogallery/assets/css/vc_videogallery.css');
			wp_enqueue_script( 'vc_scrollTo', VCMP_URL.'modules/vcmp-videogallery/assets/js/jquery.scrollTo.js', array('jquery'), '1.0', TRUE);
			wp_enqueue_script( 'vc_videogallery', VCMP_URL.'modules/vcmp-videogallery/assets/js/vc_videogallery.js', array('jquery'), '1.0', TRUE);
		}
    }
	
	
	// Parent Element
	public function video_gallery_shortcode_vc() {
		vc_map( 
			array(
				"icon" 					  => 'icon-vc-videogallery-page',
				'name'                    => __( 'Video Gallery' , 'VCMP_SLUG' ),
				'base'                    => 'video_gallery',
				'description'             => __( 'Add video gallery to your page.', 'VCMP_SLUG' ),
				'as_parent'               => array('only' => 'video_gallery_content'), // Use only|except attributes to limit child shortcodes (separate multiple values with comma)
				'content_element'         => true,
				'show_settings_on_create' => true,
				"js_view" 				  => 'VcColumnView',
				"category" 				  => __('Elegant Mega Addons', 'VCMP_SLUG'),
				'params'          		  => array(
				
					array(
						"type" => "dropdown",
						"heading" => __( "Main Video Source", "VCMP_SLUG" ),
						"param_name" => "videogallery_mainvideo",
						"value" => array(
										__( "None", "VCMP_SLUG" ) => "none", 
										__( "Youtube", "VCMP_SLUG" ) => "youtube", 
										__( "Vimeo", "VCMP_SLUG" ) => "vimeo", 
									),
						"description" => __( "Select the type of the main video source.", "VCMP_SLUG" ),
						"admin_label" => false
					),
					
					array( 
							"type" => "textfield", 
							"heading" => __( "Main Youtube Video ID", "VCMP_SLUG" ),
							"param_name" => "videogallery_main_youtube_video",
							'admin_label' => false,
							"dependency" => Array( 
											'element' => "videogallery_mainvideo",
											'value' => array( 'youtube' ),
											),
							"description" => __( "Please enter the main youtube video url. Ex:r-neGA1blsE", "VCMP_SLUG" ),
							"value" => ""
					),
					
					array( 
							"type" => "textfield", 
							"heading" => __( "Main Vimeo Video ID", "VCMP_SLUG" ),
							"param_name" => "videogallery_main_vimeo_video",
							'admin_label' => false,
							"dependency" => Array( 
											'element' => "videogallery_mainvideo",
											'value' => array( 'vimeo' ),
											),
							"description" => __( "Please enter the main vimeo video id. Ex:163662857", "VCMP_SLUG" ),
							"value" => ""
					),
					
					array(
						"type" => "textfield",
						"heading" => __( "Main Video Height", "VCMP_SLUG" ),
						"param_name" => "videogallery_main_height",
						"description" => __( "Please enter the main video height. Ex 400px", "VCMP_SLUG" )
					),
					
					array(
						"type" => "textfield",
						"heading" => __( "Extra Class Name", "VCMP_SLUG" ),
						"param_name" => "el_class",
						"description" => __( "Extra class to be customized via CSS", "VCMP_SLUG" )
					),
					
					array(
						'type' => 'css_editor',
						'heading' => __( 'Custom Design Options', 'VCMP_SLUG' ),
						'param_name' => 'videogallery_css',
						'group' => __( 'Design options', 'VCMP_SLUG' ),
					),
				),
			) 
		);
	}
	

	// Nested Element
	public function video_gallery_content_shortcode_vc() {
		vc_map( 
			array(
				"icon" => 'icon-vc-videogallery-page',
				'name'            => __('Video Item', 'VCMP_SLUG'),
				'base'            => 'video_gallery_content',
				'description'     => __( 'Add video item to your gallery.', 'VCMP_SLUG' ),
				"category" => __('Elegant Mega Addons', 'machine'),
				'content_element' => true,
				'as_child'        => array('only' => 'video_gallery'), // Use only|except attributes to limit parent (separate multiple values with comma)
				'params'          => array(
				
					array(
						"type" => "dropdown",
						"heading" => __( "Video Source", "VCMP_SLUG" ),
						"param_name" => "videogallery_video_source",
						"value" => array(
										__( "None", "VCMP_SLUG" ) => "none", 
										__( "Youtube", "VCMP_SLUG" ) => "youtube", 
										__( "Vimeo", "VCMP_SLUG" ) => "vimeo", 
									),
						"description" => __( "Select the type of the child video source.", "VCMP_SLUG" ),
						"admin_label" => false
					),
					
					array( 
							"type" => "textfield", 
							"heading" => __( "Main Youtube Video ID", "VCMP_SLUG" ),
							"param_name" => "videogallery_youtube_video_id",
							'admin_label' => false,
							"dependency" => Array( 
											'element' => "videogallery_video_source",
											'value' => array( 'youtube' ),
											),
							"description" => __( "Please enter the youtube video url. Ex:r-neGA1blsE", "VCMP_SLUG" ),
							"value" => ""
					),
					
					array( 
							"type" => "textfield", 
							"heading" => __( "Main Vimeo Video ID", "VCMP_SLUG" ),
							"param_name" => "videogallery_vimeo_video_id",
							'admin_label' => false,
							"dependency" => Array( 
											'element' => "videogallery_video_source",
											'value' => array( 'vimeo' ),
											),
							"description" => __( "Please enter the vimeo video id. Ex:163662857", "VCMP_SLUG" ),
							"value" => ""
					),
					
					array( 
							"type" => "attach_image",
							"heading" => __( "Video Thumbnail Image", "VCMP_SLUG" ),
							"param_name" => "videogallery_thumb_img",
							'admin_label' => true,
							"description" => __( "Please choose your video thumbnail image.", "VCMP_SLUG" ),
							"value" => ""
					),
					
					array( 
							"type" => "textfield",
							"heading" => __( "Video Thumbnail Title", "VCMP_SLUG" ),
							"param_name" => "videogallery_thumb_title",
							'admin_label' => true,
							"description" => __( "Please enter the video thumbnail title.", "VCMP_SLUG" ),
							"value" => ""
					),
					array( 
							"type" => "colorpicker",
							"heading" => __( "Video Thumbnail Title Color", "VCMP_SLUG" ),
							"param_name" => "videogallery_thumb_title_color",
							'admin_label' => true,
							"description" => __( "Please choose the video thumbnail title color.", "VCMP_SLUG" ),
							"value" => ""
					),
					
					array( 
							"type" => "textfield",
							"heading" => __( "Video Thumbnail Title Font Size", "VCMP_SLUG" ),
							"param_name" => "videogallery_thumb_title_fontsize",
							'admin_label' => true,
							"description" => __( "Please enter the video thumbnail title font size. Ex:14px", "VCMP_SLUG" ),
							"value" => ""
					),
					
					array( 
							"type" => "textfield",
							"heading" => __( "Video Thumbnail Sub Title", "VCMP_SLUG" ),
							"param_name" => "videogallery_thumb_desc",
							'admin_label' => true,
							"description" => __( "Please enter the video thumbnail sub title.", "VCMP_SLUG" ),
							"value" => ""
					),
					
					array( 
							"type" => "colorpicker",
							"heading" => __( "Video Thumbnail Sub Title Color", "VCMP_SLUG" ),
							"param_name" => "videogallery_thumb_desc_color",
							'admin_label' => true,
							"description" => __( "Please choose the video thumbnail sub title color.", "VCMP_SLUG" ),
							"value" => ""
					),
					
					array( 
							"type" => "textfield",
							"heading" => __( "Video Thumbnail Sub Title Font Size", "VCMP_SLUG" ),
							"param_name" => "videogallery_thumb_desc_fontsize",
							'admin_label' => true,
							"description" => __( "Please enter the video thumbnail sub title font size. Ex:11px", "VCMP_SLUG" ),
							"value" => ""
					),
					
					array(
						"type" => "textfield",
						"heading" => __( "Extra Class Name", "VCMP_SLUG" ),
						"param_name" => "el_class",
						"description" => __( "Extra class to be customized via CSS", "VCMP_SLUG" )
					 ),
				),
			) 
		);
	}
	
	
	
	
	/**
	 * Grid Gallery Shortcode
	 */
	public function vc_video_gallery_shortcode( $atts, $content = null ) {
	
		$output = $el_class = '';
		$videogallery_css = '';
		
		extract( 
			shortcode_atts( 
				array(
					'el_class' => '',
					'videogallery_mainvideo' => '',
					'videogallery_main_youtube_video' => '',
					'videogallery_main_vimeo_video' => '',
					'videogallery_main_height' => '',
					'videogallery_css' => '',
				), $atts 
			) 
		);
		
		
		$videogallery_css = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $videogallery_css, '' ), self::slug, $atts );
		
		$output = '<div class="vcmp-video-wrapper '.esc_attr( $videogallery_css ).' '.esc_attr( $el_class ).'" id="vcmp-video-wrapper">
					<div class="vcmp-video-selected">';
		
			if ( $videogallery_mainvideo == 'youtube' ) {

				$output .= '<div class="vcmp-video-iframe">
								<iframe width="100%" height="'. $videogallery_main_height .'" src="https://www.youtube.com/embed/'. $videogallery_main_youtube_video .'?rel=0" frameborder="0" allowfullscreen></iframe>
							</div>';
			};
			
			
			if ( $videogallery_mainvideo == 'vimeo' ) {

				$output .= '<div class="vcmp-video-iframe">
								<iframe width="100%" height="'. $videogallery_main_height .'" src="https://player.vimeo.com/video/'. $videogallery_main_vimeo_video .'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
							</div>';
			};
						


		$output .= '</div>
					
					<div class="vcmp-video-thumbnails">
						'. do_shortcode($content).'
					</div>
				</div>
					';
					
		return $output;
	}
	
	
	public function nl2p($str) {
		$arr=explode("\n",$str);
		$out='';

		for($i=0;$i<count($arr);$i++) {
			if(strlen(trim($arr[$i]))>0)
				$out.=''.trim($arr[$i]).'';
		}
		return $out;
	}
	

	/**
	 * Grid Gallery Content Items Shortcode
	 */
	public function video_gallery_content_shortcode( $atts, $content = null ) {
	
		$output = $el_class = '';

		extract( 
			shortcode_atts( 
				array(
					'el_class' => '',
					'videogallery_video_source' => '',
					'videogallery_youtube_video_id' => '',
					'videogallery_vimeo_video_id' => '',
					'videogallery_thumb_img' => '',
					'videogallery_thumb_title' => '',
					'videogallery_thumb_title_color' => '',
					'videogallery_thumb_title_fontsize' => '',
					'videogallery_thumb_desc' => '',
					'videogallery_thumb_desc_color' => '',
					'videogallery_thumb_desc_fontsize' => '',
				), $atts 
			) 
		);
		
		$content = $this->nl2p($content);
		
		$videogallery_thumb_img = wp_get_attachment_image_src(intval($videogallery_thumb_img), 'full');
		$videogallery_thumb_img = $videogallery_thumb_img[0];
	
	
		
		$output .=	'
					<div class="vcmp-video-thumb '.esc_attr( $el_class ).'">
						<img src="'.$videogallery_thumb_img.'" alt="'.$videogallery_thumb_title.'">
						<div class="vcmp-video-desc">
							<h1 class="name" style="font-size:'.$videogallery_thumb_title_fontsize.'; color: '.$videogallery_thumb_title_color.'; ">'.$videogallery_thumb_title.'</h1>
							<h5 class="description" style="font-size:'.$videogallery_thumb_desc_fontsize.'; color: '.$videogallery_thumb_desc_color.'; ">'.$videogallery_thumb_desc.'</h5>
						</div>
					';
						
			if ( $videogallery_video_source == 'vimeo' ) {	
				$output .= '<iframe width="100%" height="352" src="https://player.vimeo.com/video/'. $videogallery_vimeo_video_id .'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
			};
			
			if ( $videogallery_video_source == 'youtube' ) {	
				$output .= '<iframe width="100%" height="352" src="https://www.youtube.com/embed/'. $videogallery_youtube_video_id .'?rel=0" frameborder="0" allowfullscreen></iframe>';
			};
						
		$output .= '</div>';

		return $output;
		
	}

}


// A must for container functionality, replace Wbc_Item with your base name from mapping for parent container
	if(class_exists('WPBakeryShortCodesContainer')){
		class WPBakeryShortCode_video_gallery extends WPBakeryShortCodesContainer {

		}
	}

	// Replace Wbc_Inner_Item with your base name from mapping for nested element
	if(class_exists('WPBakeryShortCode')){
		class WPBakeryShortCode_video_gallery_content extends WPBakeryShortCode {

		}
	}
// Finally initialize code
new VcmpVideoGallery();

	