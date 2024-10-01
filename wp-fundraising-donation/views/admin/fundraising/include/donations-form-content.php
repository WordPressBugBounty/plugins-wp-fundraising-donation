<?php
$forms_content_class = 'xs-donate-visible';

if ( $donation_format == 'crowdfunding' ) :
	$forms_content_class = '';
endif;

?>
	<fieldset
			class="xs-donate-field-wrap donation_target_type_filed pledge_setup_target1 xs-donate-hidden <?php echo esc_attr( $forms_content_class ); ?>">
		<span class="xs-donate-field-label"><?php echo esc_html__( 'Enable Content', 'wp-fundraising' ); ?></span>
		<legend class="screen-reader-text"><?php echo esc_html__( 'Enable Content', 'wp-fundraising' ); ?></legend>


		<div class="xs-field-body">
			<div class="xs-donate-field-wrap border-top-1">
				<div class="xs-donate-field-wrap-amount">
					<div class="xs-switch-button_wraper">
						<input class="xs_donate_switch_button"
							   type="checkbox" <?php echo ( isset( $formContentData->enable ) && $formContentData->enable == 'Yes' ) ? 'checked' : ''; ?>
							   id="donation_form_content_enable" name="xs_submit_donation_data[form_content][enable]"
							   onchange="xs_show_hide_donate('.xs-donate-form-content-section');" value="Yes">
						<label for="donation_form_content_enable"
							   class="xs_donate_switch_button_label small xs-round"></label>
					</div>
					<span class="xs-donetion-field-description"><?php echo esc_html__( 'Display additional content about Campaign.', 'wp-fundraising' ); ?></span>
				</div>

			</div>


			<div class="xs-donate-form-content-section xs-repeater-field-wrap xs-donate-hidden <?php echo ( isset( $formContentData->enable ) && $formContentData->enable == 'Yes' ) ? 'xs-donate-visible' : ''; ?>">
				<!--Company Title options-->
				<div class="xs-donate-field-wrap">
					<label for="xs_donate_forms_company_name_title"> <?php echo esc_html__( 'Content Position', 'wp-fundraising' ); ?></label>

					<div class="xs-donate-field-wrap-amount">
						<ul class="xs-donate-option">
							<li>
								<label>
									<input class="xs_radio_filed"
										   name="xs_submit_donation_data[form_content][content_position]"
										   value="after-form"
										   type="radio" <?php echo ( isset( $formContentData->content_position ) && $formContentData->content_position == 'after-form' ) ? 'checked' : 'checked'; ?> > <?php echo esc_html__( 'After Form', 'wp-fundraising' ); ?>
								</label>
							</li>
							<li>
								<label>
									<input class="xs_radio_filed"
										   name="xs_submit_donation_data[form_content][content_position]"
										   value="before-form"
										   type="radio" <?php echo ( isset( $formContentData->content_position ) && $formContentData->content_position == 'before-form' ) ? 'checked' : ''; ?> > <?php echo esc_html__( 'Before Form', 'wp-fundraising' ); ?>
								</label>
							</li>
						</ul>
					</div>
				</div>

				<p class="">
					<label for="xs_donate_forms_company_name_title"> <?php echo esc_html__( 'Content Details', 'wp-fundraising' ); ?></label>
					<?php
					$content   = isset( $formContentData->content ) ? $formContentData->content : '';
					$editor_id = 'form_content_editor';
					$settings  = array(
						'media_buttons' => false,
						'textarea_name' => 'xs_submit_donation_data[form_content][content]',
					);
					wp_editor( $content, $editor_id, $settings );
					?>
				</p>


			</div>

		</div>
	</fieldset>
<?php
$additionalEnable = ! isset( $formContentData->additional ) ? 'check' : '';
if ( isset( $formContentData->additional->enable ) && $formContentData->additional->enable == 'Yes' ) {
	$additionalEnable = 'check';
}

$getCustomGlobalOptions = isset( $getGlobalOptions['custom_fileds']['enable'] ) ? $getGlobalOptions['custom_fileds']['enable'] : 'No';
if ( ! isset( $getGlobalOptionsGlo['options'] ) ) {
	$getCustomGlobalOptions = 'Yes';
}
if ( $getCustomGlobalOptions == 'Yes' ) :
	?>
	<fieldset class="xs-donate-field-wrap ">
		<span class="xs-donate-field-label"><?php echo esc_html__( 'Custom Fields', 'wp-fundraising' ); ?></span>
		<div class="xs-field-body">
			<ul class="xs-donate-option">
				<li>
					<div class="xs-switch-button_wraper">
						<input class="xs_donate_switch_button"
							   type="checkbox" <?php echo $additionalEnable == 'check' ? 'checked' : ''; ?>
							   id="donation_company_enable"
							   name="xs_submit_donation_data[form_content][additional][enable]"
							   onchange="xs_show_hide_donate('.xs-donate-company-info-section');" value="Yes">
						<label for="donation_company_enable"
							   class="xs_donate_switch_button_label small xs-round"></label>
					</div>
				</li>
				<li>
					<label for="donation_company_enable"
						   class="xs-donetion-field-description"><?php echo esc_html__( 'Add new custom filed in forms.', 'wp-fundraising' ); ?></label>
				</li>
			</ul>
			<div class="xs-donate-company-info-section xs-donate-hidden <?php echo $additionalEnable == 'check' ? 'xs-donate-visible' : ''; ?> ">
				<div class="xs-donate-repeatable-field-section ">
					<div class="xs-donate-repeatable-fields-section-wrapper">
						<div class="repater_additional_item ui-sortable" id="wfdp-additonal-sortable-sub">
							<?php

							$defaults_fields = \WfpFundraising\Apps\Settings::get_mandatory_form_fields();

							$counter = 0;

							foreach ( $defaults_fields as $group => $fields ) {

								foreach ( $fields as $fld_name => $fld_info ) {

									$required  = $fld_info['required'] ? 'required' : '';
									$checked   = $fld_info['required'] ? 'checked' : '';
									$closeable = ! empty( $fld_info['closeable'] ) && $fld_info['closeable'] == true;
									$type      = $fld_info['type'];

									require __DIR__ . '/_partial_input_type_selector.php';

									$counter++;
								}
							}

							?>
							<div class="add_button_sections">
								<button type="button"
										class="xs-additional-btnAdd xs-review-add-button">
									<?php echo esc_html__( 'Add', 'wp-fundraising' ); ?>
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="xs-clearfix"></div>
	</fieldset>


	<script type="text/javascript">
		/*Reapter data*/

		jQuery(document).ready(function ($) {

			var totalRowCountQuery = document.querySelectorAll('.xs-additional-row');
			var totalRowCount = Number(totalRowCountQuery.length) - 1;

			$('.repater_additional_item').repeater({
					btnAddClass: 'xs-additional-btnAdd',
					btnRemoveClass: 'xs-additional-btnRemove',
					groupClass: 'xs-additional-row',
					minItems: 1,
					maxItems: 0,
					startingIndex: parseInt(totalRowCount),
					showMinItemsOnLoad: false,
					reindexOnDelete: true,
					repeatMode: 'insertAfterLast',
					animation: 'fade',
					animationSpeed: 400,
					animationEasing: 'swing',
					clearValues: true
				}, []
			);

			$("#wfdp-additonal-sortable-sub").sortable();
			$("#wfdp-additonal-sortable-sub").disableSelection();
		});
	</script>
<?php endif; ?>
