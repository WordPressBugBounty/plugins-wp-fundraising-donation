<?php
$gateCampaignData = isset( $gateWaysData['payment'] ) ? $gateWaysData['payment'] : 'default';
?>
<div class="welcome-campaign wfp-welcome-setup">
	<ul class="welcome-donate-option">
		<li>
			<label class="welcome-default-check donation-target-welcome <?php echo ( $gateCampaignData == 'default' ) ? 'xs-donate-visible' : ''; ?>" onclick="xs_show_hide_donate_multiple('.welcome-default-check', '.donation-target-welcome')">
				<input name="xs_welcome_data_submit[services][payment]" <?php echo ( $gateCampaignData == 'default' ) ? 'checked' : ''; ?> value="default" type="radio"> 
				<div class="wfdp-paymant-method-data">
					<h3><?php echo esc_html__( 'Default', 'wp-fundraising' ); ?></h3>
					<p><?php echo esc_html__( 'Cash on delivery, Direct bank transfer, Check payments, PayPal, Stripe etc.', 'wp-fundraising' ); ?></p>	
				</div>			
			</label>
		</li>
		<li>
			<label class="welcome-default-check crowdfunding-target-welcome <?php echo ( $gateCampaignData == 'woocommerce' ) ? 'xs-donate-visible' : ''; ?>" onclick="xs_show_hide_donate_multiple('.welcome-default-check', '.crowdfunding-target-welcome')">
				<input name="xs_welcome_data_submit[services][payment]" <?php echo ( $gateCampaignData == 'woocommerce' ) ? 'checked' : ''; ?> value="woocommerce" type="radio"> 
				<div class="wfdp-paymant-method-data">	
					<h3><?php echo esc_html__( 'Woocommerce', 'wp-fundraising' ); ?></h3>
					<p><?php echo esc_html__( 'Woocommerce all payment getway.', 'wp-fundraising' ); ?></p>	
				</div>			
			</label>
		</li>
	</ul>
</div>
