<?php

if ( $enable_cat === \WfpFundraising\Apps\Key::WFP_YES && ! empty( $categories ) ) : ?>

	<div class="wfp-header-cat">
	<?php

		$separator = false;

	foreach ( $categories as $key => $category ) :

		if ( $separator ) :
			?>
				<span class='wfp-header-cat--separator'>-</span>
				<?php
			endif;
		?>

		<a class="wfp-header-cat--link"
		   href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>"
		   title="<?php echo esc_attr( sprintf( __( 'View all posts in %s', 'wp-fundraising' ), $category->name ) ); ?>"
		>
			<?php echo esc_html( $category->name ); ?>

			</a>
			<?php

			$separator = true;

		endforeach;
	?>

	</div>
	<?php

endif;
