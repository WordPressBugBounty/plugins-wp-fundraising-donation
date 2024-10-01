<?php

	// Verify nonce
if ( ( isset( $_GET['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['nonce'] ) ), 'wp_rest' ) ) || isset( $_GET['donation_report_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['donation_report_nonce'] ) ), 'filter_donation_report' ) ) {
	$active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'income';

} else {
	$active_tab = 'income';
}

if ( isset( $_GET['donation_report_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['donation_report_nonce'] ) ), 'filter_donation_report' ) ) {
	$fromDate     = isset( $_GET['donation_report_from_date'] ) ? sanitize_text_field( wp_unslash( $_GET['donation_report_from_date'] ) ) : gmdate( 'Y-m-' ) . '01';
	$toDate       = isset( $_GET['donate_report_to_date'] ) ? sanitize_text_field( wp_unslash( $_GET['donate_report_to_date'] ) ) : gmdate( 'Y-m-d' );
	$searchForm   = isset( $_GET['wfdp-forms-search'] ) ? sanitize_text_field( wp_unslash( $_GET['wfdp-forms-search'] ) ) : 'all';
	$statusDonate = isset( $_GET['status_modify'] ) ? sanitize_text_field( wp_unslash( $_GET['status_modify'] ) ) : 'all';
} else {
	$fromDate     = gmdate( 'Y-m-' ) . '01';
	$toDate       = gmdate( 'Y-m-d' );
	$searchForm   = 'all';
	$statusDonate = 'all';
}


?>
<div class="wrap wfp-view wfp-view-admin">
	<div class="wfdp-donation-reports">
		<h1> <?php echo esc_html__( 'Reports', 'wp-fundraising' ); ?></h1>
		<div class="wfp-donation-reports-inner">
			<?php

			require_once __DIR__ . '/reports-tab-menu.php';


			if ( empty( $fromDate ) ) {
				$fromDate = $to_date;
			}
			if ( empty( $toDate ) ) {
				$toDate = $to_date;
			}

			require \WFP_Fundraising::plugin_dir() . 'country-module/country-info.php';
			/*currency information*/

			$getMetaGeneralOp = get_option( \WfpFundraising\Apps\Settings::OK_GENERAL_DATA );
			$getMetaGeneral   = isset( $getMetaGeneralOp['options'] ) ? $getMetaGeneralOp['options'] : array();

			$defaultCurrencyInfo = isset( $getMetaGeneral['currency']['name'] ) ? $getMetaGeneral['currency']['name'] : 'US-USD';
			$explCurr            = explode( '-', $defaultCurrencyInfo );
			$currCode            = isset( $explCurr[1] ) ? $explCurr[1] : 'USD';
			$symbols             = isset( $countryList[ current( $explCurr ) ]['currency']['symbol'] ) ? $countryList[ current( $explCurr ) ]['currency']['symbol'] : '';
			$symbols             = strlen( $symbols ) > 0 ? $symbols : $currCode;
			$form_action_url     = isset( $_SERVER['PHP_SELF'] ) ? sanitize_text_field( wp_unslash( $_SERVER['PHP_SELF'] ) ) : '';

			?>
			<div class="wfdp-income-report">
				<div class="report-search">
					<form action="<?php echo esc_url( $form_action_url ); ?>"
						  method="get">
						<input type="hidden" name="post_type" value="<?php echo esc_attr( self::post_type() ); ?>">
						<input type="hidden" name="page" value="report">
						<input type="hidden" name="tab" value="<?php echo esc_attr( $active_tab ); ?>">
						<?php wp_nonce_field( 'filter_donation_report', 'donation_report_nonce' ); ?>
						<div class="wfp-search-tab-wraper">
							<div class="search-tab">
								<?php
								$getForms = get_posts(
									array(
										'post_type'   => self::post_type(),
										'order'       => 'DESC',
										'numberposts' => -1,
									)
								);
								?>
								<label for="wfdp-forms-search"> <?php echo esc_html__( 'Select Form', 'wp-fundraising' ); ?> </label>
								<select class="wfp-select2-country" name="wfdp-forms-search" id="wfdp-forms-search">
									<option value="all" <?php echo ( isset( $searchForm ) && $searchForm == 'all' ) ? 'selected' : ''; ?> > <?php echo esc_html__( 'All Forms', 'wp-fundraising' ); ?></option>
									<?php
									foreach ( $getForms as $postData ) :
										?>
										<option value="<?php echo esc_attr( $postData->ID ); ?>" <?php echo ( isset( $searchForm ) && $searchForm == $postData->ID ) ? 'selected' : ''; ?> > <?php echo esc_html( $postData->post_title ); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="search-tab">
								<label for="wfdp-forms-search"> <?php echo esc_html__( 'From Date', 'wp-fundraising' ); ?> </label>
								<input type="text" value="<?php echo esc_attr( $fromDate ); ?>" name="donate_report_from_date"
									   class="datepicker-donate" id="donate_report_from_date">
							</div>
							<div class="search-tab">
								<label for="wfdp-forms-search"> <?php echo esc_html__( 'To Date', 'wp-fundraising' ); ?> </label>
								<input type="text" value="<?php echo esc_attr( $toDate ); ?>" name="donate_report_to_date"
									   class="datepicker-donate" id="donate_report_to_date">
							</div>
							<div class="search-tab">
								<label for="wfdp-forms-search"> <?php echo esc_html__( 'Status', 'wp-fundraising' ); ?> </label>
								<select name="status_modify">
									<option value="all" <?php echo isset( $statusDonate ) && $statusDonate == 'all' ? 'selected' : ''; ?> ><?php echo esc_html__( 'All', 'wp-fundraising' ); ?>  </option>
									<option value="Pending" <?php echo isset( $statusDonate ) && $statusDonate == 'Pending' ? 'selected' : ''; ?> ><?php echo esc_html__( 'In Process', 'wp-fundraising' ); ?>  </option>
									<option value="Review" <?php echo isset( $statusDonate ) && $statusDonate == 'Review' ? 'selected' : ''; ?>> <?php echo esc_html__( 'In Review', 'wp-fundraising' ); ?> </option>
									<option value="Active" <?php echo isset( $statusDonate ) && $statusDonate == 'Active' ? 'selected' : ''; ?>> <?php echo esc_html__( 'Success', 'wp-fundraising' ); ?> </option>
									<option value="Refunded" <?php echo isset( $statusDonate ) && $statusDonate == 'Refunded' ? 'selected' : ''; ?>> <?php echo esc_html__( 'Refund', 'wp-fundraising' ); ?> </option>
									<option value="DeActive" <?php echo isset( $statusDonate ) && $statusDonate == 'DeActive' ? 'selected' : ''; ?>> <?php echo esc_html__( 'Cancel', 'wp-fundraising' ); ?> </option>
								</select>
							</div>
							<div class="search-tab">
								<button class="button button-primary" type="submit" name="filter_donation"><span
											class="wfpf wfpf-search"></span>
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>

			<?php
			if ( $active_tab == 'income' ) {
				require __DIR__ . '/include/income-reports.php';
			} elseif ( $active_tab == 'goal' ) {
				require __DIR__ . '/include/goal-reports.php';
			} elseif ( $active_tab == 'donors' ) {
				require __DIR__ . '/include/donors-reports.php';
			}
			?>
		</div>
	</div>
</div>

<script type="text/javascript">
	jQuery(document).ready(function () {
		jQuery('.wfp-select2-country').select2();
	});
</script>
