<div class="wfdp-donation-message" ></div>

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


if ( $paymentType == 'default' ) {

	require __DIR__ . '/form_content_fields.php';

	include __DIR__ . '/payment_options_content.php';
}


/**
 * After content data, if there any
 */
if ( isset( $formContentData->enable ) ) {

	require __DIR__ . '/form_content_after.php';
}

