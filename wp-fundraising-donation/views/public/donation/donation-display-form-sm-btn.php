<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

require __DIR__ . '/donation-display-form-common.php';

?>

<?php do_action( 'wfp_campaign_content_before' ); ?>

<div class="wfp-container xs-wfp-donation" style="<?php echo esc_attr( $wfpPage_width <= 0 ? '' : 'max-width:' . $wfpPage_width . 'px;' ); ?>">

	<div class="wfp-view wfp-view-public">
		<div class="wfdp-donation-form <?php echo esc_html( $wfpCustomClass ); ?>" <?php echo empty( $wfpCustomIdData ) ? '' : 'id=".' . esc_attr( $wfpCustomIdData ) . '."'; ?>>
			<form method="post"
				  class="wfdp-donationForm ft3"
				  id="wfdp-donationForm-<?php echo esc_attr( $post->ID ); ?>"
				  data-wfp-id="<?php echo esc_attr( $post->ID ); ?>"
				  data-wfp-payment_type="<?php echo esc_attr( $wfpGateCampaignData ); ?>"
				  wfp-data-url="<?php echo esc_url( $wfpUrlCheckout ); ?>">

				  <?php wp_nonce_field( 'wpf_checkout_nonce_field', 'wpf_checkout' ); ?>
				<?php

				echo "<div class='xs-modal-body wfp-donation-form-wraper'>";
				require __DIR__ . '/include/doantion-form-partial-first.php';
				echo '</div>';


				// button section
				if ( $wfp_form_styles == 'only_button' ) {
					if ( $wfp_modal_status == 'Yes' ) :
						?>

						<div class="wfdp-donation-input-form">
							<button type="button" class="xs-btn btn-special submit-btn wfdp-donation-continue-btn" name="submit-form-donation"
									data-type="modal-trigger"
									data-target="xs-donate-modal-popup"> <?php echo esc_html( $wfpFormDesignData->continue_button ? $wfpFormDesignData->continue_button : 'Continue' ); ?>
							</button>
						</div>
						<?php

					endif;

				} elseif ( $wfp_form_styles == 'all_fields' ) {
					?>

					<div class="wfp-donate-form-footer">

						<?php
						if ( isset( $wfpFormTermsData->enable ) && $wfpFormTermsData->content_position == 'before-submit-button' ) {
							?>
							<div class="xs-donate-display-amount xs-radio_style <?php echo esc_attr( $wfpEnableDisplayField ); ?>">
								<?php echo wp_kses( $wfpTermsContent, \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
							</div>
						<?php } ?>
						<div class="wfdp-donation-input-form">
							<?php
							if ( $wfp_campaign_status == 'Ends' ) {
								echo wp_kses( '<p class="xs-alert xs-alert-success">' . $wfpGoalMessage . '</p>', \WfpFundraising\Utilities\Utils::get_kses_array() );
							} else {
								?>
								<button type="submit" class="xs-btn btn-special submit-btn"
										name="submit-form-donation" <?php echo esc_html( $wfpFormTermsData->enable ? 'disabled' : '' ); ?> > <?php echo esc_html( $wfpFormDesignData->submit_button ? $wfpFormDesignData->submit_button : __( 'Donate Now', 'wp-fundraising-donation' ) ); ?>
								</button>
							<?php } ?>
						</div>
						<?php
						if ( isset( $wfpFormTermsData->enable ) && $wfpFormTermsData->content_position == 'after-submit-button' ) {
							?>
							<div class="xs-donate-display-amount xs-radio_style <?php echo esc_attr( $wfpEnableDisplayField ); ?>">
								<?php echo wp_kses( $wfpTermsContent, \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
							</div>
						<?php } ?>

					</div>

					<?php
				}


				// So if modal status is on then this needs to be printed out.
				if ( $wfp_modal_status == 'Yes' ) :
					?>
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
									include __DIR__ . '/include/doantion-form-partial-second.php';
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
												class="xs-btn btn-special submit-btn"
												<?php echo esc_html( $wfpFormTermsData->enable ? 'disabled' : '' ); ?> 
												>
										<?php echo esc_html( $wfpFormDesignData->submit_button ? $wfpFormDesignData->submit_button : __( 'Donate Now', 'wp-fundraising-donation' ) ); ?></button>
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

				<?php endif; ?>

			</form>

			<?php

			if ( \WfpFundraising\Utilities\Helper::is_woocom_payment() ) {

				\WfpFundraising\Utilities\Helper::add_2_cart_form( $post->ID );
			}

			?>

		</div>
	</div>

	<script type='text/javascript'>
		xs_donate_amount_set(<?php echo esc_html( $wfpDefaultData ); ?>,<?php echo esc_html( $post->ID ); ?>,<?php echo esc_html( $wfpToFixedPoint ); ?>);
	</script>

</div>

<?php do_action( 'wfp_campaign_content_after' ); ?>
