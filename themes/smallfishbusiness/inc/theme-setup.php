<?php
/**
 * Theme Setup
 *
 * @package SmallFishBusiness
 */

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function sfb_theme_setup() {
    // Add theme support
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [
        'comment-list',
        'comment-form',
        'search-form',
        'gallery',
        'caption',
        'style',
        'script',
    ] );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'editor-styles' );
    add_theme_support( 'wp-block-styles' );

    // Register navigation menus
    register_nav_menus( [
        'primary' => __( 'Primary Menu', 'smallfishbusiness' ),
        'footer'  => __( 'Footer Menu', 'smallfishbusiness' ),
    ] );

    // Set content width
    $GLOBALS['content_width'] = 1200;
}
add_action( 'after_setup_theme', 'sfb_theme_setup' );

/**
 * Register widget areas.
 */
function sfb_widgets_init() {
    register_sidebar( [
        'name'          => __( 'Main Sidebar', 'smallfishbusiness' ),
        'id'            => 'sidebar-main',
        'description'   => __( 'Widgets in this area will be shown on all posts and pages.', 'smallfishbusiness' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title widget-title--styled">',
        'after_title'   => '</h3>',
    ] );

    register_sidebar( [
        'name'          => __( 'Footer Column 1', 'smallfishbusiness' ),
        'id'            => 'footer-1',
        'description'   => __( 'First footer widget area.', 'smallfishbusiness' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ] );

    register_sidebar( [
        'name'          => __( 'Footer Column 2', 'smallfishbusiness' ),
        'id'            => 'footer-2',
        'description'   => __( 'Second footer widget area.', 'smallfishbusiness' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ] );

    register_sidebar( [
        'name'          => __( 'Footer Column 3', 'smallfishbusiness' ),
        'id'            => 'footer-3',
        'description'   => __( 'Third footer widget area.', 'smallfishbusiness' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ] );
}
add_action( 'widgets_init', 'sfb_widgets_init' );
