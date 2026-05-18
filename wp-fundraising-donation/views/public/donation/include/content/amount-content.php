<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

$wfp_std                       = new stdClass();
$wfp_std->enable_custom_amount = 'No';

$wfp_fixed_data = empty( $wfpFormDonation->fixed ) ? $wfp_std : $wfpFormDonation->fixed;


if ( $wfp_donation_type == 'multi-lebel' ) {

	$wfpDisplayStyle         = isset( $wfpFormDonation->display ) ? $wfpFormDonation->display : 'boxed';
	$wfpDonationLimit        = isset( $wfpFormDonation->set_limit ) ? $wfpFormDonation->set_limit : '';
	$wfp_enable_custom_amount = isset( $wfpFixedData->enable_custom_amount ) == 'Yes' ? '' : 'readonly';
	?>

	<div class="wfdp-donation-input-form xs-multi-lebel" >
		<div class="xs-donate-field-wrap-group">
			<div class="xs-donate-field-wrap">
				<label for="xs_donate_amount" class="xs-money-symbol xs-money-symbol-before"><?php echo esc_html( $wfp_cur_symbol ); ?></label>
				<input type="number" step="any" required min="0" onkeyup="xs_additional_fees(this.value, <?php echo esc_html( $post->ID ); ?>)" onblur="xs_additional_fees(this.value, <?php echo esc_html( $post->ID ); ?>)" name="xs_donate_data_submit[donate_amount]" id="xs_donate_amount" placeholder="<?php echo esc_html( apply_filters( 'donate_placeholder_amount', '1.00' ) ); ?>" class="xs-field xs-money-field xs-text_small" <?php echo esc_attr( $wfp_enable_custom_amount ); ?>>
			</div>

			<?php require __DIR__ . '/_partials/limit_details.php'; ?>
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

	$wfpDefaultData = (float) $wfp_fixed_amount;

	if ( isset( $wfpFixedData->enable_custom_amount ) == 'Yes' ) {
		$wfp_amount_dis = '';
	} else {
		$wfp_amount_dis = 'readonly';
	}

	?>
	<div class="wfdp-donation-input-form ">
		<div class="wfdp-input-payment-field xs-fixed-lebel oka">
			<div class="xs-donate-field-wrap-group">
				<div class="xs-donate-field-wrap">
					<label class="xs-money-symbol xs-money-symbol-before"><?php echo esc_html( $wfp_cur_symbol ); ?></label>
					<input
							type="number"
							step="any"
							required min="0"
						<?php echo esc_attr( ( $wfpCustomFieldEnable == 'hide' ) ? 'readonly' : '' ); ?>
							name="xs_donate_data_submit[donate_amount]"
							id="xs_donate_amount"
							value="<?php echo esc_attr( $wfp_fixed_amount ); ?>"
							placeholder="1.001"
							class="xs-field xs-money-field xs-text_small <?php echo ( $wfpCustomFieldEnable == 'hide' ) ? 'input-hidden' : ''; ?>" <?php echo esc_attr( $wfp_amount_dis ); ?>>
				</div>

				<?php require __DIR__ . '/_partials/limit_details.php'; ?>

			</div>
		</div>
	</div>
	<?php
}
?>
