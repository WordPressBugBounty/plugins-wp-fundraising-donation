<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

/*
 * AR[20200116]
 * In this file we must have $wfp_atts variable defined and not empty.
 */

$wfpFormDesignData = isset( $wfpGetMetaData->form_design ) ? $wfpGetMetaData->form_design : (object) array(
	'styles'          => 'all_fields',
	'continue_button' => 'Continue',
	'submit_button'   => 'Donate Now',
	'modal_show'      => 'No',
);

$wfpFromShCode = empty( $wfp_atts['is_short_code'] ) ? false : true;
$wfpFormId     = empty( $wfp_atts['form-id'] ) ? 0 : $wfp_atts['form-id'];

$wfp_form_id = isset( $wfp_atts['form-id'] ) ? intval( $wfp_atts['form-id'] ) : ( empty( $post->ID ) ? get_the_ID() : $post->ID );
$wfpPostId      = isset( $wfp_atts['form-id'] ) ? intval( $wfp_atts['form-id'] ) : ( empty( $post->ID ) ? get_the_ID() : $post->ID );

$wfpDonationTypeData = empty( $wfpGetMetaData->donation->format ) ? 'donation' : $wfpGetMetaData->donation->format;

$wfpShowInModal = ! empty( $wfp_atts['modal'] ) ? $wfp_atts['modal'] : ( empty( $wfpFormDesignData->modal_show ) ? 'No' : $wfpFormDesignData->modal_show );

$wfp_form_styles = ! empty( $wfp_atts['form-style'] ) ? $wfp_atts['form-style'] : $wfpFormDesignData->styles;

$wfp_modal_status = $wfpShowInModal;

$wfpPage_width = isset( $wfpGetMetaData->donation->page_width ) ? $wfpGetMetaData->donation->page_width : 0;

$wfpSymbols    = \WfpFundraising\Apps\Global_Settings::instance()->get_currency_symbol();
$wfp_cur_symbol = \WfpFundraising\Apps\Global_Settings::instance()->get_currency_code();


if ( $wfpDonationTypeData == 'donation' ) {

	if ( $wfpShowInModal == 'Yes' ) {

		if ( $wfp_form_styles == 'all_fields' ) {

			require \WFP_Fundraising::plugin_dir() . 'views/public/donation/donation-display-form-sm-all.php';

		} else {

			// Only_button

			require \WFP_Fundraising::plugin_dir() . 'views/public/donation/donation-display-form-sm-btn.php';
		}

		return;
	}

	require \WFP_Fundraising::plugin_dir() . 'views/public/donation/donation-display-form-sn.php';

	return;
}


/*
 * Below code is for crowd-funding type.
 *
 */
$wfp_cont = new \WfpFundraising\Apps\Content( false );

require \WFP_Fundraising::plugin_dir() . 'country-module/country-info.php';

/*currency information*/
$wfpGetMetaGeneralOp = get_option( \WfpFundraising\Apps\Settings::OK_GENERAL_DATA );
$wfpGetMetaGeneral   = isset( $wfpGetMetaGeneralOp['options'] ) ? $wfpGetMetaGeneralOp['options'] : array();

$wfpDefaultCurrencyInfo = isset( $wfpGetMetaGeneral['currency']['name'] ) ? $wfpGetMetaGeneral['currency']['name'] : 'US-USD';
$wfpExplCurr            = explode( '-', $wfpDefaultCurrencyInfo );
$wfpCurrCode            = isset( $wfpExplCurr[1] ) ? $wfpExplCurr[1] : 'USD';
$wfpCountCode           = isset( $wfpExplCurr[0] ) ? $wfpExplCurr[0] : 'US';
$wfpSymbols             = isset( $wfpCountryList[ $wfpCountCode ]['currency']['symbol'] ) ? $wfpCountryList[ $wfpCountCode ]['currency']['symbol'] : '';
$wfpSymbols             = strlen( $wfpSymbols ) > 0 ? $wfpSymbols : $wfpCurrCode;


$wfpSymbols = apply_filters( 'wfp_fundraising_donate_amount_symbol', $wfpSymbols, $wfpCountryList, $wfpCountCode );

$wfp_defaultThou_seperator = isset( $wfpGetMetaGeneral['currency']['thou_seperator'] ) ? $wfpGetMetaGeneral['currency']['thou_seperator'] : ',';

$wfp_defaultDecimal_seperator = isset( $wfpGetMetaGeneral['currency']['decimal_seperator'] ) ? $wfpGetMetaGeneral['currency']['decimal_seperator'] : '.';

