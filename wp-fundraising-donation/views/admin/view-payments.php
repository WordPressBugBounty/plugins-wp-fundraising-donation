<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

$wfp_url = admin_url( 'edit.php' );
if ( isset( $_GET['view_payment_nonce_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['view_payment_nonce_field'] ) ), 'view_payments_nonce' ) ) {
	$wfpFromDate = empty( $_GET['rpt_f_date'] ) ? gmdate( 'Y-m-d', strtotime( '-1 month' ) ) : gmdate( 'Y-m-d', strtotime( sanitize_text_field( wp_unslash( $_GET['rpt_f_date'] ) ) ) );
	$wfpToDate   = empty( $_GET['rpt_t_date'] ) ? gmdate( 'Y-m-d' ) : gmdate( 'Y-m-d', strtotime( sanitize_text_field( wp_unslash( $_GET['rpt_t_date'] ) ) ) );
	$wfp_camp_id  = empty( $_GET['donation_id'] ) ? '' : intval( wp_unslash( $_GET['donation_id'] ) );
} else {
	$wfpFromDate = gmdate( 'Y-m-d', strtotime( '-1 month' ) );
	$wfpToDate   = gmdate( 'Y-m-d' );
	$wfp_camp_id  = '';
}




$wfpFromDate = empty( $_GET['rpt_f_date'] ) ? gmdate( 'Y-m-d', strtotime( '-1 month' ) ) : gmdate( 'Y-m-d', strtotime( sanitize_text_field( wp_unslash( $_GET['rpt_f_date'] ) ) ) );
$wfpToDate   = empty( $_GET['rpt_t_date'] ) ? gmdate( 'Y-m-d' ) : gmdate( 'Y-m-d', strtotime( sanitize_text_field( wp_unslash( $_GET['rpt_t_date'] ) ) ) );
$wfp_camp_id  = empty( $_GET['donation_id'] ) ? '' : intval( wp_unslash( $_GET['donation_id'] ) );
$wfp_p_type   = \WfpFundraising\Apps\Fundraising_Cpt::TYPE;

?>

<div class="wfp-view wfp-view-admin wfp-payment-details">

	<div class="xs_shadow_card">

		<div class="report-search">
			<form action="<?php echo esc_url( $wfp_url ); ?>" method="get">
				<?php wp_nonce_field( 'view_payment_nonce_field', 'view_payments_nonce' ); ?>
				<input type="hidden"
					   name="post_type"
					   value="<?php  esc_attr( $wfp_p_type ); ?>"/>

				<input type="hidden" name="page" value="donations"/>

				<div class="wfp-search-tab-wraper">
					<div class="search-tab">
						<?php
						$wfpGetForms = get_posts(
							array(
								'post_type'   => $wfp_p_type,
								'order'       => 'DESC',
								'numberposts' => -1,
								'post_status' => 'publish',
							)
						);
						?>
						<label for="wfdp-forms-search"> <?php echo esc_html__( 'Select campaign', 'wp-fundraising-donation' ); ?> </label>

						<select class="wfp-select2-country" name="donation_id" id="wfdp-forms-search" required>
							<option value="" <?php echo empty( $wfp_camp_id ) ? 'selected' : ''; ?>>
								<?php echo esc_html__( 'Select a campaign', 'wp-fundraising-donation' ); ?>
							</option> 
							<?php

							foreach ( $wfpGetForms as $postData ) :
								?>
								<option value="<?php echo esc_attr( $postData->ID ); ?>"<?php echo $wfp_camp_id == $postData->ID ? 'selected' : ''; ?>>
									<?php echo esc_html( $postData->post_title ); ?>
								</option> 
								<?php
							endforeach;
							?>
						</select>
					</div>

					<div class="search-tab">
						<label for="wfdp-forms-search"> <?php echo esc_html__( 'From Date', 'wp-fundraising-donation' ); ?> </label>
						<input type="text" value="<?php echo esc_attr( $wfpFromDate ); ?>" name="rpt_f_date"
							   class="datepicker-donate" id="donate_report_from_date">
					</div>
					<div class="search-tab">
						<label for="wfdp-forms-search"> <?php echo esc_html__( 'To Date', 'wp-fundraising-donation' ); ?> </label>
						<input type="text" value="<?php echo esc_attr( $wfpToDate ); ?>" name="rpt_t_date"
							   class="datepicker-donate" id="donate_report_to_date">
					</div>


					<div class="search-tab">
						<button class="button button-primary" type="submit">
							<span class="wfpf wfpf-search"></span>
						</button>
					</div>

				</div>
			</form>
		</div>
	</div>

</div>

<?php


if ( empty( $wfp_camp_id ) ) {

	return;
}

$wfp_tab = empty( $_GET['wfp_report_tab'] ) ? 'all' : htmlentities( sanitize_text_field( wp_unslash( $_GET['wfp_report_tab'] ) ), ENT_QUOTES );

$wfp_link  = admin_url() . 'edit.php?post_type=' . $wfp_p_type . '';
$wfp_link .= '&page=donations';
$wfp_link .= '&donation_id=' . $wfp_camp_id;

if ( ! empty( $_GET['rpt_f_date'] ) ) {

	$wfp_link .= '&rpt_f_date=' . sanitize_text_field( wp_unslash( $_GET['rpt_f_date'] ) );
}

if ( ! empty( $_GET['rpt_t_date'] ) ) {

	$wfp_link .= '&rpt_t_date=' . sanitize_text_field( wp_unslash( $_GET['rpt_t_date'] ) );
}


$wfpSymbols = \WfpFundraising\Apps\Global_Settings::instance()->get_currency_symbol();

$wfp_model    = new \WfpFundraising\Model\WFP_Fundraising();
$wfp_camp_obj = $wfp_model->set_campaign( $wfp_camp_id );

?>

<div class="wfp-view wfp-view-admin wfp-payment-details">

	<div class="xs_shadow_card">

		<div class="wdp-form-information xs_shadow_card">
			<div class="xs-fundrising-title-wraper">
				<h3 class="xs-fundrising-title"><?php esc_html_e( 'Payment Details', 'wp-fundraising-donation' ); ?> </h3>
				<hr>
			</div>
			<div class="xs_payment_info_wraper">
				<div class="xs-left-content">

					<ul class="xs_payment_details">
						<li>
							<?php

							$wfp_p_count = $wfp_camp_obj->count_by_payment_gateway( 'online_payment' )->get_var();
							$wfp_p_sum   = $wfp_camp_obj->sum_by_payment_gateway( 'online_payment' )->get_var();

							?>
							<strong> <?php echo esc_html__( 'Paypal', 'wp-fundraising-donation' ); ?> : </strong>
							<span class="xs_stripe_border"></span>
							<span><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfp_p_sum ) ); ?>
								(<?php echo esc_html( $wfp_p_count ); ?>)</span>
						</li>

						<li>
							<?php

							$wfp_ofline_count = $wfp_camp_obj->count_by_payment_gateway( 'offline_payment' )->get_var();
							$wfp_ofline_sum   = $wfp_camp_obj->sum_by_payment_gateway( 'offline_payment' )->get_var();

							?>
							<strong> <?php echo esc_html__( 'Cash', 'wp-fundraising-donation' ); ?> : </strong>
							<span class="xs_stripe_border"></span>
							<span><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfp_ofline_sum ) ); ?>
								(<?php echo esc_html( $wfp_ofline_count ); ?>)</span>
						</li>

						<li>
							<?php

							$wfp_check_count = $wfp_camp_obj->count_by_payment_gateway( 'check_payment' )->get_var();
							$wfp_check_sum   = $wfp_camp_obj->sum_by_payment_gateway( 'check_payment' )->get_var();

							?>
							<strong> <?php echo esc_html__( 'Check', 'wp-fundraising-donation' ); ?> : </strong>
							<span class="xs_stripe_border"></span>
							<span><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfp_check_sum ) ); ?>
								(<?php echo esc_html( $wfp_check_count ); ?>)</span>
						</li>

						<li>
							<?php

							$wfp_bank_count = $wfp_camp_obj->count_by_payment_gateway( 'bank_payment' )->get_var();
							$wfp_bank_sum   = $wfp_camp_obj->sum_by_payment_gateway( 'bank_payment' )->get_var();

							?>
							<strong> <?php echo esc_html__( 'Bank', 'wp-fundraising-donation' ); ?> : </strong>
							<span class="xs_stripe_border"></span>
							<span><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfp_bank_sum ) ); ?>
								(<?php echo esc_html( $wfp_bank_count ); ?>)</span>
						</li>

						<li>
							<?php

							$wfp_stripe_count = $wfp_camp_obj->count_by_payment_gateway( 'stripe_payment' )->get_var();
							$wfp_stripe_sum   = $wfp_camp_obj->sum_by_payment_gateway( 'stripe_payment' )->get_var();

							?>
							<strong> <?php echo esc_html__( 'Stripe', 'wp-fundraising-donation' ); ?> : </strong>
							<span class="xs_stripe_border"></span>
							<span><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfp_stripe_sum ) ); ?>
								(<?php echo esc_html( $wfp_stripe_count ); ?>)</span>
						</li>
						<li>
							<?php

							$wfp_checkout_count = $wfp_camp_obj->count_by_payment_gateway( '2checkout' )->get_var();
							$wfp_checkout_sum   = $wfp_camp_obj->sum_by_payment_gateway( '2checkout' )->get_var();

							?>
							<strong> <?php echo esc_html__( '2Checkout', 'wp-fundraising-donation' ); ?> : </strong>
							<span class="xs_stripe_border"></span>
							<span><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfp_checkout_sum ) ); ?>
								(<?php echo esc_html( $wfp_checkout_count ); ?>)</span>
						</li>
						<li class="xs_hr_gap"></li>
						<li class="xs_success_amount">
							<?php

							$wfp_active_count = $wfp_camp_obj->count_successful()->get_var();
							$wfp_active_sum   = $wfp_camp_obj->sum_successful()->get_var();

							?>
							<strong> <?php
							// translators: %s: currency symbol.
						echo sprintf( esc_html__( 'Success Amount (%s)', 'wp-fundraising-donation' ), esc_html( $wfpSymbols ) ); ?>
								: </strong>
							<span class="xs_stripe_border"></span>
							<span><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfp_active_sum ) ); ?>
								(<?php echo esc_html( $wfp_active_count ); ?>)</span>
						</li>
					</ul>

					<ul class="xs_payment_details">
						<li class="xs_list_title">
							<?php echo esc_html__( 'Payment Types', 'wp-fundraising-donation' ); ?>
						</li>
						<?php

						$wfp_default_type_count = $wfp_camp_obj->count_by_payment_type( 'default' )->get_var();
						$wfp_default_type_sum   = $wfp_camp_obj->sum_by_payment_type( 'default' )->get_var();

						$wfp_woocommerce_type_count = $wfp_camp_obj->count_by_payment_type( 'woocommerce' )->get_var();
						$wfp_woocommerce_type_sum   = $wfp_camp_obj->sum_by_payment_type( 'woocommerce' )->get_var();

						?>
						<li>
							<strong> <?php
							// translators: %s: currency symbol.
						echo sprintf( esc_html__( 'Default (%s)', 'wp-fundraising-donation' ), esc_html( $wfpSymbols ) ); ?>
								: </strong>
							<span class="xs_stripe_border"></span>
							<span><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfp_default_type_sum ) ); ?>
								(<?php echo esc_html( $wfp_default_type_count ); ?>)</span>
						</li>

						<li>
							<strong> <?php
							// translators: %s: currency symbol.
						echo sprintf( esc_html__( 'Woocommerce (%s)', 'wp-fundraising-donation' ), esc_html( $wfpSymbols ) ); ?>
								: </strong>
							<span class="xs_stripe_border"></span>
							<span><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfp_woocommerce_type_sum ) ); ?>
								(<?php echo esc_html( $wfp_woocommerce_type_count ); ?>)</span>
						</li>
					</ul>

				</div>

				<div class="xs-right-content">

					<ul class="xs_payment_details">
						<?php

						$wfp_pending_count = $wfp_camp_obj->count_pending()->get_var();
						$wfp_pending_sum   = $wfp_camp_obj->sum_pending()->get_var();

						$wfp_review_count = $wfp_camp_obj->count_in_review()->get_var();
						$wfp_review_sum   = $wfp_camp_obj->sum_in_review()->get_var();

						?>

						<li>
							<strong> <?php echo esc_html__( 'In Process :', 'wp-fundraising-donation' ); ?> </strong>
							<span class="xs_stripe_border"></span>
							<span><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfp_pending_sum ) ); ?>
								(<?php echo esc_html( $wfp_pending_count ); ?>)</span>
						</li>

						<li>
							<strong> <?php echo esc_html__( 'In Review :', 'wp-fundraising-donation' ); ?> </strong>
							<span class="xs_stripe_border"></span>
							<span><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfp_review_sum ) ); ?>
								(<?php echo esc_html( $wfp_review_count ); ?>)</span>
						</li>
						<li class="xs_hr_gap xs_danger"></li>
						<li class="xs_success_amount xs_danger">
							<strong> <?php
							// translators: %s: currency symbol.
						echo sprintf( esc_html__( 'Total Amount (%s)', 'wp-fundraising-donation' ), esc_html( $wfpSymbols ) ); ?> </strong>
							<span class="xs_stripe_border"></span>
							<span><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfp_pending_sum + $wfp_review_sum ) ); ?>
								(<?php echo esc_html( $wfp_pending_count + $wfp_review_count ); ?>)</span>
						</li>
					</ul>
				</div>
				<div class="xs-clearfix"></div>
			</div>
		</div>

	</div>

	<?php
	$wfpTodayDate  = gmdate( 'Y-m-d' );
	$wfp_days_10ago = gmdate( 'Y-m-d', strtotime( '-10 days', strtotime( $wfpTodayDate ) ) );

	$wfp_limit        = 50;
	$wfp_total_amount = 0;

	$wfp_status = $wfp_tab == 'review' ? 'Review' : ( $wfp_tab == 'pending' ? 'Pending' : 'Active' );

	$wfp_all_donations = $wfp_camp_obj->get_all_donation_by_status_and_date( $wfp_status, $wfpFromDate, $wfpToDate, $wfp_limit );
	// $wfp_total_donation_count = $wfp_camp_obj->get_total_donation_count_by_status($wfp_status);
	$wfp_total_donation_count = count( $wfp_all_donations );

	/**
	 * todo - right now more or less 23 database query is performed to show this info
	 * todo - it is possible to reduce the number of query to less than 6! - AR
	 */
	// $wfp_camp_obj->debug_log();

	?>
	<div class="wdp-form-information xs_shadow_card">
		<div class="xs_recent_donation_title_wraper">
			<h3 class="xs-fundrising-title"><?php esc_html_e( 'Recent Donation List', 'wp-fundraising-donation' ); ?> </h3>

			<div class="xs_period_wraper xs_text_center">
				<?php echo esc_html__( 'Period : ', 'wp-fundraising-donation' ); ?>
				<datetime><?php echo esc_html( wp_date( 'F j, Y', strtotime( $wfp_days_10ago ), new \DateTimeZone( 'UTC' ) ) ); ?></datetime>
				<em><?php esc_html_e( 'to', 'wp-fundraising-donation' ); ?></em>
				<datetime><?php echo esc_html( wp_date( 'F j, Y', strtotime( $wfpTodayDate ), new \DateTimeZone( 'UTC' ) ) ); ?></datetime>
			</div>

			<div class="report-heading">
				<ul class="xs_fundrising_filter xs_text_center">
					<li><a class="<?php echo ( $wfp_tab == 'all' ) ? 'active' : ''; ?>"
					   href="<?php echo esc_url( $wfp_link ); ?>&wfp_report_tab=all"> <?php echo esc_html( strtoupper( __( 'Success', 'wp-fundraising-donation' ) ) ); ?> </a>
				</li>
				<li><a class="<?php echo ( $wfp_tab == 'review' ) ? 'active' : ''; ?>"
					   href="<?php echo esc_url( $wfp_link ); ?>&wfp_report_tab=review"> <?php echo esc_html( strtoupper( __( 'In Review', 'wp-fundraising-donation' ) ) ); ?></a>
				</li>
				<li><a class="<?php echo ( $wfp_tab == 'pending' ) ? 'active' : ''; ?>"
					   href="<?php echo esc_url( $wfp_link ); ?>&wfp_report_tab=pending"><?php echo esc_html( strtoupper( __( 'In Process', 'wp-fundraising-donation' ) ) ); ?></a>
					</li>
				</ul>
			</div>

			<div>
				<span><?php
			// translators: %s: number of items per page.
			echo sprintf( esc_html__( 'Show %s (per page) in total ', 'wp-fundraising-donation' ), esc_html( $wfp_limit ) ); ?><?php echo esc_html( $wfp_total_donation_count ); ?></span>
			</div>
		</div>

		<div class="xs_payment_review_table_wraper">
			<table class="form-table xs_payment_review_table">
				<thead>
				<tr>
					<th class="sort"><?php echo esc_html__( 'S.L.', 'wp-fundraising-donation' ); ?></th>
					<th class="name"> <?php echo esc_html__( 'Email', 'wp-fundraising-donation' ); ?></th>
					<?php /* translators: %s: currency symbol. */ ?>
					<th class="enable"> <?php echo esc_html( sprintf( __( 'Amount [%s]', 'wp-fundraising-donation' ), $wfpSymbols ) ); ?></th>
					<th class=""> <?php echo esc_html__( 'Payment Method', 'wp-fundraising-donation' ); ?> </th>
					<th class=""> <?php echo esc_html__( 'Date', 'wp-fundraising-donation' ); ?> </th>
					<th class="info"> <?php echo esc_html__( 'Action', 'wp-fundraising-donation' ); ?></th>
				</tr>
				</thead>

				<tbody>

				<?php

				foreach ( $wfp_all_donations as $wfp_sl => $wfp_donation ) {

					$wfp_total_amount += $wfp_donation->donate_amount;

					?>

					<tr id="donate_tr__<?php echo esc_attr( $wfp_donation->donate_id ); ?>">
						<td class="icon"><strong><?php echo esc_html( $wfp_sl + 1 ); ?> </strong></td>
						<td class="name"><?php echo esc_html( $wfp_donation->email ); ?></td>
						<td class="enable"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfp_donation->donate_amount ) ); ?> </td>
						<td><?php echo esc_html( \WfpFundraising\Apps\Global_Settings::$allowed_gateway[ $wfp_donation->payment_gateway ] ); ?> </td>
						<td><?php echo esc_html( wp_date( 'F d, Y', strtotime( $wfp_donation->date_time ), new \DateTimeZone( 'UTC' ) ) ); ?> </td>


						<td>
							<?php

							$wfp_cls = strtolower( $wfp_donation->status );

							if ( $wfp_tab == 'all' ) {

								$wfp_d_stat = array(
									'Active'   => esc_html__( 'Success', 'wp-fundraising-donation' ),
									'Refunded' => esc_html__( 'Refund', 'wp-fundraising-donation' ),
								);

							} else {

								$wfp_d_stat = array(
									'Pending'  => esc_html__( 'In Process', 'wp-fundraising-donation' ),
									'Review'   => esc_html__( 'In Review', 'wp-fundraising-donation' ),
									'Active'   => esc_html__( 'Success', 'wp-fundraising-donation' ),
									'DeActive' => esc_html__( 'Cancel', 'wp-fundraising-donation' ),
								);
							}

							?>
							<select class="<?php echo esc_attr( $wfp_cls ); ?>" name="status_modify"
									onchange="update_donation_status(this, '<?php echo esc_attr( $wfp_donation->donate_id ); ?>')">

								<?php

								foreach ( $wfp_d_stat as $wfp_key => $val ) {
									?>

									<option value="<?php echo esc_attr( $wfp_key ); ?>" <?php echo $wfp_donation->status == $wfp_key ? 'selected' : ''; ?>> <?php echo esc_html( $val ); ?> </option> 
															  <?php
								}

								?>

							</select>
						</td>

					</tr>

					<?php
				}

				?>

				</tbody>

				<tfoot>
				<tr>
					<th colspan="2">
						<?php echo esc_html__( 'Total Amount : ', 'wp-fundraising-donation' ); ?> [<?php echo esc_html( $wfpSymbols ); ?>]
					</th>
					<th>
						<?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfp_total_amount ) ); ?>
					</th>
					<th colspan="3">&nbsp;</th>
				</tr>
				</tfoot>

			</table>
		</div>
	</div>
</div>
