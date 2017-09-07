<?php
/**
 *  Simple Documentation - Import
 */

namespace SimpleDocumentation;

class Import {
	/**
	 *  Bootstrap
	 */
	public function bootstrap() {
		/**
		 *  @TODO
		 *  Check if the WordPress importer does the trick
		 *  Offer option to import from old version of the plugin ? maybe
		 *  Otherwise ask to upgrade then export/import
		 */
	}


	/**
	 * @return Import
	 */
	public static function instance() {
	    static $instance;

	    if ( is_null( $instance ) ) {
	        $instance = new self;
	    }

	    return $instance;
	}
}
