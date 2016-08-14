<?php
/**
 * @package Independent Publisher
 * @since   Independent Publisher 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope="itemscope" itemtype="http://schema.org/BlogPosting" itemprop="blogPost">
	<header class="entry-header">
		<?php if ( has_post_thumbnail() ) :
			$post_image = get_the_post_thumbnail_url( '', 'single-thumbnail-top' );
			printf(
				'<img class="post-thumbnail" itemprop="image" src="%s" />',
				esc_url( apply_filters( 'jetpack_photon_url', $post_image ) )
			);
		endif; ?>
		<h1 class="entry-title p-name" itemprop="name"><?php the_title(); ?></h1>
		<h2 class="entry-title-meta">
			<?php if ( independent_publisher_categorized_blog() ) {
				echo independent_publisher_entry_meta_category_prefix() . ' ' . independent_publisher_post_categories();
			} ?>
			<span class="entry-title-meta-post-date">
			<span class="sep"> <?php echo apply_filters( 'independent_publisher_entry_meta_separator', '|' ); ?> </span>
				<?php independent_publisher_posted_on_date() ?>
		</span>
			<?php do_action( 'independent_publisher_entry_title_meta', $separator = ' | ' ); ?>
		</h2>
	</header>
	<!-- .entry-header -->
	<div class="entry-content e-content" itemprop="mainContentOfPage">
		<?php do_action( 'jeherve_independent_content_before', get_the_id() ); ?>
		<?php the_content(); ?>

		<?php wp_link_pages(
			array(
				'before'           => '<div class="page-links-next-prev">',
				'after'            => '</div>',
				'nextpagelink'     => '<button class="next-page-nav">' . __( 'Next page &rarr;', 'independent-publisher' ) . '</button>',
				'previouspagelink' => '<button class="previous-page-nav">' . __( '&larr; Previous page', 'independent-publisher' ) . '</button>',
				'next_or_number'   => 'next',
			)
		); ?>
		<?php wp_link_pages(
			array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'independent-publisher' ),
				'after'  => '</div>',
			)
		); ?>

	</div>
	<!-- .entry-content -->

	<footer class="entry-meta">
		<?php do_action( 'independent_publisher_entry_meta_top' ); ?>

		<?php if ( comments_open() && ! independent_publisher_hide_comments() ) : ?>
			<div id="share-comment-button">
				<button>
					<i class="share-comment-icon"></i><?php echo esc_attr( independent_publisher_comments_call_to_action_text() ); ?>
				</button>
			</div>
		<?php endif; ?>

		<?php edit_post_link( __( 'Edit', 'independent-publisher' ), '<span class="edit-link">', '</span>' ); ?>
	</footer>
	<!-- .entry-meta -->

</article><!-- #post-<?php the_ID(); ?> -->
