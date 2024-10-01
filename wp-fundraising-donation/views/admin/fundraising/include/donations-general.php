<fieldset class="xs-donate-field-wrap">
	<span class="xs-donate-field-label"><?php echo esc_html__( 'Campaign Format', 'wp-fundraising' ); ?></span>
	<div class="xs-field-body">
		<ul class="xs-donate-option">
			<li>
				<label>
					<input name="xs_submit_donation_data[donation][format]"
						   onchange="xs_show_hide_donate_multiple('.donation_target_type_filed', '.pledge_setup_target1');"
						   class="xs_radio_filed"
						   value="donation"
						   type="radio" <?php echo ( $donation_format == 'donation' ) ? 'checked' : ''; ?> > <?php echo esc_html__( 'Single Donation', 'wp-fundraising' ); ?>
				</label>
			</li>
			<li>
				<label>
					<input name="xs_submit_donation_data[donation][format]"
						   value="crowdfunding"
						   type="radio"
						   onchange="xs_show_hide_donate_multiple('.donation_target_type_filed', '.pledge_setup_target');"
						   class="xs_radio_filed" <?php echo ( $donation_format == 'crowdfunding' ) ? 'checked' : ''; ?>  > <?php echo esc_html__( 'Crowdfunding', 'wp-fundraising' ); ?>
				</label>
			</li>
		</ul><!-- .xs-donate-option end -->
		<span class="xs-donetion-field-description"><?php echo esc_html__( 'Set format of Campaign for Donation or Crowdfunding', 'wp-fundraising' ); ?></span>
	</div><!-- .xs-field-body end -->
	<div class="xs-clearfix"></div>
</fieldset><!-- .xs-donate-field-wrap end -->


<?php
$forms_multiple_price_class = 'xs-donate-visible';
if ( $donation_format == 'crowdfunding' ) :
	$forms_multiple_price_class = '';
endif;
?>

