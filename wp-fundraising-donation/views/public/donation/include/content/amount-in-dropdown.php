<div class="xs-dropdown_style_wraper">
	<select name="" class="xs-dropdown_style" onchange="xs_donate_amount_set(this.value, <?php echo esc_html( $post->ID ); ?>);">

		<?php

		foreach ( $multiData as $mul_level ) {

			$labelName   = $mul_level->lebel;
			$priceData   = $mul_level->price;
			$default_set = isset( $mul_level->default_set ) ? $mul_level->default_set : 'No';

			if ( $default_set == 'Yes' ) {
				$defaultData = $priceData;
			}

			?>

			<option value="<?php echo esc_html( $priceData ); ?>" 
									  <?php
										if ( $default_set == 'Yes' ) {
											echo esc_attr( 'selected' );}
										?>
			 > <?php echo esc_html( $labelName ); ?></option>


			<?php

		}

		if ( ! empty( $fixed_data->enable_custom_amount ) && $fixed_data->enable_custom_amount == 'Yes' ) :
			?>

		<option value=""> <?php echo esc_html__( 'Custom', 'wp-fundraising' ); ?></option>
									 <?php

		endif;
		?>

	</select>
</div>
