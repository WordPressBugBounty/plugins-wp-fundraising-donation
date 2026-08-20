<?php

namespace WfpFundraising\Apps;

defined( 'ABSPATH' ) || exit;

/**
 * Class Name : Settings - This access for admin
 * Class Type : Normal class
 *
 * initiate all necessary classes, hooks, configs
 *
 * @since 1.0.0
 * @access Public
 */
class Settings {

	// declare custom post type here

	const post_type       = 'wp-fundraising';
	const OK_GENERAL_DATA = 'wfp_general_options_data';

	/**
	 * Construct the Settings object
	 *
	 * @since 1.0.0
	 * @access public
	 */

	public $donate;
	public $crowdfunding;

	private static $instance;


	public function _init( $load = true ) {
		if ( $load ) {

			add_action( 'init', array( $this, 'wfp_init_rest_welcome' ) );

			// Remove editor function - Filter
			add_filter( 'user_can_richedit', array( $this, 'wfp_remove_visual_editor_donate' ) );

			// Remove add media function - Action
			// add_action('admin_head', [ $this, 'wfp_remove_media_button_donate' ] );

			// Load css file / script for settings page
			add_action( 'admin_enqueue_scripts', array( $this, 'wfp_settings_css_loader' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'wfp_settings_css_loader' ) );

			add_filter( 'gettext', array( $this, 'wfp_excerpt_modify' ), 10, 10 );
		}
	}


	/**
	 *
	 * It is not in action right now
	 *
	 * @since 1.1.17
	 */
	public function init_woc_hook() {

		add_action( 'woocommerce_before_calculate_totals', array( $this, 'woc_cart_item_price_update' ), 99, 1 );
	}


	public function woc_cart_item_price_update( $cart_object ) {

		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}

