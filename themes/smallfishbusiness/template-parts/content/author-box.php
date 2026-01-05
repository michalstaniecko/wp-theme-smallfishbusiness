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

<div class="author-box">
    <div class="flex items-start gap-5">
        <!-- Avatar -->
        <div class="author-box__avatar">
            <?php echo get_avatar( $author_id, 72, '', $author_name, [
                'class' => 'rounded-full ring-2 ring-white shadow-md',
            ] ); ?>
        </div>

        <!-- Info -->
        <div class="flex-1 min-w-0">
            <p class="author-box__label">
                <?php esc_html_e( 'Written by', 'smallfishbusiness' ); ?>
            </p>

            <h3 class="author-box__name">
                <a href="<?php echo esc_url( $author_url ); ?>">
                    <?php echo esc_html( $author_name ); ?>
                </a>
            </h3>

            <?php if ( $author_bio ) : ?>
                <p class="author-box__bio">
                    <?php echo esc_html( $author_bio ); ?>
                </p>
            <?php endif; ?>

            <a href="<?php echo esc_url( $author_url ); ?>" class="author-box__link">
                <?php esc_html_e( 'View all posts', 'smallfishbusiness' ); ?>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</div>
