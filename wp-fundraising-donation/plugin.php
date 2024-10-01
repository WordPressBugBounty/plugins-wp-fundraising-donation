<?php

namespace WfpFundraising;

use WfpFundraising\Apps\Donation_Cpt;
use WfpFundraising\Apps\Fundraising_Cpt;
use WfpFundraising\Apps\Key;
use WfpFundraising\Core\Donation_Report;


/**
 * Class Plugin
 *
 * @package WfpFundraising
 */
final class Plugin {

	private static $instance;

	private $base_location;
	private $base_directory;


	public function __construct( $loc ) {

		$this->base_location = plugin_basename( $loc );

		$this->base_directory = dirname( $this->base_location );
	}


	/**
	 * Singleton design pattern
	 *
	 * @since 1.1.20
	 *
	 * @param $base
	 *
	 * @return Plugin
	 */
	public static function instance( $base ) {

		if ( ! self::$instance ) {
			self::$instance = new self( $base );
		}

		return self::$instance;
	}


	public function plugin_url() {
		return trailingslashit( plugin_dir_url( __FILE__ ) );
	}


	public function plugin_dir() {
		return trailingslashit( plugin_dir_path( __FILE__ ) );
	}


	public function views_dir() {
		return $this->plugin_dir() . 'views/';
	}


	public function init() {

		add_filter( 'the_content', array( $this, 'wfp_content_replace_for_invoice_page' ) );

		add_filter( 'plugin_action_links_' . $this->base_location, array( $this, 'wfp_action_links' ) );

		add_filter( 'post_row_actions', array( $this, 'add_donations_link' ), 10, 2 );

		/**
		 * This will hold every info related to individual
		 */
		Donation_Cpt::instance()->init();

		Donation_Report::instance()->init();

		// wpmet_plugins_page_implementation

		$this->wpmet_plugins_page();
	}


	public function wfp_action_links( $links ) {
		$links[] = '<a href="' . admin_url( 'edit.php?post_type=wp-fundraising&page=settings' ) . '"> ' . __( 'Settings', 'wp-fundraising' ) . '</a>';
		$links[] = '<a href="' . admin_url( 'post-new.php?post_type=wp-fundraising' ) . '" target="_blank">' . __( 'Add', 'wp-fundraising' ) . '</a>';

		return $links;
	}


	public function wfp_content_replace_for_invoice_page( $content ) {

		$slug      = Key::SLUG_INVOICE_PAGE;
		$curr_slug = get_post_field( 'post_name' );

		if ( $slug == $curr_slug ) {

			ob_start();

			include $this->views_dir() . 'admin/view-invoice.php';

			$content = ob_get_contents();

			ob_end_clean();
		}

		return $content;
	}

	public function add_donations_link( $actions, $post ) {

		if ( $post->post_type == Fundraising_Cpt::TYPE ) {

			$url = admin_url( 'edit.php?post_type=' . Fundraising_Cpt::TYPE . '&page=donations&donation_id=' . $post->ID );

			$trash = $actions['trash'];

			unset( $actions['trash'] );

			$actions['wfp_donations'] = '<a href="' . $url . '" title="check all donations" target="_blank" >' . esc_html__( 'Donations', 'wp-fundraising' ) . '</a>';
			$actions['trash']         = $trash;

		}

		return $actions;
	}

