<fieldset class="xs-donate-field-wrap ">
	<span class="xs-donate-field-label"><?php echo esc_html__( 'Short-code', 'wp-fundraising' ); ?></span>
	<div class="donation-none-float xs-donate-field-short-code">
		<input class="xs-field xs-text-field donate_text_filed donate_shortcode_wp" type="text" id="wp_doante_shortcode" value='[wfp-forms form-id="<?php echo esc_attr( $post->ID ); ?>" form-style="<?php echo ! empty( $formDesignData->styles ) ? esc_attr( $formDesignData->styles ) : 'all_fields'; ?>" modal="<?php echo ( isset( $formDesignData->modal_show ) && $formDesignData->modal_show == 'Yes' ) ? 'Yes' : 'No'; ?>"]' readonly="readonly" >
		<button type="button" onclick="wdp_copyTextData('wp_doante_shortcode');" class="xs_copy_button"> <span class="dashicons dashicons-admin-page"></span> </button>
		
		<span class="xs-donetion-field-description"><?php echo esc_html__( 'This is sortcode of form. Tag: form-style (all_fields, only_button) & Are you show form in modal popup, then use(Yes, No).', 'wp-fundraising' ); ?></span>
	</div>
</fieldset>

<?php if ( $showSidebarSett == 'Yes' ) : ?>
	<fieldset class="xs-donate-field-wrap ">
		<span class="xs-donate-field-label"><?php echo esc_html__( 'Enable Sidebar', 'wp-fundraising' ); ?></span>
		<div class="donation-none-float">
			<ul class="xs-donate-option">
				<li>
					<div class="xs-switch-button_wraper">
						<input class="xs_donate_switch_button" type="checkbox" id="donation_form_sidebar_enable__" <?php echo ( $enableSidebar == 'Yes' ) ? 'checked' : ''; ?> name="xs_submit_donation_data[form_settings][sidebar][enable]" value="Yes">
						<label for="donation_form_sidebar_enable__" class="xs_donate_switch_button_label small xs-round"></label>
					</div>
				</li>
				<li><span for="donation_form_sidebar_enable__" class="xs-donetion-field-description hidden"><?php echo esc_html__( 'Enable Sidebar Content in single page for this Campaign', 'wp-fundraising' ); ?></span></li>
			</ul>
		</div>
	</fieldset>
<?php endif; ?>


<fieldset class="xs-donate-field-wrap ">
	<span class="xs-donate-field-label"><?php echo esc_html__( 'Hide Campaign author', 'wp-fundraising' ); ?></span>
	<div class="donation-none-float">
		<ul class="xs-donate-option">
			<li>
				<div class="xs-switch-button_wraper">
					<input class="xs_donate_switch_button" type="checkbox" id="donation_form_show_campaign_author__" <?php echo ( $hide_campaign_author == 'Yes' ) ? 'checked' : ''; ?> name="xs_submit_donation_data[form_settings][campaign_author][enable]" value="Yes">
					<label for="donation_form_show_campaign_author__" class="xs_donate_switch_button_label small xs-round"></label>
				</div>
			</li>
			<li>
				<span class="xs-donetion-field-description hidden"><?php echo esc_html__( 'Show or hide campaign author name in campaign', 'wp-fundraising' ); ?></span>
			</li>
		</ul>
	</div>
</fieldset>


<?php if ( $hideFeaturedSett != 'Yes' ) : ?>
	<fieldset class="xs-donate-field-wrap ">
		<span class="xs-donate-field-label"><?php echo esc_html__( 'Hide Featured Info', 'wp-fundraising' ); ?></span>
		<div class="donation-none-float">
			<ul class="xs-donate-option">
				<li>
					<div class="xs-switch-button_wraper">
						<input class="xs_donate_switch_button" type="checkbox" id="donation_form_featured_enable__" <?php echo ( $enableFeatured == 'Yes' ) ? 'checked' : ''; ?> name="xs_submit_donation_data[form_settings][featured][enable]" value="Yes">
						<label for="donation_form_featured_enable__" class="xs_donate_switch_button_label small xs-round"></label>
					</div>
				</li>
				<li>
					<span for="donation_form_featured_enable__" class="xs-donetion-field-description hidden"><?php echo esc_html__( 'Disable Featured like as Image, Video, Gallery in single page for this Campaign', 'wp-fundraising' ); ?></span>
				</li>
			</ul>
		</div>
	</fieldset>
<?php endif; ?>

