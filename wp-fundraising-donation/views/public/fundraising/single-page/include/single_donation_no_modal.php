<?php

/**
 * Hook for putting anything before donation content
 */
do_action( 'wfp_campaign_content_before' );

?>

<div class="wfp-container xs-wfp-donation" style="<?php echo esc_attr( $page_width ) <= 0 ? '' : 'max-width:' . esc_attr( $page_width ) . 'px;'; ?>">

	<div class="wfp-view wfp-view-public">
		<div class="wfdp-donation-form <?php echo esc_html( $customClass ); ?>" <?php echo esc_attr( empty( $customIdData ) ? '' : 'id=".' . $customIdData . '."' ); ?>>
			<form method="post"
				  class="wfdp-donationForm ft7"
				  id="wfdp-donationForm-<?php echo esc_attr( $postId ); ?>"
				  data-wfp-id="<?php echo esc_attr( $postId ); ?>"
				  data-wfp-payment_type="<?php echo esc_attr( $paymentType ); ?>"
				  wfp-data-url="<?php echo esc_url( $urlCheckout ); ?>" >
				  <?php wp_nonce_field( 'wpf_checkout_nonce_field', 'wpf_checkout' ); ?>

				<div class="xs-modal-body wfp-donation-form-wraper">
					<?php require __DIR__ . '/partials/single_donation_body_no_modal.php'; ?>
				</div>

				<?php

				if ( $wfp_form_fields == \WfpFundraising\Apps\Key::WFP_FORM_FIELDS_ONLY_BTN ) {
					?>

					<div class="wfdp-donation-input-form wfdp-donation-continue-btn  <?php echo esc_attr( $enableDisplayField ); ?> xs-donate-visible">
						<button type="button"
								class="xs-btn btn-special submit-btn"
								onclick="xs_show_hide_donate_font('.xs-show-div-only-button__<?php echo esc_attr( $postId ); ?>');">
							<?php echo esc_html( $formDesignData->continue_button ? $formDesignData->continue_button : 'Continue' ); ?>
						</button>
					</div>

					<div class="wfp-donate-form-footer <?php echo esc_attr( $enableDisplayField ); ?>">

						<?php require __DIR__ . '/partials/_donation_button.php'; ?>

					</div>

					<?php

				} elseif ( $wfp_form_fields == \WfpFundraising\Apps\Key::WFP_FORM_FIELDS_ALL ) {
					?>

					<div class="wfp-donate-form-footer">

						<?php require __DIR__ . '/partials/_donation_button.php'; ?>

					</div>

					<?php
				}
				?>

			</form>

			<?php

			if ( \WfpFundraising\Utilities\Helper::is_woocom_payment() ) {

				\WfpFundraising\Utilities\Helper::add_2_cart_form( $postId );
			}

			?>

		</div>
	</div>

	<script type='text/javascript'>
		xs_donate_amount_set(<?php echo esc_attr( $defaultData ); ?>,<?php echo esc_attr( $postId ); ?>);
	</script>

</div>

<?php

/**
 * We are giving another hook for after the content of fund raising
 */
do_action( 'wfp_campaign_content_after' );
