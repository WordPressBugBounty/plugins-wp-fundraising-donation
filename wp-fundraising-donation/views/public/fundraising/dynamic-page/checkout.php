<?php

if ( ! isset( $_REQUEST['wpf_checkout_nonce_field'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['wpf_checkout_nonce_field'] ) ), 'wpf_checkout' ) ) {
	die( esc_html__( 'Security check failed', 'wp-fundraising' ) );
}

$author_id   = get_current_user_id();
$userCountry = get_user_meta( $author_id, '_wfp_country', true );

$getAmount  = isset( $_GET['amount'] ) ? floatval( $_GET['amount'] ) : 0.0;
$getPledge  = isset( $_GET['pledge'] ) ? intval( $_GET['pledge'] ) : 0;
$getIndex   = isset( $_GET['index'] ) ? intval( $_GET['index'] ) : -10;
$pledgeUuid = empty( $_GET['pledge_uid'] ) ? '0' : sanitize_text_field( wp_unslash( $_GET['pledge_uid'] ) );

$defaultCountry = isset( $_GET['country'] ) && strlen( sanitize_text_field( wp_unslash( $_GET['country'] ) ) ) > 1 ? sanitize_text_field( wp_unslash( $_GET['country'] ) ) : $userCountry;

// form content data
$formContentData    = isset( $getMetaData->form_content ) ? $getMetaData->form_content : (object) array(
	'enable'           => 'No',
	'content_position' => 'after-form',
);
$gateCampaignData   = 'woocommerce';
$enableDisplayField = '';

// general option data
$metaGeneralKey     = 'wfp_general_options_data';
$getMetaGeneralOp   = get_option( \WfpFundraising\Apps\Settings::OK_GENERAL_DATA );
$getMetaGeneral     = isset( $getMetaGeneralOp['options'] ) ? $getMetaGeneralOp['options'] : array();
$getMetaGeneralPage = \WfpFundraising\Apps\Settings::instance()->get_mapped_page_slug( $getMetaGeneralOp );
$checkoutPage       = \WfpFundraising\Apps\Settings::instance()->get_mapped_checkout_page_slug( $getMetaGeneralOp );

$urlCheckout = get_site_url() . '/' . $checkoutPage . '?wfpout=true';

$add_fees = isset( $getMetaData->donation->set_add_fees ) ? $getMetaData->donation->set_add_fees : (object) array(
	'enable'      => 'No',
	'fees_amount' => 0,
);

require \WFP_Fundraising::plugin_dir() . 'country-module/country-info.php';

$optionsData = isset( $gateWaysData['services'] ) ? $gateWaysData['services'] : array();

$payment_settings = \WfpFundraising\Apps\Settings::instance()->get_active_payment_settings( $getPaymentId, $optionsData );


