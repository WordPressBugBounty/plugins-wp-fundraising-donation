<?php

namespace WfpFundraising\Apps;

defined( 'ABSPATH' ) || exit;

/**
 * Class Name : Featured - Featured video added
 * Class Type : Normal class
 *
 * initiate all necessary classes, hooks, configs
 *
 * @since 1.0.0
 * @access Public
 */
class Featured {

	const post_type = 'wp-fundraising';

	private static $instance;


	public function _init( $load = true ) {

		if ( $load ) {

			if ( ! is_admin() ) {

				// replace the exisiting thumbnail
				add_filter( 'post_thumbnail_html', array( $this, 'wfp_featured_replace_thumbnail' ), 99, 5 );

				// add scripts
				add_action( 'wp_enqueue_scripts', array( $this, 'wfp_featured_public_scripts' ) );

				// check if a thumbnail has been set
				// add_filter( 'get_post_metadata', array( $this, 'wfp_featured_has_video_check' ), 99, 4 );

			}

			// these hooks only apply to the admin
			if ( is_admin() ) {

				add_action( 'add_meta_boxes', array( $this, 'wfp_meta_box_for_featured' ) );
				// add the link to featured image meta box

				// create a modal
				add_action( 'admin_footer', array( $this, 'wfp_featured_render_modal_container' ) );

				// add scripts
				add_action( 'admin_enqueue_scripts', array( $this, 'wfp_featured_admin_scripts' ) );

				// do ajax to get video information
				add_action( 'wp_ajax_featured_video_get_data', array( $this, 'ajax_render_video_data' ) );

				add_action( 'wp_ajax_featured_video_modal', array( $this, 'wfp_render_modal' ) );

				// save the video url
				add_action( 'save_post', array( $this, 'wfp_featured_save' ), 1, 2 );

			}
		}
	}


	public function wfp_meta_box_for_featured() {
		global $post;
		if ( $post->post_type == self::post_type() ) :
			add_meta_box(
				'wp_fundraising_meta_featured',
				esc_html__( 'Featured Video', 'wp-fundraising' ),
				array( $this, 'wfp_featured_add_button_video' ),
				self::post_type(),
				'side',
				'low'
			);
		endif;
	}


	/**
	 * Custom Post type : static method
	 *
	 * @since 1.0.0
	 * @access public
	 */
	private static function post_type() {
		return self::post_type;
	}


	public function wfp_featured_public_scripts() {

		wp_enqueue_script( 'jquery' );

		wp_enqueue_script( 'wfp-featured-video-js', \WFP_Fundraising::plugin_url() . 'assets/public/script/featured/featured.video.public.js', array( 'jquery' ), \WFP_Fundraising::version(), true );

		wp_enqueue_style( 'wfp-featured-video-css', \WFP_Fundraising::plugin_url() . 'assets/public/css/featured/featured.video.public.css', false,  \WFP_Fundraising::version());
	}

	public function wfp_featured_has_video_check( $value, $object_id, $meta_key, $single ) {

		if ( $meta_key == '_thumbnail_id' ) {

			$only_single = apply_filters( 'wp_featured_video_singular_only', true );

			if ( $only_single ) {

				if ( ! is_singular() ) {
					return $value;
				}
			}

			if ( $this->has_featured_video( $object_id ) ) {
				$value = true;
			}
		}
		return $value;
	}


