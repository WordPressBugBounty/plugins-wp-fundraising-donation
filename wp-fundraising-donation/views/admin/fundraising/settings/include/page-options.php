<div class="wfp-woocommerce-message xs-donate-hidden <?php echo ( $gateCampaignData == 'woocommerce' ) ? 'xs-donate-visible' : ''; ?>">
	<p>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=wc-settings&tab=advanced' ) ); ?>"> <?php echo esc_html__( 'Page Setup of Woocommerce', 'wp-fundraising' ); ?> </a>
	</p>
</div>

<div class="wfdp-payment-section wfdp-payment-section-page-option wfp-disabled-div <?php echo ( $gateCampaignData == 'woocommerce' ) ? 'wfp-disabled' : ''; ?>">
	<div class="wfdp-payment-headding">
		<h2><?php echo esc_html__( 'Setup Page Settings', 'wp-fundraising' ); ?></h2>
	</div>
	<div class="wfdp-payment-gateway">
		<form action="<?php echo esc_url( admin_url() . 'edit.php?post_type=' . self::post_type() . '&page=settings&tab=page' ); ?>"
			  method="post">
			  <?php wp_nonce_field( 'wpf_save_settings', 'wpf_settings_nonce' ); ?>
			<ul class="wfdp-social_share">
				<li class="wfdp-social_share-section-title">
					<h3><?php echo esc_html__( 'Page options', 'wp-fundraising' ); ?></h3>
				</li>
				<?php
				$pgOptions = \WfpFundraising\Apps\Settings::default_custom_page();
				$pages     = get_pages();

				foreach ( $pgOptions as $key => $arr ) :
					?>

					<li class="wfdp-social-input-container">
						<div class="wfdp-social-label">
							<?php echo esc_html( $arr['title'] ); ?>
						</div>
						<div class="wfdp-social-input wfdp-social-input-split">
							<div class="wfdp-social-input-split">
								<div class="wfdp-social-input--content">
									<?php

									$def_page_id = isset( $getMetaGeneral['pages'][ $key ] ) ? $getMetaGeneral['pages'][ $key ] : 0;
									?>

									<select class="regular-text wfp-select2-country"
											name="xs_submit_settings_data_general[options][pages][<?php echo esc_attr( $key ); ?>]">
																											 <?php

																												if ( ! empty( $pages ) && is_array( $pages ) ) {

																													foreach ( $pages as $page ) {

																														$selected = $def_page_id == $page->ID ? 'selected' : '';

																														?>
												<option <?php echo esc_attr( $selected ); ?>
												value="<?php echo esc_attr( $page->ID ); ?>"><?php echo esc_html( $page->post_title ); ?></option>
																														<?php
																													}
																												}
																												?>
									</select>
								</div>
								<div class="xs-donate-field-wrap">

									<?php

									if ( ! empty( $arr['short_code'] ) ) {
										?>
										<div class="xs-donate-field-short-code">
											<input class="donate_text_filed donate_shortcode_wp"
												   type="text"
												   id="wp_doante_shortcode_<?php echo esc_attr( $key ); ?>"
												   value='[<?php echo esc_attr( $arr['short_code'] ); ?>]'
												   readonly="readonly" />
											<button type="button"
													onclick="wdp_copyTextData('wp_doante_shortcode_<?php echo esc_attr( $key ); ?>');"
													class="xs_copy_button">
												<span class="dashicons dashicons-admin-page"></span>
											</button>
										</div>
										<?php
									}

									?>
								</div>


							</div>

						</div>
					</li>
					<?php
				endforeach;
				?>

			</ul>

			<button type="submit" name="submit_donate_page_setting"
					class="button button-primary button-large"><?php echo esc_html__( 'Save', 'wp-fundraising' ); ?></button>
		</form>
	</div>
</div>

<script type="text/javascript">
	jQuery(document).ready(function () {
		jQuery('.wfp-select2-country').select2();
	});
</script>
