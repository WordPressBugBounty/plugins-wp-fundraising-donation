<?php 
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

?>
<div class="xs-row">
	
	<div class="xs-col-md-6 intro-info short-info">
		<label for="camapign_post_name">
			<?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_categories', __( 'Campaign Categories', 'wp-fundraising' ) ) ); ?>
		</label>
		<?php
			$wfpCateId     = '';
			$wfp_categories = get_the_terms( $wfp_post_id, 'wfp-categories' );
		if ( is_array( $wfp_categories ) && sizeof( $wfp_categories ) > 0 ) {
			$wfpCateId = isset( $wfp_categories[0]->slug ) ? $wfp_categories[0]->slug : '';
		}

			$wfp_arg        = array(
				'taxonomy'   => 'wfp-categories',
				'parent'     => 0,
				'hide_empty' => false,
			);
			$wfp_categories = get_terms( $wfp_arg );
			if ( ! empty( $wfp_categories ) ) :
				$wfp_output = '<select name="campaign_meta_post[post_category][]" class="wfp-require-filed wfp-input" oninput="wfp_modify_class(this)" required="1" >';
				foreach ( $wfp_categories as $category ) {

					$wfpSubCate = get_terms(
						array(
							'taxonomy'   => 'wfp-categories',
							'parent'     => $category->term_id,
							'hide_empty' => false,
						)
					);
					if ( ! empty( $wfpSubCate ) ) {
						$wfp_output .= '<optgroup label="' . esc_attr( $category->name ) . '">';
						foreach ( $wfpSubCate as $subcategory ) {

							$wfpSelectedSub = ( $subcategory->slug === $wfpCateId ) ? 'selected' : '';

							$wfp_output .= '<option value="' . esc_attr( $subcategory->slug ) . '" ' . $wfpSelectedSub . '>
									' . esc_html( $subcategory->name ) . '</option>';
						}
							$wfp_output .= '</optgroup>';
					} else {
						$wfp_selected = ( $category->slug === $wfpCateId ) ? 'selected' : '';
						$wfp_output  .= '<option value="' . esc_attr( $category->slug ) . '" ' . $wfp_selected . '>
									' . esc_html( $category->name ) . '</option>';
					}
				}
				$wfp_output .= '</select>';
				echo wp_kses( $wfp_output, \WfpFundraising\Utilities\Utils::get_kses_array() );
			endif;
			?>
		
	</div>

	<div class="xs-col-md-6 intro-info short-info">
		<label for="camapign_post_name">
			<?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_tags', __( 'TAGs', 'wp-fundraising' ) ) ); ?>
		</label>
		<?php
		$wfp_tags = get_the_terms( $wfp_post_id, 'wfp-tags' );
		if ( is_array( $wfp_tags ) && sizeof( $wfp_tags ) > 0 ) {
			$wfp_tag = array_map(
				function( $t ) {
					return isset( $t->name ) ? $t->name : '';
				},
				$wfp_tags
			);
		} else {
			$wfp_tag = array();
		}
		$wfpTagName = implode( ',', $wfp_tag );
		?>
		<input type="text" name="campaign_meta_post[post_tags]" placeholder="tag1, tag2, tag3" id="camapign_post_name" class="wfp-input" value="<?php echo esc_attr( $wfpTagName ); ?>" oninput="wfp_modify_class(this)" required="1" >
	</div>

	<div class="xs-col-md-6 intro-info short-info">
		<label for="camapign_post_name">
			<?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_featured_type', __( 'Campaign Featured Type', 'wp-fundraising' ) ) ); ?>
		</label>
		<?php
			$wfpImageData = '';
			$wfpImageThu  = get_post_meta( $wfp_post_id, '_thumbnail_id', true );
			$wfpImageGall = get_post_meta( $wfp_post_id, 'wfp_portfolio_gallery', true );
			$wfp_video     = get_post_meta( $wfp_post_id, 'wfp_featured_video_url', true );

			$wfpImageDataList = strlen( $wfpImageGall ) > 0 ? trim( $wfpImageGall ) : trim( $wfpImageThu );
		if ( strlen( $wfpImageDataList ) > 0 ) {
			$wfpImageData = explode( ',', $wfpImageDataList );
			$wfpImageData = empty( $wfpImageData ) ? array() : $wfpImageData;
		}
		?>
		<select name="campaign_meta_post[attatch_type]" onchange="xs_show_hide_donate_font('.wfp-target-div')" class="wfp-require-filed wfp-input" oninput="wfp_modify_class(this)" required="1" >
			<option value="image"><?php echo esc_html__( 'image', 'wp-fundraising' ); ?></option>
			<option value="video" <?php echo esc_attr( strlen( $wfp_video ) > 5 ? 'selected' : '' ); ?>><?php echo esc_html__( 'Video', 'wp-fundraising' ); ?></option>
		</select>
	</div>


	<div class="xs-col-md-6 intro-info short-info wfp-target-div xs-donate-hidden <?php echo esc_attr( strlen( $wfp_video ) > 5 ? '' : 'xs-donate-visible' ); ?>">
		<label for="camapign_post_image">
			<?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_image', __( 'Campaign Image', 'wp-fundraising' ) ) ); ?>
		</label>
		<div class="image-box xs-text-right">
			
			<div class="image-box--inputs">
				<input type="file" onchange="wfp_choose_image(this)" accept="image/*" name="wfp_files_upload[]" id="camapign_post_image" class="inputfile inputfile-6" multiple="true">
				<label for="file-7"><i class="wfpf wfpf-upload"></i> <?php echo esc_html__( 'Upload', 'wp-fundraising' ); ?></label>
			</div>
			

			<ul id="wfp-file_list">
				<?php
				if ( is_array( $wfpImageData ) && sizeof( $wfpImageData ) > 0 ) {
					$wfp_m = 0;
					foreach ( $wfpImageData as $v ) :
						if ( $v > 0 ) {
							$wfp_src      = wp_get_attachment_image_src( $v );
							if(!is_array($wfp_src)){
								continue;
							}
							$wfpFileName = $wfp_src[0];
							?>
							<li id="wfp-upload-set-id__<?php echo esc_attr( $wfp_m ); ?>" class="preview_image"><span class="wfpf wfpf-close-outline remove-icon" onclick="wfp_reviwe_image(this.parentElement)"></span><img class="imageThumb" src="<?php echo esc_attr( $wfp_src[0] ); ?>" title="<?php echo esc_attr( $wfpFileName ); ?>"><input type="hidden" value="<?php echo esc_attr( $v ); ?>" name="wfp_uploaded_update[]" id="image-set-<?php echo esc_attr( $wfp_m ); ?>"><span class="sizefiles"></span></li>
							<?php
							$wfp_m++;
						}
					endforeach;
				}
				?>
			</ul>
		</div>
		
	</div>

	<div class="xs-col-md-6 intro-info short-info wfp-target-div xs-donate-hidden <?php echo esc_attr( strlen( $wfp_video ) > 5 ? 'xs-donate-visible' : '' ); ?>">
		<label for="camapign_post_video">
			<?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_video', __( 'Campaign Video URL', 'wp-fundraising' ) ) ); ?>
		</label>
		<input type="text" name="campaign_meta_post[wfp_featured_video_url]" id="camapign_post_video" value="<?php echo esc_attr( $wfp_video ); ?>" class="wfp-input" >
	</div>



	<?php
		$wfpDonationLimit = isset( $wfpGetMetaData->donation->set_limit ) ? $wfpGetMetaData->donation->set_limit : array();

		$wfp_min_amount = isset( $wfpDonationLimit->min_amt ) ? $wfpDonationLimit->min_amt : 0;
		$wfp_max_amount = isset( $wfpDonationLimit->max_amt ) ? $wfpDonationLimit->max_amt : 0;

		$wfpFixedData  = isset( $wfpGetMetaData->donation->fixed ) ? $wfpGetMetaData->donation->fixed : array();
		$wfp_recomended = isset( $wfpFixedData->price ) ? $wfpFixedData->price : 0;
	?>


	<div class="xs-col-md-6 intro-info short-info">
		<label for="camapign_post_minimum">
			<?php
			// translators: %s: currency symbol.
			echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_min_amount', sprintf( __( 'Minimum Amount (%s)', 'wp-fundraising' ), esc_html( $wfpSymbols ) ) ) );
			?>
		</label>
		<input type="number" name="campaign_meta_post[set_limit][min_amt]" id="camapign_post_minimum" value="<?php echo esc_attr( $wfp_min_amount ); ?>" class="wfp-input" >
	</div>

	<div class="xs-col-md-6 intro-info short-info">
		<label for="camapign_post_maximum">
			<?php
			// translators: %s: currency symbol.
			echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_max_amount', sprintf( __( 'Maximum Amount (%s)', 'wp-fundraising' ), esc_html( $wfpSymbols ) ) ) );
			?>
		</label>
		<input type="number" name="campaign_meta_post[set_limit][max_amt]" id="camapign_post_maximum" value="<?php echo esc_attr( $wfp_max_amount ); ?>" class="wfp-input" >
	</div>

	<div class="xs-col-md-6 intro-info short-info">
		<label for="camapign_post_recomended">
			<?php
			// translators: %s: currency symbol.
			echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_recomended_amount', sprintf( __( 'Recomended Amount (%s)', 'wp-fundraising' ), esc_html( $wfpSymbols ) ) ) );
			?>
		</label>
		<input type="number" name="campaign_meta_post[donation][fixed][price]" id="camapign_post_recomended" value="<?php echo esc_attr( $wfp_recomended ); ?>"  class="wfp-input" >
	</div>


	<?php
		$wfp_goal_setup   = isset( $wfpGetMetaData->goal_setup ) ? $wfpGetMetaData->goal_setup : array();
		$wfp_goal_type    = isset( $wfp_goal_setup->goal_type ) ? $wfp_goal_setup->goal_type : '';
		$wfpTargetAmount = isset( $wfp_goal_setup->terget->terget_goal->amount ) ? $wfp_goal_setup->terget->terget_goal->amount : 0;

		$wfp_targetdate           = isset( $wfp_goal_setup->terget->terget_goal->date ) ? $wfp_goal_setup->terget->terget_goal->date : 0;
		$wfp_targetgoaldate       = isset( $wfp_goal_setup->terget->terget_goal_date->date ) ? $wfp_goal_setup->terget->terget_goal_date->date : '';
		$wfp_targetdate_amount    = isset( $wfp_goal_setup->terget->terget_goal_date->amount ) ? $wfp_goal_setup->terget->terget_goal_date->amount : 0;
		$wfpTargetAmountCampaing = isset( $wfp_goal_setup->terget->campaign_never->amount ) ? $wfp_goal_setup->terget->campaign_never->amount : 0;
		$wfp_goal_message         = isset( $wfp_goal_setup->terget->message ) ? $wfp_goal_setup->terget->message : '';
	?>


	<div class="xs-col-md-6 intro-info short-info">
		<label for="camapign_post_goal_type">
			<?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_end_method', __( 'End Method', 'wp-fundraising' ) ) ); ?>
		</label>
		<select name="campaign_meta_post[goal_setup][goal_type]" id="camapign_post_goal_type" class="wfp-require-filed wfp-input" >
			<option value="terget_goal" <?php echo esc_attr( ( $wfp_goal_type == 'terget_goal' ) ? 'selected' : '' ); ?> ><?php echo esc_html__( 'Target Goal', 'wp-fundraising' ); ?> </option>
			<option value="terget_date" <?php echo esc_attr( ( $wfp_goal_type == 'terget_date' ) ? 'selected' : '' ); ?> ><?php echo esc_html__( 'Target Date', 'wp-fundraising' ); ?> </option>
			<option value="terget_goal_date" <?php echo esc_attr( ( $wfp_goal_type == 'terget_goal_date' ) ? 'selected' : '' ); ?> ><?php echo esc_html__( 'Target Goal & Date', 'wp-fundraising' ); ?> </option>
			<option value="campaign_never_end" <?php echo esc_attr( ( $wfp_goal_type == 'campaign_never_end' ) ? 'selected' : '' ); ?> ><?php echo esc_html__( 'Campaign Never Ends', 'wp-fundraising' ); ?> </option>
		</select>
		
	</div>


	<div class="xs-col-md-6 intro-info short-info goal_terget_amount_show ">
		<label for="camapign_post_target_raised">
			<?php
			// translators: %s: currency symbol.
			echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_raised_amount', sprintf( __( 'Raised Amount (%s)', 'wp-fundraising' ), esc_html( $wfpSymbols ) ) ) );
			?>
		</label>
		<input type="number" name="campaign_meta_post[goal_setup][terget][terget_goal][amount]" id="camapign_post_target_raised" value="<?php echo esc_attr( $wfpTargetAmount ); ?>" class="wfp-input" >
	</div>

	<div class="xs-col-md-6 intro-info short-info goal_terget_amount_show ">
		<label for="camapign_post_target_donation">
			<?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_target_date', __( 'Target Date', 'wp-fundraising' ) ) ); ?>
		</label>
		<div class="search-tab wfp-no-date-limit">
			<input type="text" name="campaign_meta_post[goal_setup][terget][terget_goal][date]" id="camapign_post_target_donation" value="<?php echo esc_attr( $wfp_targetdate ); ?>" class="wfp-input datepicker-fundrasing" >
		</div>		
	</div>
				

	<div class="xs-col-md-6 intro-info goal_terget_amount_show ">
		<label for="camapign_post_target_date_raised">
			<?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_raised_message', __( 'After Goal Raised Message', 'wp-fundraising' ) ) ); ?>
		</label>
		<textarea name="campaign_meta_post[goal_setup][terget][message]" id="camapign_post_excerpt" class="wfp-input wfp-textarea" ><?php echo wp_kses( $wfp_goal_message, \WfpFundraising\Utilities\Utils::get_kses_array() ); ?></textarea>
	</div>
</div>
