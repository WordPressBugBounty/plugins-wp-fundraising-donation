<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

require \WFP_Fundraising::plugin_dir() . 'country-module/country-info.php';

?>
<div class="wfdp-payment-section" >
	<div class="wfdp-payment-headding">
		<h2><?php echo esc_html__( 'Display Settings', 'wp-fundraising-donation' ); ?></h2>
	</div>
	<div class="wfdp-payment-gateway">
		<form action="<?php echo esc_url( admin_url() . 'edit.php?post_type=' . self::post_type() . '&page=settings&tab=display' ); ?>" method="post">
		<?php wp_nonce_field( 'wpf_save_settings', 'wpf_settings_nonce' ); ?>
		<div class="wfdp-payment-inputs-container">
			<ul class="wfdp-social_share">

				<li class="wfdp-social_share-section-title"><h3><?php echo esc_html__( 'Goal options (Default Set for All Campaign)', 'wp-fundraising-donation' ); ?></h3></li>
				<li class="wfdp-social-input-container">
					<div class="wfdp-social-label">
						<?php echo esc_html__( 'Goal Style', 'wp-fundraising-donation' ); ?>
					</div>
					<div class="wfdp-social-input">
						<ul class="xs-donate-option">
							<!-- <li><span class="xs-donetion-field-description"> <?php echo esc_html__( 'Style : ', 'wp-fundraising-donation' ); ?></span></li> -->
							<li>
								<label>
									<input class="xs_radio_filed" name="xs_submit_donation_data[goal_setup][bar_style]" value="line_bar"  type="radio" <?php echo ( isset( $wfpFormGoalData['bar_style'] ) && $wfpFormGoalData['bar_style'] == 'line_bar' ) ? 'checked' : 'checked'; ?> > <?php echo esc_html__( 'Progress ', 'wp-fundraising-donation' ); ?>
								</label>
							</li>
							<li>
								<label>
									<input class="xs_radio_filed" name="xs_submit_donation_data[goal_setup][bar_style]" value="pie_bar" type="radio" <?php echo ( isset( $wfpFormGoalData['bar_style'] ) && $wfpFormGoalData['bar_style'] == 'pie_bar' ) ? 'checked' : ''; ?>  > <?php echo esc_html__( 'Pie', 'wp-fundraising-donation' ); ?>
								</label>
							</li>
						</ul>
					</div>
				</li>
				<li class="wfdp-social-input-container">
					<div class="wfdp-social-label">
						<?php echo esc_html__( 'Display  Backers', 'wp-fundraising-donation' ); ?>
					</div>
					<div class="wfdp-social-input">
						<ul class="xs-donate-option">
							<li>
								<div class="xs-switch-button_wraper">
									<input class="xs_donate_switch_button" type="checkbox" id="donation_form_backers_enable__" <?php echo ( isset( $wfpFormGoalData['backers'] ) && $wfpFormGoalData['backers'] == 'Yes' ) ? 'checked' : ''; ?> name="xs_submit_donation_data[goal_setup][backers]" value="Yes">
									<label for="donation_form_backers_enable__" class="xs_donate_switch_button_label small xs-round"></label>
								</div>
							</li>
						</ul>
					</div>

				</li>
				<li class="wfdp-social-input-container">
					<div class="wfdp-social-label">
						<?php echo esc_html__( 'Display As', 'wp-fundraising-donation' ); ?>
					</div>
					<div class="wfdp-social-input">
						<ul class="xs-donate-option">
							<!-- <li><span class="xs-donetion-field-description"> <?php echo esc_html__( 'Display As : ', 'wp-fundraising-donation' ); ?></span></li> -->
							<li>
								<label>
									<input class="xs_radio_filed" name="xs_submit_donation_data[goal_setup][bar_display_sty]" value="percentage"  type="radio" <?php echo ( isset( $wfpFormGoalData['bar_display_sty'] ) && $wfpFormGoalData['bar_display_sty'] == 'percentage' ) ? 'checked' : 'checked'; ?> > <?php echo esc_html__( 'Percentage ', 'wp-fundraising-donation' ); ?>
								</label>
							</li>
							<li>
								<label>
									<input class="xs_radio_filed" name="xs_submit_donation_data[goal_setup][bar_display_sty]" value="amount_show" type="radio" <?php echo ( isset( $wfpFormGoalData['bar_display_sty'] ) && $wfpFormGoalData['bar_display_sty'] == 'amount_show' ) ? 'checked' : ''; ?>  > <?php echo esc_html__( 'Flat', 'wp-fundraising-donation' ); ?>
								</label>
							</li>
							<li>
								<label>
									<input class="xs_radio_filed" name="xs_submit_donation_data[goal_setup][bar_display_sty]" value="both_show" type="radio" <?php echo ( isset( $wfpFormGoalData['bar_display_sty'] ) && $wfpFormGoalData['bar_display_sty'] == 'both_show' ) ? 'checked' : ''; ?>  > <?php echo esc_html__( 'Both', 'wp-fundraising-donation' ); ?>
								</label>
							</li>
						</ul>
					</div>
				</li>
				<li class="wfdp-social-input-container">
					<div class="wfdp-social-label">
						<?php echo esc_html__( 'Color', 'wp-fundraising-donation' ); ?>
					</div>
					<div class="wfdp-social-input">
						<!-- <label for="progressbar"> <?php echo esc_html__( 'Color : ', 'wp-fundraising-donation' ); ?></label> -->
						<input type="hidden" id="progressbar" name="xs_submit_donation_data[goal_setup][bar_color]" class="wfdp_color_field" value="<?php echo isset( $wfpFormGoalData['bar_color'] ) ? esc_attr( $wfpFormGoalData['bar_color'] ) : '#0085ba'; ?>">
							
					</div>

				</li>
				<li class="wfdp-social-input-container">
					<div class="wfdp-social-label">
						<?php echo esc_html__( 'Goal Type', 'wp-fundraising-donation' ); ?>
					</div>
					<div class="wfdp-social-input">
						<ul class="xs-donate-option">
							<li>
								<label>
									<input class="xs_radio_filed" name="xs_submit_donation_data[goal_setup][goal_type]" value="terget_goal"  type="radio" <?php echo ( isset( $wfpFormGoalData['goal_type'] ) && $wfpFormGoalData['goal_type'] == 'terget_goal' ) ? 'checked' : 'checked'; ?> > <?php echo esc_html__( 'Target Goal ', 'wp-fundraising-donation' ); ?>
								</label>
							</li>
							<li>
								<label>
									<input class="xs_radio_filed" name="xs_submit_donation_data[goal_setup][goal_type]" value="terget_date"  type="radio" <?php echo ( isset( $wfpFormGoalData['goal_type'] ) && $wfpFormGoalData['goal_type'] == 'terget_date' ) ? 'checked' : ''; ?>  > <?php echo esc_html__( 'Target Date', 'wp-fundraising-donation' ); ?>
								</label>
							</li>
							<li>
								<label>
									<input class="xs_radio_filed" name="xs_submit_donation_data[goal_setup][goal_type]" value="terget_goal_date" type="radio" <?php echo ( isset( $wfpFormGoalData['goal_type'] ) && $wfpFormGoalData['goal_type'] == 'terget_goal_date' ) ? 'checked' : ''; ?>  > <?php echo esc_html__( 'Target Goal & Date', 'wp-fundraising-donation' ); ?>
								</label>
							</li>
							<li>
								<label>
									<input class="xs_radio_filed" name="xs_submit_donation_data[goal_setup][goal_type]" value="campaign_never_end" type="radio" <?php echo ( isset( $wfpFormGoalData['goal_type'] ) && $wfpFormGoalData['goal_type'] == 'campaign_never_end' ) ? 'checked' : ''; ?>  > <?php echo esc_html__( 'Campaign Never Ends', 'wp-fundraising-donation' ); ?>
								</label>
							</li>
						</ul>
					</div>

				</li>
			</ul>
			<ul class="wfdp-social_share">

				<li class="wfdp-social_share-section-title"><h3><?php echo esc_html__( 'Single Page Options(Default Set for All Campaign)', 'wp-fundraising-donation' ); ?></h3></li>
				<li class="wfdp-social-input-container">
					<div class="wfdp-social-label">
						<?php echo esc_html__( 'Enable Sidebar', 'wp-fundraising-donation' ); ?>
					</div>
					<div class="wfdp-social-input">
						<ul class="xs-donate-option">
							<li>
								<div class="xs-switch-button_wraper">
									<input class="xs_donate_switch_button" type="checkbox" id="donation_form_sidebar_enable__" <?php echo ( isset( $wfpFormSettingData['sidebar']['enable'] ) && $wfpFormSettingData['sidebar']['enable'] == 'Yes' ) ? 'checked' : ''; ?> name="xs_submit_donation_data[form_settings][sidebar][enable]" value="Yes">
									<label for="donation_form_sidebar_enable__" class="xs_donate_switch_button_label small xs-round"></label>
								</div>
							</li>
						</ul>
					</div>

				</li>
				<li class="wfdp-social-input-container">
					<div class="wfdp-social-label">
						<?php echo esc_html__( 'Hide Featured Info', 'wp-fundraising-donation' ); ?>
					</div>
					<div class="wfdp-social-input">
						<ul class="xs-donate-option">
							<li>
								<div class="xs-switch-button_wraper">
									<input class="xs_donate_switch_button" type="checkbox" id="donation_form_featured_enable__" <?php echo ( isset( $wfpFormSettingData['featured']['enable'] ) && $wfpFormSettingData['featured']['enable'] == 'Yes' ) ? 'checked' : ''; ?> name="xs_submit_donation_data[form_settings][featured][enable]" value="Yes">
									<label for="donation_form_featured_enable__" class="xs_donate_switch_button_label small xs-round"></label>
								</div>
							</li>
						</ul>
					</div>

				</li>
				<li class="wfdp-social-input-container">
					<div class="wfdp-social-label">
						<?php echo esc_html__( 'Hide Campaign Title', 'wp-fundraising-donation' ); ?>
					</div>
					<div class="wfdp-social-input">
						<ul class="xs-donate-option">
							<li>
								<div class="xs-switch-button_wraper">
									<input class="xs_donate_switch_button" type="checkbox" id="donation_form_single_title_enable__" <?php echo ( isset( $wfpFormSettingData['single_title']['enable'] ) && $wfpFormSettingData['single_title']['enable'] == 'Yes' ) ? 'checked' : ''; ?> name="xs_submit_donation_data[form_settings][single_title][enable]" value="Yes">
									<label for="donation_form_single_title_enable__" class="xs_donate_switch_button_label small xs-round"></label>
								</div>
							</li>
						</ul>
					</div>

				</li>
				<li class="wfdp-social-input-container">
					<div class="wfdp-social-label">
						<?php echo esc_html__( 'Hide Short Brief', 'wp-fundraising-donation' ); ?>
					</div>
					<div class="wfdp-social-input">
						<ul class="xs-donate-option">
							<li>
								<div class="xs-switch-button_wraper">
									<input class="xs_donate_switch_button" type="checkbox" id="donation_form_single_excerpt_enable__" <?php echo ( isset( $wfpFormSettingData['single_excerpt']['enable'] ) && $wfpFormSettingData['single_excerpt']['enable'] == 'Yes' ) ? 'checked' : ''; ?> name="xs_submit_donation_data[form_settings][single_excerpt][enable]" value="Yes">
									<label for="donation_form_single_excerpt_enable__" class="xs_donate_switch_button_label small xs-round"></label>
								</div>
							</li>
						</ul>
					</div>

				</li>
				<li class="wfdp-social-input-container">
					<div class="wfdp-social-label">
						<?php echo esc_html__( 'Hide Campaign Description', 'wp-fundraising-donation' ); ?>
					</div>
					<div class="wfdp-social-input">
						<ul class="xs-donate-option">
							<li>
								<div class="xs-switch-button_wraper">
									<input class="xs_donate_switch_button" type="checkbox" id="donation_form_single_content_enable__" <?php echo ( isset( $wfpFormSettingData['single_content']['enable'] ) && $wfpFormSettingData['single_content']['enable'] == 'Yes' ) ? 'checked' : ''; ?> name="xs_submit_donation_data[form_settings][single_content][enable]" value="Yes">
									<label for="donation_form_single_content_enable__" class="xs_donate_switch_button_label small xs-round"></label>
								</div>
							</li>
						</ul>
					</div>

				</li>
				<li class="wfdp-social-input-container">
					<div class="wfdp-social-label">
						<?php echo esc_html__( 'Hide Campaign Reviews', 'wp-fundraising-donation' ); ?>
					</div>
					<div class="wfdp-social-input">
						<ul class="xs-donate-option">
							<li>
								<div class="xs-switch-button_wraper">
									<input class="xs_donate_switch_button" type="checkbox" id="donation_form_single_review_enable__" <?php echo ( isset( $wfpFormSettingData['single_review']['enable'] ) && $wfpFormSettingData['single_review']['enable'] == 'Yes' ) ? 'checked' : ''; ?> name="xs_submit_donation_data[form_settings][single_review][enable]" value="Yes">
									<label for="donation_form_single_review_enable__" class="xs_donate_switch_button_label small xs-round"></label>
								</div>
							</li>
						</ul>
					</div>

				</li>
				<li class="wfdp-social-input-container">
					<div class="wfdp-social-label">
						<?php echo esc_html__( 'Hide Updates', 'wp-fundraising-donation' ); ?>
					</div>
					<div class="wfdp-social-input">
						<ul class="xs-donate-option">
							<li>
								<div class="xs-switch-button_wraper">
									<input class="xs_donate_switch_button" type="checkbox" id="donation_form_single_updates_enable__" <?php echo ( isset( $wfpFormSettingData['single_updates']['enable'] ) && $wfpFormSettingData['single_updates']['enable'] == 'Yes' ) ? 'checked' : ''; ?> name="xs_submit_donation_data[form_settings][single_updates][enable]" value="Yes">
									<label for="donation_form_single_updates_enable__" class="xs_donate_switch_button_label small xs-round"></label>
								</div>
							</li>
						</ul>
					</div>

				</li>
				<li class="wfdp-social-input-container">
					<div class="wfdp-social-label">
						<?php echo esc_html__( 'Hide Recent Funds', 'wp-fundraising-donation' ); ?>
					</div>
					<div class="wfdp-social-input">
						<ul class="xs-donate-option">
							<li>
								<div class="xs-switch-button_wraper">
									<input class="xs_donate_switch_button" type="checkbox" id="donation_form_single_recents_enable__" <?php echo ( isset( $wfpFormSettingData['single_recents']['enable'] ) && $wfpFormSettingData['single_recents']['enable'] == 'Yes' ) ? 'checked' : ''; ?> name="xs_submit_donation_data[form_settings][single_recents][enable]" value="Yes">
									<label for="donation_form_single_recents_enable__" class="xs_donate_switch_button_label small xs-round"></label>
								</div>
							</li>
						</ul>
					</div>

				</li>
				<li class="wfdp-social-input-container">
					<div class="wfdp-social-label">
						<?php echo esc_html__( 'Show Contributor Email', 'wp-fundraising-donation' ); ?>
					</div>
					<div class="wfdp-social-input">
						<ul class="xs-donate-option">
							<li>
								<div class="xs-switch-button_wraper">
									<input class="xs_donate_switch_button" type="checkbox" id="donation_form_contributor_enable__" <?php echo ( isset( $wfpFormSettingData['contributor']['enable'] ) && $wfpFormSettingData['contributor']['enable'] == 'Yes' ) ? 'checked' : ''; ?> name="xs_submit_donation_data[form_settings][contributor][enable]" value="Yes">
									<label for="donation_form_contributor_enable__" class="xs_donate_switch_button_label small xs-round"></label>
								</div>
							</li>
						</ul>
					</div>

				</li>
			</ul>
		</div>


		<button type="submit" name="submit_donate_display_setting" class="button button-primary button-large"><?php echo esc_html__( 'Save', 'wp-fundraising-donation' ); ?></button>
		</form>
	</div>
</div>
