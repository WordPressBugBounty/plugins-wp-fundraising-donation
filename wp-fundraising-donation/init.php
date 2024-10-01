<?php

namespace WfpFundraising;

defined( 'ABSPATH' ) || exit;

/**
 * Class Name : Init - This main class for review plugin
 * Class Type : Normal class
 *
 * initiate all necessary classes, hooks, configs
 *
 * @since 1.0.0
 * @access Public
 */
class Init {


	/**
	 * Construct the plugin object
	 *
	 * @since 1.0.0
	 * @access private
	 */
	public function __construct() {

		Apps\Settings::instance()->_init();
		Apps\Fundraising::instance()->_init();
		Apps\Content::instance()->_init();
		Apps\Featured::instance()->_init();
		Apps\Gallery::instance()->_init();

		// elementor widget controls
		if ( file_exists( plugin_dir_path( dirname( __FILE__ ) ) . 'elementor/elementor.php' ) ) {
			Apps\Elementor\Elements::instance()->_init();
		}

		// payment method setup data
		if ( file_exists( __DIR__ . '/apps/payment/setup.php' ) ) {
			require_once __DIR__ . '/apps/payment/setup.php';
		}
	}
}
