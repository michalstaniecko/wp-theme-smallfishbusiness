<?php
/**
 * Mobile Navigation
 *
 * @package SmallFishBusiness
 */
?>
<div id="mobile-menu" class="lg:hidden hidden bg-white border-t border-gray-100">
    <nav class="container mx-auto px-4 py-4">
        <?php
        wp_nav_menu( [
            'theme_location' => 'primary',
            'container'      => false,
            'menu_class'     => 'mobile-nav-menu',
            'fallback_cb'    => false,
            'depth'          => 1,
        ] );
        ?>
    </nav>
</div>
