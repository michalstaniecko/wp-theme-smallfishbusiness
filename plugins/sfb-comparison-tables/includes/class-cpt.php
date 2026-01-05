<?php
/**
 * Custom Post Type registration.
 *
 * @package SFB\ComparisonTables
 */

namespace SFB\ComparisonTables;

/**
 * Class CPT
 *
 * Registers the comparison_table custom post type.
 */
class CPT {

	/**
	 * Post type slug.
	 */
	public const POST_TYPE = 'sfb_comparison_table';

	/**
	 * Initialize the CPT.
	 *
	 * @return void
	 */
	public static function init(): void {
		add_action( 'init', array( self::class, 'register_post_type' ) );
		add_filter( 'manage_' . self::POST_TYPE . '_posts_columns', array( self::class, 'add_columns' ) );
		add_action( 'manage_' . self::POST_TYPE . '_posts_custom_column', array( self::class, 'render_columns' ), 10, 2 );
	}

	/**
	 * Register the custom post type.
	 *
	 * @return void
	 */
	public static function register_post_type(): void {
		$labels = array(
			'name'                  => _x( 'Comparison Tables', 'Post type general name', 'sfb-comparison-tables' ),
			'singular_name'         => _x( 'Comparison Table', 'Post type singular name', 'sfb-comparison-tables' ),
			'menu_name'             => _x( 'Comparison Tables', 'Admin Menu text', 'sfb-comparison-tables' ),
			'name_admin_bar'        => _x( 'Comparison Table', 'Add New on Toolbar', 'sfb-comparison-tables' ),
			'add_new'               => __( 'Add New', 'sfb-comparison-tables' ),
			'add_new_item'          => __( 'Add New Comparison Table', 'sfb-comparison-tables' ),
			'new_item'              => __( 'New Comparison Table', 'sfb-comparison-tables' ),
			'edit_item'             => __( 'Edit Comparison Table', 'sfb-comparison-tables' ),
			'view_item'             => __( 'View Comparison Table', 'sfb-comparison-tables' ),
			'all_items'             => __( 'All Tables', 'sfb-comparison-tables' ),
			'search_items'          => __( 'Search Comparison Tables', 'sfb-comparison-tables' ),
			'parent_item_colon'     => __( 'Parent Comparison Tables:', 'sfb-comparison-tables' ),
			'not_found'             => __( 'No comparison tables found.', 'sfb-comparison-tables' ),
			'not_found_in_trash'    => __( 'No comparison tables found in Trash.', 'sfb-comparison-tables' ),
			'featured_image'        => _x( 'Comparison Table Cover Image', 'Overrides the "Featured Image" phrase', 'sfb-comparison-tables' ),
			'set_featured_image'    => _x( 'Set cover image', 'Overrides the "Set featured image" phrase', 'sfb-comparison-tables' ),
			'remove_featured_image' => _x( 'Remove cover image', 'Overrides the "Remove featured image" phrase', 'sfb-comparison-tables' ),
			'use_featured_image'    => _x( 'Use as cover image', 'Overrides the "Use as featured image" phrase', 'sfb-comparison-tables' ),
			'archives'              => _x( 'Comparison Table archives', 'The post type archive label', 'sfb-comparison-tables' ),
			'insert_into_item'      => _x( 'Insert into comparison table', 'Overrides the "Insert into post" phrase', 'sfb-comparison-tables' ),
			'uploaded_to_this_item' => _x( 'Uploaded to this comparison table', 'Overrides the "Uploaded to this post" phrase', 'sfb-comparison-tables' ),
			'filter_items_list'     => _x( 'Filter comparison tables list', 'Screen reader text', 'sfb-comparison-tables' ),
			'items_list_navigation' => _x( 'Comparison tables list navigation', 'Screen reader text', 'sfb-comparison-tables' ),
			'items_list'            => _x( 'Comparison tables list', 'Screen reader text', 'sfb-comparison-tables' ),
		);

		$args = array(
			'labels'             => $labels,
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => false,
			'rewrite'            => false,
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => 25,
			'menu_icon'          => 'dashicons-editor-table',
			'supports'           => array( 'title', 'revisions' ),
			'show_in_rest'       => true, // Required for Gutenberg block to query.
		);

		register_post_type( self::POST_TYPE, $args );
	}

	/**
	 * Add custom columns to the post list.
	 *
	 * @param array $columns Existing columns.
	 * @return array Modified columns.
	 */
	public static function add_columns( array $columns ): array {
		$new_columns = array();

		foreach ( $columns as $key => $value ) {
			$new_columns[ $key ] = $value;

			// Add custom columns after title.
			if ( 'title' === $key ) {
				$new_columns['products'] = __( 'Products', 'sfb-comparison-tables' );
				$new_columns['sections'] = __( 'Sections', 'sfb-comparison-tables' );
				$new_columns['shortcode'] = __( 'Shortcode', 'sfb-comparison-tables' );
			}
		}

		return $new_columns;
	}

	/**
	 * Render custom column content.
	 *
	 * @param string $column  Column name.
	 * @param int    $post_id Post ID.
	 * @return void
	 */
	public static function render_columns( string $column, int $post_id ): void {
		switch ( $column ) {
			case 'products':
				$products = get_post_meta( $post_id, '_sfb_ct_products', true );
				$products = is_array( $products ) ? $products : array();
				$count    = count( $products );
				printf(
					/* translators: %d: number of products */
					esc_html( _n( '%d product', '%d products', $count, 'sfb-comparison-tables' ) ),
					esc_html( $count )
				);
				break;

			case 'sections':
				$sections = get_post_meta( $post_id, '_sfb_ct_sections', true );
				$sections = is_array( $sections ) ? $sections : array();
				$count    = count( $sections );
				printf(
					/* translators: %d: number of sections */
					esc_html( _n( '%d section', '%d sections', $count, 'sfb-comparison-tables' ) ),
					esc_html( $count )
				);
				break;

			case 'shortcode':
				printf(
					'<code>[sfb_comparison_table id="%d"]</code>',
					esc_attr( $post_id )
				);
				break;
		}
	}

	/**
	 * Get all comparison tables for select options.
	 *
	 * @return array Array of post ID => title pairs.
	 */
	public static function get_tables_for_select(): array {
		$tables = get_posts(
			array(
				'post_type'      => self::POST_TYPE,
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'orderby'        => 'title',
				'order'          => 'ASC',
			)
		);

		$options = array();
		foreach ( $tables as $table ) {
			$options[ $table->ID ] = $table->post_title;
		}

		return $options;
	}
}
