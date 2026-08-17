<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

$wfp_feature = new \WfpFundraising\Apps\Featured( false );
$wfp_content = new \WfpFundraising\Apps\Content( false );

// page limit
$wfp_limit = isset( $wfp_fundraising_content__show_post ) ? (int) $wfp_fundraising_content__show_post : 9;
$wfp_limit = ( $wfp_limit > 0 ) ? $wfp_limit : 9;

// order by
$wfp_orderby = isset( $wfp_fundraising_content__orderby ) ? $wfp_fundraising_content__orderby : 'post_date';
$wfp_orderby = ( strlen( $wfp_orderby ) > 2 ) ? $wfp_orderby : 'post_date';

// order
$wfp_order = isset( $wfp_fundraising_content__order ) ? $wfp_fundraising_content__order : 'DESC';
$wfp_order = ( strlen( $wfp_order ) > 1 ) ? $wfp_order : 'DESC';


$wfp_args['post_status'] = 'publish';
$wfp_args['post_type']   = \WfpFundraising\Apps\Content::post_type();

if ( $wfp_fundraising_layout_option === 'selected' ) {

	 $wfp_args['post__in'] = count( $wfp_fundraising_content__selected ) > 0 ? $wfp_fundraising_content__selected : array( -1 );

} elseif ( $wfp_fundraising_layout_option === 'categories' ) {
	 // categories query data
	 $wfp_cate_data = isset( $wfp_fundraising_content__categories ) ? $wfp_fundraising_content__categories : '';
	if ( is_array( $wfp_cate_data ) && sizeof( $wfp_cate_data ) > 0 ) {
		$wfpSubQuery          = array(
			array(
				'taxonomy' => 'wfp-categories',
				'field'    => 'term_id',
				'terms'    => $wfp_cate_data,
			),
			'relation' => 'AND',
		);
			// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- Taxonomy filter is intentionally used for this Elementor listing.
			$wfp_args['tax_query'] = $wfpSubQuery;
	}

	 	// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- Meta filter is intentionally used to exclude ended campaigns in this listing.
	 	$wfp_args['meta_query'] = array(
		 'relation' => 'AND',
		 array(
			 'key'     => '__wfp_campaign_status',
			 'value'   => 'Ends',
			 'compare' => '!=',
		 ),
	 );
}

$wfp_args['orderby']          = $wfp_fundraising_layout_option === 'recent' ? 'post_date' : $wfp_orderby;
$wfp_args['posts_per_page']   = $wfp_limit;
$wfp_args['order']            = $wfp_order;

$wfp_the_query = new \WP_Query( $wfp_args );

// layout style
$wfp_layout_style = isset( $wfp_fundraising_content__layout_style ) ? $wfp_fundraising_content__layout_style : 'wfp-layout-grid';

$wfp_desk_top_col = isset( $col_from_short_code ) ? $col_from_short_code : 3;

$wfp_column = array(
	'desktop' => isset( $wfp_settings['wfp_fundraising_content__column_grid'] ) ? esc_attr( $wfp_settings['wfp_fundraising_content__column_grid'] ) : $wfp_desk_top_col,
	'tablet'  => isset( $wfp_settings['wfp_fundraising_content__column_grid_tablet'] ) ? esc_attr( $wfp_settings['wfp_fundraising_content__column_grid_tablet'] ) : 3,
	'mobile'  => isset( $wfp_settings['wfp_fundraising_content__column_grid_mobile'] ) ? esc_attr( $wfp_settings['wfp_fundraising_content__column_grid_mobile'] ) : 1,
);

if ( $wfp_layout_style == 'wfp-layout-list' ) {
	 $wfp_column = array(
		 'desktop' => 1,
		 'tablet'  => 1,
		 'mobile'  => 1,
	 );
}

