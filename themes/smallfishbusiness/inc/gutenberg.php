<?php
/**
 * Gutenberg Configuration
 *
 * Note: Colors, typography and layout are configured in theme.json
 * This file contains additional PHP functions for Gutenberg
 *
 * @package SmallFishBusiness
 */

/**
 * Setup Gutenberg editor styles.
 */
function sfb_gutenberg_setup() {
    // Add editor styles from Vite build
    $manifest_path = get_template_directory() . '/build/.vite/manifest.json';

    if ( file_exists( $manifest_path ) ) {
        $manifest = json_decode( file_get_contents( $manifest_path ), true );

        if ( isset( $manifest['src/css/main.css'] ) ) {
            add_editor_style( 'build/' . $manifest['src/css/main.css']['file'] );
        }
    }
}
add_action( 'after_setup_theme', 'sfb_gutenberg_setup' );

/**
 * Enqueue editor assets for development mode.
 */
function sfb_enqueue_editor_assets() {
    // In development mode, enqueue from Vite dev server
    if ( defined( 'WP_DEBUG' ) && WP_DEBUG && function_exists( 'sfb_is_vite_dev_server_running' ) && sfb_is_vite_dev_server_running() ) {
        $vite_url = sfb_get_vite_dev_url();

        wp_enqueue_style(
            'sfb-editor-style',
            $vite_url . '/src/css/main.css',
            [],
            null
        );
    }
}
add_action( 'enqueue_block_editor_assets', 'sfb_enqueue_editor_assets' );

/**
 * Register custom block styles for core blocks.
 */
function sfb_register_block_styles() {
    // Heading styles
    register_block_style( 'core/heading', [
        'name'  => 'underline',
        'label' => __( 'With underline', 'smallfishbusiness' ),
    ] );

    register_block_style( 'core/heading', [
        'name'  => 'accent',
        'label' => __( 'Accent color', 'smallfishbusiness' ),
    ] );

    // Paragraph styles
    register_block_style( 'core/paragraph', [
        'name'  => 'lead',
        'label' => __( 'Lead paragraph', 'smallfishbusiness' ),
    ] );

    register_block_style( 'core/paragraph', [
        'name'  => 'highlight',
        'label' => __( 'Highlighted', 'smallfishbusiness' ),
    ] );

    // Quote styles
    register_block_style( 'core/quote', [
        'name'  => 'bordered',
        'label' => __( 'Left border', 'smallfishbusiness' ),
    ] );

    register_block_style( 'core/quote', [
        'name'  => 'large',
        'label' => __( 'Large quote', 'smallfishbusiness' ),
    ] );

    // List styles
    register_block_style( 'core/list', [
        'name'  => 'checklist',
        'label' => __( 'Checklist', 'smallfishbusiness' ),
    ] );

    // Image styles
    register_block_style( 'core/image', [
        'name'  => 'rounded',
        'label' => __( 'Rounded corners', 'smallfishbusiness' ),
    ] );

    register_block_style( 'core/image', [
        'name'  => 'shadow',
        'label' => __( 'With shadow', 'smallfishbusiness' ),
    ] );

    // Group/Container styles
    register_block_style( 'core/group', [
        'name'  => 'card',
        'label' => __( 'Card', 'smallfishbusiness' ),
    ] );

    register_block_style( 'core/group', [
        'name'  => 'highlight-box',
        'label' => __( 'Highlight box', 'smallfishbusiness' ),
    ] );
}
add_action( 'init', 'sfb_register_block_styles' );
