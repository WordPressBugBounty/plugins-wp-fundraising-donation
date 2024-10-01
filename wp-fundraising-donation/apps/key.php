<?php

namespace WfpFundraising\Apps;

class Key {

	const WFP_DONATION_TYPE_SINGLE = 'donation';
	const WFP_DONATION_TYPE_CROWED = 'crowdfunding';

	const WFP_FORM_FIELDS_ALL      = 'all_fields';
	const WFP_FORM_FIELDS_ONLY_BTN = 'only_button';

	const WFP_YES = 'Yes';
	const WFP_NO  = 'No';

	const LAYOUT_STYLE_GRID     = 'wfp-layout-grid';
	const LAYOUT_STYLE_LIST     = 'wfp-layout-list';
	const LAYOUT_STYLE_MASONARY = 'wfp-layout-masonary';

	const GOAL_TYPE_TARGET_GOAL = 'terget_goal';
	const GOAL_TYPE_TARGET_DATE = 'terget_date';
	const GOAL_TYPE_GOAL_DATE   = 'terget_goal_date';
	const GOAL_TYPE_NEVER_END   = 'campaign_never_end';

	const CAMPAIGN_STATUS_ENDED = 'Ends';

	const PAYMENT_PREFERENCE_GLOBAL   = 'use_global';
	const PAYMENT_PREFERENCE_PERSONAL = 'use_personal';


	/**
	 * Meta value keys
	 */
	const MK_FORM_OPTIONS      = 'wfp_form_options_meta_data';
	const MK_PORTFOLIO_GALLERY = 'wfp_portfolio_gallery';


	/**
	 * Options keys
	 */
	const OK_PAYMENT_OPTIONS        = 'wfp_payment_options_data';
	const OK_SETUP_SERVICE_DATA     = 'wfp_setup_services_data';
	const OK_GLOBAL_DISPLAY_OPTIONS = 'wfp_display_options_data';


	/**
	 * Page slugs
	 */
	const SLUG_INVOICE_PAGE = 'wpfd-invoice-checking';


	/**
	 * Post status
	 * get_post_statuses()
	 */
	const WP_POST_STATUS_DRAFT   = 'draft';
	const WP_POST_STATUS_PENDING = 'pending';
	const WP_POST_STATUS_PUBLISH = 'publish';
	const WP_POST_STATUS_PRIVATE = 'private';


	/**
	 * Action keys
	 */
	const FUNDRAISING_PRO_LOADED = 'wpfp/fundraising_pro/plugin_loaded';


	const PAYMENT_METHOD_PAYPAL = 'online_payment';
	const PAYMENT_METHOD_STRIPE = 'stripe_payment';



	/**
	 *
	 * @since 1.0.0
	 *
	 * @param $campaign_id
	 * @param $invoice
	 *
	 * @return string
	 */
	public static function generate_invoice_link( $campaign_id, $invoice ) {

		$url = home_url( '/' . self::SLUG_INVOICE_PAGE );

		return $url . '?invoice=' . $invoice . '&campaign=' . $campaign_id;
	}


	public static function make_user_readable( $ky ) {

		$ky = str_replace( '_', ' ', $ky );
		$ky = str_replace( '-', ' ', $ky );

		return ucfirst( $ky );
	}
}
