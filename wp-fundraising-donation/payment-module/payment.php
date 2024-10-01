<?php
function xs_payment_services() {
	$arrayPayment = array(

		'offline_payment' => array(
			'name'        => __( 'Cash Payment', 'wp-fundraising' ),
			'setup'       => array(
				'title'                     => 'input',
				'description'               => 'textarea',
				'instructions'              => 'textarea',
				'accept_for_virtual_orders' => 'checkbox',
			),
			'description' => 'Have your customers pay with cash (or by other means) upon delivery.',
		),
		'online_payment'  => array(
			'name'        => __( 'Paypal Payment', 'wp-fundraising' ),
			'setup'       => array(
				'basic_information'     => 'headding',
				'title'                 => 'input',
				'description'           => 'textarea',
				'paypal_email'          => 'input',
				'advanced_options'      => 'headding',
				'paypal_sandbox'        => 'checkbox',
				'receiver_email'        => 'input',
				'payPal_identity_token' => 'input',
				'invoice_prefix'        => 'input',
				'image_url'             => 'input',
				'API_credentials'       => 'headding',
				'live_API_username'     => 'input',
				'live_API_password'     => 'input',
				'live_API_signature'    => 'input',
				/*'redirect_page_setup' => 'headding', 'success_page' => 'dropdown', 'sub_headding' => 'After success payment then redirect to this page.', 'cancel_page' => 'dropdown', 'sub_headding_cencel' => 'After cancel payment then redirect to this page.'*/
			),
			'description' => 'PayPal Standard redirects customers to PayPal to enter their payment information',
		),

		'bank_payment'    => array(
			'name'        => __( 'Direct bank transfer', 'wp-fundraising' ),
			'setup'       => array(
				'title'                    => 'input',
				'description'              => 'textarea',
				'instructions'             => 'textarea',
				'bank_account_information' => 'headding',
				'account_details'          => array(
					'account_name'   => 'input',
					'account_no'     => 'input',
					'bank_name'      => 'input',
					'routing_number' => 'input',
					'IBAN'           => 'input',
					'BIC_/_Swift'    => 'input',
				),
			),
			'description' => 'Take payments in person via BACS. More commonly known as direct bank/wire transfer',
		),
		'check_payment'   =>
			array(
				'name'        => __( 'Check payments', 'wp-fundraising' ),
				'setup'       => array(
					'title'        => 'input',
					'description'  => 'textarea',
					'instructions' => 'textarea',
				),
				'description' => 'Take payments in person via checks. This offline gateway can also be useful to test purchases.',
			),
		'stripe_payment'  =>
			array(
				'name'        => __( 'Stripe payments', 'wp-fundraising' ),
				'setup'       => array(
					'basic_information'    => 'headding',
					'title'                => 'input',
					'description'          => 'textarea',
					'instructions'         => 'textarea',
					'image_url'            => 'input',
					'stripe_sandbox'       => 'checkbox',
					'test_api_keys'        => 'headding',
					'test_publishable_key' => 'input',
					'test_secret_key'      => 'input',
					'live_api_keys'        => 'headding',
					'live_publishable_key' => 'input',
					'live_secret_key'      => 'input',
					/*'redirect_page_setup' => 'headding', 'success_page' => 'dropdown', 'sub_headding' => 'After success payment then redirect to this page.', 'cancel_page' => 'dropdown', 'sub_headding_cencel' => 'After cancel payment then redirect to this page.'*/
				),
				'description' => 'Stripe works by adding payment fields on the checkout and then sending the details to Stripe for verification. <a href="https://dashboard.stripe.com/register" target="_blank">Sign up </a> for a Stripe account, and <a target="_blank" href="https://dashboard.stripe.com/account/apikeys">get your Stripe account keys </a>.',
			),
	);

	return $arrayPayment;
}

