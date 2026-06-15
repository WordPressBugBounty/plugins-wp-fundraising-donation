<?php 
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

?>
<div class="wfp-view wfp-view-public">
	<section class="wfp-dashboard <?php echo esc_attr( $className ); ?>" id="<?php echo esc_attr( $idName ); ?>">
		<div class="xs-row xs-no-gutters dashboard-content">
			<div class="xs-col-md-3 dashboard-left-section">
				<div class="wfp-mobile-close-btn">
					<i class="wfpf wfpf-close-outline wfp-mobile-close-btn--icon"></i>
				</div>
				<?php
					$wfpUserId = get_current_user_id();

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
					$wfpGetMetaGeneralOp = get_option( \WfpFundraising\Apps\Settings::OK_GENERAL_DATA );
					$wfpGetMetaGeneral   = isset( $wfpGetMetaGeneralOp['options'] ) ? $wfpGetMetaGeneralOp['options'] : array();

					$wfpDefaultCurrencyInfo = isset( $wfpGetMetaGeneral['currency']['name'] ) ? $wfpGetMetaGeneral['currency']['name'] : 'US-USD';
					$wfpExplCurr            = explode( '-', $wfpDefaultCurrencyInfo );
					$wfpCurrCode            = isset( $wfpExplCurr[1] ) ? $wfpExplCurr[1] : 'USD';
					$wfpSymbols             = isset( $wfpCountryList[ current( $wfpExplCurr ) ]['currency']['symbol'] ) ? $wfpCountryList[ current( $wfpExplCurr ) ]['currency']['symbol'] : '';
					$wfpSymbols             = strlen( $wfpSymbols ) > 0 ? $wfpSymbols : $wfpCurrCode;

					$wfp_defaultUse_space = isset( $wfpGetMetaGeneral['currency']['use_space'] ) ? $wfpGetMetaGeneral['currency']['use_space'] : 'off';

					$wfpGetPage = sanitize_file_name( $wfpGetPage );

					// Dynamically find all allowed pages from existing `*-content.php` files
					$wfp_allowed_pages = array_map(
						function( $file ) {
							return basename( $file, '-content.php' );
						},
						glob( __DIR__ . '/dashboard/*-content.php' )
					);

					if ( in_array( $wfpGetPage, $wfp_allowed_pages, true ) ) {
						$wfp_page_file = __DIR__ . '/dashboard/' . $wfpGetPage . '-content.php';

						if ( file_exists( $wfp_page_file ) ) {
							require $wfp_page_file;
						} else {
							echo esc_html__( 'Dashboard page not found.', 'wp-fundraising-donation' );
						}
					} else {
						echo esc_html__( 'Invalid dashboard page requested.', 'wp-fundraising-donation' );
					}
				?>
			</div>
		</div>
	</section>
</div>
