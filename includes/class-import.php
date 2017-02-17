<?php
/**
 *  Simple Documentation - Import
 */

namespace SimpleDocumentation;

class Import {
	/**
	 *  @var Import singleton instance
	 */
	private static $instance;


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
	 *  Get Instance
	 *
	 *  @return Import singleton instance
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
}
