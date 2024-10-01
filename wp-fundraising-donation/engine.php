<?php

namespace WfpFundraising;

use WfpFundraising\Core\Enqueue_Hook;

class Engine {

	private static $instance;


	public function run() {

		$any_error = $this->check_dependency_and_show_notice();

		if ( $any_error ) {
			return;
		}

		/**
		 * Enqueueing all scripts and styles
		 */
		new Enqueue_Hook();

		add_action( 'init', array( $this, '__page_create' ) );
	}

	public function check_dependency_and_show_notice() : bool {

		return false;
	}

	public function __page_create() {

		/**
		 * On every version update check if the necessary pages are created
		 */
		$version = get_option( 'wfp_fundraising_version', '1.0.0' );

		if ( $version !== \WFP_Fundraising::version() ) {

			/**
			 * We should not run db update anymore - 20210803 : AR
			 * It should be run on activation only
			 */
			// \WfpFundraising\Apps\Fundraising::instance()->wfp_action_create_table_donation();

			/**
			 * Necessary page creation checking
			 */
			\WfpFundraising\Apps\Settings::instance()->wfp_create_page_setup();

			update_option( 'wfp_fundraising_version', \WFP_Fundraising::version() );
		}
	}

	public static function instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}

