<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

require \WFP_Fundraising::plugin_dir() . 'country-module/country-info.php';

/*currency information*/
$wfpGetMetaGeneralOp = get_option( \WfpFundraising\Apps\Settings::OK_GENERAL_DATA );
$wfpGetMetaGeneral   = isset( $wfpGetMetaGeneralOp['options'] ) ? $wfpGetMetaGeneralOp['options'] : array();

$wfpDefaultCurrencyInfo = isset( $wfpGetMetaGeneral['currency']['name'] ) ? $wfpGetMetaGeneral['currency']['name'] : 'US-USD';
$wfpExplCurr            = explode( '-', $wfpDefaultCurrencyInfo );
$wfpCurrCode            = isset( $wfpExplCurr[1] ) ? $wfpExplCurr[1] : 'USD';
$wfpSymbols             = isset( $wfpCountryList[ $wfpCurrCode ]['currency']['symbol'] ) ? $wfpCountryList[ $wfpCurrCode ]['currency']['symbol'] : '';
$wfpSymbols             = strlen( $wfpSymbols ) > 0 ? $wfpSymbols : $wfpCurrCode;

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

$wfp_found = new WfpFundraising\Apps\Fundraising( false );

// target goal check
$wfpGoalStatus     = 'Yes';
$wfpGoalDataAmount = 0;
$wfpGoalMessage    = '';

$wfp_campaign_status = 'Publish';

$wfp_time        = time();
$wfp_to_date     = gmdate( 'Y-m-d' );
$wfp_target_date = gmdate( 'Y-m-d' );

$wfp_persentange   = 0;
$wfp_target_amount = 0;

$wfp_target_amount_fake  = 0;
$wfp_total_rasied_count  = 0;
$wfp_total_rasied_amount = 0;

$wfp_total_rasied_amount_fake = 0;
$wfp_total_rasied_count_fake  = 0;

if ( isset( $wfpFormGoalData->enable ) ) {
	global $wpdb;
	$wfp_goal_type           = isset( $wfpFormGoalData->goal_type ) ? $wfpFormGoalData->goal_type : 'terget_goal';
	$wfp_total_rasied_amount = $wpdb->get_var( $wpdb->prepare( "SELECT SUM(donate_amount) FROM {$wpdb->prefix}wdp_fundraising WHERE form_id = %d AND status = 'Active'", $post->ID ) ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Prepared aggregate read for load.php campaign total.
	$wfp_total_rasied_count  = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(donate_id) FROM {$wpdb->prefix}wdp_fundraising WHERE form_id = %d AND status = 'Active'", $post->ID ) ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Prepared aggregate read for load.php campaign count.

	$wfp_total_rasied_amount_fake = $wfp_total_rasied_amount;
	$wfp_total_rasied_count_fake  = $wfp_total_rasied_count;

	if ( in_array( $wfp_goal_type, array( 'terget_goal', 'terget_goal_date', 'campaign_never_end', 'terget_date' ) ) ) {
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


	update_post_meta( get_the_ID(), '__wfp_campaign_status', $wfp_campaign_status );
	// css code generate
	$wfpContinueCOlor    = isset( $wfpFormDesignData->continue_color ) ? $wfpFormDesignData->continue_color : '#0085ba';
	$wfpSubmitCOlor      = isset( $wfpFormDesignData->submit_color ) ? $wfpFormDesignData->submit_color : '#0085ba';
	$wfpBarProgressCOlor = isset( $wfpFormGoalData->bar_color ) ? $wfpFormGoalData->bar_color : '#324aff';
}
