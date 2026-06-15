<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

require __DIR__ . '/_form_terms_before.php'; ?>

	<div class="wfdp-donation-input-form">
	<?php

	if ( $wfp_campaign_status == \WfpFundraising\Apps\Key::CAMPAIGN_STATUS_ENDED ) {

		?>
			<p class="xs-alert xs-alert-success"><?php echo esc_html( $wfpGoalMessage ); ?></p>
			<?php

	} else {
		?>

			<button type="submit"
					name="submit-form-donation"
					class="xs-btn btn-special submit-btn">
			<?php echo esc_html( $wfpFormDesignData->submit_button ? $wfpFormDesignData->submit_button : ( __( 'Donate Now', 'wp-fundraising-donation' ) ) ); ?>
			</button>
			<?php
	}

	?>
	</div>

<?php require __DIR__ . '/_form_terms_after.php'; ?>
