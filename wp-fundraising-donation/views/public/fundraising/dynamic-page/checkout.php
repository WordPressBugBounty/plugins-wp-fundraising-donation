<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

if ( ! isset( $_REQUEST['wpf_checkout_nonce_field'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['wpf_checkout_nonce_field'] ) ), 'wpf_checkout' ) ) {
	die( esc_html__( 'Security check failed', 'wp-fundraising' ) );
}

$wfp_author_id   = get_current_user_id();
$wfpUserCountry = get_user_meta( $wfp_author_id, '_wfp_country', true );

$wfpGetAmount  = isset( $_GET['amount'] ) ? floatval( $_GET['amount'] ) : 0.0;
$wfpGetPledge  = isset( $_GET['pledge'] ) ? intval( $_GET['pledge'] ) : 0;
$wfpGetIndex   = isset( $_GET['index'] ) ? intval( $_GET['index'] ) : -10;
$wfpPledgeUuid = empty( $_GET['pledge_uid'] ) ? '0' : sanitize_text_field( wp_unslash( $_GET['pledge_uid'] ) );

$wfpDefaultCountry = isset( $_GET['country'] ) && strlen( sanitize_text_field( wp_unslash( $_GET['country'] ) ) ) > 1 ? sanitize_text_field( wp_unslash( $_GET['country'] ) ) : $wfpUserCountry;

// form content data
$wfpFormContentData    = isset( $wfpGetMetaData->form_content ) ? $wfpGetMetaData->form_content : (object) array(
	'enable'           => 'No',
	'content_position' => 'after-form',
);
$wfpGateCampaignData   = 'woocommerce';
$wfpEnableDisplayField = '';

// general option data
$wfpMetaGeneralKey     = 'wfp_general_options_data';
$wfpGetMetaGeneralOp   = get_option( \WfpFundraising\Apps\Settings::OK_GENERAL_DATA );
$wfpGetMetaGeneral     = isset( $wfpGetMetaGeneralOp['options'] ) ? $wfpGetMetaGeneralOp['options'] : array();
$wfpGetMetaGeneralPage = \WfpFundraising\Apps\Settings::instance()->get_mapped_page_slug( $wfpGetMetaGeneralOp );
$wfpCheckoutPage       = \WfpFundraising\Apps\Settings::instance()->get_mapped_checkout_page_slug( $wfpGetMetaGeneralOp );

$wfpUrlCheckout = get_site_url() . '/' . $wfpCheckoutPage . '?wfpout=true';

$wfpAdd_fees = isset( $wfpGetMetaData->donation->set_add_fees ) ? $wfpGetMetaData->donation->set_add_fees : (object) array(
	'enable'      => 'No',
	'fees_amount' => 0,
);

require \WFP_Fundraising::plugin_dir() . 'country-module/country-info.php';

$wfpOptionsData = isset( $wfpGateWaysData['services'] ) ? $wfpGateWaysData['services'] : array();

$wfp_payment_settings = \WfpFundraising\Apps\Settings::instance()->get_active_payment_settings( $getPaymentId, $wfpOptionsData );