<fieldset class="xs-donate-field-wrap donation_target_type_filed pledge_setup_target1 xs-donate-hidden <?php echo esc_attr( $forms_multiple_price_class ); ?>">
	<span class="xs-donate-field-label"><?php echo esc_html__( 'Pricing Labels', 'wp-fundraising' ); ?></span>

	<div class="xs-field-body">
		<ul class="xs-donate-option">
			<li>
				<input name="xs_submit_donation_data[donation][type]" value="multi-lebel" type="radio"  class="xs_radio_filed" onchange="xs_donate_donation_type(1);"  <?php echo ( $donation_type == 'multi-lebel' ) ? 'checked' : ''; ?> id="xs_donate_multi_price">
				<label for="xs_donate_multi_price"><?php echo esc_html__( 'Multi Price', 'wp-fundraising' ); ?></label>
			</li>
			<li>
				<input name="xs_submit_donation_data[donation][type]" value="fixed-lebel" type="radio"  class="xs_radio_filed" onchange="xs_donate_donation_type(2);" <?php echo ( $donation_type == 'fixed-lebel' ) ? 'checked' : ''; ?>  id="xs_dontate_fixed_price">
				<label for="xs_dontate_fixed_price"><?php echo esc_html__( 'Fixed Price', 'wp-fundraising' ); ?></label>
			</li>
		</ul><!-- .xs-donate-option end -->

		<span class="xs-donetion-field-description"><?php echo esc_html__( 'Do you want this form to have one Campaign price or multiple price (for example, $10, $20, $50)?', 'wp-fundraising' ); ?></span>

		<div class="xs-donate-repeatable-field-section xs-donate-hidden <?php echo ( $donation_type == 'multi-lebel' ) ? 'xs-donate-visible' : ''; ?> ">
			<div class="xs-donate-repeatable-fields-section-wrapper" >
			<div class="repater_donate_item ui-sortable" id="wfdp-item-sortable-sub">
				<?php
				if ( is_array( $multiData ) && sizeof( $multiData ) > 0 ) {
					$m = 0;
					foreach ( $multiData as $multi ) :
						?>
				<div class="xs-donate-row">
					<div class="xs-repeater-field-wrap xs-opened xs-column" >
						<div class="xs-donate-row-head xs-move" onclick="xs_show_hide_parents_elements(this)" >
							<h2><span class="level_donate_multi"><?php echo esc_html__( 'Donation Level: ', 'wp-fundraising' ); ?><?php echo isset( $multi->lebel ) ? esc_html( $multi->lebel ) : '' . ' '; ?></span></h2>
							<div class="xs-header-btn-group">
								<button type="button" class="xs-review-btnRemove xs-remove"><i class="wfpf wfpf-close-outline"></i></button>
								<button type="button" class="handlediv button-link xs-donate-toggole-button" aria-expanded="false"><span class="toggle-indicator"></span></button>
							</div>
						</div>
						<div class="xs-row-body xs-donate-hidden xs-donate-visible">
							<div class="xs-donate-field-wrap-group">
								<div class="xs-donate-field-wrap ">
									<label for="xs_donate_<?php echo esc_attr( $m ); ?>_amount" data-pattern-for="xs_donate_++_amount"> <?php echo esc_html__( 'Amount', 'wp-fundraising' ); ?></label>
									<div class="xs-donate-field-wrap-amount">
										<span class="xs-money-symbol xs-money-symbol-before"><?php echo esc_html( $symbols ); ?></span>
										<input type="number" step="any" min="0"  name="xs_submit_donation_data[donation][multi][dimentions][<?php echo esc_attr( $m ); ?>][price]" data-pattern-name="xs_submit_donation_data[donation][multi][dimentions][++][price]" id="xs_donate_<?php echo esc_attr( $m ); ?>_amount" data-pattern-id="xs_donate_++_amount" value="<?php echo isset( $multi->price ) ? esc_attr( $multi->price ) : '1'; ?>" placeholder="1.00" class="xs-field xs-money-field xs-text_small">
									</div>
									
								</div>
								<div class="xs-donate-field-wrap ">
									<label for="xs_donate_<?php echo esc_attr( $m ); ?>_lebel_name" data-pattern-for="xs_donate_++_lebel_name"><?php echo esc_html__( 'Label Name', 'wp-fundraising' ); ?></label>
									<input type="text"  name="xs_submit_donation_data[donation][multi][dimentions][<?php echo esc_attr( $m ); ?>][lebel]" data-pattern-name="xs_submit_donation_data[donation][multi][dimentions][++][lebel]" id="xs_donate_<?php echo esc_attr( $m ); ?>_lebel_name" data-pattern-id="xs_donate_++_lebel_name" onkeyup="xs_modify_lebel_name(this);" value="<?php echo isset( $multi->lebel ) ? esc_attr( $multi->lebel ) : ''; ?>" placeholder="Basic" class="xs-field xs-text-field">
								</div>
							</div>
							<div class="xs-donate-switch-field-wrap">
								<ul class="xs-donate-option">
									<li>
										<label for="set_default_enable__<?php echo esc_attr( $m ); ?>"><?php echo esc_html__( 'Set Default', 'wp-fundraising' ); ?></label>
										<div class="xs-switch-button_wraper">
											<input class="xs_donate_switch_button xs_donate_set_default" type="radio" <?php echo isset( $multi->default_set ) ? 'checked' : ''; ?> id="set_default_enable__<?php echo esc_attr( $m ); ?>" data-pattern-id="set_default_enable__++" name="xs_submit_donation_data[donation][multi][dimentions][<?php echo esc_attr( $m ); ?>][default_set]" data-pattern-name="xs_submit_donation_data[donation][multi][dimentions][++][default_set]" onchange="wdp_set_defult_amount(this)" value="Yes" >
											<label for="set_default_enable__<?php echo esc_attr( $m ); ?>" data-pattern-for="set_default_enable__++"  class="xs_donate_switch_button_label small xs-round"></label>
										</div>
									</li>
								</ul>
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
						<button type="button" class="xs-review-btnAdd xs-review-add-button"><?php echo esc_html__( 'Add', 'wp-fundraising' ); ?></button>
					</div>
				</div>

			</div>
		</div>

		<div class="xs-donate-fixed-field-section xs-repeater-field-wrap xs-donate-hidden <?php echo ( $donation_type == 'fixed-lebel' ) ? 'xs-donate-visible' : ''; ?>">
			<div class="xs-donate-field-wrap-group">
				<div class="xs-donate-field-wrap ">
					<label for="xs_donate_fixed_amount" > <?php echo esc_html__( 'Amount', 'wp-fundraising' ); ?></label>

					<div class="xs-donate-field-wrap-amount">
						<span class="xs-money-symbol xs-money-symbol-before"><?php echo esc_html( $symbols ); ?></span>
						<input type="number" step="any" name="xs_submit_donation_data[donation][fixed][price]" id="xs_donate_fixed_amount" value="<?php echo isset( $fixedData->price ) ? esc_attr( $fixedData->price ) : '1'; ?>" placeholder="1.00" class="xs-field xs-money-field xs-text_small">
					</div>
					
				</div>
				<div class="xs-donate-field-wrap ">
					<label for="xs_donate_fixed_lebel_name"><?php echo esc_html__( 'Label Name', 'wp-fundraising' ); ?></label>
					<input type="text" name="xs_submit_donation_data[donation][fixed][lebel]" id="xs_donate_fixed_lebel_name" value="<?php echo isset( $fixedData->lebel ) ? esc_attr( $fixedData->lebel ) : ''; ?>" placeholder="Basic" class="xs-field xs-text-field">
				</div>
			</div>
		</div>

	</div><!-- .xs-field-body end -->
	<div class="xs-clearfix"></div>
</fieldset><!-- .xs-donate-field-wrap end -->


<fieldset class="xs-donate-field-wrap xs-clearfix">
	<span class="xs-donate-field-label"><?php echo esc_html__( 'Custom amount', 'wp-fundraising' ); ?></span>
	<div class="xs-field-body">
		<ul class="xs-donate-option">
			<li>
				<div class="xs-switch-button_wraper">
					<input class="xs_donate_switch_button" type="checkbox" <?php echo ( isset( $fixedData->enable_custom_amount ) && $fixedData->enable_custom_amount == 'Yes' ) ? 'checked' : ''; ?> id="donation_custom_amount_enable" name="xs_submit_donation_data[donation][fixed][enable_custom_amount]" value="Yes" >
					<label for="donation_custom_amount_enable" class="xs_donate_switch_button_label small xs-round"></label>
				</div>
			</li>
		</ul>
	</div>
</fieldset>


<fieldset class="xs-donate-field-wrap xs-clearfix xs-donate-repeatable-field-section-display xs-donate-hidden <?php echo ( $donation_type == 'multi-lebel' ) ? 'xs-donate-visible' : ''; ?>">
	<span class="xs-donate-field-label"><?php echo esc_html__( 'Display Label', 'wp-fundraising' ); ?></span>
	<div class="xs-field-body">
		<ul class="xs-donate-option">
			<li>
				<label>
				<input name="xs_submit_donation_data[donation][display]" value="boxed"  class="xs_radio_filed" type="radio" <?php echo ( $displayData == 'boxed' ) ? 'checked' : ''; ?> > <?php echo esc_html__( 'Boxed', 'wp-fundraising' ); ?>
				</label>
			</li>
			<li>
				<label>
				<input name="xs_submit_donation_data[donation][display]" value="radio"  class="xs_radio_filed" type="radio" <?php echo ( $displayData == 'radio' ) ? 'checked' : ''; ?>  ><?php echo esc_html__( 'Radio Button', 'wp-fundraising' ); ?>
				</label>
			</li>
			<li>
				<label>
				<input name="xs_submit_donation_data[donation][display]" value="dropdown"  class="xs_radio_filed" type="radio" <?php echo ( $displayData == 'dropdown' ) ? 'checked' : ''; ?> > <?php echo esc_html__( 'Dropdown', 'wp-fundraising' ); ?>
				</label>
			</li>
		</ul>
		<span class="xs-donetion-field-description"><?php echo esc_html__( 'Set how the Campaign levels will display on the form.', 'wp-fundraising' ); ?></span>
	</div>
</fieldset>

<?php

$getLimitGlobalOptions = isset( $getGlobalOptions['limit_setup']['enable'] ) ? $getGlobalOptions['limit_setup']['enable'] : 'No';
if ( ! isset( $getGlobalOptionsGlo['options'] ) ) {
	$getLimitGlobalOptions = 'Yes';
}
if ( $getLimitGlobalOptions == 'Yes' ) :
	?>
<fieldset class="xs-donate-field-wrap xs-clearfix">
	<span class="xs-donate-field-label"><?php echo esc_html__( 'Limit Setup', 'wp-fundraising' ); ?></span>
	<div class="xs-field-body">
		<ul class="xs-donate-option">
			<li>
				<div class="xs-switch-button_wraper">
					<input class="xs_donate_switch_button" type="checkbox" <?php echo ( isset( $donationLimit->enable ) && $donationLimit->enable == 'Yes' ) ? 'checked' : ''; ?> id="donation_limit_enable" name="xs_submit_donation_data[donation][set_limit][enable]" onchange="xs_show_hide_donate('.xs-donate-limit-field-section');" value="Yes" >
					<label for="donation_limit_enable" class="xs_donate_switch_button_label small xs-round"></label>
				</div>
			</li>
			<li>
				<label for="donation_limit_enable" class="xs-donetion-field-description"><?php echo esc_html__( 'Set  min, max amount for Campaign.', 'wp-fundraising' ); ?></label>
			</li>
		</ul>
		<div class="xs-donate-limit-field-section xs-repeater-field-wrap xs-donate-hidden <?php echo ( isset( $donationLimit->enable ) && $donationLimit->enable == 'Yes' ) ? 'xs-donate-visible' : ''; ?>">
			<div class="xs-donate-field-wrap-group xs-donate-field-wrap-inline-group">
				<div class="xs-donate-field-wrap ">
					<label for="xs_donate_limit_min_amount" > <?php echo esc_html__( 'Min Amount', 'wp-fundraising' ); ?></label>

					<div class="xs-donate-field-wrap-amount">
						<span class="xs-money-symbol xs-money-symbol-before"><?php echo esc_html( $symbols ); ?></span>
						<input type="number" step="any" min="0"  name="xs_submit_donation_data[donation][set_limit][min_amt]" id="xs_donate_limit_min_amount" value="<?php echo isset( $donationLimit->min_amt ) ? esc_attr( $donationLimit->min_amt ) : '1'; ?>" placeholder="0.00" class="xs-field xs-money-field xs-text_small">
					</div>
					
				</div>
				<div class="xs-donate-field-wrap ">
					<label for="xs_donate_limit_max_amount"><?php echo esc_html__( 'Max Amount', 'wp-fundraising' ); ?></label>

					<div class="xs-donate-field-wrap-amount">
						<span class="xs-money-symbol xs-money-symbol-before"><?php echo esc_html( $symbols ); ?></span>
						<input type="number" step="any" min="0" name="xs_submit_donation_data[donation][set_limit][max_amt]" id="xs_donate_limit_max_amount" value="<?php echo isset( $donationLimit->max_amt ) ? esc_attr( $donationLimit->max_amt ) : '10'; ?>" placeholder="0.00" class="xs-field xs-money-field xs-text_small">
					</div>
					
				</div>
			</div>
			<div class="xs-donate-field-wrap-group xs-mb-0 xs_donate_filed_style_2">
				<div class="xs-donate-field-wrap xs-donate-field-wrap-with-help-text">
					<label for="xs_donate_limit_details"><?php echo esc_html__( 'Details', 'wp-fundraising' ); ?></label>

					<div class="xs-donate-field-wrap-amount xs-donate-field-wrap-no-symbol">
						<input type="text" name="xs_submit_donation_data[donation][set_limit][details]" id="xs_donate_limit_details" value="<?php echo isset( $donationLimit->details ) ? esc_attr( $donationLimit->details ) : ''; ?>" placeholder="About donation limit" class="xs-field xs-text-field xs_block_input">
						<span class="xs-donetion-field-description"><?php echo esc_html__( 'This text will be display in Campaign form.', 'wp-fundraising' ); ?></span>
					</div>
				</div>
				
			</div>
		</div>
	</div>
</fieldset>

<?php endif; ?>
<?php
	$getAddiGlobalOptions = isset( $getGlobalOptions['additional_fees']['enable'] ) ? $getGlobalOptions['additional_fees']['enable'] : 'No';
if ( ! isset( $getGlobalOptionsGlo['options'] ) ) {
	$getAddiGlobalOptions = 'Yes';
}
if ( $getAddiGlobalOptions == 'Yes' ) :
	?>
<fieldset class="xs-donate-field-wrap xs-clearfix" style="display: none">
	<span class="xs-donate-field-label"><?php echo esc_html__( 'Additional Fees', 'wp-fundraising' ); ?></span>
	<div class="xs-field-body">
		<ul class="xs-donate-option">
			<li>
				<div class="xs-switch-button_wraper">
					<input class="xs_donate_switch_button" type="checkbox" <?php echo ( isset( $add_fees->enable ) && $add_fees->enable == 'Yes' ) ? 'checked' : ''; ?> id="set_add_fees_enable" name="xs_submit_donation_data[donation][set_add_fees][enable]" onchange="xs_show_hide_donate('.xs-additonal-fees-field-section');" value="Yes" >
					<label for="set_add_fees_enable" class="xs_donate_switch_button_label small xs-round"></label>
				</div>
			</li>
			<li>
				<label for="set_add_fees_enable" class="xs-donetion-field-description"><?php echo esc_html__( 'Set Additional Fees (% or Fixed) of give amount.', 'wp-fundraising' ); ?></label>
			</li>
		</ul>
		<div class="xs-additonal-fees-field-section xs-repeater-field-wrap xs-donate-hidden <?php echo ( isset( $add_fees->enable ) && $add_fees->enable == 'Yes' ) ? 'xs-donate-visible' : ''; ?>">
			<div class="xs-donate-field-wrap-group">
				<div class="xs-donate-field-wrap">
					<label for="xs_donate_add_fees_amount" > <?php echo esc_html__( 'Amount', 'wp-fundraising' ); ?></label>
					<div class="xs_select_input_and_symbol_group xs-donate-field-wrap-amount">
						<span class="xs-money-symbol xs-money-symbol-before"><?php echo esc_html( $symbols ); ?></span>
						<input type="number" step="any" min="0" name="xs_submit_donation_data[donation][set_add_fees][fees_amount]" id="xs_donate_add_fees_amount" value="<?php echo isset( $add_fees->fees_amount ) ? esc_attr( $add_fees->fees_amount ) : '0'; ?>" placeholder="1" class="xs-field xs-money-field xs-text_small">
						<?php
						$fees_type = isset( $add_fees->fees_type ) ? $add_fees->fees_type : 'percentage';
						?>
						<select class="wfp-fees-types xs_select_filed" name="xs_submit_donation_data[donation][set_add_fees][fees_type]">
							<option value="percentage" <?php echo ( $fees_type == 'percentage' ? 'selected' : '' ); ?> > <?php echo esc_html__( 'Percentage', 'wp-fundraising' ); ?> </option>
							<option value="fixed" <?php echo ( $fees_type == 'fixed' ? 'selected' : '' ); ?> > <?php echo esc_html__( 'Fixed', 'wp-fundraising' ); ?> </option>
						</select>
					</div>
				</div>
			</div>
			<div class="xs-donate-field-wrap-group">
				<div class="xs-donate-field-wrap xs-mb-0">
					<label for="xs_donate_add_fees_label" > <?php echo esc_html__( 'Label Name', 'wp-fundraising' ); ?></label>
					<input type="text"  name="xs_submit_donation_data[donation][set_add_fees][fees_label]" id="xs_donate_add_fees_label" value="<?php echo isset( $add_fees->fees_label ) ? esc_attr( $add_fees->fees_label ) : 'Additional Fees'; ?>" placeholder="Label here" class="xs-field xs-text-field xs_block_input">
				</div>
			</div>
		</div>
	</div>
</fieldset>
<?php endif; ?>


<?php

$formDesignData = isset( $getMetaData->form_design ) ? $getMetaData->form_design : (object) array(
	'styles'          => 'all_fields',
	'continue_button' => 'Continue',
	'submit_button'   => 'Donate Now',
);

$forms_design_class = 'xs-donate-visible';

if ( $donation_format == 'crowdfunding' ) :
	$forms_design_class = '';
endif;
?>

<fieldset class="xs-donate-field-wrap donation_target_type_filed pledge_setup_target1 xs-donate-hidden <?php echo esc_attr( $forms_design_class ); ?>">
	<span class="xs-donate-field-label"><?php echo esc_html__( 'Form Styles', 'wp-fundraising' ); ?></span>
	<legend class="screen-reader-text"><?php echo esc_html__( 'Form Styles', 'wp-fundraising' ); ?></legend>

	<div class="xs-field-body xs-repeater-field-wrap">
		<ul class="xs-donate-option">
			<li>
				<label>
					<input class="xs_radio_filed" name="xs_submit_donation_data[form_design][styles]" value="all_fields" type="radio" <?php echo ( isset( $formDesignData->styles ) && $formDesignData->styles == 'all_fields' ) ? 'checked' : 'checked'; ?> onchange="xs_show_hide_donate('.xs-donate-forms-styles-continue-fields');" > <?php echo esc_html__( 'All Fields', 'wp-fundraising' ); ?>
				</label>
			</li>
			<li>
				<label>
					<input class="xs_radio_filed" name="xs_submit_donation_data[form_design][styles]" value="only_button" type="radio" <?php echo ( isset( $formDesignData->styles ) && $formDesignData->styles == 'only_button' ) ? 'checked' : ''; ?> onchange="xs_show_hide_donate('.xs-donate-forms-styles-continue-fields');" ><?php echo esc_html__( 'Only Button', 'wp-fundraising' ); ?>
				</label>
			</li>

			<span class="xs-donetion-field-description"><?php echo esc_html__( 'Set donation form style for donation information', 'wp-fundraising' ); ?></span>
		</ul>
		<div class="xs-donate-forms-styles-continue-fields xs-donate-hidden <?php echo ( isset( $formDesignData->styles ) && $formDesignData->styles != 'all_fields' ) ? 'xs-donate-visible' : ''; ?>">
			<div class="xs-donate-field-wrap border-top-1">
				<label for="xs_donate_forms_design_continue_button" > <?php echo esc_html__( 'Continue Text', 'wp-fundraising' ); ?></label>

				<div class="xs-donate-field-wrap-amount xs-donate-field-wrap-no-symbol">
					<input type="text" style="" name="xs_submit_donation_data[form_design][continue_button]" id="xs_donate_forms_design_continue_button" value="<?php echo isset( $formDesignData->continue_button ) ? esc_attr( $formDesignData->continue_button ) : 'Continue'; ?>" placeholder="Continue" class="xs-field xs-text-field">
					<span class="xs-donetion-field-description"><?php echo esc_html__( 'Continue button only apply for design(Only Button, Modal Style)', 'wp-fundraising' ); ?></span>
				</div>

			</div>
		</div>
		<div class="xs-donate-forms-styles-submit-fields">
			<div class="xs-donate-field-wrap border-top-1">
				<label for="xs_donate_forms_design_submit_button" > <?php echo esc_html__( 'Submit Text', 'wp-fundraising' ); ?></label>

				<div class="xs-donate-field-wrap-amount xs-donate-field-wrap-no-symbol">
					<input type="text" style="" name="xs_submit_donation_data[form_design][submit_button]" id="xs_donate_forms_design_submit_button" value="<?php echo isset( $formDesignData->submit_button ) ? esc_attr( $formDesignData->submit_button ) : 'Donate Now'; ?>" placeholder="Donate Now" class="xs-field xs-text-field">
					<span class="xs-donetion-field-description"><?php echo esc_html__( 'Set submit button text.', 'wp-fundraising' ); ?></span>
				</div>

			</div>

			<div class="xs-donate-field-wrap border-top-1">
				<label for="xs_donate_forms_design_submit_button" > <?php echo esc_html__( 'Form show in Modal(Popup)', 'wp-fundraising' ); ?></label>

				<div class="xs-donate-field-wrap-amount">
					<div class="xs-switch-button_wraper">
						<input class="xs_donate_switch_button" type="checkbox" <?php echo ( isset( $formDesignData->modal_show ) && $formDesignData->modal_show == 'Yes' ) ? 'checked' : ''; ?> id="donation_modal_show_enable" name="xs_submit_donation_data[form_design][modal_show]" value="Yes" >
						<label for="donation_modal_show_enable" class="xs_donate_switch_button_label small xs-round"></label>
					</div>
					<span class="xs-donetion-field-description"><?php echo esc_html__( 'Click continue button after show form in popup.', 'wp-fundraising' ); ?></span>
				</div>

			</div>
		</div>
	</div>

</fieldset>



<script type="text/javascript">
/*Reapter data*/

jQuery(document).ready(function($){
	var totalRowCountQuery = $('.xs-donate-row');
	var totalRowCount = Number(totalRowCountQuery.length) - 1;

	$('.repater_donate_item').repeater({
		  btnAddClass: 'xs-review-btnAdd',
		  btnRemoveClass: 'xs-review-btnRemove',
		  groupClass: 'xs-donate-row',
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

	  var removeButton = $('.xs-review-btnRemove');
	  for(var m = 1; m < removeButton.length; m++){
		  removeButton[m].style.display = 'block';
	  }
	  $("#wfdp-item-sortable-sub").sortable();
	  $("#wfdp-item-sortable-sub").disableSelection();
});
</script>
