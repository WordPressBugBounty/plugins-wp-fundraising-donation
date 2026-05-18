<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

?>
<ul class="wfp-bdage-list">

	<?php
	foreach ( $wfpMultiData as $mul_level ) {

		$wfpLebelName   = $mul_level->lebel;
		$wfpPriceData   = $mul_level->price;
		$wfp_default_set = isset( $mul_level->default_set ) ? $mul_level->default_set : 'No';

		if ( $wfp_default_set == 'Yes' ) {
			$wfpDefaultData = $wfpPriceData;
		}
		?>
		<li class="wfp-bdage <?php echo esc_attr( ( $wfp_default_set == 'Yes' ) ? 'donate-active' : '' ); ?>" onclick="xs_donate_amount_set(<?php echo esc_html( $wfpPriceData ); ?>, <?php echo esc_attr( $post->ID ); ?>);" data-value="<?php echo esc_attr( $wfpPriceData ); ?>" ><?php echo esc_html( $wfpLebelName ); ?></li>

		<?php

	}

	if ( ! empty( $wfp_fixed_data->enable_custom_amount ) && $wfp_fixed_data->enable_custom_amount == 'Yes' ) :
		?>
	
	<li class="wfp-bdage" onclick="xs_donate_amount_set(0, <?php echo esc_html( $post->ID ); ?>);" data-value="0" class=""><?php echo esc_html__( 'Custom', 'wp-fundraising' ); ?></li>
																	  <?php
	endif;
	?>
</ul>
