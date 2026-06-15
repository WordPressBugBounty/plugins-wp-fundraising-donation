<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

if ( isset( $_POST['wpf_settings_nonce'] ) ) {
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wpf_settings_nonce'] ) ), 'wpf_save_settings' ) ) {
		esc_html_e( 'You are not allowed to save data.', 'wp-fundraising-donation' );
	}
}
?>
<div class="wrap wfp-view wfp-view-admin">
	<div class="wfdp-donation-reports">
		<h1> <?php echo esc_html__( 'Settings', 'wp-fundraising-donation' ); ?></h1>

		<?php if ( $message_status == 'show' ) { ?>

			<div class="">
				<div class="updated  notice is-dismissible" style="margin: 1em 0px; visibility: visible; opacity: 1;">
					<p><?php echo wp_kses( $message_text, \WfpFundraising\Utilities\Utils::get_kses_array() ); ?></p>
					<button type="button" class="notice-dismiss">
						<span class="screen-reader-text"><?php echo esc_html__( 'Dismiss this notice.', 'wp-fundraising-donation' ); ?></span>
					</button>
				</div>
			</div>

			<?php
		}

		$wfpGateCampaignData   = isset( $wfpSetupData['payment'] ) ? $wfpSetupData['payment'] : 'default';
		$wfp_woc_selected_class = $wfpGateCampaignData == 'woocommerce' ? 'wfp-disabled' : '';
		$wfp_active_tab         = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'general';

		require __DIR__ . '/settings-tab-menu.php';

		if ( $wfp_active_tab == 'global' ) {
			include __DIR__ . '/include/global-options.php';

		} elseif ( $wfp_active_tab == 'general' ) {
			include __DIR__ . '/include/general-options.php';

		} elseif ( $wfp_active_tab == 'page' ) {
			include __DIR__ . '/include/page-options.php';

		} elseif ( $wfp_active_tab == 'gateway' ) {
			include __DIR__ . '/include/payment-method.php';
			include \WFP_Fundraising::plugin_dir() . 'payment-module/views/payment-setup.php';

		} elseif ( $wfp_active_tab == 'email' ) {
			include __DIR__ . '/include/email-settings.php';

		} elseif ( $wfp_active_tab == 'display' ) {
			include __DIR__ . '/include/display-settings.php';

		} elseif ( $wfp_active_tab == 'share' ) {
			include __DIR__ . '/include/share-settings.php';

		} elseif ( $wfp_active_tab == 'terms' ) {
			include __DIR__ . '/include/terms-settings.php';

		} elseif ( $wfp_active_tab == 'email_options' ) {

			if ( did_action( \WfpFundraising\Apps\Key::FUNDRAISING_PRO_LOADED ) ) {
				include \WFP_Fundraising::plugin_parent_dir() . 'wp-fundraising-donation-pro/views/admin/settings/auth-settings.php';
			}
		} elseif ( $wfp_active_tab == 'pp_gateway' ) {

			if ( did_action( \WfpFundraising\Apps\Key::FUNDRAISING_PRO_LOADED ) ) {
				include \WFP_Fundraising::plugin_parent_dir() . 'wp-fundraising-donation-pro/views/admin/settings/pp-settings.php';
			}
		}
		?>

	</div>
</div>
