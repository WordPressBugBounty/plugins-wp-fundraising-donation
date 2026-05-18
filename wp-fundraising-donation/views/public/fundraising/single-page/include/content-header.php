<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

// Get gallery
$wfp_gallery_display = '';
$wfp_gallery_array   = explode( ',', get_post_meta( get_the_ID(), 'wfp_portfolio_gallery', true ) );

if ( is_array( $wfp_gallery_array ) && sizeof( $wfp_gallery_array ) ) {
	$wfp_gallery_display .= '<ul class="wfp-portfolio-gallery">';

	foreach ( $wfp_gallery_array as $gallery_item ) {
		$wfp_gallery_display .= '<li><a class="xs_popup_gallery" data-fancybox href="' . wp_get_attachment_url( $gallery_item ) . '"><img id="portfolio-item-' . $gallery_item . '" src="' . wp_get_attachment_thumb_url( $gallery_item ) . '"></a></li>';
	}
	$wfp_gallery_display .= '</ul>';
}

$wfp_categories = get_the_terms( get_the_ID(), 'wfp-categories' );


/*
 * This page is supposed to be called only for crowd-funding type
 */

if ( $wfp_donation_format == 'crowdfunding' ) {

	require __DIR__ . '/content-header-crowdfund.php';
}
