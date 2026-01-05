<?php
/**
 * Template Name: Landing Page (Box Layout)
 * Template Post Type: page
 *
 * A clean landing page layout with white content box on gray background.
 * No sidebar, centered narrow content.
 *
 * @package SmallFishBusiness
 */

get_header();
?>

<main id="primary" class="site-main landing-page-main">
	<div class="landing-page-container py-12 md:py-16 lg:py-20">
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'landing-page-box' ); ?>>
			<?php
			while ( have_posts() ) :
				the_post();
				?>

				<h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-8 text-center">
					<?php the_title(); ?>
				</h1>

				<div class="prose prose-lg max-w-none">
					<?php the_content(); ?>
				</div>

			<?php endwhile; ?>
		</article>
	</div>
</main>

<?php
get_footer();
