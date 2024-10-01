<?php
namespace WPF_Payment;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Forbidden' );
}

/**
 * Fundraising autoloader.
 * Handles dynamically loading classes only when needed. This is the main loader file in plugin.
 *
 * @since 1.0.0
 */
class Autoload {

	/**
	 * autoloader loads all the classes needed to run the plugin.
	 *
	 * @since 1.0.0
	 * @access plugic
	 */

	public static function run_loader() {
		spl_autoload_register( array( __CLASS__, '_auto_load' ) );
	}

	/**
	 * Autoload Class.
	 * For a given class, check if it exist and load it.
	 *
	 * @since 1.0.0
	 * @access private
	 * @param string $class Class name.
	 * @return : void
	 */
	private static function _auto_load( $class_name ) {
		if ( 0 !== strpos( $class_name, __NAMESPACE__ ) ) {
			return;
		}

		$file_name = strtolower(
			preg_replace(
				array( '/\b' . __NAMESPACE__ . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ),
				array( '', '$1-$2', '-', DIRECTORY_SEPARATOR ),
				$class_name
			)
		);
		$file      = __DIR__ . DIRECTORY_SEPARATOR . $file_name . '.php';
		if ( file_exists( $file ) ) {
			require_once $file;
		}
	}
}

Autoload::run_loader();
