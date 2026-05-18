<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

$wfpEnableFeatured = isset( $wfpFormSetting->featured->enable ) ? $wfpFormSetting->featured->enable : 'No';

$wfp_gallery_display = '';
$wfp_gallery_array   = explode( ',', get_post_meta( $post->ID, 'wfp_portfolio_gallery', true ) );

if ( is_array( $wfp_gallery_array ) && sizeof( $wfp_gallery_array ) ) {
	$wfp_gallery_display .= '<ul class="wfp-portfolio-gallery">';

	foreach ( $wfp_gallery_array as $gallery_item ) {
		$wfp_gallery_display .= '<li><a class="xs_popup_gallery" href="' . wp_get_attachment_url( $gallery_item ) . '"><img id="portfolio-item-' . $gallery_item . '" src="' . wp_get_attachment_thumb_url( $gallery_item ) . '"></a></li>';
	}
	$wfp_gallery_display .= '</ul>';
}

// $wfpEnableFeatured  : No --> Do not hide | Yes --> hide it
$wfpHideFeatured = $wfpEnableFeatured;

if ( $wfpHideFeatured == 'No' ) : ?>
	<div class="wfp-entry-thumbnail post-media ">
		<?php do_action( 'wfp_single_thumbnil_before' ); ?>
		<div class="wfp-post-image">
			<?php

			echo get_the_post_thumbnail(
				$post,
				'post-thumbnail',
				array(
					'class'        => 'wfp-feature wfp-full-image',
					'title'        => 'Feature image',
					'from_sh_code' => 'Yes',
				)
			);

			?>
		</div>
		<?php

		do_action( 'wfp_single_thumbnil_after' );

		if ( apply_filters( 'wfp_single_gallery_hide', true ) ) :
			if ( is_array( $wfp_gallery_array ) && ! empty( $wfp_gallery_array ) ) {
				echo wp_kses( '<div class="wfp-post-gallery">' . $wfp_gallery_display . '</div>', \WfpFundraising\Utilities\Utils::get_kses_array() );
			}
		endif;
		?>
	</div>
	<?php

endif;
