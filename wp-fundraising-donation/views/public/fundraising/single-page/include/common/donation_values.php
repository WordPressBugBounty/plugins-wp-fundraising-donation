<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

$wfp_cont = new \WfpFundraising\Apps\Content( false );

require \WFP_Fundraising::plugin_dir() . 'country-module/country-info.php';

/*currency information*/
$wfpMetaGeneralKey   = 'wfp_general_options_data';
$wfpGetMetaGeneralOp = get_option( $wfpMetaGeneralKey );
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


// payment method setup
$wfpMetaSetupKey     = 'wfp_setup_services_data';
$wfpGetSetUpData     = get_option( $wfpMetaSetupKey );
$wfpSetupData        = isset( $wfpGetSetUpData['services'] ) ? $wfpGetSetUpData['services'] : array();
$wfpGateCampaignData = isset( $wfpSetupData['payment'] ) ? $wfpSetupData['payment'] : 'default';

$wfpUrlCheckout = get_site_url() . '/wfp-checkout?wfpout=true';

if ( $wfpGateCampaignData == 'woocommerce' ) {

	$wfp_cart_url = wc_get_cart_url();

	$wfpUrlCheckout = $wfp_cart_url . '?wfpout=true&virtual=yes';
}

$wfpDefaultData   = 0;
$wfp_donation_type = isset( $wfpGetMetaData->donation->type ) ? $wfpGetMetaData->donation->type : 'multi-lebel';

$wfpFixedData = isset( $wfpGetMetaData->donation->fixed ) ? $wfpGetMetaData->donation->fixed : array();

$wfpMultiData = isset( $wfpGetMetaData->donation->multi->dimentions ) && sizeof( $wfpGetMetaData->donation->multi->dimentions ) ? $wfpGetMetaData->donation->multi->dimentions : array();

$wfpDisplayData   = isset( $wfpGetMetaData->donation->display ) ? $wfpGetMetaData->donation->display : 'boxed';
$wfpDonationLimit = isset( $wfpGetMetaData->donation->set_limit ) ? $wfpGetMetaData->donation->set_limit : 'No';

// form donation data
$wfpFormDonation = isset( $wfpGetMetaData->donation ) ? $wfpGetMetaData->donation : array();

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
$wfpGoalStatus     = 'Yes';
$wfpGoalDataAmount = 0;
$wfpGoalMessage    = '';

$wfp_campaign_status = 'Publish';

if ( isset( $wfpFormGoalData->enable ) ) {
	global $wpdb;
	$wfp_goal_type           = isset( $wfpFormGoalData->goal_type ) ? $wfpFormGoalData->goal_type : 'terget_goal';
	$wfp_total_rasied_amount = $wpdb->get_var( $wpdb->prepare( "SELECT SUM(donate_amount) FROM {$wpdb->prefix}wdp_fundraising WHERE form_id = %d AND status = 'Active'", $post->ID ) ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Prepared aggregate read for donation values campaign total.
	$wfp_total_rasied_count  = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(donate_id) FROM {$wpdb->prefix}wdp_fundraising WHERE form_id = %d AND status = 'Active'", $post->ID ) ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Prepared aggregate read for donation values count.

	$wfp_to_date            = gmdate( 'Y-m-d' );
	$wfp_time               = time();
	$wfp_persentange        = 0;
	$wfp_target_amount      = 0;
	$wfp_target_amount_fake = 0;
	$wfp_target_date        = gmdate( 'Y-m-d' );

	$wfp_total_rasied_amount_fake = $wfp_total_rasied_amount;
	$wfp_total_rasied_count_fake  = $wfp_total_rasied_count;

	if ( in_array(
		$wfp_goal_type,
		array(
			'terget_goal',
			'terget_goal_date',
			'campaign_never_end',
			'terget_date',
		)
	) ) {
		$wfp_target_amount      = isset( $wfpFormGoalData->terget->terget_goal->amount ) ? $wfpFormGoalData->terget->terget_goal->amount : 0;
		$wfp_target_amount_fake = isset( $wfpFormGoalData->terget->terget_goal->fake_amount ) ? $wfpFormGoalData->terget->terget_goal->fake_amount : 0;
		$wfp_target_date        = isset( $wfpFormGoalData->terget->terget_goal->date ) ? $wfpFormGoalData->terget->terget_goal->date : gmdate( 'Y-m-d' );

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

	$wfpGoalMessageEmable = isset( $wfpFormGoalData->terget->enable ) ? $wfpFormGoalData->terget->enable : 'No';
	$wfpGoalMessage       = isset( $wfpFormGoalData->terget->message ) ? $wfpFormGoalData->terget->message : '';


	update_post_meta( $post->ID, '__wfp_campaign_status', $wfp_campaign_status );
}

$wfpTermsContent = '';
if ( isset( $wfpFormTermsData->enable ) ) {
	$wfpTermsContent .= '<div class="xs-switch-button_wraper">
					<input type="checkbox" class="xs_donate_switch_button" name="xs-donate-terms-condition" id="xs-donate-terms-condition" value="Yes">
					<label class="xs_donate_switch_button_label small xs-round" for="xs-donate-terms-condition"></label><span class="xs-donate-terms-label">' . $wfpFormTermsData->level . '</span>
					<span class="xs-donate-terms"> ' . $wfpFormTermsData->content . ' </span>
				</div>';
}

$wfpModalHow = isset( $wfpFormDesignData->modal_show ) ? $wfpFormDesignData->modal_show : 'No';


if ( $wfp_form_styles == 'all_fields' ) {
	$wfp_modal_status = 'No';
}

if ( $wfp_format_style == 'single_donation' ) {
	$wfp_modal_status                      = 'Yes';
	$wfp_form_styles                       = 'no_button';
	$wfpFormContentData->content_position = 'no_content';
}

/*
 * AR[20200114]
 *
 */
$wfp_modal_status = isset( $wfp_atts['modal'] ) ? $wfp_atts['modal'] : $wfpModalHow;
$wfp_form_styles  = isset( $wfp_atts['form-style'] ) ? $wfp_atts['form-style'] : $wfpFormDesignData->styles;

// css code generate
$wfpContinueCOlor    = isset( $wfpFormDesignData->continue_color ) ? $wfpFormDesignData->continue_color : '#0085ba';
$wfpSubmitCOlor      = isset( $wfpFormDesignData->submit_color ) ? $wfpFormDesignData->submit_color : '#0085ba';
$wfpBarProgressCOlor = isset( $wfpFormGoalData->bar_color ) ? $wfpFormGoalData->bar_color : '#324aff';


// overriding these..........................................................................
$wfpCustomClass  = isset( $wfp_atts['class'] ) ? $wfp_atts['class'] : $wfpCustomClass;
$wfpCustomIdData = isset( $wfp_atts['id'] ) ? $wfp_atts['id'] : $wfpCustomIdData;
$wfp_format_style = isset( $wfp_atts['format-style'] ) ? $wfp_atts['format-style'] : $wfp_format_style;
// overriding ends...........................................................................

