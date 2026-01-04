<?php
/**
 * Single Post Template
 *
 * The template for displaying single posts.
 *
 * @package SmallFishBusiness
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="container-fluid py-8">
		<!-- Breadcrumbs -->
		<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

		<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
			<!-- Main Content -->
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'lg:col-span-2' ); ?>>
				<?php
				while ( have_posts() ) :
					the_post();
					?>

					<!-- Featured Image -->
					<div class="aspect-video rounded-lg overflow-hidden mb-6">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php
							the_post_thumbnail(
								'large',
								[
									'class' => 'w-full h-full object-cover',
									'alt'   => get_the_title(),
								]
							);
							?>
						<?php else : ?>
							<!-- Placeholder Logo -->
							<div class="w-full h-full flex items-center justify-center bg-gray-100">
								<img src="<?php echo esc_url( get_template_directory_uri() . '/img/logo-small-fish-business.png' ); ?>"
								     alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
								     class="max-w-[50%] max-h-[50%] object-contain opacity-40">
							</div>
						<?php endif; ?>
					</div>

					<!-- Category Badge -->
					<?php
					$categories = get_the_category();
					if ( ! empty( $categories ) ) :
						?>
						<a href="<?php echo esc_url( get_category_link( $categories[0]->term_id ) ); ?>"
						   class="inline-block text-sm font-semibold text-primary-600 uppercase tracking-wide mb-3 hover:text-primary-700 transition-colors">
							<?php echo esc_html( $categories[0]->name ); ?>
						</a>
					<?php endif; ?>

					<!-- Title -->
					<h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
						<?php the_title(); ?>
					</h1>

					<!-- Meta -->
					<div class="flex flex-wrap items-center text-gray-500 mb-8 gap-x-2">
						<span>
							<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
								<?php echo esc_html( get_the_date() ); ?>
							</time>
						</span>
						<span class="text-gray-300">&bull;</span>
						<span><?php the_author(); ?></span>
						<span class="text-gray-300">&bull;</span>
						<span><?php sfb_reading_time(); ?></span>
					</div>

					<!-- Content -->
					<div class="prose prose-lg max-w-none">
						<?php the_content(); ?>
					</div>

					<!-- Tags -->
					<?php if ( has_tag() ) : ?>
						<div class="mt-8 pt-6 border-t border-gray-200">
							<div class="flex flex-wrap gap-2">
								<?php
								$tags = get_the_tags();
								foreach ( $tags as $tag ) :
									?>
									<a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>"
									   class="inline-block text-sm text-gray-600 bg-gray-100 px-3 py-1 rounded-full hover:bg-gray-200 transition-colors">
										#<?php echo esc_html( $tag->name ); ?>
									</a>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif; ?>

					<!-- Share Buttons -->
					<?php get_template_part( 'template-parts/components/share-buttons' ); ?>

					<!-- Author Box -->
					<?php get_template_part( 'template-parts/content/author-box' ); ?>

					<!-- Post Navigation -->
					<nav class="mt-8 pt-6 border-t border-gray-200">
						<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
							<?php
							$prev_post = get_previous_post();
							$next_post = get_next_post();
							?>

							<div>
								<?php if ( $prev_post ) : ?>
									<span class="text-sm text-gray-500 block mb-1">
										<?php esc_html_e( 'Previous', 'smallfishbusiness' ); ?>
									</span>
									<a href="<?php echo esc_url( get_permalink( $prev_post ) ); ?>"
									   class="font-medium text-gray-900 hover:text-primary-600 transition-colors line-clamp-2">
										<?php echo esc_html( get_the_title( $prev_post ) ); ?>
									</a>
								<?php endif; ?>
							</div>

							<div class="sm:text-right">
								<?php if ( $next_post ) : ?>
									<span class="text-sm text-gray-500 block mb-1">
										<?php esc_html_e( 'Next', 'smallfishbusiness' ); ?>
									</span>
									<a href="<?php echo esc_url( get_permalink( $next_post ) ); ?>"
									   class="font-medium text-gray-900 hover:text-primary-600 transition-colors line-clamp-2">
										<?php echo esc_html( get_the_title( $next_post ) ); ?>
									</a>
								<?php endif; ?>
							</div>
						</div>
					</nav>

					<!-- Comments -->
					<?php if ( comments_open() || get_comments_number() ) : ?>
						<div class="mt-12 pt-8 border-t border-gray-200">
							<?php comments_template(); ?>
						</div>
					<?php endif; ?>

				<?php endwhile; ?>
			</article>

			<!-- Sidebar with TOC -->
			<div class="lg:col-span-1">
				<div class="lg:sticky lg:top-24 space-y-8">
					<!-- Table of Contents (will be populated by JS in Phase 6) -->
					<div id="toc-container" class="hidden bg-gray-50 rounded-lg p-6">
						<h3 class="font-semibold text-gray-900 mb-4">
							<?php esc_html_e( 'Table of Contents', 'smallfishbusiness' ); ?>
						</h3>
						<nav id="toc" class="text-sm"></nav>
					</div>

					<?php get_sidebar(); ?>
				</div>
			</div>
		</div>
	</div>
</main>

<?php
get_footer();
