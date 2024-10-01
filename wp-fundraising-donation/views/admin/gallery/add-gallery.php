<div class="wfp-fundrasing featured-gallery-metabox-container">
	<div class="wfp-gallery-container">
		<?php
		foreach ( $custom_meta_fields as $field ) :
			$meta = get_post_meta( $post->ID, $field['id'], true );

			switch ( $field['type'] ) {
				case 'media':
					echo '<input id="wfp_portfolio_image" type="hidden" name="wfp_portfolio_image" value="' . esc_attr( $meta ) . '" />
					<div class="wfp_portfolio_image_container">' . ( $meta ? '<span class="wfp_portfolio_close"></span>' : '' ) . '<img id="wfp_portfolio_image_src" src="' . esc_url( wp_get_attachment_thumb_url( $this->wfp_portfolio_get_image_id( $meta ) ) ) . '"></div>
					<input id="wfp_portfolio_image_button" class="button button-primary button-large" type="button" value="Add Image" />';
					break;
				case 'gallery':
					$meta_html = '';
					if ( $meta ) {
							$meta_html .= '<ul class="wfp_portfolio_gallery_list">';
							$meta_array = explode( ',', $meta );
						foreach ( $meta_array as $meta_gall_item ) {
								$meta_html .= '<li><div class="wfp_portfolio_gallery_container"><span class="wfp_portfolio_gallery_close"><img id="' . esc_attr( $meta_gall_item ) . '" src="' . wp_get_attachment_thumb_url( $meta_gall_item ) . '"></span></div></li>';

						}
							$meta_html .= '</ul>';
					}

					echo '<input id="wfp_portfolio_gallery" type="hidden" name="wfp_portfolio_gallery" value="' . esc_attr( $meta ) . '" />
					<span id="wfp_portfolio_gallery_src">' . wp_kses( $meta_html, \WfpFundraising\Utilities\Utils::get_kses_array() ) . '</span>
					<div class="wfp_gallery_button_container"><input id="wfp_portfolio_gallery_button" class="button button-primary button-large" type="button" value="Add Gallery" /></div>';
					break;
				?>
		
				<?php
			}
		endforeach;
		?>
	</div>
</div>
