<?php
/**
 * The sidebar containing the main widget area
 *
 * @package SmallFishBusiness
 */
?>

<aside id="secondary" class="sidebar">
    <?php if ( is_active_sidebar( 'sidebar-main' ) ) : ?>
        <?php dynamic_sidebar( 'sidebar-main' ); ?>
    <?php else : ?>
        <!-- Default content if no widgets assigned -->
        <div class="widget">
            <h3 class="widget-title">
                <?php esc_html_e( 'About', 'smallfishbusiness' ); ?>
            </h3>
            <p class="text-surface-600 text-sm leading-relaxed">
                <?php bloginfo( 'description' ); ?>
            </p>
        </div>

        <div class="widget">
            <h3 class="widget-title">
                <?php esc_html_e( 'Categories', 'smallfishbusiness' ); ?>
            </h3>
            <ul class="space-y-2.5">
                <?php
                wp_list_categories( [
                    'title_li'   => '',
                    'show_count' => true,
                ] );
                ?>
            </ul>
        </div>

        <div class="widget">
            <h3 class="widget-title">
                <?php esc_html_e( 'Recent Posts', 'smallfishbusiness' ); ?>
            </h3>
            <ul class="space-y-3">
                <?php
                $recent_posts = wp_get_recent_posts( [
                    'numberposts' => 5,
                    'post_status' => 'publish',
                ] );
                foreach ( $recent_posts as $post ) :
                ?>
                    <li>
                        <a href="<?php echo esc_url( get_permalink( $post['ID'] ) ); ?>"
                           class="block text-surface-700 hover:text-primary-600 transition-colors leading-snug">
                            <?php echo esc_html( $post['post_title'] ); ?>
                        </a>
                        <time class="text-xs text-surface-400" datetime="<?php echo esc_attr( get_the_date( 'c', $post['ID'] ) ); ?>">
                            <?php echo esc_html( get_the_date( '', $post['ID'] ) ); ?>
                        </time>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</aside>
