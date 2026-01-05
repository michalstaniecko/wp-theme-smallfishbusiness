<?php
/**
 * Uninstall hook - cleanup plugin data.
 *
 * @package SFB\ComparisonTables
 */

// Exit if not called by WordPress.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Delete all comparison table posts.
$posts = get_posts(
	array(
		'post_type'      => 'sfb_comparison_table',
		'posts_per_page' => -1,
		'post_status'    => 'any',
		'fields'         => 'ids',
	)
);

foreach ( $posts as $post_id ) {
	wp_delete_post( $post_id, true );
}

// Delete any orphaned post meta.
global $wpdb;
$wpdb->query( "DELETE FROM {$wpdb->postmeta} WHERE meta_key LIKE '_sfb_ct_%'" );
