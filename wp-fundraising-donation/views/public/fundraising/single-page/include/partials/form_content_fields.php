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


if ( isset( $wfpFormContentData->additional->enable ) && $wfpFormContentData->additional->enable == 'Yes' ) {

	$wfpMultiFiledData = ! empty( $wfpFormContentData->additional->dimentions ) ?
		$wfpFormContentData->additional->dimentions :
		array(
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


	foreach ( $wfpMultiFiledData as $multi ) :

		$wfpLabelField = isset( $multi->lebel ) ? $multi->lebel : '';

		if ( strlen( $wfpLabelField ) > 0 ) {

			$wfpNameFiled = str_replace( array( '  ', '-', ' ', '.', ',', ':' ), '_', strtolower( trim( $wfpLabelField ) ) );

			if ( preg_match_all( '/\b(first|full)\b/i', strtolower( $wfpLabelField ), $matches ) ) {
				$wfp_value = $wfpFirstName;

			} elseif ( preg_match_all( '/\b(last|nick)\b/i', strtolower( $wfpLabelField ), $matches ) ) {
				$wfp_value = $wfpLastName;

			} elseif ( preg_match_all( '/\b(email)\b/i', strtolower( $wfpLabelField ), $matches ) ) {
				$wfp_value = $wfpCurrentEmail;
			} else {

				$wfp_value = get_user_meta( $wfp_author_id, '_wfp_' . $wfpNameFiled, true );
			}


			$wfp_field_type = isset( $multi->type ) ? $multi->type : 'text';
			$wfp_required   = isset( $multi->required ) ? $multi->required : '';
			?>
			<div class="wfdp-donation-input-form wfp-input-field <?php echo esc_attr( $wfpEnableDisplayField ); ?> wfp-<?php echo esc_attr( $wfp_field_type ); ?> ">
				<label for="xs-<?php echo esc_attr( $wfpNameFiled ); ?>"> <?php echo esc_html( $wfpLabelField ); ?></label>
				<?php if ( $wfp_field_type == 'text' ) { ?>
					<input type="text" class="regular-text" name="xs_donate_data_submit[additonal][<?php echo esc_html( $wfpNameFiled ); ?>]" value="<?php echo esc_html( $wfp_value ); ?>" id="xs-<?php echo esc_html( $wfpNameFiled ); ?>" <?php echo ( $wfp_required == 'Yes' ) ? 'required' : ''; ?> />
				<?php } elseif ( $wfp_field_type == 'textarea' ) { ?>
					<textarea style="width:100%;" class="regular-text" name="xs_donate_data_submit[additonal][<?php echo esc_html( $wfpNameFiled ); ?>]" id="xs-<?php echo esc_html( $wfpNameFiled ); ?>" <?php echo ( $wfp_required == 'Yes' ) ? 'required' : ''; ?>><?php echo esc_html( $wfp_value ); ?></textarea>
				<?php } elseif ( $wfp_field_type == 'number' ) { ?>
					<input type="number" class="regular-text" name="xs_donate_data_submit[additonal][<?php echo esc_html( $wfpNameFiled ); ?>]" value="<?php echo esc_html( $wfp_value ); ?>" id="xs-<?php echo esc_html( $wfpNameFiled ); ?>" <?php echo ( $wfp_required == 'Yes' ) ? 'required' : ''; ?> />
				<?php } elseif ( $wfp_field_type == 'email' ) { ?>
					<input type="email" class="regular-text" name="xs_donate_data_submit[additonal][<?php echo esc_html( $wfpNameFiled ); ?>]" value="<?php echo esc_html( $wfp_value ); ?>" id="xs-<?php echo esc_html( $wfpNameFiled ); ?>" <?php echo ( $wfp_required == 'Yes' ) ? 'required' : ''; ?> />
				<?php } else { ?>
					<input type="text" class="regular-text" name="xs_donate_data_submit[additonal][<?php echo esc_html( $wfpNameFiled ); ?>]" value="<?php echo esc_html( $wfp_value ); ?>" id="xs-<?php echo esc_html( $wfpNameFiled ); ?>" <?php echo ( $wfp_required == 'Yes' ) ? 'required' : ''; ?> />
				<?php } ?>
			</div>
			<?php
		}
	endforeach;
}

