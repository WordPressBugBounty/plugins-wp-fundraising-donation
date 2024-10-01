<?php
namespace WfpFundraising\Apps;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Forbidden' );
}
/**
 * Class Name : Gallery - Featured video added
 * Class Type : Normal class
 *
 * initiate all necessary classes, hooks, configs
 *
 * @since 1.0.0
 * @access Public
 */
class Gallery {

	const post_type = 'wp-fundraising';

	private static $instance;

	public function _init( $load = true ) {
		if ( $load ) {
			if ( is_admin() ) {
				add_action( 'add_meta_boxes', array( $this, 'wfp_portfolio_gallery_custom_meta_box' ) );

				add_action( 'admin_enqueue_scripts', array( $this, 'wfp_portfolio_gallery_load_wp_admin_style' ) );

				add_action( 'save_post', array( $this, 'wfp_featured_gallery_save' ), 1, 2 );
				// print_r( get_attached_file( 932 , false)); die();

				// echo get_post_meta( 932, '_wp_attached_file', true); die();
				// add_action('init', [ $this, 'files_upload']);

				// added tab
				// add_filter('media_upload_tabs', [ $this, 'wfp_my_upload_tab' ]);
				// add_action('media_upload_mettab', [ $this, 'wfp_add_my_new_form']);
				// add_filter('wp_get_attachment_url', [ $this, 'honor_ssl_for_attachments'], 10, 2);
				// add_filter('wp_get_attachment_thumb_url', [ $this, 'honor_ssl_for_attachments'], 10, 2 );

				// add_filter( 'media_view_strings', [ $this, 'cor_media_view_strings'] );

			}
		}
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


	public function wfp_portfolio_gallery_load_wp_admin_style() {

		if ( get_current_screen()->base !== 'post' ) {
			return;
		}

		wp_enqueue_script( 'jquery' );
		wp_enqueue_media();
		// wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script( 'media-upload' );
		wp_enqueue_script( 'thickbox' );
		wp_enqueue_style( 'thickbox' );

		wp_enqueue_script( 'wfp-featured-gallery-js', \WFP_Fundraising::plugin_url() . 'assets/admin/script/gallery/gallery_portfolio_admin.js', array( 'jquery' ), \WFP_Fundraising::version(), true );
		wp_enqueue_style( 'wfp-featured-gallery-css', \WFP_Fundraising::plugin_url() . 'assets/admin/css/gallery/gallery_portfolio_admin.css', false, \WFP_Fundraising::version() );
	}

	public static function wfp_featured_gallery_filed() {
		$prefix             = 'wfp_portfolio_';
		$custom_meta_fields = array(
			/*
			array(
				'label'=> 'Main Image',
				'desc'  => 'This is the main image that is shown in the grid and at the top of the single item page.',
				'id'    => $prefix.'image',
				'type'  => 'media'
			),*/
			array(
				'label' => 'Gallery Images',
				'desc'  => 'This is the gallery images on the single item page.',
				'id'    => $prefix . 'gallery',
				'type'  => 'gallery',
			),
		);
		return $custom_meta_fields;
	}


	public function wfp_portfolio_gallery_custom_meta_box() {
		global $post;
		if ( $post->post_type == self::post_type() ) :
			add_meta_box(
				'wfp_featured_gallery',
				'Featured Gallery',
				array( $this, 'wfp_portfolio_gallery_action' ),
				self::post_type(),
				'side',
				'low'
			);

		endif;
	}

	public function wfp_portfolio_gallery_action() {
		$custom_meta_fields = self::wfp_featured_gallery_filed();
		global $post;
		// get current post type
		$getPostTYpe = $post->post_type;

		// check post type with current post type.
		if ( $getPostTYpe == self::post_type() ) {
			$post_id = $post->ID;
			include \WFP_Fundraising::plugin_dir() . 'views/admin/gallery/add-gallery.php';
		}
	}

	public function wfp_featured_gallery_save( $post_id, $post ) {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}
		// check post id
		if ( ! empty( $post_id ) and is_object( $post ) ) {
			if ( $post->post_type == self::post_type() && ! empty( $_POST['meta-box-order-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['meta-box-order-nonce'] ) ), 'meta-box-order' ) ) {
				$custom_meta_fields = self::wfp_featured_gallery_filed();
				foreach ( $custom_meta_fields as $field ) {
					if ( isset( $_POST[ $field['id'] ] ) ) {
						$new_meta_value = sanitize_text_field( wp_unslash( $_POST[ $field['id'] ] ) );
						$meta_key       = $field['id'];
						$meta_value     = get_post_meta( $post_id, $meta_key, true );

						if ( $new_meta_value && $meta_value == null ) {
								add_post_meta( $post_id, $meta_key, $new_meta_value, true );
						} elseif ( $new_meta_value && $new_meta_value != $meta_value ) {
								update_post_meta( $post_id, $meta_key, $new_meta_value );
						} elseif ( $new_meta_value == null && $meta_value ) {
								delete_post_meta( $post_id, $meta_key, $meta_value );
						}
					}
				}
			}
		}
	}

	public function wfp_portfolio_get_image_id( $image_url ) {
		global $wpdb;
		$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid=%s", $image_url ) );
		return isset( $attachment[0] ) ? $attachment[0] : '';
	}


	 // file upload in attachment
	public function files_upload() {
		$filename = 'https://paloimages.prothom-alo.com/contents/cache/images/640x480x1/uploads/media/2013/09/29/52484400dfe2e-Oporajeyo_Bangla.jpg';

		// The ID of the post this attachment is for.
		$parent_post_id = 1;

		// Check the type of file. We'll use this as the 'post_mime_type'.
		$filetype = wp_check_filetype( basename( $filename ), null );

		// Get the path to the upload directory.
		$wp_upload_dir = wp_upload_dir();

		// Prepare an array of post data for the attachment.
		$attachment = array(
			// 'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
			'guid'           => $filename,
			'post_mime_type' => $filetype['type'],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		);

		// Insert the attachment.
		$attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );

		// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
		require_once ABSPATH . 'wp-admin/includes/image.php';

		// Generate the metadata for the attachment, and update the database record.
		$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
		wp_update_attachment_metadata( $attach_id, $attach_data );

		// set_post_thumbnail( $parent_post_id, $attach_id );
	}



	 // new tab
	public function wfp_my_upload_tab( $tabs ) {
		add_action(
			'admin_enqueue_scripts',
			function() {
				wp_enqueue_script( 'my-media-tab', \WFP_Fundraising::plugin_url() . 'assets/admin/script/gallery/mytab.js', array( 'jquery' ), \WFP_Fundraising::version(), true );
			}
		);

		$tabs['mettab'] = 'Met Tab';
		return $tabs;
	}

	public function wfp_add_my_new_form() {
		wp_iframe( array( $this, 'my_new_form' ) );
	}

	function my_new_form() {
		echo media_upload_header(); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- echo wordpress media uploader header
		echo '<p>Example HTML content goes here.</p>';
	}

	public function honor_ssl_for_attachments( $url, $post_id ) {
		$image = 'https://paloimages.prothom-alo.com/contents/cache/images/320x179x1/uploads/media/2019/09/20/85de63814ed858ddb7c6216160b0806d-5d843cc10ab43.jpg';
		return '';
	}
	public function cor_media_view_strings( $strings ) {
		// print_r($strings);
		unset( $strings['insertFromUrlTitle'] );

		return $strings;
	}

	public static function instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
