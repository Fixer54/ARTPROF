<?php
/*
Plugin Name: PW Grid/List Post Layout For Visual Composer
Plugin URI: http://proword.net/Vc_Post_Layout/
Description: Build advance grid / list from posts, custom posts, pages and add any filtering options from custom taxonomies.
Version: 3.7
Author: Proword
Author URI: http://proword.net/
Text Domain: pw_vc_post_layout
Domain Path: /languages/
*/

	if(!defined( '__PW_POST_LAYOUT_TEXTDOMAN__' )){
			define( '__PW_POST_LAYOUT_TEXTDOMAN__', 'pw_vc_post_layout' );
	}
	define('PW_PS_PL_BASENAME_GRID',plugin_basename( __FILE__ ));
	define ('PW_PS_PL_NOTIC_GRID','<div class="updated"><p>'.__("The", __PW_POST_LAYOUT_TEXTDOMAN__ ).' <strong>'.__("PW Grid/List Post Layout for Visual Composer", __PW_POST_LAYOUT_TEXTDOMAN__ ).'</strong> '.__("plugin requires", __PW_POST_LAYOUT_TEXTDOMAN__ ).' <strong>'.__("Visual Composer", __PW_POST_LAYOUT_TEXTDOMAN__ ).'</strong> '.__("Plugin installed and activated", __PW_POST_LAYOUT_TEXTDOMAN__ ).'.</p></div>');



	
	if (!class_exists('VC_PW_POST_LAYOUT_GRID_CLASS')) {
		class VC_PW_POST_LAYOUT_GRID_CLASS
		{
			function __construct()
			{
				add_action( 'after_setup_theme', array($this,'PW_PLUGIN_RUN' ),2);				
			}
			function PW_PLUGIN_RUN()
			{
				if(defined('WPB_VC_VERSION')){
					require_once 'main_class.php';
				}else
				{
					echo PW_PS_PL_NOTIC_GRID;
				}	
			}
			
			function pw_image_admin_notice_for_vc_activation()
			{
				echo PW_PS_PL_NOTIC_GRID;
			}
			
		}
	
		new VC_PW_POST_LAYOUT_GRID_CLASS;
	}
?>