<?php

if ( isset( $formTermsData->enable ) ) : ?>


<div class="xs-switch-button_wraper">
	<input type="checkbox" class="xs_donate_switch_button" name="xs-donate-terms-condition" id="xs-donate-terms-condition" value="Yes">
	<label class="xs_donate_switch_button_label small xs-round" for="xs-donate-terms-condition"></label>
	<span class="xs-donate-terms-label"><?php echo esc_html( $formTermsData->level ); ?></span>
	<span class="xs-donate-terms"><?php echo esc_html( $formTermsData->content ); ?></span>
</div>

	<?php

endif;
