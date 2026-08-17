<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

?>
<div class="xs-additional-row">
	<div class="xs-repeater-field-wrap xs-column xs-opened">
		<div class="xs-donate-row-head xs-move ui-sortable-handle"
			 onclick="xs_show_hide_parents_elements(this)">
			<h2>
				<span class="level_donate_multi"><?php echo esc_html( $fld_info['lebel'] ); ?></span>
			</h2>

			<div class="xs-header-btn-group" style="">
				<button type="button" class="xs-additional-btnRemove xs-remove">
					<span class="wfpf wfpf-close-outline"></span>
				</button>
			</div>

		</div>

		<div class="xs-row-body xs-donate-hidden xs-donate-visible">
			<div class="xs-donate-field-wrap-group xs-donate-field-wrap-inline-group">
				<div class="xs-donate-field-wrap ">

					<label for="xs_additional_<?php echo esc_attr( $wfp_counter ); ?>_type" data-pattern-for="xs_additional_++_type">
						<?php echo esc_html__( 'Type', 'wp-fundraising-donation' ); ?>
					</label>

					<div class="xs-donate-field-wrap-amount xs-donate-field-wrap-no-symbol">
						<select class="xs-field xs_select_filed"
								data-pattern-name="xs_submit_donation_data[form_content][<?php echo esc_attr( $wfp_group ); ?>][dimentions][++][type]"
								data-pattern-id="xs_additional_++_type"
								id="xs_additional_<?php echo esc_attr( $wfp_counter ); ?>_type"
								name="xs_submit_donation_data[form_content][<?php echo esc_attr( $wfp_group ); ?>][dimentions][<?php echo esc_attr( $wfp_counter ); ?>][type]">

							<option value="text" <?php echo esc_attr( $wfp_type == 'text' ? 'selected' : '' ); ?>> <?php esc_html_e( 'Text', 'wp-fundraising-donation' ); ?></option>
							<option value="email" <?php echo esc_attr( $wfp_type == 'email' ? 'selected' : '' ); ?>> <?php esc_html_e( 'Email', 'wp-fundraising-donation' ); ?></option>
							<option value="number" <?php echo esc_attr( $wfp_type == 'number' ? 'selected' : '' ); ?>> <?php esc_html_e( 'Number', 'wp-fundraising-donation' ); ?></option>
							<?php

							if ( $wfp_type == 'select' ) :
								?>
								<option value="select" selected> <?php esc_html_e( 'Selection', 'wp-fundraising-donation' ); ?></option>
																							   <?php
							endif;
							?>
						</select>
					</div>
				</div>

				<div class="xs-donate-field-wrap ">

					<label for="xs_additional_<?php echo esc_attr( $wfp_counter ); ?>_label_name"
						   data-pattern-for="xs_additional_++_label_name">
						<?php echo esc_html__( 'Label', 'wp-fundraising-donation' ); ?>
					</label>

					<div class="xs-donate-field-wrap-amount xs-donate-field-wrap-no-symbol">
						<input type="text"
							   data-pattern-name="xs_submit_donation_data[form_content][<?php echo esc_attr( $wfp_group ); ?>][dimentions][++][lebel]"
							   data-pattern-id="xs_additional_++_label_name"
							   id="xs_additional_<?php echo esc_attr( $wfp_counter ); ?>_label_name"
							   name="xs_submit_donation_data[form_content][<?php echo esc_attr( $wfp_group ); ?>][dimentions][<?php echo esc_attr( $wfp_counter ); ?>][lebel]"
							   onkeyup="xs_modify_lebel_name(this);"
							   value="<?php echo esc_attr( $fld_info['lebel'] ); ?>"
							   placeholder="<?php esc_attr_e( 'Basic', 'wp-fundraising-donation' ); ?>"
							   class="xs-field xs-text-field xs-money-field"/>
					</div>
				</div>
			</div>


			<div class="xs-donate-field-wrap ">
				<label for="xs_additional_<?php echo esc_attr( $wfp_counter ); ?>_required_name"
					   data-pattern-for="xs_additional_++_required_name">
					<?php echo esc_html__( 'Required', 'wp-fundraising-donation' ); ?>
				</label>

				<div class="xs-switch-button_wraper">
					<input class="xs_donate_switch_button"
						   data-pattern-name="xs_submit_donation_data[form_content][<?php echo esc_attr( $wfp_group ); ?>][dimentions][++][required]"
						   data-pattern-id="xs_additional_++_label_required"
						   id="xs_additional_<?php echo esc_attr( $wfp_counter ); ?>_label_required"
						   name="xs_submit_donation_data[form_content][<?php echo esc_attr( $wfp_group ); ?>][dimentions][<?php echo esc_attr( $wfp_counter ); ?>][required]"
						   type="checkbox" <?php echo esc_attr( $wfp_checked ); ?>
						   value="Yes">

					<label for="xs_additional_<?php echo esc_attr( $wfp_counter ); ?>_label_required"
						   class="xs_donate_switch_button_label small xs-round"></label>
				</div>
			</div>

		</div>
	</div>
</div>
