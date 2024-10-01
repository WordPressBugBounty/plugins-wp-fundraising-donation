<?php


if ( ! empty( $formGoalData->enable ) && $formGoalData->enable === \WfpFundraising\Apps\Key::WFP_YES ) {
	global $wpdb;
	$goal_type       = ! empty( $formGoalData->goal_type ) ? $formGoalData->goal_type : \WfpFundraising\Apps\Key::GOAL_TYPE_TARGET_GOAL;
	$total_collected = $wpdb->get_var( $wpdb->prepare( "SELECT SUM(donate_amount) FROM {$wpdb->prefix}wdp_fundraising WHERE form_id = %d AND status = 'Active'", $postId ) );
	$donation_count  = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(donate_id) FROM {$wpdb->prefix}wdp_fundraising WHERE form_id = %d AND status = 'Active'", $postId ) );

	$today        = gmdate( 'Y-m-d' );
	$fake_amount  = 0;
	$goal_amount  = 0;
	$percentage   = 0;
	$total_raised = $total_collected + $fake_amount;

	$goalStatus = \WfpFundraising\Apps\Key::WFP_YES;


	if ( in_array(
		$goal_type,
		array(
			\WfpFundraising\Apps\Key::GOAL_TYPE_TARGET_GOAL,
			\WfpFundraising\Apps\Key::GOAL_TYPE_GOAL_DATE,
			\WfpFundraising\Apps\Key::GOAL_TYPE_GOAL_DATE,
			\WfpFundraising\Apps\Key::GOAL_TYPE_NEVER_END,
		)
	) ) {

		$goal_amount = isset( $formGoalData->terget->terget_goal->amount ) ? $formGoalData->terget->terget_goal->amount : 0;
		$fake_amount = isset( $formGoalData->terget->terget_goal->fake_amount ) ? $formGoalData->terget->terget_goal->fake_amount : 0;
		$target_date = isset( $formGoalData->terget->terget_goal->date ) ? $formGoalData->terget->terget_goal->date : $today;


		$total_raised = $total_collected + $fake_amount;

		if ( $total_raised >= $goal_amount ) {
			$total_raised = $total_collected;
		}

		if ( $goal_amount > 0 ) {
			$percentage = $total_raised >= $goal_amount ? 100 : ( ( $total_raised * 100 ) / $goal_amount );
		}

		if ( $total_raised >= $goal_amount ) {
			$goalStatus = \WfpFundraising\Apps\Key::WFP_NO;
		}

		if ( $goal_type == \WfpFundraising\Apps\Key::GOAL_TYPE_GOAL_DATE || $goal_type == \WfpFundraising\Apps\Key::GOAL_TYPE_TARGET_DATE ) {

			$time        = time();
			$target_time = strtotime( $target_date );

			if ( $time > $target_time ) {
				$goalStatus = \WfpFundraising\Apps\Key::WFP_NO;
			}
		} elseif ( $goal_type == \WfpFundraising\Apps\Key::GOAL_TYPE_NEVER_END ) {
			$goalStatus = \WfpFundraising\Apps\Key::WFP_YES;
		}
	}

	$campaign_status   = ( $goalStatus == \WfpFundraising\Apps\Key::WFP_YES ) ? 'Publish' : \WfpFundraising\Apps\Key::CAMPAIGN_STATUS_ENDED;
	$goalMessageEnable = isset( $formGoalData->terget->enable ) ? $formGoalData->terget->enable : \WfpFundraising\Apps\Key::WFP_NO;
	$goalMessage       = isset( $formGoalData->terget->message ) ? $formGoalData->terget->message : '';

	update_post_meta( $postId, '__wfp_campaign_status', $campaign_status );
}
