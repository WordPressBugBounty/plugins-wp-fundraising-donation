<?php
// Get gallery
$gallery_display = '';
$gallery_array   = explode( ',', get_post_meta( get_the_ID(), 'wfp_portfolio_gallery', true ) );

if ( is_array( $gallery_array ) && sizeof( $gallery_array ) ) {
	$gallery_display .= '<ul class="wfp-portfolio-gallery">';

	foreach ( $gallery_array as $gallery_item ) {
		$gallery_display .= '<li><a class="xs_popup_gallery" data-fancybox href="' . wp_get_attachment_url( $gallery_item ) . '"><img id="portfolio-item-' . $gallery_item . '" src="' . wp_get_attachment_thumb_url( $gallery_item ) . '"></a></li>';
	}
	$gallery_display .= '</ul>';
}

$categories = get_the_terms( get_the_ID(), 'wfp-categories' );


/*
 * This page is supposed to be called only for crowd-funding type
 */

if ( $donation_format == 'crowdfunding' ) {

	require __DIR__ . '/content-header-crowdfund.php';
}