?>
<div class="wfp-view wfp-view-public">
	<div class="wfp-list-campaign wfp-content-padding <?php echo isset( $className ) ? esc_attr( $className ) : ''; ?>" id="<?php echo isset( $idName ) ? esc_attr( $idName ) : ''; ?>">
		<div class="list-campaign-body <?php echo esc_attr( $wfp_layout_style ); ?> wfp-column-<?php echo esc_attr( $wfp_column['desktop'] ); ?> wfp-column-tablet-<?php echo esc_attr( $wfp_column['tablet'] ); ?> wfp-column-mobile-<?php echo esc_attr( $wfp_column['mobile'] ); ?>">

			<?php if ( $wfp_the_query->have_posts() ) : ?>

			<!-- filter -->
				<?php
				if ( $wfp_fundraising_content__filter_enable == 'Yes' ) {
					$wfp_terms = get_terms(
						array(
							'taxonomy'   => 'wfp-categories',
							'hide_empty' => true,
						)
					);
					if ( ! empty( $wfp_terms ) ) {
						?>
						<div class="wfp-campaign-filter-nav">
							<ul>
								<?php
								foreach ( $wfp_terms as $wfp_key => $term ) {
									printf( "<li class='wfp-campaign-filter-nav-item' data-slug='%s'>%s</li>", esc_attr( $term->slug ), esc_html( $term->name ) );
								}
								?>
							</ul>
						</div>
						<?php
					}
				}
				?>
			<!-- Filter -->

				<?php if ( $wfp_fundraising_content__is_carousel === 'yes' ) : ?>
			<div class="wfp-campaign-carousel"
				 data-autoplay="<?php echo esc_attr( ( $wfp_settings['wfp_fundrising_autoplay'] == 'yes' ) ? '{ "delay": ' . $wfp_settings['wfp_fundrising_autoplay_speed'] . ' }' : 'false' ); ?>"
				 data-loop="<?php echo esc_attr( $wfp_settings['wfp_fundrising_loop'] == 'yes' ? 'true' : 'false' ); ?>"
				 data-speed="<?php echo esc_attr( $wfp_settings['wfp_fundrising_speed']['size'] * 10 ); ?>"
				 data-space-between="<?php echo '10'; // echo esc_attr($wfp_settings['wfp_fundrising_item_gap']['size']); ?>"
				 data-responsive-settings='{"wfp_fundraising_content__column_grid": "<?php echo esc_attr( $wfp_column['desktop'] ); ?>", "wfp_fundraising_content__column_grid_tablet": "<?php echo esc_attr( $wfp_column['tablet'] ); ?>", "wfp_fundraising_content__column_grid_mobile": "<?php echo esc_attr( $wfp_column['mobile'] ); ?>"}'
			>
				<div class="swiper-wrapper">
					<?php else : ?>
					<div class="wfp-campaign-row">
						<?php
						endif;
							global $wpdb;
					?>

						<?php
						while ( $wfp_the_query->have_posts() ) :
							$wfp_the_query->the_post();

							$wfp_categories = get_the_terms( get_the_ID(), 'wfp-categories' );
							$wfp_terms_slug = wp_get_post_terms( get_the_ID(), 'wfp-categories', array( 'fields' => 'slugs' ) );

							$wfp_campaign_post_id = get_the_ID();

							$wfpMetaKey      = 'wfp_form_options_meta_data';
							$wfpMetaDataJson = get_post_meta( get_the_ID(), $wfpMetaKey, false );
							$wfpGetMetaData  = json_decode( json_encode( end( $wfpMetaDataJson ) ) );

							$wfpFormGoalData = isset( $wfpGetMetaData->goal_setup ) ? $wfpGetMetaData->goal_setup : (object) array(
								'enable'    => 'No',
								'goal_type' => 'terget_goal',
							);

							$wfpGoalStatus     = 'No';
							$wfpGoalDataAmount = 0;
							$wfpGoalMessage    = '';

							$wfp_category_info  = isset( $wfp_fundraising_content__category_enable ) ? $wfp_fundraising_content__category_enable : 'Yes';
							$wfp_user_info      = isset( $wfp_fundraising_content__user_enable ) ? $wfp_fundraising_content__user_enable : 'Yes';
							$wfp_title_info     = isset( $wfp_fundraising_content__title_enable ) ? $wfp_fundraising_content__title_enable : 'Yes';
							$wfp_title_limit    = isset( $wfp_fundraising_content__title_limit ) ? $wfp_fundraising_content__title_limit : 40;
							$wfp_excerpt_info   = isset( $wfp_fundraising_content__excerpt_enable ) ? $wfp_fundraising_content__excerpt_enable : 'Yes';
							$wfp_excerpt_limit  = isset( $wfp_fundraising_content__excerpt_limit ) ? $wfp_fundraising_content__excerpt_limit : 60;
							$wfp_featured       = isset( $wfp_fundraising_content__featured_enable ) ? $wfp_fundraising_content__featured_enable : 'Yes';
							$wfp_show_days_left = false;
							$wfp_days_left      = '';


							if ( isset( $wfpFormGoalData->enable ) ) {
								$wfpGoalStatus = isset( $wfp_fundraising_content__goal_enable ) ? $wfp_fundraising_content__goal_enable : 'Yes';

								$wfp_goal_type           = isset( $wfpFormGoalData->goal_type ) ? $wfpFormGoalData->goal_type : 'terget_goal';
								$wfp_total_rasied_amount = $wpdb->get_var( $wpdb->prepare( "SELECT SUM(donate_amount) FROM {$wpdb->prefix}wdp_fundraising WHERE form_id = %d AND status = 'Active'", get_the_ID() ) ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Prepared aggregate read for campaign totals in Elementor listing.
								$wfp_total_rasied_count  = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(donate_id) FROM {$wpdb->prefix}wdp_fundraising WHERE form_id = %d AND status = 'Active'", get_the_ID() ) ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Prepared aggregate read for campaign donor count in Elementor listing.

								$wfp_time               = time();
								$wfp_persentange        = 0;
								$wfp_target_amount      = 0;
								$wfp_target_amount_fake = 0;
								$wfp_to_date            = gmdate( 'Y-m-d' );
								$wfp_target_date        = gmdate( 'Y-m-d' );

								$wfp_total_rasied_amount_fake = $wfp_total_rasied_amount;
								$wfp_total_rasied_count_fake  = $wfp_total_rasied_count;

								if ( in_array( $wfp_goal_type, array( 'terget_goal', 'terget_goal_date', 'campaign_never_end', 'terget_date' ) ) ) {
									$wfp_target_amount      = isset( $wfpFormGoalData->terget->terget_goal->amount ) ? $wfpFormGoalData->terget->terget_goal->amount : 0;
									$wfp_target_amount_fake = isset( $wfpFormGoalData->terget->terget_goal->fake_amount ) ? $wfpFormGoalData->terget->terget_goal->fake_amount : 0;
									$wfp_target_date        = isset( $wfpFormGoalData->terget->terget_goal->date ) ? $wfpFormGoalData->terget->terget_goal->date : gmdate( 'Y-m-d' );

									$wfp_target_time = strtotime( $wfp_target_date );

									$wfp_total_rasied_amount_fake = $wfp_total_rasied_amount + $wfp_target_amount_fake;
									// check amount with data
									if ( $wfp_total_rasied_amount_fake >= $wfp_target_amount ) {
										$wfp_total_rasied_amount_fake = $wfp_total_rasied_amount;
									}

									if ( $wfp_target_amount > 0 ) {
										$wfp_persentange = ( $wfp_total_rasied_amount_fake * 100 ) / $wfp_target_amount;
									}

									if ( $wfp_total_rasied_amount >= $wfp_target_amount ) {
										// $wfpGoalStatus = 'No';
									}
									if ( $wfp_goal_type == 'terget_goal_date' || $wfp_goal_type == 'terget_date' ) {
										if ( $wfp_time > $wfp_target_time ) {
											// $wfpGoalStatus = 'No';
										}
									} elseif ( $wfp_goal_type == 'campaign_never_end' ) {
										// $wfpGoalStatus = 'Yes';
									}
								}

								if ( in_array( $wfp_goal_type, array( 'terget_goal_date', 'terget_date' ) ) ) {

									$wfp_show_days_left = true;
									$wfp_target_date    = isset( $wfpFormGoalData->terget->terget_goal->date ) ? $wfpFormGoalData->terget->terget_goal->date : '';
									$wfp_days_left      = \WfpFundraising\Apps\Settings::get_days_left( $wfp_target_date );
								}

								$wfp_campaign_status = ( $wfpGoalStatus == 'Yes' ) ? 'Publish' : 'Ends';
							}
							?>
							<?php if ( $wfp_fundraising_content__is_carousel === 'yes' ) : ?>
						<div class="single-campaign-blog swiper-slide <?php echo esc_attr( ( isset( $wfp_terms_slug ) && ! empty( $wfp_terms_slug ) ) ? implode( ' ', $wfp_terms_slug ) : '' ); ?>">
							<?php else : ?>
							<div class="single-campaign-blog <?php echo esc_attr( ( isset( $wfp_terms_slug ) && ! empty( $wfp_terms_slug ) ) ? implode( ' ', $wfp_terms_slug ) : '' ); ?>">
								<?php endif; ?>

								<div class="campaign-blog wfp-flip-content-<?php echo esc_attr( $wfp_fundraising_content__flip_enable ); ?>">
									<?php if ( $wfp_featured == 'Yes' ) : ?>
										<?php do_action( 'wfp_campaign_list_thumbnil_before' ); ?>

										<div class="wfp-campaign-container">
											<a href="<?php the_permalink(); ?>">
												<?php if ( $wfp_feature->has_featured_video( get_the_ID() ) ) { ?>
													<div class="wfp-feature-video">
														<img src="<?php echo esc_url( $wfp_feature->get_video_thumbnail( get_the_ID() ) ); ?>" alt="<?php esc_attr_e( 'Video Thumbnail', 'wp-fundraising-donation' ); ?>">
													</div>
												<?php } else { ?>
													<div class="wfp-post-image">

														<?php

														if ( has_post_thumbnail() ) {
															?>

															<img src="
															<?php
															echo esc_url(
																get_the_post_thumbnail_url(
																	$wfp_campaign_post_id,
																	'post-thumbnail',
																	array(
																		'class' => 'wfp-feature wfp-full-image',
																		'title' => esc_attr__( 'Feature image', 'wp-fundraising-donation' ),
																	)
																)
															);
															?>
																		" alt="<?php esc_attr_e( 'Image Thumbnail', 'wp-fundraising-donation' ); ?>" />

															<?php
														}

														?>

													</div>
												<?php } ?>
											</a>
										</div>

										<?php do_action( 'wfp_campaign_list_thumbnil_after' ); ?>
									<?php endif; ?>

									<div class="wfp-compaign-contents">
										<div class="wfp-campaign-content">

											<?php if ( isset( $wfp_fundraising_content__time_left ) && $wfp_fundraising_content__time_left == 'Yes' && $wfp_show_days_left === true ) : ?>
												<div class="number_donation_count_list">
													<span class="wfp-icon wfpf wfpf-time"></span>
													<?php echo esc_html( $wfp_days_left ); ?> <?php echo esc_html( apply_filters( 'wfp_single_date_left_title', __( 'days left', 'wp-fundraising-donation' ) ) ); ?>
												</div>
											<?php endif; ?>

											<?php
											if ( $wfp_category_info == 'Yes' ) {
												if ( ! empty( $wfp_categories ) ) {
													?>
													<div class="wfp-campaign-content--cat">
														<?php

														$wfp_separator  = ' - ';
														$wfpOutputCate = '';
														foreach ( $wfp_categories as $category ) {
															$wfpOutputCate .= '<a class="wfp-campaign-content--cat__link" href="' . esc_url( get_category_link( $category->term_id ) ) . '" >' . esc_html( $category->name ) . '</a>' . $wfp_separator;
														}
														$wfpOutputCate = trim( $wfpOutputCate, $wfp_separator );
														?>
														<?php echo wp_kses( $wfpOutputCate, \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
													</div>
													<?php
												}
											}

											if ( $wfp_title_info == 'Yes' ) :
												?>
												<h3 class="wfp-campaign-content--title" ><a class="wfp-campaign-content--title__link" href="<?php echo esc_url( get_permalink() ); ?>">
																																					   <?php
																																						$wfp_ext = '';
																																						if ( strlen( get_the_title() ) >= $wfp_title_limit ) {
																																							$wfp_ext = ' ...';
																																						}
																																						echo wp_kses( substr( get_the_title(), 0, $wfp_title_limit ) . $wfp_ext, \WfpFundraising\Utilities\Utils::get_kses_array() );
																																						?>
														</a></h3>
												<?php
											endif;
											if ( $wfp_excerpt_info == 'Yes' ) :
												?>
												<p class="wfp-campaign-content--short-description">
												<?php
													$wfp_ext = '';
												if ( strlen( get_the_excerpt() ) >= $wfp_excerpt_limit ) {
													$wfp_ext = ' ...';
												}
													echo wp_kses( substr( get_the_excerpt(), 0, $wfp_excerpt_limit ) . $wfp_ext, \WfpFundraising\Utilities\Utils::get_kses_array() );
												?>
													 </p>
												<?php
											endif;
											if ( $wfpGoalStatus == 'Yes' ) :
												?>
												<?php include \WFP_Fundraising::plugin_dir() . 'views/public/donation/include/content/goal-content.php'; ?>
											<?php endif; ?>
										</div>
										<?php if ( $wfp_user_info == 'Yes' ) : ?>
											<div class="wfp-campign-user">
												<?php
												$wfp_author_id    = get_the_author_meta( 'ID' );
												$wfpProfileImage = get_the_author_meta( 'avatar', $wfp_author_id );
												if ( strlen( $wfpProfileImage ) < 5 ) {
													$wfpProfileImage = get_the_author_meta( 'wfp_author_profile_image', $wfp_author_id );
												}
												?>
												<div class="profile-image">
													<?php if ( strlen( $wfpProfileImage ) > 5 ) { ?>
														<img src="<?php echo esc_url( $wfpProfileImage ); ?> " class="avatar wfp-profile-image" alt="<?php the_author_meta( 'display_name', $wfp_author_id ); ?>" />
													<?php } else { ?>
														<?php echo get_avatar( $wfp_author_id, 35 ); ?>
													<?php } ?>
												</div>

												<div class="profile-info">
													<span class="display-name"><?php esc_html_e( 'Created by', 'wp-fundraising-donation' ); ?> <strong class="display-name__author"><?php the_author_meta( 'display_name', $wfp_author_id ); ?></strong></span>
												</div>
											</div>
										<?php endif; ?>

										<?php
										if ( isset( $wfp_fundraising_content__is_button ) && $wfp_fundraising_content__is_button == 'yes' ) :
											$wfp_btn1_text = isset( $wfp_settings['wfp_fundraising_content__btn1-text'] ) ? $wfp_settings['wfp_fundraising_content__btn1-text'] : '';
											$wfp_btn1_url  = isset( $wfp_settings['wfp_fundraising_content__btn1-url']['url'] ) ? $wfp_settings['wfp_fundraising_content__btn1-url']['url'] : '';

											$wfp_btn2_text = isset( $wfp_settings['wfp_fundraising_content__btn2-text'] ) ? $wfp_settings['wfp_fundraising_content__btn2-text'] : '';
											$wfp_btn2_url  = isset( $wfp_settings['wfp_fundraising_content__btn2-url']['url'] ) ? $wfp_settings['wfp_fundraising_content__btn2-url']['url'] : '';

											?>
											<div class="wfp-fundrising-button-list">
												<?php if ( $wfp_btn1_text != '' && $wfp_btn1_url != '' ) : ?>
													<a href="<?php echo esc_url( $wfp_btn1_url ); ?>" class="wfp-fundrising-button wfp-fundrising-first-btn"><?php echo esc_html( $wfp_btn1_text ); ?>
														<!-- <span class="wfp-fundrising-icon xs-icon-plus"></span> -->
													</a>
												<?php endif; ?>
												<?php if ( $wfp_btn2_text != '' && $wfp_btn2_url != '' ) : ?>
													<a href="<?php echo esc_url( $wfp_btn2_url ); ?>" class="wfp-fundrising-button wfp-fundrising-second"><?php echo esc_html( $wfp_btn2_text ); ?>
														<!-- <span class="wfp-fundrising-icon xs-icon-plus"></span> -->
													</a>
												<?php endif; ?>
											</div>
										<?php endif; ?>
									</div>
								</div>
							</div>

							<?php endwhile; ?>
							<?php wp_reset_postdata(); ?>

							<?php if ( $wfp_fundraising_content__is_carousel === 'yes' ) : ?>
						</div>
								<?php if ( 'arrows' == $wfp_settings['wfp_fundrising_navigation'] ) : ?>
									<?php $this->render_navigation(); ?>
					<?php elseif ( 'dots' == $wfp_settings['wfp_fundrising_navigation'] ) : ?>
						<?php $this->render_pagination(); ?>
					<?php elseif ( 'both' == $wfp_settings['wfp_fundrising_navigation'] ) : ?>
						<?php $this->render_navigation(); ?>
						<?php $this->render_pagination(); ?>
					<?php endif; ?>
					<?php endif; ?>
					</div>
					<?php else : ?>
						<p class="xs-alert xs-alert-danger"><?php esc_html_e( 'Sorry, not found any campaign.', 'wp-fundraising-donation' ); ?></p>
					<?php endif; ?>
				</div>
			</div>
		</div>
