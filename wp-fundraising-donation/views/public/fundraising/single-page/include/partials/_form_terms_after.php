<?php

if ( isset( $formTermsData->enable ) && $formTermsData->content_position == 'after-submit-button' ) { ?>

	<div class="xs-donate-display-amount xs-radio_style <?php echo esc_attr( $enableDisplayField ); ?> ">
		
		<?php require __DIR__ . '/partials/terms_content.php'; ?>
		
	</div>

	<?php
}
