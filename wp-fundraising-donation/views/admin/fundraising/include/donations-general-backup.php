<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

?>
<fieldset class="xs-donate-field-wrap xs-clearfix">
	<span class="xs-donate-field-label"><?php echo esc_html__( 'Campaign Format', 'wp-fundraising-donation' ); ?></span>
	<div class="xs-field-body">
		<ul class="xs-donate-option">
			<li>
				<label>
					<input name="xs_submit_donation_data[donation][format]" onchange="xs_show_hide_donate_multiple('.donation_target_type_filed', '.pledge_setup_target1');" class="xs_radio_filed" value="donation" type="radio" <?php echo ( $wfp_donation_format == 'donation' ) ? 'checked' : ''; ?> > <?php echo esc_html__( 'Single Donation', 'wp-fundraising-donation' ); ?>
				</label>
			</li>
			<li>
				<label>
					<input name="xs_submit_donation_data[donation][format]" value="crowdfunding" type="radio" onchange="xs_show_hide_donate_multiple('.donation_target_type_filed', '.pledge_setup_target');"  class="xs_radio_filed" <?php echo ( $wfp_donation_format == 'crowdfunding' ) ? 'checked' : ''; ?>  > <?php echo esc_html__( 'Crowdfunding', 'wp-fundraising-donation' ); ?>
				</label>
			</li>
		</ul>
		<span class="xs-donetion-field-description"><?php echo esc_html__( 'Set format of Campaign for Donation or Crowdfunding', 'wp-fundraising-donation' ); ?></span>
	</div>
</fieldset>
<?php
$wfp_forms_multiple_price_class = 'xs-donate-visible';
if ( $wfp_donation_format == 'crowdfunding' ) :
	$wfp_forms_multiple_price_class = '';
