<?php 
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

?>
<div class="wfdp-income-report">
	<div class="wfp-report-headding">
		<h2><?php echo esc_html__( 'Donors History', 'wp-fundraising' ); ?></h2>
		<p class="period"><?php echo esc_html__( 'Reporting Period : ', 'wp-fundraising' ); ?> <datetime><?php echo esc_html( gmdate( 'F j, Y', strtotime( $wfpFromDate ) ) ); ?></datetime> <em>to</em> <datetime><?php echo esc_html( gmdate( 'F j, Y', strtotime( $wfpToDate ) ) ); ?></datetime></p>
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

		$wfpWhereQuery      .= $wpdb->prepare( ' AND (date_time BETWEEN %s AND %s) GROUP BY (email) ORDER BY date_time DESC', $wfpFromDate, $wfpToDate );
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter -- Query is prepared in $wfpWhereQuery above and is a controlled report read.
		$wfpDonateDonateList = $wpdb->get_results( $wfpWhereQuery );

		$wfpWhereQueryTotal = '';
	if ( ! empty( $wfpDonateDonateList ) ) {

		?>
		<div class="wfp-report-table-wraper">

		
		<table class="form-table wfdp-table-design wc_gateways widefat wfp-report-table">
			<thead>
				<tr>
					<th class="name"> <?php echo esc_html__( 'Email', 'wp-fundraising' ); ?></th>
					<th> <?php echo esc_html__( 'Name', 'wp-fundraising' ); ?></th>
					<th> <?php echo esc_html__( 'Total Amount', 'wp-fundraising' ); ?></th>
					
				</tr>
			</thead>
			<tbody>
		<?php
		$wfp_m           = 1;
		$wfpTotalAmount = 0;
		global $wpdb;

		foreach ( $wfpDonateDonateList as $pendingData ) :

			$wfp_user_id = ( property_exists( $pendingData, 'user_id' ) ) ? $pendingData->user_id : 0;

			$wfpFirstName = get_user_meta( $wfp_user_id, '_wfp_first_name', true );
			$wfpLastName  = get_user_meta( $wfp_user_id, '_wfp_last_name', true );
			$wfp_email     = get_user_meta( $wfp_user_id, '_wfp_email_address', true );

			$wfpWhereQueryTotal = $wpdb->prepare( "SELECT SUM(donate_amount) FROM {$wpdb->prefix}wdp_fundraising Where (user_id = %d OR email = %s) AND (date_time BETWEEN %s AND %s)", $wfp_user_id, $wfp_email, $wfpFromDate, $wfpToDate );
			if ( $wfpStatusDonate != 'all' ) {
				$wfpWhereQueryTotal .= $wpdb->prepare( ' AND status = %s', $wfpStatusDonate );
			}
			/*
				$wfpAddTioalData = self::wfp_get_meta($pendingData->donate_id, '_wfp_additional_data');
			if(is_array($wfpAddTioalData)){
				$wfpDataAttributes = array_map(function($wfp_value, $wfp_key) {
					$wfp_key = ucwords(str_replace(['_'], ' ', $wfp_key));
					if(strlen(trim($wfp_value)) > 0):
						return '<strong>'.$wfp_key.':</strong> '.$wfp_value.' | ';
					endif;
				}, array_values($wfpAddTioalData), array_keys($wfpAddTioalData));

				$wfpDataAttributes = implode(' ', $wfpDataAttributes);
			}else{
				$wfpDataAttributes = $wfpAddTioalData;
			}*/

			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter -- Query is prepared in $wfpWhereQueryTotal above and is a controlled report read.
			$wfpDonateSum    = $wpdb->get_var( $wfpWhereQueryTotal );
			$wfpTotalAmount += $wfpDonateSum;
			?>
				<tr style="cursor:pointer;" >
					<td class="name"> <?php echo esc_html( $pendingData->email ); ?></td>
					<td class="" align="left"> <?php echo esc_html( $wfpFirstName . ' ' . $wfpLastName ); ?> </td>
					<td class="" align="left"> <?php echo esc_html( $wfpSymbols ); ?><strong><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfpDonateSum ) ); ?></strong></td>	
				</tr>
				<?php
				$wfp_m++;
			endforeach;
		?>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="2" style="text-align: right"> <?php echo esc_html__( 'Total Amount : ', 'wp-fundraising' ); ?> [<?php echo esc_html( $wfpSymbols ); ?>] </th>
					<th> <?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfpTotalAmount ) ); ?> </th>
				</tr>
			</tfoot>
		</table>
	</div>
		<?php
	} else {
		echo wp_kses( '<p style="text-align:center; padding:5px;"> Not found any reports </p>', \WfpFundraising\Utilities\Utils::get_kses_array() );}
	?>
	</div>
</div>
