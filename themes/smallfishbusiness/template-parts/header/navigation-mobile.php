<?php
/**
 * Mobile Navigation
 *
 * @package SmallFishBusiness
 */
?>
<div id="mobile-menu" class="lg:hidden hidden bg-white border-t border-gray-100">
    <nav class="container mx-auto px-4 py-4" aria-label="<?php esc_attr_e( 'Mobile navigation', 'smallfishbusiness' ); ?>">
        <?php
        wp_nav_menu( [
            'theme_location' => 'primary',
            'container'      => false,
            'menu_class'     => 'mobile-nav-menu',
            'fallback_cb'    => false,
            'depth'          => 3,
            'walker'         => new SFB_Nav_Walker_Mobile(),
            'items_wrap'     => '<ul id="mobile-primary-menu" class="%2$s" role="menu">%3$s</ul>',
        ] );
        ?>
    </nav>
</div>