<?php if ( $hideSingleTitleSett != 'Yes' ) : ?>
	<fieldset class="xs-donate-field-wrap ">
		<span class="xs-donate-field-label"><?php echo esc_html__( 'Hide Campaign Title', 'wp-fundraising' ); ?></span>
		<div class="donation-none-float">
			<ul class="xs-donate-option">
				<li>
					<div class="xs-switch-button_wraper">
						<input class="xs_donate_switch_button" type="checkbox" id="donation_form_single_title_enable__" <?php echo ( $enableTitleSIngle == 'Yes' ) ? 'checked' : ''; ?> name="xs_submit_donation_data[form_settings][single_title][enable]" value="Yes">
						<label for="donation_form_single_title_enable__" class="xs_donate_switch_button_label small xs-round"></label>
					</div>
				</li>
				<li>
					<span for="donation_form_single_title_enable__" class="xs-donetion-field-description hidden"><?php echo esc_html__( 'Disable Campaign Title in single page for this Campaign', 'wp-fundraising' ); ?></span>
				</li>
			</ul>
		</div>
	</fieldset>
<?php endif; ?>

<?php if ( $hideShortBriefSett != 'Yes' ) : ?>
	<fieldset class="xs-donate-field-wrap ">
		<span class="xs-donate-field-label"><?php echo esc_html__( 'Hide Short Brief', 'wp-fundraising' ); ?></span>
		<div class="donation-none-float">
			<ul class="xs-donate-option">
				<li>
					<div class="xs-switch-button_wraper">
						<input class="xs_donate_switch_button" type="checkbox" id="donation_form_single_excerpt_enable__" <?php echo ( $enableTitleExcerpt == 'Yes' ) ? 'checked' : ''; ?> name="xs_submit_donation_data[form_settings][single_excerpt][enable]" value="Yes">
						<label for="donation_form_single_excerpt_enable__" class="xs_donate_switch_button_label small xs-round"></label>
					</div>
				</li>
				<li>
					<span for="donation_form_single_excerpt_enable__" class="xs-donetion-field-description hidden"><?php echo esc_html__( 'Disable Short Brief in single page for this Campaign', 'wp-fundraising' ); ?></span>
				</li>
			</ul>
		</div>
	</fieldset>
<?php endif; ?>

<?php if ( $hideDescriptionSett != 'Yes' ) : ?>
	<fieldset class="xs-donate-field-wrap ">
		<span class="xs-donate-field-label"><?php echo esc_html__( 'Hide Campaign Description', 'wp-fundraising' ); ?></span>
		<div class="donation-none-float">
			<ul class="xs-donate-option">
				<li>
					<div class="xs-switch-button_wraper">
						<input class="xs_donate_switch_button" type="checkbox" id="donation_form_single_content_enable__" <?php echo ( $enableSingleContent == 'Yes' ) ? 'checked' : ''; ?> name="xs_submit_donation_data[form_settings][single_content][enable]" value="Yes">
						<label for="donation_form_single_content_enable__" class="xs_donate_switch_button_label small xs-round"></label>
					</div>
				</li>
				<li>
					<span for="donation_form_single_content_enable__" class="xs-donetion-field-description hidden"><?php echo esc_html__( 'Disable Campaign Content in single page for this Campaign', 'wp-fundraising' ); ?></span>
				</li>
			</ul>
		</div>
	</fieldset>
<?php endif; ?>

<?php if ( $hideReviewTabSett != 'Yes' ) : ?>
	<fieldset class="xs-donate-field-wrap ">
		<span class="xs-donate-field-label"><?php echo esc_html__( 'Hide Campaign Reviews', 'wp-fundraising' ); ?></span>
		<div class="donation-none-float">
			<ul class="xs-donate-option">
				<li>
					<div class="xs-switch-button_wraper">
						<input class="xs_donate_switch_button" type="checkbox" id="donation_form_single_review_enable__" <?php echo ( $enableSingleReview == 'Yes' ) ? 'checked' : ''; ?> name="xs_submit_donation_data[form_settings][single_review][enable]" value="Yes">
						<label for="donation_form_single_review_enable__" class="xs_donate_switch_button_label small xs-round"></label>
					</div>
				</li>
				<li>
					<span for="donation_form_single_review_enable__" class="xs-donetion-field-description hidden"><?php echo esc_html__( 'Disable Campaign Review in single page for this Campaign', 'wp-fundraising' ); ?></span>
				</li>
			</ul>
		</div>
	</fieldset>
<?php endif; ?>


<?php if ( $hideUpdateTabSett != 'Yes' ) : ?>
	<fieldset class="xs-donate-field-wrap ">
		<span class="xs-donate-field-label"><?php echo esc_html__( 'Hide Updates', 'wp-fundraising' ); ?></span>
		<div class="donation-none-float">
			<ul class="xs-donate-option">
				<li>
					<div class="xs-switch-button_wraper">
						<input class="xs_donate_switch_button" type="checkbox" id="donation_form_single_updates_enable__" <?php echo ( $enableSingleUpdates == 'Yes' ) ? 'checked' : ''; ?> name="xs_submit_donation_data[form_settings][single_updates][enable]" value="Yes">
						<label for="donation_form_single_updates_enable__" class="xs_donate_switch_button_label small xs-round"></label>
					</div>
				</li>
				<li>
					<span for="donation_form_single_updates_enable__" class="xs-donetion-field-description hidden"><?php echo esc_html__( 'Disable Campaign Updates in single page.', 'wp-fundraising' ); ?></span>
				</li>
			</ul>
		</div>
	</fieldset>
