<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

if ( ! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ), '_wpnonce' ) ) {
	esc_html_e( 'You are not allowed to view the page', 'wp-fundraising-donation' );
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


	$wfp_invoice     = sanitize_key( $_GET['invoice'] );
	$wfp_campaign_id = intval( $_GET['campaign'] );

	/**
	 * todo - put necessary checking for if cp id is valid
	 * todo - invoice is valid
	 */
	$wfp_author_id     = get_post_field( 'post_author', $wfp_campaign_id );
	$wfp_creator_email = get_the_author_meta( 'email', $wfp_author_id );
	$wfp_admin_email   = get_option( 'admin_email' );

	$wfp_current_user = wp_get_current_user();
	$wfp_user_email   = $wfp_current_user->user_email;

	$wfp_model    = new \WfpFundraising\Utilities\Donation();
	$wfp_donation = $wfp_model->get_donation( $wfp_campaign_id, $wfp_invoice );

	if ( empty( $wfp_donation ) ) {

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
	if ( in_array( $wfp_user_email, array( $wfp_donation['email'], $wfp_creator_email, $wfp_admin_email ) ) ) {

		$wfp_metas = $wfp_model->get_meta( $wfp_donation['donate_id'] );

		$wfp_obj = array();

		foreach ( $wfp_metas as $wfp_meta ) {

			$wfp_obj[ $wfp_meta->meta_key ] = $wfp_meta;
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
					<td><?php echo esc_html( $wfp_donation['donate_id'] ); ?></td>
				</tr>
				<tr>
					<td>Invoice</td>
					<td><?php echo esc_html( $wfp_donation['invoice'] ); ?></td>
				</tr>
				<tr>
					<td>Date</td>
					<td><?php echo esc_html( $wfp_donation['date_time'] ); ?></td>
				</tr>
				<tr>
					<td>Amount</td>
					<td><?php echo esc_html( $wfp_donation['donate_amount'] ); ?></td>
				</tr>
				<tr>
					<td>Currency</td>
					<td><?php echo esc_html( $wfp_obj['_wfp_currency']->meta_value ); ?></td>
				</tr>
				<tr>
					<td>Payment type</td>
					<td><?php echo esc_html( $wfp_donation['payment_type'] ); ?></td>
				</tr>
				<tr>
					<td>Payment gateway</td>
					<td><?php echo esc_html( $wfp_donation['payment_gateway'] ); ?></td>
				</tr>
				<tr>
					<td>Type</td>
					<td><?php echo esc_html( $wfp_donation['fundraising_type'] ); ?></td>
				</tr>
				<tr>
					<td>Status</td>
					<td><?php echo esc_html( $wfp_donation['status'] ); ?></td>
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
					<td><?php echo esc_html( $wfp_obj['_wfp_first_name']->meta_value . ' ' . $wfp_obj['_wfp_last_name']->meta_value ); ?></td>
				</tr>
				<tr>
					<td>Email</td>
					<td><?php echo esc_html( $wfp_donation['email'] ); ?></td>
				</tr>
				<tr>
					<td>Country</td>
					<td><?php echo esc_html( $wfp_obj['_wfp_country']->meta_value ); ?></td>
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

				if ( ! empty( $wfp_obj['_wfp_additional_data']->meta_value ) ) {

					$wfp_addi = unserialize( $wfp_obj['_wfp_additional_data']->meta_value, array( 'allowed_classes' => false ) );

					foreach ( (array) $wfp_addi as $wfp_ky => $vl ) {
						?>

						<tr>
							<td><?php echo esc_html( WfpFundraising\Apps\Key::make_user_readable( $wfp_ky ) ); ?></td>
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
			<?php esc_html_e( 'To view invoice details you have to be either', 'wp-fundraising-donation' ); ?>
		</div>
		<?php
	}
} else {

	$wfp_current_url = home_url( add_query_arg( array(), $GLOBALS['wp']->request ) );
	?>

	<div>
		<?php esc_html_e( 'To view invoice details please', 'wp-fundraising-donation' ); ?> <a
				href="<?php echo esc_url( wp_login_url( $wfp_current_url ) ); ?>"><?php esc_html_e( 'Log in', 'wp-fundraising-donation' ); ?></a>
	</div>

	<?php
}