	public function wpmet_plugins_page () {

		$apps_img_path = \WFP_Fundraising::plugin_url(). 'assets/admin/images/apps-page/';

		/**
         * Show our plugins menu for others wpmet plugins
        */
		\WFP_Fundraising\Wpmet\Libs\Plugins::instance()->init('wp-fundraising')
        ->set_parent_menu_slug('edit.php?post_type=wp-fundraising')
        ->set_submenu_name('Our Plugins')
        ->set_section_title('Want to Take Your Fundraising to the Next Level?')
        ->set_section_description('Install other plugins from us and unleash the full potential of your fundraising website!')
        ->set_items_per_row(4)
        ->set_plugins(
			[
				'elementskit-lite/elementskit-lite.php' => [
					'name' => esc_html__('ElementsKit', 'wp-fundraising'),
					'url'  => 'https://wordpress.org/plugins/elementskit-lite/',
					'icon' => $apps_img_path. 'elementskit.gif',
					'desc' => esc_html__('All-in-one Elementor addon trusted by 1 Million+ users, makes your website builder process easier with ultimate freedom.', 'wp-fundraising'),
					'docs' => 'https://wpmet.com/doc/elementskit/',
				],
				'getgenie/getgenie.php' => [
					'name' => esc_html__('GetGenie', 'wp-fundraising'),
					'url'  => 'https://wordpress.org/plugins/getgenie/',
					'icon' => $apps_img_path.'getgenie.gif',
					'desc' => esc_html__('Your personal AI assistant for content and SEO. Write content that ranks on Google with NLP keywords and SERP analysis data.', 'wp-fundraising'),
					'docs' => 'https://getgenie.ai/docs/',
				],
				'gutenkit-blocks-addon/gutenkit-blocks-addon.php' => [
					'name' => esc_html__('GutenKit', 'wp-fundraising'),
					'url'  => 'https://wordpress.org/plugins/gutenkit-blocks-addon/',
					'icon' => 'https://ps.w.org/gutenkit-blocks-addon/assets/icon-128x128.png?rev=3044956',
					'desc' => esc_html__('Gutenberg blocks, patterns, and templates that extend the page-building experience using the WordPress block editor.', 'wp-fundraising'),
					'docs' => 'https://wpmet.com/doc/gutenkit/',
				],
				'shopengine/shopengine.php' => [
					'name' => esc_html__('Shopengine', 'wp-fundraising'),
					'url'  => 'https://wordpress.org/plugins/shopengine/',
					'icon' => $apps_img_path. 'shopengine.gif',
					'desc' => esc_html__('Complete WooCommerce solution for Elementor to fully customize any pages including cart, checkout, shop page, and so on.', 'wp-fundraising'),
					'docs' => 'https://wpmet.com/doc/shopengine/',
				],
				'metform/metform.php' => [
					'name' => esc_html__('MetForm', 'wp-fundraising'),
					'url'  => 'https://wordpress.org/plugins/metform/',
					'icon' => $apps_img_path. 'metform.png',
					'desc' => esc_html__('Drag & drop form builder for Elementor to create contact forms, multi-step forms, and more — smoother, faster, and better!', 'wp-fundraising'),
					'docs' => 'https://wpmet.com/doc/metform/',
				],
				'emailkit/EmailKit.php' => [
					'name' => esc_html__('EmailKit', 'wp-fundraising'),
					'url'  => 'https://wordpress.org/plugins/emailkit/',
					'icon' => $apps_img_path . 'emailkit.png',
					'desc' => esc_html__('Advanced email customizer for WooCommerce and WordPress. Build, customize, and send emails from WordPress to boost your sales!', 'wp-fundraising'),
					'docs' => 'https://wpmet.com/doc/emailkit/',
				],
				'wp-social/wp-social.php' => [
					'name' => esc_html__('WP Social', 'wp-fundraising'),
					'url'  => 'https://wordpress.org/plugins/wp-social/',
					'icon' => $apps_img_path . 'wp-social.png',
					'desc' => esc_html__('Add social share, login, and engagement counter — unified solution for all social media with tons of different styles for your website.', 'wp-fundraising'),
					'docs' => 'https://wpmet.com/doc/wp-social/',
				],
				'wp-ultimate-review/wp-ultimate-review.php' => [
					'name' => esc_html__('WP Ultimate Review', 'wp-fundraising'),
					'url'  => 'https://wordpress.org/plugins/wp-ultimate-review/',
					'icon' => $apps_img_path . 'ultimate-review.png',
					'desc' => esc_html__('Collect and showcase reviews on your website to build brand credibility and social proof with the easiest solution.', 'wp-fundraising'),
					'docs' => 'https://wpmet.com/doc/wp-ultimate-review/',
				],
				'blocks-for-shopengine/shopengine-gutenberg-addon.php' => [
					'name' => esc_html__('Blocks for ShopEngine', 'wp-fundraising'),
					'url'  => 'https://wordpress.org/plugins/blocks-for-shopengine/',
					'icon' => $apps_img_path. 'shopengine.gif',
					'desc' => esc_html__('All in one WooCommerce solution for Gutenberg! Build your WooCommerce pages in a block editor with full customization.', 'wp-fundraising'),
					'docs' => 'https://wpmet.com/doc/shopengine/shopengine-gutenberg/',
				],
				'genie-image-ai/genie-image-ai.php' => [
					'name' => esc_html__('Genie Image', 'wp-fundraising'),
					'url'  => 'https://wordpress.org/plugins/genie-image-ai/',
					'icon' => $apps_img_path . 'genie-image.png',
					'desc' => esc_html__('AI-powered text-to-image generator for WordPress with OpenAI’s DALL-E 2 technology to generate high-quality images in one click.', 'wp-fundraising'),
					'docs' => 'https://getgenie.ai/docs/',
				],
			]
        )
        ->call();

	}
}
