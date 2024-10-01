<?php
$data = array(
	'campaign_id'        => get_the_ID(),
	'target_date'        => $target_date,
	'target_amount'      => $target_amount,
	'total_backers'      => $total_rasied_count,
	'total_rasid_amount' => $total_rasied_amount,
	'symbol'             => $cur_symbol,
	'format'             => $donation_format,
);

do_action( 'wfp_single_backers_before', $data );

$goalMessageEmable = isset( $formGoalData->terget->enable ) ? $formGoalData->terget->enable : 'No';
$goalMessage       = isset( $formGoalData->terget->message ) ? $formGoalData->terget->message : '';
$goalMessage       = strlen( $goalMessage ) > 2 ? $goalMessage : __( 'Campaign closed', 'wp-fundraising' );
$chart_style       = $formGoalData->bar_style;

if ( $donation_format == 'donation' ) { ?>

	<div class="wfdp-donation-input-form button-div wfp-donate-button">
		<?php if ( $campaign_status == 'Ends' ) { ?>
			<div class="wfdp-goal-target-message"><p class="xs-alert xs-alert-success"> <?php echo wp_kses( $goalMessage, \WfpFundraising\Utilities\Utils::get_kses_array() ); ?> </p>
			</div>
		<?php } else { ?>
			<button type="submit" data-type="modal-trigger" data-target="xs-donate-modal-popup"
					class="xs-btn btn-special submit-btn xs-btn-outline-primary"> <?php echo esc_html( $formDesignData->submit_button ? $formDesignData->submit_button : 'Donate Now' ); ?> </button>
		<?php } ?>
	</div>
	<?php
}

if ( $donation_format == 'crowdfunding' ) {
	global $wpdb;
	$goal_type           = isset( $formGoalData->goal_type ) ? $formGoalData->goal_type : 'terget_goal';
	$total_rasied_amount = $wpdb->get_var( $wpdb->prepare( "SELECT SUM(donate_amount) FROM {$wpdb->prefix}wdp_fundraising WHERE form_id = %d AND status = 'Active'", $post->ID ) );
	$total_rasied_count  = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(donate_id) FROM {$wpdb->prefix}wdp_fundraising WHERE form_id = %d AND status = 'Active'", $post->ID ) );

	?>
	<?php if ( isset( $chart_style ) && $chart_style == 'pie_bar' ) : ?>
	<div class="wfp-total-backers-pie">
	<?php endif; ?>

	<?php
	if ( apply_filters( 'wfp_single_target_pledged_hide', true ) ) :
		?>
		<div class="wfp-total-pledge-count">
			<p class="wfp-pledge-title"><?php echo esc_html( apply_filters( 'wfp_single_target_pledged', __( 'Pledged', 'wp-fundraising' ) ) ); ?></p>
			<p class="wfp-pledge-count">
				<em class="wfp-currency-symbol"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $defaultUse_space ) ); ?></em>
				<?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $total_rasied_amount ) ); ?>
				<em class="wfp-currency-symbol"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $defaultUse_space ) ); ?></em>
			</p>
		</div>
		<?php
	endif;

	if ( isset( $chart_style ) && $chart_style == 'pie_bar' ) :
		?>
	<div class="wfp-total-pledge-count target-gaol">
		<p class="wfp-pledge-title"><?php echo esc_html( apply_filters( 'wfp_single_target_pledged', __( 'Goal', 'wp-fundraising' ) ) ); ?></p>
		<p class="wfp-pledge-count">
			<em class="wfp-currency-symbol"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $defaultUse_space ) ); ?></em>
			<?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $target_amount ) ); ?>
			<em class="wfp-currency-symbol"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $defaultUse_space ) ); ?></em>
		</p>
	</div>
		<?php
	endif;

	if ( apply_filters( 'wfp_single_backers_title_hide', true ) ) :
		?>
		<div class="wfp-total-backers-count trace4">
			<p class="wfp-backers-title"><?php echo esc_html( apply_filters( 'wfp_single_backers_title', esc_html__( 'Backers', 'wp-fundraising' ) ) ); ?></p>
			<p class="wfp-backers-count"> <?php echo esc_html( $total_rasied_count ); ?></p>
		</div>
		<?php
	endif;

	if ( isset( $chart_style ) && $chart_style == 'pie_bar' ) :
		?>
	</div>
		<?php
	endif;


	do_action( 'wfp_single_backers_middle', $data );

	if ( apply_filters( 'wfp_single_continue_hide', true ) ) :
		?>
		<div class="wfp-total-backers-count trace3">
			<div class="wfp-additional-data">
				<?php if ( $campaign_status == 'Ends' ) { ?>
					<div class="wfdp-goal-target-message"><p
								class="xs-alert xs-alert-success"> <?php echo wp_kses( $goalMessage, \WfpFundraising\Utilities\Utils::get_kses_array() ); ?> </p></div>
				<?php } else { ?>
					<div class="pledge__detail">
						<div class="pledge__detail-info fixeddata">
							<span class="xs-money-symbol xs-money-symbol-before"><?php echo esc_html( $cur_symbol ); ?></span>
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
						if ( ! empty( $amount_limit ) && property_exists( $amount_limit, 'enable' ) ) {
							echo wp_kses( $amount_limit->details, \WfpFundraising\Utilities\Utils::get_kses_array() );
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

									if ( ! empty( $amount_limit->enable ) ) {

										if ( ! empty( $amount_limit->min_amt ) ) {
											?>
											data-min="<?php echo esc_attr( $amount_limit->min_amt ); ?>"
											<?php
										}

										if ( ! empty( $amount_limit->max_amt ) ) {

											?>
											data-max="<?php echo esc_attr( $amount_limit->max_amt ); ?>"
											<?php
										}
									}

									?>
									class="xs-btn btn-special submit-btn">
								<?php echo esc_html( apply_filters( 'wfp_single_continue_title', __( 'Continue!', 'wp-fundraising' ) ) ); ?>
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

do_action( 'wfp_single_backers_after', $data );

?>
