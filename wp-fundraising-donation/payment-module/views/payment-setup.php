<?php
defined( 'ABSPATH' ) || exit;
?>
<div class="wfdp-payment-section wfp-disabled-div <?php echo ( $wfpGateCampaignData == 'woocommerce' ) ? 'wfp-disabled' : ''; ?>" >
	<div class="wfdp-payment-headding">
		<h2><?php echo esc_html__( 'Setup Payment Gateways', 'wp-fundraising-donation' ); ?></h2>
	</div>
	 
	<div class="wfdp-payment-gateway">
		<form action="<?php echo esc_url( admin_url() . 'edit.php?post_type=' . self::post_type() . '&page=settings&tab=gateway' ); ?>" method="post">
		<?php wp_nonce_field( 'wpf_save_settings', 'wpf_settings_nonce' ); ?>
		<div class="wfdp-input-payment-field ">
			<div class="right-div">
				<table class="form-table wfdp-table-design wc_gateways widefat">
					<thead>
						<tr>
							<th class="sort"></th>
							<th class="name"> <?php echo esc_html__( 'Gateways', 'wp-fundraising-donation' ); ?></th>
							<th class="enable"> <?php echo esc_html__( 'Enable', 'wp-fundraising-donation' ); ?></th>
							<th class="description"> <?php echo esc_html__( 'Description', 'wp-fundraising-donation' ); ?></th>
							<th class="info"></th>
						</tr>
					</thead>
					<tbody id="wfdp-payment-method-sortable" class="ui-sortable">
					<?php
						$wfp_fundraising_i                 = 0; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: local counter prefixed to `wfp_fundraising_`
						$wfp_fundraising_arrayPayment = $arrayPayment; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: local array prefixed to `wfp_fundraising_`
						if ( isset( $gateWaysData['services'] ) && sizeof( $gateWaysData['services'] ) > 0 ) {
							$wfp_fundraising_arrayPayment = $gateWaysData['services']; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: local array prefixed to `wfp_fundraising_`
							if ( sizeof( $arrayPayment ) > sizeof( $gateWaysData['services'] ) ) {
								$wfp_fundraising_arrayPayment = $arrayPayment; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: local array prefixed to `wfp_fundraising_`
							}
						}

						foreach ( $wfp_fundraising_arrayPayment as $wfp_fundraising_key => $wfp_fundraising_payment ) : // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: loop variables prefixed to `wfp_fundraising_`
							$wfp_fundraising_payment = $arrayPayment[ $wfp_fundraising_key ]; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: assignment uses plugin-prefixed loop key

							$wfp_fundraising_optionsData = isset( $gateWaysData['services'][ $wfp_fundraising_key ] ) ? $gateWaysData['services'][ $wfp_fundraising_key ] : array(); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: options var prefixed to `wfp_fundraising_`

						?>
						<tr class="ui-state-default">
							<td class="icon"> <span class="dashicons dashicons-menu"></span> </td>
							<td class="name"> <?php echo esc_html( $wfp_fundraising_payment['name'] ); ?></td>
							
							<td class="enable">
								<ul class="donate-option">
									<li class="xs-switch-button_wraper">
										<input class="xs_donate_switch_button" type="checkbox" value="Yes" id="donation_form_payment_enable__<?php echo esc_attr( $wfp_fundraising_i ); ?>" <?php echo isset( $wfp_fundraising_optionsData['enable'] ) && $wfp_fundraising_optionsData['enable'] == 'Yes' ? 'checked' : ''; ?> name="xs_submit_settings_data[gateways][services][<?php echo esc_attr( $wfp_fundraising_key ); ?>][enable]">
										<label for="donation_form_payment_enable__<?php echo esc_attr( $wfp_fundraising_i ); ?>" class="xs_donate_switch_button_label small xs-round"></label>
									</li>
									
								</ul>
							</td>
							<td class="description">
							<?php echo wp_kses( $wfp_fundraising_payment['description'], \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
							</td>
							<td class="information">
							<button type="button" class="xs-btn btn-special continue-bt <?php echo esc_attr( $wfp_fundraising_key ); ?> wfdp-btn" <?php echo isset( $wfp_fundraising_optionsData['enable'] ) && $wfp_fundraising_optionsData['enable'] == 'Yes' ? '' : 'disabled'; ?> data-type="modal-trigger" data-target="xs-donate-modal-popup__<?php echo esc_attr( $wfp_fundraising_key ); ?>"> 
						<?php echo esc_html( isset( $wfp_fundraising_optionsData['enable'] ) && $wfp_fundraising_optionsData['enable'] == 'Yes' ? __( 'Manage', 'wp-fundraising-donation' ) : __( 'Setup', 'wp-fundraising-donation' ) ); ?>
							</button>
							
							</td>
							
						</tr>
						<?php
						$wfp_fundraising_i++; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: local counter prefixed to `wfp_fundraising_`
endforeach;
					?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="5" align="right"> 
								<button type="submit" name="submit_donate_settings_gateways" class="button button-primary button-large"><?php echo esc_html__( 'Save', 'wp-fundraising-donation' ); ?></button>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
		
		<?php
		require __DIR__ . '/include/payment-popup-box.php';
		?>
		</form>
	</div>
</div>
<script type="text/javascript">
/*Reapter data*/

jQuery(document).ready(function($){
   $("#wfdp-payment-method-sortable").sortable();
   $("#wfdp-payment-method-sortable").disableSelection();
});


</script>