	public function wfp_featured_replace_thumbnail( $html, $post_id, $post_thumbnail_id, $size, $attr ) {

		$isSingle = empty( $attr['from_sh_code'] ) ? is_single() : true;

		if ( get_post_type( $post_id ) === self::post_type() && $isSingle ) {

			if ( $this->has_featured_video( $post_id ) ) {

				if ( $size == 'post-thumbnail' ) {
					$size = 'large';
				}

				$size_array = $this->get_image_sizes( $size );

				$width  = $size_array['width'];
				$height = $size_array['height'];
				$crop   = $size_array['crop'];

				$height = round( ( $width / 16 ) * 9 );

				$video      = $this->get_video_id( $post_id );
				$video_type = $this->get_video_type( $post_id );

				$attr = array();

				if ( ! isset( $attr['class'] ) ) {
					$attr          = array();
					$attr['class'] = '';
				}
				$attr['class'] .= ' featured-video featured-video-type-' . $video_type . ' featured-video-' . ( $crop ? 'crop' : 'normal' );

				$attr['id'] = 'featured-video-' . $post_id;

				$attr['style'] = 'width:' . $width . 'px;';

				$attrs = '';
				if ( is_array( $attr ) && sizeof( $attr ) > 0 ) :
					foreach ( $attr as $name => $value ) {
						$attrs .= " $name=" . '"' . $value . '"';
					}
				endif;

				$html = '<div' . $attrs . '>';

				if ( $video_type == 'youtube' ) {

					$youtube_query = array(
						'autoplay' => 0,
						'origin'   => get_permalink( $post_id ),
					);

					$youtube_query = http_build_query( $youtube_query );

					$html .= '<iframe class="featured-video-iframe" type="text/html" width="' . $width . '"  height="' . $height . '"';
					$html .= 'src="//www.youtube.com/embed/' . $video . '?' . $youtube_query . '"';
					$html .= 'frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';

				} elseif ( $video_type == 'vimeo' ) {

					$vimeo_query = array(
						'autoplay' => 0,
						'byline'   => 0,
						'portrait' => 0,
						'title'    => 0,
						'badge'    => 0,
					);

					$vimeo_query = http_build_query( $vimeo_query );

					$html .= '<iframe class="featured-video-iframe" width="' . $width . '" height="' . $height . '"';
					$html .= 'src="//player.vimeo.com/video/' . $video . '?' . $vimeo_query . '"';
					$html .= 'frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';

				} elseif ( $video_type == 'dailymotion' ) {

					$dailymotion_query = array(
						'autoplay' => 0,
						'mute'     => 0,
					);

					$dailymotion_query = http_build_query( $dailymotion_query );

					$html .= '<iframe class="featured-video-iframe" width="' . $width . '" height="' . $height . '"';
					$html .= 'src="//www.dailymotion.com/embed/video/' . $video . '?' . $dailymotion_query . '"';
					$html .= 'frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';

				}

				$html .= '</div>';

			}
		}
		return $html;
	}


	public function wfp_featured_video_iframe( $post_id ) {

		if ( $this->has_featured_video( $post_id ) ) {

			$video      = $this->get_video_id( $post_id );
			$video_type = $this->get_video_type( $post_id );

			$width  = '500';
			$height = '300';

			$html = '<div class="wfp-videos-iframe">';

			if ( $video_type == 'youtube' ) {

				$youtube_query = array(
					'autoplay' => 0,
					'origin'   => get_permalink( $post_id ),
				);

				$youtube_query = http_build_query( $youtube_query );

				$html .= '<iframe class="featured-video-iframe" type="text/html"  width="' . $width . '"  height="' . $height . '"';
				$html .= ' src="//www.youtube.com/embed/' . $video . '?' . $youtube_query . '"';
				$html .= ' frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';

			} elseif ( $video_type == 'vimeo' ) {

				$vimeo_query = array(
					'autoplay' => 0,
					'byline'   => 0,
					'portrait' => 0,
					'title'    => 0,
					'badge'    => 0,
				);

				$vimeo_query = http_build_query( $vimeo_query );

				$html .= '<iframe class="featured-video-iframe" width="' . $width . '" height="' . $height . '"';
				$html .= ' src="//player.vimeo.com/video/' . $video . '?' . $vimeo_query . '"';
				$html .= ' frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';

			} elseif ( $video_type == 'dailymotion' ) {

				$dailymotion_query = array(
					'autoplay' => 0,
					'mute'     => 0,
				);

				$dailymotion_query = http_build_query( $dailymotion_query );

				$html .= '<iframe class="featured-video-iframe" width="' . $width . '" height="' . $height . '"';
				$html .= ' src="//www.dailymotion.com/embed/video/' . $video . '?' . $dailymotion_query . '"';
				$html .= ' frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';

			}

			$html .= '</div>';

		}
		return $html;
	}

