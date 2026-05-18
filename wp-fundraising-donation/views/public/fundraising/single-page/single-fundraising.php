<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

/*
 * Template Name: WP Fundrasing Single page Template
 * Template Post Type: wp-fundrasing
 */
get_header();


$wfpPostId = get_the_ID();

// setting data
$wfpEnableSidebar = isset( $wfpFormSetting->sidebar->enable ) ? $wfpFormSetting->sidebar->enable : 'No';
$wfpEnableSidebar = apply_filters( 'wfp_single_sidebar_disable', $wfpEnableSidebar );

$wfpEnableFeatured      = isset( $wfpFormSetting->featured->enable ) ? $wfpFormSetting->featured->enable : 'No';
$wfpEnableSingleTitle   = isset( $wfpFormSetting->single_title->enable ) ? $wfpFormSetting->single_title->enable : 'No';
$wfpEnableSingleExcerpt = isset( $wfpFormSetting->single_excerpt->enable ) ? $wfpFormSetting->single_excerpt->enable : 'No';
$wfpEnableSingleExcerpt = apply_filters( 'wfp_single_excerpt_hide', $wfpEnableSingleExcerpt );

$wfpEnableSingleContent = isset( $wfpFormSetting->single_content->enable ) ? $wfpFormSetting->single_content->enable : 'No';
$wfpEnableSingleContent = apply_filters( 'wfp_single_content_decription_hide', $wfpEnableSingleContent );

$wfpEnableSingleReview = isset( $wfpFormSetting->single_review->enable ) ? $wfpFormSetting->single_review->enable : 'No';
$wfpEnableSingleReview = apply_filters( 'wfp_single_content_review_hide', $wfpEnableSingleReview );

$wfpEnableSingleUpdates = isset( $wfpFormSetting->single_updates->enable ) ? $wfpFormSetting->single_updates->enable : 'No';
$wfpEnableSingleUpdates = apply_filters( 'wfp_single_content_updates_hide', $wfpEnableSingleUpdates );

$wfpEnableSingleRecents = isset( $wfpFormSetting->single_recents->enable ) ? $wfpFormSetting->single_recents->enable : 'No';
$wfpEnableSingleRecents = apply_filters( 'wfp_single_content_recent_hide', $wfpEnableSingleRecents );

$wfpEnableSingleContributor = isset( $wfpFormSetting->contributor->enable ) ? $wfpFormSetting->contributor->enable : 'No';
$wfpEnableSingleContributor = apply_filters( 'wfp_single_content_contributor_hide', $wfpEnableSingleContributor );

// general option data
$wfpGetMetaGeneralOp = get_option( \WfpFundraising\Apps\Settings::OK_GENERAL_DATA );

$wfpGetMetaGeneral     = isset( $wfpGetMetaGeneralOp['options'] ) ? $wfpGetMetaGeneralOp['options'] : array();
$wfpGetMetaGeneralPage = \WfpFundraising\Apps\Settings::instance()->get_mapped_page_slug( $wfpGetMetaGeneralOp );
$wfpCheckoutPage       = \WfpFundraising\Apps\Settings::instance()->get_mapped_checkout_page_slug( $wfpGetMetaGeneralOp );

$wfpToFixedPoint = empty( $wfpGetMetaGeneral['currency']['number_decimal'] ) ? 2 : intval( $wfpGetMetaGeneral['currency']['number_decimal'] );
$wfpUrlCheckout  = get_site_url() . '/' . $wfpCheckoutPage . '?wfpout=true';

$wfpMetaSetupKey = 'wfp_setup_services_data';
$wfpGetSetUpData = get_option( $wfpMetaSetupKey );
$wfpPaymentType  = isset( $wfpGetSetUpData['services']['payment'] ) ? $wfpGetSetUpData['services']['payment'] : 'default';

$wfp_cur_symbol = \WfpFundraising\Apps\Settings::instance()->get_curr_symbol( $wfpGetMetaGeneral, $wfpPaymentType );

if ( $wfpPaymentType == 'woocommerce' ) {

	/**
	 * Making it as virtual so there is no shipping cost on it.
	 */
	update_post_meta( $wfpPostId, '_virtual', 'yes' );

	$wfp_cart_url = wc_get_cart_url();

	$wfpUrlCheckout = $wfp_cart_url . '?wfpout=true&virtual=yes';
}

$wfpMetaKey      = 'wfp_form_options_meta_data';
$wfpMetaDataJson = get_post_meta( $wfpPostId, $wfpMetaKey, false );
$wfpGetMetaData  = json_decode( json_encode( end( $wfpMetaDataJson ), JSON_UNESCAPED_UNICODE ) );

