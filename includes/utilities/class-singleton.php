<?php
/**
 * Singleton class
 */

namespace Simple_Documentation\Utilities;


class Singleton {

	/**
	 * @return static
	 */
	public static function get_instance() {
		static $instance;

		if ( is_null( $instance ) ) {
			$instance = new static;
		}

		return $instance;
	}


	/**
	 * @return static
	 */
	public static function bootstrap() {
		return static::get_instance();
	}
}
