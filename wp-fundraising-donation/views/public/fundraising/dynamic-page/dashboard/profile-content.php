<div class="profile-content wfp-content-padding">
	<form id="wfp_regForm_profile_content" class="wfp_regForm" method="POST">
		<div class="message-campaign-status" role="alert"></div>
		<div class="profile-section">
			<div class="profile-block left-profile">
				<h3><i class="wfpf wfpf-store-front"></i><?php echo esc_html( apply_filters( 'wfp_dashboard_profile_content_billing_headding', __( 'Billing Info ', 'wp-fundraising' ) ) ); ?></h3>
				
				<div class="xs-form-group xs-row intro-info">
					<label for="billing_first_name" class="xs-col-4 xs-col-md-4 xs-col-form-label">
						<?php echo esc_html( apply_filters( 'wfp_dashboard_profile_content_first_name', __( 'First Name', 'wp-fundraising' ) ) ); ?>
					</label>
					<div class="xs-col-1 xs-col-md-1 wfp-separator">
						<span>:</span>
					</div>
					<div class="xs-col-7 xs-col-md-7 wfp-input-col">
						<input type="text" name="billing_post[_wfp_first_name]" id="billing_first_name" value="<?php echo esc_attr( get_user_meta( $userId, '_wfp_first_name', true ) ); ?>" class="xs-form-control-plaintext wfp-input" >
					</div>
				</div>
				
				<div class="xs-form-group xs-row intro-info">
					<label for="billing_last_name" class="xs-col-4 xs-col-md-4 xs-col-form-label">
						<?php echo esc_html( apply_filters( 'wfp_dashboard_profile_content_last_name', __( 'Last Name', 'wp-fundraising' ) ) ); ?>
					</label>

					<div class="xs-col-1 xs-col-md-1 wfp-separator">
						<span>:</span>
					</div>

					<div class="xs-col-7 xs-col-md-7 wfp-input-col">
						<input type="text" name="billing_post[_wfp_last_name]" id="billing_last_name" value="<?php echo esc_attr( get_user_meta( $userId, '_wfp_last_name', true ) ); ?>" class="xs-form-control-plaintext wfp-input" >
					</div>
				</div>

				<div class="xs-form-group xs-row intro-info">
					<label for="billing_company_name" class="xs-col-4 xs-col-md-4 xs-col-form-label">
						<?php echo esc_attr( apply_filters( 'wfp_dashboard_profile_content_company_name', __( 'Company', 'wp-fundraising' ) ) ); ?>
					</label>

					<div class="xs-col-1 xs-col-md-1 wfp-separator">
						<span>:</span>
					</div>

					<div class="xs-col-7 xs-col-md-7 wfp-input-col">
						<input type="text" name="billing_post[_wfp_company_name]" id="billing_company_name" value="<?php echo esc_attr( get_user_meta( $userId, '_wfp_company_name', true ) ); ?>" class="xs-form-control-plaintext wfp-input" >
					</div>
					
				</div>
				<div class="xs-form-group xs-row intro-info">
					<label for="billing_address" class="xs-col-4 xs-col-md-4 xs-col-form-label">
						<?php echo esc_html( apply_filters( 'wfp_dashboard_profile_content_address', __( 'Address', 'wp-fundraising' ) ) ); ?>
					</label>

					<div class="xs-col-1 xs-col-md-1 wfp-separator">
						<span>:</span>
					</div>

					<div class="xs-col-7 xs-col-md-7 wfp-input-col">
						<?php $post_excerpt = isset( $post_data->post_excerpt ) ? $post_data->post_excerpt : ''; ?>
						<textarea name="billing_post[_wfp_street_address]" id="billing_address" class="xs-form-control wfp-input wfp-textarea" ><?php echo esc_html( get_user_meta( $userId, '_wfp_street_address', true ) ); ?></textarea>
					</div>
				</div>

				<div class="xs-form-group xs-row intro-info">
					<label for="billing_city_name" class="xs-col-4 xs-col-md-4 xs-col-form-label">
						<?php echo esc_html( apply_filters( 'wfp_dashboard_profile_content_city_name', __( 'City', 'wp-fundraising' ) ) ); ?>
					</label>

					<div class="xs-col-1 xs-col-md-1 wfp-separator">
						<span>:</span>
					</div>

					<div class="xs-col-7 xs-col-md-7 wfp-input-col">
						<input type="text" name="billing_post[_wfp_city]" id="billing_city_name" value="<?php echo esc_attr( get_user_meta( $userId, '_wfp_city', true ) ); ?>" class="xs-form-control-plaintext wfp-input" >
					</div>
					
				</div>

				<div class="xs-form-group xs-row intro-info">
					<label for="billing_city_name" class="xs-col-4 xs-col-md-4 xs-col-form-label">
						<?php echo esc_html( apply_filters( 'wfp_dashboard_profile_content_postcode', __( 'Postcode', 'wp-fundraising' ) ) ); ?>
					</label>

					<div class="xs-col-1 xs-col-md-1 wfp-separator">
						<span>:</span>
					</div>

					<div class="xs-col-7 xs-col-md-7 wfp-input-col">
						<input type="text" name="billing_post[_wfp_postcode]" id="billing_city_name" value="<?php echo esc_attr( get_user_meta( $userId, '_wfp_postcode', true ) ); ?>" class="xs-form-control-plaintext wfp-input" >
					</div>
					
				</div>
				<div class="xs-form-group xs-row intro-info">
					<label for="billing_country" class="xs-col-4 xs-col-md-4 xs-col-form-label">
						<?php echo esc_html( apply_filters( 'wfp_dashboard_profile_content_country', __( 'Country', 'wp-fundraising' ) ) ); ?>
					</label>

					<div class="xs-col-1 xs-col-md-1 wfp-separator">
						<span>:</span>
					</div>

					<div class="xs-col-7 xs-col-md-7 wfp-input-col">
						<select class="wfp-require-filed wfp-select2-country" name="billing_post[_wfp_country]" id="billing_country" class="xs-form-control wfp-input">
							<?php
							$defaultCountry = get_user_meta( $userId, '_wfp_country', true );

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
					
				</div>
				
				<div class="xs-form-group xs-row intro-info">
					<label for="billing_city_name" class="xs-col-4 xs-col-md-4 xs-col-form-label">
						<?php echo esc_html( apply_filters( 'wfp_dashboard_profile_content_telephone', __( 'Telephone', 'wp-fundraising' ) ) ); ?>
					</label>

					<div class="xs-col-1 xs-col-md-1 wfp-separator">
						<span>:</span>
					</div>

					<div class="xs-col-7 xs-col-md-7 wfp-input-col">
						<input type="tel" name="billing_post[_wfp_phone]" id="billing_city_name" value="<?php echo esc_attr( get_user_meta( $userId, '_wfp_phone', true ) ); ?>" class="xs-form-control-plaintext wfp-input" >
					</div>
				</div>

				<div class="xs-form-group xs-row intro-info">
					<label for="billing_email_name" class="xs-col-4 xs-col-md-4 xs-col-form-label">
						<?php echo esc_html( apply_filters( 'wfp_dashboard_profile_content_email', __( 'Email', 'wp-fundraising' ) ) ); ?>
					</label>

					<div class="xs-col-1 xs-col-md-1 wfp-separator">
						<span>:</span>
					</div>

					<div class="xs-col-7 xs-col-md-7 wfp-input-col">
						<input type="email" name="billing_post[_wfp_email_address]" id="billing_email_name" value="<?php echo esc_attr( get_user_meta( $userId, '_wfp_email_address', true ) ); ?>" class="xs-form-control-plaintext wfp-input" >
					</div>
					
				</div>

				<div class="xs-text-right">
					<button type="submit" class="wfp-form-button xs-btn xs-btn-primary" name="post_dashboard_campaign"><?php echo esc_html( apply_filters( 'wfp_dashboard_profile_content_submit_button', __( 'Update', 'wp-fundraising' ) ) ); ?></button>
				</div>
				
			</div>
			<div class="profile-block right-profile">
				<h3><i class="wfpf wfpf-travel-car"></i><?php echo esc_html( apply_filters( 'wfp_dashboard_profile_content_shipping_headding', __( 'Shipping Info ', 'wp-fundraising' ) ) ); ?></h3>
				<div class="xs-form-group xs-row intro-info">
					<label for="shipping_first_name"  class="xs-col-4 xs-col-md-4 xs-col-form-label">
						<?php echo esc_html( apply_filters( 'wfp_dashboard_profile_content_first_name', __( 'First Name', 'wp-fundraising' ) ) ); ?>
					</label>

					<div class="xs-col-1 xs-col-md-1 wfp-separator">
						<span>:</span>
					</div>

					<div class="xs-col-7 xs-col-md-7 wfp-input-col">
						<input type="text" name="shipping_post[_wfp_first_name_ship]" id="shipping_first_name" value="<?php echo esc_attr( get_user_meta( $userId, '_wfp_first_name_ship', true ) ); ?>" class="xs-form-control-plaintext wfp-input" >
					</div>
					
				</div>
				<div class="xs-form-group xs-row intro-info">
					<label for="shipping_last_name"  class="xs-col-4 xs-col-md-4 xs-col-form-label">
						<?php echo esc_html( apply_filters( 'wfp_dashboard_profile_content_last_name', __( 'Last Name', 'wp-fundraising' ) ) ); ?>
					</label>

					<div class="xs-col-1 xs-col-md-1 wfp-separator">
						<span>:</span>
					</div>

					<div class="xs-col-7 xs-col-md-7 wfp-input-col">
						<input type="text" name="shipping_post[_wfp_last_name_ship]" id="shipping_last_name" value="<?php echo esc_attr( get_user_meta( $userId, '_wfp_last_name_ship', true ) ); ?>" class="xs-form-control-plaintext wfp-input" >
					</div>
					
				</div>
				<div class="xs-form-group xs-row intro-info">
					<label for="shipping_company_name"  class="xs-col-4 xs-col-md-4 xs-col-form-label">
						<?php echo esc_html( apply_filters( 'wfp_dashboard_profile_content_company_name', __( 'Company', 'wp-fundraising' ) ) ); ?>
					</label>

					<div class="xs-col-1 xs-col-md-1 wfp-separator">
						<span>:</span>
					</div>

					<div class="xs-col-7 xs-col-md-7 wfp-input-col">
						<input type="text" name="shipping_post[_wfp_company_name_ship]" id="shipping_company_name" value="<?php echo esc_attr( get_user_meta( $userId, '_wfp_company_name_ship', true ) ); ?>" class="xs-form-control-plaintext wfp-input" >
					</div>
					
				</div>
				<div class="xs-form-group xs-row intro-info">
					<label for="shipping_address"  class="xs-col-4 xs-col-md-4 xs-col-form-label">
						<?php echo esc_html( apply_filters( 'wfp_dashboard_profile_content_address', __( 'Address', 'wp-fundraising' ) ) ); ?>
					</label>

					<div class="xs-col-1 xs-col-md-1 wfp-separator">
						<span>:</span>
					</div>

					<?php $post_excerpt = isset( $post_data->post_excerpt ) ? $post_data->post_excerpt : ''; ?>
					<div class="xs-col-7 xs-col-md-7 wfp-input-col">
						<textarea name="shipping_post[_wfp_street_address_ship]" id="shipping_address" class="xs-form-control wfp-input wfp-textarea" ><?php echo esc_html( get_user_meta( $userId, '_wfp_street_address_ship', true ) ); ?></textarea>
					</div>
				</div>
				<div class="xs-form-group xs-row intro-info">
					<label for="shipping_city_name" class="xs-col-4 xs-col-md-4 xs-col-form-label">
						<?php echo esc_html( apply_filters( 'wfp_dashboard_profile_content_city_name', __( 'City', 'wp-fundraising' ) ) ); ?>
					</label>

					<div class="xs-col-1 xs-col-md-1 wfp-separator">
						<span>:</span>
					</div>

					<div class="xs-col-7 xs-col-md-7 wfp-input-col">
						<input type="text" name="shipping_post[_wfp_city_ship]" id="shipping_city_name" value="<?php echo esc_attr( get_user_meta( $userId, '_wfp_city_ship', true ) ); ?>" class="xs-form-control-plaintext wfp-input" >
					</div>
					
				</div>
				<div class="xs-form-group xs-row intro-info">
					<label for="shipping_city_name" class="xs-col-4 xs-col-md-4 xs-col-form-label">
						<?php echo esc_html( apply_filters( 'wfp_dashboard_profile_content_postcode', __( 'Postcode', 'wp-fundraising' ) ) ); ?>
					</label>

					<div class="xs-col-1 xs-col-md-1 wfp-separator">
						<span>:</span>
					</div>

					<div class="xs-col-7 xs-col-md-7 wfp-input-col">
						<input type="text" name="shipping_post[_wfp_postcode_ship]" id="shipping_city_name" value="<?php echo esc_attr( get_user_meta( $userId, '_wfp_postcode_ship', true ) ); ?>" class="xs-form-control-plaintext wfp-input" >
					</div>
				</div>
				<div class="xs-form-group xs-row intro-info">
					<label for="shipping_city_name" class="xs-col-4 xs-col-md-4 xs-col-form-label">
						<?php echo esc_html( apply_filters( 'wfp_dashboard_profile_content_country', __( 'Country', 'wp-fundraising' ) ) ); ?>
					</label>

					<div class="xs-col-1 xs-col-md-1 wfp-separator">
						<span>:</span>
					</div>

					<div class="xs-col-7 xs-col-md-7 wfp-input-col">
						<select class="wfp-require-filed wfp-select2-country" name="shipping_post[_wfp_country_ship]" id="shipping_city_name" class="xs-form-control wfp-input">
							<?php
							$defaultCountry = get_user_meta( $userId, '_wfp_country_ship', true );

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
				</div>
				
				<div class="xs-form-group xs-row intro-info">
					<label for="billing_city_name" class="xs-col-4 xs-col-md-4 xs-col-form-label">
						<?php echo esc_html( apply_filters( 'wfp_dashboard_profile_content_telephone', __( 'Telephone', 'wp-fundraising' ) ) ); ?>
					</label>

					<div class="xs-col-1 xs-col-md-1 wfp-separator">
						<span>:</span>
					</div>

					<div class="xs-col-7 xs-col-md-7 wfp-input-col">
						<input type="tel" name="shipping_post[_wfp_phone_ship]" id="billing_city_name" value="<?php echo esc_attr( get_user_meta( $userId, '_wfp_phone_ship', true ) ); ?>" class="xs-form-control-plaintext wfp-input" >
					</div>
					
				</div>
				<div class="xs-form-group xs-row intro-info">
					<label for="billing_email_name" class="xs-col-4 xs-col-md-4 xs-col-form-label">
						<?php echo esc_html( apply_filters( 'wfp_dashboard_profile_content_email', __( 'Email', 'wp-fundraising' ) ) ); ?>
					</label>

					<div class="xs-col-1 xs-col-md-1 wfp-separator">
						<span>:</span>
					</div>

					<div class="xs-col-7 xs-col-md-7 wfp-input-col">
						<input type="email" name="shipping_post[_wfp_email_address_ship]" id="billing_email_name" value="<?php echo esc_attr( get_user_meta( $userId, '_wfp_email_address_ship', true ) ); ?>" class="xs-form-control-plaintext wfp-input" >
					</div>
					
				</div>

				<div class="xs-text-right">
					<button type="submit" class="wfp-form-button xs-btn xs-btn-primary" name="post_dashboard_campaign"><?php echo esc_html( apply_filters( 'wfp_dashboard_profile_content_submit_button', __( 'Update', 'wp-fundraising' ) ) ); ?></button>
				</div>
				
				
			</div>			
		</div>
		
	</form>
</div>
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('.wfp-select2-country').select2();
	});
</script>
