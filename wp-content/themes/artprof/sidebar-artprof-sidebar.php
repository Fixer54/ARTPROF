<a href="#" id="nav-bttn" <?php if($sidebarClose === "false") { ?>class="active"<?php }?>>
    <div class="nav-bttn-line" id="nav-bttn-line-one"></div>
    <div class="nav-bttn-line" id="nav-bttn-line-two"></div>
    <div class="nav-bttn-line" id="nav-bttn-line-three"></div>
  </a>
 
 <div id="sidebar-wrapper">
 
                            <a href="<?php echo vibe_site_url(); ?>" class="logo"><img id="logo_img" src="<?php  echo apply_filters('wplms_logo_url',VIBE_URL.'/images/logo.png'); ?>" alt="<?php echo get_bloginfo('name'); ?>" />
                            <img src="<?php  $logo = vibe_get_option('logo_fixed');
                                if(isset($logo) && $logo)
                                    $url = $logo;

                                if(is_ssl()){
                                    if (substr($url, 0, 7) == "http://")
                                        $url = str_replace('http','https',$url);
                                }

                                if(isset($url))
                                    echo $url;
                                else
                                    echo apply_filters('wplms_logo_url',VIBE_URL.'/images/logo.png');
                                 ?>" id="fixed_logo" alt="<?php echo get_bloginfo('name'); ?>" /></a>
                        <?php

                            $args = apply_filters('wplms-main-menu',array(
                                 'theme_location'  => 'main-menu',
                                 'container'       => '',
                                 'menu_class'      => 'sidebar-nav',
                                 'walker'          => new vibe_walker,
                                 'fallback_cb'     => 'vibe_set_menu'
                             ));
                            wp_nav_menu( $args ); 
                        ?>



		   <form class="navbar-form" role="search" action="<?php echo home_url( '/' ); ?>">
		     <div class="form-group">
   		        <input type="search" class="form-control" placeholder="Search" name="s" id="search-input" value="<?php echo esc_attr( get_search_query() ); ?>" />
		     </div>
	   				<button type="submit" id="search-submit" class="fabutton">
	   					  <i class="fa fa-search" aria-hidden="true"></i>
	   				</button>
		   </form>



							<div class="clear-xs"></div>
							<div class="flags">
							<?php echo do_shortcode('[gtranslate]'); ?>
							</div>
							
							<div class="clear-xs"></div>
							
<a class="button donate" href="https://www.patreon.com/artprof" target="_blank">DONATE</a>
<?php
if ( function_exists('bp_loggedin_user_id') && bp_loggedin_user_id() ) {	?>			
	<a class="button user" href="<?php echo bp_loggedin_user_domain(); ?>" target="_blank">MY PROFILE</a>	
<?php } else { ?>
	<a class="button user" href="/login/">LOG IN</a>		
<?php }?>

							<div class="clear-xs"></div>
							<div class="socialmedia">
								<div class="row">
									<a href="https://www.instagram.com/art.prof/" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a>
									<a href="https://www.facebook.com/claralieu/" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a>
									<a href="https://www.pinterest.com/claralieu/" target="_blank"><i class="fa fa-pinterest-p" aria-hidden="true"></i></a>
								</div>
								<div class="row">
									<a href="https://www.youtube.com/channel/UCG19ZyhUNbkPzU105yq8Rgw/featured" target="_blank"><i class="fa fa-youtube" aria-hidden="true"></i></a>
									<a href="https://twitter.com/claralieu" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a>
								</div>
							</div>
							
							<div class="clear-xs"></div>
								<div class="sitecredits">
							&copy; <?php echo date('Y'); ?> ArtProf. All rights reserved.<br /><a href="/terms-and-conditions">Site Disclaimer</a>. Art Prof is not supported, endorsed, or sponsored by RISD.<br /><br />Site by <a href="http://thelibzter.com" title="Website design and development by Libby Fisher" target="_blank">Libby Fisher</a>.
						
							</div>
							
							
							
							
				        </div>
						
						
