<fieldset class="xs-donate-field-wrap ">
	<span class="xs-donate-field-label"><?php echo esc_html__( 'Enable Goal', 'wp-fundraising' ); ?></span>
	<div class="xs-field-body">
		<div class="xs-repeater-field-wrap">
			<ul class="xs-donate-option">
				<li>
					<div class="xs-switch-button_wraper">
						<input class="xs_donate_switch_button" type="checkbox" <?php echo ( isset( $formGoalData->enable ) && $formGoalData->enable == 'Yes' ) ? 'checked' : ''; ?> id="donation_form_goal_enable" name="xs_submit_donation_data[goal_setup][enable]" onchange="xs_show_hide_donate('.xs-donate-goal-content-section');" value="Yes" >
						<label for="donation_form_goal_enable" class="xs_donate_switch_button_label small xs-round"></label>
					</div>
				</li>
				<li><label for="donation_form_goal_enable" class="xs-donetion-field-description"><?php echo esc_html__( 'Display goal terget.', 'wp-fundraising' ); ?></label></li>
			</ul>
			<div class="xs-donate-goal-content-section xs-donate-hidden <?php echo ( isset( $formGoalData->enable ) && $formGoalData->enable == 'Yes' ) ? 'xs-donate-visible' : ''; ?>">	
				<ul class="xs-donate-option">
					<li class="xs-m-0"><label class="xs-donate-label"> <?php echo esc_html__( 'Style : ', 'wp-fundraising' ); ?></label></li>
					<li>
						<label>
							<input class="xs_radio_filed" name="xs_submit_donation_data[goal_setup][bar_style]" value="line_bar"  type="radio" <?php echo ( isset( $formGoalData->bar_style ) && $formGoalData->bar_style == 'line_bar' ) ? 'checked' : 'checked'; ?> > <?php echo esc_html__( 'Progress ', 'wp-fundraising' ); ?>
						</label>
					</li>
					<li>
						<label>
							<input class="xs_radio_filed" name="xs_submit_donation_data[goal_setup][bar_style]" value="pie_bar" type="radio" <?php echo ( isset( $formGoalData->bar_style ) && $formGoalData->bar_style == 'pie_bar' ) ? 'checked' : ''; ?>  > <?php echo esc_html__( 'Pie', 'wp-fundraising' ); ?>
						</label>
					</li>
				</ul>
			</div>
			<div class="xs-donate-goal-content-section xs-donate-hidden <?php echo ( isset( $formGoalData->enable ) && $formGoalData->enable == 'Yes' ) ? 'xs-donate-visible' : ''; ?>">	
				<ul class="xs-donate-option">
					<li class="xs-m-0"><label class="xs-donate-label"> <?php echo esc_html__( 'Display As : ', 'wp-fundraising' ); ?></label></li>
					<li>
						<label>
							<input class="xs_radio_filed" name="xs_submit_donation_data[goal_setup][bar_display_sty]" value="percentage"  type="radio" <?php echo ( isset( $formGoalData->bar_display_sty ) && $formGoalData->bar_display_sty == 'percentage' ) ? 'checked' : 'checked'; ?> > <?php echo esc_html__( 'Percentage ', 'wp-fundraising' ); ?>
						</label>
					</li>
					<li>
						<label>
							<input class="xs_radio_filed" name="xs_submit_donation_data[goal_setup][bar_display_sty]" value="amount_show" type="radio" <?php echo ( isset( $formGoalData->bar_display_sty ) && $formGoalData->bar_display_sty == 'amount_show' ) ? 'checked' : ''; ?>  > <?php echo esc_html__( 'Flat', 'wp-fundraising' ); ?>
						</label>
					</li>
					<li>
						<label>
							<input class="xs_radio_filed" name="xs_submit_donation_data[goal_setup][bar_display_sty]" value="both_show" type="radio" <?php echo ( isset( $formGoalData->bar_display_sty ) && $formGoalData->bar_display_sty == 'both_show' ) ? 'checked' : ''; ?>  > <?php echo esc_html__( 'Both', 'wp-fundraising' ); ?>
						</label>
					</li>
				</ul>
			</div>
			<div class="xs-donate-goal-content-section xs-donate-hidden <?php echo ( isset( $formGoalData->enable ) && $formGoalData->enable == 'Yes' ) ? 'xs-donate-visible' : ''; ?>">
				<label for="progressbar" class="xs-donate-label"> <?php echo esc_html__( 'Color : ', 'wp-fundraising' ); ?></label>
				<input type="hidden" id="progressbar" name="xs_submit_donation_data[goal_setup][bar_color]" class="wfdp_color_field" value="<?php echo isset( $formGoalData->bar_color ) ? esc_attr( $formGoalData->bar_color ) : '#324aff'; ?>">
			</div>
		</div>
	</div>
	<div class="xs-clearfix"></div>
