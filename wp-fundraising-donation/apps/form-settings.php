<?php

namespace WfpFundraising\Apps;

class Form_Settings {

	const MK_FORM_OPTION = 'wfp_form_options_meta_data';

	private static $instance;

	private $post;
	private $post_id;
	private $meta;
	private $options;

	public static function instance( $form_id = 0 ) {

		if ( ! self::$instance ) {
			self::$instance = new static( $form_id );
		}

		return self::$instance;
	}

	public function __construct( $form_id ) {

		$this->post_id = $form_id;

		$this->meta = get_post_meta( $form_id, self::MK_FORM_OPTION, true );
	}

	public function get_form_meta() {

		return $this->meta;
	}

	public function is_pledge_enabled() {

		return ! empty( $this->meta['pledge_setup']['enable'] );
	}

	public function is_amount_limit_enabled() {

		return ! empty( $this->meta['donation']['set_limit']['enable'] );
	}

	public function has_min_limit_amount() {

		return ! empty( $this->meta['donation']['set_limit']['min_amt'] );
	}

	public function has_max_limit_amount() {

		return ! empty( $this->meta['donation']['set_limit']['max_amt'] );
	}

	public function get_min_limit_amount() {

		return empty( $this->meta['donation']['set_limit']['min_amt'] ) ? 0 : floatval( $this->meta['donation']['set_limit']['min_amt'] );
	}

	public function get_max_limit_amount() {

		return empty( $this->meta['donation']['set_limit']['max_amt'] ) ? 0 : floatval( $this->meta['donation']['set_limit']['max_amt'] );
	}
}