endif;
?>
<fieldset class="xs-donate-field-wrap xs-clearfix donation_target_type_filed pledge_setup_target1 xs-donate-hidden <?php echo esc_attr( $wfp_forms_multiple_price_class ); ?>">
	<span class="xs-donate-field-label"><?php echo esc_html__( 'Pricing Labels', 'wp-fundraising-donation' ); ?></span>
	<div class="xs-field-body">
		<ul class="xs-donate-option">
			<li>
				<label>
				<input name="xs_submit_donation_data[donation][type]" value="multi-lebel" type="radio"  class="xs_radio_filed" onchange="xs_donate_donation_type(1);"  <?php echo ( $wfp_donation_type == 'multi-lebel' ) ? 'checked' : ''; ?> > <?php echo esc_html__( 'Multi Price', 'wp-fundraising-donation' ); ?>
				</label>
			</li>
			<li>
				<label>
				<input name="xs_submit_donation_data[donation][type]" value="fixed-lebel" type="radio"  class="xs_radio_filed" onchange="xs_donate_donation_type(2);" <?php echo ( $wfp_donation_type == 'fixed-lebel' ) ? 'checked' : ''; ?>  > <?php echo esc_html__( 'Fixed Price', 'wp-fundraising-donation' ); ?>
				</label>
			</li>
		</ul>
		<span class="xs-donetion-field-description"><?php echo esc_html__( 'Do you want this form to have one Campaign price or multiple price (for example, $10, $20, $50)?', 'wp-fundraising-donation' ); ?></span>
		<div class="xs-donate-repeatable-field-section xs-donate-hidden <?php echo ( $wfp_donation_type == 'multi-lebel' ) ? 'xs-donate-visible' : ''; ?> ">
			<div class="xs-donate-repeatable-fields-section-wrapper" >
			<div class="repater_donate_item">
				<?php
				if ( is_array( $wfpMultiData ) && sizeof( $wfpMultiData ) > 0 ) {
					$wfp_m = 0;
					foreach ( $wfpMultiData as $multi ) :
						?>
				<div class="xs-donate-row">
					<div class="xs-repeater-field-wrap xs-column" >
						<div class="xs-donate-row-head xs-move ui-sortable-handle" >
							<h2><span class="level_donate_multi"><?php
						// translators: %s: donation level label.
					echo sprintf( esc_html__( 'Donation Level:  %s', 'wp-fundraising-donation' ), isset( $multi->lebel ) ? esc_html( $multi->lebel ) : '' ); ?></span></h2>
							<div class="xs-header-btn-group">
								<button type="button" class="xs-review-btnRemove xs-remove">x</button>
								<button type="button" class="handlediv button-link xs-donate-toggole-button" aria-expanded="false"><span class="toggle-indicator"></span></button>
							</div>
						</div>
						<div class="xs-row-body xs-donate-hidden xs-donate-visible">
							<div class="xs-donate-field-wrap-group">
								<p class="xs-donate-field-wrap ">
									<label for="xs_donate_<?php echo esc_attr( $wfp_m ); ?>_amount" data-pattern-for="xs_donate_++_amount"> <?php echo esc_html__( 'Amount', 'wp-fundraising-donation' ); ?></label>
									<span class="xs-money-symbol xs-money-symbol-before"><?php echo esc_html( $wfpSymbols ); ?></span>
									<input type="number" step="any" style="" name="xs_submit_donation_data[donation][multi][dimentions][<?php echo esc_attr( $wfp_m ); ?>][price]" data-pattern-name="xs_submit_donation_data[donation][multi][dimentions][++][price]" id="xs_donate_<?php echo esc_attr( $wfp_m ); ?>_amount" data-pattern-id="xs_donate_++_amount" value="<?php echo isset( $multi->price ) ? esc_attr( $multi->price ) : '1'; ?>" placeholder="1.00" class="xs-field xs-money-field xs-text_small">
								</p>
								<p class="xs-donate-field-wrap ">
									<label for="xs_donate_<?php echo esc_attr( $wfp_m ); ?>_lebel_name" data-pattern-for="xs_donate_++_lebel_name"><?php echo esc_html__( 'Label Name', 'wp-fundraising-donation' ); ?></label>
									<input type="text" style="" name="xs_submit_donation_data[donation][multi][dimentions][<?php echo esc_attr( $wfp_m ); ?>][lebel]" data-pattern-name="xs_submit_donation_data[donation][multi][dimentions][++][lebel]" id="xs_donate_<?php echo esc_attr( $wfp_m ); ?>_lebel_name" data-pattern-id="xs_donate_++_lebel_name" onkeyup="xs_modify_lebel_name(this);" value="<?php echo isset( $multi->lebel ) ? esc_attr( $multi->lebel ) : ''; ?>" placeholder="Basic" class="xs-field xs-text-field">
								</p>
							</div>
							<div class="xs-donate-switch-field-wrap">
								<ul class="xs-donate-option">
									<li>
										<label for="set_default_enable__<?php echo esc_attr( $wfp_m ); ?>"><?php echo esc_html__( 'Set Default', 'wp-fundraising-donation' ); ?></label>
										<div class="xs-switch-button_wraper">
											<input class="xs_donate_switch_button xs_donate_set_default" type="radio" <?php echo isset( $multi->default_set ) ? 'checked' : ''; ?> id="set_default_enable__<?php echo esc_attr( $wfp_m ); ?>" data-pattern-id="set_default_enable__++" name="xs_submit_donation_data[donation][multi][dimentions][<?php echo esc_attr( $wfp_m ); ?>][default_set]" data-pattern-name="xs_submit_donation_data[donation][multi][dimentions][++][default_set]" onchange="wdp_set_defult_amount(this)" value="Yes" >
											<label for="set_default_enable__<?php echo esc_attr( $wfp_m ); ?>" data-pattern-for="set_default_enable__++"  class="xs_donate_switch_button_label small xs-round"></label>
										</div>
									</li>
								</ul>
							</div>
						</div>

						</div>
					</div>

						<?php
						$wfp_m++;
					endforeach;
				}
				?>
					<div class="add_button_sections">
						<button type="button" class="xs-review-btnAdd xs-review-add-button"><?php echo esc_html__( 'Add', 'wp-fundraising-donation' ); ?></button>
					</div>
				</div>

			</div>
		</div>
		<div class="xs-donate-fixed-field-section xs-repeater-field-wrap xs-donate-hidden <?php echo ( $wfp_donation_type == 'fixed-lebel' ) ? 'xs-donate-visible' : ''; ?>">
			<div class="xs-donate-field-wrap-group">
				<div class="xs-donate-field-wrap ">
					<label for="xs_donate_fixed_amount" > <?php echo esc_html__( 'Amount', 'wp-fundraising-donation' ); ?></label>
					<span class="xs-money-symbol xs-money-symbol-before"><?php echo esc_html( $wfpSymbols ); ?></span>
					<input type="number" step="any" style="" name="xs_submit_donation_data[donation][fixed][price]" id="xs_donate_fixed_amount" value="<?php echo isset( $wfpFixedData->price ) ? esc_attr( $wfpFixedData->price ) : '1'; ?>" placeholder="1.00" class="xs-field xs-money-field xs-text_small">
				</div>
				<div class="xs-donate-field-wrap ">
					<label for="xs_donate_fixed_lebel_name"><?php echo esc_html__( 'Label Name', 'wp-fundraising-donation' ); ?></label>
					<input type="text" name="xs_submit_donation_data[donation][fixed][lebel]" id="xs_donate_fixed_lebel_name" value="<?php echo isset( $wfpFixedData->lebel ) ? esc_attr( $wfpFixedData->lebel ) : ''; ?>" placeholder="Basic" class="xs-field xs-text-field">
				</div>
			</div>

			<div class="xs-donate-field-wrap ">
				<ul class="xs-donate-option">
					<li>
						<label for="donation_fixed_custom_enable"><?php echo esc_html__( 'Enable Custom', 'wp-fundraising-donation' ); ?></label>
						<div class="xs-switch-button_wraper">
							<input class="xs_donate_switch_button" type="checkbox" <?php echo ( isset( $wfpFixedData->enable_custom_amount ) && $wfpFixedData->enable_custom_amount == 'Yes' ) ? 'checked' : ''; ?> id="donation_fixed_custom_enable" name="xs_submit_donation_data[donation][fixed][enable_custom_amount]" value="Yes" >
							<label for="donation_fixed_custom_enable" class="xs_donate_switch_button_label small xs-round"></label>
						</div>
					</li>
				</ul>
				<span class="xs-donetion-field-description"><?php echo esc_html__( 'Fixed Amount modify', 'wp-fundraising-donation' ); ?></span>
			</div>
		</div>
	</div>
