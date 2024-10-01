<ul class="xs-donate-option wfp-radio-input-style-2 wfp-bdage-list">

	<?php

	foreach ( $multiData as $mul_level ) {

		$lebelName   = $mul_level->lebel;
		$priceData   = $mul_level->price;
		$default_set = isset( $mul_level->default_set ) ? $mul_level->default_set : 'No';

		if ( $default_set == 'Yes' ) {
			$defaultData = $priceData;
		}

		?>
		<li>
			<input type="radio" id="wfp___<?php echo esc_attr( strtolower( $lebelName ) ); ?>_<?php echo esc_html( $priceData ); ?>_<?php echo esc_html( $post->ID ); ?>" class="xs_radio_filed" onchange="xs_donate_amount_set(<?php echo esc_html( $priceData ); ?>, <?php echo esc_attr( $post->ID ); ?>);" 
													 <?php
														if ( $default_set == 'Yes' ) {
															echo esc_attr( 'checked' ); }
														?>
				 name="xs-dimention-amount" value="<?php echo esc_html( $priceData ); ?>"/>
			<label for="wfp___<?php echo esc_attr( strtolower( $lebelName ) ); ?>_<?php echo esc_html( $priceData ); ?>_<?php echo esc_html( $post->ID ); ?>"><?php echo esc_html( $lebelName ); ?></label>
		</li>

		<?php

	}

	$lebelName = 'Custom';

	if ( ! empty( $fixed_data->enable_custom_amount ) && $fixed_data->enable_custom_amount == 'Yes' ) :
		?>

	<li>
		<input type="radio" id="wfp___<?php echo esc_attr( strtolower( $lebelName ) ); ?>_0_<?php echo esc_html( $post->ID ); ?>" class="xs_radio_filed" onchange="xs_donate_amount_set(0, <?php echo esc_html( $post->ID ); ?>);" name="xs-dimention-amount" value=""/>
		<label for="wfp___<?php echo esc_attr( strtolower( $lebelName ) ); ?>_0_<?php echo esc_attr( $post->ID ); ?>"><?php echo esc_html__( 'Custom', 'wp-fundraising' ); ?></label>
	</li>
		<?php

	endif;
	?>

</ul>
