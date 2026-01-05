<?php
/**
 * Server-side rendering for FAQ Item block
 *
 * Available variables:
 * @var array    $attributes Block attributes
 * @var string   $content    Block default content
 * @var WP_Block $block      Block instance
 *
 * @package SmallFishBusiness
 */

// Get question from attributes
$question = isset( $attributes['question'] ) ? $attributes['question'] : '';

// Get block index within parent
$parent_block = isset( $block->context['sfb/faqId'] ) ? $block->context['sfb/faqId'] : 'default';

// Get block index by counting siblings
static $item_counter = array();
if ( ! isset( $item_counter[ $parent_block ] ) ) {
	$item_counter[ $parent_block ] = 0;
}
$item_index = ++$item_counter[ $parent_block ];

// Prepare wrapper attributes
$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'sfb-faq-item',
	)
);
?>

<div <?php echo $wrapper_attributes; ?>>
	<div class="sfb-faq-item__header">
		<div class="sfb-faq-item__number">
			<?php echo esc_html( $item_index . '.' ); ?>
		</div>
		<h3 class="sfb-faq-item__question">
			<?php echo esc_html( $question ); ?>
		</h3>
		<svg class="sfb-faq-item__chevron" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
			<path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
		</svg>
	</div>

	<div class="sfb-faq-item__content">
		<div class="sfb-faq-item__answer">
			<?php echo $content; ?>
		</div>
	</div>
</div>