	private function get_image_sizes( $size = '' ) {

		global $_wp_additional_image_sizes;

		$sizes                        = array();
		$get_intermediate_image_sizes = get_intermediate_image_sizes();

		// Create the full array with sizes and crop info
		foreach ( $get_intermediate_image_sizes as $_size ) {

			if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) ) {

				$sizes[ $_size ]['width']  = get_option( $_size . '_size_w' );
				$sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
				$sizes[ $_size ]['crop']   = (bool) get_option( $_size . '_crop' );

			} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {

				$sizes[ $_size ] = array(
					'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
					'height' => $_wp_additional_image_sizes[ $_size ]['height'],
					'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
				);

			}
		}

		// Get only 1 size if found
		if ( $size ) {

			if ( isset( $sizes[ $size ] ) ) {
				return $sizes[ $size ];
			} else {
				return false;
			}
		}
		return $sizes;
	}


	public function wfp_featured_save( $post_id, $post ) {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}
		// check post id
		if ( ! empty( $post_id ) and is_object( $post ) ) {
			$getPostTYpe = $post->post_type;
			if ( $getPostTYpe == self::post_type() ) {
				if ( isset( $_POST['wfp_featured_video_url'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Already checked by post
					update_post_meta( $post_id, 'wfp_featured_video_url', sanitize_text_field( wp_unslash( $_POST['wfp_featured_video_url'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Already checked by post
				}
			}
		}
	}


	public function wfp_featured_admin_scripts() {

		if ( get_current_screen()->base !== 'post' ) {
			return;
		}

		wp_enqueue_script( 'jquery' );

		wp_enqueue_script( 'wfp-featured-video-js', \WFP_Fundraising::plugin_url() . 'assets/admin/script/featured/featured.video.js', array( 'jquery' ), \WFP_Fundraising::version(), true );
		wp_localize_script(
			'wfp-featured-video-js',
			'Featured_Video',
			array(
				'SetVideo'    => __( 'Set featured video', 'wp-fundraising' ),
				'RemoveVideo' => __( 'Remove featured video', 'wp-fundraising' ),
			)
		);

		wp_localize_script(
			'wfp-featured-video-js',
			'Featured_Video_Nonce',
			array(
				'nonce' => wp_create_nonce( 'wfp_featured_video_nonce' ),
			)
		);

		wp_enqueue_style( 'wfp-featured-video-css', \WFP_Fundraising::plugin_url() . 'assets/admin/css/featured/featured.video.css', false, \WFP_Fundraising::version() );
	}


	public function wfp_featured_add_button_video() {
		global $post;
		// get current post type
		$getPostTYpe = $post->post_type;

		// check post type with current post type.
		if ( $getPostTYpe == self::post_type() ) {
			$post_id = $post->ID;
			include \WFP_Fundraising::plugin_dir() . 'views/admin/featured/add-video.php';
		}
	}


	private function get_video_url( $post_id ) {

		$video = get_post_meta( $post_id, 'wfp_featured_video_url', true );

		return $video;
	}


	private function get_video_id( $post_id ) {

		$video = $this->get_video_url( $post_id );

		if ( empty( $video ) || ! $video ) {
			return false;
		}

		$data = $this->wfp_get_video_data_new( $video );

		return isset( $data['id'] ) ? $data['id'] : '';
	}


	private function get_video_type( $post_id ) {

		$video = $this->get_video_url( $post_id );

		if ( empty( $video ) || ! $video ) {
			return false;
		}

		$data = $this->wfp_get_video_data_new( $video );

		return isset( $data['type'] ) ? $data['type'] : '';
	}


	public function get_video_thumbnail( $post_id ) {

		$video = $this->get_video_url( $post_id );

		if ( empty( $video ) || ! $video ) {
			return false;
		}

		$data = $this->wfp_get_video_data_new( $video );

		return isset( $data['thumbnail'] ) ? $data['thumbnail'] : '';
	}


	public function has_featured_video( $post_id ) {

		$video = get_post_meta( $post_id, 'wfp_featured_video_url', true );

		if ( empty( $video ) || ! $video ) {
			return false;
		}
		return true;
	}


	private function wfp_get_video_data_new( $url ) {
		$return = array();

		$youtube = 'https://www.youtube.com/oembed?url=%s&format=json';
		$vimeo   = '//vimeo.com/api/v2/video/%s.json';

		$dailymotion      = 'https://api.dailymotion.com/video/%s';
		$url_dailythubnil = 'https://www.dailymotion.com/thumbnail/video/%s';

		if ( strpos( $url, 'youtube.com' ) !== false || stripos( $url, 'youtu.be' ) !== false ) {
			$service = 'youtube';
		} elseif ( strpos( $url, 'vimeo.com' ) !== false ) {
			$service = 'vimeo';
		} elseif ( strpos( $url, 'dailymotion.com' ) !== false ) {
			$service = 'dailymotion';
		} else {
			$service = '';
		}
		if ( $service == 'youtube' ) {
			$data_url = sprintf( $youtube, $url );
			if ( stripos( $url, 'youtu.be' ) !== false ) {
				$id = substr( parse_url( $url, PHP_URL_PATH ), 1 );
				$id = strlen( $id ) > 0 ? $id : time();
			} elseif ( strpos( $url, 'youtube.com' ) !== false ) {
				parse_str( parse_url( $url, PHP_URL_QUERY ), $query );
				if ( ! isset( $query['v'] ) ) {
					return false;
				}
				$id = $query['v'];
				$id = strlen( $id ) > 0 ? $id : time();
			}
			$response = wp_remote_get( $data_url );
			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				$data = json_decode( $response['body'] );
			} else {
				return false;
			}
			if ( ! isset( $data->thumbnail_url ) ) {
				return false;
			}
			$return = array(
				'thumbnail' => $data->thumbnail_url,
				'id'        => $id,
				'type'      => 'youtube',
				'title'     => $data->title,
				'html'      => $data->html,
			);
		}

		if ( $service == 'vimeo' ) {
			$id = substr( parse_url( $url, PHP_URL_PATH ), 1 );
			if ( ! is_numeric( $id ) ) {
				return false;
			}
			$data_url = sprintf( $vimeo, $id );
			$response = wp_remote_get( $data_url );
			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				$data = json_decode( $response['body'] );
			} else {
				return false;
			}
			if ( ! isset( $data[0]->thumbnail_large ) ) {
				return false;
			}
			$id     = strlen( $id ) > 0 ? $id : $data[0]->id;
			$return = array(
				'thumbnail' => $data[0]->thumbnail_large,
				'id'        => $id,
				'type'      => 'vimeo',
				'title'     => $data[0]->title,
				'html'      => $data[0]->url,
			);
		}

		if ( $service == 'dailymotion' ) {
			$id       = substr( parse_url( $url, PHP_URL_PATH ), 1 );
			$explode  = explode( '/', $id );
			$id       = end( $explode );
			$id       = strlen( $id ) > 0 ? $id : time();
			$data_url = sprintf( $dailymotion, $id );
			$response = wp_remote_get( $data_url );

			$thubnil = sprintf( $url_dailythubnil, $id );

			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				$data = json_decode( $response['body'] );
			} else {
				return false;
			}

			if ( ! isset( $data->title ) ) {
				return false;
			}

			$id     = strlen( $id ) > 0 ? $id : $data->id;
			$return = array(
				'thumbnail' => $thubnil,
				'id'        => $id,
				'type'      => 'dailymotion',
				'title'     => $data->title,
				'html'      => '',
			);
		}
		return $return;
	}


	public function wfp_featured_render_modal_container() {
		echo '<div id="wfp-featured-video-modal-container"></div>';
	}


	public function wfp_render_modal() {
		if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'wfp_featured_video_nonce' ) ) {
			return;
		}
		$video = isset( $_POST['url'] ) ? sanitize_text_field( wp_unslash( $_POST['url'] ) ) : '';
		include \WFP_Fundraising::plugin_dir() . 'views/admin/featured/add-video-modal.php';
	}


	public function ajax_render_video_data( $ajax = true, $url = null ) {
		if ( $ajax || $ajax == '' ) {
			if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'wfp_featured_video_nonce' ) ) {
				return;
			}
			$url = isset( $_POST['url'] ) ? sanitize_text_field( wp_unslash( $_POST['url'] ) ) : $url;
		}
		$data = $this->wfp_get_video_data_new( $url );
		// echo '<pre>';var_dump($data);echo '</pre>';
		if ( $data ) {
			$title = $data['title'];
			$thumb = $data['thumbnail'];
			include \WFP_Fundraising::plugin_dir() . 'views/admin/featured/preview-video.php';
		} else {
			esc_html_e( 'This is not a valid video URL. Please try another URL.', 'wp-fundraising' );
		}

		if ( $ajax || $ajax == '' ) {
			die();
		}
	}


	public static function instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
