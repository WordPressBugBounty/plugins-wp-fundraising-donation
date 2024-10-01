<div class="password-content wfp-content-padding">
	<form id="wfp_regForm_password_content" class="wfp_regForm" method="POST" >
		<div class="message-password-status"></div>
		
		<div class="profile-section">
			<div class="profile-block left-profile xs-col-md-12 xs-col-lg-6">
				
				<h3><i class="wfpf wfpf-calculator"></i><?php echo esc_html( apply_filters( 'wfp_dashboard_password_content_headding', __( 'Change your Password ', 'wp-fundraising' ) ) ); ?></h3>

				<div class="xs-form-group xs-row intro-info">
					<label for="user_current_pass" class="xs-col-form-label">
						<?php echo esc_html( apply_filters( 'wfp_dashboard_password_content_current_password', __( 'Current Password', 'wp-fundraising' ) ) ); ?>
					</label>

					<input type="password" name="password[current_pass]" id="user_current_pass" value="" class="xs-form-control-plaintext wfp-input">

				</div>
				<div class="xs-form-group xs-row intro-info">
					<label for="user_new_pass" class="xs-col-form-label">
						<?php echo esc_html( apply_filters( 'wfp_dashboard_password_content_new_password', __( 'New Password', 'wp-fundraising' ) ) ); ?>
					</label>

					<input type="password" name="password[new_pass]" id="user_new_pass" value="" class="xs-form-control-plaintext wfp-input">
	
				</div>
				<div class="xs-form-group xs-row intro-info">
					<label for="user_confirm_pass" class="xs-col-form-label">
						<?php echo esc_html( apply_filters( 'wfp_dashboard_password_content_confirm_password', __( 'Confirm Password', 'wp-fundraising' ) ) ); ?>
					</label>

					
					<input type="password" name="password[confirm_pass]" id="user_confirm_pass" value="" class="xs-form-control-plaintext wfp-input">
				</div>	

				<div class="xs-text-right">
					<button type="submit" class="wfp-form-button xs-btn xs-btn-primary xs-float-right" name="post_dashboard_campaign"><?php echo esc_html( apply_filters( 'wfp_dashboard_password_content_submit_button', __( 'Update', 'wp-fundraising' ) ) ); ?></button>
				</div>
			</div>	
		</div>	
		<br/>
	</form>	
</div>
