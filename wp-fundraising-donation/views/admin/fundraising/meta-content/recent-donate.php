<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

?>
<div class="wdp-form-information xs_shadow_card">
	<?php
	$wfpTypeReport = isset( $_GET['type_status'] ) ? sanitize_text_field( wp_unslash( $_GET['type_status'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- This file is currently unused.

	$wfp_paged  = empty( $_GET['donate_page'] ) ? 0 : intval( $_GET['donate_page'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- This file is currently unused.
	$wfp_limit  = empty( $_GET['xs_page_limit'] ) ? 10 : ( $_GET['xs_page_limit'] > 20 ? 20 : intval( $_GET['xs_page_limit'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- This file is currently unused.
	$wfp_offset = $wfp_limit * $wfp_paged;

	$wfpTodayDate  = gmdate( 'Y-m-d' );
	$wfp_days_10ago = gmdate( 'Y-m-d', strtotime( '-10 days', strtotime( $wfpTodayDate ) ) );

	global $wpdb;

	if ( ! empty( $wfpTypeReport ) ) {
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Prepared read from the plugin's donation table for recent donations list; paginated display.
		$wfpPenddingDonateList = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'wdp_fundraising WHERE form_id = %d AND status IN (%s) ORDER BY date_time DESC LIMIT %d, %d', $post->ID, $wfpTypeReport, $wfp_offset, $wfp_limit ) );
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Prepared count query from the plugin's donation table for pagination.
		$wfpPenddingCount      = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(form_id) FROM ' . $wpdb->prefix . 'wdp_fundraising WHERE form_id = %d AND status IN (%s) ORDER BY date_time DESC', $post->ID, $wfpTypeReport ) );

	} else {
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Prepared read from the plugin's donation table for active donations list; paginated display.
		$wfpPenddingDonateList = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . "wdp_fundraising WHERE form_id = %d AND status IN ('Active') ORDER BY date_time DESC LIMIT %d, %d", $post->ID, $wfp_offset, $wfp_limit ) );
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Prepared count query from the plugin's donation table for pagination.
		$wfpPenddingCount      = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(form_id) FROM ' . $wpdb->prefix . "wdp_fundraising WHERE form_id = %d AND status IN ('Active') ORDER BY date_time DESC", $post->ID ) );
	}

	?>
	<div class="xs_recent_donation_title_wraper">
		<h3 class="xs-fundrising-title"><?php echo esc_html__( 'Recent Donation List', 'wp-fundraising-donation' ); ?>  </h3>
		<div class="xs_period_wraper xs_text_center">
			<?php echo esc_html__( 'Period : ', 'wp-fundraising-donation' ); ?>
			<datetime><?php echo esc_html( wp_date( 'F j, Y', strtotime( $wfp_days_10ago ), new \DateTimeZone( 'UTC' ) ) ); ?></datetime> <em><?php esc_html_e( 'to', 'wp-fundraising-donation' ); ?></em> <datetime><?php echo esc_html( wp_date( 'F j, Y', strtotime( $wfpTodayDate ), new \DateTimeZone( 'UTC' ) ) ); ?></datetime>
		</div>

		<div class="report-heading">
			<ul class="xs_fundrising_filter xs_text_center">
				<li> <a class="<?php echo ( $wfpTypeReport == '' ) ? 'active' : ''; ?>" href="<?php echo esc_url( admin_url() ); ?>post.php?post=<?php echo esc_attr( $post->ID ); ?>&action=edit"> <?php echo esc_html( strtoupper( __( 'All', 'wp-fundraising-donation' ) ) ); ?> </a></li>
				<li> <a class="<?php echo ( $wfpTypeReport == 'Review' ) ? 'active' : ''; ?>" href="<?php echo esc_url( admin_url() ); ?>post.php?post=<?php echo esc_attr( $post->ID ); ?>&action=edit&type_status=Review"> <?php echo esc_html( strtoupper( __( 'In Review', 'wp-fundraising-donation' ) ) ); ?></a></li>
				<li> <a class="<?php echo ( $wfpTypeReport == 'Pending' ) ? 'active' : ''; ?>" href="<?php echo esc_url( admin_url() ); ?>post.php?post=<?php echo esc_attr( $post->ID ); ?>&action=edit&type_status=Pending"><?php echo esc_html( strtoupper( __( 'In Process', 'wp-fundraising-donation' ) ) ); ?></a></li>
			</ul>
		</div>
		<div><span><?php
		// translators: %s: number of items per page.
		echo sprintf( esc_html__( 'Show %s (per page) in total ', 'wp-fundraising-donation' ), esc_html( $wfp_limit ) ); ?> <?php echo esc_html( $wfpPenddingCount ); ?></span></div>
	</div>

	<?php if ( ! empty( $wfpPenddingDonateList ) ) : ?>
		<div class="xs_payment_review_table_wraper">
			<table class="form-table xs_payment_review_table">
				<thead>
				<tr>
					<th class="sort"><?php echo esc_html__( 'S.L.', 'wp-fundraising-donation' ); ?></th>
					<th class="name"> <?php echo esc_html__( 'Email', 'wp-fundraising-donation' ); ?></th>
					<th class="enable"> <?php
					// translators: %s: currency symbol.
					echo sprintf( esc_html__( 'Amount [%s]', 'wp-fundraising-donation' ), esc_html( $wfpSymbols ) ); ?></th>
					<th class="" > <?php echo esc_html__( 'Payment Method', 'wp-fundraising-donation' ); ?> </th>
					<th class="" > <?php echo esc_html__( 'Date', 'wp-fundraising-donation' ); ?> </th>
					<th class="info"> <?php echo esc_html__( 'Action', 'wp-fundraising-donation' ); ?></th>
				</tr>
				</thead>
				<tbody>
				<?php
				$wfp_m           = 1;
				$wfpTotalAmount = 0;
				foreach ( $wfpPenddingDonateList as $pendingData ) :
					if ( $pendingData->payment_gateway == 'online_payment' ) {
						$wfp_payment_gateway = 'Paypal';
					} elseif ( $pendingData->payment_gateway == 'stripe_payment' ) {
						$wfp_payment_gateway = 'Stripe';
					} elseif ( $pendingData->payment_gateway == 'bank_payment' ) {
						$wfp_payment_gateway = 'Bank';
					} elseif ( $pendingData->payment_gateway == 'check_payment' ) {
						$wfp_payment_gateway = 'Check';
					} elseif ( $pendingData->payment_gateway == 'offline_payment' ) {
						$wfp_payment_gateway = 'Cash';
					} elseif ( $pendingData->payment_gateway == '2checkout' ) {
						$wfp_payment_gateway = '2Checkout';
					}
					$wfpTotalAmount += $pendingData->donate_amount;
					?>
					<tr id="donate_tr__<?php echo esc_attr( $pendingData->donate_id ); ?>">
						<td class="icon"> <strong><?php echo esc_html( $wfp_m ); ?> </strong></td>
						<td class="name"> <?php echo esc_html( $pendingData->email ); ?></td>
						<td class="enable"> <?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $pendingData->donate_amount ) ); ?> </td>
						<td><?php echo esc_html( $wfp_payment_gateway ); ?> </td>
						<td><?php echo esc_html( wp_date( 'F d, Y', strtotime( $pendingData->date_time ), new \DateTimeZone( 'UTC' ) ) ); ?> </td>
						<td>
							<?php
							$wfpClassNameStatus = strtolower( $pendingData->status );
							if ( $pendingData->status == 'Pending' ) {
								// $wfpClassNameStatus = 'process';
							}
							?>
							<select class="<?php echo esc_attr( $wfpClassNameStatus ); ?>" name="status_modify" id="<?php echo esc_attr( $pendingData->donate_id ); ?>" onchange="wdp_status_modify_report(this)">
								<?php if ( in_array( $pendingData->status, array( 'Pending' ) ) ) : ?>
									<option value="0" <?php echo isset( $pendingData->status ) && $pendingData->status == 'Pending' ? 'selected' : ''; ?> ><?php echo esc_html__( 'In Process', 'wp-fundraising-donation' ); ?>  </option>
								<?php endif; ?>
								<?php if ( in_array( $pendingData->status, array( 'Review' ) ) ) : ?>
									<option value="1" <?php echo isset( $pendingData->status ) && $pendingData->status == 'Review' ? 'selected' : ''; ?>> <?php echo esc_html__( 'In Review', 'wp-fundraising-donation' ); ?> </option>
								<?php endif; ?>
								<?php if ( in_array( $pendingData->status, array( 'Active', 'Pending', 'Review' ) ) ) : ?>
									<option value="2" <?php echo isset( $pendingData->status ) && $pendingData->status == 'Active' ? 'selected' : ''; ?>> <?php echo esc_html__( 'Success', 'wp-fundraising-donation' ); ?> </option>
								<?php endif; ?>
								<?php if ( in_array( $pendingData->status, array( 'Active' ) ) ) : ?>
									<option value="4" <?php echo isset( $pendingData->status ) && $pendingData->status == 'Refunded' ? 'selected' : ''; ?>> <?php echo esc_html__( 'Refund', 'wp-fundraising-donation' ); ?> </option>
								<?php endif; ?>
								<?php if ( in_array( $pendingData->status, array( 'Pending', 'Review' ) ) ) : ?>
									<option value="3" <?php echo isset( $pendingData->status ) && $pendingData->status == 'DeActive' ? 'selected' : ''; ?>> <?php echo esc_html__( 'Cancel', 'wp-fundraising-donation' ); ?> </option>
								<?php endif; ?>
							</select>
						</td>
					</tr>
					<?php
					$wfp_m++;
				endforeach;
				?>
				</tbody>
				<tfoot>
				<tr>
					<th colspan="2">
						<?php echo esc_html__( 'Total Amount : ', 'wp-fundraising-donation' ); ?> [<?php echo esc_html( $wfpSymbols ); ?>]
					</th>
					<th>
						<?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfpTotalAmount ) ); ?>
					</th>
					<th colspan="3">&nbsp;</th>
				</tr>
				</tfoot>
			</table>
		</div>
	<?php endif; ?>
</div>
