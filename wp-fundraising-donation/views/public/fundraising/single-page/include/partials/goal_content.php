<?php

if ( isset( $formGoalData->enable ) && $formGoalData->enable === \WfpFundraising\Apps\Key::WFP_YES ) {

	$cont = new \WfpFundraising\Apps\Content();

	/**
	 * Calculation started
	 */
	global $wpdb;
	$goal_type       = ! empty( $formGoalData->goal_type ) ? $formGoalData->goal_type : \WfpFundraising\Apps\Key::GOAL_TYPE_TARGET_GOAL;
	$total_collected = $wpdb->get_var( $wpdb->prepare( "SELECT SUM(donate_amount) FROM {$wpdb->prefix}wdp_fundraising WHERE form_id = %d AND status = 'Active'", $postId ) );
	$donation_count  = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(donate_id) FROM {$wpdb->prefix}wdp_fundraising WHERE form_id = %d AND status = 'Active'", $postId ) );

	$today        = gmdate( 'Y-m-d' );
	$fake_amount  = 0;
	$goal_amount  = 0;
	$percentage   = 0;
	$target_date  = $today;
	$total_raised = $total_collected + $fake_amount;

	$goalStatus = \WfpFundraising\Apps\Key::WFP_YES;


	if ( in_array(
		$goal_type,
		array(
			\WfpFundraising\Apps\Key::GOAL_TYPE_TARGET_GOAL,
			\WfpFundraising\Apps\Key::GOAL_TYPE_GOAL_DATE,
			\WfpFundraising\Apps\Key::GOAL_TYPE_GOAL_DATE,
			\WfpFundraising\Apps\Key::GOAL_TYPE_NEVER_END,
		)
	) ) {

		$goal_amount = isset( $formGoalData->terget->terget_goal->amount ) ? $formGoalData->terget->terget_goal->amount : 0;
		$fake_amount = isset( $formGoalData->terget->terget_goal->fake_amount ) ? $formGoalData->terget->terget_goal->fake_amount : 0;
		$target_date = isset( $formGoalData->terget->terget_goal->date ) ? $formGoalData->terget->terget_goal->date : $today;


		$total_raised = $total_collected + $fake_amount;

		if ( $total_raised >= $goal_amount ) {
			$total_raised = $total_collected;
		}

		if ( $goal_amount > 0 ) {
			$percentage = $total_raised >= $goal_amount ? 100 : ( ( $total_raised * 100 ) / $goal_amount );
		}

		if ( $total_raised >= $goal_amount ) {
			$goalStatus = \WfpFundraising\Apps\Key::WFP_NO;
		}

		if ( $goal_type == \WfpFundraising\Apps\Key::GOAL_TYPE_GOAL_DATE || $goal_type == \WfpFundraising\Apps\Key::GOAL_TYPE_TARGET_DATE ) {

			$time        = time();
			$target_time = strtotime( $target_date );

			if ( $time > $target_time ) {
				$goalStatus = \WfpFundraising\Apps\Key::WFP_NO;
			}
		} elseif ( $goal_type == \WfpFundraising\Apps\Key::GOAL_TYPE_NEVER_END ) {
			$goalStatus = \WfpFundraising\Apps\Key::WFP_YES;
		}
	}

	$campaign_status   = ( $goalStatus == \WfpFundraising\Apps\Key::WFP_YES ) ? 'Publish' : \WfpFundraising\Apps\Key::CAMPAIGN_STATUS_ENDED;
	$goalMessageEnable = isset( $formGoalData->terget->enable ) ? $formGoalData->terget->enable : \WfpFundraising\Apps\Key::WFP_NO;
	$goalMessage       = isset( $formGoalData->terget->message ) ? $formGoalData->terget->message : '';

	update_post_meta( $postId, '__wfp_campaign_status', $campaign_status );

	/**
	 * Calculation ended
	 */


	$width_per    = $percentage;
	$chart_style  = $formGoalData->bar_style;        // pie_bar | line_bar
	$displayStyle = $formGoalData->bar_display_sty;  // amount_show | percentage | both_show


	if ( apply_filters( 'wfp_single_goal_style', $chart_style ) == 'pie_bar' ) {

		$attr = array(
			'data-size'      => '100',
			'data-linewidth' => 20,
			'data-percent'   => round( $width_per, 2 ),
			'class'          => 'xs_donate_chart',
		);

		$pie_data = apply_filters( 'wfp_pie_bar_attr', $attr );
		$data     = '';

		foreach ( $pie_data as $k => $v ) {
			$data .= $k . '="' . $v . '" ';
		}

		$progress_bar = '<div ' . $data . '><div class="pie-counter"> <span class="pie-percent-number">' . round( $width_per ) . '</span><span class="pie-percent">%</span></div></div>';

	} else {

		// line_bar - for percentage and both there will be circle in progress bar
		$roundClass = ( $displayStyle == 'amount_show' ) ? '' : 'wfp-round-bar';
		$barColor   = empty( $formGoalData->bar_color ) ? '' : ' background-color:' . $formGoalData->bar_color;

		$progress_bar = '<div class="wfdp-progress-bar ' . $roundClass . '" >
								<div class="xs-progress">
									<div class="xs-progress-bar" role="progressbar" data-counter="' . round( $width_per ) . '%" style="width: ' . round( $width_per, 2 ) . '%; ' . $barColor . '" aria-valuemin="0" aria-valuemax="100">
									<div  style="left: calc(' . round( $width_per ) . '% - 15px); ' . $barColor . '" class="wfp-round-bar-data">' . round( $width_per ) . '%</div>
									</div>
								</div>
							</div>';
	}

	$metaDisplayKey   = \WfpFundraising\Apps\Key::OK_GLOBAL_DISPLAY_OPTIONS;
	$getMetaDisplayOp = get_option( $metaDisplayKey );
	$displayBackers   = empty( $getMetaDisplayOp['goal_setup']['backers'] ) ? 'No' : 'Yes';

	?>
	<div class="wfdp-donate-goal-progress">
	<?php

	if ( in_array(
		$goal_type,
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

					$def_cont = __( 'Raised', 'wp-fundraising' );

					echo wp_kses( apply_filters( 'wfp_single_raisedamount_title', $def_cont ), \WfpFundraising\Utilities\Utils::get_kses_array() );

					if ( $displayStyle == 'percentage' ) :
						?>

							<div class="wfp-inner-data">
								<span class="donate-percentage"><?php echo esc_attr( round( $percentage ) ); ?>%</span>
							</div> 
							<?php

						elseif ( $displayStyle == 'amount_show' ) :

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

						<?php echo wp_kses( apply_filters( 'wfp_single_goalcounter_title', esc_html__( 'Goal', 'wp-fundraising' ) ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>

						<div class="wfp-inner-data">
							<span class="wfp-currency-symbol"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $defaultUse_space ) ); ?></span>
							<strong><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $goal_amount ) ); ?></strong>
							<span class="wfp-currency-symbol"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $defaultUse_space ) ); ?></span>
						</div>
					</div>

					<?php
				endif;

			if ( apply_filters( 'wfp_single_display_backers_hide', $displayBackers ) ) :
				require __DIR__ . '/backers.php';
				endif;

			?>

			</div><!--  end of raised-->

			<?php

			if ( apply_filters( 'wfp_single_goalbar_hide', true ) ) :
				echo wp_kses( $progress_bar, \WfpFundraising\Utilities\Utils::get_kses_array() );
			endif;


			if ( apply_filters( 'wfp_single_date_left_hide', true ) ) :

				if ( in_array( $goal_type, array( \WfpFundraising\Apps\Key::GOAL_TYPE_TARGET_DATE, \WfpFundraising\Apps\Key::GOAL_TYPE_GOAL_DATE ) ) ) :

					$date1         = date_create( $today );
					$date2         = date_create( $target_date );
					$formattedDate = '';

					if ( $date1 != false && $date2 != false ) {

						$diff          = date_diff( $date1, $date2 );
						$formattedDate = $diff->format( '%R%a' );
					}

					if ( ! empty( $formattedDate ) ) :
						?>

						<span class="number_donation_count">

							<span class="wfp-icon wfpf wfpf-time"></span>

							<?php echo esc_attr( $formattedDate ); ?> <?php echo wp_kses( apply_filters( 'wfp_single_date_left_title', esc_html__( 'days left', 'wp-fundraising' ) ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>

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
