<?php
/**
 * Block Patterns
 * Custom block patterns for the Small Fish Business theme
 *
 * @package SmallFishBusiness
 */

/**
 * Register custom block pattern category and patterns.
 */
function sfb_register_block_patterns() {
    // Register pattern category
    register_block_pattern_category( 'smallfishbusiness', [
        'label' => __( 'Small Fish Business', 'smallfishbusiness' ),
    ] );

    // Call to Action Pattern
    register_block_pattern( 'smallfishbusiness/cta-box', [
        'title'       => __( 'Call to Action Box', 'smallfishbusiness' ),
        'description' => __( 'A highlighted box with title, text and button', 'smallfishbusiness' ),
        'categories'  => [ 'smallfishbusiness' ],
        'content'     => '
<!-- wp:group {"className":"is-style-highlight-box"} -->
<div class="wp-block-group is-style-highlight-box">
    <!-- wp:heading {"level":3} -->
    <h3 class="wp-block-heading">Ready to get started?</h3>
    <!-- /wp:heading -->

    <!-- wp:paragraph -->
    <p>Join thousands of small business owners who are already growing with our tips.</p>
    <!-- /wp:paragraph -->

    <!-- wp:buttons -->
    <div class="wp-block-buttons">
        <!-- wp:button -->
        <div class="wp-block-button"><a class="wp-block-button__link wp-element-button">Learn More</a></div>
        <!-- /wp:button -->
    </div>
    <!-- /wp:buttons -->
</div>
<!-- /wp:group -->
        ',
    ] );

    // Key Takeaways Pattern
    register_block_pattern( 'smallfishbusiness/key-takeaways', [
        'title'       => __( 'Key Takeaways', 'smallfishbusiness' ),
        'description' => __( 'A summary box for article key points', 'smallfishbusiness' ),
        'categories'  => [ 'smallfishbusiness' ],
        'content'     => '
<!-- wp:group {"className":"is-style-card"} -->
<div class="wp-block-group is-style-card">
    <!-- wp:heading {"level":3} -->
    <h3 class="wp-block-heading">Key Takeaways</h3>
    <!-- /wp:heading -->

    <!-- wp:list {"className":"is-style-checklist"} -->
    <ul class="is-style-checklist">
        <li>First key point from this article</li>
        <li>Second important takeaway</li>
        <li>Third thing to remember</li>
    </ul>
    <!-- /wp:list -->
</div>
<!-- /wp:group -->
        ',
    ] );

    // Two Column Comparison (Pros/Cons)
    register_block_pattern( 'smallfishbusiness/comparison', [
        'title'       => __( 'Comparison Columns', 'smallfishbusiness' ),
        'description' => __( 'Two columns for pros/cons or comparison', 'smallfishbusiness' ),
        'categories'  => [ 'smallfishbusiness' ],
        'content'     => '
<!-- wp:columns -->
<div class="wp-block-columns">
    <!-- wp:column {"className":"is-style-card"} -->
    <div class="wp-block-column is-style-card">
        <!-- wp:heading {"level":4} -->
        <h4 class="wp-block-heading">Pros</h4>
        <!-- /wp:heading -->

        <!-- wp:list -->
        <ul>
            <li>Advantage one</li>
            <li>Advantage two</li>
            <li>Advantage three</li>
        </ul>
        <!-- /wp:list -->
    </div>
    <!-- /wp:column -->

    <!-- wp:column {"className":"is-style-card"} -->
    <div class="wp-block-column is-style-card">
        <!-- wp:heading {"level":4} -->
        <h4 class="wp-block-heading">Cons</h4>
        <!-- /wp:heading -->

        <!-- wp:list -->
        <ul>
            <li>Disadvantage one</li>
            <li>Disadvantage two</li>
            <li>Disadvantage three</li>
        </ul>
        <!-- /wp:list -->
    </div>
    <!-- /wp:column -->
</div>
<!-- /wp:columns -->
        ',
    ] );

    // Featured Quote Pattern
    register_block_pattern( 'smallfishbusiness/featured-quote', [
        'title'       => __( 'Featured Quote', 'smallfishbusiness' ),
        'description' => __( 'A large, prominent quote block', 'smallfishbusiness' ),
        'categories'  => [ 'smallfishbusiness' ],
        'content'     => '
<!-- wp:quote {"className":"is-style-large"} -->
<blockquote class="wp-block-quote is-style-large">
    <p>Success is not final, failure is not fatal: it is the courage to continue that counts.</p>
    <cite>Winston Churchill</cite>
</blockquote>
<!-- /wp:quote -->
        ',
    ] );

    // Info Box Pattern
    register_block_pattern( 'smallfishbusiness/info-box', [
        'title'       => __( 'Info Box', 'smallfishbusiness' ),
        'description' => __( 'A highlighted information box', 'smallfishbusiness' ),
        'categories'  => [ 'smallfishbusiness' ],
        'content'     => '
<!-- wp:group {"className":"is-style-highlight-box"} -->
<div class="wp-block-group is-style-highlight-box">
    <!-- wp:paragraph {"className":"is-style-lead"} -->
    <p class="is-style-lead"><strong>Did you know?</strong> Small businesses make up 99.9% of all US businesses and employ nearly half of all American workers.</p>
    <!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
        ',
    ] );

    // Author Bio Pattern
    register_block_pattern( 'smallfishbusiness/author-bio', [
        'title'       => __( 'Author Bio', 'smallfishbusiness' ),
        'description' => __( 'Author information with image', 'smallfishbusiness' ),
        'categories'  => [ 'smallfishbusiness' ],
        'content'     => '
<!-- wp:group {"className":"is-style-card"} -->
<div class="wp-block-group is-style-card">
    <!-- wp:columns {"verticalAlignment":"center"} -->
    <div class="wp-block-columns are-vertically-aligned-center">
        <!-- wp:column {"width":"80px"} -->
        <div class="wp-block-column" style="flex-basis:80px">
            <!-- wp:image {"sizeSlug":"thumbnail","className":"is-style-rounded"} -->
            <figure class="wp-block-image size-thumbnail is-style-rounded"><img src="" alt="Author avatar"/></figure>
            <!-- /wp:image -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column -->
        <div class="wp-block-column">
            <!-- wp:paragraph -->
            <p><strong>About the Author</strong></p>
            <!-- /wp:paragraph -->

            <!-- wp:paragraph {"fontSize":"small"} -->
            <p class="has-small-font-size">A brief description of the author and their expertise in small business topics.</p>
            <!-- /wp:paragraph -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->
</div>
<!-- /wp:group -->
        ',
    ] );

    // Newsletter Signup Pattern
    register_block_pattern( 'smallfishbusiness/newsletter-signup', [
        'title'       => __( 'Newsletter Signup', 'smallfishbusiness' ),
        'description' => __( 'A newsletter subscription call to action', 'smallfishbusiness' ),
        'categories'  => [ 'smallfishbusiness' ],
        'content'     => '
<!-- wp:group {"className":"is-style-card","style":{"spacing":{"padding":{"top":"2rem","bottom":"2rem"}}}} -->
<div class="wp-block-group is-style-card" style="padding-top:2rem;padding-bottom:2rem">
    <!-- wp:heading {"textAlign":"center","level":3} -->
    <h3 class="wp-block-heading has-text-align-center">Stay Updated</h3>
    <!-- /wp:heading -->

    <!-- wp:paragraph {"align":"center"} -->
    <p class="has-text-align-center">Get the latest small business tips and strategies delivered to your inbox.</p>
    <!-- /wp:paragraph -->

    <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
    <div class="wp-block-buttons">
        <!-- wp:button -->
        <div class="wp-block-button"><a class="wp-block-button__link wp-element-button">Subscribe Now</a></div>
        <!-- /wp:button -->
    </div>
    <!-- /wp:buttons -->
</div>
<!-- /wp:group -->
        ',
    ] );
}
add_action( 'init', 'sfb_register_block_patterns' );
