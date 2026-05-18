<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

$wfpGateCampaignData = isset( $wfpGateWaysData['campaign'] ) ? $wfpGateWaysData['campaign'] : 'donation';
?>
<div class="welcome-campaign wfp-welcome-setup">
	<ul class="welcome-donate-option">
		<li>
			<label class="welcome-default-check donation-target-welcome <?php echo ( $wfpGateCampaignData == 'donation' ) ? 'xs-donate-visible' : ''; ?>" onclick="xs_show_hide_donate_multiple('.welcome-default-check', '.donation-target-welcome')">
				<input name="xs_welcome_data_submit[services][campaign]" <?php echo ( $wfpGateCampaignData == 'donation' ) ? 'checked' : ''; ?> value="donation" type="radio"> 
					<div class="wfdp-paymant-method-data">
						<h3><?php echo esc_html__( 'Single Donation', 'wp-fundraising' ); ?></h3>
						<p><?php echo esc_html__( 'Only for Donation system.', 'wp-fundraising' ); ?></p>
					</div>
			</label>
		</li>
		<li>
			<label class="welcome-default-check crowdfunding-target-welcome <?php echo ( $wfpGateCampaignData == 'crowdfunding' ) ? 'xs-donate-visible' : ''; ?>" onclick="xs_show_hide_donate_multiple('.welcome-default-check', '.crowdfunding-target-welcome')">
				<input name="xs_welcome_data_submit[services][campaign]" <?php echo ( $wfpGateCampaignData == 'crowdfunding' ) ? 'checked' : ''; ?> value="crowdfunding" type="radio"> 	
				<div class="wfdp-paymant-method-data">
					<h3><?php echo esc_html__( 'Crowdfunding', 'wp-fundraising' ); ?></h3>
					<p><?php echo esc_html__( 'Fundraising System with Donate.', 'wp-fundraising' ); ?></p>
				</div>	
			</label>
		</li>
	</ul>
</div>
