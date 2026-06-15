<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

	// Verify nonce
if ( ( isset( $_GET['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['nonce'] ) ), 'wp_rest' ) ) || isset( $_GET['donation_report_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['donation_report_nonce'] ) ), 'filter_donation_report' ) ) {
	$wfp_active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'income';

} else {
	$wfp_active_tab = 'income';
}

if ( isset( $_GET['donation_report_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['donation_report_nonce'] ) ), 'filter_donation_report' ) ) {
	$wfpFromDate     = isset( $_GET['donation_report_from_date'] ) ? sanitize_text_field( wp_unslash( $_GET['donation_report_from_date'] ) ) : gmdate( 'Y-m-' ) . '01';
	$wfpToDate       = isset( $_GET['donate_report_to_date'] ) ? sanitize_text_field( wp_unslash( $_GET['donate_report_to_date'] ) ) : gmdate( 'Y-m-d' );
	$wfpSearchForm   = isset( $_GET['wfdp-forms-search'] ) ? sanitize_text_field( wp_unslash( $_GET['wfdp-forms-search'] ) ) : 'all';
	$wfpStatusDonate = isset( $_GET['status_modify'] ) ? sanitize_text_field( wp_unslash( $_GET['status_modify'] ) ) : 'all';
} else {
	$wfpFromDate     = gmdate( 'Y-m-' ) . '01';
	$wfpToDate       = gmdate( 'Y-m-d' );
	$wfpSearchForm   = 'all';
	$wfpStatusDonate = 'all';
}


?>
<div class="wrap wfp-view wfp-view-admin">
	<div class="wfdp-donation-reports">
		<h1> <?php echo esc_html__( 'Reports', 'wp-fundraising-donation' ); ?></h1>
		<div class="wfp-donation-reports-inner">
			<?php

			require_once __DIR__ . '/reports-tab-menu.php';


			if ( empty( $wfpFromDate ) ) {
				$wfpFromDate = $wfp_to_date;
			}
			if ( empty( $wfpToDate ) ) {
				$wfpToDate = $wfp_to_date;
			}

			require \WFP_Fundraising::plugin_dir() . 'country-module/country-info.php';
			/*currency information*/

			$wfpGetMetaGeneralOp = get_option( \WfpFundraising\Apps\Settings::OK_GENERAL_DATA );
			$wfpGetMetaGeneral   = isset( $wfpGetMetaGeneralOp['options'] ) ? $wfpGetMetaGeneralOp['options'] : array();

			$wfpDefaultCurrencyInfo = isset( $wfpGetMetaGeneral['currency']['name'] ) ? $wfpGetMetaGeneral['currency']['name'] : 'US-USD';
			$wfpExplCurr            = explode( '-', $wfpDefaultCurrencyInfo );
			$wfpCurrCode            = isset( $wfpExplCurr[1] ) ? $wfpExplCurr[1] : 'USD';
			$wfpSymbols             = isset( $wfpCountryList[ current( $wfpExplCurr ) ]['currency']['symbol'] ) ? $wfpCountryList[ current( $wfpExplCurr ) ]['currency']['symbol'] : '';
			$wfpSymbols             = strlen( $wfpSymbols ) > 0 ? $wfpSymbols : $wfpCurrCode;
			$wfp_form_action_url     = isset( $_SERVER['PHP_SELF'] ) ? sanitize_text_field( wp_unslash( $_SERVER['PHP_SELF'] ) ) : '';

			?>
			<div class="wfdp-income-report">
				<div class="report-search">
					<form action="<?php echo esc_url( $wfp_form_action_url ); ?>"
						  method="get">
						<input type="hidden" name="post_type" value="<?php echo esc_attr( self::post_type() ); ?>">
						<input type="hidden" name="page" value="report">
						<input type="hidden" name="tab" value="<?php echo esc_attr( $wfp_active_tab ); ?>">
						<?php wp_nonce_field( 'filter_donation_report', 'donation_report_nonce' ); ?>
						<div class="wfp-search-tab-wraper">
							<div class="search-tab">
								<?php
								$wfpGetForms = get_posts(
									array(
										'post_type'   => self::post_type(),
										'order'       => 'DESC',
										'numberposts' => -1,
									)
								);
								?>
								<label for="wfdp-forms-search"> <?php echo esc_html__( 'Select Form', 'wp-fundraising-donation' ); ?> </label>
								<select class="wfp-select2-country" name="wfdp-forms-search" id="wfdp-forms-search">
									<option value="all" <?php echo ( isset( $wfpSearchForm ) && $wfpSearchForm == 'all' ) ? 'selected' : ''; ?> > <?php echo esc_html__( 'All Forms', 'wp-fundraising-donation' ); ?></option>
									<?php
									foreach ( $wfpGetForms as $postData ) :
										?>
										<option value="<?php echo esc_attr( $postData->ID ); ?>" <?php echo ( isset( $wfpSearchForm ) && $wfpSearchForm == $postData->ID ) ? 'selected' : ''; ?> > <?php echo esc_html( $postData->post_title ); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="search-tab">
								<label for="wfdp-forms-search"> <?php echo esc_html__( 'From Date', 'wp-fundraising-donation' ); ?> </label>
								<input type="text" value="<?php echo esc_attr( $wfpFromDate ); ?>" name="donate_report_from_date"
									   class="datepicker-donate" id="donate_report_from_date">
							</div>
							<div class="search-tab">
								<label for="wfdp-forms-search"> <?php echo esc_html__( 'To Date', 'wp-fundraising-donation' ); ?> </label>
								<input type="text" value="<?php echo esc_attr( $wfpToDate ); ?>" name="donate_report_to_date"
									   class="datepicker-donate" id="donate_report_to_date">
							</div>
							<div class="search-tab">
								<label for="wfdp-forms-search"> <?php echo esc_html__( 'Status', 'wp-fundraising-donation' ); ?> </label>
								<select name="status_modify">
									<option value="all" <?php echo isset( $wfpStatusDonate ) && $wfpStatusDonate == 'all' ? 'selected' : ''; ?> ><?php echo esc_html__( 'All', 'wp-fundraising-donation' ); ?>  </option>
									<option value="Pending" <?php echo isset( $wfpStatusDonate ) && $wfpStatusDonate == 'Pending' ? 'selected' : ''; ?> ><?php echo esc_html__( 'In Process', 'wp-fundraising-donation' ); ?>  </option>
									<option value="Review" <?php echo isset( $wfpStatusDonate ) && $wfpStatusDonate == 'Review' ? 'selected' : ''; ?>> <?php echo esc_html__( 'In Review', 'wp-fundraising-donation' ); ?> </option>
									<option value="Active" <?php echo isset( $wfpStatusDonate ) && $wfpStatusDonate == 'Active' ? 'selected' : ''; ?>> <?php echo esc_html__( 'Success', 'wp-fundraising-donation' ); ?> </option>
									<option value="Refunded" <?php echo isset( $wfpStatusDonate ) && $wfpStatusDonate == 'Refunded' ? 'selected' : ''; ?>> <?php echo esc_html__( 'Refund', 'wp-fundraising-donation' ); ?> </option>
									<option value="DeActive" <?php echo isset( $wfpStatusDonate ) && $wfpStatusDonate == 'DeActive' ? 'selected' : ''; ?>> <?php echo esc_html__( 'Cancel', 'wp-fundraising-donation' ); ?> </option>
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
			if ( $wfp_active_tab == 'income' ) {
				require __DIR__ . '/include/income-reports.php';
			} elseif ( $wfp_active_tab == 'goal' ) {
				require __DIR__ . '/include/goal-reports.php';
			} elseif ( $wfp_active_tab == 'donors' ) {
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
