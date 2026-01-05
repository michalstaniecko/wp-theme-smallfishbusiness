<?php
/**
 * Meta Boxes for comparison table editing.
 *
 * @package SFB\ComparisonTables
 */

namespace SFB\ComparisonTables;

/**
 * Class Meta_Boxes
 *
 * Handles meta boxes with repeater fields for products and sections.
 */
class Meta_Boxes {

	/**
	 * Meta key for products.
	 */
	public const META_PRODUCTS = '_sfb_ct_products';

	/**
	 * Meta key for sections.
	 */
	public const META_SECTIONS = '_sfb_ct_sections';

	/**
	 * Nonce action.
	 */
	private const NONCE_ACTION = 'sfb_ct_save_meta';

	/**
	 * Nonce field name.
	 */
	private const NONCE_NAME = 'sfb_ct_meta_nonce';

	/**
	 * Initialize meta boxes.
	 *
	 * @return void
	 */
	public static function init(): void {
		add_action( 'add_meta_boxes', array( self::class, 'add_meta_boxes' ) );
		add_action( 'save_post_' . CPT::POST_TYPE, array( self::class, 'save_meta' ), 10, 2 );
		add_action( 'admin_enqueue_scripts', array( self::class, 'enqueue_assets' ) );
	}

	/**
	 * Register meta boxes.
	 *
	 * @return void
	 */
	public static function add_meta_boxes(): void {
		add_meta_box(
			'sfb_ct_products',
			__( 'Products / Plans', 'sfb-comparison-tables' ),
			array( self::class, 'render_products_meta_box' ),
			CPT::POST_TYPE,
			'normal',
			'high'
		);

		add_meta_box(
			'sfb_ct_sections',
			__( 'Sections & Features', 'sfb-comparison-tables' ),
			array( self::class, 'render_sections_meta_box' ),
			CPT::POST_TYPE,
			'normal',
			'high'
		);
	}

