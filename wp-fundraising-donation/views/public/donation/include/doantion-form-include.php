<?php
$feature = new \WfpFundraising\Apps\Featured( false );

$title_enable    = isset( $atts['title'] ) ? $atts['title'] : 'Yes';
$featured_enable = isset( $atts['featured'] ) ? $atts['featured'] : 'Yes';
$categori_enable = isset( $atts['category'] ) ? $atts['category'] : 'Yes';
$goal_enable     = isset( $atts['goal'] ) ? $atts['goal'] : 'Yes';

// Get gallery
$gallery_array   = explode( ',', get_post_meta( $post->ID, 'wfp_portfolio_gallery', true ) );
$gallery_display = '';

if ( ! empty( $gallery_array ) && is_array( $gallery_array ) ) {

	$gallery_display .= '<ul class="wfp-portfolio-gallery">';

	foreach ( $gallery_array as $gallery_item ) {
		$gallery_display .= '<li><a class="xs_popup_gallery" href="' . wp_get_attachment_url( $gallery_item ) . '"><img id="portfolio-item-' . $gallery_item . '" src="' . wp_get_attachment_thumb_url( $gallery_item ) . '"></a></li>';
	}

	$gallery_display .= '</ul>';
}

$categories = get_the_terms( $post->ID, 'wfp-categories' );

?>

	<div class="wfp-modal-header">
		<?php
		if ( $categori_enable == 'Yes' ) :
			if ( ! empty( $categories ) ) {
				$separator  = "<span class='wfp-header-cat--separator'>-</span>";
				$outputCate = '';

				$array_keys = array_keys( $categories );
				$last_key   = end( $array_keys );

				foreach ( $categories as $key => $category ) {
					$outputCate .= '<a class="wfp-header-cat--link" href="' . esc_url( get_category_link( $category->term_id ) ) . '" alt="' . esc_attr( sprintf( __( 'View all posts in %s', 'wp-fundraising' ), $category->name ) ) . '">' . esc_html( $category->name ) . '</a>';

					if ( $key !== $last_key ) {
						$outputCate .= $separator;
					}
				}

				?>
				<div class="wfp-header-cat"><?php echo wp_kses( $outputCate, \WfpFundraising\Utilities\Utils::get_kses_array() ); ?></div>

				<?php
			}
		endif;

		if ( $featured_enable == 'Yes' ) :
			?>
			<!-- Before Content-->
			<?php do_action( 'wfp_single_thumbnil_before' ); ?>

			<?php if ( $feature->has_featured_video( $post->ID ) ) { ?>
				<div class="wfp-feature-video">
					<?php echo wp_kses( $feature->wfp_featured_video_iframe( $post->ID ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
				</div>
			<?php } else { ?>
				<div class="wfp-post-image">
					<?php echo get_the_post_thumbnail( $post->ID ); ?>
				</div>
			<?php } ?>

			<!-- After Content-->
			<?php

			do_action( 'wfp_single_thumbnil_after' );

		endif;

		if ( $title_enable == 'Yes' && $modal_status == 'No' ) :
			?>
			<h4 class="wfp-post-title"><?php echo esc_html( $post->post_title ); ?></h4>

			<?php
		endif;

		if ( $modal_status == 'Yes' ) {
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

		<hr class="wfp-normal-separator" style="margin: 20px 0;"/>


	</div>
	<div class="wfdp-donation-message"></div>

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


// amount content
require __DIR__ . '/content/amount-content.php';


$enableDisplayField = ( $form_styles == 'only_button' && $modal_status == 'No' ) ? 'xs-show-div-only-button__' . $post->ID . ' xs-donate-hidden' : '';

// addition fees
require __DIR__ . '/content/fees-content.php';

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


