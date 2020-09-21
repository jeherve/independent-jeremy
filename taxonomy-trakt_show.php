<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Independent Publisher
 * @since   Independent Publisher 1.0
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<main id="content" class="site-content" role="main">

			<?php if ( have_posts() ) :
				$term = get_term( get_queried_object_id() );

				$poster = get_term_meta( $term->term_id, 'show_poster', true );
				if ( ! empty( $poster['tag'] ) ) {
					echo $poster['tag'];
				}
				?>
				<header class="page-header">
					<h1 class="page-title"><?php esc_html_e( $term->name ); ?></h1>
					<?php
					// Display the show's network.
					$network = get_term_meta( $term->term_id, 'show_network', true );
					if ( ! empty( $network ) ) {
						printf(
							'<div class="show_network"><strong>Network:</strong> %s</div>',
							esc_html( $network )
						);
					}

					// Display links to other sites.
					$elsewhere = get_term_meta( $term->term_id, 'show_external_ids', true );
					if ( ! empty( $elsewhere ) ) {
						printf(
							'<div class="show_links"><a href="http://www.imdb.com/title/%1$s">IMDb</a> | <a href="https://trakt.tv/shows/%2$s">Trakt</a> | <a href="https://www.themoviedb.org/tv/%3$s">TMDb</a></div><hr />',
							esc_attr( $elsewhere['imdb'] ),
							esc_attr( $elsewhere['trakt'] ),
							esc_attr( $elsewhere['tmdb'] )
						);
					}

					// Show genres.
					$genres = array();
					$genre_args = array(
						'post_type'      => 'traktivity_event',
						'post_status'    => 'publish',
						'tax_query'      => array(
							array(
								'taxonomy' => 'trakt_show',
								'field'    => 'term_id',
								'terms'    => $term->term_id,
							),
						),
					);
					$episodes_query = new WP_Query( $genre_args );
					while ( $episodes_query->have_posts() ) {
						$episodes_query->the_post();

						$genre_terms = get_the_terms( $episodes_query->post->ID, 'trakt_genre' );

						if ( $genre_terms && ! is_wp_error( $genre_terms ) ) {
							foreach ( $genre_terms as $genre_term ) {
								if ( ! in_array( $genre_term->name, $genres ) ) {
									$genres[] = $genre_term->name;
								}
							}
						}
					}
					wp_reset_postdata();

					if ( ! empty( $genres ) ) {
						printf(
							'<div class="show_genres"><strong>Genre:</strong> %s</div><hr />',
							esc_html( implode( $genres, ', ' ) )
						);

					}

					// Show an optional tag description.
					$description = term_description( $term->term_id, 'trakt_show' );
					if ( ! empty( $description ) ) {
						echo apply_filters(
							'tag_archive_meta',
							'<div class="taxonomy-description">' . $description . '</div><hr />'
						);
					}

					// Season list.
					$seasons = array();
					$seasons_args = array(
						'post_type'      => 'traktivity_event',
						'post_status'    => 'publish',
						'posts_per_page' => -1,
						'tax_query'      => array(
							array(
								'taxonomy' => 'trakt_show',
								'field'    => 'term_id',
								'terms'    => $term->term_id,
							),
						),
					);
					$seasons_query = new WP_Query( $seasons_args );
					while ( $seasons_query->have_posts() ) {
						$seasons_query->the_post();

						// Get an event's list of seasons (always one season actually, but get_the_terms returns an array).
						$season_terms = get_the_terms( get_the_ID(), 'trakt_season' );
						if ( $season_terms && ! is_wp_error( $season_terms ) ) {
							// let's get the season number.
							foreach ( $season_terms as $season_term ) {
								$season_number = $season_term->name;
								// Was the season number already added to our array?
								if ( ! array_key_exists( $season_term->name, $seasons ) ) {
									/**
									 * Add it to our array.
									 * For that new season, we also create a new empty array
									 * that will later be filled in with episodes.
									 */
									$seasons[ $season_term->name ] = array();
								}
							}
						}

						// Now let's add the episodes to that array of seasons.
						$episode_terms = get_the_terms( get_the_ID(), 'trakt_episode' );
						if ( $episode_terms && ! is_wp_error( $episode_terms ) ) {
							/**
							 * We now have $episode_term->name being the event's episode number.
							 *             $season_term->name is the event's season number.
							 *
							 * We aim for this:
							 * $seasons array(
							 *      $season_term->name => array(
							 *          $episode_term->name => <a href="link">$episode_term->name</a>
							 *      )
							 * )
							 */
							foreach ( $seasons as $season => $episode_list ) {
								// Let's get the episode number.
								foreach ( $episode_terms as $episode_term ) {
									if ( ! array_key_exists( $episode_term->name, $episode_list ) ) {
										$seasons[ $season_number ][ $episode_term->name ] = sprintf(
											'<a href="%1$s">%2$s</a>',
											get_the_permalink(),
											esc_html( $episode_term->name )
										);
									}
								}
							}
						}
					} // End while().
					wp_reset_postdata();

					if ( ! empty( $seasons ) ) {
						// We want the first season displayed first.
						ksort( $seasons );

						echo '<div class="show_seasons"><strong>Seasons and episodes I watched:</strong> ';
						foreach ( $seasons as $season => $episodes ) {
							// We want the first episode displayed first in each list of episodes.
							ksort( $episodes );

							printf(
								'<div class="season-%1$s">%1$s: %2$s</div>',
								esc_html( $season ),
								implode( $episodes, ', ' )
							);
						}
						echo '</div>';

						// Add the time it took altogether.
						$total_time = get_term_meta( $term->term_id, 'show_runtime', true );
						if ( ! empty( $total_time ) && class_exists( 'Traktivity_Stats' ) ) {
							printf(
								'<div class="show_seasons"><strong>That makes for:</strong> %1$s watching this show!</div>',
								esc_html( Traktivity_Stats::convert_time( $total_time ) )
							);
						}
						echo '<hr />';
					}

					// Check for posts about this show.
					$blog_posts_args = array(
						'post_type'      => 'post',
						'post_status'    => 'publish',
						'tag'            => $term->slug,
					);
					$blog_posts_query = new WP_Query( $blog_posts_args );
					if ( $blog_posts_query->have_posts() ) {
						echo '<div class="blogged">I blogged about it here:<ul>';
						while ( $blog_posts_query->have_posts() ) {
							$blog_posts_query->the_post();
							printf(
								'<li><a href="%1$s">%2$s</a></li>',
								esc_url( get_the_permalink() ),
								get_the_title()
							);
						} // End while().
						echo '</ul></div><hr />';
					} // End if().
					wp_reset_postdata();

					?>
				</header><!-- .page-header -->

				<h1 class="page-title">Last Watched</h1>
				<hr>
				<?php /* Start the Loop */ ?>
					<?php while ( have_posts() ) : the_post(); ?>

					<?php
					/* Include the Post-Format-specific template for the content.
					 * If you want to overload this in a child theme then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'content', get_post_format() );
					?>

				<?php endwhile; ?>

				<?php independent_publisher_content_nav( 'nav-below' ); ?>

			<?php else : ?>

				<?php get_template_part( 'content', 'none' ); ?>

			<?php endif; ?>

		</main>
		<!-- #content .site-content -->
	</section><!-- #primary .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
