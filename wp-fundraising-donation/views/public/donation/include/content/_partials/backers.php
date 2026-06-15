<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

?>
<div class="target-date-goal  goal-donor">
	<?php echo esc_html( apply_filters( 'wfp_single_donercounter_title', __( 'Donor', 'wp-fundraising-donation' ) ) ); ?>

	<div class="wfp-inner-data">
		<?php echo esc_html( round( $wfp_total_rasied_count ) ); ?>
	</div>
</div>
