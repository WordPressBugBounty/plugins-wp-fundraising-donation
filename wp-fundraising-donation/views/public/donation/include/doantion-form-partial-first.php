<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

$wfp_feature = new \WfpFundraising\Apps\Featured( false );

$wfp_title_enable    = isset( $wfp_atts['title'] ) ? $wfp_atts['title'] : 'Yes';
$wfp_featured_enable = isset( $wfp_atts['featured'] ) ? $wfp_atts['featured'] : 'Yes';
$wfp_categori_enable = isset( $wfp_atts['category'] ) ? $wfp_atts['category'] : 'Yes';
$wfp_goal_enable     = isset( $wfp_atts['goal'] ) ? $wfp_atts['goal'] : 'Yes';


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
					$wfpOutputCate .= '<a class="wfp-header-cat--link" href="' . esc_url( get_category_link( $category->term_id ) ) . '" alt="' . esc_attr( sprintf( __( 'View all posts in %s', 'wp-fundraising-donation' ), $category->name ) ) . '">' . esc_html( $category->name ) . '</a>';

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

		<hr class="wfp-normal-separator" style="margin: 20px 0;"/>


	</div>
	<div class="wfdp-donation-message"></div>

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


