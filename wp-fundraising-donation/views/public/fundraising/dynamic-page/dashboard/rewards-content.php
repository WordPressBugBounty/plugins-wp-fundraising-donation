<div class="rewards-content wfp-content-padding">
	<h3 class="dashboard-right-section--title"> <?php echo esc_html( apply_filters( 'wfp_dashboard_reward_content_headding', __( 'Rewards Info ', 'wp-fundraising' ) ) ); ?></h3>

	<div class="xs-row">

		<?php
			global $wpdb;

			$rewards = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . "wdp_fundraising WHERE pledge_id != 0 AND user_id = %d AND status IN('Active') ORDER BY date_time DESC", $userId ) );

		if ( is_array( $rewards ) && sizeof( $rewards ) > 0 ) :
			foreach ( $rewards as $v ) :

				$form_id      = (int) isset( $v->form_id ) ? $v->form_id : 0;
				$donateAmount = (float) isset( $v->donate_amount ) ? $v->donate_amount : 0;
				$pledgeAmount = (float) isset( $v->pledge_id ) ? $v->pledge_id : 0;

				$pledge      = get_user_meta( $userId, '_wfp_pledge_user__' . $form_id . '__' . $pledgeAmount . '' );
				$user_pledge = json_decode( end( $pledge ) );

				$post = get_post( $form_id );

				if ( is_object( $user_pledge ) ) {
					?>
			<div class="xs-col-md-6 xs-col-lg-4 rewards-content-single-item">
				<div class="intro-info short-info rewards-block">
					<h3 class="wfp-pledge-title"><?php echo esc_html( '' . isset( $user_pledge->lebel ) ? wp_trim_words( $user_pledge->lebel, 5, '...' ) : '' . ' ' ); ?></h3>
					<div class="wfp-description wfp-pledge-hide">
						<p class="wfp-description--text"><?php echo esc_html( isset( $user_pledge->description ) ? wp_trim_words( $user_pledge->description, 6, '...' ) : '' ); ?></p>
					</div>
					<div class="pledge__detail">
						<span class="pledge__detail-label"> <?php echo esc_html( apply_filters( 'wfp_single_content_rewards_amount', esc_html__( 'Reward Amount:', 'wp-fundraising' ) ) ); ?></span>
						<span class="pledge__detail-info"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $defaultUse_space ) ); ?><strong><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $donateAmount ) ); ?></strong><em class="wfp-currency-symbol"><?php echo esc_attr( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $defaultUse_space ) ); ?></em> </span>
					</div>
					<?php
					$estimatedData = isset( $user_pledge->estimated ) ? $user_pledge->estimated : '';
					if ( strlen( $estimatedData ) > 3 ) {
						?>
					<div class="pledge__detail">
						<span class="pledge__detail-label"> <?php echo esc_html( apply_filters( 'wfp_single_content_rewards_estimated', esc_html__( 'Estimated Delivery:', 'wp-fundraising' ) ) ); ?></span>
						<span class="pledge__detail-info"> <?php echo esc_html( gmdate( 'M Y', strtotime( $estimatedData ) ) ); ?></span>
					</div>
						<?php
					}
						$shipsdData = isset( $user_pledge->ships ) ? $user_pledge->ships : '';
					if ( strlen( $shipsdData ) > 3 ) {
						?>
					<div class="pledge__detail">
						<span class="pledge__detail-label"> <?php echo esc_html( apply_filters( 'wfp_single_content_rewards_ships', esc_html__( 'Ships To:', 'wp-fundraising' ) ) ); ?></span>
						<span class="pledge__detail-info"> <?php echo esc_html( $shipsdData ); ?></span>
					</div>
					<?php } ?>
					<p class="wfp-rewards-title"><a class="wfp-rewards-title--link" href="<?php echo esc_url( get_permalink( $form_id ) ); ?>">
						<?php echo esc_html( '' . isset( $post->post_title ) ? wp_trim_words( $post->post_title, 5, '...' ) : '' . ' ' ); ?></a>
					</p>
				</div>	
			</div>
					<?php
				}
			endforeach;
		endif;
		?>
	</div>
</div>
