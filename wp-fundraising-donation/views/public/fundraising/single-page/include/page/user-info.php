<?php

/**
 * $hide_author - this is boolean but we have to give support for legacy code, so now we are checking negative fist!
 */
if ( empty( $hide_author ) ) :?>

	<div class="wfp-campaign-user utrace1">
		<?php

		global $post;

		$author_id    = $post->post_author;
		$profileImage = get_the_author_meta( 'avatar', $author_id );

		if ( strlen( $profileImage ) < 5 ) {
			$profileImage = get_the_author_meta( 'wdp_author_profile_image', $author_id );
		}
		?>
		<div class="profile-image">
			<?php if ( strlen( $profileImage ) > 5 ) { ?>
				<img src="<?php echo esc_url( $profileImage ); ?> " class="avatar wfp-profile-image"
					 alt="<?php esc_attr( the_author_meta( 'display_name', $author_id ) ); ?>"/>
			<?php } else { ?>
				<?php echo get_avatar( $author_id, 36 ); ?>
			<?php } ?>
		</div>

		<div class="profile-info">
			<span class="display-name"><?php the_author_meta( 'display_name', $author_id ); ?></span>
			<span class="country-name"><?php the_author_meta( 'wdp_author_country_city', $author_id ); ?></span>
		</div>
	</div>
	<?php
endif;