</fieldset>
<fieldset class="xs-donate-field-wrap xs-donate-goal-content-section xs-donate-hidden <?php echo ( isset( $formGoalData->enable ) && $formGoalData->enable == 'Yes' ) ? 'xs-donate-visible' : ''; ?>">
	<span class="xs-donate-field-label"><?php echo esc_html__( 'Goal Type', 'wp-fundraising' ); ?></span>
	<div class="xs-field-body">
		<div class="xs-repeater-field-wrap">
			<div class="donation-none-float">
				<!--Company Title options-->
				<ul class="xs-donate-option">
					<li>
						<label>
							<input class="xs_radio_filed" name="xs_submit_donation_data[goal_setup][goal_type]" value="terget_goal"  type="radio" <?php echo ( isset( $formGoalData->goal_type ) && $formGoalData->goal_type == 'terget_goal' ) ? 'checked' : 'checked'; ?> > <?php echo esc_html__( 'Target Goal ', 'wp-fundraising' ); ?>
						</label>
					</li>
					<li>
						<label>
							<input class="xs_radio_filed" name="xs_submit_donation_data[goal_setup][goal_type]" value="terget_date"  type="radio" <?php echo ( isset( $formGoalData->goal_type ) && $formGoalData->goal_type == 'terget_date' ) ? 'checked' : ''; ?>  > <?php echo esc_html__( 'Target Date', 'wp-fundraising' ); ?>
						</label>
					</li>
					<li>
						<label>
							<input class="xs_radio_filed" name="xs_submit_donation_data[goal_setup][goal_type]" value="terget_goal_date" type="radio" <?php echo ( isset( $formGoalData->goal_type ) && $formGoalData->goal_type == 'terget_goal_date' ) ? 'checked' : ''; ?>  > <?php echo esc_html__( 'Target Goal & Date', 'wp-fundraising' ); ?>
						</label>
					</li>
					<li>
						<label>
							<input class="xs_radio_filed" name="xs_submit_donation_data[goal_setup][goal_type]" value="campaign_never_end" type="radio" <?php echo ( isset( $formGoalData->goal_type ) && $formGoalData->goal_type == 'campaign_never_end' ) ? 'checked' : ''; ?>  > <?php echo esc_html__( 'Campaign Never Ends', 'wp-fundraising' ); ?>
						</label>
					</li>
				</ul>
				<div class="goal_terget_amount_show ">
					<div class="xs-donate-field-group xs-donate-field-wrap ">	
						<label for="xs_donate_fixed_amount"> <?php echo esc_html__( 'Raised Amount', 'wp-fundraising' ); ?></label>

						<div class="xs-donate-field-wrap-amount">
							<span class="xs-money-symbol xs-money-symbol-before"><?php echo esc_html( $symbols ); ?></span>
							<input type="number" style="" min="0" name="xs_submit_donation_data[goal_setup][terget][terget_goal][amount]" id="xs_donate_fixed_amount" value="<?php echo isset( $formGoalData->terget->terget_goal->amount ) ? esc_attr( $formGoalData->terget->terget_goal->amount ) : 0; ?>" placeholder="0.00" class="xs-field xs-money-field xs-text_small">
						</div>
					</div>
					<!--
					<div class="xs-donate-field-group xs-donate-field-wrap xs-donate-field-wrap-with-help-text">
						<label for="xs_donate_fixed_amount_fake"> <?php // echo esc_html__('Fake Raised Amount', 'wp-fundraising'); ?></label>

						<div class="xs-donate-field-wrap-amount">
							<span class="xs-money-symbol xs-money-symbol-before"><?php // echo $symbols; ?></span>
							<input type="number" style="" min="0" name="xs_submit_donation_data[goal_setup][terget][terget_goal][fake_amount]" id="xs_donate_fixed_amount_fake" value="<?php // echo isset($formGoalData->terget->terget_goal->fake_amount) ? $formGoalData->terget->terget_goal->fake_amount : 0; ?>" placeholder="0.00" class="xs-field xs-money-field xs-text_small">
							<span class="xs-donetion-field-description"><?php // echo esc_html__('This is fake raised amount, just show in forms.', 'wp-fundraising'); ?></span>
						</div>
						
					</div>-->
					<div class="xs-donate-field-group xs-donate-field-wrap">
						<label for="xs_donate_fixed_target_date"  style="margin-bottom:10px;"> <?php echo esc_html__( 'Target Date', 'wp-fundraising' ); ?></label><br/>
						<div class="search-tab wfp-no-date-limit">
							<input type="text" style="" name="xs_submit_donation_data[goal_setup][terget][terget_goal][date]" id="xs_donate_fixed_target_date" value="<?php echo isset( $formGoalData->terget->terget_goal->date ) ? esc_attr( $formGoalData->terget->terget_goal->date ) : ''; ?>" placeholder="YYYY-MM-DD" class="xs-field xs-text-field  datepicker-donate">
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</div>
</fieldset>

