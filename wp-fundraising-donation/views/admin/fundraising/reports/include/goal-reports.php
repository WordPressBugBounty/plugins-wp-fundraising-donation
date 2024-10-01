<div class="wfdp-income-report">
	<div class="wfp-report-headding">
		<h2><?php echo esc_html__( 'Goal Statements', 'wp-fundraising' ); ?></h2>
		<p class="period"><?php echo esc_html__( 'Reporting Period : ', 'wp-fundraising' ); ?> <datetime><?php echo esc_html( gmdate( 'F j, Y', strtotime( $fromDate ) ) ); ?></datetime> <em>to</em> <datetime><?php echo esc_html( gmdate( 'F j, Y', strtotime( $toDate ) ) ); ?></datetime></p>
	</div>
	<div class="report-body">
	<?php


		global $wpdb;

		$whereQuery = 'SELECT * FROM ' . $wpdb->prefix . 'wdp_fundraising WHERE 1 = 1';

	if ( $searchForm != 'all' ) {
		$whereQuery .= $wpdb->prepare( ' AND form_id = %d', $searchForm );
	}
	if ( $statusDonate != 'all' ) {
		$whereQuery .= $wpdb->prepare( ' AND status = %s', $statusDonate );
	}

		$whereQuery      .= $wpdb->prepare( ' AND (date_time BETWEEN %s AND %s) ORDER BY date_time DESC', $fromDate, $toDate );
		$donateDonateList = $wpdb->get_results( $whereQuery ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Already prepared in $whereQuery above.

	if ( ! empty( $donateDonateList ) ) {
		?>
		
		<?php
	} else {
		echo wp_kses( '<p style="text-align:center; padding:5px;">' . __( 'Not found any reports', 'wp-fundraising' ) . '</p>', \WfpFundraising\Utilities\Utils::get_kses_array() ); }
	?>

	</div>
</div>
