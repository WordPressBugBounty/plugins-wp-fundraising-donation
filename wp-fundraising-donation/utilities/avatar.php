<?php

namespace WfpFundraising\Utilities;

/**
 * This is the free version Avatar class for handling user related actions.
 *
 * @package WfpFundraising\Utilities
 */
class Avatar {

	public function create_account_with_info( $insertData, $notify = 'both' ) {

		$userId = wp_insert_user( $insertData );

		if ( is_wp_error( $userId ) ) {

			return $userId;
		}

		/**
		 * Notify network admin and user about their account in this iste
		 */
		wp_new_user_notification( $userId, null, $notify );

		return $userId;
	}


	public function create_account( $config = array() ) {

		$first_name = sanitize_text_field( $config['f_name'] );
		$last_name  = sanitize_text_field( $config['l_name'] );
		$email      = sanitize_email( $config['email'] );

		$user_nm = $this->get_available_username( $first_name, $last_name );

		$insertData['first_name']    = $first_name;
		$insertData['last_name']     = $last_name;
		$insertData['user_nicename'] = $user_nm;
		$insertData['user_email']    = $email;
		$insertData['display_name']  = $first_name . ' ' . $last_name;

		$password                 = wp_generate_password();
		$insertData['user_login'] = $user_nm;
		$insertData['user_pass']  = $password;

		$userId = wp_insert_user( $insertData );

		$this->notify_new_user_to_admin( $userId );

		return $userId;
	}


	public function get_available_username( $f_name, $l_name ) {

		$usr = $this->get_sanitized_username( $f_name, $l_name );

		$user_id = username_exists( $usr );

		if ( $user_id == false ) {
			return $usr;
		}

		$counter = 1;
		$usr     = $usr . $counter;

		while ( username_exists( $usr ) !== false ) {
			$counter++;
			$usr = $usr . $counter;
		}

		return $usr;
	}


	protected function get_sanitized_username( $f_name, $l_name ) {

		$user_nm = $f_name . $l_name;

		if ( empty( $user_nm ) ) {

			return $this->get_random_username();
		}

		$username = strtolower( $user_nm );
		$username = preg_replace( '/\s+/', '', $username );

		$sanitized = sanitize_user( $username );

		if ( empty( $sanitized ) ) {

			return $this->get_random_username();
		}

		if ( ! validate_username( $sanitized ) ) {

			return $this->get_random_username();
		}

		return $sanitized;
	}


	/**
	 *
	 *
	 * @param int $min_len
	 *
	 * @return bool|string
	 */
	public function get_random_username( $min_len = 10 ) {

		$sm      = 'abcdefghijklmnopqrstuvwxyz';
		$uc      = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$dg      = '0123456789';
		$special = '_';

		$uuid = substr( str_shuffle( $sm ), 0, 4 );

		$uuid .= '_' . substr( str_shuffle( $sm . $uc ), 0, 4 );

		$uuid .= '_' . substr( str_shuffle( $dg ), 0, 2 );

		return $uuid;
	}


	public function notify_new_user_to_admin( $user_id ) {

		wp_new_user_notification( $user_id, null, 'both' );

		return true;
	}
}
