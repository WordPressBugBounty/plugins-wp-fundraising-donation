<div class="wfdp-income-report-table-wraper">
	<div class="wfp-report-headding">
		<h2><?php echo esc_html__( 'Income Statements', 'wp-fundraising' ); ?></h2>
		<p class="period"><?php echo esc_html__( 'Reporting Period : ', 'wp-fundraising' ); ?> <datetime><?php echo esc_html( gmdate( 'F j, Y', strtotime( $fromDate ) ) ); ?></datetime> <em>to</em> <datetime><?php echo esc_html( gmdate( 'F j, Y', strtotime( $toDate ) ) ); ?></datetime></p>
	</div>
	<div class="report-body">
	<?php

	global $wpdb;

	$whereQuery = $wpdb->prefix . 'wdp_fundraising WHERE 1 = 1';

	if ( $searchForm != 'all' ) {
		$whereQuery .= $wpdb->prepare( ' AND form_id = %d', $searchForm );
	}
	if ( $statusDonate != 'all' ) {
		$whereQuery .= $wpdb->prepare( ' AND status = %s', $statusDonate );
	}

	$whereQuery .= $wpdb->prepare( ' AND (date_time BETWEEN %s AND %s) ORDER BY date_time DESC', $fromDate, $toDate );

	$donationWhereQuery = 'SELECT * FROM ' . $whereQuery;
	$sumWhereQuery      = 'SELECT SUM(donate_amount) FROM ' . $whereQuery;

	$donateDonateList = $wpdb->get_results( $donationWhereQuery ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Already prepared in $donationWhereQuery above.
	$donateSum        = $wpdb->get_var( $sumWhereQuery ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Already prepared in $sumWhereQuery above.

	$symbols = \WfpFundraising\Apps\Global_Settings::instance()->get_currency_symbol();

	if ( sizeof( $donateDonateList ) > 0 ) {
		?>
		<div class="wfp-report-table-wraper">
			<table class="form-table wfdp-table-design wc_gateways widefat wfp-report-table">
				<thead>
					<tr>
						<th class="name"> <?php echo esc_html__( 'Invoice', 'wp-fundraising' ); ?></th>
						<th class="name"> <?php echo esc_html__( 'Contributor’s Name', 'wp-fundraising' ); ?></th>
						<th class="name"> <?php echo esc_html__( 'Contributor’s Mail', 'wp-fundraising' ); ?></th>
						<th class="name"> 
						<?php
						echo esc_html__( 'Amount', 'wp-fundraising' );
						echo ' <strong>[' . esc_html( $symbols ) . ']</strong>';
						?>
						</th>
						<th class="" ><?php echo esc_html__( 'Date', 'wp-fundraising' ); ?> </th>
						<th><?php esc_html_e( 'Details', 'wp-fundraising' ); ?></th>
					</tr>
				</thead>
			<tbody>
			<?php
			$m           = 1;
			$totalAmount = 0;
			foreach ( $donateDonateList as $pendingData ) :

				$invoice_url = \WfpFundraising\Apps\Key::generate_invoice_link( $pendingData->form_id, $pendingData->invoice );

				$amount = (float) $pendingData->donate_amount;

				$totalAmount += $amount;
				$user_id      = ( property_exists( $pendingData, 'user_id' ) ) ? $pendingData->user_id : 0;

				$firstName = \WfpFundraising\Apps\Settings::wfp_get_metadata( $pendingData->donate_id, '_wfp_first_name' );
				$lastName  = \WfpFundraising\Apps\Settings::wfp_get_metadata( $pendingData->donate_id, '_wfp_last_name' );
				$email     = \WfpFundraising\Apps\Settings::wfp_get_metadata( $pendingData->donate_id, '_wfp_email_address' );

				$date = $pendingData->date_time;

				if ( empty( $email ) ) {
					$email = $pendingData->email;
				}

				?>
				<tr>
					<td class="icon"> <?php echo esc_html( $pendingData->invoice ); ?></td>
					<td class="name"> <?php echo esc_html( $firstName ) . ' ' . esc_html( $lastName ); ?></td>
					<td class="name"> <a href="mailto:<?php echo esc_html( $email ); ?>"><?php echo esc_html( $email ); ?></a></td>
					<td class="enable"> <?php echo esc_html( $symbols ); ?><strong><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $amount ) ); ?></strong></td>
					<td><datetime> <?php echo esc_html( gmdate( 'd M, Y', strtotime( $date ) ) ); ?></datetime></td>
					<td class="invoice"> <a href="<?php echo esc_url( wp_nonce_url( $invoice_url, '_wpnonce' ) ); ?>" target="_blank"><?php esc_html_e( 'View', 'wp-fundraising' ); ?></a> </td>
				</tr>
				<?php
				$m++;
			endforeach;
			?>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="3" style="text-align: right"> <?php echo esc_html__( 'Total Amount : ', 'wp-fundraising' ); ?> [<?php echo esc_html( $symbols ); ?>] </th>
					<th> <?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $totalAmount ) ); ?> </th>
					<th>&nbsp; </th>
				</tr>
			</tfoot>
		</table>
	</div>
		<?php
	} else {
		echo wp_kses( '<p style="text-align:center; padding:5px;"> ' . __( 'Not found any reports', 'wp-fundraising' ) . ' </p>', \WfpFundraising\Utilities\Utils::get_kses_array() ); }
	?>

	</div>
</div>
