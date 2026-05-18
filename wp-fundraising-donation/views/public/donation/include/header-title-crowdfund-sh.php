<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

$wfpEnableSingleTitle = isset( $wfpFormSetting->single_title->enable ) ? $wfpFormSetting->single_title->enable : 'No';

$wfp_categories = get_the_terms( $post->ID, 'wfp-categories' );

?>

<div class="wfp-title-section">
	<?php if ( $wfpEnableSingleTitle == 'No' ) : ?>
		<header class="wfp-post-header">
			<?php
			if ( ! empty( $wfp_categories ) ) {
				$wfp_separator  = ' - ';
				$wfpOutputCate = '';
				foreach ( $wfp_categories as $category ) {
					$wfpOutputCate .= '<a class="wfp-header-cat--link" href="' . esc_url( get_category_link( $category->term_id ) ) . '" >' . esc_html( $category->name ) . '</a>' . $wfp_separator;
				}
				$wfpOutputCate = trim( $wfpOutputCate, $wfp_separator );
				?>
				<div class="wfp-header-cat"> <?php echo wp_kses( $wfpOutputCate, \WfpFundraising\Utilities\Utils::get_kses_array() ); ?> </div>
				<?php
			}
			?>
			<?php do_action( 'wfp_single_title_before' ); ?>
			<h3 class="wfp-post-title"><?php echo esc_html( $post->post_title ); ?></h3>
			<?php do_action( 'wfp_single_title_after' ); ?>
		</header><!-- header end -->
	<?php endif; ?>

</div>
