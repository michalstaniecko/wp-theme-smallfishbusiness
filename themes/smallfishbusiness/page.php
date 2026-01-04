<?php
/**
 * Page Template
 *
 * The template for displaying static pages.
 *
 * @package SmallFishBusiness
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="container-fluid py-8">
		<!-- Breadcrumbs -->
		<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

		<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
			<!-- Main Content -->
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'lg:col-span-3' ); ?>>
				<?php
				while ( have_posts() ) :
					the_post();
					?>

					<h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-8">
						<?php the_title(); ?>
					</h1>

					<div class="prose prose-lg max-w-none">
						<?php the_content(); ?>
					</div>

					<?php
					// If comments are open or we have at least one comment.
					if ( comments_open() || get_comments_number() ) :
						?>
						<div class="mt-12 pt-8 border-t border-gray-200">
							<?php comments_template(); ?>
						</div>
					<?php endif; ?>

				<?php endwhile; ?>
			</article>

			<!-- Sidebar -->
			<div class="lg:col-span-1">
				<?php get_sidebar(); ?>
			</div>
		</div>
	</div>
</main>

<?php
get_footer();
