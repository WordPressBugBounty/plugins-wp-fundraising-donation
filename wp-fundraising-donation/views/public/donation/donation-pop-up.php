<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

$wfp_cur_symbol = \WfpFundraising\Apps\Global_Settings::instance()->get_currency_code();
?>
<div class="wfp-donation-pop-up-body">
	<p class="wfp-donation-pop-up-heading">
		<?php echo esc_html__( 'Make a donation', 'wp-fundraising-donation' ); ?>
	</p>
	<p class="wfp-donation-pop-up-paragraph">
		<?php echo esc_html__( 'To learn more about make donate charity with our visit our Contact us site. By calling +44(0)8008838450', 'wp-fundraising-donation' ); ?>
	</p>
	<form method="post" action="">
		<?php wp_nonce_field( 'wpf_donate_pop_nonce_field', 'wpf_donate_popup' ); ?>
		<div class="wfp-form">
			<input type="hidden" class="donation_type" name="donation_type" required>
			<div class="wfp-donation-pop-up-form-group">
				<label for=""><?php echo esc_html__( 'List Of Donation', 'wp-fundraising-donation' ); ?></label>
				<select name="ID" onchange="wfp_donation_pop_up_donation_list(this)" required>
					<option value=""><?php echo esc_html__( '-- Select One --', 'wp-fundraising-donation' ); ?></option>
					<?php
					$wfp_pop_up_multiple_meta = array();
					$wfp_i                        = 1;
					foreach ( $wfp_pop_up_donnations as $wfp_pop_up_donnation ) :

						$wfp_pop_up_meta_data_Json = get_post_meta( $wfp_pop_up_donnation->ID, $wfp_pop_up_metaKey, false );
						$wfp_pop_up_get_meta_data  = json_decode( json_encode( end( $wfp_pop_up_meta_data_Json ), JSON_UNESCAPED_UNICODE ) );

						if ( $wfp_pop_up_get_meta_data->donation->format == 'donation' ) :
							$wfp_pop_up_enable_custom_amount = 'No';

							if ( isset( $wfp_pop_up_get_meta_data->donation->fixed->enable_custom_amount ) && $wfp_pop_up_get_meta_data->donation->fixed->enable_custom_amount == 'Yes' ) {
								$wfp_pop_up_enable_custom_amount = 'Yes';
							}

							if ( $wfp_pop_up_get_meta_data->donation->type == 'multi-lebel' ) {
								$wfp_pop_up_multi_array = array(
									'no'                   => $wfp_i,
									'meta'                 => $wfp_pop_up_get_meta_data,
									'enable_custom_amount' => $wfp_pop_up_enable_custom_amount,
								);
								array_push( $wfp_pop_up_multiple_meta, $wfp_pop_up_multi_array );
							}
							?>
							<option data-permalink="<?php echo esc_url( get_permalink( $wfp_pop_up_donnation->ID ) ); ?>" data-no="<?php echo esc_attr( $wfp_i ); ?>" data-enable_custom_amount="<?php echo esc_attr( $wfp_pop_up_enable_custom_amount ); ?>" value="<?php echo esc_attr( $wfp_pop_up_donnation->ID ); ?>" data-type="<?php echo esc_attr( $wfp_pop_up_get_meta_data->donation->type ); ?>" data-price="<?php echo esc_attr( $wfp_pop_up_get_meta_data->donation->fixed->price ); ?>"><?php echo esc_html( $wfp_pop_up_donnation->post_title ); ?></option>
							<?php
					endif;
						$wfp_i++;
					endforeach;
					?>
				</select>
			</div>
			<div class="wfp-donation-pop-up-form-group wfp-donation-pop-up-fixed" style="display: none;">
			</div>
			<?php
			foreach ( $wfp_pop_up_multiple_meta as $wfp_meta ) :
				?>
				<div class="wfp-donation-pop-up-form-group wfp-donation-pop-up-multi wfp-donation-pop-up-multi-<?php echo esc_attr( $wfp_meta['no'] ); ?>" style="display: none;">
					<label for=""><?php esc_html_e( 'Multi Price', 'wp-fundraising-donation' ); ?></label>
					<select name="multiple_amount" id="" onchange="wfp_pop_up_custom_amount(this)" required>
						<?php
						foreach ( $wfp_meta['meta']->donation->multi->dimentions as $multi ) :
							?>
							<option value="<?php echo esc_attr( $multi->price ); ?>"><?php echo esc_html( $multi->lebel . ' (' . $multi->price . ' ' . $wfp_cur_symbol . ')' ); ?></option>
							<?php
						endforeach;
						if ( isset( $wfp_meta['meta']->donation->fixed->enable_custom_amount ) && $wfp_meta['meta']->donation->fixed->enable_custom_amount == 'Yes' ) :
							?>
							<option value="custom"><?php echo esc_html__( 'Custom', 'wp-fundraising-donation' ); ?></option>
						<?php endif; ?>

					</select>
				</div>
			<?php endforeach ?>
		</div>
		<button class="wfp-donation-pop-up-button"><?php echo esc_html__( 'Donate Now', 'wp-fundraising-donation' ); ?></button>
	</form>
</div>
