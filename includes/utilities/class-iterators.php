<?php
/**
 *  Simple Documentation - Utilities - Iterators
 *  Serves as a global iterator management iterating through iterators.
 *
 *  The logic replicates a little bit what's being done with setup_post_data() and
 *  wp_reset_postdata() in WordPress.
 *
 *  Each time we add an iterator it's like going deeper in the logic tree.
 *  That's why get current always returns the last iterator added
 *  using the reset will bring you back one level up (if you follow my logic)
 */

namespace SimpleDocumentation\Utilities;

class Iterators {
	/**
	 *  @var Iterators singleton instance
	 */
	private static $instance;
	private        $iterators = [];


	/**
	 *  Setup new Iterator - replicates setup_post_data in WP (more or less)
	 *
	 *  @param  Iterator    $iterator
	 *  @return Iterator
	 */
	public function setup( $iterator ) {
		$this->iterators[] = $iterator;

		return $this->get();
	}


	/**
	 *  Get Current Iterator
	 *
	 *  @return Iterator or false if there's none
	 */
	public function get() {
		$length = count( $this->iterators );

		if ( $length === 0 ) {
			return false;
		}

		return $this->iterators[ $length - 1 ];
	}


	/**
	 *  Reset Iterators' handler to the previous iterator
	 *
	 *  @return bool - whether there's still iterators to go through or not
	 */
	public function reset() {
		array_pop( $this->iterators );

		return ! empty( $this->iterators );
	}


	/**
	 *  Get Instance
	 *
	 *  @return Iterators singleton instance
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
}
