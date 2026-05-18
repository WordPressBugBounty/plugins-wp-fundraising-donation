<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

	$wfp_to_date = gmdate( 'Y-m-d' );

	// Verify nonce
if ( isset( $_GET['dashboard_report_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['dashboard_report_nonce'] ) ), 'filter_dashboard_report' ) ) {
	$wfpFromDate = isset( $_GET['donate_report_from_date'] ) ? sanitize_text_field( wp_unslash( $_GET['donate_report_from_date'] ) ) : gmdate( 'Y-m-' ) . '01';
	$wfpToDate   = isset( $_GET['donate_report_to_date'] ) ? sanitize_text_field( wp_unslash( $_GET['donate_report_to_date'] ) ) : gmdate( 'Y-m-d' );
} else {
	$wfpFromDate = gmdate( 'Y-m-' ) . '01';
	$wfpToDate   = gmdate( 'Y-m-d' );
}

if ( empty( $wfpFromDate ) ) {
	$wfpFromDate = $wfp_to_date;
}
if ( empty( $wfpToDate ) || $wfp_to_date < $wfpToDate ) {
	$wfpToDate = $wfp_to_date;
}
	$wfp_exp_d    = explode( '-', $wfpToDate );
	$wfpFromDate = $wfp_exp_d[0] . '-' . $wfp_exp_d[1] . '-01';

	$wfp_date1      = date_create( $wfpFromDate );
	$wfp_date2      = date_create( $wfpToDate );
	$wfp_diff       = date_diff( $wfp_date1, $wfp_date2 );
	$wfp_total_days = (int) $wfp_diff->format( '%R%a' );

// print_r($wfp_total_days);
?>
<div class="wfdp-income-report">
	<div class="report-search">
		<form action="" method="get">
			<input type="hidden" name="page" value="<?php echo esc_attr( $wfpGetPage ); ?>">
			<?php wp_nonce_field( 'filter_dashboard_report', 'dashboard_report_nonce' ); ?>
			<div class="wfp-search-tab-wraper">
				
				<div class="search-tab">
					<input type="text" value="<?php echo esc_attr( $wfpToDate ); ?>" name="donate_report_to_date" class="datepicker-fundrasing" id="donate_report_to_date">
				</div>
				
				<div class="search-tab">
					<button class="button button-primary" type="submit"><span class="wfpf wfpf-search"></span></button>
				</div>
			</div>
		</form>
	</div>
</div>
