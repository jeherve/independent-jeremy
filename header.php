<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package Independent Jeremy
 * @since   Independent Jeremy 1.0
 */
?><!DOCTYPE html>
<html <?php independent_publisher_html_tag_schema(); ?> <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
	<![endif]-->
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> itemscope="itemscope" itemtype="http://schema.org/WebPage">

<div id="page" class="site">
	<header id="masthead" class="site-header" role="banner" itemscope itemtype="http://schema.org/WPHeader">

	<?php if ( ! is_single() ) : ?>
		<div class="site-header-info">
			<?php independent_publisher_site_info(); ?>
		</div>
	<?php endif; ?>

			<nav role="navigation" class="site-navigation main-navigation">
				<a class="screen-reader-text skip-link" href="#content" title="<?php esc_attr_e( 'Skip to content', 'independent-publisher' ); ?>"><?php _e( 'Skip to content', 'independent-publisher' ); ?></a>
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'depth' => 1 ) ); ?>
			</nav><!-- .site-navigation .main-navigation -->

		<?php do_action( 'independent_publisher_header_after' ); ?>
	</header>
	<!-- #masthead .site-header -->

	<div id="main" class="site-main">
