<div class="wfp-inner-data">
	<span class="wfp-currency-symbol"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $defaultUse_space ) ); ?></span>
	<span class="donate-percentage"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $total_raised ) ); ?></span>
	<span class="wfp-currency-symbol"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $defaultUse_space ) ); ?></span>
</div>