		if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 ) {
			return;
		}

		foreach ( $cart_object->cart_contents as $key => &$item ) {
			if ( ! empty( $item['wfp_type'] ) ) {
				// set_price() overrides the price in memory for this cart calculation.
				// Do NOT call ->save() here — that would write the custom price back to
				// product meta, permanently altering the product's stored price.
				$cart_object->cart_contents[ $key ]['data']->set_price( (float) $item['custom_price'] );
			}
		}

		$cart_object->calculate_totals();
		$cart_object->set_session();
		$cart_object->maybe_set_cart_cookies();
	}


	/**
	 * Custom Post type : static method
	 *
	 * @since 1.0.0
	 * @access public
	 */
	private static function post_type() {
		return self::post_type;
	}


	public function wfp_excerpt_modify( $translation, $original ) {
		if ( 'Excerpt' == $original ) {
			return __( 'Short Brief', 'wp-fundraising-donation' );
		} else {
			$pos = strpos( $original, 'Excerpts are optional hand-crafted summaries of your' );
			if ( $pos !== false ) {
				return __( 'Tell something about your campaign.', 'wp-fundraising-donation' );
			}
		}

		return $translation;
	}


	/**
	 * Donate wfp_remove_visual_editor_donate
	 * Method Description: remove visual editor from WordPress editor.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function wfp_remove_visual_editor_donate( $default ) {
		global $post;
		if ( self::post_type() == get_post_type( $post ) ) {
			return true;
		}

		return $default;
	}


	/**
	 * Donate wfp_remove_media_button_donate
	 * Method Description: remove add media button from WordPress editor.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function wfp_remove_media_button_donate() {
		global $current_screen;
		// remove add media button from my post type
		if ( self::post_type() == $current_screen->post_type ) {
			remove_action( 'media_buttons', 'media_buttons' );
		}
	}


	/**
	 * Donate wfp_init_rest_welcome .
	 * Method Description: load rest api
	 *
	 * @since 1.0.0
	 * @access for public
	 */
	public function wfp_init_rest_welcome() {
		add_action(
			'rest_api_init',
			function() {
				register_rest_route(
					'xs-welcome-form',
					'/welcome-submit/(?P<formid>\w+)/',
					array(
						// The route writes the plugin's setup option, so it is a
						// state-changing request: POST only, never GET.
						'methods'             => \WP_REST_Server::CREATABLE,
						'callback'            => array( $this, 'wfp_action_rest_welcome_submit' ),
						'permission_callback' => array( $this, 'wfp_can_run_setup_wizard' ),
					)
				);
			}
		);
	}


	/**
	 * Permission callback for the setup wizard route.
	 *
	 * The wizard writes the `wfp_setup_services_data` option, which controls the
	 * active payment gateway and campaign type for the whole site. Only a site
	 * administrator acting from the plugin's own admin screen may do that, so we
	 * require both the `manage_options` capability and a valid `wp_rest` nonce.
	 *
	 * @since 1.8.1
	 * @access public
	 *
	 * @param \WP_REST_Request $request Current request.
	 *
	 * @return true|\WP_Error True when allowed, WP_Error otherwise.
	 */
	public function wfp_can_run_setup_wizard( \WP_REST_Request $request ) {

		if ( ! current_user_can( 'manage_options' ) ) {

			return new \WP_Error(
				'wfp_rest_forbidden',
				__( 'You are not allowed to change the setup settings.', 'wp-fundraising-donation' ),
				array( 'status' => rest_authorization_required_code() )
			);
		}

		$nonce = $request->get_header( 'X-WP-Nonce' );

		if ( empty( $nonce ) ) {
			$nonce = $request->get_param( '_wpnonce' );
		}

		if ( empty( $nonce ) || ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {

			return new \WP_Error(
				'wfp_rest_invalid_nonce',
				__( 'Invalid or expired security token.', 'wp-fundraising-donation' ),
				array( 'status' => 403 )
			);
		}

		return true;
	}


	/*
	* Get page list
	*/
	public function wfp_add_dashboard_get_page_menu( $items, $args ) {
		// echo '<pre>'; print_r($items); echo '</pre>';
		if ( $args->theme_location == 'primary' ) {

		}

		return $items;
	}


	/*
	* WP Fundraising Custom nav menu bar
	*/
	public function wfp_custom_nav_menu() {
		register_nav_menu( 'wp-fundraising-dashboard', __( 'Fundraising Dashboard', 'wp-fundraising-donation' ) );
		/*
		 wp_nav_menu( array(
		   'theme_location' => 'wp-fundraising-dashboard',
		   'container_class' => 'wfp-fundraising-dashboard' ) );*/
	}


	/**
	 * Donate wfp_action_rest_welcome_submit .
	 * Method Description: Action donate form submit when click this donate button.
	 *
	 * @since 1.0.0
	 * @access for public
	 */
	public function wfp_action_rest_welcome_submit( \WP_REST_Request $request ) {
		$return = array(
			'success' => array(),
			'error'   => array(),
		);

		$steps = self::default_setup();

		// The step name comes from the URL. Only the wizard's own step keys are
		// accepted, otherwise `finish` could be set to an arbitrary string and
		// the setup gate in check_setup() would be left in an unknown state.
		$getPath = isset( $request['formid'] ) ? sanitize_key( $request['formid'] ) : '';
		if ( ! array_key_exists( $getPath, $steps ) ) {
			$getPath = current( array_keys( $steps ) );
		}

		$submitted = $request->get_param( 'xs_welcome_data_submit' );
		$submitted = is_array( $submitted ) ? $submitted : array();
		$services  = ( isset( $submitted['services'] ) && is_array( $submitted['services'] ) ) ? $submitted['services'] : array();

		$metaSetupKey = 'wfp_setup_services_data';

		$stored = get_option( $metaSetupKey );
		$stored = ( is_array( $stored ) && isset( $stored['services'] ) && is_array( $stored['services'] ) ) ? $stored['services'] : array();

		// Allow-list: the wizard only ever submits these two fields, and each
		// one only accepts the values its own radio buttons offer.
		$allowed_services = array(
			'campaign' => array( Key::WFP_DONATION_TYPE_SINGLE, Key::WFP_DONATION_TYPE_CROWED ),
			'payment'  => array( 'default', 'woocommerce' ),
		);

		// Rebuild the option from the known keys only. A field the current step
		// does not submit keeps its stored value, so a partial submission does
		// not wipe the other steps -- but the stored value has to pass the same
		// allow-list, otherwise a value injected while this route was still
		// unauthenticated would be carried forward for ever. Anything that fails
		// both checks is dropped, and the readers all fall back to their own
		// defaults for a missing key.
		$services_out = array();

		foreach ( $allowed_services as $field => $choices ) {

			if ( isset( $services[ $field ] ) && in_array( $services[ $field ], $choices, true ) ) {

				$services_out[ $field ] = $services[ $field ];

			} elseif ( isset( $stored[ $field ] ) && in_array( $stored[ $field ], $choices, true ) ) {

				$services_out[ $field ] = $stored[ $field ];
			}
		}

		$services_out['finish'] = $getPath;

		$data = array( 'services' => $services_out );

		update_option( $metaSetupKey, $data );

		$return['success'] = array(
			'message'    => __( 'Successfully Setup', 'wp-fundraising-donation' ),
			'finish_url' => '',
		);

		return $return;
	}


	/**
	 * Method: wfp_settings_css_loader .
	 * Method Description: Settings Css Loader
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function wfp_settings_css_loader() {

		// 3th party script & css

		// flatpickr date picker
		wp_register_style( 'flatpickr-wfp', \WFP_Fundraising::plugin_url() . 'assets/flatpickr/flatpickr.min.css', false, \WFP_Fundraising::version() );
		wp_register_script( 'flatpickr-wfp', \WFP_Fundraising::plugin_url() . 'assets/flatpickr/flatpickr.js', array( 'jquery' ), \WFP_Fundraising::version(), false );
		// select 2
		wp_register_style( 'select2', \WFP_Fundraising::plugin_url() . 'assets/select2/css/select2-min.css', false, \WFP_Fundraising::version() );
		wp_register_script( 'select2', \WFP_Fundraising::plugin_url() . 'assets/select2/script/select2-min.js', array( 'jquery' ), \WFP_Fundraising::version(), false );
		// repeater
		wp_register_script( 'wfp_repeater_script', \WFP_Fundraising::plugin_url() . 'assets/admin/script/jquery.form-repeater.js', array( 'jquery' ), \WFP_Fundraising::version(), false );

		// commomn load

		// payment style
		wp_register_style( 'wfp_payment_css', \WFP_Fundraising::plugin_url() . 'payment-module/assets/css/payment-style.css', false, \WFP_Fundraising::version() );
		// modal script
		wp_register_script( 'wfp_payment_script', \WFP_Fundraising::plugin_url() . 'payment-module/assets/script/payment-script.js', array( 'jquery' ), \WFP_Fundraising::version(), false );
		// sortable drag wfp_sortable_script
		// wp_register_script( 'jquery-ui-sort', \WFP_Fundraising::plugin_url(). 'payment-module/assets/script/sortable-drag-script.js', array('jquery'), \WFP_Fundraising::version(), false);
		// payment modal script
		wp_register_script( 'wfp_payment_script_modal', \WFP_Fundraising::plugin_url() . 'payment-module/assets/script/modal-js/modal-popup.js', array( 'jquery' ), \WFP_Fundraising::version(), false );

		// admin load
		// settings css
		wp_register_style( 'wfp_settings_admin_css', \WFP_Fundraising::plugin_url() . 'assets/admin/css/settings/settings.css', false, \WFP_Fundraising::version() );
		// Donation css
		wp_register_style( 'wfp_donation_admin_css', \WFP_Fundraising::plugin_url() . 'assets/admin/css/donate/donation-form.css', false, \WFP_Fundraising::version() );
		// Donation Script
		wp_register_script( 'wfp_donation_admin_script', \WFP_Fundraising::plugin_url() . 'assets/admin/script/donate/donate-form.js', array( 'jquery' ), \WFP_Fundraising::version(), false );
		// main js
		wp_register_script( 'wfp_admin_main_script', \WFP_Fundraising::plugin_url() . 'assets/admin/script/settings/main.js', array( 'jquery' ), \WFP_Fundraising::version(), false );

		// public load

		// donation pop up
		wp_register_style( 'wfp_donation_popup_public_css', \WFP_Fundraising::plugin_url() . 'assets/public/css/donate/css/donation-popup.css', false, \WFP_Fundraising::version() );
		// donation public form
		wp_register_style( 'wfp_donation_public_css', \WFP_Fundraising::plugin_url() . 'assets/public/css/donate/display-form-styles.css', false, \WFP_Fundraising::version() );
		// font wfp
		wp_register_style( 'wfp_fonts', \WFP_Fundraising::plugin_url() . 'assets/public/css/fonts.css', false, \WFP_Fundraising::version() );
		// met font wfp
		wp_register_style( 'wfp_met-fonts', \WFP_Fundraising::plugin_url() . 'assets/public/css/met-social.css', false, \WFP_Fundraising::version() );
		// dashboard css
		wp_register_style( 'wfp_dashboard_css', \WFP_Fundraising::plugin_url() . 'assets/public/css/donate/dashboard.css', false, \WFP_Fundraising::version() );
		// login css
		wp_register_style( 'wfp_login_css', \WFP_Fundraising::plugin_url() . 'assets/public/css/donate/login.css', false, \WFP_Fundraising::version() );
		// list  campaign css
		wp_register_style( 'wfp_single_campaign_css', \WFP_Fundraising::plugin_url() . 'assets/public/css/donate/campaign.css', false, \WFP_Fundraising::version() );
		// single page
		wp_register_style( 'wfp_single_css', \WFP_Fundraising::plugin_url() . 'assets/public/css/single/single-page.css', false, \WFP_Fundraising::version() );
		// libarey css
		wp_register_style( 'wfp_css_libarey', \WFP_Fundraising::plugin_url() . 'assets/public/css/libarey/libarey.css', false, \WFP_Fundraising::version() );
		// master css
		wp_register_style( 'wfp_css_master', \WFP_Fundraising::plugin_url() . 'assets/public/css/global/master.css', false, \WFP_Fundraising::version() );
		// checkout css
		wp_register_style( 'wfp_css_checkout', \WFP_Fundraising::plugin_url() . 'assets/public/css/checkout/checkout.css', false, \WFP_Fundraising::version() );
		// responsive master
		wp_register_style( 'wfp_responsive_css_master', \WFP_Fundraising::plugin_url() . 'assets/public/css/responsive.css', array( 'wfp_single_campaign_css' ), \WFP_Fundraising::version() );

		// donation pop up
		wp_enqueue_script( 'wfp_donation_popup_public_js', \WFP_Fundraising::plugin_url() . 'assets/public/script/donate/donate-pop-up.js', false, \WFP_Fundraising::version(), true );
		// donation public script
		wp_register_script( 'wfp_donation_form_script', \WFP_Fundraising::plugin_url() . 'assets/public/script/donate/donate-form-front.js', array( 'jquery' ), \WFP_Fundraising::version(), false );
		wp_localize_script(
			'wfp_donation_form_script',
			'donation_form_ajax',
			array(
				'nonce'           => wp_create_nonce( 'stripe_nonce' ),
				'invalidRewards'  => __( 'Invalid Rewards', 'wp-fundraising-donation' ),
				'addToCartFailed' => __( 'Add to cart failed!', 'wp-fundraising-donation' ),
			)
		);
		// from step script
		wp_register_script( 'wfp_campaign_form_step', \WFP_Fundraising::plugin_url() . 'assets/public/script/donate/dashboard/lib/forms-setp.js', array( 'jquery' ), \WFP_Fundraising::version(), false );
		// dashboard script
		wp_register_script( 'wfp_dashboard_script', \WFP_Fundraising::plugin_url() . 'assets/public/script/donate/dashboard/dashboard.js', array( 'jquery' ), \WFP_Fundraising::version(), false );
		// login script
		wp_register_script( 'wfp_login_script', \WFP_Fundraising::plugin_url() . 'assets/public/script/donate/dashboard/login.js', array( 'jquery' ), \WFP_Fundraising::version(), false );
		// chart js
		wp_register_script( 'chartjs', \WFP_Fundraising::plugin_url() . 'assets/public/script/donate/dashboard/chart.min.js', array( 'jquery' ), \WFP_Fundraising::version(), false );
		// fancybox js
		wp_register_script( 'fancybox_js', \WFP_Fundraising::plugin_url() . 'assets/public/script/single-page/fancybox.umd.min.js', array( 'jquery' ), \WFP_Fundraising::version(), false );
		// single script
		wp_register_script( 'wfp_single_script', \WFP_Fundraising::plugin_url() . 'assets/public/script/single-page/single-page.js', array( 'jquery' ), \WFP_Fundraising::version(), false );
		wp_localize_script(
			'wfp_single_script',
			'wfpSingleL10n',
			array(
				'confirmRemoveReview' => __( 'Are you sure? Remove this review.', 'wp-fundraising-donation' ),
				'updateButton'        => __( 'Update', 'wp-fundraising-donation' ),
			)
		);
		// essay pie chart
		wp_register_script( 'wfp_easy_pie_script', \WFP_Fundraising::plugin_url() . 'assets/public/script/single-page/easy-pie-chart.js', array( 'jquery' ), \WFP_Fundraising::version(), false );

		// fancybox css
		wp_register_style( 'fancybox_css', \WFP_Fundraising::plugin_url() . 'assets/public/css/single/fancybox.css', false, \WFP_Fundraising::version() );
		// welcome css
		wp_register_style( 'wfp_welcome_style_css', \WFP_Fundraising::plugin_url() . 'views/welcome/css/welcome.css', false, \WFP_Fundraising::version() );
		// welcome script
		wp_register_script( 'wfp_welcome_style_script', \WFP_Fundraising::plugin_url() . 'views/welcome/script/welcome.js', array( 'jquery' ), \WFP_Fundraising::version(), false );

		// wp css
		wp_enqueue_style( 'dashicons' );
		wp_enqueue_style( 'wp-color-picker' );

		// wp color script
		wp_enqueue_script( 'wp-color-picker' );

		// flatpickr date picker
		wp_enqueue_style( 'flatpickr-wfp' );
		wp_enqueue_script( 'flatpickr-wfp' );

		// select 2
		wp_enqueue_style( 'select2' );
		wp_enqueue_script( 'select2' );

		// modal for payment
		wp_enqueue_script( 'wfp_payment_script_modal' );

		$this->wfp_localize_vendor_scripts();
	}


	/**
	 * Localise the bundled flatpickr and Select2 builds.
	 *
	 * Both libraries ship English-only UI text, so the date pickers and the
	 * country dropdowns stay English on a translated site unless we hand them
	 * localised strings. Month and weekday names are taken from WordPress core's
	 * own translations via $wp_locale, so the calendars follow the site language
	 * without needing any plugin translation for them.
	 *
	 * Attached with wp_add_inline_script( ..., 'after' ) so it runs immediately
	 * after each library and always before the inline init calls in the views.
	 *
	 * @since 1.8.1
	 * @access public
	 */
	public function wfp_localize_vendor_scripts() {

		global $wp_locale;

		if ( $wp_locale instanceof \WP_Locale ) {

			$weekdays_long  = array_values( $wp_locale->weekday );
			$weekdays_short = array();
			foreach ( $weekdays_long as $wfp_weekday ) {
				$weekdays_short[] = $wp_locale->get_weekday_abbrev( $wfp_weekday );
			}

			$months_long  = array_values( $wp_locale->month );
			$months_short = array();
			foreach ( $months_long as $wfp_month ) {
				$months_short[] = $wp_locale->get_month_abbrev( $wfp_month );
			}

			$flatpickr_l10n = array(
				'weekdays'       => array(
					'shorthand' => $weekdays_short,
					'longhand'  => $weekdays_long,
				),
				'months'         => array(
					'shorthand' => $months_short,
					'longhand'  => $months_long,
				),
				'firstDayOfWeek' => (int) get_option( 'start_of_week', 0 ),
				'rangeSeparator' => __( ' to ', 'wp-fundraising-donation' ),
				'scrollTitle'    => __( 'Scroll to increment', 'wp-fundraising-donation' ),
				'toggleTitle'    => __( 'Click to toggle', 'wp-fundraising-donation' ),
				'amPM'           => array(
					$wp_locale->get_meridiem( 'AM' ) ? $wp_locale->get_meridiem( 'AM' ) : 'AM',
					$wp_locale->get_meridiem( 'PM' ) ? $wp_locale->get_meridiem( 'PM' ) : 'PM',
				),
			);

			wp_add_inline_script(
				'flatpickr-wfp',
				'if ( window.flatpickr ) { window.flatpickr.localize( ' . wp_json_encode( $flatpickr_l10n ) . ' ); }',
				'after'
			);
		}

		$select2_l10n = array(
			'errorLoading'    => __( 'The results could not be loaded.', 'wp-fundraising-donation' ),
			'loadingMore'     => __( 'Loading more results…', 'wp-fundraising-donation' ),
			'noResults'       => __( 'No results found', 'wp-fundraising-donation' ),
			'searching'       => __( 'Searching…', 'wp-fundraising-donation' ),
			'removeAllItems'  => __( 'Remove all items', 'wp-fundraising-donation' ),
			/* translators: %s: number of characters that need to be removed */
			'inputTooLong'    => __( 'Please delete %s character(s)', 'wp-fundraising-donation' ),
			/* translators: %s: number of additional characters that need to be typed */
			'inputTooShort'   => __( 'Please enter %s or more characters', 'wp-fundraising-donation' ),
			/* translators: %s: maximum number of items that may be selected */
			'maximumSelected' => __( 'You can only select %s item(s)', 'wp-fundraising-donation' ),
		);

		wp_add_inline_script(
			'select2',
			'( function ( $ ) {
				if ( ! $ || ! $.fn || ! $.fn.select2 ) { return; }
				var t = ' . wp_json_encode( $select2_l10n ) . ';
				var fmt = function ( str, num ) { return String( str ).replace( "%s", num ); };
				$.fn.select2.defaults.set( "language", {
					errorLoading: function () { return t.errorLoading; },
					inputTooLong: function ( args ) { return fmt( t.inputTooLong, args.input.length - args.maximum ); },
					inputTooShort: function ( args ) { return fmt( t.inputTooShort, args.minimum - args.input.length ); },
					loadingMore: function () { return t.loadingMore; },
					maximumSelected: function ( args ) { return fmt( t.maximumSelected, args.maximum ); },
					noResults: function () { return t.noResults; },
					searching: function () { return t.searching; },
					removeAllItems: function () { return t.removeAllItems; }
				} );
			}( window.jQuery ) );',
			'after'
		);
	}

	/**
	 * Currency format settings
	 */

	public static function wfp_number_format_currency( $amount = '0' ) {
		include \WFP_Fundraising::plugin_dir() . 'country-module/country-info.php';
		/*currency information*/
		$metaGeneralKey   = 'wfp_general_options_data';
		$getMetaGeneralOp = get_option( $metaGeneralKey );
		$getMetaGeneral   = isset( $getMetaGeneralOp['options'] ) ? $getMetaGeneralOp['options'] : array();

		$defaultCurrencyInfo = isset( $getMetaGeneral['currency']['name'] ) ? $getMetaGeneral['currency']['name'] : 'US-USD';
		$explCurr            = explode( '-', $defaultCurrencyInfo );
		$currCode            = isset( $explCurr[1] ) ? $explCurr[1] : 'USD';

		$symbols = isset( $wfpCountryList[ current( $explCurr ) ]['currency']['symbol'] ) ? $wfpCountryList[ current( $explCurr ) ]['currency']['symbol'] : '';
		$symbols = strlen( $symbols ) > 0 ? $symbols : $currCode;

		$defaultThou_seperator = isset( $getMetaGeneral['currency']['thou_seperator'] ) ? $getMetaGeneral['currency']['thou_seperator'] : ',';

		$defaultDecimal_seperator = isset( $getMetaGeneral['currency']['decimal_seperator'] ) ? $getMetaGeneral['currency']['decimal_seperator'] : '.';

		$defaultNumberDecimal = isset( $getMetaGeneral['currency']['number_decimal'] ) ? $getMetaGeneral['currency']['number_decimal'] : '2';
		if ( $defaultNumberDecimal < 0 ) {
			$defaultNumberDecimal = 0;
		}
		$userNumber = number_format($amount ?? 0, $defaultNumberDecimal);

		$numberExplode = explode( '.', $userNumber );
		$replaceNumber = str_replace( array( ',' ), array( $defaultThou_seperator ), $numberExplode[0] );
		if ( isset( $numberExplode[1] ) ) {
			$replaceNumber .= $defaultDecimal_seperator . $numberExplode[1];
		}

		return $replaceNumber;
	}


	/**
	 * Currency icon format settings
	 */
	public static function wfp_number_format_currency_icon( $showFormat = 'default', $space = 'off' ) {

		include \WFP_Fundraising::plugin_dir() . 'country-module/country-info.php';

		$metaGeneralKey   = 'wfp_general_options_data';
		$getMetaGeneralOp = get_option( $metaGeneralKey );
		$getMetaGeneral   = isset( $getMetaGeneralOp['options'] ) ? $getMetaGeneralOp['options'] : array();

		$metaSetupKey = 'wfp_setup_services_data';
		$getSetUpData = get_option( $metaSetupKey );
		$paymentType  = isset( $getSetUpData['services']['payment'] ) ? $getSetUpData['services']['payment'] : 'default';

		$defaultCurrencyInfo = isset( $getMetaGeneral['currency']['name'] ) ? $getMetaGeneral['currency']['name'] : 'US-USD';
		$expCurr             = explode( '-', $defaultCurrencyInfo );
		$currCode            = isset( $expCurr[1] ) ? $expCurr[1] : 'USD';

		$symbols = self::instance()->get_curr_symbol( $getMetaGeneral, $paymentType );

		$defultDisplayCurrency = isset( $getMetaGeneral['currency']['display'] ) ? $getMetaGeneral['currency']['display'] : 'symbol';

		$defaultPosition = isset( $getMetaGeneral['currency']['position'] ) ? $getMetaGeneral['currency']['position'] : 'left';

		$spaceData = ( $space == 'on' ) ? '&nbsp;' : '';

		if ( $defultDisplayCurrency == 'code' ) {
			if ( $defaultPosition == 'right' ) {
				return ( $showFormat == 'right' ) ? $spaceData . $currCode : '';
			} else {
				return ( $showFormat == 'left' ) ? $currCode . $spaceData : '';
			}
		} elseif ( $defultDisplayCurrency == 'symbol' ) {
			if ( $defaultPosition == 'right' ) {
				return ( $showFormat == 'right' ) ? $spaceData . $symbols : '';
			} else {
				return ( $showFormat == 'left' ) ? $symbols . $spaceData : '';
			}
		} elseif ( $defultDisplayCurrency == 'both' ) {
			return ( $showFormat == 'right' ) ? $spaceData . $currCode : $symbols . $spaceData;

		} else {
			if ( $defaultPosition == 'right' ) {
				return ( $showFormat == 'right' ) ? $spaceData . $symbols : '';
			} else {
				return ( $showFormat == 'left' ) ? $symbols . $spaceData : '';
			}
		}
	}

	/*check page when plugin active*/
	public static function wfp_the_slug_exists( $post_name ) {
		global $wpdb;
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Prepared lookup for existing post slug; intentional to avoid duplicate page creation.
		if ( $wpdb->get_row( $wpdb->prepare( 'SELECT post_name FROM ' . $wpdb->prefix . 'posts WHERE post_name = %s', $post_name ), 'ARRAY_A' ) ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Custom Create page
	 */
	public static function default_custom_page() {

		return array(
			'dashboard'    => array(
				'title'      => __( 'WFP Dashboard', 'wp-fundraising-donation' ),
				'slug'       => 'wfp-dashboard',
				'short_code' => 'wfp-dashboard',
			),

			'checkout'     => array(
				'title'      => __( 'WFP Checkout', 'wp-fundraising-donation' ),
				'slug'       => 'wfp-checkout',
				'short_code' => 'wfp-checkout',
			),

			'success'      => array(
				'title'      => __( 'WFP Success', 'wp-fundraising-donation' ),
				'slug'       => 'wfp-success',
				'short_code' => 'wfp-success',
			),

			'cancel'       => array(
				'title'      => __( 'WFP Failed Transaction', 'wp-fundraising-donation' ),
				'slug'       => 'wfp-cancel',
				'short_code' => 'wfp-cancel',
			),

			'campaign'     => array(
				'title'      => __( 'WFP Campaign listing', 'wp-fundraising-donation' ),
				'slug'       => 'wfp-campaign',
				'short_code' => 'wfp-campaign',
			),

			'auth-form'    => array(
				'title'      => __( 'WFP Auth Form', 'wp-fundraising-donation' ),
				'slug'       => 'wfp-auth-form',
				'short_code' => 'wfp_fundraising_form',
			),

			'invoice-info' => array(
				'title'      => __( 'WFP Donation Invoice', 'wp-fundraising-donation' ),
				'slug'       => Key::SLUG_INVOICE_PAGE,
				'short_code' => '',
			),
		);
	}


	public static function get_dashboard_url( $page = 'dashboard' ) {

		$slug = self::default_custom_page()[ $page ]['slug'];

		return site_url( $slug );
	}


	/**
	 * Create default pages
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function create_default_pages() {

		$pageArray  = self::default_custom_page();
		$current_id = get_current_user_id();
		$cache      = array();

		foreach ( $pageArray as $page => $arr ) {

			$blog_page_check = get_page_by_path( $arr['slug'], 'OBJECT', 'page' );

			if ( ! isset( $blog_page_check->ID ) ) {

				if ( $page == 'campaign' ) {
					$check_content = '[' . $arr['short_code'] . ' limit="9" layout="grid" column="3" orderby="post_date" order="DESC" categories="" goal="Yes" user="Yes" category="Yes" title="Yes" excerpt="Yes" featured="Yes"]';
				} else {
					$check_content = '[' . $arr['short_code'] . ']';
				}

				$data_page = array(
					'post_type'      => 'page',
					'post_title'     => $arr['title'],
					'post_name'      => $arr['slug'],
					'post_content'   => $check_content,
					'post_status'    => 'publish',
					'post_author'    => $current_id,
					'post_slug'      => $arr['slug'],
					'comment_status' => 'closed',
				);

				$id = wp_insert_post( $data_page );

				if ( ! is_wp_error( $id ) ) {

					$cache['ids'][ $page ] = $id;
					$cache['slugs'][ $id ] = $data_page['post_slug'];
				}
			} else {

				$cache['ids'][ $page ]                  = $blog_page_check->ID;
				$cache['slugs'][ $blog_page_check->ID ] = $blog_page_check->post_name;
			}
		}

		return $cache;
	}


	/**
	 * Create page when setup plugin
	 */
	public function wfp_create_page_setup() {

		if ( is_admin() ) {

			$cachedInfo = $this->create_default_pages();

			$option = get_option( self::OK_GENERAL_DATA, [] );

			if ( empty( $option['options']['pages'] ) ) {

				// settings never been saved
				$option['options']['pages'] = $cachedInfo['ids'];
				$option['options']['slugs'] = $cachedInfo['slugs'];

				update_option( self::OK_GENERAL_DATA, $option, true );

			} else {

				// settings were saved but some page could be not the default
				// and now we have introduced new page that need to be saved

				foreach ( $cachedInfo['ids'] as $pg => $id ) {

					if ( empty( $option['options']['pages'][ $pg ] ) ) {
						$option['options']['pages'][ $pg ] = $id;
						$option['options']['slugs'][ $id ] = $cachedInfo['slugs'][ $id ];
					}
				}

				update_option( self::OK_GENERAL_DATA, $option, true );
			}
		}
	}


	/**
	 *
	 * @since 1.0.0
	 *
	 * @param string $page_key
	 * @param bool   $update_option
	 *
	 * @return array
	 */
	public function create_default_checkout_page( $update_option = false, $page_key = 'checkout' ) {

		$pageArray  = self::default_custom_page();
		$current_id = get_current_user_id();
		$cache      = array();

		$info   = $pageArray[ $page_key ];
		$exists = get_page_by_path( $info['slug'], 'OBJECT', 'page' );

		if ( ! isset( $exists->ID ) ) {

			$content = '[' . $info['short_code'] . ']';

			$data_page = array(
				'post_type'      => 'page',
				'post_title'     => $info['title'],
				'post_name'      => $info['slug'],
				'post_content'   => $content,
				'post_status'    => 'publish',
				'post_author'    => $current_id,
				'post_slug'      => $info['slug'],
				'comment_status' => 'closed',
			);

			$id = wp_insert_post( $data_page );

			if ( ! is_wp_error( $id ) ) {

				$cache['ids'][ $page_key ] = $id;
				$cache['slugs'][ $id ]     = $data_page['post_slug'];
			}
		} else {

			$cache['ids'][ $page_key ]     = $exists->ID;
			$cache['slugs'][ $exists->ID ] = $exists->post_name;
		}

		if ( $update_option === true ) {

			$option = get_option( self::OK_GENERAL_DATA );

			if ( empty( $option['options']['pages'] ) ) {

				// settings never been saved
				$option['options']['pages'] = $cache['ids'];
				$option['options']['slugs'] = $cache['slugs'];

				update_option( self::OK_GENERAL_DATA, $option, true );

			} else {

				$id                                      = $cache['ids'][ $page_key ];
				$option['options']['pages'][ $page_key ] = $id;
				$option['options']['slugs'][ $id ]       = $cache['slugs'][ $id ];

				update_option( self::OK_GENERAL_DATA, $option, true );
			}
		}

		return array(
			$page_key => $cache['slugs'][ $cache['ids'][ $page_key ] ],
			'__all__' => $cache,
		);
	}


	/**
	 * Get the page slug by its id
	 *
	 * @since 1.0.0
	 *
	 * @param $page_id
	 * @param bool    $update_option - optionally force to update the options
	 *
	 * @return string
	 */
	public function update_settings_slug( $page_id, $update_option = false ) {

		$post = get_post( $page_id );

		if ( empty( $post ) ) {

			return '';
		}

		$slug[ $post->ID ] = $post->post_name;

		if ( $update_option === true ) {

			$option = get_option( self::OK_GENERAL_DATA );

			$option['options']['slugs'][ $page_id ] = $slug[ $page_id ];

			update_option( self::OK_GENERAL_DATA, $option, true );
		}

		return $slug[ $page_id ];
	}


	/**
	 * Page settings is save but for some reason slug settings is not updated, to make it updated
	 *
	 * @since 1.0.0
	 *
	 * @param $page_ids
	 * @param bool     $update_option
	 *
	 * @return array
	 */
	public function update_all_settings_slug( $page_ids, $update_option = true ) {

		$pages = array();
		$slugs = array();
		$args  = array(
			'post_type'      => 'page',
			'posts_per_page' => -1,
			'post__in'       => array(),
		);

		foreach ( $page_ids as $pg => $id ) {

			$args['post__in'][] = $id - 0;

			$pages[ $pg ] = $id;
			$slugs[ $id ] = '';
		}

		$posts = get_posts( $args );

		foreach ( $posts as $p ) {
			$slugs[ $p->ID ] = $p->post_name;
		}

		if ( $update_option === true ) {

			$option = get_option( self::OK_GENERAL_DATA );

			$option['options']['slugs'] = $slugs;

			update_option( self::OK_GENERAL_DATA, $option, true );
		}

		return $slugs;
	}


	/**
	 * Share options
	 */
	public static function share_options() {

		$link['facebook']  = array(
			'label'  => __( 'Facebook', 'wp-fundraising-donation' ),
			'url'    => 'https://www.facebook.com/sharer/sharer.php',
			'icon'   => 'met-social met-social-facebook',
			'params' => array(
				'u' => '[%url%]',
				't' => '[%title%]',
				'v' => 3,
			),
		);
		$link['twitter']   = array(
			'label'  => __( 'Twitter', 'wp-fundraising-donation' ),
			'url'    => 'https://twitter.com/intent/tweet',
			'icon'   => 'met-social met-social-twitter',
			'params' => array(
				'text'             => '[%title%] [%url%]',
				'original_referer' => '[%url%]',
				'related'          => '[%author%]',
			),
		);
		$link['linkedin']  = array(
			'label'  => __( 'LinkedIn', 'wp-fundraising-donation' ),
			'url'    => 'https://www.linkedin.com/shareArticle',
			'icon'   => 'met-social met-social-linkedin',
			'params' => array(
				'url'     => '[%url%]',
				'title'   => '[%title%]',
				'summary' => '[%details%]',
				'source'  => '[%source%]',
				'mini'    => true,
			),
		);
		$link['pinterest'] = array(
			'label'  => __( 'Pinterest', 'wp-fundraising-donation' ),
			'url'    => 'https://pinterest.com/pin/create/button/',
			'icon'   => 'met-social met-social-pinterest',
			'params' => array(
				'url'         => '[%url%]',
				'media'       => '[%media%]',
				'description' => '[%details%]',
			),
		);
		$link['whatsapp']  = array(
			'label'  => __( 'WhatsApp', 'wp-fundraising-donation' ),
			'url'    => 'https://web.whatsapp.com/send',
			'icon'   => 'met-social met-social-whatsapp',
			'params' => array( 'text' => '[%title%] [%url%]' ),
		);
		$link['email']     = array(
			'label'  => __( 'Email', 'wp-fundraising-donation' ),
			'url'    => 'mailto:',
			'icon'   => 'met-social met-social-email',
			'params' => array(
				/* translators: 1: campaign title, 2: campaign URL */
				'body'    => sprintf( __( 'Title: %1$s \n\n URL: %2$s', 'wp-fundraising-donation' ), '[%title%]', '[%url%]' ),
				'subject' => '[%title%]',
			),
		);

		return apply_filters( 'wfp_fundraising_single_social_providers', $link ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Reason: compatibility filter kept for social share providers
	}


	// share provider
	public static function generate_social() {
		$link = self::share_options();

		if ( empty( $link ) && ! is_array( $link ) ) {
			return '';
		}

		global $post;

		global $wfp_fundraising_currentUrl, $wfp_fundraising_title, $wfp_fundraising_author, $wfp_fundraising_details, $wfp_fundraising_source, $wfp_fundraising_media, $wfp_fundraising_app_id;

		if ( is_object( $post ) && isset( $post->ID ) ) {
			$wfp_fundraising_currentUrl = get_permalink(); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: global social-share URL prefixed to `wfp_fundraising_`
		} elseif ( isset( $_SERVER['HTTP_HOST'] ) && isset( $_SERVER['REQUEST_URI'] ) ) {
			$wfp_fundraising_currentUrl = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http' ) . '://' . sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) . sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: global social-share URL prefixed to `wfp_fundraising_`
		} else {
			$wfp_fundraising_currentUrl = ''; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: global social-share URL prefixed to `wfp_fundraising_`
		}

		$postId = isset( $post->ID ) ? $post->ID : 0;

		$current_id = get_current_user_id();

		$wfp_fundraising_author = 'xpeedstudio'; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: global social-share author prefixed to `wfp_fundraising_`

		$wfp_fundraising_details = ''; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: global social-share details prefixed to `wfp_fundraising_`
		if ( isset( $post->post_excerpt ) && strlen( $post->post_excerpt ) > 2 ) {
			$wfp_fundraising_details = $post->post_excerpt; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: global social-share details prefixed to `wfp_fundraising_`
		}
		$wfp_fundraising_title = get_the_title(); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: global social-share title prefixed to `wfp_fundraising_`

		$wfp_fundraising_source        = get_bloginfo(); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: global social-share source prefixed to `wfp_fundraising_`
		$thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $postId ), 'full' );

		$custom_logo_id = get_theme_mod( 'custom_logo' );
		$image          = wp_get_attachment_image_src( $custom_logo_id, 'full' );
		$customLogo     = isset( $image[0] ) ? $image[0] : '';

		$wfp_fundraising_media  = isset( $thumbnail_src[0] ) ? $thumbnail_src[0] : $customLogo; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: global social-share media prefixed to `wfp_fundraising_`
		$wfp_fundraising_app_id = '463603197734720'; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: global social-share app id prefixed to `wfp_fundraising_`

		$metaSocialKey   = 'wfp_share_media_options';
		$getMetaSocialOp = get_option( $metaSocialKey );
		$getMetaSocial   = isset( $getMetaSocialOp['media'] ) ? $getMetaSocialOp['media'] : array();

		$linkData  = '';
		$linkData .= '<ul class="wfp-share-media">';
		foreach ( $link as $k => $v ) {

			if ( ! isset( $getMetaSocial[ $k ]['enable'] ) ) {
				continue;
			}
			$label       = isset( $v['label'] ) ? $v['label'] : '';
			$url_get     = isset( $v['url'] ) ? $v['url'] : '';
			$params_data = isset( $v['params'] ) ? $v['params'] : '';
			$onclick     = isset( $v['onclick'] ) ? $v['onclick'] : 'wfp_share(this)';
			$icon        = isset( $v['icon'] ) ? $v['icon'] : '';

			$urlCon = array_combine(
				array_keys( $params_data ),
				array_map(
					function( $v ) {
						global $wfp_fundraising_currentUrl, $wfp_fundraising_title, $wfp_fundraising_author, $wfp_fundraising_details, $wfp_fundraising_source, $wfp_fundraising_media, $wfp_fundraising_app_id;

						return str_replace(
							array(
								'[%url%]',
								'[%title%]',
								'[%author%]',
								'[%details%]',
								'[%source%]',
								'[%media%]',
								'[%app_id%]',
							),
							array( $wfp_fundraising_currentUrl, $wfp_fundraising_title, $wfp_fundraising_author, $wfp_fundraising_details, $wfp_fundraising_source, $wfp_fundraising_media, $wfp_fundraising_app_id ),
							$v
						);
					},
					$params_data
				)
			);

			$params = http_build_query( $urlCon, '&' );
			if ( ! empty( $url_get ) ) {
				$linkData .= '<li> <a href="javascript:void();"  class="wfp-' . $k . '" title="' . $label . '" id="wfp-' . $k . '" data-link="' . $url_get . '?' . $params . '" onclick="' . $onclick . '"> <i class="' . $icon . '"></i> </a></li>';
			} else {
				$url_get   = $wfp_fundraising_currentUrl;
				$linkData .= '<li> <a href="javascript:void();" class="wfp-' . $k . '" title="' . $label . '" id="wfp-' . $k . '" data-link="' . $url_get . '" onclick="' . $onclick . '"> <i class="' . $icon . '"></i> </a></li>';
			}
		}
		$linkData .= '</ul>';

		return $linkData;
	}


	/**
	 * Global options
	 */
	public static function global_options() {
		return array(
			'goal_setup'       => array(
				'name' => __( 'Goal Setup', 'wp-fundraising-donation' ),
				'note' => __( 'Enable goal setting in all compaign forms. - Tab "Goal Setup" in Campaign Form.', 'wp-fundraising-donation' ),
			),
			'pledge_setup'     => array(
				'name' => __( 'Pledge Setup', 'wp-fundraising-donation' ),
				'note' => __( 'Enable pledge setting in all compaign forms for Crowdfunding. - Tab "Pledge Setup" in Campaign Form.', 'wp-fundraising-donation' ),
			),
			'custom_fileds'    => array(
				'name' => __( 'Custom Fileds', 'wp-fundraising-donation' ),
				'note' => __( 'Use custom fileds in forms like as [Phone, Address etc.] - Section "Form Content" in Campaign Form.', 'wp-fundraising-donation' ),
			),
			// 'additional_fees'  => [
			// 'name' => 'Additional Fees',
			// 'note' => 'Enable vat(%) amount for each payment. - Section "General" in Campaign Form.',
			// ],
			'limit_setup'      => array(
				'name' => __( 'Limit Options', 'wp-fundraising-donation' ),
				'note' => __( 'Set limit amount (min, max) on the campaign submission form. - Section "General" in Campaign Form.', 'wp-fundraising-donation' ),
			),
			'contributor_info' => array(
				'name' => __( 'Contributor Info', 'wp-fundraising-donation' ),
				'note' => __( 'Enable this option to display the contributors for this Campaign. - Section "Settings" in Campaign Form.', 'wp-fundraising-donation' ),
			),
		);
	}


	/*
	* Setup Plugin Option data
	*/

	public static function default_setup() {
		$url = admin_url() . 'edit.php?post_type=' . self::post_type() . '&page=settings&tab=gateway';

		return array(
			'welcome'      => array(
				'headding' => __( 'Welcome FundEngine', 'wp-fundraising-donation' ),
				'details'  => __( 'Welcome to our FundEngine for Single Donation & Crowdfunding', 'wp-fundraising-donation' ),
				'img_url'  => \WFP_Fundraising::plugin_url() . 'views/welcome/image/screenshot-welcome.png',
				'button'   => array(
					'pre'       => __( 'Cancel', 'wp-fundraising-donation' ),
					'pre_type'  => 'close',
					'next'      => __( 'Start', 'wp-fundraising-donation' ),
					'next_type' => 'next',
					'data'      => '',
				),
				'return'   => array(
					'type'     => 'next',
					'location' => '',
				),
			),
			'campaign'     => array(
				'headding' => __( 'Campaign Format', 'wp-fundraising-donation' ),
				'details'  => __( 'Select once format for Campaign then click "Ok" button and go to next step.', 'wp-fundraising-donation' ),
				'img_url'  => '',
				'button'   => array(
					'pre'       => __( 'Previous', 'wp-fundraising-donation' ),
					'pre_type'  => 'pre',
					'next'      => __( 'Ok', 'wp-fundraising-donation' ),
					'next_type' => 'install',
				),
				'data'     => 'include/setup-campaign.php',
			),

			'payment_type' => array(
				'headding' => __( 'Payment Getway', 'wp-fundraising-donation' ),
				'details'  => __( 'Select payment getway for Campaigns and click "Select" button and go to next step.', 'wp-fundraising-donation' ),
				'img_url'  => '',
				'button'   => array(
					'pre'       => __( 'Previous', 'wp-fundraising-donation' ),
					'pre_type'  => 'pre',
					'next'      => __( 'Select', 'wp-fundraising-donation' ),
					'next_type' => 'install',
				),
				'data'     => 'include/setup-payment-type.php',
			),
			/*
			'payment_method'  => ['headding' => 'Payment Method', 'details' => '', 'img_url' => '', 'button' => ['pre' => 'Previous',  'pre_type' => 'pre', 'next' => 'Setup', 'next_type' => 'install'], 'data' => 'include/setup-payment-method.php'
			],
			'currency' => ['headding' => 'Currency & Country Selection', 'details' => '', 'img_url' => '', 'button' => ['pre' => 'Skip', 'pre_type' => 'skip',  'next' => 'Install', 'next_type' => 'install'], 'data' => 'include/setup-currency.php'
			],*/
			'finish'       => array(
				'headding' => __( 'Finish Setup..', 'wp-fundraising-donation' ),
				'details'  => __( 'Click this finish button and complete your basic setup process and go to next settings.', 'wp-fundraising-donation' ),
				'img_url'  => '',
				'button'   => array(
					'pre'       => __( 'Previous', 'wp-fundraising-donation' ),
					'pre_type'  => 'pre',
					'next'      => __( 'Finish', 'wp-fundraising-donation' ),
					'next_type' => 'finish',
				),
				'data'     => '',
				'return'   => array(
					'type'     => 'redirect',
					'location' => $url,
				),
			),
		);
	}


	public static function check_setup( $page = 'settings' ) {
		if ( $page === 'settings' ) {
			return true;
		}
		$metaSetupKey = 'wfp_setup_services_data';
		$getSetUpData = get_option( $metaSetupKey );
		$gateWaysData = isset( $getSetUpData['services'] ) ? $getSetUpData['services'] : '';
		$url          = admin_url() . 'edit.php?post_type=' . self::post_type() . '&page=settings';
		if ( empty( $gateWaysData ) ) {
			echo '
				<script>
					//setTimeout(function() {
					  window.location.href = "' . esc_url_raw( $url ) . '";
					//}, 20);
				</script>
				';
			wp_die();
		} else {
			$setpData    = self::default_setup();
			$checkFinish = isset( $getSetUpData['services']['finish'] ) ? $getSetUpData['services']['finish'] : current( array_keys( self::default_setup() ) );
			if ( $checkFinish != 'finish' ) {
				echo '
				<script>
					//setTimeout(function() {
					  window.location.href = "' . esc_url_raw( $url ) . '";
					//}, 20);
				</script>
				';
				wp_die();
			}
		}
	}


	public static function check_setup_page() {
		$metaSetupKey = 'wfp_setup_services_data';
		$getSetUpData = get_option( $metaSetupKey );
		$gateWaysData = isset( $getSetUpData['services'] ) ? $getSetUpData['services'] : array();
		if ( empty( $gateWaysData ) ) {
			return true;
		} else {
			$checkFinish = isset( $getSetUpData['services']['finish'] ) ? $getSetUpData['services']['finish'] : current( array_keys( self::default_setup() ) );
			if ( $checkFinish != 'finish' ) {
				return true;
			}
		}

		return false;
	}


	public static function sanitize( $value, $senitize_func = 'sanitize_text_field' ) {
		$senitize_func = ( in_array(
			$senitize_func,
			array(
				'sanitize_email',
				'sanitize_file_name',
				'sanitize_hex_color',
				'sanitize_hex_color_no_hash',
				'sanitize_html_class',
				'sanitize_key',
				'sanitize_meta',
				'sanitize_mime_type',
				'sanitize_sql_orderby',
				'sanitize_option',
				'sanitize_text_field',
				'sanitize_title',
				'sanitize_title_for_query',
				'sanitize_title_with_dashes',
				'sanitize_user',
				'esc_url_raw',
				'wp_filter_nohtml_kses',
			)
		) ) ? $senitize_func : 'sanitize_text_field';

		if ( ! is_array( $value ) ) {
			return $senitize_func( $value );
		} else {
			return array_map(
				function( $inner_value ) use ( $senitize_func ) {
					return self::sanitize( $inner_value, $senitize_func );
				},
				$value
			);
		}
	}


	/**
	 * Validate if email is valid but it does not confirm is email address exists or not
	 *
	 * @param $str
	 *
	 * @return bool
	 */
	public static function valid_email( $str ) {

		return filter_var( $str, FILTER_VALIDATE_EMAIL ) !== false && is_email( $str );
		// return !(!filter_var($str, FILTER_VALIDATE_EMAIL));
		// return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? false : true;
	}


	/*Donate meta data for fundraising */

	public static function wfp_update_metadata( $wfp_post_id = 0, $meta_key = '', $meta_value = '', $unique = true ) {
		global $wpdb;
		$meta_value = is_array( $meta_value ) ? serialize( $meta_value ) : $meta_value;

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.SlowDBQuery.slow_db_query_meta_key, WordPress.DB.SlowDBQuery.slow_db_query_meta_value -- Counts rows in the plugin's own meta table before updating or inserting; intentional.
		$myrows = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'wdp_fundraising_meta WHERE meta_key = %s AND donate_id = %d', $meta_key, $wfp_post_id ) );
		if ( $myrows > 0 && $unique == true ) {
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.SlowDBQuery.slow_db_query_meta_key, WordPress.DB.SlowDBQuery.slow_db_query_meta_value -- Updates an existing row in the plugin's meta table.
				$wpdb->update(
				$wpdb->prefix . 'wdp_fundraising_meta',
				array( 'meta_value' => sanitize_text_field( $meta_value ) ), // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value -- Plugin custom meta table; query is indexed and intentional.
				array(
					'meta_key'  => sanitize_text_field( $meta_key ), // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- Plugin custom meta table; query is indexed and intentional.
					'donate_id' => intval( $wfp_post_id ),
				)
			);

			return true;
		} else {
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.SlowDBQuery.slow_db_query_meta_key, WordPress.DB.SlowDBQuery.slow_db_query_meta_value -- Inserts a row into the plugin's meta table.
				$wpdb->insert(
				$wpdb->prefix . 'wdp_fundraising_meta',
				array(
					'donate_id'  => intval( $wfp_post_id ),
					'meta_key'   => sanitize_text_field( $meta_key ), // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- Plugin custom meta table; query is indexed and intentional.
					'meta_value' => sanitize_text_field( $meta_value ), // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value -- Plugin custom meta table; query is indexed and intentional.
				)
			);

			return true;
		}
	}


	public static function wfp_get_metadata( $wfp_post_id = 0, $meta_key = '' ) {
		global $wpdb;
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Reads plugin metadata directly from dedicated table; intentional and prepared.
		$myrows = $wpdb->get_results( $wpdb->prepare( 'SELECT `meta_value` FROM ' . $wpdb->prefix . 'wdp_fundraising_meta WHERE meta_key = %s AND donate_id = %d', $meta_key, $wfp_post_id ) );
		$result = isset( $myrows[0]->meta_value ) ? $myrows[0]->meta_value : '';

		$unserialized = @unserialize( $result, array( 'allowed_classes' => false ) );

		return ( $unserialized !== false ) ? $unserialized : $result;
	}


	/*Check plugin of Install or active*/
	public static function missing_woocommerce() {
		$metaSetupKey     = 'wfp_setup_services_data';
		$getSetUpData     = get_option( $metaSetupKey );
		$wfpGateCampaignData = isset( $getSetUpData['services']['payment'] ) ? $getSetUpData['services']['payment'] : 'default';
		if ( $wfpGateCampaignData == 'default' ) {
			$btn['status'] = false;

			return $btn;
		}

		if ( file_exists( \WFP_Fundraising::plugin_parent_dir() . 'woocommerce/woocommerce.php' ) ) {
			if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
				$btn['label'] = esc_html__( 'Activate Woocommerce', 'wp-fundraising-donation' );
				$btn['url']   = wp_nonce_url( 'plugins.php?action=activate&plugin=woocommerce/woocommerce.php&plugin_status=all&paged=1', 'activate-plugin_woocommerce/woocommerce.php' );

				$btn['status'] = true;
			} else {
				$btn['status'] = false;
			}
		} else {
			$btn['label']  = esc_html__( 'Install Woocommerce', 'wp-fundraising-donation' );
			$btn['url']    = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=woocommerce' ), 'install-plugin_woocommerce' );
			$btn['status'] = true;

		}

		return $btn;
	}


	/**
	 *
	 * @since 1.0.0
	 *
	 * @param array $option
	 *
	 * @return array
	 */
	public function get_mapped_page_slug( $option = array() ) {

		$ret = array();

		if ( empty( $option ) ) {

			$option = get_option( self::OK_GENERAL_DATA );
		}

		if ( empty( $option['options']['pages'] ) ) {

			$cached = $this->create_default_pages();

			foreach ( $cached['ids'] as $pg => $id ) {

				$ret[ $pg ] = $cached['slugs'][ $id ];
			}

			return $ret;
		}

		// this should not be the empty - but easin has found error here
		if ( empty( $option['options']['slugs'] ) ) {

			$slugs = $this->update_all_settings_slug( $option['options']['pages'] );

			foreach ( $option['options']['pages'] as $pg => $id ) {

				$ret[ $pg ] = $slugs[ $id ];
			}

			return $ret;
		}

		foreach ( $option['options']['pages'] as $pg => $id ) {

			$ret[ $pg ] = $option['options']['slugs'][ $id ];
		}

		return $ret;
	}


	/**
	 * Get checkout page slug that is mapped from settings
	 *
	 * @since 1.0.0
	 *
	 * @param array  $option
	 * @param string $for - optionally get other page slug by this, default is checkout page
	 *
	 * @return array|string
	 */
	public function get_mapped_checkout_page_slug( $option = array(), $for = 'checkout' ) {

		if ( empty( $option ) ) {

			$option = get_option( self::OK_GENERAL_DATA );
		}

		if ( empty( $option['options']['pages'][ $for ] ) ) {

			return $this->create_default_checkout_page( false, $for );
		}

		$id = $option['options']['pages'][ $for ];

		if ( empty( $option['options']['slugs'][ $id ] ) ) {

			return $this->update_settings_slug( $id, true );
		}

		return $option['options']['slugs'][ $id ];
	}


	public static function _encode_json( $str = '' ) {
		return json_encode( $str, JSON_UNESCAPED_UNICODE );
	}


	public static function instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * Get how may days left for a future date
	 *
	 * @since 1.0.0
	 *
	 * @param $future_date
	 * @param bool        $today
	 *
	 * @return string
	 */
	public static function get_days_left( $future_date, $today = true ) {

		$to_date = gmdate( 'Y-m-d' );

		if ( $today !== true ) {

			$to_date = $today;
		}

		$date1         = date_create( $to_date );
		$date2         = date_create( $future_date );
		$formattedDate = '';

		if ( $date1 != false && $date2 != false ) {

			$diff          = date_diff( $date1, $date2 );
			$formattedDate = $diff->format( '%R%a' );
		}

		return $formattedDate;
	}


	/**
	 *
	 * @since 1.1.16
	 *
	 * @return array
	 */
	public static function force_recheck_for_pages() {

		$pageArray  = self::default_custom_page();
		$current_id = get_current_user_id();
		$cache      = array();

		foreach ( $pageArray as $page => $arr ) {

			$blog_page_check = get_page_by_path( $arr['slug'], 'OBJECT', 'page' );

			if ( ! isset( $blog_page_check->ID ) ) {

				if ( $page == 'campaign' ) {
					$check_content = '[' . $arr['short_code'] . ' limit="9" layout="grid" column="3" orderby="post_date" order="DESC" categories="" goal="Yes" user="Yes" category="Yes" title="Yes" excerpt="Yes" featured="Yes"]';
				} else {
					$check_content = '[' . $arr['short_code'] . ']';
				}

				$data_page = array(
					'post_type'      => 'page',
					'post_title'     => $arr['title'],
					'post_name'      => $arr['slug'],
					'post_content'   => $check_content,
					'post_status'    => 'publish',
					'post_author'    => $current_id,
					'post_slug'      => $arr['slug'],
					'comment_status' => 'closed',
				);

				$id = wp_insert_post( $data_page );

				if ( ! is_wp_error( $id ) ) {

					$cache['ids'][ $page ] = $id;
					$cache['slugs'][ $id ] = $data_page['post_slug'];
				}
			} else {

				$cache['ids'][ $page ]                  = $blog_page_check->ID;
				$cache['slugs'][ $blog_page_check->ID ] = $blog_page_check->post_name;

				if ( $arr['title'] != $blog_page_check->post_title ) {

					$blog_page_check->post_title = $arr['title'];

					wp_update_post( $blog_page_check, false );
				}
			}
		}

		return $cache;
	}


	public function get_curr_symbol( $getMetaGeneral, $paymentType ) {

		if ( $paymentType == 'woocommerce' ) {

			$currency_symbol = get_woocommerce_currency_symbol();

			return empty( $currency_symbol ) ? 'USD' : $currency_symbol;
		}

		require_once \WFP_Fundraising::plugin_dir() . 'country-module/country-info.php';

		$def = empty( $getMetaGeneral['currency']['name'] ) ? 'US-USD' : $getMetaGeneral['currency']['name'];

		$exp = explode( '-', $def );

		$cn = $exp[0];

		/**
		 * Our code ensures there will be exactly two part name-code will be returned
		 * So we can safely return $exp[1]
		 */
		$currCode = $exp[1];

		return empty( $wfpCountryList[ $cn ]['currency']['symbol'] ) ? $currCode : $wfpCountryList[ $cn ]['currency']['symbol'];
	}


	/**
	 *
	 * @since 1.2.0
	 *
	 * @param $campaign_id
	 * @param $saved_options
	 *
	 * @return array
	 */
	public function get_active_payment_settings( $campaign_id, $saved_options ) {

		$payment_settings   = array();
		$pp_accounts        = array();
		$account_preference = array();
		$def_payment_array  = wfp_fundraising_payment_services();

		if ( did_action( Key::FUNDRAISING_PRO_LOADED ) ) {

			$account_preference = get_post_meta( $campaign_id, \WP_Fundraising_Pro\Keys::OK_PERSONAL_PAYMENT_PREFERENCE, true );
			$pp_accounts        = \WP_Fundraising_Pro\Fundraising_Pro::instance()->get_pp_accounts_settings( $campaign_id );
		}

		foreach ( $def_payment_array as $ac_type => $dummy ) {

			/**
			 * Either has no account preference (for old data!) or it does not have pro plugin
			 */
			if ( empty( $account_preference ) ) {

				if ( ! empty( $saved_options[ $ac_type ]['enable'] ) ) {

					$payment_settings[ $ac_type ] = $saved_options[ $ac_type ];
				}

				/**
				 * We will not execute below code because this user does not have pro plugin
				 * Or has not updated the settings for this campaign
				 */
				continue;
			}

			/**
			 * The use has updated the campaign settings and now it has preferences!
			 */
			if ( $account_preference[ $ac_type ] == \WfpFundraising\Apps\Key::PAYMENT_PREFERENCE_GLOBAL ) {

				if ( ! empty( $saved_options[ $ac_type ]['enable'] ) ) {

					$payment_settings[ $ac_type ] = $saved_options[ $ac_type ];
				}
			} else {

				if ( ! empty( $pp_accounts['gateways']['services'][ $ac_type ]['enable'] ) ) {

					$payment_settings[ $ac_type ] = $pp_accounts['gateways']['services'][ $ac_type ];
				}
			}
		}

		return $payment_settings;
	}


	/**
	 * Default custom filed in donational submit form
	 *
	 * Note: the default labels below are deliberately not wrapped in __().
	 * The label is slugified into the field's name attribute and _wfp_* meta
	 * key, and keyword-matched against /\b(first|full|last|nick|email)\b/ to
	 * identify the donor's name and email. Translating them would change the
	 * POST keys and break that detection. Labels are editable per campaign,
	 * so site owners can still localise what visitors actually see.
	 */
	public static function default_addition_filed() {
		return array(
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
	}


	/**
	 *
	 * @since 1.2.1
	 *
	 * @return mixed
	 */
	public static function get_mandatory_form_fields() {

		$fields['additional']['first_name'] = array(
			'type'      => 'text',
			'required'  => true,
			'closeable' => false,
			'id'        => 'xs_donate_first_name',
			'lebel'     => __( 'First Name', 'wp-fundraising-donation' ),
		);

		$fields['additional']['last_name'] = array(
			'type'      => 'text',
			'required'  => true,
			'closeable' => false,
			'id'        => 'xs_donate_last_name',
			'lebel'     => __( 'Last Name', 'wp-fundraising-donation' ),
		);

		$fields['additional']['email_address'] = array(
			'type'      => 'email',
			'required'  => true,
			'closeable' => false,
			'id'        => 'xs_donate_email_address',
			'lebel'     => __( 'Email Address', 'wp-fundraising-donation' ),
		);

		$fields['additional']['country_destination'] = array(
			'type'      => 'select',
			'required'  => true,
			'closeable' => false,
			'id'        => 'xs_donate_country_pledge',
			'lebel'     => __( 'Country Destination', 'wp-fundraising-donation' ),
		);

		$fields['additional']['street_address'] = array(
			'type'      => 'text',
			'required'  => true,
			'closeable' => false,
			'id'        => 'xs_donate_street_pledge',
			'lebel'     => __( 'Street Address', 'wp-fundraising-donation' ),
		);

		$fields['additional']['city'] = array(
			'type'      => 'text',
			'required'  => true,
			'closeable' => false,
			'id'        => 'xs_donate_city_pledge',
			'lebel'     => __( 'City :', 'wp-fundraising-donation' ),
		);

		$fields['additional']['postcode'] = array(
			'type'      => 'text',
			'required'  => true,
			'closeable' => false,
			'id'        => 'xs_donate_post_pledge',
			'lebel'     => __( 'Postcode / ZIP :', 'wp-fundraising-donation' ),
		);

		return $fields;
	}

}
