<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

use WfpFundraising\Apps\Key;
use WfpFundraising\Apps\Settings;

/**
 * We need the post id to grab the necessary meta values
 *
 * We also need below variables defined before including this page
 *
 * $wfp_donation_type : [WFP_DONATION_TYPE_SINGLE | WFP_DONATION_TYPE_CROWED]
 * $wfp_show_in_modal     : [Yes|No]
 * $wfp_form_fields   : [WFP_FORM_FIELDS_ALL|WFP_FORM_FIELDS_ONLY_BTN]
 */
$wfpPostId = empty( $wfpPostId ) || $wfpPostId < 0 ? get_the_ID() : $wfpPostId;

if ( empty( $post ) || ! is_object( $post ) || $post->ID !== $wfpPostId ) {

	$post   = get_post( $wfpPostId );
	$wfpPostId = $post->ID;
}



$wfpMetaKey      = Key::MK_FORM_OPTIONS;
$wfpMetaDataJson = get_post_meta( $wfpPostId, $wfpMetaKey, false );
$wfpGetMetaData  = json_decode( json_encode( end( $wfpMetaDataJson ), JSON_UNESCAPED_UNICODE ) );

$wfpOptionsKey     = Key::OK_PAYMENT_OPTIONS;
$wfpGetOptionsData = get_option( $wfpOptionsKey );
$wfpGateWaysData   = isset( $wfpGetOptionsData['gateways'] ) ? $wfpGetOptionsData['gateways'] : array();


$wfpFormDonation  = ! empty( $wfpGetMetaData->donation ) ? $wfpGetMetaData->donation : array();
$wfp_donation_type = ! empty( $wfpGetMetaData->donation->type ) ? $wfpGetMetaData->donation->type : 'multi-lebel';
$wfpMultiData     = ! empty( $wfpGetMetaData->donation->multi->dimentions ) ? $wfpGetMetaData->donation->multi->dimentions : array();
$wfpPage_width    = isset( $wfpGetMetaData->donation->page_width ) ? $wfpGetMetaData->donation->page_width : 0;


$wfpGetMetaGeneralOp = get_option( Settings::OK_GENERAL_DATA );
$wfpGetSetUpData     = get_option( Key::OK_SETUP_SERVICE_DATA );

$wfpGetMetaGeneral = isset( $wfpGetMetaGeneralOp['options'] ) ? $wfpGetMetaGeneralOp['options'] : array();
$wfpPaymentType    = ! empty( $wfpGetSetUpData['services']['payment'] ) ? $wfpGetSetUpData['services']['payment'] : 'default';

$wfpSetupData        = isset( $wfpGetSetUpData['services'] ) ? $wfpGetSetUpData['services'] : array();
$wfpGateCampaignData = isset( $wfpSetupData['payment'] ) ? $wfpSetupData['payment'] : 'default';

$wfpGetMetaGeneralPage = Settings::instance()->get_mapped_page_slug( $wfpGetMetaGeneralOp );
$wfpCheckoutPage       = Settings::instance()->get_mapped_checkout_page_slug( $wfpGetMetaGeneralOp );


$wfp_site_url     = get_site_url();
$wfpUrlCheckout  = $wfp_site_url . '/' . $wfpCheckoutPage . '?wfpout=true';
$wfpToFixedPoint = empty( $wfpGetMetaGeneral['currency']['number_decimal'] ) ? 2 : intval( $wfpGetMetaGeneral['currency']['number_decimal'] );

if ( $wfpPaymentType == 'woocommerce' ) {

	/**
	 * Making it as virtual so there is no shipping cost on it.
	 */
	update_post_meta( $wfpPostId, '_virtual', 'yes' );

	$wfp_cart_url = wc_get_cart_url();

	$wfpUrlCheckout = $wfp_cart_url . '?wfpout=true&virtual=yes';
}

/**
 * I do not from where $wfpDefaultData is coming, it was before so I just put it
 * guessing no harm comes with it :P
 */
$wfpDefaultData      = 0;
$wfp_defaultUse_space = ! empty( $wfpGetMetaGeneral['currency']['use_space'] ) ? $wfpGetMetaGeneral['currency']['use_space'] : 'off';
$wfpDefCurrencyInfo  = isset( $wfpGetMetaGeneral['currency']['name'] ) ? $wfpGetMetaGeneral['currency']['name'] : 'US-USD';


$wfp_categories = get_the_terms( $wfpPostId, 'wfp-categories' );
$wfp_enable_cat = empty( $wfp_fundraising_content_donate__category_enable ) ? Key::WFP_YES : $wfp_fundraising_content_donate__category_enable;


$wfp_featured_enable = empty( $wfp_fundraising_content_donate__featured_enable ) ? Key::WFP_YES : $wfp_fundraising_content_donate__featured_enable;
$wfp_title_enable    = empty( $wfp_fundraising_content_donate__title_enable ) ? Key::WFP_YES : $wfp_fundraising_content_donate__title_enable;
$wfp_enable_goal     = empty( $wfp_fundraising_content_donate__goal_enable ) ? Key::WFP_YES : $wfp_fundraising_content_donate__goal_enable;


$wfpFormContentData = isset( $wfpGetMetaData->form_content ) ? $wfpGetMetaData->form_content : (object) array(
	'enable'           => Key::WFP_NO,
	'content_position' => 'after-form',
);

