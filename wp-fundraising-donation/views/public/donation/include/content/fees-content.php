<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

if ( isset( $wfpAdd_fees->enable ) && $wfpAdd_fees->enable == 'Yes' ) {
	$wfp_fees_type = isset( $wfpAdd_fees->fees_type ) ? $wfpAdd_fees->fees_type : 'percentage';

	$wfp_fees_data = isset( $wfpAdd_fees->fees_amount ) ? $wfpAdd_fees->fees_amount : '0';
	if ( $wfp_fees_type == 'percentage' ) {
		$wfp_fees = ( $wfpDefaultData * $wfp_fees_data ) / 100;
	} else {
		$wfp_fees = $wfp_fees_data;
	}

	$wfp_total_fees = $wfpDefaultData + $wfp_fees;
	?>
<div class="additional_fees">
	<input type="hidden" value="<?php echo esc_attr( isset( $wfpAdd_fees->fees_amount ) ? $wfpAdd_fees->fees_amount : '0' ); ?>" id="xs_donate_additional_fees">
	<input type="hidden" value="<?php echo esc_attr( isset( $wfpAdd_fees->fees_type ) ? $wfpAdd_fees->fees_type : 'percentage' ); ?>" id="xs_donate_additional_fees_type">
	<input type="hidden" value="<?php echo esc_attr( $wfp_defaultThou_seperator ); ?>" id="xs_donate_currency_thou_seperator">
	<input type="hidden" value="<?php echo esc_attr( $wfp_defaultDecimal_seperator ); ?>" id="xs_donate_currency_decimal_seperator">
	<input type="hidden" value="<?php echo esc_attr( $wfpDefaultNumberDecimal ); ?>" id="xs_donate_currency_decimal_number">
	
	<input type="hidden" value="<?php echo esc_attr( isset( $wfpAdd_fees->fees_amount ) ? $wfpAdd_fees->fees_amount : '0' ); ?>" name="xs_donate_data_submit[addition_fees]">
	<input type="hidden" value="<?php echo esc_attr( $wfp_fees ); ?>" name="xs_donate_data_submit[addition_fees_amount]">
	<input type="hidden" value="<?php echo esc_attr( $wfp_fees_type ); ?>" name="xs_donate_data_submit[addition_fees_type]">
	
	<p>
		<?php echo wp_kses( do_action( 'wfp_donate_forms_additional_fees_before' ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
		<?php
		$wfp_additext = isset( $wfpAdd_fees->fees_label ) ? esc_html( $wfpAdd_fees->fees_label ) : 'Fees';
		echo wp_kses( apply_filters( 'wfp_donate_forms_additional_fees', esc_html( $wfp_additext ) ), \WfpFundraising\Utilities\Utils::get_kses_array() );
		if ( $wfp_fees_type == 'percentage' ) {
			?>
		  (<strong><?php echo esc_html( $wfp_fees_data ); ?></strong>%) <?php } ?>: 
		<small class="wfp-currency-symbol"><?php echo wp_kses( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $wfp_defaultUse_space ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?></small><span id="xs_donate_additional_fees_view"><strong><?php echo wp_kses( \WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfp_fees ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?></strong></span><span><small class="wfp-currency-symbol"><?php echo wp_kses( \WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $wfp_defaultUse_space ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?></small></span>
		<?php echo wp_kses( do_action( 'wfp_donate_forms_additional_fees_after' ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
	</p>
	<input type="hidden" id="xs_donate_amount_total_hidden" name="xs_donate_data_submit[donate_amount]" value="<?php echo esc_attr( $wfp_total_fees ); ?>">
	<p>
		<?php echo wp_kses( do_action( 'wfp_donate_forms_total_charge_before' ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
		<?php echo esc_html( apply_filters( 'wfp_donate_forms_total_charge', esc_html__( 'Total charge :', 'wp-fundraising-donation' ) ) ); ?>
		 <small class="wfp-currency-symbol"><?php echo wp_kses( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $wfp_defaultUse_space ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?></small><span id="xs_donate_amount_total"><strong><?php echo wp_kses( \WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfp_total_fees ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?></strong></span><span><small class="wfp-currency-symbol"><?php echo wp_kses( \WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $wfp_defaultUse_space ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?></small></span>
		 <?php echo wp_kses( do_action( 'wfp_donate_forms_total_charge_after' ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
	</p>
</div>
	<?php
}
