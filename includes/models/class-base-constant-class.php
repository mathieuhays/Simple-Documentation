<?php
/**
 * Base Constant Class
 */

namespace Simple_Documentation\Models;


class Base_Constant_Class {

	/**
	 * @return array
	 */
	public static function get_all() {
		try {
			$class = new \ReflectionClass( get_called_class() );
			return $class->getConstants();
		} catch (\Exception $exception) {
			// @TODO Debug log
		}

		return [];
	}


	/**
	 * @param string $value
	 * @param boolean $strict
	 *
	 * @return bool
	 */
	public static function exists( $value, $strict = true ) {
		return in_array(
			$value,
			array_values( static::get_all() ),
			$strict
		);
	}
}