$wfpDefaultNumberDecimal = isset( $wfpGetMetaGeneral['currency']['number_decimal'] ) ? $wfpGetMetaGeneral['currency']['number_decimal'] : '2';
if ( $wfpDefaultNumberDecimal < 0 ) {
	$wfpDefaultNumberDecimal = 0;
}

$wfp_defaultUse_space = isset( $wfpGetMetaGeneral['currency']['use_space'] ) ? $wfpGetMetaGeneral['currency']['use_space'] : 'off';

/*Custom class design data*/
$wfpCustomClass  = isset( $wfpGetMetaData->form_design->custom_class ) ? $wfpGetMetaData->form_design->custom_class : '';
$wfpCustomIdData = isset( $wfpGetMetaData->form_design->custom_id ) ? $wfpGetMetaData->form_design->custom_id : '';

$wfpCustomClass  = isset( $wfp_atts['class'] ) ? $wfp_atts['class'] : $wfpCustomClass;
$wfpCustomIdData = isset( $wfp_atts['id'] ) ? $wfp_atts['id'] : $wfpCustomIdData;

$wfp_format_style = isset( $wfp_atts['format-style'] ) ? $wfp_atts['format-style'] : $wfp_format_style;

// payment method setup
$wfpMetaSetupKey = 'wfp_setup_services_data';
$wfpGetSetUpData = get_option( $wfpMetaSetupKey );
$wfpSetupData    = isset( $wfpGetSetUpData['services'] ) ? $wfpGetSetUpData['services'] : array();
$wfpPaymentType  = \WfpFundraising\Apps\Global_Settings::instance()->get_payment_type();

$wfpUrlCheckout = get_site_url() . '/wfp-checkout?wfpout=true';

if ( $wfpPaymentType == 'woocommerce' ) {

	$wfp_cart_url = wc_get_cart_url();

	$wfpUrlCheckout = $wfp_cart_url . '?wfpout=true&virtual=yes';
}

$wfpDefaultData = 0;

$wfp_donation_type = isset( $wfpGetMetaData->donation->type ) ? $wfpGetMetaData->donation->type : 'multi-lebel';

$wfpFixedData = isset( $wfpGetMetaData->donation->fixed ) ? $wfpGetMetaData->donation->fixed : array();

$wfpMultiData = isset( $wfpGetMetaData->donation->multi->dimentions ) && sizeof( $wfpGetMetaData->donation->multi->dimentions ) ? $wfpGetMetaData->donation->multi->dimentions : array();

$wfpDisplayData   = isset( $wfpGetMetaData->donation->display ) ? $wfpGetMetaData->donation->display : 'boxed';
$wfpDonationLimit = isset( $wfpGetMetaData->donation->set_limit ) ? $wfpGetMetaData->donation->set_limit : 'No';

// form donation data
$wfpFormDonation = isset( $wfpGetMetaData->donation ) ? $wfpGetMetaData->donation : array();

// form design data
$wfpFormDesignData = isset( $wfpGetMetaData->form_design ) ? $wfpGetMetaData->form_design : (object) array(
	'styles'          => 'all_fields',
	'continue_button' => 'Continue',
	'submit_button'   => 'Donate Now',
);

// form content data
$wfpFormContentData = isset( $wfpGetMetaData->form_content ) ? $wfpGetMetaData->form_content : (object) array(
	'enable'           => 'No',
	'content_position' => 'after-form',
);

// form goal data
$wfpFormGoalData = isset( $wfpGetMetaData->goal_setup ) ? $wfpGetMetaData->goal_setup : (object) array(
	'enable'    => 'No',
	'goal_type' => 'goal_terget_amount',
);

// form terms data
$wfpFormTermsData = isset( $wfpGetMetaData->form_terma ) ? $wfpGetMetaData->form_terma : (object) array(
	'enable'           => 'No',
	'content_position' => 'after-submit-button',
);

$wfpAdd_fees = isset( $wfpGetMetaData->donation->set_add_fees ) ? $wfpGetMetaData->donation->set_add_fees : (object) array(
	'enable'      => 'No',
	'fees_amount' => 0,
);

// target goal check
$wfpGoalStatus      = 'Yes';
$wfp_campaign_status = 'Publish';
$wfpGoalDataAmount  = 0;

