<?php

require __DIR__ . '/_form_terms_before.php'; ?>

	<div class="wfdp-donation-input-form">
	<?php

	if ( $campaign_status == \WfpFundraising\Apps\Key::CAMPAIGN_STATUS_ENDED ) {

		?>
			<p class="xs-alert xs-alert-success"><?php echo esc_html( $goalMessage ); ?></p>
			<?php

	} else {
		?>

			<button type="submit"
					name="submit-form-donation"
					class="xs-btn btn-special submit-btn">
			<?php echo esc_html( $formDesignData->submit_button ? $formDesignData->submit_button : ( __( 'Donate Now', 'wp-fundraising' ) ) ); ?>
			</button>
			<?php
	}

	?>
	</div>

<?php require __DIR__ . '/_form_terms_after.php'; ?>
