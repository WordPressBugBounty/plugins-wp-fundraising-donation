<div class="reports-content wfp-content-padding">
	<h3 class="dashboard-right-section--title"> <?php echo esc_html( apply_filters( 'wfp_dashboard_income_content_headding', __( 'Income Reports ', 'wp-fundraising' ) ) ); ?></h3>

	<?php require_once __DIR__ . '/report-search/income-report-search.php'; ?>
	
	<div class="wfdp-income-report-table-wraper">
		<div class="wfp-report-headding">
			<h2><?php echo esc_html__( 'Income Statements', 'wp-fundraising' ); ?></h2>
			<p class="period"><?php echo esc_html__( 'Reporting Period : ', 'wp-fundraising' ); ?> <datetime><?php echo esc_html( gmdate( 'F j, Y', strtotime( $fromDate ) ) ); ?></datetime> <em>to</em> <datetime><?php echo esc_html( gmdate( 'F j, Y', strtotime( $toDate ) ) ); ?></datetime></p>
		</div>

		<div class="report-body">
		<?php

			global $wpdb;
			$whereQuery = $wpdb->prefix . 'wdp_fundraising';

		if ( $searchForm != 'all' && in_array( $searchForm, $post_search_id ) ) {
			$whereQuery .= $wpdb->prepare( ' WHERE form_id = %d', $searchForm );
		} else {
			$str         = implode( ',', $post_search_id );
			$whereQuery .= $wpdb->prepare( ' WHERE form_id IN (%s)', $str );
		}

		if ( $statusDonate != 'all' ) {
			$whereQuery .= $wpdb->prepare( ' AND status = %s', $statusDonate );
		}

			$whereQuery .= $wpdb->prepare( ' AND (date_time BETWEEN %s AND %s) ORDER BY date_time DESC', $fromDate, $toDate );
			$resultQuery = 'SELECT * FROM ' . $whereQuery;
			$sumQuery    = 'SELECT SUM(donate_amount) FROM ' . $whereQuery;

			$donateDonateList = $wpdb->get_results( $resultQuery ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Already prepared in $resultQuery above.
			$donateSum        = $wpdb->get_var( $sumQuery ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Already prepared in $sumQuery above.

		if ( sizeof( $donateDonateList ) > 0 ) {
			?>
			<div class="wfp-report-table-wraper">
				<table class="form-table wfdp-table-design wc_gateways widefat wfp-report-table xs-text-center">
					<thead>
						<tr>
							<th class="name"> <?php echo esc_html__( 'Invoice', 'wp-fundraising' ); ?></th>
							<th class="name"> <?php echo esc_html__( 'Contributor`s Name', 'wp-fundraising' ); ?></th>
							<th class="name"> <?php echo esc_html__( 'Contributor`s Mail', 'wp-fundraising' ); ?></th>
							<th class="name wfp-tbl-price"> 
							<?php
							echo esc_html__( 'Amount', 'wp-fundraising' );
							echo wp_kses( ' <strong>[' . $symbols . ']</strong>', \WfpFundraising\Utilities\Utils::get_kses_array() );
							?>
							</th>
							<th class="" ><?php echo esc_html__( 'Date', 'wp-fundraising' ); ?> </th>
						</tr>
					</thead>
				<tbody>
			<?php
			$m           = 1;
			$totalAmount = 0;
			foreach ( $donateDonateList as $pendingData ) :
				$amount = (float) $pendingData->donate_amount;

				$totalAmount += $amount;
				$user_id      = ( property_exists( $pendingData, 'user_id' ) ) ? $pendingData->user_id : 0;

				$firstName = get_user_meta( $user_id, '_wfp_first_name', true );
				$lastName  = get_user_meta( $user_id, '_wfp_last_name', true );
				$email     = get_user_meta( $user_id, '_wfp_email_address', true );
				$date      = $pendingData->date_time;

				if ( empty( $email ) ) {
					$email = $pendingData->email;
				}

				?>
					<tr>
						<td class="icon"> <?php echo esc_html( $pendingData->invoice ); ?></td>
						<td class="name"> <?php echo esc_html( $firstName . ' ' . $lastName ); ?></td>
						<td class="name"> <a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></td>
						<td class="enable wfp-tbl-price"> <?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $defaultUse_space ) ); ?><strong><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $amount ) ); ?></strong><em class="wfp-currency-symbol"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $defaultUse_space ) ); ?></em> </td>
						<td><datetime> <?php echo esc_html( gmdate( 'd M, Y', strtotime( $date ) ) ); ?></datetime></td>
					</tr>
					<?php
					$m++;
				endforeach;
			?>
				</tbody>
				<tfoot>
					<tr>
						<th colspan="3" style="text-align: right"> <?php echo esc_html__( 'Total Amount : ', 'wp-fundraising' ); ?> </th>
						<th> <?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $defaultUse_space ) ); ?><strong><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $totalAmount ) ); ?></strong><em class="wfp-currency-symbol"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $defaultUse_space ) ); ?></em> </th>
						<th>&nbsp; </th>
					</tr>
				</tfoot>
			</table>
		</div>
			<?php
		} else {
			echo wp_kses( '<p style="text-align:center; padding:5px;"> ' . __( 'Not found any reports', 'wp-fundraising' ) . ' </p>', \WfpFundraising\Utilities\Utils::get_kses_array() );}
		?>

		</div>
	</div>
</div>
