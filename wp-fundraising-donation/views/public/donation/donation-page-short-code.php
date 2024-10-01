<?php

/*
 * AR[20200116]
 * In this file we must have $atts variable defined and not empty.
 */

$formDesignData = isset( $getMetaData->form_design ) ? $getMetaData->form_design : (object) array(
	'styles'          => 'all_fields',
	'continue_button' => 'Continue',
	'submit_button'   => 'Donate Now',
	'modal_show'      => 'No',
);

$fromShCode = empty( $atts['is_short_code'] ) ? false : true;
$formId     = empty( $atts['form-id'] ) ? 0 : $atts['form-id'];

$wfp_form_id = isset( $atts['form-id'] ) ? intval( $atts['form-id'] ) : ( empty( $post->ID ) ? get_the_ID() : $post->ID );
$postId      = isset( $atts['form-id'] ) ? intval( $atts['form-id'] ) : ( empty( $post->ID ) ? get_the_ID() : $post->ID );

$donationTypeData = empty( $getMetaData->donation->format ) ? 'donation' : $getMetaData->donation->format;

$showInModal = ! empty( $atts['modal'] ) ? $atts['modal'] : ( empty( $formDesignData->modal_show ) ? 'No' : $formDesignData->modal_show );

$form_styles = ! empty( $atts['form-style'] ) ? $atts['form-style'] : $formDesignData->styles;

$modal_status = $showInModal;

$page_width = isset( $getMetaData->donation->page_width ) ? $getMetaData->donation->page_width : 0;

$symbols    = \WfpFundraising\Apps\Global_Settings::instance()->get_currency_symbol();
$cur_symbol = \WfpFundraising\Apps\Global_Settings::instance()->get_currency_code();


if ( $donationTypeData == 'donation' ) {

	if ( $showInModal == 'Yes' ) {

		if ( $form_styles == 'all_fields' ) {

			require \WFP_Fundraising::plugin_dir() . 'views/public/donation/donation-display-form-sm-all.php';

		} else {

			// Only_button

			require \WFP_Fundraising::plugin_dir() . 'views/public/donation/donation-display-form-sm-btn.php';
		}

		return;
	}

	require \WFP_Fundraising::plugin_dir() . 'views/public/donation/donation-display-form-sn.php';

	return;
}


/*
 * Below code is for crowd-funding type.
 *
 */
$cont = new \WfpFundraising\Apps\Content( false );

require \WFP_Fundraising::plugin_dir() . 'country-module/country-info.php';

/*currency information*/
$getMetaGeneralOp = get_option( \WfpFundraising\Apps\Settings::OK_GENERAL_DATA );
$getMetaGeneral   = isset( $getMetaGeneralOp['options'] ) ? $getMetaGeneralOp['options'] : array();

$defaultCurrencyInfo = isset( $getMetaGeneral['currency']['name'] ) ? $getMetaGeneral['currency']['name'] : 'US-USD';
$explCurr            = explode( '-', $defaultCurrencyInfo );
$currCode            = isset( $explCurr[1] ) ? $explCurr[1] : 'USD';
$countCode           = isset( $explCurr[0] ) ? $explCurr[0] : 'US';
$symbols             = isset( $countryList[ $countCode ]['currency']['symbol'] ) ? $countryList[ $countCode ]['currency']['symbol'] : '';
$symbols             = strlen( $symbols ) > 0 ? $symbols : $currCode;


$symbols = apply_filters( 'wfp_donate_amount_symbol', $symbols, $countryList, $countCode );

$defaultThou_seperator = isset( $getMetaGeneral['currency']['thou_seperator'] ) ? $getMetaGeneral['currency']['thou_seperator'] : ',';

$defaultDecimal_seperator = isset( $getMetaGeneral['currency']['decimal_seperator'] ) ? $getMetaGeneral['currency']['decimal_seperator'] : '.';

$defaultNumberDecimal = isset( $getMetaGeneral['currency']['number_decimal'] ) ? $getMetaGeneral['currency']['number_decimal'] : '2';
if ( $defaultNumberDecimal < 0 ) {
	$defaultNumberDecimal = 0;
}

$defaultUse_space = isset( $getMetaGeneral['currency']['use_space'] ) ? $getMetaGeneral['currency']['use_space'] : 'off';

/*Custom class design data*/
$customClass  = isset( $getMetaData->form_design->custom_class ) ? $getMetaData->form_design->custom_class : '';
$customIdData = isset( $getMetaData->form_design->custom_id ) ? $getMetaData->form_design->custom_id : '';

