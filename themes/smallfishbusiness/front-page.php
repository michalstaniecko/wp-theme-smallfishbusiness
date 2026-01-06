<?php
/**
 * Front Page Template
 *
 * The template for displaying the front page.
 *
 * @package SmallFishBusiness
 */

get_header();
?>

<main id="primary" class="site-main py-10 lg:py-14">
	<div class="container-fluid">
		<h1 class="sr-only"><?php bloginfo( 'name' ); ?></h1>

		<div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14">
			<!-- Main Content -->
			<div class="lg:col-span-8">
				<?php
				$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
				$args  = [
					'post_type'      => 'post',
					'posts_per_page' => 10,
					'paged'          => $paged,
				];

				$query = new WP_Query( $args );

				if ( $query->have_posts() ) :
				?>
					<div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-12 stagger-children">
						<?php
						$post_count = 0;
						while ( $query->have_posts() ) :
							$query->the_post();
							$post_count++;

							// First post is featured (full width)
							if ( $post_count === 1 && $paged === 1 ) :
							?>
								<article <?php post_class( 'article-card article-card--featured md:col-span-2' ); ?>>
									<!-- Featured Image -->
									<div class="article-card__image-wrapper aspect-[16/9] lg:aspect-[21/9]">
										<a href="<?php the_permalink(); ?>" class="block w-full h-full no-underline" tabindex="-1" aria-hidden="true">
											<?php if ( has_post_thumbnail() ) : ?>
												<?php the_post_thumbnail( 'large', [
													'class' => 'article-card__image',
													'alt'   => get_the_title(),
												] ); ?>
											<?php else : ?>
												<div class="w-full h-full flex items-center justify-center bg-surface-100">
													<img src="<?php echo esc_url( get_template_directory_uri() . '/img/logo-small-fish-business.png' ); ?>"
													     alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
													     class="max-w-[40%] max-h-[40%] object-contain opacity-30">
												</div>
											<?php endif; ?>
										</a>
									</div>

									<!-- Category -->
									<?php
									$categories = get_the_category();
									if ( ! empty( $categories ) ) :
									?>
										<a href="<?php echo esc_url( get_category_link( $categories[0]->term_id ) ); ?>"
										   class="article-card__category mt-6">
											<?php echo esc_html( $categories[0]->name ); ?>
										</a>
									<?php endif; ?>

									<!-- Title -->
									<h2 class="article-card__title text-2xl md:text-3xl lg:text-4xl">
										<a href="<?php the_permalink(); ?>">
											<?php the_title(); ?>
										</a>
									</h2>

									<!-- Meta -->
									<div class="article-card__meta">
										<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
											<?php echo esc_html( get_the_date() ); ?>
										</time>
										<span class="article-card__meta-divider" aria-hidden="true"></span>
										<span><?php echo esc_html( get_the_author() ); ?></span>
									</div>

									<!-- Excerpt -->
									<p class="article-card__excerpt text-base line-clamp-3 max-w-2xl">
										<?php echo esc_html( wp_trim_words( get_the_excerpt(), 30, '...' ) ); ?>
									</p>

									<!-- Read More -->
									<a href="<?php the_permalink(); ?>" class="article-card__read-more">
										<?php esc_html_e( 'Read article', 'smallfishbusiness' ); ?>
										<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
										</svg>
									</a>
								</article>
							<?php
							else :
								get_template_part( 'template-parts/content/article-card' );
							endif;
						endwhile;
						?>
					</div>

					<div class="mt-14">
						<?php
						// Set global query for pagination to work.
						$GLOBALS['wp_query'] = $query;
						get_template_part( 'template-parts/components/pagination' );
						wp_reset_postdata();
						?>
					</div>
				<?php else : ?>
					<div class="text-center py-16">
						<div class="max-w-md mx-auto">
							<svg class="w-16 h-16 mx-auto text-surface-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
							</svg>
							<p class="text-surface-600 text-lg">
								<?php esc_html_e( 'No posts found.', 'smallfishbusiness' ); ?>
							</p>
						</div>
					</div>
				<?php endif; ?>
			</div>

			<!-- Sidebar -->
			<aside class="lg:col-span-4">
				<div class="lg:sticky lg:top-28">
					<?php get_sidebar(); ?>
				</div>
			</aside>
		</div>
	</div>
</main>

<?php
get_footer();
