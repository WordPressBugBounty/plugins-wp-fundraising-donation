<?php

if ( ! function_exists( 'the_post_video_thumbnail' ) ) {
	function the_post_video_thumbnail( $size = 'post-thumbnail', $attr = '' ) {
		echo wp_kses( get_the_post_video_thumbnail( null, $size, $attr ), \WfpFundraising\Utilities\Utils::get_kses_array() );
	}
}

if ( ! function_exists( 'get_the_post_video_thumbnail' ) ) {
	function get_the_post_video_thumbnail( $post_id = null, $size = 'post-thumbnail', $attr = '' ) {
		$post_id = ( null === $post_id ) ? get_the_ID() : $post_id;
		$video   = new \WfpFundraising\Apps\Featured( false );
		return $video->wfp_featured_replace_thumbnail( '', $post_id, null, $size, $attr );
	}
}
