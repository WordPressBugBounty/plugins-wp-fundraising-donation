<?php
if ( isset( $request['nonce'] ) && wp_verify_nonce( $request['nonce'], 'wfp-update' ) ) {
	$paged = isset( $_GET['review_page'] ) ? sanitize_text_field( wp_unslash( $_GET['review_page'] ) ) : 1;
} else {
	$paged = 1;
}
$postId = empty( $post->ID ) ? get_the_ID() : $post->ID;

$postId = empty( $formId ) ? $postId : $formId; // overriding for short code...

$args = array(
	'post_type'      => 'wfp-update',
	'post_parent'    => $postId,
	'post_status'    => 'publish',

	'orderby'        => array(
		'post_date' => 'DESC',
	),
	'posts_per_page' => 15,
	'paged'          => $paged,
);

$the_review = new \WP_Query( $args );

$postCount = 1;

if ( $the_review->have_posts() ) { ?>

	<div class="wfp-details-update-tab">
		<?php
		while ( $the_review->have_posts() ) {
			$the_review->the_post();
			$id           = $post->ID;
			$post_date    = $post->post_date;
			$post_content = $post->post_content;

			?>
			<div class="wfp-details-update-tab--list">
				<h3 class="wfp-details-update-tab--list__title"> <?php echo esc_html( gmdate( ' d F Y', strtotime( $post_date ) ) ); ?></h3>
				<p class="wfp-details-update-tab--list__content"><?php echo wp_kses( $post_content, \WfpFundraising\Utilities\Utils::get_kses_array() ); ?></p>
			</div>
			<?php
			$postCount ++;
		}

		wp_reset_postdata();
		?>

	</div>
	<?php

}

$userId = get_current_user_id();
global $post;
$author_id = $post->post_author;

if ( is_user_logged_in() && $userId == $author_id ) {
	?>
	<div class="wfp-submit-updates">
		<div class="wfp-entry-reiv">
			<form class="wfp-user-update" id="wfp_update-<?php esc_attr( $postId ); ?>" method="post">
				<div class="message-update-status"></div>


				<div class="wfp-review-filed wfp-review-message">
					<textarea type="text" rows="4" name="updates_post[details]" class="wfp-input wfp-textarea"
							  placeholder="<?php echo esc_attr__( 'Write Update *', 'wp-fundraising' ); ?>"></textarea>
				</div>

				<div class="wfp-review-submit">
					<button type="submit" class="wfp-form-button xs-btn xs-btn-primary xs-btn-lg xs-float-right"
							name="post_review_submit"><?php echo esc_html( apply_filters( 'wfp_single_content_update_submit', __( 'Submit', 'wp-fundraising' ) ) ); ?></button>
				</div>
			</form>
		</div>
	</div>
	<?php

}
