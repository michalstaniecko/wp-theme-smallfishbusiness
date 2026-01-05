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

					<!-- Featured Image -->
					<div class="aspect-video rounded-xl overflow-hidden mb-8 bg-surface-100">
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
							<div class="w-full h-full flex items-center justify-center">
								<img src="<?php echo esc_url( get_template_directory_uri() . '/img/logo-small-fish-business.png' ); ?>"
								     alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
								     class="max-w-[40%] max-h-[40%] object-contain opacity-30">
							</div>
						<?php endif; ?>
					</div>

					<!-- Category Badge -->
					<?php
					$categories = get_the_category();
					if ( ! empty( $categories ) ) :
						?>
						<a href="<?php echo esc_url( get_category_link( $categories[0]->term_id ) ); ?>"
						   class="inline-block text-xs font-bold text-primary-600 uppercase tracking-wider mb-4 hover:text-primary-700 transition-colors no-underline"
						   style="letter-spacing: 0.1em;">
							<?php echo esc_html( $categories[0]->name ); ?>
						</a>
					<?php endif; ?>

					<!-- Title -->
					<h1 class="font-display text-3xl md:text-4xl lg:text-5xl font-semibold text-surface-900 mb-5 leading-tight">
						<?php the_title(); ?>
					</h1>

					<!-- Meta -->
					<div class="flex flex-wrap items-center text-surface-500 mb-10 gap-3">
						<span class="flex items-center gap-2">
							<?php echo get_avatar( get_the_author_meta( 'ID' ), 32, '', '', [ 'class' => 'rounded-full' ] ); ?>
							<span class="font-medium text-surface-700"><?php the_author(); ?></span>
						</span>
						<span class="w-1 h-1 rounded-full bg-surface-300" aria-hidden="true"></span>
						<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
							<?php echo esc_html( get_the_date() ); ?>
						</time>
						<span class="w-1 h-1 rounded-full bg-surface-300" aria-hidden="true"></span>
						<span><?php sfb_reading_time(); ?></span>
					</div>

					<!-- Table of Contents (above article) -->
					<?php if ( sfb_has_toc() ) : ?>
					<div id="toc-wrapper" class="mb-10">
						<div id="toc-container">
							<button id="toc-toggle" type="button">
								<span><?php esc_html_e( 'Table of Contents', 'smallfishbusiness' ); ?></span>
								<svg id="toc-toggle-icon" class="w-5 h-5 text-surface-500 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
								</svg>
							</button>
							<div class="px-5 pb-5">
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
						<div class="post-tags">
							<?php
							$tags = get_the_tags();
							foreach ( $tags as $tag ) :
								?>
								<a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="post-tag">
									#<?php echo esc_html( $tag->name ); ?>
								</a>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

					<!-- Share Buttons -->
					<?php get_template_part( 'template-parts/components/share-buttons' ); ?>

					<!-- Author Box -->
					<?php get_template_part( 'template-parts/content/author-box' ); ?>

					<!-- Post Navigation -->
					<?php
					$prev_post = get_previous_post();
					$next_post = get_next_post();
					if ( $prev_post || $next_post ) :
					?>
					<nav class="post-navigation" aria-label="<?php esc_attr_e( 'Post navigation', 'smallfishbusiness' ); ?>">
						<?php if ( $prev_post ) : ?>
							<div class="post-navigation__item">
								<span class="post-navigation__label">
									<?php esc_html_e( 'Previous', 'smallfishbusiness' ); ?>
								</span>
								<a href="<?php echo esc_url( get_permalink( $prev_post ) ); ?>" class="post-navigation__link">
									<?php echo esc_html( get_the_title( $prev_post ) ); ?>
								</a>
							</div>
						<?php else : ?>
							<div></div>
						<?php endif; ?>

						<?php if ( $next_post ) : ?>
							<div class="post-navigation__item post-navigation__item--next">
								<span class="post-navigation__label">
									<?php esc_html_e( 'Next', 'smallfishbusiness' ); ?>
								</span>
								<a href="<?php echo esc_url( get_permalink( $next_post ) ); ?>" class="post-navigation__link">
									<?php echo esc_html( get_the_title( $next_post ) ); ?>
								</a>
							</div>
						<?php endif; ?>
					</nav>
					<?php endif; ?>

					<!-- Comments -->
					<?php if ( comments_open() || get_comments_number() ) : ?>
						<div class="mt-14 pt-10 border-t border-surface-200">
							<?php comments_template(); ?>
						</div>
					<?php endif; ?>

				<?php endwhile; ?>
			</article>

			<!-- Sidebar -->
			<aside class="lg:col-span-4">
				<div class="lg:sticky lg:top-28 space-y-8">
					<?php get_sidebar(); ?>
				</div>
			</aside>
		</div>
	</div>

</main>

<?php if ( sfb_has_toc() ) : ?>
<!-- Sticky ToC (appears when scrolled past main ToC) -->
<div id="toc-sticky" class="hidden">
	<div class="bg-white/95 backdrop-blur-md border-b border-surface-200 shadow-sm">
		<div class="container-fluid">
			<div id="toc-sticky-container" class="py-2">
				<button id="toc-sticky-toggle" type="button">
					<span class="flex items-center gap-2 text-sm font-medium text-surface-700">
						<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
						</svg>
						<?php esc_html_e( 'Table of Contents', 'smallfishbusiness' ); ?>
					</span>
					<svg id="toc-sticky-icon" class="w-4 h-4 text-surface-500 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
					</svg>
				</button>
				<div id="toc-sticky-content" class="hidden py-3">
					<?php sfb_the_toc(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

<?php
get_footer();