	/**
	 * Enqueue admin assets.
	 *
	 * @param string $hook Current admin page.
	 * @return void
	 */
	public static function enqueue_assets( string $hook ): void {
		global $post_type;

		if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) || CPT::POST_TYPE !== $post_type ) {
			return;
		}

		wp_enqueue_style(
			'sfb-ct-admin',
			SFB_CT_ASSETS_URL . 'css/admin.css',
			array(),
			SFB_CT_VERSION
		);

		wp_enqueue_script(
			'sfb-ct-admin-repeater',
			SFB_CT_ASSETS_URL . 'js/admin-repeater.js',
			array(),
			SFB_CT_VERSION,
			true
		);

		wp_localize_script(
			'sfb-ct-admin-repeater',
			'sfbCtAdmin',
			array(
				'i18n' => array(
					'addProduct'       => __( 'Add Product', 'sfb-comparison-tables' ),
					'removeProduct'    => __( 'Remove', 'sfb-comparison-tables' ),
					'addSection'       => __( 'Add Section', 'sfb-comparison-tables' ),
					'removeSection'    => __( 'Remove Section', 'sfb-comparison-tables' ),
					'addRow'           => __( 'Add Feature Row', 'sfb-comparison-tables' ),
					'removeRow'        => __( 'Remove Row', 'sfb-comparison-tables' ),
					'confirmDelete'    => __( 'Are you sure you want to delete this item?', 'sfb-comparison-tables' ),
					'valueTypes'       => array(
						'text'  => __( 'Text', 'sfb-comparison-tables' ),
						'check' => __( 'Check', 'sfb-comparison-tables' ),
						'dash'  => __( 'Dash (N/A)', 'sfb-comparison-tables' ),
						'link'  => __( 'Link', 'sfb-comparison-tables' ),
					),
					'ctaStyles'        => array(
						'primary' => __( 'Primary', 'sfb-comparison-tables' ),
						'outline' => __( 'Outline', 'sfb-comparison-tables' ),
					),
				),
			)
		);
	}

	/**
	 * Render products meta box.
	 *
	 * @param \WP_Post $post Current post object.
	 * @return void
	 */
	public static function render_products_meta_box( \WP_Post $post ): void {
		wp_nonce_field( self::NONCE_ACTION, self::NONCE_NAME );

		$products = get_post_meta( $post->ID, self::META_PRODUCTS, true );
		$products = is_array( $products ) ? $products : array();

		// Ensure at least one empty product for template.
		if ( empty( $products ) ) {
			$products = array( self::get_default_product() );
		}
		?>
		<div class="sfb-ct-repeater" id="sfb-ct-products-repeater" data-type="products">
			<div class="sfb-ct-repeater__items" id="sfb-ct-products-items">
				<?php foreach ( $products as $index => $product ) : ?>
					<?php self::render_product_item( $index, $product ); ?>
				<?php endforeach; ?>
			</div>

			<button type="button" class="button sfb-ct-repeater__add" data-target="sfb-ct-products-items">
				<span class="dashicons dashicons-plus-alt2"></span>
				<?php esc_html_e( 'Add Product', 'sfb-comparison-tables' ); ?>
			</button>
		</div>

		<!-- Product template for JS -->
		<template id="sfb-ct-product-template">
			<?php self::render_product_item( '__INDEX__', self::get_default_product() ); ?>
		</template>
		<?php
	}

	/**
	 * Render a single product item.
	 *
	 * @param int|string $index   Item index.
	 * @param array      $product Product data.
	 * @return void
	 */
	private static function render_product_item( $index, array $product ): void {
		$name_prefix = "sfb_ct_products[{$index}]";
		?>
		<div class="sfb-ct-repeater__item sfb-ct-product" data-index="<?php echo esc_attr( $index ); ?>">
			<div class="sfb-ct-repeater__item-header">
				<span class="sfb-ct-repeater__item-handle dashicons dashicons-move"></span>
				<span class="sfb-ct-repeater__item-title">
					<?php echo esc_html( $product['name'] ?: __( 'New Product', 'sfb-comparison-tables' ) ); ?>
				</span>
				<button type="button" class="sfb-ct-repeater__item-toggle" aria-expanded="true">
					<span class="dashicons dashicons-arrow-up-alt2"></span>
				</button>
				<button type="button" class="sfb-ct-repeater__item-remove" title="<?php esc_attr_e( 'Remove', 'sfb-comparison-tables' ); ?>">
					<span class="dashicons dashicons-trash"></span>
				</button>
			</div>

			<div class="sfb-ct-repeater__item-content">
				<div class="sfb-ct-field-row">
					<div class="sfb-ct-field">
						<label><?php esc_html_e( 'Product Name', 'sfb-comparison-tables' ); ?></label>
						<input type="text"
							   name="<?php echo esc_attr( $name_prefix ); ?>[name]"
							   value="<?php echo esc_attr( $product['name'] ); ?>"
							   class="sfb-ct-product-name regular-text"
							   placeholder="<?php esc_attr_e( 'e.g. Pro Plan', 'sfb-comparison-tables' ); ?>">
					</div>
					<div class="sfb-ct-field">
						<label><?php esc_html_e( 'Price', 'sfb-comparison-tables' ); ?></label>
						<input type="text"
							   name="<?php echo esc_attr( $name_prefix ); ?>[price]"
							   value="<?php echo esc_attr( $product['price'] ); ?>"
							   class="regular-text"
							   placeholder="<?php esc_attr_e( 'e.g. $33/mo', 'sfb-comparison-tables' ); ?>">
					</div>
				</div>

				<div class="sfb-ct-field-row">
					<div class="sfb-ct-field">
						<label><?php esc_html_e( 'Price Subtext', 'sfb-comparison-tables' ); ?></label>
						<input type="text"
							   name="<?php echo esc_attr( $name_prefix ); ?>[priceSubtext]"
							   value="<?php echo esc_attr( $product['priceSubtext'] ); ?>"
							   class="regular-text"
							   placeholder="<?php esc_attr_e( 'e.g. $390 billed yearly', 'sfb-comparison-tables' ); ?>">
					</div>
				</div>

				<div class="sfb-ct-field-row">
					<div class="sfb-ct-field">
						<label><?php esc_html_e( 'CTA Button Text', 'sfb-comparison-tables' ); ?></label>
						<input type="text"
							   name="<?php echo esc_attr( $name_prefix ); ?>[ctaText]"
							   value="<?php echo esc_attr( $product['ctaText'] ); ?>"
							   class="regular-text"
							   placeholder="<?php esc_attr_e( 'e.g. Get Started', 'sfb-comparison-tables' ); ?>">
					</div>
					<div class="sfb-ct-field">
						<label><?php esc_html_e( 'CTA URL', 'sfb-comparison-tables' ); ?></label>
						<input type="url"
							   name="<?php echo esc_attr( $name_prefix ); ?>[ctaUrl]"
							   value="<?php echo esc_attr( $product['ctaUrl'] ); ?>"
							   class="regular-text"
							   placeholder="<?php esc_attr_e( 'https://...', 'sfb-comparison-tables' ); ?>">
					</div>
				</div>

				<div class="sfb-ct-field-row">
					<div class="sfb-ct-field">
						<label><?php esc_html_e( 'CTA Style', 'sfb-comparison-tables' ); ?></label>
						<select name="<?php echo esc_attr( $name_prefix ); ?>[ctaStyle]">
							<option value="primary" <?php selected( $product['ctaStyle'], 'primary' ); ?>>
								<?php esc_html_e( 'Primary', 'sfb-comparison-tables' ); ?>
							</option>
							<option value="outline" <?php selected( $product['ctaStyle'], 'outline' ); ?>>
								<?php esc_html_e( 'Outline', 'sfb-comparison-tables' ); ?>
							</option>
						</select>
					</div>
					<div class="sfb-ct-field">
						<label class="sfb-ct-checkbox-label">
							<input type="checkbox"
								   name="<?php echo esc_attr( $name_prefix ); ?>[isHighlighted]"
								   value="1"
								   <?php checked( $product['isHighlighted'], true ); ?>>
							<?php esc_html_e( 'Highlight this product', 'sfb-comparison-tables' ); ?>
						</label>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render sections meta box.
	 *
	 * @param \WP_Post $post Current post object.
	 * @return void
	 */
	public static function render_sections_meta_box( \WP_Post $post ): void {
		$sections     = get_post_meta( $post->ID, self::META_SECTIONS, true );
		$sections     = is_array( $sections ) ? $sections : array();
		$products     = get_post_meta( $post->ID, self::META_PRODUCTS, true );
		$products     = is_array( $products ) ? $products : array();
		$product_count = count( $products );

		// Ensure at least one empty section for template.
		if ( empty( $sections ) ) {
			$sections = array( self::get_default_section( $product_count ) );
		}
		?>
		<p class="sfb-ct-notice">
			<span class="dashicons dashicons-info"></span>
			<?php esc_html_e( 'Each feature row will have a value cell for each product defined above.', 'sfb-comparison-tables' ); ?>
		</p>

		<div class="sfb-ct-repeater" id="sfb-ct-sections-repeater" data-type="sections" data-product-count="<?php echo esc_attr( $product_count ); ?>">
			<div class="sfb-ct-repeater__items" id="sfb-ct-sections-items">
				<?php foreach ( $sections as $section_index => $section ) : ?>
					<?php self::render_section_item( $section_index, $section, $product_count ); ?>
				<?php endforeach; ?>
			</div>

			<button type="button" class="button sfb-ct-repeater__add" data-target="sfb-ct-sections-items">
				<span class="dashicons dashicons-plus-alt2"></span>
				<?php esc_html_e( 'Add Section', 'sfb-comparison-tables' ); ?>
			</button>
		</div>

		<!-- Section template for JS -->
		<template id="sfb-ct-section-template">
			<?php self::render_section_item( '__SECTION_INDEX__', self::get_default_section( $product_count ), $product_count ); ?>
		</template>

		<!-- Row template for JS -->
		<template id="sfb-ct-row-template">
			<?php self::render_row_item( '__SECTION_INDEX__', '__ROW_INDEX__', self::get_default_row( $product_count ), $product_count ); ?>
		</template>
		<?php
	}

	/**
	 * Render a single section item.
	 *
	 * @param int|string $index         Section index.
	 * @param array      $section       Section data.
	 * @param int        $product_count Number of products.
	 * @return void
	 */
	private static function render_section_item( $index, array $section, int $product_count ): void {
		$name_prefix = "sfb_ct_sections[{$index}]";
		$rows        = $section['rows'] ?? array();

		if ( empty( $rows ) ) {
			$rows = array( self::get_default_row( $product_count ) );
		}
		?>
		<div class="sfb-ct-repeater__item sfb-ct-section" data-index="<?php echo esc_attr( $index ); ?>">
			<div class="sfb-ct-repeater__item-header sfb-ct-section-header">
				<span class="sfb-ct-repeater__item-handle dashicons dashicons-move"></span>
				<span class="sfb-ct-repeater__item-title">
					<?php echo esc_html( $section['title'] ?: __( 'New Section', 'sfb-comparison-tables' ) ); ?>
				</span>
				<button type="button" class="sfb-ct-repeater__item-toggle" aria-expanded="true">
					<span class="dashicons dashicons-arrow-up-alt2"></span>
				</button>
				<button type="button" class="sfb-ct-repeater__item-remove" title="<?php esc_attr_e( 'Remove Section', 'sfb-comparison-tables' ); ?>">
					<span class="dashicons dashicons-trash"></span>
				</button>
			</div>

			<div class="sfb-ct-repeater__item-content">
				<div class="sfb-ct-field-row">
					<div class="sfb-ct-field sfb-ct-field--wide">
						<label><?php esc_html_e( 'Section Title', 'sfb-comparison-tables' ); ?></label>
						<input type="text"
							   name="<?php echo esc_attr( $name_prefix ); ?>[title]"
							   value="<?php echo esc_attr( $section['title'] ); ?>"
							   class="sfb-ct-section-title regular-text"
							   placeholder="<?php esc_attr_e( 'e.g. Grow your audience', 'sfb-comparison-tables' ); ?>">
					</div>
					<div class="sfb-ct-field">
						<label class="sfb-ct-checkbox-label">
							<input type="checkbox"
								   name="<?php echo esc_attr( $name_prefix ); ?>[isDefaultOpen]"
								   value="1"
								   <?php checked( $section['isDefaultOpen'] ?? false, true ); ?>>
							<?php esc_html_e( 'Open by default', 'sfb-comparison-tables' ); ?>
						</label>
					</div>
				</div>

				<div class="sfb-ct-section-rows">
					<h4><?php esc_html_e( 'Feature Rows', 'sfb-comparison-tables' ); ?></h4>
					<div class="sfb-ct-rows-container" data-section-index="<?php echo esc_attr( $index ); ?>">
						<?php foreach ( $rows as $row_index => $row ) : ?>
							<?php self::render_row_item( $index, $row_index, $row, $product_count ); ?>
						<?php endforeach; ?>
					</div>
					<button type="button" class="button sfb-ct-add-row" data-section-index="<?php echo esc_attr( $index ); ?>">
						<span class="dashicons dashicons-plus-alt2"></span>
						<?php esc_html_e( 'Add Feature Row', 'sfb-comparison-tables' ); ?>
					</button>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render a single row item.
	 *
	 * @param int|string $section_index Section index.
	 * @param int|string $row_index     Row index.
	 * @param array      $row           Row data.
	 * @param int        $product_count Number of products.
	 * @return void
	 */
	private static function render_row_item( $section_index, $row_index, array $row, int $product_count ): void {
		$name_prefix = "sfb_ct_sections[{$section_index}][rows][{$row_index}]";
		$values      = $row['values'] ?? array();

		// Ensure we have values for all products.
		while ( count( $values ) < $product_count ) {
			$values[] = array( 'type' => 'text', 'value' => '' );
		}
		?>
		<div class="sfb-ct-row" data-row-index="<?php echo esc_attr( $row_index ); ?>">
			<div class="sfb-ct-row__header">
				<span class="sfb-ct-row__handle dashicons dashicons-move"></span>
				<div class="sfb-ct-row__feature">
					<input type="text"
						   name="<?php echo esc_attr( $name_prefix ); ?>[feature]"
						   value="<?php echo esc_attr( $row['feature'] ?? '' ); ?>"
						   class="regular-text"
						   placeholder="<?php esc_attr_e( 'Feature name', 'sfb-comparison-tables' ); ?>">
				</div>
				<button type="button" class="sfb-ct-row__remove" title="<?php esc_attr_e( 'Remove Row', 'sfb-comparison-tables' ); ?>">
					<span class="dashicons dashicons-no-alt"></span>
				</button>
			</div>

			<div class="sfb-ct-row__values">
				<?php for ( $i = 0; $i < $product_count; $i++ ) : ?>
					<?php
					$value = $values[ $i ] ?? array( 'type' => 'text', 'value' => '' );
					$value_name_prefix = "{$name_prefix}[values][{$i}]";
					?>
					<div class="sfb-ct-cell" data-value-index="<?php echo esc_attr( $i ); ?>">
						<select name="<?php echo esc_attr( $value_name_prefix ); ?>[type]" class="sfb-ct-cell__type">
							<option value="text" <?php selected( $value['type'], 'text' ); ?>>
								<?php esc_html_e( 'Text', 'sfb-comparison-tables' ); ?>
							</option>
							<option value="check" <?php selected( $value['type'], 'check' ); ?>>
								<?php esc_html_e( 'Check', 'sfb-comparison-tables' ); ?>
							</option>
							<option value="dash" <?php selected( $value['type'], 'dash' ); ?>>
								<?php esc_html_e( 'Dash', 'sfb-comparison-tables' ); ?>
							</option>
							<option value="link" <?php selected( $value['type'], 'link' ); ?>>
								<?php esc_html_e( 'Link', 'sfb-comparison-tables' ); ?>
							</option>
						</select>
						<input type="text"
							   name="<?php echo esc_attr( $value_name_prefix ); ?>[value]"
							   value="<?php echo esc_attr( $value['value'] ?? '' ); ?>"
							   class="sfb-ct-cell__value"
							   placeholder="<?php esc_attr_e( 'Value', 'sfb-comparison-tables' ); ?>">
						<input type="url"
							   name="<?php echo esc_attr( $value_name_prefix ); ?>[url]"
							   value="<?php echo esc_attr( $value['url'] ?? '' ); ?>"
							   class="sfb-ct-cell__url"
							   placeholder="<?php esc_attr_e( 'URL', 'sfb-comparison-tables' ); ?>"
							   style="<?php echo 'link' !== $value['type'] ? 'display:none;' : ''; ?>">
					</div>
				<?php endfor; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Get default product structure.
	 *
	 * @return array Default product data.
	 */
	private static function get_default_product(): array {
		return array(
			'name'          => '',
			'price'         => '',
			'priceSubtext'  => '',
			'ctaText'       => '',
			'ctaUrl'        => '',
			'ctaStyle'      => 'primary',
			'isHighlighted' => false,
		);
	}

	/**
	 * Get default section structure.
	 *
	 * @param int $product_count Number of products.
	 * @return array Default section data.
	 */
	private static function get_default_section( int $product_count ): array {
		return array(
			'title'         => '',
			'isDefaultOpen' => false,
			'rows'          => array( self::get_default_row( $product_count ) ),
		);
	}

	/**
	 * Get default row structure.
	 *
	 * @param int $product_count Number of products.
	 * @return array Default row data.
	 */
	private static function get_default_row( int $product_count ): array {
		$values = array();
		for ( $i = 0; $i < max( 1, $product_count ); $i++ ) {
			$values[] = array( 'type' => 'text', 'value' => '' );
		}

		return array(
			'feature' => '',
			'tooltip' => '',
			'values'  => $values,
		);
	}

	/**
	 * Save meta data.
	 *
	 * @param int      $post_id Post ID.
	 * @param \WP_Post $post    Post object.
	 * @return void
	 */
	public static function save_meta( int $post_id, \WP_Post $post ): void {
		// Verify nonce.
		if ( ! isset( $_POST[ self::NONCE_NAME ] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ self::NONCE_NAME ] ) ), self::NONCE_ACTION ) ) {
			return;
		}

		// Check autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Sanitize and save products.
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$products = isset( $_POST['sfb_ct_products'] ) ? self::sanitize_products( wp_unslash( $_POST['sfb_ct_products'] ) ) : array();
		update_post_meta( $post_id, self::META_PRODUCTS, $products );

		// Sanitize and save sections.
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$sections = isset( $_POST['sfb_ct_sections'] ) ? self::sanitize_sections( wp_unslash( $_POST['sfb_ct_sections'] ) ) : array();
		update_post_meta( $post_id, self::META_SECTIONS, $sections );
	}

	/**
	 * Sanitize products array.
	 *
	 * @param array $products Raw products data.
	 * @return array Sanitized products.
	 */
	private static function sanitize_products( array $products ): array {
		$sanitized = array();

		foreach ( $products as $product ) {
			if ( ! is_array( $product ) ) {
				continue;
			}

			$sanitized[] = array(
				'name'          => sanitize_text_field( $product['name'] ?? '' ),
				'price'         => sanitize_text_field( $product['price'] ?? '' ),
				'priceSubtext'  => sanitize_text_field( $product['priceSubtext'] ?? '' ),
				'ctaText'       => sanitize_text_field( $product['ctaText'] ?? '' ),
				'ctaUrl'        => esc_url_raw( $product['ctaUrl'] ?? '' ),
				'ctaStyle'      => in_array( $product['ctaStyle'] ?? '', array( 'primary', 'outline' ), true ) ? $product['ctaStyle'] : 'primary',
				'isHighlighted' => ! empty( $product['isHighlighted'] ),
			);
		}

		return $sanitized;
	}

	/**
	 * Sanitize sections array.
	 *
	 * @param array $sections Raw sections data.
	 * @return array Sanitized sections.
	 */
	private static function sanitize_sections( array $sections ): array {
		$sanitized = array();

		foreach ( $sections as $section ) {
			if ( ! is_array( $section ) ) {
				continue;
			}

			$rows = array();
			if ( ! empty( $section['rows'] ) && is_array( $section['rows'] ) ) {
				foreach ( $section['rows'] as $row ) {
					if ( ! is_array( $row ) ) {
						continue;
					}

					$values = array();
					if ( ! empty( $row['values'] ) && is_array( $row['values'] ) ) {
						foreach ( $row['values'] as $value ) {
							if ( ! is_array( $value ) ) {
								continue;
							}

							$type = in_array( $value['type'] ?? '', array( 'text', 'check', 'dash', 'link' ), true )
								? $value['type']
								: 'text';

							$values[] = array(
								'type'  => $type,
								'value' => sanitize_text_field( $value['value'] ?? '' ),
								'url'   => 'link' === $type ? esc_url_raw( $value['url'] ?? '' ) : '',
							);
						}
					}

					$rows[] = array(
						'feature' => sanitize_text_field( $row['feature'] ?? '' ),
						'tooltip' => sanitize_text_field( $row['tooltip'] ?? '' ),
						'values'  => $values,
					);
				}
			}

			$sanitized[] = array(
				'title'         => sanitize_text_field( $section['title'] ?? '' ),
				'isDefaultOpen' => ! empty( $section['isDefaultOpen'] ),
				'rows'          => $rows,
			);
		}

		return $sanitized;
	}
}
