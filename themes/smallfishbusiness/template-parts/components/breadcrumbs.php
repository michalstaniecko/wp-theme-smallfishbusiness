<?php
/**
 * Breadcrumbs Component
 *
 * @package SmallFishBusiness
 */

/**
 * Get breadcrumbs data
 *
 * @return array
 */
function sfb_get_breadcrumbs() {
    $breadcrumbs = [];

    // Home
    $breadcrumbs[] = [
        'title' => __( 'Home', 'smallfishbusiness' ),
        'url'   => home_url( '/' ),
    ];

    if ( is_single() ) {
        // Category
        $categories = get_the_category();
        if ( ! empty( $categories ) ) {
            $breadcrumbs[] = [
                'title' => $categories[0]->name,
                'url'   => get_category_link( $categories[0]->term_id ),
            ];
        }
        // Current post
        $breadcrumbs[] = [
            'title' => get_the_title(),
            'url'   => null,
        ];
    } elseif ( is_category() ) {
        $breadcrumbs[] = [
            'title' => single_cat_title( '', false ),
            'url'   => null,
        ];
    } elseif ( is_tag() ) {
        $breadcrumbs[] = [
            'title' => single_tag_title( '', false ),
            'url'   => null,
        ];
    } elseif ( is_author() ) {
        $breadcrumbs[] = [
            'title' => get_the_author(),
            'url'   => null,
        ];
    } elseif ( is_page() ) {
        // Page with parent support
        global $post;
        if ( $post->post_parent ) {
            $ancestors = get_post_ancestors( $post->ID );
            $ancestors = array_reverse( $ancestors );
            foreach ( $ancestors as $ancestor ) {
                $breadcrumbs[] = [
                    'title' => get_the_title( $ancestor ),
                    'url'   => get_permalink( $ancestor ),
                ];
            }
        }
        $breadcrumbs[] = [
            'title' => get_the_title(),
            'url'   => null,
        ];
    } elseif ( is_search() ) {
        $breadcrumbs[] = [
            'title' => sprintf( __( 'Search: %s', 'smallfishbusiness' ), get_search_query() ),
            'url'   => null,
        ];
    } elseif ( is_archive() ) {
        $breadcrumbs[] = [
            'title' => get_the_archive_title(),
            'url'   => null,
        ];
    }

    return $breadcrumbs;
}

$breadcrumbs = sfb_get_breadcrumbs();
$count       = count( $breadcrumbs );
?>

<nav aria-label="<?php esc_attr_e( 'Breadcrumb', 'smallfishbusiness' ); ?>" class="breadcrumbs">
    <ol class="breadcrumbs__list">
        <?php foreach ( $breadcrumbs as $index => $crumb ) :
            $is_last = ( $index === $count - 1 );
        ?>
            <li class="breadcrumbs__item">
                <?php if ( $index > 0 ) : ?>
                    <svg class="breadcrumbs__separator" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                <?php endif; ?>

                <?php if ( $crumb['url'] && ! $is_last ) : ?>
                    <a href="<?php echo esc_url( $crumb['url'] ); ?>" class="breadcrumbs__link">
                        <?php echo esc_html( $crumb['title'] ); ?>
                    </a>
                <?php else : ?>
                    <span class="breadcrumbs__current" aria-current="page">
                        <?php echo esc_html( wp_trim_words( $crumb['title'], 8, '...' ) ); ?>
                    </span>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ol>
</nav>
