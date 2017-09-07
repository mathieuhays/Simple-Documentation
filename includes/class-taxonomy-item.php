<?php
/**
 * Simple Documentation
 * Taxonomy Item
 */

namespace SimpleDocumentation;

class Taxonomy_Item {
	protected $term;

	protected static $is_bootstrapped = false;

	/**
	 * Set as the default category taxonomy (for posts)
	 * Should be overridden in child class
	 */
	const TAXONOMY = 'category';

	/**
	 * TaxonomyItem constructor.
	 *
	 * @param int|\WP_Term $mixed
	 */
	public function __construct( $mixed ) {
		$this->term = get_term( $mixed );
	}


	public static function bootstrap() {
		if ( static::$is_bootstrapped ) {
			return get_taxonomy( static::TAXONOMY );
		}

		static::register();
	}


	protected static function register() {
		// .
	}

	/**
	 * @param int|\WP_Term $mixed
	 * @return Taxonomy_Item
	 */
	public function from_term( $mixed ) {
		$term = get_term( $mixed );
	}
}
