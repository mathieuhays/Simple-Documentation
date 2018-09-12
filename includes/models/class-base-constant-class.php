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
}
