<?php

namespace WfpFundraising\Apps;

defined( 'ABSPATH' ) || exit;

/**
 * Class Name : Donate - This access for admin
 * Class Type : Normal class
 *
 * initiate all necessary classes, hooks, configs
 *
 * @since 1.0.0
 * @access Public
 */

require \WFP_Fundraising::plugin_dir() . 'payment-module/payment.php';

use \WfpFundraising\Apps\Settings as Settings;
use WfpFundraising\Model\Fundraise;
use WfpFundraising\Model\Fundraise_Meta;
use WfpFundraising\Utilities\Helper;

class Fundraising {

	const WFP_MK_FORM_DATA      = 'wfp_form_options_meta_data';
	const WFP_OK_PAYMENT_DATA   = 'wfp_payment_options_data';
	const WFP_OK_GENERAL_DATA   = 'wfp_general_options_data';
	const WFP_OK_REWARD_DATA    = 'wfp_reward_options_data';
	const WFP_OK_REWARD_PARTIAL = '_wfp_pledge_rwd__';

	// declare custom post type here
	const post_type = 'wp-fundraising';

	// donation table name
	const table_name = 'wdp_fundraising';

	// additional css
	public $additionalCss;
	/**
	 * Construct the Donate object
	 *
	 * @since 1.0.0
	 * @access public
	 */

	private static $instance;


	public function _init( $load = true ) {
		if ( $load ) {
			// action for create table

			// action custom post type
			add_action( 'init', array( $this, 'wfp_donate_all_forms' ) );

			// action init rest
			add_action( 'init', array( $this, 'wfp_init_rest_admin' ) );

			// add admin menu of settings
			add_action( 'admin_menu', array( $this, 'wfp_add_admin_menu_donate' ) );

			// Load css file for donations page for admin
			add_action( 'admin_enqueue_scripts', array( $this, 'wfp_donation_files_loader_admin' ) );

			// Add meta box function - Action
			add_action( 'add_meta_boxes', array( $this, 'wfp_meta_box_for_donate' ) );

			// Save meta box data function - Action
			add_action( 'save_post', array( $this, 'wfp_meta_box_data_save_for_donate' ), 1, 2 );

			// added custom column in cutom post type
			add_filter( 'manage_edit-' . self::post_type() . '_columns', array( $this, 'wfp_custom_column_add' ) );

			// modify content in reviwer list
			add_action(
				'manage_' . self::post_type() . '_posts_custom_column',
				array(
					$this,
					'wfp_custom_column_content_update',
				),
				10,
				2
			);

			// for elementor widgets scripts
			add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'elementor_js' ) );

