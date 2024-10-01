<?php

namespace WfpFundraising\Apps;

defined( 'ABSPATH' ) || exit;

use WfpFundraising\Apps\Settings as Settings;
use WfpFundraising\Utilities\Helper;

/**
 * Class Name : Content - This access for public page
 * Class Type : Normal class
 *
 * initiate all necessary classes, hooks, configs
 *
 * @since 1.0.0
 * @access Public
 */
class Content {

	// declare custom post type here
	const post_type = 'wp-fundraising';

	const post_type_review = 'wfp-review';

	// donation table name
	const table_name = 'wdp_fundraising';

	private static $instance;

	public $user_id = 0;


	/**
	 * Construct the cpt object
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function _init( $load = true ) {

		if ( $load ) {
			$user_id = get_current_user_id();
			// Load css file in front-ent
			add_action( 'wp_enqueue_scripts', array( $this, 'wfp_donation_css_loader_public' ) );

			// action init rest
			add_action( 'init', array( $this, 'wfp_init_rest' ) );

			// active donatation message from paypal
			add_action( 'init', array( $this, 'wfp_donate_return_message' ) );

			// add shortcode options
			add_shortcode( 'wfp-forms', array( $this, 'wfp_fund_donation_shortcode' ) );

			/*
			 * Sample short code for login-registration
			 *
			 * [wfp_fundraising_form]
			 * [wfp_fundraising_form login="yes" register="yes" modal="no"]
			 * [wfp_fundraising_form login="yes" register="yes" modal="yes"]
			 * [wp_fundraising_form modal="yes" id="" class="" style="" btn_text="Sign in"]
			 */
			add_shortcode( 'wfp_fundraising_form', array( $this, 'wfp_auth_form_shortcode' ) );
			add_shortcode( 'wfp-auth-form', array( $this, 'wfp_auth_form_shortcode' ) );  // giving legacy support ; this line should be deleted after another one or two version later

			// add shortcode for dashboard page
			add_shortcode( 'wfp-dashboard', array( $this, 'wfp_dashboard_content_shortcode' ) );

			// add shortcode for success page
			add_shortcode( 'wfp-success', array( $this, 'wfp_success_content_shortcode' ) );
			// add shortcode for checkout page
			add_shortcode( 'wfp-checkout', array( $this, 'wfp_checkout_content_shortcode' ) );
			// add shortcode for cancel page
			add_shortcode( 'wfp-cancel', array( $this, 'wfp_cancel_content_shortcode' ) );
			// add shortcode for campign page
			add_shortcode( 'wfp-campaign', array( $this, 'wfp_list_campaign_shortcode' ) );

			add_shortcode( 'wfp-donation-in-popup', array( $this, 'wfp_popup' ) );

			// single page template
			add_filter( 'single_template', array( $this, 'wfp_single_page' ) );

			// dashboard content modify hook
			add_filter( 'the_content', array( $this, 'wfp_content_replace_for_dashboard_page' ) );

			// checkout content modify hook
			add_filter( 'the_content', array( $this, 'wfp_content_replace_for_checkout_page' ) );

			// list of campaign content modify hook
			add_filter( 'the_content', array( $this, 'wfp_content_replace_for_campaign_page' ) );

			// add meta tag in head of html
			add_action( 'wp_head', array( $this, 'wfp_insert_fb_in_head' ), 5 );

