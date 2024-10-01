<div class="wfp-fundrasing-meta-featured">
	<div class="video-data-item wfp-fundrasing" data-video="<?php echo esc_url( $url ); ?>" data-thumb="<?php echo esc_url( $thumb ); ?>">
		<?php if ( strlen( $thumb ) > 4 ) { ?>
		<div class="video-thumbnail">
			<span class="dashicons dashicons-video-alt3"></span>
			<img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( $title ); ?>">
		</div>
		<?php } ?>
		<div class="video-information">
			<h3><?php echo esc_html( $title ); ?></h3>
			<div class="video-type"><?php echo esc_html( $data['type'] ); ?></div>
			<button class="button-primary" id="insert-video"><?php esc_html_e( 'Set Video', 'wp-fundraising' ); ?></button>
		</div>
	</div>
</div>
