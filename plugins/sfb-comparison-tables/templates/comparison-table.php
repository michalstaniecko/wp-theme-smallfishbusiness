<?php
/**
 * Comparison table template.
 *
 * Variables available:
 * - $post_id  (int)   - Table post ID
 * - $products (array) - Products/plans data
 * - $sections (array) - Sections with feature rows
 *
 * @package SFB\ComparisonTables
 */

namespace SFB\ComparisonTables;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$product_count    = count( $products );
$highlighted_index = -1;

// Find highlighted product index.
foreach ( $products as $index => $product ) {
	if ( ! empty( $product['isHighlighted'] ) ) {
		$highlighted_index = $index;
		break;
	}
}
?>
<div class="sfb-ct" data-table-id="<?php echo esc_attr( $post_id ); ?>">
	<!-- Header Row with Products -->
	<div class="sfb-ct__header">
		<div class="sfb-ct__header-cell sfb-ct__header-cell--feature">
			<!-- Empty cell for feature column -->
		</div>
		<?php foreach ( $products as $index => $product ) : ?>
			<div class="sfb-ct__header-cell sfb-ct__product<?php echo ! empty( $product['isHighlighted'] ) ? ' sfb-ct__product--highlighted' : ''; ?>">
				<?php if ( ! empty( $product['isHighlighted'] ) ) : ?>
					<span class="sfb-ct__product-badge">
						<?php esc_html_e( 'Most Popular', 'sfb-comparison-tables' ); ?>
					</span>
				<?php endif; ?>

				<?php if ( ! empty( $product['name'] ) ) : ?>
					<h3 class="sfb-ct__product-name"><?php echo esc_html( $product['name'] ); ?></h3>
				<?php endif; ?>

				<?php if ( ! empty( $product['price'] ) ) : ?>
					<div class="sfb-ct__product-price"><?php echo esc_html( $product['price'] ); ?></div>
				<?php endif; ?>

				<?php if ( ! empty( $product['priceSubtext'] ) ) : ?>
					<div class="sfb-ct__product-subtext"><?php echo esc_html( $product['priceSubtext'] ); ?></div>
				<?php endif; ?>

				<?php if ( ! empty( $product['ctaText'] ) && ! empty( $product['ctaUrl'] ) ) : ?>
					<a href="<?php echo esc_url( $product['ctaUrl'] ); ?>"
					   class="sfb-ct__product-cta sfb-ct__product-cta--<?php echo esc_attr( $product['ctaStyle'] ?? 'primary' ); ?>">
						<?php echo esc_html( $product['ctaText'] ); ?>
					</a>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>

	<!-- Sections with Feature Rows -->
	<?php foreach ( $sections as $section_index => $section ) : ?>
		<?php
		$is_open    = ! empty( $section['isDefaultOpen'] );
		$section_id = 'sfb-ct-section-' . $post_id . '-' . $section_index;
		?>
		<div class="sfb-ct__section<?php echo $is_open ? ' is-open' : ''; ?>" data-section-index="<?php echo esc_attr( $section_index ); ?>">
			<button type="button"
					class="sfb-ct__section-header"
					aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>"
					aria-controls="<?php echo esc_attr( $section_id ); ?>">
				<span class="sfb-ct__section-title"><?php echo esc_html( $section['title'] ?? '' ); ?></span>
				<span class="sfb-ct__section-icon" aria-hidden="true">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20">
						<polyline points="6 9 12 15 18 9"></polyline>
					</svg>
				</span>
			</button>

			<div class="sfb-ct__section-content" id="<?php echo esc_attr( $section_id ); ?>">
				<?php if ( ! empty( $section['rows'] ) ) : ?>
					<?php foreach ( $section['rows'] as $row_index => $row ) : ?>
						<div class="sfb-ct__row">
							<div class="sfb-ct__cell sfb-ct__cell--feature">
								<span class="sfb-ct__feature-indicator" aria-hidden="true">&rsaquo;</span>
								<span class="sfb-ct__feature-name"><?php echo esc_html( $row['feature'] ?? '' ); ?></span>
								<?php if ( ! empty( $row['tooltip'] ) ) : ?>
									<span class="sfb-ct__feature-tooltip" title="<?php echo esc_attr( $row['tooltip'] ); ?>">
										<svg viewBox="0 0 24 24" fill="currentColor" width="16" height="16">
											<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H8c0-2.21 1.79-4 4-4s4 1.79 4 4c0 .88-.36 1.68-.93 2.25z"/>
										</svg>
									</span>
								<?php endif; ?>
							</div>

							<?php
							$values = $row['values'] ?? array();
							for ( $i = 0; $i < $product_count; $i++ ) :
								$value          = $values[ $i ] ?? array( 'type' => 'dash' );
								$is_highlighted = ( $i === $highlighted_index );
								?>
								<div class="sfb-ct__cell sfb-ct__cell--value<?php echo $is_highlighted ? ' sfb-ct__cell--highlighted' : ''; ?>">
									<?php echo Frontend::render_cell_value( $value ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</div>
							<?php endfor; ?>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
	<?php endforeach; ?>
</div>
