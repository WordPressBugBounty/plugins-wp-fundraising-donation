<?php
$feature = new \WfpFundraising\Apps\Featured( false );
$content = new \WfpFundraising\Apps\Content( false );

// page limit
$limit = isset( $wfp_fundraising_content__show_post ) ? (int) $wfp_fundraising_content__show_post : 9;
$limit = ( $limit > 0 ) ? $limit : 9;

// order by
$orderby = isset( $wfp_fundraising_content__orderby ) ? $wfp_fundraising_content__orderby : 'post_date';
$orderby = ( strlen( $orderby ) > 2 ) ? $orderby : 'post_date';

// order
$order = isset( $wfp_fundraising_content__order ) ? $wfp_fundraising_content__order : 'DESC';
$order = ( strlen( $order ) > 1 ) ? $order : 'DESC';


$args['post_status'] = 'publish';
$args['post_type']   = \WfpFundraising\Apps\Content::post_type();

if ( $wfp_fundraising_layout_option === 'selected' ) {

	 $args['post__in'] = count( $wfp_fundraising_content__selected ) > 0 ? $wfp_fundraising_content__selected : array( -1 );

} elseif ( $wfp_fundraising_layout_option === 'categories' ) {
	 // categories query data
	 $cate_data = isset( $wfp_fundraising_content__categories ) ? $wfp_fundraising_content__categories : '';
	if ( is_array( $cate_data ) && sizeof( $cate_data ) > 0 ) {
		$subQuery          = array(
			array(
				'taxonomy' => 'wfp-categories',
				'field'    => 'term_id',
				'terms'    => $cate_data,
			),
			'relation' => 'AND',
		);
		$args['tax_query'] = $subQuery;
	}

	 $args['meta_query'] = array(
		 'relation' => 'AND',
		 array(
			 'key'     => '__wfp_campaign_status',
			 'value'   => 'Ends',
			 'compare' => '!=',
		 ),
	 );
}

$args['orderby']          = $wfp_fundraising_layout_option === 'recent' ? 'post_date' : $orderby;
$args['posts_per_page']   = $limit;
$args['order']            = $order;
$args['suppress_filters'] = true;

$the_query = new \WP_Query( $args );

// layout style
$layout_style = isset( $wfp_fundraising_content__layout_style ) ? $wfp_fundraising_content__layout_style : 'wfp-layout-grid';

$desk_top_col = isset( $col_from_short_code ) ? $col_from_short_code : 3;

$column = array(
	'desktop' => isset( $settings['wfp_fundraising_content__column_grid'] ) ? esc_attr( $settings['wfp_fundraising_content__column_grid'] ) : $desk_top_col,
	'tablet'  => isset( $settings['wfp_fundraising_content__column_grid_tablet'] ) ? esc_attr( $settings['wfp_fundraising_content__column_grid_tablet'] ) : 3,
	'mobile'  => isset( $settings['wfp_fundraising_content__column_grid_mobile'] ) ? esc_attr( $settings['wfp_fundraising_content__column_grid_mobile'] ) : 1,
);

if ( $layout_style == 'wfp-layout-list' ) {
	 $column = array(
		 'desktop' => 1,
		 'tablet'  => 1,
		 'mobile'  => 1,
	 );
}

