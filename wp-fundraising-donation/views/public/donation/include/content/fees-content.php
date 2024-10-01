<?php
if ( isset( $add_fees->enable ) && $add_fees->enable == 'Yes' ) {
	$fees_type = isset( $add_fees->fees_type ) ? $add_fees->fees_type : 'percentage';

	$fees_data = isset( $add_fees->fees_amount ) ? $add_fees->fees_amount : '0';
	if ( $fees_type == 'percentage' ) {
		$fees = ( $defaultData * $fees_data ) / 100;
	} else {
		$fees = $fees_data;
	}

	$total_fees = $defaultData + $fees;
	?>
<div class="additional_fees">
	<input type="hidden" value="<?php echo esc_attr( isset( $add_fees->fees_amount ) ? $add_fees->fees_amount : '0' ); ?>" id="xs_donate_additional_fees">
	<input type="hidden" value="<?php echo esc_attr( isset( $add_fees->fees_type ) ? $add_fees->fees_type : 'percentage' ); ?>" id="xs_donate_additional_fees_type">
	<input type="hidden" value="<?php echo esc_attr( $defaultThou_seperator ); ?>" id="xs_donate_currency_thou_seperator">
	<input type="hidden" value="<?php echo esc_attr( $defaultDecimal_seperator ); ?>" id="xs_donate_currency_decimal_seperator">
	<input type="hidden" value="<?php echo esc_attr( $defaultNumberDecimal ); ?>" id="xs_donate_currency_decimal_number">
	
	<input type="hidden" value="<?php echo esc_attr( isset( $add_fees->fees_amount ) ? $add_fees->fees_amount : '0' ); ?>" name="xs_donate_data_submit[addition_fees]">
	<input type="hidden" value="<?php echo esc_attr( $fees ); ?>" name="xs_donate_data_submit[addition_fees_amount]">
	<input type="hidden" value="<?php echo esc_attr( $fees_type ); ?>" name="xs_donate_data_submit[addition_fees_type]">
	
	<p>
		<?php echo wp_kses( do_action( 'wfp_donate_forms_additional_fees_before' ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
		<?php
		$additext = isset( $add_fees->fees_label ) ? esc_html( $add_fees->fees_label ) : 'Fees';
		echo wp_kses( apply_filters( 'wfp_donate_forms_additional_fees', esc_html( $additext ) ), \WfpFundraising\Utilities\Utils::get_kses_array() );
		if ( $fees_type == 'percentage' ) {
			?>
		  (<strong><?php echo esc_html( $fees_data ); ?></strong>%) <?php } ?>: 
		<small class="wfp-currency-symbol"><?php echo wp_kses( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $defaultUse_space ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?></small><span id="xs_donate_additional_fees_view"><strong><?php echo wp_kses( \WfpFundraising\Apps\Settings::wfp_number_format_currency( $fees ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?></strong></span><span><small class="wfp-currency-symbol"><?php echo wp_kses( \WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $defaultUse_space ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?></small></span>
		<?php echo wp_kses( do_action( 'wfp_donate_forms_additional_fees_after' ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
	</p>
	<input type="hidden" id="xs_donate_amount_total_hidden" name="xs_donate_data_submit[donate_amount]" value="<?php echo esc_attr( $total_fees ); ?>">
	<p>
		<?php echo wp_kses( do_action( 'wfp_donate_forms_total_charge_before' ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
		<?php echo esc_html( apply_filters( 'wfp_donate_forms_total_charge', esc_html__( 'Total charge :', 'wp-fundraising' ) ) ); ?>
		 <small class="wfp-currency-symbol"><?php echo wp_kses( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $defaultUse_space ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?></small><span id="xs_donate_amount_total"><strong><?php echo wp_kses( \WfpFundraising\Apps\Settings::wfp_number_format_currency( $total_fees ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?></strong></span><span><small class="wfp-currency-symbol"><?php echo wp_kses( \WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $defaultUse_space ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?></small></span>
		 <?php echo wp_kses( do_action( 'wfp_donate_forms_total_charge_after' ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
	</p>
</div>
	<?php
}
