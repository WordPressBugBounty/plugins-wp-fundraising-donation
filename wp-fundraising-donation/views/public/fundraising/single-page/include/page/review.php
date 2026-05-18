
<?php 

defined( 'ABSPATH' ) || exit;
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

?>
<div class="wfp-recent-section">
	<div class="wfp-show-review">
		<?php
		if ( isset( $request['nonce'] ) && wp_verify_nonce( $request['nonce'], 'wfp-review' ) ) {
			$wfp_paged = isset( $_GET['review_page'] ) ? sanitize_text_field( wp_unslash( $_GET['review_page'] ) ) : 1;
		} else {
			$wfp_paged = 1;
		}

		$wfpPostId = empty( $post->ID ) ? get_the_ID() : $post->ID;

		$wfp_found = new WfpFundraising\Apps\Fundraising( false );

		$wfp_args = array(
			'post_type'      => 'wfp-review',
			'post_parent'    => $wfpPostId,
			'post_status'    => 'publish',

			'orderby'        => array(
				'post_date' => 'DESC',
			),
			'posts_per_page' => 10,
			'paged'          => $wfp_paged,
		);

		$wfp_the_review = new \WP_Query( $wfp_args );
		$wfpUserId     = get_current_user_id();
		$wfpPostCount  = 0;

		if ( $wfp_the_review->have_posts() ) {

			while ( $wfp_the_review->have_posts() ) {
				$wfp_the_review->the_post();
				$wfpMetaReviewID = $post->ID;

				$wfpParentId = $post->post_parent;

				$wfpMetaReviewJson = get_post_meta( $wfpMetaReviewID, '__wfp_public_review_data', true );
				$wfpGetReviewData  = json_decode( $wfpMetaReviewJson, true );

				$wfp_post_parent = get_post( $wfpParentId );
				$wfp_author_id   = ( property_exists( $wfp_post_parent, 'post_author' ) ) ? $wfp_post_parent->post_author : 0;
				// only review giver and author of the campaign can edit or delete.

				?>
				<div class="wfp-review-list" id="reviewwfp-re__<?php echo esc_attr( $wfpMetaReviewID ); ?>__<?php echo esc_attr( $wfpUserId ); ?>">
					<?php
					if ( $wfpUserId == $post->post_author || $wfpUserId == $wfp_author_id ) :
						?>
						<span id="span-reviewwfp-re__<?php echo esc_attr( $wfpMetaReviewID ); ?>__<?php echo esc_attr( $wfpUserId ); ?>"></span>
						<div class="review-action">
							<button class="xs-btn xs-btn-link review-action--btn" type="button"
									onclick="wfp_remove_review(this);"
									id="wfp-re__<?php echo esc_attr( $wfpMetaReviewID ); ?>__<?php echo esc_attr( $wfpUserId ); ?>">
								<i class="wfpf wfpf-trash"></i>
							</button>
							<button class="xs-btn xs-btn-link review-action--btn" type="button"
									onclick="wfp_edit_review(this);"
									data-id="<?php echo esc_attr( $wfpMetaReviewID ); ?>__<?php echo esc_attr( $wfpUserId ); ?>">
								<i class="wfpf wfpf-edit-pencil"></i>
							</button>
						</div>
					<?php endif; ?>
					<div class="wfp-review-header">
						<div class="wfp-review-header--ratting xs-review-rating-stars">
							<?php
							$wfp_ratting = isset( $wfpGetReviewData['ratting'] ) ? (int) $wfpGetReviewData['ratting'] : 0;
							echo wp_kses( $wfp_found->wfp_ratting_view_star_point( $wfp_ratting, 5 ), \WfpFundraising\Utilities\Utils::get_kses_array() );
							?>
						</div>
						<div class="wfp-review-header--name">
							<?php echo esc_html( isset( $wfpGetReviewData['name'] ) ? $wfpGetReviewData['name'] : '' ); ?>
						</div>
					</div>
					<div class="wfp-review-header--summery">
						<?php echo esc_html( isset( $wfpGetReviewData['summery'] ) ? $wfpGetReviewData['summery'] : '' ); ?>
					</div>
				</div>
				<?php
				$wfpPostCount++;
			}
			?>
			<div class="xs-review-pagination"></div>
			<?php
			wp_reset_postdata();
		}

		?>
	</div>
	<?php
	global $post;
	$wfp_author_id = $post->post_author;

	if ( is_user_logged_in() ) :

		if ( $wfpUserId != $wfp_author_id ) :

			$wfp_current_user = wp_get_current_user();
			?>

			<div class="wfp-entry-reiv">
				<form class="wfp-user-review " id="wfp_user-<?php echo esc_attr( $wfpPostId ); ?>" method="post">
					<div class="message-review-status"></div>
					<div class="wfp-review-header">
						<h2 class="wfp-review-header--title"><?php echo esc_html( apply_filters( 'wfp_single_content_review_write', __( 'Write a Review', 'wp-fundraising' ) ) ); ?></h2>
						<p class="wfp-review-header--description"><?php echo esc_html( apply_filters( 'wfp_single_content_review_dsc', __( 'Your email address will not be published. Required fields are marked *', 'wp-fundraising' ) ) ); ?></p>
					</div>

					<div class="wfp-review-filed xs-review-rating-stars wfp-review-rating">
						<label class="wfp-review-rating--label"
							   for="reviewer_ratting"><?php echo esc_html( apply_filters( 'wfp_single_content_reviewer_ratting', __( 'Ratting', 'wp-fundraising' ) ) ); ?></label>
						<ul id="xs_review_stars" class="xs_review_stars">
							<?php for ( $wfp_ratting = 1; $wfp_ratting <= 5; $wfp_ratting ++ ) : ?>
								<li class="star-li star  
								<?php
								if ( $wfp_ratting == 1 ) {
									echo esc_attr( 'selected' );
								}
								?>
								" data-value="<?php echo esc_attr( $wfp_ratting ); ?>">
									<i class="xs-star dashicons-before dashicons-star-filled"></i>
								</li>
							<?php endfor; ?>
						</ul>
						<input type="hidden" id="ratting_review_hidden" name="review_post[ratting]" value="1" required/>
					</div>

					<div class="wfp-review-filed-container">
						<div class="wfp-review-filed">
							<input type="text" name="review_post[name]" id="reviewer_name"
								   placeholder="<?php echo esc_attr__( 'Name *', 'wp-fundraising' ); ?>" required class="wfp-input"
								   value="<?php echo esc_attr( $wfp_current_user->user_firstname ); ?>">
						</div>
						<div class="wfp-review-filed">
							<input type="email" name="review_post[email]" id="reviewer_email"
								   placeholder="<?php echo esc_html__( 'Email *', 'wp-fundraising' ); ?>" required class="wfp-input"
								   value="<?php echo esc_attr( $wfp_current_user->user_email ); ?>">
						</div>
					</div>


					<div class="wfp-review-filed wfp-review-message">
					<textarea type="text" rows="4" name="review_post[summery]" id="reviewer_summery"
							  class="wfp-input wfp-textarea"
							  placeholder="<?php echo esc_html__( 'Your Review *', 'wp-fundraising' ); ?>"></textarea>
					</div>
					<div class="wfp-review-submit">
						<button type="submit" class="wfp-form-button xs-btn xs-btn-primary xs-btn-lg xs-float-right"
								id="wfp-review-button"
								name="post_review_submit"><?php echo esc_html( apply_filters( 'wfp_single_content_reviewer_submit', __( 'Submit', 'wp-fundraising' ) ) ); ?></button>
					</div>
					<input type="hidden" name="review_post[parent]" id="reviewer_parent" class="wfp-input" value="0">
				</form>
			</div>

			<?php
		else :
			?>

			<div class="wfp-review-list">
				<span><?php esc_html_e( 'As a author of this campaign you can not give review!', 'wp-fundraising' ); ?></span>
			</div>
			<?php
		endif;
	else :
		?>

		<div class="wfp-review-list">

			<span><?php esc_html_e( 'To give review you need to', 'wp-fundraising' ); ?> </span>
			<a href="<?php echo esc_url( wp_login_url( get_permalink() ) ); ?>" alt="<?php esc_attr_e( 'Login', 'wp-fundraising' ); ?>">
				<?php esc_html_e( 'Login', 'wp-fundraising' ); ?>
			</a>
		</div>
		<?php
	endif;

	?>
</div>
