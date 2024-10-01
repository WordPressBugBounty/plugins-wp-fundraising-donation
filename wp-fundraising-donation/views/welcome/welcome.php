<?php
// Default Setup Data
$settings = \WfpFundraising\Apps\Settings::default_setup();

$metaSetupKey = 'wfp_setup_services_data';
$getSetUpData = get_option( $metaSetupKey );
$gateWaysData = isset( $getSetUpData['services'] ) ? $getSetUpData['services'] : array();
$checkStep    = isset( $getSetUpData['services']['finish'] ) ? $getSetUpData['services']['finish'] : current( array_keys( $settings ) );

?>
<div class="wfp-welcome-contrainer">
	<div class="wfp-welcome-header">
		<h1><?php echo esc_html__( 'Welcome FundEngine', 'wp-fundraising' ); ?></h1>
	</div>
	<div class="wfp-welcome-body">
		<div class="wlecome-image">
			<img src="<?php echo esc_url( \WFP_Fundraising::plugin_url() . 'views/welcome/' ); ?>image/welcome.jpg">
		</div>
		<div class="welcome-button">
			<button type="button" class="xs-btn btn-special continue-bt welcome wfdp-btn" data-type="modal-trigger" data-target="xs-donate-modal-popup__welcome"><?php echo esc_html__( 'Setup', 'wp-fundraising' ); ?> </button>
		</div>
	</div>
</div>

<div class="wfdp-modal xs-modal-dialog wfp-welcome-dualog" id="xs-donate-modal-popup__welcome">
	<div class="wfdp-modal-inner">
		<form method="post" class="wfdp-welcomeForm" id="wfdp-welcomeForm-19">
			<div class="xs-modal-header">
				<div class="tabHeader">
					<h4><?php echo esc_html__( 'Setup Process', 'wp-fundraising' ); ?></h4>
				</div>
				<button type="button" class="xs-btn xs-btn-close danger" data-modal-dismiss="modal">X</button>
			</div>
			<div class="xs-modal-body">
				<div class="wfp-welcome-body-content">
				<?php
					$m = 0;
				foreach ( $settings as $k => $v ) :
					$openCLass = ( $k == $checkStep ) ? 'wfp-open' : '';
					$preStep   = isset( $v['button']['pre'] ) ? $v['button']['pre'] : 'Previous';
					$preType   = isset( $v['button']['pre_type'] ) ? $v['button']['pre_type'] : 'close';
					$nextStep  = isset( $v['button']['next'] ) ? $v['button']['next'] : 'Next';
					$nextType  = isset( $v['button']['next_type'] ) ? $v['button']['next_type'] : 'next';

					$dataPage = isset( $v['data'] ) ? $v['data'] : '';

					$returnType     = isset( $v['return']['type'] ) ? $v['return']['type'] : 'next';
					$returnLocation = isset( $v['return']['location'] ) ? $v['return']['location'] : ( $m + 1 );
					?>
					<div class="wfp-welcome-block <?php echo esc_attr( $openCLass ); ?>" id="wfp-<?php echo esc_attr( $k ); ?>-block" wfp-path="<?php echo esc_attr( $k ); ?>" wfp-wel-index="<?php echo esc_attr( $m ); ?>" wfp-pre-step="<?php echo esc_attr( $preStep ); ?>" wfp-pre-step-type="<?php echo esc_attr( $preType ); ?>" wfp-next-step="<?php echo esc_attr( $nextStep ); ?>" wfp-next-step-type="<?php echo esc_attr( $nextType ); ?>" wfp-return="<?php echo esc_attr( $returnType ); ?>" wfp-return-location="<?php echo esc_attr( $returnLocation ); ?>">
					<?php
					$image = isset( $v['img_url'] ) ? $v['img_url'] : '';
					if ( strlen( $image ) > 0 ) {
						?>
						<img src="<?php echo esc_attr( $image ); ?>" class="welcome-body-image wfp-image-<?php echo esc_attr( $k ); ?>" alt="<?php echo esc_attr( $k ); ?>" id="wfp-image-<?php echo esc_attr( $k ); ?>">
						<?php } ?>
						<h3><?php echo esc_html( isset( $v['headding'] ) ? $v['headding'] : '' ); ?></h3>
						<p><?php echo esc_html( isset( $v['details'] ) ? $v['details'] : '' ); ?></p>
						<?php
						if ( strlen( $dataPage ) > 2 ) :
							include __DIR__ . '/' . $dataPage;
							?>
						<?php endif; ?>
					</div>
					<?php
					$m++;
					endforeach;
				?>
				</div>
			</div>
			<div class="xs-modal-footer welcome-footer">
				<div class="wfp-fotter-block welcome-left-content">
					<button type="button" name="welcome-pre-filed" wfp-total-step="<?php echo esc_attr( sizeof( $settings ) ); ?>" wfp-button-type="pre" class="welcome-hidden wfp-next-pre-control wfdp-btn"><?php echo esc_html( isset( $settings[ $checkStep ]['button']['pre'] ) ? $settings[ $checkStep ]['button']['pre'] : 'Cancel' ); ?></button>
				</div>
				<div class="wfp-fotter-block welcome-center-content selector-setup">
					<ul>
					<?php
					$n = 0;
					foreach ( $settings as $k => $v ) :
						$selectCLass = ( $k == $checkStep ) ? 'wfp-selected' : '';
						?>
						<li class="<?php echo esc_attr( $selectCLass ); ?>" wfp-selector-index="<?php echo esc_attr( $n ); ?>" id="wfp-<?php echo esc_attr( $k ); ?>-selector"></li>
						<?php
						$n++;
					endforeach;
					?>
					</ul>
				</div>
				<div class="wfp-fotter-block welcome-right-content">
					<button data-target="wfdp-welcomeForm-19" type="submit" name="welcome-next-filed" wfp-total-step="<?php echo esc_attr( sizeof( $settings ) ); ?>" wfp-button-type="next" class="welcome-visible wfp-next-pre-control wfdp-btn"><?php echo esc_html( isset( $settings[ $checkStep ]['button']['next'] ) ? $settings[ $checkStep ]['button']['next'] : 'Start' ); ?></button>
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
