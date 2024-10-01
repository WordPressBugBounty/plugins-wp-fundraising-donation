<div class="xs-form-group intro-info">
	<label for="camapign_post_name"  class="xs-col-form-label">
		<?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_title', __( 'Campaign Title', 'wp-fundraising' ) ) ); ?>
	</label>
	<?php $post_name = isset( $post_data->post_title ) ? $post_data->post_title : ''; ?>
	<input type="text" name="campaign_post[post_title]" id="camapign_post_name" value="<?php echo esc_attr( $post_name ); ?>" class="wfp-require-filed xs-form-control-plaintext wfp-input" oninput="wfp_modify_class(this)" required="10" >
</div>
<div class="xs-form-group intro-info">
	<label for="camapign_post_excerpt" class="xs-col-form-label">
		<?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_short_description', __( 'Short Description', 'wp-fundraising' ) ) ); ?>
	</label>
	<?php $post_excerpt = isset( $post_data->post_excerpt ) ? $post_data->post_excerpt : ''; ?>
	<textarea rows="4" name="campaign_post[post_excerpt]" id="camapign_post_excerpt" class="xs-form-control wfp-input wfp-textarea" oninput="wfp_modify_class(this)" required="10" ><?php echo wp_kses( $post_excerpt, \WfpFundraising\Utilities\Utils::get_kses_array() ); ?></textarea>
	
</div>
<div class="xs-form-group intro-info">
	<label for="campaign_details_editor" class="xs-col-form-label">
		<?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campign_description', __( 'Campaign Description', 'wp-fundraising' ) ) ); ?>
	</label>
	<?php
		$post_content = isset( $post_data->post_content ) ? $post_data->post_content : '';
		$editor_id    = 'campaign_details_editor';
		$settings     = array(
			'media_buttons' => false,
			'textarea_name' => 'campaign_post[post_content]',
		);
		wp_editor( $post_content, $editor_id, $settings );
		?>
	 
	<?php
	if ( $post_id > 0 ) {
		echo wp_kses( '<input type="hidden" id="wfp_update_post" name="update_post" value="' . $post_id . '">', \WfpFundraising\Utilities\Utils::get_kses_array() );
	}
	?>
</div>
