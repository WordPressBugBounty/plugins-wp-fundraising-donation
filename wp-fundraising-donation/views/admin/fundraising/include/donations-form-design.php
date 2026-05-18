<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

$wfp_forms_design_class = 'xs-donate-visible';
if ( $wfp_donation_format == 'crowdfunding' ) :
	$wfp_forms_design_class = '';
endif;

