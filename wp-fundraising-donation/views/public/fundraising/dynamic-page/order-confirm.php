<div class="wfp-view wfp-view-public">
	<section class="wfp-order <?php echo esc_attr( $className ); ?>" id="<?php echo esc_attr( $idName ); ?>">
		<div class="checkout-content">
			<?php if ( $getPaymentId > 0 && is_object( $post ) && $orderId > 0 ) { ?>
				<div class="title-section">
					<?php do_action( 'wfp_order_heading_before' ); ?>
					<h1 class="order-heading"> <?php echo esc_html( apply_filters( 'wfp_order_heading', __( 'Order received', 'wp-fundraising' ) ) ); ?></h1>
					<?php do_action( 'wfp_order_heading_after' ); ?>
				</div>
				<div class="wfp-order-summery-section">
					<p class="order-success"><?php echo esc_html( apply_filters( 'wfp_order_success_message', __( 'Thank you. Your order has been received.', 'wp-fundraising' ) ) ); ?></p>
					<ul class="order_details">
						<li class="order-number"><?php echo esc_html( apply_filters( 'wfp_summery_order_number', __( 'Order number:', 'wp-fundraising' ) ) ); ?>
							<strong><?php echo esc_html( self::wfp_get_meta( $orderId, '_wfp_donate_id' ) ); ?></strong>
						</li>
						<li class="order-number"><?php echo esc_html( apply_filters( 'wfp_summery_order_invoice', __( 'Invoice:', 'wp-fundraising' ) ) ); ?>
							<strong><?php echo esc_html( self::wfp_get_meta( $orderId, '_wfp_invoice' ) ); ?></strong>
						</li>
						<li class="order-date"><?php echo esc_html( apply_filters( 'wfp_summery_order_date', __( 'Date:', 'wp-fundraising' ) ) ); ?>
							<strong><?php echo esc_html( gmdate( 'F d, Y', strtotime( self::wfp_get_meta( $orderId, '_wfp_date_time' ) ) ) ); ?></strong>
						</li>
						<li class="order-email"><?php echo esc_html( apply_filters( 'wfp_summery_order_email', __( 'Email:', 'wp-fundraising' ) ) ); ?>
							<strong><?php echo esc_html( self::wfp_get_meta( $orderId, '_wfp_email_address' ) ); ?></strong>
						</li>
						<li class="order-total"><?php echo esc_html( apply_filters( 'wfp_summery_order_total', __( 'Total:', 'wp-fundraising' ) ) ); ?>
							<strong><em><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $defaultUse_space ) ); ?></em><span
										class="order-amount"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( self::wfp_get_meta( $orderId, '_wfp_order_total' ) ) ); ?></span><em><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $defaultUse_space ) ); ?></em></strong>
						</li>
						<li class="order-method"> <?php echo esc_html( apply_filters( 'wfp_summery_order_method', __( 'Payment method:', 'wp-fundraising' ) ) ); ?>
							<?php
							$paymentType = self::wfp_get_meta( $orderId, '_wfp_payment_gateway' );
							if ( $paymentType == 'online_payment' ) {
								$payment_gateway = 'Paypal';
							} elseif ( $paymentType == 'stripe_payment' ) {
								$payment_gateway = 'Stripe';
							} elseif ( $paymentType == 'bank_payment' ) {
								$payment_gateway = 'Bank';
							} elseif ( $paymentType == 'check_payment' ) {
								$payment_gateway = 'Check';
							} elseif ( $paymentType == 'offline_payment' ) {
								$payment_gateway = 'Cash';
							}
							?>
							<strong><?php echo esc_html( $payment_gateway . ' payments' ); ?></strong>
						</li>
					</ul>
				</div>
				<div class="wfp-order-details-section">
					<?php do_action( 'wfp_order_details_before' ); ?>
					<h2 class="order-heading"> <?php echo esc_html( apply_filters( 'wfp_order_details', __( 'Order details', 'wp-fundraising' ) ) ); ?></h2>
					<?php do_action( 'wfp_order_details_after' ); ?>

					<div class="wfdp-table-container">
						<table class="form-table wfdp-table-design wfp-order-details">
							<thead>
							<tr>
								<th class="product-name">  <?php echo esc_html( apply_filters( 'wfp_order_details_product', __( 'Product', 'wp-fundraising' ) ) ); ?></th>
								<th class="payment-total"> <?php echo esc_html( apply_filters( 'wfp_order_details_total', __( 'Total', 'wp-fundraising' ) ) ); ?></th>
							</tr>
							</thead>
							<tbody>
							<?php
							$total_amount     = self::wfp_get_meta( $orderId, '_wfp_order_total' );
							$total_tax        = self::wfp_get_meta( $orderId, '_wfp_order_tax' );
							$total_amount_sub = (float) $total_amount + (float) $total_tax;

							$total_shiping     = self::wfp_get_meta( $orderId, '_wfp_order_shipping' );
							$total_shiping_tax = self::wfp_get_meta( $orderId, '_wfp_order_shipping_tax' );
							$total_shiping_sub = (float) $total_shiping + (float) $total_shiping_tax;

							$total_amount_sub_all = $total_amount_sub - $total_shiping_sub;

							?>
							<tr>
								<td><?php echo esc_html( $post->post_title ); ?><strong> × 1</strong></td>
								<td>
									<em><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $defaultUse_space ) ); ?></em><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $total_amount_sub_all ) ); ?>
									<em><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $defaultUse_space ) ); ?></em>
								</td>
							</tr>
							<tr>
								<td>
									<strong><?php echo esc_html( apply_filters( 'wfp_order_details_subtotal', __( 'Subtotal:', 'wp-fundraising' ) ) ); ?></strong>
								</td>
								<td>
									<strong><em><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $defaultUse_space ) ); ?></em><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $total_amount_sub ) ); ?>
										<em><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $defaultUse_space ) ); ?></em></strong>
								</td>
							</tr>
							<tr>
								<td>
									<strong><?php echo esc_html( apply_filters( 'wfp_order_details_shipping', __( 'Shipping:', 'wp-fundraising' ) ) ); ?></strong>
								</td>
								<td>
									<strong><em><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $defaultUse_space ) ); ?></em><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $total_shiping_sub ) ); ?>
										<em><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $defaultUse_space ) ); ?></em></strong>
								</td>
							</tr>
							<tr>
								<td>
									<strong><?php echo esc_html( apply_filters( 'wfp_summery_order_method', __( 'Payment method:', 'wp-fundraising' ) ) ); ?></strong>
								</td>
								<td>
									<strong><?php echo esc_html( $payment_gateway . ' payments' ); ?></strong>
								</td>
							</tr>
							<tr>
								<td>
									<strong><?php echo esc_html( apply_filters( 'wfp_order_details_total_amount', __( 'Total:', 'wp-fundraising' ) ) ); ?></strong>
								</td>
								<td>
									<strong><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $defaultUse_space ) ); ?></em><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $total_amount ) ); ?><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $defaultUse_space ) ); ?></em></strong>
								</td>
							</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="wfp-billing-section">
					<?php do_action( 'wfp_order_billing_before' ); ?>
					<h2 class="order-heading"> <?php echo esc_html( apply_filters( 'wfp_order_billing', __( 'Billing address', 'wp-fundraising' ) ) ); ?></h2>
					<?php do_action( 'wfp_order_billing_after' ); ?>
					<?php
					$addTioalData = self::wfp_get_meta( $orderId, '_wfp_additional_data' );
					if ( is_array( $addTioalData ) ) {
						$dataAttributes = array_map(
							function( $value, $key ) {
								$key = ucwords( str_replace( array( '_' ), ' ', $key ) );
								if ( strlen( trim( $value ) ) > 0 ) :
									return '<li><strong>' . $key . ':</strong> ' . $value . ' </li>';
							endif;
							},
							array_values( $addTioalData ),
							array_keys( $addTioalData )
						);

						$dataAttributes = implode( ' ', $dataAttributes );
					} else {
						$dataAttributes = $addTioalData;
					}
					?>
					<ul class="shipping_details">
						<?php echo wp_kses( $dataAttributes, \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
					</ul>
				</div>
				<?php
			} else {
				echo wp_kses( '<p class="wfp-error-message">' . apply_filters( 'wfp_order_invalid_message', __( 'Invalid Order', 'wp-fundraising' ) ) . '</p>', \WfpFundraising\Utilities\Utils::get_kses_array() );
			}
			?>
		</div>
	</section>
</div>
