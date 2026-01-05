<?php
/**
 * Plugin Name: SFB Comparison Tables
 * Plugin URI: https://smallfishbusiness.com
 * Description: Comparison tables for products and subscription plans with accordion sections.
 * Version: 1.0.0
 * Author: Small Fish Business
 * Author URI: https://smallfishbusiness.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: sfb-comparison-tables
 * Domain Path: /languages
 * Requires at least: 6.7
 * Requires PHP: 8.0
 *
 * @package SFB\ComparisonTables
 */

namespace SFB\ComparisonTables;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Plugin constants.
define( 'SFB_CT_VERSION', '1.0.0' );
define( 'SFB_CT_FILE', __FILE__ );
define( 'SFB_CT_PATH', plugin_dir_path( __FILE__ ) );
define( 'SFB_CT_URL', plugin_dir_url( __FILE__ ) );
define( 'SFB_CT_BLOCKS_PATH', SFB_CT_PATH . 'build/' );
define( 'SFB_CT_ASSETS_URL', SFB_CT_URL . 'assets/' );

// Autoloader for plugin classes.
spl_autoload_register(
	function ( $class ) {
		$prefix   = 'SFB\\ComparisonTables\\';
		$base_dir = SFB_CT_PATH . 'includes/';

		$len = strlen( $prefix );
		if ( strncmp( $prefix, $class, $len ) !== 0 ) {
			return;
		}

		$relative_class = substr( $class, $len );
		$file           = $base_dir . 'class-' . strtolower( str_replace( '_', '-', $relative_class ) ) . '.php';

		if ( file_exists( $file ) ) {
			require $file;
		}
	}
);

// Initialize the plugin.
add_action( 'plugins_loaded', __NAMESPACE__ . '\\init' );

/**
 * Initialize the plugin.
 *
 * @return void
 */
function init(): void {
	// Load text domain.
	load_plugin_textdomain(
		'sfb-comparison-tables',
		false,
		dirname( plugin_basename( SFB_CT_FILE ) ) . '/languages'
	);

	// Initialize plugin components.
	CPT::init();
	Meta_Boxes::init();
	Blocks::init();
	Frontend::init();
}

/**
 * Activation hook.
 *
 * @return void
 */
function activate(): void {
	// Register CPT on activation for flush_rewrite_rules.
	CPT::register_post_type();
	flush_rewrite_rules();
}
register_activation_hook( SFB_CT_FILE, __NAMESPACE__ . '\\activate' );

/**
 * Deactivation hook.
 *
 * @return void
 */
function deactivate(): void {
	flush_rewrite_rules();
}
register_deactivation_hook( SFB_CT_FILE, __NAMESPACE__ . '\\deactivate' );
