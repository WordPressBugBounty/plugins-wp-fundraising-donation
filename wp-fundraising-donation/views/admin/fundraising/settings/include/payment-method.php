<div class="wfdp-payment-method-container">
	<div class="wfp-woocommerce-message xs-donate-hidden <?php echo ( $gateCampaignData == 'woocommerce' ) ? 'xs-donate-visible' : ''; ?>">
		<p> <a href="<?php echo esc_url( admin_url( 'admin.php?page=wc-settings&tab=checkout' ) ); ?>"> <?php echo esc_html__( 'Payment Getways Setup of Woocommerce ', 'wp-fundraising' ); ?> </a> </p>
	</div>
	<div class="wfdp-payment-section" >
		<div class="wfdp-payment-headding">
			<h2><?php echo esc_html__( 'Payment Type', 'wp-fundraising' ); ?></h2>
		</div>
		
		<div class="welcome-campaign">
			<ul class="welcome-donate-option">
				<li>
					<label class="welcome-default-check default-target-welcome <?php echo ( $gateCampaignData == 'default' ) ? 'xs-donate-visible' : ''; ?>" onclick="wdp_payment_modify_report(this);">
						<input name="paymenttype" <?php echo ( $gateCampaignData == 'default' ) ? 'checked' : ''; ?> value="default" type="radio"> 
						<div class="wfdp-paymant-method-data">
							<h3><?php echo esc_html__( 'Default', 'wp-fundraising' ); ?></h3>
							<p><?php echo esc_html__( 'Cash on delivery, Direct bank transfer, Check payments, PayPal, Stripe etc.', 'wp-fundraising' ); ?></p>
						</div>				
					</label>
				</li>
				<li>
					<label class="welcome-default-check woocommerce-target-welcome <?php echo ( $gateCampaignData == 'woocommerce' ) ? 'xs-donate-visible' : ''; ?>" onclick="wdp_payment_modify_report(this);">
						<input name="paymenttype" <?php echo ( $gateCampaignData == 'woocommerce' ) ? 'checked' : ''; ?> value="woocommerce" type="radio"> 
						<div class="wfdp-paymant-method-data">	
							<h3><?php echo esc_html__( 'Woocommerce', 'wp-fundraising' ); ?></h3>
							<p><?php echo esc_html__( 'Woocommerce all payment getway.', 'wp-fundraising' ); ?></p>	
						</div>			
					</label>
				</li>
			</ul>
				
			
		</div>
	</div>
</div>