$customClass  = isset( $atts['class'] ) ? $atts['class'] : $customClass;
$customIdData = isset( $atts['id'] ) ? $atts['id'] : $customIdData;

$format_style = isset( $atts['format-style'] ) ? $atts['format-style'] : $format_style;

// payment method setup
$metaSetupKey = 'wfp_setup_services_data';
$getSetUpData = get_option( $metaSetupKey );
$setupData    = isset( $getSetUpData['services'] ) ? $getSetUpData['services'] : array();
$paymentType  = \WfpFundraising\Apps\Global_Settings::instance()->get_payment_type();

$urlCheckout = get_site_url() . '/wfp-checkout?wfpout=true';

if ( $paymentType == 'woocommerce' ) {

	$cart_url = wc_get_cart_url();

	$urlCheckout = $cart_url . '?wfpout=true&virtual=yes';
}

$defaultData = 0;

$donation_type = isset( $getMetaData->donation->type ) ? $getMetaData->donation->type : 'multi-lebel';

$fixedData = isset( $getMetaData->donation->fixed ) ? $getMetaData->donation->fixed : array();

$multiData = isset( $getMetaData->donation->multi->dimentions ) && sizeof( $getMetaData->donation->multi->dimentions ) ? $getMetaData->donation->multi->dimentions : array();

$displayData   = isset( $getMetaData->donation->display ) ? $getMetaData->donation->display : 'boxed';
$donationLimit = isset( $getMetaData->donation->set_limit ) ? $getMetaData->donation->set_limit : 'No';

// form donation data
$formDonation = isset( $getMetaData->donation ) ? $getMetaData->donation : array();

// form design data
$formDesignData = isset( $getMetaData->form_design ) ? $getMetaData->form_design : (object) array(
	'styles'          => 'all_fields',
	'continue_button' => 'Continue',
	'submit_button'   => 'Donate Now',
);

// form content data
$formContentData = isset( $getMetaData->form_content ) ? $getMetaData->form_content : (object) array(
	'enable'           => 'No',
	'content_position' => 'after-form',
);

// form goal data
$formGoalData = isset( $getMetaData->goal_setup ) ? $getMetaData->goal_setup : (object) array(
	'enable'    => 'No',
	'goal_type' => 'goal_terget_amount',
);

// form terms data
$formTermsData = isset( $getMetaData->form_terma ) ? $getMetaData->form_terma : (object) array(
	'enable'           => 'No',
	'content_position' => 'after-submit-button',
);

$add_fees = isset( $getMetaData->donation->set_add_fees ) ? $getMetaData->donation->set_add_fees : (object) array(
	'enable'      => 'No',
	'fees_amount' => 0,
);

// target goal check
$goalStatus      = 'Yes';
$campaign_status = 'Publish';
$goalDataAmount  = 0;

$goalMessageEmable = isset( $formGoalData->terget->enable ) ? $formGoalData->terget->enable : 'No';
$goalMessage       = isset( $formGoalData->terget->message ) ? $formGoalData->terget->message : '';
$goal_type         = isset( $formGoalData->goal_type ) ? $formGoalData->goal_type : 'terget_goal';

$persentange        = 0;
$target_amount      = 0;
$target_amount_fake = 0;
$target_date        = gmdate( 'Y-m-d' );
$time               = time();
$to_date            = gmdate( 'Y-m-d' );

if ( isset( $formGoalData->enable ) ) {
	global $wpdb;
	$total_rasied_amount = $wpdb->get_var( $wpdb->prepare( "SELECT SUM(donate_amount) FROM {$wpdb->prefix}wdp_fundraising WHERE form_id = %d AND status = 'Active'", $post->ID ) );
	$total_rasied_count  = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(donate_id) FROM {$wpdb->prefix}wdp_fundraising WHERE form_id = %d AND status = 'Active'", $post->ID ) );

	$total_rasied_amount_fake = $total_rasied_amount;
	$total_rasied_count_fake  = $total_rasied_count;

	if ( in_array( $goal_type, array( 'terget_goal', 'terget_goal_date', 'campaign_never_end', 'terget_date' ) ) ) {
		$target_amount      = isset( $formGoalData->terget->terget_goal->amount ) ? $formGoalData->terget->terget_goal->amount : 0;
		$target_amount_fake = isset( $formGoalData->terget->terget_goal->fake_amount ) ? $formGoalData->terget->terget_goal->fake_amount : 0;
		$target_date        = isset( $formGoalData->terget->terget_goal->date ) ? $formGoalData->terget->terget_goal->date : $target_date;

		$target_time = strtotime( $target_date );

		$total_rasied_amount_fake = $total_rasied_amount + $target_amount_fake;
		// check amount with data
		if ( $total_rasied_amount_fake >= $target_amount ) {
			$total_rasied_amount_fake = $total_rasied_amount;
		}
		if ( $target_amount > 0 ) {
			$persentange = ( $total_rasied_amount_fake * 100 ) / $target_amount;
		}

		if ( $total_rasied_amount >= $target_amount ) {
			$goalStatus = 'No';
		}
		if ( $goal_type == 'terget_goal_date' || $goal_type == 'terget_date' ) {
			if ( $time > $target_time ) {
				$goalStatus = 'No';
			}
		} elseif ( $goal_type == 'campaign_never_end' ) {
			$goalStatus = 'Yes';
		}
	}

	$campaign_status = ( $goalStatus == 'Yes' ) ? 'Publish' : 'Ends';


	update_post_meta( $post->ID, '__wfp_campaign_status', $campaign_status );
}


