<?php
/**
 * The sidebar containing the main widget area
 *
 * @package SmallFishBusiness
 */
?>

<aside id="secondary" class="sidebar lg:sticky lg:top-24 space-y-10">
    <?php if ( is_active_sidebar( 'sidebar-main' ) ) : ?>
        <?php dynamic_sidebar( 'sidebar-main' ); ?>
    <?php else : ?>
        <!-- Default content if no widgets assigned -->
        <div class="widget">
            <h3 class="widget-title">
                <?php esc_html_e( 'About', 'smallfishbusiness' ); ?>
            </h3>
            <p class="text-gray-600 text-sm leading-relaxed">
                <?php bloginfo( 'description' ); ?>
            </p>
        </div>

        <div class="widget">
            <h3 class="widget-title">
                <?php esc_html_e( 'Categories', 'smallfishbusiness' ); ?>
            </h3>
            <ul class="space-y-2 text-sm">
                <?php
                wp_list_categories( [
                    'title_li' => '',
                    'show_count' => true,
                ] );
                ?>
            </ul>
        </div>
    <?php endif; ?>
</aside>
