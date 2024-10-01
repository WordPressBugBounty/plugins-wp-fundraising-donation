<?php

namespace WfpFundraising\Apps;

defined( 'ABSPATH' ) || exit;

class Global_Settings {

	public static $allowed_gateway = array(
		'online_payment'  => 'Paypal',
		'stripe_payment'  => 'Stripe',
		'bank_payment'    => 'Bank',
		'check_payment'   => 'Check',
		'offline_payment' => 'Cash',
		'2checkout'       => '2Checkout',
	);

	public static $all_donation_status = array(
		'Pending',
		'Review',
		'Active',
		'DeActive',
		'Refunded',
	);

	private static $instance;

	private $general;
	private $payment;
	private $service;

	public static function instance() {

		if ( ! self::$instance ) {
			self::$instance = new static();
		}

		return self::$instance;
	}


	private function load() {

		$this->general = get_option( Settings::OK_GENERAL_DATA );
	}

	public function set_option_general( $general ) {

		$this->general = $general;

		return $this;
	}

	public function set_option_service( $service ) {

		$this->service = $service;

		return $this;
	}

	public function set_option_payment( $payment ) {

		$this->payment = $payment;

		return $this;
	}

	public function get_currency_code() {

		if ( empty( $this->service ) ) {

			$this->service = get_option( 'wfp_setup_services_data' );
		}

		if ( ! empty( $this->service['services']['payment'] ) && $this->service['services']['payment'] == 'woocommerce' ) {

			$currency_code = get_woocommerce_currency();

			return empty( $currency_code ) ? 'USD' : $currency_code;
		}

		if ( empty( $this->general ) ) {

			$this->general = get_option( Settings::OK_GENERAL_DATA );
		}

		$def = empty( $this->general['options']['currency']['name'] ) ? 'US-USD' : $this->general['options']['currency']['name'];

		$exp = explode( '-', $def );

		/**
		 * Our code ensures there will be exactly two part name-code will be returned
		 * So we can safely return $exp[1]       *
		 */
		$currCode = $exp[1];

		return $currCode;
	}


	public function get_currency_symbol() {

		if ( empty( $this->service ) ) {

			$this->service = get_option( 'wfp_setup_services_data' );
		}

		if ( ! empty( $this->service['services']['payment'] ) && $this->service['services']['payment'] == 'woocommerce' ) {

			$currency_symbol = get_woocommerce_currency_symbol();

			return empty( $currency_symbol ) ? 'USD' : $currency_symbol;
		}

		if ( empty( $this->general ) ) {

			$this->general = get_option( Settings::OK_GENERAL_DATA );
		}

		require_once \WFP_Fundraising::plugin_dir() . 'country-module/country-info.php';

		$def = empty( $this->general['options']['currency']['name'] ) ? 'US-USD' : $this->general['options']['currency']['name'];

		$exp = explode( '-', $def );

		$cn = $exp[0];

		/**
		 * Our code ensures there will be exactly two part name-code will be returned
		 * So we can safely return $exp[1]       *
		 */
		$currCode = $exp[1];

		return empty( $countryList[ $cn ]['currency']['symbol'] ) ? $currCode : $countryList[ $cn ]['currency']['symbol'];
	}

	public function load_setup_data() {

		if ( empty( $this->service ) ) {

			$this->service = get_option( 'wfp_setup_services_data', array() );
		}

		return $this;
	}

	public function get_payment_type() {

		if ( empty( $this->service ) ) {

			$this->load_setup_data();
		}

		return isset( $this->service['services']['payment'] ) ? $this->service['services']['payment'] : 'default';
	}
}





