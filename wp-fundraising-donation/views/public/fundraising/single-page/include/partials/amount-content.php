<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

require \WFP_Fundraising::plugin_dir() . 'country-module/country-info.php';

$wfpExplCurr  = explode( '-', $wfpDefCurrencyInfo );
$wfpCurrCode  = isset( $wfpExplCurr[1] ) ? $wfpExplCurr[1] : 'USD';
$wfpCountCode = isset( $wfpExplCurr[0] ) ? $wfpExplCurr[0] : 'US';
$wfpSymbols   = isset( $wfpCountryList[ $wfpCountCode ]['currency']['symbol'] ) ? $wfpCountryList[ $wfpCountCode ]['currency']['symbol'] : '';
$wfpSymbols   = strlen( $wfpSymbols ) > 0 ? $wfpSymbols : $wfpCurrCode;
$wfpSymbols   = apply_filters( 'wfp_fundraising_donate_amount_symbol', $wfpSymbols, $wfpCountryList, $wfpCountCode );


if ( $wfp_donation_type == 'multi-lebel' ) {

	$wfpDisplayStyle  = isset( $wfpFormDonation->display ) ? $wfpFormDonation->display : 'boxed';
	$wfpDonationLimit = isset( $wfpFormDonation->set_limit ) ? $wfpFormDonation->set_limit : '';

	?>

	<div class="wfdp-donation-input-form xs-multi-lebel">
		<div class="xs-donate-field-wrap-group">
			<div class="xs-donate-field-wrap">
				<label for="xs_donate_amount"
					   class="xs-money-symbol xs-money-symbol-before"><?php echo esc_html( $wfpSymbols ); ?></label>
				<input type="number" step="any" required min="0" onkeyup="xs_additional_fees(this.value, <?php echo esc_attr( $wfpPostId ); ?>)"
					   onblur="xs_additional_fees(this.value, <?php echo esc_attr( $wfpPostId ); ?>)"
					   name="xs_donate_data_submit[donate_amount]" id="xs_donate_amount"
					   placeholder="<?php echo wp_kses( apply_filters( 'donate_placeholder_amount', '1.00' ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>"
					   class="xs-field xs-money-field xs-text_small">
			</div>

			<?php require __DIR__ . '/limit_details.php'; ?>
		</div>

		<?php

		$wfpMultiData = apply_filters( 'wfp_donate_multi_amount', $wfpMultiData );

		if ( $wfpDisplayStyle == 'boxed' || $wfpDisplayStyle == '' ) {

			require __DIR__ . '/amount-in-boxed.php';

		} elseif ( $wfpDisplayStyle == 'radio' ) {

			require __DIR__ . '/amount-in-radio.php';

		} elseif ( $wfpDisplayStyle == 'dropdown' ) {

			require __DIR__ . '/amount-in-dropdown.php';
		}

		?>

	</div>

	<?php

} else {

	$wfpCustomFieldEnable = ( isset( $wfpFixedData->enable_custom_amount ) && $wfpFixedData->enable_custom_amount == 'Yes' ) ? 'show' : 'hide';

	$wfp_fixed_amount = empty( $wfpFixedData->price ) ? 0 : $wfpFixedData->price;

	$wfpDefaultData = $wfp_fixed_amount;

	?>
	<div class="wfdp-donation-input-form ">
		<div class="wfdp-input-payment-field xs-fixed-lebel oka">
			<div class="xs-donate-field-wrap-group">
				<div class="xs-donate-field-wrap">
					<label class="xs-money-symbol xs-money-symbol-before"><?php echo wp_kses( $wfpSymbols, \WfpFundraising\Utilities\Utils::get_kses_array() ); ?></label>
					<input type="number" step="any" required
						   min="0" <?php echo ( $wfpCustomFieldEnable == 'hide' ) ? 'readonly' : ''; ?>
						   onkeyup="xs_additional_fees(this.value, <?php echo esc_attr( $wfpPostId ); ?>)"
						   onblur="xs_additional_fees(this.value, <?php echo esc_attr( $wfpPostId ); ?>)"
						   name="xs_donate_data_submit[donate_amount]" id="xs_donate_amount"
						   value="<?php echo esc_attr( $wfp_fixed_amount ); ?>" placeholder="1.00"
						   class="xs-field xs-money-field xs-text_small <?php echo ( $wfpCustomFieldEnable == 'hide' ) ? 'input-hidden' : ''; ?>">
				</div>

				<?php require __DIR__ . '/limit_details.php'; ?>

			</div>
		</div>
	</div>
	<?php
}
