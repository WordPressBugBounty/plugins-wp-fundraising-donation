<?php 
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

?>
<div class="intro-info short-info">
	<label for="camapign_post_country">
		<?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_finish_country', __( 'Select your Country', 'wp-fundraising' ) ) ); ?>
	</label>
	<?php
	$wfpFormSettingData = isset( $wfpGetMetaData->form_settings ) ? $wfpGetMetaData->form_settings : array();

	$wfpDefaultCountry = isset( $wfpFormSettingData->location->country ) ? $wfpFormSettingData->location->country : 'US-CA';
	?>
	<br/>
	<select class="wfp-require-filed wfp-select2-country xs-field wfp-input" name="campaign_meta_post[form_settings][location][country]" id="camapign_post_country">
		<?php
		if ( is_array( $wfpCountryList ) && sizeof( $wfpCountryList ) > 0 ) {

			foreach ( $wfpCountryList as $wfp_key => $wfp_value ) :
				$wfp_name             = isset( $wfp_value['info']['name'] ) ? $wfp_value['info']['name'] : '';
				$wfpCountryStateList = isset( $wfp_value['states'] ) ? $wfp_value['states'] : array();
				if ( is_array( $wfpCountryStateList ) && sizeof( $wfpCountryStateList ) > 0 ) {
					?>
				<optgroup label="<?php echo esc_html( $wfp_name ); ?>">
					<?php
					foreach ( $wfpCountryStateList as $wfpKeyState => $valueState ) :
						?>
					<option value="<?php echo esc_attr( $wfp_key . '-' . $wfpKeyState ); ?>" <?php echo esc_attr( ( $wfpDefaultCountry == $wfp_key . '-' . $wfpKeyState ) ? 'selected' : '' ); ?>> <?php echo esc_html( $wfp_name . ' -- ' . $valueState ); ?> </option>
					
						<?php
					endforeach;
					?>
				</optgroup>
					<?php
				} else {
					?>
				<option value="<?php echo esc_attr( $wfp_key ); ?>" <?php echo esc_attr( ( $wfpDefaultCountry == $wfp_key ) ? 'selected' : '' ); ?>> <?php echo esc_html( $wfp_name ); ?> </option>
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
	$wfp_address = isset( $wfpFormSettingData->location->address ) ? $wfpFormSettingData->location->address : '';
	?>
	<input type="text" name="campaign_meta_post[form_settings][location][address]" id="camapign_post_location" value="<?php echo esc_attr( $wfp_address ); ?>" class="wfp-input" >
</div>
<div class="intro-info">
	<label for="camapign_post_location">
		<?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_finish_contributor_info', __( 'Contributor Email', 'wp-fundraising' ) ) ); ?>
	</label>
	<?php
	$wfp_con_enable     = isset( $wfpFormSettingData->contributor->enable ) ? $wfpFormSettingData->contributor->enable : '';
	$wfp_single_review  = isset( $wfpFormSettingData->single_review->enable ) ? $wfpFormSettingData->single_review->enable : '';
	$wfp_single_updates = isset( $wfpFormSettingData->single_updates->enable ) ? $wfpFormSettingData->single_updates->enable : '';
	?>
	<div class="xs-switch-button_wraper">
		<input class="xs_donate_switch_button" type="checkbox"  id="donation_form_contributor_info_enable" name="campaign_meta_post[form_settings][contributor][enable]" <?php echo ( $wfp_con_enable == 'Yes' ) ? 'checked' : ''; ?> value="Yes" >
		<label for="donation_form_contributor_info_enable" class="xs_donate_switch_button_label small xs-round"></label>
	</div>
	<span class="label-info"><?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_finish_contributor_info_message', __( 'Show contributor email on campaign the single page. ', 'wp-fundraising' ) ) ); ?></span>
</div>

<div class="intro-info">
	<label for="camapign_post_location">
		<?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_finish_review_enable', __( 'Disable Review', 'wp-fundraising' ) ) ); ?>
	</label>
	<div class="xs-switch-button_wraper">
		<input class="xs_donate_switch_button" type="checkbox"  id="donation_form_review_info_enable" name="campaign_meta_post[form_settings][single_review][enable]" <?php echo esc_attr( ( $wfp_single_review == 'Yes' ) ? 'checked' : '' ); ?> value="Yes" >
		<label for="donation_form_review_info_enable" class="xs_donate_switch_button_label small xs-round"></label>
	</div>
	<span class="label-info"> <?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_finish_review_enable_message', __( 'Disable user review list on campaign the single page.', 'wp-fundraising' ) ) ); ?></span>
</div>

<div class="intro-info">
	<label for="camapign_post_location">
		<?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_finish_update_enable', __( 'Disable Updates', 'wp-fundraising' ) ) ); ?>
	</label>
	<div class="xs-switch-button_wraper">
		<input class="xs_donate_switch_button" type="checkbox"  id="donation_form_single_updates_info_enable" name="campaign_meta_post[form_settings][single_updates][enable]" <?php echo esc_attr( ( $wfp_single_updates == 'Yes' ) ? 'checked' : '' ); ?> value="Yes" >
		<label for="donation_form_single_updates_info_enable" class="xs_donate_switch_button_label small xs-round"></label>
	</div>
	<span class="label-info"> <?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_finish_update_enable_message', __( 'Disable update info on campaign the single page.', 'wp-fundraising' ) ) ); ?></span>
</div>

<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('.wfp-select2-country').select2();
	});
</script>
