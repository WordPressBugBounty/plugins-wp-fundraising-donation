<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

	// Verify nonce
if ( isset( $_GET['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['nonce'] ) ), 'wp_nonce' ) ) {
	$wfpGetCamp = (int) isset( $_GET['camp'] ) ? intval( $_GET['camp'] ) : 0;
} else {
	$wfpGetCamp = 0;
}
?>

<div class="wfp-campaign-content wfp-content-padding">
	
	<form id="wfp_regForm" class="wfp_regForm" method="POST" enctype="multipart/form-data">

		<?php
		$wfp_post_data = array();
		if ( $wfpGetCamp > 0 ) {
			$post = get_post( $wfpGetCamp );
			if ( isset( $post->post_author ) && $post->post_author == $wfpUserId ) {
				if ( in_array( $post->post_status, array( 'draft', 'publish', 'pending' ) ) ) {
					$wfp_post_data = $post;
				}
			}
		}

		$wfpGetMetaData = array();
		$wfp_post_id     = isset( $wfp_post_data->ID ) ? $wfp_post_data->ID : 0;
		if ( $wfp_post_id > 0 ) {
			$wfp_get_meta_content = get_post_meta( $wfp_post_id, 'wfp_form_options_meta_data', false );
			$wfpGetMetaData      = json_decode( json_encode( end( $wfp_get_meta_content ) ) );
		}

		?>

		<div class="wfp-tab-step-control xs-text-left" wfp-control="yes">
			<button class="wfp-step xs-btn xs-btn-primary active" ><?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_tab_intro', __( '1. Campaign Intro', 'wp-fundraising-donation' ) ) ); ?></button>
			<button class="wfp-step xs-btn xs-btn-primary " ><?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_tab_details', __( '2. Add Details', 'wp-fundraising-donation' ) ) ); ?></button>
			<button class="wfp-step xs-btn xs-btn-primary" ><?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_tab_rewards', __( '3. Add Rewards', 'wp-fundraising-donation' ) ) ); ?></button>
			<button class="wfp-step xs-btn xs-btn-primary" ><?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_tab_submit', __( '4. Submit Now', 'wp-fundraising-donation' ) ) ); ?></button>
		</div>

		<div class="wfp-tabs-control ">
			<div class="wfp-tab active">
				<?php require __DIR__ . '/add-campaign/campaign-intro.php'; ?>
			</div>
			<div class="wfp-tab ">
				<?php require __DIR__ . '/add-campaign/add-details.php'; ?>
			</div>
			<div class="wfp-tab">
				<?php require __DIR__ . '/add-campaign/add-rewards.php'; ?>
			</div>
			<div class="wfp-tab">
				<?php require __DIR__ . '/add-campaign/finish.php'; ?>
			</div>
			<div class="wfp-form-fotter">
				<div class="xs-float-left">
					<button type="button" class="wfp-form-button xs-btn xs-btn-primary wfp-preview"><span class=""></span><?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_button_pre', __( 'Previous', 'wp-fundraising-donation' ) ) ); ?></button>
				</div>

				<div class="xs-float-right">
					<button type="submit" class="wfp-form-button xs-btn xs-btn-primary wfp-next xs-text-right" wfp-finish-button="Submit" name="post_dashboard_campaign"><span class=""></span><?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_button_next', __( 'Next', 'wp-fundraising-donation' ) ) ); ?></button>
				</div>
			</div>
		</div>

	</form>
</div>

<script>
	var wfp_form = wfp_tab_control('.wfp_regForm');
</script>

