<?php
/**
 * The footer for our theme
 *
 * @package SmallFishBusiness
 */
?>

    </div><!-- #content -->

    <footer id="colophon" class="site-footer bg-gray-900 text-gray-300 mt-auto">
        <!-- Footer Widgets -->
        <div class="container-fluid py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Column 1: About -->
                <div>
                    <h4 class="text-white font-semibold mb-4 text-lg">
                        <?php bloginfo( 'name' ); ?>
                    </h4>
                    <p class="text-sm text-gray-400 leading-relaxed">
                        <?php bloginfo( 'description' ); ?>
                    </p>
                </div>

                <!-- Column 2-4: Widget Areas -->
                <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
                    <div class="footer-widget-area">
                        <?php dynamic_sidebar( 'footer-1' ); ?>
                    </div>
                <?php endif; ?>

                <?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
                    <div class="footer-widget-area">
                        <?php dynamic_sidebar( 'footer-2' ); ?>
                    </div>
                <?php endif; ?>

                <?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
                    <div class="footer-widget-area">
                        <?php dynamic_sidebar( 'footer-3' ); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Footer Navigation -->
        <?php if ( has_nav_menu( 'footer' ) ) : ?>
            <div class="border-t border-gray-800">
                <div class="container-fluid py-4">
                    <nav class="footer-navigation flex flex-wrap justify-center gap-6">
                        <?php
                        wp_nav_menu( [
                            'theme_location' => 'footer',
                            'menu_id'        => 'footer-menu',
                            'container'      => false,
                            'menu_class'     => 'flex flex-wrap justify-center gap-6',
                            'fallback_cb'    => false,
                            'depth'          => 1,
                        ] );
                        ?>
                    </nav>
                </div>
            </div>
        <?php endif; ?>

        <!-- Copyright -->
        <div class="border-t border-gray-800">
            <div class="container-fluid py-6">
                <p class="text-sm text-gray-500 text-center">
                    &copy; <?php echo esc_html( date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>.
                    <?php esc_html_e( 'All rights reserved.', 'smallfishbusiness' ); ?>
                </p>
            </div>
        </div>
    </footer>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
