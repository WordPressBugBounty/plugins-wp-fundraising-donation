<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

// require __DIR__ . '/single_donation_form_common.php';

/**
 * Hook for putting anything before donation content
 */
do_action( 'wfp_campaign_content_before' );

?>

<div class="wfp-container xs-wfp-donation"
	 style="<?php echo esc_attr( $wfpPage_width <= 0 ? '' : 'max-width:' . $wfpPage_width . 'px;' ); ?>">

	<div class="wfp-view wfp-view-public">
		<div class="wfdp-donation-form <?php echo esc_html( $wfpCustomClass ); ?>" <?php echo esc_attr( empty( $wfpCustomIdData ) ? '' : 'id=".' . $wfpCustomIdData . '."' ); ?>>
			<form method="post"
				  class="wfdp-donationForm ft6"
				  id="wfdp-donationForm-<?php echo esc_attr( $wfpPostId ); ?>"
				  data-wfp-id="<?php echo esc_attr( $wfpPostId ); ?>"
				  data-wfp-payment_type="<?php echo esc_attr( $wfpPaymentType ); ?>"
				  wfp-data-url="<?php echo esc_url( $wfpUrlCheckout ); ?>" >
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
							data-target="xs-donate-modal-popup"> <?php echo esc_html( $wfpFormDesignData->continue_button ? $wfpFormDesignData->continue_button : 'Continue' ); ?>
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
								if ( isset( $wfpFormTermsData->enable ) && $wfpFormTermsData->content_position == 'before-submit-button' ) {
									?>
									<div class="xs-donate-display-amount xs-radio_style <?php echo esc_attr( $wfpEnableDisplayField ); ?> ">
										<?php echo wp_kses( $wfpTermsContent, \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
									</div>
									<?php
								}
								if ( $wfp_campaign_status == 'Ends' ) {
									echo wp_kses( '<p class="xs-alert xs-alert-success">' . $wfpGoalMessage . '</p>', \WfpFundraising\Utilities\Utils::get_kses_array() );
								} else {
									?>
									<button type="submit" name="submit-form-donation"
											class="xs-btn btn-special submit-btn"><?php echo esc_html( $wfpFormDesignData->submit_button ? $wfpFormDesignData->submit_button : 'Donate Now' ); ?></button>
									<?php
								}
								if ( isset( $wfpFormTermsData->enable ) && $wfpFormTermsData->content_position == 'after-submit-button' ) {
									?>
									<div class="xs-donate-display-amount xs-radio_style <?php echo esc_attr( $wfpEnableDisplayField ); ?>">
										<?php echo wp_kses( $wfpTermsContent, \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
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

				\WfpFundraising\Utilities\Helper::add_2_cart_form( $wfpPostId );
			}

			?>
		</div>
	</div>

	<script type='text/javascript'>
		xs_donate_amount_set(<?php echo esc_attr( $wfpDefaultData ); ?>,<?php echo esc_attr( $post->ID ); ?>);
	</script>

</div>

<?php do_action( 'wfp_campaign_content_after' ); ?>
