<div class="xs-donate-limit-details">
	<?php
	if ( ! empty( $donationLimit ) && property_exists( $donationLimit, 'enable' ) ) {
		echo esc_html( $donationLimit->details );
	}
	?>
</div>
