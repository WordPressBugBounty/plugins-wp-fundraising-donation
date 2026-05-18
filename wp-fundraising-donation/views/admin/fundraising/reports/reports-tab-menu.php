<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

$wfp_nonce = wp_create_nonce( 'wp_rest' ); ?>
<ul class="wfp-nav-tab-wrapper">
	<li>
		<a href="?post_type=<?php echo esc_attr( self::post_type() ); ?>&page=report&tab=income&nonce=<?php echo esc_attr( $wfp_nonce ); ?>" class="nav-tab 
									   <?php
										if ( $wfp_active_tab == 'income' ) {
											echo esc_attr( 'nav-tab-active' );}
										?>
		 "><?php echo esc_html__( 'Income', 'wp-fundraising' ); ?></a>
	</li>
	<li>
		<a href="?post_type=<?php echo esc_attr( self::post_type() ); ?>&page=report&tab=donors&nonce=<?php echo esc_attr( $wfp_nonce ); ?>" class="nav-tab 
									   <?php
										if ( $wfp_active_tab == 'donors' ) {
											echo esc_attr( 'nav-tab-active' );}
										?>
		"><?php echo esc_html__( 'Donors', 'wp-fundraising' ); ?></a>
	</li>
	<li>
		<a href="?post_type=<?php echo esc_attr( self::post_type() ); ?>&page=report&tab=advanced&nonce=<?php echo esc_attr( $wfp_nonce ); ?>" class="nav-tab 
									   <?php
										if ( $wfp_active_tab == 'advanced' ) {
											echo esc_attr( 'nav-tab-active' );}
										?>
		"><?php echo esc_html__( 'Purge', 'wp-fundraising' ); ?></a>
	</li>
</ul>
