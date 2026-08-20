<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

$wfpPostId = empty( $post->ID ) ? get_the_ID() : $post->ID;

if ( is_array( $wfpRecentDonation ) && sizeof( $wfpRecentDonation ) > 0 ) : ?>

	<div class="wfp-recent-section">
		<?php
		foreach ( $wfpRecentDonation as $v ) :

			$wfp_form_id      = (int) isset( $v->form_id ) ? $v->form_id : 0;
			$wfp_user_id      = (int) isset( $v->user_id ) ? $v->user_id : 0;
			$wfp_email        = isset( $v->email ) ? $v->email : '';
			$wfpDonateAmount = (float) isset( $v->donate_amount ) ? $v->donate_amount : 0;
			$wfpPledgeAmount = (float) isset( $v->pledge_id ) ? $v->pledge_id : 0;
			$wfp_date         = isset( $v->date_time ) ? $v->date_time : 0;
			?>
			<div class="recent-block">
				<div class="user-info">
					<?php if ( $wfp_user_id > 0 ) { ?>
						<div class="wfp-campaign-user utrace2">
							<?php
							$wfpProfileImage = get_the_author_meta( 'avatar', $wfp_user_id );
							if ( strlen( $wfpProfileImage ) < 5 ) {
								$wfpProfileImage = get_the_author_meta( 'wdp_author_profile_image', $wfp_user_id );
							}

							?>
							<div class="profile-image">
								<?php
								if ( strlen( $wfpProfileImage ) > 5 ) {
									?>
									<img src="<?php echo esc_url( $wfpProfileImage ); ?> " class="avatar wfp-profile-image"
										 alt="<?php the_author_meta( 'display_name', $wfp_user_id ); ?>"/>
									<?php
								} else {
									echo wp_kses( get_avatar( $wfp_user_id, 32 ), \WfpFundraising\Utilities\Utils::get_kses_array() );
								}
								?>
							</div>
							<div class="profile-info">
								<span class="display-name"><?php the_author_meta( 'display_name', $wfp_user_id ); ?></span>
								<?php if ( $wfpEnableSingleContributor == 'Yes' ) { ?>
									<span class="country-name"><?php echo esc_html( $wfp_email ); ?></span>
								<?php } ?>
							</div>
						</div>
					<?php } else { ?>
						<p> <?php echo esc_html__( 'Unknown', 'wp-fundraising-donation' ); ?> </p>
					<?php } ?>
				</div>
				<div class="price-report">
					<?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $wfp_defaultUse_space ) ); ?>
					<strong class="price-report--amount"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfpDonateAmount ) ); ?></strong>
					<em class="price-report--symbol"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $wfp_defaultUse_space ) ); ?></em>
				</div>

				<div class="report-date"><?php echo esc_html__( 'Date:', 'wp-fundraising-donation' ); ?><?php echo esc_html( wp_date( 'M Y', strtotime( $wfp_date ), new \DateTimeZone( 'UTC' ) ) ); ?></div>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
endif;
