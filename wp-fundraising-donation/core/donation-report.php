<?php

namespace WfpFundraising\Core;

use WfpFundraising\Apps\Fundraising_Cpt;
use WfpFundraising\Traits\Singleton;

class Donation_Report {

	use Singleton;

	public function init() {

		add_action( 'admin_menu', array( $this, 'wfp_add_admin_menu_donate' ) );
	}


	public function wfp_add_admin_menu_donate() {

		// add_submenu_page(
		// string $parent_slug,
		// string $page_title,
		// string $menu_title,
		// string $capability,
		// string $menu_slug,
		// callable $function = '',
		// int $position = null
		// )

		add_submenu_page(
			'edit.php?post_type=' . Fundraising_Cpt::TYPE . '',
			esc_html__( 'Donations', 'wp-fundraising' ),
			esc_html__( 'Donations', 'wp-fundraising' ),
			'manage_options',
			'donations',
			array( $this, 'payments_details' )
		);
	}


	public function payments_details() {

		include __DIR__ . '/../views/admin/view-payments.php';
	}

}
