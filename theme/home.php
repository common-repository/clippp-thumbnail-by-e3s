<?php
global $clippp_thumbnail;
wp_enqueue_style('clippp-home', $clippp_thumbnail::$base_url . 'css/home.css', ['sydney-style']);
get_header();
do_action('clippp_thumbnail');
?>
    
    </div></div></div><!-- #content -->
    
	<div id="price-wrapper" role="complementary">
		<div class="container">
			<div class="sidebar-column col-md-12">
				<?php get_template_part('price'); ?>
			</div>
		</div>
	</div>
	
	<div id="contact-wrapper" class="footer-widgets" role="complementary">
		<div class="container">
			<div class="sidebar-column col-md-12">
				<?php get_template_part('contact'); ?>
			</div>
		</div>
	</div>
	
	<div id="posts-wrapper" role="complementary">
		<div class="container">
			<div class="sidebar-column col-md-12">
				<?php get_template_part('posts'); ?>

<?php get_footer(); 