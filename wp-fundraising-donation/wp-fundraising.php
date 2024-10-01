<?php

/**
 * Plugin Name: FundEngine – Donation and Crowdfunding Platform
 * Description: FundEngine - Fundraising Donation plugin and Crowdfunding Platform comes with Single donation and crowdfunding solution. This plugin gives the ability to anyone to create a crowdfunding and single donation site with WordPress.
 * Plugin URI: https://wpmet.com/
 * Author: Wpmet
 * Version: 1.7.1
 * Author URI: https://wpmet.com/
 * Text Domain: wp-fundraising
 *
 * @package WpFundraising
 * @category Free
 *
 * License:  GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 */

defined( 'ABSPATH' ) || exit;

define( 'WFP_FUNDRAISING_VERSION', '1.7.1' );
define( 'WFP_FUNDRAISING_PREVIOUS_STABLE_VERSION', '1.7.0' );


/**
 * Auto loading the files
 */
require __DIR__ . '/autoloader.php';
//wp_met_plugins page rquire
require_once plugin_dir_path(__FILE__) . 'utilities/plugins/plugins.php';
class WFP_Fundraising {

	const PREFIX = 'wfp-donation';

	public static function version() {

		return WFP_FUNDRAISING_VERSION;
	}

	public static function min_el_version() {
		return '3.0.0';
	}

	public static function min_php_version() {
		return '7.0';
	}

	public static function min_woo_version() {
		return '4.1';
	}

	public static function plugin_file() {
		return __FILE__;
	}

	public static function plugin_url() {
		return trailingslashit( plugin_dir_url( __FILE__ ) );
	}

	public static function plugin_dir() {
		return trailingslashit( plugin_dir_path( __FILE__ ) );
	}

	public static function plugin_parent_dir() {
		return trailingslashit( plugin_dir_path( dirname( __FILE__ ) ) );
	}

	public static function widget_dir() {
		return self::plugin_dir() . 'widgets/';
	}

	public static function widget_url() {
		return self::plugin_url() . 'widgets/';
	}

	public static function module_dir() {
		return self::plugin_dir() . 'modules/';
	}

	public static function module_url() {
		return self::plugin_url() . 'modules/';
	}

	public static function views_dir() {
		return self::plugin_dir() . 'views/';
	}

	public function __construct() {

		add_action( 'init', array( $this, 'i18n' ) );
		add_action( 'plugins_loaded', array( $this, 'init' ), 100 );
	}

	public function i18n() {

		load_plugin_textdomain( 'wp-fundraising', false, self::plugin_dir() . 'languages/' );
	}

	public function init() {

		do_action( 'wfp_fundraising/before_loaded' );

		\WfpFundraising\Engine::instance()->run();

		do_action( 'wfp/fundraising/plugin_loaded' ); // legacy
		do_action( 'wfp_fundraising/after_loaded' );
	}
}

new WFP_Fundraising();


add_action(
	'plugins_loaded',
	function () {

		\WfpFundraising\Plugin::instance( __FILE__ )->init();

		wfp_donate_load_plugin_textdomain();

		/**
		 * Tell plugin is loaded
		 */
		do_action( 'wfp/fundraising/plugin_loaded' );
	},
	0
);

/**
 * Load Fundraising textdomain.
 *
 * @return void
 * @since 1.0.0
 */
function wfp_donate_load_plugin_textdomain() {

	/**
	 * Load Review Loader main page.
	 *
	 * @return plugin output
	 * @since 1.0.0
	 */
	require_once \WFP_Fundraising::plugin_dir() . 'init.php';

	new \WfpFundraising\Init();

	// include woocommerce services
	require_once \WFP_Fundraising::plugin_dir() . 'apps/wfpwoocommerce.php';
}


function activate_fundraising() {

	/**
	 * Checking the tables on activation
	 */
	\WfpFundraising\Apps\Fundraising::instance()->wfp_action_create_table_donation();

	/**
	 * Check for default pages
	 */
	\WfpFundraising\Apps\Settings::instance()->wfp_create_page_setup();
}

function deactivate_fundraising() {}

register_activation_hook( __FILE__, 'activate_fundraising' );
register_deactivation_hook( __FILE__, 'deactivate_fundraising' );
