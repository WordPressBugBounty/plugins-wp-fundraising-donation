<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

if ( isset( $request['nonce'] ) && wp_verify_nonce( $request['nonce'], 'wfp-update' ) ) {
	$wfp_paged = isset( $_GET['review_page'] ) ? sanitize_text_field( wp_unslash( $_GET['review_page'] ) ) : 1;
} else {
	$wfp_paged = 1;
}
$wfpPostId = empty( $post->ID ) ? get_the_ID() : $post->ID;

$wfpPostId = empty( $wfpFormId ) ? $wfpPostId : $wfpFormId; // overriding for short code...

$wfp_args = array(
	'post_type'      => 'wfp-update',
	'post_parent'    => $wfpPostId,
	'post_status'    => 'publish',

	'orderby'        => array(
		'post_date' => 'DESC',
	),
	'posts_per_page' => 15,
	'paged'          => $wfp_paged,
);

$wfp_the_review = new \WP_Query( $wfp_args );

$wfpPostCount = 1;

if ( $wfp_the_review->have_posts() ) { ?>

	<div class="wfp-details-update-tab">
		<?php
		while ( $wfp_the_review->have_posts() ) {
			$wfp_the_review->the_post();
			$wfp_id           = $post->ID;
			$wfp_post_date    = $post->post_date;
			$wfp_post_content = $post->post_content;

			?>
			<div class="wfp-details-update-tab--list">
				<h3 class="wfp-details-update-tab--list__title"> <?php echo esc_html( wp_date( ' d F Y', strtotime( $wfp_post_date ), new \DateTimeZone( 'UTC' ) ) ); ?></h3>
				<p class="wfp-details-update-tab--list__content"><?php echo wp_kses( $wfp_post_content, \WfpFundraising\Utilities\Utils::get_kses_array() ); ?></p>
			</div>
			<?php
			$wfpPostCount ++;
		}

		wp_reset_postdata();
		?>

	</div>
	<?php

}

$wfpUserId = get_current_user_id();
global $post;
$wfp_author_id = $post->post_author;

if ( is_user_logged_in() && $wfpUserId == $wfp_author_id ) {
	?>
	<div class="wfp-submit-updates">
		<div class="wfp-entry-reiv">
			<form class="wfp-user-update" id="wfp_update-<?php esc_attr( $wfpPostId ); ?>" method="post">
				<div class="message-update-status"></div>


				<div class="wfp-review-filed wfp-review-message">
					<textarea type="text" rows="4" name="updates_post[details]" class="wfp-input wfp-textarea"
							  placeholder="<?php echo esc_attr__( 'Write Update *', 'wp-fundraising-donation' ); ?>"></textarea>
				</div>

				<div class="wfp-review-submit">
					<button type="submit" class="wfp-form-button xs-btn xs-btn-primary xs-btn-lg xs-float-right"
							name="post_review_submit"><?php echo esc_html( apply_filters( 'wfp_single_content_update_submit', __( 'Submit', 'wp-fundraising-donation' ) ) ); ?></button>
				</div>
			</form>
		</div>
	</div>
	<?php

}