</fieldset>

<fieldset class="xs-donate-field-wrap xs-clearfix xs-donate-repeatable-field-section-display xs-donate-hidden <?php echo ( $wfp_donation_type == 'multi-lebel' ) ? 'xs-donate-visible' : ''; ?>">
	<span class="xs-donate-field-label"><?php echo esc_html__( 'Display Label', 'wp-fundraising-donation' ); ?></span>
	<div class="xs-field-body">
		<ul class="xs-donate-option">
			<li>
				<label>
				<input name="xs_submit_donation_data[donation][display]" value="boxed"  class="xs_radio_filed" type="radio" <?php echo ( $wfpDisplayData == 'boxed' ) ? 'checked' : ''; ?> > <?php echo esc_html__( 'Boxed', 'wp-fundraising-donation' ); ?>
				</label>
			</li>
			<li>
				<label>
				<input name="xs_submit_donation_data[donation][display]" value="radio"  class="xs_radio_filed" type="radio" <?php echo ( $wfpDisplayData == 'radio' ) ? 'checked' : ''; ?>  ><?php echo esc_html__( 'Radio Button', 'wp-fundraising-donation' ); ?>
				</label>
			</li>
			<li>
				<label>
				<input name="xs_submit_donation_data[donation][display]" value="dropdown"  class="xs_radio_filed" type="radio" <?php echo ( $wfpDisplayData == 'dropdown' ) ? 'checked' : ''; ?> > <?php echo esc_html__( 'Dropdown', 'wp-fundraising-donation' ); ?>
				</label>
			</li>
			<span class="xs-donetion-field-description"><?php echo esc_html__( 'Set how the Campaign levels will display on the form.', 'wp-fundraising-donation' ); ?></span>
		</ul>
	</div>
</fieldset>

<?php

$wfpGetLimitGlobalOptions = isset( $getGlobalOptions['limit_setup']['enable'] ) ? $getGlobalOptions['limit_setup']['enable'] : 'No';
if ( ! isset( $getGlobalOptionsGlo['options'] ) ) {
	$wfpGetLimitGlobalOptions = 'Yes';
}
if ( $wfpGetLimitGlobalOptions == 'Yes' ) :
	?>
