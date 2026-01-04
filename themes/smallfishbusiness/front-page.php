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

<main id="primary" class="site-main">
	<div class="container-fluid py-8">
		<h1 class="sr-only"><?php bloginfo( 'name' ); ?></h1>

		<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
			<!-- Main Content -->
			<div class="lg:col-span-3">
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
					<div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-12">
						<?php
						while ( $query->have_posts() ) :
							$query->the_post();
							get_template_part( 'template-parts/content/article-card' );
						endwhile;
						?>
					</div>

					<div class="mt-12">
						<?php
						// Set global query for pagination to work.
						$GLOBALS['wp_query'] = $query;
						get_template_part( 'template-parts/components/pagination' );
						wp_reset_postdata();
						?>
					</div>
				<?php else : ?>
					<div class="text-center py-12">
						<p class="text-gray-600">
							<?php esc_html_e( 'No posts found.', 'smallfishbusiness' ); ?>
						</p>
					</div>
				<?php endif; ?>
			</div>

			<!-- Sidebar -->
			<div class="lg:col-span-1">
				<?php get_sidebar(); ?>
			</div>
		</div>
	</div>
</main>

<?php
get_footer();
