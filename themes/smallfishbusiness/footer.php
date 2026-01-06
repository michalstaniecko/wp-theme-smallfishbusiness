<?php
/**
 * The footer for our theme
 *
 * @package SmallFishBusiness
 */
?>

    </div><!-- #content -->

    <footer id="colophon" class="site-footer mt-auto">
        <!-- Footer Widgets -->
        <div class="container-fluid py-14 lg:py-16">
            <div class="footer-grid">
                <!-- Column 1: Brand -->
                <div>
                    <div class="footer-brand">
                        <?php bloginfo( 'name' ); ?>
                    </div>
                    <p class="footer-description mb-6">
                        <?php bloginfo( 'description' ); ?>
                    </p>
                    <!-- Social Links (optional) -->
                    <div class="flex items-center gap-3">
                        <a href="#" class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-surface-800 text-surface-400 hover:bg-primary-600 hover:text-white transition-all" aria-label="Twitter">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="#" class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-surface-800 text-surface-400 hover:bg-primary-600 hover:text-white transition-all" aria-label="LinkedIn">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        </a>
                        <a href="#" class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-surface-800 text-surface-400 hover:bg-primary-600 hover:text-white transition-all" aria-label="RSS Feed">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M6.18 15.64a2.18 2.18 0 0 1 2.18 2.18C8.36 19 7.38 20 6.18 20C5 20 4 19 4 17.82a2.18 2.18 0 0 1 2.18-2.18M4 4.44A15.56 15.56 0 0 1 19.56 20h-2.83A12.73 12.73 0 0 0 4 7.27V4.44m0 5.66a9.9 9.9 0 0 1 9.9 9.9h-2.83A7.07 7.07 0 0 0 4 12.93V10.1z"/></svg>
                        </a>
                    </div>
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
            <div class="border-t border-surface-800">
                <div class="container-fluid py-5">
                    <nav class="footer-nav" aria-label="<?php esc_attr_e( 'Footer navigation', 'smallfishbusiness' ); ?>">
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
        <div class="border-t border-surface-800">
            <div class="container-fluid py-6">
                <p class="footer-copyright">
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
