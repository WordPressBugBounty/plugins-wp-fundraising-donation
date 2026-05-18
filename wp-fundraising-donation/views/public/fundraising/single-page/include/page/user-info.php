<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

/**
 * $wfp_hide_author - this is boolean but we have to give support for legacy code, so now we are checking negative fist!
 */
if ( empty( $wfp_hide_author ) ) :?>

	<div class="wfp-campaign-user utrace1">
		<?php

		global $post;

		$wfp_author_id    = $post->post_author;
		$wfpProfileImage = get_the_author_meta( 'avatar', $wfp_author_id );

		if ( strlen( $wfpProfileImage ) < 5 ) {
			$wfpProfileImage = get_the_author_meta( 'wdp_author_profile_image', $wfp_author_id );
		}
		?>
		<div class="profile-image">
			<?php if ( strlen( $wfpProfileImage ) > 5 ) { ?>
				<img src="<?php echo esc_url( $wfpProfileImage ); ?> " class="avatar wfp-profile-image"
					 alt="<?php esc_attr( the_author_meta( 'display_name', $wfp_author_id ) ); ?>"/>
			<?php } else { ?>
				<?php echo get_avatar( $wfp_author_id, 36 ); ?>
			<?php } ?>
		</div>

		<div class="profile-info">
			<span class="display-name"><?php the_author_meta( 'display_name', $wfp_author_id ); ?></span>
			<span class="country-name"><?php the_author_meta( 'wdp_author_country_city', $wfp_author_id ); ?></span>
		</div>
	</div>
	<?php
endif;
