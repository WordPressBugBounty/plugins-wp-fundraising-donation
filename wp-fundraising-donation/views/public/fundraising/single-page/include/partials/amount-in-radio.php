<ul class="xs-donate-option wfp-radio-input-style-2 wfp-bdage-list">

	<?php

	foreach ( $multiData as $mul_level ) {

		$labelName   = $mul_level->lebel;
		$priceData   = $mul_level->price;
		$default_set = isset( $mul_level->default_set ) ? $mul_level->default_set : 'No';

		if ( $default_set == 'Yes' ) {
			$defaultData = $priceData;
		}

		?>
		<li>
			<input type="radio" id="wfp___<?php echo esc_attr( strtolower( $labelName ) ); ?>_<?php echo esc_html( $priceData ); ?>_<?php echo esc_attr( $post->ID ); ?>" class="xs_radio_filed" onchange="xs_donate_amount_set(<?php echo esc_html( $priceData ); ?>, <?php echo esc_attr( $post->ID ); ?>);" 
													 <?php
														if ( $default_set == 'Yes' ) {
															echo 'checked';}
														?>
				 name="xs-dimention-amount" value="<?php echo esc_html( $priceData ); ?>"/>
			<label for="wfp___<?php echo esc_attr( strtolower( $labelName ) ); ?>_<?php echo esc_html( $priceData ); ?>_<?php echo esc_attr( $post->ID ); ?>"><?php echo esc_html( $labelName ); ?></label>
		</li>

		<?php

	}

	$labelName = 'Custom';

	if ( ! empty( $fixed_data->enable_custom_amount ) && $fixed_data->enable_custom_amount == 'Yes' ) :
		?>

	<li>
		<input type="radio" id="wfp___<?php echo esc_attr( strtolower( $labelName ) ); ?>_0_<?php echo esc_attr( $post->ID ); ?>" class="xs_radio_filed" onchange="xs_donate_amount_set(0, <?php echo esc_attr( $post->ID ); ?>);"	name="xs-dimention-amount" value=""/>
		<label for="wfp___<?php echo esc_attr( strtolower( $labelName ) ); ?>_0_<?php echo esc_attr( $post->ID ); ?>"><?php echo esc_html__( 'Custom', 'wp-fundraising' ); ?></label>
	</li>
		<?php

	endif;
	?>

</ul>
