<?php
/**
 * Small Fish Business Theme Functions
 *
 * @package SmallFishBusiness
 */

// Theme setup
require_once get_template_directory() . '/inc/theme-setup.php';

// Asset enqueuing (Vite integration)
require_once get_template_directory() . '/inc/enqueue.php';

// Gutenberg configuration
require_once get_template_directory() . '/inc/gutenberg.php';

// Block patterns
require_once get_template_directory() . '/inc/block-patterns.php';

// Helper functions
require_once get_template_directory() . '/inc/helpers.php';

// Custom widgets
require_once get_template_directory() . '/inc/widgets.php';

// Performance optimizations
require_once get_template_directory() . '/inc/performance.php';

// Navigation Walker classes
require_once get_template_directory() . '/inc/class-nav-walker.php';
require_once get_template_directory() . '/inc/class-nav-walker-mobile.php';
