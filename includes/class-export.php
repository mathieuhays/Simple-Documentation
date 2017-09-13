<?php
/**
 *  Simple Documentation - Export
 */

namespace SimpleDocumentation;

class Export {
	/**
	 *  Bootstrap
	 */
	public function bootstrap() {
		/**
		 *  @TODO
		 *  Check what's the best option for the export
		 *  Check if the WordPress export functionality enables us to attach options
		 */
	}


	/**
	 * @return Export
	 */
	public static function instance() {
		static $instance;

		if ( is_null( $instance ) ) {
			$instance = new self;
		}

		return $instance;
	}
}
