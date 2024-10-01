<div class="reports-content wfp-content-padding">
	<h3 class="dashboard-right-section--title"> <?php echo esc_html( apply_filters( 'wfp_dashboard_donate_content_headding', __( 'Donate Reports ', 'wp-fundraising' ) ) ); ?></h3>
	
	<?php require_once __DIR__ . '/report-search/donate-report-search.php'; ?>
	<div class="wfdp-income-report-table-wraper">
		<div class="wfp-report-headding">
			<h2><?php echo esc_html__( 'Donate Statements', 'wp-fundraising' ); ?></h2>
			<p class="period"><?php echo esc_html__( 'Reporting Period : ', 'wp-fundraising' ); ?> <datetime><?php echo esc_html( gmdate( 'F j, Y', strtotime( $fromDate ) ) ); ?></datetime> <em><?php esc_html_e( 'to', 'wp-fundraising' ); ?></em> <datetime><?php echo esc_html( gmdate( 'F j, Y', strtotime( $toDate ) ) ); ?></datetime></p>
		</div>
		<div class="report-body">
			<?php
			global $wpdb;

			$report = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . "wdp_fundraising WHERE user_id = %d AND status IN('Active') AND (date_time BETWEEN %s AND %s) ORDER BY date_time DESC", $userId, $fromDate, $toDate ) );

			if ( is_array( $report ) && sizeof( $report ) > 0 ) {
				?>
			<div class="wfp-report-table-wraper">
				<table class="form-table wfdp-table-design wc_gateways widefat wfp-report-table">
					<thead>
						<tr>
							<th class="name"> <?php echo esc_html__( 'Campaign', 'wp-fundraising' ); ?></th>
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
				$totalAmount = 0;
				foreach ( $report as $v ) :

					$form_id      = (int) isset( $v->form_id ) ? $v->form_id : 0;
					$donateAmount = (float) isset( $v->donate_amount ) ? $v->donate_amount : 0;
					$pledgeAmount = (float) isset( $v->pledge_id ) ? $v->pledge_id : 0;
					$date         = isset( $v->date_time ) ? $v->date_time : 0;

					$post = get_post( $form_id );

					if ( is_object( $post ) ) {
						$totalAmount += $donateAmount;
						?>
					<tr>
						<td class="icon"> <?php echo esc_html( $post->post_title ); ?></td>
						<td class="enable wfp-tbl-price"> <?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $defaultUse_space ) ); ?><strong><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $donateAmount ) ); ?></strong><em class="wfp-currency-symbol"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $defaultUse_space ) ); ?></em> </td>
						<td class="xs-text-center"><datetime> <?php echo esc_html( gmdate( 'd M, Y', strtotime( $date ) ) ); ?></datetime></td>
					</tr>
						<?php
					}
				endforeach;
				?>
				</tbody>
				<tfoot>
					<tr>
						<th colspan="1" style="text-align: right"> <?php echo esc_html__( 'Total Amount : ', 'wp-fundraising' ); ?> </th>
						<th class="wfp-tbl-price"> <?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $defaultUse_space ) ); ?><strong><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $totalAmount ) ); ?></strong><em class="wfp-currency-symbol"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $defaultUse_space ) ); ?></em> </th>
						<th>&nbsp; </th>
					</tr>
				</tfoot>
			</table>
		</div>
				<?php
			}else { ?>
				<h4 style="text-align: center;"><?php echo esc_html__('No data Found', 'wp-fundraising');?></h4>
			<?php }
			?>
		</div>
	</div>	
</div>
