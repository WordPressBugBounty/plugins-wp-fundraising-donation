<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

$wfp_feature = new \WfpFundraising\Apps\Featured( false );

$wfp_title_enable    = isset( $wfp_atts['title'] ) ? $wfp_atts['title'] : 'Yes';
$wfp_featured_enable = isset( $wfp_atts['featured'] ) ? $wfp_atts['featured'] : 'Yes';
$wfp_categori_enable = isset( $wfp_atts['category'] ) ? $wfp_atts['category'] : 'Yes';
$wfp_goal_enable     = isset( $wfp_atts['goal'] ) ? $wfp_atts['goal'] : 'Yes';

// Get gallery
$wfp_gallery_display = '';
$wfp_gallery_array   = explode( ',', get_post_meta( $post->ID, 'wfp_portfolio_gallery', true ) );

if ( is_array( $wfp_gallery_array ) && sizeof( $wfp_gallery_array ) ) {
	$wfp_gallery_display .= '<ul class="wfp-portfolio-gallery">';

	foreach ( $wfp_gallery_array as $gallery_item ) {
		$wfp_gallery_display .= '<li><a class="xs_popup_gallery" href="' . wp_get_attachment_url( $gallery_item ) . '"><img id="portfolio-item-' . $gallery_item . '" src="' . wp_get_attachment_thumb_url( $gallery_item ) . '"></a></li>';
	}
	$wfp_gallery_display .= '</ul>';
}

$wfp_categories = get_the_terms( $post->ID, 'wfp-categories' );

?>

<div class="wfp-modal-header">
	<?php
	if ( $wfp_categori_enable == 'Yes' ) :
		if ( ! empty( $wfp_categories ) ) {
			$wfp_separator  = "<span class='wfp-header-cat--separator'>-</span>";
			$wfpOutputCate = '';

			$wfp_array_keys = array_keys( $wfp_categories );
			$wfp_last_key   = end( $wfp_array_keys );
			foreach ( $wfp_categories as $wfp_key => $category ) {
					// translators: %s: category name.

				if ( $wfp_key !== $wfp_last_key ) {
					$wfpOutputCate .= $wfp_separator;
				}
			}

			?>
		<div class="wfp-header-cat"><?php echo wp_kses( $wfpOutputCate, \WfpFundraising\Utilities\Utils::get_kses_array() ); ?></div> 
		
			<?php
		}
	endif;

	if ( $wfp_featured_enable == 'Yes' ) :
		?>
		<!-- Before Content-->
		<?php do_action( 'wfp_single_thumbnil_before' ); ?>

		<?php if ( $wfp_feature->has_featured_video( $post->ID ) ) { ?>
			<div class="wfp-feature-video">
				<?php echo wp_kses( $wfp_feature->wfp_featured_video_iframe( $post->ID ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
			</div>
		<?php } else { ?>
			<div class="wfp-post-image">
				<?php echo get_the_post_thumbnail( $post->ID ); ?>
			</div>
		<?php } ?>

		<!-- After Content-->	
		<?php

		do_action( 'wfp_single_thumbnil_after' );

		if ( apply_filters( 'wfp_single_gallery_hide', true ) ) :
			if ( ! empty( $wfp_gallery_array ) ) {
				echo wp_kses( '<div class="wfp-post-gallery">' . $wfp_gallery_display . '</div>', \WfpFundraising\Utilities\Utils::get_kses_array() );
			}
		endif;
	endif;

	if ( $wfp_title_enable == 'Yes' && $wfp_modal_status == 'No' ) :
		?>
		<h4 class="wfp-post-title"><?php echo esc_html( $post->post_title ); ?></h4>

		<?php
	endif;

	if ( $wfp_modal_status == 'Yes' ) {
		?>

		<h4 class="xs-modal-header--title"><?php echo esc_html( $post->post_title ); ?></h4>

		<?php
	}
	?>


	<div class="wfp-excerpt-section">
		<?php do_action( 'wfp_single_excerpt_before' ); ?>
		<div class="wfp-post-excerpt"><?php the_excerpt(); ?></div>
		<?php do_action( 'wfp_single_excerpt_after' ); ?>
	</div>

	<hr class="wfp-normal-separator" style="margin: 20px 0;" />



</div>
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


// amount content
require __DIR__ . '/content/amount-content.php';


$wfpEnableDisplayField = ( $wfp_form_styles == 'only_button' && $wfp_modal_status == 'No' ) ? 'xs-show-div-only-button__' . $post->ID . ' xs-donate-hidden' : '';

// addition fees
require __DIR__ . '/content/fees-content.php';

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

