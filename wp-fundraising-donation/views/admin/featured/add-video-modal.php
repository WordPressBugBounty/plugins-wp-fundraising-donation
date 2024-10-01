<div tabindex="0" id="wfp_featured_video_modal" class="featured-video-modal" style="position: relative; display:none;">
	<div class="media-modal wp-core-ui">
		<a class="media-modal-close" href="#" title="<?php esc_html_e( 'Close', 'wp-fundraising' ); ?>"><span class="media-modal-icon"></span></a>
		<div class="media-modal-content">
			<div class="media-frame wp-core-ui hide-menu" id="wfp_featured_video_modal_0">
				<div class="media-frame-menu">
					<div class="media-menu">
						<a href="#" class="media-menu-item active"><?php esc_html_e( 'Set Featured Video', 'wp-fundraising' ); ?></a>
					</div>
				</div>
				<div class="media-frame-title">
					<h1><?php esc_html_e( 'Set Featured Video', 'wp-fundraising' ); ?></h1>
					<p>
						<?php esc_html_e( 'You can type any Vimeo or YouTube URL to get the metadata for the video and insert as a featured video.', 'wp-fundraising' ); ?>
					</p>
				</div>
				<div class="media-frame-content">
					<div class="attachments-browser">
						<div class="attachments" style="top: 0; left: 16px;" id="insert-video-url">
							
							<h2><?php esc_html_e( 'Insert URL', 'wp-fundraising' ); ?></h2>	

							<input type="text" value="<?php echo esc_url( $video ); ?>" id="_featured_video">

							<button id="wfp_get_video_data" class="button-primary"><?php esc_html_e( 'Check Video', 'wp-fundraising' ); ?></button>
							
							<div class="video-data">
								<?php
								if ( strlen( $video ) > 0 ) {
									$this->ajax_render_video_data( false, $video );
								}
								?>
							</div>
						</div>
					</div>
				</div>
	
			</div>
		</div>
	</div>
	<div class="media-modal-backdrop"></div>
</div>
