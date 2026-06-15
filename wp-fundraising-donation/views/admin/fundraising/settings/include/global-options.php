<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

?>
<div class="wfdp-payment-section" >
	<div class="wfdp-payment-headding">
		<h2><?php echo esc_html__( 'Setup Global Options', 'wp-fundraising-donation' ); ?></h2>
	</div>
	<div class="wfdp-payment-gateway">
		<form action="<?php echo esc_url( admin_url() . 'edit.php?post_type=' . self::post_type() . '&page=settings&tab=global' ); ?>" method="post">
		<?php wp_nonce_field( 'wpf_save_settings', 'wpf_settings_nonce' ); ?>
			<ul class="wfdp-social_share">
			<?php
			foreach ( $global_options as $wfp_key => $wfp_value ) :
				$wfpCheckEnable = isset( $getMetaGlobal[ $wfp_key ]['enable'] ) ? $getMetaGlobal[ $wfp_key ]['enable'] : 'No';
				if ( ! isset( $getMetaGlobalOp['options'] ) ) {
					$wfpCheckEnable = 'Yes';
				}
				?>
				<li class="wfdp-social-input-container"> 
					<div class="wfdp-social-label">
						<?php echo esc_html( $wfp_value['name'] ); ?>
					</div>

					<div class="wfdp-social-switch">
						<div class="xs-switch-button_wraper">
							<input class="xs_donate_switch_button" type="checkbox" id="donation_form_payment_enable__<?php echo esc_attr( $wfp_key ); ?>" <?php echo ( $wfpCheckEnable == 'Yes' ) ? 'checked' : ''; ?> name="xs_submit_settings_data_global[options][<?php echo esc_attr( $wfp_key ); ?>][enable]" value="Yes">
							<label for="donation_form_payment_enable__<?php echo esc_html( $wfp_key ); ?>" class="xs_donate_switch_button_label small xs-round"></label>
						</div>
						<span class="xs-donetion-field-description hidden"><?php echo esc_html( $wfp_value['note'] ); ?></span>
					</div>
					
				</li>
			<?php endforeach; ?>
			</ul>
			<button type="submit" name="submit_donate_global_setting" class="button button-primary button-large"><?php echo esc_html__( 'Save', 'wp-fundraising-donation' ); ?></button>
		</form>
	</div>
</div>
