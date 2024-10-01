<?php

namespace WfpFundraising\Core;

defined( 'ABSPATH' ) || exit;

class Enqueue_Hook {

	public function __construct() {

		add_action( 'wp_enqueue_scripts', array( $this, 'js_css_public' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'js_css_admin' ) );

		// admin_enqueue_scripts
		// customize_controls_enqueue_scripts
		// enqueue_block_assets
		// enqueue_block_editor_assets
		// enqueue_embed_scripts
		// login_enqueue_scripts
		// wp_enqueue_code_editor
		// wp_enqueue_editor
		// wp_enqueue_media
		// wp_enqueue_scripts
	}

	public function js_css_public() {
	}

	public function js_css_admin() {

		wp_enqueue_style( 'wfp_donation_admin_css', \WFP_Fundraising::plugin_url() . 'assets/admin/css/wfp-wp-dashboard.css', array(), \WFP_Fundraising::version() );

		wp_register_script( 'wfp_donation_admin_js', \WFP_Fundraising::plugin_url() . 'assets/admin/js/admin_main.js', array( 'jquery' ), \WFP_Fundraising::version(), false );

		wp_enqueue_script( 'wfp_donation_admin_js' );

		wp_localize_script(
			'wfp_donation_admin_js',
			'wfp_conf',
			array(
				'siteurl'    => get_option( 'siteurl' ),
				'nonce'      => wp_create_nonce( 'wp_rest' ),
				'rest_nonce' => wp_create_nonce( 'wp_rest' ),
				'resturl'    => get_rest_url(),
				'ajaxurl'    => admin_url( 'admin-ajax.php' ),
			)
		);

		wp_localize_script(
			'wfp_donation_admin_js',
			'wfpAdminObj',
			array(
				'siteurl'    => get_option( 'siteurl' ),
				'nonce'      => wp_create_nonce( 'wp_rest' ),
				'rest_nonce' => wp_create_nonce( 'wp_rest' ),
				'resturl'    => get_rest_url(),
				'ajaxurl'    => admin_url( 'admin-ajax.php' ),
			)
		);
	}
}
