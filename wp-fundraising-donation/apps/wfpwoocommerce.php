<?php

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WC_Product_Data_Store_CPT' ) ) {

	session_start();

	class Woc_Conf {

		const MK_DONATION  = '_wfp_donation';
		const MK_PLEDGE_ID = '_wfp_pledge_id';
		const MK_TYPE      = '_wfp_type';

		private $order_status;
		private $payment_type;


		public function getOrderStatus( $status ) {
			return empty( $this->order_status ) ? $this->decide_status( $status ) : $this->order_status;
		}


		public function getPaymentType( $method ) {
			return empty( $this->payment_type ) ? $this->decide_type( $method ) : $this->payment_type;
		}


		private function decide_status( $order_status ) {

			//WC_Abstract_Order::get_status() – Return the order statuses without wc- internal prefix. ref: https://woocommerce.wp-a2z.org/oik_api/wc_abstract_orderget_status/
			switch ( $order_status ) {

				case 'pending':
					$donation_status = 'Pending';
					break;

				case 'processing':
				case 'on-hold':
					$donation_status = 'Review';
					break;

				case 'completed':
					$donation_status = 'Active';
					break;

				case 'refunded':
					$donation_status = 'Refunded';
					break;

				case 'failed':
					$donation_status = 'DeActive';
					break;

				default:
					$donation_status = 'Pending';
			}

			$this->order_status = $donation_status;

			return $this->order_status;
		}


		private function decide_type( $method ) {

			switch ( $method ) {

				case 'cod':
					$type = 'offline_payment';
					break;

				case 'bacs':
					$type = 'bank_payment';
					break;

				case 'cheque':
					$type = 'check_payment';
					break;

				case 'stripe':
					$type = 'stripe_payment';
					break;

				default:
					$type = 'online_payment';
					break;

			}

			$this->payment_type = $type;

			return $this->payment_type;
		}
	}

	/**
	 * Skipping for now, later we will do it with thorough clean way
	 */
	class WC_Product_Donation2 extends WC_Product {

		protected $post_type = 'wp-fundraising';


		public function get_type() {
			return 'wfp_donation';
		}


		public function __construct( $product = 0 ) {
			$this->supports[] = 'ajax_add_to_cart';

			parent::__construct( $product );
		}
	}

	class Wfp_Product_Data_Store_CPT extends WC_Product_Data_Store_CPT {

		const post_type = 'wp-fundraising';


		public function read( &$product ) {

			$product->set_defaults();

			if ( ! $product->get_id() ||
			   ! ( $post_object = get_post( $product->get_id() ) ) ||
			! in_array(
				$post_object->post_type,
				array(
					self::post_type,
					'product',
				)
			)
			) {
				throw new Exception( __( 'Invalid product.', 'wp-fundraising' ) );
			}

			$product->set_props(
				array(
					'name'              => $post_object->post_title,
					'slug'              => $post_object->post_name,
					'date_created'      => 0 < $post_object->post_date_gmt ? wc_string_to_timestamp( $post_object->post_date_gmt ) : null,
					'date_modified'     => 0 < $post_object->post_modified_gmt ? wc_string_to_timestamp( $post_object->post_modified_gmt ) : null,
					'status'            => $post_object->post_status,
					'description'       => $post_object->post_content,
					'short_description' => $post_object->post_excerpt,
					'parent_id'         => $post_object->post_parent,
					'menu_order'        => $post_object->menu_order,
					'reviews_allowed'   => 'open' === $post_object->comment_status,
				)
			);

			$this->read_attributes( $product );
			$this->read_downloads( $product );
			$this->read_visibility( $product );
			$this->read_product_data( $product );
			$this->read_extra_data( $product );
			$product->set_object_read( true );
		}


		/**
		 * Get the product type based on product ID.
		 *
		 * @since 3.0.0
		 *
		 * @param int $product_id
		 *
		 * @return bool|string
		 */
		public function get_product_type( $product_id ) {

			$post_type = get_post_type( $product_id );

			if ( 'product_variation' === $post_type ) {

				return 'variation';
			}

			if ( 'product' === $post_type || 'wp-fundraising' === $post_type ) {

				$terms = get_the_terms( $product_id, 'product_type' );

				return ! empty( $terms ) ? sanitize_title( current( $terms )->name ) : 'simple';
			}

			return false;
		}
	}


	/**
	 * overwrite woocommerce store and make our custom post as a product
	 */
	function wfp_woocommerce_data_stores( $stores ) {
		$stores['product'] = 'Wfp_Product_Data_Store_CPT';

		return $stores;
	}



	function wfp_woocommerce_product_get_price( $price, $product ) {

		$prd_id    = $product->get_id();
		$post_type = get_post_type( $prd_id );

		if ( $post_type == 'wp-fundraising' ) {
			if ( ! empty( $_SESSION['wfp_donation_conf'][ $prd_id ]['_wfp_donation_am'] ) ) {
				$price = floatval( $_SESSION['wfp_donation_conf'][ $prd_id ]['_wfp_donation_am'] );
			}
		}

		return $price;
	}



	function wfp_checkout_create_order_line_item( $item, $cart_item_key, $values, $order ) {

		$prd_id    = $values['data']->get_id();
		$post_type = get_post_type( $prd_id );

		if ( $post_type == 'wp-fundraising' ) {

			$ssn = map_deep( $_SESSION['wfp_donation_conf'][ $prd_id ], 'sanitize_text_field' ) ;

			$item->update_meta_data( '_wfp_donation_id', $ssn['_wfp_donation_id'] );
			$item->update_meta_data( '_wfp_donation_am', $ssn['_wfp_donation_am'] );  // amount the donar want to donate
			$item->update_meta_data( '_wfp_type', $ssn['_wfp_type'] );  // crowdfund or single donation
			$item->update_meta_data( Woc_Conf::MK_DONATION, $prd_id ); // Am I not saving same values here? .... Yes, :P but deleting this line is hurtful

			if ( ! empty( $ssn['_wfp_pledge_uid'] ) ) {

				$item->update_meta_data( '_wfp_pledge_uid', $ssn['_wfp_pledge_uid'] );
				$item->update_meta_data( '_wfp_pledge_amount', $ssn['_wfp_pledge_amount'] );
				$item->update_meta_data( '_wfp_pledge_id', $ssn['_wfp_pledge_id'] );

			}
		}

		return;
	}


	/**
	 * This invokes when clicked on checkout button...........
	 */
	add_action( 'woocommerce_checkout_create_order_line_item', 'wfp_checkout_create_order_line_item', 20, 4 );
	add_filter( 'woocommerce_data_stores', 'wfp_woocommerce_data_stores' );
	add_filter( 'woocommerce_product_get_price', 'wfp_woocommerce_product_get_price', 10, 2 );
	add_action( 'woocommerce_thankyou', 'wfp_woo_callback', 10, 1 );
	add_filter( 'woocommerce_add_to_cart_redirect', 'redirect_checkout_add_cart' );



	/**
	 * After checkout completed page
	 */
	function wfp_woo_callback( $order_id ) {

		$order        = new WC_Order( $order_id );
		$order_status = $order->get_status();
		$donation_ids = array();

		foreach ( $order->get_items() as $item ) {

			if ( $item->meta_exists( Woc_Conf::MK_DONATION ) ) {

				$item_total = $item->get_total();
				$item_id    = $item->get_meta( Woc_Conf::MK_DONATION );

				$tmp['total']  = $item_total;
				$tmp['type']   = $item->get_meta( Woc_Conf::MK_TYPE );
				$tmp['pledge'] = $item->get_meta( Woc_Conf::MK_PLEDGE_ID );
				$tmp['uuid']   = $item->get_meta( '_wfp_pledge_uid' );

				$donation_ids[ $item_id ] = $tmp;
			}
		}

		if ( ! empty( $donation_ids ) ) {

			global $wpdb;

			$order_key  = $order->get_order_key();
			$pay_method = $order->get_payment_method();

			$woc = new Woc_Conf();

			$donation_status = $woc->getOrderStatus( $order_status );
			$payment_type    = $woc->getPaymentType( $pay_method );

			$email = $order->get_billing_email();
			$fnm   = $order->get_billing_first_name();
			$lnm   = $order->get_billing_last_name();

			$country  = $order->get_billing_country();
			$currency = $order->get_currency();

			if ( is_user_logged_in() ) {

				$userId = get_current_user_id();

			} elseif ( email_exists( $email ) ) {

				$userId = email_exists( $email );

			} else {

				$avatar = new \WfpFundraising\Utilities\Avatar();

				/**
				 * Lets check if pro plugin is active or not
				 */
				if ( did_action( 'wpfp/fundraising_pro/plugin_loaded' ) ) {

					$avatar = new \WP_Fundraising_Pro\Utils\Avatar();
				}

				$info['f_name'] = $fnm;
				$info['l_name'] = $lnm;
				$info['email']  = $email;

				$userId = $avatar->create_account( $info );

				$userMata['nickname']           = $fnm;
				$userMata['first_name']         = $fnm;
				$userMata['last_name']          = $lnm;
				$userMata['_wfp_email_address'] = $email;
				$userMata['_wfp_first_name']    = $fnm;
				$userMata['_wfp_last_name']     = $lnm;

				foreach ( $userMata as $k => $v ) {
					update_user_meta( $userId, $k, $v );
				}
			}

			$tbl_name = \WfpFundraising\Apps\Content::wfp_donate_table();

			foreach ( $donation_ids as $donation_id => $conf ) {

				$pledge_id   = empty( $conf['pledge'] ) ? '' : $conf['pledge'];
				$pledge_uuid = empty( $conf['uuid'] ) ? '' : $conf['uuid'];

				$wpIns = array(
					'donate_amount'    => $conf['total'],
					'form_id'          => $donation_id,
					'invoice'          => $order_key,
					'user_id'          => $userId,
					'email'            => $email,
					'fundraising_type' => $conf['type'],
					'pledge_id'        => $pledge_id,
					'payment_type'     => 'woocommerce',
					'payment_gateway'  => $payment_type,
					'date_time'        => gmdate( 'Y-m-d' ),
					'status'           => $donation_status,
				);

				if ( $wpdb->insert( $tbl_name, $wpIns ) ) {

					$id_insert = $wpdb->insert_id;

					$metaKey                          = array();
					$metaKey['_wfp_email_address']    = $email;
					$metaKey['_wfp_first_name']       = $fnm;
					$metaKey['_wfp_last_name']        = $lnm;
					$metaKey['_wfp_form_id']          = $donation_id;
					$metaKey['_wfp_donate_id']        = $id_insert;
					$metaKey['_wfp_pledge_id']        = $pledge_id;
					$metaKey['_wfp_order_key']        = 'wfp_' . $id_insert;
					$metaKey['_wfp_invoice']          = $order_key;
					$metaKey['_wfp_country']          = $country;
					$metaKey['_wfp_currency']         = $currency;
					$metaKey['_wfp_fundraising_type'] = $conf['type'];
					$metaKey['_wfp_payment_type']     = 'woocommerce';
					$metaKey['_wfp_payment_gateway']  = $payment_type;
					$metaKey['_wfp_date_time']        = gmdate( 'Y-m-d H:i:s' );

					$contentObj = new \WfpFundraising\Apps\Content();

					$contentObj->wfp_meta_update( $id_insert, $metaKey );

					/**
					 * Inserting reward info
					 */

					$rwd['donate_id']     = $id_insert;
					$rwd['invoice']       = $order_key;
					$rwd['uuid']          = $pledge_uuid;
					$rwd['user_id']       = $userId;
					$rwd['amount']        = $wpIns['donate_amount'];
					$rwd['pledge_amount'] = $wpIns['donate_amount'];

					$rwd['time'] = time();

					$reward_data = get_option( \WfpFundraising\Apps\Fundraising::WFP_OK_REWARD_DATA );
					$reward_data[ $donation_id ][ strtoupper( $pledge_id ) ][] = $rwd;

					update_option( \WfpFundraising\Apps\Fundraising::WFP_OK_REWARD_DATA, $reward_data );
				}
			}
		}
	}


	function redirect_checkout_add_cart( $cart ) {

		return wc_get_cart_url();
		// return wc_get_checkout_url();
	}


	// after checkout form
	// add_action( 'woocommerce_after_checkout_form', 'wfp_action_woocommerce_after_checkout_form', 10, 1 );
	function wfp_action_woocommerce_after_checkout_form( $wccm_after_checkout ) {
		// echo '<pre>'; print_r($wccm_after_checkout); echo '</pre>';
	}


	// after order completed
	// add_action( 'woocommerce_order_status_completed', 'wfp_call_order_status_completed', 10, 1);
	function wfp_call_order_status_completed( $array ) {
		// echo '<pre>'; print_r($array); echo '</pre>';
		// Write your code here
	}
}

session_write_close();

