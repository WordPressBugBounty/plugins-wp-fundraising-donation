<div class="wfdp-donation-input-form wfdp-input-payment-field-wraper <?php echo esc_attr( $enableDisplayField ); ?>">
	<div class="wfdp-input-payment-field">
		<?php echo wp_kses( do_action( 'wfp_donate_forms_payment_method_headding_before' ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
		<span class=""> <?php echo wp_kses( apply_filters( 'wfp_donate_forms_payment_method_headding', esc_html__( 'Select Payment Method:', 'wp-fundraising' ) ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?></span>
		<?php echo wp_kses( do_action( 'wfp_donate_forms_payment_method_headding_after' ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>

		<ul class="xs-donate-display-amount wfp-radio-input-style-2">
		<?php
		$optionsData = isset( $gateWaysData['services'] ) ? $gateWaysData['services'] : array();

		if ( ! empty( $optionsData ) && is_array( $optionsData ) ) {
			$m = 0;
			foreach ( $optionsData as $key => $payment ) :
				if ( isset( $optionsData[ $key ]['enable'] ) && $optionsData[ $key ]['enable'] == 'Yes' ) :
					$defultCheck = '';
					if ( $m == 0 ) {
						$defultCheck = 'checked';
					}

					$infoData = isset( $optionsData[ $key ]['setup'] ) ? $optionsData[ $key ]['setup'] : array();

					?>
						<li>
							<input class="xs_radio_filed" id="wfp_<?php echo esc_attr( isset( $infoData['title'] ) ? str_replace( ' ', '_', strtolower( $infoData['title'] ) ) : '' ); ?>" type="radio" name="xs_donate_data_submit[payment_method]" <?php echo esc_attr( $defultCheck ); ?> value="<?php echo esc_attr( $key ); ?>" onchange="xs_show_hide_multiple_div('.payment_method_info', '.method-<?php echo esc_attr( $key ); ?>');"> 
							<label for="wfp_<?php echo esc_attr( isset( $infoData['title'] ) ? str_replace( ' ', '_', strtolower( $infoData['title'] ) ) : '' ); ?>"><?php echo esc_attr( isset( $infoData['title'] ) ? $infoData['title'] : '' ); ?></label>
						</li>
					<?php
					$m++;
				endif;
			endforeach;
		} else {
			echo wp_kses( '<p> ' . __( 'Set Payment Method', 'wp-fundraising' ) . ' </p>', \WfpFundraising\Utilities\Utils::get_kses_array() );
		}
		?>
		</ul>
	</div>
</div>
<div class="wfdp-input-payment-field wfdp-donation-payment-details <?php echo esc_attr( $enableDisplayField ); ?>">
	<div class="xs-donate-display-amount">
	<?php
	$m = 0;
	foreach ( $optionsData as $key => $payment ) :
		if ( isset( $optionsData[ $key ]['enable'] ) && $optionsData[ $key ]['enable'] == 'Yes' ) :
			$defultCheck = '';
			if ( $m == 0 ) {
				$defultCheck = 'yes';
			}

			$infoData = isset( $optionsData[ $key ]['setup'] ) ? $optionsData[ $key ]['setup'] : array();

			?>
			<div class="payment_method_info method-<?php echo esc_attr( $key ); ?> xs-donate-hidden <?php echo ( $defultCheck == 'yes' ) ? 'xs-donate-visible' : ''; ?>">
				<h2 class="wfp-payment-method-title fdas"><?php echo esc_attr( isset( $infoData['title'] ) ? $infoData['title'] : '' ); ?> </h2>
			<?php
			if ( $key == 'bank_payment' ) {
				$setupData = isset( $arrayPayment[ $key ]['setup']['account_details'] ) ? $arrayPayment[ $key ]['setup']['account_details'] : array();
				?>
			<div> <strong> <?php echo esc_html__( 'Account Details:', 'wp-fundraising' ); ?></strong></div>
				<?php
				if ( isset( $infoData['account_details'] ) ) {
					?>
					<div class="xs-table-responsive wfdp-table-design">
						<table class="form-table wc_gateways widefat payment-details">
							<thead>
								<tr>
									<th>SL.</th>
							<?php
							foreach ( $setupData as $subKeyHead => $setupDetails ) :
								$labelNameSub = ucfirst( str_replace( array( '_', '-' ), ' ', $subKeyHead ) );
								?>
								<th class="name"> <?php echo esc_html( $labelNameSub ); ?></th>
								<?php
							endforeach;
							?>
								</tr>
							</thead>
							<tbody>
							
							<?php
							$mm = 1;
							foreach ( $infoData['account_details'] as $account ) :
								?>
								<tr>
									<td><?php echo esc_attr( $mm ) . '. '; ?></td>
									<?php foreach ( $setupData as $subKeyHead => $setupDetails ) : ?>
										<td>
											<?php echo esc_html( $account[ $subKeyHead ] ); ?>
										</td>
									<?php endforeach; ?>
								</tr>
								<?php
								$mm++;
							endforeach;
							?>
							</tbody>
						</table>
					</div>
					<?php
				}
				?>
			
			
				<?php
			}
			if ( isset( $infoData['description'] ) && strlen( $infoData['description'] ) > 4 ) :
				?>
				<div class="wfp-payment-method-acc-details"> <strong class="wfp-payment-method-acc-details--title"><?php echo wp_kses( apply_filters( 'wfp_donate_forms_payment_method_details', esc_html__( 'Details:', 'wp-fundraising' ) ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?></strong>
					<span class="wfp-payment-method-acc-details--description"><?php echo esc_html( isset( $infoData['description'] ) ? $infoData['description'] : '' ); ?></span>
				</div>
				<?php
			endif;
			if ( isset( $infoData['instructions'] ) && strlen( $infoData['instructions'] ) > 4 ) :
				?>
				<div class="wfp-payment-method-acc-details"> <strong class="wfp-payment-method-acc-details--title"><?php echo wp_kses( apply_filters( 'wfp_donate_forms_payment_method_instructions', esc_html__( 'Instructions:', 'wp-fundraising' ) ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?></strong>
					<span class="wfp-payment-method-acc-details--description"><?php echo esc_html( isset( $infoData['instructions'] ) ? $infoData['instructions'] : '' ); ?></span>
				</div>
			<?php endif; ?>
			</div>
			<?php
			$m++;
		endif;
	endforeach;
	?>
	</div>
</div>
