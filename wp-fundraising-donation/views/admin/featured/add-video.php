<div class="wfp-fundrasing featured-video-metabox-container">
	<div class="featured_video " id="featured_video">
		<?php
		if ( $this->has_featured_video( $post_id ) ) {
			echo '<span class="dashicons dashicons-video-alt3" ></span><img src="' . esc_url( $this->get_video_thumbnail( $post_id ) ) . '">';
		}
		?>
	</div>
	<div id="thumbnail-change-toggle">
		<?php
		if ( ! $this->has_featured_video( $post_id ) ) {
			echo wp_kses( '<p class="hide-if-no-js"><a href="#" id="wfp-set-featured-video">' . __( 'Set featured video', 'wp-fundraising' ) . '</a></p>', \WfpFundraising\Utilities\Utils::get_kses_array() );
		} else {
			echo wp_kses( '<p class="hide-if-no-js"><a href="#" id="wfp-remove-featured-video">' . __( 'Remove featured video', 'wp-fundraising' ) . '</a></p>', \WfpFundraising\Utilities\Utils::get_kses_array() );
		}
		?>
	</div>
	<input type="hidden" value="<?php echo esc_url( $this->get_video_url( $post_id ) ); ?>" name="wfp_featured_video_url" id="wfp_featured_video_url">
	
</div>
