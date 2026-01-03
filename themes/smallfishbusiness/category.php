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

<main id="primary" class="site-main">
	<div class="container mx-auto px-4 py-8">
		<!-- Breadcrumbs -->
		<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

		<!-- Category Header -->
		<header class="mb-8">
			<h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">
				<?php single_cat_title(); ?>
			</h1>
			<?php if ( category_description() ) : ?>
				<div class="text-gray-600 text-lg">
					<?php echo wp_kses_post( category_description() ); ?>
				</div>
			<?php endif; ?>
		</header>

		<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
			<!-- Main Content -->
			<div class="lg:col-span-2">
				<?php if ( have_posts() ) : ?>
					<div class="space-y-8">
						<?php
						while ( have_posts() ) :
							the_post();
							get_template_part( 'template-parts/content/article-card' );
						endwhile;
						?>
					</div>

					<?php get_template_part( 'template-parts/components/pagination' ); ?>
				<?php else : ?>
					<div class="bg-white rounded-lg shadow-sm p-8 text-center">
						<p class="text-gray-600">
							<?php esc_html_e( 'No posts found in this category.', 'smallfishbusiness' ); ?>
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
