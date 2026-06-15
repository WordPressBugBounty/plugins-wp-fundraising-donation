<?php 
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

?>
<div class="wfp-title-section"><?php

if ( $wfpHideTitle == 'No' ) : ?>

		<header class="wfp-post-header">
			<div class="wfp-header-cat">
				<?php

				if ( ! empty( $wfp_categories ) ) {

					$wfp_separator = '';

					foreach ( $wfp_categories as $category ) {

						echo esc_html( $wfp_separator );
						?>

						<a class="wfp-header-cat--link" href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" ><?php echo esc_html( $category->name ); ?></a>
																		 <?php

																			$wfp_separator = ' - ';
					}
				}
				?>

			</div>

			<?php do_action( 'wfp_single_title_before' ); ?>

			<h3 class="wfp-post-title"><?php echo esc_html( $post->post_title ); ?></h3>

			<?php do_action( 'wfp_single_title_after' ); ?>

		</header> 
		<?php

	endif;
?>
</div>

<div class="xs-row">
	<div class="xs-col-sm-12 xs-col-md-6 wfp-single-item"> 
	<?php

	if ( $wfpHideFeatured == 'No' ) :
		?>

			<div class="wfp-entry-thumbnail post-media ">

				<?php do_action( 'wfp_single_thumbnil_before' ); ?>

				<div class="wfp-post-image"> 
				<?php

				echo get_the_post_thumbnail(
					$wfpPostId,
					'post-thumbnail',
					array(
						'class' => 'wfp-feature wfp-full-image',
						'title' => 'Feature image',
					)
				);
				?>
				</div>

				<?php

				do_action( 'wfp_single_thumbnil_after' );

				if ( apply_filters( 'wfp_single_gallery_hide', true ) ) :


					$wfp_gallery_display = '';
					$wfp_gallery_array   = explode( ',', get_post_meta( $wfpPostId, 'wfp_portfolio_gallery', true ) );

					if ( ! empty( $wfp_gallery_array ) && is_array( $wfp_gallery_array ) ) {
						?>

						<div class="wfp-post-gallery">

							<ul class="wfp-portfolio-gallery">
							<?php

							foreach ( $wfp_gallery_array as $gallery_item ) :
								?>

									<li>
										<a class="xs_popup_gallery" href="<?php echo esc_url( wp_get_attachment_url( $gallery_item ) ); ?>">
											<img id="portfolio-item-<?php echo esc_html( $gallery_item ); ?>" src="<?php echo esc_url( wp_get_attachment_thumb_url( $gallery_item ) ); ?>">
										</a>
									</li>

									<?php

								endforeach;
							?>

							</ul>
						</div>

						<?php
					}

				endif;
				?>

			</div> 
			<?php

		endif;
	?>


		<div class="wfp-post-body">

			<?php

			if ( $wfpHideShortBrief == 'No' ) :

				$wfp_brief = get_the_excerpt( $post );

				if ( ! empty( $wfp_brief ) && strlen( $wfp_brief ) > 5 ) :
					?>

					<div class="wfp-excerpt-section">
						<h3 class="wfp-short-berif-title"><?php echo wp_kses( apply_filters( 'wfp_single_excerpt_title', esc_html__( 'Short Brief', 'wp-fundraising-donation' ) ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?></h3>
						<?php do_action( 'wfp_single_excerpt_before' ); ?>
						<div class="wfp-post-excerpt"><?php echo wp_kses( $wfp_brief, \WfpFundraising\Utilities\Utils::get_kses_array() ); ?></div>
						<?php do_action( 'wfp_single_excerpt_after' ); ?>
					</div>

					<?php

				endif;
			endif;
			?>

		</div>
	</div>

	<?php

	// Done upto above.........
	// do it later
	// AR........
	// todo - crowed working

	?>



	<div class="xs-col-sm-12 xs-col-md-6  wfp-single-item">
		<div class="wfp-goal-wraper">
			<?php

			// common logic loaded
			require __DIR__ . '/common/load.php';

			// goal bar check enable
			if ( apply_filters( 'wfp_single_goal_hide', isset( $wfpFormGoalData->enable ) ) ) {
				?>
				<div class="wfp-goal-single ">
					<?php
					// before goal content
					do_action( 'wfp_single_goal_progress_before', $wfpFormGoalData );
					include \WFP_Fundraising::plugin_dir() . 'views/public/donation/include/content/goal-content.php';
					// after goal content
					do_action( 'wfp_single_goal_progress_after', $wfpFormGoalData );
					?>
				</div>
				<?php
			}

			// backers information
			require __DIR__ . '/page/backers.php';

			// socal sharing page/backers
			require __DIR__ . '/page/social.php';

			// campaign user info
			require __DIR__ . '/page/user-info.php';
			?>
		</div>
	</div>
</div>
<div class="wfp-bar-section">
	<hr class="wfp-bar-content">
</div>
<div class="xs-row">
	<?php
	$wfpRecentTitle = ( $wfp_donation_format == 'donation' ) ? __( 'Recent Donations', 'wp-fundraising-donation' ) : __( 'Recent Funds', 'wp-fundraising-donation' );

	$wfpArgsTotal = array(
		'post_type'   => 'wfp-review',
		'post_parent' => get_the_id(),
		'post_status' => 'publish',
	);

	$wfp_the_queryTotal = new \WP_Query( $wfpArgsTotal );
	$wfp_count          = $wfp_the_queryTotal->post_count;
	wp_reset_postdata();
	?>

	<div class="xs-col-lg-8 wfp-single-tabs <?php echo ( $wfp_donation_format == 'donation' ) ? 'xs-col-lg-12' : ''; ?>">
		<ul class="wfp-tab" id="wfp_menu_fixed">
		<?php

		if ( $wfpEnableSingleContent == 'No' ) :
			?>
				<li class="wfp_tab_li active">
					<a href="#wfp_tab_content_decription"><?php echo wp_kses( apply_filters( 'wfp_single_content_decription', esc_html__( 'Description', 'wp-fundraising-donation' ) ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?></a>
				</li> 
				<?php
			endif;

		if ( $wfpEnableSingleReview == 'No' ) :
			?>
				<li class="wfp_tab_li ">
					<a href="#wfp_tab_content_review"><?php echo wp_kses( apply_filters( 'wfp_single_content_review', esc_html__( 'Reviews', 'wp-fundraising-donation' ) ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
						(<?php echo esc_attr( $wfp_count ); ?>)</a>
				</li> 
				<?php
			endif;

		if ( $wfpEnableSingleUpdates == 'No' && $wfp_donation_format == 'crowdfunding' ) :
			?>
				<li class="wfp_tab_li ">
					<a href="#wfp_tab_content_updates"><?php echo wp_kses( apply_filters( 'wfp_single_content_updates', esc_html__( 'Updates', 'wp-fundraising-donation' ) ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?></a>
				</li> 
				<?php
			endif;

		if ( $wfpEnableSingleRecents == 'No' ) :
			?>
				<li class="wfp_tab_li ">
					<a href="#wfp_tab_content_recent"><?php echo wp_kses( apply_filters( 'wfp_single_content_recent', esc_html( $wfpRecentTitle ) ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?></a>
				</li> 
				<?php
			endif;
		?>
		</ul>

		<div class="wfp-tab-content-wraper">
			<?php

			if ( $wfpEnableSingleContent == 'No' ) :
				?>
				<div class="wfp-tab-content wfp-tab-div-disable active" id="wfp_tab_content_decription">
					<div class="wfp-post-description">
						<?php do_action( 'wfp_single_content_before' ); ?>
						<?php the_content(); ?>
						<?php do_action( 'wfp_single_content_after' ); ?>
					</div>
				</div>
				<!-- Article content -->
				<?php

			endif;

			if ( $wfpEnableSingleReview == 'No' ) :
				?>
				<div class="wfp-tab-content wfp-tab-div-disable " id="wfp_tab_content_review">
					<?php include __DIR__ . '/page/review.php'; ?>
				</div> 
				<?php
			endif;

			if ( $wfpEnableSingleUpdates == 'No' && $wfp_donation_format == 'crowdfunding' ) :
				?>
				<div class="wfp-tab-content wfp-tab-div-disable " id="wfp_tab_content_updates">
					<?php include __DIR__ . '/page/updates.php'; ?>
				</div> 
				<?php
			endif;

			if ( $wfpEnableSingleRecents == 'No' ) :
				?>
				<div class="wfp-tab-content wfp-tab-div-disable " id="wfp_tab_content_recent">
					<?php include __DIR__ . '/page/recent.php'; ?>
				</div> 
				<?php
			endif;
			?>
		</div>
	</div>
	<?php

	if ( $wfp_donation_format == 'crowdfunding' ) {
		?>
		<div class="xs-col-lg-4 wfp-single-pledges">
			<?php
			$wfpEnablePladge = isset( $wfpGetMetaData->pledge_setup->enable ) ? true : false;
			if ( apply_filters( 'wfp_single_content_rewards_hide', $wfpEnablePladge ) ) {
				include __DIR__ . '/page/pledge.php';
			}
			?>
		</div>
		<?php

	}
	?>

</div>
