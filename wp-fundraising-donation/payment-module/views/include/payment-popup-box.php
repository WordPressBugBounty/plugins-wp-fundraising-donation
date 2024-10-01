<?php
$m = 0;
foreach ( $arrayPayment as $key => $payment ) :
	$optionsDataPop = isset( $gateWaysData['services'][ $key ] ) ? $gateWaysData['services'][ $key ] : array();
	?>
	<div class="wfdp-modal <?php echo esc_attr( $key ); ?>" id="xs-donate-modal-popup__<?php echo esc_attr( $key ); ?>">

		<div class="wfdp-modal-inner">
			<div class="xs-modal-header">
				<h4><?php echo esc_html( $payment['name'] . ' Setup' ); ?></h4>
				<button type="button" class="xs-btn danger xs-btn-close" data-modal-dismiss="modal">X</button>
			</div>
			<div class="xs-modal-body">
			
			<?php
			$info = isset( $payment['setup'] ) ? $payment['setup'] : array();
			$mm   = 0;
			foreach ( $info as $keyFiled => $filedData ) :
				$labelName = ucfirst( str_replace( array( '_', '-' ), ' ', $keyFiled ) );

				$valueData = isset( $optionsDataPop['setup'][ $keyFiled ] ) ? $optionsDataPop['setup'][ $keyFiled ] : '';

				?>
				<div class="payment-gateway-info">
				
					<?php
						$checkboxContainer = $filedData === 'checkbox' ? 'xs-switch-button_wraper' : '';
						$checkboxlabel     = $filedData === 'checkbox' ? 'xs_donate_switch_button_label small xs-round' : '';
					?>

					<div class="<?php echo esc_attr( $checkboxContainer ); ?> popup-right-div">

						<?php if ( $filedData != 'headding' && ! in_array( $keyFiled, array( 'sub_headding', 'sub_headding_cencel' ) ) && $filedData !== 'checkbox' ) { ?>
							<label for=""><?php echo esc_html( $labelName ); ?></label>
						<?php } ?>
						
						<?php
						if ( is_array( $filedData ) && sizeof( $filedData ) > 0 ) {

							if ( is_array( $valueData ) && sizeof( $valueData ) > 0 ) {
								$repaterRow = $valueData;
							} else {
								$repaterRow = array( '0' => array_flip( array_keys( $filedData ) ) );
							}
							// print_r($repaterRow);
							?>
							<div class="wfdp-payment-table-container">
								<table class="form-table wfdp-table-design wc_gateways widefat payment-repater">
								<thead>
									<tr>
										<th class="sort">&nbsp;</th>
										<?php
										foreach ( $filedData as $subKeyHead => $sub_valueHead ) :
											$labelNameSub = ucfirst( str_replace( array( '_', '-' ), ' ', $subKeyHead ) );
											?>
										<th class="name"> <?php echo esc_html( $labelNameSub ); ?></th>
											<?php
											endforeach;
										?>
									</tr>
								</thead>
								<tbody id="wfdp-payment-account-sortable-sub" class="wfp-account-payment-repeter">
									
									<?php foreach ( $repaterRow as $row => $rowValue ) { ?>
										<tr class="repeter-payment-div">
											<td class="sort"><span class="dashicons dashicons-menu"></span>
												<button type="button" class="xs-payment-btnRemove xs-remove">x</button>
											</td>
										<?php

										foreach ( $filedData as $subKey => $sub_value ) :
											$valueDataSub = isset( $rowValue[ $subKey ] ) ? $rowValue[ $subKey ] : '';

											$labelSubKey = ucfirst( str_replace( array( '_', '-' ), ' ', $subKey ) );
											?>
											<td>
											<?php if ( $sub_value == 'input' ) { ?>
													<input type="text" name="xs_submit_settings_data[gateways][services][<?php echo esc_attr( $key ); ?>][setup][<?php echo esc_attr( $keyFiled ); ?>][<?php echo esc_attr( $mm ); ?>][<?php echo esc_attr( $subKey ); ?>]" data-pattern-name="xs_submit_settings_data[gateways][services][<?php echo esc_attr( $key ); ?>][setup][<?php echo esc_attr( $keyFiled ); ?>][++][<?php echo esc_attr( $subKey ); ?>]" value="<?php echo esc_attr( $valueDataSub ); ?>" class="regular-text-normal"/>
												<?php } elseif ( $sub_value == 'textarea' ) { ?>
													<textarea cols="20" rows="3" name="xs_submit_settings_data[gateways][services][<?php echo esc_attr( $key ); ?>][setup][<?php echo esc_attr( $keyFiled ); ?>][<?php echo esc_attr( $mm ); ?>][<?php echo esc_attr( $subKey ); ?>]" data-pattern-name="xs_submit_settings_data[gateways][services][<?php echo esc_attr( $key ); ?>][setup][<?php echo esc_attr( $keyFiled ); ?>][++][<?php echo esc_attr( $subKey ); ?>]" class="regular-text-normal"><?php echo esc_attr( $valueDataSub ); ?></textarea>
												<?php } elseif ( $sub_value == 'headding' ) { ?>
														<h3> <?php echo esc_html( $labelName ); ?> </h3>
												<?php } elseif ( $sub_value == 'checkbox' ) { ?>
													<input type="checkbox" name="xs_submit_settings_data[gateways][services][<?php echo esc_attr( $key ); ?>][setup][<?php echo esc_attr( $keyFiled ); ?>][<?php echo esc_attr( $mm ); ?>][<?php echo esc_attr( $subKey ); ?>]" data-pattern-name="xs_submit_settings_data[gateways][services][<?php echo esc_attr( $key ); ?>][setup][<?php echo esc_attr( $keyFiled ); ?>][++][<?php echo esc_attr( $subKey ); ?>]" <?php echo isset( $valueData ) && $valueData == 'Yes' ? 'checked' : ''; ?> value="Yes" class="xs_donate_switch_button"/>
											<?php } ?>
											</td>
											<?php

											endforeach;
										?>
										</tr>
									<?php $mm++;} ?>
									
									<tr class="add-button">
										<td colspan="<?php echo count( $filedData ) + 1; ?>">
											<button type="button" class="xs-payment-btnAdd"><?php echo esc_html__( '+ Add', 'wp-fundraising' ); ?></button>
										</td>
									</tr>
								</tbody>	
								</table>
							</div>
							
							<?php

						} else {
							?>
							<?php if ( $filedData == 'input' ) { ?>
									<input type="text" name="xs_submit_settings_data[gateways][services][<?php echo esc_attr( $key ); ?>][setup][<?php echo esc_attr( $keyFiled ); ?>]" value="<?php echo esc_attr( $valueData ); ?>" class="regular-text"/>
								<?php } elseif ( $filedData == 'textarea' ) { ?>
									<textarea cols="20" rows="3" name="xs_submit_settings_data[gateways][services][<?php echo esc_attr( $key ); ?>][setup][<?php echo esc_attr( $keyFiled ); ?>]" class="regular-text"><?php echo esc_attr( $valueData ); ?></textarea>
							<?php } elseif ( $filedData == 'headding' ) { ?>
									<h3> <?php echo esc_html( $labelName ); ?> </h3>
							<?php } elseif ( in_array( $keyFiled, array( 'sub_headding', 'sub_headding_cencel' ) ) ) { ?>
									<p style="margin: 0em 0;"> <?php echo esc_html( $filedData ); ?> </p>
							<?php } elseif ( $filedData == 'checkbox' ) { ?>
									<input type="checkbox" name="xs_submit_settings_data[gateways][services][<?php echo esc_attr( $key ); ?>][setup][<?php echo esc_attr( $keyFiled ); ?>]" <?php echo isset( $valueData ) && $valueData == 'Yes' ? 'checked' : ''; ?> value="Yes" class="xs_donate_switch_button"/>
							<?php } elseif ( $filedData == 'dropdown' && ( $keyFiled == 'success_page' or $keyFiled == 'cancel_page' ) ) { ?>
									<select name="xs_submit_settings_data[gateways][services][<?php echo esc_attr( $key ); ?>][setup][<?php echo esc_attr( $keyFiled ); ?>]"> 
										<option value="">
										<?php echo esc_attr( __( 'Select page', 'wp-fundraising' ) ); ?></option> 
										<?php
										$pages = get_pages();
										foreach ( $pages as $page ) {
											$selected = '';
											if ( $valueData == get_page_link( $page->ID ) ) {
												$selected = 'selected';
											}
											$option  = '<option ' . $selected . ' value="' . get_page_link( $page->ID ) . '">';
											$option .= $page->post_title;
											$option .= '</option>';
											echo wp_kses( $option, \WfpFundraising\Utilities\Utils::get_kses_array() );
										}
										?>
									</select>
							<?php } ?>
						<?php } ?>


						
						<!-- For Checkbox label -->
						<?php if ( $filedData === 'checkbox' ) : ?>
							<label class="<?php echo esc_attr( $checkboxlabel ); ?>" for=""><?php echo $filedData !== 'checkbox' ? esc_html( $labelName ) : ''; ?></label>
							<span class="payment-gateway-label"><?php echo esc_html( $labelName ); ?></span>
						<?php endif; ?>

					</div>
				</div>
				<?php

			endforeach;
			?>
			</div>
			<div class="xs-modal-footer">
				<button type="submit" name="submit_donate_settings_gateways" class="xs-btn btn-special submit-bt wfdp-btn"><?php echo esc_html__( 'Save', 'wp-fundraising' ); ?></button>		
			</div>
		</div>
		
	</div>
	<div class="xs-backdrop"></div>
	<?php
	$m++;
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


 
