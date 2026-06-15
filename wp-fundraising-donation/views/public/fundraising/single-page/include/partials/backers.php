<?php 
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

?>
<div class="target-date-goal  goal-donor">
	<?php echo wp_kses( apply_filters( 'wfp_single_donercounter_title', esc_html__( 'Donor', 'wp-fundraising-donation' ) ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>

	<div class="wfp-inner-data">
		<?php echo esc_attr( round( $wfp_donation_count ) ); ?>
	</div>
</div>
