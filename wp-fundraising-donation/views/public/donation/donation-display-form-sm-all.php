<?php

require __DIR__ . '/donation-display-form-common.php';

?>

<?php do_action( 'wfp_campaign_content_before' ); ?>

<div class="wfp-container xs-wfp-donation"  style="<?php echo esc_attr( $page_width <= 0 ? '' : 'max-width:' . $page_width . 'px;' ); ?>">
	<div class="wfp-view wfp-view-public">
		<div class="wfdp-donation-form <?php echo esc_attr( $customClass ); ?>" id="<?php echo esc_attr( $customIdData ); ?>">
			<form method="post"
				  class="wfdp-donationForm ft2"
				  id="wfdp-donationForm-<?php echo esc_attr( $post->ID ); ?>"
				  data-wfp-id="<?php echo esc_attr( $post->ID ); ?>"
				  data-wfp-payment_type="<?php echo esc_attr( $gateCampaignData ); ?>"
				  wfp-data-url="<?php echo esc_url( $urlCheckout ); ?>" >
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

				// So if modal status is on then this needs to be printed out.
				if ( $modal_status == 'Yes' ) :
					?>
					<div class="xs-modal-dialog wfp-donate-modal-popup" id="xs-donate-modal-popup">
						<div class="wfp-donate-modal-popup-wraper">
							<div class="wfp-modal-content">
								<div class="xs-modal-header">
									<button type="button" class="xs-btn danger xs-modal-header--btn-close"
											data-modal-dismiss="modal"><i
												class="wfpf wfpf-close-outline xs-modal-header--btn-close__icon"></i>
									</button>
								</div>
								<div class="xs-modal-body wfp-donation-form-wraper">
									<?php
									include __DIR__ . '/include/doantion-form-include.php';
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
												class="xs-btn btn-special submit-btn" <?php echo esc_html( $formTermsData->enable ? 'disabled' : '' ); ?> ><?php echo esc_html( $formDesignData->submit_button ? $formDesignData->submit_button : __( 'Donate Now', 'wp-fundraising' ) ); ?></button>
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

				<?php endif; ?>

			</form>
		</div>
	</div>

	<?php require __DIR__ . '/include/_add2cart_form.php'; ?>

	<script type='text/javascript'>
		xs_donate_amount_set(<?php echo esc_html( $defaultData ); ?>,<?php echo esc_html( $post->ID ); ?>,<?php echo esc_html( $toFixedPoint ); ?>);
	</script>

</div>

<?php do_action( 'wfp_campaign_content_after' ); ?>
