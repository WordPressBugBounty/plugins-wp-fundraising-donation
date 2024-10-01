<ul class="wfp-bdage-list">

	<?php

	foreach ( $multiData as $mul_level ) {

		$labelName   = $mul_level->lebel;
		$priceData   = $mul_level->price;
		$default_set = isset( $mul_level->default_set ) ? $mul_level->default_set : 'No';

		if ( $default_set == 'Yes' ) {
			$defaultData = $priceData;
		}

		?>
		<li class="wfp-bdage" onclick="xs_donate_amount_set(<?php echo esc_html( $priceData ); ?>, <?php echo esc_attr( $postId ); ?>);" data-value="<?php echo esc_html( $priceData ); ?>" class="<?php echo ( $default_set == 'Yes' ) ? 'donate-active' : ''; ?>"><?php echo esc_html( $labelName ); ?></li>

		<?php

	}

	if ( ! empty( $fixed_data->enable_custom_amount ) && $fixed_data->enable_custom_amount == 'Yes' ) :
		?>
	
	<li class="wfp-bdage" onclick="xs_donate_amount_set(0, <?php echo esc_attr( $postId ); ?>);" data-value="0" class=""><?php echo esc_html__( 'Custom', 'wp-fundraising' ); ?></li>
																	  <?php
	endif;
	?>
</ul>
