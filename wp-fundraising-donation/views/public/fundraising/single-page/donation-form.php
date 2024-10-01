<?php

use WfpFundraising\Apps\Key;
use WfpFundraising\Apps\Settings;

/**
 * We need the post id to grab the necessary meta values
 *
 * We also need below variables defined before including this page
 *
 * $wfp_donation_type : [WFP_DONATION_TYPE_SINGLE | WFP_DONATION_TYPE_CROWED]
 * $show_in_modal     : [Yes|No]
 * $wfp_form_fields   : [WFP_FORM_FIELDS_ALL|WFP_FORM_FIELDS_ONLY_BTN]
 */
$postId = empty( $postId ) || $postId < 0 ? get_the_ID() : $postId;

if ( empty( $post ) || ! is_object( $post ) || $post->ID !== $postId ) {

	$post   = get_post( $postId );
	$postId = $post->ID;
}



$metaKey      = Key::MK_FORM_OPTIONS;
$metaDataJson = get_post_meta( $postId, $metaKey, false );
$getMetaData  = json_decode( json_encode( end( $metaDataJson ), JSON_UNESCAPED_UNICODE ) );

$optionsKey     = Key::OK_PAYMENT_OPTIONS;
$getOptionsData = get_option( $optionsKey );
$gateWaysData   = isset( $getOptionsData['gateways'] ) ? $getOptionsData['gateways'] : array();


$formDonation  = ! empty( $getMetaData->donation ) ? $getMetaData->donation : array();
$donation_type = ! empty( $getMetaData->donation->type ) ? $getMetaData->donation->type : 'multi-lebel';
$multiData     = ! empty( $getMetaData->donation->multi->dimentions ) ? $getMetaData->donation->multi->dimentions : array();
$page_width    = isset( $getMetaData->donation->page_width ) ? $getMetaData->donation->page_width : 0;


$getMetaGeneralOp = get_option( Settings::OK_GENERAL_DATA );
$getSetUpData     = get_option( Key::OK_SETUP_SERVICE_DATA );

$getMetaGeneral = isset( $getMetaGeneralOp['options'] ) ? $getMetaGeneralOp['options'] : array();
$paymentType    = ! empty( $getSetUpData['services']['payment'] ) ? $getSetUpData['services']['payment'] : 'default';

$setupData        = isset( $getSetUpData['services'] ) ? $getSetUpData['services'] : array();
$gateCampaignData = isset( $setupData['payment'] ) ? $setupData['payment'] : 'default';

$getMetaGeneralPage = Settings::instance()->get_mapped_page_slug( $getMetaGeneralOp );
$checkoutPage       = Settings::instance()->get_mapped_checkout_page_slug( $getMetaGeneralOp );


$site_url     = get_site_url();
$urlCheckout  = $site_url . '/' . $checkoutPage . '?wfpout=true';
$toFixedPoint = empty( $getMetaGeneral['currency']['number_decimal'] ) ? 2 : intval( $getMetaGeneral['currency']['number_decimal'] );

if ( $paymentType == 'woocommerce' ) {

	/**
	 * Making it as virtual so there is no shipping cost on it.
	 */
	update_post_meta( $postId, '_virtual', 'yes' );

	$cart_url = wc_get_cart_url();

	$urlCheckout = $cart_url . '?wfpout=true&virtual=yes';
}

/**
 * I do not from where $defaultData is coming, it was before so I just put it
 * guessing no harm comes with it :P
 */
$defaultData      = 0;
$defaultUse_space = ! empty( $getMetaGeneral['currency']['use_space'] ) ? $getMetaGeneral['currency']['use_space'] : 'off';
$defCurrencyInfo  = isset( $getMetaGeneral['currency']['name'] ) ? $getMetaGeneral['currency']['name'] : 'US-USD';


$categories = get_the_terms( $postId, 'wfp-categories' );
$enable_cat = empty( $wfp_fundraising_content_donate__category_enable ) ? Key::WFP_YES : $wfp_fundraising_content_donate__category_enable;


$featured_enable = empty( $wfp_fundraising_content_donate__featured_enable ) ? Key::WFP_YES : $wfp_fundraising_content_donate__featured_enable;
$title_enable    = empty( $wfp_fundraising_content_donate__title_enable ) ? Key::WFP_YES : $wfp_fundraising_content_donate__title_enable;
$enable_goal     = empty( $wfp_fundraising_content_donate__goal_enable ) ? Key::WFP_YES : $wfp_fundraising_content_donate__goal_enable;


$formContentData = isset( $getMetaData->form_content ) ? $getMetaData->form_content : (object) array(
	'enable'           => Key::WFP_NO,
	'content_position' => 'after-form',
);

$formGoalData = isset( $getMetaData->goal_setup ) ? $getMetaData->goal_setup : (object) array(
	'enable'    => Key::WFP_NO,
	'goal_type' => 'goal_terget_amount',
);

$formTermsData = isset( $getMetaData->form_terma ) ? $getMetaData->form_terma : (object) array(
	'enable'           => 'No',
	'content_position' => 'after-submit-button',
);

