<?php

defined( 'ABSPATH' ) || exit;
// load autoload
require_once __DIR__ . '/autoload.php';

// Payment Setup Data
$wfp_fundraising_setup['method'] = array( 'paypal', 'stripe' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: setup data prefixed to `wfp_fundraising_`

// paypal setup
$wfp_fundraising_setup['paypal'] = array( // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: setup data prefixed to `wfp_fundraising_`
	'_sandbox' => true,
	'_token'   => null,
);

// stripe
$wfp_fundraising_setup['stripe'] = array( // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: setup data prefixed to `wfp_fundraising_`
	'stripe_secret_key_test' => 'pk_test_sbxBoppU6hqfE6bmRYS5Wczd002Ze8bdUS',
	'stripe_secret_key'      => 'pk_live_sbxBoppU6hqfE6bmRYS5Wczd002Ze8bdUS',
	'_sandbox'               => true,
);

// \WPF_Payment\Application\Init::instance()->setup( $wfp_fundraising_setup );


// custom function
if ( ! function_exists( 'wfp_fundraising_paypal' ) ) {
	function wfp_fundraising_paypal() { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- Reason: payment gateway helper uses plugin-prefixed name
		return \WPF_Payment\Application\Paypal\Setup::instance();
	}
}

if ( ! function_exists( 'wfp_fundraising_stripe' ) ) {
	function wfp_fundraising_stripe() { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- Reason: payment gateway helper uses plugin-prefixed name
		return \WPF_Payment\Application\Stripe\Setup::instance();
	}

	// load script
	wfp_fundraising_stripe()->_load_script();
}



// Actionn for IPN Paypal Method
add_action( 'wp_ajax_ipn-ajax-wfp', 'wfp_fundraising_ipn_ajax_wfp_callback' );
add_action( 'wp_ajax_nopriv_ipn-ajax-wfp', 'wfp_fundraising_ipn_ajax_wfp_callback' );

function wfp_fundraising_ipn_ajax_wfp_callback() { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- Reason: AJAX callback uses plugin-prefixed name
	\WfpFundraising\Apps\Content::instance()->ipn_ajax_wfp_callback();
	return;
}

// Stripe Payment
add_action( 'init', 'wfp_fundraising_rest_stripe_init_rest' );
function wfp_fundraising_rest_stripe_init_rest() { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- Reason: REST bootstrap callback uses plugin-prefixed name
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
