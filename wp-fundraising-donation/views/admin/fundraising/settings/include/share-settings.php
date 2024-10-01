<div class="wfdp-payment-section" >
	<div class="wfdp-payment-headding">
		<h2><?php echo esc_html__( 'Social Share Options', 'wp-fundraising' ); ?></h2>
	</div>
	<div class="wfdp-payment-gateway">
		<form action="<?php echo esc_url( admin_url() . 'edit.php?post_type=' . self::post_type() . '&page=settings&tab=share' ); ?>" method="post">
			<?php wp_nonce_field( 'wpf_save_settings', 'wpf_settings_nonce' ); ?>
			<ul class="wfdp-social_share">
			<?php

			foreach ( $share_media as $key => $value ) :
				$checkEnable = isset( $getMetaShare[ $key ]['enable'] ) ? $getMetaShare[ $key ]['enable'] : 'No';
				?>
				<li class="wfdp-social-input-container"> 
				<div class="wfdp-social-label">
					<?php echo esc_html( 'Enable ' . $value['label'] ); ?>
				</div>
				<div class="xs-switch-button_wraper">
					<input class="xs_donate_switch_button" type="checkbox" id="donation_form_payment_enable__<?php echo esc_attr( $key ); ?>" <?php echo ( $checkEnable == 'Yes' ) ? 'checked' : ''; ?> name="xs_submit_settings_data_share[media][<?php echo esc_attr( $key ); ?>][enable]" value="Yes">
					<label for="donation_form_payment_enable__<?php echo esc_attr( $key ); ?>" class="xs_donate_switch_button_label small xs-round"></label>
				</div>
				</li>
			<?php endforeach; ?>
			</ul>
			<button type="submit" name="submit_donate_settings_share" class="button button-primary button-large"><?php echo esc_html__( 'Save', 'wp-fundraising' ); ?></button>
		</form>
	</div>
</div>