$wfpFormGoalData = isset( $wfpGetMetaData->goal_setup ) ? $wfpGetMetaData->goal_setup : (object) array(
	'enable'    => Key::WFP_NO,
	'goal_type' => 'goal_terget_amount',
);

$wfpFormTermsData = isset( $wfpGetMetaData->form_terma ) ? $wfpGetMetaData->form_terma : (object) array(
	'enable'           => 'No',
	'content_position' => 'after-submit-button',
);

$wfpFormDesignData = isset( $wfpGetMetaData->form_design ) ? $wfpGetMetaData->form_design : (object) array(
	'styles'          => Key::WFP_FORM_FIELDS_ALL,
	'modal_show'      => Key::WFP_NO,
	'continue_button' => 'Continue',
	'submit_button'   => 'Donate Now',
);

$wfpCustomIdData = isset( $wfpGetMetaData->form_design->custom_id ) ? $wfpGetMetaData->form_design->custom_id : '';
$wfpCustomClass  = isset( $wfpGetMetaData->form_design->custom_class ) ? $wfpGetMetaData->form_design->custom_class : '';

/**
 * Fallback checking if anyone does not defined the value
 */
$wfp_show_in_modal = isset( $wfp_show_in_modal ) ?
	$wfp_show_in_modal :
	(
		empty( $wfpGetMetaData->form_design->modal_show ) ? Key::WFP_NO : $wfpGetMetaData->form_design->modal_show
	);

$wfp_donation_type = isset( $wfp_donation_type ) ?
	$wfp_donation_type :
	(
			empty( $wfpGetMetaData->donation->format ) ? Key::WFP_DONATION_TYPE_SINGLE : $wfpGetMetaData->donation->format
	);

$wfp_form_fields = isset( $wfp_form_fields ) ?
	$wfp_form_fields :
	(
			empty( $wfpGetMetaData->form_design->styles ) ? Key::WFP_FORM_FIELDS_ALL : $wfpGetMetaData->form_design->styles
	);

/*
 * End of fallback checking
 *
 */


$wfp_fixed_data = ! empty( $wfpFormDonation->fixed ) ? $wfpFormDonation->fixed : (object) array( 'enable_custom_amount' => 'No' );

$wfpGoalMessage = isset( $wfpFormGoalData->terget->message ) ? $wfpFormGoalData->terget->message : '';

$wfpEnableDisplayField = (
	$wfp_form_fields == \WfpFundraising\Apps\Key::WFP_FORM_FIELDS_ONLY_BTN &&
	$wfp_show_in_modal == \WfpFundraising\Apps\Key::WFP_NO ) ?
	'xs-show-div-only-button__' . $wfpPostId . ' xs-donate-hidden' : '';


if ( $wfp_donation_type == Key::WFP_DONATION_TYPE_SINGLE ) {

	if ( $wfp_show_in_modal == Key::WFP_YES ) {

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

$wfpFormSetting = isset( $wfpGetMetaData->form_settings ) ? $wfpGetMetaData->form_settings : array();

$wfpEnableSidebar = isset( $wfpFormSetting->sidebar->enable ) ? $wfpFormSetting->sidebar->enable : 'No';
$wfpEnableSidebar = apply_filters( 'wfp_single_sidebar_disable', $wfpEnableSidebar );


$wfpHideTitle    = isset( $wfpFormSetting->single_title->enable ) ? $wfpFormSetting->single_title->enable : 'No';
$wfpHideFeatured = isset( $wfpFormSetting->featured->enable ) ? $wfpFormSetting->featured->enable : 'No';

$wfpHideShortBrief = isset( $wfpFormSetting->single_excerpt->enable ) ? $wfpFormSetting->single_excerpt->enable : 'No';
$wfpHideShortBrief = apply_filters( 'wfp_single_excerpt_hide', $wfpHideShortBrief );





// ---------------------------------------

?>


<?php do_action( 'wfp_campaign_content_before' ); ?>

<div class="wfp-container xs-wfp-crowd" style="<?php echo esc_attr( $wfpPage_width <= 0 ? '' : 'max-width:' . $wfpPage_width . 'px;' ); ?>">
	<div class="wfp-view wfp-view-public">
		<section id="main-content" class="wfp-single-page" role="main">
			<div class="xs-container">
				<div class="<?php echo ( $wfpEnableSidebar == 'Yes' ) ? 'xs-row ' : ' '; ?>">
					<div class="<?php echo ( $wfpEnableSidebar == 'Yes' ) ? 'xs-col-sm-12 xs-col-md-12 xs-col-lg-8 wfp-single-page-left-section ' : ' '; ?>">
						
						<div class="wfp-entry-content">
							<article id="post-<?php $wfpPostId; ?>" <?php post_class( '', $wfpPostId ); ?>
									 wfp-data-url="<?php echo esc_url( add_query_arg( 'wpf_checkout_nonce_field', wp_create_nonce( 'wpf_checkout' ), $wfpUrlCheckout ) ); ?>"
									 wfp-payment-type="<?php echo esc_html( $wfpPaymentType ); ?>">

								<div class="wfp_wraper_con">
									<?php require __DIR__ . '/include/crowd_donation_body.php'; ?>

									<?php require __DIR__ . '/include/content-header.php'; ?>
								</div>

							</article>
						</div>

					</div>

					<?php if ( $wfpEnableSidebar == 'Yes' ) : ?>
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
