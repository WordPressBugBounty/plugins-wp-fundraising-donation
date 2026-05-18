<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

defined( 'ABSPATH' ) || exit;

?>
<div class="wfp_hidden_form_container">
	<form action="" method="post" id="add_cart_<?php echo esc_attr( $wfpPostId ); ?>">
		<input name="add-to-cart" type="hidden" value="<?php echo esc_attr( $wfpPostId ); ?>" />
		<input name="quantity" type="hidden" value="1" min="1"  />
	</form>
</div>
