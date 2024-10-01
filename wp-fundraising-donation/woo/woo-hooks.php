<?php

namespace WfpFundraising\Woo;

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

			echo '<pre>';
			print_r( $item );
			die( 'died....!' );
			// $cart_object->cart_contents[$key]['data']->set_price( (float)$item['custom_price'] );
			// $cart_object->cart_contents[$key]['data']->save();
		}
	}

}
