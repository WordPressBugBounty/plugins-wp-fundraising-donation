<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

if ( isset( $wfpFormGoalData->enable ) && $wfpFormGoalData->enable === \WfpFundraising\Apps\Key::WFP_YES ) {

	$wfp_cont = new \WfpFundraising\Apps\Content();

	/**
	 * Calculation started
	 */
	global $wpdb;
	$wfp_goal_type       = ! empty( $wfpFormGoalData->goal_type ) ? $wfpFormGoalData->goal_type : \WfpFundraising\Apps\Key::GOAL_TYPE_TARGET_GOAL;
	$wfp_total_collected = $wpdb->get_var( $wpdb->prepare( "SELECT SUM(donate_amount) FROM {$wpdb->prefix}wdp_fundraising WHERE form_id = %d AND status = 'Active'", $wfpPostId ) ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Prepared aggregate read for goal content total.
	$wfp_donation_count  = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(donate_id) FROM {$wpdb->prefix}wdp_fundraising WHERE form_id = %d AND status = 'Active'", $wfpPostId ) ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Prepared aggregate read for goal content count.

	$wfp_today        = gmdate( 'Y-m-d' );
	$wfp_fake_amount  = 0;
	$wfp_goal_amount  = 0;
	$wfp_percentage   = 0;
	$wfp_target_date  = $wfp_today;
	$wfp_total_raised = $wfp_total_collected + $wfp_fake_amount;

	$wfpGoalStatus = \WfpFundraising\Apps\Key::WFP_YES;


	if ( in_array(
		$wfp_goal_type,
		array(
			\WfpFundraising\Apps\Key::GOAL_TYPE_TARGET_GOAL,
			\WfpFundraising\Apps\Key::GOAL_TYPE_GOAL_DATE,
			\WfpFundraising\Apps\Key::GOAL_TYPE_GOAL_DATE,
			\WfpFundraising\Apps\Key::GOAL_TYPE_NEVER_END,
		)
	) ) {

		$wfp_goal_amount = isset( $wfpFormGoalData->terget->terget_goal->amount ) ? $wfpFormGoalData->terget->terget_goal->amount : 0;
		$wfp_fake_amount = isset( $wfpFormGoalData->terget->terget_goal->fake_amount ) ? $wfpFormGoalData->terget->terget_goal->fake_amount : 0;
		$wfp_target_date = isset( $wfpFormGoalData->terget->terget_goal->date ) ? $wfpFormGoalData->terget->terget_goal->date : $wfp_today;


		$wfp_total_raised = $wfp_total_collected + $wfp_fake_amount;

		if ( $wfp_total_raised >= $wfp_goal_amount ) {
			$wfp_total_raised = $wfp_total_collected;
		}

		if ( $wfp_goal_amount > 0 ) {
			$wfp_percentage = $wfp_total_raised >= $wfp_goal_amount ? 100 : ( ( $wfp_total_raised * 100 ) / $wfp_goal_amount );
		}

		if ( $wfp_total_raised >= $wfp_goal_amount ) {
			$wfpGoalStatus = \WfpFundraising\Apps\Key::WFP_NO;
		}

		if ( $wfp_goal_type == \WfpFundraising\Apps\Key::GOAL_TYPE_GOAL_DATE || $wfp_goal_type == \WfpFundraising\Apps\Key::GOAL_TYPE_TARGET_DATE ) {

			$wfp_time        = time();
			$wfp_target_time = strtotime( $wfp_target_date );

			if ( $wfp_time > $wfp_target_time ) {
				$wfpGoalStatus = \WfpFundraising\Apps\Key::WFP_NO;
			}
		} elseif ( $wfp_goal_type == \WfpFundraising\Apps\Key::GOAL_TYPE_NEVER_END ) {
			$wfpGoalStatus = \WfpFundraising\Apps\Key::WFP_YES;
		}
	}

	$wfp_campaign_status   = ( $wfpGoalStatus == \WfpFundraising\Apps\Key::WFP_YES ) ? 'Publish' : \WfpFundraising\Apps\Key::CAMPAIGN_STATUS_ENDED;
	$wfpGoalMessageEnable = isset( $wfpFormGoalData->terget->enable ) ? $wfpFormGoalData->terget->enable : \WfpFundraising\Apps\Key::WFP_NO;
	$wfpGoalMessage       = isset( $wfpFormGoalData->terget->message ) ? $wfpFormGoalData->terget->message : '';

	update_post_meta( $wfpPostId, '__wfp_campaign_status', $wfp_campaign_status );

	/**
	 * Calculation ended
	 */


	$wfp_width_per    = $wfp_percentage;
	$wfp_chart_style  = $wfpFormGoalData->bar_style;        // pie_bar | line_bar
	$wfpDisplayStyle = $wfpFormGoalData->bar_display_sty;  // amount_show | percentage | both_show


	if ( apply_filters( 'wfp_single_goal_style', $wfp_chart_style ) == 'pie_bar' ) {

		$wfp_attr = array(
			'data-size'      => '100',
			'data-linewidth' => 20,
			'data-percent'   => round( $wfp_width_per, 2 ),
			'class'          => 'xs_donate_chart',
		);

		$wfp_pie_data = apply_filters( 'wfp_pie_bar_attr', $wfp_attr );
		$wfp_data     = '';

		foreach ( $wfp_pie_data as $wfp_k => $v ) {
			$wfp_data .= $wfp_k . '="' . $v . '" ';
		}

		$wfp_progress_bar = '<div ' . $wfp_data . '><div class="pie-counter"> <span class="pie-percent-number">' . round( $wfp_width_per ) . '</span><span class="pie-percent">%</span></div></div>';

	} else {

		// line_bar - for percentage and both there will be circle in progress bar
		$wfpRoundClass = ( $wfpDisplayStyle == 'amount_show' ) ? '' : 'wfp-round-bar';
		$wfpBarColor   = empty( $wfpFormGoalData->bar_color ) ? '' : ' background-color:' . $wfpFormGoalData->bar_color;

		$wfp_progress_bar = '<div class="wfdp-progress-bar ' . $wfpRoundClass . '" >
								<div class="xs-progress">
									<div class="xs-progress-bar" role="progressbar" data-counter="' . round( $wfp_width_per ) . '%" style="width: ' . round( $wfp_width_per, 2 ) . '%; ' . $wfpBarColor . '" aria-valuemin="0" aria-valuemax="100">
									<div  style="left: calc(' . round( $wfp_width_per ) . '% - 15px); ' . $wfpBarColor . '" class="wfp-round-bar-data">' . round( $wfp_width_per ) . '%</div>
									</div>
								</div>
							</div>';
	}

	$wfpMetaDisplayKey   = \WfpFundraising\Apps\Key::OK_GLOBAL_DISPLAY_OPTIONS;
	$wfpGetMetaDisplayOp = get_option( $wfpMetaDisplayKey );
	$wfpDisplayBackers   = empty( $wfpGetMetaDisplayOp['goal_setup']['backers'] ) ? 'No' : 'Yes';

	?>
	<div class="wfdp-donate-goal-progress">
	<?php

	if ( in_array(
		$wfp_goal_type,
		array(
			\WfpFundraising\Apps\Key::GOAL_TYPE_TARGET_GOAL,
			\WfpFundraising\Apps\Key::GOAL_TYPE_GOAL_DATE,
			\WfpFundraising\Apps\Key::GOAL_TYPE_GOAL_DATE,
			\WfpFundraising\Apps\Key::GOAL_TYPE_NEVER_END,
		)
	) ) {
		?>

			<div class="raised">
			<?php

			if ( apply_filters( 'wfp_single_raisedamount_hide', true ) ) :
				?>

					<div class="target-date-goal raised-amount"> 
					<?php

					$wfp_def_cont = __( 'Raised', 'wp-fundraising-donation' );

					echo wp_kses( apply_filters( 'wfp_single_raisedamount_title', $wfp_def_cont ), \WfpFundraising\Utilities\Utils::get_kses_array() );

					if ( $wfpDisplayStyle == 'percentage' ) :
						?>

							<div class="wfp-inner-data">
								<span class="donate-percentage"><?php echo esc_attr( round( $wfp_percentage ) ); ?>%</span>
							</div> 
							<?php

						elseif ( $wfpDisplayStyle == 'amount_show' ) :

							require __DIR__ . '/amount-to-raise.php';

						else :

							require __DIR__ . '/amount-to-raise.php';

						endif;
						?>

					</div> 
					<?php

				endif;

			if ( apply_filters( 'wfp_single_goalcounter_hide', true ) ) :
				?>

					<div class="target-date-goal  goal-amount">

						<?php echo wp_kses( apply_filters( 'wfp_single_goalcounter_title', esc_html__( 'Goal', 'wp-fundraising-donation' ) ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>

						<div class="wfp-inner-data">
							<span class="wfp-currency-symbol"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $wfp_defaultUse_space ) ); ?></span>
							<strong><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfp_goal_amount ) ); ?></strong>
							<span class="wfp-currency-symbol"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $wfp_defaultUse_space ) ); ?></span>
						</div>
					</div>

					<?php
				endif;

			if ( apply_filters( 'wfp_single_display_backers_hide', $wfpDisplayBackers ) ) :
				require __DIR__ . '/backers.php';
				endif;

			?>

			</div><!--  end of raised-->

			<?php

			if ( apply_filters( 'wfp_single_goalbar_hide', true ) ) :
				echo wp_kses( $wfp_progress_bar, \WfpFundraising\Utilities\Utils::get_kses_array() );
			endif;


			if ( apply_filters( 'wfp_single_date_left_hide', true ) ) :

				if ( in_array( $wfp_goal_type, array( \WfpFundraising\Apps\Key::GOAL_TYPE_TARGET_DATE, \WfpFundraising\Apps\Key::GOAL_TYPE_GOAL_DATE ) ) ) :

					$wfp_date1         = date_create( $wfp_today );
					$wfp_date2         = date_create( $wfp_target_date );
					$wfpFormattedDate = '';

					if ( $wfp_date1 != false && $wfp_date2 != false ) {

						$wfp_diff          = date_diff( $wfp_date1, $wfp_date2 );
						$wfpFormattedDate = $wfp_diff->format( '%R%a' );
					}

					if ( ! empty( $wfpFormattedDate ) ) :
						?>

						<span class="number_donation_count">

							<span class="wfp-icon wfpf wfpf-time"></span>

							<?php echo esc_attr( $wfpFormattedDate ); ?> <?php echo wp_kses( apply_filters( 'wfp_single_date_left_title', esc_html__( 'days left', 'wp-fundraising-donation' ) ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>

						</span>

						<?php
					endif;
				endif;
			endif;

	}
	?>
	</div>
	<?php
}
