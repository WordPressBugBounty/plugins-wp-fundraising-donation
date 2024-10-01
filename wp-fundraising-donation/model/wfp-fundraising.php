<?php

namespace WfpFundraising\Model;

class WFP_Fundraising {

	private $table_name;

	private $primary_key;

	private $primary_field = 'donate_id';

	private $connection;

	protected $table = 'wdp_fundraising';

	private $debug = array();

	private $qry;

	private $campaign_id;

	/**
	 * Skipping pain of sanitizing users input!
	 *
	 * @var array
	 */
	private $allowed_gateway = array(
		'online_payment'  => 'online_payment',
		'offline_payment' => 'offline_payment',
		'bank_payment'    => 'bank_payment',
		'stripe_payment'  => 'stripe_payment',
		'2checkout'       => '2checkout',
		'check_payment'   => 'check_payment',
	);

	private $payment_type = array(
		'default'     => 'default',
		'woocommerce' => 'woocommerce',
	);


	public function __construct() {

		global $wpdb;

		$this->connection = $wpdb;

		$this->table_name = $this->connection->prefix . $this->table;
	}


	public function set_pk( $id ) {

		$this->primary_key = $id;

		return $this;
	}


	public function set_campaign( $id ) {

		$this->campaign_id = intval( $id );

		return $this;
	}


	public function count_by_payment_gateway( $gateway = '' ) {

		$gateway = sanitize_text_field( $gateway );

		$qry = $this->connection->prepare( "SELECT COUNT(`donate_amount`) as amount FROM `{$this->table_name}` WHERE form_id = %d AND  `status` = 'Active' AND payment_gateway = %s", $this->campaign_id, $this->allowed_gateway[ $gateway ] );

		$this->qry     = $qry;
		$this->debug[] = $qry;

		return $this;
	}


	public function sum_by_payment_gateway( $gateway = '' ) {

		$gateway = sanitize_text_field( $gateway );

		$qry = $this->connection->prepare( "SELECT SUM(`donate_amount`) as amount FROM `{$this->table_name}` WHERE form_id = %d AND  `status` = 'Active' AND payment_gateway = %s", $this->campaign_id, $this->allowed_gateway[ $gateway ] );

		$this->qry     = $qry;
		$this->debug[] = $qry;

		return $this;
	}


	public function count_by_payment_type( $type ) {

		$type = sanitize_text_field( $type );

		$qry = $this->connection->prepare( 'SELECT COUNT(`donate_amount`) as amount FROM `' . $this->table_name . '` WHERE form_id = %d AND  status = "Active" AND payment_type = %s ', $this->campaign_id, $this->payment_type[ $type ] );

		$this->qry     = $qry;
		$this->debug[] = $qry;

		return $this;
	}


	public function sum_by_payment_type( $type ) {

		$type = sanitize_text_field( $type );

		$qry = $this->connection->prepare( 'SELECT SUM(`donate_amount`) as amount FROM `' . $this->table_name . '` WHERE form_id = %d AND  status = "Active" AND payment_type = %s ', $this->campaign_id, $this->payment_type[ $type ] );

		$this->qry     = $qry;
		$this->debug[] = $qry;

		return $this;
	}


	public function count_successful() {

		$qry = $this->connection->prepare( 'SELECT COUNT(`donate_amount`) as amount FROM `' . $this->table_name . '` WHERE form_id = %d AND  status = "Active" ', $this->campaign_id );

		$this->qry     = $qry;
		$this->debug[] = $qry;

		return $this;
	}

	public function count_pending() {

		$qry = $this->connection->prepare( 'SELECT COUNT(`donate_amount`) as amount FROM `' . $this->table_name . '` WHERE form_id = %d AND  status = "Pending" ', $this->campaign_id );

		$this->qry     = $qry;
		$this->debug[] = $qry;

		return $this;
	}


	public function count_in_review() {

		$qry = $this->connection->prepare( 'SELECT COUNT(`donate_amount`) as amount FROM `' . $this->table_name . '` WHERE form_id = %d AND  status = "Review" ', $this->campaign_id );

		$this->qry     = $qry;
		$this->debug[] = $qry;

		return $this;
	}

	public function sum_successful() {

		$qry = $this->connection->prepare( 'SELECT SUM(`donate_amount`) as amount FROM `' . $this->table_name . '` WHERE form_id = %d AND  status = "Active" ', $this->campaign_id );

		$this->qry     = $qry;
		$this->debug[] = $qry;

		return $this;
	}

	public function sum_pending() {

		$qry = $this->connection->prepare( 'SELECT SUM(`donate_amount`) as amount FROM `' . $this->table_name . '` WHERE form_id = %d AND  status = "Pending" ', $this->campaign_id );

		$this->qry     = $qry;
		$this->debug[] = $qry;

		return $this;
	}

	public function sum_in_review() {

		$qry = $this->connection->prepare( 'SELECT SUM(`donate_amount`) as amount FROM `' . $this->table_name . '` WHERE form_id = %d AND  status = "Review" ', $this->campaign_id );

		$this->qry     = $qry;
		$this->debug[] = $qry;

		return $this;
	}

	private function count_by_status( $status ) {

		$status = sanitize_text_field( $status );

		$status = in_array( $status, array( 'Active', 'Review', 'Pending' ) ) ? $status : 'Active';

		$qry = $this->connection->prepare( 'SELECT COUNT(`form_id`) FROM `' . $this->table_name . '` WHERE form_id = %d AND status = %s ORDER BY date_time DESC', $this->campaign_id, $status );

		$this->qry     = $qry;
		$this->debug[] = $qry;

		return $this;
	}


	private function donation_by_status( $status ) {

		$status = sanitize_text_field( $status );

		$status = in_array( $status, array( 'Active', 'Review', 'Pending' ) ) ? $status : 'Active';

		$qry = $this->connection->prepare( 'SELECT * FROM `' . $this->table_name . '` WHERE form_id = %d AND  status = %s ORDER BY date_time DESC ', $this->campaign_id, $status );

		$this->qry     = $qry;
		$this->debug[] = $qry;

		return $this;
	}


	private function filter_by_status_and_date( $status, $from, $to, $limit = 20 ) {

		$status = sanitize_text_field( $status );
		$from   = preg_replace( '([^0-9-])', '', $from );
		$to     = preg_replace( '([^0-9/])', '', $to );
		$limit  = intval( $limit );

		$status = in_array( $status, array( 'Active', 'Review', 'Pending' ) ) ? $status : 'Active';

		$qry = $this->connection->prepare( 'SELECT * FROM `' . $this->table_name . '` WHERE form_id = %d AND  status = %s AND (date_time BETWEEN %s AND %s ) ORDER BY date_time DESC limit %d', $this->campaign_id, $status, $from, $to, $limit );

		$this->qry     = $qry;
		$this->debug[] = $qry;

		return $this;
	}


	public function get_total_donation_count_by_status( $status ) {

		return $this->count_by_status( $status )->get_var();
	}


	public function get_all_donation_by_status( $status ) {

		return $this->donation_by_status( $status )->get_all();
	}

	public function get_all_donation_by_status_and_date( $status, $from, $to, $limit = 20 ) {

		return $this->filter_by_status_and_date( $status, $from, $to, $limit )->get_all();
	}


	public function get_var() {

		return $this->connection->get_var( $this->qry );
	}


	public function get() {

		return $this->connection->get_row( $this->qry );
	}


	public function get_all() {

		return $this->connection->get_results( $this->qry );
	}


	public function __toSql() {

		return $this->qry;
	}


	public function debug_log() {

		echo '<pre>';
		print_r( $this->debug );
		echo '</pre>';
	}
}


