<?php
/**
 * Custom Widgets for Small Fish Business
 *
 * @package SmallFishBusiness
 */

/**
 * About Widget
 */
class SFB_About_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'sfb_about',
            __('SFB: About', 'smallfishbusiness'),
            ['description' => __('Display about section with optional image', 'smallfishbusiness')]
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        ?>
        <div>
            <?php if (!empty($instance['image'])) : ?>
                <img src="<?php echo esc_url($instance['image']); ?>"
                     alt="<?php echo esc_attr($instance['title'] ?? ''); ?>"
                     class="w-full h-32 object-cover rounded-lg mb-4">
            <?php endif; ?>

            <?php if (!empty($instance['title'])) : ?>
                <?php echo $args['before_title'] . esc_html($instance['title']) . $args['after_title']; ?>
            <?php endif; ?>

            <?php if (!empty($instance['text'])) : ?>
                <p class="text-gray-600 text-sm mb-4">
                    <?php echo esc_html($instance['text']); ?>
                </p>
            <?php endif; ?>

            <?php if (!empty($instance['link_url']) && !empty($instance['link_text'])) : ?>
                <a href="<?php echo esc_url($instance['link_url']); ?>"
                   class="inline-flex items-center text-sm text-primary-600 hover:text-primary-700">
                    <?php echo esc_html($instance['link_text']); ?>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            <?php endif; ?>
        </div>
        <?php
        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = $instance['title'] ?? '';
        $text = $instance['text'] ?? '';
        $image = $instance['image'] ?? '';
        $link_text = $instance['link_text'] ?? '';
        $link_url = $instance['link_url'] ?? '';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'smallfishbusiness'); ?></label>
            <input class="widefat" type="text"
                   id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>"
                   value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Text:', 'smallfishbusiness'); ?></label>
            <textarea class="widefat" rows="4"
                      id="<?php echo $this->get_field_id('text'); ?>"
                      name="<?php echo $this->get_field_name('text'); ?>"><?php echo esc_textarea($text); ?></textarea>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('image'); ?>"><?php _e('Image URL:', 'smallfishbusiness'); ?></label>
            <input class="widefat" type="url"
                   id="<?php echo $this->get_field_id('image'); ?>"
                   name="<?php echo $this->get_field_name('image'); ?>"
                   value="<?php echo esc_url($image); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('link_text'); ?>"><?php _e('Link Text:', 'smallfishbusiness'); ?></label>
            <input class="widefat" type="text"
                   id="<?php echo $this->get_field_id('link_text'); ?>"
                   name="<?php echo $this->get_field_name('link_text'); ?>"
                   value="<?php echo esc_attr($link_text); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('link_url'); ?>"><?php _e('Link URL:', 'smallfishbusiness'); ?></label>
            <input class="widefat" type="url"
                   id="<?php echo $this->get_field_id('link_url'); ?>"
                   name="<?php echo $this->get_field_name('link_url'); ?>"
                   value="<?php echo esc_url($link_url); ?>">
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = [];
        $instance['title'] = sanitize_text_field($new_instance['title'] ?? '');
        $instance['text'] = sanitize_textarea_field($new_instance['text'] ?? '');
        $instance['image'] = esc_url_raw($new_instance['image'] ?? '');
        $instance['link_text'] = sanitize_text_field($new_instance['link_text'] ?? '');
        $instance['link_url'] = esc_url_raw($new_instance['link_url'] ?? '');
        return $instance;
    }
}

/**
 * Popular Posts Widget
 */
