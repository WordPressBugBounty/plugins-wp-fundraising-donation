<?php

require \WFP_Fundraising::plugin_dir() . 'country-module/country-info.php';

/*currency information*/
$getMetaGeneralOp = get_option( \WfpFundraising\Apps\Settings::OK_GENERAL_DATA );
$getMetaGeneral   = isset( $getMetaGeneralOp['options'] ) ? $getMetaGeneralOp['options'] : array();

$defaultCurrencyInfo = isset( $getMetaGeneral['currency']['name'] ) ? $getMetaGeneral['currency']['name'] : 'US-USD';
$explCurr            = explode( '-', $defaultCurrencyInfo );
$currCode            = isset( $explCurr[1] ) ? $explCurr[1] : 'USD';
$symbols             = isset( $countryList[ $currCode ]['currency']['symbol'] ) ? $countryList[ $currCode ]['currency']['symbol'] : '';
$symbols             = strlen( $symbols ) > 0 ? $symbols : $currCode;

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

$found = new WfpFundraising\Apps\Fundraising( false );

// target goal check
$goalStatus     = 'Yes';
$goalDataAmount = 0;
$goalMessage    = '';

$campaign_status = 'Publish';

$time        = time();
$to_date     = gmdate( 'Y-m-d' );
$target_date = gmdate( 'Y-m-d' );

$persentange   = 0;
$target_amount = 0;

$target_amount_fake  = 0;
$total_rasied_count  = 0;
$total_rasied_amount = 0;

$total_rasied_amount_fake = 0;
$total_rasied_count_fake  = 0;

if ( isset( $formGoalData->enable ) ) {
	global $wpdb;
	$goal_type           = isset( $formGoalData->goal_type ) ? $formGoalData->goal_type : 'terget_goal';
	$total_rasied_amount = $wpdb->get_var( $wpdb->prepare( "SELECT SUM(donate_amount) FROM {$wpdb->prefix}wdp_fundraising WHERE form_id = %d AND status = 'Active'", $post->ID ) );
	$total_rasied_count  = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(donate_id) FROM {$wpdb->prefix}wdp_fundraising WHERE form_id = %d AND status = 'Active'", $post->ID ) );

	$total_rasied_amount_fake = $total_rasied_amount;
	$total_rasied_count_fake  = $total_rasied_count;

	if ( in_array( $goal_type, array( 'terget_goal', 'terget_goal_date', 'campaign_never_end', 'terget_date' ) ) ) {
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


	update_post_meta( get_the_ID(), '__wfp_campaign_status', $campaign_status );
	// css code generate
	$continueCOlor    = isset( $formDesignData->continue_color ) ? $formDesignData->continue_color : '#0085ba';
	$submitCOlor      = isset( $formDesignData->submit_color ) ? $formDesignData->submit_color : '#0085ba';
	$barProgressCOlor = isset( $formGoalData->bar_color ) ? $formGoalData->bar_color : '#324aff';
}
