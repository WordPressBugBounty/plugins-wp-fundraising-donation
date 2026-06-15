<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

$wfpEnableSingleExcerpt = isset( $wfpFormSetting->single_excerpt->enable ) ? $wfpFormSetting->single_excerpt->enable : 'No';
$wfpEnableSingleExcerpt = apply_filters( 'wfp_single_excerpt_hide', $wfpEnableSingleExcerpt );

?>
<div class="wfp-post-body">
	<!-- Article header -->
	<?php if ( $wfpEnableSingleExcerpt == 'No' && strlen( get_the_excerpt( $post ) ) > 2 ) : ?>
		<div class="wfp-excerpt-section">
			<h3 class="wfp-short-berif-title"><?php echo esc_html( apply_filters( 'wfp_single_excerpt_title', __( 'Short Brief', 'wp-fundraising-donation' ) ) ); ?></h3>
			<?php do_action( 'wfp_single_excerpt_before' ); ?>
			<div class="wfp-post-excerpt"><?php echo wp_kses( get_the_excerpt( $post ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?></div>
			<?php do_action( 'wfp_single_excerpt_after' ); ?>
		</div>
	<?php endif; ?>

</div>
