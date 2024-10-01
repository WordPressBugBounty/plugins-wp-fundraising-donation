<?php
// check login user information
$firstName = $lastName = $currentEmail = '';
$author_id = get_current_user_id();

if ( is_user_logged_in() ) {
	$current_user = wp_get_current_user();

	$firstName = ( isset( $current_user->first_name ) && strlen( $current_user->first_name ) > 0 ) ? $current_user->first_name : get_user_meta( $author_id, '_wfp_first_name', true );

	$lastName = ( isset( $current_user->last_name ) && strlen( $current_user->last_name ) > 0 ) ? $current_user->last_name : get_user_meta( $author_id, '_wfp_last_name', true );

	$currentEmail = ( isset( $current_user->user_email ) && strlen( $current_user->user_email ) > 0 ) ? $current_user->user_email : get_user_meta( $author_id, '_wfp_email_address', true );
}

$additionalEnable = ! isset( $formContentData->additional ) ? 'check' : '';

if ( isset( $formContentData->additional->enable ) && $formContentData->additional->enable == 'Yes' ) {
	$additionalEnable = 'check';
}

$multiFiledData = isset( $formContentData->additional->dimentions ) && sizeof( $formContentData->additional->dimentions ) ? $formContentData->additional->dimentions : array(
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

if ( is_array( $multiFiledData ) && sizeof( $multiFiledData ) > 0 && $additionalEnable == 'check' ) {
	$m = 0;
	foreach ( $multiFiledData as $multi ) :
		$lebelFiled = isset( $multi->lebel ) ? $multi->lebel : '';

		if ( strlen( $lebelFiled ) > 0 ) {
			$nameFiled = str_replace( array( '  ', '-', ' ', '.', ',', ':' ), '_', strtolower( trim( $lebelFiled ) ) );

			$value = get_user_meta( $author_id, '_wfp_' . $nameFiled, true );

			if ( preg_match_all( '/\b(first|full)\b/i', strtolower( $lebelFiled ), $matches ) ) {
				$value = $firstName;
			}
			if ( preg_match_all( '/\b(last|nick)\b/i', strtolower( $lebelFiled ), $matches ) ) {
				$value = $lastName;
			}
			if ( preg_match_all( '/\b(email)\b/i', strtolower( $lebelFiled ), $matches ) ) {
				$value = $currentEmail;
			}


			$tyleFiled = isset( $multi->type ) ? $multi->type : 'text';
			$required  = isset( $multi->required ) ? $multi->required : '';
			?>
	<div class="wfdp-donation-input-form wfp-input-field <?php echo esc_attr( $enableDisplayField ); ?> wfp-<?php echo esc_attr( $tyleFiled ); ?> ">
		<label for="xs-<?php echo esc_attr( $nameFiled ); ?>"> <?php echo esc_html( $lebelFiled ); ?></label>
			<?php if ( $tyleFiled == 'text' ) { ?>
		<input type="text" class="regular-text" name="xs_donate_data_submit[additonal][<?php echo esc_attr( $nameFiled ); ?>]" value="<?php echo esc_html( $value ); ?>" id="xs-<?php echo esc_attr( $nameFiled ); ?>" <?php echo esc_attr( ( $required == 'Yes' ) ? 'required' : '' ); ?> />
		<?php } elseif ( $tyleFiled == 'textarea' ) { ?>
		<textarea style="width:100%;" class="regular-text" name="xs_donate_data_submit[additonal][<?php echo esc_attr( $nameFiled ); ?>]" id="xs-<?php echo esc_attr( $nameFiled ); ?>" <?php echo esc_attr( ( $required == 'Yes' ) ? 'required' : '' ); ?>><?php echo esc_html( $value ); ?></textarea>
		<?php } elseif ( $tyleFiled == 'number' ) { ?>
		<input type="number" class="regular-text" name="xs_donate_data_submit[additonal][<?php echo esc_attr( $nameFiled ); ?>]" value="<?php echo esc_html( $value ); ?>" id="xs-<?php echo esc_attr( $nameFiled ); ?>" <?php echo esc_attr( ( $required == 'Yes' ) ? 'required' : '' ); ?> />
		<?php } ?>
	</div>
			<?php
			$m++;
		}
	endforeach;
}
