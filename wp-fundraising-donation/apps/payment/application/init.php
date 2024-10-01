<?php
namespace WPF_Payment\Application;

defined( 'ABSPATH' ) || exit;

class Init {

	private static $instance;

	public function setup( array $config ) {
		$method = isset( $config['method'] ) ? $config['method'] : array( 'paypal', 'stripe' );
		if ( in_array( 'paypal', $method ) ) {
			$data = isset( $config['paypal'] ) ? $config['paypal'] : array();
			Paypal\Setup::instance()->init( $data );
		}

		if ( in_array( 'stripe', $method ) ) {
			$data = isset( $config['stripe'] ) ? $config['stripe'] : array();
			Stripe\Setup::instance()->init( $data );
		}
	}

	public function url_generate( $url, $params ) {
		$params_url = http_build_query( $params, '', '&' );
		if ( strpos( $url, '?' ) === false ) {
			return ( $url . '?' . $params_url );
		} else {
			return ( $url . '&' . $params_url );
		}
	}


	public static function instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}


}
