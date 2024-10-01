
<div class="wfp-modal-header">
	<?php

	require __DIR__ . '/_check_category_markup.php';

	require __DIR__ . '/_check_featured_markup.php';


	if ( $title_enable === \WfpFundraising\Apps\Key::WFP_YES ) :

		if ( $show_in_modal === \WfpFundraising\Apps\Key::WFP_YES ) :

			?><h4 class="xs-modal-header--title"><?php echo esc_html( $post->post_title ); ?></h4>
			<?php
		else :
			?>
			<h4 class="wfp-post-title"><?php echo esc_html( $post->post_title ); ?></h4>
			<?php

		endif;
	endif;

	require __DIR__ . '/_check_excerpt_markup.php';

	?>

</div>
<div class="wfdp-donation-message"></div>

<?php

/**
 * before content data, if there any
 */
if ( isset( $formContentData->enable ) ) {

	require __DIR__ . '/form_content_before.php';
}


/**
 * Show goal data
 */
if ( $enable_goal == 'Yes' ) {

	require __DIR__ . '/goal_content.php';
}


// amount content
require __DIR__ . '/amount-content.php';


// addition fees
// include(__DIR__ . '/content/fees-content.php');

if ( $paymentType == 'default' ) {

	require __DIR__ . '/form_content_fields.php';

	include __DIR__ . '/payment_options_content.php';
}

// if($gateCampaignData == 'default') {
// addition al filed content
// include(__DIR__ . '/content/filed-content.php');
// payment content
// include(__DIR__ . '/content/payment-content.php');
// }


/**
 * After content data, if there any
 */
if ( isset( $formContentData->enable ) ) {

	require __DIR__ . '/form_content_after.php';
}


