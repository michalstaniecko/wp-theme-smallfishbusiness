<?php
/**
 * Server-side rendering for FAQ block
 *
 * Available variables:
 * @var array    $attributes Block attributes
 * @var string   $content    Block default content
 * @var WP_Block $block      Block instance
 *
 * @package SmallFishBusiness
 */

// Prepare attributes
$title      = isset( $attributes['title'] ) ? $attributes['title'] : __( 'Frequently Asked Questions', 'smallfishbusiness' );
$show_title = isset( $attributes['showTitle'] ) ? $attributes['showTitle'] : true;

// Generate unique ID for FAQ container
$faq_id = isset( $attributes['anchor'] ) ? $attributes['anchor'] : 'faq-' . wp_unique_id();

// Prepare wrapper attributes
$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'wp-block-sfb-faq',
		'id'    => $faq_id,
	)
);

// Build JSON-LD Schema for FAQ using WP_Block objects
$schema_items = array();
if ( ! empty( $block->inner_blocks ) ) {
	foreach ( $block->inner_blocks as $inner_block ) {
		if ( 'sfb/faq-item' === $inner_block->name ) {
			$question = $inner_block->attributes['question'] ?? '';

			// Get answer content from inner block's inner blocks
			$answer = '';
			if ( ! empty( $inner_block->inner_blocks ) ) {
				foreach ( $inner_block->inner_blocks as $content_block ) {
					$answer .= wp_strip_all_tags( $content_block->render() );
				}
			}

			if ( ! empty( $question ) && ! empty( $answer ) ) {
				$schema_items[] = array(
					'@type'          => 'Question',
					'name'           => wp_strip_all_tags( $question ),
					'acceptedAnswer' => array(
						'@type' => 'Answer',
						'text'  => trim( $answer ),
					),
				);
			}
		}
	}
}

$faq_schema = array(
	'@context'   => 'https://schema.org',
	'@type'      => 'FAQPage',
	'mainEntity' => $schema_items,
);
?>

<div <?php echo $wrapper_attributes; ?>>
	<?php if ( $show_title && ! empty( $title ) ) : ?>
		<div class="sfb-faq__header">
			<h2 class="sfb-faq__title">
				<?php echo esc_html( $title ); ?>
			</h2>
		</div>
	<?php endif; ?>

	<div class="sfb-faq__items">
		<?php echo $content; ?>
	</div>

	<?php if ( ! empty( $schema_items ) ) : ?>
		<script type="application/ld+json">
			<?php echo wp_json_encode( $faq_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ); ?>
		</script>
	<?php endif; ?>
</div>
