<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;
// Default Setup Data
$wfp_settings = \WfpFundraising\Apps\Settings::default_setup();

$wfpMetaSetupKey = 'wfp_setup_services_data';
$wfpGetSetUpData = get_option( $wfpMetaSetupKey );
$wfpGateWaysData = isset( $wfpGetSetUpData['services'] ) ? $wfpGetSetUpData['services'] : array();
$wfpCheckStep    = isset( $wfpGetSetUpData['services']['finish'] ) ? $wfpGetSetUpData['services']['finish'] : current( array_keys( $wfp_settings ) );

?>
<div class="wfp-welcome-contrainer">
	<div class="wfp-welcome-header">
		<h1><?php echo esc_html__( 'Welcome FundEngine', 'wp-fundraising-donation' ); ?></h1>
	</div>
	<div class="wfp-welcome-body">
		<div class="wlecome-image">
			<img src="<?php echo esc_url( \WFP_Fundraising::plugin_url() . 'views/welcome/' ); ?>image/welcome.jpg">
		</div>
		<div class="welcome-button">
			<button type="button" class="xs-btn btn-special continue-bt welcome wfdp-btn" data-type="modal-trigger" data-target="xs-donate-modal-popup__welcome"><?php echo esc_html__( 'Setup', 'wp-fundraising-donation' ); ?> </button>
		</div>
	</div>
</div>

<div class="wfdp-modal xs-modal-dialog wfp-welcome-dualog" id="xs-donate-modal-popup__welcome">
	<div class="wfdp-modal-inner">
		<form method="post" class="wfdp-welcomeForm" id="wfdp-welcomeForm-19">
			<div class="xs-modal-header">
				<div class="tabHeader">
					<h4><?php echo esc_html__( 'Setup Process', 'wp-fundraising-donation' ); ?></h4>
				</div>
				<button type="button" class="xs-btn xs-btn-close danger" data-modal-dismiss="modal">X</button>
			</div>
			<div class="xs-modal-body">
				<div class="wfp-welcome-body-content">
				<?php
					$wfp_m = 0;
				foreach ( $wfp_settings as $wfp_k => $v ) :
					$wfpOpenCLass = ( $wfp_k == $wfpCheckStep ) ? 'wfp-open' : '';
					$wfpPreStep   = isset( $v['button']['pre'] ) ? $v['button']['pre'] : 'Previous';
					$wfpPreType   = isset( $v['button']['pre_type'] ) ? $v['button']['pre_type'] : 'close';
					$wfpNextStep  = isset( $v['button']['next'] ) ? $v['button']['next'] : 'Next';
					$wfpNextType  = isset( $v['button']['next_type'] ) ? $v['button']['next_type'] : 'next';

					$wfpDataPage = isset( $v['data'] ) ? $v['data'] : '';

					$wfpReturnType     = isset( $v['return']['type'] ) ? $v['return']['type'] : 'next';
					$wfpReturnLocation = isset( $v['return']['location'] ) ? $v['return']['location'] : ( $wfp_m + 1 );
					?>
					<div class="wfp-welcome-block <?php echo esc_attr( $wfpOpenCLass ); ?>" id="wfp-<?php echo esc_attr( $wfp_k ); ?>-block" wfp-path="<?php echo esc_attr( $wfp_k ); ?>" wfp-wel-index="<?php echo esc_attr( $wfp_m ); ?>" wfp-pre-step="<?php echo esc_attr( $wfpPreStep ); ?>" wfp-pre-step-type="<?php echo esc_attr( $wfpPreType ); ?>" wfp-next-step="<?php echo esc_attr( $wfpNextStep ); ?>" wfp-next-step-type="<?php echo esc_attr( $wfpNextType ); ?>" wfp-return="<?php echo esc_attr( $wfpReturnType ); ?>" wfp-return-location="<?php echo esc_attr( $wfpReturnLocation ); ?>">
					<?php
					$wfp_image = isset( $v['img_url'] ) ? $v['img_url'] : '';
					if ( strlen( $wfp_image ) > 0 ) {
						?>
						<img src="<?php echo esc_attr( $wfp_image ); ?>" class="welcome-body-image wfp-image-<?php echo esc_attr( $wfp_k ); ?>" alt="<?php echo esc_attr( $wfp_k ); ?>" id="wfp-image-<?php echo esc_attr( $wfp_k ); ?>">
						<?php } ?>
						<h3><?php echo esc_html( isset( $v['headding'] ) ? $v['headding'] : '' ); ?></h3>
						<p><?php echo esc_html( isset( $v['details'] ) ? $v['details'] : '' ); ?></p>
						<?php
						if ( strlen( $wfpDataPage ) > 2 ) :
							include __DIR__ . '/' . $wfpDataPage;
							?>
						<?php endif; ?>
					</div>
					<?php
					$wfp_m++;
					endforeach;
				?>
				</div>
			</div>
			<div class="xs-modal-footer welcome-footer">
				<div class="wfp-fotter-block welcome-left-content">
					<button type="button" name="welcome-pre-filed" wfp-total-step="<?php echo esc_attr( sizeof( $wfp_settings ) ); ?>" wfp-button-type="pre" class="welcome-hidden wfp-next-pre-control wfdp-btn"><?php echo esc_html( isset( $wfp_settings[ $wfpCheckStep ]['button']['pre'] ) ? $wfp_settings[ $wfpCheckStep ]['button']['pre'] : 'Cancel' ); ?></button>
				</div>
				<div class="wfp-fotter-block welcome-center-content selector-setup">
					<ul>
					<?php
					$wfp_n = 0;
					foreach ( $wfp_settings as $wfp_k => $v ) :
						$wfpSelectCLass = ( $wfp_k == $wfpCheckStep ) ? 'wfp-selected' : '';
						?>
						<li class="<?php echo esc_attr( $wfpSelectCLass ); ?>" wfp-selector-index="<?php echo esc_attr( $wfp_n ); ?>" id="wfp-<?php echo esc_attr( $wfp_k ); ?>-selector"></li>
						<?php
						$wfp_n++;
					endforeach;
					?>
					</ul>
				</div>
				<div class="wfp-fotter-block welcome-right-content">
					<button data-target="wfdp-welcomeForm-19" type="submit" name="welcome-next-filed" wfp-total-step="<?php echo esc_attr( sizeof( $wfp_settings ) ); ?>" wfp-button-type="next" class="welcome-visible wfp-next-pre-control wfdp-btn"><?php echo esc_html( isset( $wfp_settings[ $wfpCheckStep ]['button']['next'] ) ? $wfp_settings[ $wfpCheckStep ]['button']['next'] : 'Start' ); ?></button>
				</div>
			</div>
		</form>
	</div>
</div>
<div class="xs-backdrop"></div>

<script type="text/javascript">
	jQuery(document).ready(function ($) {
		wfp_welcome_control('.wfp-next-pre-control');
	});
</script>
