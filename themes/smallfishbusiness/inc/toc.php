<?php
/**
 * Table of Contents - Server-side generation
 *
 * Generates ToC from post content headings (H2, H3) and saves to post meta.
 * Provides schema.org structured data for SEO.
 *
 * @package SmallFishBusiness
 */

/**
 * Class SFB_Table_Of_Contents
 *
 * Handles server-side ToC generation and rendering.
 */
class SFB_Table_Of_Contents {

	/**
	 * Meta key for storing ToC data.
	 */
	const META_KEY = '_sfb_toc_data';

	/**
	 * Minimum number of headings required to show ToC.
	 */
	const MIN_HEADINGS = 2;

	/**
	 * Initialize the class.
	 */
	public static function init() {
		add_action( 'save_post', [ __CLASS__, 'generate_toc_on_save' ], 10, 3 );
		add_action( 'wp_insert_post', [ __CLASS__, 'generate_toc_on_save' ], 10, 3 );
		add_action( 'wp_head', [ __CLASS__, 'output_schema' ], 99 );
	}

	/**
	 * Output schema.org data in head for single posts.
	 */
	public static function output_schema() {
		if ( ! is_single() ) {
			return;
		}

		self::render_schema();
	}

	/**
	 * Generate ToC when post is saved.
	 *
	 * @param int     $post_id Post ID.
	 * @param WP_Post $post    Post object.
	 * @param bool    $update  Whether this is an update.
	 */
	public static function generate_toc_on_save( $post_id, $post, $update ) {
		// Skip autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Skip revisions.
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		// Only process posts.
		if ( 'post' !== $post->post_type ) {
			return;
		}

		// Only published posts.
		if ( 'publish' !== $post->post_status ) {
			return;
		}

		// Generate and save ToC.
		$toc_data = self::parse_headings( $post->post_content );
		update_post_meta( $post_id, self::META_KEY, $toc_data );
	}

	/**
	 * Parse headings from content.
	 *
	 * Uses the same ID generation logic as sfb_add_heading_ids() in helpers.php
	 * to ensure consistency between server-side ToC and rendered content.
	 *
	 * @param string $content Post content.
	 * @return array Array of heading data.
	 */
	public static function parse_headings( $content ) {
		$headings = [];

		// Process Gutenberg blocks to get rendered HTML.
		$content = do_blocks( $content );

		// Match H2 and H3 headings.
		$pattern = '/<h([2-3])([^>]*)>(.*?)<\/h[2-3]>/is';

		if ( ! preg_match_all( $pattern, $content, $matches, PREG_SET_ORDER ) ) {
			return $headings;
		}

		$counter = 0;
		foreach ( $matches as $match ) {
			$counter++;
			$level = (int) $match[1];
			$attrs = $match[2];
			$text  = wp_strip_all_tags( $match[3] );

			// Extract existing ID or generate one using same logic as sfb_add_heading_ids().
			$id = '';
			if ( preg_match( '/id=["\']([^"\']+)["\']/', $attrs, $id_match ) ) {
				$id = $id_match[1];
			} else {
				// Same formula as sfb_add_heading_ids() in helpers.php.
				$id = sanitize_title( $text ) . '-' . $counter;
			}

			$headings[] = [
				'id'    => $id,
				'text'  => $text,
				'level' => $level,
			];
		}

		return $headings;
	}

	/**
	 * Get ToC data for a post.
	 *
	 * @param int $post_id Post ID. Defaults to current post.
	 * @return array ToC data array.
	 */
	public static function get_toc( $post_id = null ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		$toc_data = get_post_meta( $post_id, self::META_KEY, true );

		// If no cached data, generate on the fly.
		if ( empty( $toc_data ) ) {
			$post     = get_post( $post_id );
			$toc_data = self::parse_headings( $post->post_content );

			// Cache for next time.
			if ( 'publish' === $post->post_status ) {
				update_post_meta( $post_id, self::META_KEY, $toc_data );
			}
		}

		return is_array( $toc_data ) ? $toc_data : [];
	}

