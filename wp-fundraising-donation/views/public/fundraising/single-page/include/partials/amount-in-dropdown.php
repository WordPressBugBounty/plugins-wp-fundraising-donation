<?php 
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

?>
<div class="xs-dropdown_style_wraper">
	<select name="" class="xs-dropdown_style" onchange="xs_donate_amount_set(this.value, <?php echo esc_attr( $wfpPostId ); ?>);">

		<?php

		foreach ( $wfpMultiData as $mul_level ) {

			$wfpLabelName   = $mul_level->lebel;
			$wfpPriceData   = $mul_level->price;
			$wfp_default_set = isset( $mul_level->default_set ) ? $mul_level->default_set : 'No';

			if ( $wfp_default_set == 'Yes' ) {
				$wfpDefaultData = $wfpPriceData;
			}

			?>

			<option value="<?php echo esc_html( $wfpPriceData ); ?>" 
									  <?php
										if ( $wfp_default_set == 'Yes' ) {
											echo 'selected';}
										?>
			 > <?php echo esc_html( $wfpLabelName ); ?></option>


			<?php

		}

		if ( ! empty( $wfp_fixed_data->enable_custom_amount ) && $wfp_fixed_data->enable_custom_amount == 'Yes' ) :
			?>

		<option value=""> <?php echo esc_html__( 'Custom', 'wp-fundraising' ); ?></option>
									 <?php

		endif;
		?>

	</select>
</div>
