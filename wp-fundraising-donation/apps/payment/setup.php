<?php

// load autoload
require_once __DIR__ . '/autoload.php';

// Payment Setup Data
$setup['method'] = array( 'paypal', 'stripe' );

// paypal setup
$setup['paypal'] = array(
	'_sandbox' => true,
	'_token'   => null,
);

// stripe
$setup['stripe'] = array(
	'stripe_secret_key_test' => 'pk_test_sbxBoppU6hqfE6bmRYS5Wczd002Ze8bdUS',
	'stripe_secret_key'      => 'pk_live_sbxBoppU6hqfE6bmRYS5Wczd002Ze8bdUS',
	'_sandbox'               => true,
);

// \WPF_Payment\Application\Init::instance()->setup($setup);


// custom function
if ( ! function_exists( 'WFP_Paypal' ) ) {
	function WFP_Paypal() {
		return \WPF_Payment\Application\Paypal\Setup::instance();
	}
}

if ( ! function_exists( 'WFP_Stripe' ) ) {
	function WFP_Stripe() {
		return \WPF_Payment\Application\Stripe\Setup::instance();
	}

	// load script
	WFP_Stripe()->_load_script();
}



// Actionn for IPN Paypal Method
add_action( 'wp_ajax_ipn-ajax-wfp', 'ipn_ajax_wfp_callback' );
add_action( 'wp_ajax_nopriv_ipn-ajax-wfp', 'ipn_ajax_wfp_callback' );

function ipn_ajax_wfp_callback() {
	\WfpFundraising\Apps\Content::instance()->ipn_ajax_wfp_callback();
	return;
}

// Stripe Payment
add_action( 'init', '__rest_stripe_init_rest' );
function __rest_stripe_init_rest() {
	add_action(
		'rest_api_init',
		function () {
			register_rest_route(
				'wfp-stripe-payment',
				'/stripe-submit/(?P<id>\w+)/',
				array(
					'methods'             => 'POST',
					'callback'            => 'stripe_ajax_wfp_callback',
					'permission_callback' => '__return_true',
				)
			);
		}
	);
}

function stripe_ajax_wfp_callback( \WP_REST_Request $request ) {
	 return \WfpFundraising\Apps\Content::instance()->stripe_ajax_wfp_callback( $request );
}
