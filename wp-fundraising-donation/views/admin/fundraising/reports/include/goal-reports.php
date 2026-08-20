<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

?>
<div class="wfdp-income-report">
	<div class="wfp-report-headding">
		<h2><?php echo esc_html__( 'Goal Statements', 'wp-fundraising-donation' ); ?></h2>
		<p class="period"><?php echo esc_html__( 'Reporting Period : ', 'wp-fundraising-donation' ); ?> <datetime><?php echo esc_html( wp_date( 'F j, Y', strtotime( $wfpFromDate ), new \DateTimeZone( 'UTC' ) ) ); ?></datetime> <em><?php esc_html_e( 'to', 'wp-fundraising-donation' ); ?></em> <datetime><?php echo esc_html( wp_date( 'F j, Y', strtotime( $wfpToDate ), new \DateTimeZone( 'UTC' ) ) ); ?></datetime></p>
	</div>
	<div class="report-body">
	<?php


		global $wpdb;

		$wfpWhereQuery = 'SELECT * FROM ' . $wpdb->prefix . 'wdp_fundraising WHERE 1 = 1';

	if ( $wfpSearchForm != 'all' ) {
		$wfpWhereQuery .= $wpdb->prepare( ' AND form_id = %d', $wfpSearchForm );
	}
	if ( $wfpStatusDonate != 'all' ) {
		$wfpWhereQuery .= $wpdb->prepare( ' AND status = %s', $wfpStatusDonate );
	}

		$wfpWhereQuery      .= $wpdb->prepare( ' AND (date_time BETWEEN %s AND %s) ORDER BY date_time DESC', $wfpFromDate, $wfpToDate );
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter -- Query is assembled above for this report read.
		$wfpDonateDonateList = $wpdb->get_results( $wfpWhereQuery );

	if ( ! empty( $wfpDonateDonateList ) ) {
		?>
		
		<?php
	} else {
		echo wp_kses( '<p style="text-align:center; padding:5px;">' . __( 'Not found any reports', 'wp-fundraising-donation' ) . '</p>', \WfpFundraising\Utilities\Utils::get_kses_array() ); }
	?>

	</div>
</div>