<?php endif; ?>


<?php if ( $hideRecentFundTabSett != 'Yes' ) : ?>
	<fieldset class="xs-donate-field-wrap ">
		<span class="xs-donate-field-label"><?php echo esc_html__( 'Hide Recent Funds', 'wp-fundraising' ); ?></span>
		<div class="donation-none-float">
			<ul class="xs-donate-option">
				<li>
					<div class="xs-switch-button_wraper">
						<input class="xs_donate_switch_button" type="checkbox" id="donation_form_single_recents_enable__" <?php echo ( $enableSingleRecents == 'Yes' ) ? 'checked' : ''; ?> name="xs_submit_donation_data[form_settings][single_recents][enable]" value="Yes">
						<label for="donation_form_single_recents_enable__" class="xs_donate_switch_button_label small xs-round"></label>
					</div>
				</li>
				<li>
					<span for="donation_form_single_recents_enable__" class="xs-donetion-field-description hidden"><?php echo esc_html__( 'Disable Donor list in single page.', 'wp-fundraising' ); ?></span>
				</li>
			</ul>
		</div>
	</fieldset>
<?php endif; ?>


<?php if ( $showContributorEmailSett == 'Yes' ) : ?>
	<fieldset class="xs-donate-field-wrap ">
		<span class="xs-donate-field-label"><?php echo esc_html__( 'Show Contributor Email', 'wp-fundraising' ); ?></span>
		<div class="donation-none-float">
			<ul class="xs-donate-option">
				<li>
					<div class="xs-switch-button_wraper">
						<input class="xs_donate_switch_button" type="checkbox" id="donation_form_contributor_enable__" <?php echo ( $enableContributorEmail == 'Yes' ) ? 'checked' : ''; ?> name="xs_submit_donation_data[form_settings][contributor][enable]" value="Yes">
						<label for="donation_form_contributor_enable__" class="xs_donate_switch_button_label small xs-round"></label>
					</div>
				</li>
				<li>
					<span for="donation_form_contributor_enable__" class="xs-donetion-field-description hidden"><?php echo esc_html__( 'Show contributors Email in single page.', 'wp-fundraising' ); ?></span>
				</li>
			</ul>
		</div>
	</fieldset>
<?php endif; ?>

<fieldset class="xs-donate-field-wrap xs-clearfix">
	<span class="xs-donate-field-label"><?php echo esc_html__( 'Page Width', 'wp-fundraising' ); ?></span>
	<div class="xs-field-body">

		<input name="xs_submit_donation_data[donation][page_width]" value="<?php echo esc_attr( $page_width ); ?>" type="number"/>

		<span class="xs-donetion-field-description"><?php echo esc_html__( 'Set what is the campaign page width. Value will be in px unit, 0 means it will inherit default', 'wp-fundraising' ); ?></span>
	</div>
</fieldset>


<fieldset class="xs-donate-field-wrap ">
	<span class="xs-donate-field-label"><?php echo esc_html__( 'Custom Form Class', 'wp-fundraising' ); ?></span>
	<div class="xs-field-body xs-repeater-field-wrap">
		<input class="xs-text-field xs_block_input" type="text" style="" name="xs_submit_donation_data[form_design][custom_class]" id="xs_donate_forms_custom_class" value="<?php echo isset( $formDesignData->custom_class ) ? esc_attr( $formDesignData->custom_class ) : ''; ?>" placeholder="Enter Class Name" class="xs-field xs-money-field">
		<span class="xs-donetion-field-description"><?php echo esc_html__( 'This class for custom design.', 'wp-fundraising' ); ?></span>
	</div>
	<div class="xs-clearfix"></div>
</fieldset>
<fieldset class="xs-donate-field-wrap">
	<span class="xs-donate-field-label"><?php echo esc_html__( 'Custom Form Id', 'wp-fundraising' ); ?></span>
	<div class="xs-field-body xs-repeater-field-wrap">
		<input class="xs-text-field xs_block_input" type="text" style="" name="xs_submit_donation_data[form_design][custom_id]" id="xs_donate_forms_custom_id" value="<?php echo isset( $formDesignData->custom_id ) ? esc_attr( $formDesignData->custom_id ) : ''; ?>" placeholder="Enter Id Name" class="xs-field xs-money-field">
		<span class="xs-donetion-field-description"><?php echo esc_html__( 'Declare form id.', 'wp-fundraising' ); ?></span>
	</div>
	<div class="xs-clearfix"></div>
</fieldset>


