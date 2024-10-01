<?php

/**
 * Hook for putting anything before donation content
 */
do_action( 'wfp_campaign_content_before' );

?>

	<div class="wfp-container xs-wfp-donation"
		 style="<?php echo esc_attr( $page_width <= 0 ? '' : 'max-width:' . $page_width . 'px;' ); ?>">
		<div class="wfp-view wfp-view-public">
			<div class="wfdp-donation-form <?php echo esc_html( $customClass ); ?>" <?php echo esc_attr( empty( $customIdData ) ? '' : 'id=".' . $customIdData . '."' ); ?>>
				<form method="post"
					  class="wfdp-donationForm ft8"
					  id="wfdp-donationForm-<?php echo esc_attr( $postId ); ?>"
					  data-wfp-id="<?php echo esc_attr( $postId ); ?>"
					  data-wfp-payment_type="<?php echo esc_attr( $paymentType ); ?>"
					  wfp-data-url="<?php echo esc_url( $urlCheckout ); ?>">
					  <?php wp_nonce_field( 'wpf_checkout_nonce_field', 'wpf_checkout' ); ?>
					<div class="wfdp-donation-input-form">
						<button type="button"
								class="xs-btn btn-special submit-btn"
								name="submit-form-donation"
								data-type="modal-trigger"
								data-target="xs-donate-modal-popup"> <?php echo esc_html__( 'Donate', 'wp-fundraising' ); ?>
						</button>
					</div>

					<?php

					/**
					 * As this template is for modal so we are prinitng the modal with all fields
					 */
					?>

					<div class="xs-modal-dialog wfp-donate-modal-popup" id="xs-donate-modal-popup">
						<div class="wfp-donate-modal-popup-wraper">
							<div class="wfp-modal-content">
								<div class="xs-modal-header">
									<button type="button"
											class="xs-btn danger xs-modal-header--btn-close"
											data-modal-dismiss="modal">
										<i class="wfpf wfpf-close-outline xs-modal-header--btn-close__icon"></i>
									</button>
								</div>

								<div class="xs-modal-body wfp-donation-form-wraper">
									<?php
									require __DIR__ . '/partials/single_donation_body_modal.php';
									?>
								</div>


								<div class="wfp-donate-form-footer">

									<?php require __DIR__ . '/partials/_donation_button.php'; ?>

								</div>
							</div>
						</div>
					</div>
					<div class="xs-backdrop wfp-modal-backdrop"></div>

				</form>

				<?php

				if ( \WfpFundraising\Utilities\Helper::is_woocom_payment() ) {

					\WfpFundraising\Utilities\Helper::add_2_cart_form( $postId );
				}

				?>

			</div>
		</div>

		<!-- maybe later i will move this part to a js file but for now putting it as it is-->
		<script type='text/javascript'>
			xs_donate_amount_set(<?php echo esc_attr( $defaultData ); ?>,<?php echo esc_attr( $postId ); ?>);
		</script>

	</div>

<?php

/**
 * We are giving another hook for after the content of fund raising
 */
do_action( 'wfp_campaign_content_after' );

?>
