<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

if ( $wfp_featured_enable === \WfpFundraising\Apps\Key::WFP_YES ) :

	/**
	 * Hook before outputting featured content
	 */
	do_action( 'wfp_single_thumbnail_before' );

	$wfp_feature = new \WfpFundraising\Apps\Featured();

	if ( $wfp_feature->has_featured_video( $wfpPostId ) ) : ?>

		<div class="wfp-feature-video">
		<?php echo wp_kses( $wfp_feature->wfp_featured_video_iframe( $wfpPostId ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
		</div>
		<?php

	else :
		?>

		<div class="wfp-post-image">
		<?php echo get_the_post_thumbnail( $wfpPostId ); ?>
		</div>
		<?php

	endif;

	/**
	 * Hook after outputting featured content
	 */
	do_action( 'wfp_single_thumbnil_after' );

endif;