?>
<div class="wfp-view wfp-view-public">
	<section class="wfp-checkout <?php echo esc_attr( $className ); ?>" id="<?php echo esc_attr( $idName ); ?>">

		<div class="checkout-content">
			<?php if ( $getPaymentId > 0 && is_object( $post ) && sizeof( (array) $post ) > 0 ) { ?>
				<form method="post"
					  class="wfdp-donationForm ft1"
					  id="wfdp-donationForm-<?php echo esc_attr( $post->ID ); ?>"
					  data-wfp-id="<?php echo esc_attr( $post->ID ); ?>"
					  wfp-data-url="<?php echo esc_url( $wfpUrlCheckout ); ?>"
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
							<div class="wfdp-donation-input-form wfp-input-field <?php echo esc_attr( $wfpEnableDisplayField ); ?>">
								<label for="xs_donate_country_pledge"></label>
								<?php

								echo esc_html( apply_filters( 'wfp_checkout_country_name', esc_html__( 'Country Destination:', 'wp-fundraising' ) ) );

								$wfpDefultStreet   = get_user_meta( $wfp_author_id, '_wfp_street_address', true );
								$wfpDefultCity     = get_user_meta( $wfp_author_id, '_wfp_city', true );
								$wfpDefultPostcode = get_user_meta( $wfp_author_id, '_wfp_postcode', true );
								?>
								<select class="regular-text wfp-select2-country"
										name="xs_donate_data_submit[additonal][country_destination]"
										id="xs_donate_country_pledge">
									<?php
									if ( is_array( $wfpCountryList ) && sizeof( $wfpCountryList ) > 0 ) {

										foreach ( $wfpCountryList as $wfp_key => $wfp_value ) :
											$wfp_name             = isset( $wfp_value['info']['name'] ) ? $wfp_value['info']['name'] : '';
											$wfpCountryStateList = isset( $wfp_value['states'] ) ? $wfp_value['states'] : array();
											if ( is_array( $wfpCountryStateList ) && sizeof( $wfpCountryStateList ) > 0 ) {
												?>
												<optgroup label="<?php echo esc_html( $wfp_name ); ?>">
													<?php
													foreach ( $wfpCountryStateList as $wfpKeyState => $valueState ) :
														?>
														<option value="<?php echo esc_attr( $valueState . ', ' . $wfp_name ); ?>"
															<?php echo esc_attr( ( $wfpDefaultCountry == $wfp_key . '-' . $wfpKeyState ) ? 'selected' : '' ); ?>>
															<?php echo esc_html( $wfp_name . ' -- ' . $valueState ); ?> </option>

														<?php
													endforeach;
													?>
												</optgroup>
												<?php
											} else {
												?>
												<option value="<?php echo esc_attr( $wfp_name ); ?>"
													<?php echo esc_attr( ( $wfpDefaultCountry == $wfp_key ) ? 'selected' : '' ); ?>>
													<?php echo esc_html( $wfp_name ); ?> </option>
												<?php
											}
										endforeach;
									}
									?>
								</select>
							</div>

							<?php

							$wfp_fields = \WfpFundraising\Apps\Settings::get_mandatory_form_fields();


							foreach ( $wfp_fields as $wfp_group => $field ) {

								foreach ( $field as $wfp_fld_name => $fld_info ) {

									if ( $wfp_fld_name == 'country_destination' ) {
										continue;
									}

									$wfp_required = $fld_info['required'] ? 'required' : '';

									if ( $fld_info['type'] == 'text' ) :
										?>

										<div class="wfdp-donation-input-form wfp-input-field <?php echo esc_attr( $wfpEnableDisplayField ); ?>">
											<label for="<?php echo esc_attr( $fld_info['id'] ); ?>">
												<?php echo esc_html( $fld_info['label'] ); ?>
											</label>
											<input type="text"
												   class="regular-text"
												   name="xs_donate_data_submit[<?php echo esc_attr( $wfp_group ); ?>][<?php echo esc_attr( $wfp_fld_name ); ?>]"
												   id="<?php echo esc_attr( $fld_info['id'] ); ?>"
												   value=""
												<?php echo esc_attr( $wfp_required ); ?>>
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
									$wfp_total_amount     = $wfpGetAmount;
									$wfp_total_tax        = 0;
									$wfp_total_amount_sub = $wfpGetAmount + $wfp_total_tax;

									$wfp_total_shiping     = 0;
									$wfp_total_shiping_tax = 0;
									$wfp_total_shiping_sub = $wfp_total_shiping + $wfp_total_shiping_tax;

									$wfp_total_amount_sub_all = $wfp_total_amount_sub - $wfp_total_shiping_sub;

									$wfp_fees_data = 0;
									$wfp_fees      = 0;
										$wfp_fees_type = '';
										if ( isset( $wfpAdd_fees->enable ) && $wfpAdd_fees->enable == 'Yes' ) {
											$wfp_fees_type = isset( $wfpAdd_fees->fees_type ) ? $wfpAdd_fees->fees_type : 'percentage';

											$wfp_fees_data = isset( $wfpAdd_fees->fees_amount ) ? $wfpAdd_fees->fees_amount : '0';
											if ( $wfp_fees_type == 'percentage' ) {
											$wfp_fees = ( $wfp_total_amount * $wfp_fees_data ) / 100;
										} else {
											$wfp_fees = $wfp_fees_data;
										}

										$wfp_total_amount = $wfp_total_amount + $wfp_fees;
									}
									?>
									<tbody>
									<tr>
										<td><strong><?php echo esc_html( $post->post_title ); ?> × 1</strong></td>
										<td>
											<em><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $wfp_defaultUse_space ) ); ?></em><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfp_total_amount_sub_all ) ); ?>
											<em><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $wfp_defaultUse_space ) ); ?></em>
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
												<?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $wfp_defaultUse_space ) ); ?>
												<?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfp_total_amount_sub ) ); ?>
												<?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $wfp_defaultUse_space ) ); ?>
											</em>
										</td>
									</tr>
									<tr>
										<td>
											<strong><?php echo esc_html( apply_filters( 'wfp_checkout_details_shipping', __( 'Shipping:', 'wp-fundraising' ) ) ); ?></strong>
										</td>
										<td>
											<em>
												<?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $wfp_defaultUse_space ) ); ?>
												<?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfp_total_shiping_sub ) ); ?>
												<?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $wfp_defaultUse_space ) ); ?>
											</em>
										</td>
									</tr>
									<?php
									if ( isset( $wfpAdd_fees->enable ) && $wfpAdd_fees->enable == 'Yes' ) {
										?>
										<tr>
											<td>
												<strong>
												<?php
											$wfp_additext = isset( $wfpAdd_fees->fees_label ) ? $wfpAdd_fees->fees_label : __( 'Fees', 'wp-fundraising' );
												echo esc_html( apply_filters( 'wfp_donate_forms_additional_fees', $wfp_additext ) );
												if ( $wfp_fees_type == 'percentage' ) {
													?>
														 (<strong><?php echo esc_html( $wfp_fees_data ); ?></strong>%) <?php } ?>
												</strong></td>
											<td>
												<em>
													<?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $wfp_defaultUse_space ) ); ?>
													<?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfp_fees ) ); ?>
													<?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $wfp_defaultUse_space ) ); ?>
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
												<?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $wfp_defaultUse_space ) ); ?>
												<?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfp_total_amount ) ); ?>
												<?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $wfp_defaultUse_space ) ); ?>
											</em>
										</td>
									</tr>
									</tfoot>
								</table>
							</div>
							<div class="wfp-order-payment-section">
								<input type="hidden" value="crowdfunding" name="xs_donate_data_submit[payment_type]">
								<input type="hidden" value="<?php echo esc_attr( $wfpGetPledge ); ?>"
									   name="xs_donate_data_submit[pledge_id]">
								<input type="hidden" value="<?php echo esc_attr( $wfpPledgeUuid ); ?>"
									   name="xs_donate_data_submit[pledge_uid]">
								<input type="hidden" value="<?php echo esc_attr( $wfpGetIndex ); ?>"
									   name="xs_donate_data_submit[index]">
								<input type="hidden" value="<?php echo esc_attr( $wfp_fees_data ); ?>"
									   name="xs_donate_data_submit[addition_fees]">
								<input type="hidden" id="xs_donate_amount_total_hidden"
									   name="xs_donate_data_submit[donate_amount]"
									   value="<?php echo esc_attr( $wfp_total_amount ); ?>">
								<input type="hidden" value="<?php echo esc_attr( $wfp_fees ); ?>"
									   name="xs_donate_data_submit[addition_fees_amount]">
										<input type="hidden" value="<?php echo esc_attr( $wfp_fees_type ); ?>"
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
