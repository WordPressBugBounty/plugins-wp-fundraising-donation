
<?php 

defined( 'ABSPATH' ) || exit;
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

?>
<div class="wfp-menu-left">
	<ul class="wfp-main-menu">
		<li class="wfp-menu-item <?php echo esc_attr( ( in_array( $wfpGetPage, array( 'dashboard' ) ) ) ? 'opend' : '' ); ?>" id="items-0"> <a href="?wfp-page=dashboard" class=""> <i class="wfpf wfpf-dashboard"></i> <?php echo esc_html( apply_filters( 'wfp_dashboard_mycampaings_text', __( 'Dashboard', 'wp-fundraising-donation' ) ) ); ?> </a></li>
		<li class="wfp-menu-item <?php echo esc_attr( ( in_array( $wfpGetPage, array( 'my-campaign' ) ) ) ? 'opend' : '' ); ?>" id="items-0"> <a href="?wfp-page=my-campaign" class=""> <i class="wfpf wfpf-chart-bar"></i> <?php echo esc_html( apply_filters( 'wfp_dashboard_mycampaings_text', __( 'My Campaigns', 'wp-fundraising-donation' ) ) ); ?> </a></li>
		<li class="wfp-menu-item <?php echo esc_attr( ( in_array( $wfpGetPage, array( 'profile', 'password', 'rewards' ) ) ) ? 'opend' : '' ); ?>"><a href="javascript:void();" class="wfp_sub_menu"> <i class="wfpf wfpf-user-add"></i> <?php echo esc_html( apply_filters( 'wfp_dashboard_myaccounts_text', __( 'My Accounts', 'wp-fundraising-donation' ) ) ); ?> </a>
			<ul class="sub-menu">
				<li class="<?php echo esc_attr( ( $wfpGetPage == 'profile' ) ? 'opend-sub' : '' ); ?>"> <a href="?wfp-page=profile" ><?php echo esc_html( apply_filters( 'wfp_dashboard_profile_text', __( 'Profile', 'wp-fundraising-donation' ) ) ); ?> </a></li>
				<li  class="<?php echo esc_attr( ( $wfpGetPage == 'password' ) ? 'opend-sub' : '' ); ?>"> <a href="?wfp-page=password" ><?php echo esc_html( apply_filters( 'wfp_dashboard_password_text', __( 'Password', 'wp-fundraising-donation' ) ) ); ?> </a></li>
				<li  class="<?php echo esc_attr( ( $wfpGetPage == 'rewards' ) ? 'opend-sub' : '' ); ?>"> <a href="?wfp-page=rewards" ><?php echo esc_html( apply_filters( 'wfp_dashboard_rewards_text', __( 'Rewards', 'wp-fundraising-donation' ) ) ); ?> </a></li>
			</ul>
		</li>
		<li class="wfp-menu-item <?php echo esc_attr( ( in_array( $wfpGetPage, array( 'donate', 'income' ) ) ) ? 'opend' : '' ); ?>" id="items-0"> <a href="javascript:void();" class="wfp_sub_menu"> <i class="wfpf wfpf-list"></i> <?php echo esc_html( apply_filters( 'wfp_dashboard_report_text', __( 'Reports', 'wp-fundraising-donation' ) ) ); ?> </a>
			<ul class="sub-menu">
				<li class="<?php echo esc_attr( ( $wfpGetPage == 'income' ) ? 'opend-sub' : '' ); ?>"> <a href="?wfp-page=income" ><?php echo esc_html( apply_filters( 'wfp_dashboard_income_text', __( 'Income', 'wp-fundraising-donation' ) ) ); ?> </a></li>
				<li  class="<?php echo esc_attr( ( $wfpGetPage == 'donate' ) ? 'opend-sub' : '' ); ?>"> <a href="?wfp-page=donate" ><?php echo esc_html( apply_filters( 'wfp_dashboard_donate_text', __( 'Donate', 'wp-fundraising-donation' ) ) ); ?> </a></li>
			</ul>
		</li>
		
	</ul>
	
</div>
<div class="wfp-logout">
	<a class="xs-btn xs-btn-danger logout-button" href="<?php echo esc_url( wp_logout_url( 'wfp-dashboard' ) ); ?>"><?php echo esc_html( apply_filters( 'wfp_dashboard_logout_text', __( 'Logout', 'wp-fundraising-donation' ) ) ); ?><i class="wfpf wfpf-arrow-right"></i></a>
</div>
