<?php

$uuid = \WfpFundraising\Utilities\Helper::get_html_unique_id();

?>

<div class="wfp-view wfp-view-public">

	<?php do_action( 'wfp_login_form_before_outer' ); ?>

	<?php

	if ( is_user_logged_in() ) :
		?>

		<a href="<?php echo esc_url( wp_logout_url() ); ?>" class="xs-btn btn-special wfp-auth-btn"><?php esc_html_e( 'Logout', 'wp-fundraising' ); ?></a>

		<a style="display: none" href="<?php echo esc_url( \WfpFundraising\Apps\Settings::get_dashboard_url() ); ?>"><?php esc_html_e( 'Dashboard', 'wp-fundraising' ); ?></a>
		<?php

	else :
		?>

	<section class="wfp-login <?php echo esc_attr( $classes ); ?>">

		<?php do_action( 'wfp_login_form_before_inner' ); ?>

	<div class="wfp-login-form-container">

		<?php if ( $showLogin === true ) : ?>

		<div class="section-content <?php echo esc_attr( $both_show ? 'xs-donate-visible' : '' ); ?>" id="wfp-login-section_<?php echo esc_attr( $uuid ); ?>">

			<p class="wfp-login-message">
				<?php do_action( 'wfp_login_form_message' ); ?>
			</p>

			<form class="wfp-form wfp-form-login login" method="post" id="wfp_auth_form_login">

				<?php do_action( 'wfp_login_form_start' ); ?>

				<div class="xs-row xs-form-group wfp-from-group">
					<div class="xs-col-2 xs-col-sm-2"><i class="wfpf wfpf-user wfp-from-group--icon"></i></div>
					<div class="xs-col-10 xs-col-sm-10">
						<input type="text" class="xs-form-control-plaintext wfp-input wfp-email" name="wfp_login[user_name]" id="user_name" autocomplete="username" placeholder="<?php echo esc_html__( 'Username', 'wp-fundraising' ); ?>" />
					</div>
				</div>

				<div class="xs-row xs-form-group wfp-from-group">
					<div class="xs-col-2 xs-col-sm-2"><i class="wfpf wfpf-lock-closed wfp-from-group--icon"></i></div>
					<div class="xs-col-10 xs-col-sm-10">
						<i class="wfpf wfpf-eye-open wfp-from-group--password__icon"></i>
						<input type="password" class="xs-form-control-plaintext wfp-input wfp-password" name="wfp_login[user_password]" id="user_password" autocomplete="password" placeholder="<?php echo esc_html__( 'Password', 'wp-fundraising' ); ?>" />
					</div>
				</div>

				<?php do_action( 'wfp_login_form_button_before' ); ?>

				<div class="wfp-from-group wfp-remember-me-container">
					<label class="wfp-rememberme">
						<input class="wfp-form__input wfp-form__input-checkbox wfp-rememberme--checkbox" name="wfp_login[rememberme]" type="checkbox" id="rememberme" value="forever" /> <span class="wfp-rememberme--label"><?php echo esc_html__( 'Remember me', 'wp-fundraising' ); ?></span>
					</label>

					<?php wp_nonce_field( 'wfp_sh_login', 'login_send' ); ?>

					<button type="submit" class="xs-btn xs-btn-primary wfp-button button wfp-form-login__submit" name="wfp-login" value="<?php echo esc_html__( 'Login Now', 'wp-fundraising' ); ?>"><?php echo esc_html__( 'Login Now', 'wp-fundraising' ); ?></button>
				</div>

				<p class="wfp-LostPassword lost_password">
					<a class="wfp-LostPassword--link" href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php echo esc_html__( 'Lost your password?', 'wp-fundraising' ); ?></a>
				</p>

				<?php do_action( 'wfp_login_form_button_end' ); ?>

				<?php do_action( 'wfp_login_form_end' ); ?>
			</form>
		</div>

		<?php endif; ?>

		<?php if ( $showRegister === true ) : ?>


		<div class="section-content <?php echo esc_attr( $both_show ? 'xs-donate-hidden' : '' ); ?>" id="wfp-register-section_<?php echo esc_attr( $uuid ); ?>">

			<p class="wfp-login-message">
				<?php do_action( 'wfp_login_form_message' ); ?>
			</p>

			<form class="wfp-form wfp-form-login register" method="post" id="wfp_auth_form_register">

				<?php do_action( 'wfp_login_form_start' ); ?>

				<div class="xs-row xs-form-group wfp-from-group">
					<div class="xs-col-2 xs-col-sm-2"><i class="wfpf wfpf-user wfp-from-group--icon"></i></div>
					<div class="xs-col-10 xs-col-sm-10">
						<input type="text" class="xs-form-control-plaintext wfp-input wfp-email" name="wfp_register[user_name]" id="register_user_name" autocomplete="username" placeholder="<?php echo esc_html__( 'User Name', 'wp-fundraising' ); ?>" />
					</div>
				</div>

				<div class="xs-row xs-form-group wfp-from-group">
					<div class="xs-col-2 xs-col-sm-2"><i class="wfpf wfpf-envelope wfp-from-group--icon"></i></div>
					<div class="xs-col-10 xs-col-sm-10">
						<input type="text" class="xs-form-control-plaintext wfp-input wfp-email" name="wfp_register[user_email]" id="user_email" autocomplete="email" placeholder="<?php echo esc_html__( 'Email', 'wp-fundraising' ); ?>" />
					</div>
				</div>

				<div class="xs-row xs-form-group wfp-from-group">
					<div class="xs-col-2 xs-col-sm-2"><i class="wfpf wfpf-lock-closed wfp-from-group--icon"></i></div>
					<div class="xs-col-10 xs-col-sm-10">
						<i class="wfpf wfpf-eye-open wfp-from-group--password__icon"></i>
						<input type="password" class="xs-form-control-plaintext wfp-input wfp-password" name="wfp_register[user_password]" id="register_password" autocomplete="password" placeholder="<?php echo esc_html__( 'Password', 'wp-fundraising' ); ?>" />
					</div>
				</div>

				<?php do_action( 'wfp_login_form_button_before' ); ?>

				<div class="wfp-from-group wfp-remember-me-container">
					<?php wp_nonce_field( 'wfp_sh_reg', 'reg_send' ); ?>

					<button type="submit" class="xs-btn xs-btn-primary wfp-button button wfp-form-register__submit" name="wfp-register" value="<?php esc_html_e( 'Register', 'wp-fundraising' ); ?>"><?php esc_html_e( 'Register', 'wp-fundraising' ); ?></button>
				</div>

				<p class="wfp-LostPassword lost_password">
					<a class="wfp-LostPassword--link" href="<?php echo esc_url( wp_login_url() ); ?>"><?php esc_html_e( 'Do you have an account?', 'wp-fundraising' ); ?></a>
				</p>

				<?php do_action( 'wfp_login_form_button_end' ); ?>

				<?php do_action( 'wfp_login_form_end' ); ?>
			</form>
		</div>

		<?php endif; ?>

	</div>

		<?php if ( $both_show ) : ?>

		<div class="section-content wfp-tab-control wfp-reg-login-navs">
			<button class="wfp-button wfp-login-btn wfp-button-active xs-btn xs-btn-muted" onclick="toggle_class_in_target('#wfp-login-section_<?php echo esc_attr( $uuid ); ?>', '.wfp-login-form-container')"><i class="wfpf wfpf-user wfp-reg-login-navs--icon"></i><?php esc_html_e( 'Login', 'wp-fundraising' ); ?></button>
			<button class="wfp-button wfp-register-btn xs-btn xs-btn-secondary" onclick="toggle_class_in_target('#wfp-register-section_<?php echo esc_attr( $uuid ); ?>', '.wfp-login-form-container')"><i class="wfpf wfpf-user wfp-reg-login-navs--icon"></i><?php esc_html_e( 'Register', 'wp-fundraising' ); ?></button>
		</div>

	<?php endif; ?>


		<?php do_action( 'wfp_login_form_after_inner' ); ?>
	</section>

	<?php endif; ?>

	<?php do_action( 'wfp_login_form_after_outer' ); ?>
</div>
