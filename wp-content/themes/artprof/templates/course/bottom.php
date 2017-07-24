<?php
if ( ! defined( 'ABSPATH' ) ) exit;

do_action('wplms_single_course_content_end');
?>
				</div>
			 <!--		<div class="col-md-12 col-sm-12" style="">	
				<div class="widget pricing">
						<?php the_course_button(); ?>
						<?php the_course_details(); ?>
					</div>

					<?php
				 		$sidebar = apply_filters('wplms_sidebar','coursesidebar',get_the_ID());
		                if ( !function_exists('dynamic_sidebar')|| !dynamic_sidebar($sidebar) ) : ?>
	               	<?php endif; ?>
						
				</div>
						-->
						
						
	                    <?php
	                        $page_comments = vibe_get_option('page_comments');
	                        if(!empty($page_comments))
	                            comments_template();
	                     ?>
						
		</div><!-- .padder -->
		
		</div><!-- #container -->
	</div>
</section>	