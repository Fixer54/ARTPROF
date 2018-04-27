<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="msvalidate.01" content="0CF7EC995E7AD3FD2A4E229865108D8F" />

<title>
<?php echo wp_title('|',true,'right'); ?>
</title>

<link href="https://fonts.googleapis.com/css?family=Merriweather:300,300i,400,400i,700,700i,900,900i" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i" rel="stylesheet">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<?php
$layout = vibe_get_option('layout');
if(!isset($layout) || !$layout)
    $layout = '';

wp_head();
?>

<script src="https://use.fontawesome.com/7d1c0302b4.js"></script>

<script type="text/javascript"> //<![CDATA[ 
var tlJsHost = ((window.location.protocol == "https:") ? "https://secure.comodo.com/" : "http://www.trustlogo.com/");
document.write(unescape("%3Cscript src='" + tlJsHost + "trustlogo/javascript/trustlogo.js' type='text/javascript'%3E%3C/script%3E"));
//]]>
</script>

</head>
<body <?php body_class($layout); ?>>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-92626035-1', 'auto');
  ga('send', 'pageview');

</script>

	<a class="skip-main" href="#page-content-wrapper">Skip to main content</a>


  	
	<?php if(is_front_page()) { ?>
		
		<div class="header container">

            <img srcset="/wp-content/themes/artprof/img/artprof_logo_home@1x.png,
            /wp-content/themes/artprof/img/artprof_logo_home@2x.png 2x" src="/wp-content/themes/artprof/img/artprof_logo_home@1x.png" alt="ArtProf">

            <?php

                $args = apply_filters('wplms-main-menu',array(
                     'theme_location'  => 'main-menu',
                     'container'       => 'nav',
                     'menu_class'      => 'home-menu',
                     'walker'          => new vibe_walker,
                     'fallback_cb'     => 'vibe_set_menu'
                 ));
                wp_nav_menu( $args ); 
            ?>
		</div>
		
		
		<?php } ?>
		
	<div id="wrapper" class="toggled">
		
	<?php get_sidebar('artprof-sidebar'); ?>
	
	<div id="page-content-wrapper">
	
   <a href="<?php echo vibe_site_url(); ?>" class="mobile-logo"><img id="logo_img" src="<?php  echo apply_filters('wplms_logo_url',VIBE_URL.'/images/logo.png'); ?>" alt="<?php echo get_bloginfo('name'); ?>" /></a>
	
	
	
	
