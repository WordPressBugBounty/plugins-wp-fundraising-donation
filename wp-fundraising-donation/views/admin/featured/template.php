<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'the_post_video_thumbnail' ) ) {
	function the_post_video_thumbnail( $wfp_size = 'post-thumbnail', $wfp_attr = '' ) {
		echo wp_kses( get_the_post_video_thumbnail( null, $wfp_size, $wfp_attr ), \WfpFundraising\Utilities\Utils::get_kses_array() );
	}
}

if ( ! function_exists( 'get_the_post_video_thumbnail' ) ) {
	function get_the_post_video_thumbnail( $wfp_post_id = null, $wfp_size = 'post-thumbnail', $wfp_attr = '' ) {
		$wfp_post_id = ( null === $wfp_post_id ) ? get_the_ID() : $wfp_post_id;
		$wfp_video   = new \WfpFundraising\Apps\Featured( false );
		return $wfp_video->wfp_featured_replace_thumbnail( '', $wfp_post_id, null, $wfp_size, $wfp_attr );
	}
}
