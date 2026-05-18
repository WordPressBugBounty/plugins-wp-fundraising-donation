<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

if ( ! empty( $wfpFormGoalData->enable ) && $wfpFormGoalData->enable === \WfpFundraising\Apps\Key::WFP_YES ) {
	global $wpdb;
	$wfp_goal_type       = ! empty( $wfpFormGoalData->goal_type ) ? $wfpFormGoalData->goal_type : \WfpFundraising\Apps\Key::GOAL_TYPE_TARGET_GOAL;
	$wfp_total_collected = $wpdb->get_var( $wpdb->prepare( "SELECT SUM(donate_amount) FROM {$wpdb->prefix}wdp_fundraising WHERE form_id = %d AND status = 'Active'", $wfpPostId ) ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Prepared aggregate read for goal calculation total.
	$wfp_donation_count  = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(donate_id) FROM {$wpdb->prefix}wdp_fundraising WHERE form_id = %d AND status = 'Active'", $wfpPostId ) ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Prepared aggregate read for goal calculation count.

	$wfp_today        = gmdate( 'Y-m-d' );
	$wfp_fake_amount  = 0;
	$wfp_goal_amount  = 0;
	$wfp_percentage   = 0;
	$wfp_total_raised = $wfp_total_collected + $wfp_fake_amount;

	$wfpGoalStatus = \WfpFundraising\Apps\Key::WFP_YES;


	if ( in_array(
		$wfp_goal_type,
		array(
			\WfpFundraising\Apps\Key::GOAL_TYPE_TARGET_GOAL,
			\WfpFundraising\Apps\Key::GOAL_TYPE_GOAL_DATE,
			\WfpFundraising\Apps\Key::GOAL_TYPE_GOAL_DATE,
			\WfpFundraising\Apps\Key::GOAL_TYPE_NEVER_END,
		)
	) ) {

		$wfp_goal_amount = isset( $wfpFormGoalData->terget->terget_goal->amount ) ? $wfpFormGoalData->terget->terget_goal->amount : 0;
		$wfp_fake_amount = isset( $wfpFormGoalData->terget->terget_goal->fake_amount ) ? $wfpFormGoalData->terget->terget_goal->fake_amount : 0;
		$wfp_target_date = isset( $wfpFormGoalData->terget->terget_goal->date ) ? $wfpFormGoalData->terget->terget_goal->date : $wfp_today;


		$wfp_total_raised = $wfp_total_collected + $wfp_fake_amount;

		if ( $wfp_total_raised >= $wfp_goal_amount ) {
			$wfp_total_raised = $wfp_total_collected;
		}

		if ( $wfp_goal_amount > 0 ) {
			$wfp_percentage = $wfp_total_raised >= $wfp_goal_amount ? 100 : ( ( $wfp_total_raised * 100 ) / $wfp_goal_amount );
		}

		if ( $wfp_total_raised >= $wfp_goal_amount ) {
			$wfpGoalStatus = \WfpFundraising\Apps\Key::WFP_NO;
		}

		if ( $wfp_goal_type == \WfpFundraising\Apps\Key::GOAL_TYPE_GOAL_DATE || $wfp_goal_type == \WfpFundraising\Apps\Key::GOAL_TYPE_TARGET_DATE ) {

			$wfp_time        = time();
			$wfp_target_time = strtotime( $wfp_target_date );

			if ( $wfp_time > $wfp_target_time ) {
				$wfpGoalStatus = \WfpFundraising\Apps\Key::WFP_NO;
			}
		} elseif ( $wfp_goal_type == \WfpFundraising\Apps\Key::GOAL_TYPE_NEVER_END ) {
			$wfpGoalStatus = \WfpFundraising\Apps\Key::WFP_YES;
		}
	}

	$wfp_campaign_status   = ( $wfpGoalStatus == \WfpFundraising\Apps\Key::WFP_YES ) ? 'Publish' : \WfpFundraising\Apps\Key::CAMPAIGN_STATUS_ENDED;
	$wfpGoalMessageEnable = isset( $wfpFormGoalData->terget->enable ) ? $wfpFormGoalData->terget->enable : \WfpFundraising\Apps\Key::WFP_NO;
	$wfpGoalMessage       = isset( $wfpFormGoalData->terget->message ) ? $wfpFormGoalData->terget->message : '';

	update_post_meta( $wfpPostId, '__wfp_campaign_status', $wfp_campaign_status );
}
