<ul class="wfp-bdage-list">

	<?php
	foreach ( $multiData as $mul_level ) {

		$lebelName   = $mul_level->lebel;
		$priceData   = $mul_level->price;
		$default_set = isset( $mul_level->default_set ) ? $mul_level->default_set : 'No';

		if ( $default_set == 'Yes' ) {
			$defaultData = $priceData;
		}
		?>
		<li class="wfp-bdage <?php echo esc_attr( ( $default_set == 'Yes' ) ? 'donate-active' : '' ); ?>" onclick="xs_donate_amount_set(<?php echo esc_html( $priceData ); ?>, <?php echo esc_attr( $post->ID ); ?>);" data-value="<?php echo esc_attr( $priceData ); ?>" ><?php echo esc_html( $lebelName ); ?></li>

		<?php

	}

	if ( ! empty( $fixed_data->enable_custom_amount ) && $fixed_data->enable_custom_amount == 'Yes' ) :
		?>
	
	<li class="wfp-bdage" onclick="xs_donate_amount_set(0, <?php echo esc_html( $post->ID ); ?>);" data-value="0" class=""><?php echo esc_html__( 'Custom', 'wp-fundraising' ); ?></li>
																	  <?php
	endif;
	?>
</ul>
