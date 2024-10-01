<?php

if ( ! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ), '_wpnonce' ) ) {
	esc_html_e( 'You are not allowed to view the page', 'wp-fundraising' );
}

if ( empty( $_GET['invoice'] ) || empty( $_GET['campaign'] ) ) {

	?>
	<div>
		<strong>No invoice found.</strong>
	</div>
	<?php

	return;
}


if ( is_user_logged_in() ) {


	$invoice     = sanitize_key( $_GET['invoice'] );
	$campaign_id = intval( $_GET['campaign'] );

	/**
	 * todo - put necessary checking for if cp id is valid
	 * todo - invoice is valid
	 */
	$author_id     = get_post_field( 'post_author', $campaign_id );
	$creator_email = get_the_author_meta( 'email', $author_id );
	$admin_email   = get_option( 'admin_email' );

	$current_user = wp_get_current_user();
	$user_email   = $current_user->user_email;

	$model    = new \WfpFundraising\Utilities\Donation();
	$donation = $model->get_donation( $campaign_id, $invoice );

	if ( empty( $donation ) ) {

		?>
		<div>
			<strong>Invalid data.</strong>
		</div>
		<?php

		return;
	}

	/**
	 * To view invoice you must be either campaign creator or admin or the donor
	 */
	if ( in_array( $user_email, array( $donation['email'], $creator_email, $admin_email ) ) ) {

		$metas = $model->get_meta( $donation['donate_id'] );

		$obj = array();

		foreach ( $metas as $meta ) {

			$obj[ $meta->meta_key ] = $meta;
		}

		?>
		<div>

			<table class="table table-bordered">
				<thead>
				<tr>
					<th colspan="2">Order details</th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td>Order No.</td>
					<td><?php echo esc_html( $donation['donate_id'] ); ?></td>
				</tr>
				<tr>
					<td>Invoice</td>
					<td><?php echo esc_html( $donation['invoice'] ); ?></td>
				</tr>
				<tr>
					<td>Date</td>
					<td><?php echo esc_html( $donation['date_time'] ); ?></td>
				</tr>
				<tr>
					<td>Amount</td>
					<td><?php echo esc_html( $donation['donate_amount'] ); ?></td>
				</tr>
				<tr>
					<td>Currency</td>
					<td><?php echo esc_html( $obj['_wfp_currency']->meta_value ); ?></td>
				</tr>
				<tr>
					<td>Payment type</td>
					<td><?php echo esc_html( $donation['payment_type'] ); ?></td>
				</tr>
				<tr>
					<td>Payment gateway</td>
					<td><?php echo esc_html( $donation['payment_gateway'] ); ?></td>
				</tr>
				<tr>
					<td>Type</td>
					<td><?php echo esc_html( $donation['fundraising_type'] ); ?></td>
				</tr>
				<tr>
					<td>Status</td>
					<td><?php echo esc_html( $donation['status'] ); ?></td>
				</tr>
				</tbody>
			</table>


			<table class="table table-bordered">
				<thead>
				<tr>
					<th colspan="2">Donor details</th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td>Name</td>
					<td><?php echo esc_html( $obj['_wfp_first_name']->meta_value . ' ' . $obj['_wfp_last_name']->meta_value ); ?></td>
				</tr>
				<tr>
					<td>Email</td>
					<td><?php echo esc_html( $donation['email'] ); ?></td>
				</tr>
				<tr>
					<td>Country</td>
					<td><?php echo esc_html( $obj['_wfp_country']->meta_value ); ?></td>
				</tr>

				</tbody>
			</table>


			<table class="table table-bordered">
				<thead>
				<tr>
					<th colspan="2">Billing details</th>
				</tr>
				</thead>
				<tbody>

				<?php

				if ( ! empty( $obj['_wfp_additional_data']->meta_value ) ) {

					$addi = unserialize( $obj['_wfp_additional_data']->meta_value );

					foreach ( $addi as $ky => $vl ) {
						?>

						<tr>
							<td><?php echo esc_html( WfpFundraising\Apps\Key::make_user_readable( $ky ) ); ?></td>
							<td><?php echo esc_html( $vl ); ?></td>
						</tr>

						<?php
					}
				}

				?>

				</tbody>
			</table>

		</div>
		<?php

	} else {

		?>
		<div>
			<?php esc_html_e( 'To view invoice details you have to be either', 'wp-fundraising' ); ?>
		</div>
		<?php
	}
} else {

	$current_url = home_url( add_query_arg( array(), $GLOBALS['wp']->request ) );
	?>

	<div>
		<?php esc_html_e( 'To view invoice details please', 'wp-fundraising' ); ?> <a
				href="<?php echo esc_url( wp_login_url( $current_url ) ); ?>"><?php esc_html_e( 'Log in', 'wp-fundraising' ); ?></a>
	</div>

	<?php
}
