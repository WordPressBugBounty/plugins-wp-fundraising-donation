<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

?>
<div class="xs-donate-limit-details">
	<?php
	if ( ! empty( $wfpDonationLimit ) && property_exists( $wfpDonationLimit, 'enable' ) ) {
		echo esc_html( $wfpDonationLimit->details );
	}
	?>
</div>
