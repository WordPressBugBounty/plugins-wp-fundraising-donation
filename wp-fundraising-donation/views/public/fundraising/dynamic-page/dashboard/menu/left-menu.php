

<div class="wfp-menu-left">
	<ul class="wfp-main-menu">
		<li class="wfp-menu-item <?php echo esc_attr( ( in_array( $getPage, array( 'dashboard' ) ) ) ? 'opend' : '' ); ?>" id="items-0"> <a href="?wfp-page=dashboard" class=""> <i class="wfpf wfpf-dashboard"></i> <?php echo esc_html( apply_filters( 'wfp_dashboard_mycampaings_text', __( 'Dashboard', 'wp-fundraising' ) ) ); ?> </a></li>
		<li class="wfp-menu-item <?php echo esc_attr( ( in_array( $getPage, array( 'my-campaign' ) ) ) ? 'opend' : '' ); ?>" id="items-0"> <a href="?wfp-page=my-campaign" class=""> <i class="wfpf wfpf-chart-bar"></i> <?php echo esc_html( apply_filters( 'wfp_dashboard_mycampaings_text', __( 'My Campaigns', 'wp-fundraising' ) ) ); ?> </a></li>
		<li class="wfp-menu-item <?php echo esc_attr( ( in_array( $getPage, array( 'profile', 'password', 'rewards' ) ) ) ? 'opend' : '' ); ?>"><a href="javascript:void();" class="wfp_sub_menu"> <i class="wfpf wfpf-user-add"></i> <?php echo esc_html( apply_filters( 'wfp_dashboard_myaccounts_text', __( 'My Accounts', 'wp-fundraising' ) ) ); ?> </a>
			<ul class="sub-menu">
				<li class="<?php echo esc_attr( ( $getPage == 'profile' ) ? 'opend-sub' : '' ); ?>"> <a href="?wfp-page=profile" ><?php echo esc_html( apply_filters( 'wfp_dashboard_profile_text', __( 'Profile', 'wp-fundraising' ) ) ); ?> </a></li>
				<li  class="<?php echo esc_attr( ( $getPage == 'password' ) ? 'opend-sub' : '' ); ?>"> <a href="?wfp-page=password" ><?php echo esc_html( apply_filters( 'wfp_dashboard_password_text', __( 'Password', 'wp-fundraising' ) ) ); ?> </a></li>
				<li  class="<?php echo esc_attr( ( $getPage == 'rewards' ) ? 'opend-sub' : '' ); ?>"> <a href="?wfp-page=rewards" ><?php echo esc_html( apply_filters( 'wfp_dashboard_rewards_text', __( 'Rewards', 'wp-fundraising' ) ) ); ?> </a></li>
			</ul>
		</li>
		<li class="wfp-menu-item <?php echo esc_attr( ( in_array( $getPage, array( 'donate', 'income' ) ) ) ? 'opend' : '' ); ?>" id="items-0"> <a href="javascript:void();" class="wfp_sub_menu"> <i class="wfpf wfpf-list"></i> <?php echo esc_html( apply_filters( 'wfp_dashboard_report_text', __( 'Reports', 'wp-fundraising' ) ) ); ?> </a>
			<ul class="sub-menu">
				<li class="<?php echo esc_attr( ( $getPage == 'income' ) ? 'opend-sub' : '' ); ?>"> <a href="?wfp-page=income" ><?php echo esc_html( apply_filters( 'wfp_dashboard_income_text', __( 'Income', 'wp-fundraising' ) ) ); ?> </a></li>
				<li  class="<?php echo esc_attr( ( $getPage == 'donate' ) ? 'opend-sub' : '' ); ?>"> <a href="?wfp-page=donate" ><?php echo esc_html( apply_filters( 'wfp_dashboard_donate_text', __( 'Donate', 'wp-fundraising' ) ) ); ?> </a></li>
			</ul>
		</li>
		
	</ul>
	
</div>
<div class="wfp-logout">
	<a class="xs-btn xs-btn-danger logout-button" href="<?php echo esc_url( wp_logout_url( 'wfp-dashboard' ) ); ?>"><?php echo esc_html( apply_filters( 'wfp_dashboard_logout_text', __( 'Logout', 'wp-fundraising' ) ) ); ?><i class="wfpf wfpf-arrow-right"></i></a>
</div>
