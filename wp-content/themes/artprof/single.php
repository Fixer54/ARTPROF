<?php

if ( !defined( 'ABSPATH' ) ) exit;

get_header(vibe_get_header());

if ( have_posts() ) : while ( have_posts() ) : the_post();


$title=get_post_meta(get_the_ID(),'vibe_title',true);

if(!isset($title) || !$title || (vibe_validate($title))){

?>
<section id="title">
    <div class="<?php echo vibe_get_container(); ?>">
        <div class="row">
            <div class="col-md-12">
                <div class="pagetitle">
                    <?php 
                        $breadcrumbs=get_post_meta(get_the_ID(),'vibe_breadcrumbs',true);
                        if(!isset($breadcrumbs) || !$breadcrumbs || vibe_validate($breadcrumbs)){
                            vibe_breadcrumbs();
                        }   
                    ?>
                    <h2 class="pagetitle"><?php the_title(); ?></h2>
                    <?php the_sub_title(); ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
}

?>
<section id="content">
    <div class="<?php echo vibe_get_container(); ?>">
        
        <div class="row">
            <?php
                $template = get_post_meta(get_the_ID(),'vibe_template',true);
				?>
                <div class="col-md-12">

                <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <div class="content">
                    <?php if(has_post_thumbnail()){ ?>
                    <div class="featured">
                        <?php the_post_thumbnail(get_the_ID(),'full'); ?>
                    </div>
                    <?php
                    }
                    the_content();
                     ?>
                </div>

                </div>
                
<?php
                comments_template();
                endwhile;
                endif;
                ?>
            </div>

        </div>
    </div>
</section>
<?php
get_footer(vibe_get_footer());
?>