<fieldset class="xs-donate-field-wrap">
	<span class="xs-donate-field-label"><?php echo esc_html__( 'Enable Pledge', 'wp-fundraising' ); ?></span>
	<div class="xs-field-body">
		<ul class="xs-donate-option">
			<li>
				<div class="xs-switch-button_wraper">
					<input class="xs_donate_switch_button" type="checkbox" <?php echo ( isset( $formPledgeData->enable ) && $formPledgeData->enable == 'Yes' ) ? 'checked' : ''; ?> id="donation_form_pledge_enable" name="xs_submit_donation_data[pledge_setup][enable]" onchange="xs_show_hide_donate('.xs-donate-pledge-content-section');" value="Yes" >
					<label for="donation_form_pledge_enable" class="xs_donate_switch_button_label small xs-round"></label>
				</div>
			</li>	
			<li><label for="donation_form_pledge_enable" class="xs-donetion-field-description"><?php echo esc_html__( 'Display Pledge or Rewards.', 'wp-fundraising' ); ?></label></li>
		</ul>
	</div>
</fieldset>
<fieldset class="xs-donate-pledge-content-section xs-donate-field-wrap xs-donate-hidden <?php echo ( isset( $formPledgeData->enable ) && $formPledgeData->enable == 'Yes' ) ? 'xs-donate-visible' : ''; ?>">	
	<span class="xs-donate-field-label"><?php echo esc_html__( 'Pledge Label', 'wp-fundraising' ); ?></span>
	<div class="xs-donate-repeatable-field-section xs-field-body">
		<div class="xs-donate-repeatable-fields-section-wrapper" >
		 <div class="repater_pledge_item ui-sortable" id="wfdp-pledge-sortable-sub" >
			<?php

			if ( is_array( $multiPleData ) && sizeof( $multiPleData ) > 0 ) {
				$m = 0;
				foreach ( $multiPleData as $multi ) :
					?>
			<div class="xs-pledge-row">
				<div class="xs-repeater-field-wrap xs-column xs-opened" >
					<div class="xs-donate-row-head xs-move " onclick="xs_show_hide_parents_elements(this)">
						<h2><span class="level_donate_multi"><?php echo esc_html( 'Donation Level dfsa: ' . isset( $multi->lebel ) ? $multi->lebel : '' . '' ); ?></span></h2>
						<div class="xs-header-btn-group">
							<button type="button" class="xs-pledge-btnRemove xs-remove"><span class="wfpf wfpf-close-outline"></span></button>
							<button type="button" class="handlediv button-link xs-donate-toggole-button" aria-expanded="false"><span class="toggle-indicator"></span></button>
						</div>
					</div>
					<div class="xs-row-body xs-donate-hidden xs-donate-visible">
						<div class="xs-donate-field-wrap ">
							<label for="xs_pledge_<?php echo esc_attr( $m ); ?>_amount" data-pattern-for="xs_pledge_++_amount"> <?php echo esc_html__( 'Amount', 'wp-fundraising' ); ?></label>

							<div class="xs-donate-field-wrap-amount">
								<span class="xs-money-symbol xs-money-symbol-before"><?php echo esc_attr( $symbols ); ?></span>
								<input type="number" style="" name="xs_submit_donation_data[pledge_setup][multi][dimentions][<?php echo esc_attr( $m ); ?>][price]" data-pattern-name="xs_submit_donation_data[pledge_setup][multi][dimentions][++][price]" id="xs_pledge_<?php echo esc_attr( $m ); ?>_amount" data-pattern-id="xs_pledge_++_amount" value="<?php echo isset( $multi->price ) ? esc_attr( $multi->price ) : '1'; ?>" placeholder="1.00" class="xs-field xs-money-field xs-text_small">
							</div>
							
						</div>

						<div class="xs-donate-field-wrap ">
							<label for="xs_pledge_<?php echo esc_attr( $m ); ?>_quantity" data-pattern-for="xs_pledge_++_quantity"> <?php echo esc_html__( 'Quantity', 'wp-fundraising' ); ?></label>
							
							<input type="number" style="" name="xs_submit_donation_data[pledge_setup][multi][dimentions][<?php echo esc_attr( $m ); ?>][quantity]" data-pattern-name="xs_submit_donation_data[pledge_setup][multi][dimentions][++][quantity]" id="xs_pledge_<?php echo esc_attr( $m ); ?>_quantity" data-pattern-id="xs_pledge_++_quantity" value="<?php echo isset( $multi->quantity ) ? esc_attr( $multi->quantity ) : ''; ?>" placeholder="1" class="xs-field xs-money-field xs-text_small">
							
						</div>
						<div class="xs-donate-field-wrap ">
							<label for="xs_pledge_<?php echo esc_attr( $m ); ?>_lebel_name" data-pattern-for="xs_pledge_++_lebel_name"><?php echo esc_html__( 'Label', 'wp-fundraising' ); ?></label>
							<input type="text" style="" name="xs_submit_donation_data[pledge_setup][multi][dimentions][<?php echo esc_attr( $m ); ?>][lebel]" data-pattern-name="xs_submit_donation_data[pledge_setup][multi][dimentions][++][lebel]" id="xs_pledge_<?php echo esc_attr( $m ); ?>_lebel_name" data-pattern-id="xs_pledge_++_lebel_name" onkeyup="xs_modify_lebel_name(this);" value="<?php echo isset( $multi->lebel ) ? esc_attr( $multi->lebel ) : ''; ?>" placeholder="Basic" class="xs-field xs-text-field">
						</div>
						<div class="xs-donate-field-wrap ">
							<label for="xs_pledge_<?php echo esc_attr( $m ); ?>_lebel_description" data-pattern-for="xs_pledge_++_lebel_description"><?php echo esc_html__( 'Label Description', 'wp-fundraising' ); ?></label>
							<input type="text" style="" name="xs_submit_donation_data[pledge_setup][multi][dimentions][<?php echo esc_attr( $m ); ?>][description]" data-pattern-name="xs_submit_donation_data[pledge_setup][multi][dimentions][++][description]" id="xs_pledge_<?php echo esc_attr( $m ); ?>_lebel_description" data-pattern-id="xs_pledge_++_lebel_description" value="<?php echo isset( $multi->description ) ? esc_attr( $multi->description ) : ''; ?>" placeholder="Basic" class="xs-field xs-text-field">
						</div>
						<div class="xs-donate-field-wrap repater_pledge_item_additional">
							<div class="repater_pledge_item_additional--title"><?php echo esc_html__( 'Additional Data : ', 'wp-fundraising' ); ?></div>
							<div class="xs-donate-field-wrap padding-left" >
								<label for="xs_pledge_<?php echo esc_attr( $m ); ?>_lebel_includes" data-pattern-for="xs_pledge_++_lebel_includes"><?php echo esc_html__( 'Includes', 'wp-fundraising' ); ?></label>
								
								<div class="xs-donate-field-wrap-amount xs-donate-field-wrap-no-symbol">
									<input type="text" style="" name="xs_submit_donation_data[pledge_setup][multi][dimentions][<?php echo esc_attr( $m ); ?>][includes]" data-pattern-name="xs_submit_donation_data[pledge_setup][multi][dimentions][++][includes]" id="xs_pledge_<?php echo esc_attr( $m ); ?>_lebel_includes" data-pattern-id="xs_pledge_++_lebel_includes" value="<?php echo isset( $multi->includes ) ? esc_attr( $multi->includes ) : ''; ?>" placeholder="value 1, value 2" class="xs-field xs-text-field">
									<span class="xs-donetion-field-description"><?php echo esc_html__( 'Multiple Value Seperate by comma(,)', 'wp-fundraising' ); ?></span>
								</div>
								
							</div>
							
							<div class="xs-donate-field-wrap padding-left" >
								<label for="xs_pledge_<?php echo esc_attr( $m ); ?>_lebel_estimated" data-pattern-for="xs_pledge_++_lebel_estimated"><?php echo esc_html__( 'Estimated Delivery', 'wp-fundraising' ); ?></label>

								<div class="search-tab wfp-no-date-limit">
									<input type="text" style="" name="xs_submit_donation_data[pledge_setup][multi][dimentions][<?php echo esc_attr( $m ); ?>][estimated]" data-pattern-name="xs_submit_donation_data[pledge_setup][multi][dimentions][++][estimated]" id="xs_pledge_<?php echo esc_attr( $m ); ?>_lebel_estimated" data-pattern-id="xs_pledge_++_lebel_estimated" value="<?php echo isset( $multi->estimated ) ? esc_attr( $multi->estimated ) : ''; ?>" placeholder="" class="xs-field xs-text-field datepicker-donate">
								</div>

								
								
							</div>
							<div class="xs-donate-field-wrap padding-left" >
								<label for="xs_pledge_<?php echo esc_attr( $m ); ?>_lebel_ships" data-pattern-for="xs_pledge_++_lebel_ships"><?php echo esc_html__( 'Ships To', 'wp-fundraising' ); ?></label>
								<input type="text" style="" name="xs_submit_donation_data[pledge_setup][multi][dimentions][<?php echo esc_attr( $m ); ?>][ships]" data-pattern-name="xs_submit_donation_data[pledge_setup][multi][dimentions][++][ships]" id="xs_pledge_<?php echo esc_attr( $m ); ?>_lebel_ships" data-pattern-id="xs_pledge_++_lebel_ships" value="<?php echo isset( $multi->ships ) ? esc_attr( $multi->ships ) : ''; ?>" placeholder="" class="xs-field xs-text-field">
								
							</div>	
						</div>
					</div>
						
					</div>
				</div>
			
					<?php
					$m++;
				endforeach;
			}
			?>
				
				<div class="add_button_sections">
					<button type="button" class="xs-pledge-btnAdd xs-review-add-button"><?php echo esc_html__( 'Add Pledge', 'wp-fundraising' ); ?></button>
				</div>
			</div>
			
		</div>
	</div>
	<div class="xs-clearfix"></div>
