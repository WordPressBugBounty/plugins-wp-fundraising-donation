<?php

$cont = new \WfpFundraising\Apps\Content( false );

require \WFP_Fundraising::plugin_dir() . 'country-module/country-info.php';

/*currency information*/
$metaGeneralKey   = 'wfp_general_options_data';
$getMetaGeneralOp = get_option( $metaGeneralKey );
$getMetaGeneral   = isset( $getMetaGeneralOp['options'] ) ? $getMetaGeneralOp['options'] : array();

$defaultCurrencyInfo = isset( $getMetaGeneral['currency']['name'] ) ? $getMetaGeneral['currency']['name'] : 'US-USD';
$explCurr            = explode( '-', $defaultCurrencyInfo );
$currCode            = isset( $explCurr[1] ) ? $explCurr[1] : 'USD';
$countCode           = isset( $explCurr[0] ) ? $explCurr[0] : 'US';
$symbols             = isset( $countryList[ $countCode ]['currency']['symbol'] ) ? $countryList[ $countCode ]['currency']['symbol'] : '';
$symbols             = strlen( $symbols ) > 0 ? $symbols : $currCode;


$symbols = apply_filters( 'wfp_donate_amount_symbol', $symbols, $countryList, $countCode );

$defaultThou_seperator = isset( $getMetaGeneral['currency']['thou_seperator'] ) ? $getMetaGeneral['currency']['thou_seperator'] : ',';

$defaultDecimal_seperator = isset( $getMetaGeneral['currency']['decimal_seperator'] ) ? $getMetaGeneral['currency']['decimal_seperator'] : '.';

$defaultNumberDecimal = isset( $getMetaGeneral['currency']['number_decimal'] ) ? $getMetaGeneral['currency']['number_decimal'] : '2';
if ( $defaultNumberDecimal < 0 ) {
	$defaultNumberDecimal = 0;
}

$defaultUse_space = isset( $getMetaGeneral['currency']['use_space'] ) ? $getMetaGeneral['currency']['use_space'] : 'off';

/*Custom class design data*/
$customClass  = isset( $getMetaData->form_design->custom_class ) ? $getMetaData->form_design->custom_class : '';
$customIdData = isset( $getMetaData->form_design->custom_id ) ? $getMetaData->form_design->custom_id : '';


// payment method setup
$metaSetupKey     = 'wfp_setup_services_data';
$getSetUpData     = get_option( $metaSetupKey );
$setupData        = isset( $getSetUpData['services'] ) ? $getSetUpData['services'] : array();
$gateCampaignData = isset( $setupData['payment'] ) ? $setupData['payment'] : 'default';

$urlCheckout = get_site_url() . '/wfp-checkout?wfpout=true';

if ( $gateCampaignData == 'woocommerce' ) {

	$cart_url = wc_get_cart_url();

	$urlCheckout = $cart_url . '?wfpout=true&virtual=yes';
}

$optionsData      = isset( $gateWaysData['services'] ) ? $gateWaysData['services'] : array();
$payment_settings = \WfpFundraising\Apps\Settings::instance()->get_active_payment_settings( $postId, $optionsData );

$defaultData   = 0;
$donation_type = isset( $getMetaData->donation->type ) ? $getMetaData->donation->type : 'multi-lebel';

$fixedData = isset( $getMetaData->donation->fixed ) ? $getMetaData->donation->fixed : array();

$multiData = isset( $getMetaData->donation->multi->dimentions ) && sizeof( $getMetaData->donation->multi->dimentions ) ? $getMetaData->donation->multi->dimentions : array();

$displayData   = isset( $getMetaData->donation->display ) ? $getMetaData->donation->display : 'boxed';
$donationLimit = isset( $getMetaData->donation->set_limit ) ? $getMetaData->donation->set_limit : 'No';

// form donation data
$formDonation = isset( $getMetaData->donation ) ? $getMetaData->donation : array();

// form content data
$formContentData = isset( $getMetaData->form_content ) ? $getMetaData->form_content : (object) array(
	'enable'           => 'No',
	'content_position' => 'after-form',
);

// form goal data
$formGoalData = isset( $getMetaData->goal_setup ) ? $getMetaData->goal_setup : (object) array(
	'enable'    => 'No',
	'goal_type' => 'goal_terget_amount',
);

// form terms data
$formTermsData = isset( $getMetaData->form_terma ) ? $getMetaData->form_terma : (object) array(
	'enable'           => 'No',
	'content_position' => 'after-submit-button',
);

$add_fees = isset( $getMetaData->donation->set_add_fees ) ? $getMetaData->donation->set_add_fees : (object) array(
	'enable'      => 'No',
	'fees_amount' => 0,
);

// target goal check
$goalStatus     = 'Yes';
$goalDataAmount = 0;
$goalMessage    = '';

