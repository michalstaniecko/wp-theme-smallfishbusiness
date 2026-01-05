<?php
/**
 * Server-side rendering for the Comparison Table Embed block.
 *
 * @package SFB\ComparisonTables
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block content.
 * @var WP_Block $block      Block instance.
 */

namespace SFB\ComparisonTables;

$table_id = absint( $attributes['tableId'] ?? 0 );

if ( ! $table_id ) {
	return;
}

$html = Frontend::render_table( $table_id );

if ( empty( $html ) ) {
	return;
}

$wrapper_attributes = get_block_wrapper_attributes( array(
	'class' => 'sfb-ct-embed-wrapper',
) );

printf(
	'<div %s>%s</div>',
	$wrapper_attributes, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	$html // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
);
