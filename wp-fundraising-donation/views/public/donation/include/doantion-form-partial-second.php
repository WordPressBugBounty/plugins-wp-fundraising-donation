<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

$wfp_feature = new \WfpFundraising\Apps\Featured( false );

$wfp_title_enable    = isset( $wfp_atts['title'] ) ? $wfp_atts['title'] : 'Yes';
$wfp_featured_enable = isset( $wfp_atts['featured'] ) ? $wfp_atts['featured'] : 'Yes';
$wfp_categori_enable = isset( $wfp_atts['category'] ) ? $wfp_atts['category'] : 'Yes';
$wfp_goal_enable     = isset( $wfp_atts['goal'] ) ? $wfp_atts['goal'] : 'Yes';
?>


<div class="wfdp-donation-message" ></div>

<?php
// before content data
if ( isset( $wfpFormContentData->enable ) && $wfpFormContentData->content_position == 'before-form' ) {
	?>
<div class="wfdp-donation-content-data before-form">
	<?php echo wp_kses( $wfpFormContentData->content, \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
</div>
	<?php
}

// goal data show
if ( $wfp_goal_enable == 'Yes' ) :
	include __DIR__ . '/content/goal-content.php';
endif;


$wfpEnableDisplayField = ( $wfp_form_styles == 'only_button' && $wfp_modal_status == 'No' ) ? 'xs-show-div-only-button__' . $post->ID . ' xs-donate-hidden' : '';


if ( $wfpGateCampaignData == 'default' ) {
	// addition al filed content
	include __DIR__ . '/content/filed-content.php';
	// payment content
	include __DIR__ . '/content/payment-content.php';
}


if ( isset( $wfpFormContentData->enable ) && $wfpFormContentData->content_position == 'after-form' ) {
	?>

	<div class="wfdp-donation-content-data before-form">
		<?php echo wp_kses( $wfpFormContentData->content, \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
	</div>
	<?php
}

