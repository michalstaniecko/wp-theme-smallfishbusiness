<?php
/**
 * 404 Error Page Template
 *
 * The template for displaying 404 pages (not found).
 *
 * @package SmallFishBusiness
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="container-fluid py-16">
		<div class="max-w-2xl mx-auto text-center">
			<h1 class="text-6xl md:text-8xl font-bold text-gray-900 mb-4">404</h1>

			<h2 class="text-2xl md:text-3xl font-semibold text-gray-700 mb-4">
				<?php esc_html_e( 'Page Not Found', 'smallfishbusiness' ); ?>
			</h2>

			<p class="text-gray-600 text-lg mb-8">
				<?php esc_html_e( 'Sorry, the page you are looking for does not exist or has been moved.', 'smallfishbusiness' ); ?>
			</p>

			<!-- Search Form -->
			<div class="mb-8 max-w-md mx-auto">
				<?php get_search_form(); ?>
			</div>

			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"
			   class="inline-flex items-center px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors">
				<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
				</svg>
				<?php esc_html_e( 'Back to Homepage', 'smallfishbusiness' ); ?>
			</a>
		</div>

		<!-- Recent Posts Section -->
		<div class="mt-16 max-w-4xl mx-auto">
			<h3 class="text-xl font-semibold text-gray-900 mb-6 text-center">
				<?php esc_html_e( 'Recent Posts', 'smallfishbusiness' ); ?>
			</h3>

			<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
				<?php
				$recent_posts = new WP_Query(
					[
						'post_type'      => 'post',
						'posts_per_page' => 3,
					]
				);

				if ( $recent_posts->have_posts() ) :
					while ( $recent_posts->have_posts() ) :
						$recent_posts->the_post();
						?>
						<article class="bg-white rounded-lg shadow-sm overflow-hidden">
							<?php if ( has_post_thumbnail() ) : ?>
								<a href="<?php the_permalink(); ?>" class="block">
									<?php
									the_post_thumbnail(
										'medium',
										[
											'class' => 'w-full h-40 object-cover',
											'alt'   => get_the_title(),
										]
									);
									?>
								</a>
							<?php endif; ?>

							<div class="p-4">
								<h4 class="font-medium text-gray-900 mb-2 line-clamp-2">
									<a href="<?php the_permalink(); ?>" class="hover:text-primary-600 transition-colors">
										<?php the_title(); ?>
									</a>
								</h4>
								<p class="text-sm text-gray-500">
									<?php echo esc_html( get_the_date() ); ?>
								</p>
							</div>
						</article>
						<?php
					endwhile;
					wp_reset_postdata();
				endif;
				?>
			</div>
		</div>
	</div>
</main>

<?php
get_footer();
