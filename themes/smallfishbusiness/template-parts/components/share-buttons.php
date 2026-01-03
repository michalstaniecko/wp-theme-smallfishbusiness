<?php
/**
 * Share Buttons Component
 *
 * @package SmallFishBusiness
 */

$share_url   = rawurlencode( get_permalink() );
$share_title = rawurlencode( get_the_title() );
?>

<div class="flex items-center gap-4 mt-6 pt-6 border-t border-gray-200">
    <span class="text-sm text-gray-500"><?php esc_html_e( 'Share:', 'smallfishbusiness' ); ?></span>

    <!-- Twitter/X -->
    <a href="https://twitter.com/intent/tweet?url=<?php echo esc_attr( $share_url ); ?>&text=<?php echo esc_attr( $share_title ); ?>"
       target="_blank"
       rel="noopener noreferrer"
       class="text-gray-400 hover:text-gray-600 transition-colors"
       aria-label="<?php esc_attr_e( 'Share on X (Twitter)', 'smallfishbusiness' ); ?>">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
        </svg>
    </a>

    <!-- LinkedIn -->
    <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo esc_attr( $share_url ); ?>"
       target="_blank"
       rel="noopener noreferrer"
       class="text-gray-400 hover:text-gray-600 transition-colors"
       aria-label="<?php esc_attr_e( 'Share on LinkedIn', 'smallfishbusiness' ); ?>">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
        </svg>
    </a>

    <!-- Facebook -->
    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_attr( $share_url ); ?>"
       target="_blank"
       rel="noopener noreferrer"
       class="text-gray-400 hover:text-gray-600 transition-colors"
       aria-label="<?php esc_attr_e( 'Share on Facebook', 'smallfishbusiness' ); ?>">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
        </svg>
    </a>

    <!-- Copy Link -->
    <button type="button"
            class="text-gray-400 hover:text-gray-600 transition-colors"
            aria-label="<?php esc_attr_e( 'Copy link', 'smallfishbusiness' ); ?>"
            data-share-copy="<?php echo esc_attr( get_permalink() ); ?>">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
        </svg>
    </button>
</div>
