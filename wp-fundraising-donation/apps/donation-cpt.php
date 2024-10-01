<?php

namespace WfpFundraising\Apps;

use WfpFundraising\Traits\Singleton;


class Donation_Cpt {

	const TYPE = 'wfp_donation';

	use Singleton;


	public function init() {

		add_action( 'init', array( $this, 'register_custom_post_types' ) );
	}

	public function register_custom_post_types() {

		$labels = array(
			'name'                  => _x( 'Donations', 'Post Type General Name', 'wp-fundraising' ),
			'singular_name'         => _x( 'Donation', 'Post Type Singular Name', 'wp-fundraising' ),
			'menu_name'             => _x( 'Donations', 'Admin Menu text', 'wp-fundraising' ),
			'name_admin_bar'        => _x( 'Donation', 'Add New on Toolbar', 'wp-fundraising' ),
			'archives'              => __( 'Donation Archives', 'wp-fundraising' ),
			'attributes'            => __( 'Donation Attributes', 'wp-fundraising' ),
			'parent_item_colon'     => __( 'Parent Donation:', 'wp-fundraising' ),
			'all_items'             => __( 'All Donations', 'wp-fundraising' ),
			'add_new_item'          => __( 'Add New Donation', 'wp-fundraising' ),
			'add_new'               => __( 'Add New', 'wp-fundraising' ),
			'new_item'              => __( 'New Donation', 'wp-fundraising' ),
			'edit_item'             => __( 'Edit Donation', 'wp-fundraising' ),
			'update_item'           => __( 'Update Donation', 'wp-fundraising' ),
			'view_item'             => __( 'View Donation', 'wp-fundraising' ),
			'view_items'            => __( 'View Donations', 'wp-fundraising' ),
			'search_items'          => __( 'Search Donation', 'wp-fundraising' ),
			'not_found'             => __( 'Not found', 'wp-fundraising' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'wp-fundraising' ),
			'featured_image'        => __( 'Featured Image', 'wp-fundraising' ),
			'set_featured_image'    => __( 'Set featured image', 'wp-fundraising' ),
			'remove_featured_image' => __( 'Remove featured image', 'wp-fundraising' ),
			'use_featured_image'    => __( 'Use as featured image', 'wp-fundraising' ),
			'insert_into_item'      => __( 'Insert into Donation', 'wp-fundraising' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Donation', 'wp-fundraising' ),
			'items_list'            => __( 'Donations list', 'wp-fundraising' ),
			'items_list_navigation' => __( 'Donations list navigation', 'wp-fundraising' ),
			'filter_items_list'     => __( 'Filter Donations list', 'wp-fundraising' ),
		);

		$args = array(
			'label'               => __( 'Donation', 'wp-fundraising' ),
			'description'         => __( '', 'wp-fundraising' ),
			'labels'              => $labels,
			'menu_icon'           => '',
			'supports'            => array( 'title', 'editor', 'author' ),
			'taxonomies'          => array(),
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'menu_position'       => 5,
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'hierarchical'        => false,
			'exclude_from_search' => true,
			'show_in_rest'        => true,
			'publicly_queryable'  => false,
			'capability_type'     => 'post',
		);

		register_post_type( self::TYPE, $args );
	}
}