</fieldset>

<script type="text/javascript">
/*Reapter data*/
jQuery(document).ready(function($){
	var totalRowCountQuery = document.querySelectorAll('.xs-pledge-row');
	var totalRowCount = Number(totalRowCountQuery.length) - 0;
	
	$('.repater_pledge_item').repeater({
		  btnAddClass: 'xs-pledge-btnAdd',
		  btnRemoveClass: 'xs-pledge-btnRemove',
		  groupClass: 'xs-pledge-row',
		  minItems: 1,
		  maxItems: 0,
		  startingIndex: parseInt(totalRowCount),
		  showMinItemsOnLoad: false,
		  reindexOnDelete: true,
		  repeatMode: 'insertAfterLast',
		  animation: 'fade',
		  animationSpeed: 400,
		  animationEasing: 'swing',
		  clearValues: true,
		  afterAdd: function(event){
			  
				event.find('.datepicker-donate').each( function(){
					var parent = $(this).parent('.search-tab'),
						noDate = $(this).parent('.search-tab').hasClass('wfp-no-date-limit'),
						config = {
							appendTo: parent.get(0),
							dateFormat: 'd-m-Y'
						};

					if(!noDate) {
						config.maxDate = "today";
					}

					$(this).flatpickr(config);

				});

		  }
	  }, [] 
	  );
	  
	  var removeButton = document.querySelectorAll('.xs-pledge-btnRemove');
	  for(var m = 1; m < removeButton.length; m++){
		  removeButton[m].style.display = 'block';
	  }
	  
	  $( "#wfdp-pledge-sortable-sub" ).sortable();
	  $( "#wfdp-pledge-sortable-sub" ).disableSelection();
	
});

</script>
