<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

// check login user information
$wfpFirstName = $wfpLastName = $wfpCurrentEmail = '';
$wfp_author_id = get_current_user_id();

if ( is_user_logged_in() ) {
	$wfp_current_user = wp_get_current_user();

	$wfpFirstName = ( isset( $wfp_current_user->first_name ) && strlen( $wfp_current_user->first_name ) > 0 ) ? $wfp_current_user->first_name : get_user_meta( $wfp_author_id, '_wfp_first_name', true );

	$wfpLastName = ( isset( $wfp_current_user->last_name ) && strlen( $wfp_current_user->last_name ) > 0 ) ? $wfp_current_user->last_name : get_user_meta( $wfp_author_id, '_wfp_last_name', true );

	$wfpCurrentEmail = ( isset( $wfp_current_user->user_email ) && strlen( $wfp_current_user->user_email ) > 0 ) ? $wfp_current_user->user_email : get_user_meta( $wfp_author_id, '_wfp_email_address', true );
}

$wfpAdditionalEnable = ! isset( $wfpFormContentData->additional ) ? 'check' : '';

if ( isset( $wfpFormContentData->additional->enable ) && $wfpFormContentData->additional->enable == 'Yes' ) {
	$wfpAdditionalEnable = 'check';
}

/*
 * Note: the default labels below are deliberately not wrapped in __().
 * The label is slugified into the field's name attribute and _wfp_* meta
 * key, and keyword-matched against /\b(first|full|last|nick|email)\b/ to
 * identify the donor's name and email. Translating them would change the
 * POST keys and break that detection. Labels are editable per campaign,
 * so site owners can still localise what visitors actually see.
 */
$wfpMultiFiledData = isset( $wfpFormContentData->additional->dimentions ) && sizeof( $wfpFormContentData->additional->dimentions ) ? $wfpFormContentData->additional->dimentions : array(
	(object) array(
		'type'     => 'text',
		'lebel'    => 'First Name',
		'default'  => '',
		'required' => 'Yes',
	),
	(object) array(
		'type'     => 'text',
		'lebel'    => 'Last Name',
		'default'  => '',
		'required' => 'Yes',
	),
	(object) array(
		'type'     => 'text',
		'lebel'    => 'Email Address',
		'default'  => '',
		'required' => 'Yes',
	),
);

if ( is_array( $wfpMultiFiledData ) && sizeof( $wfpMultiFiledData ) > 0 && $wfpAdditionalEnable == 'check' ) {
	$wfp_m = 0;
	foreach ( $wfpMultiFiledData as $multi ) :
		$wfpLebelFiled = isset( $multi->lebel ) ? $multi->lebel : '';

		if ( strlen( $wfpLebelFiled ) > 0 ) {
			$wfpNameFiled = str_replace( array( '  ', '-', ' ', '.', ',', ':' ), '_', strtolower( trim( $wfpLebelFiled ) ) );

			$wfp_value = get_user_meta( $wfp_author_id, '_wfp_' . $wfpNameFiled, true );

			if ( preg_match_all( '/\b(first|full)\b/i', strtolower( $wfpLebelFiled ), $matches ) ) {
				$wfp_value = $wfpFirstName;
			}
			if ( preg_match_all( '/\b(last|nick)\b/i', strtolower( $wfpLebelFiled ), $matches ) ) {
				$wfp_value = $wfpLastName;
			}
			if ( preg_match_all( '/\b(email)\b/i', strtolower( $wfpLebelFiled ), $matches ) ) {
				$wfp_value = $wfpCurrentEmail;
			}


			$wfpTyleFiled = isset( $multi->type ) ? $multi->type : 'text';
			$wfp_required  = isset( $multi->required ) ? $multi->required : '';
			?>
	<div class="wfdp-donation-input-form wfp-input-field <?php echo esc_attr( $wfpEnableDisplayField ); ?> wfp-<?php echo esc_attr( $wfpTyleFiled ); ?> ">
		<label for="xs-<?php echo esc_attr( $wfpNameFiled ); ?>"> <?php echo esc_html( $wfpLebelFiled ); ?></label>
			<?php if ( $wfpTyleFiled == 'text' ) { ?>
		<input type="text" class="regular-text" name="xs_donate_data_submit[additonal][<?php echo esc_attr( $wfpNameFiled ); ?>]" value="<?php echo esc_html( $wfp_value ); ?>" id="xs-<?php echo esc_attr( $wfpNameFiled ); ?>" <?php echo esc_attr( ( $wfp_required == 'Yes' ) ? 'required' : '' ); ?> />
		<?php } elseif ( $wfpTyleFiled == 'textarea' ) { ?>
		<textarea style="width:100%;" class="regular-text" name="xs_donate_data_submit[additonal][<?php echo esc_attr( $wfpNameFiled ); ?>]" id="xs-<?php echo esc_attr( $wfpNameFiled ); ?>" <?php echo esc_attr( ( $wfp_required == 'Yes' ) ? 'required' : '' ); ?>><?php echo esc_html( $wfp_value ); ?></textarea>
		<?php } elseif ( $wfpTyleFiled == 'number' ) { ?>
		<input type="number" class="regular-text" name="xs_donate_data_submit[additonal][<?php echo esc_attr( $wfpNameFiled ); ?>]" value="<?php echo esc_html( $wfp_value ); ?>" id="xs-<?php echo esc_attr( $wfpNameFiled ); ?>" <?php echo esc_attr( ( $wfp_required == 'Yes' ) ? 'required' : '' ); ?> />
		<?php } elseif ( $wfpTyleFiled == 'email' ) { ?>
		<input type="email" class="regular-text" name="xs_donate_data_submit[additonal][<?php echo esc_attr( $wfpNameFiled ); ?>]" value="<?php echo esc_html( $wfp_value ); ?>" id="xs-<?php echo esc_attr( $wfpNameFiled ); ?>" <?php echo esc_attr( ( $wfp_required == 'Yes' ) ? 'required' : '' ); ?> />
		<?php } else { ?>
		<input type="text" class="regular-text" name="xs_donate_data_submit[additonal][<?php echo esc_attr( $wfpNameFiled ); ?>]" value="<?php echo esc_html( $wfp_value ); ?>" id="xs-<?php echo esc_attr( $wfpNameFiled ); ?>" <?php echo esc_attr( ( $wfp_required == 'Yes' ) ? 'required' : '' ); ?> />
		<?php } ?>
	</div>
			<?php
			$wfp_m++;
		}
	endforeach;
}
