<?php

namespace WfpFundraising\Core;

use WfpFundraising\Base\Api;

defined( 'ABSPATH' ) || exit;

class Rest_Route extends Api {

	public function config() {

		$this->prefix = '';
		$this->param  = '';
	}

	// routes need to implement
	// window.xs_donate_url.resturl+'xs-welcome-form/welcome-submit/'+getPath
	// window.xs_donate_url.resturl + 'wfp-stripe-payment/stripe-submit/' + res.entry_id
	// window.xs_donate_url.resturl + 'xs-donate-form/payment-type-modify/' + idData,
	// window.xs_donate_url.resturl + 'xs-donate-form/donate-submit/' + idForm,
	// window.wfp_conf.resturl + 'xs-donate-form/update_status/' + donation_id,
	// window.wfp_conf.resturl + 'xs-donate-form/donate-active/' + idData,
	// $rest_url = get_rest_url(null, 'xs-donate-form/payment-redirect/' . $id_insert . '/?type=' . $xs_payment_method . '&formid=' . $formId);
	// apps/content.php:261 all of it




	public function post_test() {

		$data = $this->request->get_params();
		$idd  = $data['product_id'];

		return array(
			'status'  => 'failed',
			'message' => esc_html__( 'Product id not found.', 'wp-fundraising' ),
		);
	}

	public function get_test() {

		echo 'hello....';
	}
}
