<?php
/**
 * Pagination Component
 *
 * @package SmallFishBusiness
 */

$pagination = paginate_links( [
    'type'      => 'array',
    'prev_text' => '<span class="sr-only">' . __( 'Previous', 'smallfishbusiness' ) . '</span><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>',
    'next_text' => '<span class="sr-only">' . __( 'Next', 'smallfishbusiness' ) . '</span><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>',
] );

if ( $pagination ) :
?>
<nav aria-label="<?php esc_attr_e( 'Pagination', 'smallfishbusiness' ); ?>" class="mt-12">
    <ul class="flex items-center justify-center gap-2">
        <?php foreach ( $pagination as $link ) :
            // Base classes for all pagination items
            $base_classes = 'inline-flex items-center justify-center min-w-[40px] h-10 px-3 rounded-lg text-sm font-medium transition-colors';

            // Determine if this is the current page
            $is_current = strpos( $link, 'current' ) !== false;

            // Add appropriate styling classes
            if ( $is_current ) {
                $style_classes = 'bg-primary-600 text-white';
            } else {
                $style_classes = 'bg-gray-100 text-gray-700 hover:bg-gray-200';
            }

            // Replace the default WordPress classes
            $link = preg_replace(
                '/class=["\']([^"\']*page-numbers[^"\']*)["\']/',
                'class="' . $base_classes . ' ' . $style_classes . '"',
                $link
            );
        ?>
            <li>
                <?php echo $link; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
<?php endif; ?>
