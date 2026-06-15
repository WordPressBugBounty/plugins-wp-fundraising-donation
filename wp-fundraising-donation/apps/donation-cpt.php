<?php

namespace WfpFundraising\Apps;

defined( 'ABSPATH' ) || exit;

use WfpFundraising\Traits\Singleton;


class Donation_Cpt {

	const TYPE = 'wfp_donation';

	use Singleton;


	public function init() {

		add_action( 'init', array( $this, 'register_custom_post_types' ) );
	}

	public function register_custom_post_types() {

		$labels = array(
			'name'                  => _x( 'Donations', 'Post Type General Name', 'wp-fundraising-donation' ),
			'singular_name'         => _x( 'Donation', 'Post Type Singular Name', 'wp-fundraising-donation' ),
			'menu_name'             => _x( 'Donations', 'Admin Menu text', 'wp-fundraising-donation' ),
			'name_admin_bar'        => _x( 'Donation', 'Add New on Toolbar', 'wp-fundraising-donation' ),
			'archives'              => __( 'Donation Archives', 'wp-fundraising-donation' ),
			'attributes'            => __( 'Donation Attributes', 'wp-fundraising-donation' ),
			'parent_item_colon'     => __( 'Parent Donation:', 'wp-fundraising-donation' ),
			'all_items'             => __( 'All Donations', 'wp-fundraising-donation' ),
			'add_new_item'          => __( 'Add New Donation', 'wp-fundraising-donation' ),
			'add_new'               => __( 'Add New', 'wp-fundraising-donation' ),
			'new_item'              => __( 'New Donation', 'wp-fundraising-donation' ),
			'edit_item'             => __( 'Edit Donation', 'wp-fundraising-donation' ),
			'update_item'           => __( 'Update Donation', 'wp-fundraising-donation' ),
			'view_item'             => __( 'View Donation', 'wp-fundraising-donation' ),
			'view_items'            => __( 'View Donations', 'wp-fundraising-donation' ),
			'search_items'          => __( 'Search Donation', 'wp-fundraising-donation' ),
			'not_found'             => __( 'Not found', 'wp-fundraising-donation' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'wp-fundraising-donation' ),
			'featured_image'        => __( 'Featured Image', 'wp-fundraising-donation' ),
			'set_featured_image'    => __( 'Set featured image', 'wp-fundraising-donation' ),
			'remove_featured_image' => __( 'Remove featured image', 'wp-fundraising-donation' ),
			'use_featured_image'    => __( 'Use as featured image', 'wp-fundraising-donation' ),
			'insert_into_item'      => __( 'Insert into Donation', 'wp-fundraising-donation' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Donation', 'wp-fundraising-donation' ),
			'items_list'            => __( 'Donations list', 'wp-fundraising-donation' ),
			'items_list_navigation' => __( 'Donations list navigation', 'wp-fundraising-donation' ),
			'filter_items_list'     => __( 'Filter Donations list', 'wp-fundraising-donation' ),
		);

		$args = array(
			'label'               => __( 'Donation', 'wp-fundraising-donation' ),
			'description'         => __( 'Donation post type for managing fundraising campaigns.', 'wp-fundraising-donation' ),
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
