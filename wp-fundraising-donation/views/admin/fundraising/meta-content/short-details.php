<div class="wdp-form-information xs_shadow_card">
	<div class="xs-fundrising-title-wraper">
		<h3 class="xs-fundrising-title"><?php esc_html_e( 'Payment Details', 'wp-fundraising' ); ?>  </h3>
		<hr>
	</div>

	<?php

	$r_url  = admin_url() . 'edit.php?post_type=' . \WfpFundraising\Apps\Fundraising_Cpt::TYPE . '';
	$r_url .= '&page=donations';
	$r_url .= '&donation_id=' . $post->ID;

	?>

	<div style="text-align: center; font-size: 1.2em">
		<span><?php esc_html_e( 'We have moved this part under new menu item "Donations". Please click to view', 'wp-fundraising' ); ?> <a href="<?php echo esc_url( $r_url ); ?>"><?php esc_html_e( 'Recent donation', 'wp-fundraising' ); ?></a></span>
	</div>
</div>
