<?php

// require __DIR__ . '/single_donation_form_common.php';

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
				  class="wfdp-donationForm ft6"
				  id="wfdp-donationForm-<?php echo esc_attr( $postId ); ?>"
				  data-wfp-id="<?php echo esc_attr( $postId ); ?>"
				  data-wfp-payment_type="<?php echo esc_attr( $paymentType ); ?>"
				  wfp-data-url="<?php echo esc_url( $urlCheckout ); ?>" >
				  <?php wp_nonce_field( 'wpf_checkout_nonce_field', 'wpf_checkout' ); ?>

				<div class='xs-modal-body wfp-donation-form-wraper'>
					<?php require __DIR__ . '/partials/single_doantion_form_partial_first.php'; ?>
				</div>

				<?php

				/**
				 * We know for sure this template is for modal=yes and only button
				 * so removing all condition checking
				 */
				?>

				<div class="wfdp-donation-input-form">
					<button type="button" class="xs-btn btn-special submit-btn wfdp-donation-continue-btn" name="submit-form-donation"
							data-type="modal-trigger"
							data-target="xs-donate-modal-popup"> <?php echo esc_html( $formDesignData->continue_button ? $formDesignData->continue_button : 'Continue' ); ?>
					</button>
				</div>

				<div class="xs-modal-dialog wfp-donate-modal-popup" id="xs-donate-modal-popup">
					<div class="wfp-donate-modal-popup-wraper">
						<div class="wfp-modal-content">

							<div class="xs-modal-header">
								<h4 class="xs-modal-header--title"><?php echo esc_html( $post->post_title ); ?></h4>
								<button type="button" class="xs-btn danger xs-modal-header--btn-close"
										data-modal-dismiss="modal"><i
											class="wfpf wfpf-close-outline xs-modal-header--btn-close__icon"></i>
								</button>
							</div>

							<div class="xs-modal-body wfp-donation-form-wraper">
								<?php

								require __DIR__ . '/partials/single_doantion_form_partial_second.php';

								?>
							</div>
							<div class="wfp-donate-form-footer">
								<?php
								if ( isset( $formTermsData->enable ) && $formTermsData->content_position == 'before-submit-button' ) {
									?>
									<div class="xs-donate-display-amount xs-radio_style <?php echo esc_attr( $enableDisplayField ); ?> ">
										<?php echo wp_kses( $termsContent, \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
									</div>
									<?php
								}
								if ( $campaign_status == 'Ends' ) {
									echo wp_kses( '<p class="xs-alert xs-alert-success">' . $goalMessage . '</p>', \WfpFundraising\Utilities\Utils::get_kses_array() );
								} else {
									?>
									<button type="submit" name="submit-form-donation"
											class="xs-btn btn-special submit-btn"><?php echo esc_html( $formDesignData->submit_button ? $formDesignData->submit_button : 'Donate Now' ); ?></button>
									<?php
								}
								if ( isset( $formTermsData->enable ) && $formTermsData->content_position == 'after-submit-button' ) {
									?>
									<div class="xs-donate-display-amount xs-radio_style <?php echo esc_attr( $enableDisplayField ); ?>">
										<?php echo wp_kses( $termsContent, \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
									</div>
								<?php } ?>
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

	<script type='text/javascript'>
		xs_donate_amount_set(<?php echo esc_attr( $defaultData ); ?>,<?php echo esc_attr( $post->ID ); ?>);
	</script>

</div>

<?php do_action( 'wfp_campaign_content_after' ); ?>
