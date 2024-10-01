<div class="my-campaign wfp-content-padding">
	<h2 class="dashboard-right-section--title"> <?php echo esc_html( apply_filters( 'wfp_dashboard_mycampaign_heading', __( 'My campaigns ', 'wp-fundraising' ) ) ); ?></h2>
	
	<div class="campaign-tab-container">	
		
		<?php


			// Verify nonce
		if ( isset( $_GET['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['nonce'] ) ), 'wp_rest' ) ) {
			$getType = isset( $_GET['type'] ) ? sanitize_text_field( wp_unslash( $_GET['type'] ) ) : 'all';
		} else {
			$getType = 'all';
		}

		?>
		<div class="campaign-tab">
			<ul class="top-menu-dashboard">
				<li class="<?php echo esc_attr( ( $getType == 'all' ) ? 'active' : '' ); ?>" > <a href="?wfp-page=my-campaign&type=all&nonce=<?php echo esc_attr( wp_create_nonce( 'wp_rest' ) ); ?>"> <?php echo esc_html__( 'All', 'wp-fundraising' ); ?> </a></li>
				<li class="<?php echo esc_attr( ( $getType == 'publish' ) ? 'active' : '' ); ?>" > <a href="?wfp-page=my-campaign&type=publish&nonce=<?php echo esc_attr( wp_create_nonce( 'wp_rest' ) ); ?>"> <?php echo esc_html__( 'Publish', 'wp-fundraising' ); ?> </a></li>
				<li class="<?php echo esc_attr( ( $getType == 'review' ) ? 'active' : '' ); ?>" > <a href="?wfp-page=my-campaign&type=review&nonce=<?php echo esc_attr( wp_create_nonce( 'wp_rest' ) ); ?>"> <?php echo esc_html__( 'Review', 'wp-fundraising' ); ?> </a></li>
				<li class="<?php echo esc_attr( ( $getType == 'draft' ) ? 'active' : '' ); ?>" > <a href="?wfp-page=my-campaign&type=draft&nonce=<?php echo esc_attr( wp_create_nonce( 'wp_rest' ) ); ?>"> <?php echo esc_html__( 'Draft', 'wp-fundraising' ); ?> </a></li>
			</ul>
		</div>
		<div class="campaign-body">
			<?php

			$args['author'] = $userId;
			if ( $getType == 'all' ) {
				$args['post_status'] = array( 'draft', 'publish', 'pending' );
			} elseif ( $getType == 'review' ) {
				$args['post_status'] = 'pending';
			} else {
				$args['post_status'] = ( $getType == 'publish' ) ? 'publish' : 'draft';
			}
			$args['post_type'] = self::post_type();

			$args['orderby'] = 'post_date';
			$args['order']   = 'DESC';

			$the_query = new \WP_Query( $args );
			?>
			
			<?php
			if ( $the_query->have_posts() ) :
				global $wpdb;
				?>
				<?php
				while ( $the_query->have_posts() ) :
					$the_query->the_post();
					?>
					
					
					<div class="campaign-blog">
						<div class="campaign-blog-content">
							<h3 class="campaign-blog--title"><a class="campaign-blog--title__link" href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?> </a></h3>
							<!--<p><?php // the_excerpt(); ?> </p> -->
							
							<div class="campaign-blog--raised"> 
							<?php
							esc_html_e( 'Raised Amount : ', 'wp-fundraising' );
							$raised_amount = $wpdb->get_var( $wpdb->prepare( "SELECT SUM(donate_amount) FROM {$wpdb->prefix}wdp_fundraising WHERE form_id = %d AND status = 'Active' AND payment_gateway NOT IN ('test_payment')", get_the_ID() ) );
							?>
							 
								<span class="campaign-blog--raised__price"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $defaultUse_space ) ); ?><strong><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $raised_amount ) ); ?></strong><em class="wfp-currency-symbol"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $defaultUse_space ) ); ?></em> </span>
							</div>
							
							<div class="campaign-blog--date"><?php echo esc_html__( 'Publish Date : ', 'wp-fundraising' ); ?><time class="campaign-blog--date__text" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" itemprop="datePublished"><?php echo esc_html( get_the_date() ); ?></time> </div>
						</div>
						
						<div class="campaign-blog-edit-btn">
							<?php if ( in_array( get_post_status(), array( 'draft', 'publish', 'pending' ) ) ) : ?>
								<a class="xs-btn xs-btn-outline-primary xs-btn-sm campaign-blog--edit" name="filter_my_campaign" href="?wfp-page=campaign&camp=<?php echo esc_attr( get_the_ID() ); ?>&nonce=<?php echo esc_attr( wp_create_nonce( 'wp_nonce' ) ); ?>" > <?php echo esc_html__( 'Edit : ', 'wp-fundraising' ); ?> </a>
							<?php endif; ?>
						</div>
						
					</div>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			<?php else : ?>
				<p><?php esc_html_e( 'Sorry, not found any campaign.', 'wp-fundraising' ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</div>