<fieldset class="xs-donate-field-wrap xs-clearfix">
	<span class="xs-donate-field-label"><?php echo esc_html__( 'Limit Setup', 'wp-fundraising-donation' ); ?></span>
	<div class="xs-field-body">
		<ul class="xs-donate-option xs-mb-2">
			<li>
				<div class="xs-switch-button_wraper">
					<input class="xs_donate_switch_button" type="checkbox" <?php echo ( isset( $wfpDonationLimit->enable ) && $wfpDonationLimit->enable == 'Yes' ) ? 'checked' : ''; ?> id="donation_limit_enable" name="xs_submit_donation_data[donation][set_limit][enable]" onchange="xs_show_hide_donate('.xs-donate-limit-field-section');" value="Yes" >
					<label for="donation_limit_enable" class="xs_donate_switch_button_label small xs-round"></label>
				</div>
			</li>
			<li>
				<label for="donation_limit_enable" class="xs-donetion-field-description"><?php echo esc_html__( 'Set  min, max amount for Campaign.', 'wp-fundraising-donation' ); ?></label>
			</li>
		</ul>
		<div class="xs-donate-limit-field-section xs-repeater-field-wrap xs-donate-hidden <?php echo ( isset( $wfpDonationLimit->enable ) && $wfpDonationLimit->enable == 'Yes' ) ? 'xs-donate-visible' : ''; ?>">
			<div class="xs-donate-field-wrap-group xs-mb-0">
				<div class="xs-donate-field-wrap ">
					<label for="xs_donate_limit_min_amount" > <?php echo esc_html__( 'Min Amount', 'wp-fundraising-donation' ); ?></label>
					<span class="xs-money-symbol xs-money-symbol-before"><?php echo esc_html( $wfpSymbols ); ?></span>
					<input type="number" step="any" style="" name="xs_submit_donation_data[donation][set_limit][min_amt]" id="xs_donate_limit_min_amount" value="<?php echo isset( $wfpDonationLimit->min_amt ) ? esc_attr( $wfpDonationLimit->min_amt ) : '1'; ?>" placeholder="0.00" class="xs-field xs-money-field xs-text_small">
				</div>
				<div class="xs-donate-field-wrap ">
					<label for="xs_donate_limit_max_amount"><?php echo esc_html__( 'Max Amount', 'wp-fundraising-donation' ); ?></label>
					<span class="xs-money-symbol xs-money-symbol-before"><?php echo esc_html( $wfpSymbols ); ?></span>
					<input type="number" step="any" name="xs_submit_donation_data[donation][set_limit][max_amt]" id="xs_donate_limit_max_amount" value="<?php echo isset( $wfpDonationLimit->max_amt ) ? esc_attr( $wfpDonationLimit->max_amt ) : '10'; ?>" placeholder="0.00" class="xs-field xs-money-field xs-text_small">
				</div>
			</div>
			<div class="xs-donate-field-wrap-group xs-mb-0 xs_donate_filed_style_2">
				<div class="xs-donate-field-wrap">
					<label for="xs_donate_limit_details"><?php echo esc_html__( 'Details', 'wp-fundraising-donation' ); ?></label>
					<input type="text" name="xs_submit_donation_data[donation][set_limit][details]" id="xs_donate_limit_details" value="<?php echo isset( $wfpDonationLimit->details ) ? esc_attr( $wfpDonationLimit->details ) : ''; ?>" placeholder="About donation limit" class="xs-field xs-text-field xs_block_input">
				</div>
				<span class="xs-donetion-field-description"><?php echo esc_html__( 'This text will be display in Campaign form.', 'wp-fundraising-donation' ); ?></span>
			</div>
		</div>
	</div>
</fieldset>

	<?php
endif;
?>
<?php
$wfpGetAddiGlobalOptions = isset( $getGlobalOptions['additional_fees']['enable'] ) ? $getGlobalOptions['additional_fees']['enable'] : 'No';
if ( ! isset( $getGlobalOptionsGlo['options'] ) ) {
	$wfpGetAddiGlobalOptions = 'Yes';
}
if ( $wfpGetAddiGlobalOptions == 'Yes' ) :
	?>
