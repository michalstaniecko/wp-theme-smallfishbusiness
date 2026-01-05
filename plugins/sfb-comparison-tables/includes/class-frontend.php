<?php
/**
 * Frontend rendering for comparison tables.
 *
 * @package SFB\ComparisonTables
 */

namespace SFB\ComparisonTables;

/**
 * Class Frontend
 *
 * Handles frontend rendering and assets.
 */
class Frontend {

	/**
	 * Initialize frontend.
	 *
	 * @return void
	 */
	public static function init(): void {
		add_shortcode( 'sfb_comparison_table', array( self::class, 'render_shortcode' ) );
		add_action( 'wp_enqueue_scripts', array( self::class, 'register_assets' ) );
	}

	/**
	 * Register frontend assets (enqueue only when needed).
	 *
	 * @return void
	 */
	public static function register_assets(): void {
		wp_register_style(
			'sfb-ct-frontend',
			SFB_CT_ASSETS_URL . 'css/frontend.css',
			array(),
			SFB_CT_VERSION
		);

		wp_register_script(
			'sfb-ct-frontend-accordion',
			SFB_CT_ASSETS_URL . 'js/frontend-accordion.js',
			array(),
			SFB_CT_VERSION,
			true
		);
	}

	/**
	 * Render shortcode.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string Rendered HTML.
	 */
	public static function render_shortcode( array $atts ): string {
		$atts = shortcode_atts(
			array(
				'id' => 0,
			),
			$atts,
			'sfb_comparison_table'
		);

		$post_id = absint( $atts['id'] );

		if ( ! $post_id ) {
			return '';
		}

		return self::render_table( $post_id );
	}

	/**
	 * Render comparison table.
	 *
	 * @param int $post_id Table post ID.
	 * @return string Rendered HTML.
	 */
	public static function render_table( int $post_id ): string {
		$post = get_post( $post_id );

		if ( ! $post || CPT::POST_TYPE !== $post->post_type || 'publish' !== $post->post_status ) {
			return '';
		}

		$products = get_post_meta( $post_id, Meta_Boxes::META_PRODUCTS, true );
		$sections = get_post_meta( $post_id, Meta_Boxes::META_SECTIONS, true );

		if ( ! is_array( $products ) || empty( $products ) ) {
			return '';
		}

		if ( ! is_array( $sections ) ) {
			$sections = array();
		}

		/**
		 * Filter table data before rendering.
		 *
		 * @param array $data     Table data with products and sections.
		 * @param int   $post_id  Table post ID.
		 */
		$data = apply_filters(
			'sfb_ct_table_data',
			array(
				'products' => $products,
				'sections' => $sections,
			),
			$post_id
		);

		$products = $data['products'];
		$sections = $data['sections'];

		// Enqueue assets.
		wp_enqueue_style( 'sfb-ct-frontend' );
		wp_enqueue_script( 'sfb-ct-frontend-accordion' );

		/**
		 * Action before table rendering.
		 *
		 * @param int $post_id Table post ID.
		 */
		do_action( 'sfb_ct_before_table', $post_id );

		ob_start();
		include SFB_CT_PATH . 'templates/comparison-table.php';
		$html = ob_get_clean();

		/**
		 * Filter rendered table HTML.
		 *
		 * @param string $html    Rendered HTML.
		 * @param int    $post_id Table post ID.
		 */
		$html = apply_filters( 'sfb_ct_table_html', $html, $post_id );

		/**
		 * Action after table rendering.
		 *
		 * @param int $post_id Table post ID.
		 */
		do_action( 'sfb_ct_after_table', $post_id );

		return $html;
	}

	/**
	 * Get SVG icon for cell value type.
	 *
	 * @param string $type Value type (check, dash).
	 * @return string SVG HTML.
	 */
	public static function get_icon( string $type ): string {
		switch ( $type ) {
			case 'check':
				return '<svg class="sfb-ct-icon sfb-ct-icon--check" viewBox="0 0 24 24" fill="currentColor" width="20" height="20" aria-hidden="true"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>';

			case 'dash':
				return '<span class="sfb-ct-icon sfb-ct-icon--dash" aria-label="' . esc_attr__( 'Not available', 'sfb-comparison-tables' ) . '">—</span>';

			default:
				return '';
		}
	}

	/**
	 * Render cell value.
	 *
	 * @param array $value Cell value data.
	 * @return string Rendered cell content.
	 */
	public static function render_cell_value( array $value ): string {
		$type      = $value['type'] ?? 'text';
		$text      = $value['value'] ?? '';
		$url       = $value['url'] ?? '';

		switch ( $type ) {
			case 'check':
				return self::get_icon( 'check' );

			case 'dash':
				return self::get_icon( 'dash' );

			case 'link':
				if ( empty( $text ) ) {
					return '';
				}
				return sprintf(
					'<a href="%s" class="sfb-ct-cell__link">%s</a>',
					esc_url( $url ),
					esc_html( $text )
				);

			case 'text':
			default:
				return esc_html( $text );
		}
	}
}
