<?php
/**
 * Performance Optimizations
 *
 * @package SmallFishBusiness
 */

/**
 * Remove emoji scripts and styles.
 */
function sfb_disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

    // Remove emoji DNS prefetch.
    add_filter( 'emoji_svg_url', '__return_false' );
}
add_action( 'init', 'sfb_disable_emojis' );

/**
 * Remove unnecessary WordPress header tags.
 */
function sfb_remove_header_clutter() {
    // Remove WordPress version.
    remove_action( 'wp_head', 'wp_generator' );

    // Remove Windows Live Writer manifest.
    remove_action( 'wp_head', 'wlwmanifest_link' );

    // Remove Really Simple Discovery.
    remove_action( 'wp_head', 'rsd_link' );

    // Remove shortlink.
    remove_action( 'wp_head', 'wp_shortlink_wp_head' );

    // Remove REST API link.
    remove_action( 'wp_head', 'rest_output_link_wp_head' );

    // Remove oEmbed discovery links.
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
}
add_action( 'init', 'sfb_remove_header_clutter' );

/**
 * Disable XML-RPC for security.
 */
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Remove XML-RPC header.
 *
 * @param array $headers HTTP headers.
 * @return array Modified headers.
 */
function sfb_remove_xmlrpc_header( $headers ) {
    unset( $headers['X-Pingback'] );
    return $headers;
}
add_filter( 'wp_headers', 'sfb_remove_xmlrpc_header' );

/**
 * Disable self-pingbacks.
 *
 * @param array $links Links to ping.
 */
function sfb_disable_self_pingbacks( &$links ) {
    $home = get_option( 'home' );
    foreach ( $links as $l => $link ) {
        if ( strpos( $link, $home ) === 0 ) {
            unset( $links[ $l ] );
        }
    }
}
add_action( 'pre_ping', 'sfb_disable_self_pingbacks' );

/**
 * Remove query strings from static resources for better caching.
 *
 * @param string $src Resource URL.
 * @return string Modified URL without version query string.
 */
function sfb_remove_script_version( $src ) {
    if ( strpos( $src, '?ver=' ) ) {
        $src = remove_query_arg( 'ver', $src );
    }
    return $src;
}
// Only apply in production (when not debugging).
if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
    add_filter( 'script_loader_src', 'sfb_remove_script_version', 15, 1 );
    add_filter( 'style_loader_src', 'sfb_remove_script_version', 15, 1 );
}

/**
 * Add defer attribute to non-critical scripts.
 *
 * @param string $tag    The script tag.
 * @param string $handle The script handle.
 * @param string $src    The script source.
 * @return string Modified script tag.
 */
function sfb_defer_scripts( $tag, $handle, $src ) {
    // Don't defer admin scripts or critical scripts.
    $no_defer = [ 'jquery', 'jquery-core', 'jquery-migrate', 'vite-client', 'sfb-main' ];

    if ( is_admin() || in_array( $handle, $no_defer, true ) ) {
        return $tag;
    }

    // Skip if already has defer or async.
    if ( strpos( $tag, ' defer' ) !== false || strpos( $tag, ' async' ) !== false ) {
        return $tag;
    }

    return str_replace( ' src=', ' defer src=', $tag );
}
add_filter( 'script_loader_tag', 'sfb_defer_scripts', 10, 3 );

/**
 * Limit post revisions to reduce database bloat.
 */
if ( ! defined( 'WP_POST_REVISIONS' ) ) {
    define( 'WP_POST_REVISIONS', 5 );
}
