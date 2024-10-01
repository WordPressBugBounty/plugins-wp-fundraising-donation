<?php
$gateCampaignData = isset( $gateWaysData['campaign'] ) ? $gateWaysData['campaign'] : 'donation';
?>
<div class="welcome-campaign wfp-welcome-setup">
	<ul class="welcome-donate-option">
		<li>
			<label class="welcome-default-check donation-target-welcome <?php echo ( $gateCampaignData == 'donation' ) ? 'xs-donate-visible' : ''; ?>" onclick="xs_show_hide_donate_multiple('.welcome-default-check', '.donation-target-welcome')">
				<input name="xs_welcome_data_submit[services][campaign]" <?php echo ( $gateCampaignData == 'donation' ) ? 'checked' : ''; ?> value="donation" type="radio"> 
					<div class="wfdp-paymant-method-data">
						<h3><?php echo esc_html__( 'Single Donation', 'wp-fundraising' ); ?></h3>
						<p><?php echo esc_html__( 'Only for Donation system.', 'wp-fundraising' ); ?></p>
					</div>
			</label>
		</li>
		<li>
			<label class="welcome-default-check crowdfunding-target-welcome <?php echo ( $gateCampaignData == 'crowdfunding' ) ? 'xs-donate-visible' : ''; ?>" onclick="xs_show_hide_donate_multiple('.welcome-default-check', '.crowdfunding-target-welcome')">
				<input name="xs_welcome_data_submit[services][campaign]" <?php echo ( $gateCampaignData == 'crowdfunding' ) ? 'checked' : ''; ?> value="crowdfunding" type="radio"> 	
				<div class="wfdp-paymant-method-data">
					<h3><?php echo esc_html__( 'Crowdfunding', 'wp-fundraising' ); ?></h3>
					<p><?php echo esc_html__( 'Fundraising System with Donate.', 'wp-fundraising' ); ?></p>
				</div>	
			</label>
		</li>
	</ul>
</div>
