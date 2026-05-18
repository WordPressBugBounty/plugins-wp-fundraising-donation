<?php 
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

?>
<div class="reports-content--list">
		<?php
			global $wpdb;

			$wfp_report = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . "wdp_fundraising WHERE user_id = %d AND status IN('Active') ORDER BY date_time DESC", $wfpUserId ) ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Prepared read for dashboard backer report list.

		if ( is_array( $wfp_report ) && sizeof( $wfp_report ) > 0 ) :
			foreach ( $wfp_report as $v ) :

				$wfp_form_id      = (int) isset( $v->form_id ) ? $v->form_id : 0;
				$wfpDonateAmount = (float) isset( $v->donate_amount ) ? $v->donate_amount : 0;
				$wfpPledgeAmount = (float) isset( $v->pledge_id ) ? $v->pledge_id : 0;
				$wfp_date         = isset( $v->date_time ) ? $v->date_time : 0;

				$post = get_post( $wfp_form_id );

				if ( is_object( $post ) ) {
					?>
				<div class="intro-info report-block">
					<div class="wfp-report-title"><a class="wfp-report-title--link" href="<?php echo esc_url( get_permalink( $wfp_form_id ) ); ?>">
						<?php echo esc_html( '' . isset( $post->post_title ) ? $post->post_title : '' . ' ' ); ?></a>
					</div>
					<div class="price-report">
						<?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $wfp_defaultUse_space ) ); ?>
						<strong><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfpDonateAmount ) ); ?></strong>
						<em class="wfp-currency-symbol"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $wfp_defaultUse_space ) ); ?></em> 
					</div>
					<div class="report-date"><?php echo esc_html__( 'Date:', 'wp-fundraising' ); ?> <?php echo esc_html( gmdate( 'M Y', strtotime( $wfp_date ) ) ); ?></div>
				</div>	
					<?php
				}
				endforeach;
			endif;
		?>

	</div>