class SFB_Popular_Posts_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'sfb_popular_posts',
            __('SFB: Popular Posts', 'smallfishbusiness'),
            ['description' => __('Display popular posts by views or comments', 'smallfishbusiness')]
        );
    }

    public function widget($args, $instance) {
        $title = $instance['title'] ?? __('Popular Posts', 'smallfishbusiness');
        $count = $instance['count'] ?? 5;
        $order_by = $instance['order_by'] ?? 'comment_count';

        $query_args = [
            'post_type'      => 'post',
            'posts_per_page' => $count,
            'orderby'        => $order_by,
            'order'          => 'DESC',
        ];

        $posts = new WP_Query($query_args);

        echo $args['before_widget'];
        ?>
        <div>
            <?php echo $args['before_title'] . esc_html($title) . $args['after_title']; ?>

            <?php if ($posts->have_posts()) : ?>
                <ul class="space-y-4">
                    <?php while ($posts->have_posts()) : $posts->the_post(); ?>
                        <li class="flex space-x-3">
                            <?php if (has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>" class="flex-shrink-0">
                                    <?php the_post_thumbnail('thumbnail', [
                                        'class' => 'w-16 h-16 object-cover rounded',
                                    ]); ?>
                                </a>
                            <?php endif; ?>

                            <div class="flex-1 min-w-0">
                                <a href="<?php the_permalink(); ?>"
                                   class="text-sm font-medium text-gray-900 hover:text-primary-600 line-clamp-2">
                                    <?php the_title(); ?>
                                </a>
                                <p class="text-xs text-gray-500 mt-1">
                                    <?php echo get_the_date(); ?>
                                </p>
                            </div>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else : ?>
                <p class="text-gray-500 text-sm"><?php _e('No posts found.', 'smallfishbusiness'); ?></p>
            <?php endif; ?>

            <?php wp_reset_postdata(); ?>
        </div>
        <?php
        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = $instance['title'] ?? __('Popular Posts', 'smallfishbusiness');
        $count = $instance['count'] ?? 5;
        $order_by = $instance['order_by'] ?? 'comment_count';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'smallfishbusiness'); ?></label>
            <input class="widefat" type="text"
                   id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>"
                   value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Number of posts:', 'smallfishbusiness'); ?></label>
            <input class="tiny-text" type="number" min="1" max="10"
                   id="<?php echo $this->get_field_id('count'); ?>"
                   name="<?php echo $this->get_field_name('count'); ?>"
                   value="<?php echo esc_attr($count); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('order_by'); ?>"><?php _e('Order by:', 'smallfishbusiness'); ?></label>
            <select class="widefat"
                    id="<?php echo $this->get_field_id('order_by'); ?>"
                    name="<?php echo $this->get_field_name('order_by'); ?>">
                <option value="comment_count" <?php selected($order_by, 'comment_count'); ?>>
                    <?php _e('Comment count', 'smallfishbusiness'); ?>
                </option>
                <option value="date" <?php selected($order_by, 'date'); ?>>
                    <?php _e('Date', 'smallfishbusiness'); ?>
                </option>
                <option value="rand" <?php selected($order_by, 'rand'); ?>>
                    <?php _e('Random', 'smallfishbusiness'); ?>
                </option>
            </select>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = [];
        $instance['title'] = sanitize_text_field($new_instance['title'] ?? '');
        $instance['count'] = absint($new_instance['count'] ?? 5);
        $instance['order_by'] = sanitize_text_field($new_instance['order_by'] ?? 'comment_count');
        return $instance;
    }
}

/**
 * Categories Widget (styled)
 */
class SFB_Categories_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'sfb_categories',
            __('SFB: Categories', 'smallfishbusiness'),
            ['description' => __('Display categories with post counts', 'smallfishbusiness')]
        );
    }

    public function widget($args, $instance) {
        $title = $instance['title'] ?? __('Categories', 'smallfishbusiness');
        $show_count = !empty($instance['show_count']);
        $hide_empty = !empty($instance['hide_empty']);

        $categories = get_categories([
            'orderby'    => 'name',
            'order'      => 'ASC',
            'hide_empty' => $hide_empty,
        ]);

        echo $args['before_widget'];
        ?>
        <div>
            <?php echo $args['before_title'] . esc_html($title) . $args['after_title']; ?>

            <?php if (!empty($categories)) : ?>
                <ul class="space-y-2">
                    <?php foreach ($categories as $category) : ?>
                        <li>
                            <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>"
                               class="flex items-center justify-between text-gray-700 hover:text-primary-600 py-1">
                                <span><?php echo esc_html($category->name); ?></span>
                                <?php if ($show_count) : ?>
                                    <span class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full">
                                        <?php echo esc_html($category->count); ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else : ?>
                <p class="text-gray-500 text-sm"><?php _e('No categories found.', 'smallfishbusiness'); ?></p>
            <?php endif; ?>
        </div>
        <?php
        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = $instance['title'] ?? __('Categories', 'smallfishbusiness');
        $show_count = !empty($instance['show_count']);
        $hide_empty = isset($instance['hide_empty']) ? $instance['hide_empty'] : true;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'smallfishbusiness'); ?></label>
            <input class="widefat" type="text"
                   id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>"
                   value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <input type="checkbox"
                   id="<?php echo $this->get_field_id('show_count'); ?>"
                   name="<?php echo $this->get_field_name('show_count'); ?>"
                   <?php checked($show_count); ?>>
            <label for="<?php echo $this->get_field_id('show_count'); ?>">
                <?php _e('Show post counts', 'smallfishbusiness'); ?>
            </label>
        </p>
        <p>
            <input type="checkbox"
                   id="<?php echo $this->get_field_id('hide_empty'); ?>"
                   name="<?php echo $this->get_field_name('hide_empty'); ?>"
                   <?php checked($hide_empty); ?>>
            <label for="<?php echo $this->get_field_id('hide_empty'); ?>">
                <?php _e('Hide empty categories', 'smallfishbusiness'); ?>
            </label>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = [];
        $instance['title'] = sanitize_text_field($new_instance['title'] ?? '');
        $instance['show_count'] = !empty($new_instance['show_count']);
        $instance['hide_empty'] = !empty($new_instance['hide_empty']);
        return $instance;
    }
}

/**
 * Register widgets
 */
function sfb_register_widgets() {
    register_widget('SFB_About_Widget');
    register_widget('SFB_Popular_Posts_Widget');
    register_widget('SFB_Categories_Widget');
}
add_action('widgets_init', 'sfb_register_widgets');
