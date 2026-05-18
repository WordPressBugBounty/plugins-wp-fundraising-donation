<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

if ( $wfp_enable_cat === \WfpFundraising\Apps\Key::WFP_YES && ! empty( $wfp_categories ) ) : ?>

	<div class="wfp-header-cat">
	<?php

		$wfp_separator = false;

	foreach ( $wfp_categories as $wfp_key => $category ) :

		if ( $wfp_separator ) :
			?>
				<span class='wfp-header-cat--separator'>-</span>
				<?php
			endif;
		?>

		<a class="wfp-header-cat--link"
		   href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>"
		   title="<?php
		   // translators: %s: category name.
		   echo esc_attr( sprintf( __( 'View all posts in %s', 'wp-fundraising' ), $category->name ) ); ?>"
		>
			<?php echo esc_html( $category->name ); ?>

			</a>
			<?php

			$wfp_separator = true;

		endforeach;
	?>

	</div>
	<?php

endif;
