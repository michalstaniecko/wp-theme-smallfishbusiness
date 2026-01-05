<?php
/**
 * Server-side rendering of the accordion item block.
 *
 * The following variables are exposed to the file:
 *     $attributes (array): The block attributes.
 *     $content (string): The block default content.
 *     $block (WP_Block): The block instance.
 *
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

// Get item title
$title = ! empty( $attributes['title'] ) ? $attributes['title'] : '';

// Get item index from parent context
static $item_counter = 0;
$item_counter++;
$item_number = $item_counter;

// Arrow down SVG icon
$arrow_icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6 1.41-1.41z"/></svg>';

?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'sfb-accordion-item' ) ); ?>>
	<div class="sfb-accordion-item__header">
		<div class="sfb-accordion-item__title">
			<span class="sfb-accordion-item__number"><?php echo esc_html( $item_number ); ?></span>
			<span><?php echo esc_html( $title ); ?></span>
		</div>
		<div class="sfb-accordion-item__icon">
			<?php echo $arrow_icon; ?>
		</div>
	</div>
	<div class="sfb-accordion-item__content">
		<div>
			<div class="sfb-accordion-item__inner">
				<?php echo $content; ?>
			</div>
		</div>
	</div>
</div>
