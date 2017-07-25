<?php
/*
Plugin Name: ArtProf Filterable Content Shortcode
Plugin URI: http://artprof.org
Description: Creates filterable layouts of any post type via shortcode.
Version: 1.0
Author: TheLibzter
Author URI: http://artprof.com
*/



function artprof_filterable_content_func( $atts ) {
    $atts = shortcode_atts( array(
        'posttype' => 'page'
    ), $atts, 'artprof_content' );
	$thisPostType = $atts['posttype'];
	
	global $post;
	$output = '';
	$filters = '';
	$toplevel = array();	
	$a=array(); // array to hold categories in
	
	global $listview;
	if($listview === 'true'){ 
		$listclass = ' list';
	} else {
		$listclass = ' grid';	
	}
	
	if($thisPostType === 'course') {
		$args = array(
			'post_type'      => $thisPostType,
	    	'posts_per_page' => -1,
	    	'order'          => 'DESC',
			'orderby'        => 'date'
		);
	} else {
		$args = array(
			'post_type'      => $thisPostType,
	    	'posts_per_page' => -1,
	    	'post_parent'    => $post->ID,
	    	'order'          => 'DESC',
			'orderby'        => 'date'
			
		);
	}
	$the_query = new WP_Query( $args ); 
	
	if ( $the_query->have_posts() ) :
		
	 	 $output .= '<div class="nopoststoshow">No posts match your filters.<br />Please try again.</div>';
	   	 $output .= '<div id="js-grid-masonry" class="cbp'.$listclass.'">';
	 while ( $the_query->have_posts() ) : $the_query->the_post();
	 	$thisID = $post->ID;
		
		if($thisPostType === 'course') {
			$termsArray = get_the_terms($post->ID, 'course-cat');
		} else {
	 		$termsArray = get_the_category(); 					
		}
		
	 	foreach ( $termsArray as $term ) {
			$thisTerm = $term->name;
			if($thisPostType === 'course') {
			$category_parent_id = $term->parent; 
				} else {
					$category_parent_id = $term->category_parent; 
				}
	        if(!in_array($category_parent_id, $toplevel)){
		        $toplevel[]=$category_parent_id;
			}
	        if(!in_array($thisTerm, $a)){
	        $a[]=$thisTerm;
	        }
	 	}
		 $termsString = ""; 
	 	foreach ( $termsArray as $term ) { 		
	 	   $termsString .= ' filter-' . $term->slug.' ';
	 	}
	 	$output .= '<div class="cbp-item' . $termsString . '">';
		
		$output .= '<div class="item-avatar" data-id="' . $thisID . '"><a href="' . get_the_permalink() . '" title="' . get_the_title() .'">';		
		if ( has_post_thumbnail() ) {
			$thumb_id = get_post_thumbnail_id();
			$thumb_url_array = wp_get_attachment_image_src($thumb_id, 'thumbnail-size', true);
			$thumb_url = $thumb_url_array[0];
			$postThumb = '<img src="'.$thumb_url.'">';
		}  else {
			$postThumb = '<img src="/wp-content/uploads/2016/01/french_horn_painting.jpg">';
		}
	 	$output .= $postThumb . '</div>';		
		$output .= '<h3>' . get_the_title() .'</h3></a></div>';
		
	 endwhile;
	 	$output .= '</div>';
		
   	 $output .= '<div id="loadMore" class="cbp-l-loadMore-button" style="display:none;">
                       <a href="ajax-juicy-projects/loadMore.html" class="cbp-l-loadMore-link" rel="nofollow">
                           <span class="cbp-l-loadMore-defaultText">LOAD MORE (<span class="cbp-l-loadMore-loadItems">6</span>)</span>
                           <span class="cbp-l-loadMore-loadingText">LOADING...</span>
                           <span class="cbp-l-loadMore-noMoreLoading">NO MORE WORKS</span>
                       </a>
                   </div>
   ';
	endif;
	

	foreach($toplevel as $c){
		if($thisPostType === 'course') {
			$category_parent = get_term($c, 'course-cat');						
		} else {
		    $category_parent = get_term( $c, 'category' );
		}
		
	    $parent = $category_parent->slug;		
	    $parentName = $category_parent->name;		
		$filters .= '<div id="' .$parent.'" class="filter-group"><div class="option-combo ' .$parent.'">';
	    $filters .= '<strong>' .$parentName.'</strong>';
	    $filters .= '<ul class="filter option-set clearfix" data-filter-group="' .$parent.'"> ';
	     $filters .= '<li><div data-filter="" data-class="any" class="cbp-filter-item selected">All</div></li>';
			
		$current_cat_id = $c;

		if($thisPostType === 'course') {
			$thisTaxonomy = 'course-cat';			
		} else {
		    $thisTaxonomy = 'category';
		}

		$kids = get_terms([
		    'taxonomy' => $thisTaxonomy,
		    'parent'   => $current_cat_id,
		]);
		foreach($kids as $kid){
			$thisKid = $kid->name;
			$thisFilter = $kid->slug;
	        if(in_array($thisKid, $a)){
			    $filters .= '<li><div data-filter=".filter-'.$thisFilter.'" class="cbp-filter-item">'.$thisKid.'</div></li>';
			}		
		}				
		$filters .= '</ul></div></div>';
	
				
	}		

	wp_reset_query();
	
	if($listview === 'true'){ 
		$list = " on";
		$grid = "";
	} else {
		$list = "";
		$grid = " on";
	}
	$filters .= '<div class="row view-toggle"><div class="col-sm-12">';
	$filters .= '</div></div>';
	
  	 $preloader = '<div id="ajax-loader"><img src="http://preloaders.net/preloaders/290/preview.gif" /></div>';			
						
	return $preloader . '<div id="preload">' . $filters . $output . '</div>';
}
	
