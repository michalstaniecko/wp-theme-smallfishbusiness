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

/**
 * Add IDs to headings in content for TOC.
 *
 * @param string $content Post content.
 * @return string Modified content with heading IDs.
 */
function sfb_add_heading_ids( $content ) {
    if ( ! is_single() ) {
        return $content;
    }

    $pattern = '/<h([2-3])([^>]*)>(.*?)<\/h[2-3]>/i';

    $counter = 0;
    $callback = function( $matches ) use ( &$counter ) {
        $counter++;

        $level = $matches[1];
        $attrs = $matches[2];
        $text  = $matches[3];

        // Check if ID already exists
        if ( strpos( $attrs, 'id=' ) === false ) {
            $id    = sanitize_title( wp_strip_all_tags( $text ) ) . '-' . $counter;
            $attrs = ' id="' . esc_attr( $id ) . '"' . $attrs;
        }

        return "<h{$level}{$attrs}>{$text}</h{$level}>";
    };

    return preg_replace_callback( $pattern, $callback, $content );
}
add_filter( 'the_content', 'sfb_add_heading_ids' );

/**
 * Add loading="lazy" to images in content.
 *
 * @param string $content Post content.
 * @return string Modified content with lazy loading.
 */
function sfb_add_lazy_loading( $content ) {
    // Add loading="lazy" to img tags that don't have it
    $content = preg_replace(
        '/<img((?!loading=)[^>]*)>/i',
        '<img$1 loading="lazy">',
        $content
    );
    return $content;
}
add_filter( 'the_content', 'sfb_add_lazy_loading' );
add_filter( 'post_thumbnail_html', 'sfb_add_lazy_loading' );

/**
 * Add fetchpriority="high" to above-the-fold featured images.
 * Remove lazy loading for featured images on single posts/pages.
 *
 * @param string       $html              The post thumbnail HTML.
 * @param int          $post_id           The post ID.
 * @param int          $post_thumbnail_id The post thumbnail ID.
 * @param string|int[] $size              The post thumbnail size.
 * @param array        $attr              Attributes for the image markup.
 * @return string Modified HTML.
 */
function sfb_featured_image_priority( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
    // Add high priority for featured images (usually above the fold)
    if ( is_single() || is_page() ) {
        $html = str_replace( '<img', '<img fetchpriority="high"', $html );
        // Remove lazy loading for featured image
        $html = str_replace( ' loading="lazy"', '', $html );
    }
    return $html;
}
add_filter( 'post_thumbnail_html', 'sfb_featured_image_priority', 15, 5 );

/**
 * Custom comment callback for wp_list_comments.
 *
 * @param WP_Comment $comment The comment object.
 * @param array      $args    Arguments passed to wp_list_comments.
 * @param int        $depth   Depth of the current comment.
 */
function sfb_comment_callback( $comment, $args, $depth ) {
    $tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
    ?>
    <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( 'bg-gray-50 rounded-lg p-6', $comment ); ?>>
        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
            <footer class="comment-meta flex items-start gap-4 mb-4">
                <div class="comment-author vcard flex-shrink-0">
                    <?php echo get_avatar( $comment, 48, '', '', [ 'class' => 'rounded-full' ] ); ?>
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="fn font-semibold text-gray-900">
                            <?php echo get_comment_author_link( $comment ); ?>
                        </span>
                        <time datetime="<?php echo esc_attr( get_comment_date( 'c', $comment ) ); ?>" class="text-sm text-gray-500">
                            <?php
                            printf(
                                /* translators: 1: comment date, 2: comment time */
                                esc_html__( '%1$s at %2$s', 'smallfishbusiness' ),
                                get_comment_date( '', $comment ),
                                get_comment_time()
                            );
                            ?>
                        </time>
                    </div>

                    <?php if ( '0' === $comment->comment_approved ) : ?>
                        <p class="comment-awaiting-moderation text-sm text-amber-600 mt-1">
                            <?php esc_html_e( 'Your comment is awaiting moderation.', 'smallfishbusiness' ); ?>
                        </p>
                    <?php endif; ?>
                </div>
            </footer>

            <div class="comment-content prose prose-sm max-w-none text-gray-700 mb-4">
                <?php comment_text(); ?>
            </div>

            <div class="reply text-sm">
                <?php
                comment_reply_link(
                    array_merge(
                        $args,
                        [
                            'add_below' => 'div-comment',
                            'depth'     => $depth,
                            'max_depth' => $args['max_depth'],
                            'before'    => '<span class="text-primary-600 hover:text-primary-700 font-medium">',
                            'after'     => '</span>',
                        ]
                    )
                );
                ?>

                <?php
                edit_comment_link(
                    esc_html__( 'Edit', 'smallfishbusiness' ),
                    '<span class="edit-link text-gray-500 hover:text-gray-700 ml-4">',
                    '</span>'
                );
                ?>
            </div>
        </article>
    <?php
}
