<?php

namespace WfpFundraising\Woo;

defined( 'ABSPATH' ) || exit;


use WfpFundraising\Traits\Singleton;

class Woo_Hooks {

	use Singleton;

	public function init() {

		add_action( 'woocommerce_payment_complete', array( $this, 'woc_payment_complete' ) );

		// add_action( 'woocommerce_before_calculate_totals', [$this, 'woc_cart_item_price_update'], 99, 1 );
	}

	public function woc_payment_complete( $order_id ) {

		$order        = wc_get_order( $order_id );
		$billingEmail = $order->billing_email;
		$products     = $order->get_items();

		foreach ( $products as $prod ) {

			$items[ $prod['product_id'] ] = $prod['name'];
		}
	}


	public function woc_cart_item_price_update( $cart_object ) {

		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}

		if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 ) {
			return;
		}

		foreach ( $cart_object->cart_contents as $key => &$item ) {

			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				error_log( 'wfp cart item: ' . wp_json_encode( $item ) ); //phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log -- Debug-only block guarded by WP_DEBUG
			}
			// $cart_object->cart_contents[$key]['data']->set_price( (float)$item['custom_price'] );
			// $cart_object->cart_contents[$key]['data']->save();
		}
	}

}
