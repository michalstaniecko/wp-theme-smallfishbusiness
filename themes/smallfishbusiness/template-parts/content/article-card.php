<?php
/**
 * Article Card Component
 * Editorial minimalist vertical card for post listings
 *
 * @package SmallFishBusiness
 */
?>
<article <?php post_class( 'article-card' ); ?>>
	<!-- Featured Image -->
	<div class="article-card__image-wrapper">
		<a href="<?php the_permalink(); ?>" class="block w-full h-full no-underline" tabindex="-1" aria-hidden="true">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'large', [
					'class' => 'article-card__image',
					'alt'   => get_the_title(),
				] ); ?>
			<?php else : ?>
				<!-- Placeholder Logo -->
				<div class="w-full h-full flex items-center justify-center bg-surface-100">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/img/logo-small-fish-business.png' ); ?>"
					     alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
					     class="max-w-[50%] max-h-[50%] object-contain opacity-30 transition-opacity duration-500 hover:opacity-50">
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
		   class="article-card__category">
			<?php echo esc_html( $categories[0]->name ); ?>
		</a>
	<?php endif; ?>

	<!-- Title -->
	<h2 class="article-card__title">
		<a href="<?php the_permalink(); ?>">
			<?php the_title(); ?>
		</a>
	</h2>

	<!-- Meta -->
	<div class="article-card__meta">
		<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
			<?php echo esc_html( get_the_date() ); ?>
		</time>
		<span class="article-card__meta-divider" aria-hidden="true"></span>
		<span><?php echo esc_html( get_the_author() ); ?></span>
	</div>

	<!-- Excerpt -->
	<p class="article-card__excerpt">
		<?php echo esc_html( wp_trim_words( get_the_excerpt(), 18, '...' ) ); ?>
	</p>

	<!-- Read More -->
	<a href="<?php the_permalink(); ?>" class="article-card__read-more">
		<?php esc_html_e( 'Read more', 'smallfishbusiness' ); ?>
		<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
			<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
		</svg>
	</a>
</article>
