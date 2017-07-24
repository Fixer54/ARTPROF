<?php
	
/*======= Vibe Course Categories ======== */  

class artprof_course_categories extends vibe_course_categories  {
 
 
    /** constructor -- name this the same as the class above */
    function __construct() {
    $widget_ops = array( 'classname' => 'ArtProf Course Categories', 'description' => __('ArtProf Course Categories ', 'vibe') );
    $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'artprof_course_categories' );
    parent::__construct( 'artprof_course_categories', __('Course Categories', 'vibe'), $widget_ops, $control_ops );
  }
        
 
    /** @see WP_Widget::widget -- do not rename this */
    function widget( $args, $instance ) {
    extract( $args );

    //Our variables from the widget settings.
    $title = apply_filters('widget_title', $instance['title'] );
    $exclude_terms = (isset($instance['exclude_terms'])?esc_attr($instance['exclude_terms']):'');
	  $sort = esc_attr($instance['sort']);
	  $order = esc_attr($instance['order']); 
    
    echo $before_widget;

    // Display the widget title 
    if ( $title )
    echo $before_title . $title . $after_title;
    

    $args = apply_filters('wplms_course_filters_course_cat',array(
        'title_li'=>'',
        'taxonomy'=>'course-cat',
    		'orderby'    => $order,
		 	  'order' => $sort
    	));

    if(!empty($exclude_terms)){
      $exclude_terms= explode(',',$exclude_terms);
      foreach($exclude_terms as $k=>$term){
         if(!is_numeric($term)){
            $term =  get_term_by( 'slug', $term, 'course-cat');
            $exclude_terms[$k] = $term->term_id;
         }
      }
    }
    	
    $args['exclude'] = $exclude_terms;

    echo '<ul class="'.$order.' '.(empty($instance['hierarchial'])?'':'hierarchial').'">';
	

    if($order == 'hierarchial' || !empty($instance['hierarchial'])){ 
      $args['hierarchial']=1;
      $args['orderby']= 'name';
    }
	
	//new 
	$args['walker'] = new Custom_Walker_Category();
  	echo wp_list_categories($args);
    echo '</ul>';
    echo $after_widget;
                
    }
 
    /** @see WP_Widget::update -- do not rename this */
    function update($new_instance, $old_instance) {   
    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['exclude_terms'] = $new_instance['exclude_terms'];
    $instance['sort'] = $new_instance['sort'];
    $instance['order'] = $new_instance['order'];
    $instance['hierarchial'] = $new_instance['hierarchial'];
    
    return $instance;
    }
 
    /** @see WP_Widget::form -- do not rename this */
    function form($instance) {  
        $defaults = array( 
                    'title'  => __('Course Categories','vibe'),
                    'exclude_ids'  => '',
                    'hierarchial'=>0,
                    'sort'  => 'DESC',
                    'order' => ''
                    );
  		
  		$instance = wp_parse_args( (array) $instance, $defaults );
      $title  = esc_attr($instance['title']);
      $hierarchial = esc_attr($instance['hierarchial']);
      $exclude_terms = esc_attr($instance['exclude_terms']);
		  $sort = esc_attr($instance['sort']);
		  $order = esc_attr($instance['order']);                               
        ?>
         
         <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','vibe'); ?></label> 
          <input class="regular_text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
  		<p>
          <label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Order by','vibe'); ?></label> 
           <select class="select" name="<?php echo $this->get_field_name('order'); ?>">
           		<option value="name" <?php selected('name',$order); ?>><?php _e('Name','vibe'); ?></option>
           		<option value="slug" <?php selected('slug',$order); ?>><?php _e('Slug','vibe'); ?></option>
           		<option value="count" <?php selected('count',$order); ?>><?php _e('Course Count','vibe'); ?></option>
            </select>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('sort'); ?>"><?php _e('Sort Order ','vibe'); ?></label> 
           <select class="select" name="<?php echo $this->get_field_name('sort'); ?>">
           		<option value="ASC" <?php selected('ASC',$sort); ?>><?php _e('Ascending','vibe'); ?></option>
           		<option value="DESC" <?php selected('DESC',$sort); ?>><?php _e('Descending','vibe'); ?></option>
            </select>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('hierarchial'); ?>">
          <input type="checkbox" id="<?php echo $this->get_field_id('hierarchial'); ?>" name="<?php echo $this->get_field_name('hierarchial'); ?>" value="1" <?php checked($hierarchial,1); ?>/><?php _e('hierarchial ','vibe'); ?></label> 
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('exclude_terms'); ?>"><?php _e('Exclude Course Category Terms slugs (comma saperated):','vibe'); ?></label> 
          <input class="regular_text" id="<?php echo $this->get_field_id('exclude_terms'); ?>" name="<?php echo $this->get_field_name('exclude_terms'); ?>" type="text" value="<?php echo $exclude_terms; ?>" />
        </p>
        
        <?php 
        wp_reset_query();
        wp_reset_postdata();
}
}