<fieldset class="xs-donate-field-wrap xs-clearfix">
	<span class="xs-donate-field-label"><?php echo esc_html__( 'Additional Fees', 'wp-fundraising-donation' ); ?></span>
	<div class="xs-field-body">
		<ul class="xs-donate-option xs-mb-2">
			<li>
				<div class="xs-switch-button_wraper">
					<input class="xs_donate_switch_button" type="checkbox" <?php echo ( isset( $wfpAdd_fees->enable ) && $wfpAdd_fees->enable == 'Yes' ) ? 'checked' : ''; ?> id="set_add_fees_enable" name="xs_submit_donation_data[donation][set_add_fees][enable]" onchange="xs_show_hide_donate('.xs-donate-fees-field-section');" value="Yes" >
					<label for="set_add_fees_enable" class="xs_donate_switch_button_label small xs-round"></label>
				</div>
			</li>
			<li>
				<label for="set_add_fees_enable" class="xs-donetion-field-description"><?php echo esc_html__( 'Set Additional Fees (% or Fixed) of give amount.', 'wp-fundraising-donation' ); ?></label>
			</li>
		</ul>
		<div class="xs-additonal-fees-field-section xs-repeater-field-wrap xs-donate-hidden <?php echo ( isset( $wfpAdd_fees->enable ) && $wfpAdd_fees->enable == 'Yes' ) ? 'xs-donate-visible' : ''; ?>">
			<div class="xs-donate-field-wrap-group xs-mb-0">
				<div class="xs-donate-field-wrap xs-mb-2">
					<label for="xs_donate_add_fees_amount" > <?php echo esc_html__( 'Amount', 'wp-fundraising-donation' ); ?></label>
					<div class="xs_select_input_and_symbol_group">
						<span class="xs-money-symbol xs-money-symbol-before"><?php echo esc_html( $wfpSymbols ); ?></span>
						<input type="number" step="any" style="" min="0" name="xs_submit_donation_data[donation][set_add_fees][fees_amount]" id="xs_donate_add_fees_amount" value="<?php echo isset( $wfpAdd_fees->fees_amount ) ? esc_attr( $wfpAdd_fees->fees_amount ) : '0'; ?>" placeholder="1" class="xs-field xs-money-field xs-text_small">
							<?php
							$wfp_fees_type = isset( $wfpAdd_fees->fees_type ) ? $wfpAdd_fees->fees_type : 'percentage';
							?>
						<select class="wfp-fees-types xs_select_filed" name="xs_submit_donation_data[donation][set_add_fees][fees_type]">
							<option value="percentage" <?php echo ( $wfp_fees_type == 'percentage' ? 'selected' : '' ); ?> > <?php echo esc_html__( 'Percentage', 'wp-fundraising-donation' ); ?> </option>
							<option value="fixed" <?php echo ( $wfp_fees_type == 'fixed' ? 'selected' : '' ); ?> > <?php echo esc_html__( 'Fixed', 'wp-fundraising-donation' ); ?> </option>
						</select>
					</div>
				</div>
			</div>
			<div class="xs-donate-field-wrap-group xs-mb-0">
				<div class="xs-donate-field-wrap xs-mb-0">
					<label for="xs_donate_add_fees_label" > <?php echo esc_html__( 'Label Name', 'wp-fundraising-donation' ); ?></label>
					<input type="text" style="" name="xs_submit_donation_data[donation][set_add_fees][fees_label]" id="xs_donate_add_fees_label" value="<?php echo isset( $wfpAdd_fees->fees_label ) ? esc_attr( $wfpAdd_fees->fees_label ) : 'Additional Fees'; ?>" placeholder="Label here" class="xs-field xs-text-field xs_block_input">
				</div>
			</div>
		</div>
	</div>
</fieldset>
	<?php
endif;
?>
<script type="text/javascript">
/*Reapter data*/

jQuery(document).ready(function(){
	var totalRowCountQuery = jQuery('.xs-donate-row');
	var totalRowCount = Number(totalRowCountQuery.length) - 1;

	jQuery('.repater_donate_item').repeater({
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

	  var removeButton = jQuery('.xs-review-btnRemove');
	  for(var m = 1; m < removeButton.length; m++){
		  removeButton[m].style.display = 'block';
	  }
});
</script>
