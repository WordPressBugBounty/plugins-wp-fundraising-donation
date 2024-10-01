<?php
if ( apply_filters( 'wfp_single_social_hide', true ) ) :
	$metaSocialKey   = 'wfp_share_media_options';
	$getMetaSocialOp = get_option( $metaSocialKey );
	$getMetaSocial   = isset( $getMetaSocialOp['media'] ) ? $getMetaSocialOp['media'] : array();

	if ( ! empty( $getMetaSocial ) ) {  ?>

		<div class="wfp-social-share">
			<p> <?php echo esc_html( apply_filters( 'wfp_single_social_title', __( 'Social Share:', 'wp-fundraising' ) )); ?></p>
			<?php echo wp_kses( \WfpFundraising\Apps\Settings::generate_social(), \WfpFundraising\Utilities\Utils::get_kses_array() ); ?>
		</div>

		<script>
			function wfp_share(idda){
				if(idda){
					var getLink = idda.setAttribute('href', 'javascript:void(0)');
					var getLink = idda.getAttribute('data-link');
					window.open(getLink, 'wfp_sharer', 'width=626,height=436');
				}
			}
			function wfp_copy_link(idda){
				if(idda){
					var getLink = idda.setAttribute('href', 'javascript:void(0)');
					var getLink = idda.getAttribute('data-link');
					var linkData = prompt("Copy link, then click OK.", getLink);
					if(linkData){
						document.execCommand("copy");
					}
				}
			}
		</script>
		<?php
	}
endif;
