<?php

$std                       = new stdClass();
$std->enable_custom_amount = 'No';

$fixed_data = empty( $formDonation->fixed ) ? $std : $formDonation->fixed;


if ( $donation_type == 'multi-lebel' ) {

	$displayStyle         = isset( $formDonation->display ) ? $formDonation->display : 'boxed';
	$donationLimit        = isset( $formDonation->set_limit ) ? $formDonation->set_limit : '';
	$enable_custom_amount = isset( $fixedData->enable_custom_amount ) == 'Yes' ? '' : 'readonly';
	?>

	<div class="wfdp-donation-input-form xs-multi-lebel" >
		<div class="xs-donate-field-wrap-group">
			<div class="xs-donate-field-wrap">
				<label for="xs_donate_amount" class="xs-money-symbol xs-money-symbol-before"><?php echo esc_html( $cur_symbol ); ?></label>
				<input type="number" step="any" required min="0" onkeyup="xs_additional_fees(this.value, <?php echo esc_html( $post->ID ); ?>)" onblur="xs_additional_fees(this.value, <?php echo esc_html( $post->ID ); ?>)" name="xs_donate_data_submit[donate_amount]" id="xs_donate_amount" placeholder="<?php echo esc_html( apply_filters( 'donate_placeholder_amount', '1.00' ) ); ?>" class="xs-field xs-money-field xs-text_small" <?php echo esc_attr( $enable_custom_amount ); ?>>
			</div>

			<?php require __DIR__ . '/_partials/limit_details.php'; ?>
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

	$defaultData = (float) $fixed_amount;

	if ( isset( $fixedData->enable_custom_amount ) == 'Yes' ) {
		$amount_dis = '';
	} else {
		$amount_dis = 'readonly';
	}

	?>
	<div class="wfdp-donation-input-form ">
		<div class="wfdp-input-payment-field xs-fixed-lebel oka">
			<div class="xs-donate-field-wrap-group">
				<div class="xs-donate-field-wrap">
					<label class="xs-money-symbol xs-money-symbol-before"><?php echo esc_html( $cur_symbol ); ?></label>
					<input
							type="number"
							step="any"
							required min="0"
						<?php echo esc_attr( ( $customFieldEnable == 'hide' ) ? 'readonly' : '' ); ?>
							name="xs_donate_data_submit[donate_amount]"
							id="xs_donate_amount"
							value="<?php echo esc_attr( $fixed_amount ); ?>"
							placeholder="1.001"
							class="xs-field xs-money-field xs-text_small <?php echo ( $customFieldEnable == 'hide' ) ? 'input-hidden' : ''; ?>" <?php echo esc_attr( $amount_dis ); ?>>
				</div>

				<?php require __DIR__ . '/_partials/limit_details.php'; ?>

			</div>
		</div>
	</div>
	<?php
}
?>