$formDesignData = isset( $getMetaData->form_design ) ? $getMetaData->form_design : (object) array(
	'styles'          => Key::WFP_FORM_FIELDS_ALL,
	'modal_show'      => Key::WFP_NO,
	'continue_button' => 'Continue',
	'submit_button'   => 'Donate Now',
);

$customIdData = isset( $getMetaData->form_design->custom_id ) ? $getMetaData->form_design->custom_id : '';
$customClass  = isset( $getMetaData->form_design->custom_class ) ? $getMetaData->form_design->custom_class : '';

/**
 * Fallback checking if anyone does not defined the value
 */
$show_in_modal = isset( $show_in_modal ) ?
	$show_in_modal :
	(
		empty( $getMetaData->form_design->modal_show ) ? Key::WFP_NO : $getMetaData->form_design->modal_show
	);

$wfp_donation_type = isset( $wfp_donation_type ) ?
	$wfp_donation_type :
	(
			empty( $getMetaData->donation->format ) ? Key::WFP_DONATION_TYPE_SINGLE : $getMetaData->donation->format
	);

$wfp_form_fields = isset( $wfp_form_fields ) ?
	$wfp_form_fields :
	(
			empty( $getMetaData->form_design->styles ) ? Key::WFP_FORM_FIELDS_ALL : $getMetaData->form_design->styles
	);

/*
 * End of fallback checking
 *
 */


$fixed_data = ! empty( $formDonation->fixed ) ? $formDonation->fixed : (object) array( 'enable_custom_amount' => 'No' );

$goalMessage = isset( $formGoalData->terget->message ) ? $formGoalData->terget->message : '';

$enableDisplayField = (
	$wfp_form_fields == \WfpFundraising\Apps\Key::WFP_FORM_FIELDS_ONLY_BTN &&
	$show_in_modal == \WfpFundraising\Apps\Key::WFP_NO ) ?
	'xs-show-div-only-button__' . $postId . ' xs-donate-hidden' : '';


if ( $wfp_donation_type == Key::WFP_DONATION_TYPE_SINGLE ) {

	if ( $show_in_modal == Key::WFP_YES ) {

		if ( $wfp_form_fields == Key::WFP_FORM_FIELDS_ALL ) {

			require __DIR__ . '/include/single_donation_modal_all_fields.php';

		} else {

			// Only_button

			require __DIR__ . '/include/single_donation_modal_only_btn.php';
		}

		return;
	}

	require __DIR__ . '/include/single_donation_no_modal.php';

	return;
}


/**
 * Below will be crowed-funding only
 */

$formSetting = isset( $getMetaData->form_settings ) ? $getMetaData->form_settings : array();

$enableSidebar = isset( $formSetting->sidebar->enable ) ? $formSetting->sidebar->enable : 'No';
$enableSidebar = apply_filters( 'wfp_single_sidebar_disable', $enableSidebar );


$hideTitle    = isset( $formSetting->single_title->enable ) ? $formSetting->single_title->enable : 'No';
$hideFeatured = isset( $formSetting->featured->enable ) ? $formSetting->featured->enable : 'No';

$hideShortBrief = isset( $formSetting->single_excerpt->enable ) ? $formSetting->single_excerpt->enable : 'No';
$hideShortBrief = apply_filters( 'wfp_single_excerpt_hide', $hideShortBrief );





// ---------------------------------------

?>


<?php do_action( 'wfp_campaign_content_before' ); ?>

<div class="wfp-container xs-wfp-crowd" style="<?php echo esc_attr( $page_width <= 0 ? '' : 'max-width:' . $page_width . 'px;' ); ?>">
	<div class="wfp-view wfp-view-public">
		<section id="main-content" class="wfp-single-page" role="main">
			<div class="xs-container">
				<div class="<?php echo ( $enableSidebar == 'Yes' ) ? 'xs-row ' : ' '; ?>">
					<div class="<?php echo ( $enableSidebar == 'Yes' ) ? 'xs-col-sm-12 xs-col-md-12 xs-col-lg-8 wfp-single-page-left-section ' : ' '; ?>">
						
						<div class="wfp-entry-content">
							<article id="post-<?php $postId; ?>" <?php post_class( '', $postId ); ?>
									 wfp-data-url="<?php echo esc_url( add_query_arg( 'wpf_checkout_nonce_field', wp_create_nonce( 'wpf_checkout' ), $urlCheckout ) ); ?>"
									 wfp-payment-type="<?php echo esc_html( $paymentType ); ?>">

								<div class="wfp_wraper_con">
									<?php require __DIR__ . '/include/crowd_donation_body.php'; ?>

									<?php require __DIR__ . '/include/content-header.php'; ?>
								</div>

							</article>
						</div>

					</div>

					<?php if ( $enableSidebar == 'Yes' ) : ?>
						<div class="xs-col-sm-12 xs-col-md-12 xs-col-lg-4 wfp-single-page-sidebar-section ">
							<?php do_action( 'wfp_single_sidebar_before' ); ?>
							<?php get_sidebar(); ?>
							<?php do_action( 'wfp_single_sidebar_after' ); ?>
						</div>
					<?php endif; ?>

				</div>
			</div>
		</section>
	</div>
</div>

<?php do_action( 'wfp_campaign_content_after' ); ?>
