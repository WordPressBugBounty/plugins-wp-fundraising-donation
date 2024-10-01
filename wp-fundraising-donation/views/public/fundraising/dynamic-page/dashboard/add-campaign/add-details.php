<div class="xs-row">
	
	<div class="xs-col-md-6 intro-info short-info">
		<label for="camapign_post_name">
			<?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_categories', __( 'Campaign Categories', 'wp-fundraising' ) ) ); ?>
		</label>
		<?php
			$cateId     = '';
			$categories = get_the_terms( $post_id, 'wfp-categories' );
		if ( is_array( $categories ) && sizeof( $categories ) > 0 ) {
			$cateId = isset( $categories[0]->slug ) ? $categories[0]->slug : '';
		}

			$arg        = array(
				'taxonomy'   => 'wfp-categories',
				'parent'     => 0,
				'hide_empty' => false,
			);
			$categories = get_terms( $arg );
			if ( ! empty( $categories ) ) :
				$output = '<select name="campaign_meta_post[post_category][]" class="wfp-require-filed wfp-input" oninput="wfp_modify_class(this)" required="1" >';
				foreach ( $categories as $category ) {

					$subCate = get_terms(
						array(
							'taxonomy'   => 'wfp-categories',
							'parent'     => $category->term_id,
							'hide_empty' => false,
						)
					);
					if ( ! empty( $subCate ) ) {
						$output .= '<optgroup label="' . esc_attr( $category->name ) . '">';
						foreach ( $subCate as $subcategory ) {

							$selectedSub = ( $subcategory->slug === $cateId ) ? 'selected' : '';

							$output .= '<option value="' . esc_attr( $subcategory->slug ) . '" ' . $selectedSub . '>
									' . esc_html( $subcategory->name ) . '</option>';
						}
							$output .= '</optgroup>';
					} else {
						$selected = ( $category->slug === $cateId ) ? 'selected' : '';
						$output  .= '<option value="' . esc_attr( $category->slug ) . '" ' . $selected . '>
									' . esc_html( $category->name ) . '</option>';
					}
				}
				$output .= '</select>';
				echo wp_kses( $output, \WfpFundraising\Utilities\Utils::get_kses_array() );
			endif;
			?>
		
	</div>

	<div class="xs-col-md-6 intro-info short-info">
		<label for="camapign_post_name">
			<?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_tags', __( 'TAGs', 'wp-fundraising' ) ) ); ?>
		</label>
		<?php
		$tags = get_the_terms( $post_id, 'wfp-tags' );
		if ( is_array( $tags ) && sizeof( $tags ) > 0 ) {
			$tag = array_map(
				function( $t ) {
					return isset( $t->name ) ? $t->name : '';
				},
				$tags
			);
		} else {
			$tag = array();
		}
		$tagName = implode( ',', $tag );
		?>
		<input type="text" name="campaign_meta_post[post_tags]" placeholder="tag1, tag2, tag3" id="camapign_post_name" class="wfp-input" value="<?php echo esc_attr( $tagName ); ?>" oninput="wfp_modify_class(this)" required="1" >
	</div>

	<div class="xs-col-md-6 intro-info short-info">
		<label for="camapign_post_name">
			<?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_featured_type', __( 'Campaign Featured Type', 'wp-fundraising' ) ) ); ?>
		</label>
		<?php
			$imageData = '';
			$imageThu  = get_post_meta( $post_id, '_thumbnail_id', true );
			$imageGall = get_post_meta( $post_id, 'wfp_portfolio_gallery', true );
			$video     = get_post_meta( $post_id, 'wfp_featured_video_url', true );

			$imageDataList = strlen( $imageGall ) > 0 ? trim( $imageGall ) : trim( $imageThu );
		if ( strlen( $imageDataList ) > 0 ) {
			$imageData = explode( ',', $imageDataList );
			$imageData = empty( $imageData ) ? array() : $imageData;
		}
		?>
		<select name="campaign_meta_post[attatch_type]" onchange="xs_show_hide_donate_font('.wfp-target-div')" class="wfp-require-filed wfp-input" oninput="wfp_modify_class(this)" required="1" >
			<option value="image"><?php echo esc_html__( 'image', 'wp-fundraising' ); ?></option>
			<option value="video" <?php echo esc_attr( strlen( $video ) > 5 ? 'selected' : '' ); ?>><?php echo esc_html__( 'Video', 'wp-fundraising' ); ?></option>
		</select>
	</div>


	<div class="xs-col-md-6 intro-info short-info wfp-target-div xs-donate-hidden <?php echo esc_attr( strlen( $video ) > 5 ? '' : 'xs-donate-visible' ); ?>">
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
				if ( is_array( $imageData ) && sizeof( $imageData ) > 0 ) {
					$m = 0;
					foreach ( $imageData as $v ) :
						if ( $v > 0 ) {
							$src      = wp_get_attachment_image_src( $v );
							if(!is_array($src)){
								continue;
							}
							$fileName = $src[0];
							?>
							<li id="wfp-upload-set-id__<?php echo esc_attr( $m ); ?>" class="preview_image"><span class="wfpf wfpf-close-outline remove-icon" onclick="wfp_reviwe_image(this.parentElement)"></span><img class="imageThumb" src="<?php echo esc_attr( $src[0] ); ?>" title="<?php echo esc_attr( $fileName ); ?>"><input type="hidden" value="<?php echo esc_attr( $v ); ?>" name="wfp_uploaded_update[]" id="image-set-<?php echo esc_attr( $m ); ?>"><span class="sizefiles"></span></li>
							<?php
							$m++;
						}
					endforeach;
				}
				?>
			</ul>
		</div>
		
	</div>

	<div class="xs-col-md-6 intro-info short-info wfp-target-div xs-donate-hidden <?php echo esc_attr( strlen( $video ) > 5 ? 'xs-donate-visible' : '' ); ?>">
		<label for="camapign_post_video">
			<?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_video', __( 'Campaign Video URL', 'wp-fundraising' ) ) ); ?>
		</label>
		<input type="text" name="campaign_meta_post[wfp_featured_video_url]" id="camapign_post_video" value="<?php echo esc_attr( $video ); ?>" class="wfp-input" >
	</div>



	<?php
		$donationLimit = isset( $getMetaData->donation->set_limit ) ? $getMetaData->donation->set_limit : array();

		$min_amount = isset( $donationLimit->min_amt ) ? $donationLimit->min_amt : 0;
		$max_amount = isset( $donationLimit->max_amt ) ? $donationLimit->max_amt : 0;

		$fixedData  = isset( $getMetaData->donation->fixed ) ? $getMetaData->donation->fixed : array();
		$recomended = isset( $fixedData->price ) ? $fixedData->price : 0;
	?>


	<div class="xs-col-md-6 intro-info short-info">
		<label for="camapign_post_minimum">
			<?php echo esc_attr( apply_filters( 'wfp_dashboard_newcam_campaign_min_amount', __( 'Minimum Amount (' . $symbols . ')', 'wp-fundraising' ) ) ); ?>
		</label>
		<input type="number" name="campaign_meta_post[set_limit][min_amt]" id="camapign_post_minimum" value="<?php echo esc_attr( $min_amount ); ?>" class="wfp-input" >
	</div>

	<div class="xs-col-md-6 intro-info short-info">
		<label for="camapign_post_maximum">
			<?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_max_amount', __( 'Maximum Amount (' . $symbols . ')', 'wp-fundraising' ) ) ); ?>
		</label>
		<input type="number" name="campaign_meta_post[set_limit][max_amt]" id="camapign_post_maximum" value="<?php echo esc_attr( $max_amount ); ?>" class="wfp-input" >
	</div>

	<div class="xs-col-md-6 intro-info short-info">
		<label for="camapign_post_recomended">
			<?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_recomended_amount', __( 'Recomended Amount (' . $symbols . ')', 'wp-fundraising' ) ) ); ?>
		</label>
		<input type="number" name="campaign_meta_post[donation][fixed][price]" id="camapign_post_recomended" value="<?php echo esc_attr( $recomended ); ?>"  class="wfp-input" >
	</div>


	<?php
		$goal_setup   = isset( $getMetaData->goal_setup ) ? $getMetaData->goal_setup : array();
		$goal_type    = isset( $goal_setup->goal_type ) ? $goal_setup->goal_type : '';
		$targetAmount = isset( $goal_setup->terget->terget_goal->amount ) ? $goal_setup->terget->terget_goal->amount : 0;

		$targetdate           = isset( $goal_setup->terget->terget_goal->date ) ? $goal_setup->terget->terget_goal->date : 0;
		$targetgoaldate       = isset( $goal_setup->terget->terget_goal_date->date ) ? $goal_setup->terget->terget_goal_date->date : '';
		$targetdate_amount    = isset( $goal_setup->terget->terget_goal_date->amount ) ? $goal_setup->terget->terget_goal_date->amount : 0;
		$targetAmountCampaing = isset( $goal_setup->terget->campaign_never->amount ) ? $goal_setup->terget->campaign_never->amount : 0;
		$goal_message         = isset( $goal_setup->terget->message ) ? $goal_setup->terget->message : '';
	?>


	<div class="xs-col-md-6 intro-info short-info">
		<label for="camapign_post_goal_type">
			<?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_end_method', __( 'End Method', 'wp-fundraising' ) ) ); ?>
		</label>
		<select name="campaign_meta_post[goal_setup][goal_type]" id="camapign_post_goal_type" class="wfp-require-filed wfp-input" >
			<option value="terget_goal" <?php echo esc_attr( ( $goal_type == 'terget_goal' ) ? 'selected' : '' ); ?> ><?php echo esc_html__( 'Target Goal', 'wp-fundraising' ); ?> </option>
			<option value="terget_date" <?php echo esc_attr( ( $goal_type == 'terget_date' ) ? 'selected' : '' ); ?> ><?php echo esc_html__( 'Target Date', 'wp-fundraising' ); ?> </option>
			<option value="terget_goal_date" <?php echo esc_attr( ( $goal_type == 'terget_goal_date' ) ? 'selected' : '' ); ?> ><?php echo esc_html__( 'Target Goal & Date', 'wp-fundraising' ); ?> </option>
			<option value="campaign_never_end" <?php echo esc_attr( ( $goal_type == 'campaign_never_end' ) ? 'selected' : '' ); ?> ><?php echo esc_html__( 'Campaign Never Ends', 'wp-fundraising' ); ?> </option>
		</select>
		
	</div>


	<div class="xs-col-md-6 intro-info short-info goal_terget_amount_show ">
		<label for="camapign_post_target_raised">
			<?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_raised_amount', __( 'Raised Amount (' . $symbols . ')', 'wp-fundraising' ) ) ); ?>
		</label>
		<input type="number" name="campaign_meta_post[goal_setup][terget][terget_goal][amount]" id="camapign_post_target_raised" value="<?php echo esc_attr( $targetAmount ); ?>" class="wfp-input" >
	</div>

	<div class="xs-col-md-6 intro-info short-info goal_terget_amount_show ">
		<label for="camapign_post_target_donation">
			<?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_target_date', __( 'Target Date', 'wp-fundraising' ) ) ); ?>
		</label>
		<div class="search-tab wfp-no-date-limit">
			<input type="text" name="campaign_meta_post[goal_setup][terget][terget_goal][date]" id="camapign_post_target_donation" value="<?php echo esc_attr( $targetdate ); ?>" class="wfp-input datepicker-fundrasing" >
		</div>		
	</div>
				

	<div class="xs-col-md-6 intro-info goal_terget_amount_show ">
		<label for="camapign_post_target_date_raised">
			<?php echo esc_html( apply_filters( 'wfp_dashboard_newcam_campaign_raised_message', __( 'After Goal Raised Message', 'wp-fundraising' ) ) ); ?>
		</label>
		<textarea name="campaign_meta_post[goal_setup][terget][message]" id="camapign_post_excerpt" class="wfp-input wfp-textarea" ><?php echo wp_kses( $goal_message, \WfpFundraising\Utilities\Utils::get_kses_array() ); ?></textarea>
	</div>
</div>
