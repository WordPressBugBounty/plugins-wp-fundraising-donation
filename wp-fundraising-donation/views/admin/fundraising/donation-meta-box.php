<?php
defined( 'ABSPATH' ) || exit;

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals
?>
<div class="wfp-view wfp-view-admin">
	<div class="xs-donate-metabox-panel-wrap xs_shadow_card">
		<?php
		require \WFP_Fundraising::plugin_dir() . 'country-module/country-info.php';

		// get setup data
		$wfpMetaSetupKey = 'wfp_setup_services_data';
		$wfpGetSetUpData = get_option( $wfpMetaSetupKey );
		$wfpSetupData    = isset( $wfpGetSetUpData['services'] ) ? $wfpGetSetUpData['services'] : array();

		// get type of founding
		$wfpFoundingTyepe = isset( $wfpSetupData['campaign'] ) ? $wfpSetupData['campaign'] : 'donation';

		$wfp_donation_format = isset( $wfpGetMetaData->donation->format ) ? $wfpGetMetaData->donation->format : $wfpFoundingTyepe;

		$wfp_donation_type = isset( $wfpGetMetaData->donation->type ) ? $wfpGetMetaData->donation->type : 'multi-lebel';

		$wfpFixedData = isset( $wfpGetMetaData->donation->fixed ) ? $wfpGetMetaData->donation->fixed : array();

		$wfpMultiData = isset( $wfpGetMetaData->donation->multi->dimentions ) && sizeof( $wfpGetMetaData->donation->multi->dimentions ) ? $wfpGetMetaData->donation->multi->dimentions : array(
			(object) array(
				'price' => '1.00',
				'lebel' => 'Basic',
			),
		);

		$wfpDisplayData = isset( $wfpGetMetaData->donation->display ) ? $wfpGetMetaData->donation->display : 'boxed';

		$wfpDonationLimit = isset( $wfpGetMetaData->donation->set_limit ) ? $wfpGetMetaData->donation->set_limit : array();

		$wfpAdd_fees = isset( $wfpGetMetaData->donation->set_add_fees ) ? $wfpGetMetaData->donation->set_add_fees : array();

		$wfpPage_width = isset( $wfpGetMetaData->donation->page_width ) ? $wfpGetMetaData->donation->page_width : 0;

		/*currency information*/
		$wfpMetaGeneralKey   = 'wfp_general_options_data';
		$wfpGetMetaGeneralOp = get_option( $wfpMetaGeneralKey );
		$wfpGetMetaGeneral   = isset( $wfpGetMetaGeneralOp['options'] ) ? $wfpGetMetaGeneralOp['options'] : array();

		$wfpDefaultCurrencyInfo = isset( $wfpGetMetaGeneral['currency']['name'] ) ? $wfpGetMetaGeneral['currency']['name'] : 'US-USD';
		$wfpExplCurr            = explode( '-', $wfpDefaultCurrencyInfo );
		$wfpCurrCode            = isset( $wfpExplCurr[1] ) ? $wfpExplCurr[1] : 'USD';
		$wfpSymbols             = isset( $wfpCountryList[ current( $wfpExplCurr ) ]['currency']['symbol'] ) ? $wfpCountryList[ current( $wfpExplCurr ) ]['currency']['symbol'] : '';
		$wfpSymbols             = strlen( $wfpSymbols ) > 0 ? $wfpSymbols : $wfpCurrCode;

		$wfp_defaultUse_space = isset( $wfpGetMetaGeneral['currency']['use_space'] ) ? $wfpGetMetaGeneral['currency']['use_space'] : 'off';

		?>
		<ul class="xs-donate-form-data-tabs xs-donate-metabox-tabs">
			<li class="form_field_options_tab active">
				<a href="#form_general_options">
					<span class="xs-donate-title-wraper">
						<span class="xs-donate-title"><?php echo esc_html__( 'General', 'wp-fundraising' ); ?></span>
						<span class="xs-donate-label"><?php echo esc_html__( 'Features or Elements', 'wp-fundraising' ); ?></span>
					</span>
					<span class="xs-donate-icon dashicons-before dashicons-admin-site"></span>
				</a>
			</li>

			<?php
			$wfpGetGoalGlobalOptions = isset( $getGlobalOptions['goal_setup']['enable'] ) ? $getGlobalOptions['goal_setup']['enable'] : 'No';
			if ( ! isset( $getGlobalOptionsGlo['options'] ) ) {
				$wfpGetGoalGlobalOptions = 'Yes';
			}
			if ( $wfpGetGoalGlobalOptions == 'Yes' ) :
				?>
				<li class="form_field_options_tab">
					<a href="#form_donate_goal_setup">
					<span class="xs-donate-title-wraper">
						<span class="xs-donate-title"><?php echo esc_html__( 'Goal Setup', 'wp-fundraising' ); ?></span>
						<span class="xs-donate-label"><?php echo esc_html__( 'Features or Elements', 'wp-fundraising' ); ?></span>
					</span>
						<span class="xs-donate-icon dashicons-before dashicons-admin-plugins"></span>
					</a>
				</li>
				<?php
			endif;
			$wfpGetPledgeGlobalOptions = isset( $getGlobalOptions['pledge_setup']['enable'] ) ? $getGlobalOptions['pledge_setup']['enable'] : 'No';
			if ( ! isset( $getGlobalOptionsGlo['options'] ) ) {
				$wfpGetPledgeGlobalOptions = 'Yes';
			}
			$wfpPledge = '';
			if ( $wfp_donation_format == 'crowdfunding' && $wfpGetPledgeGlobalOptions == 'Yes' ) :
				$wfpPledge = 'xs-donate-visible';
			endif;
			?>
			<li class="form_field_options_tab donation_target_type_filed pledge_setup_target xs-donate-hidden <?php echo esc_attr( $wfpPledge ); ?>">
				<a href="#form_donate_Pledge_setup">
					<span class="xs-donate-title-wraper">
						<span class="xs-donate-title"><?php echo esc_html__( 'Pledge Setup', 'wp-fundraising' ); ?></span>
						<span class="xs-donate-label"><?php echo esc_html__( 'Features or Elements', 'wp-fundraising' ); ?></span>
					</span>
					<span class="xs-donate-icon dashicons-before dashicons-sticky"></span>
				</a>
			</li>

			<li class="form_field_options_tab">
				<a href="#form_donate_form_terms_condition">
					<span class="xs-donate-title-wraper">
						<span class="xs-donate-title"><?php echo esc_html__( 'Terms & Condition', 'wp-fundraising' ); ?></span>
						<span class="xs-donate-label"><?php echo esc_html__( 'Features or Elements', 'wp-fundraising' ); ?></span>
					</span>
					<span class="xs-donate-icon dashicons-before dashicons-image-filter"></span>
				</a>
			</li>

			<li class="form_field_options_tab">
				<a href="#form_donate_form_content">
					<span class="xs-donate-title-wraper">
						<span class="xs-donate-title"><?php echo esc_html__( 'Form Content', 'wp-fundraising' ); ?></span>
						<span class="xs-donate-label"><?php echo esc_html__( 'Features or Elements', 'wp-fundraising' ); ?></span>
					</span>
					<span class="xs-donate-icon dashicons-before dashicons-admin-settings"></span>
				</a>
			</li>
			<li class="form_field_options_tab">
				<a href="#form_donate_form_settings">
					<span class="xs-donate-title-wraper">
						<span class="xs-donate-title"><?php echo esc_html__( 'Settings', 'wp-fundraising' ); ?></span>
						<span class="xs-donate-label"><?php echo esc_html__( 'Features or Elements', 'wp-fundraising' ); ?></span>
					</span>
					<span class="xs-donate-icon dashicons-before dashicons-admin-tools"></span>
				</a>
			</li>

			<?php

			if ( did_action( \WfpFundraising\Apps\Key::FUNDRAISING_PRO_LOADED ) ) {
				?>

				<li class="form_field_options_tab">
					<a href="#form_donate_pp_settings">
					<span class="xs-donate-title-wraper">
						<span class="xs-donate-title"><?php echo esc_html__( 'Payment accounts', 'wp-fundraising' ); ?></span>
						<span class="xs-donate-label"><?php echo esc_html__( 'Account settings to receive donation', 'wp-fundraising' ); ?></span>
					</span>
						<span class="xs-donate-icon dashicons-before dashicons-admin-tools"></span>
					</a>
				</li> 
				<?php
			}

			?>

		</ul>

		<div class="xs-donate-metabox-div">
			<!-- Start Donate Options Here-->

			<div class="xs-tab-div-disable xs-tab-content active" id="form_general_options">
				<?php
				// general options
				require __DIR__ . '/include/donations-general.php';
				?>
			</div>
			<!-- End Donate Options Here-->
			<!-- Start Donate From Design Here-->


			<!-- End Donate From Design Here-->
			<!-- Start Donate From Content Here-->
			<div class="xs-tab-div-disable xs-tab-content" id="form_donate_form_content">
				<?php
				$wfpFormContentData = isset( $wfpGetMetaData->form_content ) ? $wfpGetMetaData->form_content : (object) array(
					'enable'           => 'No',
					'content_position' => 'after-form',
				);

				$wfpMultiFiledData = isset( $wfpGetMetaData->form_content->additional->dimentions ) && sizeof( $wfpGetMetaData->form_content->additional->dimentions ) ? $wfpGetMetaData->form_content->additional->dimentions : \WfpFundraising\Apps\Settings::default_addition_filed();

				require __DIR__ . '/include/donations-form-content.php';
				?>
			</div>
			<!-- end Donate From Content Here-->
			<!-- Start Donate Goal Setup Here-->
			<div class="xs-tab-div-disable xs-tab-content" id="form_donate_goal_setup">
				<?php
				$wfpFormGoalData = isset( $wfpGetMetaData->goal_setup ) ? $wfpGetMetaData->goal_setup : (object) array(
					'enable'    => 'No',
					'goal_type' => 'goal_terget_amount',
				);
				require __DIR__ . '/include/donations-goal-setup.php';
				?>
			</div>
			<!-- end Donate Goal Setup Here-->

			<!-- Start Donate Goal Setup Here-->
			<div class="xs-tab-div-disable xs-tab-content" id="form_donate_Pledge_setup">
				<?php
				$wfpFormPledgeData = isset( $wfpGetMetaData->pledge_setup ) ? $wfpGetMetaData->pledge_setup : (object) array( 'enable' => 'No' );

				$wfpMultiPleData = isset( $wfpGetMetaData->pledge_setup->multi->dimentions ) && sizeof( $wfpGetMetaData->pledge_setup->multi->dimentions ) ? $wfpGetMetaData->pledge_setup->multi->dimentions : array(
					(object) array(
						'price'       => '1.00',
						'lebel'       => 'Basic',
						'description' => 'Basic Information',
					),
				);

				require __DIR__ . '/include/donations-pledge-setup.php';
				?>
			</div>
			<!-- end Donate Goal Setup Here-->
			<!-- Start Donate terms & Conditions Here-->
			<div class="xs-tab-div-disable xs-tab-content" id="form_donate_form_terms_condition">
				<?php
				// this data get from option of terms
				$wfpMetaTermsKey   = 'wfp_etrms_condition_options_data';
				$wfpGetMetaTermsOp = get_option( $wfpMetaTermsKey );
				$wfpGetMetaTerms   = isset( $wfpGetMetaTermsOp['form_terma'] ) ? json_decode( json_encode( $wfpGetMetaTermsOp['form_terma'] ) ) : (object) array(
					'enable'           => 'No',
					'content_position' => 'before-submit-button',
				);

				$wfpFormTermsData = isset( $wfpGetMetaData->form_terma ) ? $wfpGetMetaData->form_terma : $wfpGetMetaTerms;
				require __DIR__ . '/include/donations-terms-condition.php';

				?>

			</div>
			<!-- End Donate terms & Conditions Here-->

			<!-- Start Donate Settings Here-->
			<div class="xs-tab-div-disable xs-tab-content" id="form_donate_form_settings">
				<?php
				$wfpFormSettingData = isset( $wfpGetMetaData->form_settings ) ? $wfpGetMetaData->form_settings : array();

				$wfpGetContriGlobalOptions = isset( $getGlobalOptions['contributor_info']['enable'] ) ? $getGlobalOptions['contributor_info']['enable'] : 'No';

				if ( ! isset( $wfpGetMetaData->form_settings ) ) {
					$wfpGetContriGlobalOptions = 'No';
				}

				$wfpShowSidebarSett = empty( $globalDisplaySettings['form_settings']['sidebar']['enable'] ) ? 'No' : $globalDisplaySettings['form_settings']['sidebar']['enable'];
				$wfpEnableSidebar   = $wfpShowSidebarSett == 'Yes' ? ( isset( $wfpFormSettingData->sidebar->enable ) ? $wfpFormSettingData->sidebar->enable : 'No' ) : 'No';
				// Override from global - if global is turned off then this settings has no effect.


				$wfpHideFeaturedSett = empty( $globalDisplaySettings['form_settings']['featured']['enable'] ) ? 'No' : $globalDisplaySettings['form_settings']['featured']['enable'];
				$wfpEnableFeatured   = $wfpHideFeaturedSett == 'Yes' ? 'Yes' : ( isset( $wfpFormSettingData->featured->enable ) ? $wfpFormSettingData->featured->enable : 'No' );
				// Overriding by global - if global settings is turned on then then enable featured is always on - no way to disable it.

				$wfpHideSingleTitleSett = empty( $globalDisplaySettings['form_settings']['single_title']['enable'] ) ? 'No' : $globalDisplaySettings['form_settings']['single_title']['enable'];
				$wfpEnableTitleSIngle   = $wfpHideSingleTitleSett == 'Yes' ? 'Yes' : ( isset( $wfpFormSettingData->single_title->enable ) ? $wfpFormSettingData->single_title->enable : 'No' );


				$wfpHideShortBriefSett = empty( $globalDisplaySettings['form_settings']['single_excerpt']['enable'] ) ? 'No' : $globalDisplaySettings['form_settings']['single_excerpt']['enable'];
				$wfpEnableTitleExcerpt = $wfpHideShortBriefSett == 'Yes' ? 'Yes' : ( isset( $wfpFormSettingData->single_excerpt->enable ) ? $wfpFormSettingData->single_excerpt->enable : 'No' );

				$wfpHideDescriptionSett = empty( $globalDisplaySettings['form_settings']['single_content']['enable'] ) ? 'No' : $globalDisplaySettings['form_settings']['single_content']['enable'];
				$wfpEnableSingleContent = $wfpHideDescriptionSett == 'Yes' ? 'Yes' : ( isset( $wfpFormSettingData->single_content->enable ) ? $wfpFormSettingData->single_content->enable : 'No' );


				$wfpHideReviewTabSett  = empty( $globalDisplaySettings['form_settings']['single_review']['enable'] ) ? 'No' : $globalDisplaySettings['form_settings']['single_review']['enable'];
				$wfpEnableSingleReview = $wfpHideReviewTabSett == 'Yes' ? 'Yes' : ( isset( $wfpFormSettingData->single_review->enable ) ? $wfpFormSettingData->single_review->enable : 'No' );

				$wfpHideUpdateTabSett   = empty( $globalDisplaySettings['form_settings']['single_updates']['enable'] ) ? 'No' : $globalDisplaySettings['form_settings']['single_updates']['enable'];
				$wfpEnableSingleUpdates = $wfpHideUpdateTabSett == 'Yes' ? 'Yes' : ( isset( $wfpFormSettingData->single_updates->enable ) ? $wfpFormSettingData->single_updates->enable : 'No' );

				$wfpHideRecentFundTabSett = empty( $globalDisplaySettings['form_settings']['single_recents']['enable'] ) ? 'No' : $globalDisplaySettings['form_settings']['single_recents']['enable'];
				$wfpEnableSingleRecents   = $wfpHideRecentFundTabSett == 'Yes' ? 'Yes' : ( isset( $wfpFormSettingData->single_recents->enable ) ? $wfpFormSettingData->single_recents->enable : 'No' );

				$wfpShowContributorEmailSett = empty( $globalDisplaySettings['form_settings']['contributor']['enable'] ) ? 'No' : $globalDisplaySettings['form_settings']['contributor']['enable'];
				$wfpEnableContributorEmail   = $wfpShowContributorEmailSett == 'No' ? 'No' : ( isset( $wfpFormSettingData->contributor->enable ) ? $wfpFormSettingData->contributor->enable : $wfpGetContriGlobalOptions );

				$wfp_hide_campaign_author = isset( $wfpFormSettingData->campaign_author->enable ) ? $wfpFormSettingData->campaign_author->enable : 'No';


				require __DIR__ . '/include/donations-settings.php';

				?>
			</div>
			<!-- End Donate Settings Here-->

			<?php

			if ( did_action( \WfpFundraising\Apps\Key::FUNDRAISING_PRO_LOADED ) ) {

				$wfpMetaKey         = \WfpFundraising\Apps\Key::OK_PAYMENT_OPTIONS;
				$wfp_global_gateways = get_option( $wfpMetaKey, array() );

				$wfp_def_payment_arr = wfp_fundraising_payment_services();

				$wfp_current_user    = wp_get_current_user();
				$wfp_campaign_author = $post->post_status == 'auto-draft' ? $wfp_current_user->ID : $post->post_author;

				$wfp_pp_gate_ways = get_user_meta( $wfp_campaign_author, \WP_Fundraising_Pro\Keys::MK_PP_GATEWAY_SETTINGS, true );


				include \WFP_Fundraising::plugin_parent_dir() . 'wp-fundraising-donation-pro/views/admin/settings/pp-metabox-settings.php';
			}
			?>


		</div>

	</div>
	<!--Include short details of forms-->
	<?php require __DIR__ . '/meta-content/short-details.php'; ?>

	<!--Include recent donation of forms-->

</div>

