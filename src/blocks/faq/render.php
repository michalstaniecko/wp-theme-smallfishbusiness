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

// Get inner blocks content
$inner_blocks = $block->parsed_block['innerBlocks'] ?? array();

// Generate unique ID for FAQ container
$faq_id = isset( $attributes['anchor'] ) ? $attributes['anchor'] : 'faq-' . wp_unique_id();

// Prepare wrapper attributes
$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'wp-block-sfb-faq',
		'id'    => $faq_id,
	)
);

// Build JSON-LD Schema for FAQ
$schema_items = array();
foreach ( $inner_blocks as $inner_block ) {
	if ( 'sfb/faq-item' === $inner_block['blockName'] ) {
		$question = $inner_block['attrs']['question'] ?? '';

		// Get answer content from inner blocks
		$answer_blocks = $inner_block['innerBlocks'] ?? array();
		$answer        = '';
		foreach ( $answer_blocks as $answer_block ) {
			$answer .= render_block( $answer_block );
		}

		if ( ! empty( $question ) && ! empty( $answer ) ) {
			$schema_items[] = array(
				'@type'          => 'Question',
				'name'           => wp_strip_all_tags( $question ),
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => wp_strip_all_tags( $answer ),
				),
			);
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