?>
<div class="wfp-view wfp-view-public">
	<div class="wfp-list-campaign wfp-content-padding <?php echo isset( $className ) ? esc_attr( $className ) : ''; ?>" id="<?php echo isset( $idName ) ? esc_attr( $idName ) : ''; ?>">
		<div class="list-campaign-body <?php echo esc_attr( $layout_style ); ?> wfp-column-<?php echo esc_attr( $column['desktop'] ); ?> wfp-column-tablet-<?php echo esc_attr( $column['tablet'] ); ?> wfp-column-mobile-<?php echo esc_attr( $column['mobile'] ); ?>">

			<?php if ( $the_query->have_posts() ) : ?>

			<!-- filter -->
				<?php
				if ( $wfp_fundraising_content__filter_enable == 'Yes' ) {
					$terms = get_terms(
						'wfp-categories',
						array(
							'hide_empty' => true,
						)
					);
					if ( ! empty( $terms ) ) {
						?>
						<div class="wfp-campaign-filter-nav">
							<ul>
								<?php
								foreach ( $terms as $key => $term ) {
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
				 data-autoplay="<?php echo esc_attr( ( $settings['wfp_fundrising_autoplay'] == 'yes' ) ? '{ "delay": ' . $settings['wfp_fundrising_autoplay_speed'] . ' }' : 'false' ); ?>"
				 data-loop="<?php echo esc_attr( $settings['wfp_fundrising_loop'] == 'yes' ? 'true' : 'false' ); ?>"
				 data-speed="<?php echo esc_attr( $settings['wfp_fundrising_speed']['size'] * 10 ); ?>"
				 data-space-between="<?php echo '10'; // echo esc_attr($settings['wfp_fundrising_item_gap']['size']); ?>"
				 data-responsive-settings='{"wfp_fundraising_content__column_grid": "<?php echo esc_attr( $column['desktop'] ); ?>", "wfp_fundraising_content__column_grid_tablet": "<?php echo esc_attr( $column['tablet'] ); ?>", "wfp_fundraising_content__column_grid_mobile": "<?php echo esc_attr( $column['mobile'] ); ?>"}'
			>
				<div class="swiper-wrapper">
					<?php else : ?>
					<div class="wfp-campaign-row">
						<?php
						endif;
							global $wpdb;
					?>

						<?php
						while ( $the_query->have_posts() ) :
							$the_query->the_post();

							$categories = get_the_terms( get_the_ID(), 'wfp-categories' );
							$terms_slug = wp_get_post_terms( get_the_ID(), 'wfp-categories', array( 'fields' => 'slugs' ) );

							$campaign_post_id = get_the_ID();

							$metaKey      = 'wfp_form_options_meta_data';
							$metaDataJson = get_post_meta( get_the_ID(), $metaKey, false );
							$getMetaData  = json_decode( json_encode( end( $metaDataJson ) ) );

							$formGoalData = isset( $getMetaData->goal_setup ) ? $getMetaData->goal_setup : (object) array(
								'enable'    => 'No',
								'goal_type' => 'terget_goal',
							);

							$goalStatus     = 'No';
							$goalDataAmount = 0;
							$goalMessage    = '';

							$category_info  = isset( $wfp_fundraising_content__category_enable ) ? $wfp_fundraising_content__category_enable : 'Yes';
							$user_info      = isset( $wfp_fundraising_content__user_enable ) ? $wfp_fundraising_content__user_enable : 'Yes';
							$title_info     = isset( $wfp_fundraising_content__title_enable ) ? $wfp_fundraising_content__title_enable : 'Yes';
							$title_limit    = isset( $wfp_fundraising_content__title_limit ) ? $wfp_fundraising_content__title_limit : 40;
							$excerpt_info   = isset( $wfp_fundraising_content__excerpt_enable ) ? $wfp_fundraising_content__excerpt_enable : 'Yes';
							$excerpt_limit  = isset( $wfp_fundraising_content__excerpt_limit ) ? $wfp_fundraising_content__excerpt_limit : 60;
							$featured       = isset( $wfp_fundraising_content__featured_enable ) ? $wfp_fundraising_content__featured_enable : 'Yes';
							$show_days_left = false;
							$days_left      = '';


							if ( isset( $formGoalData->enable ) ) {
								$goalStatus = isset( $wfp_fundraising_content__goal_enable ) ? $wfp_fundraising_content__goal_enable : 'Yes';

								$goal_type           = isset( $formGoalData->goal_type ) ? $formGoalData->goal_type : 'terget_goal';
								$total_rasied_amount = $wpdb->get_var( $wpdb->prepare( "SELECT SUM(donate_amount) FROM {$wpdb->prefix}wdp_fundraising WHERE form_id = %d AND status = 'Active'", get_the_ID() ) );
								$total_rasied_count  = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(donate_id) FROM {$wpdb->prefix}wdp_fundraising WHERE form_id = %d AND status = 'Active'", get_the_ID() ) );

								$time               = time();
								$persentange        = 0;
								$target_amount      = 0;
								$target_amount_fake = 0;
								$to_date            = gmdate( 'Y-m-d' );
								$target_date        = gmdate( 'Y-m-d' );

								$total_rasied_amount_fake = $total_rasied_amount;
								$total_rasied_count_fake  = $total_rasied_count;

								if ( in_array( $goal_type, array( 'terget_goal', 'terget_goal_date', 'campaign_never_end', 'terget_date' ) ) ) {
									$target_amount      = isset( $formGoalData->terget->terget_goal->amount ) ? $formGoalData->terget->terget_goal->amount : 0;
									$target_amount_fake = isset( $formGoalData->terget->terget_goal->fake_amount ) ? $formGoalData->terget->terget_goal->fake_amount : 0;
									$target_date        = isset( $formGoalData->terget->terget_goal->date ) ? $formGoalData->terget->terget_goal->date : gmdate( 'Y-m-d' );

									$target_time = strtotime( $target_date );

									$total_rasied_amount_fake = $total_rasied_amount + $target_amount_fake;
									// check amount with data
									if ( $total_rasied_amount_fake >= $target_amount ) {
										$total_rasied_amount_fake = $total_rasied_amount;
									}

									if ( $target_amount > 0 ) {
										$persentange = ( $total_rasied_amount_fake * 100 ) / $target_amount;
									}

									if ( $total_rasied_amount >= $target_amount ) {
										// $goalStatus = 'No';
									}
									if ( $goal_type == 'terget_goal_date' || $goal_type == 'terget_date' ) {
										if ( $time > $target_time ) {
											// $goalStatus = 'No';
										}
									} elseif ( $goal_type == 'campaign_never_end' ) {
										// $goalStatus = 'Yes';
									}
								}

								if ( in_array( $goal_type, array( 'terget_goal_date', 'terget_date' ) ) ) {

									$show_days_left = true;
									$target_date    = isset( $formGoalData->terget->terget_goal->date ) ? $formGoalData->terget->terget_goal->date : '';
									$days_left      = \WfpFundraising\Apps\Settings::get_days_left( $target_date );
								}

								$campaign_status = ( $goalStatus == 'Yes' ) ? 'Publish' : 'Ends';
							}
							?>
							<?php if ( $wfp_fundraising_content__is_carousel === 'yes' ) : ?>
						<div class="single-campaign-blog swiper-slide <?php echo esc_attr( ( isset( $terms_slug ) && ! empty( $terms_slug ) ) ? implode( ' ', $terms_slug ) : '' ); ?>">
							<?php else : ?>
							<div class="single-campaign-blog <?php echo esc_attr( ( isset( $terms_slug ) && ! empty( $terms_slug ) ) ? implode( ' ', $terms_slug ) : '' ); ?>">
								<?php endif; ?>

								<div class="campaign-blog wfp-flip-content-<?php echo esc_attr( $wfp_fundraising_content__flip_enable ); ?>">
									<?php if ( $featured == 'Yes' ) : ?>
										<?php do_action( 'wfp_campaign_list_thumbnil_before' ); ?>

										<div class="wfp-campaign-container">
											<a href="<?php the_permalink(); ?>">
												<?php if ( $feature->has_featured_video( get_the_ID() ) ) { ?>
													<div class="wfp-feature-video">
														<img src="<?php echo esc_url( $feature->get_video_thumbnail( get_the_ID() ) ); ?>" alt="Video Thumbnail">
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
																	$campaign_post_id,
																	'post-thumbnail',
																	array(
																		'class' => 'wfp-feature wfp-full-image',
																		'title' => 'Feature image',
																	)
																)
															);
															?>
																		" alt="Image Thumbnail" />

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

											<?php if ( isset( $wfp_fundraising_content__time_left ) && $wfp_fundraising_content__time_left == 'Yes' && $show_days_left === true ) : ?>
												<div class="number_donation_count_list">
													<span class="wfp-icon wfpf wfpf-time"></span>
													<?php echo esc_html( $days_left ); ?> <?php echo esc_html( apply_filters( 'wfp_single_date_left_title', __( 'days left', 'wp-fundraising' ) ) ); ?>
												</div>
											<?php endif; ?>

											<?php
											if ( $category_info == 'Yes' ) {
												if ( ! empty( $categories ) ) {
													?>
													<div class="wfp-campaign-content--cat">
														<?php

														$separator  = ' - ';
														$outputCate = '';
														foreach ( $categories as $category ) {
															$outputCate .= '<a class="wfp-campaign-content--cat__link" href="' . esc_url( get_category_link( $category->term_id ) ) . '" >' . esc_html( $category->name ) . '</a>' . $separator;
														}
														$outputCate = trim( $outputCate, $separator );
														?>
														<?php echo wp_kses( $outputCate, \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
													</div>
													<?php
												}
											}

											if ( $title_info == 'Yes' ) :
												?>
												<h3 class="wfp-campaign-content--title" ><a class="wfp-campaign-content--title__link" href="<?php echo esc_url( get_permalink() ); ?>">
																																					   <?php
																																						$ext = '';
																																						if ( strlen( get_the_title() ) >= $title_limit ) {
																																							$ext = ' ...';
																																						}
																																						echo wp_kses( substr( get_the_title(), 0, $title_limit ) . $ext, \WfpFundraising\Utilities\Utils::get_kses_array() );
																																						?>
														</a></h3>
												<?php
											endif;
											if ( $excerpt_info == 'Yes' ) :
												?>
												<p class="wfp-campaign-content--short-description">
												<?php
													$ext = '';
												if ( strlen( get_the_excerpt() ) >= $excerpt_limit ) {
													$ext = ' ...';
												}
													echo wp_kses( substr( get_the_excerpt(), 0, $excerpt_limit ) . $ext, \WfpFundraising\Utilities\Utils::get_kses_array() );
												?>
													 </p>
												<?php
											endif;
											if ( $goalStatus == 'Yes' ) :
												?>
												<?php include \WFP_Fundraising::plugin_dir() . 'views/public/donation/include/content/goal-content.php'; ?>
											<?php endif; ?>
										</div>
										<?php if ( $user_info == 'Yes' ) : ?>
											<div class="wfp-campign-user">
												<?php
												$author_id    = get_the_author_meta( 'ID' );
												$profileImage = get_the_author_meta( 'avatar', $author_id );
												if ( strlen( $profileImage ) < 5 ) {
													$profileImage = get_the_author_meta( 'wfp_author_profile_image', $author_id );
												}
												?>
												<div class="profile-image">
													<?php if ( strlen( $profileImage ) > 5 ) { ?>
														<img src="<?php echo esc_url( $profileImage ); ?> " class="avatar wfp-profile-image" alt="<?php the_author_meta( 'display_name', $author_id ); ?>" />
													<?php } else { ?>
														<?php echo get_avatar( $author_id, 35 ); ?>
													<?php } ?>
												</div>

												<div class="profile-info">
													<span class="display-name"><?php esc_html_e( 'Created by', 'wp-fundraising' ); ?> <strong class="display-name__author"><?php the_author_meta( 'display_name', $author_id ); ?></strong></span>
												</div>
											</div>
										<?php endif; ?>

										<?php
										if ( isset( $wfp_fundraising_content__is_button ) && $wfp_fundraising_content__is_button == 'yes' ) :
											$btn1_text = isset( $settings['wfp_fundraising_content__btn1-text'] ) ? $settings['wfp_fundraising_content__btn1-text'] : '';
											$btn1_url  = isset( $settings['wfp_fundraising_content__btn1-url']['url'] ) ? $settings['wfp_fundraising_content__btn1-url']['url'] : '';

											$btn2_text = isset( $settings['wfp_fundraising_content__btn2-text'] ) ? $settings['wfp_fundraising_content__btn2-text'] : '';
											$btn2_url  = isset( $settings['wfp_fundraising_content__btn2-url']['url'] ) ? $settings['wfp_fundraising_content__btn2-url']['url'] : '';

											?>
											<div class="wfp-fundrising-button-list">
												<?php if ( $btn1_text != '' && $btn1_url != '' ) : ?>
													<a href="<?php echo esc_url( $btn1_url ); ?>" class="wfp-fundrising-button wfp-fundrising-first-btn"><?php echo esc_html( $btn1_text ); ?>
														<!-- <span class="wfp-fundrising-icon xs-icon-plus"></span> -->
													</a>
												<?php endif; ?>
												<?php if ( $btn2_text != '' && $btn2_url != '' ) : ?>
													<a href="<?php echo esc_url( $btn2_url ); ?>" class="wfp-fundrising-button wfp-fundrising-second"><?php echo esc_html( $btn2_text ); ?>
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
								<?php if ( 'arrows' == $settings['wfp_fundrising_navigation'] ) : ?>
									<?php $this->render_navigation(); ?>
					<?php elseif ( 'dots' == $settings['wfp_fundrising_navigation'] ) : ?>
						<?php $this->render_pagination(); ?>
					<?php elseif ( 'both' == $settings['wfp_fundrising_navigation'] ) : ?>
						<?php $this->render_navigation(); ?>
						<?php $this->render_pagination(); ?>
					<?php endif; ?>
					<?php endif; ?>
					</div>
					<?php else : ?>
						<p class="xs-alert xs-alert-danger"><?php esc_html_e( 'Sorry, not found any campaign.', 'wp-fundraising' ); ?></p>
					<?php endif; ?>
				</div>
			</div>
		</div>
