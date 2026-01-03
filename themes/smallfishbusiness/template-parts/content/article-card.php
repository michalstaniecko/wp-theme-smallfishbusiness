<?php
/**
 * Article Card Component
 * Used on listing pages (home, category, archive)
 *
 * @package SmallFishBusiness
 */
?>
<article <?php post_class( 'bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow' ); ?>>
    <div class="md:flex">
        <!-- Thumbnail -->
        <?php if ( has_post_thumbnail() ) : ?>
            <div class="md:w-1/3 md:flex-shrink-0">
                <a href="<?php the_permalink(); ?>" class="block aspect-video md:aspect-[4/3] overflow-hidden">
                    <?php the_post_thumbnail( 'medium_large', [
                        'class' => 'w-full h-full object-cover hover:scale-105 transition-transform duration-300',
                        'alt'   => get_the_title(),
                    ] ); ?>
                </a>
            </div>
        <?php endif; ?>

        <!-- Content -->
        <div class="p-6 <?php echo has_post_thumbnail() ? 'md:w-2/3' : ''; ?>">
            <!-- Category Badge -->
            <?php
            $categories = get_the_category();
            if ( ! empty( $categories ) ) :
            ?>
                <a href="<?php echo esc_url( get_category_link( $categories[0]->term_id ) ); ?>"
                   class="inline-block text-xs font-semibold text-primary-600 uppercase tracking-wide mb-2 hover:text-primary-700 transition-colors">
                    <?php echo esc_html( $categories[0]->name ); ?>
                </a>
            <?php endif; ?>

            <!-- Title -->
            <h2 class="text-xl font-semibold mb-2">
                <a href="<?php the_permalink(); ?>" class="text-gray-900 hover:text-primary-600 transition-colors">
                    <?php the_title(); ?>
                </a>
            </h2>

            <!-- Meta -->
            <div class="flex items-center text-sm text-gray-500 mb-3">
                <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                    <?php echo esc_html( get_the_date() ); ?>
                </time>
                <span class="mx-2" aria-hidden="true">&bull;</span>
                <span><?php echo esc_html( get_the_author() ); ?></span>
            </div>

            <!-- Excerpt -->
            <p class="text-gray-600 mb-4 line-clamp-3">
                <?php echo esc_html( wp_trim_words( get_the_excerpt(), 25, '...' ) ); ?>
            </p>

            <!-- Read More -->
            <a href="<?php the_permalink(); ?>"
               class="inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-700 transition-colors">
                <?php esc_html_e( 'Read more', 'smallfishbusiness' ); ?>
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</article>
