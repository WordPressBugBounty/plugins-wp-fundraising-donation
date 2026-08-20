<?php 
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

?>
<div class="wfdp-income-report-table-wraper">
	<div class="wfp-report-headding">
		<h2><?php echo esc_html__( 'Income Statements', 'wp-fundraising-donation' ); ?></h2>
		<p class="period"><?php echo esc_html__( 'Reporting Period : ', 'wp-fundraising-donation' ); ?> <datetime><?php echo esc_html( wp_date( 'F j, Y', strtotime( $wfpFromDate ), new \DateTimeZone( 'UTC' ) ) ); ?></datetime> <em><?php esc_html_e( 'to', 'wp-fundraising-donation' ); ?></em> <datetime><?php echo esc_html( wp_date( 'F j, Y', strtotime( $wfpToDate ), new \DateTimeZone( 'UTC' ) ) ); ?></datetime></p>
	</div>
	<div class="report-body">
	<?php

	global $wpdb;

	$wfpWhereQuery = $wpdb->prefix . 'wdp_fundraising WHERE 1 = 1';

	if ( $wfpSearchForm != 'all' ) {
		$wfpWhereQuery .= $wpdb->prepare( ' AND form_id = %d', $wfpSearchForm );
	}
	if ( $wfpStatusDonate != 'all' ) {
		$wfpWhereQuery .= $wpdb->prepare( ' AND status = %s', $wfpStatusDonate );
	}

	$wfpWhereQuery .= $wpdb->prepare( ' AND (date_time BETWEEN %s AND %s) ORDER BY date_time DESC', $wfpFromDate, $wfpToDate );

	$wfpDonationWhereQuery = 'SELECT * FROM ' . $wfpWhereQuery;
	$wfpSumWhereQuery      = 'SELECT SUM(donate_amount) FROM ' . $wfpWhereQuery;

	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter -- Query is assembled above for this report read.
	$wfpDonateDonateList = $wpdb->get_results( $wfpDonationWhereQuery );
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter -- Query is assembled above for this report read.
	$wfpDonateSum        = $wpdb->get_var( $wfpSumWhereQuery );

	$wfpSymbols = \WfpFundraising\Apps\Global_Settings::instance()->get_currency_symbol();

	if ( sizeof( $wfpDonateDonateList ) > 0 ) {
		?>
		<div class="wfp-report-table-wraper">
			<table class="form-table wfdp-table-design wc_gateways widefat wfp-report-table">
				<thead>
					<tr>
						<th class="name"> <?php echo esc_html__( 'Invoice', 'wp-fundraising-donation' ); ?></th>
						<th class="name"> <?php echo esc_html__( 'Contributor’s Name', 'wp-fundraising-donation' ); ?></th>
						<th class="name"> <?php echo esc_html__( 'Contributor’s Mail', 'wp-fundraising-donation' ); ?></th>
						<th class="name"> 
						<?php
						echo esc_html__( 'Amount', 'wp-fundraising-donation' );
						echo ' <strong>[' . esc_html( $wfpSymbols ) . ']</strong>';
						?>
						</th>
						<th class="" ><?php echo esc_html__( 'Date', 'wp-fundraising-donation' ); ?> </th>
						<th><?php esc_html_e( 'Details', 'wp-fundraising-donation' ); ?></th>
					</tr>
				</thead>
			<tbody>
			<?php
			$wfp_m           = 1;
			$wfpTotalAmount = 0;
			foreach ( $wfpDonateDonateList as $pendingData ) :

				$wfp_invoice_url = \WfpFundraising\Apps\Key::generate_invoice_link( $pendingData->form_id, $pendingData->invoice );

				$wfp_amount = (float) $pendingData->donate_amount;

				$wfpTotalAmount += $wfp_amount;
				$wfp_user_id      = ( property_exists( $pendingData, 'user_id' ) ) ? $pendingData->user_id : 0;

				$wfpFirstName = \WfpFundraising\Apps\Settings::wfp_get_metadata( $pendingData->donate_id, '_wfp_first_name' );
				$wfpLastName  = \WfpFundraising\Apps\Settings::wfp_get_metadata( $pendingData->donate_id, '_wfp_last_name' );
				$wfp_email     = \WfpFundraising\Apps\Settings::wfp_get_metadata( $pendingData->donate_id, '_wfp_email_address' );

				$wfp_date = $pendingData->date_time;

				if ( empty( $wfp_email ) ) {
					$wfp_email = $pendingData->email;
				}

				?>
				<tr>
					<td class="icon"> <?php echo esc_html( $pendingData->invoice ); ?></td>
					<td class="name"> <?php echo esc_html( $wfpFirstName ) . ' ' . esc_html( $wfpLastName ); ?></td>
					<td class="name"> <a href="mailto:<?php echo esc_html( $wfp_email ); ?>"><?php echo esc_html( $wfp_email ); ?></a></td>
					<td class="enable"> <?php echo esc_html( $wfpSymbols ); ?><strong><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfp_amount ) ); ?></strong></td>
					<td><datetime> <?php echo esc_html( wp_date( 'd M, Y', strtotime( $wfp_date ), new \DateTimeZone( 'UTC' ) ) ); ?></datetime></td>
					<td class="invoice"> <a href="<?php echo esc_url( wp_nonce_url( $wfp_invoice_url, '_wpnonce' ) ); ?>" target="_blank"><?php esc_html_e( 'View', 'wp-fundraising-donation' ); ?></a> </td>
				</tr>
				<?php
				$wfp_m++;
			endforeach;
			?>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="3" style="text-align: right"> <?php echo esc_html__( 'Total Amount : ', 'wp-fundraising-donation' ); ?> [<?php echo esc_html( $wfpSymbols ); ?>] </th>
					<th> <?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfpTotalAmount ) ); ?> </th>
					<th>&nbsp; </th>
				</tr>
			</tfoot>
		</table>
	</div>
		<?php
	} else {
		echo wp_kses( '<p style="text-align:center; padding:5px;"> ' . __( 'Not found any reports', 'wp-fundraising-donation' ) . ' </p>', \WfpFundraising\Utilities\Utils::get_kses_array() ); }
	?>

	</div>
</div>
