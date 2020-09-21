<?php

/**
 * Custom things addd to the theme
 */
function jeherve_custom_theme_setup() {
	add_image_size( 'single-thumbnail-top', 700, 240, array( 'center', 'top' ) );

	// Declare AMP support.
	//add_theme_support( 'amp' );

	// Declare support for Geolocation.
	add_theme_support( 'jetpack-geo-location' );
}
add_action( 'after_setup_theme', 'jeherve_custom_theme_setup', 11 );

// Enqueue and dequeue things
function jeherve_custom_styles_scripts() {
	// Stylesheets
	wp_enqueue_style( 'independent-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'independent-jeremy', get_stylesheet_directory_uri(). '/style.css' );

	wp_dequeue_style( 'genericons' );
	wp_dequeue_script( 'fade-post-title' );
	wp_dequeue_style( 'customizer' );
	wp_dequeue_script( 'enhanced-comment-form-js' );
}
add_action( 'wp_enqueue_scripts', 'jeherve_custom_styles_scripts', 11 );

/**
 * Empty Theme credits
 */
function independent_publisher_get_footer_credits() {
	return;
}
/**
 * No custom things.
 */
function independent_publisher_jetpack_sharing_css() {
	return;
}
function independent_publisher_jetpack_sharing_label_css() {
	return;
}
function independent_publisher_wp_pagenavi_css() {
	return;
}
function independent_publisher_stylesheet() {
	return;
}
function independent_publisher_show_related_tags() {
	return;
}
function independent_publisher_enhanced_comment_form() {
	return;
}
function independent_publisher_site_logo_icon_js() {
	return;
}
/**
 * Always display the author meta box.
 */
function independent_publisher_show_author_card() {
	return true;
}

/**
 * Custom Header
 */
function independent_publisher_site_info() {
	?>
	<?php if ( get_header_image() ) :

	$header_image_args = array(
		'resize' => absint( get_custom_header()->width ) . absint( get_custom_header()->height ),
	);
	$header_image = apply_filters(
		'jetpack_photon_url',
		get_header_image(),
		$header_image_args
	);
	?>
		<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
			<img class="no-grav" src="<?php echo esc_url( $header_image ); ?>" height="<?php echo absint( get_custom_header()->height ); ?>" width="<?php echo absint( get_custom_header()->width ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" />
		</a>
	<?php endif; ?>
	<h1 class="site-title">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
	</h1>
	<?php
}

/**
 * Color work.
 *
 * @param string $colors_css CSS added to each post.
 * @param string $color Post's average color.
 * @param string $contrast Matching contrast color.
 */
function jeherve_custom_colors( $colors_css, $color, $contrast ) {
	$colors_css = sprintf(
		'
.post-thumbnail {
  position: relative; /* for child pseudo-element */
  margin: 0 -9999rem;
  /* add back negative margin value */
  padding: 0 9999rem;
  background: #%1$s;
}
.mejs-container, .mejs-container .mejs-controls, .mejs-embed, .mejs-embed body {
    background: #%1$s !important;
}
	',
	$color
	);

	// if the contrast color is black (light background color), set the audio player colors to black.
	if ( '0,0,0' === $contrast ) {
		$colors_css .= sprintf(
			"
			.mejs-controls .mejs-button button { background-image: url('%s/mejs-controls-dark.svg') !important;}
			.mejs-controls .mejs-time-rail .mejs-time-total, .mejs-controls .mejs-time-rail .mejs-time-current, .mejs-controls .mejs-time-rail .mejs-time-loaded { background-color: #000 !important; }
			.mejs-controls .mejs-time .mejs-currenttime, .mejs-controls .mejs-time .mejs-duration { color: #000 !important;}
			",
			get_stylesheet_directory_uri()
		);
	}

	return $colors_css;
}
add_filter( 'colorposts_css_output', 'jeherve_custom_colors', 10, 3 );
