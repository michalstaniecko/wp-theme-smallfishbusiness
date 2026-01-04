<?php
/**
 * Article Card Component
 * Modern minimalist vertical card for post listings
 *
 * @package SmallFishBusiness
 */
?>
<article <?php post_class( 'group' ); ?>>
	<!-- Featured Image -->
	<div class="mb-4 rounded-lg overflow-hidden">
		<a href="<?php the_permalink(); ?>" class="block aspect-video relative bg-gray-100 no-underline">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'large', [
					'class' => 'w-full h-full object-cover transition-transform duration-500 group-hover:scale-105',
					'alt'   => get_the_title(),
				] ); ?>
			<?php else : ?>
				<!-- Placeholder Logo -->
				<div class="w-full h-full flex items-center justify-center bg-gray-100">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/img/logo-small-fish-business.png' ); ?>"
					     alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
					     class="max-w-[60%] max-h-[60%] object-contain opacity-50 transition-opacity duration-500 group-hover:opacity-70">
				</div>
			<?php endif; ?>
		</a>
	</div>

	<!-- Category -->
	<?php
	$categories = get_the_category();
	if ( ! empty( $categories ) ) :
	?>
		<a href="<?php echo esc_url( get_category_link( $categories[0]->term_id ) ); ?>"
		   class="inline-block text-xs font-medium text-gray-500 uppercase tracking-wider mb-3 hover:text-gray-700 transition-colors no-underline">
			<?php echo esc_html( $categories[0]->name ); ?>
		</a>
	<?php endif; ?>

	<!-- Title -->
	<h2 class="text-xl font-bold mb-3 leading-snug">
		<a href="<?php the_permalink(); ?>" class="text-primary-600 hover:text-primary-700 transition-colors no-underline">
			<?php the_title(); ?>
		</a>
	</h2>

	<!-- Meta -->
	<div class="flex items-center text-sm text-gray-500 mb-3">
		<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
			<?php echo esc_html( get_the_date() ); ?>
		</time>
		<span class="mx-2 text-gray-300" aria-hidden="true">&bull;</span>
		<span><?php echo esc_html( get_the_author() ); ?></span>
	</div>

	<!-- Excerpt -->
	<p class="text-gray-600 mb-4 line-clamp-3 leading-relaxed">
		<?php echo esc_html( wp_trim_words( get_the_excerpt(), 20, '...' ) ); ?>
	</p>

	<!-- Read More -->
	<a href="<?php the_permalink(); ?>"
	   class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors group/link no-underline">
		<?php esc_html_e( 'Read more', 'smallfishbusiness' ); ?>
		<svg class="w-4 h-4 ml-1 transition-transform group-hover/link:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
			<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
		</svg>
	</a>
</article>
