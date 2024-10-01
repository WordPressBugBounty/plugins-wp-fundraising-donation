<?php
/*
 * Template Name: WP Fundrasing Single page Template
 * Template Post Type: wp-fundrasing
 */
get_header();


$postId = get_the_ID();

// setting data
$enableSidebar = isset( $formSetting->sidebar->enable ) ? $formSetting->sidebar->enable : 'No';
$enableSidebar = apply_filters( 'wfp_single_sidebar_disable', $enableSidebar );

$enableFeatured      = isset( $formSetting->featured->enable ) ? $formSetting->featured->enable : 'No';
$enableSingleTitle   = isset( $formSetting->single_title->enable ) ? $formSetting->single_title->enable : 'No';
$enableSingleExcerpt = isset( $formSetting->single_excerpt->enable ) ? $formSetting->single_excerpt->enable : 'No';
$enableSingleExcerpt = apply_filters( 'wfp_single_excerpt_hide', $enableSingleExcerpt );

$enableSingleContent = isset( $formSetting->single_content->enable ) ? $formSetting->single_content->enable : 'No';
$enableSingleContent = apply_filters( 'wfp_single_content_decription_hide', $enableSingleContent );

$enableSingleReview = isset( $formSetting->single_review->enable ) ? $formSetting->single_review->enable : 'No';
$enableSingleReview = apply_filters( 'wfp_single_content_review_hide', $enableSingleReview );

$enableSingleUpdates = isset( $formSetting->single_updates->enable ) ? $formSetting->single_updates->enable : 'No';
$enableSingleUpdates = apply_filters( 'wfp_single_content_updates_hide', $enableSingleUpdates );

$enableSingleRecents = isset( $formSetting->single_recents->enable ) ? $formSetting->single_recents->enable : 'No';
$enableSingleRecents = apply_filters( 'wfp_single_content_recent_hide', $enableSingleRecents );

$enableSingleContributor = isset( $formSetting->contributor->enable ) ? $formSetting->contributor->enable : 'No';
$enableSingleContributor = apply_filters( 'wfp_single_content_contributor_hide', $enableSingleContributor );

// general option data
$getMetaGeneralOp = get_option( \WfpFundraising\Apps\Settings::OK_GENERAL_DATA );

$getMetaGeneral     = isset( $getMetaGeneralOp['options'] ) ? $getMetaGeneralOp['options'] : array();
$getMetaGeneralPage = \WfpFundraising\Apps\Settings::instance()->get_mapped_page_slug( $getMetaGeneralOp );
$checkoutPage       = \WfpFundraising\Apps\Settings::instance()->get_mapped_checkout_page_slug( $getMetaGeneralOp );

$toFixedPoint = empty( $getMetaGeneral['currency']['number_decimal'] ) ? 2 : intval( $getMetaGeneral['currency']['number_decimal'] );
$urlCheckout  = get_site_url() . '/' . $checkoutPage . '?wfpout=true';

$metaSetupKey = 'wfp_setup_services_data';
$getSetUpData = get_option( $metaSetupKey );
$paymentType  = isset( $getSetUpData['services']['payment'] ) ? $getSetUpData['services']['payment'] : 'default';

$cur_symbol = \WfpFundraising\Apps\Settings::instance()->get_curr_symbol( $getMetaGeneral, $paymentType );

if ( $paymentType == 'woocommerce' ) {

	/**
	 * Making it as virtual so there is no shipping cost on it.
	 */
	update_post_meta( $postId, '_virtual', 'yes' );

	$cart_url = wc_get_cart_url();

	$urlCheckout = $cart_url . '?wfpout=true&virtual=yes';
}

$metaKey      = 'wfp_form_options_meta_data';
$metaDataJson = get_post_meta( $postId, $metaKey, false );
$getMetaData  = json_decode( json_encode( end( $metaDataJson ), JSON_UNESCAPED_UNICODE ) );

$formDesignData = isset( $getMetaData->form_design ) ? $getMetaData->form_design : (object) array(
	'styles'          => 'all_fields',
	'continue_button' => 'Continue',
	'submit_button'   => 'Donate Now',
);

$defaultData      = 0;
$showInModal      = isset( $formDesignData->modal_show ) ? $formDesignData->modal_show : 'No';
$donationTypeData = empty( $getMetaData->donation->format ) ? 'donation' : $getMetaData->donation->format;
$amount_limit     = property_exists( $getMetaData->donation, 'set_limit' ) ? $getMetaData->donation->set_limit : array();


$optinsKey      = 'wfp_payment_options_data';
$getOptionsData = get_option( $optinsKey );
$gateWaysData   = isset( $getOptionsData['gateways'] ) ? $getOptionsData['gateways'] : array();
$form_styles    = $formDesignData->styles;

if ( ! empty( $fromWhere ) && $fromWhere == 'single_page_view' ) {

	$format_style = isset( $donation_format ) && $donation_format == 'donation' ? 'single_donation' : 'crowdfunding';
}

$page_width = isset( $getMetaData->donation->page_width ) ? $getMetaData->donation->page_width : 0;

$hide_author = empty( $getMetaData->form_settings->campaign_author->enable ) ? false : ( $getMetaData->form_settings->campaign_author->enable == 'Yes' );

if ( $donationTypeData == 'donation' ) {

	$atts = array(); // as this is a single page, so it will not have any short-code config. - AR[20200116]


	if ( $showInModal == 'Yes' ) {

		if ( $form_styles == 'all_fields' ) {

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

	<div class="xs-wfp-crowd" style="<?php echo esc_attr( $page_width <= 0 ? '' : 'max-width:' . $page_width . 'px;' ); ?>">
		<div class="wfp-view wfp-view-public">
			<section id="main-content" class="wfp-single-page" role="main">

				<div class="<?php echo ( $enableSidebar == 'Yes' ) ? 'xs-row ' : ' '; ?>">
					<div class="<?php echo ( $enableSidebar == 'Yes' ) ? 'xs-col-sm-12 xs-col-lg-8 wfp-single-page-left-section ' : ' '; ?>">
						<?php
						while ( have_posts() ) :
							the_post();
							?>
							<div class="wfp-entry-content">
								<article 
										id="post-<?php the_ID(); ?>" <?php post_class(); ?>
										wfp-data-url="<?php echo esc_url( add_query_arg( 'wpf_checkout_nonce_field', wp_create_nonce( 'wpf_checkout' ), $urlCheckout ) ); ?>"
										wfp-payment-type="<?php echo esc_html( $paymentType ); ?>">
									<div class="wfp_wraper_con">
										<?php include __DIR__ . '/include/content-header.php'; ?>
									</div>
								</article>
							</div>
						<?php endwhile; ?>
					</div>
					<?php if ( $enableSidebar == 'Yes' ) : ?>
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
