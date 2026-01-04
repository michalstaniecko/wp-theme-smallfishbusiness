<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 *
 * @package SmallFishBusiness
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container-fluid py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-3">
                <?php if ( have_posts() ) : ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-12">
                        <?php
                        while ( have_posts() ) :
                            the_post();
                            get_template_part( 'template-parts/content/article-card' );
                        endwhile;
                        ?>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-12">
                        <?php
                        the_posts_pagination( [
                            'mid_size'  => 2,
                            'prev_text' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg><span class="sr-only">' . esc_html__( 'Previous', 'smallfishbusiness' ) . '</span>',
                            'next_text' => '<span class="sr-only">' . esc_html__( 'Next', 'smallfishbusiness' ) . '</span><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>',
                        ] );
                        ?>
                    </div>
                <?php else : ?>
                    <div class="text-center py-12">
                        <p class="text-gray-600"><?php esc_html_e( 'No posts found.', 'smallfishbusiness' ); ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
</main>

<?php
get_footer();
