<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

require \WFP_Fundraising::plugin_dir() . 'country-module/country-info.php';

?>
<div class="wfdp-payment-section" >
	<div class="wfdp-payment-headding">
		<h2><?php echo esc_html__( 'Setup General Settings', 'wp-fundraising-donation' ); ?></h2>
	</div>
	<div class="wfdp-payment-gateway">
		<form action="<?php echo esc_url( admin_url() . 'edit.php?post_type=' . self::post_type() . '&page=settings&tab=general' ); ?>" method="post">
		<?php wp_nonce_field( 'wpf_save_settings', 'wpf_settings_nonce' ); ?>
		<div class="wfdp-payment-inputs-container">
			<ul class="wfdp-social_share">
				<li class="wfdp-social_share-section-title"><h3><?php echo esc_html__( 'Currency options', 'wp-fundraising-donation' ); ?></h3></li>
				<li class="wfdp-social-input-container">
					<div class="wfdp-social-label">
						<?php echo esc_html__( 'Currency', 'wp-fundraising-donation' ); ?>
					</div>
					<div class="wfdp-social-input">
						<?php

						$wfpDefaultCountry = isset( $wfpGetMetaGeneral['location']['country'] ) ? $wfpGetMetaGeneral['location']['country'] : 'US-CA';

						$wfpOnlyCOuntry = explode( '-', $wfpDefaultCountry );
						$wfpDefultCode  = isset( $wfpCountryList[ $wfpOnlyCOuntry[0] ]['currency']['code'] ) ? $wfpCountryList[ $wfpOnlyCOuntry[0] ]['currency']['code'] : 'USD';

						$wfpDefaultCurrency = isset( $wfpGetMetaGeneral['currency']['name'] ) ? $wfpGetMetaGeneral['currency']['name'] : $wfpOnlyCOuntry[0] . '-' . $wfpDefultCode;

						// Locale defaults for the selected currency's country, used below as the
						// fallback for symbol position and the separator/decimal fields.
						$wfpCurrencyCountry = current( explode( '-', $wfpDefaultCurrency ) );
						$localListDefult    = isset( $wfpCountryList[ $wfpCurrencyCountry ]['currency'] ) ? $wfpCountryList[ $wfpCurrencyCountry ]['currency'] : array();

						?>
						<select class="regular-text wfp-select2-country" name="xs_submit_settings_data_general[options][currency][name]">
							<?php
							if ( is_array( $wfpCountryList ) && sizeof( $wfpCountryList ) > 0 ) {
								foreach ( $wfpCountryList as $wfp_key => $wfp_value ) :
									$wfp_name    = isset( $wfp_value['info']['name'] ) ? $wfp_value['info']['name'] : '';
									$wfp_code    = isset( $wfp_value['currency']['code'] ) ? $wfp_value['currency']['code'] : '';
									$wfpSymbols = isset( $wfp_value['currency']['symbol'] ) ? $wfp_value['currency']['symbol'] : '';
									$wfpSymbols = strlen( $wfpSymbols ) > 0 ? '(' . $wfpSymbols . ')' : '';
									?>
									<option value="<?php echo esc_attr( $wfp_key . '-' . $wfp_code ); ?>" <?php echo ( $wfpDefaultCurrency == $wfp_key . '-' . $wfp_code ) ? 'selected' : ''; ?>> <?php echo esc_html( $wfp_name . ' -- ' . $wfp_code . $wfpSymbols ); ?> </option>
									<?php

								endforeach;
							}
							?>
						</select>
					</div>
				</li>
				<li class="wfdp-social-input-container">
					<div class="wfdp-social-label">
						<?php echo esc_html__( 'Symbol Position', 'wp-fundraising-donation' ); ?>
					</div>
					<div class="wfdp-social-input">
					<?php
					$wfpDefultPositionCountry = isset( $localListDefult['currency_pos'] ) ? $localListDefult['currency_pos'] : 'right';
					$wfpDefaultPosition       = isset( $wfpGetMetaGeneral['currency']['position'] ) ? $wfpGetMetaGeneral['currency']['position'] : $wfpDefultPositionCountry;
					?>
						<select class="regular-text xs-text_small" name="xs_submit_settings_data_general[options][currency][position]">
							<option value="left" <?php echo ( $wfpDefaultPosition == 'left' ) ? 'selected' : ''; ?> > <?php echo esc_html__( 'Left', 'wp-fundraising-donation' ); ?></option>
							<option value="right"<?php echo ( $wfpDefaultPosition == 'right' ) ? 'selected' : ''; ?> > <?php echo esc_html__( 'Right', 'wp-fundraising-donation' ); ?></option>
						</select>
					</div>
				</li>
				<li class="wfdp-social-input-container">
					<div class="wfdp-social-label">
						<?php echo esc_html__( 'Thousand separator', 'wp-fundraising-donation' ); ?>
					</div>
					<div class="wfdp-social-input">
					<?php
					$wfp_defaultThou_seperatorCountry = isset( $localListDefult['thousand'] ) ? $localListDefult['thousand'] : ',';
					$wfp_defaultThou_seperator        = isset( $wfpGetMetaGeneral['currency']['thou_seperator'] ) ? $wfpGetMetaGeneral['currency']['thou_seperator'] : $wfp_defaultThou_seperatorCountry;
					?>
						<input type="text" class="regular-text xs-text_small" name="xs_submit_settings_data_general[options][currency][thou_seperator]" value="<?php echo esc_attr( $wfp_defaultThou_seperator ); ?>">
						<span class="xs-donetion-field-description hidden"><?php echo esc_html__( 'Use Thousand Seperator in Display Currency.', 'wp-fundraising-donation' ); ?></span>
					</div>

				</li>
				<li class="wfdp-social-input-container">
					<div class="wfdp-social-label">
						<?php echo esc_html__( 'Decimal separator', 'wp-fundraising-donation' ); ?>
					</div>
					<div class="wfdp-social-input">
					<?php
					$wfp_defaultDecimal_seperatorCountry = isset( $localListDefult['decimal'] ) ? $localListDefult['decimal'] : '.';
					$wfp_defaultDecimal_seperator        = isset( $wfpGetMetaGeneral['currency']['decimal_seperator'] ) ? $wfpGetMetaGeneral['currency']['decimal_seperator'] : $wfp_defaultDecimal_seperatorCountry;
					?>
						<input type="text" class="regular-text xs-text_small" name="xs_submit_settings_data_general[options][currency][decimal_seperator]" value="<?php echo esc_attr( $wfp_defaultDecimal_seperator ); ?>">
						
						<span class="xs-donetion-field-description hidden"><?php echo esc_html__( 'Use Decimal Seperator in Display Currency.', 'wp-fundraising-donation' ); ?></span>
					</div>

				</li>
				<li class="wfdp-social-input-container">
					<div class="wfdp-social-label">
						<?php echo esc_html__( 'Number of decimals', 'wp-fundraising-donation' ); ?>
					</div>
					<div class="wfdp-social-input">

					<?php

					$wfpDefaultNumberDecimalCountry = isset( $localListDefult['num_decimals'] ) ? $localListDefult['num_decimals'] : '2';
					$wfpDefaultNumberDecimal        = isset( $wfpGetMetaGeneral['currency']['number_decimal'] ) ? $wfpGetMetaGeneral['currency']['number_decimal'] : $wfpDefaultNumberDecimalCountry;
					?>
						<input type="number" min="0" class="regular-text xs-text_small" name="xs_submit_settings_data_general[options][currency][number_decimal]" value="<?php echo esc_attr( $wfpDefaultNumberDecimal ); ?>">
						<span class="xs-donetion-field-description hidden"><?php echo esc_html__( 'Show Decimal Number in Display Currency.', 'wp-fundraising-donation' ); ?></span>
					</div>

				</li>
				<!--
				<li>
					<div>
						<?php // echo esc_html__('Display Currency', 'wp-fundraising-donation'); ?>
					</div>
					<div>
					<?php

					// $wfpDefultDisplayCurrency = isset($wfpGetMetaGeneral['currency']['display']) ? $wfpGetMetaGeneral['currency']['display'] : 'symbol';
					?>
						<select class="regular-text " name="xs_submit_settings_data_general[options][currency][display]">
							<option value="code" <?php // echo ($wfpDefultDisplayCurrency == 'code') ? 'selected' : ''; ?> > <?php // echo esc_html__('Code (USD)', 'wp-fundraising-donation'); ?></option>
							<option value="code" <?php // echo ($wfpDefultDisplayCurrency == 'code') ? 'selected' : ''; ?> > <?php // echo esc_html__('Code (USD)', 'wp-fundraising-donation'); ?></option>
							<option value="symbol"<?php // echo ($wfpDefultDisplayCurrency == 'symbol') ? 'selected' : ''; ?> > <?php // echo esc_html__('Symbol ($)', 'wp-fundraising-donation'); ?></option>
							<option value="both"<?php // echo ($wfpDefultDisplayCurrency == 'both') ? 'selected' : ''; ?> > <?php // echo esc_html__('($) Both (USD)', 'wp-fundraising-donation'); ?></option>
						</select>
						<br/>
						<span class="xs-donetion-field-description hidden"><?php // echo esc_html__('Use Currency symbol and Currency Code in Display Currency.', 'wp-fundraising-donation'); ?></span>
					</div>
				</li>-->
				<li class="wfdp-social-input-container wfdp-social-no-border">
					<div class="wfdp-social-label">
						<?php echo esc_html__( 'Use space with symbol', 'wp-fundraising-donation' ); ?>
					</div>

					<div class="wfdp-social-switch">
						<div class="xs-switch-button_wraper">

							<?php

							$wfp_defaultUse_space = isset( $wfpGetMetaGeneral['currency']['use_space'] ) ? $wfpGetMetaGeneral['currency']['use_space'] : 'off';
							?>

							<input class="xs_donate_switch_button" type="checkbox" id="donation_form_currency_enable__space" <?php echo ( $wfp_defaultUse_space == 'on' ) ? 'checked' : ''; ?> name="xs_submit_settings_data_general[options][currency][use_space]" value="on">
							<label for="donation_form_currency_enable__space" class="xs_donate_switch_button_label small xs-round"></label>
						</div>
						<span class="xs-donetion-field-description hidden"><?php echo esc_html__( 'Use space in display currency.', 'wp-fundraising-donation' ); ?></span>
					</div>


				</li>
			</ul>
			<ul class="wfdp-social_share">
				<li class="wfdp-social_share-section-title wfp-disabled-div <?php echo ( $wfpGateCampaignData == 'woocommerce' ) ? 'wfp-disabled' : ''; ?>"><h3><?php echo esc_html__( 'Location', 'wp-fundraising-donation' ); ?></h3></li>

				<li class="wfdp-social-input-container wfp-disabled-div <?php echo ( $wfpGateCampaignData == 'woocommerce' ) ? 'wfp-disabled' : ''; ?>">
					<div class="wfdp-social-label">
						<?php echo esc_html__( 'Address Line', 'wp-fundraising-donation' ); ?>
					</div>
					<div class="wfdp-social-input">
					<?php
					$wfpDefaultAddress = isset( $wfpGetMetaGeneral['location']['address'] ) ? $wfpGetMetaGeneral['location']['address'] : '';
					?>
						<input type="text" class="regular-text" name="xs_submit_settings_data_general[options][location][address]" value="<?php echo esc_attr( $wfpDefaultAddress ); ?>">
					</div>

				</li>
				<li class="wfdp-social-input-container wfp-disabled-div <?php echo ( $wfpGateCampaignData == 'woocommerce' ) ? 'wfp-disabled' : ''; ?>">
					<div class="wfdp-social-label">
						<?php echo esc_html__( 'City', 'wp-fundraising-donation' ); ?>
					</div>
					<div class="wfdp-social-input">
					<?php
					$wfpDefaultCity = isset( $wfpGetMetaGeneral['location']['city'] ) ? $wfpGetMetaGeneral['location']['city'] : '';
					?>
						<input type="text" class="regular-text" name="xs_submit_settings_data_general[options][location][city]" value="<?php echo esc_attr( $wfpDefaultCity ); ?>">
					</div>

				</li>
				<li class="wfdp-social-input-container wfp-disabled-div <?php echo ( $wfpGateCampaignData == 'woocommerce' ) ? 'wfp-disabled' : ''; ?>">
					<div class="wfdp-social-label">
						<?php echo esc_html__( 'Country / State', 'wp-fundraising-donation' ); ?>
					</div>
					<div class="wfdp-social-input">
						<?php

						$wfpDefaultCountry = isset( $wfpGetMetaGeneral['location']['country'] ) ? $wfpGetMetaGeneral['location']['country'] : 'US-CA';
						?>
						<select class="regular-text wfp-select2-country" name="xs_submit_settings_data_general[options][location][country]">
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
										<option value="<?php echo esc_attr( $wfp_key . '-' . $wfpKeyState ); ?>" <?php echo ( $wfpDefaultCountry == $wfp_key . '-' . $wfpKeyState ) ? 'selected' : ''; ?>> <?php echo esc_html( $wfp_name . ' -- ' . $valueState ); ?> </option>
										
											<?php
										endforeach;
										?>
									</optgroup>
										<?php
									} else {
										?>
									<option value="<?php echo esc_attr( $wfp_key ); ?>" <?php echo ( $wfpDefaultCountry == $wfp_key ) ? 'selected' : ''; ?>> <?php esc_html( $wfp_name ); ?> </option>
										<?php
									}
								endforeach;
							}
							?>
						</select>
					</div>
				</li>

				<li class="wfdp-social-input-container wfp-disabled-div <?php echo ( $wfpGateCampaignData == 'woocommerce' ) ? 'wfp-disabled' : ''; ?>">
					<div class="wfdp-social-label">
						<?php echo esc_html__( 'Postcode / ZIP', 'wp-fundraising-donation' ); ?>
					</div>
					<div class="wfdp-social-input">
					<?php
					$wfpDefaultPostcode = isset( $wfpGetMetaGeneral['location']['postcode'] ) ? $wfpGetMetaGeneral['location']['postcode'] : '';
					?>
						<input type="text" class="regular-text xs-text_small" name="xs_submit_settings_data_general[options][location][postcode]" value="<?php echo esc_attr( $wfpDefaultPostcode ); ?>">
					</div>

				</li>
			</ul>
		</div>

		<button type="submit" name="submit_donate_general_setting" class="button button-primary button-large"><?php echo esc_html__( 'Save', 'wp-fundraising-donation' ); ?></button>
		</form>
	</div>
</div>

<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('.wfp-select2-country').select2();
	});
</script>
