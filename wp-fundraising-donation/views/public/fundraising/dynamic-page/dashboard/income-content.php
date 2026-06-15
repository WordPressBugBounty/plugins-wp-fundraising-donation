<?php 
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

?>
<div class="reports-content wfp-content-padding">
	<h3 class="dashboard-right-section--title"> <?php echo esc_html( apply_filters( 'wfp_dashboard_income_content_headding', __( 'Income Reports ', 'wp-fundraising-donation' ) ) ); ?></h3>

	<?php require_once __DIR__ . '/report-search/income-report-search.php'; ?>
	
	<div class="wfdp-income-report-table-wraper">
		<div class="wfp-report-headding">
			<h2><?php echo esc_html__( 'Income Statements', 'wp-fundraising-donation' ); ?></h2>
			<p class="period"><?php echo esc_html__( 'Reporting Period : ', 'wp-fundraising-donation' ); ?> <datetime><?php echo esc_html( gmdate( 'F j, Y', strtotime( $wfpFromDate ) ) ); ?></datetime> <em>to</em> <datetime><?php echo esc_html( gmdate( 'F j, Y', strtotime( $wfpToDate ) ) ); ?></datetime></p>
		</div>

		<div class="report-body">
		<?php

			global $wpdb;
			$wfpWhereQuery = $wpdb->prefix . 'wdp_fundraising';

		if ( $wfpSearchForm != 'all' && in_array( $wfpSearchForm, $wfp_post_search_id ) ) {
			$wfpWhereQuery .= $wpdb->prepare( ' WHERE form_id = %d', $wfpSearchForm );
		} else {
			$wfp_str         = implode( ',', $wfp_post_search_id );
			$wfpWhereQuery .= $wpdb->prepare( ' WHERE form_id IN (%s)', $wfp_str );
		}

		if ( $wfpStatusDonate != 'all' ) {
			$wfpWhereQuery .= $wpdb->prepare( ' AND status = %s', $wfpStatusDonate );
		}

			$wfpWhereQuery .= $wpdb->prepare( ' AND (date_time BETWEEN %s AND %s) ORDER BY date_time DESC', $wfpFromDate, $wfpToDate );
			$wfpResultQuery = 'SELECT * FROM ' . $wfpWhereQuery;
			$wfpSumQuery    = 'SELECT SUM(donate_amount) FROM ' . $wfpWhereQuery;

			$wfpDonateDonateList = $wpdb->get_results( $wfpResultQuery ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter -- Query is assembled above for dashboard income report list.
			$wfpDonateSum        = $wpdb->get_var( $wfpSumQuery ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter -- Query is assembled above for dashboard income report total.

		if ( sizeof( $wfpDonateDonateList ) > 0 ) {
			?>
			<div class="wfp-report-table-wraper">
				<table class="form-table wfdp-table-design wc_gateways widefat wfp-report-table xs-text-center">
					<thead>
						<tr>
							<th class="name"> <?php echo esc_html__( 'Invoice', 'wp-fundraising-donation' ); ?></th>
							<th class="name"> <?php echo esc_html__( 'Contributor`s Name', 'wp-fundraising-donation' ); ?></th>
							<th class="name"> <?php echo esc_html__( 'Contributor`s Mail', 'wp-fundraising-donation' ); ?></th>
							<th class="name wfp-tbl-price"> 
							<?php
							echo esc_html__( 'Amount', 'wp-fundraising-donation' );
							echo wp_kses( ' <strong>[' . $wfpSymbols . ']</strong>', \WfpFundraising\Utilities\Utils::get_kses_array() );
							?>
							</th>
							<th class="" ><?php echo esc_html__( 'Date', 'wp-fundraising-donation' ); ?> </th>
						</tr>
					</thead>
				<tbody>
			<?php
			$wfp_m           = 1;
			$wfpTotalAmount = 0;
			foreach ( $wfpDonateDonateList as $pendingData ) :
				$wfp_amount = (float) $pendingData->donate_amount;

				$wfpTotalAmount += $wfp_amount;
				$wfp_user_id      = ( property_exists( $pendingData, 'user_id' ) ) ? $pendingData->user_id : 0;

				$wfpFirstName = get_user_meta( $wfp_user_id, '_wfp_first_name', true );
				$wfpLastName  = get_user_meta( $wfp_user_id, '_wfp_last_name', true );
				$wfp_email     = get_user_meta( $wfp_user_id, '_wfp_email_address', true );
				$wfp_date      = $pendingData->date_time;

				if ( empty( $wfp_email ) ) {
					$wfp_email = $pendingData->email;
				}

				?>
					<tr>
						<td class="icon"> <?php echo esc_html( $pendingData->invoice ); ?></td>
						<td class="name"> <?php echo esc_html( $wfpFirstName . ' ' . $wfpLastName ); ?></td>
						<td class="name"> <a href="mailto:<?php echo esc_attr( $wfp_email ); ?>"><?php echo esc_html( $wfp_email ); ?></a></td>
						<td class="enable wfp-tbl-price"> <?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $wfp_defaultUse_space ) ); ?><strong><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfp_amount ) ); ?></strong><em class="wfp-currency-symbol"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $wfp_defaultUse_space ) ); ?></em> </td>
						<td><datetime> <?php echo esc_html( gmdate( 'd M, Y', strtotime( $wfp_date ) ) ); ?></datetime></td>
					</tr>
					<?php
					$wfp_m++;
				endforeach;
			?>
				</tbody>
				<tfoot>
					<tr>
						<th colspan="3" style="text-align: right"> <?php echo esc_html__( 'Total Amount : ', 'wp-fundraising-donation' ); ?> </th>
						<th> <?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $wfp_defaultUse_space ) ); ?><strong><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfpTotalAmount ) ); ?></strong><em class="wfp-currency-symbol"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $wfp_defaultUse_space ) ); ?></em> </th>
						<th>&nbsp; </th>
					</tr>
				</tfoot>
			</table>
		</div>
			<?php
		} else {
			echo wp_kses( '<p style="text-align:center; padding:5px;"> ' . __( 'Not found any reports', 'wp-fundraising-donation' ) . ' </p>', \WfpFundraising\Utilities\Utils::get_kses_array() );}
		?>

		</div>
	</div>
</div>