<fieldset class="xs-donate-field-wrap xs-donate-goal-content-section xs-donate-hidden <?php echo ( isset( $formGoalData->enable ) && $formGoalData->enable == 'Yes' ) ? 'xs-donate-visible' : ''; ?>">
	<span class="xs-donate-field-label"><?php echo esc_html__( 'After Goal Raised', 'wp-fundraising' ); ?></span>
	<div class="xs-field-body">
		<div class="donation-none-float">
			<ul class="xs-donate-option">
				<li>
					<div class="xs-switch-button_wraper">
						<input class="xs_donate_switch_button" type="checkbox" <?php echo ( isset( $formGoalData->terget->enable ) && $formGoalData->terget->enable == 'Yes' ) ? 'checked' : ''; ?> id="donation_form_goal_taget_message_enable" name="xs_submit_donation_data[goal_setup][terget][enable]" onchange="xs_show_hide_donate('.xs-donate-goal-message-section');" value="Yes" >
						<label for="donation_form_goal_taget_message_enable" class="xs_donate_switch_button_label small xs-round"></label>
					</div>
				</li>
				<li><label for="donation_form_goal_taget_message_enable"  class="xs-donetion-field-description"><?php echo esc_html__( 'Enable after goal target.', 'wp-fundraising' ); ?></label></li>
			</ul>
			<div class="xs-donate-goal-message-section xs-donate-hidden xs-repeater-field-wrap xs-donate-field-wrap-with-help-text <?php echo ( isset( $formGoalData->terget->enable ) && $formGoalData->terget->enable == 'Yes' ) ? 'xs-donate-visible' : ''; ?>">
				<label for="xs_donate_forms_company_name_title" > <?php echo esc_html__( 'Message:', 'wp-fundraising' ); ?></label>
			

				<?php
				$content = isset( $formGoalData->terget->message ) ? $formGoalData->terget->message : '';

				$editor_id = 'form_goal_target_message_editor';
				$settings  = array(
					'media_buttons' => false,
					'textarea_name' => 'xs_submit_donation_data[goal_setup][terget][message]',
				);
				wp_editor( $content, $editor_id, $settings );
				?>

				
			</div>
		</div>
	</div>
</fieldset>

