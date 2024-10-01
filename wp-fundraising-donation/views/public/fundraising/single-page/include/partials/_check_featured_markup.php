<?php

if ( $featured_enable === \WfpFundraising\Apps\Key::WFP_YES ) :

	/**
	 * Hook before outputting featured content
	 */
	do_action( 'wfp_single_thumbnail_before' );

	$feature = new \WfpFundraising\Apps\Featured();

	if ( $feature->has_featured_video( $postId ) ) : ?>

		<div class="wfp-feature-video">
		<?php echo wp_kses( $feature->wfp_featured_video_iframe( $postId ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
		</div>
		<?php

	else :
		?>

		<div class="wfp-post-image">
		<?php echo get_the_post_thumbnail( $postId ); ?>
		</div>
		<?php

	endif;

	/**
	 * Hook after outputting featured content
	 */
	do_action( 'wfp_single_thumbnil_after' );

endif;
