<?php
/* FUNCTION */
class fg_functions extends VC_FastGallery_Class {
	public function vcfastgallery_function( $atts, $content = null ) {
			static $instance = 0;
			$instance++;
		
			extract( shortcode_atts( array(
				'fg_gallery_name' => '',
				'fg_gallery_name_show' => 'false',
				'columns' => '1',
				'fg_type' => 'prettyphoto',
				'size' => 'fg-normal',
				'fg_responsive' => 'fg_responsive',
				'fg_style' => 'fg_style1',
				'fg_over_image' => 'fg_over_image_on',
				'fg_thumbs_one' => 'off',
				'fg_lazyload' => 'off',
				'fg_lazyload_effect' => 'yes',
				'fg_pagination_active' => 'off',
				'fg_pagination_number' => '10',
				'fg_pagination_style' => 'fg_pagination_style1',
				'fg_main_color' => '#FC615D',
				'fg_main_color_opacity' => '1',
				'fg_secondary_color' => '#FFFFFF',
				'fg_spacing_active' => 'off',
				'fg_spacing' => '20',
				'fg_gallery_name_font_size' => '20',
				'fg_gallery_name_font_color' => '#FC615D',
				'fg_gallery_name_text_align' => 'center',
				'fg_image_lightbox' => 'plus',		
				'images' => '',   
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
				'fg_caption' => 'off',
				'fg_autoplay' => 'true',
				'fg_nav' => 'thumbs',
				'fg_navposition' => 'bottom',
				'fg_allowfullscreen' => 'true',
				'fg_transition' => 'slide',
				'fg_arrow' => 'true',
				'fg_fit' => 'none',
				'fg_loop' => 'true',
				'fg_thumbs_grid' => 'fg-normal',
				'fg_thumbs_masonry' => 'fg-masonry',
				'fg_thumbs_lightbox' => 'large',
				'lg_mode' => 'lg-slide',
				'lg_speed' => '2000',
				'lg_thumbnail' => 'true',
				'lg_controls' => 'true',
				'custom_url_target' => '_blank', 
				'fg_active_custom_responsive' => 'fg_responsive',
				'fg_smartphone_p_columns' => '1',
				'fg_smartphone_l_columns' => '1',
				'fg_tablet_p_columns' => '1',
				'fg_tablet_l_columns' => '1',
				'fg_desktop_medium_columns' => '1',
				'fg_desktop_small_columns' => '1',
				'fg_animate' 			=> 'off',
				'fg_animate_effect' 	=> 'fade-in',
				'fg_delay' 			=> '0' 		 					
			), $atts ) );
		    
			/* LOAD JS/CSS */
						 
			wp_enqueue_script('fastgallery-frontend-script');
			wp_enqueue_style('fonts-vc');
			
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
			if($fg_type == 'fotorama') {
				wp_enqueue_style( 'fotorama-css-vc' );
				wp_enqueue_script( 'fotorama-js');
			}
			if($fg_type == 'lightgallery') {
				wp_enqueue_style( 'fg-lightgallery' );
				wp_enqueue_script( 'fg-lightgallery-js');
			}
			if($fg_type == 'photoswipe') {
				wp_enqueue_style( 'fg-photoswipe' );
				wp_enqueue_style( 'fg-photoswipe-default-skin' );
				wp_enqueue_script( 'fg-photoswipe-js');
				wp_enqueue_script( 'fg-photoswipe-ui-default-js');
				wp_enqueue_script( 'fg-photoswipe-general-js');
			}												
			if($size == 'fg-masonry') {
				wp_enqueue_script( 'fg-masonry' );
			}
			
			/* CHECK CUSTOM RESPONSIVE */
			if($fg_responsive = 'fg_responsive') {
				if($fg_active_custom_responsive == 'fg_responsive') {
					$fg_responsive = 'fg_responsive';
				} else {
					wp_enqueue_style( 'custom-responsive-vc' );
					$fg_responsive = 'fg_smartphone_p_col-'.$fg_smartphone_p_columns.' fg_smartphone_l_col-'.$fg_smartphone_l_columns.' fg_tablet_p_col-'.$fg_tablet_p_columns.' fg_tablet_l_col-'.$fg_tablet_l_columns.' fg_desktop_medium_col-'.$fg_desktop_medium_columns.' fg_desktop_small_col-'.$fg_desktop_small_columns.'';				
				}	
			}
						
			$content = wpb_js_remove_wpautop($content, true); // fix unclosed/unwanted paragraph tags in $content

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
 
			$columns = intval($columns);
			$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
			$float = is_rtl() ? 'right' : 'left';

			// CHECK MAIN COLOR
			$rgb_main_color = fastgallery_vc_hex2rgb($fg_main_color);
			$rgba_main_color = "rgba( ".$rgb_main_color[0]." , ".$rgb_main_color[1]." , ".$rgb_main_color[2]." , ".$fg_main_color_opacity.")";	
			$rgb_secondary_color = fastgallery_vc_hex2rgb($fg_secondary_color);
			$rgba_secondary_color = "rgba( ".$rgb_secondary_color[0]." , ".$rgb_secondary_color[1]." , ".$rgb_secondary_color[2]." , 0.3)";	
			// END MAIN COLOR
 
			$selector = "gallery-{$instance}";
		  
			$gallery_style = $gallery_div = '';
			
			if($fg_type == 'photoswipe') { // IF PHOTOSWIPE STYLE
				
				$gallery_style = photoswipe_style($rgba_main_color,
												  $fg_main_color,
												  $fg_secondary_color,
												  $rgba_secondary_color,
												  $fg_pagination_active,
												  $fg_spacing_active, 
												  $fg_spacing, 
												  $fg_image_lightbox, 
												  $selector, 
												  $fg_gallery_name_font_size, 
												  $fg_gallery_name_font_color, 
												  $fg_gallery_name_text_align,
												  $float,
						  						  $itemwidth,
						  						  $fg_gallery_name_show);
			
			} else {
				$gallery_style = "
				<style type='text/css'>
					#{$selector} {
					margin: auto;
					}
					#{$selector} .fg-gallery-item {
					float: {$float};
					margin-top: 10px;
					text-align: center;
					width: {$itemwidth}%;
					}
					#{$selector} .fg-gallery-caption {
					margin-left: 0;
					}
					#{$selector}.fastgallery .fg-gallery-caption, 
					#{$selector}.fastgallery .fg-gallery-caption:hover {
						background-color:".$rgba_main_color.";
					}
					#{$selector}.fastgallery.gallery .fastgallery-gallery-icon .fg_zoom a, 
					#{$selector}.fastgallery.gallery .fastgallery-gallery-icon .fg_zoom a:hover {
						color:".$fg_main_color.";
					}
					#{$selector}.fastgallery.fg_style1 .fg-gallery-caption {
						color:".$fg_secondary_color.";	
					}
					#{$selector}.fastgallery.gallery.fg_style2 .fastgallery-gallery-icon .fg_zoom a {
						background:".$rgba_secondary_color.";
					}
					#{$selector}.fastgallery.fg_style2 .fg-gallery-caption {
						color:".$fg_secondary_color.";	
					}			
					#{$selector}.fastgallery.gallery.fg_style3 .fg_zoom, #{$selector}.fastgallery.gallery.fg_style3 .fg_zoom:hover {
						background:".$rgba_main_color.";
					}
					#{$selector}.fastgallery.fg_style3 .fg-gallery-caption {
						color:".$fg_secondary_color.";	
					}				
					#{$selector}.fastgallery.fg_style4 .fg-gallery-caption,			
					#{$selector}.fastgallery.gallery.fg_style4 .fastgallery-gallery-icon .fg_zoom a, 
					#{$selector}.fastgallery.gallery.fg_style4 .fastgallery-gallery-icon .fg_zoom a:hover {
						color:".$fg_secondary_color.";
					}
					#{$selector}.fastgallery.gallery.fg_style4 .fastgallery-gallery-icon .fg_zoom a, 
					#{$selector}.fastgallery.gallery.fg_style4 .fastgallery-gallery-icon .fg_zoom a:hover	{
						background:".$rgba_main_color.";
					}			
					#{$selector}.fastgallery.gallery.fg_style5 .fastgallery-gallery-icon .fg_zoom a, 
					#{$selector}.fastgallery.gallery.fg_style5 .fastgallery-gallery-icon .fg_zoom a:hover	{
						color:".$fg_secondary_color.";
						background-color:".$rgba_main_color.";
					}					
					#{$selector}.fastgallery.gallery.fg_style6 .fastgallery-gallery-icon .fg_zoom a,
					#{$selector}.fastgallery.gallery.fg_style6 .fastgallery-gallery-icon .fg_zoom a:hover {
						color:".$fg_secondary_color.";
						background:".$rgba_main_color.";				
					}
				
					#{$selector}.fastgallery.fg_style6 .fg-gallery-caption {
						color:".$fg_secondary_color.";	
					}
					#{$selector}.fastgallery.gallery.fg_style7 .fastgallery-gallery-icon .fg_zoom a,
					#{$selector}.fastgallery.gallery.fg_style7 .fastgallery-gallery-icon .fg_zoom a:hover {
						color:".$fg_secondary_color.";
						background:".$rgba_main_color.";				
					}		
					#{$selector}.fastgallery.fg_style7 .fg-gallery-caption {
						color:".$fg_secondary_color.";	
					}
					
					#{$selector}.fastgallery.gallery.fg_style8 .fastgallery-gallery-icon .fg_zoom a,
					#{$selector}.fastgallery.gallery.fg_style8 .fastgallery-gallery-icon .fg_zoom a:hover {
						color:".$fg_secondary_color.";
						background:".$rgba_main_color.";				
					}
				
					#{$selector}.fastgallery.fg_style8 .fg-gallery-caption {
						color:".$fg_secondary_color.";	
					}
					
					#{$selector}.fastgallery.gallery.fg_style9 .fastgallery-gallery-icon .fg_zoom a,
					#{$selector}.fastgallery.gallery.fg_style9 .fastgallery-gallery-icon .fg_zoom a:hover {
						color:".$fg_secondary_color.";
						background:".$rgba_main_color.";				
					}		
					#{$selector}.fastgallery.fg_style9 .fg-gallery-caption {
						color:".$fg_secondary_color.";	
					}
					
					#{$selector}.fastgallery.gallery.fg_style10 .fastgallery-gallery-icon .fg_zoom a,
					#{$selector}.fastgallery.gallery.fg_style10 .fastgallery-gallery-icon .fg_zoom a:hover {
						color:".$fg_secondary_color.";
						background:".$rgba_main_color.";				
					}		
					#{$selector}.fastgallery.fg_style10 .fg-gallery-caption {
						color:".$fg_secondary_color.";	
					}
					#{$selector}.fastgallery.fg_style11 .fg-gallery-caption {
						color:".$fg_secondary_color.";	
					}
					#{$selector}.fastgallery.fg_style11 .fastgallery-gallery-icon .fg_zoom a, 
					#{$selector}.fastgallery.fg_style11 .fastgallery-gallery-icon .fg_zoom a:hover {
						color:".$fg_main_color.";
						background:".$rgba_secondary_color.";
					}
					#{$selector}.fastgallery.fg_style12 .fg-gallery-caption {
						color:".$fg_secondary_color.";	
					}
					#{$selector}.fastgallery.fg_style12 .fastgallery-gallery-icon .fg_zoom a, 
					#{$selector}.fastgallery.fg_style12 .fastgallery-gallery-icon .fg_zoom a:hover {
						color:".$fg_main_color.";
						background:".$rgba_secondary_color.";
					}																
					/* FOTORAMA */
					#{$selector}.fastgallery.fotorama.fg_style1 .fotorama__thumb-border {
						border-color:".$rgba_main_color.";
					}
					#{$selector}.fastgallery.fotorama.fg_style1 .fotorama__html > div {
						background:".$rgba_main_color.";
						color:".$fg_secondary_color.";
					}
					#{$selector}.fastgallery.fotorama.fg_style2 .fotorama__thumb-border {
						border-color:".$rgba_main_color.";
					}
					#{$selector}.fastgallery.fotorama.fg_style2 .fotorama__html > div {
						background:".$rgba_main_color.";
						color:".$fg_secondary_color.";
					}
					#{$selector}.fastgallery.fotorama.fg_style3 .fotorama__thumb-border {
						border-color:".$rgba_main_color.";
					}						
					#{$selector}.fastgallery.fotorama.fg_style3 .fotorama__html > div {
						color:".$fg_secondary_color.";
					}
					#{$selector}.fastgallery.fotorama.fg_style4 .fotorama__thumb-border {
						border-color:".$rgba_main_color.";
					}						
					#{$selector}.fastgallery.fotorama.fg_style4 .fotorama__html > div {
						color:".$fg_secondary_color.";
					}
					#{$selector}.fastgallery.fotorama.fg_style5 .fotorama__thumb-border {
						border-color:".$rgba_main_color.";
						background:".$rgba_main_color.";
					}						
					#{$selector}.fastgallery.fotorama.fg_style5 .fotorama__html > div {
						color:".$fg_secondary_color.";
					}
					#{$selector}.fastgallery.fotorama.fg_style6 .fotorama__thumb-border {
						border-color:".$rgba_main_color.";
						background:".$rgba_main_color.";
					}						
					#{$selector}.fastgallery.fotorama.fg_style6 .fotorama__html > div {
						color:".$fg_secondary_color.";
					}
					#{$selector}.fastgallery.fotorama.fg_style7 .fotorama__thumb-border {
						border-color:".$rgba_main_color.";
						background:".$rgba_main_color.";
					}						
					#{$selector}.fastgallery.fotorama.fg_style7 .fotorama__html > div {
						color:".$fg_secondary_color.";
						background:".$rgba_main_color.";
					}
					#{$selector}.fastgallery.fotorama.fg_style8 .fotorama__thumb-border {
						border-color:".$rgba_main_color.";
						background:".$rgba_main_color.";
					}						
					#{$selector}.fastgallery.fotorama.fg_style8 .fotorama__html > div {
						color:".$fg_secondary_color.";
						background:".$rgba_main_color.";
					}
					#{$selector}.fastgallery.fotorama.fg_style9 .fotorama__thumb-border {
						border-color:".$rgba_main_color.";
						background:".$rgba_main_color.";
					}						
					#{$selector}.fastgallery.fotorama.fg_style9 .fotorama__html > div {
						color:".$fg_secondary_color.";
						background:".$rgba_main_color.";
					}
					#{$selector}.fastgallery.fotorama.fg_style10 .fotorama__thumb-border {
						border-color:".$rgba_main_color.";
						background:".$rgba_main_color.";
					}						
					#{$selector}.fastgallery.fotorama.fg_style10 .fotorama__html > div {
						color:".$fg_secondary_color.";
						background:".$rgba_main_color.";
					}
					#{$selector}.fastgallery.fotorama.fg_style11 .fotorama__thumb-border {
						border-color:".$rgba_main_color.";
					}
					#{$selector}.fastgallery.fotorama.fg_style11 .fotorama__html > div {
						background:".$rgba_main_color.";
						color:".$fg_secondary_color.";
					}
					#{$selector}.fastgallery.fotorama.fg_style12 .fotorama__thumb-border {
						border-color:".$rgba_main_color.";
					}
					#{$selector}.fastgallery.fotorama.fg_style12 .fotorama__html > div {
						background:".$rgba_main_color.";
						color:".$fg_secondary_color.";
					}										
					/* THUMBS ONE ON */
					#{$selector}.fastgallery.fg_thumbs_one .fg-gallery-item {
						display:none;
					}
					#{$selector}.fastgallery.fg_thumbs_one .fg-gallery-item:first-child {
						display:block;
					}
					#{$selector}.fastgallery.fg_thumbs_one {
						width:auto!important;
					}
				";
				
				if($fg_pagination_active == 'on') {
					$gallery_style .= "
						#{$selector}.fastgallery.fg_pagination_style1 ul.fg_pagination li a {
							background:".$rgba_main_color.";
							color:".$fg_secondary_color.";
						}
						#{$selector}.fastgallery.fg_pagination_style1 ul.fg_pagination li a:hover {
							background:".$fg_secondary_color.";
							color:".$rgba_main_color.";
						}
						#{$selector}.fastgallery.fg_pagination_style1 ul.fg_pagination li.fg_current {
							background:".$fg_secondary_color.";
							color:".$rgba_main_color.";
						}
						#{$selector}.fastgallery.fg_pagination_style2 ul.fg_pagination li a {
							color:".$fg_secondary_color.";
						}
						#{$selector}.fastgallery.fg_pagination_style2 ul.fg_pagination li a:hover {
							color:".$rgba_main_color.";
						}
						#{$selector}.fastgallery.fg_pagination_style2 ul.fg_pagination li.fg_current {
							color:".$rgba_main_color.";
						}																		
					";
				}
				
				
				
				
				if($fg_spacing_active == 'on') {
					$gallery_style .= "
						.fastgallery.gallery {
							width:100%;
							width: -webkit-calc(100% + ".$fg_spacing."px);
							width: calc(100% + ".$fg_spacing."px);
							/*margin-left:".$fg_spacing."px;*/
						}
						.fastgallery .fg-gallery-item {
							margin-right:".$fg_spacing."px!important;
							margin-bottom:".$fg_spacing."px!important;
						}
						.fastgallery.gallery-columns-2 .fg-gallery-item {
							max-width: 48%;
							max-width: -webkit-calc(50% - ".$fg_spacing."px);
							max-width:         calc(50% - ".$fg_spacing."px);
						}
						
						.fastgallery.gallery-columns-3 .fg-gallery-item {
							max-width: 32%;
							max-width: -webkit-calc(33.3% - ".$fg_spacing."px);
							max-width:         calc(33.3% - ".$fg_spacing."px);
						}
						
						.fastgallery.gallery-columns-4 .fg-gallery-item {
							max-width: 23%;
							max-width: -webkit-calc(25% - ".$fg_spacing."px);
							max-width:         calc(25% - ".$fg_spacing."px);
						}
						
						.fastgallery.gallery-columns-5 .fg-gallery-item {
							max-width: 19%;
							max-width: -webkit-calc(20% - ".$fg_spacing."px);
							max-width:         calc(20% - ".$fg_spacing."px);
						}
						
						.fastgallery.gallery-columns-6 .fg-gallery-item {
							max-width: 15%;
							max-width: -webkit-calc(16.7% - ".$fg_spacing."px);
							max-width:         calc(16.7% - ".$fg_spacing."px);
						}
						
						.fastgallery.gallery-columns-7 .fg-gallery-item {
							max-width: 13%;
							max-width: -webkit-calc(14.28% - ".$fg_spacing."px);
							max-width:         calc(14.28% - ".$fg_spacing."px);
						}
						
						.fastgallery.gallery-columns-8 .fg-gallery-item {
							max-width: 11%;
							max-width: -webkit-calc(12.5% - ".$fg_spacing."px);
							max-width:         calc(12.5% - ".$fg_spacing."px);
						}
						
						.fastgallery.gallery-columns-9 .fg-gallery-item {
							max-width: 9%;
							max-width: -webkit-calc(11.1% - ".$fg_spacing."px);
							max-width:         calc(11.1% - ".$fg_spacing."px);
						}
					";
				}
				
				if($fg_type == 'only_image') {
					$gallery_style .= "
					#{$selector}.fastgallery .fg_zoom a {
						display:none;	
					}
					";
				}

				if($fg_image_lightbox == 'zoomin') {
					$gallery_style .= '#'.$selector.'.fastgallery .icon-plus:before {	
										content: "\e6ef"!important;
					}';
				}
				if($fg_image_lightbox == 'image') {
					$gallery_style .= '#'.$selector.'.fastgallery .icon-plus:before {	
										content: "\e687"!important;
					}';
				}	
				if($fg_image_lightbox == 'images') {
					$gallery_style .= '#'.$selector.'.fastgallery .icon-plus:before {	
										content: "\e605"!important;
					}';
				}	
				if($fg_image_lightbox == 'spinner_icon') {
					$gallery_style .= '#'.$selector.'.fastgallery .icon-plus:before {	
										content: "\e6e7"!important;
					}';
				}
				if($fg_image_lightbox == 'search_icon') {
					$gallery_style .= '#'.$selector.'.fastgallery .icon-plus:before {	
										content: "\e6ee"!important;
					}';
				}				
				
				if($fg_gallery_name_show == 'true') {
					$gallery_style .= ".fg_gallery_title-{$instance}.fg_gallery_name {
							font-size:".$fg_gallery_name_font_size."px;
							color:".$fg_gallery_name_font_color.";
							text-align:".$fg_gallery_name_text_align.";
					}";
				}
				
				$gallery_style .= "</style>";	
			
				} // # IF PHOTOSWIPE STYLE 

				// PHOTOBOX
				if($fg_type == 'photobox') { // PHOTOBOX CSS/JS
				
					if($fg_lazyload == 'on') {
						$pb_thumbs = 'false';
					}
			
					$gallery_script = '<script type="text/javascript">
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
					$gallery_script = '<script type="text/javascript">		
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
					$gallery_script = '<script type="text/javascript">
						jQuery(function($){
							$(\'#'.$selector.' .fg_magnificPopup\').magnificPopup({
								type: \'image\',					
								gallery:{
									enabled:true
								}
								});
						});		
					</script>';
				}
				
				
				
				
				// FOTORAMA
				if($fg_type == 'fotorama') {
					
				$gallery_script = "<script type=\"text/javascript\">
					jQuery(function($){
						$('#$selector.fastgallery').fotorama({
							maxwidth: '100%',
							arrows: ".$fg_arrow.",
							autoplay: ".$fg_autoplay.",
							";
							if($fg_nav != 'dot') { 
								$gallery_script .= "nav: '".$fg_nav."',"; 
							}	
				$gallery_script .=	"navposition: '".$fg_navposition."',
							transition: '".$fg_transition."',
							
							allowfullscreen: ".$fg_allowfullscreen.",
							
							loop: ".$fg_loop.",
							fit: '".$fg_fit."',									  
						});
					});
					</script>";
				}
				
				// LIGHTGALLERY
				if($fg_type == 'lightgallery') {
					if($lg_mode == 'lg-fade') {
						$lg_mode = 'fade';	
					} else {
						$lg_mode = 'slide';
					}
					$gallery_script = '<script type="text/javascript">
						jQuery(function($){
							$(\'#'.$selector.'.gallery.fastgallery\').lightGallery({
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
				if($fg_type == 'custom_url' || $fg_type == 'only_image' || $fg_type == 'photoswipe') {
					$gallery_script = '';
				}
				
				// LAZY LOAD GRID
				$lazyload_class = '';
				if($fg_lazyload == 'on' && $size == 'fg-normal') {
					wp_enqueue_script( 'fg-lazyload-js');
					wp_enqueue_script( 'fg-imagesLoaded-js');
					$gallery_script .= '<script type="text/javascript">
						jQuery(function($){
							$(\'#'.$selector.' .fg-gallery-item img\').lazyload(';
							if($fg_lazyload_effect == 'yes') {	
										
								$gallery_script .= '{
													effect: \'fadeIn\',
													effectspeed: 2000
													}';
							
							}							
					$gallery_script .= ');
						});		
					</script>';
					$lazyload_class = 'fg_lazyload';								
				}
				
				// LAZY LOAD MASONRY
				if($size == 'fg-masonry') {
					
					if($fg_lazyload == 'off') { // MASONRY WHEN LAZY LOAD IS OFF
											
						$gallery_script .= '<script type="text/javascript">
						jQuery(window).load(function() {
							jQuery(document).ready(function($){
								$(\'.fastgallery.brick-masonry\').masonry({
									singleMode: true,
									itemSelector: \'.fg-gallery-item\'
								});
							});
						});
						</script>';
						
					} else { // LAZY LOAD IS ON
						
						wp_enqueue_script( 'fg-lazyload-js');
						wp_enqueue_script( 'fg-imagesLoaded-js');
						$gallery_script .= '<script type="text/javascript">
							jQuery(document).ready(function($){
						$("#'.$selector.' .fg-gallery-item img").lazyload(';
							if($fg_lazyload_effect == 'yes') {	
										
								$gallery_script .= '{
													effect: \'fadeIn\',
													effectspeed: 2000
													}';
							
							}
							
							$gallery_script .= ');

						$(\'#'.$selector.' .fg-gallery-item img\').load(function() {
							masonry_update();
						});

						function masonry_update() {     
							var $works_list = $(\'#'.$selector.'\');
							$works_list.imagesLoaded(function(){
								$works_list.masonry({
									itemSelector: \'.fg-gallery-item\',　
								});
							});
						 }    
						 
						 
						});
						</script>';
						
					}
				}


				// GALLERY NAME
				$gallery_name = '';
				if($fg_gallery_name_show == 'true') {
					$gallery_name = '<h2 class="fg_gallery_title-'.$instance.' fg_gallery_name">'.$fg_gallery_name.'</h2>';	
				}
				
				$size_class = sanitize_html_class( $size );
				// != FOTORAMA
				if($fg_type == 'fotorama') {	
					$gallery_div = "".$gallery_name."<div id='$selector' class='fastgallery ".$fg_style." ".$animation_info." ".$lazyload_class."";
					$output = $gallery_style . $gallery_script . $gallery_div;
				} elseif ($fg_thumbs_one == 'fg_thumbs_one') {
					
					if ($fg_type == 'photoswipe') {
					
					$gallery_div = "".$gallery_name."<div id='$selector' class='gallery galleryid-{$instance} gallery-columns-{$columns} gallery-size-{$size_class} fastgallery ".$fg_responsive." ".$fg_style." ".$fg_thumbs_one." ".$lazyload_class." ".$fg_over_image."'><div class='fg-photoswipe'>";
					
					} else {
						
					$gallery_div = "".$gallery_name."<div id='$selector' class='gallery galleryid-{$instance} gallery-columns-{$columns} gallery-size-{$size_class} fastgallery ".$fg_responsive." ".$fg_style." ".$fg_thumbs_one." ".$lazyload_class." ".$fg_over_image."'>";

					}
					
					$output = $gallery_style . $gallery_script . $gallery_div;		
				} else {
					if ($fg_type == 'photoswipe') {
						$gallery_div = "".$gallery_name."<div id='$selector' class='gallery galleryid-{$instance} gallery-columns-{$columns} gallery-size-{$size_class} fastgallery brick-masonry ".$fg_responsive." ".$fg_style." ".$fg_thumbs_one." ".$lazyload_class." ".$fg_over_image."'><div class='fg-photoswipe'>";
					} else {
						$gallery_div = "".$gallery_name."<div id='$selector' class='gallery galleryid-{$instance} gallery-columns-{$columns} gallery-size-{$size_class} fastgallery brick-masonry ".$fg_responsive." ".$fg_style." ".$fg_thumbs_one." ".$lazyload_class." ".$fg_over_image."'>";
					}
					$output = $gallery_style . $gallery_script . $gallery_div;
				}
				
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
				
				
				
				$i = - 1;
				
				// IF FOTORAMA CHANGE FOREACH
				if($fg_type == 'fotorama') {	
			 
					foreach ( $images as $id ) {
			 
						$image_url = wp_get_attachment_image_src( $id, $fg_thumbs_lightbox );
						$attachment_caption_array = get_post( $id );
						$attachment_caption	= $attachment_caption_array->post_excerpt;
						if(empty($attachment_caption)) { $attachment_caption = ' '; }
						$output .= '<div data-img="'.$image_url[0].'">'.$attachment_caption.'</div>';
			
					}
			 
			 
			 
					$output .= '<div style="clear:both"></div></div>';
			 
			 
				} elseif ($fg_type == 'photoswipe') { // IF PHOTOSWIPE 
				
					foreach ( $images as $id ) {
				
						$image_url = wp_get_attachment_image_src( $id, $fg_thumbs_lightbox );
						
						if($size == 'fg-normal') {
							$link_text = wp_get_attachment_image( $id , $fg_thumbs_grid);
						} else {
							$link_text = wp_get_attachment_image( $id , $fg_thumbs_masonry);
						}
						
						if($fg_lazyload == 'on') {
							if($size == 'fg-normal') {
								$link_text = wp_get_attachment_image_src( $id , $fg_thumbs_grid);
							} else {
								$link_text = wp_get_attachment_image_src( $id , $fg_thumbs_masonry);
							}
							$link_text = '<img data-original="'.$link_text[0].'" width="'.$link_text[1].'" height="'.$link_text[2].'">';
						
						}	
											
						$attachment_caption_array = get_post( $id );
						$attachment_caption	= $attachment_caption_array->post_excerpt;
						if(empty($attachment_caption)) { $attachment_caption = ' '; }	
						
						// CHECK CAPTION
						$caption_check = '';
						if($fg_caption == 'off'  || empty($attachment_caption) || $attachment_caption == ' ') {
							$caption_check = 'no-caption';
						}
						// END CHECK CAPTION						
						
						$output .= '<figure class=\'fg-gallery-item fastgallery-gallery-icon '.$caption_check .' fg_zoom\'>
										<a href="'.$image_url[0].'" itemprop="contentUrl" data-size="'.$image_url[1].'x'.$image_url[2].'">
											'.$link_text.'<span class=\'fg-zoom-icon icon-plus\'></span>
										</a>
										<figcaption itemprop="caption description">'.$attachment_caption.'</figcaption>';	
									

					
						if ($fg_caption == 'on' && !empty($attachment_caption) && $attachment_caption != ' ') {
						$output .= "
							<{$captiontag} class='fg-wp-caption-text fg-gallery-caption'><div class='caption-container'>
							" . $attachment_caption . "
							</div></{$captiontag}>";
						}			
									
						if($fg_style == 'fg_style4' || $fg_style == 'fg_style5' || $fg_style == 'fg_style6'
							|| $fg_style == 'fg_style7' || $fg_style == 'fg_style8' || $fg_style == 'fg_style9'
							|| $fg_style == 'fg_style10') {
							
							$output .= '<div class="fastgallery-mask"></div>';
							
						}
									
																			
						$output .=	'</figure>';				
					}
					
					$output .= '</div></div><div style="clear:both"></div>';
					$output .= '<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true"><div class="pswp__bg"></div>
								<div class="pswp__scroll-wrap">
									<div class="pswp__container">
										<div class="pswp__item"></div>
										<div class="pswp__item"></div>
										<div class="pswp__item"></div>
									</div>
									<div class="pswp__ui pswp__ui--hidden">
										<div class="pswp__top-bar">
											<div class="pswp__counter"></div>
											<button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
											<button class="pswp__button pswp__button--share" title="Share"></button>
											<button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
											<button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
											<div class="pswp__preloader">
												<div class="pswp__preloader__icn">
												  <div class="pswp__preloader__cut">
													<div class="pswp__preloader__donut"></div>
												  </div>
												</div>
											</div>
										</div>
										<div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
											<div class="pswp__share-tooltip"></div> 
										</div>
										<button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
										</button>
										<button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
										</button>
										<div class="pswp__caption">
											<div class="pswp__caption__center"></div>
										</div>
									  </div>
									</div></div>';
				
				} else { // IF #PHOTOSWIPE 
							
				foreach ( $images as $id ) {
					$_post = get_post( $id );
					$image_attributes = wp_get_attachment_image_src( $_post->ID, $fg_thumbs_lightbox );
					if($fg_type == 'custom_url') {					
						$url = get_post_meta( $id, '_custom_url', true );						
					} else {
						$url = $image_attributes[0];
					}
					$attachment_caption_array = get_post( $_post->ID );
					$attachment_caption	= $attachment_caption_array->post_excerpt;
					
					if($size == 'fg-normal') {
						$link_text = wp_get_attachment_image( $id , $fg_thumbs_grid);
					} else {
						$link_text = wp_get_attachment_image( $id , $fg_thumbs_masonry);
					}
					
					if($fg_lazyload == 'on') {
						if($size == 'fg-normal') {
							$link_text = wp_get_attachment_image_src( $id , $fg_thumbs_grid);
						} else {
							$link_text = wp_get_attachment_image_src( $id , $fg_thumbs_masonry);
						}
						$link_text = '<img data-original="'.$link_text[0].'" width="'.$link_text[1].'" height="'.$link_text[2].'">';
					
					}
					
					if($fg_type == 'lightgallery') {
						$image_output = "<div class='fg_zoom'>$link_text<a href='$url'><span class='fg-zoom-icon icon-plus'></span></a></div>";
					} elseif($fg_type == 'custom_url') {
						$image_output = "<div class='fg_zoom'>$link_text<a href='$url' target='$custom_url_target'><span class='fg-zoom-icon icon-plus'></span></a></div>";					
					} else {
						$image_output = "<div class='fg_zoom'>$link_text<a href='$url' title=\"$attachment_caption\" data-rel-fg='prettyPhoto[album-{$instance}]' class='fg_magnificPopup'><span class='fg-zoom-icon icon-plus'></span><span style='display:none'>$link_text</span></a></div>";							
					}
					$orientation = '';
					if ( isset( $image_meta['height'], $image_meta['width'] ) )
					$orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';
					
					// LIGHTGALLERY
					if($fg_type != 'lightgallery') {	
						$output .= "<{$itemtag} class='fg-gallery-item ".$animation_info."";
					} else {
						$output .= "<{$itemtag} data-src='$url' class='fg-gallery-item ".$animation_info."";	
					}
					// #LIGHTGALLERY
					
					// CHECK CAPTION
					$caption_check = '';
					if($fg_caption == 'off' || empty($attachment_caption)) {
						$caption_check = 'no-caption';
					}
					// END CHECK CAPTION
					
					$output .= "
					<{$icontag} class='fastgallery-gallery-icon $caption_check'>$image_output";
					if ($fg_caption == 'on' && !empty($attachment_caption)) {
					$output .= "
						<{$captiontag} class='fg-wp-caption-text fg-gallery-caption'><div class='caption-container'>
						" . $attachment_caption . "
						</div></{$captiontag}>";
					}
					$output .= "</{$icontag}></{$itemtag}>";
					}				 
					$output .= "
					</div>\n";
					
					if($fg_thumbs_one == 'off') {
						$output .= '<div class="fg_clear"></div>';
					}
						
				}
				
				if($fg_pagination_active == 'on') {
				
					$output .= '<div id="'.$selector.'" class="fastgallery '.$fg_pagination_style.'">'.get_fg_pagination($num_page_for_pagination,$pagination).'</div>';
				
				}
				return $output;
}



}
new fg_functions();



// PAGINATION FUNCTION //
function get_fg_pagination($num_page_for_pagination,$pagination) {
	$output = '<ul class="fg_pagination">';
	for($i=1; $i <= $num_page_for_pagination; $i++) {
		
		if($i == $pagination) {
			$output .= '<li class="fg_current">'.$i.'</li>'; // CURRENT PAGE
		} else {
			$output .= '<li><a href="'.get_post_permalink().'&fg_page='.$i.'">'.$i.'</a></li>'; // OTHER PAGE
		}
	}
	$output .= '</ul>';
	return $output;
}

// ADD VAR FUNCTION FOR PAGINATION
function add_query_vars_fg_pagination( $vars ){
  $vars[] = "fg_page";
  return $vars;
}
add_filter( 'query_vars', 'add_query_vars_fg_pagination' );

// THUMBNAILS FUNCTION
function fastgallery_vc_add_image_sizes() {

	add_image_size( 'fg-masonry', 500 );
	add_image_size( 'fg-normal', 800 , 800 , true);

}

add_action( 'init', 'fastgallery_vc_add_image_sizes' );


// HEX FUNCTION
function fastgallery_vc_hex2rgb($hex) {

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

function photoswipe_style($rgba_main_color,
						  $fg_main_color,
						  $fg_secondary_color,
						  $rgba_secondary_color,
						  $fg_pagination_active,
						  $fg_spacing_active, 
						  $fg_spacing, 
						  $fg_image_lightbox, 
						  $selector, 
						  $fg_gallery_name_font_size, 
						  $fg_gallery_name_font_color, 
						  $fg_gallery_name_text_align,
						  $float,
						  $itemwidth,
						  $fg_gallery_name_show
						  ) {
							  
							  
				$gallery_style = "
				<style type='text/css'>
					#{$selector} {
					margin: auto;
					}
					#{$selector} .fg-gallery-item {
					float: {$float};
					margin-top: 10px;
					text-align: center;
					width: {$itemwidth}%;
					}
					#{$selector} .fg-gallery-caption {
					margin-left: 0;
					}
					#{$selector}.fastgallery .fg-gallery-caption, 
					#{$selector}.fastgallery .fg-gallery-caption:hover {
						background-color:".$rgba_main_color.";
					}
					#{$selector}.fastgallery.gallery .fastgallery-gallery-icon.fg_zoom a, 
					#{$selector}.fastgallery.gallery .fastgallery-gallery-icon.fg_zoom a:hover {
						color:".$fg_main_color.";
					}
					#{$selector}.fastgallery.fg_style1 .fg-gallery-caption {
						color:".$fg_secondary_color.";	
					}
					#{$selector}.fastgallery.gallery.fg_style2 .fastgallery-gallery-icon.fg_zoom a {
						background:".$rgba_secondary_color.";
					}
					#{$selector}.fastgallery.fg_style2 .fg-gallery-caption {
						color:".$fg_secondary_color.";	
					}			
					#{$selector}.fastgallery.gallery.fg_style3 .fg_zoom, 
					#{$selector}.fastgallery.gallery.fg_style3 .fg_zoom:hover {
						background:".$rgba_main_color.";
					}
					#{$selector}.fastgallery.fg_style3 .fg-gallery-caption {
						color:".$fg_secondary_color.";	
					}				
					#{$selector}.fastgallery.fg_style4 .fg-gallery-caption,			
					#{$selector}.fastgallery.gallery.fg_style4 .fastgallery-gallery-icon.fg_zoom a, 
					#{$selector}.fastgallery.gallery.fg_style4 .fastgallery-gallery-icon.fg_zoom a:hover {
						color:".$fg_secondary_color.";
					}
					#{$selector}.fastgallery.gallery.fg_style4 .fastgallery-gallery-icon.fg_zoom a, 
					#{$selector}.fastgallery.gallery.fg_style4 .fastgallery-gallery-icon.fg_zoom a:hover,
					#{$selector}.fastgallery.gallery.fg_style4 .fg-photoswipe .fastgallery-mask	{
						background:".$rgba_main_color.";
					}			
					#{$selector}.fastgallery.gallery.fg_style5 .fastgallery-gallery-icon.fg_zoom a, 
					#{$selector}.fastgallery.gallery.fg_style5 .fastgallery-gallery-icon.fg_zoom a:hover {
						color:".$fg_secondary_color.";
					}
					#{$selector}.fastgallery.gallery.fg_style5 .fg-photoswipe .fastgallery-mask	{
						background-color:".$rgba_main_color.";
					}											
					#{$selector}.fastgallery.gallery.fg_style6 .fastgallery-gallery-icon.fg_zoom a,
					#{$selector}.fastgallery.gallery.fg_style6 .fastgallery-gallery-icon.fg_zoom a:hover {
						color:".$fg_secondary_color.";
						background:".$rgba_main_color.";				
					}
					#{$selector}.fastgallery.gallery.fg_style6 .fg-photoswipe .fastgallery-mask	{
						background:".$rgba_main_color.";
					}				
					#{$selector}.fastgallery.fg_style6 .fg-gallery-caption {
						color:".$fg_secondary_color.";	
					}
					#{$selector}.fastgallery.gallery.fg_style7 .fastgallery-gallery-icon.fg_zoom a,
					#{$selector}.fastgallery.gallery.fg_style7 .fastgallery-gallery-icon.fg_zoom a:hover {
						color:".$fg_secondary_color.";
						background:".$rgba_main_color.";				
					}
					#{$selector}.fastgallery.gallery.fg_style7 .fg-photoswipe .fastgallery-mask	{
						background:".$rgba_main_color.";
					}		
					#{$selector}.fastgallery.fg_style7 .fg-gallery-caption {
						color:".$fg_secondary_color.";	
					}
					
					#{$selector}.fastgallery.gallery.fg_style8 .fastgallery-gallery-icon.fg_zoom a,
					#{$selector}.fastgallery.gallery.fg_style8 .fastgallery-gallery-icon.fg_zoom a:hover {
						color:".$fg_secondary_color.";
						background:".$rgba_main_color.";				
					}

					#{$selector}.fastgallery.gallery.fg_style8 .fg-photoswipe .fastgallery-mask	{
						background:".$rgba_main_color.";
					}	
				
					#{$selector}.fastgallery.fg_style8 .fg-gallery-caption {
						color:".$fg_secondary_color.";	
					}
					
					#{$selector}.fastgallery.gallery.fg_style9 .fastgallery-gallery-icon.fg_zoom a,
					#{$selector}.fastgallery.gallery.fg_style9 .fastgallery-gallery-icon.fg_zoom a:hover {
						color:".$fg_secondary_color.";				
					}
					#{$selector}.fastgallery.gallery.fg_style9 .fg-photoswipe .fastgallery-mask	{
						background:".$rgba_main_color.";
					}							
					#{$selector}.fastgallery.fg_style9 .fg-gallery-caption {
						color:".$fg_secondary_color.";	
					}
					
					#{$selector}.fastgallery.gallery.fg_style10 .fastgallery-gallery-icon.fg_zoom a,
					#{$selector}.fastgallery.gallery.fg_style10 .fastgallery-gallery-icon.fg_zoom a:hover {
						color:".$fg_secondary_color.";
						background:".$rgba_main_color.";				
					}
					#{$selector}.fastgallery.gallery.fg_style10 .fg-photoswipe .fastgallery-mask	{
						background:".$rgba_main_color.";
					}							
					#{$selector}.fastgallery.fg_style10 .fg-gallery-caption {
						color:".$fg_secondary_color.";	
					}
					#{$selector}.fastgallery.fg_style11 .fg-gallery-caption {
						color:".$fg_secondary_color.";	
					}
					#{$selector}.fastgallery.fg_style11 .fastgallery-gallery-icon.fg_zoom a, 
					#{$selector}.fastgallery.fg_style11 .fastgallery-gallery-icon.fg_zoom a:hover {
						color:".$fg_main_color.";
						background:".$rgba_secondary_color.";
					}
					#{$selector}.fastgallery.fg_style12 .fg-gallery-caption {
						color:".$fg_secondary_color.";	
					}
					#{$selector}.fastgallery.fg_style12 .fastgallery-gallery-icon.fg_zoom a, 
					#{$selector}.fastgallery.fg_style12 .fastgallery-gallery-icon.fg_zoom a:hover {
						color:".$fg_main_color.";
						background:".$rgba_secondary_color.";
					}																									
					/* THUMBS ONE ON */
					#{$selector}.fastgallery.fg_thumbs_one .fg-gallery-item {
						display:none;
					}
					#{$selector}.fastgallery.fg_thumbs_one .fg-gallery-item:first-child {
						display:block;
					}
					#{$selector}.fastgallery.fg_thumbs_one {
						width:auto!important;
					}
				";
				
				if($fg_pagination_active == 'on') {
					$gallery_style .= "
						#{$selector}.fastgallery.fg_pagination_style1 ul.fg_pagination li a {
							background:".$rgba_main_color.";
							color:".$fg_secondary_color.";
						}
						#{$selector}.fastgallery.fg_pagination_style1 ul.fg_pagination li a:hover {
							background:".$fg_secondary_color.";
							color:".$rgba_main_color.";
						}
						#{$selector}.fastgallery.fg_pagination_style1 ul.fg_pagination li.fg_current {
							background:".$fg_secondary_color.";
							color:".$rgba_main_color.";
						}
						#{$selector}.fastgallery.fg_pagination_style2 ul.fg_pagination li a {
							color:".$fg_secondary_color.";
						}
						#{$selector}.fastgallery.fg_pagination_style2 ul.fg_pagination li a:hover {
							color:".$rgba_main_color.";
						}
						#{$selector}.fastgallery.fg_pagination_style2 ul.fg_pagination li.fg_current {
							color:".$rgba_main_color.";
						}																		
					";
				}
				
				
				
				
				if($fg_spacing_active == 'on') {
					$gallery_style .= "
						.fastgallery.gallery {
							width:100%;
							width: -webkit-calc(100% + ".$fg_spacing."px);
							width: calc(100% + ".$fg_spacing."px);
							/*margin-left:".$fg_spacing."px;*/
						}
						.fastgallery .fg-gallery-item {
							margin-right:".$fg_spacing."px!important;
							margin-bottom:".$fg_spacing."px!important;
						}
						.fastgallery.gallery-columns-2 .fg-gallery-item {
							max-width: 48%;
							max-width: -webkit-calc(50% - ".$fg_spacing."px);
							max-width:         calc(50% - ".$fg_spacing."px);
						}
						
						.fastgallery.gallery-columns-3 .fg-gallery-item {
							max-width: 32%;
							max-width: -webkit-calc(33.3% - ".$fg_spacing."px);
							max-width:         calc(33.3% - ".$fg_spacing."px);
						}
						
						.fastgallery.gallery-columns-4 .fg-gallery-item {
							max-width: 23%;
							max-width: -webkit-calc(25% - ".$fg_spacing."px);
							max-width:         calc(25% - ".$fg_spacing."px);
						}
						
						.fastgallery.gallery-columns-5 .fg-gallery-item {
							max-width: 19%;
							max-width: -webkit-calc(20% - ".$fg_spacing."px);
							max-width:         calc(20% - ".$fg_spacing."px);
						}
						
						.fastgallery.gallery-columns-6 .fg-gallery-item {
							max-width: 15%;
							max-width: -webkit-calc(16.7% - ".$fg_spacing."px);
							max-width:         calc(16.7% - ".$fg_spacing."px);
						}
						
						.fastgallery.gallery-columns-7 .fg-gallery-item {
							max-width: 13%;
							max-width: -webkit-calc(14.28% - ".$fg_spacing."px);
							max-width:         calc(14.28% - ".$fg_spacing."px);
						}
						
						.fastgallery.gallery-columns-8 .fg-gallery-item {
							max-width: 11%;
							max-width: -webkit-calc(12.5% - ".$fg_spacing."px);
							max-width:         calc(12.5% - ".$fg_spacing."px);
						}
						
						.fastgallery.gallery-columns-9 .fg-gallery-item {
							max-width: 9%;
							max-width: -webkit-calc(11.1% - ".$fg_spacing."px);
							max-width:         calc(11.1% - ".$fg_spacing."px);
						}
					";
				}

				if($fg_image_lightbox == 'zoomin') {
					$gallery_style .= '#'.$selector.'.fastgallery .icon-plus:before {	
										content: "\e6ef"!important;
					}';
				}
				if($fg_image_lightbox == 'image') {
					$gallery_style .= '#'.$selector.'.fastgallery .icon-plus:before {	
										content: "\e687"!important;
					}';
				}	
				if($fg_image_lightbox == 'images') {
					$gallery_style .= '#'.$selector.'.fastgallery .icon-plus:before {	
										content: "\e605"!important;
					}';
				}	
				if($fg_image_lightbox == 'spinner_icon') {
					$gallery_style .= '#'.$selector.'.fastgallery .icon-plus:before {	
										content: "\e6e7"!important;
					}';
				}
				if($fg_image_lightbox == 'search_icon') {
					$gallery_style .= '#'.$selector.'.fastgallery .icon-plus:before {	
										content: "\e6ee"!important;
					}';
				}				
				
				if($fg_gallery_name_show == 'true') {
					$gallery_style .= ".fg_gallery_title-{$instance}.fg_gallery_name {
							font-size:".$fg_gallery_name_font_size."px;
							color:".$fg_gallery_name_font_color.";
							text-align:".$fg_gallery_name_text_align.";
					}";
				}
				
				$gallery_style .= "</style>";								  
							  
							  
				return $gallery_style;				  
							  
				}

			
?>