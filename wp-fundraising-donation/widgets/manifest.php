<?php

namespace WfpFundraising\Widgets;

use WfpFundraising\Utilities\Helper;

defined( 'ABSPATH' ) || exit;

/**
 *
 * @package WfpFundraising\Widgets
 */
class Manifest {

	public function init() {

		add_action( 'elementor/elements/categories_registered', array( $this, 'widget_categories' ) );
		add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_widgets' ) );
	}


	protected function widget_list() {

		$list = array(

			'wfp-fundraising-donate'  => array(
				'slug'       => 'wfp-fundraising-donate',
				'title'      => esc_html__( 'Donate Button', 'wp-fundraising' ),
				'base_class' => '\Elementor\Wfp_Donate',
				'package'    => 'free',
			),

			'wfp-fundraising-listing' => array(
				'slug'       => 'wfp-fundraising-listing',
				'title'      => esc_html__( 'Campaign List', 'wp-fundraising' ),
				'base_class' => '\Elementor\Wfp_Listing',
				'package'    => 'free',
			),
		);

		return apply_filters( 'wfp_fundraising/widgets/list', $list );
	}


	public function register_widgets() {

		foreach ( $this->widget_list() as $name => $item ) {

			if ( isset( $item['path'] ) && file_exists( $item['path'] . '/' . $item['slug'] . '.php' ) ) {
				require_once $item['path'] . '/' . $item['slug'] . '.php';
			}

			if ( ! isset( $item['base_class'] ) ) {
				$item['base_class'] = '\Elementor\\' . Helper::make_classname( $name );
			}

			if ( isset( $item['base_class'] ) && class_exists( $item['base_class'] ) ) {

				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new $item['base_class']() );
			}
		}
	}

	public function widget_categories( $elements_manager ) {

		$elements_manager->add_category(
			'wfp-fundraising',
			array(
				'title' => esc_html__( 'FundEngine', 'wp-fundraising' ),
				'icon'  => 'fa fa-plug',
			)
		);
	}
}

