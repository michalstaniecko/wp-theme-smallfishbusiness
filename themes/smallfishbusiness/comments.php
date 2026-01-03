<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @package SmallFishBusiness
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password,
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title text-2xl font-bold text-gray-900 mb-6">
			<?php
			$comment_count = get_comments_number();
			printf(
				/* translators: 1: comment count number */
				esc_html( _n( '%1$s Comment', '%1$s Comments', $comment_count, 'smallfishbusiness' ) ),
				number_format_i18n( $comment_count )
			);
			?>
		</h2>

		<?php the_comments_navigation(); ?>

		<ol class="comment-list space-y-6 mb-8">
			<?php
			wp_list_comments(
				[
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 48,
					'callback'    => 'sfb_comment_callback',
				]
			);
			?>
		</ol>

		<?php the_comments_navigation(); ?>

		<?php if ( ! comments_open() ) : ?>
			<p class="no-comments text-gray-500 italic">
				<?php esc_html_e( 'Comments are closed.', 'smallfishbusiness' ); ?>
			</p>
		<?php endif; ?>

	<?php endif; ?>

	<?php
	comment_form(
		[
			'class_container'  => 'comment-respond',
			'class_form'       => 'comment-form space-y-4',
			'title_reply'      => __( 'Leave a Comment', 'smallfishbusiness' ),
			'title_reply_to'   => __( 'Reply to %s', 'smallfishbusiness' ),
			'cancel_reply_link' => __( 'Cancel reply', 'smallfishbusiness' ),
			'label_submit'     => __( 'Post Comment', 'smallfishbusiness' ),
			'submit_button'    => '<button type="submit" name="%1$s" id="%2$s" class="%3$s px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors cursor-pointer">%4$s</button>',
			'submit_field'     => '<p class="form-submit">%1$s %2$s</p>',
			'comment_field'    => '<p class="comment-form-comment"><label for="comment" class="block text-sm font-medium text-gray-700 mb-2">' . esc_html__( 'Comment', 'smallfishbusiness' ) . ' <span class="required text-red-500">*</span></label><textarea id="comment" name="comment" cols="45" rows="6" maxlength="65525" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"></textarea></p>',
		]
	);
	?>

</div>
