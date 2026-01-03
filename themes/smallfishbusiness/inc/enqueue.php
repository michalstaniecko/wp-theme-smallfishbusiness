<?php
/**
 * Enqueue scripts and styles (Vite integration)
 *
 * @package SmallFishBusiness
 */

/**
 * Check if Vite dev server is running.
 *
 * @return bool
 */
function sfb_is_vite_dev_server_running() {
    $vite_port = 5173;
    $handle    = @fsockopen( 'localhost', $vite_port, $errno, $errstr, 0.1 );

    if ( $handle ) {
        fclose( $handle );
        return true;
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
