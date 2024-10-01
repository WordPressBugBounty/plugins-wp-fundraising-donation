<div class="wfp-view wfp-view-public">

	<?php do_action( 'wfp_login_form_before_outer' ); ?>

	<section class="wfp-login <?php echo esc_attr( $className ); ?>" id="<?php echo esc_attr( $idName ); ?>">

		<?php do_action( 'wfp_login_form_before_inner' ); ?>

		<div class="wfp-login-form-container">

			<div class="section-content wfp-login-content xs-donate-hidden <?php echo esc_attr( ( 'yes' != get_option( 'wfp_enable_myaccount_registration' ) ) ? 'xs-donate-visible' : '' ); ?>" id="wfp-login-section">
				<?php if ( apply_filters( 'wfp_login_form_login_heading_display', true ) == true ) { ?>
				<div class="wfp-login-heading">
					<h2 class="wfp-login-heading--main"><?php echo esc_html( apply_filters( 'wfp_login_form_login_heading', esc_html__( 'Welcome to ', 'wp-fundraising' ) ) ); ?><?php bloginfo( 'name' ); ?></h2>
					<h3 class="wfp-login-heading--sub"><?php echo esc_html( apply_filters( 'wfp_login_form_login_sub_heading', esc_html__( 'Login your account', 'wp-fundraising' ) ) ); ?></h3>
				</div>
				<?php } ?>
				
				<p class="wfp-login-message"><?php do_action( 'wfp_login_form_message' ); ?></p>

				<form class="wfp-form wfp-form-login login" method="post" id="wfp_auth_form_login">
					<?php do_action( 'wfp_login_form_start' ); ?>

						<div class="xs-row xs-form-group wfp-from-group">
							<div class="xs-col-2 xs-col-sm-2"><i class="wfpf wfpf-user wfp-from-group--icon"></i></div>
							<div class="xs-col-10 xs-col-sm-10">
								<input type="text" class="xs-form-control-plaintext wfp-input wfp-email" name="wfp_login[user_name]" id="username" autocomplete="username" placeholder="Username" />
							</div>
						</div>

						<div class="xs-row xs-form-group wfp-from-group">
							<div class="xs-col-2 xs-col-sm-2"><i class="wfpf wfpf-lock-closed wfp-from-group--icon"></i></div>
							<div class="xs-col-10 xs-col-sm-10  wfp-from-group--password">
								<i class="wfpf wfpf-eye-open wfp-from-group--password__icon"></i>
								<input type="password" class="xs-form-control-plaintext wfp-input wfp-password" name="wfp_login[user_password]" id="password" autocomplete="password" placeholder="Password" />
							</div>
						</div>

						<?php do_action( 'wfp_login_form_button_before' ); ?>
						<div class="wfp-from-group wfp-remember-me-container">
							<label class="wfp-rememberme">
								<input class="wfp-form__input wfp-form__input-checkbox wfp-rememberme--checkbox" name="wfp_login[rememberme]" type="checkbox" id="rememberme" value="forever" /> <span class="wfp-rememberme--label"><?php echo esc_html( apply_filters( 'wfp_login_form_remember', esc_html__( 'Remember me', 'wp-fundraising' ) ) ); ?></span>
							</label>
							<?php wp_nonce_field( 'wfp-login', 'wfp-login-nonce' ); ?>
							<button type="submit" class="xs-btn xs-btn-primary wfp-button button wfp-form-login__submit" name="wfp-login" value="<?php echo esc_attr( apply_filters( 'wfp_login_form_login_button', esc_html__( 'Login Now', 'wp-fundraising' ) ) ); ?>"><?php echo esc_html( apply_filters( 'wfp_login_form_login_button', __( 'Login Now', 'wp-fundraising' ) ) ); ?></button>
						</div>
						<p class="wfp-LostPassword lost_password">
							<a class="wfp-LostPassword--link" href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php echo esc_html( apply_filters( 'wfp_login_form_lost_password', __( 'Lost your password?', 'wp-fundraising' ) ) ); ?></a>
						</p>
						<?php do_action( 'wfp_login_form_button_end' ); ?>
						
					<?php do_action( 'wfp_login_form_end' ); ?>
				</form>
			</div>
			
			<div class="section-content wfp-register-content xs-donate-hidden <?php echo esc_attr( ( 'yes' === get_option( 'wfp_enable_myaccount_registration' ) ) ? 'xs-donate-visible' : '' ); ?>" id="wfp-register-section">

				<?php if ( apply_filters( 'wfp_login_form_login_heading_display', true ) == true ) { ?>
					<div class="wfp-login-heading">
						<h2 class="wfp-login-heading--main"><?php echo esc_html( apply_filters( 'wfp_login_form_login_heading', esc_html__( 'Welcome to ', 'wp-fundraising' ) ) ); ?><?php bloginfo( 'name' ); ?></h2>
						<h3 class="wfp-login-heading--sub"><?php echo esc_html( apply_filters( 'wfp_login_form_login_sub_heading', esc_html__( 'Create your account today!', 'wp-fundraising' ) ) ); ?></h3>
					</div>
				<?php } ?>
				<p class="wfp-login-message">
				<?php do_action( 'wfp_login_form_message' ); ?>
				</p>
				<form class="wfp-form wfp-form-login register" method="post" id="wfp_auth_form_register">
					<?php do_action( 'wfp_login_form_start' ); ?>

						<div class="xs-row xs-form-group wfp-from-group">
							<div class="xs-col-2 xs-col-sm-2"><i class="wfpf wfpf-user wfp-from-group--icon"></i></div>
							<div class="xs-col-10 xs-col-sm-10  wfp-from-group--password">
								<input type="text" class="xs-form-control-plaintext wfp-input wfp-email" name="wfp_register[user_name]" id="register_username" autocomplete="username" placeholder="User Name" />
							</div>
						</div>

						<div class="xs-row xs-form-group wfp-from-group">
							<div class="xs-col-2 xs-col-sm-2"><i class="wfpf wfpf-envelope wfp-from-group--icon"></i></div>
							<div class="xs-col-10 xs-col-sm-10  wfp-from-group--password">
								<input type="text" class="xs-form-control-plaintext wfp-input wfp-email" name="wfp_register[user_email]" id="email" autocomplete="email" placeholder="Email" />
							</div>
						</div>

						<div class="xs-row xs-form-group wfp-from-group">
							<div class="xs-col-2 xs-col-sm-2"><i class="wfpf wfpf-lock-closed wfp-from-group--icon"></i></div>
							<div class="xs-col-10 xs-col-sm-10  wfp-from-group--password">
								<i class="wfpf wfpf-eye-open wfp-from-group--password__icon"></i>
								<input type="password" class="xs-form-control-plaintext wfp-input wfp-password" name="wfp_register[user_password]" id="register_password" autocomplete="password" placeholder="Password" />
							</div>
						</div>

						<?php do_action( 'wfp_login_form_button_before' ); ?>
						<div class="wfp-from-group wfp-remember-me-container">
							<?php wp_nonce_field( 'wfp-register', 'wfp-register-nonce' ); ?>
							<button type="submit" class="xs-btn xs-btn-primary wfp-button button wfp-form-register__submit" name="wfp-register" value="<?php echo esc_attr( apply_filters( 'wfp_register_form_register_button', __( 'Register', 'wp-fundraising' ) ) ); ?>"><?php echo esc_html( apply_filters( 'wfp_register_form_register_button', __( 'Register', 'wp-fundraising' ) ) ); ?></button>
						</div>
						<p class="wfp-LostPassword lost_password">
							<a class="wfp-LostPassword--link" href="<?php echo esc_url( wp_login_url() ); ?>"><?php echo esc_html( apply_filters( 'wfp_register_form_have_account', esc_html__( 'Do you have an account?', 'wp-fundraising' ) ) ); ?></a>
						</p>
						<?php do_action( 'wfp_login_form_button_end' ); ?>
						
					<?php do_action( 'wfp_login_form_end' ); ?>
				</form>
			</div>
		</div>
		<div class="section-content wfp-tab-control wfp-reg-login-navs">
			<button class="wfp-button wfp-login-btn wfp-button-active xs-btn xs-btn-muted" onclick="xs_show_hide_login_multiple('.xs-donate-hidden', '#wfp-login-section')"><i class="wfpf wfpf-user wfp-reg-login-navs--icon"></i>Login</button>
			<button class="wfp-button wfp-register-btn xs-btn xs-btn-secondary" onclick="xs_show_hide_login_multiple('.xs-donate-hidden', '#wfp-register-section')"><i class="wfpf wfpf-user wfp-reg-login-navs--icon"></i>Register</button>
		</div>
		
		<?php do_action( 'wfp_login_form_after_inner' ); ?>
	</section>
	<?php do_action( 'wfp_login_form_after_outer' ); ?>
</div>
