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
	<div class="container-fluid py-8">
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

		<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
			<!-- Main Content -->
			<div class="lg:col-span-3">
				<?php if ( have_posts() ) : ?>
					<div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-12">
						<?php
						while ( have_posts() ) :
							the_post();
							get_template_part( 'template-parts/content/article-card' );
						endwhile;
						?>
					</div>

					<div class="mt-12">
						<?php get_template_part( 'template-parts/components/pagination' ); ?>
					</div>
				<?php else : ?>
					<div class="text-center py-12">
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
