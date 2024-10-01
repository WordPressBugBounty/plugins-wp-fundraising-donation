<?php

namespace WfpFundraising\Utilities;

defined( 'ABSPATH' ) || exit;

use WfpFundraising\Apps\Key;

/**
 * Global static class
 *
 * @since 1.0.0
 */
class Helper {

	/**
	 * Auto generate classname from path
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public static function make_classname( $dirname ) {
		$dirname    = pathinfo( $dirname, PATHINFO_FILENAME );
		$class_name = explode( '-', $dirname );
		$class_name = array_map( 'ucfirst', $class_name );
		$class_name = implode( '_', $class_name );

		return $class_name;
	}


	/**
	 * Get an moderate entropy unique id, specially for generating uuid for pledge
	 *
	 * Note: ensure that all letters are capitol otherwise old reward data will be lost
	 *  specially with woocommerce reward save, there strtoupper used!
	 *
	 * @since 1.1.15
	 *
	 * @param int $prefix_len
	 *
	 * @return string
	 */
	public static function get_uuid( $prefix_len = 6 ) {

		$textShuffle = '@ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$uuid        = substr( str_shuffle( $textShuffle ), 0, $prefix_len ) . '-' . time();

		return $uuid;
	}


	/**
	 * Generates a unique string and adda given prefix
	 *
	 * @since 1.1.15
	 *
	 * @param int    $prefix_len - given prefix, default wfp_
	 * @param string $prefix - default 12 character long
	 *
	 * @return string
	 */
	public static function get_html_unique_id( $prefix_len = 12, $prefix = 'wfp_' ) {

		$textShuffle = 'abcdefghijklmnopqrstuvwxyz_';
		$uuid        = substr( str_shuffle( $textShuffle ), 0, $prefix_len );

		return $prefix . $uuid;
	}


	/**
	 *
	 * @since 1.2.0
	 *
	 * @param $option
	 *
	 * @return string
	 */
	public static function get_default_donation_type( $option ) {

		return empty( $option['services']['campaign'] ) ? Key::WFP_DONATION_TYPE_SINGLE : $option['services']['campaign'];
	}

	public static function is_woocom_payment() {

		$key  = 'wfp_setup_services_data';
		$data = get_option( $key, array() );

		$gate = isset( $data['services']['payment'] ) ? $data['services']['payment'] : 'default';

		return $gate === 'woocommerce';
	}

	public static function add_2_cart_form( $postId ) {

		$path = \WFP_Fundraising::views_dir() . 'public/donation/include/_add2cart_form.php';

		include $path;
	}

	public static function get_wfp_mandatory_fields() {

		$fields[] = array(
			'type'     => 'text',
			'label'    => __( 'First Name', 'wp-fundraising' ),
			'required' => Key::WFP_YES,
		);

		$fields[] = array(
			'type'     => 'text',
			'label'    => __( 'Last Name', 'wp-fundraising' ),
			'required' => Key::WFP_YES,
		);

		$fields[] = array(
			'type'     => 'email',
			'label'    => __( 'Email Address', 'wp-fundraising' ),
			'required' => Key::WFP_YES,
		);

		$fields[] = array(
			'type'     => 'country', // !!! :P
			'label'    => __( 'Country Destination', 'wp-fundraising' ),
			'required' => Key::WFP_YES,
		);

		$fields[] = array(
			'type'     => 'text',
			'label'    => __( 'Street Address', 'wp-fundraising' ),
			'required' => Key::WFP_YES,
		);

		$fields[] = array(
			'type'     => 'text',
			'label'    => __( 'City', 'wp-fundraising' ),
			'required' => Key::WFP_YES,
		);

		$fields[] = array(
			'type'     => 'text',
			'label'    => __( 'Postcode / ZIP', 'wp-fundraising' ),
			'required' => Key::WFP_YES,
		);

		return $fields;
	}
}
