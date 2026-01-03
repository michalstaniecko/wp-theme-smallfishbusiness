<?php
/**
 * Author Box Component
 * Displayed at the end of single posts
 *
 * @package SmallFishBusiness
 */

$author_id   = get_the_author_meta( 'ID' );
$author_name = get_the_author();
$author_bio  = get_the_author_meta( 'description' );
$author_url  = get_author_posts_url( $author_id );
?>

<div class="bg-gray-50 rounded-lg p-6 mt-8">
    <div class="flex items-start gap-4">
        <!-- Avatar -->
        <div class="flex-shrink-0">
            <?php echo get_avatar( $author_id, 80, '', $author_name, [
                'class' => 'rounded-full',
            ] ); ?>
        </div>

        <!-- Info -->
        <div class="flex-1 min-w-0">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">
                <?php esc_html_e( 'Written by', 'smallfishbusiness' ); ?>
            </p>

            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                <a href="<?php echo esc_url( $author_url ); ?>" class="hover:text-primary-600 transition-colors">
                    <?php echo esc_html( $author_name ); ?>
                </a>
            </h3>

            <?php if ( $author_bio ) : ?>
                <p class="text-gray-600 text-sm mb-3 leading-relaxed">
                    <?php echo esc_html( $author_bio ); ?>
                </p>
            <?php endif; ?>

            <a href="<?php echo esc_url( $author_url ); ?>"
               class="inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-700 transition-colors">
                <?php esc_html_e( 'View all posts', 'smallfishbusiness' ); ?>
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</div>
