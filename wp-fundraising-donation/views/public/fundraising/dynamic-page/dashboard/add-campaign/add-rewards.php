<div class="intro-info">
	<label for="camapign_post_enable_rewards">
		<?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_enable_rewards', __( 'Enable Rewards', 'wp-fundraising' ) ) ); ?>
	</label>
	<div class="xs-switch-button_wraper">
		<?php
		$formPledgeData = isset( $getMetaData->pledge_setup ) ? $getMetaData->pledge_setup : (object) array( 'enable' => 'No' );

		?>
		<input class="xs_donate_switch_button" type="checkbox" <?php echo esc_attr( ( isset( $formPledgeData->enable ) && $formPledgeData->enable == 'Yes' ) ? 'checked' : '' ); ?> id="donation_form_pledge_enable" name="campaign_meta_post[pledge_setup][enable]" onchange="xs_show_hide_donate_font('.xs-donate-pledge-content-section');" value="Yes" >
		<label for="donation_form_pledge_enable" class="xs_donate_switch_button_label small xs-round"></label>
	</div>
	<span class="label-info"><?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_enable_rewards_message', __( 'Setup rewards for campaign.', 'wp-fundraising' ) ) ); ?></span>
</div>
<div class="intro-info xs-donate-pledge-content-section xs-donate-hidden xs-donate-hidden <?php echo esc_attr( ( isset( $formPledgeData->enable ) && $formPledgeData->enable == 'Yes' ) ? 'xs-donate-visible' : '' ); ?>">
	<div class="repater_pledge_item ui-sortable fd" id="wfdp-pledge-sortable-sub" >
	<?php

	$multiPleData = isset( $getMetaData->pledge_setup->multi->dimentions ) && sizeof( $getMetaData->pledge_setup->multi->dimentions ) ? $getMetaData->pledge_setup->multi->dimentions : array(
		(object) array(
			'price'       => '1.00',
			'lebel'       => 'Basic',
			'description' => 'Basic Information',
		),
	);

	if ( is_array( $multiPleData ) && sizeof( $multiPleData ) > 0 ) {
		$m = 0;
		foreach ( $multiPleData as $multi ) :
			?>
	<div class="xs-pledge-row">
		<div class="xs-repeater-field-wrap xs-column" >
			<div class="xs-donate-row-head xs-move " onclick="xs_show_hide_parents_elements_dash(this)">
				<h2><span class="level_donate_multi"><?php echo esc_html( isset( $multi->lebel ) ? $multi->lebel : '' . '' ); ?></span></h2>
				<div class="xs-header-btn-group">
					<button type="button" class="xs-pledge-btnRemove xs-remove"><span class="wfpf wfpf-close-outline"></span></button>
					<!-- <button type="button" class="handlediv button-link xs-donate-toggole-button" aria-expanded="false"><span class="toggle-indicator"></span></button> -->
				</div>
			</div>
			<div class="xs-row-body xs-donate-hidden xs-donate-visible">
				<div class="xs-form-group xs-row xs-donate-field-wrap">
					<div class="xs-col-sm-4">
						<label class="xs-col-form-label" for="xs_pledge_<?php echo esc_attr( $m ); ?>_amount" data-pattern-for="xs_pledge_++_amount"> <?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_rewards_amount', __( 'Amount', 'wp-fundraising' ) ) ); ?></label>
					</div>
					<div class="xs-col-sm-8">
						<span class="xs-money-symbol xs-money-symbol-before"><?php echo esc_html( $symbols ); ?></span>
						<input type="number" style="" name="campaign_meta_post[pledge_setup][multi][dimentions][<?php echo esc_attr( $m ); ?>][price]" data-pattern-name="campaign_meta_post[pledge_setup][multi][dimentions][++][price]" id="xs_pledge_<?php echo esc_attr( $m ); ?>_amount" data-pattern-id="xs_pledge_++_amount" value="<?php echo esc_attr( isset( $multi->price ) ? $multi->price : '1' ); ?>" placeholder="1.00" class="xs-field xs-money-field xs-text_small wfp-input xs-money-symbol-before-input">
					</div>
				</div>
				<div class="xs-form-group xs-row xs-donate-field-wrap ">
					<div class="xs-col-sm-4">
						<label  class="xs-col-form-label" for="xs_pledge_<?php echo esc_attr( $m ); ?>_quantity" data-pattern-for="xs_pledge_++_quantity"><?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_rewards_quantity', __( 'Quantity', 'wp-fundraising' ) ) ); ?></label>
					</div>
					
					<div class="xs-col-sm-8">
						<input type="number" style="" name="campaign_meta_post[pledge_setup][multi][dimentions][<?php echo esc_attr( $m ); ?>][quantity]" data-pattern-name="campaign_meta_post[pledge_setup][multi][dimentions][++][quantity]" id="xs_pledge_<?php echo esc_attr( $m ); ?>_quantity" data-pattern-id="xs_pledge_++_quantity" value="<?php echo esc_attr( isset( $multi->quantity ) ? $multi->quantity : '' ); ?>" placeholder="1" class="xs-field xs-money-field xs-text_small wfp-input">
					</div>
					
				</div>
				<div class="xs-form-group xs-row xs-donate-field-wrap ">

					<div class="xs-col-sm-4">
						<label  class="xs-col-form-label" for="xs_pledge_<?php echo esc_attr( $m ); ?>_lebel_name" data-pattern-for="xs_pledge_++_lebel_name"> <?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_rewards_lebel', __( 'Label', 'wp-fundraising' ) ) ); ?></label>
					</div>
					
					<div class="xs-col-sm-8">
						<input type="text" style="" name="campaign_meta_post[pledge_setup][multi][dimentions][<?php echo esc_attr( $m ); ?>][lebel]" data-pattern-name="campaign_meta_post[pledge_setup][multi][dimentions][++][lebel]" id="xs_pledge_<?php echo esc_attr( $m ); ?>_lebel_name" data-pattern-id="xs_pledge_++_lebel_name" onkeyup="xs_modify_lebel_name_dash(this);" value="<?php echo esc_attr( isset( $multi->lebel ) ? $multi->lebel : '' ); ?>" placeholder="<?php esc_html_e( 'Basic', 'wp-fundraising' ); ?>" class="xs-field xs-money-field wfp-input">
					</div>
				</div>

				<div class="xs-form-group xs-row xs-donate-field-wrap ">

					<div class="xs-col-sm-4">
						<label class="xs-col-form-label" for="xs_pledge_<?php echo esc_attr( $m ); ?>_lebel_description" data-pattern-for="xs_pledge_++_lebel_description"><?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_rewards_lebel_description', __( 'Label Description', 'wp-fundraising' ) ) ); ?></label>
					</div>

					<div class="xs-col-sm-8">
						<input type="text" style="" name="campaign_meta_post[pledge_setup][multi][dimentions][<?php echo esc_attr( $m ); ?>][description]" data-pattern-name="campaign_meta_post[pledge_setup][multi][dimentions][++][description]" id="xs_pledge_<?php echo esc_attr( $m ); ?>_lebel_description" data-pattern-id="xs_pledge_++_lebel_description" value="<?php echo esc_attr( isset( $multi->description ) ? $multi->description : '' ); ?>" placeholder="Basic" class="xs-field xs-money-field wfp-input">
					</div>
				</div>

				<div class="xs-donate-field-wrap repater_pledge_item_additional">
					<div class="wfp-section-title"><?php echo esc_html__( 'Additional Data : ', 'wp-fundraising' ); ?></div>
					<div class="xs-form-group xs-row xs-donate-field-wrap padding-left" >

						<div class="xs-col-sm-4">
							<label class="xs-col-form-label" for="xs_pledge_<?php echo esc_attr( $m ); ?>_lebel_includes" data-pattern-for="xs_pledge_++_lebel_includes"><?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_rewards_includes', __( 'Includes', 'wp-fundraising' ) ) ); ?></label>
						</div>
						
						<div class="xs-col-sm-8">
							<input type="text" style="" name="campaign_meta_post[pledge_setup][multi][dimentions][<?php echo esc_attr( $m ); ?>][includes]" data-pattern-name="campaign_meta_post[pledge_setup][multi][dimentions][++][includes]" id="xs_pledge_<?php echo esc_attr( $m ); ?>_lebel_includes" data-pattern-id="xs_pledge_++_lebel_includes" value="<?php echo esc_attr( isset( $multi->includes ) ? $multi->includes : '' ); ?>" placeholder="value 1, value 2" class="xs-field xs-money-field wfp-input">
							<span class="xs-donetion-field-description"><?php echo esc_html( esc_html__( 'Multiple Value Seperate by comma(,)', 'wp-fundraising' ) ); ?></span>
						</div>
						
					</div>
					
					<div class="xs-form-group xs-row xs-donate-field-wrap padding-left" >

						<div class="xs-col-sm-4">
							<label class="xs-col-form-label" for="xs_pledge_<?php echo esc_attr( $m ); ?>_lebel_estimated" data-pattern-for="xs_pledge_++_lebel_estimated"><?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_rewards_estimated_delivery', __( 'Estimated Delivery', 'wp-fundraising' ) ) ); ?></label>
						</div>
						
						<div class="xs-col-sm-8">
							<div class="search-tab wfp-no-date-limit">
								<input type="text" style="" name="campaign_meta_post[pledge_setup][multi][dimentions][<?php echo esc_attr( $m ); ?>][estimated]" data-pattern-name="campaign_meta_post[pledge_setup][multi][dimentions][++][estimated]" id="xs_pledge_<?php echo esc_attr( $m ); ?>_lebel_estimated" data-pattern-id="xs_pledge_++_lebel_estimated" value="<?php echo esc_attr( isset( $multi->estimated ) ? $multi->estimated : '' ); ?>" placeholder="" class="xs-field xs-money-field wfp-input datepicker-fundrasing">
							</div>
						</div>
						
					</div>
					<div class="xs-form-group xs-row xs-donate-field-wrap padding-left" >

						<div class="xs-col-sm-4">
							<label class="xs-col-form-label" for="xs_pledge_<?php echo esc_attr( $m ); ?>_lebel_ships" data-pattern-for="xs_pledge_++_lebel_ships"><?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_rewards_ships_to', __( 'Ships To', 'wp-fundraising' ) ) ); ?></label>
						</div>

						<div class="xs-col-sm-8">
							<input type="text" style="" name="campaign_meta_post[pledge_setup][multi][dimentions][<?php echo esc_attr( $m ); ?>][ships]" data-pattern-name="campaign_meta_post[pledge_setup][multi][dimentions][++][ships]" id="xs_pledge_<?php echo esc_attr( $m ); ?>_lebel_ships" data-pattern-id="xs_pledge_++_lebel_ships" value="<?php echo esc_attr( isset( $multi->ships ) ? $multi->ships : '' ); ?>" placeholder="" class="xs-field xs-money-field wfp-input">
						</div>
						
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
			<button type="button" class="xs-pledge-btnAdd xs-review-add-button"><?php echo esc_html__( 'Add Rewards', 'wp-fundraising' ); ?></button>
		</div>
	</div>
</div>

<script type="text/javascript">
/*Reapter data*/

jQuery(document).ready(function($){
	
	var totalRowCountQuery = $('.xs-pledge-row');

	var totalRowCount = Number(totalRowCountQuery.length) - 1;

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

			event.find(".datepicker-fundrasing").each( function(){
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
	  var removeButton = $('.xs-pledge-btnRemove');
	  for(var m = 1; m < removeButton.length; m++){
		  removeButton[m].style.display = 'block';
	  }
	 

   //sortable script data
	// $( "#wfdp-pledge-sortable-sub" ).sortable();
	// $( "#wfdp-pledge-sortable-sub" ).disableSelection();
});

</script>