	/**
	 * Check if post has enough headings for ToC.
	 *
	 * @param int $post_id Post ID.
	 * @return bool True if ToC should be displayed.
	 */
	public static function has_toc( $post_id = null ) {
		$toc = self::get_toc( $post_id );
		return count( $toc ) >= self::MIN_HEADINGS;
	}

	/**
	 * Render ToC HTML.
	 *
	 * @param int  $post_id Post ID.
	 * @param bool $echo    Whether to echo or return.
	 * @return string|void HTML output if $echo is false.
	 */
	public static function render( $post_id = null, $echo = true ) {
		if ( ! self::has_toc( $post_id ) ) {
			return '';
		}

		$toc = self::get_toc( $post_id );

		ob_start();
		?>
		<nav id="toc" class="toc-nav" aria-label="<?php esc_attr_e( 'Table of Contents', 'smallfishbusiness' ); ?>">
			<ul class="space-y-1">
				<?php foreach ( $toc as $item ) : ?>
					<li class="<?php echo 3 === $item['level'] ? 'pl-4' : ''; ?>">
						<a href="#<?php echo esc_attr( $item['id'] ); ?>"
						   class="toc-link"
						   data-target="<?php echo esc_attr( $item['id'] ); ?>">
							<?php echo esc_html( $item['text'] ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</nav>
		<?php

		$html = ob_get_clean();

		if ( $echo ) {
			echo $html;
		} else {
			return $html;
		}
	}

	/**
	 * Get schema.org structured data for ToC.
	 *
	 * @param int $post_id Post ID.
	 * @return array Schema.org data.
	 */
	public static function get_schema( $post_id = null ) {
		if ( ! self::has_toc( $post_id ) ) {
			return [];
		}

		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		$toc       = self::get_toc( $post_id );
		$permalink = get_permalink( $post_id );

		$items = [];
		foreach ( $toc as $index => $item ) {
			$items[] = [
				'@type'    => 'ListItem',
				'position' => $index + 1,
				'name'     => $item['text'],
				'url'      => $permalink . '#' . $item['id'],
			];
		}

		return [
			'@type'           => 'ItemList',
			'name'            => __( 'Table of Contents', 'smallfishbusiness' ),
			'itemListElement' => $items,
		];
	}

	/**
	 * Render schema.org JSON-LD script.
	 *
	 * @param int $post_id Post ID.
	 */
	public static function render_schema( $post_id = null ) {
		$schema = self::get_schema( $post_id );

		if ( empty( $schema ) ) {
			return;
		}

		// Merge with Article schema.
		$full_schema = [
			'@context' => 'https://schema.org',
			'@type'    => 'Article',
			'hasPart'  => $schema,
		];

		printf(
			'<script type="application/ld+json">%s</script>',
			wp_json_encode( $full_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
		);
	}
}

// Initialize.
SFB_Table_Of_Contents::init();

/**
 * Template helper functions.
 */

/**
 * Check if current post has ToC.
 *
 * @param int $post_id Post ID.
 * @return bool
 */
function sfb_has_toc( $post_id = null ) {
	return SFB_Table_Of_Contents::has_toc( $post_id );
}

/**
 * Display ToC.
 *
 * @param int $post_id Post ID.
 */
function sfb_the_toc( $post_id = null ) {
	SFB_Table_Of_Contents::render( $post_id );
}

/**
 * Get ToC HTML.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function sfb_get_toc( $post_id = null ) {
	return SFB_Table_Of_Contents::render( $post_id, false );
}

/**
 * Output ToC schema.org data.
 *
 * @param int $post_id Post ID.
 */
function sfb_toc_schema( $post_id = null ) {
	SFB_Table_Of_Contents::render_schema( $post_id );
}
