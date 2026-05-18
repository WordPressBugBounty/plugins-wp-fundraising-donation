<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

require __DIR__ . '/donation-display-form-common.php';

?>

<?php do_action( 'wfp_campaign_content_before' ); ?>

<div class="wfp-container xs-wfp-donation"
	 style="<?php echo esc_attr( $wfpPage_width <= 0 ? '' : 'max-width:' . $wfpPage_width . 'px;' ); ?>">

	<div class="wfp-view wfp-view-public">
		<div class="wfdp-donation-form <?php echo esc_attr( $wfpCustomClass ); ?>" id="<?php echo esc_attr( $wfpCustomIdData ); ?>">

			<form method="post"
				  class="wfdp-donationForm ft4"
				  data-wfp-id="<?php echo esc_attr( $post->ID ); ?>"
				  data-wfp-payment_type="<?php echo esc_attr( $wfpGateCampaignData ); ?>"
				  id="wfdp-donationForm-<?php echo esc_attr( $post->ID ); ?>"
				  wfp-data-url="<?php echo esc_url( $wfpUrlCheckout ); ?>" >
				  <?php wp_nonce_field( 'wpf_checkout_nonce_field', 'wpf_checkout' ); ?>
				<div class='xs-modal-body wfp-donation-form-wraper'>
					<?php require __DIR__ . '/include/doantion-form-include-sn.php'; ?>
				</div>

				<?php

				// button section
				if ( $wfp_form_styles == 'only_button' ) {
					?>

					<div class="wfdp-donation-input-form wfdp-donation-continue-btn  <?php echo esc_attr( $wfpEnableDisplayField ); ?> xs-donate-visible">
						<button type="button"
								class="xs-btn btn-special submit-btn"
								onclick="xs_show_hide_donate_font('.xs-show-div-only-button__<?php echo esc_attr( $post->ID ); ?>');">
							<?php echo esc_html( $wfpFormDesignData->continue_button ? $wfpFormDesignData->continue_button : __( 'Continue', 'wp-fundraising' ) ); ?>
						</button>
					</div>

					<div class="wfp-donate-form-footer <?php echo esc_attr( $wfpEnableDisplayField ); ?>">
						<?php if ( isset( $wfpFormTermsData->enable ) && $wfpFormTermsData->content_position == 'before-submit-button' ) { ?>
							<div class="xs-donate-display-amount <?php echo esc_attr( $wfpEnableDisplayField ); ?>">
								<?php echo wp_kses( $wfpTermsContent, \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
							</div>
						<?php } ?>

						<div class="wfdp-donation-input-form">
							<?php
							if ( $wfp_campaign_status == 'Ends' ) {
								echo wp_kses( '<p class="xs-alert xs-alert-success">' . $wfpGoalMessage . '</p>', \WfpFundraising\Utilities\Utils::get_kses_array() );
							} else {
								?>
								<button type="submit"
										class="xs-btn btn-special submit-btn"
										name="submit-form-donation" 
										<?php echo esc_html( $wfpFormTermsData->enable ? 'disabled' : '' ); ?> >
									<?php echo esc_html( $wfpFormDesignData->submit_button ? $wfpFormDesignData->submit_button : __( 'Donate Now', 'wp-fundraising' ) ); ?>
								</button>
								<?php
							}
							?>
						</div>

						<?php if ( isset( $wfpFormTermsData->enable ) && $wfpFormTermsData->content_position == 'after-submit-button' ) { ?>
							<div class="xs-donate-display-amount <?php echo esc_attr( $wfpEnableDisplayField ); ?>">
								<?php echo wp_kses( $wfpTermsContent, \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
							</div>
							<?php
						}
						?>

					</div>

					<?php

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
										name="submit-form-donation" <?php echo esc_html( isset( $wfpFormTermsData->enable ) ? 'disabled' : '' ); ?> > <?php echo esc_html( $wfpFormDesignData->submit_button ? $wfpFormDesignData->submit_button : __( 'Donate Now', 'wp-fundraising' ) ); ?>
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
				?>

			</form>
		</div>
	</div>

	<?php require __DIR__ . '/include/_add2cart_form.php'; ?>

	<script type='text/javascript'>
		<?php
		if ( isset( $_POST['wpf_donate_popup'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wpf_donate_popup'] ) ), 'wpf_donate_popup' ) ) :
			?>
			<?php
			if ( isset( $_POST['donation_type'] ) ) :
				if ( isset( $_POST['donation_type'] ) && $_POST['donation_type'] == 'multi-lebel' ) {
					if ( isset( $_POST['multiple_amount'] ) && $_POST['multiple_amount'] != 'custom' ) {
						$wfp_xs_wfs_amount = intval( $_POST['multiple_amount'] );
					} else {
						$wfp_xs_wfs_amount = isset( $_POST['custom_amount'] ) ? intval( $_POST['custom_amount'] ) : '';
					}
				} else {
					if ( isset( $_POST['type'] ) && $_POST['type'] != 'custom' ) {
						$wfp_xs_wfs_amount = intval( $_POST['type'] );
					} else {
						$wfp_xs_wfs_amount = isset( $_POST['custom_amount'] ) ? intval( $_POST['custom_amount'] ) : '';
					}
				}
				?>
				xs_donate_amount_set(<?php echo esc_html( intval( $wfp_xs_wfs_amount ) ); ?>, <?php echo esc_html( $post->ID ); ?>);

				<?php
			else :
				;
				?>

				xs_donate_amount_set(<?php echo esc_html( $wfpDefaultData ); ?>, <?php echo esc_html( $post->ID ); ?>, <?php echo esc_html( $wfpToFixedPoint ); ?>);

			<?php endif; ?>
		<?php endif; ?>
	</script>


</div>

<?php do_action( 'wfp_campaign_content_after' ); ?>
