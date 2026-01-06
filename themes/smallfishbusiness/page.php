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

<main id="primary" class="site-main py-10 lg:py-14">
	<div class="container-fluid">
		<!-- Breadcrumbs -->
		<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

		<div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14">
			<!-- Main Content -->
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'lg:col-span-8' ); ?>>
				<?php
				while ( have_posts() ) :
					the_post();
					?>

					<h1 class="font-display text-3xl md:text-4xl lg:text-5xl font-semibold text-surface-900 mb-10 leading-tight">
						<?php the_title(); ?>
					</h1>

					<div class="prose prose-lg max-w-none">
						<?php the_content(); ?>
					</div>

					<?php
					// If comments are open or we have at least one comment.
					if ( comments_open() || get_comments_number() ) :
						?>
						<div class="mt-14 pt-10 border-t border-surface-200">
							<?php comments_template(); ?>
						</div>
					<?php endif; ?>

				<?php endwhile; ?>
			</article>

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
