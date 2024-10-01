

<div class="wfp-title-section">
	<?php if ( $enableSingleTitle == 'No' ) : ?>
		<header class="wfp-post-header xs-container">
			<?php
			if ( ! empty( $categories ) ) {
				$separator  = ' - ';
				$outputCate = '';
				foreach ( $categories as $category ) {
					$outputCate .= '<a class="wfp-header-cat--link" href="' . esc_url( get_category_link( $category->term_id ) ) . '" >' . esc_html( $category->name ) . '</a>' . $separator;
				}
				$outputCate = trim( $outputCate, $separator );

				if ( apply_filters( 'wfp_crowd_category_hide', true ) ) :
					?>

					<div class="wfp-header-cat"> <?php echo wp_kses( $outputCate, \WfpFundraising\Utilities\Utils::get_kses_array() ); ?> </div>

					<?php
				endif;
			}
			?>
			<div class="xs-containe">
			<?php do_action( 'wfp_single_title_before' ); ?>
			<h3 class="wfp-post-title"><?php the_title(); ?></h3>
			<?php do_action( 'wfp_single_title_after' ); ?>
			</div>
		</header><!-- header end -->
	<?php endif; ?>
</div>

<div class="xs-container single-content-body">
<div class="xs-row">
	<div class="xs-col-sm-12 xs-col-md-6 wfp-single-item">
		<?php

		$featured_image_url = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );

		if ( $enableFeatured == 'No' ) :
			?>

			<div class="wfp-entry-thumbnail post-media ">

				<?php do_action( 'wfp_single_thumbnil_before' ); ?>

				<div class="wfp-post-image">

					<?php
					the_post_thumbnail(
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
					if ( ! empty( $gallery_array ) ) {
						echo wp_kses( '<div class="wfp-post-gallery">' . $gallery_display . '</div>', \WfpFundraising\Utilities\Utils::get_kses_array() );
					}
				endif;
				?>
			</div>
		<?php endif; ?>

		<div class="wfp-post-body">
			<!-- Article header -->
			<?php if ( $enableSingleExcerpt == 'No' && strlen( get_the_excerpt() ) > 2 ) : ?>
				<div class="wfp-excerpt-section">
					<h3 class="wfp-short-berif-title"><?php echo wp_kses( apply_filters( 'wfp_single_excerpt_title', esc_html__( 'Short Brief', 'wp-fundraising' ) ), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?></h3>
					<?php do_action( 'wfp_single_excerpt_before' ); ?>
					<div class="wfp-post-excerpt"><?php the_excerpt(); ?></div>
					<?php do_action( 'wfp_single_excerpt_after' ); ?>
				</div>
			<?php endif; ?>

		</div> <!-- end post-body -->
	</div>

	<div class="xs-col-sm-12 xs-col-md-6  wfp-single-item">
		<div class="wfp-goal-wraper">
			<?php

			// common logic loaded
			require __DIR__ . '/common/load.php';

			// goal bar check enable
			if ( apply_filters( 'wfp_single_goal_hide', isset( $formGoalData->enable ) ) ) {
				?>
				<div class="wfp-goal-single ">
					<?php
					// before goal content
					do_action( 'wfp_single_goal_progress_before', $formGoalData );
					include \WFP_Fundraising::plugin_dir() . 'views/public/donation/include/content/goal-content.php';
					// after goal content
					do_action( 'wfp_single_goal_progress_after', $formGoalData );
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

	$recentTitle = ( $donation_format == 'donation' ) ? __( 'Recent Donations', 'wp-fundraising' ) : __( 'Recent Funds', 'wp-fundraising' );

	$argsTotal      = array(
		'post_type'   => 'wfp-review',
		'post_parent' => get_the_id(),
		'post_status' => 'publish',
	);
	$the_queryTotal = new \WP_Query( $argsTotal );
	$count          = $the_queryTotal->post_count;
	wp_reset_postdata();
	?>
	<div class="xs-col-lg-8 wfp-single-tabs <?php echo ( $donation_format == 'donation' ) ? 'xs-col-lg-12' : ''; ?>">
		<ul class="wfp-tab" id="wfp_menu_fixed">
			<?php if ( $enableSingleContent == 'No' ) : ?>
				<li class="wfp_tab_li active"><a
							href="#wfp_tab_content_decription"><?php echo esc_html( apply_filters( 'wfp_single_content_decription', __( 'Description', 'wp-fundraising' ) ) ); ?></a>
				</li>
				<?php
			endif;
			if ( $enableSingleReview == 'No' ) :
				?>
				<li class="wfp_tab_li "><a
							href="#wfp_tab_content_review"><?php echo esc_html( apply_filters( 'wfp_single_content_review', __( 'Reviews', 'wp-fundraising' ) ) ); ?>
						(<?php echo esc_attr( $count ); ?>)</a></li>
				<?php
			endif;
			if ( $enableSingleUpdates == 'No' && $donation_format == 'crowdfunding' ) :
				?>
				<li class="wfp_tab_li "><a
							href="#wfp_tab_content_updates"><?php echo esc_html( apply_filters( 'wfp_single_content_updates', __( 'Updates', 'wp-fundraising' ) ) ); ?></a>
				</li>
				<?php
			endif;
			if ( $enableSingleRecents == 'No' ) :
				?>
				<li class="wfp_tab_li "><a
							href="#wfp_tab_content_recent"><?php echo esc_html( apply_filters( 'wfp_single_content_recent', __( $recentTitle, 'wp-fundraising' ) ) ); ?></a>
				</li>
			<?php endif; ?>
		</ul>

		<div class="wfp-tab-content-wraper">
			<?php if ( $enableSingleContent == 'No' ) : ?>
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
			if ( $enableSingleReview == 'No' ) :
				?>
				<div class="wfp-tab-content wfp-tab-div-disable " id="wfp_tab_content_review">
					<?php include __DIR__ . '/page/review.php'; ?>
				</div>
				<?php
			endif;
			if ( $enableSingleUpdates == 'No' && $donation_format == 'crowdfunding' ) :
				?>
				<div class="wfp-tab-content wfp-tab-div-disable " id="wfp_tab_content_updates">
					<?php include __DIR__ . '/page/updates.php'; ?>
				</div>
				<?php
			endif;
			if ( $enableSingleRecents == 'No' ) :
				?>
				<div class="wfp-tab-content wfp-tab-div-disable " id="wfp_tab_content_recent">
					<?php include __DIR__ . '/page/recent.php'; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<?php if ( $donation_format == 'crowdfunding' ) { ?>
		<div class="xs-col-lg-4 wfp-single-pledges">
			<?php
			if ( \WfpFundraising\Apps\Form_Settings::instance( $postId )->is_pledge_enabled() ) {
				include __DIR__ . '/page/pledge.php';
			}
			?>
		</div>
	<?php } ?>
</div>
</div>
