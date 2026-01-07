<?php
/**
 * Enqueue scripts and styles (Vite integration)
 *
 * @package SmallFishBusiness
 */

/**
 * Check if Vite dev server is running.
 * Uses host.docker.internal for Docker environments.
 *
 * @return bool
 */
function sfb_is_vite_dev_server_running() {
    $vite_port = 5173;

    // Try host.docker.internal first (Docker on macOS/Windows)
    $hosts_to_try = [ 'host.docker.internal', 'localhost' ];

    foreach ( $hosts_to_try as $host ) {
        $handle = @fsockopen( $host, $vite_port, $errno, $errstr, 0.3 );
        if ( $handle ) {
            fclose( $handle );
            return true;
        }
    }

    return false;
}

/**
 * Get Vite dev server URL.
 *
 * @return string
 */
function sfb_get_vite_dev_url() {
    return 'http://localhost:5173';
}

/**
 * Enqueue frontend assets.
 */
function sfb_enqueue_assets() {
    $theme_path = get_template_directory();
    $theme_uri  = get_template_directory_uri();

    // Development mode - load from Vite dev server
    if ( defined( 'WP_DEBUG' ) && WP_DEBUG && sfb_is_vite_dev_server_running() ) {
        $vite_url = sfb_get_vite_dev_url();

        // Vite client for HMR
        wp_enqueue_script(
            'vite-client',
            $vite_url . '/@vite/client',
            [],
            null,
            false
        );

        // Main CSS
        wp_enqueue_style(
            'sfb-style',
            $vite_url . '/src/css/main.css',
            [],
            null
        );

        // Main JS
        wp_enqueue_script(
            'sfb-main',
            $vite_url . '/src/js/main.js',
            [],
            null,
            true
        );

        return;
    }

    // Production mode - load from manifest
    $manifest_path = $theme_path . '/build/.vite/manifest.json';

    if ( ! file_exists( $manifest_path ) ) {
        return;
    }

    $manifest = json_decode( file_get_contents( $manifest_path ), true );

    if ( ! $manifest ) {
        return;
    }

    // Enqueue CSS
    if ( isset( $manifest['src/css/main.css'] ) ) {
        wp_enqueue_style(
            'sfb-style',
            $theme_uri . '/build/' . $manifest['src/css/main.css']['file'],
            [],
            null
        );
    }

    // Enqueue JS
    if ( isset( $manifest['src/js/main.js'] ) ) {
        wp_enqueue_script(
            'sfb-main',
            $theme_uri . '/build/' . $manifest['src/js/main.js']['file'],
            [],
            null,
            true
        );
    }
}
add_action( 'wp_enqueue_scripts', 'sfb_enqueue_assets' );

/**
 * Add type="module" to Vite scripts.
 *
 * @param string $tag    The script tag.
 * @param string $handle The script handle.
 * @param string $src    The script source.
 * @return string Modified script tag.
 */
function sfb_script_type_module( $tag, $handle, $src ) {
    $module_handles = [ 'vite-client', 'sfb-main' ];

    if ( in_array( $handle, $module_handles, true ) ) {
        return '<script type="module" src="' . esc_url( $src ) . '"></script>' . "\n";
    }

    return $tag;
}
add_filter( 'script_loader_tag', 'sfb_script_type_module', 10, 3 );

/**
 * Preload and preconnect fonts for better performance.
 */
function sfb_preload_fonts() {
    ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&display=swap">
    </noscript>
    <?php
}
add_action( 'wp_head', 'sfb_preload_fonts', 1 );

/**
 * Add resource hints for fonts.
 *
 * @param array  $urls          URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed.
 * @return array Modified URLs.
 */
function sfb_resource_hints( $urls, $relation_type ) {
    if ( 'preconnect' === $relation_type ) {
        $urls[] = [
            'href' => 'https://fonts.googleapis.com',
        ];
        $urls[] = [
            'href'        => 'https://fonts.gstatic.com',
            'crossorigin' => 'anonymous',
        ];
    }
    return $urls;
}
add_filter( 'wp_resource_hints', 'sfb_resource_hints', 10, 2 );
