<?php 
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

?>
<div class="rewards-content wfp-content-padding">
	<h3 class="dashboard-right-section--title"> <?php echo esc_html( apply_filters( 'wfp_dashboard_reward_content_headding', __( 'Rewards Info ', 'wp-fundraising-donation' ) ) ); ?></h3>

	<div class="xs-row">

		<?php
			global $wpdb;

			$wfp_rewards = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . "wdp_fundraising WHERE pledge_id != 0 AND user_id = %d AND status IN('Active') ORDER BY date_time DESC", $wfpUserId ) ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Prepared read for dashboard rewards list.

		if ( is_array( $wfp_rewards ) && sizeof( $wfp_rewards ) > 0 ) :
			foreach ( $wfp_rewards as $v ) :

				$wfp_form_id      = (int) isset( $v->form_id ) ? $v->form_id : 0;
				$wfpDonateAmount = (float) isset( $v->donate_amount ) ? $v->donate_amount : 0;
				$wfpPledgeAmount = (float) isset( $v->pledge_id ) ? $v->pledge_id : 0;

				$wfpPledge      = get_user_meta( $wfpUserId, '_wfp_pledge_user__' . $wfp_form_id . '__' . $wfpPledgeAmount . '' );
				$wfp_user_pledge = json_decode( end( $wfpPledge ) );

				$post = get_post( $wfp_form_id );

				if ( is_object( $wfp_user_pledge ) ) {
					?>
			<div class="xs-col-md-6 xs-col-lg-4 rewards-content-single-item">
				<div class="intro-info short-info rewards-block">
					<h3 class="wfp-pledge-title"><?php echo esc_html( '' . isset( $wfp_user_pledge->lebel ) ? wp_trim_words( $wfp_user_pledge->lebel, 5, '...' ) : '' . ' ' ); ?></h3>
					<div class="wfp-description wfp-pledge-hide">
						<p class="wfp-description--text"><?php echo esc_html( isset( $wfp_user_pledge->description ) ? wp_trim_words( $wfp_user_pledge->description, 6, '...' ) : '' ); ?></p>
					</div>
					<div class="pledge__detail">
						<span class="pledge__detail-label"> <?php echo esc_html( apply_filters( 'wfp_single_content_rewards_amount', esc_html__( 'Reward Amount:', 'wp-fundraising-donation' ) ) ); ?></span>
						<span class="pledge__detail-info"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $wfp_defaultUse_space ) ); ?><strong><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfpDonateAmount ) ); ?></strong><em class="wfp-currency-symbol"><?php echo esc_attr( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $wfp_defaultUse_space ) ); ?></em> </span>
					</div>
					<?php
					$wfpEstimatedData = isset( $wfp_user_pledge->estimated ) ? $wfp_user_pledge->estimated : '';
					if ( strlen( $wfpEstimatedData ) > 3 ) {
						?>
					<div class="pledge__detail">
						<span class="pledge__detail-label"> <?php echo esc_html( apply_filters( 'wfp_single_content_rewards_estimated', esc_html__( 'Estimated Delivery:', 'wp-fundraising-donation' ) ) ); ?></span>
						<span class="pledge__detail-info"> <?php echo esc_html( gmdate( 'M Y', strtotime( $wfpEstimatedData ) ) ); ?></span>
					</div>
						<?php
					}
						$wfpShipsdData = isset( $wfp_user_pledge->ships ) ? $wfp_user_pledge->ships : '';
					if ( strlen( $wfpShipsdData ) > 3 ) {
						?>
					<div class="pledge__detail">
						<span class="pledge__detail-label"> <?php echo esc_html( apply_filters( 'wfp_single_content_rewards_ships', esc_html__( 'Ships To:', 'wp-fundraising-donation' ) ) ); ?></span>
						<span class="pledge__detail-info"> <?php echo esc_html( $wfpShipsdData ); ?></span>
					</div>
					<?php } ?>
					<p class="wfp-rewards-title"><a class="wfp-rewards-title--link" href="<?php echo esc_url( get_permalink( $wfp_form_id ) ); ?>">
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
