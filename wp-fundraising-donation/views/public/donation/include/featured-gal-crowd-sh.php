<?php

$enableFeatured = isset( $formSetting->featured->enable ) ? $formSetting->featured->enable : 'No';

$gallery_display = '';
$gallery_array   = explode( ',', get_post_meta( $post->ID, 'wfp_portfolio_gallery', true ) );

if ( is_array( $gallery_array ) && sizeof( $gallery_array ) ) {
	$gallery_display .= '<ul class="wfp-portfolio-gallery">';

	foreach ( $gallery_array as $gallery_item ) {
		$gallery_display .= '<li><a class="xs_popup_gallery" href="' . wp_get_attachment_url( $gallery_item ) . '"><img id="portfolio-item-' . $gallery_item . '" src="' . wp_get_attachment_thumb_url( $gallery_item ) . '"></a></li>';
	}
	$gallery_display .= '</ul>';
}

// $enableFeatured  : No --> Do not hide | Yes --> hide it
$hideFeatured = $enableFeatured;

if ( $hideFeatured == 'No' ) : ?>
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
			if ( is_array( $gallery_array ) && ! empty( $gallery_array ) ) {
				echo wp_kses( '<div class="wfp-post-gallery">' . $gallery_display . '</div>', \WfpFundraising\Utilities\Utils::get_kses_array() );
			}
		endif;
		?>
	</div>
	<?php

endif;
