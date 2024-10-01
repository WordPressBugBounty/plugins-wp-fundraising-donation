<?php
$url = admin_url( 'edit.php' );
if ( isset( $_GET['view_payment_nonce_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['view_payment_nonce_field'] ) ), 'view_payments_nonce' ) ) {
	$fromDate = empty( $_GET['rpt_f_date'] ) ? gmdate( 'Y-m-d', strtotime( '-1 month' ) ) : gmdate( 'Y-m-d', strtotime( sanitize_text_field( wp_unslash( $_GET['rpt_f_date'] ) ) ) );
	$toDate   = empty( $_GET['rpt_t_date'] ) ? gmdate( 'Y-m-d' ) : gmdate( 'Y-m-d', strtotime( sanitize_text_field( wp_unslash( $_GET['rpt_t_date'] ) ) ) );
	$camp_id  = empty( $_GET['donation_id'] ) ? '' : intval( wp_unslash( $_GET['donation_id'] ) );
} else {
	$fromDate = gmdate( 'Y-m-d', strtotime( '-1 month' ) );
	$toDate   = gmdate( 'Y-m-d' );
	$camp_id  = '';
}




$fromDate = empty( $_GET['rpt_f_date'] ) ? gmdate( 'Y-m-d', strtotime( '-1 month' ) ) : gmdate( 'Y-m-d', strtotime( sanitize_text_field( wp_unslash( $_GET['rpt_f_date'] ) ) ) );
$toDate   = empty( $_GET['rpt_t_date'] ) ? gmdate( 'Y-m-d' ) : gmdate( 'Y-m-d', strtotime( sanitize_text_field( wp_unslash( $_GET['rpt_t_date'] ) ) ) );
$camp_id  = empty( $_GET['donation_id'] ) ? '' : intval( wp_unslash( $_GET['donation_id'] ) );
$p_type   = \WfpFundraising\Apps\Fundraising_Cpt::TYPE;

?>

<div class="wfp-view wfp-view-admin wfp-payment-details">

	<div class="xs_shadow_card">

		<div class="report-search">
			<form action="<?php echo esc_url( $url ); ?>" method="get">
				<?php wp_nonce_field( 'view_payment_nonce_field', 'view_payments_nonce' ); ?>
				<input type="hidden"
					   name="post_type"
					   value="<?php echo esc_attr( $p_type ); ?>"/>

				<input type="hidden" name="page" value="donations"/>

				<div class="wfp-search-tab-wraper">
					<div class="search-tab">
						<?php
						$getForms = get_posts(
							array(
								'post_type'   => $p_type,
								'order'       => 'DESC',
								'numberposts' => -1,
								'post_status' => 'publish',
							)
						);
						?>
						<label for="wfdp-forms-search"> <?php echo esc_html__( 'Select campaign', 'wp-fundraising' ); ?> </label>

						<select class="wfp-select2-country" name="donation_id" id="wfdp-forms-search" required>
							<option value="" <?php echo empty( $camp_id ) ? 'selected' : ''; ?>>
								<?php echo esc_html__( 'Select a campaign', 'wp-fundraising' ); ?>
							</option> 
							<?php

							foreach ( $getForms as $postData ) :
								?>
								<option value="<?php echo esc_attr( $postData->ID ); ?>"<?php echo $camp_id == $postData->ID ? 'selected' : ''; ?>>
									<?php echo esc_html( $postData->post_title ); ?>
								</option> 
								<?php
							endforeach;
							?>
						</select>
					</div>

					<div class="search-tab">
						<label for="wfdp-forms-search"> <?php echo esc_html__( 'From Date', 'wp-fundraising' ); ?> </label>
						<input type="text" value="<?php echo esc_attr( $fromDate ); ?>" name="rpt_f_date"
							   class="datepicker-donate" id="donate_report_from_date">
					</div>
					<div class="search-tab">
						<label for="wfdp-forms-search"> <?php echo esc_html__( 'To Date', 'wp-fundraising' ); ?> </label>
						<input type="text" value="<?php echo esc_attr( $toDate ); ?>" name="rpt_t_date"
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


if ( empty( $camp_id ) ) {

	return;
}

$tab = empty( $_GET['wfp_report_tab'] ) ? 'all' : htmlentities( sanitize_text_field( wp_unslash( $_GET['wfp_report_tab'] ) ), ENT_QUOTES );

$link  = admin_url() . 'edit.php?post_type=' . $p_type . '';
$link .= '&page=donations';
$link .= '&donation_id=' . $camp_id;

if ( ! empty( $_GET['rpt_f_date'] ) ) {

	$link .= '&rpt_f_date=' . sanitize_text_field( wp_unslash( $_GET['rpt_f_date'] ) );
}

if ( ! empty( $_GET['rpt_t_date'] ) ) {

	$link .= '&rpt_t_date=' . sanitize_text_field( wp_unslash( $_GET['rpt_t_date'] ) );
}


$symbols = \WfpFundraising\Apps\Global_Settings::instance()->get_currency_symbol();

$model    = new \WfpFundraising\Model\WFP_Fundraising();
$camp_obj = $model->set_campaign( $camp_id );

?>

<div class="wfp-view wfp-view-admin wfp-payment-details">

	<div class="xs_shadow_card">

		<div class="wdp-form-information xs_shadow_card">
			<div class="xs-fundrising-title-wraper">
				<h3 class="xs-fundrising-title"><?php esc_html_e( 'Payment Details', 'wp-fundraising' ); ?> </h3>
				<hr>
			</div>
			<div class="xs_payment_info_wraper">
				<div class="xs-left-content">

					<ul class="xs_payment_details">
						<li>
							<?php

							$p_count = $camp_obj->count_by_payment_gateway( 'online_payment' )->get_var();
							$p_sum   = $camp_obj->sum_by_payment_gateway( 'online_payment' )->get_var();

							?>
							<strong> <?php echo esc_html__( 'Paypal', 'wp-fundraising' ); ?> : </strong>
							<span class="xs_stripe_border"></span>
							<span><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $p_sum ) ); ?>
								(<?php echo esc_html( $p_count ); ?>)</span>
						</li>

						<li>
							<?php

							$ofline_count = $camp_obj->count_by_payment_gateway( 'offline_payment' )->get_var();
							$ofline_sum   = $camp_obj->sum_by_payment_gateway( 'offline_payment' )->get_var();

							?>
							<strong> <?php echo esc_html__( 'Cash', 'wp-fundraising' ); ?> : </strong>
							<span class="xs_stripe_border"></span>
							<span><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $ofline_sum ) ); ?>
								(<?php echo esc_html( $ofline_count ); ?>)</span>
						</li>

						<li>
							<?php

							$check_count = $camp_obj->count_by_payment_gateway( 'check_payment' )->get_var();
							$check_sum   = $camp_obj->sum_by_payment_gateway( 'check_payment' )->get_var();

							?>
							<strong> <?php echo esc_html__( 'Check', 'wp-fundraising' ); ?> : </strong>
							<span class="xs_stripe_border"></span>
							<span><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $check_sum ) ); ?>
								(<?php echo esc_html( $check_count ); ?>)</span>
						</li>

						<li>
							<?php

							$bank_count = $camp_obj->count_by_payment_gateway( 'bank_payment' )->get_var();
							$bank_sum   = $camp_obj->sum_by_payment_gateway( 'bank_payment' )->get_var();

							?>
							<strong> <?php echo esc_html__( 'Bank', 'wp-fundraising' ); ?> : </strong>
							<span class="xs_stripe_border"></span>
							<span><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $bank_sum ) ); ?>
								(<?php echo esc_html( $bank_count ); ?>)</span>
						</li>

						<li>
							<?php

							$stripe_count = $camp_obj->count_by_payment_gateway( 'stripe_payment' )->get_var();
							$stripe_sum   = $camp_obj->sum_by_payment_gateway( 'stripe_payment' )->get_var();

							?>
							<strong> <?php echo esc_html__( 'Stripe', 'wp-fundraising' ); ?> : </strong>
							<span class="xs_stripe_border"></span>
							<span><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $stripe_sum ) ); ?>
								(<?php echo esc_html( $stripe_count ); ?>)</span>
						</li>
						<li>
							<?php

							$checkout_count = $camp_obj->count_by_payment_gateway( '2checkout' )->get_var();
							$checkout_sum   = $camp_obj->sum_by_payment_gateway( '2checkout' )->get_var();

							?>
							<strong> <?php echo esc_html__( '2Checkout', 'wp-fundraising' ); ?> : </strong>
							<span class="xs_stripe_border"></span>
							<span><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $checkout_sum ) ); ?>
								(<?php echo esc_html( $checkout_count ); ?>)</span>
						</li>
						<li class="xs_hr_gap"></li>
						<li class="xs_success_amount">
							<?php

							$active_count = $camp_obj->count_successful()->get_var();
							$active_sum   = $camp_obj->sum_successful()->get_var();

							?>
							<strong> <?php echo sprintf( esc_html__( 'Success Amount (%s)', 'wp-fundraising' ), $symbols ); ?>
								: </strong>
							<span class="xs_stripe_border"></span>
							<span><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $active_sum ) ); ?>
								(<?php echo esc_html( $active_count ); ?>)</span>
						</li>
					</ul>

					<ul class="xs_payment_details">
						<li class="xs_list_title">
							<?php echo esc_html__( 'Payment Types', 'wp-fundraising' ); ?>
						</li>
						<?php

						$default_type_count = $camp_obj->count_by_payment_type( 'default' )->get_var();
						$default_type_sum   = $camp_obj->sum_by_payment_type( 'default' )->get_var();

						$woocommerce_type_count = $camp_obj->count_by_payment_type( 'woocommerce' )->get_var();
						$woocommerce_type_sum   = $camp_obj->sum_by_payment_type( 'woocommerce' )->get_var();

						?>
						<li>
							<strong> <?php echo sprintf( esc_html__( 'Default (%s)', 'wp-fundraising' ), $symbols ); ?>
								: </strong>
							<span class="xs_stripe_border"></span>
							<span><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $default_type_sum ) ); ?>
								(<?php echo esc_html( $default_type_count ); ?>)</span>
						</li>

						<li>
							<strong> <?php echo sprintf( esc_html__( 'Woocommerce (%s)', 'wp-fundraising' ), $symbols ); ?>
								: </strong>
							<span class="xs_stripe_border"></span>
							<span><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $woocommerce_type_sum ) ); ?>
								(<?php echo esc_html( $woocommerce_type_count ); ?>)</span>
						</li>
					</ul>

				</div>

				<div class="xs-right-content">

					<ul class="xs_payment_details">
						<?php

						$pending_count = $camp_obj->count_pending()->get_var();
						$pending_sum   = $camp_obj->sum_pending()->get_var();

						$review_count = $camp_obj->count_in_review()->get_var();
						$review_sum   = $camp_obj->sum_in_review()->get_var();

						?>

						<li>
							<strong> <?php echo esc_html__( 'In Process :', 'wp-fundraising' ); ?> </strong>
							<span class="xs_stripe_border"></span>
							<span><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $pending_sum ) ); ?>
								(<?php echo esc_html( $pending_count ); ?>)</span>
						</li>

						<li>
							<strong> <?php echo esc_html__( 'In Review :', 'wp-fundraising' ); ?> </strong>
							<span class="xs_stripe_border"></span>
							<span><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $review_sum ) ); ?>
								(<?php echo esc_html( $review_count ); ?>)</span>
						</li>
						<li class="xs_hr_gap xs_danger"></li>
						<li class="xs_success_amount xs_danger">
							<strong> <?php echo sprintf( esc_html__( 'Total Amount (%s)', 'wp-fundraising' ), $symbols ); ?> </strong>
							<span class="xs_stripe_border"></span>
							<span><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $pending_sum + $review_sum ) ); ?>
								(<?php echo esc_html( $pending_count + $review_count ); ?>)</span>
						</li>
					</ul>
				</div>
				<div class="xs-clearfix"></div>
			</div>
		</div>

	</div>

	<?php
	$todayDate  = gmdate( 'Y-m-d' );
	$days_10ago = gmdate( 'Y-m-d', strtotime( '-10 days', strtotime( $todayDate ) ) );

	$limit        = 50;
	$total_amount = 0;

	$status = $tab == 'review' ? 'Review' : ( $tab == 'pending' ? 'Pending' : 'Active' );

	$all_donations = $camp_obj->get_all_donation_by_status_and_date( $status, $fromDate, $toDate, $limit );
	// $total_donation_count = $camp_obj->get_total_donation_count_by_status($status);
	$total_donation_count = count( $all_donations );

	/**
	 * todo - right now more or less 23 database query is performed to show this info
	 * todo - it is possible to reduce the number of query to less than 6! - AR
	 */
	// $camp_obj->debug_log();

	?>
	<div class="wdp-form-information xs_shadow_card">
		<div class="xs_recent_donation_title_wraper">
			<h3 class="xs-fundrising-title"><?php esc_html_e( 'Recent Donation List', 'wp-fundraising' ); ?> </h3>

			<div class="xs_period_wraper xs_text_center">
				<?php echo esc_html__( 'Period : ', 'wp-fundraising' ); ?>
				<datetime><?php echo esc_html( gmdate( 'F j, Y', strtotime( $days_10ago ) ) ); ?></datetime>
				<em>to</em>
				<datetime><?php echo esc_html( gmdate( 'F j, Y', strtotime( $todayDate ) ) ); ?></datetime>
			</div>

			<div class="report-heading">
				<ul class="xs_fundrising_filter xs_text_center">
					<li><a class="<?php echo ( $tab == 'all' ) ? 'active' : ''; ?>"
						   href="<?php echo esc_url( $link ); ?>&wfp_report_tab=all"> <?php echo esc_html__( strtoupper( 'Success' ), 'wp-fundraising' ); ?> </a>
					</li>
					<li><a class="<?php echo ( $tab == 'review' ) ? 'active' : ''; ?>"
						   href="<?php echo esc_url( $link ); ?>&wfp_report_tab=review"> <?php echo esc_html__( strtoupper( 'In Review ' ), 'wp-fundraising' ); ?></a>
					</li>
					<li><a class="<?php echo ( $tab == 'pending' ) ? 'active' : ''; ?>"
						   href="<?php echo esc_url( $link ); ?>&wfp_report_tab=pending"><?php echo esc_html__( strtoupper( ' In Process ' ), 'wp-fundraising' ); ?></a>
					</li>
				</ul>
			</div>

			<div>
				<span><?php echo sprintf( esc_html__( 'Show %s (per page) in total ', 'wp-fundraising' ), esc_html( $limit ) ); ?><?php echo esc_html( $total_donation_count ); ?></span>
			</div>
		</div>

		<div class="xs_payment_review_table_wraper">
			<table class="form-table xs_payment_review_table">
				<thead>
				<tr>
					<th class="sort"><?php echo esc_html__( 'S.L.', 'wp-fundraising' ); ?></th>
					<th class="name"> <?php echo esc_html__( 'Email', 'wp-fundraising' ); ?></th>
					<th class="enable"> <?php echo esc_html( 'Amount [' . $symbols . ']' ); ?></th>
					<th class=""> <?php echo esc_html__( 'Payment Method', 'wp-fundraising' ); ?> </th>
					<th class=""> <?php echo esc_html__( 'Date', 'wp-fundraising' ); ?> </th>
					<th class="info"> <?php echo esc_html__( 'Action', 'wp-fundraising' ); ?></th>
				</tr>
				</thead>

				<tbody>

				<?php

				foreach ( $all_donations as $sl => $donation ) {

					$total_amount += $donation->donate_amount;

					?>

					<tr id="donate_tr__<?php echo esc_attr( $donation->donate_id ); ?>">
						<td class="icon"><strong><?php echo esc_html( $sl + 1 ); ?> </strong></td>
						<td class="name"><?php echo esc_html( $donation->email ); ?></td>
						<td class="enable"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $donation->donate_amount ) ); ?> </td>
						<td><?php echo esc_html( \WfpFundraising\Apps\Global_Settings::$allowed_gateway[ $donation->payment_gateway ] ); ?> </td>
						<td><?php echo esc_html( gmdate( 'F d, Y', strtotime( $donation->date_time ) ) ); ?> </td>


						<td>
							<?php

							$cls = strtolower( $donation->status );

							if ( $tab == 'all' ) {

								$d_stat = array(
									'Active'   => esc_html__( 'Success', 'wp-fundraising' ),
									'Refunded' => esc_html__( 'Refund', 'wp-fundraising' ),
								);

							} else {

								$d_stat = array(
									'Pending'  => esc_html__( 'In Process', 'wp-fundraising' ),
									'Review'   => esc_html__( 'In Review', 'wp-fundraising' ),
									'Active'   => esc_html__( 'Success', 'wp-fundraising' ),
									'DeActive' => esc_html__( 'Cancel', 'wp-fundraising' ),
								);
							}

							?>
							<select class="<?php echo esc_attr( $cls ); ?>" name="status_modify"
									onchange="update_donation_status(this, '<?php echo esc_attr( $donation->donate_id ); ?>')">

								<?php

								foreach ( $d_stat as $key => $val ) {
									?>

									<option value="<?php echo esc_attr( $key ); ?>" <?php echo $donation->status == $key ? 'selected' : ''; ?>> <?php echo esc_html( $val ); ?> </option> 
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
						<?php echo esc_html__( 'Total Amount : ', 'wp-fundraising' ); ?> [<?php echo esc_html( $symbols ); ?>]
					</th>
					<th>
						<?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $total_amount ) ); ?>
					</th>
					<th colspan="3">&nbsp;</th>
				</tr>
				</tfoot>

			</table>
		</div>
	</div>
</div>
