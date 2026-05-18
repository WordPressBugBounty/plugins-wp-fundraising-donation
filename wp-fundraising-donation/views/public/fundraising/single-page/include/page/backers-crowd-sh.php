<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

$wfpPostId = empty( $post->ID ) ? get_the_ID() : $post->ID;

$wfpGoalMessage = strlen( $wfpGoalMessage ) > 2 ? $wfpGoalMessage : __( 'Campaign closed', 'wp-fundraising' );

if ( $wfpDonationTypeData == 'crowdfunding' ) {
	global $wpdb;
	$wfpTotalRaisedAmount = $wpdb->get_var( $wpdb->prepare( "SELECT SUM(donate_amount) FROM {$wpdb->prefix}wdp_fundraising WHERE form_id = %d AND status = 'Active'", $wfpPostId ) ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Prepared aggregate read for crowdfunding total.
	$wfpTotalRaisedCount  = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(donate_id) FROM {$wpdb->prefix}wdp_fundraising WHERE form_id = %d AND status = 'Active'", $wfpPostId ) ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Prepared aggregate read for crowdfunding count.


	$wfp_data = array(
		'campaign_id'        => $wfpPostId,
		'target_date'        => $wfp_target_date,
		'target_amount'      => $wfp_target_amount,
		'total_backers'      => $wfpTotalRaisedCount,
		'total_rasid_amount' => $wfpTotalRaisedAmount,
		'symbol'             => $wfpSymbols,
		'format'             => $wfpDonationTypeData,
	);

	do_action( 'wfp_single_backers_before', $wfp_data );


	if ( apply_filters( 'wfp_single_backers_title_hide', true ) ) : ?>
		<div class="wfp-total-backers-count trace2">
			<p class="wfp-backers-title"><?php echo esc_html( apply_filters( 'wfp_single_backers_title', __( 'Backers', 'wp-fundraising' ) ) ); ?></p>
			<p class="wfp-backers-count"> <?php echo esc_html( $wfpTotalRaisedCount ); ?></p>
		</div>

		<?php
	endif;

	if ( apply_filters( 'wfp_single_target_pledged_hide', true ) ) :
		?>
		<div class="wfp-total-pledge-count">
			<p class="wfp-pledge-title"><?php echo esc_html( apply_filters( 'wfp_single_target_pledged', __( 'Pledged', 'wp-fundraising' ) ) ); ?></p>
			<p class="wfp-pledge-count">
				<em class="wfp-currency-symbol"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $wfp_defaultUse_space ) ); ?></em><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfpTotalRaisedAmount ) ); ?>
				<em class="wfp-currency-symbol"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $wfp_defaultUse_space ) ); ?></em>
			</p>
		</div>

		<?php
	endif;

	do_action( 'wfp_single_backers_middle', $wfp_data );

	if ( apply_filters( 'wfp_single_continue_hide', true ) ) :
		?>

		<div class="wfp-total-backers-count trace1">
			<div class="wfp-additional-data">
				<?php if ( $wfp_campaign_status == 'Ends' ) { ?>
					<div class="wfdp-goal-target-message">
						<p class="xs-alert xs-alert-success"> <?php echo wp_kses( $wfpGoalMessage, \WfpFundraising\Utilities\Utils::get_kses_array() ); ?> </p>
					</div>
				<?php } else { ?>

					<div class="pledge__detail">
						<div class="pledge__detail-info fixeddata">
							<span class="xs-money-symbol xs-money-symbol-before"><?php echo esc_html( $wfpSymbols ); ?></span>
							<input type="number" min="0" required name="xs_donate_amount_pledge_fixed"
								   id="xs_donate_amount_pledge_fixed" value="" placeholder="1.00"
								   class="xs-field xs-money-field wfp-pledge-amount " />
						</div>

						<div class="xs-donate-limit-details">
						<?php
						if ( ! empty( $wfp_amount_limit ) && property_exists( $wfp_amount_limit, 'enable' ) ) {
							echo wp_kses( $wfp_amount_limit->details, \WfpFundraising\Utilities\Utils::get_kses_array() );
						}
						?>
						</div>

					</div>

					<div class="pledge__detail">
						<div class="pledge__detail-info">
							<button
								type="submit"
								name="submit-form-donation"
								onclick="set_pleadge_amount_data_fixed(this)"
								wfp-id="<?php echo esc_attr( $wfpPostId ); ?>"
								wfp-pledge="0"

								<?php
								if ( \WfpFundraising\Apps\Form_Settings::instance( $wfpPostId )->is_amount_limit_enabled() ) {

									if ( \WfpFundraising\Apps\Form_Settings::instance( $wfpPostId )->has_min_limit_amount() ) {
										?>
										data-min="<?php echo esc_html( \WfpFundraising\Apps\Form_Settings::instance( $wfpPostId )->get_min_limit_amount() ); ?>"
										<?php
									}

									if ( \WfpFundraising\Apps\Form_Settings::instance( $wfpPostId )->has_max_limit_amount() ) {

										?>
										data-max="<?php echo esc_html( \WfpFundraising\Apps\Form_Settings::instance( $wfpPostId )->get_max_limit_amount() ); ?>"
										<?php
									}
								}
								?>

								id="wfp_pledge_button_fixed"
								class="xs-btn btn-special submit-btn">
									<?php echo esc_html( apply_filters( 'wfp_single_continue_title', __( 'Continue', 'wp-fundraising' ) ) ); ?>
							</button>
						</div>
					</div>

					<div class="wfp_hidden_form_container">
						<form action="" method="post" id="add_cart_<?php echo esc_attr( $wfpPostId ); ?>">
							<input name="add-to-cart" type="hidden" value="<?php echo esc_attr( $wfpPostId ); ?>" />
							<input name="quantity" type="hidden" value="1" min="1"  />
						</form>
					</div>

				<?php } ?>
			</div>
		</div>

		<?php
	endif;

	do_action( 'wfp_single_backers_after', $wfp_data );
}
