<?php
/**
 * Server-side rendering of the accordion block.
 *
 * The following variables are exposed to the file:
 *     $attributes (array): The block attributes.
 *     $content (string): The block default content.
 *     $block (WP_Block): The block instance.
 *
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

// Generate unique ID for the accordion
$accordion_id = isset( $attributes['anchor'] ) ? $attributes['anchor'] : 'accordion-' . wp_unique_id();

// Extract FAQ items for JSON-LD schema
$faq_items = array();
if ( ! empty( $block->inner_blocks ) ) {
	foreach ( $block->inner_blocks as $index => $inner_block ) {
		if ( 'sfb/accordion-item' === $inner_block->name ) {
			$item_title = ! empty( $inner_block->attributes['title'] ) ? $inner_block->attributes['title'] : '';

			// Get content from inner block's inner blocks (the answer content)
			$plain_content = '';
			if ( ! empty( $inner_block->inner_blocks ) ) {
				foreach ( $inner_block->inner_blocks as $content_block ) {
					$plain_content .= wp_strip_all_tags( $content_block->render() );
				}
			}

			if ( ! empty( $item_title ) && ! empty( $plain_content ) ) {
				$faq_items[] = array(
					'@type'          => 'Question',
					'name'           => $item_title,
					'acceptedAnswer' => array(
						'@type' => 'Answer',
						'text'  => trim( $plain_content ),
					),
				);
			}
		}
	}
}

// Build JSON-LD schema
$schema = array(
	'@context'   => 'https://schema.org',
	'@type'      => 'FAQPage',
	'mainEntity' => $faq_items,
);

?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'sfb-accordion' ) ); ?> id="<?php echo esc_attr( $accordion_id ); ?>">
	<?php echo $content; ?>
</div>

<?php if ( ! empty( $faq_items ) ) : ?>
<script type="application/ld+json">
<?php echo wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ); ?>
</script>
<?php endif; ?>
