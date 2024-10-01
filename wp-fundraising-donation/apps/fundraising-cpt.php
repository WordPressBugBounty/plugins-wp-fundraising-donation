<?php

namespace WfpFundraising\Apps;

use WfpFundraising\Traits\Singleton;


class Fundraising_Cpt {

	const TYPE = 'wp-fundraising';

	use Singleton;


	public function init() {

		add_action( 'init', array( $this, 'register_custom_post_types' ) );
	}

	public function register_custom_post_types() {

		$args = array(
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
		);

		register_post_type( self::TYPE, $args );

		register_taxonomy(
			'wfp-categories',
			array( self::TYPE ),
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

		register_taxonomy(
			'wfp-tags',
			array( self::TYPE ),
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
}
