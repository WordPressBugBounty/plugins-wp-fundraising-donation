<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals
defined( 'ABSPATH' ) || exit;
?>
<div class="wfp-fundrasing-meta-featured">
	<div class="video-data-item wfp-fundrasing" data-video="<?php echo esc_url( $wfp_url ); ?>" data-thumb="<?php echo esc_url( $wfp_thumb ); ?>">
		<?php if ( strlen( $wfp_thumb ) > 4 ) { ?>
		<div class="video-thumbnail">
			<span class="dashicons dashicons-video-alt3"></span>
			<img src="<?php echo esc_url( $wfp_thumb ); ?>" alt="<?php echo esc_attr( $wfp_title ); ?>">
		</div>
		<?php } ?>
		<div class="video-information">
			<h3><?php echo esc_html( $wfp_title ); ?></h3>
			<div class="video-type"><?php echo esc_html( $wfp_data['type'] ); ?></div>
			<button class="button-primary" id="insert-video"><?php esc_html_e( 'Set Video', 'wp-fundraising-donation' ); ?></button>
		</div>
	</div>
</div>
