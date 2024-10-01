<?php
namespace WPF_Payment\Application\Paypal;

class Ipn {

	 // when sendbox
	 private $sandbox = false;

	 private $use_local_certs = true;

	 const VERIFY_URI = 'https://paypal.com/cgi-bin/webscr';

	 const SANDBOX_VERIFY_URI = 'https://www.sandbox.paypal.com/cgi-bin/webscr';

	 const VALID = 'VERIFIED';

	 const INVALID = 'INVALID';

	public function sandbox() {
		$this->sandbox = true;
	}

	public function get_verify_url() {
		if ( $this->sandbox ) {
			return self::SANDBOX_VERIFY_URI;
		} else {
			return self::VERIFY_URI;
		}
	}

	public function disable_certificate() {
		$this->use_local_certs = false;
	}

	public function verify() {
		if ( ! count( $_POST ) ) {  //phpcs:ignore WordPress.Security.NonceVerification.Missing -- Already checked inside apps\content.php file ipn_ajax_wfp_callback() method
			throw new \Exception( 'Missing POST Data' );
		}

		$raw_post_data  = file_get_contents( 'php://input' );
		$raw_post_array = explode( '&', $raw_post_data );
		$myPost         = array();
		foreach ( $raw_post_array as $keyval ) {
			$keyval = explode( '=', $keyval );
			if ( count( $keyval ) == 2 ) {
				if ( $keyval[0] === 'payment_date' ) {
					if ( substr_count( $keyval[1], '+' ) === 1 ) {
						$keyval[1] = str_replace( '+', '%2B', $keyval[1] );
					}
				}
				$myPost[ $keyval[0] ] = urldecode( $keyval[1] );
			}
		}

		$req                     = 'cmd=_notify-validate';
		$get_magic_quotes_exists = false;
		if ( function_exists( 'get_magic_quotes_gpc' ) ) {
			$get_magic_quotes_exists = true;
		}
		foreach ( $myPost as $key => $value ) {
			if ( $get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1 ) {
				$value = urlencode( stripslashes( $value ) );
			} else {
				$value = urlencode( $value );
			}
			$req .= "&$key=$value";
		}
 
		$args = [
			'method'      => 'POST',
			'httpversion' => '1.1',
			'timeout'     => 30,
			'sslverify' 	=> true,
			'headers'     => array(
				'User-Agent' => 'PHP-IPN-Verification-Script',
				'Connection' => 'close',
			),
			'body'        => $req
		];

		if ($this->use_local_certs) {
			$args['sslcertificates'] = __DIR__ . "/cert/cacert.pem";
		}

		$res = wp_remote_post($this->get_verify_url(), $args);

		if ( ! ($res['response']['code'] = 200)) {
			throw new \Exception($res['response']['message']);
		}

		if ($res['response']['code'] != 200) {
			throw new \Exception("PayPal responded with http code " . $res['response']['code']);
		}

		if ($res['response']['body'] == self::VALID) {
			return true;
		} else {
			return false;
		}
     }
}
