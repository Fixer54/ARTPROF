<?php

if ( ! defined( 'ABSPATH' ) ) exit;
$id= vibe_get_bp_page_id('course');

$title=get_post_meta($id,'vibe_title',true);

if(!isset($title) || !$title || (vibe_validate($title))){

?>
<section id="title">
    <div class="<?php echo vibe_get_container(); ?>">
        <div class="row">
             <div class="col-md-9 col-sm-8">
                <div class="pagetitle">
                	<?php 
                        $breadcrumbs=get_post_meta($id,'vibe_breadcrumbs',true);
                        if(!isset($breadcrumbs) || !$breadcrumbs || vibe_validate($breadcrumbs)){
                            vibe_breadcrumbs();
                        }   
                    ?>
                	<h1><?php echo vibe_get_title($id); ?></h1>
                    <?php the_sub_title($id); ?>
                </div>
            </div>
            <div class="col-md-3 col-sm-4">
            	<?php 
            		do_action('wplms_be_instructor_button');	
				?>
            </div>
        </div>
    </div>
</section>
<?php
}


?>



<section id="content">
	<div id="buddypress">
		
		<?php echo do_shortcode('[artprof_content]'); ?>
	
		
    <div class="<?php echo vibe_get_container(); ?>">

	<?php do_action( 'bp_before_directory_course_page' ); ?>

		<div class="padder">

		<?php do_action( 'bp_before_directory_course' ); ?>
			
			
		<?php do_action( 'bp_after_directory_course' ); ?>

		</div><!-- .padder -->
	
	<?php do_action( 'bp_after_directory_course_page' ); ?>
</div><!-- #content -->



</div>
</section>
<?php
