<fieldset class="xs-donate-field-wrap ">
	<span class="xs-donate-field-label"><?php echo esc_html__( 'Enable Terms', 'wp-fundraising' ); ?></span>
	<div class="xs-field-body">
		<ul class="xs-donate-option">
			<li>
				<div class="xs-switch-button_wraper">
					<input class="xs_donate_switch_button" type="checkbox" <?php echo ( isset( $formTermsData->enable ) && $formTermsData->enable == 'Yes' ) ? 'checked' : ''; ?> id="donation_form_terms_enable" name="xs_submit_donation_data[form_terma][enable]" onchange="xs_show_hide_donate('.xs-donate-form-content-section');" value="Yes" >
					<label for="donation_form_terms_enable" class="xs_donate_switch_button_label small xs-round"></label>
				</div>
			</li>
			<li><label for="donation_form_terms_enable" class="xs-donetion-field-description"><?php echo esc_html__( 'Display Terms & condition enable.', 'wp-fundraising' ); ?></label></li>
		</ul>
		<div class="xs-donate-form-content-section xs-donate-hidden xs-repeater-field-wrap <?php echo ( isset( $formTermsData->enable ) && $formTermsData->enable == 'Yes' ) ? 'xs-donate-visible' : ''; ?>">
			<!--Company Title options-->
			<div class="xs-donate-field-wrap">
				<label for="xs_donate_forms_company_name_title" > <?php echo esc_html__( 'Position Checkbox', 'wp-fundraising' ); ?></label>

				<div class="xs-donate-field-wrap-amount">
					<ul class="xs-donate-option">
						<li>
							<label>
							<input class="xs_radio_filed" name="xs_submit_donation_data[form_terma][content_position]" value="after-submit-button" type="radio" <?php echo ( isset( $formTermsData->content_position ) && $formTermsData->content_position == 'after-submit-button' ) ? 'checked' : 'checked'; ?> > <?php echo esc_html__( 'After Submit Button', 'wp-fundraising' ); ?>
							</label>
						</li>
						<li>
							<label>
							<input class="xs_radio_filed" name="xs_submit_donation_data[form_terma][content_position]" value="before-submit-button" type="radio" <?php echo ( isset( $formTermsData->content_position ) && $formTermsData->content_position == 'before-submit-button' ) ? 'checked' : ''; ?>  > <?php echo esc_html__( 'Before Submit Button', 'wp-fundraising' ); ?>
							</label>
						</li>
					</ul>
				</div>
			</div>
			
			<div class="xs-donate-field-wrap">
				<label for="xs_donate_forms_design_submit_button" > <?php echo esc_html__( 'Agreement Label', 'wp-fundraising' ); ?></label>
				<input type="text" style="" name="xs_submit_donation_data[form_terma][level]" id="xs_donate_forms_design_submit_button" value="<?php echo isset( $formTermsData->level ) ? esc_attr( $formTermsData->level ) : 'Agree to Terms'; ?>" placeholder="Enter terms & condition level" class="xs-field xs-text-field">
			</div>
			<p class="">
				<label for="xs_donate_forms_company_name_title" > <?php echo esc_html__( 'Agreement Details', 'wp-fundraising' ); ?></label>
				<?php
				$content = isset( $formTermsData->content ) ? $formTermsData->content : '';

				$editor_id = 'form_terms_editor';
				$settings  = array(
					'media_buttons' => false,
					'textarea_name' => 'xs_submit_donation_data[form_terma][content]',
				);
				wp_editor( $content, $editor_id, $settings );
				?>
				 
			</p>
		</div>
	</div>
	<div class="xs-clearfix"></div>
</fieldset>
