<?php
/**
 * Simple Documentation
 * Base Model
 */

namespace SimpleDocumentation\Models;

abstract class Base_Model {
	abstract public function get_id();

	/**
	 * Whether the specified object is an instance of the current class
	 *
	 * @param mixed $object
	 *
	 * @return bool
	 */
	public static function is_instance( $object ) {
		return is_a( $object, get_called_class() );
	}

	/**
	 * Whether the two specified objects refers to the same post
	 *
	 * @param mixed $mixed_1
	 * @param mixed $mixed_2
	 *
	 * @return bool
	 */
	public static function equals( $mixed_1, $mixed_2 ) {
		if ( ! self::is_instance( $mixed_1 ) ||
			 ! self::is_instance( $mixed_2 )
		) {
			return false;
		}

		/**
		 * @var static $mixed_1
		 * @var static $mixed_2
		 */
		return $mixed_1->get_id() === $mixed_2->get_id();
	}
}