			add_filter( 'wfp_single_social_providers', array( $this, 'add_share_link' ) );
			// add_filter('wp_nav_menu_items', [ $this, 'wfp_add_dashboard_page_menu'], 10, 2);
		}
	}


	/**
	 * Custom Post type : static method
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string
	 */
	public static function post_type() {
		return self::post_type;
	}


	/**
	 * Custom Post type : static method
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $table
	 *
	 * @return string
	 */
	public static function wfp_donate_table( $table = '' ) {
		global $wpdb;

		return empty( $table ) ? $wpdb->prefix . self::table_name : $wpdb->prefix . $table;
	}


	// share link added
	public function add_share_link( $link ) {
		unset( $link['linkedin'] );

		$link['link'] = array(
			'label'   => 'Copy Link',
			'onclick' => 'wfp_copy_link(this)',
			'url'     => '',
			'params'  => array( 'url' => '[%url%]' ),
			'icon'    => 'met-social met-social-link',
		);

		return $link;
	}


	/**
	 * Donate wfp_insert_fb_in_head() .
	 * Method Description: Add meta data for share facebook
	 *
	 * @since 1.0.0
	 * @access for public
	 */
	public function wfp_insert_fb_in_head() {
		global $post;
		if ( get_post_type( $post ) === self::post_type() && is_single() ) {
			if ( ! is_singular() ) {
				return;
			}
			$current_id = get_current_user_id();
			$user       = get_userdata( $current_id );
			$userName   = 'xpeedstudio';
			if ( is_object( $user ) ) {
				$userName = isset( $user->data->user_nicename ) ? $user->data->user_nicename : '';
			}
			$descrpition = '';
			if ( strlen( $post->post_excerpt ) > 2 ) {
				$descrpition = $post->post_excerpt;
			}
			$meta  = '';
			$meta .= '<meta property="fb:admins" content="' . $userName . '"/>';
			$meta .= '<meta property="og:title" content="' . get_the_title() . '"/>';
			$meta .= '<meta property="og:type" content="article"/>';
			$meta .= '<meta property="og:description" content="' . $descrpition . '"/>';
			$meta .= '<meta property="og:url" content="' . get_permalink() . '"/>';
			$meta .= '<meta property="og:site_name" content="' . get_bloginfo() . '"/>';
			if ( ! has_post_thumbnail( $post->ID ) ) {
				$gallery_array = explode( ',', get_post_meta( $post->ID, 'wfp_portfolio_gallery', true ) );
				if ( is_array( $gallery_array ) && sizeof( $gallery_array ) ) {
					$default_image = wp_get_attachment_thumb_url( $gallery_array[0] );
					$meta         .= '<meta property="og:image" content="' . $default_image . '"/>';
				}
			} else {
				$thumbnail_src                        = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' );
				! is_bool( $thumbnail_src ) && $meta .= '<meta property="og:image" content="' . esc_attr( $thumbnail_src[0] ) . '"/>';
			}
			echo wp_kses( $meta, \WfpFundraising\Utilities\Utils::get_kses_array() );
		}
	}


	public function wfp_single_page( $single ) {
		global $post;
		$formSetting     = $formGoalData = $multiPleData = $getMetaData = array();
		$donation_format = 'donation';

		if ( get_post_type( $post ) === self::post_type() && is_single() && ( ( $post->post_status === 'publish' ) || is_preview() ) ) {

			if ( file_exists( \WFP_Fundraising::plugin_dir() . 'views/public/fundraising/single-page/single-fundraising.php' ) ) {
				// post meta of form data

				global $getMetaData;
				$metaKey      = 'wfp_form_options_meta_data';
				$metaDataJson = get_post_meta( $post->ID, $metaKey, false );
				$getMetaData  = json_decode( json_encode( end( $metaDataJson ), JSON_UNESCAPED_UNICODE ) );

				global $donation_format;
				$donation_format = isset( $getMetaData->donation->format ) ? $getMetaData->donation->format : 'donation';

				// setting data
				global $formSetting;
				$formSetting = isset( $getMetaData->form_settings ) ? $getMetaData->form_settings : array();

				// form goal data
				global $formGoalData;
				$formGoalData = isset( $getMetaData->goal_setup ) ? $getMetaData->goal_setup : (object) array(
					'enable'    => 'No',
					'goal_type' => 'terget_goal',
				);

				global $multiPleData;
				$multiPleData = isset( $getMetaData->pledge_setup->multi->dimentions ) && sizeof( $getMetaData->pledge_setup->multi->dimentions ) ? $getMetaData->pledge_setup->multi->dimentions : array();

				global $formDesignData;
				$formDesignData = isset( $getMetaData->form_design ) ? $getMetaData->form_design : (object) array(
					'styles'          => 'all_fields',
					'continue_button' => 'Continue',
					'submit_button'   => 'Donate Now',
				);

				global $recentDonation, $wpdb;

				$table          = self::table_name;
				$recentDonation = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . "wdp_fundraising WHERE form_id = %d AND status IN('Active')", $post->ID ) );

				global $fromWhere;
				$fromWhere = 'single_page_view';

				// theme directory for template redirect..

				$files_theme = trailingslashit( get_template_directory() ) . 'wp-fundraising/single-page/single-fundraising.php';
				if ( file_exists( $files_theme ) ) {
					return $files_theme;
				}

				// default template
				return \WFP_Fundraising::plugin_dir() . 'views/public/fundraising/single-page/single-fundraising.php';
			}
		}

		return $single;
	}


	/**
	 * Donate wfp_init_rest .
	 * Method Description: load rest api
	 *
	 * @since 1.0.0
	 * @access for public
	 */
	public function wfp_init_rest() {
		add_action(
			'rest_api_init',
			function() {
				register_rest_route(
					'xs-donate-form',
					'/donate-submit/(?P<formid>\w+)/',
					array(
						'methods'             => 'POST',
						'callback'            => array( $this, 'wfp_action_rest_donate_submit' ),
						'permission_callback' => '__return_true',
					)
				);

				register_rest_route(
					'xs-donate-form',
					'/payment-redirect/(?P<id>\w+)/',
					array(
						'methods'             => 'GET',
						'callback'            => array( $this, 'wfp_action_rest_payment_redirect' ),
						'permission_callback' => '__return_true',
					)
				);

				register_rest_route(
					'xs-fundraising-form',
					'/campaign-submit/(?P<formid>\w+)/',
					array(
						'methods'             => 'POST',
						'callback'            => array( $this, 'wfp_action_rest_campaign_submit_by_user' ),
						'permission_callback' => '__return_true',
					)
				);

				register_rest_route(
					'xs-profile-form',
					'/billing-submit/(?P<formid>\w+)/',
					array(
						'methods'             => 'POST',
						'callback'            => array( $this, 'wfp_action_rest_profile_submit_by_user' ),
						'permission_callback' => '__return_true',
					)
				);

				register_rest_route(
					'xs-password-form',
					'/password-submit/(?P<formid>\w+)/',
					array(
						'methods'             => 'POST',
						'callback'            => array( $this, 'wfp_action_rest_password_submit_by_user' ),
						'permission_callback' => '__return_true',
					)
				);

				register_rest_route(
					'xs-login-form',
					'/user-login/(?P<formid>\w+)/',
					array(
						'methods'             => 'POST',
						'callback'            => array( $this, 'wfp_action_rest_login_user' ),
						'permission_callback' => '__return_true',
					)
				);

				register_rest_route(
					'xs-register-form',
					'/user-register/(?P<formid>\w+)/',
					array(
						'methods'             => 'POST',
						'callback'            => array( $this, 'wfp_action_rest_register_user' ),
						'permission_callback' => '__return_true',
					)
				);

				register_rest_route(
					'woc-redirect',
					'add-to-cart',
					array(
						'methods'             => 'POST',
						'callback'            => array( $this, 'wfp_action_woc_redirect' ),
						'permission_callback' => '__return_true',
					)
				);

				register_rest_route(
					'wfp-xs-auth',
					'/login/',
					array(
						'methods'             => 'POST',
						'callback'            => array( $this, 'wfp_sh_login' ),
						'permission_callback' => '__return_true',
					)
				);

				register_rest_route(
					'wfp-xs-auth',
					'/register/',
					array(
						'methods'             => 'POST',
						'callback'            => array( $this, 'wfp_sh_register' ),
						'permission_callback' => '__return_true',
					)
				);

				register_rest_route(
					'xs-review-form',
					'/user-review/(?P<formid>\w+)/',
					array(
						'methods'             => 'POST',
						'callback'            => array( $this, 'wfp_action_rest_user_review' ),
						'permission_callback' => '__return_true',
					)
				);

				register_rest_route(
					'xs-update-form',
					'/user-update/(?P<formid>\w+)/',
					array(
						'methods'             => 'POST',
						'callback'            => array( $this, 'wfp_action_rest_user_update' ),
						'permission_callback' => '__return_true',
					)
				);

				// delete review data
				register_rest_route(
					'xs-review-form',
					'/delete-review/(?P<formid>\w+)/',
					array(
						'methods'             => 'GET',
						'callback'            => array( $this, 'wfp_action_rest_review_delete' ),
						'permission_callback' => '__return_true',
					)
				);

				// update review data
				register_rest_route(
					'xs-review-form',
					'/update-review/(?P<formid>\w+)/',
					array(
						'methods'             => 'GET',
						'callback'            => array( $this, 'wfp_action_rest_review_update' ),
						'permission_callback' => '__return_true',
					)
				);
			},
			11
		);
	}

	public function wfp_action_rest_payment_redirect( \WP_REST_Request $request ) {

		if ( empty( $request['nonce'] ) || ! wp_verify_nonce( $request['nonce'], 'wp_rest' ) ) {
			return;
		}

		$formId = isset( $request['formid'] ) ? intval( $request['formid'] ) : 0;
		$id     = isset( $request['id'] ) ? intval( $request['id'] ) : 0;
		$type   = isset( $request['type'] ) ? sanitize_text_field( $request['type'] ) : '';

		$check_post = get_post( $formId );
		if ( ! is_object( $check_post ) && ! property_exists( $check_post, 'ID' ) ) {
			return esc_html__( 'Sorry wrong campaign.', 'wp-fundraising' );
		}

		if ( $type != 'online_payment' ) {
			return esc_html__( 'Invalid payment method.', 'wp-fundraising' );
		}

		global $wpdb;
		$forms = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'wdp_fundraising WHERE donate_id = %d', $id ) );

		if ( ! isset( $forms[0]->status ) ) {
			return esc_html__( 'Invalid payment.', 'wp-fundraising' );
		}

		if ( isset( $forms[0]->status ) && in_array( $forms[0]->status, array( 'Active', 'Delete', 'ReFunded' ) ) ) {
			return sprintf( esc_html__( 'Your payment already processed. [Code: %s]', 'wp-fundraising' ), $forms[0]->status );
		}

		// get payment Getway information
		$optinsKey      = 'wfp_payment_options_data';
		$getOptionsData = get_option( $optinsKey );
		$gateWaysData   = isset( $getOptionsData['gateways']['services'] ) ? $getOptionsData['gateways']['services'] : array();

		$getMetaGeneralOp   = get_option( \WfpFundraising\Apps\Settings::OK_GENERAL_DATA );
		$getMetaGeneral     = isset( $getMetaGeneralOp['options'] ) ? $getMetaGeneralOp['options'] : array();
		$getMetaGeneralPage = Settings::instance()->get_mapped_page_slug( $getMetaGeneralOp );
		$successPage        = isset( $getMetaGeneralPage['success'] ) ? $getMetaGeneralPage['success'] : 'wfp-success';
		$cencelPage         = isset( $getMetaGeneralPage['cancel'] ) ? $getMetaGeneralPage['cancel'] : 'wfp-cancel';

		$donate_amount = $forms[0]->donate_amount;
		$email_address = $forms[0]->email;
		$invoiceToken  = $forms[0]->invoice;

		// $status = self::instance()->wfp_get_meta($id, '_wfp_payment_status');

		self::wfp_update_meta( $id, '_wfp_payment_status', 'Review', true );

		/**
		 * AR:20201108
		 * WTF...... why key & token value is swapped for different pages!!!!
		 * Due to above wtf, we can not remove str_rot from $id value though it has no effect on integer
		 */

		if ( strlen( $successPage ) > 0 ) {
			$success_url = home_url() . '/' . $successPage . '?donatestatus=success&token=' . str_rot13( $invoiceToken ) . '&target=' . $formId . '&key=' . str_rot13( $id ) . '&nonce=' . wp_create_nonce( 'payment_nonce' );
		} else {
			$success_url = home_url() . '?donatestatus=success&token=' . str_rot13( $invoiceToken ) . '&target=' . $formId . '&key=' . str_rot13( $id ) . '&nonce=' . wp_create_nonce( 'payment_nonce' );
		}

		if ( strlen( $cencelPage ) > 0 ) {
			$cancel_url = home_url() . '/' . $cencelPage . '?donatestatus=cancel&token=' . str_rot13( $id ) . '&key=' . str_rot13( $invoiceToken ) . '&nonce=' . wp_create_nonce( 'payment_nonce' );
		} else {
			$cancel_url = home_url() . '?donatestatus=cancel&token=' . str_rot13( $id ) . '&key=' . str_rot13( $invoiceToken ) . '&nonce=' . wp_create_nonce( 'payment_nonce' );
		}

		$infoData       = isset( $gateWaysData[ $type ]['setup'] ) ? $gateWaysData[ $type ]['setup'] : array();
		$typePay        = isset( $infoData['paypal_sandbox'] ) ? $infoData['paypal_sandbox'] : 'No';
		$paypalEmail    = isset( $infoData['paypal_email'] ) ? $infoData['paypal_email'] : '';
		$invoice_prefix = isset( $infoData['invoice_prefix'] ) ? $infoData['invoice_prefix'] : 'RES-DONE-';
		$identity       = isset( $infoData['payPal_identity_token'] ) ? $infoData['payPal_identity_token'] : '';

		$dataUrl = array(
			'business'      => $paypalEmail,
			'item_name'     => $check_post->post_title,
			'item_number'   => $formId,
			'tx'            => $invoiceToken,
			'custom'        => 'RESDONE' . $formId . $id,
			'amount'        => $donate_amount,
			'no_shipping'   => 0,
			'payer_email'   => $email_address,
			'no_note'       => 1,
			'currency_code' => 'USD',
		);
		if ( strlen( $identity ) > 0 ) {
			$dataUrl['at'] = $identity;
		}
		$dataUrl['return']        = $success_url;
		$dataUrl['cancel_return'] = $cancel_url;

		$notify_url_rest = add_query_arg(
			array(
				'action'   => 'ipn-ajax-wfp',
				'form_id'  => $formId,
				'entry_id' => $id,
				'sandbox'  => $typePay,
				'nonce'    => wp_create_nonce( 'ipn-ajax-wfp' ),
			),
			admin_url( 'admin-ajax.php' )
		);

		$dataUrl['notify_url'] = $notify_url_rest;

		// paypal setup

		if ( function_exists( 'WFP_Paypal' ) ) {
			$setup = array();
			if ( $typePay == 'Yes' ) {
				$setup['_sandbox'] = true;
			}
			$payment = WFP_Paypal()->init( $setup );

			// process paypal
			$dataUrl['currency_code'] = Global_Settings::instance()->get_currency_code();
			$payment->PaymentProcess( $dataUrl );
		}

		return '';
	}

	// action for stripe payment
	public function stripe_ajax_wfp_callback( $request ) {

		if ( empty( $request['nonce'] ) || ! wp_verify_nonce( $request['nonce'], 'stripe_nonce' ) ) {
			return;
		}

		$return   = array(
			'status'  => 'error',
			'message' => '',
		);
		$token    = ! empty( $request['token'] ) ? sanitize_text_field( wp_unslash( $request['token'] ) ) : '';
		$form_id  = ! empty( $request['form_id'] ) ? intval( $request['form_id'] ) : '';
		$entry_id = ! empty( $request['entry_id'] ) ? intval( $request['entry_id'] ) : 0;
		$sandbox  = ! empty( $request['sandbox'] ) ? sanitize_text_field( wp_unslash( $request['sandbox'] ) ) : 'No';

		$status = self::wfp_get_meta( $entry_id, '_wfp_payment_status' );
		if ( ! in_array( $status, array( 'Pending', 'Review' ) ) ) {
			$return = array(
				'status'  => 'error',
				'message' => esc_html__( 'Sorry wrong campaign.', 'wp-fundraising' ),
			);

			return $return;
		}
		if ( ! function_exists( 'WFP_Stripe' ) ) {
			$return = array(
				'status'  => 'error',
				'message' => esc_html__( 'Sorry Stripe Method not Active.', 'wp-fundraising' ),
			);

			return $return;
		}

		global $wpdb;

		$forms = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'wdp_fundraising WHERE donate_id = %d', $entry_id ) );

		if ( ! isset( $forms[0]->status ) ) {
			$return = array(
				'status'  => 'error',
				'message' => esc_html__( 'Invalid payment.', 'wp-fundraising' ),
			);

			return $return;
		}

		if ( isset( $forms[0]->status ) && in_array( $forms[0]->status, array( 'Active', 'Delete', 'ReFunded' ) ) ) {
			$return = array(
				'status'  => 'error',
				'message' => sprintf( esc_html__( 'Your payment already processed. [Code: %s]', 'wp-fundraising' ), $forms[0]->status ),
			);

			return $return;
		}

		// get payment Getway information
		$optinsKey      = 'wfp_payment_options_data';
		$getOptionsData = get_option( $optinsKey );
		$gateWaysData   = isset( $getOptionsData['gateways']['services'] ) ? $getOptionsData['gateways']['services'] : array();
		$infoData       = isset( $gateWaysData['stripe_payment']['setup'] ) ? $gateWaysData['stripe_payment']['setup'] : array();

		$sandbox_enable = isset( $infoData['stripe_sandbox'] ) ? $infoData['stripe_sandbox'] : 'No';

		$sandbox = ( $sandbox_enable == 'Yes' ) ? true : false;

		$setup['_sandbox']               = $sandbox;
		$setup['stripe_secret_key']      = isset( $infoData['live_secret_key'] ) ? $infoData['live_secret_key'] : '';
		$setup['stripe_secret_key_test'] = isset( $infoData['test_secret_key'] ) ? $infoData['test_secret_key'] : '';

		$payment = WFP_Stripe()->init( $setup );

		$amount      = floatval( $forms[0]->donate_amount );
		$amount_cent = $amount * 100;

		$currency_code = Global_Settings::instance()->get_currency_code();
		$payment_data  = array(
			'token'    => $token,
			'amount'   => $amount_cent,
			'currency' => $currency_code,
		);

		$data = $payment->stripe_verify( $payment_data );

		if ( isset( $data['status'] ) && $data['status'] ) {
			$response = $data['get'];
			$txn_id   = ! empty( $response['invoice'] ) ? $response['invoice'] : $response['balance_transaction'];
			self::wfp_update_meta( $entry_id, '_wfp_payment_token', $token, true );
			self::wfp_update_meta( $entry_id, '_wfp_payment_status', 'Active', true );
			self::wfp_update_meta( $entry_id, '_wfp_payment_txn_id', $txn_id, true );
			self::wfp_update_meta( $entry_id, '_wfp_payment_txn_data', $response, true );

			global $wpdb;
			$tableName = self::wfp_donate_table();
			if ( $wpdb->update(
				$tableName,
				array( 'status' => 'Active' ),
				array(
					'status'    => 'Pending',
					'donate_id' => $entry_id,
					'form_id'   => $form_id,
				)
			) ) {

			}
			$return = array(
				'status'  => 'success',
				'message' => esc_html__( 'Thanks for your payment', 'wp-fundraising' ),
			);

			return $return;
		}
		$return = array(
			'status'  => 'error',
			'message' => esc_html__( 'System error!', 'wp-fundraising' ),
		);

		return $return;
	}


	// action for IPN Callback of Paypal
	public function ipn_ajax_wfp_callback() {

		if ( empty( $_REQUEST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) ), 'wfp_ipn_nonce' ) ) {
			return;
		}

		$form_id  = ! empty( $_REQUEST['form_id'] ) ? intval( $_REQUEST['form_id'] ) : '';
		$entry_id = ! empty( $_REQUEST['entry_id'] ) ? intval( $_REQUEST['entry_id'] ) : 0;
		$sandbox  = ! empty( $_REQUEST['sandbox'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['sandbox'] ) ) : 'No';

		$status = self::wfp_get_meta( $entry_id, '_wfp_payment_status' );
		if ( $status != 'Review' ) {
			return;
		}
		if ( ! function_exists( 'WFP_Paypal' ) ) {
			return;
		}

		$sandbox = ( $sandbox == 'Yes' ) ? true : false;
		$ipn     = WFP_Paypal()->ipn_verify( $sandbox );

		if ( isset( $ipn['status'] ) && $ipn['status'] ) {
			$txn_id   = $ipn['txn_id'];
			$response = $ipn['response'];
			self::wfp_update_meta( $entry_id, '_wfp_payment_status', 'Active', true );
			self::wfp_update_meta( $entry_id, '_wfp_payment_txn_id', $txn_id, true );
			self::wfp_update_meta( $entry_id, '_wfp_payment_txn_data', $response, true );

			global $wpdb;
			$tableName = self::wfp_donate_table();
			if ( $wpdb->update(
				$tableName,
				array( 'status' => 'Active' ),
				array(
					'status'    => 'Pending',
					'donate_id' => $entry_id,
					'form_id'   => $form_id,
				)
			) ) {
				return 'success';
			}
		}

		return;
	}


	/**
	 * Donate wfp_action_rest_donate_submit .
	 * Method Description: Action donate form submit when click this donate button.
	 *
	 * @since 1.0.0
	 *
	 * @access for public
	 */
	public function wfp_action_rest_donate_submit( \WP_REST_Request $request ) {

		// todo - submit crowd and reward...
		$data = isset( $request['xs_donate_data_submit'] ) ? map_deep( $request['xs_donate_data_submit'], 'sanitize_text_field' ) : array();

		$index            = isset( $data['index'] ) ? $data['index'] : -10;
		$pledge_id        = isset( $data['pledge_id'] ) ? $data['pledge_id'] : 0;
		$pledge_uid       = empty( $data['pledge_uid'] ) ? '0' : $data['pledge_uid'];
		$fundraising_type = isset( $data['payment_type'] ) ? $data['payment_type'] : 'donation';
		$is_reward        = ! empty( $data['pledge_uid'] );

		$return = array(
			'success' => array(),
			'error'   => array(),
		);

		$error = false;

		global $wpdb;

		$formId     = isset( $request['formid'] ) ? intval( $request['formid'] ) : 0;
		$check_post = get_post( $formId );

		if ( ! is_object( $check_post ) && ! property_exists( $check_post, 'ID' ) ) {
			$return['error'] = esc_html__( 'Sorry invalid form of Donation.', 'wp-fundraising' );

			return $return;
		}

		$metaKey      = Fundraising::WFP_MK_FORM_DATA;
		$metaDataJson = get_post_meta( $check_post->ID, $metaKey, false );

		// get settings information
		$getMetaData = json_decode( json_encode( end( $metaDataJson ), JSON_UNESCAPED_UNICODE ) );

		$opt_key        = Fundraising::WFP_OK_PAYMENT_DATA;
		$getOptionsData = get_option( $opt_key );
		$gateWaysData   = isset( $getOptionsData['gateways']['services'] ) ? $getOptionsData['gateways']['services'] : array();

		// option currency
		$metaGeneralKey   = Fundraising::WFP_OK_GENERAL_DATA;
		$getMetaGeneralOp = get_option( $metaGeneralKey );
		$getMetaGeneral   = isset( $getMetaGeneralOp['options'] ) ? $getMetaGeneralOp['options'] : array();

		$defaultCurrencyInfo = isset( $getMetaGeneral['currency']['name'] ) ? $getMetaGeneral['currency']['name'] : 'US-USD';

		$email_address   = $first_name = $last_name = '';
		$additionalFiled = isset( $data['additonal'] ) ? $data['additonal'] : array();

		// additional filed from setup data
		$multiFiledData   = isset( $getMetaData->form_content->additional->dimentions ) && sizeof( $getMetaData->form_content->additional->dimentions ) ? $getMetaData->form_content->additional->dimentions : array(
			(object) array(
				'type'     => 'text',
				'lebel'    => 'First Name',
				'default'  => '',
				'required' => 'Yes',
			),
			(object) array(
				'type'     => 'text',
				'lebel'    => 'Last Name',
				'default'  => '',
				'required' => 'Yes',
			),
			(object) array(
				'type'     => 'text',
				'lebel'    => 'Email Address',
				'default'  => '',
				'required' => 'Yes',
			),
		);
		$additionalEnable = ! isset( $getMetaData->form_content->additional ) ? 'check' : '';

		if ( isset( $getMetaData->form_content->additional->enable ) && $getMetaData->form_content->additional->enable == 'Yes' ) {
			$additionalEnable = 'check';
		}

		$metaKey  = array();
		$userMata = array();

		if ( is_array( $multiFiledData ) && sizeof( $multiFiledData ) > 0 && $additionalEnable == 'check' ) {
			foreach ( $multiFiledData as $multi ) :
				$lebelFiled = isset( $multi->lebel ) ? $multi->lebel : '';
				if ( strlen( $lebelFiled ) > 0 ) {
					$nameFiled = str_replace( array( '  ', '-', ' ', '.', ',', ':' ), '_', strtolower( trim( $lebelFiled ) ) );

					$filedValue = isset( $additionalFiled[ $nameFiled ] ) ? $additionalFiled[ $nameFiled ] : '';

					if ( preg_match_all( '/\b(first|full)\b/i', strtolower( $lebelFiled ), $matches ) ) {
						$first_name = $filedValue;
					}

					if ( preg_match_all( '/\b(last|nick)\b/i', strtolower( $lebelFiled ), $matches ) ) {
						$last_name = $filedValue;
					}

					if ( \WfpFundraising\Apps\Settings::valid_email( $filedValue ) ) {
						$email_address = $filedValue;
					}

					if ( preg_match_all( '/\b(email)\b/i', strtolower( $lebelFiled ), $matches ) ) {
						$email_address = $filedValue;
					}

					$required = isset( $multi->required ) ? $multi->required : '';
					if ( $required == 'Yes' ) {
						if ( strlen( $filedValue ) <= 0 ) :
							$return['error'] = 'Enter ' . $lebelFiled;
							$error           = true;
						endif;
					}

					$metaKey[ '_wfp_' . $nameFiled ] = $filedValue;
					// set user meta
					$userMata[ '_wfp_' . $nameFiled ] = $filedValue;
				}
			endforeach;
		}

		$xs_payment_method = isset( $data['payment_method'] ) ? trim( $data['payment_method'] ) : '';

		// check payment method
		if ( strlen( $xs_payment_method ) <= 0 ) :
			$return['error'] = esc_html__( 'Enter select payment method', 'wp-fundraising' );
			$error           = true;
		endif;

		// check donate amount
		$donate_amount        = isset( $data['donate_amount'] ) ? $data['donate_amount'] : 0;
		$addition_fees        = isset( $data['addition_fees'] ) ? $data['addition_fees'] : 0;
		$addition_fees_amount = isset( $data['addition_fees_amount'] ) ? $data['addition_fees_amount'] : 0;
		$addition_fees_type   = isset( $data['addition_fees_type'] ) ? $data['addition_fees_type'] : '';
		if ( strlen( $donate_amount ) <= 0 ) :
			$return['error'] = esc_html__( 'Enter donate amount', 'wp-fundraising' );
			$error           = true;
		endif;

		// payment_type

		$formDonation = isset( $getMetaData->donation ) ? $getMetaData->donation : array();
		if ( isset( $formDonation->set_limit->enable ) && $formDonation->set_limit->enable == 'Yes' ) {
			$minPrice = isset( $formDonation->set_limit->min_amt ) ? $formDonation->set_limit->min_amt : 0;
			$maxPrice = isset( $formDonation->set_limit->max_amt ) ? $formDonation->set_limit->max_amt : 0;

			if ( $donate_amount < $minPrice ) {
				$return['error'] = esc_html__( 'Sorry! minimum donate amount: ', 'wp-fundraising' ) . $minPrice;
				$error           = true;
			}
			if ( $donate_amount > $maxPrice && $maxPrice != 0 ) {
				$return['error'] = esc_html__( 'Sorry! max donate amount: ', 'wp-fundraising' ) . $maxPrice;
				$error           = true;
			}
		}

		/*Check user*/
		$userId = 0;
		if ( is_user_logged_in() ) {

			$userId = get_current_user_id();
		} else {

			$userId = email_exists( $email_address );

			if ( false === $userId ) {
				$userdata                  = array();
				$userdata['user_nicename'] = $first_name;
				$userdata['display_name']  = $first_name . ' ' . $last_name;
				$userdata['user_email']    = $email_address;
				$userdata['user_login']    = current( explode( '@', $email_address ) );
				$userdata['user_pass']     = '123456';

				if ( strlen( $email_address ) > 8 && Settings::valid_email( $email_address ) ) {

					/**
					 * todo - patching the old code, need time to clean-up
					 */
					$avatar = new \WfpFundraising\Utilities\Avatar();

					/**
					 * Lets check if pro plugin is active or not
					 */
					if ( did_action( 'wpfp/fundraising_pro/plugin_loaded' ) ) {

						$avatar = new \WP_Fundraising_Pro\Utils\Avatar();
					}

					$info['f_name'] = $first_name;
					$info['l_name'] = $last_name;
					$info['email']  = $email_address;

					$userId = $avatar->create_account( $info );

					if ( is_wp_error( $userId ) ) {
						$userId = 0;
					} else {
						$userStatus = 1;
						// set meta data for user information
						$userMata['nickname']           = $first_name;
						$userMata['first_name']         = $first_name;
						$userMata['last_name']          = $last_name;
						$userMata['description']        = '';
						$userMata['_wfp_email_address'] = $email_address;
						$userMata['_wfp_first_name']    = $first_name;
						$userMata['_wfp_last_name']     = $last_name;
						if ( is_array( $additionalFiled ) && sizeof( $additionalFiled ) > 0 ) :
							foreach ( $additionalFiled as $kk => $vv ) :
								$userMata[ '_wfp_' . $kk ] = $vv;
							endforeach;
						endif;

						foreach ( $userMata as $k => $v ) {
							update_user_meta( $userId, $k, $v );
						}
					}
				}
			}
		}

		if ( ! $error ) {

			$getMetaGeneralPage = Settings::instance()->get_mapped_page_slug( $getMetaGeneralOp );
			$successPage        = Settings::instance()->get_mapped_checkout_page_slug( $getMetaGeneralOp, 'success' );
			$cencelPage         = Settings::instance()->get_mapped_checkout_page_slug( $getMetaGeneralOp, 'cancel' );
			$checkoutPage       = Settings::instance()->get_mapped_checkout_page_slug( $getMetaGeneralOp );

			$tableName = self::wfp_donate_table();

			$typePay = 'No';
			$status  = 'Review';
			$url     = '';

			/**
			 * Here is one wtf reference
			 * here if online payment then status is Pending but in success page developer totally forgot about this status
			 * so when queried in database nothing come out of it and hence invalid order!!! AR:20201108
			 */
			if ( in_array( $xs_payment_method, array( Key::PAYMENT_METHOD_PAYPAL, Key::PAYMENT_METHOD_STRIPE ) ) ) {
				$status = 'Pending';
			}

			$invoiceToken = Helper::get_uuid();

			$wpInsrtr = array(
				'donate_amount'    => $donate_amount,
				'form_id'          => $formId,
				'invoice'          => $invoiceToken,
				'user_id'          => $userId,
				'email'            => $email_address,
				'fundraising_type' => $fundraising_type,
				'pledge_id'        => $pledge_id,
				'payment_gateway'  => $xs_payment_method,
				'date_time'        => gmdate( 'Y-m-d' ),
				'status'           => $status,
			);

			if ( $wpdb->insert( $tableName, $wpInsrtr ) ) :

				$id_insert                            = $wpdb->insert_id;
				$currencyInfo                         = explode( '-', $defaultCurrencyInfo );
				$metaKey['_wfp_email_address']        = $email_address;
				$metaKey['_wfp_user_id']              = $userId;
				$metaKey['_wfp_first_name']           = $first_name;
				$metaKey['_wfp_last_name']            = $last_name;
				$metaKey['_wfp_additional_data']      = $additionalFiled;
				$metaKey['_wfp_form_id']              = $formId;
				$metaKey['_wfp_donate_id']            = $id_insert;
				$metaKey['_wfp_pledge_id']            = $pledge_id;
				$metaKey['_wfp_pledge_uid']           = $pledge_uid;
				$metaKey['_wfp_order_key']            = 'wfp_' . $id_insert;
				$metaKey['_wfp_invoice']              = $invoiceToken;
				$metaKey['_wfp_order_shipping']       = 0;
				$metaKey['_wfp_order_shipping_tax']   = 0;
				$metaKey['_wfp_order_total']          = $donate_amount;
				$metaKey['_wfp_order_tax']            = 0;
				$metaKey['_wfp_addition_fees']        = $addition_fees;
				$metaKey['_wfp_addition_fees_amount'] = $addition_fees_amount;
				$metaKey['_wfp_addition_fees_type']   = $addition_fees_type;
				$metaKey['_wfp_country']              = current( $currencyInfo );
				$metaKey['_wfp_currency']             = end( $currencyInfo );
				$metaKey['_wfp_fundraising_type']     = $fundraising_type;
				$metaKey['_wfp_payment_type']         = 'default';
				$metaKey['_wfp_payment_gateway']      = $xs_payment_method;
				$metaKey['_wfp_date_time']            = gmdate( 'Y-m-d H:i:s' );
				$metaKey['_wfp_payment_status']       = $status;

				$the_curr   = $metaKey['_wfp_currency'];
				$the_amount = $the_curr . '' . $donate_amount;

				// insert meta
				foreach ( $metaKey as $k => $v ) {
					self::wfp_update_meta( $id_insert, $k, $v, true );
				}

				if ( $index >= 0 ) {
					$metaKey      = Fundraising::WFP_MK_FORM_DATA;
					$metaDataJson = get_post_meta( $formId, $metaKey, false );
					$getMetaData  = json_decode( json_encode( end( $metaDataJson ), JSON_UNESCAPED_UNICODE ) );
					$multiPleData = isset( $getMetaData->pledge_setup->multi->dimentions ) && sizeof( $getMetaData->pledge_setup->multi->dimentions ) ? $getMetaData->pledge_setup->multi->dimentions : array();

					if ( isset( $multiPleData[ $index ] ) ) {
						$data = json_encode( (array) $multiPleData[ $index ], JSON_UNESCAPED_UNICODE );
						update_user_meta( $userId, '_wfp_pledge_user__' . $formId . '__' . $pledge_id . '', $data );
						update_user_meta( $userId, Fundraising::WFP_OK_REWARD_PARTIAL . $formId . '__' . $pledge_uid . '', $data );
					}

					$rwd['donate_id']     = $id_insert;
					$rwd['invoice']       = $invoiceToken;
					$rwd['uuid']          = $pledge_uid;
					$rwd['user_id']       = $userId;
					$rwd['amount']        = $donate_amount;
					$rwd['pledge_amount'] = $pledge_id;
					$rwd['time']          = time();

					$reward_data                             = get_option( Fundraising::WFP_OK_REWARD_DATA );
					$reward_data[ $formId ][ $pledge_uid ][] = $rwd;

					update_option( Fundraising::WFP_OK_REWARD_DATA, $reward_data );

					$the_amount = $the_curr . '' . $donate_amount;

				}

				/**
				 * Donation is successful
				 * lets confirm it to donor
				 */
				if ( did_action( Key::FUNDRAISING_PRO_LOADED ) ) {

					\WP_Fundraising_Pro\Utils\Notifier::instance()->notify_donor( $is_reward, $email_address, $the_amount );
					\WP_Fundraising_Pro\Utils\Notifier::instance()->notify_campaign_admin( $is_reward, $formId, $invoiceToken, $id_insert, $email_address );
				}

				$otder_page = get_site_url() . '/' . $checkoutPage . '?wfporder=true&id-order=' . str_rot13( $invoiceToken ) . '&target=' . $formId;

				$rest_url = '';

				if ( $xs_payment_method == 'stripe_payment' ) {
					$infoData = isset( $gateWaysData[ $xs_payment_method ]['setup'] ) ? $gateWaysData[ $xs_payment_method ]['setup'] : array();
					$typePay  = isset( $infoData['stripe_sandbox'] ) ? $infoData['stripe_sandbox'] : 'No';

					$image_url = isset( $infoData['image_url'] ) ? $infoData['image_url'] : '';

					if ( strlen( $successPage ) > 0 ) {
						$return_url = get_site_url() . '/' . $successPage . '?donatestatus=success&token=' . str_rot13( $invoiceToken ) . '&target=' . $formId . '&key=' . str_rot13( $id_insert ) . '&nonce=' . wp_create_nonce( 'payment_nonce' );
					} else {
						$return_url = get_site_url() . '?donatestatus=success&token=' . str_rot13( $invoiceToken ) . '&target=' . $formId . '&key=' . str_rot13( $id_insert ) . '&nonce=' . wp_create_nonce( 'payment_nonce' );
					}
					if ( strlen( $cencelPage ) > 0 ) {
						$cancel_return = get_site_url() . '/' . $cencelPage . '?donatestatus=cancel&token=' . str_rot13( $id_insert ) . '&key=' . str_rot13( $invoiceToken ) . '&nonce=' . wp_create_nonce( 'payment_nonce' );
					} else {
						$cancel_return = get_site_url() . '?donatestatus=cancel&token=' . str_rot13( $id_insert ) . '&key=' . str_rot13( $invoiceToken ) . '&nonce=' . wp_create_nonce( 'payment_nonce' );
					}

					if ( strlen( trim( $image_url ) ) < 5 ) {
						$image_url = 'https://stripe.com/img/documentation/checkout/marketplace.png';
					}
					if ( $typePay == 'Yes' ) {
						$keys = isset( $infoData['test_publishable_key'] ) ? $infoData['test_publishable_key'] : '';
					} else {
						$keys = isset( $infoData['live_publishable_key'] ) ? $infoData['live_publishable_key'] : '';
					}

					$curr = explode( '-', $defaultCurrencyInfo );
					unset( $curr[0] );
					$def = implode( '-', $curr );

					$return['success']['sandbox']       = $typePay;
					$return['success']['image_url']     = $image_url;
					$return['success']['return_url']    = $return_url;
					$return['success']['cancel_return'] = $cancel_return;
					$return['success']['name_post']     = $check_post->post_title;
					$return['success']['description']   = $check_post->ID;
					$return['success']['amount']        = $donate_amount;
					$return['success']['entry_id']      = $id_insert;
					$return['success']['form_id']       = $formId;
					$return['success']['currency_code'] = $def;
					$return['success']['keys']          = $keys;
					$return['success']['email']         = $email_address;

					$rest_url = get_site_url();

				} elseif ( $xs_payment_method == 'online_payment' ) {
					$rest_url = get_rest_url( null, 'xs-donate-form/payment-redirect/' . $id_insert . '/?type=' . $xs_payment_method . '&formid=' . $formId . '&nonce=' . wp_create_nonce( 'wp_rest' ) );
				}

				$return['success']['message']    = esc_html__( 'Successfully donation. ', 'wp-fundraising' );
				$return['success']['type']       = $xs_payment_method;
				$return['success']['url']        = $rest_url;
				$return['success']['check_id']   = $invoiceToken;
				$return['success']['order_page'] = $otder_page;
			else :
				$return['error'] = esc_html__( 'Something is wrong', 'wp-fundraising' );
			endif;
		}

		return $return;
	}


	// return error message
	public function wfp_donate_return_message() {

		if ( empty( $_GET['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['nonce'] ) ), 'payment_nonce' ) ) {
			return;
		}

		$returnData      = isset( $_GET['donatestatus'] ) ? sanitize_text_field( wp_unslash( $_GET['donatestatus'] ) ) : '';
		$returnDatatoken = isset( $_GET['token'] ) ? str_rot13( sanitize_text_field( wp_unslash( $_GET['token'] ) ) ) : 0;
		$returnkey       = isset( $_GET['key'] ) ? str_rot13( sanitize_text_field( wp_unslash( $_GET['key'] ) ) ) : 0;
		if ( in_array( $returnData, array( 'success', 'cancel' ) ) && ! empty( $returnDatatoken ) ) {
			global $wpdb;
			$tableName = self::wfp_donate_table();

			if ( $returnData == 'success' ) {

			} elseif ( $returnData == 'cancel' ) {
				if ( $wpdb->update(
					$tableName,
					array( 'status' => 'DeActive' ),
					array(
						'status'    => 'Pending',
						'donate_id' => $returnDatatoken,
						'invoice'   => $returnkey,
					)
				) ) {

				}
			}
		}
	}


	/**
	 * Donate wfp_action_rest_campaign_submit_by_user .
	 * Method Description: Campaign form submit from Dashboard
	 *
	 * todo - campaign create from front-end
	 *
	 * @since 1.0.0
	 * @access for public
	 */
	public function wfp_action_rest_campaign_submit_by_user( \WP_REST_Request $request ) {

		$return       = array(
			'success' => array(),
			'error'   => array(),
		);
		$post_id      = 0;
		$userId       = get_current_user_id();
		$p_title      = '';
		$notify_admin = false;

		if ( empty( $userId ) ) {
			$return['error'] = __( 'Something error.', 'wp-fundraising' );
			return $return;
		}

		$formId      = isset( $request['formid'] ) ? intval( $request['formid'] ) : 0;
		$post        = (array) isset( $request['campaign_post'] ) ? map_deep( $request['campaign_post'], 'sanitize_text_field' ) : array();
		$meta_post   = (array) isset( $request['campaign_meta_post'] ) ? map_deep( $request['campaign_meta_post'], 'sanitize_text_field' ) : array();
		$update_post = isset( $request['update_post'] ) ? intval( $request['update_post'] ) : 0;

		if ( $update_post > 0 ) {

			$upd = get_post( $update_post );

			if ( isset( $upd->post_author ) && $upd->post_author == get_current_user_id() ) {

				if ( ! in_array( $upd->post_status, array( 'draft', 'publish', 'pending' ) ) ) {

					$return['error'] = __( 'Sorry could\'t update this campaign', 'wp-fundraising' );

					return $return;
				}

				$post['ID'] = $update_post;
				$post_id    = $update_post;
				wp_update_post( $post, false );

			}
		} else {

			/**
			 * New campaign request
			 */
			$post['post_type']   = self::post_type();
			$post['post_status'] = Key::WP_POST_STATUS_PENDING;
			$p_title             = $post['post_title'];

			$post_id = (int) wp_insert_post( $post );

			update_post_meta( $post_id, '__wfp_campaign_status', Key::WP_POST_STATUS_PENDING );

			/**
			 * For woo-commerce payment method
			 * so it does not add shipping cost to it
			 */
			update_post_meta( $post_id, '_virtual', 'yes' );

			$notify_admin = true;
		}

		if ( $post_id > 0 ) {

			/**
			 * added post categories
			 */
			$getCategories = isset( $meta_post['post_category'] ) ? (array) $meta_post['post_category'] : array();

			if ( is_array( $getCategories ) && ! empty( $getCategories ) ) {
				wp_set_object_terms( $post_id, $getCategories, 'wfp-categories', false );
			}

			/**
			 * added tags
			 */
			$getTags = isset( $meta_post['post_tags'] ) ? (string) $meta_post['post_tags'] : '';
			$getTags = explode( ',', $getTags );

			if ( ! empty( $getTags ) ) {
				wp_set_object_terms( $post_id, $getTags, 'wfp-tags', true );
			}

			$generateMeta = array();

			// step donation
			$generateMeta['donation']['format']              = Key::WFP_DONATION_TYPE_CROWED;
			$generateMeta['donation']['type']                = 'fixed-lebel';
			$generateMeta['donation']['multi']['dimentions'] = array(
				(object) array(
					'price' => '1.00',
					'lebel' => 'Basic',
				),
			);
			$generateMeta['donation']['fixed']['price']      = isset( $meta_post['donation']['fixed']['price'] ) ? $meta_post['donation']['fixed']['price'] : 1;
			$generateMeta['donation']['fixed']['lebel']      = '';
			$generateMeta['donation']['display']             = 'boxed';

			$setLimitMin = (float) isset( $meta_post['set_limit']['min_amt'] ) ? $meta_post['set_limit']['min_amt'] : 0;
			$setLimitMax = (float) isset( $meta_post['set_limit']['max_amt'] ) ? $meta_post['set_limit']['max_amt'] : 0;

			$generateMeta['donation']['set_limit']['enable']  = ( $setLimitMin > 0 || $setLimitMax > 0 ) ? 'Yes' : 'No';
			$generateMeta['donation']['set_limit']['min_amt'] = $setLimitMin;
			$generateMeta['donation']['set_limit']['max_amt'] = $setLimitMax;
			$generateMeta['donation']['set_limit']['details'] = '';

			$generateMeta['donation']['set_add_fees']['enable']      = 'No';
			$generateMeta['donation']['set_add_fees']['fees_amount'] = 0;
			$generateMeta['donation']['set_add_fees']['fees_type']   = 'percentage';
			$generateMeta['donation']['set_add_fees']['fees_label']  = '';

			// step form design
			$generateMeta['form_design']['styles']          = 'all_fields';
			$generateMeta['form_design']['continue_button'] = 'Continue';
			$generateMeta['form_design']['submit_button']   = 'Donate Now';
			$generateMeta['form_design']['custom_class']    = '';
			$generateMeta['form_design']['custom_id']       = '';

			// form content
			$generateMeta['form_content']['enable']                   = 'No';
			$generateMeta['form_content']['content_position']         = 'after-form';
			$generateMeta['form_content']['content']                  = '';
			$generateMeta['form_content']['additional']['enable']     = 'Yes';
			$generateMeta['form_content']['additional']['dimentions'] = \WfpFundraising\Apps\Settings::default_addition_filed();

			// goal setup
			$metaDisplayKey   = 'wfp_display_options_data';
			$getMetaDisplayOp = get_option( $metaDisplayKey );

			$formGoalData    = isset( $getMetaDisplayOp['goal_setup'] ) ? $getMetaDisplayOp['goal_setup'] : array(
				'goal_type'       => 'terget_goal',
				'bar_style'       => 'line_bar',
				'backers'         => 'Yes',
				'bar_display_sty' => 'amount_show',
				'bar_color'       => '#007bff',
			);
			$formSettingData = isset( $getMetaDisplayOp['form_settings'] ) ? $getMetaDisplayOp['form_settings'] : array();

			if ( isset( $meta_post['goal_setup']['goal_type'] ) && empty( $meta_post['goal_setup']['goal_type'] ) ) {
				$generateMeta['goal_setup'] = (object) $formGoalData;
			} else {
				$generateMeta['goal_setup']['enable']          = 'Yes';
				$generateMeta['goal_setup']['goal_type']       = isset( $meta_post['goal_setup']['goal_type'] ) ? $meta_post['goal_setup']['goal_type'] : 'terget_goal';
				$generateMeta['goal_setup']['bar_style']       = isset( $formGoalData['bar_style'] ) ? $formGoalData['bar_style'] : 'line_bar';
				$generateMeta['goal_setup']['bar_display_sty'] = isset( $formGoalData['bar_display_sty'] ) ? $formGoalData['bar_display_sty'] : 'amount_show';
				$generateMeta['goal_setup']['bar_color']       = isset( $formGoalData['bar_display_sty'] ) ? $formGoalData['bar_display_sty'] : '#007bff';
				$generateMeta['goal_setup']['terget']          = isset( $meta_post['goal_setup']['terget'] ) ? $meta_post['goal_setup']['terget'] : array();

			}
			if ( isset( $meta_post['goal_setup']['terget']['message'] ) && strlen( $meta_post['goal_setup']['terget']['message'] ) > 2 ) {
				$generateMeta['goal_setup']['terget']['enable'] = 'Yes';
			}
			// pledge setup
			$generateMeta['pledge_setup'] = isset( $meta_post['pledge_setup'] ) ? $meta_post['pledge_setup'] : array();

			// form settings
			$generateMeta['form_settings'] = $formSettingData;
			if ( isset( $meta_post['form_settings']['contributor']['enable'] ) ) {
				$generateMeta['form_settings']['contributor']['enable'] = 'Yes';
			}
			if ( isset( $meta_post['form_settings']['single_review']['enable'] ) ) {
				$generateMeta['form_settings']['single_review']['enable'] = 'Yes';
			}

			if ( isset( $meta_post['form_settings']['single_updates']['enable'] ) ) {
				$generateMeta['form_settings']['single_updates']['enable'] = 'Yes';
			}

			// Update country location
			isset( $meta_post['form_settings']['location']['country'] ) && $generateMeta['form_settings']['location']['country'] = $meta_post['form_settings']['location']['country'];

			// Update country location address
			isset( $meta_post['form_settings']['location']['country'] ) && $generateMeta['form_settings']['location']['address'] = $meta_post['form_settings']['location']['address'];

			// forms terms
			$generateMeta['form_terma'] = (object) array(
				'enable'           => 'No',
				'content_position' => 'before-submit-button',
			);

			// form meta key
			$metaKey = 'wfp_form_options_meta_data';
			// meta post data modify. Save meta optins data
			update_post_meta( $post_id, $metaKey, $generateMeta );

			// set new key type of form [donation or croudfounding]
			$metaKeyType = 'wfp_founding_form_format_type';
			$formatType  = isset( $generateMeta['donation']['format'] ) ? $generateMeta['donation']['format'] : 'crowdfunding';
			update_post_meta( $post_id, $metaKeyType, $formatType );

			// set user current post user update user
			$metaKeyUserUpdate = 'wfp_founding_form_update_user';
			$metaUserJson      = get_post_meta( $post_id, $metaKeyUserUpdate, false );
			$metaUserJson      = array(
				'date' => time(),
				'user' => get_current_user_id(),
			);
			update_post_meta( $post_id, $metaKeyUserUpdate, $metaUserJson );

			$newupload = array();

			// attatchment type
			if ( isset( $meta_post['attatch_type'] ) ) {
				if ( $meta_post['attatch_type'] == 'image' ) {

					if ( isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' == $_SERVER['REQUEST_METHOD'] ) {
						$files = isset( $_FILES['wfp_files_upload'] ) ? map_deep( $_FILES['wfp_files_upload'], 'sanitize_text_field' ) : array(); //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash -- If we use wp_unslash. Then the file tmp_name is lost
						if ( isset( $files['name'] ) && sizeof( $files['name'] ) > 0 ) {
							
							$check     = isset( $request['wfp_uploaded'] ) ? map_deep( $request['wfp_uploaded'], 'sanitize_text_field' ) : array();
							foreach ( $files['name'] as $key => $value ) {
								if ( $files['name'][ $key ] ) {
									$file   = array(
										'name'     => $files['name'][ $key ],
										'type'     => $files['type'][ $key ],
										'tmp_name' => $files['tmp_name'][ $key ],
										'error'    => $files['error'][ $key ],
										'size'     => $files['size'][ $key ],
									);
									$_FILES = array( 'wfp_files_upload' => $file );
									foreach ( $_FILES as $file => $array ) {
										$fileID = str_replace( ' ', '_', $files['name'][ $key ] );
										if ( in_array( $fileID, $check ) ) {
											$newupload1 = $this->my_handle_attachment( $file, $post_id, true );
											if ( $newupload1 > 0 ) {
												$newupload[] = $newupload1;
											}
										}
									}
								}
							}

							if ( ! empty( $newupload ) ) {
								$existing_data_ttt = get_post_meta( $post_id, 'wfp_portfolio_gallery', true );
								if($existing_data_ttt){
									$new_data_to_update = array_merge(explode( ',', $existing_data_ttt ), $newupload);
								} else {
									$new_data_to_update = $newupload;
								}

								update_post_meta( $post_id, 'wfp_portfolio_gallery', implode( ',', $new_data_to_update ) );
							}
						}
					}
				} elseif ( $meta_post['attatch_type'] == 'video' ) {
					$videoUrl = isset( $meta_post['wfp_featured_video_url'] ) ? $meta_post['wfp_featured_video_url'] : '';
					update_post_meta( $post_id, 'wfp_featured_video_url', $videoUrl );
				}
				
				$updateImage = isset( $request['wfp_uploaded_update'] ) ? map_deep( $request['wfp_uploaded_update'], 'sanitize_text_field' ) : array();
				
				if(!empty($updateImage) || !empty($newupload)){

					if(isset($newupload) && is_array($newupload)){
						$updateImage = array_merge( $updateImage, $newupload );
					}

					update_post_meta( $post_id, '_thumbnail_id', reset( $updateImage ) );
					update_post_meta( $post_id, 'wfp_portfolio_gallery', implode( ',', $updateImage ) );


				} else {
					delete_post_meta( $post_id, 'wfp_portfolio_gallery');
					delete_post_meta( $post_id, '_thumbnail_id');
				}
			}

			if ( $update_post > 0 ) {
				$return['success'] = esc_html__( 'Successfully Update this Campaign.', 'wp-fundraising' );
			} else {
				$return['success'] = esc_html__( 'Successfully Submitted Campaign for Review.', 'wp-fundraising' );
			}

			/**
			 * Notify admin a campaign has been submitted for review
			 */
			if ( $notify_admin ) {

				/**
				 * This is a pro feature, so
				 */
				if ( did_action( Key::FUNDRAISING_PRO_LOADED ) ) {

					\WP_Fundraising_Pro\Utils\Notifier::instance()->notify_admin_new_campaign( $p_title, $post_id );
				}
			}

			return $return;
		}

		$return['error'] = __( 'Invalid Campaign.', 'wp-fundraising' );

		return $return;
	}


	public function my_handle_attachment( $file_handler, $post_id, $set_thu = false ) {

		if ( isset( $_FILES[ $file_handler ]['error'] ) && $_FILES[ $file_handler ]['error'] !== UPLOAD_ERR_OK ) {
			__return_false();
		}

		require_once ABSPATH . 'wp-admin' . '/includes/image.php';
		require_once ABSPATH . 'wp-admin' . '/includes/file.php';
		require_once ABSPATH . 'wp-admin' . '/includes/media.php';

		$attach_id = (int) media_handle_upload( $file_handler, $post_id );
		if ( $set_thu ) {
			update_post_meta( $post_id, '_thumbnail_id', $attach_id );
		}

		return $attach_id;
	}


	/**
	 * Donate wfp_action_rest_profile_submit_by_user .
	 * Method Description: Profile form submit from Dashboard by the current user.
	 *
	 * @since 1.0.0
	 * @access for public
	 */
	public function wfp_action_rest_profile_submit_by_user( \WP_REST_Request $request ) {
		$return = array(
			'success' => array(),
			'error'   => array(),
		);
		$error  = false;
		$formId = isset( $request['formid'] ) ? intval( $request['formid'] ) : 0;

		$billing = (array) isset( $request['billing_post'] ) ? map_deep( $request['billing_post'], 'sanitize_text_field' ) : array();

		$userId = get_current_user_id();
		if ( empty( $userId ) || $userId == 0 ) {
			$return['error'] = __( 'Something error.', 'wp-fundraising' );

			return $return;
		}

		$allowed_billing_keys = [
			'_wfp_first_name',
			'_wfp_last_name',
			'_wfp_company_name',
			'_wfp_street_address',
			'_wfp_city',
			'_wfp_postcode',
			'_wfp_country',
			'_wfp_phone',
			'_wfp_email_address'
		];
		foreach ( $billing as $biling_key => $billing_value ) {

			// Only update user meta if the key is in the allowed list
			if ( in_array( $biling_key, $allowed_billing_keys, true ) ) {
				// update user meta
				update_user_meta( $userId, $biling_key, $billing_value );
			}
		}

		$shipping = (array) isset( $request['shipping_post'] ) ? map_deep( $request['shipping_post'], 'sanitize_text_field' ) : array();

		$allowed_shipping_keys = [
			'_wfp_first_name_ship',
			'_wfp_last_name_ship',
			'_wfp_company_name_ship',
			'_wfp_street_address_ship',
			'_wfp_city_ship',
			'_wfp_postcode_ship',
			'_wfp_country_ship',
			'_wfp_phone_ship',
			'_wfp_email_address_ship'	
		];

		foreach ( $shipping as $shipping_key => $shipping_value ) {
			// Only update user meta if the key is in the allowed list
			if ( in_array( $shipping_key, $allowed_shipping_keys, true ) ) {
				// update user meta
				update_user_meta( $userId, $shipping_key, $shipping_value );
			}
		}

		$return['success'] = __( 'Successfully Update your Information.', 'wp-fundraising' );

		return $return;
	}


	/**
	 * Donate wfp_action_rest_password_submit_by_user .
	 * Method Description: user password modify
	 *
	 * @since 1.0.0
	 * @access for public
	 */
	public function wfp_action_rest_password_submit_by_user( \WP_REST_Request $request ) {

		$return = array(
			'success' => array(),
			'error'   => array(),
		);
		$userId = get_current_user_id();

		if ( $userId == 0 || $userId == '' ) {
			$return['error'] = __( 'Sorry you could not able to update password.', 'wp-fundraising' );

			return $return;
		}

		$user_info    = get_userdata( $userId );
		$userPassword = isset( $user_info->user_pass ) ? $user_info->user_pass : '';

		$password = isset( $request['password'] ) ? map_deep( $request['password'], 'sanitize_text_field' ) : array();

		$current = isset( $password['current_pass'] ) ? $password['current_pass'] : '';

		$new = isset( $password['new_pass'] ) ? $password['new_pass'] : '';

		$confirm = isset( $password['confirm_pass'] ) ? $password['confirm_pass'] : '';

		if ( strlen( $current ) > 2 ) {
			if ( wp_check_password( $current, $userPassword, $userId ) ) {
				if ( strlen( $new ) > 5 && $new === $confirm ) {

					wp_set_password( $new, $userId );

					$return['success'] = __( 'Successfully set new password.', 'wp-fundraising' );

				} else {
					$return['error'] = __( 'Sorry don\'t match new password', 'wp-fundraising' );
				}
			} else {
				$return['error'] = __( 'Sorry current don\'t match password', 'wp-fundraising' );
			}
		} else {
			$return['error'] = __( 'Please enter current password.', 'wp-fundraising' );
		}

		return $return;
	}


	/**
	 * Donate wfp_action_rest_login_user .
	 * Method Description: create new user
	 *
	 * @since 1.0.0
	 * @access for public
	 */

	public function wfp_action_rest_login_user( \WP_REST_Request $request ) {

		$return    = array(
			'success' => array(),
			'error'   => array(),
		);
		$wfp_login = isset( $request['wfp_login'] ) ? map_deep( $request['wfp_login'], 'sanitize_text_field' ) : array();

		if ( is_user_logged_in() ) {
			$return['success'] = true;
		} else {
			$username = isset( $wfp_login['username'] ) ? $wfp_login['username'] : '';
			if ( empty( trim( $username ) ) ) {
				$return['error'] = __( 'Please enter email or username', 'wp-fundraising' );

				return $return;
			}

			$password = isset( $wfp_login['username'] ) ? $wfp_login['password'] : '';
			if ( empty( trim( $password ) ) ) {
				$return['error'] = __( 'Please enter password', 'wp-fundraising' );

				return $return;
			}

			$creds = array(
				'user_login'    => $username,
				'user_password' => $password,
				'remember'      => isset( $wfp_login['rememberme'] ) ? true : false,
			);

			$user = wp_signon( $creds, false );
			if ( is_wp_error( $user ) ) {
				$return['error'] = $user->get_error_message();
			} else {
				$return['success'] = true;
			}
		}
		return $return;
	}

	/**
	 * Donate wfp_action_rest_login_user .
	 * Method Description: create new user
	 *
	 * @since 1.0.0
	 * @access for public
	 */

	public function wfp_action_rest_register_user( \WP_REST_Request $request ) {

		$return       = array(
			'success' => array(),
			'error'   => array(),
		);
		$wfp_register = isset( $request['wfp_register'] ) ? map_deep( $request['wfp_register'], 'sanitize_text_field' ) : array();
		$userId       = 0;

		if ( is_user_logged_in() ) {
			$return['error'] = esc_html__( 'System Error', 'wp-fundraising' );
		} else {
			$email_address = isset( $wfp_register['email'] ) ? $wfp_register['email'] : '';
			$username      = isset( $wfp_register['username'] ) ? $wfp_register['username'] : '';
			$password      = isset( $wfp_register['password'] ) ? $wfp_register['password'] : '';

			if ( email_exists( $email_address ) ) {
				$userId          = email_exists( $email_address );
				$return['error'] = esc_html__( 'Already used this email', 'wp-fundraising' );
			} else {
				if ( empty( trim( $email_address ) ) ) {
					$return['error'] = esc_html__( 'Please enter email address', 'wp-fundraising' );

					return $return;
				}
				if ( empty( trim( $username ) ) ) {
					$return['error'] = esc_html__( 'Please enter your user name', 'wp-fundraising' );

					return $return;
				}

				if ( empty( trim( $password ) ) ) {
					$return['error'] = esc_html__( 'Please set your password', 'wp-fundraising' );

					return $return;
				}

				$userdata                  = array();
				$userdata['user_nicename'] = $username;
				$userdata['display_name']  = $username;
				$userdata['user_email']    = $email_address;
				$userdata['user_login']    = current( explode( '@', $email_address ) );
				$userdata['user_pass']     = $password;
				if ( strlen( $email_address ) > 8 && \WfpFundraising\Apps\Settings::valid_email( $email_address ) ) {
					$userId = wp_insert_user( $userdata );
					if ( is_wp_error( $userId ) ) {
						$return['error'] = esc_html__( 'Sorry system error. Try again.', 'wp-fundraising' );
					} else {

						$creds = array(
							'user_email'    => $email_address,
							'user_password' => $password,
							'remember'      => true,
						);

						$user              = wp_signon( $creds, false );
						$return['success'] = true;
					}
				} else {
					$return['error'] = esc_html__( 'Please enter your valid email address', 'wp-fundraising' );
				}
			}
		}
		return $return;
	}


	/**
	 * Donate wfp_action_rest_user_review .
	 * Method Description: post review for user only one review
	 *
	 * @since 1.0.0
	 * @access for public
	 */
	public function wfp_action_rest_user_review( \WP_REST_Request $request ) {
		global $wpdb;

		$return = array(
			'success' => array(),
			'error'   => array(),
		);
		$formId = isset( $request['formid'] ) ? intval( $request['formid'] ) : 0;

		$post = get_post( $formId );

		if ( ! property_exists( $post, 'ID' ) ) {
			$return['error'] = __( 'Sorry invalid campaign.', 'wp-fundraising' );

			return $return;
		}

		if ( is_user_logged_in() ) {

			$wfp_review = isset( $request['review_post'] ) ? map_deep( $request['review_post'], 'sanitize_text_field' ) : array();
			$name       = isset( $wfp_review['name'] ) ? $wfp_review['name'] : '';
			$email      = isset( $wfp_review['email'] ) ? $wfp_review['email'] : '';
			$ratting    = isset( $wfp_review['ratting'] ) ? (int) $wfp_review['ratting'] : 0;
			$summery    = isset( $wfp_review['summery'] ) ? $wfp_review['summery'] : '';
			$parent_id  = isset( $wfp_review['parent'] ) ? (int) $wfp_review['parent'] : 0;

			if ( empty( trim( $email ) ) ) {
				$return['error'] = __( 'Please enter email address', 'wp-fundraising' );

				return $return;
			}
			if ( empty( trim( $name ) ) ) {
				$return['error'] = __( 'Please enter your name', 'wp-fundraising' );

				return $return;
			}

			if ( empty( trim( $ratting ) ) ) {
				$return['error'] = __( 'Please give your ratting', 'wp-fundraising' );

				return $return;
			}

			$userId = get_current_user_id();

			// check post by title and post_author

			$postTitle   = 'Wfp Review : ' . $formId . '-' . $userId;
			$contentData = 'Name: ' . $name . '<br/> Email: ' . $email . ' <br/> Ratting: ' . $ratting . '<br/> Summery: ' . $summery;

			/*Check update data*/
			if ( $parent_id > 0 ) {

				$post_p = get_post( $parent_id );

				if ( ! property_exists( $post_p, 'ID' ) ) {
					$return['error'] = __( 'Sorry invalid review.', 'wp-fundraising' );

					return $return;
				}

				$author_id = ( property_exists( $post_p, 'post_author' ) ) ? (int) $post_p->post_author : 0;
				$post_type = ( property_exists( $post_p, 'post_type' ) ) ? $post_p->post_type : '';

				$author_id_parent = ( property_exists( $post, 'post_author' ) ) ? $post->post_author : 0;

				if ( $author_id != $userId and $userId != $author_id_parent ) {
					$return['error'] = __( 'Sorry you are not valid user.', 'wp-fundraising' );

					return $return;
				}
				// return $post_type;
				if ( $post_type != 'wfp-review' ) {
					$return['error'] = __( 'Invalid Review.', 'wp-fundraising' );

					return $return;
				}
				$update                 = array();
				$update['ID']           = $parent_id;
				$update['post_content'] = $contentData;
				$update['post_title']   = $postTitle;
				if ( wp_update_post( $update ) ) {
					$metaKey      = '__wfp_public_review_data';
					$metaDataJson = get_post_meta( $parent_id, $metaKey, true );
					$getMetaData  = json_decode( $metaDataJson, true );

					$meta_pa                  = array();
					$meta_pa['wfp_review_id'] = $formId;
					$meta_pa['wfp_user_id']   = $userId;
					$meta_pa['name']          = isset( $getMetaData['name'] ) ? $getMetaData['name'] : '';
					$meta_pa['email']         = isset( $getMetaData['email'] ) ? $getMetaData['email'] : '';
					$meta_pa['summery']       = $summery;
					$meta_pa['ratting']       = $ratting;
					$meta_pa['status']        = 'Publish';

					if ( update_post_meta( $parent_id, $metaKey, json_encode( $meta_pa, JSON_UNESCAPED_UNICODE ) ) ) {
						$return['success'] = __( 'Successfully updated review', 'wp-fundraising' );

						return $return;
					}
				} else {
					$return['error'] = __( 'Sorry system error.', 'wp-fundraising' );

					return $return;
				}
			}

			$wpdb->query( $wpdb->prepare( 'SELECT ID FROM ' . $wpdb->posts . ' WHERE post_author = %d AND post_parent = %d AND post_type = \'wfp-review\'', $userId, $formId ) );

			if ( $wpdb->num_rows ) {
				$return['error'] = __( 'Sorry already submitted review', 'wp-fundraising' );

				return $return;
			}

			// submit post data
			$postarr                 = array();
			$postarr['post_author']  = $userId;
			$postarr['post_content'] = $contentData;
			$postarr['post_title']   = $postTitle;
			$postarr['post_parent']  = $formId;
			$postarr['post_status']  = 'publish';
			$postarr['post_type']    = 'wfp-review';

			$getPostId = (int) wp_insert_post( $postarr );

			if ( ! empty( $getPostId ) ) {
				$metaKey = '__wfp_public_review_data';

				$meta_dat                  = array();
				$meta_dat['wfp_review_id'] = $formId;
				$meta_dat['wfp_user_id']   = $userId;
				$meta_dat['name']          = $name;
				$meta_dat['email']         = $email;
				$meta_dat['summery']       = $summery;
				$meta_dat['ratting']       = $ratting;
				$meta_dat['status']        = 'Publish';

				if ( update_post_meta( $getPostId, $metaKey, json_encode( $meta_dat, JSON_UNESCAPED_UNICODE ) ) ) {
					$return['success'] = __( 'Successfully submitted review', 'wp-fundraising' );

					return $return;
				}
			} else {
				$return['error'] = __( 'System error', 'wp-fundraising' );

				return $return;
			}
		} else {
			$return['error'] = __( 'Please sign in first', 'wp-fundraising' );

			return $return;
		}

		return $return;
	}


	/**
	 * Donate wfp_action_rest_review_delete .
	 * Method Description: review delete by reviewer user
	 *
	 * @since 1.0.0
	 * @access for public
	 */
	public function wfp_action_rest_review_delete( \WP_REST_Request $request ) {
		$return = array(
			'success' => array(),
			'error'   => array(),
		);

		if ( ! is_user_logged_in() ) {
			$return['error'] = __( 'Sorry you are not valid author for delete review.', 'wp-fundraising' );

			return $return;
		}

		$form_data = isset( $request['params'] ) ? sanitize_text_field( $request['params'] ) : '';
		$ex_form   = explode( '__', $form_data );
		$user_id   = isset( $ex_form[2] ) ? $ex_form[2] : 0;
		$formId    = isset( $ex_form[1] ) ? $ex_form[1] : 0;

		$post = get_post( $formId );

		if ( ! property_exists( $post, 'ID' ) ) {
			$return['error'] = __( 'Sorry invalid review.', 'wp-fundraising' );

			return $return;
		}

		$author_id = ( property_exists( $post, 'post_author' ) ) ? (int) $post->post_author : 0;
		$post_type = ( property_exists( $post, 'post_type' ) ) ? $post->post_type : '';

		$parentId         = ( property_exists( $post, 'post_parent' ) ) ? (int) $post->post_parent : 0;
		$post_parent      = get_post( $parentId );
		$author_id_parent = ( property_exists( $post_parent, 'post_author' ) ) ? (int) $post_parent->post_author : 0;

		if ( $author_id != $user_id and $user_id != $author_id_parent ) {
			$return['error'] = __( 'Sorry you are not valid user.', 'wp-fundraising' );

			return $return;
		}

		if ( $post_type != 'wfp-review' ) {
			$return['error'] = __( 'Invalid Review.', 'wp-fundraising' );

			return $return;
		}

		if ( wp_delete_post( $formId, true ) ) {
			$return['success'] = __( 'Successfully remove your review.', 'wp-fundraising' );
		} else {
			$return['error'] = __( 'System error.', 'wp-fundraising' );
		}

		return $return;
	}


	/**
	 * Donate wfp_action_rest_review_update .
	 * Method Description: review update by reviewer user
	 *
	 * @since 1.0.0
	 * @access for public
	 */
	public function wfp_action_rest_review_update( \WP_REST_Request $request ) {
		$return = array(
			'success' => array(),
			'error'   => array(),
		);

		if ( ! is_user_logged_in() ) {
			$return['error'] = __( 'Sorry you are not valid author for delete review.', 'wp-fundraising' );

			return $return;
		}

		$form_data = isset( $request['params'] ) ? sanitize_text_field( $request['params'] ) : '';
		$ex_form   = explode( '__', $form_data );
		$user_id   = isset( $ex_form[1] ) ? $ex_form[1] : 0;
		$formId    = isset( $ex_form[0] ) ? $ex_form[0] : 0;

		$post = get_post( $formId );

		if ( ! property_exists( $post, 'ID' ) ) {
			$return['error'] = __( 'Sorry invalid review.', 'wp-fundraising' );

			return $return;
		}

		$author_id = ( property_exists( $post, 'post_author' ) ) ? (int) $post->post_author : 0;
		$post_type = ( property_exists( $post, 'post_type' ) ) ? $post->post_type : '';

		$parentId         = ( property_exists( $post, 'post_parent' ) ) ? (int) $post->post_parent : 0;
		$post_parent      = get_post( $parentId );
		$author_id_parent = ( property_exists( $post_parent, 'post_author' ) ) ? (int) $post_parent->post_author : 0;

		if ( $author_id != $user_id and $user_id != $author_id_parent ) {
			$return['error'] = __( 'Sorry you are not valid user.', 'wp-fundraising' );

			return $return;
		}

		if ( $post_type != 'wfp-review' ) {
			$return['error'] = __( 'Invalid Review.', 'wp-fundraising' );

			return $return;
		}
		$metaDataJson = get_post_meta( $formId, '__wfp_public_review_data', true );
		$getMetaData  = json_decode( $metaDataJson, true );

		$return['success']['parent']  = $formId;
		$return['success']['ratting'] = isset( $getMetaData['ratting'] ) ? (int) $getMetaData['ratting'] : 0;
		$return['success']['name']    = isset( $getMetaData['name'] ) ? (string) $getMetaData['name'] : '';
		$return['success']['email']   = isset( $getMetaData['email'] ) ? (string) $getMetaData['email'] : '';
		$return['success']['summery'] = isset( $getMetaData['summery'] ) ? (string) $getMetaData['summery'] : '';

		return $return;
	}


	/**
	 * Donate wfp_action_rest_user_update .
	 * Method Description: post review for user only one review
	 *
	 * @since 1.0.0
	 * @access for public
	 */
	public function wfp_action_rest_user_update( \WP_REST_Request $request ) {
		global $wpdb;

		$return = array(
			'success' => array(),
			'error'   => array(),
		);
		$error  = false;
		$formId = isset( $request['formid'] ) ? intval( $request['formid'] ) : 0;

		$post = get_post( $formId );

		if ( ! property_exists( $post, 'ID' ) ) {
			$return['error'] = __( 'Sorry invalid campaign.', 'wp-fundraising' );

			return $return;
		}

		$userId = get_current_user_id();

		$author_id = ( property_exists( $post, 'post_author' ) ) ? $post->post_author : 0;

		if ( is_user_logged_in() && $userId == $author_id ) {

			$wfp_review = isset( $request['updates_post'] ) ? map_deep( $request['updates_post'], 'sanitize_text_field' ) : array();
			$update     = isset( $wfp_review['details'] ) ? $wfp_review['details'] : '';

			if ( empty( trim( $update ) ) ) {
				$return['error'] = __( 'Please enter your update details.', 'wp-fundraising' );

				return $return;
			}

			$postTitle = 'Wfp Update : ' . $formId . '-' . gmdate( 'Y-m-d H' );

			// submit post data
			$postarr                 = array();
			$postarr['post_author']  = $userId;
			$postarr['post_content'] = $update;
			$postarr['post_title']   = $postTitle;
			$postarr['post_parent']  = $formId;
			$postarr['post_status']  = 'publish';
			$postarr['post_type']    = 'wfp-update';

			$getPostId = (int) wp_insert_post( $postarr );

			if ( ! empty( $getPostId ) ) {
				$return['success'] = __( 'Successfully submitted update', 'wp-fundraising' );

				return $return;
			} else {
				$return['error'] = __( 'System error', 'wp-fundraising' );

				return $return;
			}
		} else {
			$return['error'] = __( 'Sorry invalid user for post campaign update.', 'wp-fundraising' );

			return $return;
		}

		return $return;
	}


	/**
	 * Donate wdp_donation_css_loader_front .
	 * Method Description: load css files in front-end page
	 *
	 * @since 1.0.0
	 * @access for public
	 */
	public function wfp_donation_css_loader_public() {

		global $post;

		// repeater
		wp_enqueue_script( 'wfp_repeater_script' );
		// sortable
		wp_enqueue_script( 'jquery-ui-sort' );

		// get general page options data
		$getMetaGeneralOp = get_option( \WfpFundraising\Apps\Settings::OK_GENERAL_DATA );

		// $screen = get_current_screen();
		$current_page_id = get_queried_object_id();

		$dashboardPage = ! empty( $getMetaGeneralOp['options']['pages']['dashboard'] ) && $current_page_id == $getMetaGeneralOp['options']['pages']['dashboard'];
		$campaignPage  = ! empty( $getMetaGeneralOp['options']['pages']['campaign'] ) && $current_page_id == $getMetaGeneralOp['options']['pages']['campaign'];
		$checkoutPage  = ! empty( $getMetaGeneralOp['options']['pages']['checkout'] ) && $current_page_id == $getMetaGeneralOp['options']['pages']['checkout'];
		$successPage   = ! empty( $getMetaGeneralOp['options']['pages']['success'] ) && $current_page_id == $getMetaGeneralOp['options']['pages']['success'];

		// donation admin css
		wp_enqueue_style( 'wfp_donation_admin_css' );

		// front-end css
		wp_enqueue_style( 'wfp_donation_public_css' );

		// pop up style
		wp_enqueue_style( 'wfp_donation_popup_public_css' );

		// fonts
		wp_enqueue_style( 'wfp_fonts' );
		wp_enqueue_style( 'wfp_met-fonts' );

		if ( $dashboardPage || $campaignPage ) {

			wp_enqueue_style( 'wfp_dashboard_css' );
			wp_enqueue_style( 'wfp_single_campaign_css' );

		} elseif ( $checkoutPage || $successPage ) {
			// checkout css
			wp_enqueue_style( 'wfp_css_checkout' );
		}

		// As from now on any page can call this using short code
		wp_enqueue_style( 'wfp_login_css' );

		// single page css
		wp_enqueue_style( 'wfp_single_css' );

		// global laibray
		wp_enqueue_style( 'wfp_css_libarey' );

		// mater css
		wp_enqueue_style( 'wfp_css_master' );

		// main js for front-end
		wp_enqueue_script( 'wfp_donation_form_script' );
		wp_localize_script(
			'wfp_donation_form_script',
			'xs_donate_url',
			array(
				'siteurl' => get_option( 'siteurl' ),
				'nonce'   => wp_create_nonce( 'wp_rest' ),
				'resturl' => get_rest_url(),
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
			)
		);

		// pop up script
		wp_enqueue_script( 'wfp_donation_popup_public_js' );

		if ( $dashboardPage || $campaignPage ) {

			// chart js
			wp_enqueue_script( 'chartjs' );

			// form js laibray
			wp_enqueue_script( 'wfp_campaign_form_step' );

			// dashboard
			wp_enqueue_script( 'wfp_dashboard_script' );
		}

		// login js for short code
		wp_enqueue_script( 'wfp_login_script' );
		wp_localize_script(
			'wfp_login_script',
			'wfp_xs_url',
			array(
				'siteurl'          => get_option( 'siteurl' ),
				'nonce'            => wp_create_nonce( 'wp_rest' ),
				'resturl'          => get_rest_url(),
				'xs_rest_login'    => get_rest_url( null, 'wfp-xs-auth/login/' ),
				'xs_rest_register' => get_rest_url( null, 'wfp-xs-auth/register/' ),
			)
		);

		if ( is_single() || is_page() ) {
			// fancybox css
			wp_enqueue_style( 'fancybox_css' );

			// fancybox js
			wp_enqueue_script( 'fancybox_js' );

			// single js
			wp_enqueue_script( 'wfp_single_script' );

		}

		wp_enqueue_script( 'wfp_easy_pie_script' );

		// stripe checkout script
		wp_enqueue_script( 'wfp_stripe_checkout_js' );

		// responsive css
		wp_enqueue_style( 'wfp_responsive_css_master' );

		/**
		 * Checking for short code dependency
		 */
		if ( is_a( $post, 'WP_Post' ) ) {

			if ( has_shortcode( $post->post_content, 'wfp-dashboard' ) || has_shortcode( $post->post_content, 'wfp-campaign' ) ) {

				/**
				 * css loading
				 */
				wp_enqueue_style( 'wfp_dashboard_css' );
				wp_enqueue_style( 'wfp_single_campaign_css' );

				/**
				 * js loading
				 */
				// chart js
				wp_enqueue_script( 'chartjs' );

				// form js laibray
				wp_enqueue_script( 'wfp_campaign_form_step' );

				// dashboard
				wp_enqueue_script( 'wfp_dashboard_script' );

			} elseif ( has_shortcode( $post->post_content, 'wfp-checkout' ) || has_shortcode( $post->post_content, 'wfp-success' ) ) {

				/**
				 * css loading
				 */
				wp_enqueue_style( 'wfp_css_checkout' );

				/**
				 * js loading
				 */

			}
		}
	}


	/**
	 * Donate wfp_fund_donation_shortcode.
	 * Method Description: Short-code options
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function wfp_fund_donation_shortcode( $atts, $content = null ) {
		// create shortcode
		$atts = shortcode_atts(
			array(
				'form-id'       => 0,
				'form-style'    => '',
				'modal'         => '',
				'class'         => '',
				'id'            => '',
				'format-style'  => '',
				'goal'          => 'Yes',
				'user'          => 'Yes',
				'category'      => 'Yes',
				'title'         => 'Yes',
				'featured'      => 'Yes',
				'is_short_code' => 'Yes',
			),
			$atts,
			'wfp-forms'
		);

		$postId = isset( $atts['form-id'] ) ? $atts['form-id'] : 0;

		if ( $postId > 0 ) {

			$post = get_post( $postId );

			if ( is_object( $post ) ) {

				$this->additionalCss = $postId;

				return $this->wfp_fund_donation_form_display( $post, $atts );
			}
		}
	}

	/**
	 * Show login and register form from short-code
	 *
	 * @author Md. Atiqur Rahman <atiqur.su@gmail.com>
	 *
	 * @param $atts
	 * @param null $content
	 *
	 * @return string
	 */
	public function wfp_auth_form_shortcode( $atts, $content = null ) {

		$atts = shortcode_atts(
			array(
				'login'          => 'yes',
				'register'       => 'no',
				'modal'          => 'yes',
				'class'          => '',
				'id'             => '',
				'style'          => '',
				'btn_text'       => __( 'Login', 'wp-fundraising' ),
				'reg_btn_text'   => __( 'Register', 'wp-fundraising' ),
				'login_btn_text' => __( 'Login Now', 'wp-fundraising' ),
			),
			$atts,
			'wfp_fundraising_form'
		);

		$classes       = $atts['class'];
		$showLogin     = strtolower( $atts['login'] ) == 'yes';
		$showRegister  = strtolower( $atts['register'] ) == 'yes';
		$show_in_modal = strtolower( $atts['modal'] ) == 'yes';
		$both_show     = $showLogin && $showRegister;
		$mdl_btn_txt   = $atts['btn_text'];

		// [wfp_fundraising_form]
		// [wfp_fundraising_form login="yes" register="yes" modal="no" ]
		// [wfp_fundraising_form login="yes" register="yes" modal="yes" ]
		// [wfp_fundraising_form modal="yes" id="" class="" style="" btn_text="Sign in"]

		ob_start();

		if ( $show_in_modal ) {
			include \WFP_Fundraising::plugin_dir() . 'views/public/auth/modal.php';

		} else {
			include \WFP_Fundraising::plugin_dir() . 'views/public/auth/form.php';
		}

		$getContent = ob_get_contents();
		ob_end_clean();

		return $getContent;
	}


	/**
	 * Donate wfp_fund_donation_form_display.
	 * Method Description: Display donation forms
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function wfp_fund_donation_form_display( $post, $atts = array(), $format_style = '' ) {

		if ( ! isset( $post->ID ) ) {
			return '';
		}

		$getPostTYpe  = $post->post_type;
		$arrayPayment = xs_payment_services();

		// require file
		if ( $getPostTYpe == self::post_type() ) {

			// get post meta data
			$metaKey      = 'wfp_form_options_meta_data';
			$metaDataJson = get_post_meta( $post->ID, $metaKey, false );
			$getMetaData  = json_decode( json_encode( end( $metaDataJson ), JSON_UNESCAPED_UNICODE ) );

			// get option for payment gateways
			$optinsKey      = 'wfp_payment_options_data';
			$getOptionsData = get_option( $optinsKey );
			$gateWaysData   = isset( $getOptionsData['gateways'] ) ? $getOptionsData['gateways'] : array();

			global $multiPleData;
			$multiPleData = isset( $getMetaData->pledge_setup->multi->dimentions ) && sizeof( $getMetaData->pledge_setup->multi->dimentions ) ? $getMetaData->pledge_setup->multi->dimentions : array();

			global $recentDonation, $wpdb;
			$recentDonation = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . "wdp_fundraising WHERE form_id = %d AND status IN('Active')", $post->ID ) );

			$amount_limit = property_exists( $getMetaData->donation, 'set_limit' ) ? $getMetaData->donation->set_limit : array();

			ob_start();
			include \WFP_Fundraising::plugin_dir() . 'views/public/donation/donation-page-short-code.php';
			$getContent = ob_get_contents();
			ob_end_clean();

			// end object data
			return $getContent;
		}
	}

	/**
	 * Donate wfp_content_replace_for_success_page.
	 * Method Description: Content replace for dashboard page
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function wfp_content_replace_for_dashboard_page( $content ) {

		$getMetaGeneralOp   = get_option( \WfpFundraising\Apps\Settings::OK_GENERAL_DATA );
		$getMetaGeneral     = isset( $getMetaGeneralOp['options'] ) ? $getMetaGeneralOp['options'] : array();
		$getMetaGeneralPage = Settings::instance()->get_mapped_page_slug( $getMetaGeneralOp );

		$dashboardPage = isset( $getMetaGeneralPage['dashboard'] ) ? $getMetaGeneralPage['dashboard'] : 'wfp-dashboard';

		if ( $dashboardPage != 'wfp-dashboard' ) {
			if ( strcmp( $dashboardPage, get_post_field( 'post_name' ) ) === 0 ) {
				if ( ! preg_match( '/\[wfp-dashboard\b/', $content ) ) {
					$content .= '[wfp-dashboard]';
				}
			}
		}

		return $content;
	}


	/**
	 * Donate wfp_content_replace_for_campaign_page.
	 * Method Description: Content replace for campaign page
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function wfp_content_replace_for_campaign_page( $content ) {

		$getMetaGeneralOp   = get_option( \WfpFundraising\Apps\Settings::OK_GENERAL_DATA );
		$getMetaGeneral     = isset( $getMetaGeneralOp['options'] ) ? $getMetaGeneralOp['options'] : array();
		$getMetaGeneralPage = Settings::instance()->get_mapped_page_slug( $getMetaGeneralOp );

		$campaignPage = isset( $getMetaGeneralPage['campaign'] ) ? $getMetaGeneralPage['campaign'] : 'wfp-campaign';
		if ( $campaignPage != 'wfp-campaign' ) {
			if ( strcmp( $campaignPage, get_post_field( 'post_name' ) ) === 0 ) {
				if ( ! preg_match( '/\[wfp-campaign\b/', $content ) ) {
					$content .= '[wfp-campaign]';
				}
			}
		}

		return $content;
	}


	/**
	 * Donate wfp_content_replace_for_checkout_page.
	 * Method Description: Content replace for checkout page
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function wfp_content_replace_for_checkout_page( $content ) {

		$getMetaGeneralOp   = get_option( \WfpFundraising\Apps\Settings::OK_GENERAL_DATA );
		$getMetaGeneral     = isset( $getMetaGeneralOp['options'] ) ? $getMetaGeneralOp['options'] : array();
		$getMetaGeneralPage = Settings::instance()->get_mapped_page_slug( $getMetaGeneralOp );

		// get payment type from options data
		$metaSetupKey = 'wfp_setup_services_data';
		$getSetUpData = get_option( $metaSetupKey );
		$paymentType  = isset( $getMetaData['services']['payment'] ) ? $getMetaData['services']['payment'] : 'default';

		$dashboardPage = isset( $getMetaGeneralPage['checkout'] ) ? $getMetaGeneralPage['checkout'] : 'wfp-checkout';

		if ( $paymentType == 'woocommerce' ) {
			// $shortCode = '[woocommerce_checkout]';
			if ( $dashboardPage != 'checkout' ) {
				if ( strcmp( $dashboardPage, get_post_field( 'post_name' ) ) === 0 ) {

					$content .= '[woocommerce_checkout]';
				}
			}
		} else {
			if ( $dashboardPage != 'wfp-checkout' ) {
				if ( strcmp( $dashboardPage, get_post_field( 'post_name' ) ) === 0 ) {
					if ( ! preg_match( '/\[wfp-checkout\b/', $content ) ) {
						$content .= '[wfp-checkout]';
					}
				}
			}
		}

		return $content;
	}


	/**
	 * Donate wfp_dashboard_content_shortcode.
	 * Method Description: Shortcode action for dashboard page.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function wfp_dashboard_content_shortcode( $atts, $content = null ) {

		// create shortcode
		$atts = shortcode_atts(
			array(
				'class' => '',
				'id'    => 'wfp-dashboard',
			),
			$atts,
			'wfp-dashboard'
		);

		$className = isset( $atts['class'] ) ? $atts['class'] : '';
		$idName    = isset( $atts['id'] ) ? $atts['id'] : 'wfp-dashboard';

		$getPage = isset( $_GET['wfp-page'] ) ? sanitize_text_field( wp_unslash( $_GET['wfp-page'] ) ) : 'dashboard'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- No action, nonce is not required

		ob_start();

		if ( is_user_logged_in() ) {

			include \WFP_Fundraising::plugin_dir() . 'views/public/fundraising/dynamic-page/dashboard.php';

		} else {

			include \WFP_Fundraising::plugin_dir() . 'views/public/fundraising/dynamic-page/login.php';
		}

		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}


	/**
	 * Donate wfp_success_content_shortcode.
	 * Method Description: Shortcode action for success page.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function wfp_success_content_shortcode( $atts, $content = null ) {
		// create shortcode
		$atts = shortcode_atts(
			array(
				'class' => '',
				'id'    => 'wfp-success',
			),
			$atts,
			'wfp-success'
		);

		$className = isset( $atts['class'] ) ? $atts['class'] : '';
		$idName    = isset( $atts['id'] ) ? $atts['id'] : 'wfp-success';

		// start object content

		ob_start();
		include \WFP_Fundraising::plugin_dir() . 'views/public/fundraising/dynamic-page/success.php';

		/*currency information*/
		$getMetaGeneralOp = get_option( \WfpFundraising\Apps\Settings::OK_GENERAL_DATA );
		$getMetaGeneral   = isset( $getMetaGeneralOp['options'] ) ? $getMetaGeneralOp['options'] : array();
		$defaultUse_space = isset( $getMetaGeneral['currency']['use_space'] ) ? $getMetaGeneral['currency']['use_space'] : 'off';

		$getPaymentId    = isset( $_GET['target'] ) ? intval( $_GET['target'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- No action, nonce is not required
		$getPaymentOrder = isset( $_GET['token'] ) ? str_rot13( sanitize_text_field( wp_unslash( $_GET['token'] ) ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- No action, nonce is not required
		$post            = get_post( $getPaymentId );

		if ( empty( $post ) ) {

			return;
		}

		$returnkey = isset( $_GET['key'] ) ? intval( str_rot13( sanitize_text_field( wp_unslash( $_GET['key'] ) ) ) ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- No action, nonce is not required

		/**
		 * Adding another status check "Pending" in query
		 * because when paypal payment is done the status is Pending!
		 */
		global $wpdb;
		$info    = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . "wdp_fundraising WHERE donate_id = %d AND invoice = %s AND status IN('Active', 'Review', 'Pending') GROUP BY (invoice) ORDER BY donate_id DESC", $returnkey, $getPaymentOrder ) );
		$info    = current( $info );
		$orderId = isset( $info->donate_id ) ? $info->donate_id : 0;

		include \WFP_Fundraising::plugin_dir() . 'views/public/fundraising/dynamic-page/order-confirm.php';

		$content = ob_get_contents();

		ob_end_clean();

		return $content;
	}


	/**
	 * Donate wfp_checkout_content_shortcode.
	 * Method Description: Shortcode action for checkout page.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function wfp_checkout_content_shortcode( $atts, $content = null ) {
		// create shortcode
		$atts = shortcode_atts(
			array(
				'class' => '',
				'id'    => 'wfp-checkout',
			),
			$atts,
			'wfp-checkout'
		);

		$className = isset( $atts['class'] ) ? $atts['class'] : '';
		$idName    = isset( $atts['id'] ) ? $atts['id'] : 'wfp-checkout';

		/*currency information*/
		$getMetaGeneralOp = get_option( \WfpFundraising\Apps\Settings::OK_GENERAL_DATA );
		$getMetaGeneral   = isset( $getMetaGeneralOp['options'] ) ? $getMetaGeneralOp['options'] : array();
		$defaultUse_space = isset( $getMetaGeneral['currency']['use_space'] ) ? $getMetaGeneral['currency']['use_space'] : 'off';

		// start object content data
		ob_start();
		if ( isset( $_GET['wfpout'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- No action, nonce is not required

			$getPaymentId = isset( $_GET['target'] ) ? intval( $_GET['target'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- No action, nonce is not required
			$post         = get_post( $getPaymentId );

			$arrayPayment = xs_payment_services();

			// get post meta data
			$metaKey      = Fundraising::WFP_MK_FORM_DATA;
			$metaDataJson = get_post_meta( $post->ID, $metaKey, false );
			$getMetaData  = json_decode( json_encode( end( $metaDataJson ), JSON_UNESCAPED_UNICODE ) );

			// get option for payment gateways
			$optinsKey      = Fundraising::WFP_OK_PAYMENT_DATA;
			$getOptionsData = get_option( $optinsKey );
			$gateWaysData   = isset( $getOptionsData['gateways'] ) ? $getOptionsData['gateways'] : array();

			include \WFP_Fundraising::plugin_dir() . 'views/public/fundraising/dynamic-page/checkout.php';

		} elseif ( isset( $_GET['wfporder'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- No action, nonce is not required
			$getPaymentId    = isset( $_GET['target'] ) ? intval( $_GET['target'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- No action, nonce is not required
			$getPaymentOrder = isset( $_GET['id-order'] ) ? str_rot13( sanitize_text_field( wp_unslash( $_GET['id-order'] ) ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- No action, nonce is not required
			$post            = get_post( $getPaymentId );

			global $wpdb;
			$info    = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . "wdp_fundraising WHERE form_id = %d AND invoice = %s AND status IN('Active', 'Review') GROUP BY (invoice) ORDER BY donate_id DESC", $getPaymentId, $getPaymentOrder ) );
			$info    = current( $info );
			$orderId = isset( $info->donate_id ) ? $info->donate_id : 0;

			include \WFP_Fundraising::plugin_dir() . 'views/public/fundraising/dynamic-page/order-confirm.php';
		}
		$contentData = ob_get_contents();
		ob_end_clean();

		// end object content

		return $contentData;
	}


	/**
	 * Donate wfp_cancel_content_shortcode.
	 * Method Description: Shortcode action for cancel page.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function wfp_cancel_content_shortcode( $atts, $content = null ) {
		// create shortcode
		$atts      = shortcode_atts(
			array(
				'class' => '',
				'id'    => 'wfp-cancel',
			),
			$atts,
			'wfp-cancel'
		);
		$className = isset( $atts['class'] ) ? $atts['class'] : '';
		$idName    = isset( $atts['id'] ) ? $atts['id'] : 'wfp-cancel';

		// start object content data
		ob_start();
		include \WFP_Fundraising::plugin_dir() . 'views/public/fundraising/dynamic-page/cancel.php';
		$content = ob_get_contents();
		ob_end_clean();

		// end object content data

		return $content;
	}


	/**
	 * Donate wfp_cancel_campaign_shortcode.
	 * Method Description: Shortcode action for cancel page.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function wfp_list_campaign_shortcode( $atts, $content = null ) {
		// create shortcode
		$atts      = shortcode_atts(
			array(
				'class'      => '',
				'id'         => 'wfp-campaign',
				'layout'     => 'grid',
				'column'     => '3',
				'limit'      => 9,
				'orderby'    => 'post_date',
				'order'      => 'DESC',
				'categories' => '',
				'goal'       => 'Yes',
				'user'       => 'Yes',
				'category'   => 'Yes',
				'title'      => 'Yes',
				'excerpt'    => 'Yes',
				'featured'   => 'Yes',
			),
			$atts,
			'wfp-campaign'
		);
		$className = isset( $atts['class'] ) ? $atts['class'] : '';
		$idName    = isset( $atts['id'] ) ? $atts['id'] : 'wfp-campaign';
		$layout    = isset( $atts['layout'] ) ? $atts['layout'] : 'grid';
		$column    = isset( $atts['column'] ) ? $atts['column'] : '3';

		$wfp_fundraising_content__show_post       = isset( $atts['limit'] ) ? $atts['limit'] : 9;
		$wfp_fundraising_content__orderby         = isset( $atts['orderby'] ) ? $atts['orderby'] : 'post_date';
		$wfp_fundraising_content__order           = isset( $atts['order'] ) ? $atts['order'] : 'DESC';
		$wfp_fundraising_content__goal_enable     = isset( $atts['goal'] ) ? $atts['goal'] : 'Yes';
		$wfp_fundraising_content__user_enable     = isset( $atts['user'] ) ? $atts['user'] : 'Yes';
		$wfp_fundraising_content__category_enable = isset( $atts['category'] ) ? $atts['category'] : 'Yes';
		$wfp_fundraising_content__title_enable    = isset( $atts['title'] ) ? $atts['title'] : 'Yes';
		$wfp_fundraising_content__excerpt_enable  = isset( $atts['excerpt'] ) ? $atts['excerpt'] : 'Yes';
		$wfp_fundraising_content__featured_enable = isset( $atts['featured'] ) ? $atts['featured'] : 'Yes';
		$wfp_fundraising_layout_option            = '';

		// categories
		if ( ! empty( $atts['categories'] ) ) {
			$wfp_fundraising_content__categories = explode( ',', $atts['categories'] );
			$wfp_fundraising_layout_option       = 'categories';
		}

		// layout style
		if ( $layout == 'list' ) {
			$wfp_fundraising_content__layout_style = Key::LAYOUT_STYLE_LIST;
		} elseif ( $layout == 'masonary' ) {
			$wfp_fundraising_content__layout_style = Key::LAYOUT_STYLE_MASONARY;
		} else {
			$wfp_fundraising_content__layout_style = Key::LAYOUT_STYLE_GRID;
		}

		// column select
		if ( $column == '4' ) {
			$wfp_fundraising_content__column_grid = 'xs-col-lg-3';
		} elseif ( $column == '2' ) {
			$wfp_fundraising_content__column_grid = 'xs-col-lg-6';
		} else {
			$wfp_fundraising_content__column_grid = 'xs-col-lg-4';
		}

		$col_from_short_code = $column;

		include \WFP_Fundraising::plugin_dir() . 'country-module/country-info.php';
		/*currency information*/
		$getMetaGeneralOp = get_option( \WfpFundraising\Apps\Settings::OK_GENERAL_DATA );
		$getMetaGeneral   = isset( $getMetaGeneralOp['options'] ) ? $getMetaGeneralOp['options'] : array();

		$defaultCurrencyInfo = isset( $getMetaGeneral['currency']['name'] ) ? $getMetaGeneral['currency']['name'] : 'US-USD';
		$explCurr            = explode( '-', $defaultCurrencyInfo );
		$currCode            = isset( $explCurr[1] ) ? $explCurr[1] : 'USD';
		$symbols             = isset( $countryList[ current( $explCurr ) ]['currency']['symbol'] ) ? $countryList[ current( $explCurr ) ]['currency']['symbol'] : '';
		$symbols             = strlen( $symbols ) > 0 ? $symbols : $currCode;

		$defaultUse_space = isset( $getMetaGeneral['currency']['use_space'] ) ? $getMetaGeneral['currency']['use_space'] : 'off';

		// start object content data
		ob_start();
		// include( \WFP_Fundraising::plugin_dir().'views/public/fundraising/dynamic-page/campaign.php' );
		include \WFP_Fundraising::plugin_dir() . 'views/public/fundraising/dynamic-page/campaign_listing.php';
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}


	/*
	* Added menu in manu bar for dashboard page
	*/
	public function wfp_add_dashboard_page_menu( $items, $args ) {

		if ( $args->theme_location == 'primary' ) {

			// page setup data
			if ( is_user_logged_in() ) {

				$getMetaGeneralOp   = get_option( \WfpFundraising\Apps\Settings::OK_GENERAL_DATA );
				$getMetaGeneralPage = Settings::instance()->get_mapped_page_slug( $getMetaGeneralOp );

				$dashboardPage = isset( $getMetaGeneralPage['dashboard'] ) ? $getMetaGeneralPage['dashboard'] : 'wfp-dashboard';

				$items .= '<li class="menu-item wfp-main-manu" id="wfp-dashboard-menu"><a href="' . home_url( '/' ) . $dashboardPage . '/"> ' . __( 'Dahsboard', 'wp-fundraising' ) . '</a></li>';

				$items .= '<li class="menu-item wfp-main-manu" id="wfp-sign-out-menu"><a href="' . wp_logout_url() . '">' . __( 'Sign Out', 'wp-fundraising' ) . '</a></li>';
			} else {
				$items .= '<li class="menu-item wfp-main-manu" id="wfp-login-menu"><a href="' . wp_login_url() . '">' . __( 'Login', 'wp-fundraising' ) . '</a></li>';
				$items .= '<li class="menu-item wfp-main-manu" id="wfp-register-menu"><a href="' . wp_registration_url() . '">' . __( 'Register', 'wp-fundraising' ) . '</a></li>';
			}
		}

		return $items;
	}

	public function wfp_update_meta( $post_id = 0, $meta_key = '', $meta_value = '', $unique = true ) {
		return \WfpFundraising\Apps\Settings::wfp_update_metadata( $post_id, $meta_key, $meta_value, $unique );
	}


	private function update_meta( $wpdb, $tbl_nm, $meta_key, $val, $post_id ) {

		$row = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'wdp_fundraising_meta WHERE meta_key = %s AND donate_id = %d', $meta_key, $post_id ) );

		if ( $row > 0 ) {
			return $wpdb->update(
				$tbl_nm,
				array( 'meta_value' => $val ),
				array(
					'meta_key'  => $meta_key,
					'donate_id' => $post_id,
				)
			);
		}

		return $this->insert_meta( $wpdb, $tbl_nm, $meta_key, $val, $post_id );
	}


	private function insert_meta( $wpdb, $tbl_nm, $meta_key, $val, $post_id ) {

		return $wpdb->insert(
			$tbl_nm,
			array(
				'donate_id'  => $post_id,
				'meta_key'   => $meta_key,
				'meta_value' => $val,
			)
		);
	}


	public function wfp_meta_update( $post_id = 0, $meta_arr = array(), $unique = true ) {

		global $wpdb;

		$tbl_nm = self::wfp_donate_table( 'wdp_fundraising_meta' );

		$func = $unique ? 'update_meta' : 'insert_meta';

		foreach ( $meta_arr as $meta_key => $meta_val ) {

			$meta_val = is_array( $meta_val ) ? serialize( $meta_val ) : $meta_val;

			$this->$func( $wpdb, $tbl_nm, $meta_key, $meta_val, $post_id );
		}

		return true;
	}


	public function wfp_get_meta( $post_id = 0, $meta_key = '' ) {
		return \WfpFundraising\Apps\Settings::wfp_get_metadata( $post_id, $meta_key );
	}


	public static function instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 *
	 * @since 1.8.0
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return array
	 */
	public function wfp_sh_login( \WP_REST_Request $request ) {

		if ( is_user_logged_in() ) {

			return array(
				'success' => true,
			);
		}

		$wfp_login = isset( $request['wfp_login'] ) ? map_deep( $request['wfp_login'], 'sanitize_text_field' ) : array();

		if ( empty( $wfp_login['user_name'] ) ) {

			return array(
				'error' => __( 'Please enter email or username', 'wp-fundraising' ),
			);
		}

		if ( empty( $wfp_login['user_password'] ) ) {

			return array(
				'error' => __( 'Please enter password', 'wp-fundraising' ),
			);
		}

		$username = $wfp_login['user_name'];
		$password = $wfp_login['user_password'];

		$credentials = array(
			'user_login'    => $username,
			'user_password' => $password,
			'remember'      => isset( $wfp_login['rememberme'] ),
		);

		$user = wp_signon( $credentials, false );

		if ( is_wp_error( $user ) ) {

			return array(
				'error' => $user->get_error_message(),
			);
		}

		return array(
			'success' => true,
		);
	}


	/**
	 *
	 * @since 1.8.0
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return array
	 */
	public function wfp_sh_register( \WP_REST_Request $request ) {

		if ( is_user_logged_in() ) {

			return array(
				'error' => __( 'System Error', 'wp-fundraising' ),
			);
		}

		/**
		 * We have fixed a ticket
		 */
		if ( ! get_option( 'users_can_register', false ) ) {

			return array(
				'error' => __( 'New user registration option is turned off', 'wp-fundraising' ),
			);
		}

		$wfp_register = isset( $request['wfp_register'] ) ? map_deep( $request['wfp_register'], 'sanitize_text_field' ) : array();

		if ( email_exists( $wfp_register['user_email'] ) ) {

			return array(
				'error' => __( 'This email is already used', 'wp-fundraising' ),
			);
		}

		if ( empty( $wfp_register['user_email'] ) || ! Settings::valid_email( $wfp_register['user_email'] ) ) {

			return array(
				'error' => __( 'Please enter a valid email address', 'wp-fundraising' ),
			);
		}

		$user_name = $this->get_sanitized_username( $wfp_register['user_name'] );

		if ( empty( $user_name ) ) {

			return array(
				'error' => __( 'Please enter valid username', 'wp-fundraising' ),
			);
		}

		if ( empty( $wfp_register['user_password'] ) ) {

			return array(
				'error' => __( 'Please set your password', 'wp-fundraising' ),
			);
		}

		$user_email = esc_sql( trim( $wfp_register['user_email'] ) );
		$password   = $wfp_register['user_password'];

		$user_data                  = array();
		$user_data['user_nicename'] = $user_name;
		$user_data['display_name']  = trim( $wfp_register['user_name'] );
		$user_data['user_email']    = $user_email;
		$user_data['user_login']    = $user_name;
		$user_data['user_pass']     = $password;

		$avatar = new \WfpFundraising\Utilities\Avatar();

		/**
		 * Lets check if pro plugin is active or not
		 */
		if ( did_action( 'wpfp/fundraising_pro/plugin_loaded' ) ) {

			$avatar = new \WP_Fundraising_Pro\Utils\Avatar();
		}

		$userId = $avatar->create_account_with_info( $user_data, 'admin' );

		if ( is_wp_error( $userId ) ) {

			return array(
				'error' => __( 'Sorry system error. Try again', 'wp-fundraising' ),
			);
		}

		$cred = array(
			'user_email'    => $user_email,
			'user_password' => $password,
			'remember'      => true,
		);

		$user = wp_signon( $cred, false );

		return array(
			'success' => true,
		);
	}


	/**
	 * Temporary redirect to set the woocommerce necessary parameter before adding into cart
	 *
	 * @since 1.1.17
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return array
	 */
	public function wfp_action_woc_redirect( \WP_REST_Request $request ) {
		
		session_start();

		$param        = $request->get_params();
		$custom_price = floatval( $param['price'] );
		$product_id   = intval( $param['id'] );

		update_post_meta( $product_id, '_price', $custom_price );
		update_post_meta( $product_id, '_virtual', 'yes' );
		update_post_meta( $product_id, '_regular_price', $custom_price );

		$curr_session[ $product_id ] = array(
			'_wfp_donation_id' => $product_id,
			'_wfp_donation_am' => $custom_price,
			'_wfp_type'        => sanitize_key( $param['type'] ),
		);

		if ( ! empty( $param['pledge_uid'] ) ) {
			$curr_session[ $product_id ]['_wfp_pledge_uid']    = sanitize_key( $param['pledge_uid'] );
			$curr_session[ $product_id ]['_wfp_pledge_id']     = sanitize_key( $param['pledge_id'] );
			$curr_session[ $product_id ]['_wfp_pledge_amount'] = $custom_price;
		}

		$_SESSION['wfp_donation_conf'] = $curr_session;
		
		session_write_close();

		return array(
			'success'  => true,
			'cart_key' => '',
			'param'    => $param,
		);
	}


	/**
	 *
	 * @since 1.1.15
	 *
	 * @param $user_nm
	 *
	 * @return string
	 */
	protected function get_sanitized_username( $user_nm ) {

		$username = preg_replace( '/\s+/', '', $user_nm );

		$sanitized = sanitize_user( $username );

		if ( empty( $sanitized ) ) {

			return '';
		}

		if ( ! validate_username( $sanitized ) ) {

			return '';
		}

		return $sanitized;
	}

	public function wfp_popup() {

		$wfp_pop_up_args = array(
			'post_type' => 'wp-fundraising',
		);

		$wfp_pop_up_donnations = get_posts( $wfp_pop_up_args );
		$wfp_pop_up_metaKey    = Fundraising::WFP_MK_FORM_DATA;

		// start object content data
		ob_start();
		include \WFP_Fundraising::plugin_dir() . 'views/public/donation/donation-pop-up.php';
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

}


