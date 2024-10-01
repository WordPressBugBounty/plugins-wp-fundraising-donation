<div class="wfp-view wfp-view-public">
	<section class="wfp-dashboard <?php echo esc_attr( $className ); ?>" id="<?php echo esc_attr( $idName ); ?>">
		<div class="xs-row xs-no-gutters dashboard-content">
			<div class="xs-col-md-3 dashboard-left-section">
				<div class="wfp-mobile-close-btn">
					<i class="wfpf wfpf-close-outline wfp-mobile-close-btn--icon"></i>
				</div>
				<?php
					$userId = get_current_user_id();

					require __DIR__ . '/dashboard/menu/short-profile.php';
					require __DIR__ . '/dashboard/menu/left-menu.php';
				?>
			</div>
			<div class="xs-col-md-9 dashboard-right-section">
				<div class="wfp-mobile-nav">
					<i class="wfpf wfpf-menu wfp-mobile-nav--icon"></i>
				</div>
				<?php
					require \WFP_Fundraising::plugin_dir() . 'country-module/country-info.php';
					/*currency information*/
					$getMetaGeneralOp = get_option( \WfpFundraising\Apps\Settings::OK_GENERAL_DATA );
					$getMetaGeneral   = isset( $getMetaGeneralOp['options'] ) ? $getMetaGeneralOp['options'] : array();

					$defaultCurrencyInfo = isset( $getMetaGeneral['currency']['name'] ) ? $getMetaGeneral['currency']['name'] : 'US-USD';
					$explCurr            = explode( '-', $defaultCurrencyInfo );
					$currCode            = isset( $explCurr[1] ) ? $explCurr[1] : 'USD';
					$symbols             = isset( $countryList[ current( $explCurr ) ]['currency']['symbol'] ) ? $countryList[ current( $explCurr ) ]['currency']['symbol'] : '';
					$symbols             = strlen( $symbols ) > 0 ? $symbols : $currCode;

					$defaultUse_space = isset( $getMetaGeneral['currency']['use_space'] ) ? $getMetaGeneral['currency']['use_space'] : 'off';

					require __DIR__ . '/dashboard/' . $getPage . '-content.php';
				?>
			</div>
		</div>
	</section>
</div>
