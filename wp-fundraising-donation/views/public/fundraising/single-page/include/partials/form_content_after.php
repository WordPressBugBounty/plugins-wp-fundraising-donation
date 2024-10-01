<?php

if ( $formContentData->enable === \WfpFundraising\Apps\Key::WFP_YES && $formContentData->content_position == 'after-form' ) : ?>

	<div class="wfdp-donation-content-data before-form">
		<?php echo esc_html( $formContentData->content ); ?>
	</div>

	<?php
endif;
