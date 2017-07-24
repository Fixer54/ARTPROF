<?php
/* FUNCTION */
class fg_mosaic_functions extends VC_FastGallery_Mosaic_Class {
	public function vcfastgallery_mosaic_function( $atts, $content = null ) {
			static $instance = 0;
			$instance++;
		
		
			extract( shortcode_atts( array(
				'fg_gallery_name' => '',
				'fg_gallery_name_show' => 'false',
				'fgm_layout' => 'fg_layout1',
				'fg_type' => 'prettyphoto',
				'fgm_height' => '100',
				'fgm_custom_height' => '150',
				'fgm_allow'	=> 'false',
				'fg_responsive'	=> 'fg_responsive',
				'fg_over_image'	=> 'fg_over_image_on',
				'fg_pagination_active' => 'off',
				'fg_pagination_number' => '10',
				'fg_pagination_style' => 'fg_pagination_style1',				
				'images' => '', 
				'fg_style' => 'fg_style1',
				'fg_main_color' => '#FC615D',
				'fg_main_color_opacity' => '1',
				'fg_secondary_color' => '#FFFFFF',
				'fg_spacing_active' => 'off',
				'fgm_padding' => '20',
				'fgm_image_lightbox' => 'plus',
				'fgm_image_width'	=> 'small',
				'fg_gallery_name_font_size' => '20',
				'fg_gallery_name_font_color' => '#FC615D',
				'fg_gallery_name_text_align' => 'center',		
				  
				'itemtag' => 'div', 
				'icontag' => 'div',
				'captiontag' => 'div',
				
				'pp_animation_speed' => 'fast',
				'pp_autoplay' => 'true',
				'pp_time' => '2000',
				'pp_title' => 'true',
				'pp_social' => 'true',
				'pb_thumbs' => 'true',
				'pb_time' => '2000',
				'pb_autoplay' => 'true',				
				'pb_counter' => 'true',
				'pb_history' => 'false',
				'pb_loop' => 'true',
				'mp_gallery' => 'true',
				'lg_mode' => 'lg-slide',
				'lg_speed' => '2000', 
				'lg_thumbnail' => 'true',
				'lg_controls' => 'true',				
				'custom_url_target' => '_blank', 
				
				'fgm_image_lightbox_size' => 'fgm_default',

				'fg_seo'	=> 'off',

				'fg_animate' 			=> 'off',
				'fg_animate_effect' 	=> 'fade-in',
				'fg_delay' 			=> '0' 		 					
			), $atts ) );
		    
			/* LOAD JS/CSS */
			wp_enqueue_style( 'fastgallery_mosaic-vc-main-style' );			 
			wp_enqueue_script('fastgallery_mosaic-frontend-script');
			wp_enqueue_style('fonts-vc');
			wp_enqueue_script( 'fgm_removeWhitespace' );
 			wp_enqueue_script( 'fgm_collagePlus' );			
			
			if($fg_type == 'photobox') {
				wp_enqueue_style( 'photobox-vc' );
				wp_enqueue_style( 'photoboxie-vc' );
				wp_enqueue_style( 'photobox-style-vc' );
				wp_enqueue_script( 'photobox-js');
			}
			if($fg_type == 'prettyphoto') {
				wp_enqueue_style( 'prettyPhoto-vc' );
				wp_enqueue_script( 'prettyPhoto-js');
			}
			if($fg_type == 'magnific-popup') {
				wp_enqueue_style( 'magnific-popup-vc' );
				wp_enqueue_script( 'magnific-popup-js');
			}			
			if($fg_type == 'lightgallery') {
				wp_enqueue_style( 'fg-lightgallery' );
				wp_enqueue_script( 'fg-lightgallery-js');
			}
						
			$content = wpb_js_remove_wpautop($content, true);

			/* LOAD VAR */
			$itemtag = tag_escape($itemtag);
			$captiontag = tag_escape($captiontag);
			$icontag = tag_escape($icontag);
			$valid_tags = wp_kses_allowed_html( 'post' );
			if ( ! isset( $valid_tags[ $itemtag ] ) )
				$itemtag = 'dl';
			if ( ! isset( $valid_tags[ $captiontag ] ) )
				$captiontag = 'dd';
			if ( ! isset( $valid_tags[ $icontag ] ) )
				$icontag = 'dt';

			// CHECK MAIN COLOR
			$rgb_main_color = fastgallery_mosaic_vc_hex2rgb($fg_main_color);
			$rgba_main_color = "rgba( ".$rgb_main_color[0]." , ".$rgb_main_color[1]." , ".$rgb_main_color[2]." , ".$fg_main_color_opacity.")";	
			$rgb_secondary_color = fastgallery_mosaic_vc_hex2rgb($fg_secondary_color);
			$rgba_secondary_color = "rgba( ".$rgb_secondary_color[0]." , ".$rgb_secondary_color[1]." , ".$rgb_secondary_color[2]." , 0.3)";	
			// END MAIN COLOR
 
			$selector = "gallery-{$instance}";
		  
			$gallery_style = $gallery_div = '';
				$gallery_style = "<style type='text/css'>
				#{$selector} {
					margin: auto;
				}
				#{$selector}.FGM-Collage .fg-gallery-item {
					text-align: center;			
				}
				#{$selector} .fg-gallery-caption {
					margin-left: 0;
				}
				#{$selector}.fastgallery_mosaic .fg-gallery-caption, 
				#{$selector}.fastgallery_mosaic .fg-gallery-caption:hover {
					background-color:".$rgba_main_color.";
				}
				#{$selector}.fastgallery_mosaic.gallery .gallery-icon .fg_zoom a, 
				#{$selector}.fastgallery_mosaic.gallery .gallery-icon .fg_zoom a:hover {
					color:".$fg_main_color.";
				}
				#{$selector}.fastgallery_mosaic.fg_style1 .fg-gallery-caption {
					color:".$fg_secondary_color.";	
				}
				#{$selector}.fastgallery_mosaic.gallery.fg_style2 .gallery-icon .fg_zoom a {
					background:".$rgba_secondary_color.";
				}
				#{$selector}.fastgallery_mosaic.fg_style2 .fg-gallery-caption {
					color:".$fg_secondary_color.";	
				}			
				#{$selector}.fastgallery_mosaic.gallery.fg_style3 .fg_zoom, #{$selector}.fastgallery_mosaic.gallery.fg_style3 .fg_zoom:hover {
					background:".$rgba_main_color.";
				}
				#{$selector}.fastgallery_mosaic.fg_style3 .fg-gallery-caption {
					color:".$fg_secondary_color.";	
				}				
				#{$selector}.fastgallery_mosaic.fg_style4 .fg-gallery-caption,			
				#{$selector}.fastgallery_mosaic.gallery.fg_style4 .gallery-icon .fg_zoom a, 
				#{$selector}.fastgallery_mosaic.gallery.fg_style4 .gallery-icon .fg_zoom a:hover {
					color:".$fg_secondary_color.";
				}
				#{$selector}.fastgallery_mosaic.gallery.fg_style4 .gallery-icon .fg_zoom a, 
				#{$selector}.fastgallery_mosaic.gallery.fg_style4 .gallery-icon .fg_zoom a:hover	{
					background:".$rgba_main_color.";
				}			
				#{$selector}.fastgallery_mosaic.gallery.fg_style5 .gallery-icon .fg_zoom a, 
				#{$selector}.fastgallery_mosaic.gallery.fg_style5 .gallery-icon .fg_zoom a:hover	{
					color:".$fg_secondary_color.";
					background-color:".$rgba_main_color.";
				}					
				#{$selector}.fastgallery_mosaic.gallery.fg_style6 .gallery-icon .fg_zoom a,
				#{$selector}.fastgallery_mosaic.gallery.fg_style6 .gallery-icon .fg_zoom a:hover {
					color:".$fg_secondary_color.";
					background:".$rgba_main_color.";				
				}
			
				#{$selector}.fastgallery_mosaic.fg_style6 .fg-gallery-caption {
					color:".$fg_secondary_color.";	
				}
				#{$selector}.fastgallery_mosaic.gallery.fg_style7 .gallery-icon .fg_zoom a,
				#{$selector}.fastgallery_mosaic.gallery.fg_style7 .gallery-icon .fg_zoom a:hover {
					color:".$fg_secondary_color.";
					background:".$rgba_main_color.";				
				}		
				#{$selector}.fastgallery_mosaic.fg_style7 .fg-gallery-caption {
					color:".$fg_secondary_color.";	
				}
				
				#{$selector}.fastgallery_mosaic.gallery.fg_style8 .gallery-icon .fg_zoom a,
				#{$selector}.fastgallery_mosaic.gallery.fg_style8 .gallery-icon .fg_zoom a:hover {
					color:".$fg_secondary_color.";
					background:".$rgba_main_color.";				
				}
			
				#{$selector}.fastgallery_mosaic.fg_style8 .fg-gallery-caption {
					color:".$fg_secondary_color.";	
				}
				
				#{$selector}.fastgallery_mosaic.gallery.fg_style9 .gallery-icon .fg_zoom a,
				#{$selector}.fastgallery_mosaic.gallery.fg_style9 .gallery-icon .fg_zoom a:hover {
					color:".$fg_secondary_color.";
					background:".$rgba_main_color.";				
				}		
				#{$selector}.fastgallery_mosaic.fg_style9 .fg-gallery-caption {
					color:".$fg_secondary_color.";	
				}
				
				#{$selector}.fastgallery_mosaic.gallery.fg_style10 .gallery-icon .fg_zoom a,
				#{$selector}.fastgallery_mosaic.gallery.fg_style10 .gallery-icon .fg_zoom a:hover {
					color:".$fg_secondary_color.";
					background:".$rgba_main_color.";				
				}		
				#{$selector}.fastgallery_mosaic.fg_style10 .fg-gallery-caption {
					color:".$fg_secondary_color.";	
				}";
		
				if($fgm_image_lightbox == 'zoomin') {
					$gallery_style .= '#'.$selector.'.fastgallery_mosaic.gallery .gallery-icon .icon-plus:before {	
										content: "\e6ef"!important;
					}';
				}
				if($fgm_image_lightbox == 'image') {
					$gallery_style .= '#'.$selector.'.fastgallery_mosaic.gallery .gallery-icon .icon-plus:before {	
										content: "\e687"!important;
					}';
				}	
				if($fgm_image_lightbox == 'images') {
					$gallery_style .= '#'.$selector.'.fastgallery_mosaic.gallery .gallery-icon .icon-plus:before {	
										content: "\e605"!important;
					}';
				}	
				if($fgm_image_lightbox == 'spinner') {
					$gallery_style .= '#'.$selector.'.fastgallery_mosaic.gallery .gallery-icon .icon-plus:before {	
										content: "\e6e7"!important;
					}';
				}
				if($fgm_image_lightbox == 'search') {
					$gallery_style .= '#'.$selector.'.fastgallery_mosaic.gallery .gallery-icon .icon-plus:before {	
										content: "\e6ee"!important;
					}';
				}

				if($fgm_image_width == 'small') {
					$gallery_style .= "#{$selector}.fastgallery_mosaic.gallery span {
										font-size:20px!important;
					}
					#{$selector}.fastgallery_mosaic.gallery .gallery-icon .fg-zoom-icon {
										margin-left:-10px!important;
										margin-top:-40px;
					}
					#{$selector}.fastgallery_mosaic.gallery.fg_style2 .gallery-icon .fg-zoom-icon,
					#{$selector}.fastgallery_mosaic.gallery.fg_style5 .gallery-icon .fg-zoom-icon  {
										margin-top:-10px;
					}		
					#{$selector}.fastgallery_mosaic.gallery .gallery-icon.no-caption .fg-zoom-icon {
										margin-top:-10px!important;
					}
					#{$selector}.fastgallery_mosaic.fg_style7 .fg-gallery-caption,
					#{$selector}.fastgallery_mosaic.fg_style8 .fg-gallery-caption {
										top:55%;
					}
					";
				}
				
				if($fgm_image_width == 'medium') {
					$gallery_style .= "#{$selector}.fastgallery_mosaic.gallery span {
										font-size:30px!important;
					}
					#{$selector}.fastgallery_mosaic.gallery .gallery-icon .fg-zoom-icon {
										margin-left:-15px!important;
										margin-top:-40px;
					}
					#{$selector}.fastgallery_mosaic.gallery.fg_style2 .gallery-icon .fg-zoom-icon,
					#{$selector}.fastgallery_mosaic.gallery.fg_style5 .gallery-icon .fg-zoom-icon  {
										margin-top:-15px;
					}		
					#{$selector}.fastgallery_mosaic.gallery .gallery-icon.no-caption .fg-zoom-icon {
										margin-top:-15px!important;
					}
					#{$selector}.fastgallery_mosaic.fg_style7 .fg-gallery-caption,
					#{$selector}.fastgallery_mosaic.fg_style8 .fg-gallery-caption {
										top:55%;
					}
					";
				}
		
				if($fgm_image_width == 'large') {
					$gallery_style .= "#{$selector}.fastgallery_mosaic.gallery span {
										font-size:50px!important;
					}
					#{$selector}.fastgallery_mosaic.gallery .gallery-icon .fg-zoom-icon {
										margin-left:-25px!important;
										margin-top:-50px;
					}
					#{$selector}.fastgallery_mosaic.gallery.fg_style2 .gallery-icon .fg-zoom-icon,
					#{$selector}.fastgallery_mosaic.gallery.fg_style5 .gallery-icon .fg-zoom-icon {
										margin-top:-25px;
					}		
					#{$selector}.fastgallery_mosaic.gallery .gallery-icon.no-caption .fg-zoom-icon {
										margin-top:-25px!important;
					}
					#{$selector}.fastgallery_mosaic.fg_style7 .fg-gallery-caption,
					#{$selector}.fastgallery_mosaic.fg_style8 .fg-gallery-caption {
										top:55%;
					}
					";
				}	

				if($fg_pagination_active == 'on') {
					$gallery_style .= "
						#{$selector}.fastgallery_mosaic.fg_pagination_style1 ul.fg_pagination li a {
							background:".$rgba_main_color.";
							color:".$fg_secondary_color.";
						}
						#{$selector}.fastgallery_mosaic.fg_pagination_style1 ul.fg_pagination li a:hover {
							background:".$fg_secondary_color.";
							color:".$rgba_main_color.";
						}
						#{$selector}.fastgallery_mosaic.fg_pagination_style1 ul.fg_pagination li.fg_current {
							background:".$fg_secondary_color.";
							color:".$rgba_main_color.";
						}
						#{$selector}.fastgallery_mosaic.fg_pagination_style2 ul.fg_pagination li a {
							color:".$fg_secondary_color.";
						}
						#{$selector}.fastgallery_mosaic.fg_pagination_style2 ul.fg_pagination li a:hover {
							color:".$rgba_main_color.";
						}
						#{$selector}.fastgallery_mosaic.fg_pagination_style2 ul.fg_pagination li.fg_current {
							color:".$rgba_main_color.";
						}																		
					";
				}
				
				if($fg_gallery_name_show == 'true') {
					$gallery_style .= ".fg_gallery_title-{$instance}.fg_gallery_name {
							font-size:".$fg_gallery_name_font_size."px;
							color:".$fg_gallery_name_font_color.";
							text-align:".$fg_gallery_name_text_align.";
					}";
				}
				
				$gallery_style .= "</style>";	

				if($fgm_height == 'custom_value') {
					$fgm_height = $fgm_custom_height;
				}

				// JAVASCRIPT MOSAIC //

				$gallery_script = "<script type=\"text/javascript\">";
				
				if($fg_responsive == 'fg_responsive') {
			
					$gallery_script .= "jQuery(function($){
							$(window).load(function () {
						window.onresize = function(){ location.reload(); }
						var mq = window.matchMedia( \"(min-width: 1000px)\" );
						if (mq.matches) {	
							
									function collage() {
										$('#$selector.FGM-Collage').removeWhitespace().collagePlus(
										{
												'targetHeight'  		: ".$fgm_height.",
												'padding'				: ".$fgm_padding.",
												'allowPartialLastRow'   : ".$fgm_allow."
											}
										);
									};						
									$(document).ready(function(){
										collage();
									});	
									var resizeTimer = null;
									$(window).bind('resize', function() {
										$('.FGM-Collage .fg-gallery-item').css(\"opacity\", 0);
										if (resizeTimer) clearTimeout(resizeTimer);
										resizeTimer = setTimeout(collage, 200);
									});	
						}
					});
					});";
						
				} else {
					
					$gallery_script .= "jQuery(function($){
						$(window).load(function () {
									$(document).ready(function(){
										collage();
									});
									function collage() {
										$('#$selector.FGM-Collage').removeWhitespace().collagePlus(
										{
												'targetHeight'  		: ".$fgm_height.",
												'padding'				: ".$fgm_padding.",
												'allowPartialLastRow'   : ".$fgm_allow."
											}
										);
									};	
									var resizeTimer = null;
									$(window).bind('resize', function() {
										$('.FGM-Collage .fg-gallery-item').css(\"opacity\", 0);
										if (resizeTimer) clearTimeout(resizeTimer);
										resizeTimer = setTimeout(collage, 200);
									});	
					});
					});";		
						
				}
											
				$gallery_script .= "</script>";
	
				// #JAVASCRIPT MOSAIC //
	
				// PHOTOBOX
				if($fg_type == 'photobox') { // PHOTOBOX CSS/JS
						
					$gallery_script .= '<script type="text/javascript">
						jQuery(function($){
							$(\'#'.$selector.'\').photobox(\'a\', { 
								thumbs: '.$pb_thumbs.', 
								time: '.$pb_time.',
								autoplay: '.$pb_autoplay.',
								counter: '.$pb_counter.',
								history: '.$pb_history.',
								loop: '.$pb_loop.',				 
							});
						});
					</script>';
					
				} // CLOSE PHOTOBOX CSS/JS

				// PRETTYPHOTO
				if($fg_type == 'prettyphoto') {
					$gallery_script .= '<script type="text/javascript">		
					jQuery(function($){
						jQuery(document).ready(function($){
								$("#'.$selector.' a[data-rel-fg^=\'prettyPhoto\']").prettyPhoto({
									animation_speed: \''.$pp_animation_speed.'\',
									slideshow: '.$pp_time.',
									autoplay_slideshow: '.$pp_autoplay.',
									show_title: '.$pp_title.',';
									if($pp_social == 'false') {				
										$gallery_script .= 'social_tools: false';
									}
					$gallery_script .= '});
						}); 
					});
					</script>';
				}
				
				// MAGNIFIC POPUP
				if($fg_type == 'magnific-popup') {
					$gallery_script .= '<script type="text/javascript">
						jQuery(function($){
							$(\'#'.$selector.' .fg_magnificPopup\').magnificPopup({
								type: \'image\',					
								gallery:{
									enabled:'.$mp_gallery.'
								}
								});
						});		
					</script>';
				}
				
				// LIGHTGALLERY
				if($fg_type == 'lightgallery') {
					if($lg_mode == 'lg-fade') {
						$lg_mode = 'fade';	
					} else {
						$lg_mode = 'slide';
					}
					$gallery_script .= '<script type="text/javascript">
						jQuery(function($){
							$(\'#'.$selector.'.gallery.fastgallery_mosaic\').lightGallery({
								mode:\''.$lg_mode.'\',
								speed: '.$lg_speed.',
								thumbnail: '.$lg_thumbnail.',
								controls: '.$lg_controls.'								
							});
						});		
					</script>';												
				}
				
				
				// ANIMATION
				if($fg_animate == 'on') {
					wp_enqueue_style( 'fg-animations' );
					wp_enqueue_script( 'fg-appear-js');
					wp_enqueue_script( 'fg-animate-js');
					$animation_info = " animate-in' data-anim-type='".$fg_animate_effect."' data-anim-delay='".$fg_delay."'>";			
				} else {
					$animation_info = " '>";					
				}
				// #ANIMATION
				
				
				// CUSTOM URL
				if($fg_type == 'custom_url' || $fg_type == 'only_image') {
					$gallery_script .= '';
				}

				// GALLERY NAME
				$gallery_name = '';
				if($fg_gallery_name_show == 'true') {
					$gallery_name = '<h2 class="fg_gallery_title-'.$instance.' fg_gallery_name">'.$fg_gallery_name.'</h2>';	
				}
				
				$size_class = sanitize_html_class( $fgm_layout );
				$gallery_div = $gallery_name . "<div id='$selector' class='FGM-Collage gallery galleryid-{$instance} gallery-size-{$size_class} fastgallery_mosaic ".$fg_responsive." ".$fg_style." ".$fg_over_image." ".$fgm_layout."'>";
				$output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_script . "\n\t\t" . $gallery_div );
				
				$images = explode( ',', $images );
				
				// PAGINATION ### Change array for pagination ###
				if($fg_pagination_active == 'on') {
				
					$pagination = get_query_var('fg_page');		
					
					if(!isset($pagination) || empty($pagination)) { $pagination = 1; }
					
					$images_array = array_chunk($images,$fg_pagination_number);
													
					$pag = $pagination - 1;
					
					$images = $images_array[$pag];				

					$num_page_for_pagination = count($images_array); // VALUE FOR PAGINATION FUNCTION

				}
				// #PAGINATION				
							
				$i = 0;
									
				foreach ( $images as $id ) {
					
					// ALT IMAGE
					$alt = get_post_meta($id, '_wp_attachment_image_alt', true);
					// END ALT IMAGE

					$image_meta = wp_get_attachment_metadata( $id );
	 
					$orientation = '';
					if ( isset( $image_meta['height'], $image_meta['width'] ) )
					$orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';

					
					if($fgm_layout == 'fg_layout1') {
					
						$tag_grid_array = array('fgm-2-6','fgm-1-6','fgm-3-6','fgm-1-6','fgm-2-6','fgm-3-6','fgm-3-6','fgm-2-6','fgm-1-6','fgm-2-6','fgm-1-6','fgm-3-6',
												'fgm-2-6','fgm-1-6','fgm-3-6','fgm-1-6','fgm-2-6','fgm-3-6','fgm-3-6','fgm-2-6','fgm-1-6','fgm-2-6','fgm-1-6','fgm-3-6',
												'fgm-2-6','fgm-1-6','fgm-3-6','fgm-1-6','fgm-2-6','fgm-3-6','fgm-3-6','fgm-2-6','fgm-1-6','fgm-2-6','fgm-1-6','fgm-3-6',
												'fgm-2-6','fgm-1-6','fgm-3-6','fgm-1-6','fgm-2-6','fgm-3-6','fgm-3-6','fgm-2-6','fgm-1-6','fgm-2-6','fgm-1-6','fgm-3-6',
												'fgm-2-6','fgm-1-6','fgm-3-6','fgm-1-6','fgm-2-6','fgm-3-6','fgm-3-6','fgm-2-6','fgm-1-6','fgm-2-6','fgm-1-6','fgm-3-6');
					}
					if($fgm_layout == 'fg_layout2') {
						
						$tag_grid_array = array('fgm-1-6','fgm-1-6','fgm-1-6','fgm-3-6','fgm-2-6','fgm-1-6','fgm-2-6','fgm-3-6','fgm-3-6','fgm-2-6','fgm-2-6','fgm-1-6',
												'fgm-1-6','fgm-1-6','fgm-1-6','fgm-3-6','fgm-2-6','fgm-1-6','fgm-2-6','fgm-3-6','fgm-3-6','fgm-2-6','fgm-2-6','fgm-1-6',									
												'fgm-1-6','fgm-1-6','fgm-1-6','fgm-3-6','fgm-2-6','fgm-1-6','fgm-2-6','fgm-3-6','fgm-3-6','fgm-2-6','fgm-2-6','fgm-1-6',
												'fgm-1-6','fgm-1-6','fgm-1-6','fgm-3-6','fgm-2-6','fgm-1-6','fgm-2-6','fgm-3-6','fgm-3-6','fgm-2-6','fgm-2-6','fgm-1-6',
												'fgm-1-6','fgm-1-6','fgm-1-6','fgm-3-6','fgm-2-6','fgm-1-6','fgm-2-6','fgm-3-6','fgm-3-6','fgm-2-6','fgm-2-6','fgm-1-6');
					
					}
					if($fgm_layout == 'fg_layout3') {
						
						$tag_grid_array = array('fgm-3-6','fgm-3-6','fgm-3-6','fgm-2-6','fgm-2-6','fgm-2-6','fgm-1-6','fgm-1-6','fgm-1-6','fgm-3-6','fgm-2-6','fgm-1-6',
												'fgm-3-6','fgm-3-6','fgm-3-6','fgm-2-6','fgm-2-6','fgm-2-6','fgm-1-6','fgm-1-6','fgm-1-6','fgm-3-6','fgm-2-6','fgm-1-6',									
												'fgm-3-6','fgm-3-6','fgm-3-6','fgm-2-6','fgm-2-6','fgm-2-6','fgm-1-6','fgm-1-6','fgm-1-6','fgm-3-6','fgm-2-6','fgm-1-6',
												'fgm-3-6','fgm-3-6','fgm-3-6','fgm-2-6','fgm-2-6','fgm-2-6','fgm-1-6','fgm-1-6','fgm-1-6','fgm-3-6','fgm-2-6','fgm-1-6',
												'fgm-3-6','fgm-3-6','fgm-3-6','fgm-2-6','fgm-2-6','fgm-2-6','fgm-1-6','fgm-1-6','fgm-1-6','fgm-3-6','fgm-2-6','fgm-1-6');
					
					}
					if($fgm_layout == 'fg_layout4') {
						
						$tag_grid_array = array('fgm-1-6','fgm-2-6','fgm-3-6','fgm-1-6','fgm-2-6','fgm-3-6','fgm-1-6','fgm-2-6','fgm-2-6','fgm-2-6','fgm-1-6','fgm-3-6',
												'fgm-1-6','fgm-2-6','fgm-3-6','fgm-1-6','fgm-2-6','fgm-3-6','fgm-1-6','fgm-2-6','fgm-2-6','fgm-2-6','fgm-1-6','fgm-3-6',									
												'fgm-1-6','fgm-2-6','fgm-3-6','fgm-1-6','fgm-2-6','fgm-3-6','fgm-1-6','fgm-2-6','fgm-2-6','fgm-2-6','fgm-1-6','fgm-3-6',
												'fgm-1-6','fgm-2-6','fgm-3-6','fgm-1-6','fgm-2-6','fgm-3-6','fgm-1-6','fgm-2-6','fgm-2-6','fgm-2-6','fgm-1-6','fgm-3-6',
												'fgm-1-6','fgm-2-6','fgm-3-6','fgm-1-6','fgm-2-6','fgm-3-6','fgm-1-6','fgm-2-6','fgm-2-6','fgm-2-6','fgm-1-6','fgm-3-6');
					
					}
					if($fgm_layout == 'fg_layout5') {
						
						$tag_grid_array = array('fgm-1-6','fgm-1-6','fgm-2-6','fgm-2-6','fgm-3-6','fgm-3-6','fgm-1-6','fgm-1-6','fgm-2-6','fgm-2-6','fgm-3-6','fgm-3-6',
												'fgm-1-6','fgm-1-6','fgm-2-6','fgm-2-6','fgm-3-6','fgm-3-6','fgm-1-6','fgm-1-6','fgm-2-6','fgm-2-6','fgm-3-6','fgm-3-6',									
												'fgm-1-6','fgm-1-6','fgm-2-6','fgm-2-6','fgm-3-6','fgm-3-6','fgm-1-6','fgm-1-6','fgm-2-6','fgm-2-6','fgm-3-6','fgm-3-6',
												'fgm-1-6','fgm-1-6','fgm-2-6','fgm-2-6','fgm-3-6','fgm-3-6','fgm-1-6','fgm-1-6','fgm-2-6','fgm-2-6','fgm-3-6','fgm-3-6',
												'fgm-1-6','fgm-1-6','fgm-2-6','fgm-2-6','fgm-3-6','fgm-3-6','fgm-1-6','fgm-1-6','fgm-2-6','fgm-2-6','fgm-3-6','fgm-3-6');
					
					}
											
					if(empty($tag_grid_array[$i])) { $tag_grid_array[$i] = 'fgm-2-6'; }
							
					$tag_grid = $tag_grid_array[$i];	
					
					if($fg_seo == 'on') {
						
						$default_attr = array(
								'title' => trim(strip_tags(($attachment->post_title))),
								'alt'   => trim(strip_tags( get_post_meta($attachment->ID, '_wp_attachment_image_alt', true) ))			
						);
						$link_text = wp_get_attachment_image( $id, $tag_grid_array[$i], false, $default_attr );
					
					} else {
						
						$link_text = wp_get_attachment_image( $id, $tag_grid_array[$i] );
					
					}					
					
					
					
					/* FUNCTION THUMBS */
					
					$_post = get_post( $id );
					$image_attributes = wp_get_attachment_image_src( $_post->ID, $fgm_image_lightbox_size );
					if($fg_type == 'custom_url') {					
						$url = get_post_meta( $id, '_custom_url', true );						
					} else {
						$url = $image_attributes[0];
					}
					$attachment_caption_array = get_post( $_post->ID );
					$attachment_caption	= $attachment_caption_array->post_excerpt;		

					// CHECK CAPTION
					$caption_check = '';
					if(empty($attachment_caption)) {
						$caption_check = 'no-caption';
					}
					// END CHECK CAPTION
								
					// LIGHTGALLERY
					if($fg_type != 'lightgallery') {	
						$output .= "<{$itemtag} class='fg-gallery-item ".$animation_info."";
					} else {
						$output .= "<{$itemtag} data-src='$url' class='fg-gallery-item ".$animation_info."";	
					}
					// #LIGHTGALLERY					

					$output .= "<{$icontag} class='gallery-icon {$orientation} $caption_check'>";

					if($fg_type == 'lightgallery') {
						$output .= "<div class='fg_zoom'>$link_text<a href='$url'><span class='fg-zoom-icon icon-plus'></span></a></div>";
					} elseif($fg_type == 'custom_url') {
						$output .= "<div class='fg_zoom'>$link_text<a href='$url' target='$custom_url_target'><span class='fg-zoom-icon icon-plus'></span></a></div>";					
					} else {
						$output .= "<div class='fg_zoom'>$link_text<a href='$url' title=\"$attachment_caption\" data-rel-fg='prettyPhoto[album-{$instance}]' class='fg_magnificPopup'><span class='fg-zoom-icon icon-plus'></span><span style='display:none'>$link_text</span></a></div>";							
					}					
			
					/* #FUNCTION THUMBS */
							
					if (!empty($attachment_caption)) {
					$output .= "
						<{$captiontag} class='fg-wp-caption-text fg-gallery-caption'><div class='caption-container'>
						" . $attachment_caption . "
						</div></{$captiontag}>";
					}
					$output .= "</{$icontag}></{$itemtag}>";
					$i++;
					}	// CLOSE FOREACH
					
					
					
								 
					$output .= "
					</div>\n";	
					
				if($fg_pagination_active == 'on') {
				
					$output .= '<div id="'.$selector.'" class="fastgallery_mosaic '.$fg_pagination_style.'">'.get_fg_pagination($num_page_for_pagination,$pagination).'</div>';
				
				}					
				return $output;
}









}
new fg_mosaic_functions();

function fastgallery_mosaic_add_image_sizes() {

	add_image_size( 'fgm-2-6', 1000 , 800 , true );
	add_image_size( 'fgm-3-6', 1200 , 800 , true );
	add_image_size( 'fgm-1-6', 1400 , 800 , true );
	add_image_size( 'fgm_default', 1000 , 800 , true );

}

add_action( 'init', 'fastgallery_mosaic_add_image_sizes' );

// HEX FUNCTION
function fastgallery_mosaic_vc_hex2rgb($hex) {

   $hex = str_replace("#", "", $hex);

if(strlen($hex) == 3) {
		$r = hexdec(substr($hex,0,1).substr($hex,0,1));
		$g = hexdec(substr($hex,1,1).substr($hex,1,1));
		$b = hexdec(substr($hex,2,1).substr($hex,2,1));
	} else {
		$r = hexdec(substr($hex,0,2));
		$g = hexdec(substr($hex,2,2));
		$b = hexdec(substr($hex,4,2));
	}
	$rgb = array($r, $g, $b);
	return $rgb;
}

?>