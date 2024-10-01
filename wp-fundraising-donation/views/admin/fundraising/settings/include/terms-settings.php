<div class="wfdp-payment-section">
	<div class="wfdp-payment-headding">
		<h2><?php echo esc_html__( 'Setup Terms & Condition', 'wp-fundraising' ); ?></h2>
	</div>
	<div class="wfdp-payment-gateway">
		<form action="<?php echo esc_url( admin_url() . 'edit.php?post_type=' . self::post_type() . '&page=settings&tab=terms' ); ?>"
			  method="post">
			<?php wp_nonce_field( 'wpf_save_settings', 'wpf_settings_nonce' ); ?>
			<ul class="wfdp-social_share">
				<li class="wfdp-social-input-container">
					<div class="wfdp-social-label">
						<?php echo esc_html__( 'Enable Terms ', 'wp-fundraising' ); ?>
					</div>
					<div class="xs-switch-button_wraper">
						<input class="xs_donate_switch_button"
							   type="checkbox" <?php echo ( isset( $getMetaTerms->enable ) && $getMetaTerms->enable == 'Yes' ) ? 'checked' : ''; ?>
							   id="donation_form_terms_enable"
							   name="xs_submit_terms_condition_data[form_terma][enable]"
							   value="Yes">
						<label for="donation_form_terms_enable"
							   class="xs_donate_switch_button_label small xs-round"></label>
					</div>
				</li>
				<li class="wfdp-social-input-container xs-donate-form-content-section">
					<div class="wfdp-social-label">
						<?php echo esc_html__( 'Position Checkbox ', 'wp-fundraising' ); ?>
					</div>
					<div class="wfdp-social-input">
						<ul class="xs-donate-option">
							<li>
								<label>
									<input class="xs_radio_filed"
										   name="xs_submit_terms_condition_data[form_terma][content_position]"
										   value="after-submit-button"
										   type="radio" <?php echo ( isset( $getMetaTerms->content_position ) && $getMetaTerms->content_position == 'after-submit-button' ) ? 'checked' : 'checked'; ?> > <?php echo esc_html__( 'After Submit Button', 'wp-fundraising' ); ?>
								</label>
							</li>
							<li>
								<label>
									<input class="xs_radio_filed"
										   name="xs_submit_terms_condition_data[form_terma][content_position]"
										   value="before-submit-button"
										   type="radio" <?php echo ( isset( $getMetaTerms->content_position ) && $getMetaTerms->content_position == 'before-submit-button' ) ? 'checked' : ''; ?> > <?php echo esc_html__( 'Before Submit Button', 'wp-fundraising' ); ?>
								</label>
							</li>
						</ul>

					</div>
				</li>
				<li class="wfdp-social-input-container xs-donate-form-content-section">
					<div class="wfdp-social-label">
						<?php echo esc_html__( 'Agreement Label ', 'wp-fundraising' ); ?>
					</div>
					<div class="wfdp-social-input">
						<input class="regular-text"
							   type="text"
							   style=""
							   name="xs_submit_terms_condition_data[form_terma][level]"
							   id="xs_donate_forms_design_submit_button"
							   value="<?php echo isset( $getMetaTerms->level ) ? esc_attr( $getMetaTerms->level ) : 'Agree to Terms'; ?>"
							   placeholder="Enter terms & condition level" class="xs-field xs-money-field">
					</div>
				</li>
				<li class="wfdp-social-input-container wfdp-social-textarea">
					<div class="wfdp-social-label">
						<?php echo esc_html__( 'Agreement Details ', 'wp-fundraising' ); ?>
					</div>
					<div class="wfdp-social-input">
						<?php
						$content = isset( $getMetaTerms->content ) ? $getMetaTerms->content : '';

						$editor_id = 'form_terms_editor';
						$settings  = array(
							'media_buttons' => false,
							'textarea_name' => 'xs_submit_terms_condition_data[form_terma][content]',
						);
						wp_editor( $content, $editor_id, $settings );
						?>

					</div>
				</li>

			</ul>

			<button type="submit"
					name="submit_donate_terms_setting"
					class="button button-primary button-large">
				<?php echo esc_html__( 'Save', 'wp-fundraising' ); ?>
			</button>
		</form>
	</div>
</div>
