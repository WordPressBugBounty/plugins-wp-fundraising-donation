<div class="wfdp-payment-section wfp-disabled-div <?php echo ( $gateCampaignData == 'woocommerce' ) ? 'wfp-disabled' : ''; ?>" >
	<div class="wfdp-payment-headding">
		<h2><?php echo esc_html__( 'Setup Payment Gateways', 'wp-fundraising' ); ?></h2>
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
							<th class="name"> <?php echo esc_html__( 'Gateways', 'wp-fundraising' ); ?></th>
							<th class="enable"> <?php echo esc_html__( 'Enable', 'wp-fundraising' ); ?></th>
							<th class="description"> <?php echo esc_html__( 'Description', 'wp-fundraising' ); ?></th>
							<th class="info"></th>
						</tr>
					</thead>
					<tbody id="wfdp-payment-method-sortable" class="ui-sortable">
					<?php
						$i                 = 0;
						$arrayPaymentArray = $arrayPayment;
					if ( isset( $gateWaysData['services'] ) && sizeof( $gateWaysData['services'] ) > 0 ) {
						$arrayPaymentArray = $gateWaysData['services'];
						if ( sizeof( $arrayPayment ) > sizeof( $gateWaysData['services'] ) ) {
							$arrayPaymentArray = $arrayPayment;
						}
					}

					foreach ( $arrayPaymentArray as $key => $payment ) :
						$payment = $arrayPayment[ $key ];

						$optionsData = isset( $gateWaysData['services'][ $key ] ) ? $gateWaysData['services'][ $key ] : array();

						?>
						<tr class="ui-state-default">
							<td class="icon"> <span class="dashicons dashicons-menu"></span> </td>
							<td class="name"> <?php echo esc_html( $payment['name'] ); ?></td>
							
							<td class="enable">
								<ul class="donate-option">
									<li class="xs-switch-button_wraper">
										<input class="xs_donate_switch_button" type="checkbox" value="Yes" id="donation_form_payment_enable__<?php echo esc_attr( $i ); ?>" <?php echo isset( $optionsData['enable'] ) && $optionsData['enable'] == 'Yes' ? 'checked' : ''; ?> name="xs_submit_settings_data[gateways][services][<?php echo esc_attr( $key ); ?>][enable]">
										<label for="donation_form_payment_enable__<?php echo esc_attr( $i ); ?>" class="xs_donate_switch_button_label small xs-round"></label>
									</li>
									
								</ul>
							</td>
							<td class="description">
							<?php echo wp_kses( $payment['description'], \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
							</td>
							<td class="information">
							<button type="button" class="xs-btn btn-special continue-bt <?php echo esc_attr( $key ); ?> wfdp-btn" <?php echo isset( $optionsData['enable'] ) && $optionsData['enable'] == 'Yes' ? '' : 'disabled'; ?> data-type="modal-trigger" data-target="xs-donate-modal-popup__<?php echo esc_attr( $key ); ?>"> 
							<?php echo esc_html__( isset( $optionsData['enable'] ) && $optionsData['enable'] == 'Yes' ? 'Manage' : 'Setup', 'wp-fundraising' ); ?> 
							</button>
							
							</td>
							
						</tr>
						<?php
						$i++;
endforeach;
					?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="5" align="right"> 
								<button type="submit" name="submit_donate_settings_gateways" class="button button-primary button-large"><?php echo esc_html__( 'Save', 'wp-fundraising' ); ?></button>
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
