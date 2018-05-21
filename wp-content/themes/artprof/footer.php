</div><!-- end of #page-content-wrapper -->

<?php if(is_front_page()) { ?>
<div class="sitecredits">&copy; <?php echo date('Y'); ?> ArtProf. All rights reserved. <a href="/terms-and-conditions">Site Disclaimer</a>.<br /></div>
<?php } ?>

</div><!-- end of #wrapper -->


<?php if(!is_front_page()) { ?>
<!-- Social Icons -->
<ul id="social_side_links">
	<?php
	global $post;
	?>
	<li><a class="facebookshare" href="javascript:" onclick="javascript:window.open('//www.facebook.com/sharer/sharer.php?u=<?php echo the_permalink();?>', '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" target="_blank" title="Share On Facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
	<li><a class="twittershare" href="javascript:" onclick="window.open('//twitter.com/share?url=<?php echo the_permalink();?>&amp;text=<?php echo social_share_excerpt();?>','_blank','width=800,height=300')" title="Share On Twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
	<li><a class="pinterestshare" onclick="window.open('//pinterest.com/pin/create/button/?url=<?php echo the_permalink();?>&amp;media=<?php if ( has_post_thumbnail() ) { echo get_the_post_thumbnail_url(); } else { echo '/wp-content/uploads/2017/01/logo.png'; } ?>&amp;description=<?php echo social_share_excerpt(); ?>','pinIt','toolbar=0,status=0,width=800,height=500');" href="javascript:void(0);" title="Share On Pinterest"><i class="fa fa-pinterest-p" aria-hidden="true"></i></a></li>
</ul>
<?php }?>
	
<?php
wp_footer(); 
?>

	<?php include('includes/scripts.php'); ?>

<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
  (adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-3012444609101905",
    enable_page_level_ads: true
  });
</script>

<script language="JavaScript" type="text/javascript">
TrustLogo("https://artprof.org/comodo_secure_seal_76x26_transp.png", "CL1", "none");
</script>
</body>
</html>