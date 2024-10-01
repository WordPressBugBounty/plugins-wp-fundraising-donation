<div class="wfdp-income-report">
	<div class="wfp-report-headding">
		<h2><?php echo esc_html__( 'Donors History', 'wp-fundraising' ); ?></h2>
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

		$whereQuery      .= $wpdb->prepare( ' AND (date_time BETWEEN %s AND %s) GROUP BY (email) ORDER BY date_time DESC', $fromDate, $toDate );
		$donateDonateList = $wpdb->get_results( $whereQuery ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Already prepared in $whereQuery above.

		$whereQueryTotal = '';
	if ( ! empty( $donateDonateList ) ) {

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
		$m           = 1;
		$totalAmount = 0;
		global $wpdb;

		foreach ( $donateDonateList as $pendingData ) :

			$user_id = ( property_exists( $pendingData, 'user_id' ) ) ? $pendingData->user_id : 0;

			$firstName = get_user_meta( $user_id, '_wfp_first_name', true );
			$lastName  = get_user_meta( $user_id, '_wfp_last_name', true );
			$email     = get_user_meta( $user_id, '_wfp_email_address', true );

			$whereQueryTotal = $wpdb->prepare( "SELECT SUM(donate_amount) FROM {$wpdb->prefix}wdp_fundraising Where (user_id = %d OR email = %s) AND (date_time BETWEEN %s AND %s)", $user_id, $email, $fromDate, $toDate );
			if ( $statusDonate != 'all' ) {
				$whereQueryTotal .= $wpdb->prepare( ' AND status = %s', $statusDonate );
			}
			/*
				$addTioalData = self::wfp_get_meta($pendingData->donate_id, '_wfp_additional_data');
			if(is_array($addTioalData)){
				$dataAttributes = array_map(function($value, $key) {
					$key = ucwords(str_replace(['_'], ' ', $key));
					if(strlen(trim($value)) > 0):
						return '<strong>'.$key.':</strong> '.$value.' | ';
					endif;
				}, array_values($addTioalData), array_keys($addTioalData));

				$dataAttributes = implode(' ', $dataAttributes);
			}else{
				$dataAttributes = $addTioalData;
			}*/

			$donateSum    = $wpdb->get_var( $whereQueryTotal ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Already prepared in $whereQueryTotal above.
			$totalAmount += $donateSum;
			?>
				<tr style="cursor:pointer;" >
					<td class="name"> <?php echo esc_html( $pendingData->email ); ?></td>
					<td class="" align="left"> <?php echo esc_html( $firstName . ' ' . $lastName ); ?> </td>
					<td class="" align="left"> <?php echo esc_html( $symbols ); ?><strong><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $donateSum ) ); ?></strong></td>	
				</tr>
				<?php
				$m++;
			endforeach;
		?>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="2" style="text-align: right"> <?php echo esc_html__( 'Total Amount : ', 'wp-fundraising' ); ?> [<?php echo esc_html( $symbols ); ?>] </th>
					<th> <?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $totalAmount ) ); ?> </th>
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

