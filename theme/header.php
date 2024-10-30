<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php if ( ! function_exists( 'has_site_icon' ) || ! has_site_icon() ) : ?>
	<?php if ( get_theme_mod('site_favicon') ) : ?>
		<link rel="shortcut icon" href="<?php echo esc_url(get_theme_mod('site_favicon')); ?>" />
	<?php endif; ?>
<?php endif; ?>

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div class="preloader">
    <div class="spinner">
        <div class="pre-bounce1"></div>
        <div class="pre-bounce2"></div>
    </div>
</div>	
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'sydney' ); ?></a>
    <?php include __DIR__ . '/front-movie.php'; ?>
    
	<header id="masthead" class="site-header" role="banner">
		<div class="header-wrap">
			<nav class="container">
	        	<a
	        		href="<?php echo esc_url( home_url( '/' ) ); ?>"
	        		title="<?php bloginfo('name'); ?>"
	        	><img
	        		class="site-logo"
	        		src="<?php echo esc_url(get_theme_mod('site_logo') ? get_theme_mod('site_logo') : 'http://www.oradoko.jp/gourmet/images/noimage.jpg'); ?>"
	        		alt="<?php bloginfo('name'); ?>"
	        	>
	        	</a>
	        	<a href="#posts-wrapper"><span>Posts</span><span>ポスト</span></a>
	        	<a href="#contact-wrapper"><span>Contact</span><span>コンタクト</span></a>
	        	<a href="#price-wrapper"><span>Price</span><span>プライス</span></a>
	        	<a href="#content"><span>Wedding</span><span>ウェディング</span></a>
			</nav>
		</div>
	</header><!-- #masthead -->
	<script>
		
		window.jQuery(function($) {
			var h = $("#masthead"), p = $("#front-movie").find("video");
			
			function size_to_fit(e) {
				var c = p.offset().top * 2 + p.height(), s = $(this).scrollTop();
				if (h.hasClass("fixed")) {
				} else {
					
				}
			}
			
			$(window).on("load scroll", size_to_fit);
		})
		
	</script>

	<div id="intro" class="page-wrap panel-grid-cell text-center">
		<div class="container content-wrapper">
			<h1><?php bloginfo('description'); ?></h1>
		</div>
	</div>
	<div id="content" class="page-wrap panel-grid-cell">
		<div class="container content-wrapper">
		    <h2 class="widget-title">Wedding</h2>

			<div class="row">