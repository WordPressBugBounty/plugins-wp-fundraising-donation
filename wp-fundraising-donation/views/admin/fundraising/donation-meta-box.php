<div class="wfp-view wfp-view-admin">
	<div class="xs-donate-metabox-panel-wrap xs_shadow_card">
		<?php
		require \WFP_Fundraising::plugin_dir() . 'country-module/country-info.php';

		// get setup data
		$metaSetupKey = 'wfp_setup_services_data';
		$getSetUpData = get_option( $metaSetupKey );
		$setupData    = isset( $getSetUpData['services'] ) ? $getSetUpData['services'] : array();

		// get type of founding
		$foundingTyepe = isset( $setupData['campaign'] ) ? $setupData['campaign'] : 'donation';

		$donation_format = isset( $getMetaData->donation->format ) ? $getMetaData->donation->format : $foundingTyepe;

		$donation_type = isset( $getMetaData->donation->type ) ? $getMetaData->donation->type : 'multi-lebel';

		$fixedData = isset( $getMetaData->donation->fixed ) ? $getMetaData->donation->fixed : array();

		$multiData = isset( $getMetaData->donation->multi->dimentions ) && sizeof( $getMetaData->donation->multi->dimentions ) ? $getMetaData->donation->multi->dimentions : array(
			(object) array(
				'price' => '1.00',
				'lebel' => 'Basic',
			),
		);

		$displayData = isset( $getMetaData->donation->display ) ? $getMetaData->donation->display : 'boxed';

		$donationLimit = isset( $getMetaData->donation->set_limit ) ? $getMetaData->donation->set_limit : array();

		$add_fees = isset( $getMetaData->donation->set_add_fees ) ? $getMetaData->donation->set_add_fees : array();

		$page_width = isset( $getMetaData->donation->page_width ) ? $getMetaData->donation->page_width : 0;

		/*currency information*/
		$metaGeneralKey   = 'wfp_general_options_data';
		$getMetaGeneralOp = get_option( $metaGeneralKey );
		$getMetaGeneral   = isset( $getMetaGeneralOp['options'] ) ? $getMetaGeneralOp['options'] : array();

		$defaultCurrencyInfo = isset( $getMetaGeneral['currency']['name'] ) ? $getMetaGeneral['currency']['name'] : 'US-USD';
		$explCurr            = explode( '-', $defaultCurrencyInfo );
		$currCode            = isset( $explCurr[1] ) ? $explCurr[1] : 'USD';
		$symbols             = isset( $countryList[ current( $explCurr ) ]['currency']['symbol'] ) ? $countryList[ current( $explCurr ) ]['currency']['symbol'] : '';
		$symbols             = strlen( $symbols ) > 0 ? $symbols : $currCode;

		$defaultUse_space = isset( $getMetaGeneral['currency']['use_space'] ) ? $getMetaGeneral['currency']['use_space'] : 'off';

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
			$getGoalGlobalOptions = isset( $getGlobalOptions['goal_setup']['enable'] ) ? $getGlobalOptions['goal_setup']['enable'] : 'No';
			if ( ! isset( $getGlobalOptionsGlo['options'] ) ) {
				$getGoalGlobalOptions = 'Yes';
			}
			if ( $getGoalGlobalOptions == 'Yes' ) :
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
			$getPledgeGlobalOptions = isset( $getGlobalOptions['pledge_setup']['enable'] ) ? $getGlobalOptions['pledge_setup']['enable'] : 'No';
			if ( ! isset( $getGlobalOptionsGlo['options'] ) ) {
				$getPledgeGlobalOptions = 'Yes';
			}
			$pledge = '';
			if ( $donation_format == 'crowdfunding' && $getPledgeGlobalOptions == 'Yes' ) :
				$pledge = 'xs-donate-visible';
			endif;
			?>
			<li class="form_field_options_tab donation_target_type_filed pledge_setup_target xs-donate-hidden <?php echo esc_attr( $pledge ); ?>">
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
				$formContentData = isset( $getMetaData->form_content ) ? $getMetaData->form_content : (object) array(
					'enable'           => 'No',
					'content_position' => 'after-form',
				);

				$multiFiledData = isset( $getMetaData->form_content->additional->dimentions ) && sizeof( $getMetaData->form_content->additional->dimentions ) ? $getMetaData->form_content->additional->dimentions : \WfpFundraising\Apps\Settings::default_addition_filed();

				require __DIR__ . '/include/donations-form-content.php';
				?>
			</div>
			<!-- end Donate From Content Here-->
			<!-- Start Donate Goal Setup Here-->
			<div class="xs-tab-div-disable xs-tab-content" id="form_donate_goal_setup">
				<?php
				$formGoalData = isset( $getMetaData->goal_setup ) ? $getMetaData->goal_setup : (object) array(
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
				$formPledgeData = isset( $getMetaData->pledge_setup ) ? $getMetaData->pledge_setup : (object) array( 'enable' => 'No' );

				$multiPleData = isset( $getMetaData->pledge_setup->multi->dimentions ) && sizeof( $getMetaData->pledge_setup->multi->dimentions ) ? $getMetaData->pledge_setup->multi->dimentions : array(
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
				$metaTermsKey   = 'wfp_etrms_condition_options_data';
				$getMetaTermsOp = get_option( $metaTermsKey );
				$getMetaTerms   = isset( $getMetaTermsOp['form_terma'] ) ? json_decode( json_encode( $getMetaTermsOp['form_terma'] ) ) : (object) array(
					'enable'           => 'No',
					'content_position' => 'before-submit-button',
				);

				$formTermsData = isset( $getMetaData->form_terma ) ? $getMetaData->form_terma : $getMetaTerms;
				require __DIR__ . '/include/donations-terms-condition.php';

				?>

			</div>
			<!-- End Donate terms & Conditions Here-->

			<!-- Start Donate Settings Here-->
			<div class="xs-tab-div-disable xs-tab-content" id="form_donate_form_settings">
				<?php
				$formSettingData = isset( $getMetaData->form_settings ) ? $getMetaData->form_settings : array();

				$getContriGlobalOptions = isset( $getGlobalOptions['contributor_info']['enable'] ) ? $getGlobalOptions['contributor_info']['enable'] : 'No';

				if ( ! isset( $getMetaData->form_settings ) ) {
					$getContriGlobalOptions = 'No';
				}

				$showSidebarSett = empty( $globalDisplaySettings['form_settings']['sidebar']['enable'] ) ? 'No' : $globalDisplaySettings['form_settings']['sidebar']['enable'];
				$enableSidebar   = $showSidebarSett == 'Yes' ? ( isset( $formSettingData->sidebar->enable ) ? $formSettingData->sidebar->enable : 'No' ) : 'No';
				// Override from global - if global is turned off then this settings has no effect.


				$hideFeaturedSett = empty( $globalDisplaySettings['form_settings']['featured']['enable'] ) ? 'No' : $globalDisplaySettings['form_settings']['featured']['enable'];
				$enableFeatured   = $hideFeaturedSett == 'Yes' ? 'Yes' : ( isset( $formSettingData->featured->enable ) ? $formSettingData->featured->enable : 'No' );
				// Overriding by global - if global settings is turned on then then enable featured is always on - no way to disable it.

				$hideSingleTitleSett = empty( $globalDisplaySettings['form_settings']['single_title']['enable'] ) ? 'No' : $globalDisplaySettings['form_settings']['single_title']['enable'];
				$enableTitleSIngle   = $hideSingleTitleSett == 'Yes' ? 'Yes' : ( isset( $formSettingData->single_title->enable ) ? $formSettingData->single_title->enable : 'No' );


				$hideShortBriefSett = empty( $globalDisplaySettings['form_settings']['single_excerpt']['enable'] ) ? 'No' : $globalDisplaySettings['form_settings']['single_excerpt']['enable'];
				$enableTitleExcerpt = $hideShortBriefSett == 'Yes' ? 'Yes' : ( isset( $formSettingData->single_excerpt->enable ) ? $formSettingData->single_excerpt->enable : 'No' );

				$hideDescriptionSett = empty( $globalDisplaySettings['form_settings']['single_content']['enable'] ) ? 'No' : $globalDisplaySettings['form_settings']['single_content']['enable'];
				$enableSingleContent = $hideDescriptionSett == 'Yes' ? 'Yes' : ( isset( $formSettingData->single_content->enable ) ? $formSettingData->single_content->enable : 'No' );


				$hideReviewTabSett  = empty( $globalDisplaySettings['form_settings']['single_review']['enable'] ) ? 'No' : $globalDisplaySettings['form_settings']['single_review']['enable'];
				$enableSingleReview = $hideReviewTabSett == 'Yes' ? 'Yes' : ( isset( $formSettingData->single_review->enable ) ? $formSettingData->single_review->enable : 'No' );

				$hideUpdateTabSett   = empty( $globalDisplaySettings['form_settings']['single_updates']['enable'] ) ? 'No' : $globalDisplaySettings['form_settings']['single_updates']['enable'];
				$enableSingleUpdates = $hideUpdateTabSett == 'Yes' ? 'Yes' : ( isset( $formSettingData->single_updates->enable ) ? $formSettingData->single_updates->enable : 'No' );

				$hideRecentFundTabSett = empty( $globalDisplaySettings['form_settings']['single_recents']['enable'] ) ? 'No' : $globalDisplaySettings['form_settings']['single_recents']['enable'];
				$enableSingleRecents   = $hideRecentFundTabSett == 'Yes' ? 'Yes' : ( isset( $formSettingData->single_recents->enable ) ? $formSettingData->single_recents->enable : 'No' );

				$showContributorEmailSett = empty( $globalDisplaySettings['form_settings']['contributor']['enable'] ) ? 'No' : $globalDisplaySettings['form_settings']['contributor']['enable'];
				$enableContributorEmail   = $showContributorEmailSett == 'No' ? 'No' : ( isset( $formSettingData->contributor->enable ) ? $formSettingData->contributor->enable : $getContriGlobalOptions );

				$hide_campaign_author = isset( $formSettingData->campaign_author->enable ) ? $formSettingData->campaign_author->enable : 'No';


				require __DIR__ . '/include/donations-settings.php';

				?>
			</div>
			<!-- End Donate Settings Here-->

			<?php

			if ( did_action( \WfpFundraising\Apps\Key::FUNDRAISING_PRO_LOADED ) ) {

				$metaKey         = \WfpFundraising\Apps\Key::OK_PAYMENT_OPTIONS;
				$global_gateways = get_option( $metaKey, array() );

				$def_payment_arr = xs_payment_services();

				$current_user    = wp_get_current_user();
				$campaign_author = $post->post_status == 'auto-draft' ? $current_user->ID : $post->post_author;

				$pp_gate_ways = get_user_meta( $campaign_author, \WP_Fundraising_Pro\Keys::MK_PP_GATEWAY_SETTINGS, true );


				include \WFP_Fundraising::plugin_parent_dir() . 'wp-fundraising-donation-pro/views/admin/settings/pp-metabox-settings.php';
			}
			?>


		</div>

	</div>
	<!--Include short details of forms-->
	<?php require __DIR__ . '/meta-content/short-details.php'; ?>

	<!--Include recent donation of forms-->

</div>

