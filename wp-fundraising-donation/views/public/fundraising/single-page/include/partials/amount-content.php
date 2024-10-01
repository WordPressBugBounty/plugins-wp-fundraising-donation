<?php

require \WFP_Fundraising::plugin_dir() . 'country-module/country-info.php';

$explCurr  = explode( '-', $defCurrencyInfo );
$currCode  = isset( $explCurr[1] ) ? $explCurr[1] : 'USD';
$countCode = isset( $explCurr[0] ) ? $explCurr[0] : 'US';
$symbols   = isset( $countryList[ $countCode ]['currency']['symbol'] ) ? $countryList[ $countCode ]['currency']['symbol'] : '';
$symbols   = strlen( $symbols ) > 0 ? $symbols : $currCode;
$symbols   = apply_filters( 'wfp_donate_amount_symbol', $symbols, $countryList, $countCode );


if ( $donation_type == 'multi-lebel' ) {

	$displayStyle  = isset( $formDonation->display ) ? $formDonation->display : 'boxed';
	$donationLimit = isset( $formDonation->set_limit ) ? $formDonation->set_limit : '';

	?>

	<div class="wfdp-donation-input-form xs-multi-lebel">
		<div class="xs-donate-field-wrap-group">
			<div class="xs-donate-field-wrap">
				<label for="xs_donate_amount"
					   class="xs-money-symbol xs-money-symbol-before"><?php echo esc_html( $symbols ); ?></label>
				<input type="number" step="any" required min="0" onkeyup="xs_additional_fees(this.value, <?php echo esc_attr( $postId ); ?>)"
					   onblur="xs_additional_fees(this.value, <?php echo esc_attr( $postId ); ?>)"
					   name="xs_donate_data_submit[donate_amount]" id="xs_donate_amount"
					   placeholder="<?php echo wp_kses( apply_filters( 'donate_placeholder_amount', '1.00' ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>"
					   class="xs-field xs-money-field xs-text_small">
			</div>

			<?php require __DIR__ . '/limit_details.php'; ?>
		</div>

		<?php

		$multiData = apply_filters( 'wfp_donate_multi_amount', $multiData );

		if ( $displayStyle == 'boxed' || $displayStyle == '' ) {

			require __DIR__ . '/amount-in-boxed.php';

		} elseif ( $displayStyle == 'radio' ) {

			require __DIR__ . '/amount-in-radio.php';

		} elseif ( $displayStyle == 'dropdown' ) {

			require __DIR__ . '/amount-in-dropdown.php';
		}

		?>

	</div>

	<?php

} else {

	$customFieldEnable = ( isset( $fixedData->enable_custom_amount ) && $fixedData->enable_custom_amount == 'Yes' ) ? 'show' : 'hide';

	$fixed_amount = empty( $fixedData->price ) ? 0 : $fixedData->price;

	$defaultData = $fixed_amount;

	?>
	<div class="wfdp-donation-input-form ">
		<div class="wfdp-input-payment-field xs-fixed-lebel oka">
			<div class="xs-donate-field-wrap-group">
				<div class="xs-donate-field-wrap">
					<label class="xs-money-symbol xs-money-symbol-before"><?php echo wp_kses( $symbols, \WfpFundraising\Utilities\Utils::get_kses_array() ); ?></label>
					<input type="number" step="any" required
						   min="0" <?php echo ( $customFieldEnable == 'hide' ) ? 'readonly' : ''; ?>
						   onkeyup="xs_additional_fees(this.value, <?php echo esc_attr( $postId ); ?>)"
						   onblur="xs_additional_fees(this.value, <?php echo esc_attr( $postId ); ?>)"
						   name="xs_donate_data_submit[donate_amount]" id="xs_donate_amount"
						   value="<?php echo esc_attr( $fixed_amount ); ?>" placeholder="1.00"
						   class="xs-field xs-money-field xs-text_small <?php echo ( $customFieldEnable == 'hide' ) ? 'input-hidden' : ''; ?>">
				</div>

				<?php require __DIR__ . '/limit_details.php'; ?>

			</div>
		</div>
	</div>
	<?php
}
