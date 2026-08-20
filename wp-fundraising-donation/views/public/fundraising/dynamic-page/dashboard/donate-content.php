<?php 
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

?>

<div class="reports-content wfp-content-padding">
	<h3 class="dashboard-right-section--title"> <?php echo esc_html( apply_filters( 'wfp_dashboard_donate_content_headding', __( 'Donate Reports ', 'wp-fundraising-donation' ) ) ); ?></h3>
	
	<?php require_once __DIR__ . '/report-search/donate-report-search.php'; ?>
	<div class="wfdp-income-report-table-wraper">
		<div class="wfp-report-headding">
			<h2><?php echo esc_html__( 'Donate Statements', 'wp-fundraising-donation' ); ?></h2>
			<p class="period"><?php echo esc_html__( 'Reporting Period : ', 'wp-fundraising-donation' ); ?> <datetime><?php echo esc_html( wp_date( 'F j, Y', strtotime( $wfpFromDate ), new \DateTimeZone( 'UTC' ) ) ); ?></datetime> <em><?php esc_html_e( 'to', 'wp-fundraising-donation' ); ?></em> <datetime><?php echo esc_html( wp_date( 'F j, Y', strtotime( $wfpToDate ), new \DateTimeZone( 'UTC' ) ) ); ?></datetime></p>
		</div>
		<div class="report-body">
			<?php
			global $wpdb;

			$wfp_report = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . "wdp_fundraising WHERE user_id = %d AND status IN('Active') AND (date_time BETWEEN %s AND %s) ORDER BY date_time DESC", $wfpUserId, $wfpFromDate, $wfpToDate ) ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Prepared read for dashboard donation report list.

			if ( is_array( $wfp_report ) && sizeof( $wfp_report ) > 0 ) {
				?>
			<div class="wfp-report-table-wraper">
				<table class="form-table wfdp-table-design wc_gateways widefat wfp-report-table">
					<thead>
						<tr>
							<th class="name"> <?php echo esc_html__( 'Campaign', 'wp-fundraising-donation' ); ?></th>
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
				$wfpTotalAmount = 0;
				foreach ( $wfp_report as $v ) :

					$wfp_form_id      = (int) isset( $v->form_id ) ? $v->form_id : 0;
					$wfpDonateAmount = (float) isset( $v->donate_amount ) ? $v->donate_amount : 0;
					$wfpPledgeAmount = (float) isset( $v->pledge_id ) ? $v->pledge_id : 0;
					$wfp_date         = isset( $v->date_time ) ? $v->date_time : 0;

					$post = get_post( $wfp_form_id );

					if ( is_object( $post ) ) {
						$wfpTotalAmount += $wfpDonateAmount;
						?>
					<tr>
						<td class="icon"> <?php echo esc_html( $post->post_title ); ?></td>
						<td class="enable wfp-tbl-price"> <?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $wfp_defaultUse_space ) ); ?><strong><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfpDonateAmount ) ); ?></strong><em class="wfp-currency-symbol"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $wfp_defaultUse_space ) ); ?></em> </td>
						<td class="xs-text-center"><datetime> <?php echo esc_html( wp_date( 'd M, Y', strtotime( $wfp_date ), new \DateTimeZone( 'UTC' ) ) ); ?></datetime></td>
					</tr>
						<?php
					}
				endforeach;
				?>
				</tbody>
				<tfoot>
					<tr>
						<th colspan="1" style="text-align: right"> <?php echo esc_html__( 'Total Amount : ', 'wp-fundraising-donation' ); ?> </th>
						<th class="wfp-tbl-price"> <?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $wfp_defaultUse_space ) ); ?><strong><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfpTotalAmount ) ); ?></strong><em class="wfp-currency-symbol"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $wfp_defaultUse_space ) ); ?></em> </th>
						<th>&nbsp; </th>
					</tr>
				</tfoot>
			</table>
		</div>
				<?php
			}else { ?>
				<h4 style="text-align: center;"><?php echo esc_html__('No data Found', 'wp-fundraising-donation');?></h4>
			<?php }
			?>
		</div>
	</div>	
</div>
