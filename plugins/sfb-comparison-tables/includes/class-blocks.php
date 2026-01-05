<?php
/**
 * Gutenberg blocks registration.
 *
 * @package SFB\ComparisonTables
 */

namespace SFB\ComparisonTables;

/**
 * Class Blocks
 *
 * Handles registration of Gutenberg blocks.
 */
class Blocks {

	/**
	 * Initialize blocks.
	 *
	 * @return void
	 */
	public static function init(): void {
		add_action( 'init', array( self::class, 'register_blocks' ) );
		add_action( 'rest_api_init', array( self::class, 'register_rest_routes' ) );
	}

	/**
	 * Register Gutenberg blocks.
	 *
	 * @return void
	 */
	public static function register_blocks(): void {
		$blocks_dir = SFB_CT_BLOCKS_PATH;

		if ( ! is_dir( $blocks_dir ) ) {
			return;
		}

		// Check for blocks-manifest.php (WP 6.7+).
		$manifest_path = $blocks_dir . 'blocks-manifest.php';

		if ( file_exists( $manifest_path ) ) {
			// WP 6.8+ - most efficient.
			if ( function_exists( 'wp_register_block_types_from_metadata_collection' ) ) {
				wp_register_block_types_from_metadata_collection( $blocks_dir, $manifest_path );
				return;
			}

			// WP 6.7 - metadata collection.
			if ( function_exists( 'wp_register_block_metadata_collection' ) ) {
				wp_register_block_metadata_collection( $blocks_dir, $manifest_path );
			}

			$manifest_data = require $manifest_path;
			foreach ( array_keys( $manifest_data ) as $block_type ) {
				register_block_type( $blocks_dir . $block_type );
			}
			return;
		}

		// Fallback - register individual blocks.
		$block_dirs = glob( $blocks_dir . '*', GLOB_ONLYDIR );

		if ( ! $block_dirs ) {
			return;
		}

		foreach ( $block_dirs as $block_dir ) {
			if ( file_exists( $block_dir . '/block.json' ) ) {
				register_block_type( $block_dir );
			}
		}
	}

	/**
	 * Register REST API routes for block data.
	 *
	 * @return void
	 */
	public static function register_rest_routes(): void {
		register_rest_route(
			'sfb-ct/v1',
			'/tables',
			array(
				'methods'             => 'GET',
				'callback'            => array( self::class, 'get_tables' ),
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);

		register_rest_route(
			'sfb-ct/v1',
			'/table/(?P<id>\d+)',
			array(
				'methods'             => 'GET',
				'callback'            => array( self::class, 'get_table' ),
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
				'args'                => array(
					'id' => array(
						'validate_callback' => function ( $param ) {
							return is_numeric( $param );
						},
					),
				),
			)
		);
	}

	/**
	 * Get all tables for the block editor.
	 *
	 * @return \WP_REST_Response
	 */
	public static function get_tables(): \WP_REST_Response {
		$tables = get_posts(
			array(
				'post_type'      => CPT::POST_TYPE,
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'orderby'        => 'title',
				'order'          => 'ASC',
			)
		);

		$data = array();
		foreach ( $tables as $table ) {
			$products = get_post_meta( $table->ID, Meta_Boxes::META_PRODUCTS, true );
			$sections = get_post_meta( $table->ID, Meta_Boxes::META_SECTIONS, true );

			$data[] = array(
				'id'            => $table->ID,
				'title'         => $table->post_title,
				'productCount'  => is_array( $products ) ? count( $products ) : 0,
				'sectionCount'  => is_array( $sections ) ? count( $sections ) : 0,
			);
		}

		return new \WP_REST_Response( $data, 200 );
	}

	/**
	 * Get single table data for preview.
	 *
	 * @param \WP_REST_Request $request Request object.
	 * @return \WP_REST_Response
	 */
	public static function get_table( \WP_REST_Request $request ): \WP_REST_Response {
		$post_id = (int) $request->get_param( 'id' );
		$post    = get_post( $post_id );

		if ( ! $post || CPT::POST_TYPE !== $post->post_type ) {
			return new \WP_REST_Response(
				array( 'error' => __( 'Table not found', 'sfb-comparison-tables' ) ),
				404
			);
		}

		$products = get_post_meta( $post_id, Meta_Boxes::META_PRODUCTS, true );
		$sections = get_post_meta( $post_id, Meta_Boxes::META_SECTIONS, true );

		return new \WP_REST_Response(
			array(
				'id'       => $post_id,
				'title'    => $post->post_title,
				'products' => is_array( $products ) ? $products : array(),
				'sections' => is_array( $sections ) ? $sections : array(),
			),
			200
		);
	}
}