add_shortcode( 'artprof_content', 'artprof_filterable_content_func' );







function artprof_cp_categories_func($atts) {
	
    $atts = shortcode_atts( array(
        'posttype' => 'page'
    ), $atts, 'artprof_cp_categories' );
	$thisPostType = $atts['posttype'];
	
	
	global $post;
	$filterlist = '';
	$toplevel = array();	
	$a=array(); 
	
	if($thisPostType === 'course') {
		$args = array(
			'post_type'      => $thisPostType,
	    	'posts_per_page' => -1,
		    'post_parent'    => $post->ID,
	    	'order'          => 'ASC',
	    	'orderby'        => 'menu_order'
		);
	} else {
		$args = array(
		    'post_type'      => $thisPostType,
		    'posts_per_page' => -1,
		    'post_parent'    => $post->ID,
		    'order'          => 'ASC',
		    'orderby'        => 'menu_order'
		);		
	}
	
	$the_query = new WP_Query( $args );  
	
	if ( $the_query->have_posts() ) :
	 while ( $the_query->have_posts() ) : $the_query->the_post();
	if($thisPostType === 'course') {
		$termsArray = get_the_terms($post->ID, 'course-cat');
	} else {
 		$termsArray = get_the_category();  				
	}
	 
	 	foreach ( $termsArray as $term ) { 
			$thisTerm = $term->slug;
			if($thisPostType === 'course') {
			$category_parent_id = $term->parent; 
				} else {
					$category_parent_id = $term->category_parent; 
				}
			
	        if(!in_array($category_parent_id, $toplevel)){
		        $toplevel[]=$category_parent_id;
			}
	        if(!in_array($thisTerm, $a)){
	        $a[]=$thisTerm;
	        }
	 	}
	 endwhile;
	endif;
	
	
	foreach($toplevel as $c){
		if($thisPostType === 'course') {
			$category_parent = get_term($c, 'course-cat');						
		} else {
		    $category_parent = get_term( $c, 'category' );
		}
	    $parent = $category_parent->slug;				
		$filterlist .= '#' . $parent;
		if(end($toplevel) !== $c){
		  $filterlist .= ', '; 
		}
	}	
	wp_reset_query();
	return $filterlist;
}
	
add_shortcode( 'artprof_cp_categories', 'artprof_cp_categories_func' );






function artprof_curriculum_func() {

   global $post;
   $id= get_the_ID();

   $class='';
   $output = '';
   $settings = get_option('lms_settings');
   if(isset($settings['general']['curriculum_accordion']))
   	$class="accordion";
   $output .= '
   <div class="course_title">
   	<h2>'. _e('Course Curriculum','vibe') . '</h2>
   </div>

   <div class="course_curriculum ' . $class . '">';

   do_action('wplms_course_curriculum_section',$id);
   $course_curriculum = vibe_sanitize(get_post_meta($id,'vibe_course_curriculum',false));
   if(isset($course_curriculum)){

   	foreach($course_curriculum as $lesson){
   		if(is_numeric($lesson)){
   			$icon = get_post_meta($lesson,'vibe_type',true);

   			if(get_post_type($lesson) == 'quiz')
   				$icon='task';

   					$href=get_the_title($lesson);
   					$free='';
   					$free = get_post_meta($lesson,'vibe_free',true);

   					$curriculum_course_link = apply_filters('wplms_curriculum_course_link',0);
   					if(vibe_validate($free) || ($post->post_author == get_current_user_id()) || current_user_can('manage_options') || $curriculum_course_link){
   						$href=apply_filters('wplms_course_curriculum_free_access','<a href="'.get_permalink($lesson).'?id='.get_the_ID().'">'.get_the_title($lesson).(vibe_validate($free)?'<span>'.__('FREE','vibe').'</span>':'').'</a>',$lesson,$free);
   					}

   			$output .= '<div class="course_lesson">
   					<i class="icon-'.$icon.'"></i><h6>'.apply_filters('wplms_curriculum_course_lesson',$href,$lesson).'</h6>';
   					$minutes=0;
   					$hours=0;
   					$min = get_post_meta($lesson,'vibe_duration',true);
   					$minutes = $min;
   					if($minutes){
   						if($minutes > 60){ 
   							$hours = intval($minutes/60);
   							$minutes = $minutes - $hours*60;
   						}
   			return apply_filters('wplms_curriculum_time_filter','<span><i class="icon-clock"></i> '.(isset($hours)?$hours.__(' Hours','vibe'):'').' '.$minutes.' '.__('minutes','vibe').'</span><b>'.((isset($hours) && $hours)?sprintf('%02d',$hours):"00").':'.sprintf('%02d', $minutes).'</b>',$min,$lesson);
   					}	

   					$output .= '</div>';
   		}else{
   			$output .= '<h5 class="course_section">'.$lesson.'</h5>';
   		}
   	}
   }

   $output .= '</div>';

   return $output;
}

add_shortcode( 'artprof_curriculum', 'artprof_curriculum_func' );


