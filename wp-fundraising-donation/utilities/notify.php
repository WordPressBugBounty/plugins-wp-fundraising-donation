<?php

namespace WfpFundraising\Utilities;

class Notify {

	/**
	 * Thank you message to donor
	 * and confirming donation has made
	 *
	 * @since 1.1.16
	 *
	 * @param $campaign_id
	 * @param $donor_id
	 * @param $donation_id
	 *
	 * @return bool
	 */
	public static function donation_success_to_donor( $campaign_id, $donor_id, $donation_id ) {

		return true;
	}


	/**
	 * Send a notification email to campaign creator
	 * that a donation has made with payment type, amount, and status
	 * and donor basic info
	 *
	 * @since 1.1.16
	 *
	 * @param $campaign_id
	 * @param $donor_id
	 * @param $donation_id
	 *
	 * @return bool
	 */
	public static function donation_success_to_creator( $campaign_id, $donor_id, $donation_id ) {

		// $email = get_site_option('admin_email');
		//
		// if(is_email($email) == false) {
		// return false;
		// }
		//
		// $user = get_userdata($user_id);
		//
		// $msg = sprintf(
		// __(
		// 'New User: %1$s
		// Email address: %2$s
		// Remote IP address: %3$s
		// Registered using : %4$s'
		// ),
		// $user->user_login,
		// $user->email,
		// wp_unslash($_SERVER['REMOTE_ADDR']),
		// $social_type
		// );
		//
		//
		// $msg = apply_filters('newuser_notify_siteadmin', $msg, $user);
		//
		// * translators: New user notification email subject. %s: User login. */
		// wp_mail($email, sprintf(__('New User Registration: %s'), $user->user_login), $msg);
		//
		// return true;

		return true;
	}

}
