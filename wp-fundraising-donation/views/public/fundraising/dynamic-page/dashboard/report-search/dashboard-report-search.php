<?php
	$to_date = gmdate( 'Y-m-d' );

	// Verify nonce
if ( isset( $_GET['dashboard_report_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['dashboard_report_nonce'] ) ), 'filter_dashboard_report' ) ) {
	$fromDate = isset( $_GET['donate_report_from_date'] ) ? sanitize_text_field( wp_unslash( $_GET['donate_report_from_date'] ) ) : gmdate( 'Y-m-' ) . '01';
	$toDate   = isset( $_GET['donate_report_to_date'] ) ? sanitize_text_field( wp_unslash( $_GET['donate_report_to_date'] ) ) : gmdate( 'Y-m-d' );
} else {
	$fromDate = gmdate( 'Y-m-' ) . '01';
	$toDate   = gmdate( 'Y-m-d' );
}

if ( empty( $fromDate ) ) {
	$fromDate = $to_date;
}
if ( empty( $toDate ) || $to_date < $toDate ) {
	$toDate = $to_date;
}
	$exp_d    = explode( '-', $toDate );
	$fromDate = $exp_d[0] . '-' . $exp_d[1] . '-01';

	$date1      = date_create( $fromDate );
	$date2      = date_create( $toDate );
	$diff       = date_diff( $date1, $date2 );
	$total_days = (int) $diff->format( '%R%a' );

// print_r($total_days);
?>
<div class="wfdp-income-report">
	<div class="report-search">
		<form action="" method="get">
			<input type="hidden" name="page" value="<?php echo esc_attr( $getPage ); ?>">
			<?php wp_nonce_field( 'filter_dashboard_report', 'dashboard_report_nonce' ); ?>
			<div class="wfp-search-tab-wraper">
				
				<div class="search-tab">
					<input type="text" value="<?php echo esc_attr( $toDate ); ?>" name="donate_report_to_date" class="datepicker-fundrasing" id="donate_report_to_date">
				</div>
				
				<div class="search-tab">
					<button class="button button-primary" type="submit"><span class="wfpf wfpf-search"></span></button>
				</div>
			</div>
		</form>
	</div>
</div>
