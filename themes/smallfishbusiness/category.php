<?php
/**
 * Category Archive Template
 *
 * The template for displaying category archives.
 *
 * @package SmallFishBusiness
 */

get_header();
?>

<main id="primary" class="site-main py-10 lg:py-14">
	<div class="container-fluid">
		<!-- Breadcrumbs -->
		<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

		<!-- Category Header -->
		<header class="mb-10 lg:mb-12">
			<h1 class="font-display text-3xl md:text-4xl lg:text-5xl font-semibold text-surface-900 mb-3">
				<?php single_cat_title(); ?>
			</h1>
			<?php if ( category_description() ) : ?>
				<div class="text-surface-600 text-lg max-w-2xl leading-relaxed">
					<?php echo wp_kses_post( category_description() ); ?>
				</div>
			<?php endif; ?>
		</header>

		<div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14">
			<!-- Main Content -->
			<div class="lg:col-span-8">
				<?php if ( have_posts() ) : ?>
					<div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-12 stagger-children">
						<?php
						while ( have_posts() ) :
							the_post();
							get_template_part( 'template-parts/content/article-card' );
						endwhile;
						?>
					</div>

					<div class="mt-14">
						<?php get_template_part( 'template-parts/components/pagination' ); ?>
					</div>
				<?php else : ?>
					<div class="text-center py-16">
						<div class="max-w-md mx-auto">
							<svg class="w-16 h-16 mx-auto text-surface-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
							</svg>
							<p class="text-surface-600 text-lg">
								<?php esc_html_e( 'No posts found in this category.', 'smallfishbusiness' ); ?>
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
