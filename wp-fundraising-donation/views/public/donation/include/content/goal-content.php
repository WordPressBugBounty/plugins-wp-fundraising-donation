<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

if ( isset( $wfpFormGoalData->enable ) ) {

	$wfp_width_per = $wfp_persentange;

	if ( $wfp_persentange > 100 ) {
		$wfp_width_per = 100;
	}

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

	$wfpMetaDisplayKey   = 'wfp_display_options_data';
	$wfpGetMetaDisplayOp = get_option( $wfpMetaDisplayKey );
	$wfpFormDisplayData  = isset( $wfpGetMetaDisplayOp['goal_setup'] ) ? $wfpGetMetaDisplayOp['goal_setup'] : array();
	$wfpDefaultBackres   = ! isset( $wfpGetMetaDisplayOp['goal_setup'] ) ? 'Yes' : 'No';
	$wfpDisplayBackers   = isset( $wfpFormDisplayData['backers'] ) ? 'Yes' : $wfpDefaultBackres;

	?>
	<div class="wfdp-donate-goal-progress <?php echo esc_attr( $wfpDisplayStyle ); ?>">
													 <?php

														if ( in_array( $wfp_goal_type, array( 'terget_goal', 'terget_goal_date', 'campaign_never_end', 'terget_date' ) ) ) {
															?>
																<?php if ( ! is_single() || ( $wfp_chart_style != 'pie_bar' ) ) : ?>
				<div class="raised">
																	<?php

																	if ( apply_filters( 'wfp_single_raisedamount_hide', true ) ) :
																		?>

						<div class="target-date-goal raised-amount"> 
																			<?php

																			$wfp_def_cont = ' ' . __( 'Raised', 'wp-fundraising' );

																			echo esc_html(
																				( $wfpDisplayStyle != 'amount_show' && $wfpDisplayStyle != 'both_show' ) ? apply_filters( 'wfp_single_raisedamount_title', $wfp_def_cont ) : ''
																			);

																			if ( $wfpDisplayStyle == 'percentage' ) :
																				?>

								<div class="wfp-inner-data">
									<span class="donate-percentage"><?php echo esc_html( round( $wfp_persentange ) ); ?>%</span>
								</div> 
																				<?php

																				elseif ( $wfpDisplayStyle == 'amount_show' || $wfpDisplayStyle == 'both_show' ) :

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
																					( $wfpDisplayStyle != 'amount_show' && $wfpDisplayStyle != 'both_show' ) ? apply_filters( 'wfp_single_goalcounter_title', 'Goal' ) : ''
																				);
																				?>

							<div class="wfp-inner-data">
																		<?php
																		if ( $wfpDisplayStyle == 'amount_show' || $wfpDisplayStyle == 'both_show' ) {
																			echo wp_kses( '<span class="wfp-of">' . __( 'of', 'wp-fundraising' ) . '</span>', \WfpFundraising\Utilities\Utils::get_kses_array() );
																		}
																		?>
								<span class="wfp-currency-symbol">
																		<?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $wfp_defaultUse_space ) ); ?>
								</span>
								<strong>
																		<?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfp_target_amount ) ); ?>
								</strong>
								<span class="wfp-currency-symbol">
																		<?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $wfp_defaultUse_space ) ); ?>
								</span>
							</div>
						</div>

																			<?php
					endif;

																	if ( apply_filters( 'wfp_single_display_backers_hide', $wfpDisplayBackers ) ) :
																		if ( ! is_single() && $wfpDisplayStyle != 'amount_show' && $wfpDisplayStyle != 'both_show' ) {
																			require __DIR__ . '/_partials/backers.php';
																		}
					endif;

																	?>

				</div><!--  end of raised-->
			<?php endif ?>

																<?php
																if ( apply_filters( 'wfp_single_goalbar_hide', true ) ) :
																	echo wp_kses( $wfp_progress_bar, \WfpFundraising\Utilities\Utils::get_kses_array() );
																endif;


																if ( apply_filters( 'wfp_single_date_left_hide', true ) ) :

																	if ( in_array( $wfp_goal_type, array( 'terget_goal_date', 'terget_date' ) ) ) :

																		$wfp_date1         = date_create( $wfp_to_date );
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

																			<?php echo esc_html( $wfpFormattedDate ); ?> <?php echo esc_html( apply_filters( 'wfp_single_date_left_title', __( 'days left', 'wp-fundraising' ) ) ); ?>
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