$termsContent = '';
if ( isset( $formTermsData->enable ) ) {
	$termsContent .= '<div class="xs-switch-button_wraper">
					<input type="checkbox" class="xs_donate_switch_button" name="xs-donate-terms-condition" id="xs-donate-terms-condition" value="Yes">
					<label class="xs_donate_switch_button_label small xs-round" for="xs-donate-terms-condition"></label><span class="xs-donate-terms-label">' . $formTermsData->level . '</span>
					<span class="xs-donate-terms"> ' . $formTermsData->content . ' </span>
				</div>';
}

$modalHow = isset( $formDesignData->modal_show ) ? $formDesignData->modal_show : 'No';

$form_styles = isset( $atts['form-style'] ) ? $atts['form-style'] : $formDesignData->styles;

$modal_status = isset( $atts['modal'] ) ? $atts['modal'] : $modalHow;

if ( $form_styles == 'all_fields' ) {
	$modal_status = 'No';
}

if ( $format_style == 'single_donation' ) {
	$modal_status                      = 'Yes';
	$form_styles                       = 'no_button';
	$formContentData->content_position = 'no_content';
}

// css code generate
$continueCOlor    = isset( $formDesignData->continue_color ) ? $formDesignData->continue_color : '#0085ba';
$submitCOlor      = isset( $formDesignData->submit_color ) ? $formDesignData->submit_color : '#0085ba';
$barProgressCOlor = isset( $formGoalData->bar_color ) ? $formGoalData->bar_color : '#324aff';


$formSetting = isset( $getMetaData->form_settings ) ? $getMetaData->form_settings : array();

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

$multiPleData = isset( $getMetaData->pledge_setup->multi->dimentions ) && sizeof( $getMetaData->pledge_setup->multi->dimentions ) ? $getMetaData->pledge_setup->multi->dimentions : array();

$postContent = $post->post_content;

$page_width = isset( $getMetaData->donation->page_width ) ? $getMetaData->donation->page_width : 0;

?>

