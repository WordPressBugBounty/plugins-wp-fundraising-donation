<?php

$enableSingleExcerpt = isset( $formSetting->single_excerpt->enable ) ? $formSetting->single_excerpt->enable : 'No';
$enableSingleExcerpt = apply_filters( 'wfp_single_excerpt_hide', $enableSingleExcerpt );

?>
<div class="wfp-post-body">
	<!-- Article header -->
	<?php if ( $enableSingleExcerpt == 'No' && strlen( get_the_excerpt( $post ) ) > 2 ) : ?>
		<div class="wfp-excerpt-section">
			<h3 class="wfp-short-berif-title"><?php echo esc_html( apply_filters( 'wfp_single_excerpt_title', __( 'Short Brief', 'wp-fundraising' ) ) ); ?></h3>
			<?php do_action( 'wfp_single_excerpt_before' ); ?>
			<div class="wfp-post-excerpt"><?php echo wp_kses( get_the_excerpt( $post ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?></div>
			<?php do_action( 'wfp_single_excerpt_after' ); ?>
		</div>
	<?php endif; ?>

</div>
