<?php
/**
 * @package Independent Publisher
 * @since   Independent Publisher 1.0
 */
?>
<article id="post-<?php the_ID(); ?>" <?php independent_publisher_post_classes(); ?>>
	<header class="entry-header">
		<h1 class="entry-title p-name">
			<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'independent-publisher' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
		</h1>
	</header>
	<!-- .entry-header -->

	<div class="entry-content e-content">
			<?php do_action( 'jeherve_independent_content_before', get_the_id() ); ?>

			<?php /* Only show featured image for Standard post and gallery post formats */ ?>
			<?php if ( has_post_thumbnail() && in_array( get_post_format(), array( 'gallery', false ) ) ) : ?>
				<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'independent-publisher' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_post_thumbnail( 'independent_publisher_post_thumbnail' ); ?></a>
			<?php endif; ?>

			<?php the_content( independent_publisher_continue_reading_text() ); ?>

			<?php wp_link_pages(
				array(
					'before' => '<div class="page-links">' . __( 'Pages:', 'independent-publisher' ),
					'after'  => '</div>'
				)
			); ?>

	</div>
	<!-- .entry-content -->

	<footer class="entry-meta">

		<?php
		/* Show author name and post categories only when post type == post AND
		 * we're not showing the first post full content
		 */
		?>
		<?php if ( 'post' == get_post_type() && independent_publisher_is_not_first_post_full_content() ) : // post type == post conditional hides category text for Pages on Search ?>
			<?php independent_publisher_posted_author_cats() ?>
		<?php endif; ?>

		<?php /* Show post date when show post date option enabled */
		?>
		<?php if ( independent_publisher_show_date_entry_meta() ) : ?>
			<?php echo independent_publisher_get_post_date() ?>
		<?php endif; ?>

		<?php $separator = apply_filters( 'independent_publisher_entry_meta_separator', '|' ); ?>

		<?php /* Show webmentions link only when post is not password-protected AND pings open AND there are mentions on this post */ ?>
		<?php if ( !post_password_required() && pings_open() && independent_publisher_comment_count_mentions() ) : ?>
			<?php $mention_count = independent_publisher_comment_count_mentions(); ?>
			<?php $mention_label = (independent_publisher_comment_count_mentions() > 1 ? __( 'Webmentions', 'independent-publisher' ) : __( 'Webmention', 'independent-publisher' ) ); ?>
			<span class="mentions-link"><a href="<?php the_permalink(); ?>#webmentions"><?php echo $mention_count . ' ' . $mention_label; ?></a></span><span class="sep"><?php echo (comments_open() && !independent_publisher_hide_comments()) ?  ' '.$separator : '' ?></span>
		<?php endif; ?>

		<?php /* Show comments link only when post is not password-protected AND comments are enabled on this post */ ?>
		<?php if ( !post_password_required() && comments_open() && !independent_publisher_hide_comments() ) : ?>
			<span class="comments-link"><?php comments_popup_link( __( 'Comment', 'independent-publisher' ), __( '1 Comment', 'independent-publisher' ), __( '% Comments', 'independent-publisher' ) ); ?></span>
		<?php endif; ?>

		<?php edit_post_link( __( 'Edit', 'independent-publisher' ), '<span class="sep"> ' . $separator . ' </span> <span class="edit-link">', '</span>' ); ?>

	</footer>
	<!-- .entry-meta -->
</article><!-- #post-<?php the_ID(); ?> -->
