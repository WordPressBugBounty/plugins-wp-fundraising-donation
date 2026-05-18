<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

?>

<div class="wfdp-donation-message" ></div>

<?php

/**
 * before content data, if there any
 */
if ( isset( $wfpFormContentData->enable ) ) {

	require __DIR__ . '/form_content_before.php';
}


/**
 * Show goal data
 */
if ( $wfp_enable_goal == 'Yes' ) {

	require __DIR__ . '/goal_content.php';
}


if ( $wfpPaymentType == 'default' ) {

	require __DIR__ . '/form_content_fields.php';

	include __DIR__ . '/payment_options_content.php';
}


/**
 * After content data, if there any
 */
if ( isset( $wfpFormContentData->enable ) ) {

	require __DIR__ . '/form_content_after.php';
}

