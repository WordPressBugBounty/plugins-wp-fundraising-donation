<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

if ( $wfpFormContentData->enable === \WfpFundraising\Apps\Key::WFP_YES && $wfpFormContentData->content_position == 'after-form' ) : ?>

	<div class="wfdp-donation-content-data before-form">
		<?php echo esc_html( $wfpFormContentData->content ); ?>
	</div>

	<?php
endif;
