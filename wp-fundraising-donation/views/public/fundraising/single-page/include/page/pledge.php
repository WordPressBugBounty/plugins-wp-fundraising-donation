<?php

/**
 * As this file is included for both shortcode and norman and no common variable was defined to
 * declaring another understandable variable, so actual page id is not returned for wfp_form_id
 * old postId is not changed, replace it step by step :P
 */

$wfp_form_id = isset( $atts['form-id'] ) ? intval( $atts['form-id'] ) : ( empty( $post->ID ) ? get_the_ID() : $post->ID );
$postId      = empty( $post->ID ) ? get_the_ID() : $post->ID;

?>

<div class="wfp-pledge-section" id="pledge_section__<?php echo esc_attr( $postId ); ?>">
	<h2 class="wfp-pledge-section-title"><?php echo esc_html( apply_filters( 'wfp_single_content_rewards', __( 'Rewards', 'wp-fundraising' ) ) ); ?></h2>
	<?php
	$m   = 0;
	$opt = get_option( \WfpFundraising\Apps\Fundraising::WFP_OK_REWARD_DATA );
	foreach ( $multiPleData as $multi ) :
		?>
		<div class="wfp-pledge-block" id="wfp-pledge-block__<?php echo esc_attr( $m ); ?>" onclick="wfp_select_pledge(this);">
			<div class="pledge__hover-content xs-text-center">
				<p><?php echo esc_html( apply_filters( 'wfp_single_content_select_rewards', __( 'Select this reward', 'wp-fundraising' ) ) ); ?></p>
			</div>
			<h3 class="wfp-pledge-title"><?php echo esc_html( '' . isset( $multi->lebel ) ? $multi->lebel : '' . ' ' ); ?></h3>

			<div class="wfp-description wfp-pledge-hide">
				<p><?php echo wp_kses( isset( $multi->description ) ? $multi->description : '', \WfpFundraising\Utilities\Utils::get_kses_array() ); ?></p>
			</div>

			<div class="pledge__detail">
				<span class="pledge__detail-label"> <?php echo esc_html( apply_filters( 'wfp_single_content_rewards_amount', __( 'Reward Amount:', 'wp-fundraising' ) ) ); ?></span>
				<span class="pledge__detail-info">
					<?php
					$amount = isset( $multi->price ) ? $multi->price : '0';
					echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $defaultUse_space ) );
					?>
					<strong><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $amount ) ); ?></strong>
					<span class="wfp-currency-symbol"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $defaultUse_space ) ); ?></span>
				</span>
			</div>

			<?php
			$quantityData = isset( $multi->quantity ) ? $multi->quantity : '';

			if ( $quantityData > 0 ) {

				if ( empty( $multi->id ) || empty( $opt[ $postId ][ $multi->id ] ) ) {
					$claimed = 0;
				} else {
					$claimed = empty( $opt[ $postId ][ $multi->id ] ) ? 0 : count( $opt[ $postId ][ $multi->id ] );
				}

				?>

				<div class="pledge__detail">
					<span class="pledge__detail-label"> <?php echo esc_html( apply_filters( 'wfp_single_content_rewards_left', __( 'Item Left:', 'wp-fundraising' ) ) ); ?></span>
					<span class="pledge__detail-info"><?php echo wp_kses( $claimed, \WfpFundraising\Utilities\Utils::get_kses_array() ); ?> <?php echo esc_html__( 'out of', 'wp-fundraising' ); ?> <?php echo esc_html( $quantityData ); ?> <?php echo esc_html__( 'claimed', 'wp-fundraising' ); ?> </span>
				</div>

				<?php
			}
			$includesData = isset( $multi->includes ) ? $multi->includes : '';
			if ( strlen( $includesData ) > 2 ) {
				$explodeLi = explode( ',', $includesData );
				?>
				<div class="pledge__detail wfp-pledge-hide">
					<?php if ( is_array( $explodeLi ) && ! empty( $explodeLi ) ) { ?>
						<span class="pledge__detail-label"> <?php echo esc_html( apply_filters( 'wfp_single_content_rewards_include', __( 'Includes:', 'wp-fundraising' ) ) ); ?></span>
						<ul class="pledge__detail-info-list">
							<?php foreach ( $explodeLi as $listData ) : ?>
								<li> <?php echo esc_html( $listData ); ?> </li>
							<?php endforeach; ?>
						</ul>
					<?php } ?>
				</div>
				<?php
			}
			$estimatedData = isset( $multi->estimated ) ? $multi->estimated : '';
			if ( strlen( $estimatedData ) > 3 ) {
				?>
				<div class="pledge__detail">
					<span class="pledge__detail-label"> <?php echo esc_html( apply_filters( 'wfp_single_content_rewards_estimated', __( 'Estimated Delivery:', 'wp-fundraising' ) ) ); ?></span>
					<span class="pledge__detail-info"> <?php echo esc_html( date( 'M Y', strtotime( $estimatedData ) ) ); ?></span>
				</div>
				<?php
			}
			$shipsdData = isset( $multi->ships ) ? $multi->ships : '';

			if ( strlen( $shipsdData ) > 3 ) {
				?>
				<div class="pledge__detail">
					<span class="pledge__detail-label"> <?php echo esc_html( apply_filters( 'wfp_single_content_rewards_ships', __( 'Ships To:', 'wp-fundraising' ) ) ); ?></span>
					<span class="pledge__detail-info"> <?php echo esc_html( $shipsdData ); ?></span>
				</div>
			<?php } ?>


			<div class="wfp-pledge-hide">
				<?php

				if ( $campaign_status == 'Publish' ) {
					?>
					<span class="pledge__detail-label"> <?php echo esc_html( apply_filters( 'wfp_single_content_rewards_pledge_amount', __( 'Pledge amount:', 'wp-fundraising' ) ) ); ?></span>
					<div class="wfp-additional-data">
						<div class="wfdp-input-payment-field xs-fixed-lebel">
							<div class="pledge__detail-info">
								<span class="xs-money-symbol xs-money-symbol-before"><?php echo esc_html( $cur_symbol ); ?> </span>
								<input type="number"
									   min="0"
									   name="xs_donate_amount_pledge"
									   id="xs_donate_amount_pledge"
									   disabled
									   value="<?php echo esc_attr( strlen( $amount ) > 0 ? $amount : 0 ); ?>"
									   placeholder="1.00"
									   class="xs-field xs-money-field wfp-pledge-amount "/>
							</div>
						</div>

						<button type="submit"
								name="submit-form-donation"
								onclick="submit_pledge_amount(this)"
								data-form-id="<?php echo esc_attr( $wfp_form_id ); ?>"
								data-pledge_id="<?php echo esc_attr( empty( $multi->id ) ? '0' : $multi->id ); ?>"
								data-pledge_amt="<?php echo esc_attr( empty( $amount ) ? '0' : floatval( $amount ) ); ?>"
								data-gateway="<?php echo esc_attr( \WfpFundraising\Apps\Global_Settings::instance()->get_payment_type() ); ?>"
								data-wfp-index="<?php echo esc_attr( $m ); ?>"
								wfp-id="<?php echo esc_attr( $postId ); ?>"
								wfp-index="<?php echo esc_attr( $m ); ?>"
								data-checkout_url="<?php echo esc_url( add_query_arg( 'wpf_checkout_nonce_field', wp_create_nonce( 'wpf_checkout' ), $urlCheckout ) ); ?>"
								wfp-pledge="<?php echo esc_attr( strlen( $amount ) > 0 ? $amount : 0 ); ?>"
								id="wfp_pledge_button"
								class="xs-btn btn-special submit-btn">
							<?php echo esc_html( apply_filters( 'wfp_single_content_rewards_continue_button', __( 'Continue', 'wp-fundraising' ) ) ); ?>
						</button>

						<?php // only if woocommerce payment enabled then below ?>
						<div class="wfp_hidden_form_container">
							<form action="" method="post" id="add_cart_<?php echo esc_attr( $wfp_form_id ); ?>">
								<input name="add-to-cart" type="hidden" value="<?php echo esc_attr( $wfp_form_id ); ?>"/>
								<input name="quantity" type="hidden" value="1" min="1"/>
							</form>
						</div>

					</div>
				<?php } ?>
			</div>
		</div>
		<?php
		$m++;
	endforeach;
	?>
</div>