$campaign_status = 'Publish';

if ( isset( $formGoalData->enable ) ) {

	global $wpdb;
	$goal_type           = isset( $formGoalData->goal_type ) ? $formGoalData->goal_type : 'terget_goal';
	$total_rasied_amount = $wpdb->get_var( $wpdb->prepare( "SELECT SUM(donate_amount) FROM {$wpdb->prefix}wdp_fundraising WHERE form_id = %d AND status = 'Active'", $post->ID ) );
	$total_rasied_count  = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(donate_id) FROM {$wpdb->prefix}wdp_fundraising WHERE form_id = %d AND status = 'Active'", $post->ID ) );

	$to_date            = gmdate( 'Y-m-d' );
	$time               = time();
	$persentange        = 0;
	$target_amount      = 0;
	$target_amount_fake = 0;
	$target_date        = gmdate( 'Y-m-d' );

	$total_rasied_amount_fake = $total_rasied_amount;
	$total_rasied_count_fake  = $total_rasied_count;

	if ( in_array(
		$goal_type,
		array(
			'terget_goal',
			'terget_goal_date',
			'campaign_never_end',
			'terget_date',
		)
	) ) {
		$target_amount      = isset( $formGoalData->terget->terget_goal->amount ) ? $formGoalData->terget->terget_goal->amount : 0;
		$target_amount_fake = isset( $formGoalData->terget->terget_goal->fake_amount ) ? $formGoalData->terget->terget_goal->fake_amount : 0;
		$target_date        = isset( $formGoalData->terget->terget_goal->date ) ? $formGoalData->terget->terget_goal->date : gmdate( 'Y-m-d' );

		$target_time = strtotime( $target_date );

		$total_rasied_amount_fake = $total_rasied_amount + $target_amount_fake;
		// check amount with data
		if ( $total_rasied_amount_fake >= $target_amount ) {
			$total_rasied_amount_fake = $total_rasied_amount;
		}
		if ( $target_amount > 0 ) {
			$persentange = ( $total_rasied_amount_fake * 100 ) / $target_amount;
		}

		if ( $total_rasied_amount >= $target_amount ) {
			$goalStatus = 'No';
		}
		if ( $goal_type == 'terget_goal_date' || $goal_type == 'terget_date' ) {
			if ( $time > $target_time ) {
				$goalStatus = 'No';
			}
		} elseif ( $goal_type == 'campaign_never_end' ) {
			$goalStatus = 'Yes';
		}
	}

	$campaign_status = ( $goalStatus == 'Yes' ) ? 'Publish' : 'Ends';

	$goalMessageEmable = isset( $formGoalData->terget->enable ) ? $formGoalData->terget->enable : 'No';
	$goalMessage       = isset( $formGoalData->terget->message ) ? $formGoalData->terget->message : '';


	update_post_meta( $post->ID, '__wfp_campaign_status', $campaign_status );
}

$termsContent = '';
if ( isset( $formTermsData->enable ) ) {
	$termsContent .= '<div class="xs-switch-button_wraper">
					<input type="checkbox" class="xs_donate_switch_button" name="xs-donate-terms-condition" id="xs-donate-terms-condition" value="Yes">
					<label class="xs_donate_switch_button_label small xs-round" for="xs-donate-terms-condition"></label><span class="xs-donate-terms-label">' . $formTermsData->level . '</span>
					<span class="xs-donate-terms"> ' . $formTermsData->content . ' </span>
				</div>';
}

$modalHow = isset( $formDesignData->modal_show ) ? $formDesignData->modal_show : 'No';


if ( $form_styles == 'all_fields' ) {
	$modal_status = 'No';
}

if ( $format_style == 'single_donation' ) {
	$modal_status                      = 'Yes';
	$form_styles                       = 'no_button';
	$formContentData->content_position = 'no_content';
}

/*
 * AR[20200114]
 *
 */
$modal_status = isset( $atts['modal'] ) ? $atts['modal'] : $modalHow;
$form_styles  = isset( $atts['form-style'] ) ? $atts['form-style'] : $formDesignData->styles;

// css code generate
$continueCOlor    = isset( $formDesignData->continue_color ) ? $formDesignData->continue_color : '#0085ba';
$submitCOlor      = isset( $formDesignData->submit_color ) ? $formDesignData->submit_color : '#0085ba';
$barProgressCOlor = isset( $formGoalData->bar_color ) ? $formGoalData->bar_color : '#324aff';


// overriding these..........................................................................
$customClass  = isset( $atts['class'] ) ? $atts['class'] : $customClass;
$customIdData = isset( $atts['id'] ) ? $atts['id'] : $customIdData;
$format_style = isset( $atts['format-style'] ) ? $atts['format-style'] : $format_style;
// overriding ends...........................................................................

