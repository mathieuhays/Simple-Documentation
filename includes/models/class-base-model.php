<?php
/**
 * Simple Documentation
 * Base Model
 */

namespace Simple_Documentation\Models;


abstract class Base_Model {

	/**
	 * @return int|string
	 */
	abstract public function get_id();


	/**
	 * @param mixed $mixed
	 *
	 * @return bool
	 */
	public static function is_instance( $mixed ) {
		return is_a( $mixed, get_called_class() );
	}


	/**
	 * @param mixed $mixed_1
	 * @param mixed $mixed_2
	 *
	 * @return bool
	 */
	public static function equals( $mixed_1, $mixed_2 ) {
		if ( ! static::is_instance( $mixed_1 ) ||
			 ! static::is_instance( $mixed_2 ) ) {
			return false;
		}

		/**
		 * @var static $mixed_1
		 * @var static $mixed_2
		 */
		return $mixed_1->get_id() === $mixed_2->get_id();
	}
}
