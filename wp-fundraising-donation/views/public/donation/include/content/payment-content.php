<?php 
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

?>
<div class="wfdp-donation-input-form wfdp-input-payment-field-wraper">
	<div class="wfdp-input-payment-field">
		<?php echo wp_kses( do_action( 'wfp_donate_forms_payment_method_headding_before' ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
		<span class=""> <?php echo esc_html( apply_filters( 'wfp_donate_forms_payment_method_headding', __( 'Select Payment Method:', 'wp-fundraising' ) ) ); ?></span>
		<?php echo wp_kses( do_action( 'wfp_donate_forms_payment_method_headding_after' ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
		<ul class="xs-donate-display-amount wfp-radio-input-style-2">
			<?php

			if ( empty( $wfp_payment_settings ) ) {

				echo wp_kses( '<p> ' . __( 'No payment method is set up.', 'wp-fundraising' ) . ' </p>', \WfpFundraising\Utilities\Utils::get_kses_array() );

			} else {

				$wfp_checked = 'checked';

				foreach ( $wfp_payment_settings as $wfp_account => $wfp_settings_info ) {

					$wfp_title  = empty( $wfp_settings_info['setup']['title'] ) ? '' : $wfp_settings_info['setup']['title'];
					$wfp_dom_id = 'wfp_' . $wfp_account;
					?>

					<li>
						<input class="xs_radio_filed" id="<?php echo esc_attr( $wfp_dom_id ); ?>" type="radio"
							   name="xs_donate_data_submit[payment_method]" <?php echo esc_attr( $wfp_checked ); ?>
							   value="<?php echo esc_attr( $wfp_account ); ?>"
							   onchange="xs_show_hide_multiple_div('.payment_method_info', '.method-<?php echo esc_attr( $wfp_account ); ?>');">
						<label for="<?php echo esc_attr( $wfp_dom_id ); ?>"><?php echo esc_html( $wfp_title ); ?></label>
					</li>
					<?php

					$wfp_checked = '';
				}
			}

			?>
		</ul>
	</div>
</div>
<div class="wfdp-input-payment-field wfdp-donation-payment-details">
	<div class="xs-donate-display-amount">
		<?php

		$wfp_checked = 'yes';

		foreach ( $wfp_payment_settings as $wfp_account => $wfp_settings_info ) {

			$wfp_title = empty( $wfp_settings_info['setup']['title'] ) ? '' : $wfp_settings_info['setup']['title'];

			?>
			<div class="payment_method_info method-<?php echo esc_attr( $wfp_account ); ?> xs-donate-hidden <?php echo esc_attr( ( $wfp_checked == 'yes' ) ? 'xs-donate-visible' : '' ); ?>">
				<h2 class="wfp-payment-method-title fdas"><?php echo esc_html( $wfp_title ); ?> </h2> 
																	 <?php

																		if ( $wfp_account == 'bank_payment' ) {
																			?>

					<div><strong> <?php echo esc_html__( 'Account Details:', 'wp-fundraising' ); ?></strong></div> 
																			<?php

																			if ( ! empty( $wfp_settings_info['setup']['account_details'] ) ) {

																				$wfpSetupData = $wfp_settings_info['setup']['account_details'];

																				?>
						<div class="xs-table-responsive wfdp-table-design">
							<table class="form-table wc_gateways widefat payment-details">
								<thead>
								<tr>
									<th>SL.</th> 
																							<?php

																							foreach ( $wfpSetupData as $setupDatum ) {

																								foreach ( $setupDatum as $wfpSubKeyHead => $setupDetails ) {

																									$wfpLabelNameSub = ucfirst( str_replace( array( '_', '-' ), ' ', $wfpSubKeyHead ) );
																									?>

											<th class="name"> <?php echo esc_html( $wfpLabelNameSub ); ?></th> 
																									<?php
																								}

																								break;
																							}
																							?>
								</tr>
								</thead>
								<tbody>
																							<?php
																							foreach ( $wfpSetupData as $wfp_count => $setupDatum ) {
																								?>
										<tr><td><?php echo esc_html( ++$wfp_count . '. ' ); ?></td>
																								<?php
																								foreach ( $setupDatum as $wfpSubKeyHead => $setupDetails ) {
																									?>
											<td>
																									<?php echo esc_html( $setupDetails ); ?>
											</td> 
																									<?php
																								}
																								?>
										</tr>
																								<?php
																							}
																							?>
								</tbody>
							</table>
						</div>
																				<?php
																			}
																		}

																		if ( isset( $wfp_settings_info['setup']['description'] ) && strlen( $wfp_settings_info['setup']['description'] ) > 4 ) :
																			?>
					<div class="wfp-payment-method-acc-details"><strong
								class="wfp-payment-method-acc-details--title"><?php echo esc_html( apply_filters( 'wfp_donate_forms_payment_method_details', esc_html__( 'Details:', 'wp-fundraising' ) ) ); ?></strong>
						<span class="wfp-payment-method-acc-details--description"><?php echo wp_kses( isset( $wfp_settings_info['setup']['description'] ) ? $wfp_settings_info['setup']['description'] : '', \WfpFundraising\Utilities\Utils::get_kses_array() ); ?></span>
					</div>
																			<?php

				endif;

																		if ( isset( $wfp_settings_info['setup']['instructions'] ) && strlen( $wfp_settings_info['setup']['instructions'] ) > 4 ) :
																			?>
					<div class="wfp-payment-method-acc-details"><strong
								class="wfp-payment-method-acc-details--title"><?php echo esc_html( apply_filters( 'wfp_donate_forms_payment_method_instructions', esc_html__( 'Instructions:', 'wp-fundraising' ) ) ); ?></strong>
						<span class="wfp-payment-method-acc-details--description"><?php echo esc_html( isset( $wfp_settings_info['setup']['instructions'] ) ? $wfp_settings_info['setup']['instructions'] : '' ); ?></span>
					</div> 
																			<?php

				endif;
																		?>
			</div>
			<?php

			$wfp_checked = '';
		}

		?>
	</div>
</div>
