<?php
$feature = new \WfpFundraising\Apps\Featured( false );

$title_enable    = isset( $atts['title'] ) ? $atts['title'] : 'Yes';
$featured_enable = isset( $atts['featured'] ) ? $atts['featured'] : 'Yes';
$categori_enable = isset( $atts['category'] ) ? $atts['category'] : 'Yes';
$goal_enable     = isset( $atts['goal'] ) ? $atts['goal'] : 'Yes';
?>


<div class="wfdp-donation-message" ></div>

<?php
// before content data
if ( isset( $formContentData->enable ) && $formContentData->content_position == 'before-form' ) {
	?>
<div class="wfdp-donation-content-data before-form">
	<?php echo wp_kses( $formContentData->content, \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
</div>
	<?php
}

// goal data show
if ( $goal_enable == 'Yes' ) :
	include __DIR__ . '/content/goal-content.php';
endif;


$enableDisplayField = ( $form_styles == 'only_button' && $modal_status == 'No' ) ? 'xs-show-div-only-button__' . $post->ID . ' xs-donate-hidden' : '';


if ( $gateCampaignData == 'default' ) {
	// addition al filed content
	include __DIR__ . '/content/filed-content.php';
	// payment content
	include __DIR__ . '/content/payment-content.php';
}


if ( isset( $formContentData->enable ) && $formContentData->content_position == 'after-form' ) {
	?>

	<div class="wfdp-donation-content-data before-form">
		<?php echo wp_kses( $formContentData->content, \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
	</div>
	<?php
}

