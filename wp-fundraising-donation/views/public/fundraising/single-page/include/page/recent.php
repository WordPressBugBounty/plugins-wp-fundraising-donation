<?php

$postId = empty( $post->ID ) ? get_the_ID() : $post->ID;

if ( is_array( $recentDonation ) && sizeof( $recentDonation ) > 0 ) : ?>

	<div class="wfp-recent-section">
		<?php
		foreach ( $recentDonation as $v ) :

			$form_id      = (int) isset( $v->form_id ) ? $v->form_id : 0;
			$user_id      = (int) isset( $v->user_id ) ? $v->user_id : 0;
			$email        = isset( $v->email ) ? $v->email : '';
			$donateAmount = (float) isset( $v->donate_amount ) ? $v->donate_amount : 0;
			$pledgeAmount = (float) isset( $v->pledge_id ) ? $v->pledge_id : 0;
			$date         = isset( $v->date_time ) ? $v->date_time : 0;
			?>
			<div class="recent-block">
				<div class="user-info">
					<?php if ( $user_id > 0 ) { ?>
						<div class="wfp-campaign-user utrace2">
							<?php
							$profileImage = get_the_author_meta( 'avatar', $user_id );
							if ( strlen( $profileImage ) < 5 ) {
								$profileImage = get_the_author_meta( 'wdp_author_profile_image', $user_id );
							}

							?>
							<div class="profile-image">
								<?php
								if ( strlen( $profileImage ) > 5 ) {
									?>
									<img src="<?php echo esc_url( $profileImage ); ?> " class="avatar wfp-profile-image"
										 alt="<?php the_author_meta( 'display_name', $user_id ); ?>"/>
									<?php
								} else {
									echo wp_kses( get_avatar( $user_id, 32 ), \WfpFundraising\Utilities\Utils::get_kses_array() );
								}
								?>
							</div>
							<div class="profile-info">
								<span class="display-name"><?php the_author_meta( 'display_name', $user_id ); ?></span>
								<?php if ( $enableSingleContributor == 'Yes' ) { ?>
									<span class="country-name"><?php echo esc_html( $email ); ?></span>
								<?php } ?>
							</div>
						</div>
					<?php } else { ?>
						<p> <?php echo esc_html__( 'Unknown', 'wp-fundraising' ); ?> </p>
					<?php } ?>
				</div>
				<div class="price-report">
					<?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $defaultUse_space ) ); ?>
					<strong class="price-report--amount"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $donateAmount ) ); ?></strong>
					<em class="price-report--symbol"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $defaultUse_space ) ); ?></em>
				</div>

				<div class="report-date"><?php echo esc_html__( 'Date:', 'wp-fundraising' ); ?><?php echo esc_html( gmdate( 'M Y', strtotime( $date ) ) ); ?></div>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
endif;
