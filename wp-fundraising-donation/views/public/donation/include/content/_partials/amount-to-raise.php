<?php 
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

?>
<div class="wfp-inner-data">
	<span class="wfp-currency-symbol"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $wfp_defaultUse_space ) ); ?></span>
	<span class="donate-percentage"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfp_total_rasied_amount_fake ) ); ?></span>
	<span class="wfp-currency-symbol"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $wfp_defaultUse_space ) ); ?></span>
</div>
