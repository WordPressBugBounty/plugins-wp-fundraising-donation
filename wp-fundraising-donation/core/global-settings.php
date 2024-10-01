<?php

namespace WfpFundraising\Core;

use WfpFundraising\Traits\Singleton;

class Global_Settings {

	use Singleton;

	const WFP_MK_FORM_DATA      = 'wfp_form_options_meta_data';
	const WFP_OK_PAYMENT_DATA   = 'wfp_payment_options_data';
	const WFP_OK_GENERAL_DATA   = 'wfp_general_options_data';
	const WFP_OK_REWARD_DATA    = 'wfp_reward_options_data';
	const WFP_OK_REWARD_PARTIAL = '_wfp_pledge_rwd__';
	const OK_GENERAL_DATA       = 'wfp_general_options_data';

	private $general_option;

	public function __construct() {

		$this->general_option = get_option( self::WFP_OK_GENERAL_DATA, array() );
	}
}
