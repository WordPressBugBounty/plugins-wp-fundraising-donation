<?php

defined( 'ABSPATH' ) || exit;

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

	global $wpdb;
?>
<div class="my-campaign wfp-content-padding">
	<?php require_once __DIR__ . '/report-search/dashboard-report-search.php'; ?>
	<!-- <p class="period"><?php // echo esc_html__('Reporting Period : ', 'wp-fundraising-donation'); ?> <datetime><?php // echo esc_html(date("F j, Y", strtotime($wfpFromDate))); ?></datetime> <em>to</em> <datetime><?php // echo esc_html(date("F j, Y", strtotime($wfpToDate))); ?></datetime></p>
	 -->
	<div class="rewards-content rasied-amount-overview">
		<div class="intro-info rewards-block">
			<?php
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
				$wfpFundAmount = $wpdb->get_var(
					// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
					$wpdb->prepare(
						"SELECT SUM(fund.donate_amount) FROM $wpdb->posts as post 
				INNER JOIN " . $wpdb->prefix . "wdp_fundraising as fund ON post.ID = fund.form_id
				WHERE post.post_author = %d AND post.post_type = %s AND fund.status IN ('Active', 'Review')
				AND (fund.date_time BETWEEN %s AND %s)",
						$wfpUserId,
						self::post_type(),
						$wfpFromDate,
						$wfpToDate
					)
				); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Prepared aggregate read for dashboard fund raised total.
				?>
			<p class="intro-info--title"> <?php echo esc_html__( 'Fund Raised', 'wp-fundraising' ); ?></p>
			<p class="intro-info--price"> <?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $wfp_defaultUse_space ) ); ?><strong><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfpFundAmount ) ); ?></strong><span class="wfp-currency-symbol"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $wfp_defaultUse_space ) ); ?></span></p>
		</div>
		<div class="intro-info rewards-block">
			<?php
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
				$wfpBackendAmount = $wpdb->get_var(
					// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
					$wpdb->prepare(
						"SELECT SUM(fund.donate_amount) FROM $wpdb->posts as post 
				INNER JOIN " . $wpdb->prefix . "wdp_fundraising as fund ON post.ID = fund.form_id
				WHERE post.post_author = %d AND post.post_type = %s AND fund.status IN ('Active')
				AND (fund.date_time BETWEEN %s AND %s)",
						$wfpUserId,
						self::post_type(),
						$wfpFromDate,
						$wfpToDate
					)
				); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Prepared aggregate read for dashboard backed total.
				?>
			<p class="intro-info--title"> <?php echo esc_html__( 'Total Backed', 'wp-fundraising' ); ?></p>
			<p class="intro-info--price"> <?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $wfp_defaultUse_space ) ); ?><strong><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfpBackendAmount ) ); ?></strong><em class="wfp-currency-symbol"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $wfp_defaultUse_space ) ); ?></em></p>
		</div>
		<div class="intro-info rewards-block">
			<?php
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
				$wfpPledgeAmount = $wpdb->get_var(
					$wpdb->prepare(
						"SELECT SUM(fund.donate_amount) FROM $wpdb->posts as post 
				INNER JOIN " . $wpdb->prefix . "wdp_fundraising as fund ON post.ID = fund.form_id
				WHERE post.post_author = %d AND post.post_type = %s AND fund.status IN ('Active') AND fund.pledge_id > 0
				AND (fund.date_time BETWEEN %s AND %s)",
						$wfpUserId,
						self::post_type(),
						$wfpFromDate,
						$wfpToDate
					)
				); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Prepared aggregate read for dashboard pledge total.
				?>
			<p class="intro-info--title"> <?php echo esc_html__( 'Pledge Received', 'wp-fundraising' ); ?></p>
			<p class="intro-info--price"> <?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $wfp_defaultUse_space ) ); ?><strong><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfpPledgeAmount ) ); ?></strong><em class="wfp-currency-symbol"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $wfp_defaultUse_space ) ); ?></em></p>
		</div>
	</div>

	<div class="profile-content wfp-my-campaign-full-width">
		<div class="my-campaign-chart profile-section">
			<div class="my-campaign-chart-inner profile-block">
				<h3> <i class="wfpf wfpf-cog"></i> <?php echo esc_html( apply_filters( 'wfp_dashboard_dashboard_heading', __( 'Income Data', 'wp-fundraising' ) ) ); ?></h3>
				<?php
					$wfpDataChartArray = array();
					$wfpDataChart      = array();

					// chart data
					$wfpChartLabel = array();
				for ( $wfp_m = 1; $wfp_m <= ( $wfp_total_days + 1 ); $wfp_m++ ) {
					$wfpChartLabel[] = str_pad( $wfp_m, 2, '0', STR_PAD_LEFT );
				}

					$wfpDataChartArray['labels'] = $wfpChartLabel;

					$wfpDataLabelsJson = wp_json_encode( array_filter( $wfpDataChartArray['labels'] ) );

					// chart data
					$wfp_data_lavel['rasied_report'] = array(
						'label'           => 'Raised',
						'backgroundColor' => 'rgba(53, 60, 220, 0.35)',
					);
					$wfp_data_lavel['backed_report'] = array(
						'label'           => 'Income',
						'backgroundColor' => 'rgba(120, 43, 40, 0.26)',
					);
					$wfp_data_lavel['pledge_report'] = array(
						'label'           => 'Pledge',
						'backgroundColor' => 'rgba(220, 53, 59, 0.46)',
					);

					foreach ( $wfp_data_lavel as $wfp_k => $v ) :
						$wfp_data_value = array();
						if ( $wfp_k == 'rasied_report' ) {
							$wfp_status = " AND fund.status IN ('Active', 'Review')";
						} elseif ( $wfp_k == 'pledge_report' ) {
							$wfp_status = " AND fund.status IN ('Active') AND fund.pledge_id > 0";
						} else {
							$wfp_status = " AND fund.status IN ('Active')";
						}

						for ( $wfp_m = 1; $wfp_m <= ( $wfp_total_days + 1 ); $wfp_m++ ) {

							$wfp_date_query = $wfp_exp_d[0] . '-' . $wfp_exp_d[1] . '-' . str_pad( $wfp_m, 2, '0', STR_PAD_LEFT );

							$wfp_data_value[] = (int) $wpdb->get_var( $wpdb->prepare( "SELECT SUM(fund.donate_amount) FROM $wpdb->posts as post INNER JOIN " . $wpdb->prefix . "wdp_fundraising as fund ON post.ID = fund.form_id WHERE post.post_author = %d AND post.post_type = %s AND (fund.date_time BETWEEN %s AND %s) $wfp_status", $wfpUserId, self::post_type(), $wfp_date_query, $wfp_date_query ) ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter -- $wfp_status variable does not have any dynamic value.
						}

						$wfpDataChartArray['datasets'][] = array(
							'label'                => $v['label'],
							'data'                 => array_map( 'intval', $wfp_data_value ),
							'backgroundColor'      => $v['backgroundColor'],
							'hoverBackgroundColor' => $v['backgroundColor'],
							'borderColor'          => $v['backgroundColor'],
							'hoverBorderColor'     => $v['backgroundColor'],
							'borderWidth'          => 1,
							'showLine'             => true,
							'fill'                 => 'origin',
						);
					endforeach;

					$wfpDataJson = wp_json_encode( array_filter( $wfpDataChartArray['datasets'] ) );
					?>
				<div class="wfp-chart" >
					<canvas id="wfp-income-chart"></canvas>
				</div>
			</div>
		</div>
	</div>
	

	<div class="profile-content">
		
		<div class="profile-section">
			<!--<div class="profile-block left-profile">
				<h3><i class="fas fa-building"></i><?php // echo esc_html(apply_filters('wfp_dashboard_statistics_headding', __('Statistics Reports ', 'wp-fundraising-donation'))); ?></h3>
				<div class="statistics-item">
					<?php
					// $wfp_persentange = 0;
					// if($wfpFundAmount > 0){
						// $wfp_persentange = ($wfpBackendAmount * 100 ) / $wfpFundAmount;
					// }
					?>
					<div class="xs-progress wfp-campaign-content--progress">
						<div class="xs-progress-bar" role="xs-progressbar" style="width: <?php // echo esc_attr($wfp_persentange); ?>%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="<?php // echo esc_attr($wfpFundAmount); ?>"></div>
					</div>
					<span class="wfp-campaign-content--raised-fund"><?php // echo esc_html__(' Raised Amount :', 'wp-fundraising-donation'); ?> <strong class="wfp-campaign-content--raised-fund__amount"><?php // echo esc_html(WfpFundraising\Apps\Settings::wfp_number_format_currency_icon('left', $wfp_defaultUse_space)); ?><strong><?php // echo esc_html(WfpFundraising\Apps\Settings::wfp_number_format_currency($wfpBackendAmount)); ?></strong><em class="wfp-currency-symbol"><?php // echo esc_html(WfpFundraising\Apps\Settings::wfp_number_format_currency_icon('right', $wfp_defaultUse_space)); ?></em></strong> </span>
				</div>
			</div>-->
			
			<div class="profile-block left-profile">
				<h3><i class="wfpf wfpf-user-add"></i><?php echo esc_html( apply_filters( 'wfp_dashboard_information_headding', __( 'My Information ', 'wp-fundraising' ) ) ); ?></h3>
				<div class="wfp-my-profile-data xs-donate-hidden xs-donate-visible">
					<div class="xs-form-group xs-row intro-info">
						<label for="user_first_name"  class="xs-col-4 xs-col-md-4 xs-col-form-label">
							<?php echo esc_html( apply_filters( 'wfp_dashboard_profile_content_username', __( 'Username ', 'wp-fundraising' ) ) ); ?>
						</label>
						<div class="xs-col-1 xs-col-md-1 wfp-separator">
							<span>:</span>
						</div>
						<div class="xs-col-7 xs-col-md-7 wfp-input-col">
							<span><?php echo esc_html( get_user_meta( $wfpUserId, 'nickname', true ) ); ?></span>
						</div>
						
					</div>
					<div class="xs-form-group xs-row intro-info">
						<label for="user_first_name"  class="xs-col-4 xs-col-sm-4 xs-col-form-label">
							<?php echo esc_html( apply_filters( 'wfp_dashboard_profile_content_telephone', __( 'Phone ', 'wp-fundraising' ) ) ); ?>
						</label>
						<div class="xs-col-1 xs-col-sm-1 wfp-separator">
							<span> : </span>
						</div>
						<div class="xs-col-7 xs-col-md-7 wfp-input-col">
							<span><?php echo esc_html( get_user_meta( $wfpUserId, '_wfp_phone', true ) ); ?></span>
						</div>
						
					</div>
					<div class="xs-form-group xs-row intro-info">
						<label for="user_first_name"  class="xs-col-4 xs-col-sm-4 xs-col-form-label">
							<?php echo esc_html( apply_filters( 'wfp_dashboard_profile_content_first_name', __( 'First Name', 'wp-fundraising' ) ) ); ?>
						</label>
						<div class="xs-col-1 xs-col-sm-1 wfp-separator">
							<span> : </span>
						</div>
						<div class="xs-col-7 xs-col-md-7 wfp-input-col">
							<span><?php echo esc_html( get_user_meta( $wfpUserId, '_wfp_first_name', true ) ); ?></span>
						</div>
						
					</div>
					<div class="xs-form-group xs-row intro-info">
						<label for="user_last_name"  class="xs-col-4 xs-col-sm-4 xs-col-form-label">
							<?php echo esc_html( apply_filters( 'wfp_dashboard_profile_content_last_name', __( 'Last Name', 'wp-fundraising' ) ) ); ?>

						</label>
						<div class="xs-col-1 xs-col-sm-1 wfp-separator">
							<span> : </span>
						</div>
						<div class="xs-col-7 xs-col-md-7 wfp-input-col">
							<span><?php echo esc_html( get_user_meta( $wfpUserId, '_wfp_last_name', true ) ); ?></span>
						</div>
						
					</div>
					<div class="xs-form-group xs-row intro-info">
						<label for="user_last_name"  class="xs-col-4 xs-col-sm-4 xs-col-form-label">
							<?php echo esc_html( apply_filters( 'wfp_dashboard_profile_content_email', __( 'Email', 'wp-fundraising' ) ) ); ?>

						</label>
						<div class="xs-col-1 xs-col-sm-1 wfp-separator">
							<span> : </span>
						</div>
						<div class="xs-col-7 xs-col-md-7 wfp-input-col">
							<span><?php echo esc_html( get_user_meta( $wfpUserId, '_wfp_email_address', true ) ); ?></span>
						</div>
						
					</div>
					<div class="xs-form-group xs-row intro-info">
						<label for="user_last_name"  class="xs-col-4 xs-col-sm-4 xs-col-form-label">
							<?php echo esc_html( apply_filters( 'wfp_dashboard_profile_content_bio', __( 'Bio', 'wp-fundraising' ) ) ); ?>

						</label>
						<div class="xs-col-1 xs-col-sm-1 wfp-separator">
							<span> : </span>
						</div>
						<div class="xs-col-7 xs-col-md-7 wfp-input-col">
							<span><?php echo esc_html( get_user_meta( $wfpUserId, 'description', true ) ); ?></span>
						</div>
						
					</div>
				</div>
			</div>

			<div class="profile-block right-profile wfp-current-balance">
				<h3><i class="wfpf wfpf-wallet"></i><?php echo esc_html( apply_filters( 'wfp_dashboard_balance_headding', __( 'My Balance ', 'wp-fundraising' ) ) ); ?></h3>
				<?php
					// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
					$wfpBalanceAmount = $wpdb->get_var(
						// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
						$wpdb->prepare(
							"SELECT SUM(fund.donate_amount) FROM $wpdb->posts as post 
					INNER JOIN " . $wpdb->prefix . "wdp_fundraising as fund ON post.ID = fund.form_id
					WHERE post.post_author = %d AND post_type = %s AND fund.status IN ('Active')",
							$wfpUserId,
							self::post_type()
						)
					); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Prepared aggregate read for dashboard balance total.
					?>
				<p class="wfp-current-balance--title"> <?php echo esc_html__( 'Current Balance', 'wp-fundraising' ); ?></p>
				<p class="wfp-current-balance--price"> <?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $wfp_defaultUse_space ) ); ?><strong><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfpBalanceAmount ) ); ?></strong><span class="wfp-currency-symbol"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $wfp_defaultUse_space ) ); ?></span></p>
			
			</div>

		</div>

	</div>

	<div class="profile-content wfp-my-campaign-list">
		<div class="profile-section">
			
			<div class="profile-block">
				<h3><i class="wfpf wfpf-sun-umbrella-1"></i><?php echo esc_html( apply_filters( 'wfp_dashboard_my_campaign_headding', __( 'My Campaign List', 'wp-fundraising' ) ) ); ?></h3>
				<div class="campaign-body">
				<?php

				$wfp_args['author']      = $wfpUserId;
				$wfp_args['post_status'] = array( 'publish' );
				$wfp_args['post_type']   = self::post_type();

				$wfp_args['orderby'] = 'post_date';
				$wfp_args['order']   = 'DESC';

				$wfp_the_query = new \WP_Query( $wfp_args );
				?>
				
				<?php if ( $wfp_the_query->have_posts() ) : ?>
				<div class="wfp-report-table-wraper wfp-campaign-list">
					<table class="form-table wfdp-table-design wc_gateways widefat wfp-report-table">
						<thead>
							<tr>
								<th class="name"> <?php echo esc_html__( 'Campaigns', 'wp-fundraising' ); ?></th>
								<th class="name xs-text-center"> <?php echo esc_html__( 'Date', 'wp-fundraising' ); ?></th>
								<th class="name"> <?php echo esc_html__( 'Fund Raised', 'wp-fundraising' ); ?></th>
							</tr>
						</thead>
					<tbody>
					<?php
					while ( $wfp_the_query->have_posts() ) :
						$wfp_the_query->the_post();
						?>
						<tr>
							<td class="icon"> <a class="wfp-campaign-list--link" href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html( wp_trim_words( get_the_title(), 3, '...' ) ); ?></a></td>
							<td class="name">
							<time class="campaign-blog--date__text" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" itemprop="datePublished"><?php echo esc_html( get_the_date() ); ?></time>
							</td>
							<td>
								<?php
									// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
									$wfp_raised_amount = $wpdb->get_var( $wpdb->prepare( "SELECT SUM( 'donate_amount') FROM `{$wpdb->prefix}wdp_fundraising` WHERE form_id = %d AND `status` = 'Active' AND payment_gateway NOT IN ('test_payment')", get_the_ID() ) ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Prepared aggregate read for campaign listing raised total.
								?>
								<?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'left', $wfp_defaultUse_space ) ); ?><strong><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency( $wfp_raised_amount ) ); ?></strong><span class="wfp-currency-symbol"><?php echo esc_html( WfpFundraising\Apps\Settings::wfp_number_format_currency_icon( 'right', $wfp_defaultUse_space ) ); ?></span> 
							</td>
						</tr>
							
						<?php endwhile; ?>
						<?php wp_reset_postdata(); ?>				
						</tbody>
					</table>
				</div>
				<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
	<?php
	// var_dump($wfpDataLabelsJson);
	?>
	jQuery(document).ready(function(){
		var incomeId = document.querySelector('#wfp-income-chart');
		var income = new Chart(incomeId, {
			type: 'line',
			data: {
				labels: <?php echo wp_kses_data( $wfpDataLabelsJson ); ?>,
				datasets: <?php echo wp_kses_data( $wfpDataJson ); ?>
			},
			options: {
				showLines: true,
				scales: {
					yAxes: [{
						stacked: false
					}]
				},
				elements: {
					line: {
						tension: .4
					}
				}
			}
		});
	});
</script>