$wfpFormDesignData = isset( $wfpGetMetaData->form_design ) ? $wfpGetMetaData->form_design : (object) array(
	'styles'          => 'all_fields',
	'continue_button' => 'Continue',
	'submit_button'   => 'Donate Now',
);

$wfpDefaultData      = 0;
$wfpShowInModal      = isset( $wfpFormDesignData->modal_show ) ? $wfpFormDesignData->modal_show : 'No';
$wfpDonationTypeData = empty( $wfpGetMetaData->donation->format ) ? 'donation' : $wfpGetMetaData->donation->format;
$wfp_amount_limit     = property_exists( $wfpGetMetaData->donation, 'set_limit' ) ? $wfpGetMetaData->donation->set_limit : array();


$wfpOptinsKey      = 'wfp_payment_options_data';
$wfpGetOptionsData = get_option( $wfpOptinsKey );
$wfpGateWaysData   = isset( $wfpGetOptionsData['gateways'] ) ? $wfpGetOptionsData['gateways'] : array();
$wfp_form_styles    = $wfpFormDesignData->styles;

if ( ! empty( $fromWhere ) && $fromWhere == 'single_page_view' ) {

	$wfp_format_style = isset( $wfp_donation_format ) && $wfp_donation_format == 'donation' ? 'single_donation' : 'crowdfunding';
}

$wfpPage_width = isset( $wfpGetMetaData->donation->page_width ) ? $wfpGetMetaData->donation->page_width : 0;

$wfp_hide_author = empty( $wfpGetMetaData->form_settings->campaign_author->enable ) ? false : ( $wfpGetMetaData->form_settings->campaign_author->enable == 'Yes' );

if ( $wfpDonationTypeData == 'donation' ) {

	$wfp_atts = array(); // as this is a single page, so it will not have any short-code config. - AR[20200116]


	if ( $wfpShowInModal == 'Yes' ) {

		if ( $wfp_form_styles == 'all_fields' ) {

			require \WFP_Fundraising::plugin_dir() . 'views/public/donation/donation-display-form-sm-all.php';

		} else {

			// Only_button

			require \WFP_Fundraising::plugin_dir() . 'views/public/donation/donation-display-form-sm-btn.php';
		}

		get_footer();

		return;
	}

	require \WFP_Fundraising::plugin_dir() . 'views/public/donation/donation-display-form-sn.php';

	get_footer();

	return;
}

/*
 * Below code is for crowd-funding type....
 */

?>

<?php do_action( 'wfp_campaign_content_before' ); ?>

	<div class="xs-wfp-crowd" style="<?php echo esc_attr( $wfpPage_width <= 0 ? '' : 'max-width:' . $wfpPage_width . 'px;' ); ?>">
		<div class="wfp-view wfp-view-public">
			<section id="main-content" class="wfp-single-page" role="main">

				<div class="<?php echo ( $wfpEnableSidebar == 'Yes' ) ? 'xs-row ' : ' '; ?>">
					<div class="<?php echo ( $wfpEnableSidebar == 'Yes' ) ? 'xs-col-sm-12 xs-col-lg-8 wfp-single-page-left-section ' : ' '; ?>">
						<?php
						while ( have_posts() ) :
							the_post();
							?>
							<div class="wfp-entry-content">
								<article 
										id="post-<?php the_ID(); ?>" <?php post_class(); ?>
										wfp-data-url="<?php echo esc_url( add_query_arg( 'wpf_checkout_nonce_field', wp_create_nonce( 'wpf_checkout' ), $wfpUrlCheckout ) ); ?>"
										wfp-payment-type="<?php echo esc_html( $wfpPaymentType ); ?>">
									<div class="wfp_wraper_con">
										<?php include __DIR__ . '/include/content-header.php'; ?>
									</div>
								</article>
							</div>
						<?php endwhile; ?>
					</div>
					<?php if ( $wfpEnableSidebar == 'Yes' ) : ?>
						<div class="xs-col-sm-12 xs-col-lg-4 wfp-single-page-sidebar-section ">
							<?php do_action( 'wfp_single_sidebar_before' ); ?>
							<?php get_sidebar(); ?>
							<?php do_action( 'wfp_single_sidebar_after' ); ?>
						</div>
					<?php endif; ?>
				</div>

			</section>
		</div>
	</div>

<?php do_action( 'wfp_campaign_content_after' ); ?>

<?php

// footer page design
get_footer();
