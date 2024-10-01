<?php

if ( $wfp_donation_type == \WfpFundraising\Apps\Key::WFP_DONATION_TYPE_SINGLE ) {

	$atts = array(); // any value passed from $atts need to be put in a variable above


	if ( $show_in_modal == \WfpFundraising\Apps\Key::WFP_YES ) {

		if ( $wfp_form_fields == \WfpFundraising\Apps\Key::WFP_FORM_FIELDS_ALL ) {

			require \WFP_Fundraising::plugin_dir() . 'views/public/donation/donation-display-form-sm-all.php';

		} else {

			// Only_button

			require \WFP_Fundraising::plugin_dir() . 'views/public/fundraising/single-page/include/single_modal_only_btn.php';
		}

		return;
	}

	require \WFP_Fundraising::plugin_dir() . 'views/public/donation/donation-display-form-sn.php';

	return;
}


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
$metaSetupKey     = 'wfp_setup_services_data';
$getSetUpData     = get_option( $metaSetupKey );
$setupData        = isset( $getSetUpData['services'] ) ? $getSetUpData['services'] : array();
$gateCampaignData = isset( $setupData['payment'] ) ? $setupData['payment'] : 'default';

$urlCheckout = get_site_url() . '/wfp-checkout?wfpout=true';

if ( $gateCampaignData == 'woocommerce' ) {

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
$goalStatus     = 'Yes';
$goalDataAmount = 0;
$goalMessage    = '';

$campaign_status = 'Publish';

if ( isset( $formGoalData->enable ) ) {
	global $wpdb;
	$goal_type           = isset( $formGoalData->goal_type ) ? $formGoalData->goal_type : 'terget_goal';
	$total_rasied_amount = $wpdb->get_var( $wpdb->prepare( "SELECT SUM(donate_amount) FROM {$wpdb->prefix}wdp_fundraising WHERE form_id = %d AND status = 'Active'", $post->ID ) );
	$total_rasied_count  = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(donate_id) FROM {$wpdb->prefix}wdp_fundraising WHERE form_id = %d AND status = 'Active'", $post->ID ) );

	$to_date            = gmdate( 'Y-m-d' );
	$time               = time();
	$persentange        = 0;
	$target_amount      = 0;
	$target_amount_fake = 0;
	$target_date        = gmdate( 'Y-m-d' );

	$total_rasied_amount_fake = $total_rasied_amount;
	$total_rasied_count_fake  = $total_rasied_count;

	if ( in_array(
		$goal_type,
		array(
			'terget_goal',
			'terget_goal_date',
			'campaign_never_end',
			'terget_date',
		)
	) ) {
		$target_amount      = isset( $formGoalData->terget->terget_goal->amount ) ? $formGoalData->terget->terget_goal->amount : 0;
		$target_amount_fake = isset( $formGoalData->terget->terget_goal->fake_amount ) ? $formGoalData->terget->terget_goal->fake_amount : 0;
		$target_date        = isset( $formGoalData->terget->terget_goal->date ) ? $formGoalData->terget->terget_goal->date : gmdate( 'Y-m-d' );

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

	$goalMessageEmable = isset( $formGoalData->terget->enable ) ? $formGoalData->terget->enable : 'No';
	$goalMessage       = isset( $formGoalData->terget->message ) ? $formGoalData->terget->message : '';


	update_post_meta( $post->ID, '__wfp_campaign_status', $campaign_status );
}


// if($goalStatus == 'Yes'){
// terms show
$termsContent = '';
if ( isset( $formTermsData->enable ) ) {
	$termsContent .= '<div class="xs-switch-button_wraper">
					<input type="checkbox" class="xs_donate_switch_button" name="xs-donate-terms-condition" id="xs-donate-terms-condition" value="Yes">
					<label class="xs_donate_switch_button_label small xs-round" for="xs-donate-terms-condition"></label><span class="xs-donate-terms-label">' . $formTermsData->level . '</span>
					<span class="xs-donate-terms"> ' . $formTermsData->content . ' </span>
				</div>';
}

$modalHow = isset( $formDesignData->modal_show ) ? $formDesignData->modal_show : 'No';


if ( $format_style == 'single_donation' ) {
	$modal_status                      = 'Yes';
	$form_styles                       = 'no_button';
	$formContentData->content_position = 'no_content';
}

/*
 * AR[20200114]
 *
 */
$modal_status = isset( $atts['modal'] ) ? $atts['modal'] : $modalHow;
$form_styles  = isset( $atts['form-style'] ) ? $atts['form-style'] : $formDesignData->styles;


// css code generate
$continueCOlor    = isset( $formDesignData->continue_color ) ? $formDesignData->continue_color : '#0085ba';
$submitCOlor      = isset( $formDesignData->submit_color ) ? $formDesignData->submit_color : '#0085ba';
$barProgressCOlor = isset( $formGoalData->bar_color ) ? $formGoalData->bar_color : '#324aff';


?>
<div class="wfp-view wfp-view-public">
	<div class="wfdp-donation-form <?php echo esc_attr( $customClass ); ?>" id="<?php echo esc_attr( $customIdData ); ?>">
		<form method="post"
			  class="wfdp-donationForm ft5"
			  id="wfdp-donationForm-<?php echo esc_attr( $post->ID ); ?>"
			  data-wfp-id="<?php echo esc_attr( $post->ID ); ?>"
			  data-wfp-payment_type="<?php echo esc_attr( $gateCampaignData ); ?>"
			  wfp-data-url="<?php echo esc_url( $urlCheckout ); ?>">

			<?php
			wp_nonce_field( 'wpf_checkout_nonce_field', 'wpf_checkout' );
			// include files
			if ( $modal_status == 'No' ) {
				echo "<div class='xs-modal-body wfp-donation-form-wraper'>";
				include __DIR__ . '/include/doantion-form-include.php';
				echo '</div>';
			}


			// button section
			if ( $form_styles == 'only_button' ) {
				if ( $modal_status == 'Yes' ) :
					?>
					<div class="wfdp-donation-input-form">
						<button type="button" class="xs-btn btn-special submit-btn" name="submit-form-donation"
								data-type="modal-trigger"
								data-target="xs-donate-modal-popup"> <?php echo esc_html( $formDesignData->continue_button ? $formDesignData->continue_button : __( 'Continue', 'wp-fundraising' ) ); ?>
						</button>
					</div>
					<?php
				else :
					?>
					<div class="wfdp-donation-input-form wfdp-donation-continue-btn  <?php echo esc_attr( $enableDisplayField ); ?> xs-donate-visible">
						<button type="button" class="xs-btn btn-special submit-btn"
								onclick="xs_show_hide_donate_font('.xs-show-div-only-button__<?php echo esc_attr( $post->ID ); ?>');"> <?php echo esc_html( $formDesignData->continue_button ? $formDesignData->continue_button : __( 'Continue', 'wp-fundraising' ) ); ?>
						</button>
					</div>

					<div class="wfp-donate-form-footer <?php echo esc_attr( $enableDisplayField ); ?>">
						<?php
						if ( isset( $formTermsData->enable ) && $formTermsData->content_position == 'before-submit-button' ) {
							?>
							<div class="xs-donate-display-amount">
								<?php echo wp_kses( $termsContent, \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
							</div>
						<?php } ?>


						<div class="wfdp-donation-input-form  <?php echo esc_attr( $enableDisplayField ); ?> ">
							<?php
							if ( $campaign_status == 'Ends' ) {
								echo wp_kses( '<p class="xs-alert xs-alert-success">' . $goalMessage . '</p>', \WfpFundraising\Utilities\Utils::get_kses_array() );
							} else {
								?>
								<button type="submit" class="xs-btn btn-special submit-btn"
										name="submit-form-donation"> <?php echo esc_html( $formDesignData->submit_button ? $formDesignData->submit_button : 'Donate Now' ); ?>
								</button>
							<?php } ?>
						</div>

						<?php
						if ( isset( $formTermsData->enable ) && $formTermsData->content_position == 'after-submit-button' ) {
							?>
							<div class="xs-donate-display-amount <?php echo esc_attr( $enableDisplayField ); ?>">
								<?php echo wp_kses( $termsContent, \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
							</div>
						<?php } ?>

					</div>

					<?php
				endif;

			} elseif ( $form_styles == 'all_fields' ) {
				?>

				<div class="wfp-donate-form-footer">

					<?php
					if ( isset( $formTermsData->enable ) && $formTermsData->content_position == 'before-submit-button' ) {
						?>
						<div class="xs-donate-display-amount xs-radio_style <?php echo esc_attr( $enableDisplayField ); ?>">
							<?php echo wp_kses( $termsContent, \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
						</div>
					<?php } ?>
					<div class="wfdp-donation-input-form">
						<?php
						if ( $campaign_status == 'Ends' ) {
							echo wp_kses( '<p class="xs-alert xs-alert-success">' . $goalMessage . '</p>', \WfpFundraising\Utilities\Utils::get_kses_array() );
						} else {
							?>
							<button type="submit" class="xs-btn btn-special submit-btn"
									name="submit-form-donation"> <?php echo esc_html( $formDesignData->submit_button ? $formDesignData->submit_button : 'Donate Now' ); ?>
							</button>
						<?php } ?>
					</div>
					<?php
					if ( isset( $formTermsData->enable ) && $formTermsData->content_position == 'after-submit-button' ) {
						?>
						<div class="xs-donate-display-amount xs-radio_style <?php echo esc_attr( $enableDisplayField ); ?>">
							<?php echo wp_kses( $termsContent, \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
						</div>
					<?php } ?>

				</div>

				<?php
			}


			// So if modal status is on then this needs to be printed out.
			if ( $modal_status == 'Yes' ) :
				?>
				<div class="xs-modal-dialog wfp-donate-modal-popup" id="xs-donate-modal-popup">
					<div class="wfp-donate-modal-popup-wraper">
						<div class="wfp-modal-content">
							<div class="xs-modal-header">
								<h4 class="xs-modal-header--title"><?php echo esc_html( $post->post_title ); ?></h4>
								<button type="button" class="xs-btn danger xs-modal-header--btn-close"
										data-modal-dismiss="modal"><i
											class="wfpf wfpf-close-outline xs-modal-header--btn-close__icon"></i>
								</button>
							</div>
							<div class="xs-modal-body wfp-donation-form-wraper">
								<?php
								include __DIR__ . '/include/doantion-form-include.php';
								?>
							</div>
							<div class="wfp-donate-form-footer">
								<?php
								if ( isset( $formTermsData->enable ) && $formTermsData->content_position == 'before-submit-button' ) {
									?>
									<div class="xs-donate-display-amount xs-radio_style <?php echo esc_attr( $enableDisplayField ); ?> ">
										<?php echo wp_kses( $termsContent, \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
									</div>
									<?php
								}
								if ( $campaign_status == 'Ends' ) {
									echo wp_kses( '<p class="xs-alert xs-alert-success">' . $goalMessage . '</p>', \WfpFundraising\Utilities\Utils::get_kses_array() );
								} else {
									?>
									<button type="submit" name="submit-form-donation"
											class="xs-btn btn-special submit-btn"><?php echo esc_html( $formDesignData->submit_button ? $formDesignData->submit_button : __( 'Donate Now', 'wp-fundraising' ) ); ?></button>
									<?php
								}
								if ( isset( $formTermsData->enable ) && $formTermsData->content_position == 'after-submit-button' ) {
									?>
									<div class="xs-donate-display-amount xs-radio_style <?php echo esc_attr( $enableDisplayField ); ?>">
										<?php echo wp_kses( $termsContent, \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
				<div class="xs-backdrop wfp-modal-backdrop"></div>

			<?php endif; ?>

		</form>
	</div>
</div>

<script type='text/javascript'>
	xs_donate_amount_set(<?php echo esc_html( $defaultData ); ?>,<?php echo esc_html( $post->ID ); ?>);
</script>