$wfpGoalMessageEmable = isset( $wfpFormGoalData->terget->enable ) ? $wfpFormGoalData->terget->enable : 'No';
$wfpGoalMessage       = isset( $wfpFormGoalData->terget->message ) ? $wfpFormGoalData->terget->message : '';
$wfp_goal_type         = isset( $wfpFormGoalData->goal_type ) ? $wfpFormGoalData->goal_type : 'terget_goal';

$wfp_persentange        = 0;
$wfp_target_amount      = 0;
$wfp_target_amount_fake = 0;
$wfp_target_date        = gmdate( 'Y-m-d' );
$wfp_time               = time();
$wfp_to_date            = gmdate( 'Y-m-d' );

if ( isset( $wfpFormGoalData->enable ) ) {
	global $wpdb;
	$wfp_total_rasied_amount = $wpdb->get_var( $wpdb->prepare( "SELECT SUM(donate_amount) FROM {$wpdb->prefix}wdp_fundraising WHERE form_id = %d AND status = 'Active'", $post->ID ) ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Prepared aggregate read for campaign totals in donation shortcode rendering.
	$wfp_total_rasied_count  = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(donate_id) FROM {$wpdb->prefix}wdp_fundraising WHERE form_id = %d AND status = 'Active'", $post->ID ) ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Prepared aggregate read for campaign donor count in donation shortcode rendering.

	$wfp_total_rasied_amount_fake = $wfp_total_rasied_amount;
	$wfp_total_rasied_count_fake  = $wfp_total_rasied_count;

	if ( in_array( $wfp_goal_type, array( 'terget_goal', 'terget_goal_date', 'campaign_never_end', 'terget_date' ) ) ) {
		$wfp_target_amount      = isset( $wfpFormGoalData->terget->terget_goal->amount ) ? $wfpFormGoalData->terget->terget_goal->amount : 0;
		$wfp_target_amount_fake = isset( $wfpFormGoalData->terget->terget_goal->fake_amount ) ? $wfpFormGoalData->terget->terget_goal->fake_amount : 0;
		$wfp_target_date        = isset( $wfpFormGoalData->terget->terget_goal->date ) ? $wfpFormGoalData->terget->terget_goal->date : $wfp_target_date;

		$wfp_target_time = strtotime( $wfp_target_date );

		$wfp_total_rasied_amount_fake = $wfp_total_rasied_amount + $wfp_target_amount_fake;
		// check amount with data
		if ( $wfp_total_rasied_amount_fake >= $wfp_target_amount ) {
			$wfp_total_rasied_amount_fake = $wfp_total_rasied_amount;
		}
		if ( $wfp_target_amount > 0 ) {
			$wfp_persentange = ( $wfp_total_rasied_amount_fake * 100 ) / $wfp_target_amount;
		}

		if ( $wfp_total_rasied_amount >= $wfp_target_amount ) {
			$wfpGoalStatus = 'No';
		}
		if ( $wfp_goal_type == 'terget_goal_date' || $wfp_goal_type == 'terget_date' ) {
			if ( $wfp_time > $wfp_target_time ) {
				$wfpGoalStatus = 'No';
			}
		} elseif ( $wfp_goal_type == 'campaign_never_end' ) {
			$wfpGoalStatus = 'Yes';
		}
	}

	$wfp_campaign_status = ( $wfpGoalStatus == 'Yes' ) ? 'Publish' : 'Ends';


	update_post_meta( $post->ID, '__wfp_campaign_status', $wfp_campaign_status );
}


$wfpTermsContent = '';
if ( isset( $wfpFormTermsData->enable ) ) {
	$wfp_level = isset( $wfpFormTermsData->level ) ? $wfpFormTermsData->level : '';
    $wfp_content = isset( $wfpFormTermsData->content ) ? $wfpFormTermsData->content : '';
	$wfpTermsContent .= '<div class="xs-switch-button_wraper">
					<input type="checkbox" class="xs_donate_switch_button" name="xs-donate-terms-condition" id="xs-donate-terms-condition" value="Yes">
					<label class="xs_donate_switch_button_label small xs-round" for="xs-donate-terms-condition"></label><span class="xs-donate-terms-label">' . $wfp_level . '</span>
					<span class="xs-donate-terms"> ' . $wfp_content . ' </span>
				</div>';
}

$wfpModalHow = isset( $wfpFormDesignData->modal_show ) ? $wfpFormDesignData->modal_show : 'No';

$wfp_form_styles = isset( $wfp_atts['form-style'] ) ? $wfp_atts['form-style'] : $wfpFormDesignData->styles;

$wfp_modal_status = isset( $wfp_atts['modal'] ) ? $wfp_atts['modal'] : $wfpModalHow;

if ( $wfp_form_styles == 'all_fields' ) {
	$wfp_modal_status = 'No';
}

if ( $wfp_format_style == 'single_donation' ) {
	$wfp_modal_status                      = 'Yes';
	$wfp_form_styles                       = 'no_button';
	$wfpFormContentData->content_position = 'no_content';
}

// css code generate
$wfpContinueCOlor    = isset( $wfpFormDesignData->continue_color ) ? $wfpFormDesignData->continue_color : '#0085ba';
$wfpSubmitCOlor      = isset( $wfpFormDesignData->submit_color ) ? $wfpFormDesignData->submit_color : '#0085ba';
$wfpBarProgressCOlor = isset( $wfpFormGoalData->bar_color ) ? $wfpFormGoalData->bar_color : '#324aff';


$wfpFormSetting = isset( $wfpGetMetaData->form_settings ) ? $wfpGetMetaData->form_settings : array();

$wfpEnableSingleContent = isset( $wfpFormSetting->single_content->enable ) ? $wfpFormSetting->single_content->enable : 'No';
$wfpEnableSingleContent = apply_filters( 'wfp_single_content_decription_hide', $wfpEnableSingleContent );

$wfpEnableSingleReview = isset( $wfpFormSetting->single_review->enable ) ? $wfpFormSetting->single_review->enable : 'No';
$wfpEnableSingleReview = apply_filters( 'wfp_single_content_review_hide', $wfpEnableSingleReview );

$wfpEnableSingleUpdates = isset( $wfpFormSetting->single_updates->enable ) ? $wfpFormSetting->single_updates->enable : 'No';
$wfpEnableSingleUpdates = apply_filters( 'wfp_single_content_updates_hide', $wfpEnableSingleUpdates );

$wfpEnableSingleRecents = isset( $wfpFormSetting->single_recents->enable ) ? $wfpFormSetting->single_recents->enable : 'No';
$wfpEnableSingleRecents = apply_filters( 'wfp_single_content_recent_hide', $wfpEnableSingleRecents );

$wfpEnableSingleContributor = isset( $wfpFormSetting->contributor->enable ) ? $wfpFormSetting->contributor->enable : 'No';
$wfpEnableSingleContributor = apply_filters( 'wfp_single_content_contributor_hide', $wfpEnableSingleContributor );

$wfpMultiPleData = isset( $wfpGetMetaData->pledge_setup->multi->dimentions ) && sizeof( $wfpGetMetaData->pledge_setup->multi->dimentions ) ? $wfpGetMetaData->pledge_setup->multi->dimentions : array();

$wfpPostContent = $post->post_content;

$wfpPage_width = isset( $wfpGetMetaData->donation->page_width ) ? $wfpGetMetaData->donation->page_width : 0;

?>

<div class="wfp-container xs-wfp-crowd" style="<?php echo $wfpPage_width <= 0 ? '' : 'width:' . esc_attr( $wfpPage_width ) . 'px;'; ?>">
	<div class="wfp-view wfp-view-public">
		<section id="main-content" class="wfp-single-page" role="main">
			<div class="xs-container">
				<div class="">
					<div class="">
						<div class="wfp-entry-content">
							<article id="post-<?php echo esc_attr( $post->ID ); ?>"
									 class="post-<?php echo esc_attr( $post->ID ); ?> <?php echo esc_attr( join( ' ', get_post_class( '', $post->ID ) ) ); ?>"
									 wfp-data-url="<?php echo esc_url( add_query_arg( 'wpf_checkout_nonce_field', wp_create_nonce( 'wpf_checkout' ), $wfpUrlCheckout ) ); ?>"
									 wfp-payment-type="<?php echo esc_html( $wfpPaymentType ); ?>">

								<div class="wfp_wraper_con">

									<?php require __DIR__ . '/include/header-title-crowdfund-sh.php'; ?>

									<div class="xs-row">
										<div class="xs-col-sm-12 xs-col-md-6 wfp-single-item">

											<?php require __DIR__ . '/include/featured-gal-crowd-sh.php'; ?>

											<?php require __DIR__ . '/include/post-body-crowd-sh.php'; ?>

										</div>

										<div class="xs-col-sm-12 xs-col-md-6  wfp-single-item">
											<div class="wfp-goal-wraper">

												<?php

												// backers information
												require __DIR__ . '/../fundraising/single-page/include/page/backers-crowd-sh.php';

												// social share
												require __DIR__ . '/../fundraising/single-page/include/page/social.php';

												// post author info
												require __DIR__ . '/../fundraising/single-page/include/page/user-info.php';

												?>

											</div>
										</div>
									</div>

									<div class="wfp-bar-section">
										<hr class="wfp-bar-content">
									</div>

									<div class="xs-row">
										<?php

										$wfpRecentTitle = __( 'Recent Funds', 'wp-fundraising' );

										$wfpArgsTotal = array(
											'post_type'   => 'wfp-review',
											'post_parent' => $wfpPostId,
											'post_status' => 'publish',
										);

										$wfp_the_queryTotal = new \WP_Query( $wfpArgsTotal );
										$wfp_count          = $wfp_the_queryTotal->post_count;
										wp_reset_postdata();
										?>
										<div class="xs-col-lg-8 wfp-single-tabs">
											<ul class="wfp-tab" id="wfp_menu_fixed">
												<?php if ( $wfpEnableSingleContent == 'No' ) : ?>
													<li class="wfp_tab_li active"><a
																href="#wfp_tab_content_decription"><?php echo esc_html( apply_filters( 'wfp_single_content_decription', esc_html__( 'Description', 'wp-fundraising' ) ) ); ?></a>
													</li>
													<?php
												endif;
												if ( $wfpEnableSingleReview == 'No' ) :
													?>
													<li class="wfp_tab_li "><a
																href="#wfp_tab_content_review"><?php echo esc_html( apply_filters( 'wfp_single_content_review', esc_html__( 'Reviews', 'wp-fundraising' ) ) ); ?>
															(<?php echo esc_html( $wfp_count ); ?>)</a></li>
													<?php
												endif;
												if ( $wfpEnableSingleUpdates == 'No' ) :
													?>
													<li class="wfp_tab_li "><a
																href="#wfp_tab_content_updates"><?php echo esc_html( apply_filters( 'wfp_single_content_updates', esc_html__( 'Updates', 'wp-fundraising' ) ) ); ?></a>
													</li>
													<?php
												endif;
												if ( $wfpEnableSingleRecents == 'No' ) :
													?>
													<li class="wfp_tab_li "><a
																href="#wfp_tab_content_recent"><?php echo esc_html( apply_filters( 'wfp_single_content_recent', esc_html( $wfpRecentTitle ) ) ); ?></a>
													</li>
												<?php endif; ?>
											</ul>

											<div class="wfp-tab-content-wraper">
												<?php if ( $wfpEnableSingleContent == 'No' ) : ?>
													<div class="wfp-tab-content wfp-tab-div-disable active"
														 id="wfp_tab_content_decription">
														<div class="wfp-post-description">
															<?php

															do_action( 'wfp_single_content_before' );

															echo wp_kses( $wfpPostContent, \WfpFundraising\Utilities\Utils::get_kses_array() );

															do_action( 'wfp_single_content_after' );

															?>
														</div>
													</div>
													<!-- Article content -->
													<?php
												endif;
												if ( $wfpEnableSingleReview == 'No' ) :
													?>
													<div class="wfp-tab-content wfp-tab-div-disable "
														 id="wfp_tab_content_review">
														<?php include __DIR__ . '/../fundraising/single-page/include/page/review.php'; ?>
													</div>
													<?php
												endif;
												if ( $wfpEnableSingleUpdates == 'No' ) :
													?>
													<div class="wfp-tab-content wfp-tab-div-disable " id="wfp_tab_content_updates">
														<?php include __DIR__ . '/../fundraising/single-page/include/page/updates.php'; ?>
													</div>
													<?php
												endif;
												if ( $wfpEnableSingleRecents == 'No' ) :
													?>
													<div class="wfp-tab-content wfp-tab-div-disable "
														 id="wfp_tab_content_recent">
														<?php include __DIR__ . '/../fundraising/single-page/include/page/recent.php'; ?>
													</div>
												<?php endif; ?>
											</div>
										</div>

										<div class="xs-col-lg-4 wfp-single-pledges">
											<?php

											if ( \WfpFundraising\Apps\Form_Settings::instance( $wfp_form_id )->is_pledge_enabled() ) {
												include __DIR__ . '/../fundraising/single-page/include/page/pledge.php';
											}
											?>
										</div>
									</div>
								</div>
							</article>
						</div>

					</div>
				</div>

			</div>
		</section>
	</div>

	<script type='text/javascript'>
		xs_donate_amount_set(<?php echo esc_html( $wfpDefaultData ); ?>,<?php echo esc_html( $post->ID ); ?>);
	</script>

</div>
