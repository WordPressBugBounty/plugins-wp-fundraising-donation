<?php

$enableSingleTitle = isset( $formSetting->single_title->enable ) ? $formSetting->single_title->enable : 'No';

$categories = get_the_terms( $post->ID, 'wfp-categories' );

?>

<div class="wfp-title-section">
	<?php if ( $enableSingleTitle == 'No' ) : ?>
		<header class="wfp-post-header">
			<?php
			if ( ! empty( $categories ) ) {
				$separator  = ' - ';
				$outputCate = '';
				foreach ( $categories as $category ) {
					$outputCate .= '<a class="wfp-header-cat--link" href="' . esc_url( get_category_link( $category->term_id ) ) . '" >' . esc_html( $category->name ) . '</a>' . $separator;
				}
				$outputCate = trim( $outputCate, $separator );
				?>
				<div class="wfp-header-cat"> <?php echo wp_kses( $outputCate, \WfpFundraising\Utilities\Utils::get_kses_array() ); ?> </div>
				<?php
			}
			?>
			<?php do_action( 'wfp_single_title_before' ); ?>
			<h3 class="wfp-post-title"><?php echo esc_html( $post->post_title ); ?></h3>
			<?php do_action( 'wfp_single_title_after' ); ?>
		</header><!-- header end -->
	<?php endif; ?>

</div>