			// add_action('wfp_single_goal_progress_before', [$this, 'wfp_hook_check']);
			// add_action('wfp_single_goal_progress_after', [$this, 'wfp_hook_check']);
			// add_filter('wfp_country_set', [$this, 'wfp_hook_check']);
			// add_filter('wfp_country_set_data', [$this, 'wfp_hook_checkdata']);
		}
	}


	// elementor js
	public function elementor_js() {
		wp_enqueue_script(
			'wp-fundrising-elementor',
			\WFP_Fundraising::plugin_url() . 'apps/elementor/assets/js/elementor.js',
			array(
				'jquery',
				'elementor-frontend',
			),
			\WFP_Fundraising::version(),
			true
		);
	}

	/*
	public function wfp_hook_check(){
		return 'GOL';
	}

	public function wfp_hook_checkdata(){
		return ['info' => ['name' => 'Test Country', 'phone_code' => '+09'], 'currency' => ['code' => 'FGH', 'symbol' => '?' ], 'states' => [ 'STA-1' => 'State 1'] ];
	}*/

	/**
	 * Custom Post type : static method
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public static function post_type() {
		return self::post_type;
	}


	/**
	 * Custom Post type : static method
	 *
	 * @since 1.0.0
	 * @access public
	 */
	private static function wfp_donate_table( $table = '' ) {
		global $wpdb;
		if ( strlen( $table ) > 0 ) {
			return $wpdb->prefix . $table;
		} else {
			return $wpdb->prefix . self::table_name;
		}
	}


	/**
	 * Donate wfp_donate_all_forms
	 * Method Description: Register donate post type
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function wfp_donate_all_forms() {

		register_post_type(
			self::post_type(),
			array(
				'labels'             => array(
					'name'          => esc_html__( 'FundEngine', 'wp-fundraising' ),
					'singular_name' => esc_html__( 'FundEngine', 'wp-fundraising' ),
					'all_items'     => esc_html__( 'All Campaigns', 'wp-fundraising' ),
					'add_new'       => esc_html__( 'Add Campaign', 'wp-fundraising' ),
					'add_new_item'  => esc_html__( 'Campaign Name', 'wp-fundraising' ),
					'edit_item'     => esc_html__( 'Edit Campaign', 'wp-fundraising' ),
					'view_item'     => esc_html__( 'View Campaign', 'wp-fundraising' ),
					'view_items'    => esc_html__( 'View Campaigns', 'wp-fundraising' ),
					'search_items'  => esc_html__( 'Search Campaign', 'wp-fundraising' ),

				),
				'supports'           => array( 'title', 'thumbnail', 'editor', 'excerpt' ),
				'public'             => true,
				'publicly_queryable' => true,
				'query_var'          => true,
				'has_archive'        => true,
				'rewrite'            => array( 'slug' => 'fundraising' ),
				// 'rewrite' => false,
				'menu_position'      => 108,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'menu_icon'          => \WFP_Fundraising::plugin_url() . 'assets/admin/images/menu-icon.png',
				'capability_type'    => 'post',
				'capabilities'       => array(
					// 'create_posts' => 'do_not_allow',
				),
				'map_meta_cap'       => true,
				// 'taxonomies'          => array( 'category', 'post_tag'),
			)
		);
		// added categoryies
		register_taxonomy(
			'wfp-categories',
			array( self::post_type() ),
			array(
				'hierarchical'      => true,
				'labels'            => array(
					'name'              => __( 'Categories', 'wp-fundraising' ),
					'singular_name'     => __( 'Categories', 'wp-fundraising' ),
					'search_items'      => __( 'Search Categories', 'wp-fundraising' ),
					'all_items'         => __( 'All Categories', 'wp-fundraising' ),
					'parent_item'       => __( 'Parent Categories', 'wp-fundraising' ),
					'parent_item_colon' => __( 'Parent Categories:', 'wp-fundraising' ),
					'edit_item'         => __( 'Edit Categories', 'wp-fundraising' ),
					'update_item'       => __( 'Update Categories', 'wp-fundraising' ),
					'add_new_item'      => __( 'Add New Categories', 'wp-fundraising' ),
					'new_item_name'     => __( 'New Categories', 'wp-fundraising' ),
					'menu_name'         => __( 'Categories', 'wp-fundraising' ),
				),
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'wfp-categories' ),
			)
		);

		// added tags
		// register_taxonomy_for_object_type( 'post_tag', self::post_type() );
		// added tags
		register_taxonomy(
			'wfp-tags',
			array( self::post_type() ),
			array(
				'hierarchical'      => false,
				'labels'            => array(
					'name'              => __( 'Tags', 'wp-fundraising' ),
					'singular_name'     => __( 'Tags', 'wp-fundraising' ),
					'search_items'      => __( 'Search Tags', 'wp-fundraising' ),
					'all_items'         => __( 'All Tags', 'wp-fundraising' ),
					'parent_item'       => __( 'Parent Tags', 'wp-fundraising' ),
					'parent_item_colon' => __( 'Parent Tags:', 'wp-fundraising' ),
					'edit_item'         => __( 'Edit Tags', 'wp-fundraising' ),
					'update_item'       => __( 'Update Tags', 'wp-fundraising' ),
					'add_new_item'      => __( 'Add New Tags', 'wp-fundraising' ),
					'new_item_name'     => __( 'New Tags', 'wp-fundraising' ),
					'menu_name'         => __( 'Tags', 'wp-fundraising' ),
				),
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'wfp-tags' ),
			)
		);
	}


	/**
	 * Donate wfp_add_admin_menu_donate
	 * Method Description: Added sub menu for add forms
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function wfp_add_admin_menu_donate() {
		add_submenu_page(
			'edit.php?post_type=' . self::post_type() . '',
			esc_html__( 'Reports', 'wp-fundraising' ),
			esc_html__( 'Reports', 'wp-fundraising' ),
			'manage_options',
			'report',
			array( $this, 'wfp_donate_reports' )
		);

		add_submenu_page(
			'edit.php?post_type=' . self::post_type() . '',
			esc_html__( 'Settings', 'wp-fundraising' ),
			esc_html__( 'Settings', 'wp-fundraising' ),
			'manage_options',
			'settings',
			array( $this, 'wfp_donate_settings' )
		);
	}


	/**
	 * Donate wfp_meta_box_for_donate.
	 * Method Description: Added meta box in editor.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function wfp_meta_box_for_donate() {
		global $post;
		if ( $post->post_type == self::post_type() ) :
			add_meta_box(
				'wp_fundraising_meta',
				esc_html__( 'Campaign Form', 'wp-fundraising' ),
				array( $this, 'wfp_meta_box_html_for_donate' ),
				self::post_type(),
				'normal',
				'high'
			);
		endif;
	}


	/**
	 * Donate wfp_meta_box_html_for_donate.
	 * Method Description: Metabox template view page
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function wfp_meta_box_html_for_donate() {
		global $post;

		if ( ! is_admin() ) {
			return $post->ID;
		}

		// get current post type
		$getPostTYpe = $post->post_type;

		Settings::check_setup( 'metabox-page' );

		// check post type with current post type.
		if ( $getPostTYpe == self::post_type() ) {
			// output for display settings. Get from options
			$metaKey      = self::WFP_MK_FORM_DATA;
			$metaDataJson = get_post_meta( $post->ID, $metaKey, false );
			$getMetaData  = json_decode( json_encode( end( $metaDataJson ), JSON_UNESCAPED_UNICODE ) );

			// get global setting data
			$metaGlobalKey       = 'wfp_global_options_data';
			$getGlobalOptionsGlo = get_option( $metaGlobalKey );
			$getGlobalOptions    = isset( $getGlobalOptionsGlo['options'] ) ? $getGlobalOptionsGlo['options'] : array();

			$globalDisplaySettingsKey = 'wfp_display_options_data';
			$globalDisplaySettings    = get_option( $globalDisplaySettingsKey );

			include \WFP_Fundraising::plugin_dir() . 'views/admin/fundraising/donation-meta-box.php';
		}
	}


	/**
	 * Donate wdp_meta_box_data_save.
	 * Method Description: Metabox save data in db
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function wfp_meta_box_data_save_for_donate( $post_id, $post ) {

		if ( ! is_admin() ) {
			return $post_id;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) || ! is_admin() ) {
			return $post_id;
		}

		global $wpdb;
		// check post id
		if ( ! empty( $post_id ) && is_object( $post ) ) {
			$getPostTYpe = $post->post_type;
			if ( $getPostTYpe == self::post_type() && ! empty( $_POST['meta-box-order-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['meta-box-order-nonce'] ) ), 'meta-box-order' ) ) {

				$metaDonateData = isset( $_POST['xs_submit_donation_data'] ) ? map_deep( wp_unslash( $_POST['xs_submit_donation_data'] ), 'sanitize_text_field' ) : array();

				if ( ! empty( $metaDonateData ) && is_array( $metaDonateData ) ) :

					// start end 1
					// multiple donation amount entry dimentions
					$multiValue = array();
					if ( isset( $metaDonateData['donation']['multi']['dimentions'] ) && sizeof( $metaDonateData['donation']['multi']['dimentions'] ) > 0 ) {
						$multiDataArray = $metaDonateData['donation']['multi']['dimentions'];
						foreach ( $multiDataArray as $valueMulti ) :
							$multiValue[] = (object) $valueMulti;
						endforeach;
					}

					$metaDonateData['donation']['multi']['dimentions'] = $multiValue;

					// additional filed dimension
					$multiAdditionalValue = array();

					/**
					 * Showing additional fields switch is turned off
					 * So we must insert mandatory fields for billing!
					 */
					if ( empty( $metaDonateData['form_content']['enable'] ) ) {

						$fld = Helper::get_wfp_mandatory_fields();

						foreach ( $fld as $item ) {
							$multiAdditionalValue[] = (object) $item;
						}
					} else {

						$fld = $metaDonateData['form_content']['additional']['dimentions'];

						if ( ! empty( $fld ) ) {
							foreach ( $fld as $item ) {
								$multiAdditionalValue[] = (object) $item;
							}
						}
					}

					$metaDonateData['form_content']['additional']['dimentions'] = $multiAdditionalValue;

					// pledge setup data insert
					$multiPledgeValue = array();
					if ( ! empty( $metaDonateData['pledge_setup']['multi']['dimentions'] ) ) {
						$multiPleDataArray = $metaDonateData['pledge_setup']['multi']['dimentions'];
						foreach ( $multiPleDataArray as $valueMulti ) :
							$valueMulti['id']   = Helper::get_uuid();
							$multiPledgeValue[] = (object) $valueMulti;
						endforeach;
					}

					$metaDonateData['pledge_setup']['multi']['dimentions'] = $multiPledgeValue;

					// form meta key
					$metaKey = self::WFP_MK_FORM_DATA;
					// meta post data modify. Save meta optins data
					update_post_meta( $post_id, $metaKey, $metaDonateData );

					// set new key type of form [donation or croudfounding]
					$metaKeyType = 'wfp_founding_form_format_type';
					$formatType  = isset( $metaDonateData['donation']['format'] ) ? $metaDonateData['donation']['format'] : 'donation';

					update_post_meta( $post_id, $metaKeyType, $formatType );

					// set user current post user update user
					$metaKeyUserUpdate = 'wfp_founding_form_update_user';
					$metaUserJson      = get_post_meta( $post_id, $metaKeyUserUpdate, false );
					$metaUserJson      = array(
						'date' => time(),
						'user' => get_current_user_id(),
					);
					update_post_meta( $post_id, $metaKeyUserUpdate, $metaUserJson );

					update_post_meta( $post_id, '__wfp_campaign_status', 'Publish' );

					if ( did_action( \WfpFundraising\Apps\Key::FUNDRAISING_PRO_LOADED ) ) {

						$account_preference = empty( $metaDonateData['pp_selected'] ) ? array() : $metaDonateData['pp_selected'];

						update_post_meta( $post_id, \WP_Fundraising_Pro\Keys::OK_PERSONAL_PAYMENT_PREFERENCE, $account_preference );
					}

					/**
					 * For woocommerce payment method
					 * so it does not add shipping cost to it
					 */
					update_post_meta( $post_id, '_virtual', 'yes' );

				endif; // end if 1;
			}
		}
	}


	/**
	 * Donate wfp_custom_column_add.
	 * Method Description: Custom post column modify.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function wfp_custom_column_add( $columns ) {
		include \WFP_Fundraising::plugin_dir() . 'country-module/country-info.php';
		/*currency information*/
		$metaGeneralKey   = 'wfp_general_options_data';
		$getMetaGeneralOp = get_option( $metaGeneralKey );
		$getMetaGeneral   = isset( $getMetaGeneralOp['options'] ) ? $getMetaGeneralOp['options'] : array();

		$defaultCurrencyInfo = isset( $getMetaGeneral['currency']['name'] ) ? $getMetaGeneral['currency']['name'] : 'US-USD';
		$explCurr            = explode( '-', $defaultCurrencyInfo );
		$currCode            = isset( $explCurr[1] ) ? $explCurr[1] : 'USD';

		$symbols = isset( $countryList[ $currCode ]['currency']['symbol'] ) ? $countryList[ $currCode ]['currency']['symbol'] : '';
		$symbols = strlen( $symbols ) > 0 ? $symbols : $currCode;

		$columns = array(
			'cb'        => '<input type="checkbox" />',
			'title'     => esc_html__( 'Name', 'wp-fundraising' ),
			'amount'    => esc_html__( 'Amount (' . $symbols . ')', 'wp-fundraising' ),
			'goal_info' => esc_html__( 'Goal', 'wp-fundraising' ),
			'raised'    => esc_html__( 'Raised Amount (' . $symbols . ')', 'wp-fundraising' ),
			'settings'  => esc_html__( 'Settings', 'wp-fundraising' ),
			'author'    => esc_html__( 'Author', 'wp-fundraising' ),
			'date'      => esc_html__( 'Donate Date', 'wp-fundraising' ),
		);

		return $columns;
	}


	/**
	 * Donate wfp_custom_column_content_update.
	 * Method Description: Custom post column contnt modify.
	 *
	 * @params $column - custon column name
	 * @params $post_id - get post id
	 * @since 1.0.0
	 * @access public
	 */
	public function wfp_custom_column_content_update( $column, $post_id ) {
		$author_id = get_post_field( 'post_author', $post_id );

		$current_id = get_current_user_id();
		$user       = get_userdata( $current_id );
		$user_roles = $user->roles;

		if ( empty( $post_id ) ) {
			return '';
		}

		if ( $author_id != $current_id && ! in_array( $user_roles, array( 'administrator' ) ) ) {
			// return false;
		}

		// post meta
		$metaKey      = self::WFP_MK_FORM_DATA;
		$metaDataJson = get_post_meta( $post_id, $metaKey, false );
		$getMetaData  = json_decode( json_encode( end( $metaDataJson ), JSON_UNESCAPED_UNICODE ) );

		global $wpdb;

		switch ( $column ) :
			case 'amount':
				$donation_type = isset( $getMetaData->donation->type ) ? $getMetaData->donation->type : 'multi-lebel';
				$amount_range  = '';
				if ( $donation_type == 'multi-lebel' ) {
					$priceLevel = isset( $getMetaData->donation->multi->dimentions ) ? $getMetaData->donation->multi->dimentions : '';
					if ( is_array( $priceLevel ) ) {
						$amount_range .= \WfpFundraising\Apps\Settings::wfp_number_format_currency( (float) current( $priceLevel )->price ) . ' - ';
						$amount_range .= \WfpFundraising\Apps\Settings::wfp_number_format_currency( (float) end( $priceLevel )->price );
					} else {
						$amount_range = '0';
					}
				} else {
					$amount_range = isset( $getMetaData->donation->fixed->price ) ? \WfpFundraising\Apps\Settings::wfp_number_format_currency( (float) $getMetaData->donation->fixed->price ) : 0;
				}
				echo esc_html( $amount_range );
				break;

			case 'goal_info':
				if ( isset( $getMetaData->goal_setup->enable ) ) {
					$goal_type       = isset( $getMetaData->goal_setup->goal_type ) ? $getMetaData->goal_setup->goal_type : 'goal_terget_amount';
					$totalGoalAMount = $wpdb->get_var( $wpdb->prepare( "SELECT SUM(donate_amount) FROM {$wpdb->prefix}wdp_fundraising WHERE form_id = %d AND status = 'Active' AND payment_gateway NOT IN ('test_payment')", $post_id ) );

					if ( $goal_type == 'terget_goal' ) {
						$targetValueGoal = isset( $getMetaData->goal_setup->terget->terget_goal->amount ) ? $getMetaData->goal_setup->terget->terget_goal->amount : 0;
					} elseif ( $goal_type == 'terget_goal_date' ) {
						$targetValueGoal = isset( $getMetaData->goal_setup->terget->terget_goal_date->amount ) ? $getMetaData->goal_setup->terget->terget_goal_date->amount : 0;
					} elseif ( $goal_type == 'campaign_never' ) {
						$targetValueGoal = isset( $getMetaData->goal_setup->terget->campaign_never->amount ) ? $getMetaData->goal_setup->terget->campaign_never->amount : 0;
					} else {
						$totalGoalAMount = 0;
						$targetValueGoal = 0;
					}

					if ( $getMetaData->goal_setup->bar_display_sty == 'percentage' ) {
						$goalDataAmount = 0;
						if ( $targetValueGoal > 0 ) {
							$goalDataAmount = ( $totalGoalAMount * 100 ) / $targetValueGoal;
							if ( $goalDataAmount >= 100 ) {
								$goalDataAmount = 100;
							}
						}
						echo intval( $goalDataAmount ) . '%';
					} else {
						$g_info = '<strong>' . \WfpFundraising\Apps\Settings::wfp_number_format_currency( $totalGoalAMount ) . '</strong> of <strong>' . \WfpFundraising\Apps\Settings::wfp_number_format_currency( $targetValueGoal ) . '</strong> raised';
						echo wp_kses( $g_info, \WfpFundraising\Utilities\Utils::get_kses_array() );
					}
				} else {
					echo 'No';
				}
				break;

			case 'raised':
				$raised_amount = $wpdb->get_var( $wpdb->prepare( "SELECT SUM(donate_amount) FROM {$wpdb->prefix}wdp_fundraising WHERE form_id = %d AND status = 'Active' AND payment_gateway NOT IN ('test_payment')", $post_id ) );
				echo esc_html( \WfpFundraising\Apps\Settings::wfp_number_format_currency( $raised_amount ) );
				break;

			case 'author':
				break;

			case 'settings':
				$parentUrl = get_edit_post_link( isset( $post_id ) ? $post_id : 0 );
				echo '<a href="' . esc_attr( $parentUrl ) . '#form_donate_form_settings" target="_blank"> Short-code </a>';
				break;

		endswitch;
	}


	/**
	 * Donate wfp_donate_reports.
	 * Method Description: Donate reports
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function wfp_donate_reports() {
		// reports
		// get options for setup plugin
		\WfpFundraising\Apps\Settings::check_setup( 'reports' );

		require_once \WFP_Fundraising::plugin_dir() . 'views/admin/fundraising/reports/donation-reports.php';
	}


	/**
	 * Donate wfp_donate_settings.
	 * Method Description: Metabox settings options
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function wfp_donate_settings() {

		// page setup true for all conditions.
		if ( \WfpFundraising\Apps\Settings::check_setup_page() ) {
			require_once \WFP_Fundraising::plugin_dir() . 'views/welcome/welcome.php';
			wp_die();
		}

		$arrayPayment = xs_payment_services();
		$current_user = wp_get_current_user();
		$pp_gate_ways = array();

		// payment setup data
		$metaSetupKey = 'wfp_setup_services_data';
		$getSetUpData = get_option( $metaSetupKey );

		$message_status = 'no';

		// check install woocommerce or active plugin
		$checkActive = \WfpFundraising\Apps\Settings::missing_woocommerce();
		if ( $checkActive['status'] ) {
			$message_status = 'show';
			$message_text   = '<a href="' . $checkActive['url'] . '"> ' . $checkActive['label'] . ' </a>';
		}

		$metaKey = 'wfp_payment_options_data';

		if (
			isset( $_POST['submit_donate_settings_gateways'] ) &&
			isset( $_POST['wpf_settings_nonce'] ) &&
			wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wpf_settings_nonce'] ) ), 'wpf_save_settings' )
		) {

			$post_options = isset( $_POST['xs_submit_settings_data'] ) ? map_deep( wp_unslash( $_POST['xs_submit_settings_data'] ), 'sanitize_text_field' ) : array();

			if ( isset( $post_options['gateways']['services'] ) && sizeof( $post_options['gateways']['services'] ) > 0 ) {
				$multiDataArray = $post_options['gateways']['services'];
				foreach ( $multiDataArray as $keyDatt => $valueMulti ) :
					if ( isset( $valueMulti['setup']['account_details'] ) ) {
						$multiValue = array();
						$arrayData  = $valueMulti['setup']['account_details'];
						foreach ( $arrayData as $dynaicValue ) {
							$multiValue[] = $dynaicValue;
						}
						$post_options['gateways']['services'][ $keyDatt ]['setup']['account_details'] = $multiValue;

					}
				endforeach;

			}

			if ( update_option( $metaKey, $post_options, 'Yes' ) ) {
				$message_status = 'show';
				$message_text   = 'Successfully set payment gateways.';
			}
		}

		if ( did_action( \WfpFundraising\Apps\Key::FUNDRAISING_PRO_LOADED ) ) {

			/**
			 * Personal account data
			 */
			if ( isset( $_POST['submit_pp_settings_gateways'] ) ) {

				$pp_data = ! empty( $_POST['wpfp_pp_settings_data'] ) ? map_deep( wp_unslash( $_POST['wpfp_pp_settings_data'] ), 'sanitize_text_field' ) : array();

				if ( ! empty( $pp_data['gateways']['services'] ) ) {

					if ( update_user_meta( $current_user->ID, \WP_Fundraising_Pro\Keys::MK_PP_GATEWAY_SETTINGS, $pp_data ) ) {
						$message_status = 'show';
						$message_text   = 'Personal payment gateways account has been successfully set.';
					}
				}
			}

			$pp_gate_ways = get_user_meta( $current_user->ID, \WP_Fundraising_Pro\Keys::MK_PP_GATEWAY_SETTINGS, true );
		}

		$getMetaData  = get_option( $metaKey );
		$gateWaysData = isset( $getMetaData['gateways'] ) ? $getMetaData['gateways'] : array();
		// end payment system

		// start share options
		$share_media = \WfpFundraising\Apps\Settings::share_options();

		$metaShareKey = 'wfp_share_media_options';
		if ( isset( $_POST['submit_donate_settings_share'] ) ) {
			$post_options = isset( $_POST['xs_submit_settings_data_share'] ) ? map_deep( wp_unslash( $_POST['xs_submit_settings_data_share'] ), 'sanitize_text_field' ) : array();

			if ( update_option( $metaShareKey, $post_options, 'Yes' ) ) {
				$message_status = 'show';
				$message_text   = 'Successfully enable social share options.';
			}
		}
		$getMetaShare = get_option( $metaShareKey );
		if ( isset( $getMetaShare['media'] ) ) {
			$getMetaShare = $getMetaShare['media'];
		}

		/*
		* Global Options
		*/
		$global_options = \WfpFundraising\Apps\Settings::global_options();

		$metaGlobalKey = 'wfp_global_options_data';
		if ( isset( $_POST['submit_donate_global_setting'] ) ) {
			$post_options = isset( $_POST['xs_submit_settings_data_global'] ) ? map_deep( wp_unslash( $_POST['xs_submit_settings_data_global'] ), 'sanitize_text_field' ) : array();

			// global options data
			if ( update_option( $metaGlobalKey, $post_options, 'Yes' ) ) {
				$message_status = 'show';
				$message_text   = 'Successfully set global options.';
			}
		}
		$getMetaGlobalOp = get_option( $metaGlobalKey );
		$getMetaGlobal   = isset( $getMetaGlobalOp['options'] ) ? $getMetaGlobalOp['options'] : array();
		// print_r($getMetaGlobal);

		/**
		 * General Setting
		 * and page option uses same key but different form
		 * so always merge it!
		 * Important otherwise leads to a bug which is difficult to hunt down
		 */
		$metaGeneralKey   = 'wfp_general_options_data';
		$getMetaGeneralOp = get_option( $metaGeneralKey );

		if ( isset( $_POST['submit_donate_general_setting'] ) ) {

			$post_options = isset( $_POST['xs_submit_settings_data_general'] ) ? map_deep( wp_unslash( $_POST['xs_submit_settings_data_general'] ), 'sanitize_text_field' ) : array();

			foreach ( $post_options['options'] as $op_key => $nv ) {

				$getMetaGeneralOp['options'][ $op_key ] = $nv;
			}

			// global options data
			if ( update_option( $metaGeneralKey, $getMetaGeneralOp, true ) ) {
				$message_status = 'show';
				$message_text   = 'Successfully set general options.';
			}
		}

		/**
		 * Display Setting
		 */
		$metaDisplayKey = 'wfp_display_options_data';
		if ( isset( $_POST['submit_donate_display_setting'] ) ) {

			$post_options = isset( $_POST['xs_submit_donation_data'] ) ? map_deep( wp_unslash( $_POST['xs_submit_donation_data'] ), 'sanitize_text_field' ) : array();

			if ( update_option( $metaDisplayKey, $post_options, 'Yes' ) ) {
				$message_status = 'show';
				$message_text   = 'Successfully set diaplay options.';
			}
		}

		$getMetaDisplayOp = get_option( $metaDisplayKey );
		$formGoalData     = isset( $getMetaDisplayOp['goal_setup'] ) ? $getMetaDisplayOp['goal_setup'] : array(
			'goal_type'       => 'terget_goal',
			'bar_style'       => 'line_bar',
			'backers'         => 'Yes',
			'bar_display_sty' => 'amount_show',
		);

		$formSettingData = isset( $getMetaDisplayOp['form_settings'] ) ? $getMetaDisplayOp['form_settings'] : array();

		/**
		 * Page Setting
		 */
		if ( isset( $_POST['submit_donate_page_setting'] ) ) {
			$post_options = isset( $_POST['xs_submit_settings_data_general'] ) ? map_deep( wp_unslash( $_POST['xs_submit_settings_data_general'] ), 'sanitize_text_field' ) : array();

			$args = array(
				'post_type'      => 'page',
				'posts_per_page' => -1,
				'post__in'       => array(),
			);

			$pages = array();
			$slugs = array();

			if ( ! empty( $post_options['options']['pages'] ) ) {

				foreach ( $post_options['options']['pages'] as $pg => $id ) {

					$args['post__in'][] = $id - 0;

					$pages[ $pg ] = $id;
					$slugs[ $id ] = '';
				}

				$posts = get_posts( $args );

				foreach ( $posts as $p ) {
					$slugs[ $p->ID ] = $p->post_name;
				}

				$post_options['options']['pages'] = $pages;
				$post_options['options']['slugs'] = $slugs;
			}

			$getMetaGeneralOp['options']['pages'] = $post_options['options']['pages'];
			$getMetaGeneralOp['options']['slugs'] = $post_options['options']['slugs'];

			if ( update_option( $metaGeneralKey, $getMetaGeneralOp, true ) ) {
				$message_status = 'show';
				$message_text   = 'Successfully set page options.';
			}
		}

		$getMetaGeneral = isset( $getMetaGeneralOp['options'] ) ? $getMetaGeneralOp['options'] : array();

		/**
		 * General Setting
		 */
		$metaTermsKey = 'wfp_etrms_condition_options_data';
		if ( isset( $_POST['submit_donate_terms_setting'] ) ) {
			$post_options = isset( $_POST['xs_submit_terms_condition_data'] ) ? map_deep( wp_unslash( $_POST['xs_submit_terms_condition_data'] ), 'sanitize_text_field' ) : array();
			// terms options data
			if ( update_option( $metaTermsKey, $post_options, 'Yes' ) ) {
				$message_status = 'show';
				$message_text   = 'Successfully set terms options.';
			}
		}
		$getMetaTermsOp = get_option( $metaTermsKey );
		$getMetaTerms   = isset( $getMetaTermsOp['form_terma'] ) ? json_decode( json_encode( $getMetaTermsOp['form_terma'], JSON_UNESCAPED_UNICODE ) ) : (object) array();

		$setupData = isset( $getSetUpData['services'] ) ? $getSetUpData['services'] : array();

		/**
		 * Pro plugin loaded
		 */
		if ( did_action( 'wpfp/fundraising_pro/plugin_loaded' ) ) {

			/**
			 * Auth settings saving for pro
			 */
			$ok_auth = \WP_Fundraising_Pro\Keys::OK_AUTH_SETTINGS;

			if ( isset( $_POST['submit_auth_settings_btn'] ) ) {

				$post_options = isset( $_POST['wpfd_submit_auth_data'] ) ? map_deep( wp_unslash( $_POST['wpfd_submit_auth_data'] ), 'sanitize_text_field' ) : array();

				$post_options['notify_who']             = empty( $post_options['notify_who'] ) ? 'both' : $post_options['notify_who'];
				$post_options['email_verify']           = empty( $post_options['email_verify'] ) ? 'no' : $post_options['email_verify'];
				$post_options['email_donation_donor']   = empty( $post_options['email_donation_donor'] ) ? 'no' : $post_options['email_donation_donor'];
				$post_options['email_donation_author']  = empty( $post_options['email_donation_author'] ) ? 'no' : $post_options['email_donation_author'];
				$post_options['pending_campaign_admin'] = empty( $post_options['pending_campaign_admin'] ) ? 'no' : $post_options['pending_campaign_admin'];

				if ( update_option( $ok_auth, $post_options, true ) ) {
					$message_status = 'show';
					$message_text   = 'Successfully set auth options.';
				}
			}

			$auth_settings_option = get_option( $ok_auth );
		}

		require_once \WFP_Fundraising::plugin_dir() . 'views/admin/fundraising/settings/donation-settings.php';
	}


	/**
	 * This function will be added in pro versino
	 *
	 * @since 1.0.0
	 */
	public function wpfdp_advance_management() {}

	/**
	 * Donate wdp_donation_css_loader .
	 * Method Description: Settings Css Loader
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function wfp_donation_files_loader_admin() {
		// repeater
		wp_enqueue_script( 'wfp_repeater_script' );
		// sortable
		wp_enqueue_script( 'jquery-ui-sortable' );

		// admin settings css
		wp_enqueue_style( 'wfp_settings_admin_css' );

		// donation css
		wp_enqueue_style( 'wfp_donation_admin_css' );

		// fonts
		wp_enqueue_style( 'wfp_fonts' );

		// donation script
		wp_enqueue_script( 'wfp_donation_admin_script' );
		wp_localize_script(
			'wfp_donation_admin_script',
			'xs_donate_url',
			array(
				'siteurl' => get_option( 'siteurl' ),
				'nonce'   => wp_create_nonce( 'wp_rest' ),
				'resturl' => get_rest_url(),
			)
		);

		// payment script
		wp_enqueue_script( 'wfp_payment_script' );
		// payment css
		wp_enqueue_style( 'wfp_payment_css' );

		// main script
		wp_enqueue_script( 'wfp_admin_main_script' );

		// modal script
		wp_enqueue_script( 'wfp_payment_script_modal' );

		// welcome css
		wp_enqueue_style( 'wfp_welcome_style_css' );
		// welcome script
		wp_enqueue_script( 'wfp_welcome_style_script' );
		wp_localize_script(
			'wfp_welcome_style_script',
			'xs_donate_url',
			array(
				'siteurl' => get_option( 'siteurl' ),
				'resturl' => get_rest_url(),
			)
		);
	}

	/**
	 * Donate wfp_init_rest_admin .
	 * Method Description: load rest api admin
	 *
	 * @since 1.0.0
	 * @access for public
	 */
	public function wfp_init_rest_admin() {

		add_action(
			'rest_api_init',
			function() {
				register_rest_route(
					'xs-donate-form',
					'/donate-active/(?P<donateid>\w+)/',
					array(
						'methods'             => 'GET',
						'callback'            => array( $this, 'wfp_action_rest_donate_status_active' ),
						'permission_callback' => '__return_true',

					)
				);

				register_rest_route(
					'xs-donate-form',
					'/update_status/(?P<idd>\d+)/',
					array(
						'methods'             => 'GET',
						'callback'            => array( $this, 'update_donation_status' ),
						'permission_callback' => '__return_true',

					)
				);

				register_rest_route(
					'xs-donate-form',
					'/payment-type-modify/(?P<donateid>\w+)/',
					array(
						'methods'             => 'GET',
						'callback'            => array( $this, 'wfp_payment_type_modify' ),
						'permission_callback' => '__return_true',
					)
				);
			}
		);
	}

	/**
	 * Donate wfp_action_rest_donate_status_active() .
	 * Method Description: Action donate form modify donate status when select this dropdown.
	 *
	 * @since 1.0.0
	 * @access for public
	 */
	public function wfp_action_rest_donate_status_active( \WP_REST_Request $request ) {

		if( !current_user_can( 'manage_options' ) ){
			
			return array(
				'success' => false,
				'error'   => 'Access Denied',
			);
		}
		
		global $wpdb;
		$return    = array(
			'success' => array(),
			'error'   => array(),
		);
		$donateid  = isset( $request['donateid'] ) ? $request['donateid'] : 0;
		$donateVal = isset( $request['status'] ) ? $request['status'] : '';

		if ( $donateVal == 0 ) {
			$donateValue = 'Pending';
			$donateFront = 'pending';
		} elseif ( $donateVal == 1 ) {
			$donateValue = 'Review';
			$donateFront = 'review';
		} elseif ( $donateVal == 2 ) {
			$donateValue = 'Active';
			$donateFront = 'active';
		} elseif ( $donateVal == 3 ) {
			$donateValue = 'DeActive';
			$donateFront = 'refund';
		} elseif ( $donateVal == 4 ) {
			$donateValue = 'Refunded';
			$donateFront = 'refund';
		}

		$tableName = self::wfp_donate_table( '' );

		if ( $donateid > 0 && in_array( $donateValue, array( 'Pending', 'Review', 'Active', 'DeActive', 'Refunded' ) ) ) {
			if ( $wpdb->update( $tableName, array( 'status' => $donateValue ), array( 'donate_id' => $donateid ) ) ) {

				$return['success'] = $donateFront;
			} else {
				$return['error'] = esc_html__( 'Unsuccess', 'wp-fundraising' );
			}
		}

		return $return;
	}


	public function update_donation_status( \WP_REST_Request $request ) {
		
		if( !current_user_can( 'manage_options' ) ){
			
			return array(
				'success' => false,
				'error'   => 'Access Denied',
			);
		}

		global $wpdb;

		$data = $request->get_params();

		$idd    = intval( $data['idd'] );
		$status = $data['status'];

		$tableName = self::wfp_donate_table( '' );

		$return['error'] = esc_html__( 'Failed to validate', 'wp-fundraising' );

		if ( $idd > 0 && in_array( $status, Global_Settings::$all_donation_status ) ) {

			$return['error'] = esc_html__( 'Failed', 'wp-fundraising' );

			if ( $wpdb->update( $tableName, array( 'status' => $status ), array( 'donate_id' => $idd ) ) ) {

				$return['success'] = strtolower( $status );
			}
		}

		return $return;
	}


	public function wfp_payment_type_modify( \WP_REST_Request $request ) {

		if( !current_user_can( 'manage_options' ) ){
			
			return array(
				'success' => false,
				'error'   => 'Access Denied',
			);
		}

		/*payment type settings*/
		$return   = array(
			'success' => array(),
			'error'   => array(),
		);
		$donateid = isset( $request['donateid'] ) ? $request['donateid'] : 'default';
		if ( strlen( $donateid ) > 0 ) {
			$metaSetupKey = 'wfp_setup_services_data';
			$getSetUpData = get_option( $metaSetupKey );

			$getSetUpData['services']['payment'] = $donateid;
			$setupData                           = \WfpFundraising\Apps\Settings::sanitize( $getSetUpData );
			if ( update_option( $metaSetupKey, $setupData, false ) ) {
				$return['success'] = array( 'Successfully' );
			}
		}

		return $return;
	}

	/**
	 * Donate wfp_action_create_table_donation() .
	 * Method Description: Action donate form submit when click this donate button.
	 *
	 * @since 1.0.0
	 * @access for public
	 */
	public function wfp_action_create_table_donation() {

		global $wpdb;

		// create table for donation
		if ( $wpdb->query( "SHOW TABLES LIKE '{$wpdb->prefix}wdp_fundraising'" ) == false ) {

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';

			// create fundraising table
			$wdp_sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}wdp_fundraising (
				`donate_id` mediumint(9) NOT NULL AUTO_INCREMENT,
				`form_id` bigint(20) NOT NULL COMMENT 'This id From wp post table',
				`invoice` varchar(150) NOT NULL UNIQUE KEY,
				`donate_amount` double NOT NULL DEFAULT '0',
				`user_id` mediumint(9) NOT NULL,
				`email` varchar(200) NOT NULL,
				`fundraising_type` ENUM('donation', 'crowdfunding') DEFAULT 'donation',
				`payment_type` ENUM('default', 'woocommerce') DEFAULT 'default',
				`pledge_id` varchar(20) NOT NULL DEFAULT '0',
				`payment_gateway` ENUM('offline_payment', 'online_payment', 'bank_payment', 'check_payment', 'stripe_payment', 'other_payment') default 'online_payment',
				`date_time` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				`status` ENUM('Active', 'Pending', 'Review', 'Refunded', 'DeActive', 'Delete') DEFAULT 'Pending',
				PRIMARY KEY (`donate_id`)
			  ) {$wpdb->get_charset_collate()}";

			dbDelta( $wdp_sql );

			$wdp_meta = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}wdp_fundraising_meta (
					`meta_id` mediumint NOT NULL AUTO_INCREMENT,
					`donate_id` mediumint NOT NULL,
					`meta_key` varchar(255),
					`meta_value` longtext,
					PRIMARY KEY(`meta_id`)
				) {$wpdb->get_charset_collate()}";

			dbDelta( $wdp_meta );
		}
	}

	public function wfp_update_meta( $post_id = 0, $meta_key = '', $meta_value = '', $unique = true ) {
		return \WfpFundraising\Apps\Settings::wfp_update_metadata( $post_id, $meta_key, $meta_value, $unique );
	}


	public function wfp_get_meta( $post_id = 0, $meta_key = '' ) {
		return \WfpFundraising\Apps\Settings::wfp_get_metadata( $post_id, $meta_key );
	}

	/**
	 * Review wfp_ratting_view_star_point . for star style of content view
	 * Method Description: this method use for ratting view in admin page
	 *
	 * @params $rat, get ratting value
	 * @params $max, limit score data
	 * @return ratting html data
	 * @since 1.0.0
	 * @access public
	 */
	public function wfp_ratting_view_star_point( $rat = 0, $max = 5 ) {
		$tarring  = '';
		$tarring .= '<ul class="xs_review_stars">';
		$halF     = 0;
		for ( $ratting = 1; $ratting <= $max; $ratting++ ) :
			$rattingClass = 'dashicons-star-empty';
			if ( $halF == 1 ) {
				$rattingClass = 'dashicons-star-half';
				$halF         = 0;
			}
			if ( $ratting <= $rat ) {
				$rattingClass = 'dashicons-star-filled';
				if ( $ratting == floor( $rat ) ) :
					$expLode = explode( '.', $rat );
					if ( is_array( $expLode ) && sizeof( $expLode ) > 1 ) {
						$halF = 1;
					}

				endif;
			}

			$tarring .= '<li class="star-li star selected"> <i class="xs-star dashicons-before ' . esc_html( $rattingClass ) . '" aria-hidden="true"></i> </li>';
		endfor;
		$tarring .= '</ul>';

		return $tarring;
	}


	public static function instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}


