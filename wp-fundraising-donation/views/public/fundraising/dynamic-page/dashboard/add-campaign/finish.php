<div class="intro-info short-info">
	<label for="camapign_post_country">
		<?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_finish_country', __( 'Select your Country', 'wp-fundraising' ) ) ); ?>
	</label>
	<?php
	$formSettingData = isset( $getMetaData->form_settings ) ? $getMetaData->form_settings : array();

	$defaultCountry = isset( $formSettingData->location->country ) ? $formSettingData->location->country : 'US-CA';
	?>
	<br/>
	<select class="wfp-require-filed wfp-select2-country xs-field wfp-input" name="campaign_meta_post[form_settings][location][country]" id="camapign_post_country">
		<?php
		if ( is_array( $countryList ) && sizeof( $countryList ) > 0 ) {

			foreach ( $countryList as $key => $value ) :
				$name             = isset( $value['info']['name'] ) ? $value['info']['name'] : '';
				$countryStateList = isset( $value['states'] ) ? $value['states'] : array();
				if ( is_array( $countryStateList ) && sizeof( $countryStateList ) > 0 ) {
					?>
				<optgroup label="<?php echo esc_html( $name ); ?>">
					<?php
					foreach ( $countryStateList as $keyState => $valueState ) :
						?>
					<option value="<?php echo esc_attr( $key . '-' . $keyState ); ?>" <?php echo esc_attr( ( $defaultCountry == $key . '-' . $keyState ) ? 'selected' : '' ); ?>> <?php echo esc_html( $name . ' -- ' . $valueState ); ?> </option>
					
						<?php
					endforeach;
					?>
				</optgroup>
					<?php
				} else {
					?>
				<option value="<?php echo esc_attr( $key ); ?>" <?php echo esc_attr( ( $defaultCountry == $key ) ? 'selected' : '' ); ?>> <?php echo esc_html( $name ); ?> </option>
					<?php
				}
			endforeach;
		}
		?>
	</select>
</div>
<div class="intro-info short-info">
	<label for="camapign_post_location">
		<?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_finish_location', __( 'Campaign Location', 'wp-fundraising' ) ) ); ?>
	</label>
	<?php
	$address = isset( $formSettingData->location->address ) ? $formSettingData->location->address : '';
	?>
	<input type="text" name="campaign_meta_post[form_settings][location][address]" id="camapign_post_location" value="<?php echo esc_attr( $address ); ?>" class="wfp-input" >
</div>
<div class="intro-info">
	<label for="camapign_post_location">
		<?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_finish_contributor_info', __( 'Contributor Email', 'wp-fundraising' ) ) ); ?>
	</label>
	<?php
	$con_enable     = isset( $formSettingData->contributor->enable ) ? $formSettingData->contributor->enable : '';
	$single_review  = isset( $formSettingData->single_review->enable ) ? $formSettingData->single_review->enable : '';
	$single_updates = isset( $formSettingData->single_updates->enable ) ? $formSettingData->single_updates->enable : '';
	?>
	<div class="xs-switch-button_wraper">
		<input class="xs_donate_switch_button" type="checkbox"  id="donation_form_contributor_info_enable" name="campaign_meta_post[form_settings][contributor][enable]" <?php echo ( $con_enable == 'Yes' ) ? 'checked' : ''; ?> value="Yes" >
		<label for="donation_form_contributor_info_enable" class="xs_donate_switch_button_label small xs-round"></label>
	</div>
	<span class="label-info"><?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_finish_contributor_info_message', __( 'Show contributor email on campaign the single page. ', 'wp-fundraising' ) ) ); ?></span>
</div>

<div class="intro-info">
	<label for="camapign_post_location">
		<?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_finish_review_enable', __( 'Disable Review', 'wp-fundraising' ) ) ); ?>
	</label>
	<div class="xs-switch-button_wraper">
		<input class="xs_donate_switch_button" type="checkbox"  id="donation_form_review_info_enable" name="campaign_meta_post[form_settings][single_review][enable]" <?php echo esc_attr( ( $single_review == 'Yes' ) ? 'checked' : '' ); ?> value="Yes" >
		<label for="donation_form_review_info_enable" class="xs_donate_switch_button_label small xs-round"></label>
	</div>
	<span class="label-info"> <?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_finish_review_enable_message', __( 'Disable user review list on campaign the single page.', 'wp-fundraising' ) ) ); ?></span>
</div>

<div class="intro-info">
	<label for="camapign_post_location">
		<?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_finish_update_enable', __( 'Disable Updates', 'wp-fundraising' ) ) ); ?>
	</label>
	<div class="xs-switch-button_wraper">
		<input class="xs_donate_switch_button" type="checkbox"  id="donation_form_single_updates_info_enable" name="campaign_meta_post[form_settings][single_updates][enable]" <?php echo esc_attr( ( $single_updates == 'Yes' ) ? 'checked' : '' ); ?> value="Yes" >
		<label for="donation_form_single_updates_info_enable" class="xs_donate_switch_button_label small xs-round"></label>
	</div>
	<span class="label-info"> <?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_finish_update_enable_message', __( 'Disable update info on campaign the single page.', 'wp-fundraising' ) ) ); ?></span>
</div>

<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('.wfp-select2-country').select2();
	});
</script>
