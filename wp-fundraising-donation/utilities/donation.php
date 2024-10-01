<?php

namespace WfpFundraising\Utilities;

class Donation {

	private $row;


	public function get_donation( $campaign_id, $invoice ) {
		global $wpdb;

		$invoice = sanitize_key( $invoice );

		$row = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM `' . $wpdb->prefix . 'wdp_fundraising` WHERE `form_id` = %d AND `invoice` = %s;', intval( $campaign_id ), $invoice ), ARRAY_A );

		$this->row = $row;

		return $row;
	}


	public function get_meta( $donation_id ) {
		global $wpdb;

		$order_id = intval( $donation_id );

		$row = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM `' . $wpdb->prefix . 'wdp_fundraising_meta` WHERE `donate_id` = %d', $order_id ) );

		$this->row = $row;

		return $row;
	}


	public function get_meta_by_key( $donation_id, $key ) {
		global $wpdb;

		$order_id = intval( $donation_id );
		$meta     = sanitize_key( $key );

		$row = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM `' . $wpdb->prefix . 'wdp_fundraising_meta` WHERE `donate_id` = %d AND `meta_key` = %s;', $order_id, $meta ) );

		$this->row = $row;

		return $row;
	}


}
