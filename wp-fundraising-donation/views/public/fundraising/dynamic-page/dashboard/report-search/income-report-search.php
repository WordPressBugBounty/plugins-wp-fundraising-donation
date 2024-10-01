<?php
	$to_date = gmdate( 'Y-m-d' );

	// Verify nonce
if ( isset( $_GET['income_report_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['income_report_nonce'] ) ), 'filter_income_report' ) ) {
	$searchForm   = isset( $_GET['wfdp-forms-search'] ) ? sanitize_text_field( wp_unslash( $_GET['wfdp-forms-search'] ) ) : 'all';
	$statusDonate = isset( $_GET['status_modify'] ) ? sanitize_text_field( wp_unslash( $_GET['status_modify'] ) ) : 'Active';
	$fromDate     = isset( $_GET['donate_report_from_date'] ) ? sanitize_text_field( wp_unslash( $_GET['donate_report_from_date'] ) ) : gmdate( 'Y-m-' ) . '01';
	$toDate       = isset( $_GET['donate_report_to_date'] ) ? sanitize_text_field( wp_unslash( $_GET['donate_report_to_date'] ) ) : gmdate( 'Y-m-d' );
} else {
	$searchForm   = 'all';
	$statusDonate = 'Active';
	$fromDate     = gmdate( 'Y-m-' ) . '01';
	$toDate       = gmdate( 'Y-m-d' );
}

if ( empty( $fromDate ) ) {
	$fromDate = $to_date;
}

if ( empty( $toDate ) ) {
	$toDate = $to_date;
}

	$post_search_id = array();

?>
<div class="wfdp-income-report">
	<div class="report-search">
		<form action="" method="get">
			<input type="hidden" name="wfp-page" value="<?php echo esc_attr( $getPage ); ?>">
			<div class="wfp-search-tab-wraper">
				<div class="search-tab">
					<?php
					$getForms = get_posts(
						array(
							'post_type' => self::post_type(),
							'author'    => $userId,
						)
					);
					 wp_nonce_field( 'filter_income_report', 'income_report_nonce' );
					?>

					<label for="wfdp-forms-search"> <?php echo esc_html__( 'Select Form', 'wp-fundraising' ); ?> </label>
					<select class="" name="wfdp-forms-search" id="wfdp-forms-search">
						<option value="all" <?php echo esc_attr( ( isset( $searchForm ) && $searchForm == 'all' ) ? 'selected' : '' ); ?> > <?php echo esc_html__( 'All Forms', 'wp-fundraising' ); ?></option>
						<?php
						foreach ( $getForms as $postData ) :
							?>
						<option value="<?php echo esc_attr( $postData->ID ); ?>" <?php echo esc_attr( ( isset( $searchForm ) && $searchForm == $postData->ID ) ? 'selected' : '' ); ?> > <?php echo esc_html( $postData->post_title ); ?></option>
							<?php
							$post_search_id[] = $postData->ID;
						endforeach;
						?>
					</select>
				</div>
				<div class="search-tab">
					<label for="wfdp-forms-search"> <?php echo esc_html__( 'From Date', 'wp-fundraising' ); ?> </label>
					<input type="text" value="<?php echo esc_attr( $fromDate ); ?>" name="donate_report_from_date" class="datepicker-fundrasing" id="donate_report_from_date">
				</div>
				<div class="search-tab">
					<label for="wfdp-forms-search"> <?php echo esc_html__( 'To Date', 'wp-fundraising' ); ?> </label>
					<input type="text" value="<?php echo esc_attr( $toDate ); ?>" name="donate_report_to_date" class="datepicker-fundrasing" id="donate_report_to_date">
				</div>
				<div class="search-tab">
					<label for="wfdp-forms-search"> <?php echo esc_html__( 'Status', 'wp-fundraising' ); ?> </label>
					<select name="status_modify" >
						<option value="all" <?php echo esc_attr( isset( $statusDonate ) && $statusDonate == 'all' ? 'selected' : '' ); ?> ><?php echo esc_html__( 'All', 'wp-fundraising' ); ?>  </option>
						<option value="Pending" <?php echo esc_attr( isset( $statusDonate ) && $statusDonate == 'Pending' ? 'selected' : '' ); ?> ><?php echo esc_html__( 'In Process', 'wp-fundraising' ); ?>  </option>
						<option value="Review" <?php echo esc_attr( isset( $statusDonate ) && $statusDonate == 'Review' ? 'selected' : '' ); ?>> <?php echo esc_html__( 'In Review', 'wp-fundraising' ); ?> </option>
						<option value="Active" <?php echo esc_attr( isset( $statusDonate ) && $statusDonate == 'Active' ? 'selected' : '' ); ?>> <?php echo esc_html__( 'Success', 'wp-fundraising' ); ?> </option>
						<option value="Refunded" <?php echo esc_attr( isset( $statusDonate ) && $statusDonate == 'Refunded' ? 'selected' : '' ); ?>> <?php echo esc_html__( 'Refund', 'wp-fundraising' ); ?> </option>
						<option value="DeActive" <?php echo esc_attr( isset( $statusDonate ) && $statusDonate == 'DeActive' ? 'selected' : '' ); ?>> <?php echo esc_html__( 'Cancel', 'wp-fundraising' ); ?> </option>
					</select>
				</div>
				<div class="search-tab">
					<button class="button button-primary" type="submit"><span class="wfpf wfpf-search"></span></button>
				</div>
			</div>
		</form>
	</div>
</div>
