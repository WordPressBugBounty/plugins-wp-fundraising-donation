<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

$wfp_data = array(
	'campaign_id'        => get_the_ID(),
	'target_date'        => $wfp_target_date,
	'target_amount'      => $wfp_target_amount,
	'total_backers'      => $wfp_total_rasied_count,
	'total_rasid_amount' => $wfp_total_rasied_amount,
	'symbol'             => $wfp_cur_symbol,
	'format'             => $wfp_donation_format,
);

do_action( 'wfp_single_backers_before', $wfp_data );

$wfpGoalMessageEmable = isset( $wfpFormGoalData->terget->enable ) ? $wfpFormGoalData->terget->enable : 'No';
$wfpGoalMessage       = isset( $wfpFormGoalData->terget->message ) ? $wfpFormGoalData->terget->message : '';
$wfpGoalMessage       = strlen( $wfpGoalMessage ) > 2 ? $wfpGoalMessage : __( 'Campaign closed', 'wp-fundraising-donation' );
$wfp_chart_style       = $wfpFormGoalData->bar_style;

if ( $wfp_donation_format == 'donation' ) { ?>

	<div class="wfdp-donation-input-form button-div wfp-donate-button">
		<?php if ( $wfp_campaign_status == 'Ends' ) { ?>
			<div class="wfdp-goal-target-message"><p class="xs-alert xs-alert-success"> <?php echo wp_kses( $wfpGoalMessage, \WfpFundraising\Utilities\Utils::get_kses_array() ); ?> </p>
			</div>
		<?php } else { ?>
			<button type="submit" data-type="modal-trigger" data-target="xs-donate-modal-popup"
					class="xs-btn btn-special submit-btn xs-btn-outline-primary"> <?php echo esc_html( $wfpFormDesignData->submit_button ? $wfpFormDesignData->submit_button : 'Donate Now' ); ?> </button>
		<?php } ?>
	</div>
	<?php
}

if ( $wfp_donation_format == 'crowdfunding' ) {
	global $wpdb;
	$wfp_goal_type           = isset( $wfpFormGoalData->goal_type ) ? $wfpFormGoalData->goal_type : 'terget_goal';
	$wfp_total_rasied_amount = $wpdb->get_var( $wpdb->prepare( "SELECT SUM(donate_amount) FROM {$wpdb->prefix}wdp_fundraising WHERE form_id = %d AND status = 'Active'", $post->ID ) ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Prepared aggregate read for backers total.
	$wfp_total_rasied_count  = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(donate_id) FROM {$wpdb->prefix}wdp_fundraising WHERE form_id = %d AND status = 'Active'", $post->ID ) ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Prepared aggregate read for backers count.

	?>
	<?php if ( isset( $wfp_chart_style ) && $wfp_chart_style == 'pie_bar' ) : ?>
	<div class="wfp-total-backers-pie">
	<?php endif; ?>

	<?php
	if ( apply_filters( 'wfp_single_target_pledged_hide', true ) ) :
		?>
		<div class="wfp-total-pledge-count">
			<p class="wfp-pledge-title"><?php echo esc_html( apply_filters( 'wfp_single_target_pledged', __( 'Pledged', 'wp-fundraising-donation' ) ) ); ?></p>
			<p class="wfp-pledge-count">
				<em class="wfp-currency-symbol"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $wfp_defaultUse_space ) ); ?></em>
				<?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfp_total_rasied_amount ) ); ?>
				<em class="wfp-currency-symbol"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $wfp_defaultUse_space ) ); ?></em>
			</p>
		</div>
		<?php
	endif;

	if ( isset( $wfp_chart_style ) && $wfp_chart_style == 'pie_bar' ) :
		?>
	<div class="wfp-total-pledge-count target-gaol">
		<p class="wfp-pledge-title"><?php echo esc_html( apply_filters( 'wfp_single_target_pledged', __( 'Goal', 'wp-fundraising-donation' ) ) ); ?></p>
		<p class="wfp-pledge-count">
			<em class="wfp-currency-symbol"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $wfp_defaultUse_space ) ); ?></em>
			<?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfp_target_amount ) ); ?>
			<em class="wfp-currency-symbol"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $wfp_defaultUse_space ) ); ?></em>
		</p>
	</div>
		<?php
	endif;

	if ( apply_filters( 'wfp_single_backers_title_hide', true ) ) :
		?>
		<div class="wfp-total-backers-count trace4">
			<p class="wfp-backers-title"><?php echo esc_html( apply_filters( 'wfp_single_backers_title', esc_html__( 'Backers', 'wp-fundraising-donation' ) ) ); ?></p>
			<p class="wfp-backers-count"> <?php echo esc_html( $wfp_total_rasied_count ); ?></p>
		</div>
		<?php
	endif;

	if ( isset( $wfp_chart_style ) && $wfp_chart_style == 'pie_bar' ) :
		?>
	</div>
		<?php
	endif;


	do_action( 'wfp_single_backers_middle', $wfp_data );

	if ( apply_filters( 'wfp_single_continue_hide', true ) ) :
		?>
		<div class="wfp-total-backers-count trace3">
			<div class="wfp-additional-data">
				<?php if ( $wfp_campaign_status == 'Ends' ) { ?>
					<div class="wfdp-goal-target-message"><p
								class="xs-alert xs-alert-success"> <?php echo wp_kses( $wfpGoalMessage, \WfpFundraising\Utilities\Utils::get_kses_array() ); ?> </p></div>
				<?php } else { ?>
					<div class="pledge__detail">
						<div class="pledge__detail-info fixeddata">
							<span class="xs-money-symbol xs-money-symbol-before"><?php echo esc_html( $wfp_cur_symbol ); ?></span>
							<input
								type="number"
								min="0"
								required
								name="xs_donate_amount_pledge_fixed"
								id="xs_donate_amount_pledge_fixed"
								value=""
								placeholder="1.00"
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
								wfp-id="<?php the_ID(); ?>"
								wfp-pledge="0"
								id="wfp_pledge_button_fixed"
									<?php

									if ( ! empty( $wfp_amount_limit->enable ) ) {

										if ( ! empty( $wfp_amount_limit->min_amt ) ) {
											?>
											data-min="<?php echo esc_attr( $wfp_amount_limit->min_amt ); ?>"
											<?php
										}

										if ( ! empty( $wfp_amount_limit->max_amt ) ) {

											?>
											data-max="<?php echo esc_attr( $wfp_amount_limit->max_amt ); ?>"
											<?php
										}
									}

									?>
									class="xs-btn btn-special submit-btn">
								<?php echo esc_html( apply_filters( 'wfp_single_continue_title', __( 'Continue!', 'wp-fundraising-donation' ) ) ); ?>
							</button>
						</div>
					</div>


					<div class="wfp_hidden_form_container">
						<form action="" method="post" id="add_cart_<?php the_ID(); ?>">
							<input name="add-to-cart" type="hidden" value="<?php the_ID(); ?>" />
							<input name="quantity" type="hidden" value="1" min="1"  />
						</form>
					</div>

				<?php } ?>
			</div>
		</div>

		<?php
	endif;
}

do_action( 'wfp_single_backers_after', $wfp_data );

?>
