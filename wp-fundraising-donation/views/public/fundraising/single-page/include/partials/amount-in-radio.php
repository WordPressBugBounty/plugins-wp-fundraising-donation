<?php 
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

?>
<ul class="xs-donate-option wfp-radio-input-style-2 wfp-bdage-list">

	<?php

	foreach ( $wfpMultiData as $mul_level ) {

		$wfpLabelName   = $mul_level->lebel;
		$wfpPriceData   = $mul_level->price;
		$wfp_default_set = isset( $mul_level->default_set ) ? $mul_level->default_set : 'No';

		if ( $wfp_default_set == 'Yes' ) {
			$wfpDefaultData = $wfpPriceData;
		}

		?>
		<li>
			<input type="radio" id="wfp___<?php echo esc_attr( strtolower( $wfpLabelName ) ); ?>_<?php echo esc_html( $wfpPriceData ); ?>_<?php echo esc_attr( $post->ID ); ?>" class="xs_radio_filed" onchange="xs_donate_amount_set(<?php echo esc_html( $wfpPriceData ); ?>, <?php echo esc_attr( $post->ID ); ?>);" 
													 <?php
														if ( $wfp_default_set == 'Yes' ) {
															echo 'checked';}
														?>
				 name="xs-dimention-amount" value="<?php echo esc_html( $wfpPriceData ); ?>"/>
			<label for="wfp___<?php echo esc_attr( strtolower( $wfpLabelName ) ); ?>_<?php echo esc_html( $wfpPriceData ); ?>_<?php echo esc_attr( $post->ID ); ?>"><?php echo esc_html( $wfpLabelName ); ?></label>
		</li>

		<?php

	}

	$wfpLabelName = 'Custom';

	if ( ! empty( $wfp_fixed_data->enable_custom_amount ) && $wfp_fixed_data->enable_custom_amount == 'Yes' ) :
		?>

	<li>
		<input type="radio" id="wfp___<?php echo esc_attr( strtolower( $wfpLabelName ) ); ?>_0_<?php echo esc_attr( $post->ID ); ?>" class="xs_radio_filed" onchange="xs_donate_amount_set(0, <?php echo esc_attr( $post->ID ); ?>);"	name="xs-dimention-amount" value=""/>
		<label for="wfp___<?php echo esc_attr( strtolower( $wfpLabelName ) ); ?>_0_<?php echo esc_attr( $post->ID ); ?>"><?php echo esc_html__( 'Custom', 'wp-fundraising-donation' ); ?></label>
	</li>
		<?php

	endif;
	?>

</ul>
