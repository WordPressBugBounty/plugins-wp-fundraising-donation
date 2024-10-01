<div class="wdp-form-information xs_shadow_card">
	<?php
	$typeReport = isset( $_GET['type_status'] ) ? sanitize_text_field( wp_unslash( $_GET['type_status'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- This file is currently unused.

	$paged  = empty( $_GET['donate_page'] ) ? 0 : intval( $_GET['donate_page'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- This file is currently unused.
	$limit  = empty( $_GET['xs_page_limit'] ) ? 10 : ( $_GET['xs_page_limit'] > 20 ? 20 : intval( $_GET['xs_page_limit'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- This file is currently unused.
	$offset = $limit * $paged;

	$todayDate  = gmdate( 'Y-m-d' );
	$days_10ago = gmdate( 'Y-m-d', strtotime( '-10 days', strtotime( $todayDate ) ) );

	global $wpdb;

	if ( ! empty( $typeReport ) ) {
		$penddingDonateList = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'wdp_fundraising WHERE form_id = %d AND status IN (%s) ORDER BY date_time DESC LIMIT %d, %d', $post->ID, $typeReport, $offset, $limit ) );
		$penddingCount      = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(form_id) FROM ' . $wpdb->prefix . 'wdp_fundraising WHERE form_id = %d AND status IN (%s) ORDER BY date_time DESC', $post->ID, $typeReport ) );

	} else {
		$penddingDonateList = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . "wdp_fundraising WHERE form_id = %d AND status IN ('Active') ORDER BY date_time DESC LIMIT %d, %d", $post->ID, $offset, $limit ) );
		$penddingCount      = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(form_id) FROM ' . $wpdb->prefix . "wdp_fundraising WHERE form_id = %d AND status IN ('Active') ORDER BY date_time DESC", $post->ID ) );
	}

	?>
	<div class="xs_recent_donation_title_wraper">
		<h3 class="xs-fundrising-title"><?php echo esc_html__( 'Recent Donation List', 'wp-fundraising' ); ?>  </h3>
		<div class="xs_period_wraper xs_text_center">
			<?php echo esc_html__( 'Period : ', 'wp-fundraising' ); ?>
			<datetime><?php echo esc_html( gmdate( 'F j, Y', strtotime( $days_10ago ) ) ); ?></datetime> <em>to</em> <datetime><?php echo esc_html( gmdate( 'F j, Y', strtotime( $todayDate ) ) ); ?></datetime>
		</div>

		<div class="report-heading">
			<ul class="xs_fundrising_filter xs_text_center">
				<li> <a class="<?php echo ( $typeReport == '' ) ? 'active' : ''; ?>" href="<?php echo esc_url( admin_url() ); ?>post.php?post=<?php echo esc_attr( $post->ID ); ?>&action=edit"> <?php echo esc_html__( strtoupper( 'All' ), 'wp-fundraising' ); ?> </a></li>
				<li> <a class="<?php echo ( $typeReport == 'Review' ) ? 'active' : ''; ?>" href="<?php echo esc_url( admin_url() ); ?>post.php?post=<?php echo esc_attr( $post->ID ); ?>&action=edit&type_status=Review"> <?php echo esc_html__( strtoupper( 'In Review ' ), 'wp-fundraising' ); ?></a></li>
				<li> <a class="<?php echo ( $typeReport == 'Pending' ) ? 'active' : ''; ?>" href="<?php echo esc_url( admin_url() ); ?>post.php?post=<?php echo esc_attr( $post->ID ); ?>&action=edit&type_status=Pending"><?php echo esc_html__( strtoupper( ' In Process ' ), 'wp-fundraising' ); ?></a></li>
			</ul>
		</div>
		<div><span><?php echo sprintf( esc_html__( 'Show %s (per page) in total ', 'wp-fundraising' ), esc_html( $limit ) ); ?> <?php echo esc_html( $penddingCount ); ?></span></div>
	</div>

	<?php if ( ! empty( $penddingDonateList ) ) : ?>
		<div class="xs_payment_review_table_wraper">
			<table class="form-table xs_payment_review_table">
				<thead>
				<tr>
					<th class="sort"><?php echo esc_html__( 'S.L.', 'wp-fundraising' ); ?></th>
					<th class="name"> <?php echo esc_html__( 'Email', 'wp-fundraising' ); ?></th>
					<th class="enable"> <?php echo sprintf( esc_html__( 'Amount [%s]', 'wp-fundraising' ), $symbols ); ?></th>
					<th class="" > <?php echo esc_html__( 'Payment Method', 'wp-fundraising' ); ?> </th>
					<th class="" > <?php echo esc_html__( 'Date', 'wp-fundraising' ); ?> </th>
					<th class="info"> <?php echo esc_html__( 'Action', 'wp-fundraising' ); ?></th>
				</tr>
				</thead>
				<tbody>
				<?php
				$m           = 1;
				$totalAmount = 0;
				foreach ( $penddingDonateList as $pendingData ) :
					if ( $pendingData->payment_gateway == 'online_payment' ) {
						$payment_gateway = 'Paypal';
					} elseif ( $pendingData->payment_gateway == 'stripe_payment' ) {
						$payment_gateway = 'Stripe';
					} elseif ( $pendingData->payment_gateway == 'bank_payment' ) {
						$payment_gateway = 'Bank';
					} elseif ( $pendingData->payment_gateway == 'check_payment' ) {
						$payment_gateway = 'Check';
					} elseif ( $pendingData->payment_gateway == 'offline_payment' ) {
						$payment_gateway = 'Cash';
					} elseif ( $pendingData->payment_gateway == '2checkout' ) {
						$payment_gateway = '2Checkout';
					}
					$totalAmount += $pendingData->donate_amount;
					?>
					<tr id="donate_tr__<?php echo esc_attr( $pendingData->donate_id ); ?>">
						<td class="icon"> <strong><?php echo esc_html( $m ); ?> </strong></td>
						<td class="name"> <?php echo esc_html( $pendingData->email ); ?></td>
						<td class="enable"> <?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $pendingData->donate_amount ) ); ?> </td>
						<td><?php echo esc_html( $payment_gateway ); ?> </td>
						<td><?php echo esc_html( gmdate( 'F d, Y', strtotime( $pendingData->date_time ) ) ); ?> </td>
						<td>
							<?php
							$classNameStatus = strtolower( $pendingData->status );
							if ( $pendingData->status == 'Pending' ) {
								// $classNameStatus = 'process';
							}
							?>
							<select class="<?php echo esc_attr( $classNameStatus ); ?>" name="status_modify" id="<?php echo esc_attr( $pendingData->donate_id ); ?>" onchange="wdp_status_modify_report(this)">
								<?php if ( in_array( $pendingData->status, array( 'Pending' ) ) ) : ?>
									<option value="0" <?php echo isset( $pendingData->status ) && $pendingData->status == 'Pending' ? 'selected' : ''; ?> ><?php echo esc_html__( 'In Process', 'wp-fundraising' ); ?>  </option>
								<?php endif; ?>
								<?php if ( in_array( $pendingData->status, array( 'Review' ) ) ) : ?>
									<option value="1" <?php echo isset( $pendingData->status ) && $pendingData->status == 'Review' ? 'selected' : ''; ?>> <?php echo esc_html__( 'In Review', 'wp-fundraising' ); ?> </option>
								<?php endif; ?>
								<?php if ( in_array( $pendingData->status, array( 'Active', 'Pending', 'Review' ) ) ) : ?>
									<option value="2" <?php echo isset( $pendingData->status ) && $pendingData->status == 'Active' ? 'selected' : ''; ?>> <?php echo esc_html__( 'Success', 'wp-fundraising' ); ?> </option>
								<?php endif; ?>
								<?php if ( in_array( $pendingData->status, array( 'Active' ) ) ) : ?>
									<option value="4" <?php echo isset( $pendingData->status ) && $pendingData->status == 'Refunded' ? 'selected' : ''; ?>> <?php echo esc_html__( 'Refund', 'wp-fundraising' ); ?> </option>
								<?php endif; ?>
								<?php if ( in_array( $pendingData->status, array( 'Pending', 'Review' ) ) ) : ?>
									<option value="3" <?php echo isset( $pendingData->status ) && $pendingData->status == 'DeActive' ? 'selected' : ''; ?>> <?php echo esc_html__( 'Cancel', 'wp-fundraising' ); ?> </option>
								<?php endif; ?>
							</select>
						</td>
					</tr>
					<?php
					$m++;
				endforeach;
				?>
				</tbody>
				<tfoot>
				<tr>
					<th colspan="2">
						<?php echo esc_html__( 'Total Amount : ', 'wp-fundraising' ); ?> [<?php echo esc_html( $symbols ); ?>]
					</th>
					<th>
						<?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $totalAmount ) ); ?>
					</th>
					<th colspan="3">&nbsp;</th>
				</tr>
				</tfoot>
			</table>
		</div>
	<?php endif; ?>
</div>
