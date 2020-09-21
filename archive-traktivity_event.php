<?php
/**
 * The template for displaying Trakt Archive pages.
 *
 * @package Independent Jeremy
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<main id="content" class="site-content" role="main">

			<?php if ( have_posts() ) : ?>

				<header class="page-header">
					<h1 class="page-title">
						<?php _e( 'Recently watched', 'independent-publisher' ); ?>
					</h1>
					A quick list of the last TV shows and movies I watched.
				</header><!-- .page-header -->

				<div class="event-grid">
					<div class="grid-sizer"></div>
					<div class="gutter-sizer"></div>

					<?php while ( have_posts() ) : the_post(); ?>
						<?php get_template_part( 'content', 'traktivity_archive' ); ?>
					<?php endwhile; ?>

				</div>
				<!-- .event-grid -->

				<?php independent_publisher_content_nav( 'nav-below' ); ?>

			<?php else : ?>

				<?php get_template_part( 'content', 'none' ); ?>

			<?php endif; ?>

		</main>
		<!-- #content .site-content -->
	</section><!-- #primary .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
