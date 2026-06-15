<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

?>
<div class="setting-nav-wrapper">
	<h2 class="nav-tab-wrapper">
		<a href="?post_type=<?php echo esc_attr( self::post_type() ); ?>&page=settings&tab=general" class="nav-tab 
									   <?php
										if ( $wfp_active_tab == 'general' ) {
											echo esc_attr( 'nav-tab-active' );}
										?>
		 "><?php esc_html_e( 'General Settings', 'wp-fundraising-donation' ); ?></a>
		<a href="?post_type=<?php echo esc_attr( self::post_type() ); ?>&page=settings&tab=global" class="nav-tab 
									   <?php
										if ( $wfp_active_tab == 'global' ) {
											echo esc_attr( 'nav-tab-active' );}
										?>
		 "><?php esc_html_e( 'Global Options', 'wp-fundraising-donation' ); ?></a>
		<a href="?post_type=<?php echo esc_attr( self::post_type() ); ?>&page=settings&tab=display" class="nav-tab 
									   <?php
										if ( $wfp_active_tab == 'display' ) {
											echo esc_attr( 'nav-tab-active' );}
										?>
		 "><?php esc_html_e( 'Display Settings', 'wp-fundraising-donation' ); ?></a>
		<a href="?post_type=<?php echo esc_attr( self::post_type() ); ?>&page=settings&tab=gateway" class="nav-tab 
									   <?php
										if ( $wfp_active_tab == 'gateway' ) {
											echo esc_attr( 'nav-tab-active' );}
										?>
		 "><?php esc_html_e( 'Payment Method', 'wp-fundraising-donation' ); ?></a>
		<a href="?post_type=<?php echo esc_attr( self::post_type() ); ?>&page=settings&tab=share" class="nav-tab 
									   <?php
										if ( $wfp_active_tab == 'share' ) {
											echo esc_attr( 'nav-tab-active' );}
										?>
		 "><?php esc_html_e( 'Share Options', 'wp-fundraising-donation' ); ?></a>
		<a href="?post_type=<?php echo esc_attr( self::post_type() ); ?>&page=settings&tab=terms" class="nav-tab 
									   <?php
										if ( $wfp_active_tab == 'terms' ) {
											echo esc_attr( 'nav-tab-active' );}
										?>
		 "><?php esc_html_e( 'Terms & Condition', 'wp-fundraising-donation' ); ?></a>
		<a href="?post_type=<?php echo esc_attr( self::post_type() ); ?>&page=settings&tab=page" class="nav-tab 
									   <?php
										if ( $wfp_active_tab == 'page' ) {
											echo esc_attr( 'nav-tab-active' );}
										?>
		 "><?php esc_html_e( 'Page Options', 'wp-fundraising-donation' ); ?></a>

		<?php

		if ( did_action( \WfpFundraising\Apps\Key::FUNDRAISING_PRO_LOADED ) ) {
			?>

			<a href="?post_type=<?php echo esc_attr( self::post_type() ); ?>&page=settings&tab=email_options" class="nav-tab 
										   <?php
											if ( $wfp_active_tab == 'email_options' ) {
												echo esc_attr( 'nav-tab-active' );}
											?>
			 "><?php esc_html_e( 'Email settings', 'wp-fundraising-donation' ); ?></a>

			<a href="?post_type=<?php echo esc_attr( self::post_type() ); ?>&page=settings&tab=pp_gateway" class="nav-tab 
										   <?php
											if ( $wfp_active_tab == 'pp_gateway' ) {
												echo esc_attr( 'nav-tab-active' );}
											?>
			 "><?php esc_html_e( 'Personal payment', 'wp-fundraising-donation' ); ?></a>
										   <?php
		}

		?>
	</h2>
</div>
