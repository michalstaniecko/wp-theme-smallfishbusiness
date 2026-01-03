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
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <?php if ( have_posts() ) : ?>
                    <div class="space-y-8">
                        <?php
                        while ( have_posts() ) :
                            the_post();
                            ?>
                            <article id="post-<?php the_ID(); ?>" <?php post_class( 'bg-white rounded-lg shadow-sm overflow-hidden' ); ?>>
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <a href="<?php the_permalink(); ?>" class="block">
                                        <?php the_post_thumbnail( 'large', [ 'class' => 'w-full h-48 md:h-64 object-cover' ] ); ?>
                                    </a>
                                <?php endif; ?>

                                <div class="p-6">
                                    <!-- Category Badge -->
                                    <?php
                                    $categories = get_the_category();
                                    if ( ! empty( $categories ) ) :
                                        ?>
                                        <a href="<?php echo esc_url( get_category_link( $categories[0]->term_id ) ); ?>"
                                           class="inline-block text-xs font-medium text-primary-600 bg-primary-50 px-2 py-1 rounded mb-3 hover:bg-primary-100 transition-colors">
                                            <?php echo esc_html( $categories[0]->name ); ?>
                                        </a>
                                    <?php endif; ?>

                                    <header class="entry-header mb-3">
                                        <?php the_title( '<h2 class="entry-title text-xl md:text-2xl font-semibold text-gray-900 leading-tight"><a href="' . esc_url( get_permalink() ) . '" class="hover:text-primary-600 transition-colors">', '</a></h2>' ); ?>
                                    </header>

                                    <!-- Meta -->
                                    <div class="entry-meta text-sm text-gray-500 mb-4 flex items-center gap-4">
                                        <span class="posted-on">
                                            <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                                                <?php echo esc_html( get_the_date() ); ?>
                                            </time>
                                        </span>
                                        <span class="byline">
                                            <?php
                                            printf(
                                                /* translators: %s: author name */
                                                esc_html__( 'by %s', 'smallfishbusiness' ),
                                                '<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" class="hover:text-gray-700">' . esc_html( get_the_author() ) . '</a>'
                                            );
                                            ?>
                                        </span>
                                    </div>

                                    <div class="entry-content text-gray-600 leading-relaxed">
                                        <?php the_excerpt(); ?>
                                    </div>

                                    <a href="<?php the_permalink(); ?>"
                                       class="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium mt-4 transition-colors">
                                        <?php esc_html_e( 'Read more', 'smallfishbusiness' ); ?>
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
                            </article>
                            <?php
                        endwhile;
                        ?>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        <?php
                        the_posts_pagination( [
                            'mid_size'  => 2,
                            'prev_text' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg><span class="sr-only">' . esc_html__( 'Previous', 'smallfishbusiness' ) . '</span>',
                            'next_text' => '<span class="sr-only">' . esc_html__( 'Next', 'smallfishbusiness' ) . '</span><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>',
                        ] );
                        ?>
                    </div>
                <?php else : ?>
                    <div class="bg-white rounded-lg shadow-sm p-8 text-center">
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
