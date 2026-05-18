<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

?>
<div class="wfp-fundrasing featured-gallery-metabox-container">
	<div class="wfp-gallery-container">
		<?php
		foreach ( $custom_meta_fields as $field ) :
			$wfp_meta = get_post_meta( $post->ID, $field['id'], true );

			switch ( $field['type'] ) {
				case 'media':
					echo '<input id="wfp_portfolio_image" type="hidden" name="wfp_portfolio_image" value="' . esc_attr( $wfp_meta ) . '" />
					<div class="wfp_portfolio_image_container">' . ( $wfp_meta ? '<span class="wfp_portfolio_close"></span>' : '' ) . '<img id="wfp_portfolio_image_src" src="' . esc_url( wp_get_attachment_thumb_url( $this->wfp_portfolio_get_image_id( $wfp_meta ) ) ) . '"></div>
					<input id="wfp_portfolio_image_button" class="button button-primary button-large" type="button" value="Add Image" />';
					break;
				case 'gallery':
					$wfp_meta_html = '';
					if ( $wfp_meta ) {
							$wfp_meta_html .= '<ul class="wfp_portfolio_gallery_list">';
							$wfp_meta_array = explode( ',', $wfp_meta );
						foreach ( $wfp_meta_array as $meta_gall_item ) {
								$wfp_meta_html .= '<li><div class="wfp_portfolio_gallery_container"><span class="wfp_portfolio_gallery_close"><img id="' . esc_attr( $meta_gall_item ) . '" src="' . wp_get_attachment_thumb_url( $meta_gall_item ) . '"></span></div></li>';

						}
							$wfp_meta_html .= '</ul>';
					}

					echo '<input id="wfp_portfolio_gallery" type="hidden" name="wfp_portfolio_gallery" value="' . esc_attr( $wfp_meta ) . '" />
					<span id="wfp_portfolio_gallery_src">' . wp_kses( $wfp_meta_html, \WfpFundraising\Utilities\Utils::get_kses_array() ) . '</span>
					<div class="wfp_gallery_button_container"><input id="wfp_portfolio_gallery_button" class="button button-primary button-large" type="button" value="Add Gallery" /></div>';
					break;
				?>
		
				<?php
			}
		endforeach;
		?>
	</div>
</div>
