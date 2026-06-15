<?php

defined( 'ABSPATH' ) || exit;

$wfp_fundraising_m = 0; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: local counter prefixed to `wfp_fundraising_`
foreach ( $arrayPayment as $wfp_fundraising_key => $wfp_fundraising_payment ) : // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: loop variables prefixed to `wfp_fundraising_`
	$wfp_fundraising_optionsDataPop = isset( $gateWaysData['services'][ $wfp_fundraising_key ] ) ? $gateWaysData['services'][ $wfp_fundraising_key ] : array(); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: options data prefixed to `wfp_fundraising_`
	?>
	<div class="wfdp-modal <?php echo esc_attr( $wfp_fundraising_key ); ?>" id="xs-donate-modal-popup__<?php echo esc_attr( $wfp_fundraising_key ); ?>">

		<div class="wfdp-modal-inner">
			<div class="xs-modal-header">
				<h4><?php echo esc_html( $wfp_fundraising_payment['name'] . ' Setup' ); ?></h4>
				<button type="button" class="xs-btn danger xs-btn-close" data-modal-dismiss="modal">X</button>
			</div>
			<div class="xs-modal-body">
			
			<?php
			$wfp_fundraising_info = isset( $wfp_fundraising_payment['setup'] ) ? $wfp_fundraising_payment['setup'] : array(); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: setup array prefixed to `wfp_fundraising_`
			$wfp_fundraising_mm   = 0; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: local row counter prefixed to `wfp_fundraising_`
			foreach ( $wfp_fundraising_info as $wfp_fundraising_keyFiled => $wfp_fundraising_filedData ) : // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: nested loop variables prefixed to `wfp_fundraising_`
				$wfp_fundraising_labelName = ucfirst( str_replace( array( '_', '-' ), ' ', $wfp_fundraising_keyFiled ) ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: label helper prefixed to `wfp_fundraising_`

				$wfp_fundraising_valueData = isset( $wfp_fundraising_optionsDataPop['setup'][ $wfp_fundraising_keyFiled ] ) ? $wfp_fundraising_optionsDataPop['setup'][ $wfp_fundraising_keyFiled ] : ''; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: field value prefixed to `wfp_fundraising_`

				?>
				<div class="payment-gateway-info">
				
					<?php
						$wfp_fundraising_checkboxContainer = $wfp_fundraising_filedData === 'checkbox' ? 'xs-switch-button_wraper' : ''; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: CSS helper prefixed to `wfp_fundraising_`
						$wfp_fundraising_checkboxlabel     = $wfp_fundraising_filedData === 'checkbox' ? 'xs_donate_switch_button_label small xs-round' : ''; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: CSS helper prefixed to `wfp_fundraising_`
					?>

						<div class="<?php echo esc_attr( $wfp_fundraising_checkboxContainer ); ?> popup-right-div">

							<?php if ( $wfp_fundraising_filedData != 'headding' && ! in_array( $wfp_fundraising_keyFiled, array( 'sub_headding', 'sub_headding_cencel' ) ) && $wfp_fundraising_filedData !== 'checkbox' ) { ?>
								<label for=""><?php echo esc_html( $wfp_fundraising_labelName ); ?></label>
						<?php } ?>
						
						<?php
							if ( is_array( $wfp_fundraising_filedData ) && sizeof( $wfp_fundraising_filedData ) > 0 ) {

								if ( is_array( $wfp_fundraising_valueData ) && sizeof( $wfp_fundraising_valueData ) > 0 ) {
									$wfp_fundraising_repaterRow = $wfp_fundraising_valueData; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: repeater rows prefixed to `wfp_fundraising_`
							} else {
									$wfp_fundraising_repaterRow = array( '0' => array_flip( array_keys( $wfp_fundraising_filedData ) ) ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: repeater rows prefixed to `wfp_fundraising_`
							}
							// print_r( $wfp_fundraising_repaterRow );
							?>
							<div class="wfdp-payment-table-container">
								<table class="form-table wfdp-table-design wc_gateways widefat payment-repater">
								<thead>
									<tr>
										<th class="sort">&nbsp;</th>
										<?php
											foreach ( $wfp_fundraising_filedData as $wfp_fundraising_subKeyHead => $wfp_fundraising_sub_valueHead ) : // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: nested header vars prefixed to `wfp_fundraising_`
												$wfp_fundraising_labelNameSub = ucfirst( str_replace( array( '_', '-' ), ' ', $wfp_fundraising_subKeyHead ) ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: nested label prefixed to `wfp_fundraising_`
											?>
											<th class="name"> <?php echo esc_html( $wfp_fundraising_labelNameSub ); ?></th>
											<?php
											endforeach;
										?>
									</tr>
								</thead>
								<tbody id="wfdp-payment-account-sortable-sub" class="wfp-account-payment-repeter">
									
									<?php foreach ( $wfp_fundraising_repaterRow as $wfp_fundraising_row => $wfp_fundraising_rowValue ) { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: nested repeater loop variables already use the plugin prefix ?>
										<tr class="repeter-payment-div">
											<td class="sort"><span class="dashicons dashicons-menu"></span>
												<button type="button" class="xs-payment-btnRemove xs-remove">x</button>
											</td>
										<?php

										foreach ( $wfp_fundraising_filedData as $wfp_fundraising_subKey => $wfp_fundraising_sub_value ) : // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: nested field vars prefixed to `wfp_fundraising_`
											$wfp_fundraising_valueDataSub = isset( $wfp_fundraising_rowValue[ $wfp_fundraising_subKey ] ) ? $wfp_fundraising_rowValue[ $wfp_fundraising_subKey ] : ''; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: nested field value prefixed to `wfp_fundraising_`

											$wfp_fundraising_labelSubKey = ucfirst( str_replace( array( '_', '-' ), ' ', $wfp_fundraising_subKey ) ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: nested label prefixed to `wfp_fundraising_`
											?>
											<td>
											<?php if ( $wfp_fundraising_sub_value == 'input' ) { ?>
													<input type="text" name="xs_submit_settings_data[gateways][services][<?php echo esc_attr( $wfp_fundraising_key ); ?>][setup][<?php echo esc_attr( $wfp_fundraising_keyFiled ); ?>][<?php echo esc_attr( $wfp_fundraising_mm ); ?>][<?php echo esc_attr( $wfp_fundraising_subKey ); ?>]" data-pattern-name="xs_submit_settings_data[gateways][services][<?php echo esc_attr( $wfp_fundraising_key ); ?>][setup][<?php echo esc_attr( $wfp_fundraising_keyFiled ); ?>][++][<?php echo esc_attr( $wfp_fundraising_subKey ); ?>]" value="<?php echo esc_attr( $wfp_fundraising_valueDataSub ); ?>" class="regular-text-normal"/>
												<?php } elseif ( $wfp_fundraising_sub_value == 'textarea' ) { ?>
													<textarea cols="20" rows="3" name="xs_submit_settings_data[gateways][services][<?php echo esc_attr( $wfp_fundraising_key ); ?>][setup][<?php echo esc_attr( $wfp_fundraising_keyFiled ); ?>][<?php echo esc_attr( $wfp_fundraising_mm ); ?>][<?php echo esc_attr( $wfp_fundraising_subKey ); ?>]" data-pattern-name="xs_submit_settings_data[gateways][services][<?php echo esc_attr( $wfp_fundraising_key ); ?>][setup][<?php echo esc_attr( $wfp_fundraising_keyFiled ); ?>][++][<?php echo esc_attr( $wfp_fundraising_subKey ); ?>]" class="regular-text-normal"><?php echo esc_attr( $wfp_fundraising_valueDataSub ); ?></textarea>
												<?php } elseif ( $wfp_fundraising_sub_value == 'headding' ) { ?>
														<h3> <?php echo esc_html( $wfp_fundraising_labelName ); ?> </h3>
												<?php } elseif ( $wfp_fundraising_sub_value == 'checkbox' ) { ?>
													<input type="checkbox" name="xs_submit_settings_data[gateways][services][<?php echo esc_attr( $wfp_fundraising_key ); ?>][setup][<?php echo esc_attr( $wfp_fundraising_keyFiled ); ?>][<?php echo esc_attr( $wfp_fundraising_mm ); ?>][<?php echo esc_attr( $wfp_fundraising_subKey ); ?>]" data-pattern-name="xs_submit_settings_data[gateways][services][<?php echo esc_attr( $wfp_fundraising_key ); ?>][setup][<?php echo esc_attr( $wfp_fundraising_keyFiled ); ?>][++][<?php echo esc_attr( $wfp_fundraising_subKey ); ?>]" <?php echo isset( $wfp_fundraising_valueData ) && $wfp_fundraising_valueData == 'Yes' ? 'checked' : ''; ?> value="Yes" class="xs_donate_switch_button"/>
											<?php } ?>
											</td>
											<?php

											endforeach;
										?>
										</tr>
									<?php $wfp_fundraising_mm++; } ?>
									
									<tr class="add-button">
										<td colspan="<?php echo count( $wfp_fundraising_filedData ) + 1; ?>">
											<button type="button" class="xs-payment-btnAdd"><?php echo esc_html__( '+ Add', 'wp-fundraising-donation' ); ?></button>
										</td>
									</tr>
								</tbody>	
								</table>
							</div>
							
							<?php

						} else {
							?>
							<?php if ( $wfp_fundraising_filedData == 'input' ) { ?>
									<input type="text" name="xs_submit_settings_data[gateways][services][<?php echo esc_attr( $wfp_fundraising_key ); ?>][setup][<?php echo esc_attr( $wfp_fundraising_keyFiled ); ?>]" value="<?php echo esc_attr( $wfp_fundraising_valueData ); ?>" class="regular-text"/>
								<?php } elseif ( $wfp_fundraising_filedData == 'textarea' ) { ?>
									<textarea cols="20" rows="3" name="xs_submit_settings_data[gateways][services][<?php echo esc_attr( $wfp_fundraising_key ); ?>][setup][<?php echo esc_attr( $wfp_fundraising_keyFiled ); ?>]" class="regular-text"><?php echo esc_attr( $wfp_fundraising_valueData ); ?></textarea>
							<?php } elseif ( $wfp_fundraising_filedData == 'headding' ) { ?>
									<h3> <?php echo esc_html( $wfp_fundraising_labelName ); ?> </h3>
							<?php } elseif ( in_array( $wfp_fundraising_keyFiled, array( 'sub_headding', 'sub_headding_cencel' ) ) ) { ?>
									<p style="margin: 0em 0;"> <?php echo esc_html( $wfp_fundraising_filedData ); ?> </p>
							<?php } elseif ( $wfp_fundraising_filedData == 'checkbox' ) { ?>
									<input type="checkbox" name="xs_submit_settings_data[gateways][services][<?php echo esc_attr( $wfp_fundraising_key ); ?>][setup][<?php echo esc_attr( $wfp_fundraising_keyFiled ); ?>]" <?php echo isset( $wfp_fundraising_valueData ) && $wfp_fundraising_valueData == 'Yes' ? 'checked' : ''; ?> value="Yes" class="xs_donate_switch_button"/>
							<?php } elseif ( $wfp_fundraising_filedData == 'dropdown' && ( $wfp_fundraising_keyFiled == 'success_page' or $wfp_fundraising_keyFiled == 'cancel_page' ) ) { ?>
									<select name="xs_submit_settings_data[gateways][services][<?php echo esc_attr( $wfp_fundraising_key ); ?>][setup][<?php echo esc_attr( $wfp_fundraising_keyFiled ); ?>]"> 
										<option value="">
										<?php echo esc_attr( __( 'Select page', 'wp-fundraising-donation' ) ); ?></option> 
										<?php
										$pages = get_pages();
										foreach ( $pages as $page ) {
											$wfp_fundraising_selected = ''; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: selected state already uses the plugin prefix
											if ( $wfp_fundraising_valueData == get_page_link( $page->ID ) ) {
												$wfp_fundraising_selected = 'selected'; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: selected state already uses the plugin prefix
											}
											$wfp_fundraising_option  = '<option ' . $wfp_fundraising_selected . ' value="' . get_page_link( $page->ID ) . '">'; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: option markup prefixed to `wfp_fundraising_`
											$wfp_fundraising_option .= $page->post_title; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: option markup prefixed to `wfp_fundraising_`
											$wfp_fundraising_option .= '</option>'; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: option markup prefixed to `wfp_fundraising_`
											echo wp_kses( $wfp_fundraising_option, \WfpFundraising\Utilities\Utils::get_kses_array() );
										}
										?>
									</select>
							<?php } ?>
						<?php } ?>


						
						<!-- For Checkbox label -->
						<?php if ( $wfp_fundraising_filedData === 'checkbox' ) : ?>
							<label class="<?php echo esc_attr( $wfp_fundraising_checkboxlabel ); ?>" for=""><?php echo $wfp_fundraising_filedData !== 'checkbox' ? esc_html( $wfp_fundraising_labelName ) : ''; ?></label>
							<span class="payment-gateway-label"><?php echo esc_html( $wfp_fundraising_labelName ); ?></span>
						<?php endif; ?>

					</div>
				</div>
				<?php

			endforeach;
			?>
			</div>
			<div class="xs-modal-footer">
				<button type="submit" name="submit_donate_settings_gateways" class="xs-btn btn-special submit-bt wfdp-btn"><?php echo esc_html__( 'Save', 'wp-fundraising-donation' ); ?></button>		
			</div>
		</div>
		
	</div>
	<div class="xs-backdrop"></div>
	<?php
			$wfp_fundraising_m++; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Reason: local counter prefixed to `wfp_fundraising_`
endforeach;
?>

<script type="text/javascript">
/*Reapter data*/

jQuery(document).ready(function($){
	var totalRowCountQuery = $('tr.repeter-payment-div');
	var totalRowCount = Number(totalRowCountQuery.length) - 1;

	$('.wfp-account-payment-repeter').repeater({
		  btnAddClass: 'xs-payment-btnAdd',
		  btnRemoveClass: 'xs-payment-btnRemove',
		  groupClass: 'repeter-payment-div',
		  minItems: 1,
		  maxItems: 0,
		  startingIndex: parseInt(totalRowCount),
		  showMinItemsOnLoad: false,
		  reindexOnDelete: true,
		  repeatMode: 'insertAfterLast',
		  animation: 'fade',
		  animationSpeed: 400,
		  animationEasing: 'swing',
		  clearValues: true
	  }, [] 
	  );
	
	  var removeButton = $('.xs-payment-btnRemove');
	  for(var m = 1; m < removeButton.length; m++){
		  removeButton[m].style.display = 'block';
	  }
	  
	  $("#wfdp-payment-account-sortable-sub").sortable();
	  $("#wfdp-payment-account-sortable-sub").disableSelection();
	
});

</script>


 
