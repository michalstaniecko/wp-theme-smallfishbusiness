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

		<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
			<!-- Main Content -->
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'lg:col-span-3' ); ?>>
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

					<!-- Table of Contents (above article) -->
					<?php if ( sfb_has_toc() ) : ?>
					<div id="toc-wrapper" class="mb-8">
						<div id="toc-container" class="bg-gray-50 rounded-lg overflow-hidden">
							<button id="toc-toggle" type="button" class="w-full flex items-center justify-between p-4 hover:bg-gray-100 transition-colors cursor-pointer">
								<span class="font-semibold text-gray-900"><?php esc_html_e( 'Table of Contents', 'smallfishbusiness' ); ?></span>
								<svg id="toc-toggle-icon" class="w-5 h-5 text-gray-500 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
								</svg>
							</button>
							<div class="px-4 pb-4 text-sm">
								<?php sfb_the_toc(); ?>
							</div>
						</div>
					</div>
					<?php endif; ?>

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

			<!-- Sidebar -->
			<div class="lg:col-span-1">
				<div class="lg:sticky lg:top-24 space-y-8">
					<?php get_sidebar(); ?>
				</div>
			</div>
		</div>
	</div>

</main>

<?php if ( sfb_has_toc() ) : ?>
<!-- Sticky ToC (appears when scrolled past main ToC) -->
<div id="toc-sticky" class="fixed top-20 left-0 right-0 z-40 hidden">
	<div class="bg-white border-b border-gray-200 shadow-sm">
		<div class="container-fluid">
			<div id="toc-sticky-container" class="py-2">
				<button id="toc-sticky-toggle" type="button" class="w-full flex items-center justify-between py-2 hover:bg-gray-50 transition-colors cursor-pointer rounded">
					<span class="flex items-center gap-2 text-sm font-medium text-gray-700">
						<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
						</svg>
						<?php esc_html_e( 'Table of Contents', 'smallfishbusiness' ); ?>
					</span>
					<svg id="toc-sticky-icon" class="w-4 h-4 text-gray-500 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
					</svg>
				</button>
				<div id="toc-sticky-content" class="hidden text-sm py-2 max-h-60 overflow-y-auto">
					<?php sfb_the_toc(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

<?php
get_footer();
