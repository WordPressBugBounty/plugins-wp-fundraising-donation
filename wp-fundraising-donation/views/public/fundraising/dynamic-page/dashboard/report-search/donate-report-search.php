<?php
	$to_date = gmdate( 'Y-m-d' );

	// Verify nonce
if ( isset( $_GET['donate_report_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['donate_report_nonce'] ) ), 'filter_donate_report' ) ) {
	$fromDate = isset( $_GET['donate_report_from_date'] ) ? sanitize_text_field( wp_unslash( $_GET['donate_report_from_date'] ) ) : gmdate( 'Y-m-' ) . '01';
	$toDate   = isset( $_GET['donate_report_to_date'] ) ? sanitize_text_field( wp_unslash( $_GET['donate_report_to_date'] ) ) : gmdate( 'Y-m-d' );
} else {
	$fromDate = gmdate( 'Y-m-' ) . '01';
	$toDate   = gmdate( 'Y-m-d' );
}

if ( empty( $fromDate ) ) {
	$fromDate = $to_date;
}
if ( empty( $toDate ) ) {
	$toDate = $to_date;
}

?>
<div class="wfdp-income-report">
	<div class="report-search">
		<form action="" method="get">
			<input type="hidden" name="wfp-page" value="<?php echo esc_attr( $getPage ); ?>">
			<?php wp_nonce_field( 'filter_donate_report', 'donate_report_nonce' ); ?>
			<div class="wfp-search-tab-wraper">
				<div class="search-tab">
					<label for="wfdp-forms-search"> <?php echo esc_html__( 'From Date', 'wp-fundraising' ); ?> </label>
					<input type="text" value="<?php echo esc_attr( $fromDate ); ?>" name="donate_report_from_date" class="datepicker-fundrasing" id="donate_report_from_date">
				</div>
				<div class="search-tab">
					<label for="wfdp-forms-search"> <?php echo esc_html__( 'To Date', 'wp-fundraising' ); ?> </label>
					<input type="text" value="<?php echo esc_attr( $toDate ); ?>" name="donate_report_to_date" class="datepicker-fundrasing" id="donate_report_to_date">
				</div>
				
				<div class="search-tab">
					<button class="button button-primary" type="submit"><span class="wfpf wfpf-search"></span></button>
				</div>
			</div>
		</form>
	</div>
</div>
