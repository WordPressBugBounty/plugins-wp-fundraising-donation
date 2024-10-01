<div class="xs-additional-row">
	<div class="xs-repeater-field-wrap xs-column xs-opened">
		<div class="xs-donate-row-head xs-move ui-sortable-handle"
			 onclick="xs_show_hide_parents_elements(this)">
			<h2>
				<span class="level_donate_multi"><?php echo esc_html( $fld_info['label'] ); ?></span>
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

					<label for="xs_additional_<?php echo esc_attr( $counter ); ?>_type" data-pattern-for="xs_additional_++_type">
						<?php echo esc_html__( 'Type', 'wp-fundraising' ); ?>
					</label>

					<div class="xs-donate-field-wrap-amount xs-donate-field-wrap-no-symbol">
						<select class="xs-field xs_select_filed"
								data-pattern-name="xs_submit_donation_data[form_content][<?php echo esc_attr( $group ); ?>][dimentions][++][type]"
								data-pattern-id="xs_additional_++_type"
								id="xs_additional_<?php echo esc_attr( $counter ); ?>_type"
								name="xs_submit_donation_data[form_content][<?php echo esc_attr( $group ); ?>][dimentions][<?php echo esc_attr( $counter ); ?>][type]">

							<option value="text" <?php echo esc_attr( $type == 'text' ? 'selected' : '' ); ?>> <?php esc_html_e( 'Text', 'wp-fundraising' ); ?></option>
							<option value="email" <?php echo esc_attr( $type == 'email' ? 'selected' : '' ); ?>> <?php esc_html_e( 'Email', 'wp-fundraising' ); ?></option>
							<option value="number" <?php echo esc_attr( $type == 'number' ? 'selected' : '' ); ?>> <?php esc_html_e( 'Number', 'wp-fundraising' ); ?></option>
							<?php

							if ( $type == 'select' ) :
								?>
								<option value="number" selected> <?php esc_html_e( 'Selection', 'wp-fundraising' ); ?></option>
																							   <?php
							endif;
							?>
						</select>
					</div>
				</div>

				<div class="xs-donate-field-wrap ">

					<label for="xs_additional_<?php echo esc_attr( $counter ); ?>_label_name"
						   data-pattern-for="xs_additional_++_label_name">
						<?php echo esc_html__( 'Label', 'wp-fundraising' ); ?>
					</label>

					<div class="xs-donate-field-wrap-amount xs-donate-field-wrap-no-symbol">
						<input type="text"
							   data-pattern-name="xs_submit_donation_data[form_content][<?php echo esc_attr( $group ); ?>][dimentions][++][label]"
							   data-pattern-id="xs_additional_++_label_name"
							   id="xs_additional_<?php echo esc_attr( $counter ); ?>_label_name"
							   name="xs_submit_donation_data[form_content][<?php echo esc_attr( $group ); ?>][dimentions][<?php echo esc_attr( $counter ); ?>][label]"
							   onkeyup="xs_modify_lebel_name(this);"
							   value="<?php echo esc_attr( $fld_info['label'] ); ?>"
							   placeholder="Basic"
							   class="xs-field xs-text-field xs-money-field"/>
					</div>
				</div>
			</div>


			<div class="xs-donate-field-wrap ">
				<label for="xs_additional_<?php echo esc_attr( $counter ); ?>_required_name"
					   data-pattern-for="xs_additional_++_required_name">
					<?php echo esc_html__( 'Required', 'wp-fundraising' ); ?>
				</label>

				<div class="xs-switch-button_wraper">
					<input class="xs_donate_switch_button"
						   data-pattern-name="xs_submit_donation_data[form_content][<?php echo esc_attr( $group ); ?>][dimentions][++][required]"
						   data-pattern-id="xs_additional_++_label_required"
						   id="xs_additional_<?php echo esc_attr( $counter ); ?>_label_required"
						   name="xs_submit_donation_data[form_content][<?php echo esc_attr( $group ); ?>][dimentions][<?php echo esc_attr( $counter ); ?>][required]"
						   type="checkbox" <?php echo esc_attr( $checked ); ?>
						   value="Yes">

					<label for="xs_additional_<?php echo esc_attr( $counter ); ?>_label_required"
						   class="xs_donate_switch_button_label small xs-round"></label>
				</div>
			</div>

		</div>
	</div>
</div>
