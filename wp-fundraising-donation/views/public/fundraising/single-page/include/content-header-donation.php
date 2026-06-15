<?php 
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

?>
<div class="xs-row">
	<div class="xs-col-sm-12 xs-col-md-6 wfp-single-item">
		<?php

		/*
		 * AR[20200115]
		 * No idea why this block was given here before, so I am keeping it.
		 */
		if ( $wfpEnableFeatured == 'No' ) : ?>
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
				<?php do_action( 'wfp_single_thumbnil_after' ); ?>
				<?php
				if ( apply_filters( 'wfp_single_gallery_hide', true ) ) :
					if ( is_array( $wfp_gallery_array ) && sizeof( $wfp_gallery_array ) > 1 ) {
						echo wp_kses( '<div class="wfp-post-gallery">' . $wfp_gallery_display . '</div>', \WfpFundraising\Utilities\Utils::get_kses_array() );
					}
				endif;
				?>
			</div>
		<?php endif; ?>
	</div>

	<div class="xs-col-sm-12 xs-col-md-12  wfp-single-item">
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

			?>
		</div>
	</div>
</div>

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
			<h3 class="wfp-post-title"><?php the_title(); ?></h3>
			<?php do_action( 'wfp_single_title_after' ); ?>
		</header><!-- header end -->
	<?php endif; ?>
</div>

<div class="xs-row">
	<div class="xs-col-sm-12 xs-col-md-6 wfp-single-item">

		<div class="wfp-post-body">
			<!-- Article header -->
			<?php if ( $wfpEnableSingleExcerpt == 'No' && strlen( get_the_excerpt() ) > 2 ) : ?>
				<div class="wfp-excerpt-section">
					<?php do_action( 'wfp_single_excerpt_before' ); ?>
					<div class="wfp-post-excerpt"><?php the_excerpt(); ?></div>
					<?php do_action( 'wfp_single_excerpt_after' ); ?>
				</div>
			<?php endif; ?>

		</div>
	</div>
</div>


<div class="wfp-bar-section">
	<hr class="wfp-bar-content">
</div>


<div class="xs-row">
	<?php

	$wfpRecentTitle = ( $wfp_donation_format == 'donation' ) ? __( 'Recent Donations', 'wp-fundraising-donation' ) : __( 'Recent Funds', 'wp-fundraising-donation' );

	$wfpArgsTotal      = array(
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
			<?php if ( $wfpEnableSingleContent == 'No' ) : ?>
				<li class="wfp_tab_li active"><a
							href="#wfp_tab_content_decription"><?php echo esc_html( apply_filters( 'wfp_single_content_decription', __( 'Description', 'wp-fundraising-donation' ) ) ); ?></a>
				</li>
				<?php
			endif;
			if ( $wfpEnableSingleReview == 'No' ) :
				?>
				<li class="wfp_tab_li "><a
							href="#wfp_tab_content_review"><?php echo esc_html( apply_filters( 'wfp_single_content_review', __( 'Reviews', 'wp-fundraising-donation' ) ) ); ?>
						(<?php echo esc_attr( $wfp_count ); ?>)</a></li>
				<?php
			endif;
			if ( $wfpEnableSingleUpdates == 'No' && $wfp_donation_format == 'crowdfunding' ) :
				?>
				<li class="wfp_tab_li "><a
							href="#wfp_tab_content_updates"><?php echo esc_html( apply_filters( 'wfp_single_content_updates', __( 'Updates', 'wp-fundraising-donation' ) ) ); ?></a>
				</li>
				<?php
			endif;
			if ( $wfpEnableSingleRecents == 'No' ) :
				?>
				<li class="wfp_tab_li "><a
							href="#wfp_tab_content_recent"><?php echo esc_html( apply_filters( 'wfp_single_content_recent', $wfpRecentTitle ) ); ?></a>
				</li>
			<?php endif; ?>
		</ul>

		<div class="wfp-tab-content-wraper">
			<?php if ( $wfpEnableSingleContent == 'No' ) : ?>
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
			<?php endif; ?>
		</div>
	</div>
	<?php if ( $wfp_donation_format == 'crowdfunding' ) { ?>
		<div class="xs-col-lg-4 wfp-single-pledges">
			<?php
			$wfpEnablePladge = isset( $wfpGetMetaData->pledge_setup->enable ) ? true : false;
			if ( apply_filters( 'wfp_single_content_rewards_hide', $wfpEnablePladge ) ) {
				include __DIR__ . '/page/pledge.php';
			}
			?>
		</div>
	<?php } ?>
</div>
<?php if ( $wfp_donation_format == 'donation' ) { ?>
	<div class="wp-fundraising-forms-content">
		<?php
		$wfp_post1 = get_post( get_the_ID() );
		if ( is_object( $wfp_post1 ) ) {
			echo wp_kses( \WfpFundraising\Apps\Content::instance()->wfp_fund_donation_form_display( $wfp_post1, '', 'single_donation' ), \WfpFundraising\Utilities\Utils::get_kses_array() );
		}
		?>
	</div>
<?php }

?>