<div class="wfp-container xs-wfp-crowd" style="<?php echo $page_width <= 0 ? '' : 'width:' . esc_attr( $page_width ) . 'px;'; ?>">
	<div class="wfp-view wfp-view-public">
		<section id="main-content" class="wfp-single-page" role="main">
			<div class="xs-container">
				<div class="">
					<div class="">
						<div class="wfp-entry-content">
							<article id="post-<?php echo esc_attr( $post->ID ); ?>"
									 class="post-<?php echo esc_attr( $post->ID ); ?> <?php echo esc_attr( join( ' ', get_post_class( '', $post->ID ) ) ); ?>"
									 wfp-data-url="<?php echo esc_url( add_query_arg( 'wpf_checkout_nonce_field', wp_create_nonce( 'wpf_checkout' ), $urlCheckout ) ); ?>"
									 wfp-payment-type="<?php echo esc_html( $paymentType ); ?>">

								<div class="wfp_wraper_con">

									<?php require __DIR__ . '/include/header-title-crowdfund-sh.php'; ?>

									<div class="xs-row">
										<div class="xs-col-sm-12 xs-col-md-6 wfp-single-item">

											<?php require __DIR__ . '/include/featured-gal-crowd-sh.php'; ?>

											<?php require __DIR__ . '/include/post-body-crowd-sh.php'; ?>

										</div>

										<div class="xs-col-sm-12 xs-col-md-6  wfp-single-item">
											<div class="wfp-goal-wraper">

												<?php

												// backers information
												require __DIR__ . '/../fundraising/single-page/include/page/backers-crowd-sh.php';

												// social share
												require __DIR__ . '/../fundraising/single-page/include/page/social.php';

												// post author info
												require __DIR__ . '/../fundraising/single-page/include/page/user-info.php';

												?>

											</div>
										</div>
									</div>

									<div class="wfp-bar-section">
										<hr class="wfp-bar-content">
									</div>

									<div class="xs-row">
										<?php

										$recentTitle = __( 'Recent Funds', 'wp-fundraising' );

										$argsTotal = array(
											'post_type'   => 'wfp-review',
											'post_parent' => $postId,
											'post_status' => 'publish',
										);

										$the_queryTotal = new \WP_Query( $argsTotal );
										$count          = $the_queryTotal->post_count;
										wp_reset_postdata();
										?>
										<div class="xs-col-lg-8 wfp-single-tabs">
											<ul class="wfp-tab" id="wfp_menu_fixed">
												<?php if ( $enableSingleContent == 'No' ) : ?>
													<li class="wfp_tab_li active"><a
																href="#wfp_tab_content_decription"><?php echo esc_html( apply_filters( 'wfp_single_content_decription', esc_html__( 'Description', 'wp-fundraising' ) ) ); ?></a>
													</li>
													<?php
												endif;
												if ( $enableSingleReview == 'No' ) :
													?>
													<li class="wfp_tab_li "><a
																href="#wfp_tab_content_review"><?php echo esc_html( apply_filters( 'wfp_single_content_review', esc_html__( 'Reviews', 'wp-fundraising' ) ) ); ?>
															(<?php echo esc_html( $count ); ?>)</a></li>
													<?php
												endif;
												if ( $enableSingleUpdates == 'No' ) :
													?>
													<li class="wfp_tab_li "><a
																href="#wfp_tab_content_updates"><?php echo esc_html( apply_filters( 'wfp_single_content_updates', esc_html__( 'Updates', 'wp-fundraising' ) ) ); ?></a>
													</li>
													<?php
												endif;
												if ( $enableSingleRecents == 'No' ) :
													?>
													<li class="wfp_tab_li "><a
																href="#wfp_tab_content_recent"><?php echo esc_html( apply_filters( 'wfp_single_content_recent', esc_html( $recentTitle ) ) ); ?></a>
													</li>
												<?php endif; ?>
											</ul>

											<div class="wfp-tab-content-wraper">
												<?php if ( $enableSingleContent == 'No' ) : ?>
													<div class="wfp-tab-content wfp-tab-div-disable active"
														 id="wfp_tab_content_decription">
														<div class="wfp-post-description">
															<?php

															do_action( 'wfp_single_content_before' );

															echo wp_kses( $postContent, \WfpFundraising\Utilities\Utils::get_kses_array() );

															do_action( 'wfp_single_content_after' );

															?>
														</div>
													</div>
													<!-- Article content -->
													<?php
												endif;
												if ( $enableSingleReview == 'No' ) :
													?>
													<div class="wfp-tab-content wfp-tab-div-disable "
														 id="wfp_tab_content_review">
														<?php include __DIR__ . '/../fundraising/single-page/include/page/review.php'; ?>
													</div>
													<?php
												endif;
												if ( $enableSingleUpdates == 'No' ) :
													?>
													<div class="wfp-tab-content wfp-tab-div-disable " id="wfp_tab_content_updates">
														<?php include __DIR__ . '/../fundraising/single-page/include/page/updates.php'; ?>
													</div>
													<?php
												endif;
												if ( $enableSingleRecents == 'No' ) :
													?>
													<div class="wfp-tab-content wfp-tab-div-disable "
														 id="wfp_tab_content_recent">
														<?php include __DIR__ . '/../fundraising/single-page/include/page/recent.php'; ?>
													</div>
												<?php endif; ?>
											</div>
										</div>

										<div class="xs-col-lg-4 wfp-single-pledges">
											<?php

											if ( \WfpFundraising\Apps\Form_Settings::instance( $wfp_form_id )->is_pledge_enabled() ) {
												include __DIR__ . '/../fundraising/single-page/include/page/pledge.php';
											}
											?>
										</div>
									</div>
								</div>
							</article>
						</div>

					</div>
				</div>

			</div>
		</section>
	</div>

	<script type='text/javascript'>
		xs_donate_amount_set(<?php echo esc_html( $defaultData ); ?>,<?php echo esc_html( $post->ID ); ?>);
	</script>

</div>
