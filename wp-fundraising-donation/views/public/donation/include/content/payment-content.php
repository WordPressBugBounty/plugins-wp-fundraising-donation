<div class="wfdp-donation-input-form wfdp-input-payment-field-wraper">
	<div class="wfdp-input-payment-field">
		<?php echo wp_kses( do_action( 'wfp_donate_forms_payment_method_headding_before' ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
		<span class=""> <?php echo esc_html( apply_filters( 'wfp_donate_forms_payment_method_headding', __( 'Select Payment Method:', 'wp-fundraising' ) ) ); ?></span>
		<?php echo wp_kses( do_action( 'wfp_donate_forms_payment_method_headding_after' ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
		<ul class="xs-donate-display-amount wfp-radio-input-style-2">
			<?php

			if ( empty( $payment_settings ) ) {

				echo wp_kses( '<p> ' . __( 'No payment method is set up.', 'wp-fundraising' ) . ' </p>', \WfpFundraising\Utilities\Utils::get_kses_array() );

			} else {

				$checked = 'checked';

				foreach ( $payment_settings as $account => $settings_info ) {

					$title  = empty( $settings_info['setup']['title'] ) ? '' : $settings_info['setup']['title'];
					$dom_id = 'wfp_' . $account;
					?>

					<li>
						<input class="xs_radio_filed" id="<?php echo esc_attr( $dom_id ); ?>" type="radio"
							   name="xs_donate_data_submit[payment_method]" <?php echo esc_attr( $checked ); ?>
							   value="<?php echo esc_attr( $account ); ?>"
							   onchange="xs_show_hide_multiple_div('.payment_method_info', '.method-<?php echo esc_attr( $account ); ?>');">
						<label for="<?php echo esc_attr( $dom_id ); ?>"><?php echo esc_html( $title ); ?></label>
					</li>
					<?php

					$checked = '';
				}
			}

			?>
		</ul>
	</div>
</div>
<div class="wfdp-input-payment-field wfdp-donation-payment-details">
	<div class="xs-donate-display-amount">
		<?php

		$checked = 'yes';

		foreach ( $payment_settings as $account => $settings_info ) {

			$title = empty( $settings_info['setup']['title'] ) ? '' : $settings_info['setup']['title'];

			?>
			<div class="payment_method_info method-<?php echo esc_attr( $account ); ?> xs-donate-hidden <?php echo esc_attr( ( $checked == 'yes' ) ? 'xs-donate-visible' : '' ); ?>">
				<h2 class="wfp-payment-method-title fdas"><?php echo esc_html( $title ); ?> </h2> 
																	 <?php

																		if ( $account == 'bank_payment' ) {
																			?>

					<div><strong> <?php echo esc_html__( 'Account Details:', 'wp-fundraising' ); ?></strong></div> 
																			<?php

																			if ( ! empty( $settings_info['setup']['account_details'] ) ) {

																				$setupData = $settings_info['setup']['account_details'];

																				?>
						<div class="xs-table-responsive wfdp-table-design">
							<table class="form-table wc_gateways widefat payment-details">
								<thead>
								<tr>
									<th>SL.</th> 
																							<?php

																							foreach ( $setupData as $setupDatum ) {

																								foreach ( $setupDatum as $subKeyHead => $setupDetails ) {

																									$labelNameSub = ucfirst( str_replace( array( '_', '-' ), ' ', $subKeyHead ) );
																									?>

											<th class="name"> <?php echo esc_html( $labelNameSub ); ?></th> 
																									<?php
																								}

																								break;
																							}
																							?>
								</tr>
								</thead>
								<tbody>
																							<?php
																							foreach ( $setupData as $count => $setupDatum ) {
																								?>
										<tr><td><?php echo esc_html( ++$count . '. ' ); ?></td>
																								<?php
																								foreach ( $setupDatum as $subKeyHead => $setupDetails ) {
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

																		if ( isset( $settings_info['setup']['description'] ) && strlen( $settings_info['setup']['description'] ) > 4 ) :
																			?>
					<div class="wfp-payment-method-acc-details"><strong
								class="wfp-payment-method-acc-details--title"><?php echo esc_html( apply_filters( 'wfp_donate_forms_payment_method_details', esc_html__( 'Details:', 'wp-fundraising' ) ) ); ?></strong>
						<span class="wfp-payment-method-acc-details--description"><?php echo wp_kses( isset( $settings_info['setup']['description'] ) ? $settings_info['setup']['description'] : '', \WfpFundraising\Utilities\Utils::get_kses_array() ); ?></span>
					</div>
																			<?php

				endif;

																		if ( isset( $settings_info['setup']['instructions'] ) && strlen( $settings_info['setup']['instructions'] ) > 4 ) :
																			?>
					<div class="wfp-payment-method-acc-details"><strong
								class="wfp-payment-method-acc-details--title"><?php echo esc_html( apply_filters( 'wfp_donate_forms_payment_method_instructions', esc_html__( 'Instructions:', 'wp-fundraising' ) ) ); ?></strong>
						<span class="wfp-payment-method-acc-details--description"><?php echo esc_html( isset( $settings_info['setup']['instructions'] ) ? $settings_info['setup']['instructions'] : '' ); ?></span>
					</div> 
																			<?php

				endif;
																		?>
			</div>
			<?php

			$checked = '';
		}

		?>
	</div>
</div>
