<?php 
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

?>
<div class="wfp-add-campaign">
	<button class="xs-btn xs-btn-primary xs-text-center add-button">
		<a href="?wfp-page=campaign"><?php echo esc_html( apply_filters( 'wfp_dashboard_new_campaign_text', __( '+ New Campaign', 'wp-fundraising-donation' ) ) ); ?><span class="icon"></span> </a>
	</button>
</div>

<div class="wfp-profile-user test">
	<?php
	$wfp_author_id    = get_current_user_id();
	$wfpProfileImage = get_the_author_meta( 'avatar', $wfp_author_id );
	if ( strlen( $wfpProfileImage ) < 5 ) {
		$wfpProfileImage = get_the_author_meta( 'wdp_author_profile_image', $wfp_author_id );
	}

	?>
	<div class="profile-image">
		<?php if ( strlen( $wfpProfileImage ) > 5 ) { ?>
			<img src="<?php echo esc_url( $wfpProfileImage ); ?> " class="avatar wfp-profile-image" alt="<?php esc_attr( the_author_meta( 'display_name', $wfp_author_id ) ); ?>" />
		<?php } else { ?>
			<?php echo wp_kses( get_avatar( $wfp_author_id, 60 ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
		<?php } ?>
	</div>

	<div class="profile-info">
		<span class="display-name">Howdy! <strong><?php esc_html( the_author_meta( 'display_name', $wfp_author_id ) ); ?></strong></span>
	</div>

	<div class="wfp-donate-amount">
	<?php
		global $wpdb;
		//phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Prepared aggregate read for dashboard short profile amount.
		$wfpBackendAmount = $wpdb->get_var(
			//phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Prepared aggregate read for dashboard short profile amount.
			$wpdb->prepare(
				"SELECT SUM(fund.donate_amount) FROM $wpdb->posts as post 
		INNER JOIN " . $wpdb->prefix . "wdp_fundraising as fund ON post.ID = fund.form_id
		WHERE post.post_author = %d AND post_type = %s AND fund.status IN ('Active')",
				$wfpUserId,
				self::post_type()
			)
		);
		?>
		<span><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', 'on' ) ); ?><strong><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfpBackendAmount ) ); ?></strong><em class="wfp-currency-symbol"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', 'on' ) ); ?></em> </span>
	</div>
</div>
