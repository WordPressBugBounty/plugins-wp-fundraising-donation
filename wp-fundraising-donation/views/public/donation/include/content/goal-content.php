<?php

if ( isset( $formGoalData->enable ) ) {

	$width_per = $persentange;

	if ( $persentange > 100 ) {
		$width_per = 100;
	}

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

	$metaDisplayKey   = 'wfp_display_options_data';
	$getMetaDisplayOp = get_option( $metaDisplayKey );
	$formDisplayData  = isset( $getMetaDisplayOp['goal_setup'] ) ? $getMetaDisplayOp['goal_setup'] : array();
	$defaultBackres   = ! isset( $getMetaDisplayOp['goal_setup'] ) ? 'Yes' : 'No';
	$displayBackers   = isset( $formDisplayData['backers'] ) ? 'Yes' : $defaultBackres;

	?>
	<div class="wfdp-donate-goal-progress <?php echo esc_attr( $displayStyle ); ?>">
													 <?php

														if ( in_array( $goal_type, array( 'terget_goal', 'terget_goal_date', 'campaign_never_end', 'terget_date' ) ) ) {
															?>
																<?php if ( ! is_single() || ( $chart_style != 'pie_bar' ) ) : ?>
				<div class="raised">
																	<?php

																	if ( apply_filters( 'wfp_single_raisedamount_hide', true ) ) :
																		?>

						<div class="target-date-goal raised-amount"> 
																			<?php

																			$def_cont = ' ' . __( 'Raised', 'wp-fundraising' );

																			echo esc_html(
																				( $displayStyle != 'amount_show' && $displayStyle != 'both_show' ) ? apply_filters( 'wfp_single_raisedamount_title', $def_cont ) : ''
																			);

																			if ( $displayStyle == 'percentage' ) :
																				?>

								<div class="wfp-inner-data">
									<span class="donate-percentage"><?php echo esc_html( round( $persentange ) ); ?>%</span>
								</div> 
																				<?php

																				elseif ( $displayStyle == 'amount_show' || $displayStyle == 'both_show' ) :

																					require __DIR__ . '/_partials/amount-to-raise.php';
																					printf( "<span class='wfp-raised-text'>%s</span>", esc_html__( 'raised', 'wp-fundraising' ) );

																				else :

																					require __DIR__ . '/_partials/amount-to-raise.php';

																				endif;
																				?>

						</div> 
																			<?php

					endif;


																	if ( apply_filters( 'wfp_single_goalcounter_hide', true ) ) :
																		?>

						<div class="target-date-goal  goal-amount">

																				<?php
																				echo esc_html(
																					( $displayStyle != 'amount_show' && $displayStyle != 'both_show' ) ? apply_filters( 'wfp_single_goalcounter_title', 'Goal' ) : ''
																				);
																				?>

							<div class="wfp-inner-data">
																		<?php
																		if ( $displayStyle == 'amount_show' || $displayStyle == 'both_show' ) {
																			echo wp_kses( '<span class="wfp-of">' . __( 'of', 'wp-fundraising' ) . '</span>', \WfpFundraising\Utilities\Utils::get_kses_array() );
																		}
																		?>
								<span class="wfp-currency-symbol">
																		<?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $defaultUse_space ) ); ?>
								</span>
								<strong>
																		<?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $target_amount ) ); ?>
								</strong>
								<span class="wfp-currency-symbol">
																		<?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $defaultUse_space ) ); ?>
								</span>
							</div>
						</div>

																			<?php
					endif;

																	if ( apply_filters( 'wfp_single_display_backers_hide', $displayBackers ) ) :
																		if ( ! is_single() && $displayStyle != 'amount_show' && $displayStyle != 'both_show' ) {
																			require __DIR__ . '/_partials/backers.php';
																		}
					endif;

																	?>

				</div><!--  end of raised-->
			<?php endif ?>

																<?php
																if ( apply_filters( 'wfp_single_goalbar_hide', true ) ) :
																	echo wp_kses( $progress_bar, \WfpFundraising\Utilities\Utils::get_kses_array() );
																endif;


																if ( apply_filters( 'wfp_single_date_left_hide', true ) ) :

																	if ( in_array( $goal_type, array( 'terget_goal_date', 'terget_date' ) ) ) :

																		$date1         = date_create( $to_date );
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

																			<?php echo esc_html( $formattedDate ); ?> <?php echo esc_html( apply_filters( 'wfp_single_date_left_title', __( 'days left', 'wp-fundraising' ) ) ); ?>
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