?>
<div class="wfp-view wfp-view-public">
	<section class="wfp-checkout <?php echo esc_attr( $className ); ?>" id="<?php echo esc_attr( $idName ); ?>">

		<div class="checkout-content">
			<?php if ( $getPaymentId > 0 && is_object( $post ) && sizeof( (array) $post ) > 0 ) { ?>
				<form method="post"
					  class="wfdp-donationForm ft1"
					  id="wfdp-donationForm-<?php echo esc_attr( $post->ID ); ?>"
					  data-wfp-id="<?php echo esc_attr( $post->ID ); ?>"
					  wfp-data-url="<?php echo esc_url( $urlCheckout ); ?>"
					  data-wfp-payment_type="default" >

					<div class="wfdp-donation-message"></div>

					<div class="xs-row">
						<div class="xs-col-lg-6">
							<div class="wfp-title-section">
								<?php do_action( 'wfp_checkout_title_before' ); ?>
								<h2 class="checkout-heading">
									<?php echo esc_html( apply_filters( 'wfp_checkout_billing_details', esc_html__( 'Billing details:', 'wp-fundraising' ) ) ); ?>
								</h2>
								<?php do_action( 'wfp_checkout_title_after' ); ?>
							</div>
							<?php
							include \WFP_Fundraising::plugin_dir() . 'views/public/donation/include/content/filed-content.php';
							?>
							<div class="wfdp-donation-input-form wfp-input-field <?php echo esc_attr( $enableDisplayField ); ?>">
								<label for="xs_donate_country_pledge"></label>
								<?php

								echo esc_html( apply_filters( 'wfp_checkout_country_name', esc_html__( 'Country Destination:', 'wp-fundraising' ) ) );

								$defultStreet   = get_user_meta( $author_id, '_wfp_street_address', true );
								$defultCity     = get_user_meta( $author_id, '_wfp_city', true );
								$defultPostcode = get_user_meta( $author_id, '_wfp_postcode', true );
								?>
								<select class="regular-text wfp-select2-country"
										name="xs_donate_data_submit[additonal][country_destination]"
										id="xs_donate_country_pledge">
									<?php
									if ( is_array( $countryList ) && sizeof( $countryList ) > 0 ) {

										foreach ( $countryList as $key => $value ) :
											$name             = isset( $value['info']['name'] ) ? $value['info']['name'] : '';
											$countryStateList = isset( $value['states'] ) ? $value['states'] : array();
											if ( is_array( $countryStateList ) && sizeof( $countryStateList ) > 0 ) {
												?>
												<optgroup label="<?php echo esc_html( $name ); ?>">
													<?php
													foreach ( $countryStateList as $keyState => $valueState ) :
														?>
														<option value="<?php echo esc_attr( $valueState . ', ' . $name ); ?>"
															<?php echo esc_attr( ( $defaultCountry == $key . '-' . $keyState ) ? 'selected' : '' ); ?>>
															<?php echo esc_html( $name . ' -- ' . $valueState ); ?> </option>

														<?php
													endforeach;
													?>
												</optgroup>
												<?php
											} else {
												?>
												<option value="<?php echo esc_attr( $name ); ?>"
													<?php echo esc_attr( ( $defaultCountry == $key ) ? 'selected' : '' ); ?>>
													<?php echo esc_html( $name ); ?> </option>
												<?php
											}
										endforeach;
									}
									?>
								</select>
							</div>

							<?php

							$fields = \WfpFundraising\Apps\Settings::get_mandatory_form_fields();


							foreach ( $fields as $group => $field ) {

								foreach ( $field as $fld_name => $fld_info ) {

									if ( $fld_name == 'country_destination' ) {
										continue;
									}

									$required = $fld_info['required'] ? 'required' : '';

									if ( $fld_info['type'] == 'text' ) :
										?>

										<div class="wfdp-donation-input-form wfp-input-field <?php echo esc_attr( $enableDisplayField ); ?>">
											<label for="<?php echo esc_attr( $fld_info['id'] ); ?>">
												<?php echo esc_html( $fld_info['label'] ); ?>
											</label>
											<input type="text"
												   class="regular-text"
												   name="xs_donate_data_submit[<?php echo esc_attr( $group ); ?>][<?php echo esc_attr( $fld_name ); ?>]"
												   id="<?php echo esc_attr( $fld_info['id'] ); ?>"
												   value=""
												<?php echo esc_attr( $required ); ?>>
										</div>

										<?php
									endif;
								}
							}


							?>

						</div>
						<div class="xs-col-lg-6">
							<div class="wfp-title-section">
								<?php do_action( 'wfp_checkout_details_before' ); ?>
								<h2 class="order-heading">
									<?php echo esc_html( apply_filters( 'wfp_checkout_details', esc_html__( 'Checkout Details', 'wp-fundraising' ) ) ); ?>
								</h2>
								<?php do_action( 'wfp_checkout_details_after' ); ?>
							</div>
							<div class="wfp-order-details-section">
								<table class="form-table wfdp-table-design wfp-order-details">
									<thead>
									<tr>
										<th class="product-name">
											<?php echo esc_html( apply_filters( 'wfp_order_checkout_product', __( 'Product', 'wp-fundraising' ) ) ); ?>
										</th>
										<th class="payment-total">
											<?php echo esc_html( apply_filters( 'wfp_order_checkout_total', __( 'Total', 'wp-fundraising' ) ) ); ?>
										</th>
									</tr>
									</thead>
									<?php
									$total_amount     = $getAmount;
									$total_tax        = 0;
									$total_amount_sub = $getAmount + $total_tax;

									$total_shiping     = 0;
									$total_shiping_tax = 0;
									$total_shiping_sub = $total_shiping + $total_shiping_tax;

									$total_amount_sub_all = $total_amount_sub - $total_shiping_sub;

									$fees_data = 0;
									$fees      = 0;
									$fees_type = '';
									if ( isset( $add_fees->enable ) && $add_fees->enable == 'Yes' ) {
										$fees_type = isset( $add_fees->fees_type ) ? $add_fees->fees_type : 'percentage';

										$fees_data = isset( $add_fees->fees_amount ) ? $add_fees->fees_amount : '0';
										if ( $fees_type == 'percentage' ) {
											$fees = ( $total_amount * $fees_data ) / 100;
										} else {
											$fees = $fees_data;
										}

										$total_amount = $total_amount + $fees;
									}
									?>
									<tbody>
									<tr>
										<td><strong><?php echo esc_html( $post->post_title ); ?> × 1</strong></td>
										<td>
											<em><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $defaultUse_space ) ); ?></em><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $total_amount_sub_all ) ); ?>
											<em><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $defaultUse_space ) ); ?></em>
										</td>
									</tr>
									</tbody>
									<tfoot>
									<tr>
										<td>
											<strong><?php echo esc_html( apply_filters( 'wfp_checkout_details_subtotal', __( 'Subtotal:', 'wp-fundraising' ) ) ); ?></strong>
										</td>
										<td>
											<em>
												<?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $defaultUse_space ) ); ?>
												<?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $total_amount_sub ) ); ?>
												<?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $defaultUse_space ) ); ?>
											</em>
										</td>
									</tr>
									<tr>
										<td>
											<strong><?php echo esc_html( apply_filters( 'wfp_checkout_details_shipping', __( 'Shipping:', 'wp-fundraising' ) ) ); ?></strong>
										</td>
										<td>
											<em>
												<?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $defaultUse_space ) ); ?>
												<?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $total_shiping_sub ) ); ?>
												<?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $defaultUse_space ) ); ?>
											</em>
										</td>
									</tr>
									<?php
									if ( isset( $add_fees->enable ) && $add_fees->enable == 'Yes' ) {
										?>
										<tr>
											<td>
												<strong>
												<?php
												$additext = isset( $add_fees->fees_label ) ? $add_fees->fees_label : 'Fees';
													echo esc_html( apply_filters( 'wfp_donate_forms_additional_fees', __( $additext, 'wp-fundraising' ) ) );
												if ( $fees_type == 'percentage' ) {
													?>
														 (<strong><?php echo esc_html( $fees_data ); ?></strong>%) <?php } ?>
												</strong></td>
											<td>
												<em>
													<?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $defaultUse_space ) ); ?>
													<?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $fees ) ); ?>
													<?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $defaultUse_space ) ); ?>
												</em>
											</td>
										</tr>
									<?php } ?>
									<tr>
										<td>
											<strong><?php echo esc_html( apply_filters( 'wfp_checkout_details_total_amount', __( 'Total:', 'wp-fundraising' ) ) ); ?></strong>
										</td>
										<td>
											<em>
												<?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $defaultUse_space ) ); ?>
												<?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $total_amount ) ); ?>
												<?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $defaultUse_space ) ); ?>
											</em>
										</td>
									</tr>
									</tfoot>
								</table>
							</div>
							<div class="wfp-order-payment-section">
								<input type="hidden" value="crowdfunding" name="xs_donate_data_submit[payment_type]">
								<input type="hidden" value="<?php echo esc_attr( $getPledge ); ?>"
									   name="xs_donate_data_submit[pledge_id]">
								<input type="hidden" value="<?php echo esc_attr( $pledgeUuid ); ?>"
									   name="xs_donate_data_submit[pledge_uid]">
								<input type="hidden" value="<?php echo esc_attr( $getIndex ); ?>"
									   name="xs_donate_data_submit[index]">
								<input type="hidden" value="<?php echo esc_attr( $fees_data ); ?>"
									   name="xs_donate_data_submit[addition_fees]">
								<input type="hidden" id="xs_donate_amount_total_hidden"
									   name="xs_donate_data_submit[donate_amount]"
									   value="<?php echo esc_attr( $total_amount ); ?>">
								<input type="hidden" value="<?php echo esc_attr( $fees ); ?>"
									   name="xs_donate_data_submit[addition_fees_amount]">
								<input type="hidden" value="<?php echo esc_attr( $fees_type ); ?>"
									   name="xs_donate_data_submit[addition_fees_type]">
								<?php
								include \WFP_Fundraising::plugin_dir() . 'views/public/donation/include/content/payment-content.php';
								?>
							</div>
							<div class="submit-form-checkout">
								<button type="submit" name="submit-form-donation" class="xs-btn xs-btn-primary xs-btn-block xs-btn-lg"><?php echo esc_html( apply_filters( 'wfp_checkout_button', __( 'Checkout', 'wp-fundraising' ) ) ); ?></button>
							</div>
						</div>
					</div>
				</form>
				<?php
			} else {
				echo wp_kses( '<p class="wfp-error-message">' . apply_filters( 'wfp_checkout_invalid_message', __( 'Invalid Payment', 'wp-fundraising' ) ) . '</p>', \WfpFundraising\Utilities\Utils::get_kses_array() );
			}
			?>
		</div>

	</section>
</div>

<script type="text/javascript">
	jQuery(document).ready(function () {
		jQuery('.wfp-select2-country').select2();
	});
</script>
