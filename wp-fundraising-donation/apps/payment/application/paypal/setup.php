<?php

namespace WPF_Payment\Application\Paypal;

use \WPF_Payment\Application\Init as Init;

use \WPF_Payment\Application\Util as Util;

class Setup {

	private static $instance;

	private $ajax_hook     = 'ipn-ajax-wfp';
	private $ajax_callback = 'ipn_ajax_wfp_callback';

	private $_url         = 'https://www.paypal.com/cgi-bin/webscr?';
	private $_sandbox_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr?';
	private $_token       = null;
	private $_sandbox     = false;

	public $return_config = array();

	public $return_process = array();

	public $_data_url = array();

	public $paypal_url = null;

	public function init( array $config ) {

		if ( isset( $config['_url'] ) ) {
			$this->_url = strlen( $config['_url'] ) > 10 ? $config['_url'] : '';
		}
		if ( isset( $config['_sandbox_url'] ) ) {
			$this->_sandbox_url = strlen( $config['_sandbox_url'] ) > 10 ? $config['_sandbox_url'] : '';
		}

		if ( isset( $config['_sandbox'] ) ) {
			$this->_sandbox = $config['_sandbox'];
		}

		if ( isset( $config['_token'] ) ) {
			$this->_token = $config['_token'];
		}

		$this->return_config['_url']         = $this->_url;
		$this->return_config['_sandbox_url'] = $this->_sandbox_url;
		$this->return_config['_sandbox']     = $this->_sandbox;
		$this->return_config['_token']       = $this->_token;

		return $this;
	}

	public function ipn_verify( $sandbox = false, $cer = false ) {
		$ipn = new Ipn();

		// enable sandbox
		if ( $sandbox ) {
			$ipn->sandbox();
		}

		// disable certificate
		if ( $cer ) {
			$ipn->disable_certificate();
		}

		$verified         = $ipn->verify();
		$return['status'] = false;

		if ( $verified ) {
			$return['status']   = true;
			$return['txn_id']   = isset( $_REQUEST['txn_id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['txn_id'] ) ) : ''; //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Already checked inside apps\content.php file ipn_ajax_wfp_callback() method
			$return['response'] = isset( $_REQUEST ) ? $_REQUEST : ''; //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Already checked inside apps\content.php file ipn_ajax_wfp_callback() method
		} else {
			error_log( 'Invalid PayPal IPN request: ' . json_encode( $_REQUEST ) ); //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Already checked inside apps\content.php file ipn_ajax_wfp_callback() method
		}

		header( 'HTTP/1.1 200 OK' );

		return $return;
	}

	public function PaymentProcess( array $data ) {

		$this->_data_url['cmd']         = '_xclick';
		$this->_data_url['business']    = '';
		$this->_data_url['item_name']   = 'Item Name #-' . time();
		$this->_data_url['item_number'] = time();

		$textShuffle                    = '@ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$tx                             = substr( str_shuffle( $textShuffle ), 0, 6 ) . '-' . time();
		$this->_data_url['tx']          = $tx;
		$this->_data_url['custom']      = 'RESDONE-' . time();
		$this->_data_url['amount']      = 0;
		$this->_data_url['quantity']    = 1;
		$this->_data_url['no_shipping'] = 0;

		$this->_data_url['currency_code'] = $data['currency_code'];

		foreach ( $data as $k => $v ) {
			$this->_data_url[ $k ] = $v;
		}

		if ( ! empty( trim( $this->_token ) ) ) {
			$this->_data_url['at'] = $this->_token;
		}

		$this->paypal_url = $this->_url;
		if ( $this->_sandbox ) {
			$this->paypal_url = $this->_sandbox_url;
		}

		$this->paypal_url = Init::instance()->url_generate( $this->paypal_url, $this->_data_url );

		Util::redirect( $this->paypal_url );
	}

	public function get_paypal_url() {
		return $this->paypal_url;
	}

	public function _return_config() {
		return $this->return_config;
	}

	public function _return_payment_data() {
		return $this->_data_url;
	}

	public static function instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
