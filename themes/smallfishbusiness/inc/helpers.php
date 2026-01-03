<?php
/**
 * Helper Functions
 *
 * @package SmallFishBusiness
 */

/**
 * Get estimated reading time for a post.
 *
 * @param int $post_id Post ID. Defaults to current post.
 * @return int Reading time in minutes.
 */
function sfb_get_reading_time( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    $content    = get_post_field( 'post_content', $post_id );
    $word_count = str_word_count( wp_strip_all_tags( $content ) );
    $reading_time = ceil( $word_count / 200 );

    return max( 1, $reading_time );
}

/**
 * Display estimated reading time.
 *
 * @param int $post_id Post ID. Defaults to current post.
 */
function sfb_reading_time( $post_id = null ) {
    $time = sfb_get_reading_time( $post_id );
    printf(
        /* translators: %d: reading time in minutes */
        esc_html( _n( '%d min read', '%d min read', $time, 'smallfishbusiness' ) ),
        $time
    );
}

/**
 * Get truncated excerpt with custom length.
 *
 * @param int    $length Number of words.
 * @param int    $post_id Post ID. Defaults to current post.
 * @param string $more Text to append when truncated.
 * @return string Truncated excerpt.
 */
function sfb_get_excerpt( $length = 25, $post_id = null, $more = '...' ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    $excerpt = get_the_excerpt( $post_id );

    if ( empty( $excerpt ) ) {
        $excerpt = get_post_field( 'post_content', $post_id );
    }

    $excerpt = wp_strip_all_tags( $excerpt );
    $words   = explode( ' ', $excerpt );

    if ( count( $words ) > $length ) {
        $excerpt = implode( ' ', array_slice( $words, 0, $length ) ) . $more;
    }

    return $excerpt;
}

/**
 * Check if current page is a blog page (archive, single post, or posts page).
 *
 * @return bool
 */
function sfb_is_blog() {
    return is_home() || is_archive() || is_single();
}

/**
 * Get primary category for a post.
 *
 * @param int $post_id Post ID. Defaults to current post.
 * @return WP_Term|false Primary category or false.
 */
function sfb_get_primary_category( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    $categories = get_the_category( $post_id );

    if ( empty( $categories ) ) {
        return false;
    }

    // Return first category (can be enhanced with Yoast SEO primary category)
    return $categories[0];
}
