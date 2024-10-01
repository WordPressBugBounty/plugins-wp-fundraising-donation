<?php
require \WFP_Fundraising::plugin_dir() . 'country-module/country-info.php';

?>
<div class="wfdp-payment-section" >
	<div class="wfdp-payment-headding">
		<h2><?php echo esc_html__( 'Setup General Settings', 'wp-fundraising' ); ?></h2>
	</div>
	<div class="wfdp-payment-gateway">
		<form action="<?php echo esc_url( admin_url() . 'edit.php?post_type=' . self::post_type() . '&page=settings&tab=general' ); ?>" method="post">
		<?php wp_nonce_field( 'wpf_save_settings', 'wpf_settings_nonce' ); ?>
		<div class="wfdp-payment-inputs-container">
			<ul class="wfdp-social_share">
				<li class="wfdp-social_share-section-title"><h3><?php echo esc_html__( 'Currency options', 'wp-fundraising' ); ?></h3></li>
				<li class="wfdp-social-input-container">
					<div class="wfdp-social-label">
						<?php echo esc_html__( 'Currency', 'wp-fundraising' ); ?>
					</div>
					<div class="wfdp-social-input">
						<?php

						$defaultCountry = isset( $getMetaGeneral['location']['country'] ) ? $getMetaGeneral['location']['country'] : 'US-CA';

						$onlyCOuntry = explode( '-', $defaultCountry );
						$defultCode  = isset( $countryList[ $onlyCOuntry[0] ]['currency']['code'] ) ? $countryList[ $onlyCOuntry[0] ]['currency']['code'] : 'USD';

						$defaultCurrency = isset( $getMetaGeneral['currency']['name'] ) ? $getMetaGeneral['currency']['name'] : $onlyCOuntry[0] . '-' . $defultCode;

						?>
						<select class="regular-text wfp-select2-country" name="xs_submit_settings_data_general[options][currency][name]">
							<?php
							if ( is_array( $countryList ) && sizeof( $countryList ) > 0 ) {
								foreach ( $countryList as $key => $value ) :
									$name    = isset( $value['info']['name'] ) ? $value['info']['name'] : '';
									$code    = isset( $value['currency']['code'] ) ? $value['currency']['code'] : '';
									$symbols = isset( $value['currency']['symbol'] ) ? $value['currency']['symbol'] : '';
									$symbols = strlen( $symbols ) > 0 ? '(' . $symbols . ')' : '';
									?>
									<option value="<?php echo esc_attr( $key . '-' . $code ); ?>" <?php echo ( $defaultCurrency == $key . '-' . $code ) ? 'selected' : ''; ?>> <?php echo esc_html( $name . ' -- ' . $code . $symbols ); ?> </option>
									<?php

								endforeach;
							}
							?>
						</select>
					</div>
				</li>
				<li class="wfdp-social-input-container">
					<div class="wfdp-social-label">
						<?php echo esc_html__( 'Symbol Position', 'wp-fundraising' ); ?>
					</div>
					<div class="wfdp-social-input">
					<?php
					$defultPositionCountry = isset( $localListDefult['currency_pos'] ) ? $localListDefult['currency_pos'] : 'right';
					$defaultPosition       = isset( $getMetaGeneral['currency']['position'] ) ? $getMetaGeneral['currency']['position'] : $defultPositionCountry;
					?>
						<select class="regular-text xs-text_small" name="xs_submit_settings_data_general[options][currency][position]">
							<option value="left" <?php echo ( $defaultPosition == 'left' ) ? 'selected' : ''; ?> > <?php echo esc_html__( 'Left', 'wp-fundraising' ); ?></option>
							<option value="right"<?php echo ( $defaultPosition == 'right' ) ? 'selected' : ''; ?> > <?php echo esc_html__( 'Right', 'wp-fundraising' ); ?></option>
						</select>
					</div>
				</li>
				<li class="wfdp-social-input-container">
					<div class="wfdp-social-label">
						<?php echo esc_html__( 'Thousand separator', 'wp-fundraising' ); ?>
					</div>
					<div class="wfdp-social-input">
					<?php
					$defaultThou_seperatorCountry = isset( $localListDefult['thousand_sep'] ) ? $localListDefult['thousand_sep'] : ',';
					$defaultThou_seperator        = isset( $getMetaGeneral['currency']['thou_seperator'] ) ? $getMetaGeneral['currency']['thou_seperator'] : $defaultThou_seperatorCountry;
					?>
						<input type="text" class="regular-text xs-text_small" name="xs_submit_settings_data_general[options][currency][thou_seperator]" value="<?php echo esc_attr( $defaultThou_seperator ); ?>">
						<span class="xs-donetion-field-description hidden"><?php echo esc_html__( 'Use Thousand Seperator in Display Currency.', 'wp-fundraising' ); ?></span>
					</div>

				</li>
				<li class="wfdp-social-input-container">
					<div class="wfdp-social-label">
						<?php echo esc_html__( 'Decimal separator', 'wp-fundraising' ); ?>
					</div>
					<div class="wfdp-social-input">
					<?php
					$defaultDecimal_seperatorCountry = isset( $localListDefult['decimal_sep'] ) ? $localListDefult['decimal_sep'] : '.';
					$defaultDecimal_seperator        = isset( $getMetaGeneral['currency']['decimal_seperator'] ) ? $getMetaGeneral['currency']['decimal_seperator'] : $defaultDecimal_seperatorCountry;
					?>
						<input type="text" class="regular-text xs-text_small" name="xs_submit_settings_data_general[options][currency][decimal_seperator]" value="<?php echo esc_attr( $defaultDecimal_seperator ); ?>">
						
						<span class="xs-donetion-field-description hidden"><?php echo esc_html__( 'Use Decimal Seperator in Display Currency.', 'wp-fundraising' ); ?></span>
					</div>

				</li>
				<li class="wfdp-social-input-container">
					<div class="wfdp-social-label">
						<?php echo esc_html__( 'Number of decimals', 'wp-fundraising' ); ?>
					</div>
					<div class="wfdp-social-input">

					<?php

					$defaultNumberDecimalCountry = isset( $localListDefult['num_decimals'] ) ? $localListDefult['num_decimals'] : '2';
					$defaultNumberDecimal        = isset( $getMetaGeneral['currency']['number_decimal'] ) ? $getMetaGeneral['currency']['number_decimal'] : $defaultNumberDecimalCountry;
					?>
						<input type="number" min="0" class="regular-text xs-text_small" name="xs_submit_settings_data_general[options][currency][number_decimal]" value="<?php echo esc_attr( $defaultNumberDecimal ); ?>">
						<span class="xs-donetion-field-description hidden"><?php echo esc_html__( 'Show Decimal Number in Display Currency.', 'wp-fundraising' ); ?></span>
					</div>

				</li>
				<!--
				<li>
					<div>
						<?php // echo esc_html__('Display Currency', 'wp-fundraising'); ?>
					</div>
					<div>
					<?php

					// $defultDisplayCurrency = isset($getMetaGeneral['currency']['display']) ? $getMetaGeneral['currency']['display'] : 'symbol';
					?>
						<select class="regular-text " name="xs_submit_settings_data_general[options][currency][display]">
							<option value="code" <?php // echo ($defultDisplayCurrency == 'code') ? 'selected' : ''; ?> > <?php // echo esc_html__('Code (USD)', 'wp-fundraising'); ?></option>
							<option value="code" <?php // echo ($defultDisplayCurrency == 'code') ? 'selected' : ''; ?> > <?php // echo esc_html__('Code (USD)', 'wp-fundraising'); ?></option>
							<option value="symbol"<?php // echo ($defultDisplayCurrency == 'symbol') ? 'selected' : ''; ?> > <?php // echo esc_html__('Symbol ($)', 'wp-fundraising'); ?></option>
							<option value="both"<?php // echo ($defultDisplayCurrency == 'both') ? 'selected' : ''; ?> > <?php // echo esc_html__('($) Both (USD)', 'wp-fundraising'); ?></option>
						</select>
						<br/>
						<span class="xs-donetion-field-description hidden"><?php // echo esc_html__('Use Currency symbol and Currency Code in Display Currency.', 'wp-fundraising'); ?></span>
					</div>
				</li>-->
				<li class="wfdp-social-input-container wfdp-social-no-border">
					<div class="wfdp-social-label">
						<?php echo esc_html__( 'Use space with symbol', 'wp-fundraising' ); ?>
					</div>

					<div class="wfdp-social-switch">
						<div class="xs-switch-button_wraper">

							<?php

							$defaultUse_space = isset( $getMetaGeneral['currency']['use_space'] ) ? $getMetaGeneral['currency']['use_space'] : 'off';
							?>

							<input class="xs_donate_switch_button" type="checkbox" id="donation_form_currency_enable__space" <?php echo ( $defaultUse_space == 'on' ) ? 'checked' : ''; ?> name="xs_submit_settings_data_general[options][currency][use_space]" value="on">
							<label for="donation_form_currency_enable__space" class="xs_donate_switch_button_label small xs-round"></label>
						</div>
						<span class="xs-donetion-field-description hidden"><?php echo esc_html__( 'Use space in display currency.', 'wp-fundraising' ); ?></span>
					</div>


				</li>
			</ul>
			<ul class="wfdp-social_share">
				<li class="wfdp-social_share-section-title wfp-disabled-div <?php echo ( $gateCampaignData == 'woocommerce' ) ? 'wfp-disabled' : ''; ?>"><h3><?php echo esc_html__( 'Location', 'wp-fundraising' ); ?></h3></li>

				<li class="wfdp-social-input-container wfp-disabled-div <?php echo ( $gateCampaignData == 'woocommerce' ) ? 'wfp-disabled' : ''; ?>">
					<div class="wfdp-social-label">
						<?php echo esc_html__( 'Address Line', 'wp-fundraising' ); ?>
					</div>
					<div class="wfdp-social-input">
					<?php
					$defaultAddress = isset( $getMetaGeneral['location']['address'] ) ? $getMetaGeneral['location']['address'] : '';
					?>
						<input type="text" class="regular-text" name="xs_submit_settings_data_general[options][location][address]" value="<?php echo esc_attr( $defaultAddress ); ?>">
					</div>

				</li>
				<li class="wfdp-social-input-container wfp-disabled-div <?php echo ( $gateCampaignData == 'woocommerce' ) ? 'wfp-disabled' : ''; ?>">
					<div class="wfdp-social-label">
						<?php echo esc_html__( 'City', 'wp-fundraising' ); ?>
					</div>
					<div class="wfdp-social-input">
					<?php
					$defaultCity = isset( $getMetaGeneral['location']['city'] ) ? $getMetaGeneral['location']['city'] : '';
					?>
						<input type="text" class="regular-text" name="xs_submit_settings_data_general[options][location][city]" value="<?php echo esc_attr( $defaultCity ); ?>">
					</div>

				</li>
				<li class="wfdp-social-input-container wfp-disabled-div <?php echo ( $gateCampaignData == 'woocommerce' ) ? 'wfp-disabled' : ''; ?>">
					<div class="wfdp-social-label">
						<?php echo esc_html__( 'Country / State', 'wp-fundraising' ); ?>
					</div>
					<div class="wfdp-social-input">
						<?php

						$defaultCountry = isset( $getMetaGeneral['location']['country'] ) ? $getMetaGeneral['location']['country'] : 'US-CA';
						?>
						<select class="regular-text wfp-select2-country" name="xs_submit_settings_data_general[options][location][country]">
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
										<option value="<?php echo esc_attr( $key . '-' . $keyState ); ?>" <?php echo ( $defaultCountry == $key . '-' . $keyState ) ? 'selected' : ''; ?>> <?php echo esc_html( $name . ' -- ' . $valueState ); ?> </option>
										
											<?php
										endforeach;
										?>
									</optgroup>
										<?php
									} else {
										?>
									<option value="<?php echo esc_attr( $key ); ?>" <?php echo ( $defaultCountry == $key ) ? 'selected' : ''; ?>> <?php esc_html( $name ); ?> </option>
										<?php
									}
								endforeach;
							}
							?>
						</select>
					</div>
				</li>

				<li class="wfdp-social-input-container wfp-disabled-div <?php echo ( $gateCampaignData == 'woocommerce' ) ? 'wfp-disabled' : ''; ?>">
					<div class="wfdp-social-label">
						<?php echo esc_html__( 'Postcode / ZIP', 'wp-fundraising' ); ?>
					</div>
					<div class="wfdp-social-input">
					<?php
					$defaultPostcode = isset( $getMetaGeneral['location']['postcode'] ) ? $getMetaGeneral['location']['postcode'] : '';
					?>
						<input type="text" class="regular-text xs-text_small" name="xs_submit_settings_data_general[options][location][postcode]" value="<?php echo esc_attr( $defaultPostcode ); ?>">
					</div>

				</li>
			</ul>
		</div>

		<button type="submit" name="submit_donate_general_setting" class="button button-primary button-large"><?php echo esc_html__( 'Save', 'wp-fundraising' ); ?></button>
		</form>
	</div>
</div>

<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('.wfp-select2-country').select2();
	});
</script>
